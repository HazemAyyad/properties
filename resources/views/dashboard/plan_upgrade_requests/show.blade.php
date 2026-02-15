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
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Request details') }}</h5>
                        <span class="badge bg-{{ $request->status === 'pending' ? 'warning' : ($request->status === 'accepted' ? 'success' : 'danger') }}">
                            {{ __($request->status) }}
                        </span>
                    </div>
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tr>
                                <th width="200">{{ __('User') }}</th>
                                <td>
                                    @if($request->user)
                                        <a href="{{ route('admin.users.edit', $request->user->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="ti ti-user me-1"></i>{{ $request->user->name }}
                                        </a>
                                    @else
                                        —
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>{{ __('Email') }}</th>
                                <td>{{ $request->user ? $request->user->email : '—' }}</td>
                            </tr>
                            <tr>
                                <th>{{ __('Requested plan') }}</th>
                                <td>{{ $request->plan ? $request->plan->title : '—' }} ({{ $request->plan ? $request->plan->price_monthly : '' }} JOD)</td>
                            </tr>
                            <tr>
                                <th>{{ __('Date') }}</th>
                                <td>{{ $request->created_at->format('Y-m-d H:i') }}</td>
                            </tr>
                            @if($request->admin_notes)
                            <tr>
                                <th>{{ __('Admin notes') }}</th>
                                <td>{{ $request->admin_notes }}</td>
                            </tr>
                            @endif
                        </table>
                    </div>
                </div>

                <div class="card mb-4">
                    <div class="card-header"><h5 class="mb-0">{{ __('Transfer receipt') }}</h5></div>
                    <div class="card-body text-center">
                        <img src="{{ url($request->transfer_receipt) }}" alt="{{ __('Transfer receipt') }}" class="img-fluid rounded" style="max-height: 400px;">
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                @if($request->isPending())
                <div class="card">
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
                <div class="card">
                    <div class="card-body">
                        <p class="text-muted mb-0">{{ __('This request has already been processed.') }}</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var requestId = {{ $request->id }};
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
