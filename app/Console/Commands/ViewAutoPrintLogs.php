<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ViewAutoPrintLogs extends Command
{
    protected $signature = 'print:logs {--lines=50 : Number of lines to show}';
    protected $description = 'View recent auto print logs (for testing without printer)';

    public function handle()
    {
        $logPath = storage_path('logs/laravel.log');
        
        if (!File::exists($logPath)) {
            $this->error('Log file not found: ' . $logPath);
            return 1;
        }

        $lines = (int) $this->option('lines');
        
        // Read last N lines from log file
        $logContent = File::get($logPath);
        $logLines = explode("\n", $logContent);
        $recentLines = array_slice($logLines, -$lines);
        
        // Filter for auto print logs
        $autoPrintLogs = [];
        $currentLog = null;
        
        foreach ($recentLines as $line) {
            if (strpos($line, 'Auto Print Kitchen') !== false || strpos($line, 'Auto Print Bar') !== false) {
                if ($currentLog) {
                    $autoPrintLogs[] = $currentLog;
                }
                $currentLog = ['header' => $line, 'preview' => ''];
            } elseif ($currentLog && strpos($line, 'preview') !== false) {
                // Extract preview content
                if (preg_match('/"preview":\s*"([^"]+)"/', $line, $matches)) {
                    $currentLog['preview'] = str_replace('\\n', "\n", $matches[1]);
                }
            }
        }
        
        if ($currentLog) {
            $autoPrintLogs[] = $currentLog;
        }
        
        if (empty($autoPrintLogs)) {
            $this->info('No auto print logs found in recent ' . $lines . ' lines.');
            $this->info('Try submitting an order as waiter to generate logs.');
            return 0;
        }
        
        $this->info('=== Recent Auto Print Logs ===');
        $this->newLine();
        
        foreach ($autoPrintLogs as $index => $log) {
            $this->line('--- Log #' . ($index + 1) . ' ---');
            $this->line($log['header']);
            if ($log['preview']) {
                $this->newLine();
                $this->line($log['preview']);
            }
            $this->newLine();
        }
        
        return 0;
    }
}

