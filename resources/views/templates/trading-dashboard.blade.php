@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Trading Dashboard
        </h2>
        <div class="flex space-x-3">
            <x-button variant="outline" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                </svg>
                Refresh
            </x-button>
            <x-button variant="primary" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                New Bot
            </x-button>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Portfolio Overview Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <x-card class="border-l-4 border-l-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Total Balance</p>
                    <p class="text-2xl font-bold text-green-600">$24,580.50</p>
                    <p class="text-sm text-green-600">+12.5% today</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Bots</p>
                    <p class="text-2xl font-bold text-blue-600">8</p>
                    <p class="text-sm text-gray-500">2 paused</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Today's Profit</p>
                    <p class="text-2xl font-bold text-purple-600">$1,205.30</p>
                    <p class="text-sm text-purple-600">+8.2%</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="border-l-4 border-l-orange-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-orange-600">87.5%</p>
                    <p class="text-sm text-gray-500">142/162 trades</p>
                </div>
                <div class="p-3 bg-orange-100 rounded-full">
                    <svg class="w-6 h-6 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- Quick Actions -->
    <x-card title="Quick Actions">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <x-button variant="outline" class="flex flex-col items-center p-4 h-auto">
                <svg class="w-8 h-8 mb-2 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                <span>Create Bot</span>
            </x-button>

            <x-button variant="outline" class="flex flex-col items-center p-4 h-auto">
                <svg class="w-8 h-8 mb-2 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
                <span>Generate Signal</span>
            </x-button>

            <x-button variant="outline" class="flex flex-col items-center p-4 h-auto">
                <svg class="w-8 h-8 mb-2 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                </svg>
                <span>View Reports</span>
            </x-button>

            <x-button variant="outline" class="flex flex-col items-center p-4 h-auto">
                <svg class="w-8 h-8 mb-2 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span>Settings</span>
            </x-button>
        </div>
    </x-card>

    <!-- Active Trading Bots and Recent Signals -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Active Bots -->
        <x-card title="Active Trading Bots" subtitle="Currently running automated strategies">
            <div class="space-y-4">
                @foreach([
                    ['name' => 'BTC Scalper Pro', 'pair' => 'BTC/USDT', 'profit' => '+$520.30', 'status' => 'active', 'color' => 'green'],
                    ['name' => 'ETH DCA Bot', 'pair' => 'ETH/USDT', 'profit' => '+$180.75', 'status' => 'active', 'color' => 'green'],
                    ['name' => 'Altcoin Grid', 'pair' => 'ADA/USDT', 'profit' => '-$45.20', 'status' => 'paused', 'color' => 'yellow'],
                    ['name' => 'Momentum Trader', 'pair' => 'SOL/USDT', 'profit' => '+$350.85', 'status' => 'active', 'color' => 'green']
                ] as $bot)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <div class="w-3 h-3 rounded-full bg-{{ $bot['color'] }}-500"></div>
                        <div>
                            <p class="font-medium text-gray-900">{{ $bot['name'] }}</p>
                            <p class="text-sm text-gray-500">{{ $bot['pair'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="font-medium {{ strpos($bot['profit'], '+') === 0 ? 'text-green-600' : 'text-red-600' }}">
                            {{ $bot['profit'] }}
                        </p>
                        <x-badge variant="{{ $bot['status'] === 'active' ? 'success' : 'warning' }}" size="sm">
                            {{ ucfirst($bot['status']) }}
                        </x-badge>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <x-button variant="outline" class="w-full">
                    View All Bots
                </x-button>
            </div>
        </x-card>

        <!-- Recent Signals -->
        <x-card title="Recent Trading Signals" subtitle="Latest market analysis results">
            <div class="space-y-4">
                @foreach([
                    ['pair' => 'BTC/USDT', 'signal' => 'BUY', 'price' => '$42,350', 'confidence' => '92%', 'time' => '2 min ago'],
                    ['pair' => 'ETH/USDT', 'signal' => 'HOLD', 'price' => '$2,680', 'confidence' => '78%', 'time' => '5 min ago'],
                    ['pair' => 'ADA/USDT', 'signal' => 'SELL', 'price' => '$0.485', 'confidence' => '85%', 'time' => '8 min ago'],
                    ['pair' => 'SOL/USDT', 'signal' => 'BUY', 'price' => '$98.50', 'confidence' => '88%', 'time' => '12 min ago']
                ] as $signal)
                <div class="flex items-center justify-between p-3 bg-gray-50 rounded-lg">
                    <div class="flex items-center space-x-3">
                        <x-badge 
                            variant="{{ $signal['signal'] === 'BUY' ? 'success' : ($signal['signal'] === 'SELL' ? 'danger' : 'warning') }}"
                            size="sm"
                        >
                            {{ $signal['signal'] }}
                        </x-badge>
                        <div>
                            <p class="font-medium text-gray-900">{{ $signal['pair'] }}</p>
                            <p class="text-sm text-gray-500">{{ $signal['price'] }} • {{ $signal['confidence'] }}</p>
                        </div>
                    </div>
                    <div class="text-right">
                        <p class="text-sm text-gray-500">{{ $signal['time'] }}</p>
                    </div>
                </div>
                @endforeach
            </div>
            
            <div class="mt-4 pt-4 border-t border-gray-200">
                <x-button variant="outline" class="w-full">
                    Generate New Signal
                </x-button>
            </div>
        </x-card>
    </div>

    <!-- Market Overview -->
    <x-card title="Market Overview" subtitle="Top cryptocurrency performance">
        <x-table :headers="['Cryptocurrency', 'Price', '24h Change', 'Volume', 'Market Cap', 'Actions']">
            @foreach([
                ['symbol' => 'BTC', 'name' => 'Bitcoin', 'price' => '$42,350.00', 'change' => '+2.45%', 'volume' => '$28.5B', 'cap' => '$830.2B', 'trend' => 'up'],
                ['symbol' => 'ETH', 'name' => 'Ethereum', 'price' => '$2,680.50', 'change' => '+1.85%', 'volume' => '$15.2B', 'cap' => '$322.4B', 'trend' => 'up'],
                ['symbol' => 'ADA', 'name' => 'Cardano', 'price' => '$0.485', 'change' => '-0.75%', 'volume' => '$890M', 'cap' => '$17.2B', 'trend' => 'down'],
                ['symbol' => 'SOL', 'name' => 'Solana', 'price' => '$98.50', 'change' => '+5.20%', 'volume' => '$2.1B', 'cap' => '$42.8B', 'trend' => 'up'],
                ['symbol' => 'DOT', 'name' => 'Polkadot', 'price' => '$7.45', 'change' => '-1.20%', 'volume' => '$450M', 'cap' => '$9.1B', 'trend' => 'down']
            ] as $crypto)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="w-8 h-8 bg-gray-200 rounded-full flex items-center justify-center mr-3">
                            <span class="text-xs font-bold">{{ $crypto['symbol'] }}</span>
                        </div>
                        <div>
                            <div class="text-sm font-medium text-gray-900">{{ $crypto['name'] }}</div>
                            <div class="text-sm text-gray-500">{{ $crypto['symbol'] }}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $crypto['price'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="inline-flex items-center text-sm {{ $crypto['trend'] === 'up' ? 'text-green-600' : 'text-red-600' }}">
                        @if($crypto['trend'] === 'up')
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                            </svg>
                        @else
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                            </svg>
                        @endif
                        {{ $crypto['change'] }}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $crypto['volume'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $crypto['cap'] }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-button variant="outline" size="sm">Trade</x-button>
                </td>
            </tr>
            @endforeach
        </x-table>
    </x-card>

    <!-- Recent Activity -->
    <x-card title="Recent Activity" subtitle="Latest trades and bot actions">
        <div class="flow-root">
            <ul class="-mb-8">
                @foreach([
                    ['action' => 'Bot executed BUY order', 'details' => 'BTC Scalper Pro bought 0.025 BTC at $42,350', 'time' => '2 minutes ago', 'type' => 'trade', 'success' => true],
                    ['action' => 'Signal generated', 'details' => 'Strong BUY signal for SOL/USDT with 88% confidence', 'time' => '12 minutes ago', 'type' => 'signal', 'success' => true],
                    ['action' => 'Bot paused', 'details' => 'Altcoin Grid bot automatically paused due to market volatility', 'time' => '25 minutes ago', 'type' => 'system', 'success' => false],
                    ['action' => 'Profit target reached', 'details' => 'ETH DCA Bot reached 15% profit target and closed position', 'time' => '1 hour ago', 'type' => 'trade', 'success' => true]
                ] as $index => $activity)
                <li>
                    <div class="relative pb-8">
                        @if($index < 3)
                            <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                        @endif
                        <div class="relative flex space-x-3">
                            <div>
                                <span class="h-8 w-8 rounded-full {{ $activity['success'] ? 'bg-green-500' : 'bg-red-500' }} flex items-center justify-center ring-8 ring-white">
                                    @if($activity['type'] === 'trade')
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1"></path>
                                        </svg>
                                    @elseif($activity['type'] === 'signal')
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                                        </svg>
                                    @else
                                        <svg class="h-5 w-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3m0 0v3m0-3h3m-3 0H9m12 0a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                        </svg>
                                    @endif
                                </span>
                            </div>
                            <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                <div>
                                    <p class="text-sm text-gray-900">{{ $activity['action'] }}</p>
                                    <p class="text-sm text-gray-500">{{ $activity['details'] }}</p>
                                </div>
                                <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                    {{ $activity['time'] }}
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
                @endforeach
            </ul>
        </div>
        
        <div class="mt-6 pt-4 border-t border-gray-200">
            <x-button variant="outline" class="w-full">
                View Full Activity Log
            </x-button>
        </div>
    </x-card>
</div>

<!-- Toast notifications for live updates -->
<div x-data="{ showToast: false, toastMessage: '', toastType: 'info' }" 
     @trading-update.window="showToast = true; toastMessage = $event.detail.message; toastType = $event.detail.type; setTimeout(() => showToast = false, 5000)">
    <x-toast x-show="showToast" :type="toastType" x-text="toastMessage"></x-toast>
</div>

@push('scripts')
<script>
// Simulate real-time updates
setInterval(() => {
    // Simulate random price updates
    const cryptos = ['BTC', 'ETH', 'ADA', 'SOL', 'DOT'];
    const randomCrypto = cryptos[Math.floor(Math.random() * cryptos.length)];
    const priceChange = (Math.random() - 0.5) * 100;
    
    if (Math.random() > 0.7) { // 30% chance of showing notification
        window.dispatchEvent(new CustomEvent('trading-update', {
            detail: {
                message: `${randomCrypto} price ${priceChange > 0 ? 'increased' : 'decreased'} by ${Math.abs(priceChange).toFixed(2)}%`,
                type: priceChange > 0 ? 'success' : 'warning'
            }
        }));
    }
}, 10000); // Every 10 seconds
</script>
@endpush
@endsection
