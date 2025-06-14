<div class="row">
    <div class="col-md-12">
        <div class="table-responsive">
            <table class="table table-striped mb-0">
                <tbody>
                    <tr>
                        <th>Subscription ID</th>
                        <td>{{ $subscription->id }}</td>
                    </tr>
                    <tr>
                        <th>User</th>
                        <td>
                            @if($subscription->user)
                                <div>
                                    <strong>{{ $subscription->user->name }}</strong>
                                    <p class="text-muted mb-0">{{ $subscription->user->email }}</p>
                                    <a href="{{ route('admin.users.show', $subscription->user->id) }}" class="btn btn-sm btn-soft-primary mt-2">
                                        View User
                                    </a>
                                </div>
                            @else
                                User not found
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Plan</th>
                        <td>
                            @if($subscription->plan)
                                <div>
                                    <strong>{{ $subscription->plan->name }}</strong>
                                    <p class="text-muted mb-0">{{ \App\CentralLogics\Helpers::setInterval($subscription->plan->interval) }}</p>
                                </div>
                            @else
                                Plan not found
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <td>${{ number_format($subscription->price, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Status</th>
                        <td>
                            @php
                                $statusClass = [
                                    'active' => 'badge bg-success',
                                    'canceled' => 'badge bg-danger',
                                    'pending' => 'badge bg-warning',
                                    'problem' => 'badge bg-info'
                                ];
                                $class = $statusClass[$subscription->status] ?? 'badge bg-secondary';
                            @endphp
                            <span class="{{ $class }}">{{ ucfirst($subscription->status) }}</span>
                        </td>
                    </tr>
                    <tr>
                        <th>Stripe ID</th>
                        <td>{{ $subscription->stripe_id ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Stripe Status</th>
                        <td>{{ $subscription->stripe_status ?? 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Created At</th>
                        <td>{{ $subscription->created_at ? $subscription->created_at->format('M d, Y H:i:s') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>Start Date</th>
                        <td>{{ $subscription->created_at ? $subscription->created_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    <tr>
                        <th>End Date</th>
                        <td>{{ $subscription->ends_at ? $subscription->ends_at->format('M d, Y') : 'N/A' }}</td>
                    </tr>
                    @if($subscription->canceled_at)
                    <tr>
                        <th>Canceled At</th>
                        <td>{{ $subscription->canceled_at->format('M d, Y H:i:s') }}</td>
                    </tr>
                    @endif
                    <tr>
                        <th>Payment Method</th>
                        <td>{{ ucfirst($subscription->payment_method ?? 'N/A') }}</td>
                    </tr>
                    <tr>
                        <th>Transaction ID</th>
                        <td>{{ $subscription->transaction_id ?? 'N/A' }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
