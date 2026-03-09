@extends('dashboard.layouts.app')
@section('style')
    <style>.card { padding: 1.5rem !important; }</style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Subscriptions') }}</li>
            </ol>
        </nav>

        <div class="row g-4 mb-4">
            <div class="col-sm-6 col-xl-3">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'active_paid']) }}" class="text-decoration-none">
                    <div class="card h-100 border-primary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-primary">
                                        <i class="ti ti-check ti-md"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $summary['active_paid'] }}</h5>
                                    <small class="text-muted">{{ __('Active Paid') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'expiring_soon']) }}" class="text-decoration-none">
                    <div class="card h-100 border-warning">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-warning">
                                        <i class="ti ti-clock ti-md"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $summary['expiring_soon'] }}</h5>
                                    <small class="text-muted">{{ __('Expiring Soon') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'expired']) }}" class="text-decoration-none">
                    <div class="card h-100 border-danger">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-danger">
                                        <i class="ti ti-x ti-md"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $summary['expired'] }}</h5>
                                    <small class="text-muted">{{ __('Expired') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-sm-6 col-xl-3">
                <a href="{{ request()->fullUrlWithQuery(['filter' => 'on_basic']) }}" class="text-decoration-none">
                    <div class="card h-100 border-secondary">
                        <div class="card-body">
                            <div class="d-flex align-items-center">
                                <div class="avatar flex-shrink-0 me-3">
                                    <span class="avatar-initial rounded bg-label-secondary">
                                        <i class="ti ti-users ti-md"></i>
                                    </span>
                                </div>
                                <div>
                                    <h5 class="mb-0">{{ $summary['on_basic'] }}</h5>
                                    <small class="text-muted">{{ __('On Basic Plan') }}</small>
                                </div>
                            </div>
                        </div>
                    </div>
                </a>
            </div>
        </div>

        <div class="card">
            <div class="card-header d-flex flex-wrap justify-content-between gap-2 align-items-center">
                <h5 class="mb-0">{{ __('Subscription Monitoring') }}</h5>
            </div>
            <div class="card-body">
                <form method="get" class="mb-4">
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Filter') }}</label>
                            <select name="filter" class="form-select">
                                <option value="all" {{ ($filter ?? '') == 'all' ? 'selected' : '' }}>{{ __('All') }}</option>
                                <option value="active_paid" {{ ($filter ?? '') == 'active_paid' ? 'selected' : '' }}>{{ __('Active Paid') }}</option>
                                <option value="expiring_soon" {{ ($filter ?? '') == 'expiring_soon' ? 'selected' : '' }}>{{ __('Expiring Soon') }}</option>
                                <option value="expired" {{ ($filter ?? '') == 'expired' ? 'selected' : '' }}>{{ __('Expired') }}</option>
                                <option value="on_basic" {{ ($filter ?? '') == 'on_basic' ? 'selected' : '' }}>{{ __('On Basic Plan') }}</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Plan') }}</label>
                            <select name="plan_id" class="form-select">
                                <option value="">{{ __('All Plans') }}</option>
                                @foreach($plans as $p)
                                    <option value="{{ $p->id }}" {{ ($planId ?? '') == $p->id ? 'selected' : '' }}>{{ $p->title }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label">{{ __('Sort') }}</label>
                            <select name="sort" class="form-select">
                                <option value="expiry" {{ ($sort ?? 'expiry') == 'expiry' ? 'selected' : '' }}>{{ __('Nearest expiry') }}</option>
                                <option value="name" {{ ($sort ?? '') == 'name' ? 'selected' : '' }}>{{ __('Name') }}</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label">{{ __('Search') }}</label>
                            <input type="text" name="search" class="form-control" placeholder="{{ __('Name or email') }}" value="{{ $search ?? '' }}">
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">{{ __('Filter') }}</button>
                        </div>
                    </div>
                </form>

                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                        <tr>
                            <th>{{ __('User') }}</th>
                            <th>{{ __('Email') }}</th>
                            <th>{{ __('Current Plan') }}</th>
                            <th>{{ __('Last Paid Plan') }}</th>
                            <th>{{ __('Start Date') }}</th>
                            <th>{{ __('End Date') }}</th>
                            <th>{{ __('Days Left') }}</th>
                            <th>{{ __('Status') }}</th>
                            <th>{{ __('Property usage') }}</th>
                            <th>{{ __('Action') }}</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($usersPaginator as $user)
                            @php $st = $user->subscription_status ?? []; @endphp
                            <tr>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ $st['plan']?->title ?? '—' }}</td>
                                <td>{{ $st['last_plan']?->title ?? '—' }}</td>
                                <td>{{ $st['subscription_started_at']?->format('Y-m-d') ?? '—' }}</td>
                                <td>{{ $st['subscription_ends_at']?->format('Y-m-d') ?? '—' }}</td>
                                <td>{{ $st['days_remaining'] !== null ? $st['days_remaining'] : '—' }}</td>
                                <td>
                                    @php
                                        $statusLabels = [
                                            \App\Services\SubscriptionService::STATUS_ACTIVE_PAID => ['label' => __('Active Paid'), 'class' => 'success'],
                                            \App\Services\SubscriptionService::STATUS_EXPIRING_SOON => ['label' => __('Expiring Soon'), 'class' => 'warning'],
                                            \App\Services\SubscriptionService::STATUS_EXPIRED => ['label' => __('Expired'), 'class' => 'danger'],
                                            \App\Services\SubscriptionService::STATUS_ON_BASIC => ['label' => __('On Basic Plan'), 'class' => 'secondary'],
                                        ];
                                        $info = $statusLabels[$st['status'] ?? ''] ?? ['label' => $st['status'] ?? '—', 'class' => 'secondary'];
                                    @endphp
                                    <span class="badge bg-{{ $info['class'] }}">{{ $info['label'] }}</span>
                                </td>
                                <td>{{ $st['property_count'] ?? 0 }}</td>
                                <td>
                                    <a href="{{ route('admin.users.profile', $user->id) }}" class="btn btn-sm btn-icon btn-outline-primary">
                                        <i class="ti ti-eye"></i>
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="d-flex justify-content-center mt-4">
                    {{ $usersPaginator->links('pagination::bootstrap-5') }}
                </div>
            </div>
        </div>
    </div>
@endsection
