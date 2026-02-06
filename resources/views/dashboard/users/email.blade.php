@extends('dashboard.layouts.app')
@section('style')
    <link href="{{asset('assets/css/style.bundle.css')}}" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" type="text/css" href="{{asset('assets/css/forms/form-validation.css')}}">
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <!--begin::Toolbar-->
    <div class="toolbar" id="kt_toolbar">
        <!--begin::Container-->
        <div id="kt_toolbar_container" class="container-fluid d-flex flex-stack">
            <!--begin::Page title-->
            <div data-kt-swapper="true" data-kt-swapper-mode="prepend" data-kt-swapper-parent="{default: '#kt_content_container', 'lg': '#kt_toolbar_container'}" class="page-title d-flex align-items-center flex-wrap me-3 mb-5 mb-lg-0">
                <!--begin::Title-->
                <ol class="breadcrumb text-muted fs-6 fw-bold">

                    <li class="breadcrumb-item pe-3"><a href="{{route('com.index')}}" class="pe-3">{{__('Dashboard')}}</a></li>
                    <li class="breadcrumb-item pe-3"><a href="{{route('com.user_active.index')}}" class="pe-3">{{__('Users')}}</a></li>
                    <li class="breadcrumb-item px-3 text-muted">{{__('Send Email')}}</li>
                </ol>
                <!--end::Title-->
            </div>
            <!--end::Page title-->

        </div>
        <!--end::Container-->
    </div>
    <!--end::Toolbar-->
    <!--begin: Datatable -->
    <div id="kt_content_container" class="container" data-select2-id="select2-data-kt_content_container">
        <!--begin::Card-->
        <div class="card">
            <!--begin::Card body-->
            <div class="card-body pt-3">
                <form class="form fv-plugins-bootstrap5 fv-plugins-framework" id="mainAdd" method="post" action="javascript:void(0)" >
                    <div class="kt-portlet__body">
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required fw-bold fs-6 mb-2" for="subject">{{__('Subject')}}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
                            <input type="text" name="subject" id="subject" class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('Subject')}}"  required>
                            <!--end::Input-->
                        </div>
                        <div class="fv-row mb-7 fv-plugins-icon-container">
                            <!--begin::Label-->
                            <label class="required fw-bold fs-6 mb-2" for="description">{{__('Description')}}</label>
                            <!--end::Label-->
                            <!--begin::Input-->
{{--                            <textarea name="description" id="description" required class="form-control form-control-solid mb-3 mb-lg-0" placeholder="{{__('Description')}}" rows="4"></textarea>--}}
                            <textarea  id="description" name="description"></textarea>
                            <!--end::Input-->
                        </div>

                    </div>
                    <div class="text-center pt-15">
                        <button type="submit" class="btn btn-primary" id="add_form" data-kt-users-modal-action="submit">
                            <span class="indicator-label">Submit</span>
                            <span class="indicator-progress">Please wait...
                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                            </span>
                        </button>
                    </div>
                </form>

            </div>
            <!--end::Card body-->
        </div>
        <!--end::Card-->

    </div>


    <!-- END: Content-->
@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('assets/js/validation/jquery.validate.js')}}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js" ></script>
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>

    <script>
        $('#user_id').select2();
    </script>
    <!-- END: Page JS-->

    <script type="text/javascript">
        CKEDITOR.replace('description', {
            filebrowserUploadUrl: "{{route('com.ckeditor.image-upload-email', ['_token' => csrf_token() ])}}",
            filebrowserUploadMethod: 'form'
        });
    </script>
    <script>
        $(function () {
            'use strict';

            var changePicture = $('#change-picture'),
                userAvatar = $('.user-avatar');


            // Change user profile picture
            if (changePicture.length) {
                $(changePicture).on('change', function (e) {
                    var reader = new FileReader(),
                        files = e.target.files;
                    reader.onload = function () {
                        if (userAvatar.length) {
                            userAvatar.attr('src', reader.result);
                        }
                    };
                    reader.readAsDataURL(files[0]);
                });
            }
        });
    </script>
    <script>

        var data_url='{{ route('com.send_email_to_user',$user->id)}}'

        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = "";
                    if ($(this).parents('.form-group').length > 0)
                        parent = $(this).parents('.form-group');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>'
                        parent.append(error_message);
                    }
                });
            }

            $(document).on("click", "#add_form", function() {
                var form = $(this.form);
                if(! form.valid()) {
                    return false
                };
                if (form.valid()) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }

                    });
                    // var Content = CKEDITOR.instances['description'].getData();

                    var postData = new FormData(this.form);
                    // postData['description']=Content
                    postData.append('description', CKEDITOR.instances['description'].getData());
                    // console.log(postData)
                    $('#add_form').html('');
                    $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{__('Saving')}}...</span>');
                    $.ajax({
                        url: data_url,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#add_form').empty();
                            $('#add_form').html('{{__('Save')}}');
                            // swal.fire({
                            //     type: 'success',
                            //     title: response.success
                            // });
                            setTimeout(function () {
                                toastr['success'](
                                    response.success,
                                    {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);
                            document.getElementById("mainAdd").reset();
                            $('.custom-error').remove();

                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#add_form').empty();
                            $('#add_form').html('{{__('Save')}}');
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

