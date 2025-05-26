@extends('layouts.app')

@section('header')
    <div class="flex justify-between items-center">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            API Documentation & Management
        </h2>
        <div class="flex space-x-3">
            <x-button variant="outline" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Download Postman Collection
            </x-button>
            <x-button variant="primary" size="sm">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m2-2a2 2 0 00-2-2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002-2M9 7a2 2 0 012-2m0 0a2 2 0 00-2 2m0 0a2 2 0 002 2"></path>
                </svg>
                Generate API Key
            </x-button>
        </div>
    </div>
@endsection

@section('content')
<div class="space-y-6">
    <!-- API Overview -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <x-card class="border-l-4 border-l-blue-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">API Calls Today</p>
                    <p class="text-2xl font-bold text-blue-600">2,847</p>
                    <p class="text-sm text-gray-500">of 10,000 limit</p>
                </div>
                <div class="p-3 bg-blue-100 rounded-full">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="border-l-4 border-l-green-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Success Rate</p>
                    <p class="text-2xl font-bold text-green-600">99.2%</p>
                    <p class="text-sm text-green-600">Excellent uptime</p>
                </div>
                <div class="p-3 bg-green-100 rounded-full">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </x-card>

        <x-card class="border-l-4 border-l-purple-500">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm font-medium text-gray-600">Active Keys</p>
                    <p class="text-2xl font-bold text-purple-600">3</p>
                    <p class="text-sm text-gray-500">of 5 allowed</p>
                </div>
                <div class="p-3 bg-purple-100 rounded-full">
                    <svg class="w-6 h-6 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 7a2 2 0 012 2m0 0a2 2 0 012 2m-2-2a2 2 0 00-2 2m2-2a2 2 0 00-2-2M9 7a2 2 0 00-2 2m0 0a2 2 0 002 2m0 0a2 2 0 002-2M9 7a2 2 0 012-2m0 0a2 2 0 00-2 2m0 0a2 2 0 002 2"></path>
                    </svg>
                </div>
            </div>
        </x-card>
    </div>

    <!-- API Key Management -->
    <x-card title="API Key Management" subtitle="Manage your API keys and access tokens">
        <div class="space-y-4 mb-6">
            @foreach([
                ['name' => 'Production API Key', 'key' => 'gp_prod_1234567890abcdef', 'created' => '2024-01-15', 'last_used' => '2 minutes ago', 'calls' => '1,247', 'status' => 'active'],
                ['name' => 'Development API Key', 'key' => 'gp_dev_abcdef1234567890', 'created' => '2024-01-10', 'last_used' => '1 hour ago', 'calls' => '856', 'status' => 'active'],
                ['name' => 'Mobile App Key', 'key' => 'gp_mobile_567890abcdef1234', 'created' => '2024-01-05', 'last_used' => '1 day ago', 'calls' => '744', 'status' => 'inactive']
            ] as $apiKey)
            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-lg">
                <div class="flex-1">
                    <div class="flex items-center space-x-3">
                        <h4 class="font-medium text-gray-900">{{ $apiKey['name'] }}</h4>
                        <x-badge 
                            variant="{{ $apiKey['status'] === 'active' ? 'success' : 'secondary' }}" 
                            size="sm"
                        >
                            {{ ucfirst($apiKey['status']) }}
                        </x-badge>
                    </div>
                    <div class="mt-1 flex items-center space-x-4 text-sm text-gray-500">
                        <span>Created: {{ $apiKey['created'] }}</span>
                        <span>•</span>
                        <span>Last used: {{ $apiKey['last_used'] }}</span>
                        <span>•</span>
                        <span>{{ $apiKey['calls'] }} calls today</span>
                    </div>
                    <div class="mt-2 flex items-center space-x-2">
                        <code class="px-2 py-1 bg-gray-200 rounded text-sm font-mono">{{ $apiKey['key'] }}</code>
                        <x-button variant="outline" size="sm" onclick="copyToClipboard('{{ $apiKey['key'] }}')">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                            </svg>
                        </x-button>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <x-button variant="outline" size="sm">
                        View Details
                    </x-button>
                    <x-button variant="danger" size="sm">
                        Revoke
                    </x-button>
                </div>
            </div>
            @endforeach
        </div>
        
        <x-button variant="primary">
            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
            </svg>
            Generate New API Key
        </x-button>
    </x-card>

    <!-- API Documentation Tabs -->
    <x-card title="API Documentation">
        <div x-data="{ activeTab: 'authentication' }" class="space-y-6">
            <!-- Tab Navigation -->
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    @foreach([
                        'authentication' => 'Authentication',
                        'signals' => 'Trading Signals',
                        'bots' => 'Trading Bots',
                        'portfolio' => 'Portfolio',
                        'webhooks' => 'Webhooks'
                    ] as $tab => $label)
                    <button 
                        @click="activeTab = '{{ $tab }}'"
                        :class="activeTab === '{{ $tab }}' ? 'border-blue-500 text-blue-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300'"
                        class="whitespace-nowrap py-2 px-1 border-b-2 font-medium text-sm"
                    >
                        {{ $label }}
                    </button>
                    @endforeach
                </nav>
            </div>

            <!-- Authentication Tab -->
            <div x-show="activeTab === 'authentication'" class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Authentication</h3>
                    <p class="text-gray-600 mb-4">All API requests require authentication using your API key. Include it in the request headers:</p>
                    
                    <div class="bg-gray-900 rounded-lg p-4 mb-4">
                        <code class="text-green-400 text-sm">
                            Authorization: Bearer YOUR_API_KEY<br>
                            Content-Type: application/json
                        </code>
                    </div>
                    
                    <x-alert type="info" title="Security Note">
                        Never expose your API key in client-side code. Keep it secure and regenerate it if compromised.
                    </x-alert>
                </div>
            </div>

            <!-- Trading Signals Tab -->
            <div x-show="activeTab === 'signals'" class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Trading Signals API</h3>
                    
                    <div class="space-y-6">
                        <!-- Get Signals Endpoint -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <x-badge variant="success" size="sm">GET</x-badge>
                                <code class="text-sm font-mono">/api/v1/signals</code>
                                <span class="text-sm text-gray-500">Get trading signals</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Parameters</h4>
                                    <div class="bg-gray-50 rounded p-3">
                                        <code class="text-sm">
                                            {<br>
                                            &nbsp;&nbsp;"symbol": "BTC/USDT", // Trading pair<br>
                                            &nbsp;&nbsp;"timeframe": "1h", // 1m, 5m, 15m, 1h, 4h, 1d<br>
                                            &nbsp;&nbsp;"analysis_type": "technical" // technical, sentiment, combined<br>
                                            }
                                        </code>
                                    </div>
                                </div>
                                
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Response</h4>
                                    <div class="bg-gray-50 rounded p-3">
                                        <code class="text-sm">
                                            {<br>
                                            &nbsp;&nbsp;"signal": "BUY",<br>
                                            &nbsp;&nbsp;"confidence": 0.92,<br>
                                            &nbsp;&nbsp;"price": 42350.00,<br>
                                            &nbsp;&nbsp;"target": 45000.00,<br>
                                            &nbsp;&nbsp;"stop_loss": 40000.00,<br>
                                            &nbsp;&nbsp;"timestamp": "2024-01-20T10:30:00Z"<br>
                                            }
                                        </code>
                                    </div>
                                </div>
                                
                                <x-button variant="primary" size="sm">Try It Out</x-button>
                            </div>
                        </div>

                        <!-- Generate Signal Endpoint -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <x-badge variant="warning" size="sm">POST</x-badge>
                                <code class="text-sm font-mono">/api/v1/signals/generate</code>
                                <span class="text-sm text-gray-500">Generate new signal</span>
                            </div>
                            
                            <div class="space-y-4">
                                <div>
                                    <h4 class="font-medium text-gray-900 mb-2">Request Body</h4>
                                    <div class="bg-gray-50 rounded p-3">
                                        <code class="text-sm">
                                            {<br>
                                            &nbsp;&nbsp;"symbol": "ETH/USDT",<br>
                                            &nbsp;&nbsp;"timeframe": "4h",<br>
                                            &nbsp;&nbsp;"analysis_type": "combined"<br>
                                            }
                                        </code>
                                    </div>
                                </div>
                                
                                <x-button variant="primary" size="sm">Try It Out</x-button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Trading Bots Tab -->
            <div x-show="activeTab === 'bots'" class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Trading Bots API</h3>
                    
                    <div class="space-y-6">
                        <!-- List Bots -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <x-badge variant="success" size="sm">GET</x-badge>
                                <code class="text-sm font-mono">/api/v1/bots</code>
                                <span class="text-sm text-gray-500">List all trading bots</span>
                            </div>
                            
                            <x-button variant="primary" size="sm">Try It Out</x-button>
                        </div>

                        <!-- Create Bot -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <x-badge variant="warning" size="sm">POST</x-badge>
                                <code class="text-sm font-mono">/api/v1/bots</code>
                                <span class="text-sm text-gray-500">Create new trading bot</span>
                            </div>
                            
                            <x-button variant="primary" size="sm">Try It Out</x-button>
                        </div>

                        <!-- Control Bot -->
                        <div class="border border-gray-200 rounded-lg p-6">
                            <div class="flex items-center space-x-3 mb-4">
                                <x-badge variant="info" size="sm">PUT</x-badge>
                                <code class="text-sm font-mono">/api/v1/bots/{id}/control</code>
                                <span class="text-sm text-gray-500">Start/stop/pause bot</span>
                            </div>
                            
                            <x-button variant="primary" size="sm">Try It Out</x-button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Portfolio Tab -->
            <div x-show="activeTab === 'portfolio'" class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Portfolio API</h3>
                    <p class="text-gray-600 mb-4">Access your portfolio data, balances, and trading history.</p>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <x-badge variant="success" size="sm">GET</x-badge>
                                <code class="text-sm font-mono">/api/v1/portfolio/balance</code>
                            </div>
                            <p class="text-sm text-gray-600">Get account balance</p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <x-badge variant="success" size="sm">GET</x-badge>
                                <code class="text-sm font-mono">/api/v1/portfolio/positions</code>
                            </div>
                            <p class="text-sm text-gray-600">Get open positions</p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <x-badge variant="success" size="sm">GET</x-badge>
                                <code class="text-sm font-mono">/api/v1/portfolio/history</code>
                            </div>
                            <p class="text-sm text-gray-600">Get trading history</p>
                        </div>

                        <div class="border border-gray-200 rounded-lg p-4">
                            <div class="flex items-center space-x-2 mb-2">
                                <x-badge variant="success" size="sm">GET</x-badge>
                                <code class="text-sm font-mono">/api/v1/portfolio/performance</code>
                            </div>
                            <p class="text-sm text-gray-600">Get performance metrics</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Webhooks Tab -->
            <div x-show="activeTab === 'webhooks'" class="space-y-6">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Webhooks</h3>
                    <p class="text-gray-600 mb-4">Configure webhooks to receive real-time notifications about trading events.</p>
                    
                    <div class="space-y-4">
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Supported Events</h4>
                            <ul class="space-y-2 text-sm text-gray-600">
                                <li>• <code>signal.generated</code> - New trading signal created</li>
                                <li>• <code>bot.started</code> - Trading bot started</li>
                                <li>• <code>bot.stopped</code> - Trading bot stopped</li>
                                <li>• <code>trade.executed</code> - Trade executed by bot</li>
                                <li>• <code>portfolio.updated</code> - Portfolio balance changed</li>
                            </ul>
                        </div>
                        
                        <div class="border border-gray-200 rounded-lg p-4">
                            <h4 class="font-medium text-gray-900 mb-2">Webhook Configuration</h4>
                            <div class="bg-gray-50 rounded p-3">
                                <code class="text-sm">
                                    POST /api/v1/webhooks<br>
                                    {<br>
                                    &nbsp;&nbsp;"url": "https://your-app.com/webhook",<br>
                                    &nbsp;&nbsp;"events": ["signal.generated", "trade.executed"],<br>
                                    &nbsp;&nbsp;"secret": "your-webhook-secret"<br>
                                    }
                                </code>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </x-card>

    <!-- Rate Limits -->
    <x-card title="Rate Limits & Usage" subtitle="Monitor your API usage and limits">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <h4 class="font-medium text-gray-900 mb-4">Current Usage</h4>
                <div class="space-y-4">
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Requests per minute</span>
                            <span class="text-sm font-medium">45/100</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-blue-600 h-2 rounded-full" style="width: 45%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Daily requests</span>
                            <span class="text-sm font-medium">2,847/10,000</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-green-600 h-2 rounded-full" style="width: 28.47%"></div>
                        </div>
                    </div>
                    
                    <div>
                        <div class="flex justify-between items-center mb-2">
                            <span class="text-sm text-gray-600">Monthly requests</span>
                            <span class="text-sm font-medium">85,420/500,000</span>
                        </div>
                        <div class="w-full bg-gray-200 rounded-full h-2">
                            <div class="bg-purple-600 h-2 rounded-full" style="width: 17.08%"></div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div>
                <h4 class="font-medium text-gray-900 mb-4">Plan Limits</h4>
                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-600">Rate limit:</span>
                        <span class="font-medium">100 requests/minute</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Daily limit:</span>
                        <span class="font-medium">10,000 requests</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Monthly limit:</span>
                        <span class="font-medium">500,000 requests</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-600">Concurrent connections:</span>
                        <span class="font-medium">10</span>
                    </div>
                </div>
                
                <div class="mt-4">
                    <x-button variant="outline" class="w-full">
                        Upgrade Plan
                    </x-button>
                </div>
            </div>
        </div>
    </x-card>
</div>

@push('scripts')
<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show toast notification
        window.dispatchEvent(new CustomEvent('show-toast', {
            detail: {
                message: 'API key copied to clipboard!',
                type: 'success'
            }
        }));
    }).catch(function() {
        window.dispatchEvent(new CustomEvent('show-toast', {
            detail: {
                message: 'Failed to copy API key',
                type: 'error'
            }
        }));
    });
}
</script>
@endpush
@endsection
