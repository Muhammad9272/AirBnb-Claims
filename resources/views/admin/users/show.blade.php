@extends('admin.layouts.master')
@section('title') User Details @endsection

@section('content')
@component('components.breadcrumb')
@slot('li_1') <a href="{{ route('admin.users.index') }}">Users</a> @endslot
@slot('title') User Details @endslot
@endcomponent

<div class="row">
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
                        @if($data->role_type === 'influencer')
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-purple">
                                <i class="ri-star-fill"></i> Influencer
                            </span>
                        @endif
                    </div>
                    <h4 class="mt-3 mb-1">{{ ucfirst($data->name) }}</h4>
                    <p class="text-muted mb-1">User ID: {{ $data->affiliate_code }}</p>
                    @if($data->role_type === 'influencer')
                        <a href="{{ route('admin.influencers.show', $data->id) }}" class="btn btn-sm btn-purple">
                            <i class="ri-dashboard-line"></i> View Influencer Dashboard
                        </a>
                    @endif
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

    <div class="col-xl-6">
        <!-- Referral Information Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Referral & Affiliate Information</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-borderless mb-0">
                        <tbody>
                            <tr>
                                <th width="40%" class="ps-0">Affiliate Code:</th>
                                <td>
                                    <code class="text-primary fs-15">{{ $data->affiliate_code }}</code>
                                    <button class="btn btn-sm btn-soft-primary ms-2" onclick="copyToClipboard('{{ $data->affiliate_code }}')">
                                        <i class="ri-file-copy-line"></i> Copy
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Referral Link:</th>
                                <td>
                                    <small class="text-muted d-block">{{ url('/?ref=' . $data->affiliate_code) }}</small>
                                    <button class="btn btn-sm btn-soft-info mt-1" onclick="copyToClipboard('{{ url('/?ref=' . $data->affiliate_code) }}')">
                                        <i class="ri-links-line"></i> Copy Link
                                    </button>
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Referred By:</th>
                                <td>
                                    @php
                                        $referrer = $data->referred_by ? \App\Models\User::find($data->referred_by) : null;
                                    @endphp
                                    @if($referrer)
                                        <a href="{{ route('admin.users.show', $referrer->id) }}" class="text-decoration-underline">
                                            {{ $referrer->name }}
                                        </a>
                                        <small class="text-muted d-block">Code: {{ $referrer->affiliate_code }}</small>
                                        @if($referrer->role_type === 'influencer')
                                            <span class="badge bg-purple-soft text-purple mt-1">
                                                <i class="ri-star-fill"></i> Influencer
                                            </span>
                                        @endif
                                    @else
                                        <span class="text-muted">Direct signup (no referrer)</span>
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th class="ps-0">Total Referrals:</th>
                                <td>
                                    @php
                                        $totalReferrals = \App\Models\User::where('referred_by', $data->id)->count();
                                    @endphp
                                    <span class="badge bg-success fs-14">{{ $totalReferrals }}</span>
                                    @if($totalReferrals > 0)
                                        <button class="btn btn-sm btn-soft-success ms-2" data-bs-toggle="collapse" data-bs-target="#referralsList">
                                            <i class="ri-eye-line"></i> View All
                                        </button>
                                    @endif
                                </td>
                            </tr>
                            @if($data->role_type === 'influencer')
                            <tr>
                                <th class="ps-0">Influencer Earnings:</th>
                                <td>
                                    @php
                                        $totalCommissions = \App\Models\InfluencerCommission::where('influencer_user_id', $data->id)->sum('commission_amount');
                                        $paidCommissions = \App\Models\InfluencerCommission::where('influencer_user_id', $data->id)->where('status', 'paid')->sum('commission_amount');
                                    @endphp
                                    <div>
                                        <strong>Total:</strong> <span class="text-success">${{ number_format($totalCommissions, 2) }}</span><br>
                                        <strong>Paid:</strong> <span class="text-primary">${{ number_format($paidCommissions, 2) }}</span>
                                    </div>
                                </td>
                            </tr>
                            @endif
                            <tr>
                                <th class="ps-0">Affiliate Earnings:</th>
                                <td>
                                    <span class="text-success fw-bold">${{ number_format($affiliate_earnings, 2) }}</span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                @if($totalReferrals > 0)
                <div class="collapse mt-3" id="referralsList">
                    <div class="card card-body">
                        <h6 class="mb-3">Referred Users ({{ $totalReferrals }})</h6>
                        <div class="table-responsive">
                            <table class="table table-sm table-hover">
                                <thead>
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Joined</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                        $referredUsers = \App\Models\User::where('referred_by', $data->id)->orderBy('created_at', 'desc')->limit(10)->get();
                                    @endphp
                                    @foreach($referredUsers as $referred)
                                    <tr>
                                        <td>{{ $referred->name }}</td>
                                        <td><small>{{ $referred->email }}</small></td>
                                        <td><small>{{ $referred->created_at->format('M d, Y') }}</small></td>
                                        <td>
                                            <a href="{{ route('admin.users.show', $referred->id) }}" class="btn btn-sm btn-info">
                                                <i class="ri-eye-line"></i>
                                            </a>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            @if($totalReferrals > 10)
                                <p class="text-muted text-center mb-0">Showing 10 of {{ $totalReferrals }} referrals</p>
                            @endif
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<script>
function copyToClipboard(text) {
    navigator.clipboard.writeText(text).then(function() {
        // Show success toast if toastr is available
        if (typeof toastr !== 'undefined') {
            toastr.success('Copied to clipboard!');
        } else {
            alert('Copied to clipboard!');
        }
    }).catch(function() {
        alert('Failed to copy');
    });
}
</script>
@endsection
