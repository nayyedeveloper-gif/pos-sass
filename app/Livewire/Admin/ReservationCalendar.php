<?php

namespace App\Livewire\Admin;

use App\Models\Reservation;
use App\Models\Table;
use Carbon\Carbon;
use Livewire\Component;

class ReservationCalendar extends Component
{
    public $currentDate;
    public $calendarDays = [];
    public $selectedDate;
    public $timeSlots = [];
    public $reservations = [];
    public $tables = [];
    public $selectedTime;
    public $selectedTable;
    public $showReservationModal = false;
    public $reservationDetails = [
        'customer_name' => '',
        'customer_phone' => '',
        'guest_count' => 2,
        'notes' => ''
    ];

    protected $listeners = ['refreshCalendar' => '$refresh'];

    public function mount()
    {
        $this->currentDate = now();
        $this->selectedDate = now()->format('Y-m-d');
        $this->generateCalendar();
        $this->loadTimeSlots();
        $this->loadTables();
        $this->loadReservations();
    }

    public function loadTables()
    {
        $this->tables = Table::active()
            ->orderBy('name')
            ->get();
    }

    public function loadReservations()
    {
        $date = Carbon::parse($this->selectedDate);
        $this->reservations = Reservation::with('table')
            ->whereDate('reservation_time', $date->format('Y-m-d'))
            ->orderBy('reservation_time')
            ->get()
            ->groupBy(function($reservation) {
                return Carbon::parse($reservation->reservation_time)->format('H:i');
            });
    }

    public function generateCalendar()
    {
        $startOfMonth = $this->currentDate->copy()->startOfMonth();
        $endOfMonth = $this->currentDate->copy()->endOfMonth();
        
        $startDay = $startOfMonth->copy()->startOfWeek(Carbon::SUNDAY);
        $endDay = $endOfMonth->copy()->endOfWeek(Carbon::SATURDAY);
        
        $this->calendarDays = collect();
        
        while ($startDay->lte($endDay)) {
            $this->calendarDays->push([
                'date' => $startDay->copy(),
                'isCurrentMonth' => $startDay->month === $this->currentDate->month,
                'isToday' => $startDay->isToday(),
                'hasReservations' => $this->hasReservations($startDay->format('Y-m-d')),
            ]);
            
            $startDay->addDay();
        }
    }

    protected function hasReservations($date)
    {
        return Reservation::whereDate('reservation_time', $date)->exists();
    }

    public function loadTimeSlots()
    {
        $this->timeSlots = [];
        $startTime = Carbon::parse('10:00');
        $endTime = Carbon::parse('22:00');
        
        while ($startTime <= $endTime) {
            $this->timeSlots[] = $startTime->format('H:i');
            $startTime->addMinutes(30);
        }
    }

    public function selectDate($date)
    {
        $this->selectedDate = $date;
        $this->loadReservations();
    }

    public function selectTime($time)
    {
        $this->selectedTime = $time;
        $this->showReservationModal = true;
    }

    public function createReservation()
    {
        $validated = $this->validate([
            'reservationDetails.customer_name' => 'required|string|max:255',
            'reservationDetails.customer_phone' => 'required|string|max:20',
            'reservationDetails.guest_count' => 'required|integer|min:1|max:20',
            'selectedTable' => 'required|exists:tables,id',
            'selectedTime' => 'required',
        ]);

        $reservationTime = Carbon::parse($this->selectedDate . ' ' . $this->selectedTime);

        $reservation = Reservation::create([
            'customer_name' => $this->reservationDetails['customer_name'],
            'customer_phone' => $this->reservationDetails['customer_phone'],
            'reservation_time' => $reservationTime,
            'guest_count' => $this->reservationDetails['guest_count'],
            'table_id' => $this->selectedTable,
            'notes' => $this->reservationDetails['notes'],
            'status' => 'confirmed',
            'created_by' => auth()->id(),
        ]);

        $this->showReservationModal = false;
        $this->reset(['reservationDetails', 'selectedTable', 'selectedTime']);
        $this->loadReservations();
        $this->emit('refreshCalendar');
        
        session()->flash('message', 'Reservation created successfully.');
    }

    public function previousMonth()
    {
        $this->currentDate->subMonth();
        $this->generateCalendar();
    }

    public function nextMonth()
    {
        $this->currentDate->addMonth();
        $this->generateCalendar();
    }

    public function render()
    {
        return view('livewire.admin.reservation-calendar', [
            'monthName' => $this->currentDate->format('F Y'),
        ]);
    }
}
