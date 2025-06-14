@extends('admin.layouts.master')
@section('title') User Details @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.users.index') }}">Users</a> @endslot
@slot('title') User Details @endslot
@endcomponent

<div class="row justify-content-center">
    <div class="col-xl-6">
        <div class="card">
            <div class="card-header pb-0 border-0">
                <div class="d-flex align-items-center justify-content-between">
                    <h5 class="card-title mb-0">User Information</h5>
                    <p class="mb-0 text-muted">Joined: {{ $data->created_at->format('F d, Y') }}</p>
                </div>
            </div>
            
            <div class="card-body">
                <div class="text-center mb-4">
                    <div class="position-relative d-inline-block">
                        <img src="{!! Helpers::image($data->photo, 'user/avatar/', 'user.png') !!}" 
                             alt="Profile" 
                             class="rounded-circle img-thumbnail"
                             style="width: 120px; height: 120px; object-fit: cover;">
                    </div>
                    <h4 class="mt-3 mb-1">{{ ucfirst($data->name) }}</h4>
                    <p class="text-muted mb-3">User ID: {{ $data->affiliate_code }}</p>
                </div>

                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th width="35%" class="ps-0">Email:</th>
                                <td class="text-muted">{{ $data->email }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Phone:</th>
                                <td class="text-muted">{{ $data->phone ?: 'Not provided' }}</td>
                            </tr>
                            <tr>
                                <th class="ps-0">Status:</th>
                                <td>
                                    <span class="badge bg-{{ $data->status ? 'success' : 'danger' }}">
                                        {{ $data->status ? 'Active' : 'Inactive' }}
                                    </span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
