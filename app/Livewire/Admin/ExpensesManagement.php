<?php

namespace App\Livewire\Admin;

use App\Models\Expense;
use Livewire\Component;
use Livewire\WithPagination;

class ExpensesManagement extends Component
{
    use WithPagination;

    public $search = '';
    public $categoryFilter = '';
    public $dateFilter = 'today';
    
    public $expenseId;
    public $category = '';
    public $description = '';
    public $amount = '';
    public $expense_date;
    public $payment_method = 'cash';
    public $receipt_number = '';
    public $notes = '';
    
    public $showModal = false;
    public $editMode = false;
    public $deleteConfirm = false;
    public $expenseToDelete;

    public $categories = [
        'food_ingredients' => 'အစားအစာ ပစ္စည်းများ',
        'beverages' => 'အဖျော်ယမကာ ပစ္စည်းများ',
        'utilities' => 'လျှပ်စစ်၊ ရေ၊ အင်တာနက်',
        'rent' => 'ဆိုင်ငှား',
        'salaries' => 'လစာများ',
        'maintenance' => 'ပြုပြင်ထိန်းသိမ်းမှု',
        'supplies' => 'ရုံးသုံးပစ္စည်းများ',
        'marketing' => 'ကြော်ငြာ',
        'transportation' => 'သယ်ယူပို့ဆောင်ရေး',
        'other' => 'အခြား',
    ];

    protected $queryString = ['search', 'categoryFilter', 'dateFilter'];

    public function mount()
    {
        $this->expense_date = today()->format('Y-m-d');
    }

    protected function rules()
    {
        return [
            'category' => 'required|string',
            'description' => 'required|string|max:255',
            'amount' => 'required|numeric|min:0',
            'expense_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'receipt_number' => 'nullable|string|max:100',
            'notes' => 'nullable|string',
        ];
    }

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function updatingCategoryFilter()
    {
        $this->resetPage();
    }

    public function updatingDateFilter()
    {
        $this->resetPage();
    }

    public function create()
    {
        $this->resetForm();
        $this->editMode = false;
        $this->showModal = true;
    }

    public function edit($id)
    {
        $expense = Expense::findOrFail($id);
        
        $this->expenseId = $expense->id;
        $this->category = $expense->category;
        $this->description = $expense->description;
        $this->amount = $expense->amount;
        $this->expense_date = $expense->expense_date->format('Y-m-d');
        $this->payment_method = $expense->payment_method;
        $this->receipt_number = $expense->receipt_number;
        $this->notes = $expense->notes;
        
        $this->editMode = true;
        $this->showModal = true;
    }

    public function save()
    {
        $this->validate();

        $data = [
            'user_id' => auth()->id(),
            'category' => $this->category,
            'description' => $this->description,
            'amount' => $this->amount,
            'expense_date' => $this->expense_date,
            'payment_method' => $this->payment_method,
            'receipt_number' => $this->receipt_number,
            'notes' => $this->notes,
        ];

        if ($this->editMode) {
            $expense = Expense::findOrFail($this->expenseId);
            $expense->update($data);
            session()->flash('message', 'အသုံးစရိတ်ကို အောင်မြင်စွာ ပြင်ဆင်ပြီးပါပြီ။');
        } else {
            Expense::create($data);
            session()->flash('message', 'အသုံးစရိတ်ကို အောင်မြင်စွာ ထည့်သွင်းပြီးပါပြီ။');
        }

        $this->closeModal();
    }

    public function confirmDelete($id)
    {
        $this->expenseToDelete = $id;
        $this->deleteConfirm = true;
    }

    public function delete()
    {
        $expense = Expense::findOrFail($this->expenseToDelete);
        $expense->delete();
        
        session()->flash('message', 'အသုံးစရိတ်ကို အောင်မြင်စွာ ဖျက်ပြီးပါပြီ။');
        $this->deleteConfirm = false;
        $this->expenseToDelete = null;
    }

    public function closeModal()
    {
        $this->showModal = false;
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->reset([
            'expenseId',
            'category',
            'description',
            'amount',
            'payment_method',
            'receipt_number',
            'notes',
        ]);
        $this->expense_date = today()->format('Y-m-d');
        $this->payment_method = 'cash';
        $this->resetValidation();
    }

    public function render()
    {
        $query = $this->buildQuery();
        $expenses = $query->latest('expense_date')->paginate(20);
        
        // Calculate totals based on filters
        $totalsQuery = $this->buildQuery();
        $totalAmount = $totalsQuery->sum('amount');

        // Calculate by category based on filters
        $expensesByCategory = $this->buildQuery()
            ->selectRaw('category, sum(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        return view('livewire.admin.expenses-management', [
            'expenses' => $expenses,
            'totalAmount' => $totalAmount,
            'expensesByCategory' => $expensesByCategory,
        ]);
    }

    public function export()
    {
        $expenses = $this->buildQuery()->latest('expense_date')->get();
        $totalAmount = $expenses->sum('amount');
        
        $expensesByCategory = $this->buildQuery()
            ->selectRaw('category, sum(amount) as total_amount')
            ->groupBy('category')
            ->orderByDesc('total_amount')
            ->get();

        $filename = 'expenses_report_' . now()->format('Y_m_d') . '.xls';

        return response()->streamDownload(function() use ($expenses, $totalAmount, $expensesByCategory) {
            echo view('admin.expenses.export', [
                'expenses' => $expenses,
                'totalAmount' => $totalAmount,
                'expensesByCategory' => $expensesByCategory,
                'categories' => $this->categories,
            ])->render();
        }, $filename);
    }

    private function buildQuery()
    {
        $query = Expense::with('user');

        if ($this->search) {
            $query->where(function($q) {
                $q->where('description', 'like', '%' . $this->search . '%')
                  ->orWhere('receipt_number', 'like', '%' . $this->search . '%');
            });
        }

        if ($this->categoryFilter) {
            $query->where('category', $this->categoryFilter);
        }

        if ($this->dateFilter) {
            switch ($this->dateFilter) {
                case 'today':
                    $query->whereDate('expense_date', today());
                    break;
                case 'yesterday':
                    $query->whereDate('expense_date', today()->subDay());
                    break;
                case 'week':
                    $query->whereBetween('expense_date', [now()->startOfWeek(), now()->endOfWeek()]);
                    break;
                case 'month':
                    $query->whereMonth('expense_date', now()->month)
                          ->whereYear('expense_date', now()->year);
                    break;
            }
        }

        return $query;
    }
}
