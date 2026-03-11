@extends('user_dashboard.layouts.app')
@section('style')
    <style>
        .profile-card { background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .profile-card .card-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; }
        .history-table { font-size: 0.9rem; }
        .history-table th { background: #f8f9fa; }
    </style>
@endsection
@section('content')
    <div class="widget-box-2">
        <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-4">
            <h5 class="mb-0">{{ __('Upgrade requests history') }}</h5>
            <a href="{{ route('user.profile.index') }}" class="tf-btn outline">{{ __('Back to profile') }}</a>
        </div>

        @if(isset($latestProcessedRequest) && $latestProcessedRequest)
            @if($latestProcessedRequest->isAccepted())
                <div class="alert alert-success">
                    <strong>{{ __('Latest request: Accepted') }}</strong>
                    <p class="mb-1">{{ __('Your plan was upgraded to') }} <strong>{{ $latestProcessedRequest->plan ? $latestProcessedRequest->plan->title : '' }}</strong>. {{ __('Your account has been upgraded.') }}</p>
                    @if(isset($user) && ($user->subscription_started_at || $user->subscription_ends_at))
                        <p class="mb-1 small">
                            @if($user->subscription_started_at) {{ __('Subscription starts') }}: {{ $user->subscription_started_at->format('Y-m-d') }}@endif
                            @if($user->subscription_started_at && $user->subscription_ends_at) — @endif
                            @if($user->subscription_ends_at) {{ __('Subscription ends') }}: {{ $user->subscription_ends_at->format('Y-m-d') }}@endif
                        </p>
                    @endif
                    @if($latestProcessedRequest->admin_notes)
                        <span class="small">{{ __('Admin notes') }}: {{ $latestProcessedRequest->admin_notes }}</span>
                    @endif
                </div>
            @else
                <div class="alert alert-danger">
                    <strong>{{ __('Latest request: Rejected') }}</strong>
                    @if($latestProcessedRequest->admin_notes)
                        <br>{{ __('Admin notes') }}: {{ $latestProcessedRequest->admin_notes }}
                    @endif
                    <br><a href="{{ route('user.profile.upgrade') }}" class="btn btn-sm btn-outline-danger mt-2">{{ __('Submit a new upgrade request') }}</a>
                </div>
            @endif
        @endif

        <div class="profile-card">
            @if(isset($upgradeHistory) && $upgradeHistory->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered history-table">
                        <thead>
                            <tr>
                                <th>{{ __('Request Date') }}</th>
                                <th>{{ __('Requested Plan') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Transfer Receipt') }}</th>
                                <th>{{ __('Processed Date') }}</th>
                                <th>{{ __('Admin Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upgradeHistory as $req)
                            <tr>
                                <td>{{ $req->created_at->format('Y-m-d H:i') }}</td>
                                <td>{{ $req->plan ? $req->plan->title : '—' }}</td>
                                <td>
                                    @if($req->status === \App\Models\PlanUpgradeRequest::STATUS_PENDING)
                                        <span class="badge bg-warning text-dark">{{ __('Pending') }}</span>
                                    @elseif($req->status === \App\Models\PlanUpgradeRequest::STATUS_ACCEPTED)
                                        <span class="badge bg-success">{{ __('Accepted') }}</span>
                                    @else
                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if($req->transfer_receipt_url)
                                        <a href="{{ $req->transfer_receipt_url }}" target="_blank" rel="noopener">{{ __('View receipt') }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $req->processed_at ? $req->processed_at->format('Y-m-d H:i') : '—' }}</td>
                                <td class="small">{{ $req->admin_notes ?: '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <div class="d-flex justify-content-center mt-3">
                    {{ $upgradeHistory->links() }}
                </div>
            @else
                <p class="text-muted mb-0">{{ __('No upgrade requests yet.') }}</p>
                <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary mt-3">{{ __('Request an upgrade') }}</a>
            @endif
        </div>
    </div>
@endsection
