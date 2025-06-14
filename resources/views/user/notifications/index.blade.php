@extends('front.layouts.app')
@section('meta_title') Notifications @endsection

@section('content')
<div class="bg-gray-50 py-12 min-h-screen">
    <div class="container mx-auto px-4">
        <div class="flex flex-col lg:flex-row gap-8">
            <!-- Sidebar -->
            <div class="lg:w-1/4">
                @include('user.partials.sidebar')
            </div>

            <!-- Main Content -->
            <div class="lg:w-3/4">
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">Notifications</h1>
                        <div class="flex gap-2">
                            @if($notifications->count() > 0)
                                <form action="{{ route('user.notifications.mark-all-read') }}" method="POST" class="inline">
                                    @csrf
                                    <button type="submit" class="text-sm text-blue-600 hover:text-blue-800">
                                        Mark all as read
                                    </button>
                                </form>
                                <span class="text-gray-300">|</span>
                                <form action="{{ route('user.notifications.delete-all') }}" method="POST" class="inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-sm text-red-600 hover:text-red-800" onclick="return confirm('Are you sure you want to delete all notifications?')">
                                        Delete all
                                    </button>
                                </form>
                            @endif
                        </div>
                    </div>

                    @include('includes.alerts')

                    @if($notifications->count() > 0)
                        <div class="divide-y divide-gray-200">
                            @foreach($notifications as $notification)
                                <div class="p-4 hover:bg-gray-50 {{ $notification->is_read ? 'bg-white' : 'bg-blue-50' }} transition duration-150">
                                    <div class="flex items-start justify-between">
                                        <div>
                                            <h3 class="text-lg font-medium text-gray-900">
                                                @if(!$notification->is_read)
                                                    <span class="inline-block w-2 h-2 bg-blue-500 rounded-full mr-2"></span>
                                                @endif
                                                {{ $notification->title }}
                                            </h3>
                                            <p class="mt-1 text-sm text-gray-600 whitespace-pre-line">{{ $notification->message }}</p>
                                            <div class="mt-2 text-xs text-gray-500">
                                                {{ $notification->created_at->diffForHumans() }}
                                            </div>
                                        </div>
                                        <div class="flex items-center space-x-2">
                                            @if(!$notification->is_read)
                                                <form action="{{ route('user.notifications.mark-read', $notification->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="p-1 text-blue-600 hover:bg-blue-100 rounded">
                                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                                                        </svg>
                                                    </button>
                                                </form>
                                            @endif
                                            <form action="{{ route('user.notifications.delete', $notification->id) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1 text-red-600 hover:bg-red-100 rounded">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                    @if($notification->link)
                                        <div class="mt-3">
                                            <a href="{{$notification->link}}" 
                                               class="inline-flex items-center text-sm text-accent hover:text-accent-dark">
                                                <span>View details</span>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 ml-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                </svg>
                                            </a>
                                        </div>
                                    @endif
                                </div>
                            @endforeach
                        </div>
                        
                        <div class="p-4">
                            {{ $notifications->links() }}
                        </div>
                    @else
                        <div class="text-center py-16">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900">No notifications</h3>
                            <p class="mt-1 text-sm text-gray-500">You don't have any notifications at the moment.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
