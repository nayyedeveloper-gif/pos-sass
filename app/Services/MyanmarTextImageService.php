<?php

namespace App\Services;

use Exception;

class MyanmarTextImageService
{
    private string $tempDir;
    private array $fontPaths;
    private string $rendererPath;

    public function __construct()
    {
        $this->tempDir = storage_path('app/temp/printer_images');
        $this->rendererPath = base_path('text_renderer');
        
        // Ensure temp directory exists
        if (!file_exists($this->tempDir)) {
            mkdir($this->tempDir, 0755, true);
        }

        // Compile Swift Renderer if missing or if source is newer
        if (file_exists(base_path('text_renderer.swift'))) {
            $shouldCompile = !file_exists($this->rendererPath);
            if (!$shouldCompile) {
                $sourceTime = filemtime(base_path('text_renderer.swift'));
                $binTime = filemtime($this->rendererPath);
                if ($sourceTime > $binTime) {
                    $shouldCompile = true;
                }
            }
            
            if ($shouldCompile) {
                shell_exec("swiftc " . escapeshellarg(base_path('text_renderer.swift')) . " -o " . escapeshellarg($this->rendererPath));
            }
        }
    }

    /**
     * Get the best available Myanmar font name for ImageMagick
     */
    private function getMyanmarFontName(bool $bold = false): string
    {
        // Prefer Myanmar Sangam MN (macOS system font) which has good shaping
        // fallback to Noto Sans Myanmar
        if ($bold) {
            return 'Myanmar-Sangam-MN-Bold'; 
        }
        return 'Myanmar-Sangam-MN';
    }

    /**
     * Get the best available Myanmar font
     */
    private function getMyanmarFont(): ?string
    {
        $basePath = base_path();
        
        // Use Padauk font - it has complete Myanmar glyphs
        // Even though GD cannot do complex script shaping, Padauk renders better
        $padaukPath = $basePath . '/storage/fonts/Padauk-Regular.ttf';
        if (file_exists($padaukPath) && filesize($padaukPath) >= 100000) {
            return $padaukPath;
        }
        
        // Fallback to Zawgyi if Padauk not available
        $zawgyiPath = $basePath . '/storage/fonts/zawgyione.ttf';
        if (file_exists($zawgyiPath) && filesize($zawgyiPath) >= 50000) {
            return $zawgyiPath;
        }
        
        $fontPaths = [
            '/System/Library/Fonts/Supplemental/Myanmar Sangam MN.ttc',
            '/Library/Fonts/Padauk.ttf',
            $basePath . '/storage/fonts/NotoSansMyanmar-Regular.ttf',
            '/Library/Fonts/Myanmar3.ttf',
            '/System/Library/Fonts/Supplemental/Myanmar MN.ttc',
        ];

        foreach ($fontPaths as $path) {
            if (file_exists($path)) {
                // Check for corrupt font files (small or HTML)
                if (filesize($path) < 50000) continue;
                
                // Check header for HTML (simple check)
                $fp = fopen($path, 'r');
                $header = fread($fp, 4);
                fclose($fp);
                if ($header === '<!DO' || $header === '<htm') continue;

                return $path;
            }
        }
        
        return '/System/Library/Fonts/Helvetica.ttc';
    }

    /**
     * Get bold Myanmar font
     */
    private function getBoldFont(): string
    {
        $basePath = base_path();
        
        $boldFonts = [
            // Use Zawgyi for bold as well to ensure consistency on Windows
            $basePath . '/storage/fonts/zawgyione.ttf',
            $basePath . '/storage/fonts/Padauk-Bold.ttf',
            $basePath . '/storage/fonts/NotoSansMyanmar-Bold.ttf',
        ];
        
        foreach ($boldFonts as $path) {
            if (file_exists($path)) {
                 if (filesize($path) < 50000) continue;
                 return $path;
            }
        }
        
        return $this->getMyanmarFont();
    }

    private function getZawgyiFont(): string
    {
        $path = base_path() . '/storage/fonts/zawgyione.ttf';
        if (file_exists($path) && filesize($path) >= 50000) {
            return $path;
        }

        return $this->getMyanmarFont();
    }

    public function createTextImageZawgyi(
        string $text,
        int $fontSize = 24,
        int $width = 384,
        bool $bold = false,
        string $align = 'center'
    ): string {
        $fontFile = $bold ? $this->getBoldFont() : $this->getZawgyiFont();
        return $this->createTextImageGDWithFont($text, $fontSize, $width, $bold, $align, $fontFile, false);
    }

