<?php

use Livewire\Volt\Component;
use Livewire\Attributes\On; 

new class extends Component {
    public $customer_name = '';
    public $phone_number = '';
    public $total_amount = 0;

    public function mount()
    {
        $this->calculateTotal();
    }


    public function calculateTotal()
    {
        $basket = session()->get('basket', []);

        $this->total_amount = collect($basket)->sum(fn($item) => $item['price'] * $item['quantity']);
    }

    public function processPayment()
    {
        $this->validate([
            'customer_name' => 'required|min:3',
            'phone_number' => ['required', 'regex:/^(0)[671][0-9]{8}$/'],
        ]);

        $formattedPhone = '255' . substr($this->phone_number, 1);

        $order = \App\Models\Order::create([
            'customer_name' => $this->customer_name,
            'phone_number' => $formattedPhone,
            'total_amount' => $this->total_amount,
            'status' => 'pending',
        ]);

        session()->forget('basket');

        session()->flash('success', "Oda Na. #{$order->id} imepokelewa! Malipo ya M-Pesa yanashughulikiwa.");

        return redirect()->to('/');
    }
}; ?>

<div class="bg-white p-8 rounded-2xl shadow-lg border border-gray-100">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Checkout</h2>

    <div class="mb-6 bg-orange-50 p-4 rounded-xl">
        <p class="text-gray-600 text-sm uppercase tracking-wider font-semibold">Jumla ya Malipo:</p>
        <p class="text-3xl font-black text-orange-600">{{ number_format($total_amount) }} TZS</p>
    </div>

    <form wire:submit.prevent="processPayment" class="space-y-4">
        <div>
            <label class="block text-sm font-bold text-gray-700">Jina Kamili (Full Name)</label>
            <input type="text" wire:model="customer_name" placeholder="Mfano: John Doe"
                   class="w-full p-3 border border-gray-300 rounded-xl mt-1 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
            @error('customer_name') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <div>
            <label class="block text-sm font-bold text-gray-700">Namba ya M-Pesa (Phone Number)</label>
            <div class="relative">
                <input type="text" wire:model="phone_number" placeholder="07XXXXXXXX"
                       class="w-full p-3 border border-gray-300 rounded-xl mt-1 focus:ring-2 focus:ring-orange-500 focus:border-orange-500 outline-none transition">
            </div>
            @error('phone_number') <span class="text-red-500 text-xs">{{ $message }}</span> @enderror
        </div>

        <button type="submit"
                class="w-full bg-green-600 hover:bg-green-700 text-dark font-bold py-4 rounded-xl shadow-lg transform active:scale-95 transition duration-200 mt-4">
            Lipia sasa (M-Pesa)
        </button>
    </form>
</div>
