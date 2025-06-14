@extends('admin.layouts.master')

@section('title')
    Edit Review
@endsection

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                <h4 class="mb-sm-0">Edit Review</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('admin.reviews.index') }}">Reviews</a></li>
                        <li class="breadcrumb-item active">Edit</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card">
                <div class="card-header bg-light">
                    <h5 class="card-title mb-0">Review Details</h5>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="d-flex align-items-center mb-3">
                                <img src="{{ Helpers::image($review->product->main_image, 'products/') }}" 
                                     alt="{{ $review->product->name }}" 
                                     class="avatar-md me-3">
                                <div>
                                    <h5 class="fw-semibold">{{ $review->product->name }}</h5>
                                    <p class="text-muted mb-0">Sku: {{ $review->product->sku }}</p>
                                    <p class="text-muted mb-0">
                                        {{ $review->product->category ? $review->product->category->name : 'No category' }}
                                    </p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6 text-md-end">
                            <p class="mb-1"><strong>Review Date:</strong> {{ $review->created_at->format('M d, Y') }}</p>
                            <p class="mb-1">
                                <strong>Rating:</strong> 
                                {!! $review->getStarsHtml() !!}
                            </p>
                            <p class="mb-0">
                                <strong>Status:</strong> 
                                <span class="badge {{ $review->approved ? 'bg-success' : 'bg-warning' }}">
                                    {{ $review->approved ? 'Approved' : 'Pending' }}
                                </span>
                                @if($review->verified_purchase)
                                    <span class="badge bg-info ms-1">Verified Purchase</span>
                                @endif
                            </p>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-4 mb-md-0">
                            <h5 class="fw-semibold mb-3">Customer</h5>
                            <div class="d-flex align-items-center">
                                <img src="{{ Helpers::image($review->user->photo, 'users/avatar/', 'user.png') }}" 
                                     alt="{{ $review->user->name }}" 
                                     class="avatar-sm rounded-circle me-3">
                                <div>
                                    <h6 class="mb-1">{{ $review->user->name }}</h6>
                                    <p class="mb-0 text-muted">{{ $review->user->email }}</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-semibold mb-3">Review Content</h5>
                            <div class="border rounded p-3 bg-light">
                                {{ $review->review_text ?? 'No written review provided.' }}
                            </div>
                        </div>
                    </div>

                    <form action="{{ route('admin.reviews.update', $review->id) }}" method="POST" class="mt-4" id="geniusform">
                        @csrf
                        @include('admin.includes.ajax-alerts')
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <div class="form-check form-switch">
                                    <input class="form-check-input" type="checkbox" id="approved" name="approved" value="1" 
                                           {{ $review->approved ? 'checked' : '' }}>
                                    <label class="form-check-label" for="approved">Approve this review</label>
                                </div>
                                <small class="text-muted">Only approved reviews are visible on the product page.</small>
                            </div>
                            <div class="col-md-12 mt-3">
                                <label for="admin_reply" class="form-label">Admin Reply (Optional)</label>
                                <textarea class="form-control" id="admin_reply" name="admin_reply" rows="4">{!! $review->admin_reply !!}</textarea>
                                <small class="text-muted">Your reply will be visible to customers alongside the review.</small>
                            </div>
                        </div>

                        <div class="mt-4">
                            <button type="submit" class="btn btn-primary">Update Review</button>
                            <a href="{{ route('admin.reviews.index') }}" class="btn btn-light ms-2">Cancel</a>
                            <a href="{{ route('admin.reviews.delete', $review->id) }}" 
                               class="btn btn-danger float-end"
                               onclick="return confirm('Are you sure you want to delete this review?')">
                                Delete Review
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
