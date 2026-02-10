<?php
use Livewire\Volt\Component;
use App\Models\MenuItem;

new class extends Component {

    public function with()
    {
        return [
            'items' => MenuItem::all(),
        ];
    }

    public function addToBasket($itemId)
    {
        $item = MenuItem::find($itemId);
        $basket = session()->get('basket', []);

        if(isset($basket[$itemId])) {
            $basket[$itemId]['quantity']++;
        } else {
            $basket[$itemId] = [
                "name" => $item->name,
                "quantity" => 1,
                "price" => $item->price,
            ];
        }

        session()->put('basket', $basket);

        // Hii inatuma signal kwa Basket component ijisasishe
        $this->dispatch('basket-updated');

        session()->flash('success', "Umeongeza {$item->name} kwenye kapu!");
    }
}; ?>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6 p-4">
    @foreach($items as $item)
        <div class="bg-white p-6 rounded-2xl shadow-sm border border-orange-100 flex flex-col justify-between">
            <div>
                <h3 class="text-xl font-bold text-gray-800">{{ $item->name }}</h3>
                <p class="text-sm text-gray-500 mb-4">{{ $item->description }}</p>
            </div>

            <div class="flex justify-between items-center mt-4">
                <span class="text-lg font-semibold text-orange-600">
                    {{ number_format($item->price) }} TZS
                </span>

                <button
                    wire:click="addToBasket({{ $item->id }})"
                    class="bg-orange-500 hover:bg-orange-600 text-dark px-6 py-2 rounded-full text-sm font-bold transition shadow-md"
                >
                    Weka Kapuni
                </button>
            </div>
        </div>
    @endforeach

    @if (session()->has('success'))
        <div class="fixed bottom-5 right-5 bg-green-600 text-white px-6 py-3 rounded-xl shadow-2xl transition-all">
            {{ session('success') }}
        </div>
    @endif
</div>
