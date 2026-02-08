<?php

use Livewire\Component;

new class extends Component
{
    public $name="Latte";
    public $basePrice = 5000;
    public $extraSugar = false;
    public $quantity = 1;

    public function getTotalProperty()
    {
        $extra = $this->extraSugar ? 500:0;
        return ($this->basePrice + $extra) * $this->quantity;
    }

    public function addToOrder()
    {
        session()->flash('message', "Added {$this->quantity} {$this->name} to cart!");
    }
};
?>

<div class="p-6 border rounded-xl shadow-sm bg-white">
    <h3 class="text-xl font-bold">{{ $name }}</h3>
    <p class="text-gray-600">Base Price: {{ number_format($basePrice) }} TZS</p>

    <div class="mt-4 space-y-2">
        <div class="flex items-center gap-4">
            <button wire:click="$set('quantity', {{ max(1, $quantity - 1) }})" class="px-2 bg-gray-200">-</button>
            <span>{{ $quantity }}</span>
            <button wire:click="$set('quantity', {{ $quantity + 1 }})" class="px-2 bg-gray-200">+<button>
        </div>

        <label class="flex items-center gap-2 cursor-pointer">
            <input type="checkbox" wire:model.live="extraSugar">
            <span>Add Extra Sugar (+500 TZS)</span>
        </label>
    </div>

    <hr class="my-4">

    <div class="flex justify-between items-center">
        <span class="text-lg font-bold">Total: {{ number_format($this->total) }} TZS </span>
        <button wire:click="addToOrder" class="hg-brown-600 text-white px-4 py-2 rounded-lg bg-blue-600"> Order Now </button>
    </div>

    @if (session()->has('message'))
        <div class="mt-2 text-green-600 text-sm italic">
            {{ session('message') }}
        </div>
    @endif
</div>