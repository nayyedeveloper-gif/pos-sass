<?php

namespace App\Services;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Printer;
use App\Models\Setting;
use Mike42\Escpos\PrintConnectors\NetworkPrintConnector;
use Mike42\Escpos\Printer as EscposPrinter;
use Mike42\Escpos\EscposImage;
use Exception;

class PrinterService
{
    private MyanmarTextImageService $myanmarTextService;
    private ?CloudPrinterService $cloudPrinter = null;

    public function __construct()
    {
        $this->myanmarTextService = new MyanmarTextImageService();
        
        // Initialize cloud printer if using print agent
        if (config('printing.mode') === 'cloud' && config('printing.use_agent', false)) {
            $this->cloudPrinter = app(CloudPrinterService::class);
        }
        // If cloud mode but not using agent, use direct VPN connection
        // Printers should be accessible via VPN (Tailscale/ZeroTier)
    }

    /**
     * Initialize printer with Myanmar Unicode support
     */
    private function initializePrinter($connector)
    {
        $escposPrinter = new EscposPrinter($connector);
        // Nippon 808UE has built-in Myanmar font
        // No codepage change needed - use default
        return $escposPrinter;
    }

    /**
     * Print Myanmar text as image (for printers without Unicode support)
     */
    private function printMyanmarText($printer, string $text, int $fontSize = 24, bool $bold = false, int $justification = EscposPrinter::JUSTIFY_CENTER)
    {
        try {
            // Map justification to alignment string for image generator
            $align = 'center';
            if ($justification === EscposPrinter::JUSTIFY_LEFT) {
                $align = 'left';
            } elseif ($justification === EscposPrinter::JUSTIFY_RIGHT) {
                $align = 'right';
            }

            $imagePath = $this->myanmarTextService->createTextImage($text, $fontSize, 384, $bold, $align);
            
            if (file_exists($imagePath) && filesize($imagePath) > 0) {
                $img = EscposImage::load($imagePath);
                $printer->setJustification($justification);
                $printer->bitImage($img);
                $this->myanmarTextService->deleteImage($imagePath);
            } else {
                throw new Exception("Generated image is empty or missing: $imagePath");
            }
        } catch (Exception $e) {
            // Log error
            logger()->error('Myanmar text image generation failed for text: "' . $text . '" - Error: ' . $e->getMessage());
        }
    }

    private function printMyanmarTextNoNbsp($printer, string $text, int $fontSize = 24, bool $bold = false, int $justification = EscposPrinter::JUSTIFY_CENTER): void
    {
        try {
            $align = 'center';
            if ($justification === EscposPrinter::JUSTIFY_LEFT) {
                $align = 'left';
            } elseif ($justification === EscposPrinter::JUSTIFY_RIGHT) {
                $align = 'right';
            }

            $imagePath = $this->myanmarTextService->createTextImageNoNbsp($text, $fontSize, 384, $bold, $align);

            if (file_exists($imagePath) && filesize($imagePath) > 0) {
                $img = EscposImage::load($imagePath);
                $printer->setJustification($justification);
                $printer->bitImage($img);
                $this->myanmarTextService->deleteImage($imagePath);
            } else {
                throw new Exception("Generated image is empty or missing: $imagePath");
            }
        } catch (Exception $e) {
            logger()->error('Myanmar text image (no NBSP) generation failed for text: "' . $text . '" - Error: ' . $e->getMessage());
        }
    }

    private function printMyanmarTextSmart($printer, string $text, int $fontSize = 24, bool $bold = false, int $justification = EscposPrinter::JUSTIFY_CENTER): void
    {
        // Only decide based on Zawgyi/Unicode when it's Myanmar text.
        if (\App\Services\ZawgyiConverter::isMyanmarUnicode($text) && \App\Services\ZawgyiConverter::isLikelyZawgyi($text)) {
            $this->printMyanmarTextZawgyi($printer, $text, $fontSize, $bold, $justification);
            return;
        }

        $this->printMyanmarText($printer, $text, $fontSize, $bold, $justification);
    }