    public function createTextImageNoNbsp(
        string $text,
        int $fontSize = 24,
        int $width = 384,
        bool $bold = false,
        string $align = 'center'
    ): string {
        // Use exact font size
        $effectiveFontSize = $fontSize;

        // Generate unique filename
        $filename = $this->tempDir . '/' . md5($text . $effectiveFontSize . ($bold ? 'bold' : '') . $align . 'swiftNoNbsp' . time()) . '.png';

        // Try shaping-capable renderer first (same as createTextImage)
        if (file_exists($this->rendererPath)) {
            $cmd = sprintf(
                '%s %s %s %d %d %s',
                escapeshellcmd($this->rendererPath),
                escapeshellarg($text),
                escapeshellarg($filename),
                $effectiveFontSize,
                $width,
                escapeshellarg($align)
            );

            shell_exec($cmd);

            if (file_exists($filename) && filesize($filename) > 0) {
                return $filename;
            }
        }

        // Fallback to GD - use NBSP for Myanmar text to prevent square boxes for spaces
        $isMyanmar = \App\Services\ZawgyiConverter::isMyanmarUnicode($text);
        $fontFile = $isMyanmar
            ? ($bold ? $this->getBoldFont() : $this->getMyanmarFont())
            : $this->getEnglishFont();

        // Use NBSP for Myanmar text (required for proper space rendering in GD)
        return $this->createTextImageGDWithFont($text, $fontSize, $width, $bold, $align, $fontFile, $isMyanmar);
    }

    /**
     * Create an image from Myanmar text
     * 
     * @param string $text Myanmar text to convert
     * @param int $fontSize Font size in points
     * @param int $width Image width in pixels (default 384 for 48mm thermal printer)
     * @param bool $bold Make text bold
     * @param string $align Alignment (center, left, right)
     * @return string Path to created image file
     */
    public function createTextImage(
        string $text, 
        int $fontSize = 24, 
        int $width = 384,
        bool $bold = false,
        string $align = 'center'
    ): string {
        // Use exact font size
        $effectiveFontSize = $fontSize;
        
        // Generate unique filename
        $filename = $this->tempDir . '/' . md5($text . $effectiveFontSize . ($bold ? 'bold' : '') . $align . 'swift' . time()) . '.png';
        
        // Use Swift Renderer if available (Best for macOS)
        if (file_exists($this->rendererPath)) {
            $cmd = sprintf(
                '%s "%s" "%s" %d %d "%s"',
                escapeshellcmd($this->rendererPath),
                $text, 
                $filename,
                $effectiveFontSize,
                $width,
                $align
            );
            
            shell_exec($cmd);
            
            if (file_exists($filename) && filesize($filename) > 0) {
                return $filename;
            }
        }
        
        // Fallback to GD if Swift fails (should not happen on macOS with compiled binary)
        // Note: GD fallback doesn't strictly support alignment param in current implementation easily,
        // but it centers by default. Left align is handled by separate method usually.
        // We'll improve GD fallback slightly to respect align if easy, otherwise keep as is.
        if ($align === 'left') {
             return $this->createLeftAlignedImageGD($text, $fontSize, $width, $bold);
        }
        return $this->createTextImageGD($text, $fontSize, $width, $bold);
    }

    /**
     * Get English font
     */
    private function getEnglishFont(): string
    {
        // Windows standard font
        if (file_exists('C:/Windows/Fonts/arial.ttf')) {
            return 'C:/Windows/Fonts/arial.ttf';
        }
        // Fallback
        return $this->getMyanmarFont();
    }

    /**
     * Create an image for left-aligned text
     */
    public function createLeftAlignedImage(
        string $text, 
        int $fontSize = 20, 
        int $width = 384,
        bool $bold = false
    ): string {
        return $this->createTextImage($text, $fontSize, $width, $bold, 'left');
    }
    
    private function createLeftAlignedImageGD(
        string $text, 
        int $fontSize = 20, 
        int $width = 384,
        bool $bold = false
    ): string {
        // Pass 'left' alignment to main GD function
        return $this->createTextImageGD($text, $fontSize, $width, $bold, 'left');
    }

    /**
     * Fallback GD implementation
     */
    private function createTextImageGD(
        string $text, 
        int $fontSize = 24, 
        int $width = 384,
        bool $bold = false,
        string $align = 'center'
    ): string {
        // Check if text contains Myanmar characters
        $isMyanmar = \App\Services\ZawgyiConverter::isMyanmarUnicode($text);
        
        // Choose font based on content
        if ($isMyanmar) {
            $fontFile = $bold ? $this->getBoldFont() : $this->getMyanmarFont();
            
            // Only convert to Zawgyi if using Zawgyi font
            if (stripos($fontFile, 'zawgyi') !== false || stripos($fontFile, 'Zawgyi') !== false) {
                $text = \App\Services\ZawgyiConverter::convertToZawgyi($text);
                // For Zawgyi font, use figure space (U+2007)
                $text = str_replace(' ', "\xE2\x80\x87", $text);
            } else {
                // For Padauk/Unicode fonts, use NBSP
                $text = str_replace(' ', "\xC2\xA0", $text);
            }
        } else {
            // Use English font for purely English/Number text
            $fontFile = $this->getEnglishFont();
        }

        $adjustedFontSize = $fontSize;
        // Adjust height calculation
        $height = (int)($adjustedFontSize * 2.5); // Reduced from 3.0 to minimize vertical gap
        
        $image = imagecreatetruecolor($width, $height);
        // Use white background
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);
        
