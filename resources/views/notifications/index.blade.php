@extends('layouts.app')

@section('title', 'Notifications')

@section('breadcrumbs')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary">
                    <i class="fas fa-home mr-1"></i> Home
                </a>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                    <span class="text-gray-500">Notifications</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="flex flex-wrap justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Your Notifications</h1>
        <div class="flex space-x-2 mt-2 sm:mt-0">
            @if($countsInfo['unread'] > 0)
                <form action="{{ route('notifications.mark-all-read') }}" method="POST">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-primary text-white rounded-lg hover:bg-primary-dark transition-colors shadow-sm flex items-center">
                        <i class="fas fa-check-double mr-2"></i> Mark all as read
                    </button>
                </form>
            @endif
            
            @if($countsInfo['all'] > 0)
                <form action="{{ route('notifications.clear-all') }}" method="POST" onsubmit="return confirm('Are you sure you want to delete all notifications?')">
                    @csrf
                    <button type="submit" class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors shadow-sm flex items-center">
                        <i class="fas fa-trash-alt mr-2"></i> Clear all
                    </button>
                </form>
            @endif
        </div>
    </div>

    <!-- Notification Filters -->
    <div class="bg-white rounded-lg shadow-sm mb-6 p-4">
        <div class="flex flex-wrap gap-4">
            <a href="{{ route('notifications.index') }}" 
               class="px-4 py-2 rounded-full transition-colors duration-200 {{ request()->get('filter', 'all') === 'all' ? 'bg-primary text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                All <span class="ml-1 {{ request()->get('filter', 'all') === 'all' ? 'bg-white text-primary' : 'bg-gray-200' }} text-xs py-0.5 px-2 rounded-full">{{ $countsInfo['all'] }}</span>
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'unread']) }}" 
               class="px-4 py-2 rounded-full transition-colors duration-200 {{ request()->get('filter') === 'unread' ? 'bg-primary text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Unread <span class="ml-1 {{ request()->get('filter') === 'unread' ? 'bg-white text-primary' : 'bg-gray-200' }} text-xs py-0.5 px-2 rounded-full">{{ $countsInfo['unread'] }}</span>
            </a>
            <a href="{{ route('notifications.index', ['filter' => 'read']) }}" 
               class="px-4 py-2 rounded-full transition-colors duration-200 {{ request()->get('filter') === 'read' ? 'bg-primary text-white font-medium shadow-sm' : 'bg-gray-100 text-gray-700 hover:bg-gray-200' }}">
                Read <span class="ml-1 {{ request()->get('filter') === 'read' ? 'bg-white text-primary' : 'bg-gray-200' }} text-xs py-0.5 px-2 rounded-full">{{ $countsInfo['read'] }}</span>
            </a>
        </div>
    </div>

    <!-- Notifications List -->
    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        @if($notifications->count() > 0)
            <div class="divide-y divide-gray-100">
                @foreach($notifications as $notification)
                    <div class="p-5 hover:bg-gray-50 transition-colors duration-200 {{ $notification->read_at ? 'bg-white' : 'bg-blue-50' }}">
                        <div class="flex flex-col sm:flex-row">
                            <div class="flex-shrink-0 mb-3 sm:mb-0 sm:mr-4">
                                @if($notification->type === 'order')
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100">
                                        <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                                    </span>
                                @elseif($notification->type === 'system')
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100">
                                        <i class="fas fa-cog text-blue-600 text-xl"></i>
                                    </span>
                                @elseif($notification->type === 'promotion')
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-purple-100">
                                        <i class="fas fa-gift text-purple-600 text-xl"></i>
                                    </span>
                                @else
                                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100">
                                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                                    </span>
                                @endif
                            </div>
                            
                            <div class="flex-1">
                                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-2">
                                    <a href="{{ route('notifications.show', $notification) }}" class="text-lg font-semibold text-gray-900 hover:text-primary transition-colors">
                                        {{ $notification->title }}
                                        @if(!$notification->read_at)
                                            <span class="inline-block w-2 h-2 bg-primary rounded-full ml-2 align-middle"></span>
                                        @endif
                                    </a>
                                    <div class="flex items-center mt-1 sm:mt-0">
                                        <span class="text-xs text-gray-500 bg-gray-100 px-2 py-1 rounded-full">
                                            <i class="far fa-clock mr-1"></i>
                                            {{ $notification->created_at->diffForHumans() }}
                                        </span>
                                    </div>
                                </div>
                                <p class="text-gray-600 mb-4">{{ Str::limit($notification->message, 150) }}</p>
                                
                                <div class="flex flex-wrap items-center justify-between mt-2">
                                    <div>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                            {{ $notification->type === 'order' ? 'bg-green-100 text-green-800' : 
                                            ($notification->type === 'system' ? 'bg-blue-100 text-blue-800' : 
                                            ($notification->type === 'promotion' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) }}">
                                            {{ ucfirst($notification->type) }}
                                        </span>
                                    </div>
                                    
                                    <div class="flex mt-3 sm:mt-0">
                                        @if(!$notification->read_at)
                                            <form action="{{ route('notifications.mark-read', $notification) }}" method="POST" class="mr-2">
                                                @csrf
                                                <button type="submit" class="text-sm text-primary hover:text-primary-dark transition-colors">
                                                    <i class="fas fa-check mr-1"></i> Mark read
                                                </button>
                                            </form>
                                        @endif
                                        
                                        <a href="{{ route('notifications.show', $notification) }}" class="text-sm text-primary hover:text-primary-dark transition-colors mr-3">
                                            <i class="fas fa-eye mr-1"></i> View
                                        </a>
                                        
                                        <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-sm text-red-600 hover:text-red-800 transition-colors">
                                                <i class="fas fa-trash-alt mr-1"></i> Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
            
            <div class="p-4 border-t border-gray-200">
                {{ $notifications->links() }}
            </div>
        @else
            <div class="p-10 text-center">
                <div class="inline-flex items-center justify-center h-20 w-20 rounded-full bg-gray-100 mb-6">
                    <i class="fas fa-bell text-gray-400 text-3xl"></i>
                </div>
                <h3 class="text-lg font-medium text-gray-900 mb-1">No notifications</h3>
                <p class="text-gray-500 max-w-md mx-auto">You don't have any notifications at the moment. We'll notify you when there are updates or important messages.</p>
            </div>
        @endif
    </div>
</div>
@endsection 