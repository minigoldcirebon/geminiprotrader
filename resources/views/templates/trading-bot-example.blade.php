@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Trading Bot Management') }}
    </h2>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Stats Overview -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <x-card title="Active Bots" class="bg-green-50 border-green-200">
            <div class="text-3xl font-bold text-green-600">{{ $stats['active_bots'] ?? 0 }}</div>
            <p class="text-sm text-green-600">Currently running</p>
        </x-card>

        <x-card title="Total Trades" class="bg-blue-50 border-blue-200">
            <div class="text-3xl font-bold text-blue-600">{{ $stats['total_trades'] ?? 0 }}</div>
            <p class="text-sm text-blue-600">This month</p>
        </x-card>

        <x-card title="Profit/Loss" class="bg-purple-50 border-purple-200">
            <div class="text-3xl font-bold {{ ($stats['profit'] ?? 0) >= 0 ? 'text-green-600' : 'text-red-600' }}">
                ${{ number_format($stats['profit'] ?? 0, 2) }}
            </div>
            <p class="text-sm text-purple-600">Total P&L</p>
        </x-card>

        <x-card title="Success Rate" class="bg-yellow-50 border-yellow-200">
            <div class="text-3xl font-bold text-yellow-600">{{ $stats['success_rate'] ?? 0 }}%</div>
            <p class="text-sm text-yellow-600">Win rate</p>
        </x-card>
    </div>

    <!-- Bot Management -->
    <x-card title="Trading Bots" subtitle="Manage your automated trading bots">
        <div class="flex justify-between items-center mb-6">
            <div class="flex space-x-3">
                <x-button variant="primary" href="{{ route('bots.create') }}">
                    Create New Bot
                </x-button>
                <x-button variant="outline">
                    Import Configuration
                </x-button>
            </div>
            
            <div class="flex space-x-2">
                <x-form.select name="status_filter" placeholder="All Status" :options="[
                    'active' => 'Active',
                    'inactive' => 'Inactive',
                    'paused' => 'Paused'
                ]" />
            </div>
        </div>

        @if($bots->count() > 0)
            <x-table :headers="['Bot Name', 'Strategy', 'Status', 'Performance', 'Last Trade', 'Actions']">
                @foreach($bots as $bot)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="h-8 w-8 bg-blue-500 rounded-full flex items-center justify-center">
                                    <svg class="h-4 w-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                        <path d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <div class="text-sm font-medium text-gray-900">{{ $bot->name }}</div>
                                    <div class="text-sm text-gray-500">{{ $bot->symbol }}</div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            {{ $bot->strategy }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            @if($bot->status === 'active')
                                <x-badge variant="success">Active</x-badge>
                            @elseif($bot->status === 'paused')
                                <x-badge variant="warning">Paused</x-badge>
                            @else
                                <x-badge variant="secondary">Inactive</x-badge>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm {{ $bot->performance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $bot->performance >= 0 ? '+' : '' }}{{ number_format($bot->performance, 2) }}%
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $bot->last_trade_at ? $bot->last_trade_at->diffForHumans() : 'Never' }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                            <div class="flex space-x-2">
                                <x-button variant="outline" size="sm" href="{{ route('bots.show', $bot) }}">
                                    View
                                </x-button>
                                @if($bot->status === 'active')
                                    <x-button variant="warning" size="sm">
                                        Pause
                                    </x-button>
                                @else
                                    <x-button variant="success" size="sm">
                                        Start
                                    </x-button>
                                @endif
                                <x-button variant="danger" size="sm">
                                    Stop
                                </x-button>
                            </div>
                        </td>
                    </tr>
                @endforeach
            </x-table>

            <div class="mt-6">
                {{ $bots->links() }}
            </div>
        @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                </svg>
                <h3 class="mt-2 text-sm font-medium text-gray-900">No trading bots</h3>
                <p class="mt-1 text-sm text-gray-500">Get started by creating your first trading bot.</p>
                <div class="mt-6">
                    <x-button variant="primary" href="{{ route('bots.create') }}">
                        Create Trading Bot
                    </x-button>
                </div>
            </div>
        @endif
    </x-card>

    <!-- Recent Activity -->
    <x-card title="Recent Activity" subtitle="Latest bot actions and trades">
        @if(isset($recentActivity) && $recentActivity->count() > 0)
            <div class="flow-root">
                <ul class="-mb-8">
                    @foreach($recentActivity as $activity)
                        <li>
                            <div class="relative pb-8">
                                <div class="relative flex space-x-3">
                                    <div>
                                        <span class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center ring-8 ring-white">
                                            <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </span>
                                    </div>
                                    <div class="min-w-0 flex-1">
                                        <div>
                                            <div class="text-sm">
                                                <span class="font-medium text-gray-900">{{ $activity->description }}</span>
                                            </div>
                                            <p class="mt-0.5 text-sm text-gray-500">
                                                {{ $activity->created_at->diffForHumans() }}
                                            </p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    @endforeach
                </ul>
            </div>
        @else
            <p class="text-gray-500 text-center py-4">No recent activity</p>
        @endif
    </x-card>
</div>

<!-- Modal untuk konfirmasi aksi -->
<x-modal name="confirm-action" title="Confirm Action">
    <p class="text-sm text-gray-600">Are you sure you want to perform this action?</p>
    
    <div class="mt-6 flex justify-end space-x-3">
        <x-button variant="outline" x-on:click="$dispatch('close-modal', 'confirm-action')">
            Cancel
        </x-button>
        <x-button variant="danger">
            Confirm
        </x-button>
    </div>
</x-modal>
@endsection
