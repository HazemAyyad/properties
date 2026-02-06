@extends('dashboard.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/form-validation.css') }}" />
@endsection
@section('content')
    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Edit') }} {{ $setting->name }}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Edit') }} {{ $setting->name }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)">
                            @foreach ($lang as $item)

                                @if ($item == 'en')
                                    <div class="form-group mb-3">
                                        <label class="form-label"
                                            for="{{ $setting->key }}">{{ $setting->name }}
                                            {{ $item }}</label>
                                        <textarea id="{{ $setting->key }}" name="{{ $setting->key }}">{{ $setting->value }}</textarea>
                                    </div>
                                @else
                                   <div class="form-group mb-3">
                                    <label class="form-label"
                                        for="{{ $setting->key }}_{{ $item }}">{{ $setting->name }}
                                        {{ $item }}</label>
                                    <textarea id="{{ $setting->key }}_{{ $item }}" name="{{ $setting->key }}_{{ $item }}">{{ $setting['value_'.$item] }}</textarea>
                                </div>
                                @endif

                            @endforeach
                            <button type="submit" class="btn btn-primary waves-effect waves-light " id="add_form">
                                {{ __('Save') }}
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

    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <!-- END: Page JS-->
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
    <script !src="">
        /**
         * Selects & Tags
         */

        'use strict';

        $(function() {
            const select2 = $('.select2');

            if (select2.length) {
                select2.each(function() {
                    var $this = $(this);
                    $this.wrap('<div class="position-relative"></div>').select2({
                        placeholder: 'Select value',
                        dropdownParent: $this.parent()
                    });
                });
            }

        });
    </script>

    <script>
        var data_url = '{{ route('admin.settings.page.update', $setting->id) }}'

        $(document).ready(function() {
            function myHandel(obj, id) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function() {
                    var parent = "";
                    if ($(this).parents('.fv-row').length > 0)
                        parent = $(this).parents('.fv-row');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message =
                            '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj
                            .messages[name][0] + '</p></div>'
                        parent.append(error_message);
                    }
                });
            }

            $(document).on("click", "#add_form", function() {
                var form = $(this.form);
                if (!form.valid()) {
                    return false
                };
                if (form.valid()) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }

                    });
                    var postData = new FormData(this.form);
                    @foreach ($lang as $item)
                    @if($item=='en')
                    postData.append('{{ $setting->key }}', CKEDITOR.instances[
                                '{{ $setting->key }}']
                            .getData());
                    @else
                    postData.append('{{ $setting->key }}_{{ $item }}', CKEDITOR.instances[
                                '{{ $setting->key }}_{{ $item }}']
                            .getData());
                    @endif

                    @endforeach

                    $('#add_form').html('');
                    $('#add_form').append(
                        '<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{ __('Saving') }}...</span>');
                    $.ajax({
                        url: data_url,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#add_form').html('{{ __('Save') }}');
                            setTimeout(function() {
                                toastr['success'](
                                    response.success, {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);

                            $('.custom-error').remove();

                        },
                        error: function(data) {
                            $('.custom-error').remove();
                            $('#add_form').empty();
                            $('#add_form').html('{{ __('Save') }}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (typeof(response.responseJSON) != "undefined") {
                                    myHandel(response);
                                    setTimeout(function() {
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
