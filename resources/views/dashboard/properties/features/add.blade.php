@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/fonts/tabler-icons.css')}}"/>

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
                        <a href="{{route('admin.property_features.index')}}">{{__('Property Features')}}</a>
                    </li>
                    <li class="breadcrumb-item active">{{__('Create Feature')}}</li>
                    <!-- Basic table -->


                    <!--/ Basic table -->
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{__('Create Feature')}}</h5>
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
                                                            <label class="form-label" for="name_{{ $locale }}">{{ __('Name') }} ({{ strtoupper($locale) }})</label>
                                                            <input type="text" class="form-control" name="name[{{ $locale }}]" id="name_{{ $locale }}" placeholder="{{ __('Name in ') . strtoupper($locale) }}" required>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                <!-- Icons -->
                                <div class="col-md-6 mb-6">
                                    <label for="selectpickerIcons" class="form-label">Category</label>
                                    <select class="selectpicker w-100 show-tick" id="selectpickerIcons" name="category_id" data-icon-base="fa-solid"   data-style="btn-default">
                                        @foreach($categories as $category)
                                            <option value="{{$category->id}}" class="mb-2">{{$category->name}}</option>
                                        @endforeach

                                    </select>
                                </div>

                                <!-- Icons -->
                                <div class="col-md-6 mb-6">
                                    <label for="selectpickerIcons" class="form-label">Icons</label>
                                    <select class="selectpicker w-100 show-tick" id="selectpickerIcons" name="icon" data-icon-base="fa-solid"   data-style="btn-default">
                                         @foreach($icons as $icon)
                                            <option data-icon=" {{$icon->icon}} " class="mb-2" value="{{$icon->icon}}">{{$icon->icon}}</option>
                                         @endforeach

                                    </select>
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

        var data_url='{{ route('admin.property_features.store')}}'

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
    <script src="{{asset('assets/vendor/libs/bootstrap-select/bootstrap-select.js')}}"></script>

    <script src="{{asset('assets/js/forms-selects.js')}}"></script>
@endsection
