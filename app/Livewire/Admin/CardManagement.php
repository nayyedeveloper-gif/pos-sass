<?php

namespace App\Livewire\Admin;

use App\Models\Card;
use App\Models\CardTransaction;
use App\Models\Customer;
use App\Models\Setting;
use Livewire\Component;
use Livewire\WithPagination;
use Illuminate\Support\Facades\DB;

class CardManagement extends Component
{
    use WithPagination;

    // Modal states
    public $showIssueModal = false;
    public $showLoadModal = false;
    public $showDetailsModal = false;

    // Form fields for issue card
    public $customer_id = null;
    public $card_type = 'virtual';
    public $initial_balance = 0;
    public $notes = '';

    // Form fields for load money
    public $selected_card_id = null;
    public $load_amount = 0;
    public $payment_method = 'cash';

    // Search and filter
    public $search = '';
    public $statusFilter = 'all';
    public $cardTypeFilter = 'all';

    // Selected card for details
    public $selectedCard = null;

    protected $queryString = ['search', 'statusFilter', 'cardTypeFilter'];

    public function mount()
    {
        // Check if card system is enabled
        if (!Setting::get('card_system_enabled', false)) {
            session()->flash('error', 'Card System ကို Settings မှာ ဖွင့်ရန် လိုအပ်ပါသည်။');
        }
    }

    public function openIssueModal()
    {
        $this->resetForm();
        $this->showIssueModal = true;
    }

    public function closeIssueModal()
    {
        $this->showIssueModal = false;
        $this->resetForm();
    }

    public function openLoadModal($cardId)
    {
        $this->selected_card_id = $cardId;
        $this->load_amount = 0;
        $this->payment_method = 'cash';
        $this->showLoadModal = true;
    }

    public function closeLoadModal()
    {
        $this->showLoadModal = false;
        $this->selected_card_id = null;
        $this->load_amount = 0;
    }

    public function openDetailsModal($cardId)
    {
        $this->selectedCard = Card::with(['customer', 'transactions.creator'])
            ->findOrFail($cardId);
        $this->showDetailsModal = true;
    }

    public function closeDetailsModal()
    {
        $this->showDetailsModal = false;
        $this->selectedCard = null;
    }

    public function issueCard()
    {
        $this->validate([
            'card_type' => 'required|in:virtual,physical',
            'initial_balance' => 'required|numeric|min:0',
            'customer_id' => 'nullable|exists:customers,id',
            'notes' => 'nullable|string|max:500',
        ]);

        try {
            DB::beginTransaction();

            // Generate card number
            $cardNumber = Card::generateCardNumber();

            // Calculate expiry date if enabled
            $expiryDate = null;
            if (Setting::get('card_expiry_enabled', false)) {
                $months = Setting::get('card_expiry_months', 12);
                $expiryDate = now()->addMonths($months);
            }

            // Create card
            $card = Card::create([
                'card_number' => $cardNumber,
                'customer_id' => $this->customer_id,
                'balance' => 0,
                'status' => 'active',
                'card_type' => $this->card_type,
                'issued_date' => now(),
                'expiry_date' => $expiryDate,
                'notes' => $this->notes,
            ]);

            // Load initial balance if provided
            if ($this->initial_balance > 0) {
                $card->addBalance($this->initial_balance, 'initial', 0, auth()->id());
            }

            DB::commit();

            session()->flash('message', "Card {$cardNumber} ကို အောင်မြင်စွာ ထုတ်ပေးပြီးပါပြီ။");
            $this->closeIssueModal();
            $this->resetPage();

        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Card ထုတ်ပေးရာတွင် အမှားအယွင်း ဖြစ်ပေါ်ခဲ့ပါသည်။');
        }
    }

    public function loadMoney()
    {
        $this->validate([
            'load_amount' => 'required|numeric|min:100',
            'payment_method' => 'required|string',
        ]);

        try {
            $card = Card::findOrFail($this->selected_card_id);

            if (!$card->isActive()) {
                session()->flash('error', 'ဤ Card ကို လက်ရှိ အသုံးပြု၍ မရပါ။');
                return;
            }

            // Calculate bonus if enabled
            $bonusAmount = 0;
            if (Setting::get('card_bonus_enabled', false)) {
                $bonusPercentage = Setting::get('card_bonus_percentage', 0);
                $bonusAmount = ($this->load_amount * $bonusPercentage) / 100;
            }

            $card->addBalance(
                $this->load_amount,
                $this->payment_method,
                $bonusAmount,
                auth()->id()
            );

            $totalAdded = $this->load_amount + $bonusAmount;
            $message = "Card {$card->card_number} သို့ {$this->load_amount} Ks ထည့်သွင်းပြီးပါပြီ။";
            if ($bonusAmount > 0) {
                $message .= " (Bonus: {$bonusAmount} Ks)";
            }

            session()->flash('message', $message);
            $this->closeLoadModal();

        } catch (\Exception $e) {
            session()->flash('error', 'ငွေထည့်သွင်းရာတွင် အမှားအယွင်း ဖြစ်ပေါ်ခဲ့ပါသည်။');
        }
    }

    public function toggleStatus($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);
            $card->status = $card->status === 'active' ? 'inactive' : 'active';
            $card->save();

            $status = $card->status === 'active' ? 'ဖွင့်' : 'ပိတ်';
            session()->flash('message', "Card {$card->card_number} ကို {$status}ပြီးပါပြီ။");

        } catch (\Exception $e) {
            session()->flash('error', 'Status ပြောင်းလဲရာတွင် အမှားအယွင်း ဖြစ်ပေါ်ခဲ့ပါသည်။');
        }
    }

    public function blockCard($cardId)
    {
        try {
            $card = Card::findOrFail($cardId);
            $card->status = 'blocked';
            $card->save();

            session()->flash('message', "Card {$card->card_number} ကို ပိတ်ဆို့ပြီးပါပြီ။");

        } catch (\Exception $e) {
            session()->flash('error', 'Card ပိတ်ဆို့ရာတွင် အမှားအယွင်း ဖြစ်ပေါ်ခဲ့ပါသည်။');
        }
    }

    public function resetForm()
    {
        $this->customer_id = null;
        $this->card_type = 'virtual';
        $this->initial_balance = 0;
        $this->notes = '';
        $this->resetErrorBag();
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingStatusFilter()
    {
        $this->resetPage();
    }

    public function updatingCardTypeFilter()
    {
        $this->resetPage();
    }

    public function render()
    {
        $query = Card::with('customer')
            ->when($this->search, function ($q) {
                $q->where('card_number', 'like', '%' . $this->search . '%')
                  ->orWhereHas('customer', function ($query) {
                      $query->where('name', 'like', '%' . $this->search . '%')
                            ->orWhere('phone', 'like', '%' . $this->search . '%');
                  });
            })
            ->when($this->statusFilter !== 'all', function ($q) {
                $q->where('status', $this->statusFilter);
            })
            ->when($this->cardTypeFilter !== 'all', function ($q) {
                $q->where('card_type', $this->cardTypeFilter);
            })
            ->latest();

        $cards = $query->paginate(15);

        // Statistics
        $stats = [
            'total_cards' => Card::count(),
            'active_cards' => Card::where('status', 'active')->count(),
            'total_balance' => Card::where('status', 'active')->sum('balance'),
            'total_loaded' => CardTransaction::where('transaction_type', 'load')->sum('amount'),
        ];

        $customers = Customer::orderBy('name')->get();

        return view('livewire.admin.card-management', [
            'cards' => $cards,
            'stats' => $stats,
            'customers' => $customers,
        ]);
    }
}
