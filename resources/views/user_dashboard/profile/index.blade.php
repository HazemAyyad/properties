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
    </style>

@endsection
@section('content')

    <div class="widget-box-2">
        <form id="editUserForm" name="editUserForm" class="row g-3" action="javascript:void(0)">
            @csrf
        <div class="box">
            <h6 class="title">{{__('Account Settings')}}</h6>
            <div class="box-agent-account">
                <h6>{{__('Agent Account')}}</h6>
                <p class="note">{{__('Your current account type is set to agent, if you want to remove your agent account, and return to normal account, you must click the button below')}}</p>
                <a href="#" class="tf-btn primary">{{__('Remove Agent Account')}}</a>
            </div>
        </div>
        <div class="box">
            <h6 class="title">{{__('Avatar')}}</h6>
            <div class="box-agent-avt">
                <div class="avatar">
                    <img src="{{$user->photo}}" alt="avatar" loading="lazy" width="128" height="128">
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
        <div class="box">
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
        </div>
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
