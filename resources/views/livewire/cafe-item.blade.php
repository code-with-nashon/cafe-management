<?php

use Livewire\Volt\Component;
use App\Models\MenuItem;

new class extends Component {
    public $items;

    public function mount()
    {
        $this->items = MenuItem::all();
    }

    public function order($itemId)
    {   
        $item = MenuItem::find($itemId);
        session()->flash('success', "Umeongeza {$item->name} kwenye oda yako!");
    }
}; 

?>

<div class="grid grid-cols-1 md:grid-cols-2gap-4 p-4">
    @foreach($items as $item)
        <div class="bg-white p-6 rounded-2xl shadow-sm border borderorange-100">
            <h3 class="text-xl font-bold text-gray-800">{{ $item->name }}</h3>
            <p class="text-sm text-gray-500 mb-4">{{ $item->description }}</p>

            <div class="flex justify-between items-center mt-auto">
                <span class="text-lg font-semibold text-orang-600">
                    {{ number_format($item->price) }} TZS
                </span>

                <button wire:click="order({{ $item->id }})" class="bg-orange-500 hover:bg-orange-600 text-white px-4 py-2 rounded-full text-sm font-bold transition">
                    Weka Oda
                </button>
            </div>
        </div>
    @endforeach

    @if (session()->has('success'))
        <div class="fixed bottom-5 right-5 bg-green-500 text-white p-4 rounded-lg">
            {{ session('success') }}
        </div>
    @endif
</div>