    private function printMyanmarTextZawgyi($printer, string $text, int $fontSize = 24, bool $bold = false, int $justification = EscposPrinter::JUSTIFY_CENTER)
    {
        try {
            $align = 'center';
            if ($justification === EscposPrinter::JUSTIFY_LEFT) {
                $align = 'left';
            } elseif ($justification === EscposPrinter::JUSTIFY_RIGHT) {
                $align = 'right';
            }

            $imagePath = $this->myanmarTextService->createTextImageZawgyi($text, $fontSize, 384, $bold, $align);

            if (file_exists($imagePath) && filesize($imagePath) > 0) {
                $img = EscposImage::load($imagePath);
                $printer->setJustification($justification);
                $printer->bitImage($img);
                $this->myanmarTextService->deleteImage($imagePath);
            } else {
                throw new Exception("Generated image is empty or missing: $imagePath");
            }
        } catch (Exception $e) {
            logger()->error('Myanmar Zawgyi text image generation failed for text: "' . $text . '" - Error: ' . $e->getMessage());
        }
    }
    /**
     * Print kitchen order items
     */
    public function printKitchenOrder(Order $order, array $items = null)
    {
        // Use cloud printing if enabled
        if ($this->cloudPrinter && $this->cloudPrinter->isEnabled()) {
            return $this->cloudPrinter->printKitchenOrder($order, $items);
        }
        
        // Otherwise use local printing
        return $this->printKitchenOrderLocal($order, $items);
    }

    private function printKitchenOrderLocal(Order $order, array $items = null)
    {
        $this->printCategoryOrderLocal($order, $items, 'kitchen', "အစားအစာများ");
    }
    
