@props([
    'position' => 'top-right', // top-right, top-left, bottom-right, bottom-left, top-center, bottom-center
    'maxNotifications' => 5
])

@php
$positionClasses = [
    'top-right' => 'top-4 right-4',
    'top-left' => 'top-4 left-4',
    'bottom-right' => 'bottom-4 right-4',
    'bottom-left' => 'bottom-4 left-4',
    'top-center' => 'top-4 left-1/2 transform -translate-x-1/2',
    'bottom-center' => 'bottom-4 left-1/2 transform -translate-x-1/2'
];
$positionClass = $positionClasses[$position] ?? $positionClasses['top-right'];
@endphp

<div 
    x-data="notificationSystem({{ $maxNotifications }})"
    x-init="init()"
    class="fixed {{ $positionClass }} z-50 space-y-2"
    style="max-width: 400px;"
>
    <template x-for="notification in notifications" :key="notification.id">
        <div 
            x-show="notification.show"
            x-transition:enter="transform transition ease-out duration-300"
            x-transition:enter-start="translate-x-full opacity-0"
            x-transition:enter-end="translate-x-0 opacity-100"
            x-transition:leave="transform transition ease-in duration-300"
            x-transition:leave-start="translate-x-0 opacity-100"
            x-transition:leave-end="translate-x-full opacity-0"
            class="relative bg-white rounded-lg shadow-lg border border-gray-200 p-4 max-w-sm w-full"
            :class="{
                'border-l-4 border-l-green-500': notification.type === 'success',
                'border-l-4 border-l-red-500': notification.type === 'error',
                'border-l-4 border-l-yellow-500': notification.type === 'warning',
                'border-l-4 border-l-blue-500': notification.type === 'info'
            }"
        >
            <div class="flex items-start">
                <!-- Icon -->
                <div class="flex-shrink-0">
                    <template x-if="notification.type === 'success'">
                        <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>
                    
                    <template x-if="notification.type === 'error'">
                        <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </template>
                    
                    <template x-if="notification.type === 'warning'">
                        <svg class="h-5 w-5 text-yellow-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.732-.833-2.5 0L4.268 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                        </svg>
                    </template>
                    
                    <template x-if="notification.type === 'info'">
                        <svg class="h-5 w-5 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </template>
                </div>
                
                <!-- Content -->
                <div class="ml-3 flex-1">
                    <div x-show="notification.title" class="text-sm font-medium text-gray-900 mb-1" x-text="notification.title"></div>
                    <div class="text-sm text-gray-700" x-text="notification.message"></div>
                    
                    <!-- Actions -->
                    <div x-show="notification.actions && notification.actions.length > 0" class="mt-3 flex space-x-2">
                        <template x-for="action in notification.actions" :key="action.text">
                            <button 
                                @click="action.handler(); removeNotification(notification.id)"
                                class="text-sm font-medium text-blue-600 hover:text-blue-500"
                                x-text="action.text"
                            ></button>
                        </template>
                    </div>
                    
                    <!-- Progress bar for auto-dismiss -->
                    <div x-show="notification.autoDismiss && notification.duration > 0" class="mt-2">
                        <div class="w-full bg-gray-200 rounded-full h-1">
                            <div 
                                class="h-1 rounded-full transition-all ease-linear"
                                :class="{
                                    'bg-green-500': notification.type === 'success',
                                    'bg-red-500': notification.type === 'error',
                                    'bg-yellow-500': notification.type === 'warning',
                                    'bg-blue-500': notification.type === 'info'
                                }"
                                :style="'width: ' + (notification.progress || 100) + '%'"
                            ></div>
                        </div>
                    </div>
                </div>
                
                <!-- Close button -->
                <div class="ml-4 flex-shrink-0">
                    <button 
                        @click="removeNotification(notification.id)"
                        class="rounded-md text-gray-400 hover:text-gray-600 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    >
                        <span class="sr-only">Close</span>
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </template>
</div>

<script>
function notificationSystem(maxNotifications = 5) {
    return {
        notifications: [],
        nextId: 1,
        maxNotifications: maxNotifications,
        
        init() {
            // Listen for notification events
            window.addEventListener('show-notification', (event) => {
                this.addNotification(event.detail);
            });
            
            // Legacy support for 'show-toast' event
            window.addEventListener('show-toast', (event) => {
                this.addNotification(event.detail);
            });
            
            // Listen for custom notification events
            window.addEventListener('notification', (event) => {
                this.addNotification(event.detail);
            });
        },
        
        addNotification(options) {
            const notification = {
                id: this.nextId++,
                type: options.type || 'info',
                title: options.title || '',
                message: options.message || '',
                duration: options.duration || (options.autoDismiss !== false ? 5000 : 0),
                autoDismiss: options.autoDismiss !== false,
                actions: options.actions || [],
                show: true,
                progress: 100
            };
            
            // Remove excess notifications if we're at the limit
            if (this.notifications.length >= this.maxNotifications) {
                this.notifications.shift();
            }
            
            this.notifications.push(notification);
            
            // Auto-dismiss logic
            if (notification.autoDismiss && notification.duration > 0) {
                let elapsed = 0;
                const interval = 50; // Update every 50ms for smooth progress
                
                const timer = setInterval(() => {
                    elapsed += interval;
                    notification.progress = Math.max(0, 100 - (elapsed / notification.duration * 100));
                    
                    if (elapsed >= notification.duration) {
                        clearInterval(timer);
                        this.removeNotification(notification.id);
                    }
                }, interval);
            }
        },
        
        removeNotification(id) {
            const index = this.notifications.findIndex(n => n.id === id);
            if (index > -1) {
                this.notifications[index].show = false;
                // Remove from array after transition
                setTimeout(() => {
                    const currentIndex = this.notifications.findIndex(n => n.id === id);
                    if (currentIndex > -1) {
                        this.notifications.splice(currentIndex, 1);
                    }
                }, 300);
            }
        },
        
        clearAll() {
            this.notifications.forEach(notification => {
                notification.show = false;
            });
            setTimeout(() => {
                this.notifications = [];
            }, 300);
        }
    };
}

// Global helper functions
window.showNotification = function(options) {
    window.dispatchEvent(new CustomEvent('show-notification', { detail: options }));
};

window.showToast = function(message, type = 'info', options = {}) {
    window.showNotification({
        message: message,
        type: type,
        ...options
    });
};

window.showSuccess = function(message, title = '', options = {}) {
    window.showNotification({
        message: message,
        title: title,
        type: 'success',
        ...options
    });
};

window.showError = function(message, title = '', options = {}) {
    window.showNotification({
        message: message,
        title: title,
        type: 'error',
        ...options
    });
};

window.showWarning = function(message, title = '', options = {}) {
    window.showNotification({
        message: message,
        title: title,
        type: 'warning',
        ...options
    });
};

window.showInfo = function(message, title = '', options = {}) {
    window.showNotification({
        message: message,
        title: title,
        type: 'info',
        ...options
    });
};
</script>
