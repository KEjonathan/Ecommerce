@extends('layouts.app')

@section('title', $notification->title)

@section('breadcrumbs')
    <nav class="flex" aria-label="Breadcrumb">
        <ol class="inline-flex items-center space-x-1 md:space-x-3">
            <li class="inline-flex items-center">
                <a href="{{ url('/') }}" class="text-gray-700 hover:text-primary">
                    <i class="fas fa-home mr-1"></i> Home
                </a>
            </li>
            <li>
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                    <a href="{{ route('notifications.index') }}" class="text-gray-700 hover:text-primary">
                        Notifications
                    </a>
                </div>
            </li>
            <li aria-current="page">
                <div class="flex items-center">
                    <i class="fas fa-chevron-right text-gray-400 text-sm mx-2"></i>
                    <span class="text-gray-500">{{ Str::limit($notification->title, 40) }}</span>
                </div>
            </li>
        </ol>
    </nav>
@endsection

@section('content')
<div class="max-w-4xl mx-auto py-6">
    <div class="mb-6 flex items-center">
        <a href="{{ route('notifications.index') }}" class="inline-flex items-center text-primary hover:text-primary-dark transition-colors">
            <i class="fas fa-arrow-left mr-2"></i>
            Back to notifications
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <!-- Notification Header -->
        <div class="px-6 py-5 border-b border-gray-100 flex flex-col sm:flex-row sm:items-center sm:justify-between">
            <div class="flex items-center mb-3 sm:mb-0">
                @if($notification->type === 'order')
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-green-100 mr-4">
                        <i class="fas fa-shopping-bag text-green-600 text-xl"></i>
                    </span>
                @elseif($notification->type === 'system')
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-blue-100 mr-4">
                        <i class="fas fa-cog text-blue-600 text-xl"></i>
                    </span>
                @elseif($notification->type === 'promotion')
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-purple-100 mr-4">
                        <i class="fas fa-gift text-purple-600 text-xl"></i>
                    </span>
                @else
                    <span class="inline-flex items-center justify-center h-12 w-12 rounded-full bg-gray-100 mr-4">
                        <i class="fas fa-bell text-gray-600 text-xl"></i>
                    </span>
                @endif
                <h1 class="text-xl font-bold text-gray-800">{{ $notification->title }}</h1>
            </div>
            <div class="flex items-center">
                <span class="px-3 py-1 rounded-full text-xs font-medium 
                    {{ $notification->read_at ? 'bg-gray-100 text-gray-700' : 'bg-blue-100 text-blue-800' }}">
                    {{ $notification->read_at ? 'Read' : 'Unread' }}
                </span>
            </div>
        </div>

        <!-- Notification Content -->
        <div class="p-6">
            <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 pb-6 border-b border-gray-100">
                <div>
                    <div class="flex items-center text-sm text-gray-500">
                        <i class="far fa-calendar-alt mr-2"></i>
                        <span>{{ $notification->created_at->format('F j, Y \a\t g:i a') }}</span>
                    </div>
                    @if($notification->read_at)
                        <div class="flex items-center text-sm text-gray-500 mt-2">
                            <i class="far fa-eye mr-2"></i>
                            <span>Read: {{ $notification->read_at->format('F j, Y \a\t g:i a') }}</span>
                        </div>
                    @endif
                </div>
                <div class="mt-3 sm:mt-0">
                    <span class="px-3 py-1 rounded-full text-xs font-medium uppercase {{ 
                        $notification->type === 'order' ? 'bg-green-100 text-green-800' : 
                        ($notification->type === 'system' ? 'bg-blue-100 text-blue-800' : 
                        ($notification->type === 'promotion' ? 'bg-purple-100 text-purple-800' : 'bg-gray-100 text-gray-800')) 
                    }}">
                        {{ $notification->type }}
                    </span>
                </div>
            </div>

            <div class="prose max-w-none">
                <p class="text-gray-700 leading-relaxed">{{ $notification->message }}</p>
            </div>

            @if($notification->data && !empty($notification->data))
                <div class="mt-8 border-t border-gray-100 pt-6">
                    <h3 class="text-lg font-medium text-gray-800 mb-4">Additional Information</h3>
                    <div class="bg-gray-50 rounded-lg p-5">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            @foreach($notification->data as $key => $value)
                                @if($key !== 'link' && $key !== 'link_text')
                                    <div class="sm:col-span-1">
                                        <dt class="text-sm font-medium text-gray-500">{{ Str::title(str_replace('_', ' ', $key)) }}</dt>
                                        <dd class="mt-1 text-sm text-gray-900">
                                            @if(is_array($value))
                                                <pre class="bg-gray-100 rounded p-2 text-xs overflow-auto">{{ json_encode($value, JSON_PRETTY_PRINT) }}</pre>
                                            @else
                                                {{ $value }}
                                            @endif
                                        </dd>
                                    </div>
                                @endif
                            @endforeach
                        </dl>
                    </div>
                </div>
            @endif

            <!-- Action Links -->
            @if($notification->data && isset($notification->data['link']))
                <div class="mt-6">
                    <a href="{{ $notification->data['link'] }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-primary hover:bg-primary-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                        <i class="fas fa-external-link-alt mr-2"></i>
                        {{ $notification->data['link_text'] ?? 'View Details' }}
                    </a>
                </div>
            @endif
        </div>

        <!-- Footer with actions -->
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-100 flex flex-col sm:flex-row sm:justify-between sm:items-center">
            <div class="flex space-x-2 mb-3 sm:mb-0">
                @if(!$notification->read_at)
                    <form action="{{ route('notifications.mark-read', $notification) }}" method="POST">
                        @csrf
                        <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary transition-colors">
                            <i class="fas fa-check mr-2"></i> Mark as read
                        </button>
                    </form>
                @endif
            </div>
            <form action="{{ route('notifications.destroy', $notification) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this notification?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-red-600 bg-white hover:bg-red-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 transition-colors">
                    <i class="fas fa-trash-alt mr-2"></i> Delete
                </button>
            </form>
        </div>
    </div>

    <!-- Related Notifications -->
    @if(isset($relatedNotifications) && $relatedNotifications->count() > 0)
        <div class="mt-8">
            <h2 class="text-xl font-bold text-gray-800 mb-4">Related Notifications</h2>
            
            <div class="bg-white rounded-lg shadow-sm overflow-hidden">
                <div class="divide-y divide-gray-100">
                    @foreach($relatedNotifications as $related)
                        <a href="{{ route('notifications.show', $related) }}" class="block hover:bg-gray-50 transition-colors duration-200">
                            <div class="p-4 flex items-start">
                                <!-- Icon -->
                                <div class="flex-shrink-0 mr-4">
                                    @if($related->type === 'order')
                                        <div class="h-10 w-10 rounded-full bg-green-100 flex items-center justify-center">
                                            <i class="fas fa-shopping-bag text-green-500"></i>
                                        </div>
                                    @elseif($related->type === 'system')
                                        <div class="h-10 w-10 rounded-full bg-blue-100 flex items-center justify-center">
                                            <i class="fas fa-cog text-blue-500"></i>
                                        </div>
                                    @elseif($related->type === 'promotion')
                                        <div class="h-10 w-10 rounded-full bg-purple-100 flex items-center justify-center">
                                            <i class="fas fa-gift text-purple-500"></i>
                                        </div>
                                    @else
                                        <div class="h-10 w-10 rounded-full bg-gray-100 flex items-center justify-center">
                                            <i class="fas fa-bell text-gray-500"></i>
                                        </div>
                                    @endif
                                </div>
                                
                                <!-- Content -->
                                <div class="flex-1">
                                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                                        <h3 class="font-medium text-gray-800 {{ $related->isUnread() ? 'font-semibold' : '' }} line-clamp-1">
                                            {{ $related->title }}
                                            @if($related->isUnread())
                                                <span class="inline-block w-2 h-2 bg-primary rounded-full ml-2 align-middle"></span>
                                            @endif
                                        </h3>
                                        <span class="text-xs text-gray-500 mt-1 sm:mt-0">{{ $related->created_at->diffForHumans() }}</span>
                                    </div>
                                    
                                    <p class="text-sm text-gray-600 mt-1 line-clamp-2">{{ $related->message }}</p>
                                </div>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    @endif
</div>
@endsection 