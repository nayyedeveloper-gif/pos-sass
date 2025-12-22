<?php

namespace App\Livewire\Admin;

use App\Models\Printer;
use Livewire\Component;

class PrintersManagement extends Component
{
    public $printers;
    
    public $printerId;
    public $name;
    public $type;
    public $ip_address;
    public $port = 9100;
    public $paper_width = 80;
    public $is_active = true;
    
    public $showModal = false;
    public $editMode = false;
    public $testingPrinter = null;

    protected function rules()
    {
        return [
            'name' => 'required|string|max:255',
            'type' => 'required|in:kitchen,Bar,bar,receipt,nan_pyar',
            'ip_address' => 'required|ip',
            'port' => 'required|integer|min:1|max:65535',
            'paper_width' => 'required|integer|in:58,80',
            'is_active' => 'boolean',
        ];
    }

    public function mount()
    {
        $this->loadPrinters();
    }

    public function loadPrinters()
    {
        $this->printers = Printer::orderBy('type')->get();
    }

    public function edit($id)
    {
        $printer = Printer::findOrFail($id);
        
        $this->printerId = $printer->id;
        $this->name = $printer->name;
        $this->type = $printer->type;
        $this->ip_address = $printer->ip_address;
        $this->port = $printer->port;
        $this->paper_width = $printer->paper_width;
        $this->is_active = $printer->is_active;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'name' => $this->name,
            'type' => $this->type,
            'ip_address' => $this->ip_address,
            'port' => $this->port,
            'paper_width' => $this->paper_width,
            'is_active' => $this->is_active,
        ];

        if ($this->editMode) {
            $printer = Printer::findOrFail($this->printerId);
            $printer->update($data);
            session()->flash('message', 'ပရင်တာကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
        }

        $this->closeModal();
        $this->loadPrinters();
    }

    public function toggleActive($id)
    {
        $printer = Printer::findOrFail($id);
        $printer->update(['is_active' => !$printer->is_active]);
        
        session()->flash('message', 'ပရင်တာ အခြေအနေကို ပြောင်းလဲပြီးပါပြီ။');
        $this->loadPrinters();
    }

    public function testPrinter($id)
    {
        $this->testingPrinter = $id;
        $printer = Printer::findOrFail($id);
        
        try {
            // Test printer connection
            $socket = @fsockopen($printer->ip_address, $printer->port, $errno, $errstr, 5);
            
            if ($socket) {
                // Send test print
                $testContent = "\n";
                $testContent .= "=================================\n";
                $testContent .= "      PRINTER TEST\n";
                $testContent .= "      ပရင်တာ စမ်းသပ်မှု\n";
                $testContent .= "=================================\n";
                $testContent .= "Printer: {$printer->name}\n";
                $testContent .= "Type: {$printer->type}\n";
                $testContent .= "IP: {$printer->ip_address}:{$printer->port}\n";
                $testContent .= "Time: " . now()->format('Y-m-d H:i:s') . "\n";
                $testContent .= "=================================\n";
                $testContent .= "\n\n\n";
                
                fwrite($socket, $testContent);
                fclose($socket);
                
                session()->flash('message', 'ပရင်တာ စမ်းသပ်မှု အောင်မြင်ပါသည်။ ပရင့်ထုတ်ပြီးပါပြီ။');
            } else {
                session()->flash('error', "ပရင်တာနှင့် ချိတ်ဆက်၍မရပါ: {$errstr} ({$errno})");
            }
        } catch (\Exception $e) {
            session()->flash('error', 'ပရင်တာ စမ်းသပ်မှု မအောင်မြင်ပါ: ' . $e->getMessage());
        }
        
        $this->testingPrinter = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'printerId',
            'name',
            'type',
            'ip_address',
            'port',
            'paper_width',
            'is_active',
        ]);
        $this->port = 9100;
        $this->paper_width = 80;
        $this->is_active = true;
        $this->resetValidation();
    }

    public function render()
    {
        return view('livewire.admin.printers-management');
    }
}
