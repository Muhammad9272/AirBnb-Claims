@extends('admin.layouts.master')
@section('title') Notifications @endsection
@section('css')
<style>
    .notification-item {
        transition: all 0.2s ease;
    }
    .notification-item:hover {
        background-color: #f8f9fa;
    }
    .notification-unread {
        background-color: #e7f4ff;
    }
    .notification-unread:hover {
        background-color: #d1e7ff;
    }
</style>
@endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.dashboard') }}">Dashboard</a> @endslot
@slot('title') Notifications @endslot
@endcomponent

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h4 class="card-title mb-0 flex-grow-1">Notifications</h4>
                @if($notifications->count() > 0)
                <div>
                    <form action="{{ route('admin.notifications.mark-all-read') }}" method="POST" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-primary">
                            <i class="ri-check-double-line align-bottom"></i> Mark All Read
                        </button>
                    </form>
                    <form action="{{ route('admin.notifications.delete-all') }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure you want to delete all notifications?')">
                            <i class="ri-delete-bin-line align-bottom"></i> Delete All
                        </button>
                    </form>
                </div>
                @endif
            </div>
            @php 
            $content_type = [
                'new_evidence' => 'New Evidence',
                'new_user_comment' => 'New User Comment',
                'claim_submitted' => 'Claim Submitted',
            ];
            @endphp
            <div class="card-body">
                @if($notifications->count() > 0)
                <div class="table-responsive">
                    <table class="">
                        <thead class="table-light">
                            <tr>
                                <th scope="col" style="width: 70px;"></th>
                                <th scope="col">Title</th>
                                <th scope="col">Message</th>
                                <th scope="col">Date</th>
                                <th scope="col" style="width: 120px;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($notifications as $notification)
                            <tr class="notification-item {{ !$notification->is_read ? 'notification-unread' : '' }}">
                                <td>
                                    @if(!$notification->is_read)
                                    <span class="badge bg-info rounded-pill">New</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-medium">{{ $notification->title }}</div>
                                    <div class="text-muted">{{ $content_type[$notification->type] ?? $notification->type }}</div>
                                </td>
                                <td>{{ \Illuminate\Support\Str::limit($notification->message, 100) }}</td>
                                <td>{{ $notification->created_at->diffForHumans() }}</td>
                                <td>
                                    <div class="d-flex gap-2">
                                        @if($notification->link)
                                        <a href="{{$notification->link}}" class="btn btn-sm btn-primary">
                                            <i class="ri-eye-line"></i>
                                        </a>
                                        @elseif(!$notification->is_read)
                                        <form action="{{ route('admin.notifications.mark-read', $notification->id) }}" method="POST">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success">
                                                <i class="ri-check-line"></i>
                                            </button>
                                        </form>
                                        @endif
                                        
                                        <form action="{{ route('admin.notifications.delete', $notification->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="ri-delete-bin-line"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="mt-3">
                    {{ $notifications->links() }}
                </div>
                @else
                <div class="text-center p-5">
                    <div class="avatar-lg mx-auto mb-4">
                        <div class="avatar-title bg-light text-primary rounded-circle">
                            <i class="ri-notification-line fs-1"></i>
                        </div>
                    </div>
                    <h5>No notifications yet!</h5>
                    <p class="text-muted">You don't have any notifications at the moment.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
