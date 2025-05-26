<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Complete Your Payment') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-xl sm:rounded-lg p-6 md:p-10">

                @if($payment)

                    @if($payment->payment_status === 'waiting')
                        <div class="text-center mb-6">
                            <h1 class="text-2xl font-bold text-gray-900">GEMINI PRO</h1>
                        </div>

                        <div class="bg-gray-50 p-6 rounded-lg border border-gray-200">
                            <div class="flex justify-between items-center mb-6">
                                <h3 class="text-lg font-semibold text-gray-800">Send deposit</h3>
                                <div class="text-lg font-medium bg-gray-200 text-gray-800 px-3 py-1 rounded" id="countdown-timer">
                                    <span>--:--</span>
                                </div>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 gap-8 items-center">
                                <div class="text-center p-4 border border-gray-300 rounded-lg bg-white relative">
                                    <h4 class="text-sm font-medium text-gray-600 mb-2">Scan QR Code</h4>
                                    <div class="inline-block p-2 bg-white rounded-lg border">
                                        {{-- Cek jika alamat ada sebelum generate --}}
                                        @if($payment->pay_address)
                                            {{-- Generate QR Code menggunakan library --}}
                                            {!! QrCode::size(224)->generate($payment->pay_address) !!}
                                            {{-- Ukuran 224px agar pas di dalam border (250-8-8-10) --}}
                                        @else
                                            <div class="w-56 h-56 mx-auto flex items-center justify-center bg-gray-100 text-red-500 text-center p-4">
                                                QR Code Error: <br> Address not available.
                                            </div>
                                        @endif
                                    </div>
                                    <p class="mt-2 text-xs text-gray-500">Scan this code with your wallet</p>
                                </div>

                                <div class="space-y-6">
                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Amount</label>
                                        <div class="flex items-baseline justify-between mt-1">
                                            <span class="text-2xl font-bold text-gray-900">
                                                {{ $payment->pay_amount }} <span class="text-xl">{{ strtoupper($payment->pay_currency) }}</span>
                                            </span>
                                            <span class="text-gray-500">~ ${{ number_format($payment->price_amount, 2) }}</span>
                                            <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                                                {{-- Ganti ETH dengan jaringan yang sesuai jika ada datanya --}}
                                                ETH
                                            </span>
                                        </div>
                                        <p class="text-xs text-yellow-600 mt-1">
                                            <strong>Important:</strong> Send the exact amount.
                                        </p>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Address</label>
                                        <div class="mt-1 flex rounded-md shadow-sm border border-gray-300 bg-gray-100 p-3">
                                            <code class="flex-1 text-sm break-all text-gray-800">{{ $payment->pay_address }}</code>
                                            <button onclick="copyToClipboard('{{ $payment->pay_address }}', this)"
                                                    class="ml-3 text-blue-600 hover:text-blue-800 focus:outline-none">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                            </button>
                                        </div>
                                    </div>

                                    <div>
                                        <label class="block text-sm font-medium text-gray-700">Deposit options</label>
                                        <div class="mt-2 flex space-x-3">
                                            <img src="https://upload.wikimedia.org/wikipedia/commons/3/36/MetaMask_Fox.svg" alt="MetaMask" class="w-10 h-10 p-1 border rounded-md hover:shadow-lg cursor-pointer">
                                            <img src="https://seeklogo.com/images/C/coinbase-coin-logo-C86F46D7B8-seeklogo.com.png" alt="Coinbase Wallet" class="w-10 h-10 p-1 border rounded-md hover:shadow-lg cursor-pointer">
                                        </div>
                                    </div>

                                    <div class="text-center mt-6">
                                        <button onclick="window.location.reload();"
                                                class="w-full px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                            I have made the payment
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                    @elseif(in_array($payment->payment_status, ['completed', 'finished']))
                        <div class="bg-green-50 border border-green-200 rounded-lg p-6 text-center">
                             <h3 class="text-xl font-medium text-green-800">Payment Completed!</h3>
                             <p class="text-green-700 mt-2 mb-4">Your payment has been confirmed and your subscription is now active!</p>
                             <a href="{{ route('dashboard') }}" class="px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700">Go to Dashboard</a>
                        </div>
                    
                    @else
                        <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                             <h3 class="text-xl font-medium text-red-800">Payment Failed or Expired</h3>
                             <p class="text-red-700 mt-2 mb-4">Status: {{ ucfirst($payment->payment_status) }}. Please try again or contact support.</p>
                             <a href="{{ route('billing.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Back to Billing</a>
                        </div>
                    @endif

                @else 
                    <div class="bg-red-50 border border-red-200 rounded-lg p-6 text-center">
                         <h3 class="text-xl font-medium text-red-800">Error</h3>
                         <p class="text-red-700 mt-2 mb-4">Could not retrieve payment details. Please go back and try again.</p>
                         <a href="{{ route('billing.index') }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700">Back to Billing</a>
                    </div>
                @endif

                 <div class="mt-8 text-center">
                    <a href="{{ route('billing.index') }}" 
                       class="text-sm text-gray-600 hover:text-gray-900">
                        ← Back to Billing
                    </a>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        function copyToClipboard(text, buttonElement) {
            navigator.clipboard.writeText(text).then(function() {
                const originalContent = buttonElement.innerHTML;
                buttonElement.innerHTML = `<svg class="w-5 h-5 text-green-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>`;
                setTimeout(() => {
                    buttonElement.innerHTML = originalContent;
                }, 1500);
            }, function(err) {
                alert('Could not copy address: ', err);
            });
        }

        @if($payment)
            const expiryTimeString = '{{ $payment->payment_expires_at ?? ($payment->payment_created_at ? $payment->payment_created_at->addMinutes(30)->toIso8601String() : now()->addMinutes(30)->toIso8601String()) }}';
            const expiryTime = new Date(expiryTimeString).getTime();
            const timerElement = document.getElementById('countdown-timer');

            function updateTimer() {
                const now = new Date().getTime();
                const distance = expiryTime - now;

                if (distance < 0) {
                    clearInterval(timerInterval);
                    if(timerElement) timerElement.innerHTML = "Expired";
                    return;
                }

                const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
                const seconds = Math.floor((distance % (1000 * 60)) / 1000);

                const displayMinutes = minutes < 10 ? '0' + minutes : minutes;
                const displaySeconds = seconds < 10 ? '0' + seconds : seconds;

                if(timerElement) timerElement.innerHTML = `<span>${displayMinutes}:${displaySeconds}</span>`;
            }

            let timerInterval = null;
            if ('{{ $payment->payment_status }}' === 'waiting') {
                if (timerElement) {
                     updateTimer(); 
                     timerInterval = setInterval(updateTimer, 1000);
                     
                     setInterval(function() {
                        if (expiryTime - new Date().getTime() > 0) {
                           window.location.reload();
                        } else {
                           clearInterval(this); 
                           window.location.reload();
                        }
                    }, 30000);
                }
            } else if (timerElement) {
                 timerElement.innerHTML = `<span>{{ ucfirst($payment->payment_status) }}</span>`;
            }
        @endif
    </script>
    @endpush

</x-app-layout>