        $bbox = imagettfbbox($adjustedFontSize, 0, $fontFile, $text);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $textHeight = abs($bbox[7] - $bbox[1]);
        
        // Calculate X based on alignment
        $x = 0;
        if ($align === 'center') {
            $x = (int)(($width - $textWidth) / 2);
        } elseif ($align === 'right') {
            $x = $width - $textWidth - 5; // 5px padding
        } else { // left
            $x = 5; // 5px padding
        }
        
        // Calculate Y (baseline)
        // Approximate centering vertically or baseline
        $y = (int)(($height + $textHeight) / 2) - 2;
        
        imagettftext($image, $adjustedFontSize, 0, $x, $y, $black, $fontFile, $text);
        
        if ($bold) {
            imagettftext($image, $adjustedFontSize, 0, $x + 1, $y, $black, $fontFile, $text);
        }
        
        $filename = $this->tempDir . '/' . md5($text . $fontSize . ($bold ? 'bold' : '') . $align . 'GD' . time()) . '.png';
        imagepng($image, $filename, 0);
        imagedestroy($image);
        
        return $filename;
    }

    private function createTextImageGDWithFont(
        string $text,
        int $fontSize,
        int $width,
        bool $bold,
        string $align,
        string $fontFile,
        bool $useNbsp = true
    ): string {
        // Convert Unicode to Zawgyi if using Zawgyi font (same as createTextImageGD)
        if (stripos($fontFile, 'Zawgyi') !== false || stripos($fontFile, 'zawgyi') !== false) {
            if (\App\Services\ZawgyiConverter::isMyanmarUnicode($text)) {
                $text = \App\Services\ZawgyiConverter::convertToZawgyi($text);
            }
            // For Zawgyi font, use figure space (U+2007) which renders as blank
            $text = str_replace(' ', "\xE2\x80\x87", $text);
        } elseif ($useNbsp) {
            $text = str_replace(' ', "\xC2\xA0", $text);
        }

        $adjustedFontSize = $fontSize;
        $height = (int)($adjustedFontSize * 2.5);

        $image = imagecreatetruecolor($width, $height);
        $white = imagecolorallocate($image, 255, 255, 255);
        $black = imagecolorallocate($image, 0, 0, 0);
        imagefill($image, 0, 0, $white);

        $bbox = imagettfbbox($adjustedFontSize, 0, $fontFile, $text);
        $textWidth = abs($bbox[4] - $bbox[0]);
        $textHeight = abs($bbox[7] - $bbox[1]);

        $x = 0;
        if ($align === 'center') {
            $x = (int)(($width - $textWidth) / 2);
        } elseif ($align === 'right') {
            $x = $width - $textWidth - 5;
        } else {
            $x = 5;
        }

        $y = (int)(($height + $textHeight) / 2) - 2;

        imagettftext($image, $adjustedFontSize, 0, $x, $y, $black, $fontFile, $text);
        if ($bold) {
            imagettftext($image, $adjustedFontSize, 0, $x + 1, $y, $black, $fontFile, $text);
        }

        $filename = $this->tempDir . '/' . md5($text . $fontSize . ($bold ? 'bold' : '') . $align . 'GDZ' . time()) . '.png';
        imagepng($image, $filename, 0);
        imagedestroy($image);

        return $filename;
    }

    /**
     * Clean up old temporary image files
     */
    public function cleanup(int $olderThanMinutes = 60): void
    {
        $files = glob($this->tempDir . '/*.png');
        $now = time();
        
        foreach ($files as $file) {
            if (is_file($file)) {
                if ($now - filemtime($file) >= $olderThanMinutes * 60) {
                    @unlink($file);
                }
            }
        }
    }

    /**
     * Delete a specific image file
     */
    public function deleteImage(string $imagePath): void
    {
        if (file_exists($imagePath)) {
            @unlink($imagePath);
        }
    }

    /**
     * Check if Myanmar font is available
     */
    public function hasMyanmarFont(): bool
    {
        foreach ($this->fontPaths as $path) {
            if (file_exists($path)) {
                return true;
            }
        }
        return false;
    }
}
