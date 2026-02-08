@extends('dashboard.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />

    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}" />
{{--    <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />--}}
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}">{{__('Home')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('users.index')}}">{{__('Users')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('users.edit',$user->id)}}">{{$user->name}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Security')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <!-- User Sidebar -->
            <div class="col-xl-4 col-lg-5 col-md-5 order-1 order-md-0">
                <!-- User Card -->
                <div class="card mb-4">
                    <div class="card-body">
                        <div class="user-avatar-section">
                            <div class="d-flex align-items-center flex-column">

                                <div class="user-info text-center">
                                    <h4 class="mb-2">{{$user->name}}</h4>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-around flex-wrap mt-3 pt-3 pb-4 border-bottom">
                            <div class="d-flex align-items-start me-4 mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="ti ti-checkbox ti-sm"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">{{$count_orders}}</p>
                                    <small>{{__('Orders')}}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="fa-solid fa-coins"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">{{$count_coins}}</p>
                                    <small>{{__('Coins')}}</small>
                                </div>
                            </div>
                            <div class="d-flex align-items-start mt-3 gap-2">
                                <span class="badge bg-label-primary p-2 rounded"><i class="fa-solid fa-hands-holding-child"></i></span>
                                <div>
                                    <p class="mb-0 fw-semibold">{{$count_referrals}}</p>
                                    <small>{{__('referral')}}</small>
                                </div>
                            </div>
                        </div>
                        <p class="mt-4 small text-uppercase text-muted">{{__('Details')}}</p>
                        <div class="info-container">
                            <ul class="list-unstyled">
                                <li class="mb-2">
                                    <span class="fw-semibold me-1">{{__('Name:')}}</span>
                                    <span>{{$user->name}}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">{{__('Email:')}}</span>
                                    <span>{{$user->email}}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">{{__('Mobile:')}}</span>
                                    <span>{{$user->mobile}}</span>
                                </li>
                                <li class="mb-2 pt-1">
                                    <span class="fw-semibold me-1">{{__('Status:')}}</span>
                                    @if($user->email_verified_at!=null)
                                        <span class="badge bg-label-success">{{__('Active')}}</span>
                                    @else
                                        <span class="badge bg-label-danger">{{__('Not Active')}}</span>
                                    @endif
                                </li>

                            </ul>
                            <div class="d-flex justify-content-center">
                                <a
                                    href="javascript:;"
                                    class="btn btn-primary me-3"
                                    data-bs-target="#editUser"
                                    data-bs-toggle="modal"
                                >Edit</a
                                >
                                <a href="{{route('users.login',$user->id)}}" target="_blank" class="btn btn-danger me-3"><i class="fa-solid fa-right-to-bracket text-white" ></i></a>
                                {{--                                <a href="javascript:;" class="btn btn-label-danger suspend-user">Suspended</a>--}}
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /User Card -->

            </div>
            <!--/ User Sidebar -->

            <!-- User Content -->
            <div class="col-xl-8 col-lg-7 col-md-7 order-0 order-md-1">
                <!-- User Pills -->
                <ul class="nav nav-pills flex-column flex-md-row mb-4">
                    <li class="nav-item">
                        <a class="nav-link " href="{{route('users.edit',$user->id)}}"
                        ><i class="ti ti-user-check ti-xs me-1"></i>Account</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link active" href="javascript:void(0);"
                        ><i class="ti ti-lock ti-xs me-1"></i>Security</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users.orders',$user->id)}}"
                        ><i class="ti ti-currency-dollar ti-xs me-1"></i>Orders</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users.coins',$user->id)}}"
                        ><i class="ti ti-bell ti-xs me-1"></i>Coins</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users.invoices',$user->id)}}"
                        ><i class="fa-solid fa-file-invoice"></i>Invoices</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users.address',[$user->id,0])}}"
                        ><i class="fa-solid fa-map-location me-1"></i>From Addresses</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('users.address',[$user->id,1])}}"
                        ><i class="fa-solid fa-map-location me-1"></i>Going Addresses</a
                        >
                    </li>
                    <li class="nav-item">
                        <a class="nav-link   " href="{{route('users.referrals',$user->id)}}"
                        ><i class="fa-solid fa-hands-holding-child me-1"></i>User Referrals</a
                        >
                    </li>
                </ul>
                <!--/ User Pills -->
                <!-- Change Password -->
                <div class="card mb-4">
                    <h5 class="card-header">Change Password</h5>
                    <div class="card-body">
                        <form id="formChangePassword" method="POST" action="javascript:void(0)">
                            @csrf
                            <div class="alert alert-warning" role="alert">
                                <h5 class="alert-heading mb-2">Ensure that these requirements are met</h5>
                                <span>Minimum 8 characters long, uppercase & symbol</span>
                            </div>
                            <div class="row">
                                <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                                    <label class="form-label" for="password">New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control"
                                            type="password"
                                            id="password"
                                            name="password"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        />
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>

                                <div class="mb-3 col-12 col-sm-6 form-password-toggle">
                                    <label class="form-label" for="password_confirmation">Confirm New Password</label>
                                    <div class="input-group input-group-merge">
                                        <input
                                            class="form-control"
                                            type="password"
                                            name="password_confirmation"
                                            id="password_confirmation"
                                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                                        />
                                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                                    </div>
                                </div>
                                <div>
                                    <button type="submit" id="update_password" class="btn btn-primary me-2">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
                <!--/ Change Password -->
            </div>
            <!--/ User Content -->
        </div>

        <!-- Modal -->
        <!-- Edit User Modal -->
        <div class="modal fade" id="editUser" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-simple modal-edit-user">
                <div class="modal-content p-3 p-md-5">
                    <div class="modal-body">
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        <div class="text-center mb-4">
                            <h3 class="mb-2">Edit User Information</h3>
                            <p class="text-muted">Updating user details will receive a privacy audit.</p>
                        </div>
                        <form id="editUserForm" name="editUserForm" class="row g-3" action="javascript:void(0)">
                            @csrf
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="first_name">First Name</label>
                                <input
                                    type="text"
                                    id="first_name"
                                    name="first_name"
                                    class="form-control"
                                    placeholder="John"
                                    value="{{$user->first_name}}"
                                    required
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="last_name">Last Name</label>
                                <input
                                    type="text"
                                    id="last_name"
                                    name="last_name"
                                    class="form-control"
                                    placeholder="Doe"
                                    value="{{$user->last_name}}"
{{--                                    required--}}
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="email">Email</label>
                                <input
                                    type="text"
                                    id="email"
                                    name="email"
                                    class="form-control"
                                    placeholder="example@domain.com"
                                    value="{{$user->email}}"
{{--                                    required--}}
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="modalEditUserMobile">Mobile</label>
                                <input
                                    type="text"
                                    id="mobile"
                                    name="mobile"
                                    class="form-control"
                                    placeholder="+111111111111"
                                    value="{{$user->mobile}}"
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="discount">Discount</label>
                                <input
                                    type="number"
                                    id="discount"
                                    name="discount"
                                    class="form-control"
                                    placeholder="example@domain.com"
                                    value="{{$user->discount}}"
                                />
                            </div>
                            <div class="col-12 col-md-6">
                                <label class="form-label" for="status">Verify Status</label>
                                <select
                                    id="status"
                                    name="status"
                                    class="form-select"
                                    aria-label="Default select example"
{{--                                    required--}}
                                >
                                    <option value="1" {{$user->email_verified_at!=null?'selected':''}}>Active</option>
                                    <option value="2" {{$user->email_verified_at==null?'selected':''}}>Inactive</option>
                                </select>
                            </div>
                            @if($user->parent_referral==0)
                                <div class="col-12 col-md-3">
                                    <label class="form-label" for="referral_rewards">Referral Rewards</label>
                                    <input
                                        type="number"
                                        id="referral_rewards"
                                        name="referral_rewards"
                                        class="form-control"
                                        placeholder="4"
                                        value="{{$user->referral_rewards}}"
                                    />
                                </div>
                                <div class="col-12 col-md-3">
                                    <small class="text-light fw-bold">Type Referral Rewards</small>
                                    <div class="form-check mt-3">
                                        <input name="type_referral_rewards" class="form-check-input" type="radio" value="0" {{$user->type_referral_rewards==0?'checked':''}} id="defaultRadio1">
                                        <label class="form-check-label" for="defaultRadio1"> Percentage </label>
                                    </div>
                                    <div class="form-check mt-3">
                                        <input name="type_referral_rewards" class="form-check-input" type="radio" value="1" {{$user->type_referral_rewards==1?'checked':''}} id="defaultRadio2">
                                        <label class="form-check-label" for="defaultRadio2"> Coins </label>
                                    </div>

                                </div>
                            @endif
                            <div class="col-12 text-center">
                                <button type="submit" class="btn btn-primary me-sm-3 me-1" id="user_update">Submit</button>
                                <button
                                    type="reset"
                                    class="btn btn-label-secondary"
                                    data-bs-dismiss="modal"
                                    aria-label="Close"
                                >
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <!--/ Edit User Modal -->


        <!-- /Modal -->
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-buttons/datatables-buttons.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-rowgroup/datatables.rowgroup.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.js')}}"></script>

