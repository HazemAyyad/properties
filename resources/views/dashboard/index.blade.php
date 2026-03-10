@extends('dashboard.layouts.app')
@section('style')
    <style>
        .card { padding: 1rem 1.25rem !important; }
        .stat-card-link { text-decoration: none; color: inherit; display: block; }
        .stat-card-link:hover { color: inherit; }
        .alert-dashboard { border-left-width: 4px; }
        .quick-action-card { transition: transform 0.15s ease; }
        .quick-action-card:hover { transform: translateY(-2px); }
    </style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item active">{{ __('Dashboard') }}</li>
            </ol>
        </nav>

        {{-- Alerts: Admin attention --}}
        @if(array_sum($alerts) > 0)
            <div class="row mb-4">
                <div class="col-12">
                    <div class="card border-warning alert-dashboard">
                        <div class="card-body">
                            <h6 class="card-title text-warning mb-3"><i class="ti ti-alert-triangle me-2"></i>{{ __('Needs your attention') }}</h6>
                            <div class="d-flex flex-wrap gap-3">
                                @if($alerts['pending_properties'] > 0)
                                    <a href="{{ route('admin.properties.index', '0') }}" class="btn btn-sm btn-warning">
                                        {{ __('Pending Properties') }}: <strong>{{ $alerts['pending_properties'] }}</strong>
                                    </a>
                                @endif
                                @if($alerts['pending_plan_upgrades'] > 0)
                                    <a href="{{ route('admin.plan-upgrade-requests.index') }}" class="btn btn-sm btn-warning">
                                        {{ __('Plan Upgrade Requests') }}: <strong>{{ $alerts['pending_plan_upgrades'] }}</strong>
                                    </a>
                                @endif
                                @if($alerts['pending_featured_listing'] > 0)
                                    <a href="{{ route('admin.properties.featured-listings') }}" class="btn btn-sm btn-warning">
                                        {{ __('Featured Listing requests') }}: <strong>{{ $alerts['pending_featured_listing'] }}</strong>
                                    </a>
                                @endif
                                @if($alerts['pending_featured_3d'] > 0)
                                    <a href="{{ route('admin.properties.featured-3d-tours') }}" class="btn btn-sm btn-warning">
                                        {{ __('3D Tour requests') }}: <strong>{{ $alerts['pending_featured_3d'] }}</strong>
                                    </a>
                                @endif
                                @if($alerts['expired_subscriptions'] > 0)
                                    <a href="{{ route('admin.subscriptions.index') }}?filter=expired" class="btn btn-sm btn-danger">
                                        {{ __('Expired Subscriptions') }}: <strong>{{ $alerts['expired_subscriptions'] }}</strong>
                                    </a>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        {{-- Summary cards row 1 --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.users.index') }}" class="stat-card-link">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-users ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['total_users'] }}</h5>
                                <small class="text-muted">{{ __('Total Users') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.properties.index', '1') }}" class="stat-card-link">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class="ti ti-building ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['total_properties'] }}</h5>
                                <small class="text-muted">{{ __('Total Properties') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.properties.index', '1') }}" class="stat-card-link">
                    <div class="card h-100 border-success">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="ti ti-check ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['approved_properties'] }}</h5>
                                <small class="text-muted">{{ __('Approved') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.properties.index', '0') }}" class="stat-card-link">
                    <div class="card h-100 border-warning">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-clock ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['pending_properties'] }}</h5>
                                <small class="text-muted">{{ __('Pending') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        {{-- Summary cards row 2 --}}
        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.properties.index', '2') }}" class="stat-card-link">
                    <div class="card h-100 border-danger">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-x ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['rejected_properties'] }}</h5>
                                <small class="text-muted">{{ __('Rejected') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.properties.featured-listings') }}" class="stat-card-link">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-primary"><i class="ti ti-star ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['featured_listings'] }}</h5>
                                <small class="text-muted">{{ __('Featured Listings') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.properties.featured-3d-tours') }}" class="stat-card-link">
                    <div class="card h-100">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-info"><i class="ti ti-video ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['featured_3d_tours'] }}</h5>
                                <small class="text-muted">{{ __('Featured 3D Tours') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.subscriptions.index') }}" class="stat-card-link">
                    <div class="card h-100 border-success">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-success"><i class="ti ti-credit-card ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['active_paid_subscriptions'] }}</h5>
                                <small class="text-muted">{{ __('Active Paid') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.subscriptions.index') }}?filter=expired" class="stat-card-link">
                    <div class="card h-100 border-danger">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-danger"><i class="ti ti-clock-off ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['expired_subscriptions'] }}</h5>
                                <small class="text-muted">{{ __('Expired') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ route('admin.plan-upgrade-requests.index') }}" class="stat-card-link">
                    <div class="card h-100 border-warning">
                        <div class="card-body d-flex align-items-center">
                            <div class="avatar flex-shrink-0 me-3">
                                <span class="avatar-initial rounded bg-label-warning"><i class="ti ti-file-upload ti-md"></i></span>
                            </div>
                            <div>
                                <h5 class="mb-0">{{ $cards['pending_plan_upgrades'] }}</h5>
                                <small class="text-muted">{{ __('Pending Upgrades') }}</small>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="row g-4">
            {{-- Latest Pending Properties --}}
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Latest Pending Properties') }}</h5>
                        <a href="{{ route('admin.properties.index', '0') }}" class="btn btn-sm btn-primary">{{ __('View all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        @if($latestPendingProperties->isEmpty())
                            <p class="text-muted p-4 mb-0">{{ __('No pending properties') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Title') }}</th>
                                            <th>{{ __('Owner') }}</th>
                                            <th>{{ __('Date') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestPendingProperties->take(5) as $p)
                                            <tr>
                                                <td><span class="text-truncate d-inline-block" style="max-width:180px">{{ $p->title }}</span></td>
                                                <td>{{ $p->user ? $p->user->name : '—' }}</td>
                                                <td>{{ $p->created_at ? $p->created_at->format('Y-m-d') : '—' }}</td>
                                                <td>
                                                    <a href="{{ route('admin.properties.edit', $p->id) }}" class="btn btn-sm btn-icon btn-outline-primary"><i class="ti ti-eye"></i></a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Recent Payment / Upgrade Requests --}}
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Recent Payment / Upgrade Requests') }}</h5>
                        <a href="{{ route('admin.plan-upgrade-requests.index') }}" class="btn btn-sm btn-primary">{{ __('View all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        @if($recentPaymentRequests->isEmpty())
                            <p class="text-muted p-4 mb-0">{{ __('No recent requests') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Type') }}</th>
                                            <th>{{ __('Related') }}</th>
                                            <th>{{ __('Status') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($recentPaymentRequests->take(5) as $r)
                                            <tr>
                                                <td>{{ $r->user ? $r->user->name : '—' }}</td>
                                                <td>
                                                    @if($r->type === 'subscription') {{ __('Subscription') }}
                                                    @elseif($r->type === 'featured_listing') {{ __('Featured Listing') }}
                                                    @else {{ __('3D Tour') }}
                                                    @endif
                                                </td>
                                                <td><span class="text-truncate d-inline-block" style="max-width:120px">{{ $r->related }}</span></td>
                                                <td>
                                                    @if($r->status === 'pending')
                                                        <span class="badge bg-warning">{{ __('Pending') }}</span>
                                                    @elseif($r->status === 'accepted')
                                                        <span class="badge bg-success">{{ __('Approved') }}</span>
                                                    @else
                                                        <span class="badge bg-danger">{{ __('Rejected') }}</span>
                                                    @endif
                                                </td>
                                                <td><a href="{{ $r->url }}" class="btn btn-sm btn-icon btn-outline-primary"><i class="ti ti-arrow-right"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Subscriptions Expiring Soon --}}
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Subscriptions Expiring Soon') }}</h5>
                        <a href="{{ route('admin.subscriptions.index') }}?filter=expiring_soon" class="btn btn-sm btn-primary">{{ __('View all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        @if($expiringSoonUsers->isEmpty())
                            <p class="text-muted p-4 mb-0">{{ __('None') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('User') }}</th>
                                            <th>{{ __('Plan') }}</th>
                                            <th>{{ __('End date') }}</th>
                                            <th>{{ __('Days left') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($expiringSoonUsers->take(5) as $e)
                                            <tr>
                                                <td>{{ $e->user->name ?? '—' }}</td>
                                                <td>{{ $e->plan ? $e->plan->title : '—' }}</td>
                                                <td>{{ $e->ends_at ? $e->ends_at->format('Y-m-d') : '—' }}</td>
                                                <td><span class="badge bg-warning">{{ $e->days_left ?? '—' }}</span></td>
                                                <td><a href="{{ route('admin.subscriptions.index') }}?filter=expiring_soon" class="btn btn-sm btn-icon btn-outline-primary"><i class="ti ti-arrow-right"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Latest Registered Users --}}
            <div class="col-lg-6 mb-4">
                <div class="card h-100">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Latest Registered Users') }}</h5>
                        <a href="{{ route('admin.users.index') }}" class="btn btn-sm btn-primary">{{ __('View all') }}</a>
                    </div>
                    <div class="card-body p-0">
                        @if($latestUsers->isEmpty())
                            <p class="text-muted p-4 mb-0">{{ __('No users yet') }}</p>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead>
                                        <tr>
                                            <th>{{ __('Name') }}</th>
                                            <th>{{ __('Email') }}</th>
                                            <th>{{ __('Joined') }}</th>
                                            <th>{{ __('Plan') }}</th>
                                            <th></th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($latestUsers->take(5) as $u)
                                            <tr>
                                                <td>{{ $u->name }}</td>
                                                <td><span class="text-truncate d-inline-block" style="max-width:140px">{{ $u->email }}</span></td>
                                                <td>{{ $u->created_at ? $u->created_at->format('Y-m-d') : '—' }}</td>
                                                <td>{{ $u->plan ? $u->plan->title : '—' }}</td>
                                                <td><a href="{{ route('admin.users.edit', $u->id) }}" class="btn btn-sm btn-icon btn-outline-primary"><i class="ti ti-eye"></i></a></td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <h5 class="mb-3">{{ __('Quick Actions') }}</h5>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.properties.create') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-plus ti-lg mb-2 d-block"></i>
                        <small>{{ __('Add Property') }}</small>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.properties.index', '0') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-clock ti-lg mb-2 d-block"></i>
                        <small>{{ __('Pending Properties') }}</small>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.plan-upgrade-requests.index') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-file-upload ti-lg mb-2 d-block"></i>
                        <small>{{ __('Plan Upgrades') }}</small>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.subscriptions.index') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-credit-card ti-lg mb-2 d-block"></i>
                        <small>{{ __('Subscriptions') }}</small>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.users.index') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-users ti-lg mb-2 d-block"></i>
                        <small>{{ __('Users') }}</small>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.settings.page', 'settings') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-settings ti-lg mb-2 d-block"></i>
                        <small>{{ __('Settings') }}</small>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-md-4 col-lg-2">
                <a href="{{ route('admin.plans.index') }}" class="card quick-action-card h-100 text-center text-decoration-none text-body">
                    <div class="card-body py-4">
                        <i class="ti ti-list ti-lg mb-2 d-block"></i>
                        <small>{{ __('Plans') }}</small>
                    </div>
                </a>
            </div>
        </div>

        {{-- Subscriptions overview (compact) --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Subscriptions Overview') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col"><span class="badge bg-success">{{ $subscriptionOverview['active_paid'] }}</span> {{ __('Active Paid') }}</div>
                            <div class="col"><span class="badge bg-warning">{{ $subscriptionOverview['expiring_soon'] }}</span> {{ __('Expiring Soon') }}</div>
                            <div class="col"><span class="badge bg-danger">{{ $subscriptionOverview['expired'] }}</span> {{ __('Expired') }}</div>
                            <div class="col"><span class="badge bg-secondary">{{ $subscriptionOverview['on_basic'] }}</span> {{ __('On Basic Plan') }}</div>
                            <div class="col"><span class="badge bg-warning">{{ $subscriptionOverview['pending_upgrades'] }}</span> {{ __('Pending Upgrades') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Users overview (compact) --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Users Overview') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col"><strong>{{ $usersOverview['total'] }}</strong> {{ __('Total') }}</div>
                            <div class="col"><strong>{{ $usersOverview['new_this_month'] }}</strong> {{ __('New this month') }}</div>
                            <div class="col"><strong>{{ $usersOverview['with_active_paid'] }}</strong> {{ __('With active paid plan') }}</div>
                            <div class="col"><strong>{{ $usersOverview['on_basic'] }}</strong> {{ __('On basic plan') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Simple stats / charts --}}
        @php
            $totalProp = $chartPropertiesByStatus['approved'] + $chartPropertiesByStatus['pending'] + $chartPropertiesByStatus['rejected'];
            $totalProp = $totalProp ?: 1;
        @endphp
        <div class="row g-4 mb-4">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('Properties by status') }}</h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ __('Approved') }}</span>
                                <span>{{ $chartPropertiesByStatus['approved'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-success" style="width: {{ 100 * $chartPropertiesByStatus['approved'] / $totalProp }}%"></div>
                            </div>
                        </div>
                        <div class="mb-2">
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ __('Pending') }}</span>
                                <span>{{ $chartPropertiesByStatus['pending'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-warning" style="width: {{ 100 * $chartPropertiesByStatus['pending'] / $totalProp }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between small mb-1">
                                <span>{{ __('Rejected') }}</span>
                                <span>{{ $chartPropertiesByStatus['rejected'] }}</span>
                            </div>
                            <div class="progress" style="height: 8px;">
                                <div class="progress-bar bg-danger" style="width: {{ 100 * $chartPropertiesByStatus['rejected'] / $totalProp }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('Last 30 days') }}</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><strong>{{ $chartNewUsersLast30 }}</strong> {{ __('New users') }}</p>
                        <p class="mb-0"><strong>{{ $chartUpgradeRequestsLast30 }}</strong> {{ __('Plan upgrade requests') }}</p>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="mb-0">{{ __('Subscription distribution') }}</h6>
                    </div>
                    <div class="card-body">
                        <p class="mb-2"><span class="badge bg-success">{{ $subscriptionOverview['active_paid'] }}</span> {{ __('Active paid') }}</p>
                        <p class="mb-2"><span class="badge bg-warning">{{ $subscriptionOverview['expiring_soon'] }}</span> {{ __('Expiring soon') }}</p>
                        <p class="mb-2"><span class="badge bg-danger">{{ $subscriptionOverview['expired'] }}</span> {{ __('Expired') }}</p>
                        <p class="mb-0"><span class="badge bg-secondary">{{ $subscriptionOverview['on_basic'] }}</span> {{ __('On basic') }}</p>
                    </div>
                </div>
            </div>
        </div>

        {{-- CMS / Content health --}}
        <div class="row g-4 mb-4">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="mb-0">{{ __('Content health') }}</h5>
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-md-2 col-4"><a href="{{ route('admin.blogs.index') }}">{{ $cmsHealth['blogs_count'] }}</a> {{ __('Blogs') }}</div>
                            <div class="col-md-2 col-4"><a href="{{ route('admin.faqs.index') }}">{{ $cmsHealth['faqs_count'] }}</a> {{ __('FAQs') }}</div>
                            <div class="col-md-2 col-4"><a href="{{ route('admin.services.index') }}">{{ $cmsHealth['services_count'] }}</a> {{ __('Services') }}</div>
                            <div class="col-md-2 col-4"><a href="{{ route('admin.policies.index') }}">{{ $cmsHealth['policies_count'] }}</a> {{ __('Policies') }}</div>
                            <div class="col-md-2 col-4">{{ $cmsHealth['vision_goals_count'] }} {{ __('Vision goals') }}</div>
                            <div class="col-md-2 col-4">{{ $cmsHealth['vision_section_exists'] ? __('Yes') : __('No') }} {{ __('Vision section') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
