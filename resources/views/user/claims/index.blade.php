@extends('front.layouts.app')
@section('meta_title') My Claims @endsection

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
                <div class="bg-white rounded-xl shadow-lg overflow-hidden mb-8">
                    <div class="p-6 bg-white border-b border-gray-200 flex justify-between items-center">
                        <h1 class="text-2xl font-bold text-gray-800">My Claims</h1>
                        <a href="{{ route('user.claims.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-dark">
                            <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                            </svg>
                            New Claim
                        </a>
                    </div>

                    @include('includes.alerts')

                    <div class="p-6">
                        @if(count($claims) > 0)
                            <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                ID
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Title
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Amount
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Status
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Action
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        @foreach($claims as $key=>$claim)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $key+1 }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">
                                                        {{ \Illuminate\Support\Str::limit($claim->title, 30) }}
                                                    </div>
                                                    {{-- <div class="text-xs text-gray-500">
                                                        Booking Ref: {{ $claim->booking_reference }}
                                                    </div> --}}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    ${{ number_format($claim->amount_requested, 2) }}
                                                    @if($claim->amount_approved && in_array($claim->status, ['approved', 'paid']))
                                                        <div class="text-xs text-green-600">
                                                            Approved: ${{ number_format($claim->amount_approved, 2) }}
                                                        </div>
                                                    @endif
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $claim->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    @php
                                                        $statusClasses = [
                                                            'submitted' => 'bg-blue-100 text-blue-800',
                                                            'in_review' => 'bg-purple-100 text-purple-800',
                                                            'pending_evidence' => 'bg-yellow-100 text-yellow-800',
                                                            'approved' => 'bg-green-100 text-green-800',
                                                            'rejected' => 'bg-red-100 text-red-800',
                                                            'paid' => 'bg-emerald-100 text-emerald-800'
                                                        ];
                                                        
                                                        $class = $statusClasses[$claim->status] ?? 'bg-gray-100 text-gray-800';
                                                    @endphp
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $class }}">
                                                        {{ ucfirst(str_replace('_', ' ', $claim->status)) }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <a href="{{ route('user.claims.show', $claim->id) }}" class="text-accent hover:text-accent-dark">
                                                        View
                                                    </a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mt-4">
                                {{ $claims->links() }}
                            </div>
                        @else
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                                <h3 class="mt-2 text-sm font-medium text-gray-900">No claims yet</h3>
                                <p class="mt-1 text-sm text-gray-500">
                                    Get started by creating a new claim for your Airbnb property.
                                </p>
                                <div class="mt-6">
                                    <a href="{{ route('user.claims.create') }}" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-accent hover:bg-accent-dark focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-accent">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
                                        </svg>
                                        Create your first claim
                                    </a>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
                
                <!-- Claims Stats -->
                <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <h2 class="text-xl font-bold text-gray-800">Claims Overview</h2>
                    </div>
                    <div class="p-6">
                        <dl class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-3">
                            <div class="bg-blue-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                                <dt class="truncate text-sm font-medium text-blue-800">Total Claims</dt>
                                <dd class="mt-1 text-3xl font-semibold text-blue-900">{{ $claims->total() }}</dd>
                            </div>
                            <div class="bg-purple-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                                <dt class="truncate text-sm font-medium text-purple-800">In Progress</dt>
                                <dd class="mt-1 text-3xl font-semibold text-purple-900">
                                    {{ $claims->where('status', 'submitted')->count() + $claims->where('status', 'in_review')->count() + $claims->where('status', 'pending_evidence')->count() }}
                                </dd>
                            </div>
                            <div class="bg-green-50 overflow-hidden rounded-lg px-4 py-5 sm:p-6">
                                <dt class="truncate text-sm font-medium text-green-800">Approved/Paid</dt>
                                <dd class="mt-1 text-3xl font-semibold text-green-900">
                                    {{ $claims->where('status', 'approved')->count() + $claims->where('status', 'paid')->count() }}
                                </dd>
                            </div>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
