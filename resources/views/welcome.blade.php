<x-layouts.app>
    <div class="max-w-6xl mx-auto mt-10 grid grid-cols-1 lg:grid-cols-3 gap-8 p-4">
        <div class="lg:col-span-2">
            <h1 class="text-2xl font-bold mb-6">Menu ya Leo</h1>
            <livewire:cafe-item />
        </div>

        <div class="lg:col-span-1">
            <livewire:basket />
        </div>

        <div>
            <livewire:checkout />
        </div>
    </div>
</x-layouts.app>
