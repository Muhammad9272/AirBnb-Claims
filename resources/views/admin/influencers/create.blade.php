@extends('admin.layouts.master')
@section('title', 'Add New Influencer')

@section('content')
    @component('components.breadcrumb')
        @slot('li_1')
            <a href="{{ route('admin.influencers.index') }}">Influencers</a>
        @endslot
        @slot('title')
            Add New
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Add New Influencer</h4>
                    <p class="text-muted mt-1">Create a new influencer account and send login credentials via email</p>
                </div>

                <div class="card-body">
                    @include('admin.includes.alerts')

                    <form action="{{ route('admin.influencers.store') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="name" class="form-label">Full Name <span class="text-danger">*</span></label>
                                    <input type="text" 
                                           class="form-control @error('name') is-invalid @enderror" 
                                           id="name" 
                                           name="name" 
                                           value="{{ old('name') }}" 
                                           required>
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="email" class="form-label">Email Address <span class="text-danger">*</span></label>
                                    <input type="email" 
                                           class="form-control @error('email') is-invalid @enderror" 
                                           id="email" 
                                           name="email" 
                                           value="{{ old('email') }}" 
                                           required>
                                    @error('email')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="mb-3">
                                    <label for="phone" class="form-label">Phone Number</label>
                                    <input type="tel" 
                                           class="form-control @error('phone') is-invalid @enderror" 
                                           id="phone" 
                                           name="phone" 
                                           value="{{ old('phone') }}">
                                    @error('phone')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <div class="form-text">Optional contact number for the influencer</div>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="alert alert-info">
                                    <h6 class="alert-heading"><i class="ri-information-line"></i> Automatic Setup</h6>
                                    <ul class="mb-0">
                                        <li>A secure random password will be generated automatically</li>
                                        <li>A unique affiliate code will be assigned</li>
                                        <li>Login credentials will be sent to the provided email address</li>
                                        <li>The influencer will be set as active by default</li>
                                        <li>Role will be set to "Influencer" (role_id: 2, role_type: influencer)</li>
                                    </ul>
                                </div>
                            </div>

                            <div class="col-12">
                                <div class="d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="ri-add-line"></i> Create Influencer & Send Credentials
                                    </button>
                                    <a href="{{ route('admin.influencers.index') }}" class="btn btn-secondary">
                                        <i class="ri-arrow-left-line"></i> Back to List
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Influencer Program Details</h6>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-start mb-3">
                        <div class="avatar-xs me-3 mt-1">
                            <span class="avatar-title rounded-circle bg-success bg-soft text-success">
                                <i class="ri-percent-line"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">Commission Rate</h6>
                            <p class="text-muted mb-0 small">10% commission on approved claims</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="avatar-xs me-3 mt-1">
                            <span class="avatar-title rounded-circle bg-warning bg-soft text-warning">
                                <i class="ri-time-line"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">Commission Duration</h6>
                            <p class="text-muted mb-0 small">30 days from customer subscription</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start mb-3">
                        <div class="avatar-xs me-3 mt-1">
                            <span class="avatar-title rounded-circle bg-info bg-soft text-info">
                                <i class="ri-links-line"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">Unique Affiliate Link</h6>
                            <p class="text-muted mb-0 small">Automatically generated tracking code</p>
                        </div>
                    </div>

                    <div class="d-flex align-items-start">
                        <div class="avatar-xs me-3 mt-1">
                            <span class="avatar-title rounded-circle bg-primary bg-soft text-primary">
                                <i class="ri-dashboard-line"></i>
                            </span>
                        </div>
                        <div>
                            <h6 class="mb-1">Dashboard Access</h6>
                            <p class="text-muted mb-0 small">Track referrals and earnings</p>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h6 class="card-title mb-0">Email Template Preview</h6>
                </div>
                <div class="card-body">
                    <p class="text-muted small">The influencer will receive an email containing:</p>
                    <ul class="text-muted small">
                        <li>Welcome message and program overview</li>
                        <li>Login credentials (email + generated password)</li>
                        <li>Direct link to login page</li>
                        <li>Commission structure details</li>
                        <li>Instructions to change password</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
@endsection