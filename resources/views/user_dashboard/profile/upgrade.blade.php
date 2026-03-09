@extends('user_dashboard.layouts.app')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .error { color: #ed2027 !important; }
        .custom-error p { color: #ed2027; margin-top: 5px; font-size: 0.9rem; }
        .plan-card { border: 2px solid #e9ecef; border-radius: 12px; padding: 1.25rem; margin-bottom: 1rem; cursor: pointer; transition: all 0.2s; }
        .plan-card:hover { border-color: #1779A7; box-shadow: 0 4px 12px rgba(23,121,167,0.15); }
        .plan-card.selected { border-color: #1779A7; background: linear-gradient(135deg, #e8f4f8 0%, #d4ebf2 100%); box-shadow: 0 4px 16px rgba(23,121,167,0.2); }
        .plan-card input { display: none; }
        .plan-card .plan-features { list-style: none; padding: 0; margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #5c6368; }
        .plan-card .plan-features li { display: flex; align-items: flex-start; gap: 6px; margin-bottom: 4px; }
        .plan-card .plan-features .check-icon { flex-shrink: 0; width: 16px; height: 16px; font-size: 10px; background: #1779A7; color: #fff; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
        .upgrade-hero { background: linear-gradient(135deg, #1779A7 0%, #1e8fc4 100%); border-radius: 16px; padding: 2rem; color: #fff; margin-bottom: 2rem; }
        .pending-box { background: linear-gradient(135deg, #fff8e6 0%, #fff3cd 100%); border: 2px solid #ffc107; border-radius: 12px; padding: 1.5rem; }
    </style>
@endsection
@section('content')
    @if(session('error'))
    <div class="plan-limit-msg" style="background: linear-gradient(135deg, #fff8e6 0%, #fff3cd 100%); border: 1px solid #e8d88a; border-radius: 12px; padding: 1rem 1.25rem; color: #6c5a2b; margin-bottom: 1.5rem; font-size: 0.95rem;">
        {{ session('error') }}
    </div>
    @endif
    <div class="widget-box-2">
        <div class="upgrade-hero">
            <h5 class="mb-1">{{ __('Upgrade Plan') }}</h5>
            <p class="mb-0 opacity-90">{{ __('Your current plan') }}: <strong>{{ $user->plan ? $user->plan->title : __('None') }}</strong></p>
        </div>

        @if($pendingRequest ?? null)
            <div class="pending-box">
                <h6 class="text-warning-dark mb-2">⏳ {{ __('Pending request') }}</h6>
                <p class="mb-2">{{ __('You are on plan') }} <strong>{{ $user->plan ? $user->plan->title : __('None') }}</strong> {{ __('and requested') }} <strong>{{ $pendingRequest->plan ? $pendingRequest->plan->title : '' }}</strong>.</p>
                <p class="mb-0 text-muted">{{ __('Please wait for approval or rejection before submitting a new request.') }}</p>
                <a href="{{ route('user.profile.index') }}" class="tf-btn outline mt-3">{{ __('Back to profile') }}</a>
            </div>
        @else
        <form id="upgradeForm" action="javascript:void(0)">
            @csrf
            <div class="box mb-4">
                <label class="form-label fw-semibold mb-3">{{ __('Select the plan you want') }} <span class="text-danger">*</span></label>
                <div class="row g-3">
                @foreach($plans as $plan)
                    @if(!$user->plan || $user->plan_id != $plan->id)
                    <div class="col-md-6 col-lg-4">
                        <div class="plan-card h-100" data-plan-id="{{ $plan->id }}">
                            <label class="d-block mb-0 cursor-pointer h-100">
                                <input type="radio" name="plan_id" value="{{ $plan->id }}" required>
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <strong class="fs-6">{{ $plan->title }}</strong>
                                    <span class="badge rounded-pill" style="background-color: #1779A7;">{{ $plan->price_monthly }} JOD</span>
                                </div>
                                <p class="mb-2 small text-muted">{{ $plan->description }}</p>
                                <ul class="plan-features">
                                    @if($plan->duration_months)
                                        <li><span class="check-icon icon-tick"></span><span>{{ $plan->duration_months }} {{ $plan->duration_months == 1 ? __('month') : __('months') }}</span></li>
                                    @endif
                                    <li><span class="check-icon icon-tick"></span><span>{{ __('Properties') }}: {{ $plan->number_of_properties_display }}</span></li>
                                    @if($plan->extra_support && trim((string)$plan->extra_support) !== '' && trim((string)$plan->extra_support) !== 'none')
                                        <li><span class="check-icon icon-tick"></span><span>{{ $plan->extra_support }}</span></li>
                                    @endif
                                    @foreach($plan->features as $feature)
                                        @if($feature->status != 0)
                                            <li><span class="check-icon icon-tick"></span><span>{{ $feature->title }}</span></li>
                                        @endif
                                    @endforeach
                                </ul>
                            </label>
                        </div>
                    </div>
                    @endif
                @endforeach
                </div>
                @if($plans->isEmpty() || $plans->count() == ($user->plan ? 1 : 0))
                    <p class="text-muted">{{ __('No other plans available.') }}</p>
                @endif
            </div>

            <div class="box mb-4">
                <label for="transfer_receipt" class="form-label">{{ __('Transfer receipt (image of payment)') }} <span class="text-danger">*</span></label>
                <input type="file" name="transfer_receipt" id="transfer_receipt" class="form-control" accept="image/*" required>
                <p class="small text-muted mt-1">{{ __('Upload a clear image of your bank transfer receipt.') }}</p>
            </div>

            <div class="box">
                <button type="submit" id="btnSubmit" class="tf-btn primary">{{ __('Send upgrade request') }}</button>
                <a href="{{ route('user.profile.index') }}" class="tf-btn outline ms-2">{{ __('Back to profile') }}</a>
            </div>
        </form>
        @endif
    </div>
@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script>
        $(function () {
            $('.plan-card').on('click', function () {
                $('.plan-card').removeClass('selected');
                $(this).addClass('selected').find('input[type=radio]').prop('checked', true);
            });
            $('input[name=plan_id]').on('change', function () {
                $('.plan-card').removeClass('selected');
                $(this).closest('.plan-card').addClass('selected');
            });

            function validateForm() {
                $('.custom-error').remove();
                var ok = true;
                if (!$('input[name=plan_id]:checked').length) {
                    $('.box:first').append('<div class="custom-error"><p>{{ __("Please select a plan") }}</p></div>');
                    ok = false;
                }
                if (!$('#transfer_receipt')[0].files.length) {
                    $('#transfer_receipt').closest('.box').append('<div class="custom-error"><p>{{ __("Please upload the transfer receipt") }}</p></div>');
                    ok = false;
                }
                return ok;
            }

            $('#upgradeForm').submit(function (e) {
                e.preventDefault();
                if (!validateForm()) return false;
                var form = this;
                var btn = $('#btnSubmit');
                var originalText = btn.html();
                btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> {{ __("Sending...") }}');
                $('.custom-error').remove();

                var formData = new FormData(form);
                $.ajax({
                    url: '{{ route("user.profile.upgrade.store") }}',
                    type: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    success: function (res) {
                        toastr.success(res.success);
                        btn.prop('disabled', false).html(originalText);
                        setTimeout(function () { window.location.href = '{{ route("user.profile.index") }}'; }, 1500);
                    },
                    error: function (xhr) {
                        btn.prop('disabled', false).html(originalText);
                        var msg = xhr.responseJSON && xhr.responseJSON.message ? xhr.responseJSON.message : '{{ __("An error occurred") }}';
                        toastr.error(msg);
                        if (xhr.status === 422 && xhr.responseJSON && xhr.responseJSON.responseJSON) {
                            $.each(xhr.responseJSON.responseJSON, function (k, v) {
                                $('[name="' + k + '"]').after('<div class="custom-error"><p>' + (v[0] || '') + '</p></div>');
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
