@props(['notifications' => [], 'unreadCount' => 0])

<div class="relative" x-data="{ open: false }">
    <button @click="open = !open" class="relative p-1 text-gray-600 hover:text-primary focus:outline-none">
        <i class="fas fa-bell text-xl"></i>
        @if($unreadCount > 0)
            <span class="notification-badge">
                {{ $unreadCount > 9 ? '9+' : $unreadCount }}
            </span>
        @endif
    </button>
    
    <!-- Notification Dropdown -->
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 transform scale-95"
         x-transition:enter-end="opacity-100 transform scale-100"
         x-transition:leave="transition ease-in duration-100"
         x-transition:leave-start="opacity-100 transform scale-100"
         x-transition:leave-end="opacity-0 transform scale-95"
         @click.away="open = false" 
         class="absolute right-0 mt-2 w-80 bg-white rounded-md shadow-lg py-1 z-50"
         style="max-height: 80vh; overflow-y: auto; width: 320px;">
        <div class="px-4 py-2 border-b sticky top-0 bg-white z-10">
            <div class="flex justify-between items-center">
                <h3 class="font-semibold">Notifications</h3>
                <a href="{{ route('notifications.index') }}" class="text-sm text-primary hover:underline">View All</a>
            </div>
        </div>
        
        <div class="max-h-[calc(80vh-60px)] overflow-y-auto">
            @forelse($notifications as $notification)
                <a href="{{ route('notifications.show', $notification) }}" class="block px-4 py-3 hover:bg-gray-50 transition-colors duration-200 {{ !$notification->read_at ? 'bg-blue-50' : '' }} border-b border-gray-100">
                    <div class="flex">
                        <div class="flex-shrink-0 mr-3">
                            @if($notification->type === 'order')
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-green-100">
                                    <i class="fas fa-shopping-bag text-green-600"></i>
                                </span>
                            @elseif($notification->type === 'system')
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-blue-100">
                                    <i class="fas fa-cog text-blue-600"></i>
                                </span>
                            @elseif($notification->type === 'promotion')
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-purple-100">
                                    <i class="fas fa-gift text-purple-600"></i>
                                </span>
                            @else
                                <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-gray-100">
                                    <i class="fas fa-bell text-gray-600"></i>
                                </span>
                            @endif
                        </div>
                        <div class="flex-1">
                            <p class="font-medium text-gray-800 line-clamp-1">{{ $notification->title }}</p>
                            <p class="text-sm text-gray-500 line-clamp-2">{{ $notification->message }}</p>
                            <p class="text-xs text-gray-400 mt-1">{{ $notification->created_at->diffForHumans() }}</p>
                        </div>
                    </div>
                </a>
            @empty
                <div class="px-4 py-8 text-sm text-gray-500 text-center">
                    <i class="fas fa-bell text-gray-300 text-2xl mb-2 block"></i>
                    <p>No notifications yet</p>
                </div>
            @endforelse
        </div>
        
        @if($notifications->count() > 0)
            <div class="px-4 py-2 border-t sticky bottom-0 bg-white z-10">
                <div class="flex justify-between">
                    @if($unreadCount > 0)
                        <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                            @csrf
                            <button type="submit" class="text-sm text-primary hover:underline focus:outline-none">
                                <i class="fas fa-check-double mr-1"></i> Mark all as read
                            </button>
                        </form>
                    @endif
                    <form action="{{ route('notifications.clear-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all notifications?')">
                        @csrf
                        <button type="submit" class="text-sm text-red-600 hover:underline focus:outline-none">
                            <i class="fas fa-trash-alt mr-1"></i> Clear all
                        </button>
                    </form>
                </div>
            </div>
        @endif
    </div>
</div> 