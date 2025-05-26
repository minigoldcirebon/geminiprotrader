@props([
    'steps' => [],
    'currentStep' => 1,
    'title' => null
])

<div class="max-w-4xl mx-auto">
    @if($title)
        <div class="text-center mb-8">
            <h2 class="text-2xl font-bold text-gray-900">{{ $title }}</h2>
        </div>
    @endif

    <!-- Step Navigation -->
    <nav aria-label="Progress" class="mb-8">
        <ol class="flex items-center">
            @foreach($steps as $index => $step)
                @php
                    $stepNumber = $index + 1;
                    $isCompleted = $stepNumber < $currentStep;
                    $isCurrent = $stepNumber == $currentStep;
                @endphp
                
                <li class="relative {{ $loop->last ? '' : 'pr-8 sm:pr-20' }} flex-1">
                    @if(!$loop->last)
                        <!-- Connector line -->
                        <div class="absolute inset-0 flex items-center" aria-hidden="true">
                            <div class="h-0.5 w-full {{ $isCompleted ? 'bg-blue-600' : 'bg-gray-200' }}"></div>
                        </div>
                    @endif
                    
                    <div class="relative flex items-center justify-center">
                        @if($isCompleted)
                            <!-- Completed step -->
                            <div class="h-8 w-8 rounded-full bg-blue-600 flex items-center justify-center">
                                <svg class="h-5 w-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        @elseif($isCurrent)
                            <!-- Current step -->
                            <div class="h-8 w-8 rounded-full border-2 border-blue-600 bg-white flex items-center justify-center">
                                <span class="text-blue-600 font-medium text-sm">{{ $stepNumber }}</span>
                            </div>
                        @else
                            <!-- Upcoming step -->
                            <div class="h-8 w-8 rounded-full border-2 border-gray-300 bg-white flex items-center justify-center">
                                <span class="text-gray-500 font-medium text-sm">{{ $stepNumber }}</span>
                            </div>
                        @endif
                    </div>
                    
                    <div class="mt-3 text-center">
                        <span class="text-sm font-medium {{ $isCurrent ? 'text-blue-600' : ($isCompleted ? 'text-blue-600' : 'text-gray-500') }}">
                            {{ $step }}
                        </span>
                    </div>
                </li>
            @endforeach
        </ol>
    </nav>

    <!-- Step Content -->
    <div class="bg-white shadow rounded-lg p-6">
        {{ $slot }}
    </div>
</div>
