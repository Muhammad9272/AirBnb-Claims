@extends('front.layouts.app')

@section('meta_title', 'Support Tickets')

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
                    <!-- Header -->
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <div>
                            <h1 class="text-2xl font-bold text-gray-800">Support Tickets</h1>
                            <p class="text-gray-600 mt-1">Manage your support conversations</p>
                        </div>
                        <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-200">
                            <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            New Ticket
                        </a>
                    </div>

                    <!-- Stats Bar -->
                    <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
                        <div class="text-sm text-gray-600">
                            <span class="font-medium">{{ $tickets->count() }}</span> ticket(s) found
                        </div>
                    </div>

                    <!-- Tickets List -->
                    <div class="bg-white">
                        @if($tickets->count() > 0)
                            <div class="divide-y divide-gray-100">
                                @foreach($tickets as $ticket)
                                    <a href="{{ route('user.tickets.show', $ticket->ticket_id) }}" 
                                       class="flex items-center justify-between p-6 hover:bg-gray-50 transition-all duration-200 group">
                                        <div class="flex items-center space-x-4">
                                            <div @class([
                                                'w-3 h-3 rounded-full flex-shrink-0',
                                                'bg-green-500' => $ticket->status === 'open',
                                                'bg-yellow-500' => $ticket->status === 'pending',
                                                'bg-red-500' => $ticket->status === 'closed',
                                            ])></div>
                                            <div>
                                                <div class="flex items-center mb-2">
                                                    <h3 class="font-semibold text-gray-800 group-hover:text-accent transition-colors duration-200">{{ $ticket->subject }}</h3>
                                                    <span class="ml-3 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                                                        @if($ticket->status === 'open') bg-green-100 text-green-800
                                                        @elseif($ticket->status === 'pending') bg-yellow-100 text-yellow-800
                                                        @else bg-red-100 text-red-800
                                                        @endif">
                                                        {{ ucfirst($ticket->status) }}
                                                    </span>
                                                </div>
                                                <div class="flex items-center space-x-4">
                                                    <p class="text-sm text-gray-500 flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.415-1.415L11 9.586V6z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $ticket->created_at->diffForHumans() }}
                                                    </p>
                                                    <p class="text-sm text-gray-500 flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H4a2 2 0 00-2 2v10a2 2 0 002 2h12a2 2 0 002-2V6a2 2 0 00-2-2h-1V3a1 1 0 10-2 0v1H7V3a1 1 0 00-1-1zm0 5a1 1 0 000 2h8a1 1 0 100-2H6z" clip-rule="evenodd" />
                                                        </svg>
                                                        Ticket #{{ $ticket->id }}
                                                    </p>
                                                    {{-- <p class="text-sm text-gray-500 flex items-center">
                                                        <svg class="w-4 h-4 mr-1.5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                            <path fill-rule="evenodd" d="M18 10c0 3.866-3.582 7-8 7a8.841 8.841 0 01-4.083-.98L2 17l1.338-3.123C2.493 12.767 2 11.434 2 10c0-3.866 3.582-7 8-7s8 3.134 8 7zM7 9H5v2h2V9zm8 0h-2v2h2V9zM9 9h2v2H9V9z" clip-rule="evenodd" />
                                                        </svg>
                                                        {{ $ticket->messages->count() }} {{ Str::plural('message', $ticket->messages->count()) }}
                                                    </p> --}}
                                                </div>
                                            </div>
                                        </div>
                                        <div class="flex items-center">
                                            <svg class="w-5 h-5 text-gray-400 group-hover:text-accent transition-colors duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </div>
                                    </a>
                                @endforeach
                            </div>
                        @else
                            <div class="p-12 text-center">
                                <div class="inline-flex items-center justify-center w-20 h-20 rounded-full bg-blue-50 text-blue-500 mb-6">
                                    <svg class="w-10 h-10" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-semibold text-gray-800 mb-2">No Tickets Found</h3>
                                <p class="text-gray-600 mb-8 max-w-md mx-auto">You haven't created any support tickets yet. Create your first ticket to get help from our support team.</p>
                                <a href="{{ route('user.tickets.create') }}" class="inline-flex items-center px-6 py-3 border border-transparent rounded-xl shadow-sm text-base font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create Your First Ticket
                                </a>
                            </div>
                        @endif
                    </div>

                    <!-- Pagination -->
                    @if($tickets->hasPages())
                        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200">
                            {{ $tickets->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection