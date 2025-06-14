@extends('admin.layouts.master')
@section('title', 'Option Types')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.option-types.index') }}">Option Types</a>
        @endslot
        @slot('title')
            Option Types List
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row ms-2">
                        <div class="col-lg-12 col-md-4 m-auto text-end">
                            <button class="btn btn-primary" id="add-option" data-href="{{ route('admin.option-types.create') }}">Add Option Type</button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <table id="geniustable" class="table dt-responsive align-middle" style="width:100%">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Name</th>
                                
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>

<!-- Modal for Add/Edit Option Type -->
<div class="modal fade" id="GeniusAjaxModal" data-bs-backdrop="static" data-bs-keyboard="false" tabindex="-1" aria-labelledby="GeniusAjaxModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="GeniusAjaxModalLabel">Add/Edit Option Type</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Dynamic content will be loaded here -->
            </div>
            
        </div>
    </div>
</div>

@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script>
        var table = $('#geniustable').DataTable({
            processing: true,
            serverSide: true,
            ajax: '{{ route('admin.option-types.datatables') }}',
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'name', name: 'name' },
                { data: 'status', name: 'status' },
                { data: 'action', name: 'action' }
            ]
        });

        // Load modal for adding new option type
        $(document).on('click', '#add-option', function() {
            var href = $(this).data('href');
            $('#GeniusAjaxModal').find('.modal-title').text('Add Option Type');
            $('#GeniusAjaxModal .modal-body').load(href, function() {
                $('#GeniusAjaxModal').modal('show');
            });
        });

        // Load modal for editing option type
        $(document).on('click', '.edit-option', function() {
            var href = $(this).data('href');
            $('#GeniusAjaxModal').find('.modal-title').text('Edit Option Type');
            $('#GeniusAjaxModal .modal-body').load(href, function() {
                $('#GeniusAjaxModal').modal('show');
            });
        });

        // Delete option type
        $(document).on('click', '.delete-option', function() {
            var href = $(this).data('href');
            if (confirm('Are you sure you want to delete this option type?')) {
                $.ajax({
                    url: href,
                    type: 'GET',
                    success: function(response) {
                       
                        table.ajax.reload();
                    }
                });
            }
        });
    </script>
@endsection
