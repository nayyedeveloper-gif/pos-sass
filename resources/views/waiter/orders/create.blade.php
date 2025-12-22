<x-app-layout>
    @livewire('waiter.create-order', ['table' => request('table'), 'type' => request('type'), 'order' => request('order')])
</x-app-layout>
