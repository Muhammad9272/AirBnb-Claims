@extends('front.layouts.app')

@section('meta_title', 'Bit Tasks')

@section('css')
<link href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css" rel="stylesheet">
<style>
    /* DataTable wrapper and controls */
    .dataTables_wrapper {
        @apply text-gray-700 dark:text-gray-300;
    }

    /* Length (Show entries) control */
    #bitTasksTable_length {
        @apply mb-4;
    }

    #bitTasksTable_length label {
        @apply flex items-center gap-2;
    }

    #bitTasksTable_length select {
        @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-2;
        min-width: 80px;
    }

    /* Filter (Search) control */
    #bitTasksTable_filter {
        @apply mb-4;
    }

    #bitTasksTable_filter label {
        @apply flex items-center gap-2;
    }

    #bitTasksTable_filter input {
        @apply bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-900 dark:text-gray-100 text-sm rounded-lg focus:ring-purple-500 focus:border-purple-500 block p-2;
        min-width: 200px;
    }

    /* Table styling */
    #bitTasksTable {
        @apply w-full border-collapse;
    }

    #bitTasksTable thead th {
        @apply bg-gray-50 dark:bg-gray-700 text-gray-700 dark:text-gray-300 px-4 py-3 text-left border-b border-gray-200 dark:border-gray-600;
    }

    #bitTasksTable tbody tr {
        @apply bg-white dark:bg-gray-800 border-b border-gray-200 dark:border-gray-600;
    }

    #bitTasksTable tbody td {
        @apply px-4 py-3 text-gray-700 dark:text-gray-300;
    }

    /* Pagination controls */
    .dataTables_paginate {
        @apply mt-4 flex items-center justify-end gap-2;
    }

    .paginate_button {
        @apply px-3 py-1 text-sm font-medium rounded-md border border-gray-300 dark:border-gray-600 bg-white dark:bg-gray-700 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-600;
    }

    .paginate_button.current {
        @apply bg-purple-600 text-white border-purple-600 hover:bg-purple-700 dark:hover:bg-purple-700;
    }

    .paginate_button.disabled {
        @apply opacity-50 cursor-not-allowed;
    }

    /* Processing display */
    .dataTables_processing {
        @apply bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-600 shadow-lg rounded-lg p-4;
    }
</style>
@endsection

@section('content')
<div class="container mx-auto px-4 py-12">
    <div class="flex flex-col lg:flex-row gap-8">
        <!-- Sidebar -->
        <div class="lg:w-1/4">
            @include('user.partials.sidebar')

        </div>

        <!-- Main Content -->
        <div class="lg:w-3/4">
            <!-- Header -->
            <div class="bg-gradient-to-r from-purple-600 to-indigo-600 rounded-xl p-6 shadow mb-8">
                <div class="flex justify-between items-center">
                    <div>
                        <h1 class="text-2xl font-bold text-white">Bit Tasks</h1>
                        <p class="text-white/80 mt-1">Complete tasks to earn bits which you can use for discounts!</p>
                    </div>
                    <div class="text-center bg-white/20 rounded-lg p-3">
                        <p class="text-sm text-white/80">Your Balance</p>
                        <p class="text-xl font-bold text-white">{{ auth()->user()->bit_balance }} Bits</p>
                    </div>
                </div>
            </div>

            <!-- DataTable -->
            <div class="bg-white dark:bg-gray-800 rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <table id="bitTasksTable" class="w-full text-left stripe hover">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="whitespace-nowrap">Title</th>
                                <th class="whitespace-nowrap">Bits</th>
                                <th class="whitespace-nowrap">Submissions</th>
                                <th class="whitespace-nowrap">Status</th>
                                <th class="whitespace-nowrap">Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
$(document).ready(function() {
    $('#bitTasksTable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('user.bit-tasks.index') }}",
        columns: [
            {data: 'title', name: 'title'},
            {data: 'bit_value', name: 'bit_value'},
            {data: 'total_submissions', name: 'total_submissions'},
            {data: 'status', name: 'status', orderable: false, searchable: false},
            {data: 'action', name: 'action', orderable: false, searchable: false}
        ],
        responsive: true,
        dom: '<"flex flex-col md:flex-row justify-between items-center mb-4"lf>rt<"flex flex-col md:flex-row justify-between items-center mt-4"ip>',
        language: {
            search: "Search:",
            lengthMenu: "_MENU_ per page",
            processing: '<i class="fas fa-spinner fa-spin"></i> Loading...',
            paginate: {
                first: '<i class="fas fa-angle-double-left"></i>',
                last: '<i class="fas fa-angle-double-right"></i>',
                next: '<i class="fas fa-angle-right"></i>',
                previous: '<i class="fas fa-angle-left"></i>'
            }
        },
        drawCallback: function() {
            // Style action buttons
            $('.btn-primary').addClass('inline-flex items-center px-4 py-2 bg-purple-600 hover:bg-purple-700 text-white rounded-lg text-sm font-medium transition-colors');
            
            // Style status badges
            $('.badge.bg-success').addClass('px-2.5 py-0.5 rounded text-xs font-medium bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300');
            $('.badge.bg-danger').addClass('px-2.5 py-0.5 rounded text-xs font-medium bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300');
        }
    });
});
</script>
@endsection
