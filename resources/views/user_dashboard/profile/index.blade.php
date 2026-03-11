@extends('user_dashboard.layouts.app')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .error { color: #ed2027 !important; }
        .form-control.style-1.error, input.error, textarea.error, select.error { border-color: #ed2027 !important; }
        .custom-error p { color: #ed2027; margin-top: 5px; font-size: 0.9rem; }
        .profile-card { background: #fff; border: 1px solid #e9ecef; border-radius: 12px; padding: 1.5rem; margin-bottom: 1.5rem; }
        .profile-card .card-title { font-size: 1.1rem; font-weight: 600; margin-bottom: 1rem; padding-bottom: 0.5rem; border-bottom: 1px solid #eee; }
        .current-plan-card { background: linear-gradient(135deg, #1779A7 0%, #1e8fc4 100%); border-radius: 16px; padding: 1.5rem; color: #fff; }
        .current-plan-card .plan-name { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
        .current-plan-card .plan-desc { opacity: 0.95; margin-bottom: 1rem; font-size: 0.95rem; }
        .current-plan-card .list-price { list-style: none; padding: 0; margin: 0 0 1rem 0; }
        .current-plan-card .list-price .item { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 6px; font-size: 0.9rem; }
        .current-plan-card .list-price .check-icon { flex-shrink: 0; width: 18px; height: 18px; font-size: 11px; background: rgba(255,255,255,0.9); color: #1779A7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
        .current-plan-card .plan-cost { font-weight: 600; margin-bottom: 1rem; }
        .current-plan-card .tf-btn { background: #fff; color: #1779A7; border-color: #fff; }
        .current-plan-card .tf-btn:hover { background: #f0f0f0; color: #1779A7; border-color: #f0f0f0; }
        .pending-request-msg { background: #fff8e6; border: 1px solid #f0c674; border-radius: 12px; padding: 1rem; color: #856404; margin-top: 0.75rem; }
        .account-status-badge { font-size: 0.85rem; padding: 0.35rem 0.75rem; border-radius: 50px; }
        .account-status-badge.basic { background: #6c757d; color: #fff; }
        .account-status-badge.active_paid { background: #198754; color: #fff; }
        .account-status-badge.pending_upgrade { background: #ffc107; color: #000; }
        .account-status-badge.expired { background: #dc3545; color: #fff; }
        .history-table { font-size: 0.9rem; }
        .history-table th { background: #f8f9fa; }
        .receipt-thumb { max-width: 60px; max-height: 40px; object-fit: cover; border-radius: 4px; }
        .profile-card .collapse-toggle { cursor: pointer; user-select: none; display: flex; align-items: center; justify-content: space-between; gap: 0.5rem; padding: 0.25rem 0; }
        .profile-card .collapse-toggle:hover { opacity: 0.9; }
        .profile-card .collapse-icon { transition: transform 0.2s ease; flex-shrink: 0; }
        .profile-card .collapse-toggle[aria-expanded="true"] .collapse-icon { transform: rotate(180deg); }
        .profile-card .collapse-body { padding-top: 0.5rem; }
    </style>
@endsection
@section('content')

    @if(!empty($showExpiredPlanAlert))
    <div class="plan-limit-box limit-reached mb-4" style="background: linear-gradient(135deg, #fff9e8 0%, #fff5d6 100%); border: 1px solid #e5d4a1; border-radius: 14px; padding: 1.25rem 1.5rem; color: #5a4a1a;">
        <div class="plan-info">
            {{ __('Your paid subscription has expired and your account has been moved to the Basic plan. You cannot add new properties until you upgrade or free up slots.') }}
        </div>
        <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary">{{ __('Upgrade Plan') }}</a>
    </div>
    @endif

    <div class="widget-box-2">
        {{-- 1. Account / Subscription --}}
        <div class="profile-card">
            <h6 class="card-title mb-0">
                <span class="collapse-toggle w-100" data-bs-toggle="collapse" data-bs-target="#collapseAccount" aria-expanded="true" aria-controls="collapseAccount">
                    <span>{{ __('Account & Subscription') }}</span>
                    <span class="collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                </span>
            </h6>
            <div id="collapseAccount" class="collapse show collapse-body">
            <div class="current-plan-card mt-3">
                @php
                    $statusBadge = 'basic';
                    $statusLabel = __('Basic');
                    if (isset($pendingRequest) && $pendingRequest) {
                        $statusBadge = 'pending_upgrade';
                        $statusLabel = __('Pending Upgrade');
                    } elseif (isset($subscriptionStatus)) {
                        if ($subscriptionStatus['status'] === \App\Services\SubscriptionService::STATUS_ACTIVE_PAID) {
                            $statusBadge = 'active_paid';
                            $statusLabel = __('Active Paid');
                        } elseif ($subscriptionStatus['status'] === \App\Services\SubscriptionService::STATUS_EXPIRING_SOON) {
                            $statusBadge = 'pending_upgrade';
                            $statusLabel = __('Expiring soon');
                        } elseif ($subscriptionStatus['status'] === \App\Services\SubscriptionService::STATUS_EXPIRED) {
                            $statusBadge = 'expired';
                            $statusLabel = __('Expired');
                        }
                    }
                @endphp
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2 mb-2">
                    <span class="account-status-badge {{ $statusBadge }}">{{ $statusLabel }}</span>
                </div>
                @if($user->plan)
                    <div class="plan-name">{{ $user->plan->title }}</div>
                    <p class="plan-desc mb-0">{{ $user->plan->description }}</p>
                    <ul class="list-price">
                        @if($user->plan->duration_months)
                            <li class="item">
                                <span class="check-icon icon-tick"></span>
                                <span>{{ $user->plan->duration_months }} {{ $user->plan->duration_months == 1 ? __('month') : __('months') }}</span>
                            </li>
                        @endif
                        <li class="item">
                            <span class="check-icon icon-tick"></span>
                            <span>{{ __('Properties') }}: {{ $user->plan->number_of_properties_display }}</span>
                            @if(isset($planLimit) && $planLimit['limit'] !== null && $planLimit['limit'] >= 0)
                                — {{ __('Used') }}: {{ $planLimit['used'] ?? 0 }}
                                @if($planLimit['remaining'] !== null)
                                    / {{ __('Remaining') }}: {{ $planLimit['remaining'] }}
                                @endif
                            @endif
                        </li>
                        @if(isset($subscriptionInfo) && !$subscriptionInfo['is_basic'] && $subscriptionInfo['expires_at'])
                        <li class="item">
                            <span class="check-icon icon-tick"></span>
                            <span>{{ __('Subscription starts') }}: {{ $user->subscription_started_at ? $user->subscription_started_at->format('Y-m-d') : '—' }}</span>
                        </li>
                        <li class="item">
                            <span class="check-icon icon-tick"></span>
                            <span>{{ __('Subscription ends') }}: {{ $subscriptionInfo['expires_at']->format('Y-m-d') }}
                                @if($subscriptionInfo['days_remaining'] !== null)
                                    ({{ $subscriptionInfo['days_remaining'] }} {{ __('days left') }})
                                @endif
                            </span>
                        </li>
                        @endif
                        @foreach($user->plan->features as $feature)
                            @if($feature->status != 0)
                                <li class="item">
                                    <span class="check-icon icon-tick"></span>
                                    <span>{{ $feature->title }}</span>
                                </li>
                            @endif
                        @endforeach
                    </ul>
                    <div class="plan-cost">{{ __('Cost') }}: {{ $user->plan->price_monthly }} JOD @if($user->plan->duration_months)/ {{ __('month') }}@endif</div>
                    @if(isset($pendingRequest) && $pendingRequest)
                        <div class="pending-request-msg">
                            {{ __('You have a request being processed. Please wait for approval or rejection.') }}
                        </div>
                    @else
                        <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary">{{ __('Upgrade Plan') }}</a>
                    @endif
                @else
                    <p class="plan-desc">{{ __('No plan assigned.') }}</p>
                    @if(isset($pendingRequest) && $pendingRequest)
                        <div class="pending-request-msg">
                            {{ __('You have a request being processed. Please wait for approval or rejection.') }}
                        </div>
                    @else
                        <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary">{{ __('Choose a Plan') }}</a>
                    @endif
                @endif
            </div>
            </div>
        </div>

        {{-- 2. Latest upgrade request result (accepted/rejected) --}}
        @if(isset($latestProcessedRequest) && $latestProcessedRequest)
        <div class="profile-card">
            <h6 class="card-title mb-0">
                <span class="collapse-toggle w-100" data-bs-toggle="collapse" data-bs-target="#collapseLatestResult" aria-expanded="false" aria-controls="collapseLatestResult">
                    <span>{{ __('Latest upgrade request result') }}</span>
                    <span class="collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                </span>
            </h6>
            <div id="collapseLatestResult" class="collapse collapse-body">
            @if($latestProcessedRequest->isAccepted())
                <div class="alert alert-success mb-0">
                    <strong>{{ __('Request accepted') }}</strong>
                    <p class="mb-1 mt-1">{{ __('Your account plan was upgraded to') }} <strong>{{ $latestProcessedRequest->plan ? $latestProcessedRequest->plan->title : '' }}</strong>. {{ __('Your account has been upgraded.') }}</p>
                    @if($user->subscription_started_at || $user->subscription_ends_at)
                        <p class="mb-1 small">
                            @if($user->subscription_started_at) {{ __('Subscription starts') }}: {{ $user->subscription_started_at->format('Y-m-d') }}@endif
                            @if($user->subscription_started_at && $user->subscription_ends_at) — @endif
                            @if($user->subscription_ends_at) {{ __('Subscription ends') }}: {{ $user->subscription_ends_at->format('Y-m-d') }}@endif
                        </p>
                    @endif
                    @if($latestProcessedRequest->admin_notes)
                        <p class="mb-1 small">{{ __('Admin notes') }}: {{ $latestProcessedRequest->admin_notes }}</p>
                    @endif
                    <p class="mb-0 small text-muted">{{ __('Processed on') }}: {{ $latestProcessedRequest->processed_at ? $latestProcessedRequest->processed_at->format('Y-m-d H:i') : $latestProcessedRequest->updated_at->format('Y-m-d H:i') }}</p>
                </div>
            @else
                <div class="alert alert-danger mb-0">
                    <strong>{{ __('Request rejected') }}</strong>
                    <p class="mb-1 mt-1">{{ __('Your upgrade request was not approved.') }}</p>
                    @if($latestProcessedRequest->admin_notes)
                        <p class="mb-2"><strong>{{ __('Admin notes') }}:</strong> {{ $latestProcessedRequest->admin_notes }}</p>
                    @endif
                    <p class="mb-2 small text-muted">{{ __('Processed on') }}: {{ $latestProcessedRequest->processed_at ? $latestProcessedRequest->processed_at->format('Y-m-d H:i') : $latestProcessedRequest->updated_at->format('Y-m-d H:i') }}</p>
                    <a href="{{ route('user.profile.upgrade') }}" class="btn btn-sm btn-outline-danger">{{ __('Submit a new upgrade request') }}</a>
                </div>
            @endif
            </div>
        </div>
        @endif

        {{-- 3. Profile Information --}}
        <div class="profile-card">
            <h6 class="card-title mb-0">
                <span class="collapse-toggle w-100" data-bs-toggle="collapse" data-bs-target="#collapseProfile" aria-expanded="false" aria-controls="collapseProfile">
                    <span>{{ __('Profile Information') }}</span>
                    <span class="collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                </span>
            </h6>
            <div id="collapseProfile" class="collapse collapse-body">
            <form id="editUserForm" name="editUserForm" class="row g-3" action="javascript:void(0)">
                @csrf
                <div class="box w-100">
                    <h6 class="title mb-2">{{ __('Avatar') }}</h6>
                    <div class="box-agent-avt d-flex flex-wrap align-items-center gap-3">
                        <div class="avatar">
                            <img src="{{ $user->avatar_url }}" alt="avatar" loading="lazy" width="128" height="128" class="rounded" id="avatarPreview">
                        </div>
                        <div class="content uploadfile">
                            <p class="mb-1">{{ __('Upload a new avatar') }}</p>
                            <div class="box-ip">
                                <input type="file" name="photo" class="ip-file form-control" accept="image/jpeg,image/png,image/gif">
                            </div>
                            <p class="small text-muted mb-0">{{ __('JPEG, PNG or GIF. Max 2MB.') }}</p>
                        </div>
                    </div>
                </div>

                <h6 class="title w-100 mt-3 mb-2">{{ __('Personal information') }}</h6>
                <div class="box box-fieldset w-100">
                    <label for="name">{{ __('Full name') }} <span class="text-danger">*</span></label>
                    <input type="text" value="{{ $user->name }}" required name="name" id="name" class="form-control style-1">
                </div>
                <div class="box box-fieldset w-100">
                    <label for="desc">{{ __('Description') }}</label>
                    <textarea name="description" id="desc" class="form-control style-1" rows="3">{{ $user->description }}</textarea>
                </div>

                <h6 class="title w-100 mt-3 mb-2">{{ __('Company / Professional') }}</h6>
                <div class="box grid-4 gap-30">
                    <div class="box-fieldset">
                        <label for="company">{{ __('Your Company') }}</label>
                        <input type="text" name="company" id="company" value="{{ $user->company }}" class="form-control style-1">
                    </div>
                    <div class="box-fieldset">
                        <label for="position">{{ __('Position') }}</label>
                        <input type="text" name="position" id="position" value="{{ $user->position }}" class="form-control style-1">
                    </div>
                    <div class="box-fieldset">
                        <label for="job">{{ __('Job') }}</label>
                        <input type="text" name="job" id="job" value="{{ $user->job }}" class="form-control style-1">
                    </div>
                    <div class="box-fieldset">
                        <label for="office_no">{{ __('Office Number') }}</label>
                        <input type="text" name="office_no" id="office_no" value="{{ $user->office_no }}" class="form-control style-1">
                    </div>
                </div>
                <div class="box box-fieldset w-100">
                    <label for="office_address">{{ __('Office Address') }}</label>
                    <input type="text" name="office_address" id="office_address" value="{{ $user->office_address }}" class="form-control style-1">
                </div>

                <h6 class="title w-100 mt-3 mb-2">{{ __('Contact information') }}</h6>
                <div class="box grid-4 gap-30">
                    <div class="box-fieldset">
                        <label for="email">{{ __('Email address') }} <span class="text-danger">*</span></label>
                        <input type="email" name="email" id="email" required value="{{ $user->email }}" class="form-control style-1">
                    </div>
                    <div class="box-fieldset">
                        <label for="mobile">{{ __('Your Phone') }} <span class="text-danger">*</span></label>
                        <input type="text" name="mobile" id="mobile" required value="{{ $user->mobile }}" class="form-control style-1">
                    </div>
                    <div class="box-fieldset">
                        <label for="location">{{ __('Location') }}</label>
                        <input type="text" name="location" id="location" value="{{ $user->location }}" class="form-control style-1">
                    </div>
                </div>

                <h6 class="title w-100 mt-3 mb-2">{{ __('Social links') }} <span class="text-muted small">({{ __('optional') }})</span></h6>
                <div class="box grid-4 gap-30">
                    <div class="box-fieldset">
                        <label for="fb">{{ __('Facebook') }}</label>
                        <input type="url" name="facebook" id="fb" value="{{ $user->facebook }}" class="form-control style-1" placeholder="https://">
                    </div>
                    <div class="box-fieldset">
                        <label for="tw">{{ __('Twitter') }}</label>
                        <input type="url" name="twitter" id="tw" value="{{ $user->twitter }}" class="form-control style-1" placeholder="https://">
                    </div>
                    <div class="box-fieldset">
                        <label for="linkedin">{{ __('LinkedIn') }}</label>
                        <input type="url" name="linkedin" id="linkedin" value="{{ $user->linkedin }}" class="form-control style-1" placeholder="https://">
                    </div>
                </div>

                <div class="box w-100 mt-2">
                    <button type="submit" id="user_update" class="tf-btn primary">{{ __('Save & Update') }}</button>
                </div>
            </form>
            </div>
        </div>

        {{-- 4. Security (Change password) --}}
        <div class="profile-card">
            <h6 class="card-title mb-0">
                <span class="collapse-toggle w-100" data-bs-toggle="collapse" data-bs-target="#collapseSecurity" aria-expanded="false" aria-controls="collapseSecurity">
                    <span>{{ __('Security') }}</span>
                    <span class="collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                </span>
            </h6>
            <div id="collapseSecurity" class="collapse collapse-body">
            <h6 class="title mb-2">{{ __('Change password') }}</h6>
            <form id="formChangePassword" method="POST" action="javascript:void(0)">
                @csrf
                <div class="box grid-3 gap-30">
                    <div class="box-fieldset">
                        <label for="current_password">{{ __('Current password') }} <span class="text-danger">*</span></label>
                        <div class="box-password">
                            <input type="password" id="current_password" name="current_password" class="form-contact style-1 password-field" placeholder="••••••••" autocomplete="current-password">
                            <span class="show-pass"><i class="icon-pass icon-eye"></i><i class="icon-pass icon-eye-off"></i></span>
                        </div>
                    </div>
                    <div class="box-fieldset">
                        <label for="password">{{ __('New password') }} <span class="text-danger">*</span></label>
                        <div class="box-password">
                            <input type="password" class="form-contact style-1 password-field2" id="password" name="password" placeholder="••••••••" autocomplete="new-password" minlength="8">
                            <span class="show-pass2"><i class="icon-pass icon-eye"></i><i class="icon-pass icon-eye-off"></i></span>
                        </div>
                        <p class="small text-muted mb-0">{{ __('At least 8 characters.') }}</p>
                    </div>
                    <div class="box-fieldset">
                        <label for="password_confirmation">{{ __('Confirm new password') }} <span class="text-danger">*</span></label>
                        <div class="box-password">
                            <input type="password" class="form-contact style-1 password-field3" name="password_confirmation" id="password_confirmation" placeholder="••••••••" autocomplete="new-password">
                            <span class="show-pass3"><i class="icon-pass icon-eye"></i><i class="icon-pass icon-eye-off"></i></span>
                        </div>
                    </div>
                </div>
                <div class="box mt-2">
                    <button type="submit" id="update_password" class="tf-btn primary">{{ __('Update Password') }}</button>
                </div>
            </form>
            </div>
        </div>

        {{-- 5. Upgrade requests history --}}
        <div class="profile-card" id="upgrade-history">
            <h6 class="card-title mb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="collapse-toggle" data-bs-toggle="collapse" data-bs-target="#collapseHistory" aria-expanded="false" aria-controls="collapseHistory">
                    <span>{{ __('Upgrade requests history') }}</span>
                    <span class="collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                </span>
                <a href="{{ route('user.profile.upgrade.history') }}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()">{{ __('View all') }}</a>
            </h6>
            <div id="collapseHistory" class="collapse collapse-body">
            @if(isset($upgradeHistory) && $upgradeHistory->isNotEmpty())
                <div class="table-responsive">
                    <table class="table table-bordered history-table">
                        <thead>
                            <tr>
                                <th>{{ __('Request Date') }}</th>
                                <th>{{ __('Requested Plan') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Receipt') }}</th>
                                <th>{{ __('Processed') }}</th>
                                <th>{{ __('Admin notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($upgradeHistory->take(5) as $req)
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
                                        <a href="{{ $req->transfer_receipt_url }}" target="_blank" rel="noopener">{{ __('View') }}</a>
                                    @else
                                        —
                                    @endif
                                </td>
                                <td>{{ $req->processed_at ? $req->processed_at->format('Y-m-d H:i') : '—' }}</td>
                                <td class="small">{{ $req->admin_notes ? Str::limit($req->admin_notes, 40) : '—' }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">{{ __('No upgrade requests yet.') }} <a href="{{ route('user.profile.upgrade') }}">{{ __('Request an upgrade') }}</a></p>
            @endif
            </div>
        </div>

        {{-- 6. Billing & Receipts (unified: plan upgrade + featured listing + 3D tour) --}}
        <div class="profile-card">
            <h6 class="card-title mb-0 d-flex justify-content-between align-items-center flex-wrap gap-2">
                <span class="collapse-toggle" data-bs-toggle="collapse" data-bs-target="#collapseBilling" aria-expanded="false" aria-controls="collapseBilling">
                    <span>{{ __('Billing & receipts') }}</span>
                    <span class="collapse-icon"><svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><polyline points="6 9 12 15 18 9"></polyline></svg></span>
                </span>
                <a href="{{ route('user.invoices.index') }}" class="btn btn-sm btn-outline-primary" onclick="event.stopPropagation()">{{ __('View all') }}</a>
            </h6>
            <div id="collapseBilling" class="collapse collapse-body">
            <p class="text-muted small mb-2">{{ __('Plan upgrades, featured listings, and 3D tour payments.') }}</p>
            @if(isset($billingItems) && $billingItems->isNotEmpty())
                @php
                    $totalCount = $billingItems->count();
                    $pendingCount = $billingItems->where('status', 'pending')->count();
                    $approvedCount = $billingItems->where('status', 'approved')->count();
                    $rejectedCount = $billingItems->where('status', 'rejected')->count();
                @endphp
                <div class="row g-2 mb-3">
                    <div class="col-6 col-md-3"><div class="border rounded p-2 text-center small"><strong>{{ $totalCount }}</strong><br>{{ __('Total') }}</div></div>
                    <div class="col-6 col-md-3"><div class="border rounded p-2 text-center small"><strong>{{ $pendingCount }}</strong><br>{{ __('Pending') }}</div></div>
                    <div class="col-6 col-md-3"><div class="border rounded p-2 text-center small text-success"><strong>{{ $approvedCount }}</strong><br>{{ __('Approved') }}</div></div>
                    <div class="col-6 col-md-3"><div class="border rounded p-2 text-center small text-danger"><strong>{{ $rejectedCount }}</strong><br>{{ __('Rejected') }}</div></div>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered history-table">
                        <thead>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <th>{{ __('Type') }}</th>
                                <th>{{ __('Related Item') }}</th>
                                <th>{{ __('Status') }}</th>
                                <th>{{ __('Receipt') }}</th>
                                <th>{{ __('Notes') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($billingItems->take(10) as $item)
                            <tr>
                                <td>{{ $item->date ? $item->date->format('Y-m-d H:i') : '—' }}</td>
                                <td>{{ $item->type_label ?? '—' }}</td>
                                <td>{{ $item->related_item ?? '—' }}</td>
                                <td>
                                    @if(($item->status ?? '') === 'approved')
                                        <span class="badge bg-success">{{ __('Approved') }}</span>
                                    @elseif(($item->status ?? '') === 'rejected')
                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                    @else
                                        <span class="badge bg-warning text-dark">{{ __('Pending') }}</span>
                                    @endif
                                </td>
                                <td>
                                    @if(!empty($item->receipt_url))
                                        <a href="{{ $item->receipt_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">{{ __('View') }}</a>
                                    @elseif(!empty($item->receipt_path))
                                        @php $url = \App\Http\Controllers\UserDashboard\InvoicesController::normalizeReceiptUrl($item->receipt_path); @endphp
                                        @if($url)<a href="{{ $url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-secondary">{{ __('View') }}</a>@else—@endif
                                    @else
                                        —
                                    @endif
                                </td>
                                <td class="small">{{ Str::limit($item->notes ?? '—', 35) }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <p class="text-muted mb-0">{{ __('No billing records yet.') }} <a href="{{ route('user.invoices.index') }}">{{ __('My Invoices') }}</a></p>
            @endif
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var data_url_user = '{{ route('user.profile.update') }}';
        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj) obj.messages = obj.responseJSON;
                $('input, select, textarea').each(function () {
                    var parent = $(this).parents('.fv-row, .input-group').first();
                    var name = $(this).attr('name');
                    if (obj.messages && obj.messages[name] && $(this).attr('type') !== 'hidden') {
                        var error_message = '<div class="custom-error"><p>' + obj.messages[name][0] + '</p></div>';
                        if (parent.length) parent.append(error_message); else $(this).after(error_message);
                        $(this).addClass('error');
                    }
                });
            }
            $('#editUserForm').validate({ rules: { name: 'required', email: { required: true, email: true }, mobile: 'required' } });
            $('#formChangePassword').validate({
                rules: {
                    current_password: 'required',
                    password: { required: true, minlength: 8 },
                    password_confirmation: { required: true, equalTo: '#password' }
                },
                messages: {
                    password: { minlength: '{{ __("At least 8 characters.") }}' },
                    password_confirmation: { equalTo: '{{ __("Passwords do not match.") }}' }
                }
            });

            $('#editUserForm').submit(function() {
                var myform = $('#editUserForm');
                if (!myform.valid()) return false;
                var postData = new FormData($('form#editUserForm')[0]);
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $('#user_update').html('<span class="spinner-border spinner-border-sm align-middle me-2"></span>{{ __("Saving") }}...');
                $.ajax({
                    url: data_url_user,
                    type: 'POST',
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#user_update').html('{{ __("Save & Update") }}');
                        toastr.success(response.success);
                        $('.custom-error').remove(); $('.error').removeClass('error');
                        var src = $('input[name=photo]')[0].files.length ? URL.createObjectURL($('input[name=photo]')[0].files[0]) : $('#avatarPreview').attr('src');
                        if ($('input[name=photo]')[0].files.length) $('#avatarPreview').attr('src', src);
                    },
                    error: function (data) {
                        $('.custom-error').remove();
                        $('#user_update').html('{{ __("Save & Update") }}');
                        var response = data.responseJSON;
                        if (data.status == 422 && response && response.responseJSON) {
                            myHandel(response);
                            toastr.error(response.message || '{{ __("Please fix the errors.") }}');
                        } else {
                            swal.fire({ icon: 'error', title: (response && response.message) ? response.message : '{{ __("Error") }}' });
                        }
                    }
                });
            });

            $('input[name=photo]').on('change', function() {
                if (this.files && this.files[0]) {
                    $('#avatarPreview').attr('src', URL.createObjectURL(this.files[0]));
                }
            });

            var data_url_pw = '{{ route('user.profile.update_password') }}';
            $('#formChangePassword').submit(function() {
                var myform = $('#formChangePassword');
                if (!myform.valid()) return false;
                var postData = new FormData(myform[0]);
                $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
                $('#update_password').html('<span class="spinner-border spinner-border-sm align-middle me-2"></span>{{ __("Updating") }}...');
                $.ajax({
                    url: data_url_pw,
                    type: 'POST',
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#update_password').html('{{ __("Update Password") }}');
                        toastr.success(response.success);
                        myform[0].reset();
                        $('.custom-error').remove(); $('.error').removeClass('error');
                    },
                    error: function (data) {
                        $('.custom-error').remove();
                        $('#update_password').html('{{ __("Update Password") }}');
                        var response = data.responseJSON;
                        if (data.status == 422 && response && response.responseJSON) {
                            myHandel(response);
                            toastr.error(response.message || '{{ __("Please fix the errors.") }}');
                        } else {
                            swal.fire({ icon: 'error', title: (response && response.message) ? response.message : '{{ __("Error") }}' });
                        }
                    }
                });
            });

            $('.show-pass').on('click', function() { var inp = $(this).siblings('input'); inp.attr('type', inp.attr('type') === 'password' ? 'text' : 'password'); });
            $('.show-pass2').on('click', function() { var inp = $(this).siblings('input'); inp.attr('type', inp.attr('type') === 'password' ? 'text' : 'password'); });
            $('.show-pass3').on('click', function() { var inp = $(this).siblings('input'); inp.attr('type', inp.attr('type') === 'password' ? 'text' : 'password'); });
        });
    </script>
@endsection
