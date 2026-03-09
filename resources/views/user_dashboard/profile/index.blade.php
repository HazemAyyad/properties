@extends('user_dashboard.layouts.app')
@section('style')
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        .error {
            color: #ed2027 !important;
        }
        .form-control.style-1.error,
        input.error,
        textarea.error,
        select.error {
            border-color: #ed2027 !important;
        }
        .custom-error p {
            color: #ed2027;
            margin-top: 5px;
            font-size: 0.9rem;
        }
        .current-plan-card {
            background: linear-gradient(135deg, #1779A7 0%, #1e8fc4 100%);
            border-radius: 16px;
            padding: 1.5rem;
            color: #fff;
        }
        .current-plan-card .plan-name { font-size: 1.25rem; font-weight: 600; margin-bottom: 0.5rem; }
        .current-plan-card .plan-desc { opacity: 0.95; margin-bottom: 1rem; font-size: 0.95rem; }
        .current-plan-card .list-price { list-style: none; padding: 0; margin: 0 0 1rem 0; }
        .current-plan-card .list-price .item { display: flex; align-items: flex-start; gap: 8px; margin-bottom: 6px; font-size: 0.9rem; }
        .current-plan-card .list-price .check-icon { flex-shrink: 0; width: 18px; height: 18px; font-size: 11px; background: rgba(255,255,255,0.9); color: #1779A7; border-radius: 50%; display: inline-flex; align-items: center; justify-content: center; }
        .current-plan-card .plan-cost { font-weight: 600; margin-bottom: 1rem; }
        .current-plan-card .tf-btn { background: #fff; color: #1779A7; border-color: #fff; }
        .current-plan-card .tf-btn:hover { background: #f0f0f0; color: #1779A7; border-color: #f0f0f0; }
        .pending-request-msg { background: #fff8e6; border: 1px solid #f0c674; border-radius: 12px; padding: 1rem; color: #856404; margin-top: 0.75rem; }
    </style>

@endsection
@section('content')

    @if((isset($subscriptionResult) && ($subscriptionResult['was_downgraded'] ?? false)) || (isset($subscriptionInfo) && ($subscriptionInfo['is_basic'] ?? false) && $user->last_plan_id))
    <div class="plan-limit-box limit-reached mb-4" style="background: linear-gradient(135deg, #fff9e8 0%, #fff5d6 100%); border: 1px solid #e5d4a1; border-radius: 14px; padding: 1.25rem 1.5rem; color: #5a4a1a;">
        <div class="plan-info">
            {{ __('Your paid subscription has expired and your account has been moved to the Basic plan. You cannot add new properties until you upgrade or free up slots.') }}
        </div>
        <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary">{{ __('Upgrade Plan') }}</a>
    </div>
    @endif

    <div class="widget-box-2">
        {{-- الخطة الحالية وترقية الحساب --}}
        <div class="box">
            <h6 class="title">{{ __('Current Plan') }}</h6>
            <div class="current-plan-card">
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
                        </li>
                        @if(isset($subscriptionInfo) && !$subscriptionInfo['is_basic'] && $subscriptionInfo['expires_at'])
                        <li class="item">
                            <span class="check-icon icon-tick"></span>
                            <span>{{ __('Subscription ends') }}: {{ $subscriptionInfo['expires_at']->format('Y-m-d') }}
                                @if($subscriptionInfo['days_remaining'] !== null)
                                    ({{ $subscriptionInfo['days_remaining'] }} {{ __('days left') }})
                                @endif
                            </span>
                        </li>
                        @endif
                        @if($user->plan->extra_support && trim((string)$user->plan->extra_support) !== '' && trim((string)$user->plan->extra_support) !== 'none')
                            <li class="item">
                                <span class="check-icon icon-tick"></span>
                                <span>{{ $user->plan->extra_support }}</span>
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

        <form id="editUserForm" name="editUserForm" class="row g-3" action="javascript:void(0)">
            @csrf
         <div class="box">
            <h6 class="title">{{__('Account Settings')}}</h6>
            <!-- <div class="box-agent-account">
                <h6>{{__('Agent Account')}}</h6>
                <p class="note">{{__('Your current account type is set to agent, if you want to remove your agent account, and return to normal account, you must click the button below')}}</p>
                <a href="#" class="tf-btn primary">{{__('Remove Agent Account')}}</a>
            </div> -->
        </div>
         <div class="box">
            <h6 class="title">{{__('Avatar')}}</h6>
            <div class="box-agent-avt">
                <div class="avatar">
                    <img src="{{ $user->avatar_url }}" alt="avatar" loading="lazy" width="128" height="128">
                </div>
                <div class="content uploadfile">
                    <p>{{__('Upload a new avatar')}}</p>
                    <div class="box-ip">
                        <input type="file" name="photo" class="ip-file">
                    </div>
                    <p>JPEG 100x100</p>
                </div>
            </div>
        </div>
        <!-- <div class="box">
            <h6 class="title">{{__('Agent Poster')}}</h6>
            <div class="box-agent-avt">
                <div class="img-poster">
                    <img src="{{$user->agent_poster}}"  alt="avatar" loading="lazy">
                </div>
                <div class="content uploadfile">
                    <p>{{__('Upload a new poster')}}</p>
                    <div class="box-ip">
                        <input type="file" name="agent_poster" class="ip-file">
                    </div>
                    <span>JPEG 100x100</span>
                </div>
            </div>
        </div> -->
        <h6 class="title">{{__('Information')}}</h6>
        <div class="box box-fieldset">
            <label for="name">{{__('Full name')}}:<span>*</span></label>
            <input type="text" value="{{$user->name}}" required name="name" class="form-control style-1">
        </div>
        <div class="box box-fieldset">
            <label for="desc">{{__('Description')}}:<span>*</span></label>
            <textarea name="description" required>{{$user->description}}</textarea>
        </div>
        <div class="box grid-4 gap-30">
            <div class="box-fieldset">
                <label for="company">{{__('Your Company')}}:<span>*</span></label>
                <input type="text" name="company" required value="{{$user->company}}" class="form-control style-1">
            </div>
            <div class="box-fieldset">
                <label for="position">{{__('Position')}}:<span>*</span></label>
                <input type="text" name="position" required value="{{$user->position}}" class="form-control style-1">
            </div>
            <div class="box-fieldset">
                <label for="num">{{__('Office Number')}}:<span>*</span></label>
                <input type="number" name="office_no" required value="{{$user->office_no}}" class="form-control style-1">
            </div>
            <div class="box-fieldset">
                <label for="address">{{__('Office Address')}}:<span>*</span></label>
                <input type="text" name="office_address" required value="{{$user->office_address}}" class="form-control style-1">
            </div>
        </div>
        <div class="box grid-4 gap-30 box-info-2">
            <div class="box-fieldset">
                <label for="job">{{__('Job')}}:<span>*</span></label>
                <input type="text" name="job" required value="{{$user->job}}" class="form-control style-1">
            </div>
            <div class="box-fieldset">
                <label for="email">{{__('Email address')}}:<span>*</span></label>
                <input type="text" name="email" required value="{{$user->email}}" class="form-control style-1">
            </div>
            <div class="box-fieldset">
                <label for="mobile">{{__('Your Phone')}}:<span>*</span></label>
                <input type="number" name="mobile" required value="{{$user->mobile}}"  class="form-control style-1">
            </div>
        </div>
        <div class="box box-fieldset">
            <label for="location">{{__('Location')}}:<span>*</span></label>
            <input type="text" name="location" required value="{{$user->location}}" class="form-control style-1">
        </div>
        <div class="box box-fieldset">
            <label for="fb">{{__('Facebook')}}:<span>*</span></label>
            <input type="url" name="facebook" required value="{{$user->facebook}}" class="form-control style-1">
        </div>
        <div class="box box-fieldset">
            <label for="tw">{{__('Twitter')}}:<span>*</span></label>
            <input type="url" name="twitter" required value="{{$user->twitter}}" class="form-control style-1">
        </div>
        <div class="box box-fieldset">
            <label for="linkedin">{{__('Linkedin')}}:<span>*</span></label>
            <input type="url"  name="linkedin" required value="{{$user->linkedin}}" class="form-control style-1">
        </div>
        <div class="box">
            <button type="submit" id="user_update"  class="tf-btn primary">{{__('Save & Update')}}</button>
        </div>
        </form>
        <h6 class="title">{{__('Change password')}}</h6>
        <form id="formChangePassword" method="POST" action="javascript:void(0)">
            @csrf
        <div class="box grid-3 gap-30">
            <div class="box-fieldset">
                <label for="old-pass">{{__('Old Password')}}:<span>*</span></label>
                <div class="box-password">
                    <input type="password" class="form-contact style-1 password-field" placeholder="{{__('Password')}}">
                    <span class="show-pass">
                                            <i class="icon-pass icon-eye"></i>
                                            <i class="icon-pass icon-eye-off"></i>
                                        </span>
                </div>
            </div>
            <div class="box-fieldset">
                <label for="new-pass">{{__('New Password')}}:<span>*</span></label>
                <div class="box-password">
                    <input type="password" class="form-contact style-1 password-field2"

                           id="password"
                           name="password"
                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    >
                    <span class="show-pass2">
                                            <i class="icon-pass icon-eye"></i>
                                            <i class="icon-pass icon-eye-off"></i>
                                        </span>
                </div>
            </div>
            <div class="box-fieldset">
                <label for="password_confirmation">{{__('Confirm Password')}}:<span>*</span></label>
                <div class="box-password">
                    <input type="password" class="form-contact style-1 password-field3"

                           name="password_confirmation"
                           id="password_confirmation"
                           placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                    >
                    <span class="show-pass3">
                                            <i class="icon-pass icon-eye"></i>
                                            <i class="icon-pass icon-eye-off"></i>
                                        </span>
                </div>
            </div>
        </div>
        <div class="box">
            <button type="submit" id="update_password" class="tf-btn primary">{{__('Update Password')}}</button>
        </div>
        </form>
    </div>

@endsection
@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{--    update User--}}
    <script>
        var data_url_user='{{ route('user.profile.update')}}';

        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj) {
                    obj.messages = obj.responseJSON;
                }

                $('input, select, textarea').each(function () {
                    var parent = $(this).parents('.fv-row, .input-group').first();

                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="custom-error"><p>' + obj.messages[name][0] + '</p></div>';

                        if (parent.length > 0) {
                            parent.append(error_message);
                        } else {
                            $(this).after(error_message);
                        }

                        // Add error class to input
                        $(this).addClass('error');
                    }
                });
            }

            $("#editUserForm").validate();
            $("#formChangePassword").validate();

            $("#editUserForm").submit(function() {
                let myform = $('#editUserForm');

                if (!myform.valid()) {
                    return false;
                }

                if (myform.valid()) {
                    var postData = new FormData($('form#editUserForm')[0]);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#user_update').html('');
                    $('#user_update').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{__('Saving')}}...</span>');
                    $.ajax({
                        url: data_url_user,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#user_update').html('{{__('Save')}}');
                            setTimeout(function () {
                                toastr['success'](
                                    response.success, {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);
                            $('.custom-error').remove();
                            $('.error').removeClass('error');
                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#user_update').empty();
                            $('#user_update').html('{{__('Save')}}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (typeof (response.responseJSON) != "undefined") {
                                    myHandel(response);
                                    setTimeout(function () {
                                        toastr['error'](
                                            response.message, {
                                                closeButton: true,
                                                tapToDismiss: false
                                            }
                                        );
                                    }, 200);
                                }
                            } else {
                                swal.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        }
                    });
                }
            });

            var data_url = '{{ route('user.profile.update_password')}}';

            $("#formChangePassword").submit(function() {
                let myform = $('#formChangePassword');

                if (!myform.valid()) {
                    return false;
                }

                if (myform.valid()) {
                    var postData = new FormData($('form#formChangePassword')[0]);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });
                    $('#update_password').html('');
                    $('#update_password').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{__('Saving')}}...</span>');
                    $.ajax({
                        url: data_url,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#update_password').html('{{__('Save')}}');
                            setTimeout(function () {
                                toastr['success'](
                                    response.success, {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);
                            myform[0].reset();
                            $('.custom-error').remove();
                            $('.error').removeClass('error');
                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#update_password').empty();
                            $('#update_password').html('{{__('Save')}}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (typeof (response.responseJSON) != "undefined") {
                                    myHandel(response);
                                    setTimeout(function () {
                                        toastr['error'](
                                            response.message, {
                                                closeButton: true,
                                                tapToDismiss: false
                                            }
                                        );
                                    }, 200);
                                }
                            } else {
                                swal.fire({
                                    icon: 'error',
                                    title: response.message
                                });
                            }
                        }
                    });
                }
            });
        });
    </script>



@endsection
