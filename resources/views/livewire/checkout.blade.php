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
            // 'phone_number' => ['required', 'regex:/^(0)[671][0-9]{8}$/'],
            'phone_number' => 'required|numeric|digits_between:10,12',
        ]);

        try {
            $sessionID = $thid->getSessionId();

            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $sessionID,
                'Origin' => 'developer.vodacom.co.tz',
                'Content-Type' => 'application/json',
            ])->post('https://openapi.m-pesa.vodacom.co.tz/sandbox/ipg/v2/vodatanzania/c2bPayment/singleStage/', [
                'input_Amount' => $this->total_amount,
                'input_Country' => 'TZN',
                'input_Currency' => 'TZS',
                'input_CustomerMSISDN' => $this->phone_number,
                'input_ServiceProviderCode' => '000000',
                'input_TransactionReference' => 'CAFE' . time(),
                'input_PurchasedItemsDesc' => 'Cafe Order Payment',
            ]);

            if($response->json()['output_ResponseCode'] === 'INS-0') {
                session()->flash('success', 'Malipo yameanza. Tafadhali kamilisha kwenye simu yako.');
                return redirect()->to('/');
            }
        } catch(\Exception $e) {
            session()->flash('error', 'Samahani, itilafu imetokea ' . $e->getMessage());
        }

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

    private function getSessionId()
    {
        $publicKey = env('VODACOM_PUBLIC_KEY');
        $apiKey = env('VODACOM_API_KEY');

        //encrypting the key
        openssl_public_encrypt($apiKey, $encrypted, $publicKey, OPENSSL_PKCS1_PADDING);
        $bearerToken = base64_encode($encrypted);

        $response = Http::withHeaders([
            'Authorization'=> 'Bearer ' . $bearerToken,
            'Origin' => 'developer.vodacom.co.tz',
        ])->get('https://openapi.m-pesa.vodacom.co.tz/sandbox/ipg/v2/vodatanzania/getSession/');

        return $response->json()['output_sessionID'];
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

        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif
    </form>
</div>
