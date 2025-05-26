@extends('layouts.app')

@section('header')
    <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        {{ __('Component Examples') }}
    </h2>
@endsection

@section('content')
<div class="space-y-8">
    <!-- Alert Examples -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Alert Components</h3>
        <div class="space-y-4">
            <x-alert type="success" title="Success!" dismissible>
                Your data has been saved successfully.
            </x-alert>
            
            <x-alert type="warning" title="Warning">
                Please review your information before proceeding.
            </x-alert>
            
            <x-alert type="error" title="Error occurred">
                There was a problem processing your request.
            </x-alert>
            
            <x-alert type="info">
                This is an informational message without a title.
            </x-alert>
        </div>
    </section>

    <!-- Button Examples -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Button Components</h3>
        <div class="flex flex-wrap gap-4">
            <x-button variant="primary">Primary Button</x-button>
            <x-button variant="secondary">Secondary Button</x-button>
            <x-button variant="success">Success Button</x-button>
            <x-button variant="danger">Danger Button</x-button>
            <x-button variant="warning">Warning Button</x-button>
            <x-button variant="info">Info Button</x-button>
            <x-button variant="outline">Outline Button</x-button>
        </div>
        
        <div class="flex flex-wrap gap-4 mt-4">
            <x-button variant="primary" size="sm">Small</x-button>
            <x-button variant="primary" size="md">Medium</x-button>
            <x-button variant="primary" size="lg">Large</x-button>
        </div>
        
        <div class="flex flex-wrap gap-4 mt-4">
            <x-button variant="primary" loading>Loading...</x-button>
            <x-button variant="secondary" disabled>Disabled</x-button>
        </div>
    </section>

    <!-- Badge Examples -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Badge Components</h3>
        <div class="flex flex-wrap gap-4">
            <x-badge variant="primary">Primary</x-badge>
            <x-badge variant="secondary">Secondary</x-badge>
            <x-badge variant="success">Success</x-badge>
            <x-badge variant="danger">Danger</x-badge>
            <x-badge variant="warning">Warning</x-badge>
            <x-badge variant="info">Info</x-badge>
            <x-badge variant="light">Light</x-badge>
            <x-badge variant="dark">Dark</x-badge>
        </div>
        
        <div class="flex flex-wrap gap-4 mt-4">
            <x-badge variant="primary" size="sm">Small</x-badge>
            <x-badge variant="primary" size="md">Medium</x-badge>
            <x-badge variant="primary" size="lg">Large</x-badge>
        </div>
    </section>

    <!-- Card Examples -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Card Components</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <x-card title="Simple Card" subtitle="This is a basic card">
                <p class="text-gray-600">Card content goes here.</p>
            </x-card>
            
            <x-card title="Custom Card" background="bg-blue-50" class="border-blue-200">
                <p class="text-blue-700">This card has custom styling.</p>
            </x-card>
            
            <x-card>
                <h4 class="font-semibold mb-2">Card without predefined title</h4>
                <p class="text-gray-600">You can add your own title structure.</p>
            </x-card>
        </div>
    </section>

    <!-- Form Examples -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Form Components</h3>
        <x-card title="Form Example">
            <form class="space-y-4">
                <x-form.input 
                    label="Full Name" 
                    name="name" 
                    placeholder="Enter your full name"
                    required
                />
                
                <x-form.input 
                    label="Email" 
                    name="email" 
                    type="email"
                    placeholder="Enter your email"
                    help="We'll never share your email with anyone."
                    required
                />
                
                <x-form.select 
                    label="Country" 
                    name="country"
                    :options="[
                        'id' => 'Indonesia',
                        'us' => 'United States',
                        'uk' => 'United Kingdom',
                        'au' => 'Australia'
                    ]"
                    required
                />
                
                <x-form.input 
                    label="Password" 
                    name="password" 
                    type="password"
                    placeholder="Enter your password"
                    required
                />
                
                <div class="flex justify-end">
                    <x-button variant="primary" type="submit">
                        Submit Form
                    </x-button>
                </div>
            </form>
        </x-card>
    </section>

    <!-- Additional Form Components -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Additional Form Components</h3>
        <x-card title="Advanced Form Examples">
            <form class="space-y-6">
                <!-- Textarea -->
                <x-form.textarea 
                    label="Description" 
                    name="description" 
                    placeholder="Enter a detailed description..."
                    help="Please provide as much detail as possible."
                    rows="4"
                    required
                />
                
                <!-- Checkbox Examples -->
                <div class="space-y-4">
                    <h4 class="font-medium text-gray-900">Preferences</h4>
                    
                    <x-form.checkbox 
                        label="Email Notifications" 
                        name="email_notifications"
                        value="1"
                        help="Receive email updates about your trading activity"
                    />
                    
                    <x-form.checkbox 
                        name="terms"
                        value="1"
                        required
                    >
                        I agree to the <a href="#" class="text-blue-600 hover:underline">Terms and Conditions</a> and <a href="#" class="text-blue-600 hover:underline">Privacy Policy</a>
                    </x-form.checkbox>
                </div>
                
                <!-- Radio Button Examples -->
                <x-form.radio 
                    label="Trading Experience Level" 
                    name="experience"
                    :options="
                        [
                            'beginner' => 'Beginner (0-1 years)',
                            'intermediate' => 'Intermediate (1-3 years)',
                            'advanced' => 'Advanced (3+ years)',
                            'expert' => 'Expert (Professional trader)'
                        ]
                    "
                    help="This helps us customize your experience"
                    required
                />
                
                <!-- Radio with Different Layout -->
                <x-form.radio 
                    label="Preferred Trading Style" 
                    name="trading_style"
                    :options="
                        [
                            'scalping' => 'Scalping',
                            'day_trading' => 'Day Trading',
                            'swing_trading' => 'Swing Trading',
                            'hodl' => 'HODL'
                        ]
                    "
                    layout="horizontal"
                    required
                />
                
                <!-- File Upload Examples -->
                <x-form.file 
                    label="Profile Picture" 
                    name="avatar"
                    accept="image/*"
                    maxSize="2MB"
                    help="Upload a profile picture (JPG, PNG, GIF - Max 2MB)"
                />
                
                <x-form.file 
                    label="Trading Documents" 
                    name="documents"
                    multiple
                    accept=".pdf,.doc,.docx"
                    maxSize="10MB"
                    help="Upload multiple documents (PDF, DOC, DOCX - Max 10MB each)"
                    :preview="false"
                />
                
                <div class="flex justify-end space-x-3">
                    <x-button variant="outline" type="button">
                        Reset Form
                    </x-button>
                    <x-button variant="primary" type="submit">
                        Save Settings
                    </x-button>
                </div>
            </form>
        </x-card>
    </section>

    <!-- Table Example -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Table Component</h3>
        <x-table :headers="['Name', 'Email', 'Role', 'Status', 'Actions']">
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">John Doe</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">john@example.com</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">Admin</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge variant="success">Active</x-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-button variant="outline" size="sm">Edit</x-button>
                </td>
            </tr>
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">Jane Smith</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">jane@example.com</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">User</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <x-badge variant="warning">Pending</x-badge>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <x-button variant="outline" size="sm">Edit</x-button>
                </td>
            </tr>
        </x-table>
    </section>

    <!-- Empty State Example -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Empty State Component</h3>
        <x-card>
            <x-empty-state 
                title="No trading bots found"
                description="Get started by creating your first automated trading bot."
                icon="chart"
                actionText="Create Trading Bot"
                actionUrl="#"
            />
        </x-card>
    </section>

    <!-- Form Wizard Example -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Form Wizard Component</h3>
        <x-form-wizard 
            :steps="['Basic Info', 'Configuration', 'Review', 'Complete']"
            :currentStep="2"
            title="Create New Trading Bot"
        >
            <div class="text-center py-8">
                <h4 class="text-lg font-semibold mb-2">Step 2: Configuration</h4>
                <p class="text-gray-600">Configure your trading bot settings here.</p>
                
                <div class="flex justify-between mt-8">
                    <x-button variant="outline">Previous</x-button>
                    <x-button variant="primary">Next Step</x-button>
                </div>
            </div>
        </x-form-wizard>
    </section>

    <!-- Modal Trigger Example -->
    <section>
        <h3 class="text-lg font-semibold mb-4">Modal Component</h3>
        <x-button 
            variant="primary" 
            x-on:click="$dispatch('open-modal', 'example-modal')"
        >
            Open Modal
        </x-button>

        <x-modal name="example-modal" title="Example Modal">
            <p class="text-gray-600 mb-4">This is an example modal content. You can put any content here.</p>
            
            <div class="flex justify-end space-x-3">
                <x-button variant="outline" x-on:click="$dispatch('close-modal', 'example-modal')">
                    Cancel
                </x-button>
                <x-button variant="primary">
                    Save Changes
                </x-button>
            </div>
        </x-modal>
    </section>
</div>
@endsection
