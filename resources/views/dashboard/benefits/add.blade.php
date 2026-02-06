@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/rateyo/rateyo.css')}}" />

@endsection
@section('content')

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.dashboard')}}">Home</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{route('admin.benefits.index')}}">{{__('Benefits')}}</a>
                    </li>
                    <li class="breadcrumb-item active">{{__('Benefit')}}</li>
                    <!-- Basic table -->


                    <!--/ Basic table -->
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{__('Create Benefit')}}</h5>
                        </div>
                        <div class="card-body">
                            <form id="mainAdd" method="post" action="javascript:void(0)" >
                                @csrf
                                <div class="col-md-12">
                                    <div class="accordion" id="accordionExample">
                                        @foreach ($lang as $index => $locale)
                                            <div class="card accordion-item @if ($index === 0) active @endif">
                                                <h2 class="accordion-header" id="heading{{ $locale }}">
                                                    <button type="button" class="accordion-button @if ($index !== 0) collapsed @endif" data-bs-toggle="collapse" data-bs-target="#accordion{{ $locale }}" aria-expanded="{{ $index === 0 ? 'true' : 'false' }}" aria-controls="accordion{{ $locale }}" role="tabpanel">
                                                        {{ strtoupper($locale) }}
                                                    </button>
                                                </h2>

                                                <div id="accordion{{ $locale }}" class="accordion-collapse collapse @if ($index === 0) show @endif" data-bs-parent="#accordionExample">
                                                    <div class="accordion-body">
                                                        <div class="form-group">
                                                            <label class="form-label" for="title_{{ $locale }}">{{ __('Title') }} ({{ strtoupper($locale) }})</label>
                                                            <input type="text" class="form-control" name="title[{{ $locale }}]"    id="title_{{ $locale }}" placeholder="{{ __('Title in ') . strtoupper($locale) }}" required>
                                                        </div>


                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="description_{{ $locale }}">{{__('description')}}  ({{ strtoupper($locale) }})</label>
                                                            <textarea name="description[{{ $locale }}]" id="description_{{ $locale }}"  class="form-control" rows="3"> </textarea>
                                                        </div>
                                                        <div class="col-md-12">
                                                            <label class="form-label" for="slug_{{ $locale }}">{{ __('Permalink') }} ({{ strtoupper($locale) }})</label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text" id="slug_{{ $locale }}">{{ config('app.url') }}/property/</span>
                                                                <input type="text" id="slug_{{ $locale }}" name="slug[{{ $locale }}]"   class="form-control" aria-describedby="slug" readonly>
                                                                <div id="slug-feedback">
                                                                    <i class="fa fa-check text-success d-none"></i>
                                                                    <i class="fa fa-times text-danger d-none"></i>
                                                                </div>
                                                                <!-- Loading Spinner -->
                                                                <div id="loading-spinner" class="d-none">
                                                                    <i class="fa fa-spinner fa-spin"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>



                                <div class="form-group  mb-3">
                                    <label for="photo" class="form-label">photo</label>
                                    <input class="form-control" name="photo" type="file" id="photo" required>
                                </div>
                                <button type="submit" class="btn btn-primary waves-effect waves-light " id="add_form"   >
                                    {{__('Save')}}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <!-- / Content -->

@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <!-- END: Page JS-->
    <script src="{{asset('assets/vendor/libs/rateyo/rateyo.js')}}"></script>

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
        $(document).ready(function() {
            // Initialize the rating plugin

            var data_url='{{ route('admin.benefits.store')}}'
            // Function to handle error messages
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
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>';
                        parent.append(error_message);
                    }
                });
            }

            $(document).on("click", "#add_form", function() {
                var form = $(this.form);

                // Get the rating value

                if(!form.valid()) {
                    return false;
                }

                if (form.valid()) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var postData = new FormData(this.form);


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
                            $('#add_form').html('{{__('Save')}}');
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
                                        swal.fire['error'](
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
