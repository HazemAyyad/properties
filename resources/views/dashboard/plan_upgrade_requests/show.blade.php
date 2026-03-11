@extends('dashboard.layouts.app')
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item"><a href="{{ route('admin.plan-upgrade-requests.index') }}">{{ __('Plan Upgrade Requests') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Request') }} #{{ $request->id }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-lg-8">
                {{-- 1. Request Overview Card --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center flex-wrap gap-2">
                        <h5 class="mb-0">{{ __('Request Overview') }}</h5>
                        <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'accepted' ? 'success' : 'danger') }} text-capitalize">
                            {{ __($request->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-borderless mb-0">
                            <tr>
                                <th width="180" class="text-muted">{{ __('Request ID') }}</th>
                                <td>#{{ $request->id }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('Requested plan') }}</th>
                                <td>{{ $request->plan ? $request->plan->title : '—' }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('Submitted at') }}</th>
                                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            <tr>
                                <th class="text-muted">{{ __('Processed at') }}</th>
                                <td>{{ $request->processed_at ? $request->processed_at->format('Y-m-d H:i') : '—' }}</td>
                            </tr>
                            @if($request->admin_notes)
                            <tr>
                                <th class="text-muted">{{ __('Admin notes') }}</th>
                                <td>{{ $request->admin_notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                {{-- 2. User Information Card --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">{{ __('User Information') }}</h5></div>
                    <div class="card-body">
                        @if($request->user)
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th width="180" class="text-muted">{{ __('Name') }}</th>
                                    <td>
                                        <a href="{{ route('admin.users.edit', $request->user->id) }}" class="fw-semibold">{{ $request->user->name }}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Email') }}</th>
                                    <td>{{ $request->user->email }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Phone') }}</th>
                                    <td>{{ $request->user->mobile ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Current plan') }}</th>
                                    <td>{{ $request->user->plan ? $request->user->plan->title : '—' }}</td>
                                </tr>
                                @if($request->user->subscription_started_at || $request->user->subscription_ends_at)
                                <tr>
                                    <th class="text-muted">{{ __('Subscription period') }}</th>
                                    <td>
                                        @if($request->user->subscription_started_at) {{ $request->user->subscription_started_at->format('Y-m-d') }} @endif
                                        @if($request->user->subscription_started_at && $request->user->subscription_ends_at) — @endif
                                        @if($request->user->subscription_ends_at) {{ $request->user->subscription_ends_at->format('Y-m-d') }} @endif
                                    </td>
                                </tr>
                                @endif
                            </table>
                        @else
                            <p class="text-muted mb-0">{{ __('User not found.') }}</p>
                        @endif
                    </div>
                </div>

                {{-- 3. Requested Plan Details Card --}}
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">{{ __('Requested Plan Details') }}</h5></div>
                    <div class="card-body">
                        @if($request->plan)
                            <table class="table table-borderless mb-0">
                                <tr>
                                    <th width="180" class="text-muted">{{ __('Plan name') }}</th>
                                    <td>{{ $request->plan->title }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Description') }}</th>
                                    <td>{{ $request->plan->description ?? '—' }}</td>
                                </tr>
                                @if($request->plan->duration_months)
                                <tr>
                                    <th class="text-muted">{{ __('Duration') }}</th>
                                    <td>{{ $request->plan->duration_months }} {{ $request->plan->duration_months == 1 ? __('month') : __('months') }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <th class="text-muted">{{ __('Property limit') }}</th>
                                    <td>{{ $request->plan->number_of_properties_display ?? '—' }}</td>
                                </tr>
                                <tr>
                                    <th class="text-muted">{{ __('Price') }}</th>
                                    <td>{{ $request->plan->price_monthly ?? '—' }} JOD {{ $request->plan->duration_months ? '/ ' . __('month') : '' }}</td>
                                </tr>
                            </table>
                            @if($request->plan->features && $request->plan->features->isNotEmpty())
                                <hr>
                                <p class="small text-muted mb-1">{{ __('Plan features') }}</p>
                                <ul class="mb-0 small">
                                    @foreach($request->plan->features as $f)
                                        @if($f->status != 0)
                                            <li>{{ $f->title }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            @endif
                        @else
                            <p class="text-muted mb-0">{{ __('Plan not found.') }}</p>
                        @endif
                    </div>
                </div>

                {{-- 4. Transfer Receipt Preview --}}
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Transfer receipt') }}</h5>
                        @if($request->transfer_receipt_url)
                            <a href="{{ $request->transfer_receipt_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-outline-primary">{{ __('Open full receipt') }}</a>
                        @endif
                    </div>
                    <div class="card-body text-center">
                        @if($request->transfer_receipt_url)
                            <img src="{{ $request->transfer_receipt_url }}" alt="{{ __('Transfer receipt') }}" class="img-fluid rounded" style="max-height: 420px;">
                        @else
                            <p class="text-muted mb-0">{{ __('No receipt uploaded.') }}</p>
                        @endif
                    </div>
                </div>

                {{-- 5. User Request History --}}
                @if(isset($userOtherRequests) && $userOtherRequests->isNotEmpty())
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">{{ __('Same user – other upgrade requests') }}</h5></div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead>
                                    <tr>
                                        <th>{{ __('Date') }}</th>
                                        <th>{{ __('Requested plan') }}</th>
                                        <th>{{ __('Status') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($userOtherRequests as $other)
                                    <tr>
                                        <td>{{ $other->created_at->format('Y-m-d H:i') }}</td>
                                        <td>{{ $other->plan ? $other->plan->title : '—' }}</td>
                                        <td>
                                            <span class="badge bg-{{ $other->status === 'pending' ? 'warning' : ($other->status === 'accepted' ? 'success' : 'danger') }} text-capitalize">
                                                {{ __($other->status) }}
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
                @endif
            </div>

            <div class="col-lg-4">
                {{-- Context alerts --}}
                @if(isset($userHasOtherPending) && $userHasOtherPending)
                    <div class="alert alert-warning mb-4">
                        <strong>{{ __('Note') }}</strong><br>
                        {{ __('This user has another pending upgrade request.') }}
                    </div>
                @endif
                @if(isset($requestedPlanAlreadyActive) && $requestedPlanAlreadyActive && $request->isPending())
                    <div class="alert alert-info mb-4">
                        <strong>{{ __('Note') }}</strong><br>
                        {{ __('The requested plan is already the user’s current plan.') }}
                    </div>
                @endif

                {{-- 5. Actions Area --}}
                @if($request->isPending())
                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">{{ __('Actions') }}</h5></div>
                    <div class="card-body">
                        <form id="formReject" class="mb-3">
                            @csrf
                            <label for="admin_notes" class="form-label">{{ __('Notes (optional)') }}</label>
                            <textarea name="admin_notes" id="admin_notes" class="form-control mb-2" rows="2" placeholder="{{ __('Reason for rejection or notes') }}"></textarea>
                            <button type="button" id="btnReject" class="btn btn-danger w-100">{{ __('Reject') }}</button>
                        </form>
                        <hr>
                        <form id="formAccept">
                            @csrf
                            <label for="accept_notes" class="form-label">{{ __('Notes (optional)') }}</label>
                            <textarea name="admin_notes" id="accept_notes" class="form-control mb-2" rows="2"></textarea>
                            <button type="button" id="btnAccept" class="btn btn-success w-100">{{ __('Accept & upgrade user plan') }}</button>
                        </form>
                    </div>
                </div>
                @else
                <div class="card mb-4">
                    <div class="card-body text-center">
                        <p class="text-muted mb-2">{{ __('This request has already been processed.') }}</p>
                        <span class="badge bg-{{ $request->status === 'accepted' ? 'success' : 'danger' }} text-capitalize">{{ __($request->status) }}</span>
                    </div>
                </div>
                @endif

                <a href="{{ route('admin.plan-upgrade-requests.index') }}" class="btn btn-outline-secondary w-100">{{ __('Back to requests list') }}</a>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var acceptUrl = '{{ route("admin.plan-upgrade-requests.accept", $request->id) }}';
        var rejectUrl = '{{ route("admin.plan-upgrade-requests.reject", $request->id) }}';

        $('#btnAccept').on('click', function () {
            var btn = $(this);
            var notes = $('#accept_notes').val();
            btn.prop('disabled', true);
            $.ajax({
                url: acceptUrl,
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), admin_notes: notes },
                success: function (res) {
                    Swal.fire({ icon: 'success', title: res.success });
                    setTimeout(function () { location.reload(); }, 1000);
                },
                error: function (xhr) {
                    btn.prop('disabled', false);
                    Swal.fire({ icon: 'error', title: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ __("Error") }}' });
                }
            });
        });

        $('#btnReject').on('click', function () {
            var btn = $(this);
            var notes = $('#admin_notes').val();
            btn.prop('disabled', true);
            $.ajax({
                url: rejectUrl,
                type: 'POST',
                data: { _token: $('meta[name="csrf-token"]').attr('content'), admin_notes: notes },
                success: function (res) {
                    Swal.fire({ icon: 'success', title: res.success });
                    setTimeout(function () { location.reload(); }, 1000);
                },
                error: function (xhr) {
                    btn.prop('disabled', false);
                    Swal.fire({ icon: 'error', title: xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ __("Error") }}' });
                }
            });
        });
    </script>
@endsection
