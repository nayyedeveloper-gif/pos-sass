<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use App\Models\Table;
use App\Models\Customer;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\Auth;

class ReservationManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $statusFilter = 'all';
    public $dateFilter = '';
    
    // Modal & Form State
    public $showModal = false;
    public $isEditMode = false;
    public $reservationId = null;
    
    public $customer_name;
    public $customer_phone;
    public $reservation_time;
    public $guest_count = 1;
    public $table_id;
    public $notes;
    public $status = 'pending';
    
    // Available Tables for selection
    public $tables = [];

    protected $listeners = ['refreshReservations' => '$refresh'];

    public function mount()
    {
        $this->dateFilter = now()->format('Y-m-d');
        $this->tables = Table::active()->get();
    }

    protected function rules()
    {
        return [
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'reservation_time' => 'required|date|after:now',
            'guest_count' => 'required|integer|min:1',
            'table_id' => 'nullable|exists:tables,id',
            'notes' => 'nullable|string',
            'status' => 'required|in:pending,confirmed,cancelled,completed,no_show',
        ];
    }

    public function render()
    {
        $query = Reservation::query()
            ->with(['table', 'customer'])
            ->orderBy('reservation_time', 'asc');

        if ($this->search) {
            $query->where(function ($q) {
                $q->where('customer_name', 'like', '%' . $this->search . '%')
                  ->orWhere('customer_phone', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }

        if ($this->dateFilter) {
            $query->whereDate('reservation_time', $this->dateFilter);
        }

        $reservations = $query->paginate(10);

        return view('livewire.admin.reservation-management', [
            'reservations' => $reservations
        ]);
    }

    public function openCreateModal()
    {
        $this->resetForm();
        $this->isEditMode = false;
        $this->reservation_time = now()->addHour()->format('Y-m-d\TH:i');
        $this->showModal = true;
    }

    public function edit($id)
    {
        $reservation = Reservation::findOrFail($id);
        
        $this->reservationId = $id;
        $this->customer_name = $reservation->customer_name;
        $this->customer_phone = $reservation->customer_phone;
        $this->reservation_time = $reservation->reservation_time->format('Y-m-d\TH:i');
        $this->guest_count = $reservation->guest_count;
        $this->table_id = $reservation->table_id;
        $this->notes = $reservation->notes;
        $this->status = $reservation->status;
        
        $this->isEditMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $validatedData = $this->validate();

        // Check if customer exists or create new if not found by phone
        $customer = Customer::firstOrCreate(
            ['phone' => $this->customer_phone],
            ['name' => $this->customer_name]
        );

        if ($this->isEditMode) {
            $reservation = Reservation::findOrFail($this->reservationId);
            $reservation->update([
                'customer_id' => $customer->id,
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'reservation_time' => $this->reservation_time,
                'guest_count' => $this->guest_count,
                'table_id' => $this->table_id,
                'notes' => $this->notes,
                'status' => $this->status,
            ]);
            
            session()->flash('message', 'Reservation updated successfully.');
        } else {
            Reservation::create([
                'customer_id' => $customer->id,
                'customer_name' => $this->customer_name,
                'customer_phone' => $this->customer_phone,
                'reservation_time' => $this->reservation_time,
                'guest_count' => $this->guest_count,
                'table_id' => $this->table_id,
                'notes' => $this->notes,
                'status' => $this->status,
                'created_by' => Auth::id(),
            ]);
            
            session()->flash('message', 'Reservation created successfully.');
        }

        $this->showModal = false;
        $this->resetForm();
    }

    public function updateStatus($id, $status)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->update(['status' => $status]);
        session()->flash('message', 'Reservation status updated.');
    }

    public function delete($id)
    {
        $reservation = Reservation::findOrFail($id);
        $reservation->delete();
        session()->flash('message', 'Reservation deleted successfully.');
    }

    private function resetForm()
    {
        $this->customer_name = '';
        $this->customer_phone = '';
        $this->reservation_time = '';
        $this->guest_count = 1;
        $this->table_id = null;
        $this->notes = '';
        $this->status = 'pending';
        $this->reservationId = null;
    }
}
