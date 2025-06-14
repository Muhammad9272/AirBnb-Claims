@extends('admin.layouts.master')
@section('title', 'Support Tickets')

@section('css')
    <link href="https://cdn.datatables.net/1.11.5/css/dataTables.bootstrap5.min.css" rel="stylesheet" type="text/css" />
@endsection

@section('content')
    @component('components.breadcrumb')
        @slot('li_1') <a href="{{ route('admin.tickets.index') }}">Support</a> @endslot
        @slot('title') Tickets @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <table id="ticketsTable" class="table dt-responsive nowrap w-100">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Subject</th>
                                <th>User</th>
                                <th>Status</th>
                                <th>Last Update</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>

    <script type="text/javascript">
        var table = $('#ticketsTable').DataTable({
            processing: true,
            serverSide: true,
            ajax: "{{ route('admin.tickets.datatables') }}",
            columns: [
                { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                { data: 'subject', name: 'subject' },
                { data: 'user', name: 'user.name' },
                { 
                    data: 'status',
                    name: 'status',
                   render: function(data) {
                        const label = data.charAt(0).toUpperCase() + data.slice(1); // ucfirst
                        return `<span class="badge bg-${data === 'open' ? 'success' : 
                                                             data === 'pending' ? 'warning' : 
                                                             'danger'}">${label}</span>`;
                    }

                },
                { data: 'updated_at', name: 'updated_at' },
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ]
        });
    </script>
@endsection
