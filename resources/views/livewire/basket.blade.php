<?php
use Livewire\Volt\Component;
use Livewire\Attributes\On;

new class extends Component {
    public $items = [];
    public $total = 0;

    #[On('basket-updated')]
    public function mount()
    {
        $this->items = session()->get('basket', []);
        $this->total = collect($this->items)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function clear()
    {
        session()->forget('basket');
        $this->mount();
    }
}; ?>

<div class="bg-gray-50 p-4 rounded-xl border-2 border-dashed border-gray-200">
    <h2 class="font-bold text-lg mb-4">Kapu Lako (Your Basket)</h2>

    @empty($items)
        <p class="text-gray-500 italic">Kapu ni tupu...</p>
    @else
        <ul class="space-y-2">
            @foreach($items as $id => $details)
                <li class="flex justify-between text-sm">
                    <span>{{ $details['quantity'] }}x {{ $details['name'] }}</span>
                    <span>{{ number_format($details['price'] * $details['quantity']) }} TZS</span>
                </li>
            @endforeach
        </ul>

        <div class="mt-4 pt-4 border-t font-bold flex justify-between text-orange-700">
            <span>Jumla:</span>
            <span>{{ number_format($total) }} TZS</span>
        </div>

        <button wire:click="clear" class="text-xs text-red-500 mt-2 underline">Ondoa Vyote</button>

        <button class="w-full mt-4 bg-green-600 text-white py-2 rounded-lg font-bold">
            Lipia na M-Pesa
        </button>
    @endempty
</div>