{{--    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>--}}
{{--    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>--}}
{{--    <script src="{{asset('/assets/js/app-user-view-security.js')}}"></script>--}}
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

    {{--    update User--}}
    <script>

        var data_url_user='{{ route('users.update',$user->id)}}'

        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = "";
                    if ($(this).parents('.fv-row').length > 0)
                        parent = $(this).parents('.fv-row');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>'
                        parent.append(error_message);
                    }
                });
            }

            $("#editUserForm").submit(function() {
                let myform  =  $('#editUserForm');

                if(!myform.valid() ) {
                    return false
                };
                if (myform.valid()) {
                    var postData = new FormData($( 'form#editUserForm' )[ 0 ]);
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
                                    response.success,
                                    {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);

                            $('.custom-error').remove();

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
                                            response.message,
                                            {
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
    {{--    update Password--}}
    <script>

        var data_url='{{ route('users.update_password',$user->id)}}'

        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = "";
                    if ($(this).parents('.fv-row').length > 0)
                        parent = $(this).parents('.fv-row');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>'
                        parent.append(error_message);
                    }
                });
            }

            $("#formChangePassword").submit(function() {
                let myform  =  $('#formChangePassword');

                if(!myform.valid() ) {
                    return false
                };
                if (myform.valid()) {
                    var postData = new FormData($( 'form#formChangePassword' )[ 0 ]);
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
                                    response.success,
                                    {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                                win
                            }, 200);
                            myform[0].reset()
                            $('.custom-error').remove();

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
                                            response.message,
                                            {
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
