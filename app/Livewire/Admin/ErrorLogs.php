<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\File;

class ErrorLogs extends Component
{
    use WithPagination;

    public $search = '';
    public $levelFilter = '';
    public $perPage = 50;

    protected $queryString = ['search', 'levelFilter'];

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingLevelFilter()
    {
        $this->resetPage();
    }

    public function clearLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            File::put($logFile, '');
            session()->flash('message', 'Log files ကို ရှင်းလင်းပြီးပါပြီ။');
        } else {
            session()->flash('error', 'Log file မတွေ့ပါ။');
        }
    }

    public function downloadLogs()
    {
        $logFile = storage_path('logs/laravel.log');
        
        if (File::exists($logFile)) {
            return response()->download($logFile, 'laravel_' . date('Y-m-d_His') . '.log');
        }
        
        session()->flash('error', 'Log file မတွေ့ပါ။');
    }

    public function render()
    {
        $logFile = storage_path('logs/laravel.log');
        $logs = [];
        
        if (File::exists($logFile)) {
            $content = File::get($logFile);
            
            // Parse log entries
            preg_match_all(
                '/\[(\d{4}-\d{2}-\d{2}[^\]]+)\]\s+(\w+)\.(\w+):\s+(.+?)(?=\[\d{4}-\d{2}-\d{2}|$)/s',
                $content,
                $matches,
                PREG_SET_ORDER
            );
            
            $logs = collect($matches)->map(function ($match) {
                return [
                    'timestamp' => $match[1] ?? '',
                    'environment' => $match[2] ?? '',
                    'level' => $match[3] ?? '',
                    'message' => trim($match[4] ?? ''),
                ];
            })->reverse()->values();

            // Apply filters
            if ($this->search) {
                $logs = $logs->filter(function ($log) {
                    return stripos($log['message'], $this->search) !== false ||
                           stripos($log['timestamp'], $this->search) !== false;
                });
            }

            if ($this->levelFilter) {
                $logs = $logs->filter(function ($log) {
                    return strtolower($log['level']) === strtolower($this->levelFilter);
                });
            }
        }

        // Manual pagination
        $currentPage = $this->getPage();
        $total = $logs->count();
        $logs = $logs->slice(($currentPage - 1) * $this->perPage, $this->perPage)->values();

        return view('livewire.admin.error-logs', [
            'logs' => $logs,
            'total' => $total,
            'currentPage' => $currentPage,
            'lastPage' => ceil($total / $this->perPage),
        ]);
    }
}
