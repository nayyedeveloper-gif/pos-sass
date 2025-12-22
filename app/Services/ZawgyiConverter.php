<?php

namespace App\Services;

/**
 * Simple Unicode to Zawgyi Converter
 * Based on common character mappings
 */
class ZawgyiConverter
{
    private static array $unicodeToZawgyi = [
        // Consonants
        'က' => 'က', 'ခ' => 'ခ', 'ග' => 'ဂ', 'ඝ' => 'ဃ',
        'င' => 'င', 'စ' => 'စ', 'ඡ' => 'ဆ', 'ජ' => 'ဇ',
        'ඣ' => 'ဈ', 'ඤ' => 'ဉ', 'ည' => 'ည', 'ဋ' => 'ဋ',
        'ඨ' => 'ဌ', 'ඩ' => 'ဍ', 'ඪ' => 'ဎ', 'ණ' => 'ဏ',
        'တ' => 'တ', 'ထ' => 'ထ', 'ද' => 'ဒ', 'ධ' => 'ဓ',
        'န' => 'န', 'ප' => 'ပ', 'ඵ' => 'ဖ', 'බ' => 'ဗ',
        'භ' => 'ဘ', 'မ' => 'မ', 'ය' => 'ယ', 'ර' => 'ရ',
        'ල' => 'လ', 'ව' => 'ဝ', 'သ' => 'သ', 'ဟ' => 'ဟ',
        'ဠ' => 'ဠ', 'အ' => 'အ',
        
        // Independent vowels
        'ဣ' => 'ဣ', 'ဤ' => 'ဤ', 'ဥ' => 'ဥ', 'ဦ' => 'ဦ',
        'ဧ' => 'ဧ', 'ဩ' => 'ဩ', 'ဪ' => 'ဪ',
        
        // Dependent vowels - need special handling
        'ာ' => 'ာ', 'ါ' => 'ါ',
        'ိ' => 'ိ', 'ီ' => 'ီ',
        'ု' => 'ု', 'ူ' => 'ူ',
        'ေ' => 'ေ', 'ဲ' => 'ဲ',
        'ံ' => 'ံ', '့' => 'ံ့',
        '္' => '္', '်' => '်',
        
        // Medials
        'ျ' => 'ျ', 'ြ' => 'ြ',
        'ွ' => 'ွ', 'ှ' => 'ှ',
        
        // Signs
        'း' => 'း', 'ဿ' => 'ဿ',
        '၀' => '၀', '၁' => '၁', '၂' => '၂', '၃' => '၃', '၄' => '၄',
        '၅' => '၅', '၆' => '၆', '၇' => '၇', '၈' => '၈', '၉' => '၉',
        '။' => '။', '၊' => '၊',
    ];

