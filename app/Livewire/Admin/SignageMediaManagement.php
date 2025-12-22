<?php

namespace App\Livewire\Admin;

use App\Models\SignageMedia;
use Livewire\Component;
use Livewire\WithFileUploads;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Storage;

class SignageMediaManagement extends Component
{
    use WithFileUploads, WithPagination;

    public $title;
    public $title_mm;
    public $type = 'image';
    public $file;
    public $duration = 10;
    public $description;
    public $is_active = true;
    
    public $editingId = null;
    public $showModal = false;

    protected $rules = [
        'title' => 'required|string|max:255',
        'title_mm' => 'nullable|string|max:255',
        'type' => 'required|in:video,image',
        'file' => 'nullable|file|mimes:jpg,jpeg,png,gif,mp4,webm,mov|max:51200', // 50MB
        'duration' => 'required|integer|min:1|max:300',
        'description' => 'nullable|string',
    ];

    public function openModal()
    {
        $this->resetForm();
        $this->showModal = true;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset(['title', 'title_mm', 'type', 'file', 'duration', 'description', 'editingId', 'is_active']);
        $this->duration = 10;
        $this->is_active = true;
        $this->type = 'image';
    }

    public function save()
    {
        $this->validate();

        $data = [
            'title' => $this->title,
            'title_mm' => $this->title_mm,
            'type' => $this->type,
            'duration' => $this->duration,
            'description' => $this->description,
            'is_active' => $this->is_active,
        ];

        if ($this->file) {
            $path = $this->file->store('signage-media', 'public');
            $data['file_path'] = $path;
        }

        if ($this->editingId) {
            $media = SignageMedia::findOrFail($this->editingId);
            
            // Delete old file if new file uploaded
            if ($this->file && $media->file_path) {
                Storage::disk('public')->delete($media->file_path);
            }
            
            $media->update($data);
            session()->flash('message', 'Media updated successfully!');
        } else {
            // Set sort_order to last
            $data['sort_order'] = SignageMedia::max('sort_order') + 1;
            SignageMedia::create($data);
            session()->flash('message', 'Media added successfully!');
        }

        $this->closeModal();
    }

    public function edit($id)
    {
        $media = SignageMedia::findOrFail($id);
        
        $this->editingId = $media->id;
        $this->title = $media->title;
        $this->title_mm = $media->title_mm;
        $this->type = $media->type;
        $this->duration = $media->duration;
        $this->description = $media->description;
        $this->is_active = $media->is_active;
        
        $this->showModal = true;
    }

    public function delete($id)
    {
        $media = SignageMedia::findOrFail($id);
        
        // Delete file
        if ($media->file_path) {
            Storage::disk('public')->delete($media->file_path);
        }
        
        $media->delete();
        session()->flash('message', 'Media deleted successfully!');
    }

    public function toggleActive($id)
    {
        $media = SignageMedia::findOrFail($id);
        $media->update(['is_active' => !$media->is_active]);
    }

    public function updateOrder($items)
    {
        foreach ($items as $item) {
            SignageMedia::where('id', $item['value'])->update(['sort_order' => $item['order']]);
        }
    }

    public function render()
    {
        return view('livewire.admin.signage-media-management', [
            'mediaItems' => SignageMedia::orderBy('sort_order')->paginate(12)
        ]);
    }
}