    /**
     * Shared local print flow for category printers (kitchen, bar, nan_pyar)
     */
    private function printCategoryOrderLocal(Order $order, ?array $items, string $category, string $headerLabel, int $timeout = 2): void
    {
        // Resolve printer + config keys based on category
        switch ($category) {
            case 'bar':
                $printer = Printer::active()->bar()->first();
                $ip = $printer ? $printer->ip_address : config('printing.printers.bar.ip');
                $port = $printer ? $printer->port : config('printing.printers.bar.port');
                break;
            case 'nan_pyar':
                $printer = Printer::active()->nanPyar()->first();
                $ip = $printer ? $printer->ip_address : config('printing.printers.nan_pyar.ip');
                $port = $printer ? $printer->port : config('printing.printers.nan_pyar.port');
                break;
            default:
                $printer = Printer::active()->kitchen()->first();
                $ip = $printer ? $printer->ip_address : config('printing.printers.kitchen.ip');
                $port = $printer ? $printer->port : config('printing.printers.kitchen.port');
        }

        if (!$ip) {
            logger()->warning(ucfirst($category) . ' printer not configured or not active');
            return;
        }

        // Select items for the target category
        if ($items !== null) {
            $orderItems = collect($items)->filter(function ($item) use ($category) {
                return $item->item->category->printer_type === $category;
            });
        } else {
            $orderItems = $order->items()
                ->whereHas('item.category', function ($query) use ($category) {
                    $query->where('printer_type', $category);
                })
                ->unprinted()
                ->get();
        }

        if ($orderItems->isEmpty()) {
            logger()->info('No ' . $category . ' items to print for order: ' . $order->order_number);
            return;
        }

        logger()->info("Attempting to print {$orderItems->count()} {$category} items for order: " . $order->order_number);

        try {
            $connector = new NetworkPrintConnector($ip, $port, $timeout);
            $escposPrinter = $this->initializePrinter($connector);

            // Header - Larger font for kitchen visibility
            $this->printMyanmarText($escposPrinter, $headerLabel, 40, true, EscposPrinter::JUSTIFY_CENTER);
            $escposPrinter->feed(1);

            // Order info - Professional layout with larger fonts
            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $this->printMyanmarText($escposPrinter, "Order: " . $order->order_number, 32, true, EscposPrinter::JUSTIFY_LEFT);

            if ($order->table) {
                $tableName = $order->table->name_mm ?? $order->table->name;
                $this->printMyanmarText($escposPrinter, "Table: " . $tableName, 36, true, EscposPrinter::JUSTIFY_LEFT);
            } else {
                $this->printMyanmarText($escposPrinter, "Takeaway", 32, true, EscposPrinter::JUSTIFY_LEFT);
            }

            $this->printMyanmarText($escposPrinter, "Time: " . now()->format('g:i A'), 28, false, EscposPrinter::JUSTIFY_LEFT);

            // Waiter name
            if ($order->waiter) {
                $this->printMyanmarText($escposPrinter, "Waiter: " . $order->waiter->name, 28, false, EscposPrinter::JUSTIFY_LEFT);
            }

            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $escposPrinter->feed(1);

            // Items - Larger fonts for kitchen visibility
            foreach ($orderItems as $orderItem) {
                $itemName = $orderItem->item->name_mm ?: $orderItem->item->name;
                $itemText = $orderItem->quantity . "x " . $itemName;
                $this->printMyanmarText($escposPrinter, $itemText, 36, true, EscposPrinter::JUSTIFY_LEFT);

                if ($orderItem->item->name_mm && $orderItem->item->name &&
                    $orderItem->item->name_mm !== $orderItem->item->name) {
                    $this->printMyanmarText($escposPrinter, "   (" . $orderItem->item->name . ")", 26, false, EscposPrinter::JUSTIFY_LEFT);
                }

                if ($orderItem->notes) {
                    $this->printMyanmarText($escposPrinter, "   Note: " . $orderItem->notes, 26, true, EscposPrinter::JUSTIFY_LEFT);
                }

                if ($orderItem->is_foc) {
                    $this->printMyanmarText($escposPrinter, "   ** FOC **", 28, true, EscposPrinter::JUSTIFY_LEFT);
                }

                $escposPrinter->feed(1);
            }

            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $escposPrinter->feed(2);
            $escposPrinter->cut();
            $escposPrinter->close();

            foreach ($orderItems as $orderItem) {
                $orderItem->update([
                    'is_printed' => true,
                    'printed_at' => now(),
                ]);
            }

        } catch (Exception $e) {
            logger()->error(ucfirst($category) . ' printer error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'printer_ip' => $ip,
                'printer_port' => $port,
                'exception' => $e
            ]);
            throw new Exception(ucfirst($category) . ' printer error: ' . $e->getMessage());
        }
    }

    /**
     * Print Bar order items
     */
    public function printBarOrder(Order $order, array $items = null)
    {
        // Use cloud printing if enabled
        if ($this->cloudPrinter && $this->cloudPrinter->isEnabled()) {
            return $this->cloudPrinter->printBarOrder($order, $items);
        }
        
        // Otherwise use local printing
        return $this->printBarOrderLocal($order, $items);
    }

    /**
     * Print Bar order locally
     * @param Order $order Order with loaded table relationship
     * @param array|null $items
     * @return void
     */
    private function printBarOrderLocal(Order $order, array $items = null)
    {
        $printer = Printer::active()->bar()->first();
        $ip = $printer ? $printer->ip_address : config('printing.printers.bar.ip');
        $port = $printer ? $printer->port : config('printing.printers.bar.port');

        if (!$ip) {
            logger()->warning('Bar printer not configured or not active');
            return;
        }

        if ($items !== null) {
            $orderItems = collect($items)->filter(function ($item) {
                return $item->item->category->printer_type === 'bar';
            });
        } else {
            $orderItems = $order->items()
                ->whereHas('item.category', function ($query) {
                    $query->where('printer_type', 'bar');
                })
                ->unprinted()
                ->get();
        }

        if ($orderItems->isEmpty()) {
            logger()->info('No bar items to print for order: ' . $order->order_number);
            return;
        }

        logger()->info('Attempting to print ' . $orderItems->count() . ' bar items for order: ' . $order->order_number);

        try {
            $connector = new NetworkPrintConnector($ip, $port, 2);
            $escposPrinter = $this->initializePrinter($connector);

            // Header
            $this->printMyanmarText($escposPrinter, "သောက်စရာများ", 28, true, EscposPrinter::JUSTIFY_CENTER);
            $escposPrinter->feed(1);

            // Order info - Professional layout
            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $this->printMyanmarText($escposPrinter, "Order: " . $order->order_number, 24, true, EscposPrinter::JUSTIFY_LEFT);

            if ($order->table) {
                $tableName = $order->table->name_mm ?? $order->table->name;
                $this->printMyanmarText($escposPrinter, "Table: " . $tableName, 22, false, EscposPrinter::JUSTIFY_LEFT);
            } else {
                $this->printMyanmarText($escposPrinter, "Takeaway", 22, false, EscposPrinter::JUSTIFY_LEFT);
            }

            $this->printMyanmarText($escposPrinter, "Time: " . now()->format('g:i A'), 20, false, EscposPrinter::JUSTIFY_LEFT);

            // Waiter name
            if ($order->waiter) {
                $this->printMyanmarText($escposPrinter, "Waiter: " . $order->waiter->name, 20, false, EscposPrinter::JUSTIFY_LEFT);
            }

            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $escposPrinter->feed(1);

            // Items
            foreach ($orderItems as $orderItem) {
                $itemName = $orderItem->item->name_mm ?: $orderItem->item->name;
                $itemText = $orderItem->quantity . "x " . $itemName;
                $this->printMyanmarText($escposPrinter, $itemText, 24, true, EscposPrinter::JUSTIFY_LEFT);

                if ($orderItem->item->name_mm && $orderItem->item->name &&
                    $orderItem->item->name_mm !== $orderItem->item->name) {
                    $this->printMyanmarText($escposPrinter, "   (" . $orderItem->item->name . ")", 18, false, EscposPrinter::JUSTIFY_LEFT);
                }

                if ($orderItem->notes) {
                    $this->printMyanmarText($escposPrinter, "   Note: " . $orderItem->notes, 18, false, EscposPrinter::JUSTIFY_LEFT);
                }

                if ($orderItem->is_foc) {
                    $this->printMyanmarText($escposPrinter, "   ** FOC **", 20, true, EscposPrinter::JUSTIFY_LEFT);
                }

                $escposPrinter->feed(1);
            }

            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $escposPrinter->feed(2);
            $escposPrinter->cut();
            $escposPrinter->close();

            foreach ($orderItems as $orderItem) {
                $orderItem->update([
                    'is_printed' => true,
                    'printed_at' => now(),
                ]);
            }

        } catch (Exception $e) {
            logger()->error('Bar printer error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'printer_ip' => $ip,
                'printer_port' => $port,
                'exception' => $e
            ]);
            throw new Exception('Bar printer error: ' . $e->getMessage());
        }
    }

    /**
     * Print customer receipt
     */
    public function printReceipt(Order $order)
    {
        // Use cloud printing if enabled
        if ($this->cloudPrinter && $this->cloudPrinter->isEnabled()) {
            return $this->cloudPrinter->printReceipt($order);
        }
        
        // Otherwise use local printing
        return $this->printReceiptLocal($order);
    }

    /**
     * Print customer receipt locally
     * @param Order $order Order with loaded table and waiter relationships
     * @return void
     */
    private function printReceiptLocal(Order $order)
    {
        $printer = Printer::active()->receipt()->first();
        
        // Fallback to config if no printer in DB
        $printerIp = $printer ? $printer->ip_address : config('printing.printers.receipt.ip');
        $printerPort = $printer ? $printer->port : config('printing.printers.receipt.port');
        
        if (!$printerIp) {
            logger()->warning('Receipt printer not configured or not active');
            return; // Don't throw exception, just log and return
        }

        logger()->info('Attempting to print receipt for order: ' . $order->order_number);

        try {
            // Use 3 second timeout to avoid blocking
            $connector = new NetworkPrintConnector($printerIp, $printerPort, 3);
            $escposPrinter = $this->initializePrinter($connector);

            // Print logo if available
            $escposPrinter->setJustification(EscposPrinter::JUSTIFY_CENTER);
            $logo = Setting::get('app_logo');
            $showLogo = Setting::get('show_logo_on_receipt', false);
            
            if ($logo && $showLogo) {
                try {
                    $logoPath = storage_path('app/public/' . $logo);
                    if (file_exists($logoPath)) {
                        $img = EscposImage::load($logoPath);
                        $escposPrinter->bitImage($img);
                        $escposPrinter->feed();
                    }
                } catch (\Exception $e) {
                    logger()->warning('Failed to print logo: ' . $e->getMessage());
                }
            }

            // Business header - Use image for Myanmar text
            $businessName = Setting::get('business_name', config('app.name'));
            $businessNameMm = Setting::get('business_name_mm', $businessName);
            $businessAddressMm = Setting::get('business_address_mm', Setting::get('business_address'));
            $businessPhone = Setting::get('business_phone');
            
            // Print Myanmar business name as image (use regular font, no bold)
            try {
                // Business Name
                if ($businessNameMm) {
                    $imagePath1 = $this->myanmarTextService->createTextImage($businessNameMm, 42, 384, true);
                    $img1 = EscposImage::load($imagePath1);
                    $escposPrinter->bitImage($img1);
                    $this->myanmarTextService->deleteImage($imagePath1);
                    $escposPrinter->text("\n"); // Add space
                } else {
                    $escposPrinter->setTextSize(2, 2);
                    $escposPrinter->text($businessName . "\n");
                    $escposPrinter->setTextSize(1, 1);
                }

                // Address line: Myanmar text
                if ($businessAddressMm) {
                    $imagePathAddr = $this->myanmarTextService->createTextImage($businessAddressMm, 28, 384, false);
                    $imgAddr = EscposImage::load($imagePathAddr);
                    $escposPrinter->bitImage($imgAddr);
                    $this->myanmarTextService->deleteImage($imagePathAddr);
                }
                
            } catch (Exception $e) {
                logger()->warning('Failed to print Myanmar business info: ' . $e->getMessage());
                // Fallback: print English name only
                $escposPrinter->setTextSize(2, 2);
                $escposPrinter->text($businessName . "\n");
                $escposPrinter->setTextSize(1, 1);
            }
            
            $escposPrinter->setTextSize(1, 1);
            
            if ($businessPhone) {
                $escposPrinter->setJustification(EscposPrinter::JUSTIFY_CENTER);
                $escposPrinter->text($businessPhone . "\n");
            }
            $escposPrinter->text(str_repeat("=", 48) . "\n");

            // Order info - Professional layout
            $escposPrinter->setJustification(EscposPrinter::JUSTIFY_LEFT);
            $escposPrinter->text(__('printer.order_number') . ": " . $order->order_number . "\n");
            $escposPrinter->text(__('printer.date') . ": " . now()->format('d/m/Y g:i A') . "\n");
            
            if ($order->table) {
                $tableNameMm = $order->table->name_mm;
                $tableNameEn = $order->table->name;
                
                if ($tableNameMm) {
                    // Print Myanmar table name as image - larger font
                    $this->printMyanmarText($escposPrinter, __('printer.table') . ": " . $tableNameMm, 30, true, EscposPrinter::JUSTIFY_LEFT);
                } else {
                    $escposPrinter->text(__('printer.table') . ": " . $tableNameEn . "\n");
                }
            }
            
            if ($order->waiter) {
                $escposPrinter->text(__('printer.waiter') . ": " . $order->waiter->name . "\n");
            }
            
            $escposPrinter->text(str_repeat("-", 48) . "\n");

            // Items header - Better alignment
            $escposPrinter->setEmphasis(true);
            $escposPrinter->text(sprintf("%-28s %3s %8s\n", __('printer.item'), __('printer.quantity'), __('printer.amount')));
            $escposPrinter->setEmphasis(false);
            $escposPrinter->text(str_repeat("-", 48) . "\n");

            // Items - Improved formatting
            /** @var OrderItem $orderItem */
            foreach ($order->items as $orderItem) {
                $itemNameMm = $orderItem->item->name_mm; // Use Unicode name
                $itemNameEn = $orderItem->item->name;
                
                $qty = $orderItem->quantity;
                $amount = number_format($orderItem->subtotal, 0);
                
                $escposPrinter->setJustification(EscposPrinter::JUSTIFY_LEFT);
                
                // Print Myanmar name as image if available - larger font for visibility
                if ($itemNameMm) {
                    $this->printMyanmarText($escposPrinter, $itemNameMm, 32, true, EscposPrinter::JUSTIFY_LEFT);
                }
                
                // Print English name with quantity and amount - larger text
                $escposPrinter->setTextSize(1, 1);
                $escposPrinter->setEmphasis(true);
                $escposPrinter->text($itemNameEn . "\n");
                $escposPrinter->setEmphasis(false);
                $escposPrinter->text(sprintf("  %d x %s = ", $qty, number_format($orderItem->price, 0)));
                $escposPrinter->setEmphasis(true);
                $escposPrinter->text(sprintf("%s Ks\n", $amount));
                $escposPrinter->setEmphasis(false);
                
                if ($orderItem->notes) {
                    $escposPrinter->setEmphasis(true);
                    $escposPrinter->text("  * " . $orderItem->notes . "\n");
                    $escposPrinter->setEmphasis(false);
                }
                
                if ($orderItem->is_foc) {
                    $escposPrinter->setEmphasis(true);
                    $escposPrinter->text("  ** FREE **\n");
                    $escposPrinter->setEmphasis(false);
                }
            }

            $escposPrinter->text(str_repeat("-", 48) . "\n");

            // Totals - Professional alignment with bold prices
            $escposPrinter->setJustification(EscposPrinter::JUSTIFY_RIGHT);
            $escposPrinter->text(__('printer.subtotal') . ": ");
            $escposPrinter->setEmphasis(true);
            $escposPrinter->setTextSize(1, 1);
            $escposPrinter->text(sprintf("%8s Ks\n", number_format($order->subtotal, 0)));
            $escposPrinter->setEmphasis(false);
            
            if ($order->tax_amount > 0) {
                $escposPrinter->text(__('printer.tax') . " (" . number_format($order->tax_percentage, 0) . "%%): ");
                $escposPrinter->setEmphasis(true);
                $escposPrinter->setTextSize(1, 1);
                $escposPrinter->text(sprintf("%8s Ks\n", number_format($order->tax_amount, 0)));
                $escposPrinter->setEmphasis(false);
            }
            
            if ($order->discount_amount > 0) {
                $escposPrinter->text(__('printer.discount') . " (" . number_format($order->discount_percentage, 0) . "%%): -");
                $escposPrinter->setEmphasis(true);
                $escposPrinter->setTextSize(1, 1);
                $escposPrinter->text(sprintf("%7s Ks\n", number_format($order->discount_amount, 0)));
                $escposPrinter->setEmphasis(false);
            }
            
            if ($order->service_charge > 0) {
                $escposPrinter->text(__('printer.service_charge') . ": ");
                $escposPrinter->setEmphasis(true);
                $escposPrinter->setTextSize(1, 1);
                $escposPrinter->text(sprintf("%8s Ks\n", number_format($order->service_charge, 0)));
                $escposPrinter->setEmphasis(false);
            }
            
            $escposPrinter->text(str_repeat("=", 48) . "\n");
            $escposPrinter->setEmphasis(true);
            $escposPrinter->setTextSize(2, 1);
            $escposPrinter->text(sprintf(__('printer.total') . ": %8s Ks\n", number_format($order->total, 0)));
            $escposPrinter->setTextSize(1, 1);
            $escposPrinter->setEmphasis(false);
            
            // Add payment information if available
            if ($order->paid_amount && $order->paid_amount > 0) {
                $escposPrinter->text(str_repeat("=", 48) . "\n");
                $escposPrinter->setJustification(EscposPrinter::JUSTIFY_RIGHT);
                $escposPrinter->text(__('printer.paid') . ": ");
                $escposPrinter->setEmphasis(true);
                $escposPrinter->setTextSize(1, 1);
                $escposPrinter->text(sprintf("%8s Ks\n", number_format($order->paid_amount, 0)));
                $escposPrinter->setEmphasis(false);
                
                if ($order->change_amount && $order->change_amount > 0) {
                    $escposPrinter->text(__('printer.change') . ": ");
                    $escposPrinter->setEmphasis(true);
                    $escposPrinter->setTextSize(1, 1);
                    $escposPrinter->text(sprintf("%8s Ks\n", number_format($order->change_amount, 0)));
                    $escposPrinter->setEmphasis(false);
                }
                $escposPrinter->setJustification(EscposPrinter::JUSTIFY_CENTER);
            }
            
            $escposPrinter->text(str_repeat("=", 48) . "\n");

            // Footer - Professional layout
            $escposPrinter->setJustification(EscposPrinter::JUSTIFY_CENTER);
            $escposPrinter->text("\n");
            
            try {
                $footerTextMm = "ကျေးဇူးတင်ပါသည်";
                $imagePathFooter = $this->myanmarTextService->createTextImage($footerTextMm, 36, 384, true);
                $imgFooter = EscposImage::load($imagePathFooter);
                $escposPrinter->bitImage($imgFooter);
                $this->myanmarTextService->deleteImage($imagePathFooter);
            } catch (Exception $e) {
                $escposPrinter->setEmphasis(true);
                $escposPrinter->text("Thank You!\n");
                $escposPrinter->setEmphasis(false);
            }
            
            $escposPrinter->text("\n");

            $escposPrinter->feed(3);
            $escposPrinter->cut();
            $escposPrinter->close();

        } catch (Exception $e) {
            logger()->error('Receipt printer error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'printer_ip' => $printerIp,
                'printer_port' => $printerPort,
            ]);
            throw new Exception('Receipt printer error: ' . $e->getMessage());
        }
    }

    /**
     * Print Nan Pyar order items
     */
    public function printNanPyarOrder(Order $order, array $items = null)
    {
        // Use cloud printing if enabled
        if ($this->cloudPrinter && $this->cloudPrinter->isEnabled()) {
            // Assuming cloud printer supports nan_pyar, otherwise fall back or implement later
            // For now, fall back to local or implement similar logic if cloud supports it.
            // return $this->cloudPrinter->printNanPyarOrder($order, $items);
        }
        
        // Otherwise use local printing
        return $this->printNanPyarOrderLocal($order, $items);
    }

    /**
     * Print Nan Pyar order locally
     * @param Order $order Order with loaded table relationship
     * @param array|null $items
     * @return void
     */
    private function printNanPyarOrderLocal(Order $order, array $items = null)
    {
        $printer = Printer::active()->nanPyar()->first();
        $ip = $printer ? $printer->ip_address : config('printing.printers.nan_pyar.ip');
        $port = $printer ? $printer->port : config('printing.printers.nan_pyar.port');

        if (!$ip) {
            logger()->warning('Nan Pyar printer not configured or not active');
            return;
        }

        if ($items !== null) {
            $orderItems = collect($items)->filter(function ($item) {
                return $item->item->category->printer_type === 'nan_pyar';
            });
        } else {
            $orderItems = $order->items()
                ->whereHas('item.category', function ($query) {
                    $query->where('printer_type', 'nan_pyar');
                })
                ->unprinted()
                ->get();
        }

        if ($orderItems->isEmpty()) {
            logger()->info('No nan pyar items to print for order: ' . $order->order_number);
            return;
        }

        logger()->info('Attempting to print ' . $orderItems->count() . ' nan pyar items for order: ' . $order->order_number);

        try {
            $connector = new NetworkPrintConnector($ip, $port, 2);
            $escposPrinter = $this->initializePrinter($connector);

            // Header
            $this->printMyanmarText($escposPrinter, "နံပြား/ပလာတာ/အီကြာ", 28, true, EscposPrinter::JUSTIFY_CENTER);
            $escposPrinter->feed(1);

            // Order info - Professional layout
            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $this->printMyanmarText($escposPrinter, "Order: " . $order->order_number, 24, true, EscposPrinter::JUSTIFY_LEFT);

            if ($order->table) {
                $tableName = $order->table->name_mm ?? $order->table->name;
                $this->printMyanmarText($escposPrinter, "Table: " . $tableName, 22, false, EscposPrinter::JUSTIFY_LEFT);
            } else {
                $this->printMyanmarText($escposPrinter, "Takeaway", 22, false, EscposPrinter::JUSTIFY_LEFT);
            }

            $this->printMyanmarText($escposPrinter, "Time: " . now()->format('g:i A'), 20, false, EscposPrinter::JUSTIFY_LEFT);

            // Waiter name
            if ($order->waiter) {
                $this->printMyanmarText($escposPrinter, "Waiter: " . $order->waiter->name, 20, false, EscposPrinter::JUSTIFY_LEFT);
            }

            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $escposPrinter->feed(1);

            // Items
            foreach ($orderItems as $orderItem) {
                $itemName = $orderItem->item->name_mm ?: $orderItem->item->name;
                $itemText = $orderItem->quantity . "x " . $itemName;
                $this->printMyanmarText($escposPrinter, $itemText, 24, true, EscposPrinter::JUSTIFY_LEFT);

                if ($orderItem->item->name_mm && $orderItem->item->name &&
                    $orderItem->item->name_mm !== $orderItem->item->name) {
                    $this->printMyanmarText($escposPrinter, "   (" . $orderItem->item->name . ")", 18, false, EscposPrinter::JUSTIFY_LEFT);
                }

                if ($orderItem->notes) {
                    $this->printMyanmarText($escposPrinter, "   Note: " . $orderItem->notes, 18, false, EscposPrinter::JUSTIFY_LEFT);
                }

                if ($orderItem->is_foc) {
                    $this->printMyanmarText($escposPrinter, "   ** FOC **", 20, true, EscposPrinter::JUSTIFY_LEFT);
                }

                $escposPrinter->feed(1);
            }

            $escposPrinter->text(str_repeat("=", 32) . "\n");
            $escposPrinter->feed(2);
            $escposPrinter->cut();
            $escposPrinter->close();

            foreach ($orderItems as $orderItem) {
                $orderItem->update([
                    'is_printed' => true,
                    'printed_at' => now(),
                ]);
            }

        } catch (Exception $e) {
            logger()->error('Nan Pyar printer error: ' . $e->getMessage(), [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'printer_ip' => $ip,
                'printer_port' => $port,
                'exception' => $e
            ]);
            throw new Exception('Nan Pyar printer error: ' . $e->getMessage());
        }
    }

    /**
     * Print order to kitchen and bar based on categories
     */
    public function printOrderToKitchenAndBar(Order $order, array $newItems = null)
    {
        // Print to kitchen
        try {
            $this->printKitchenOrder($order, $newItems);
        } catch (Exception $e) {
            // Log error but don't stop the process
            logger()->error('Kitchen print failed: ' . $e->getMessage());
        }

        // Print to Nan Pyar
        try {
            $this->printNanPyarOrder($order, $newItems);
        } catch (Exception $e) {
            logger()->error('Nan Pyar print failed: ' . $e->getMessage());
        }

        // Print to bar
        try {
            $this->printBarOrder($order, $newItems);
        } catch (Exception $e) {
            // Log error but don't stop the process
            logger()->error('Bar print failed: ' . $e->getMessage());
        }
    }

    /**
     * Preview kitchen order print (without actually printing)
     */
    public function previewKitchenOrder(Order $order, array $items = null)
    {
        if ($items !== null) {
            $orderItems = collect($items)->filter(function ($item) {
                return $item->item && 
                       $item->item->category && 
                       $item->item->category->printer_type === 'kitchen';
            })->values();
        } else {
            // Always use query builder to ensure proper filtering
            // Make sure we're filtering by order_id and printer_type
            $orderItems = \App\Models\OrderItem::where('order_id', $order->id)
                ->with(['item.category'])
                ->whereHas('item.category', function ($query) {
                    $query->where('printer_type', 'kitchen');
                })
                ->get();
            
            // Double-check filtering in case whereHas didn't work
            $orderItems = $orderItems->filter(function ($orderItem) {
                return $orderItem->item && 
                       $orderItem->item->category && 
                       $orderItem->item->category->printer_type === 'kitchen';
            })->values();
        }

        if ($orderItems->isEmpty()) {
            return "No kitchen items in this order.\n";
        }

        $output = "";
        
        // Header - Kitchen label only
        $kitchenText = __('printer.kitchen');
        $output .= str_pad($kitchenText, 48, " ", STR_PAD_BOTH) . "\n";
        $output .= str_repeat("-", 48) . "\n";
        
        // Order info
        $output .= __('printer.order_number') . " / " . __('printer.order') . ": " . $order->order_number . "\n";
        
        if ($order->table) {
            $tableName = $order->table->name_mm ?? $order->table->name;
            $output .= __('printer.table') . " / " . __('printer.table_en') . ": " . $tableName . "\n";
        } else {
            $output .= __('printer.type') . " / " . __('printer.type_en') . ": " . __('printer.takeaway') . "\n";
        }
        
        $output .= __('printer.time') . " / " . __('printer.time_en') . ": " . now()->format('g:i A') . "\n";
        $output .= str_repeat("-", 48) . "\n";
        
        // Items
        foreach ($orderItems as $orderItem) {
            $itemNameMm = $orderItem->item->name_mm;
            $itemNameEn = $orderItem->item->name;
            
            // Print Myanmar name if available
            if ($itemNameMm) {
                $output .= sprintf("x%d%s\n", $orderItem->quantity, $itemNameMm);
                if ($itemNameEn) {
                    $output .= "     " . $itemNameEn . "\n";
                }
            } else {
                $output .= sprintf("x%d%s\n", $orderItem->quantity, $itemNameEn);
            }
            
            if ($orderItem->notes) {
                $output .= "   Notes: " . $orderItem->notes . "\n";
            }
            
            if ($orderItem->is_foc) {
                $output .= "   ** FOC **\n";
            }
            
            $output .= "\n";
        }
        
        $output .= str_repeat("-", 48) . "\n";
        
        return $output;
    }

    /**
     * Preview Bar order print (without actually printing)
     */
    public function previewBarOrder(Order $order, array $items = null)
    {
        if ($items !== null) {
            $orderItems = collect($items)->filter(function ($item) {
                return $item->item && 
                       $item->item->category && 
                       $item->item->category->printer_type === 'bar';
            })->values();
        } else {
            // Always use query builder to ensure proper filtering
            // Make sure we're filtering by order_id and printer_type
            $orderItems = \App\Models\OrderItem::where('order_id', $order->id)
                ->with(['item.category'])
                ->whereHas('item.category', function ($query) {
                    $query->where('printer_type', 'bar');
                })
                ->get();
            
            // Double-check filtering in case whereHas didn't work
            $orderItems = $orderItems->filter(function ($orderItem) {
                return $orderItem->item && 
                       $orderItem->item->category && 
                       $orderItem->item->category->printer_type === 'bar';
            })->values();
        }

        if ($orderItems->isEmpty()) {
            return "No bar items in this order.\n";
        }

        $output = "";
        
        // Header - Bar label only
        $BarText = __('printer.Bar');
        $output .= str_pad($BarText, 48, " ", STR_PAD_BOTH) . "\n";
        $output .= str_repeat("-", 48) . "\n";
        
        // Order info
        $output .= __('printer.order_number') . " / " . __('printer.order') . ": " . $order->order_number . "\n";
        
        if ($order->table) {
            $tableName = $order->table->name_mm ?? $order->table->name;
            $output .= __('printer.table') . " / " . __('printer.table_en') . ": " . $tableName . "\n";
        } else {
            $output .= __('printer.type') . " / " . __('printer.type_en') . ": " . __('printer.takeaway') . "\n";
        }
        
        $output .= __('printer.time') . " / " . __('printer.time_en') . ": " . now()->format('g:i A') . "\n";
        $output .= str_repeat("-", 48) . "\n";
        
        // Items
        foreach ($orderItems as $orderItem) {
            $itemNameMm = $orderItem->item->name_mm;
            $itemNameEn = $orderItem->item->name;
            
            // Print Myanmar name if available
            if ($itemNameMm) {
                $output .= sprintf("x%d  %s\n", $orderItem->quantity, $itemNameMm);
                if ($itemNameEn) {
                    $output .= "     " . $itemNameEn . "\n";
                }
            } else {
                $output .= sprintf("x%d  %s\n", $orderItem->quantity, $itemNameEn);
            }
            
            if ($orderItem->notes) {
                $output .= "   Notes: " . $orderItem->notes . "\n";
            }
            
            if ($orderItem->is_foc) {
                $output .= "   ** FOC **\n";
            }
            
            $output .= "\n";
        }
        
        $output .= str_repeat("-", 48) . "\n";
        
        return $output;
    }
}