    /**
     * Convert Unicode Myanmar text to Zawgyi
     * Comprehensive converter with proper reordering
     */
    public static function convertToZawgyi(string $unicode): string
    {
        $zawgyi = $unicode;
        
        // Step 1: Handle complex patterns first - ကြော် pattern
        // consonant + ြ + ေ + ာ + ် -> ေ + ၾ + consonant + ာ + ္
        $zawgyi = preg_replace('/က\x{103C}\x{1031}\x{102C}\x{103A}/u', 'ေၾကာ္', $zawgyi);
        
        // Step 2: Handle ေ (U+1031) - needs to move before consonant
        $zawgyi = preg_replace('/([\x{1000}-\x{1021}])\x{1031}/u', 'ေ$1', $zawgyi);
        
        // Step 3: Handle ် (U+103A) asat/killer
        $zawgyi = preg_replace('/\x{103A}/u', '္', $zawgyi);
        
        // Step 4: Handle ္ (U+1039) virama
        $zawgyi = preg_replace('/\x{1039}/u', '္', $zawgyi);
        
        // Step 5: Handle ျ (U+103B) medial ya
        // In Zawgyi font, U+103B renders incorrectly, use U+107D (ၽ) or keep as is
        // For now, keep as ျ but the font may not render it correctly
        $zawgyi = preg_replace('/\x{103B}/u', 'ျ', $zawgyi);
        
        // Step 6: Handle ြ (U+103C) medial ra
        // For ကြ specifically, use ၾက
        $zawgyi = preg_replace('/က\x{103C}/u', 'ၾက', $zawgyi);
        // For other consonants + ြ, use ျ + consonant pattern  
        $zawgyi = preg_replace('/([\x{1001}-\x{1021}])\x{103C}/u', 'ျ$1', $zawgyi);
        
        // Step 7: Handle ှ (U+103E) medial ha -> ွ in Zawgyi
        $zawgyi = preg_replace('/\x{103E}/u', 'ွ', $zawgyi);
        
        // Step 8: Handle ွ (U+103D) medial wa - keep as ွ in Zawgyi
        // Don't convert to ြ as that breaks words like ကွဲ
        $zawgyi = preg_replace('/\x{103D}/u', 'ွ', $zawgyi);
        
        // Step 9: Handle vowels
        $zawgyi = preg_replace('/\x{102F}/u', 'ု', $zawgyi);
        $zawgyi = preg_replace('/\x{1030}/u', 'ူ', $zawgyi);
        $zawgyi = preg_replace('/\x{1036}/u', 'ံ', $zawgyi);
        $zawgyi = preg_replace('/\x{1037}/u', '့', $zawgyi);
        $zawgyi = preg_replace('/\x{1038}/u', 'း', $zawgyi);
        $zawgyi = preg_replace('/\x{1032}/u', 'ဲ', $zawgyi);
        
        // Step 10: Handle များ -> မ်ား
        $zawgyi = str_replace('များ', 'မ်ား', $zawgyi);
        
        // Step 11: Common word fixes for Zawgyi font rendering
        // The Zawgyi font has issues with U+103B (ျ) - it renders as ြ
        // So we need to use alternative representations
        $wordFixes = [
            'ပုံမြန္' => 'ပံုမွန္',
            'ပုံမွန္' => 'ပံုမွန္',
            'အီျကာ' => 'အီၾကာ',
            // Fix consonant + ျ patterns - Zawgyi font renders ျ incorrectly
            'ကျ' => 'က်',
            'ချ' => 'ခ်',
            'ဂျ' => 'ဂ်',
            'ငျ' => 'င်',
            'စျ' => 'စ်',
            'ဆျ' => 'ဆ်',
            'ဇျ' => 'ဇ်',
            'ညျ' => 'ည်',
            'တျ' => 'တ်',
            'ထျ' => 'ထ်',
            'ဒျ' => 'ဒ်',
            'နျ' => 'န်',
            'ပျ' => 'ပ်',
            'ဖျ' => 'ဖ်',
            'ဗျ' => 'ဗ်',
            'ဘျ' => 'ဘ်',
            'မျ' => 'မ်',
            'ယျ' => 'ယ်',
            'ရျ' => 'ရ်',
            'လျ' => 'လ်',
            'သျ' => 'သ်',
            'ဟျ' => 'ဟ်',
            'အျ' => 'အ်',
        ];
        
        foreach ($wordFixes as $from => $to) {
            $zawgyi = str_replace($from, $to, $zawgyi);
        }
        
        return $zawgyi;
    }

    /**
     * Heuristic detection for Zawgyi-encoded Myanmar text.
     * Intended for printer rendering decisions only.
     */
    public static function isLikelyZawgyi(string $text): bool
    {
        // Zawgyi-only code points (commonly appear in Zawgyi-encoded strings)
        if (preg_match('/[\x{1060}-\x{1097}]/u', $text) === 1) {
            return true;
        }

        return false;
    }

    /**
     * Check if text contains Myanmar Unicode characters
     */
    public static function isMyanmarUnicode(string $text): bool
    {
        return preg_match('/[\x{1000}-\x{109F}]/u', $text) === 1;
    }
}
