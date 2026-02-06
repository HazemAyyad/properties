@extends('dashboard.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/form-validation.css') }}" />
    <link rel="stylesheet" href="{{ asset('site/quill/quill.snow.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Edit') }} About Us</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Edit') }} About Us</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)">
                            @csrf
                            @foreach ($settings as $setting)
                                @if($setting->key == 'description')
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="description">{{ __('Description (EN)') }}</label>
                                        <textarea id="description_en" name="description_en" class="form-control" rows="5">{{ $setting->value }}</textarea>

                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="description_ar">{{ __('Description (AR)') }}</label>

                                        <textarea id="description_ar" name="description_ar" class="form-control" rows="5">{{ $setting->value_ar }}</textarea>

                                    </div>
                                @elseif($setting->key == 'img-video')
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="img-video">{{ __('Image/Video') }}</label>
                                        <input type="file" id="img-video" name="img-video" class="form-control">
                                    </div>
                                @elseif($setting->key == 'video')
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="video">{{ __('Video URL') }}</label>
                                        <input type="url" id="video" name="video" class="form-control" value="{{ $setting->value }}">
                                    </div>
                                @elseif($setting->key == 'why_choose_us')
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="why_choose_us">{{ __('Why Choose Us (EN)') }}</label>
                                        <textarea id="why_choose_us" name="why_choose_us" class="form-control" rows="5">{{ $setting->value }}</textarea>
                                    </div>
                                    <div class="form-group mb-3">
                                        <label class="form-label" for="why_choose_us_ar">{{ __('Why Choose Us (AR)') }}</label>
                                        <textarea id="why_choose_us_ar" name="why_choose_us_ar" class="form-control" rows="5">{{ $setting->value_ar }}</textarea>
                                    </div>
                                @endif
                            @endforeach

                            <button type="submit" class="btn btn-primary waves-effect waves-light" id="add_form">
                                {{ __('Save') }}
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
    <script src="{{ asset('site/quill/quill.js') }}"></script>

    <script>
        $(document).ready(function() {

            $('#mainAdd').submit(function(e) {
                e.preventDefault();

                $.ajaxSetup({
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
                });

                var postData = new FormData(this);


                $('#add_form').html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                    '<span class="ml-25 align-middle">{{ __("Saving") }}...</span>');

                $.ajax({
                    url: '{{ route("admin.settings.page.update_about_us", $settings->first()->page) }}',
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#add_form').html('{{ __("Save") }}');
                        toastr.success(response.success, { closeButton: true, tapToDismiss: false });
                    },
                    error: function(data) {
                        $('#add_form').html('{{ __("Save") }}');
                        var response = data.responseJSON;
                        if (data.status == 422) {
                            if (response && response.errors) {
                                $.each(response.errors, function(key, value) {
                                    var error_message = '<div class="custom-error"><p style="color: red">' + value[0] + '</p></div>';
                                    $('[name="' + key + '"]').closest('.form-group').append(error_message);
                                });
                            }
                        } else {
                            swal.fire({ icon: 'error', title: response.message });
                        }
                    }
                });
            });
        });
    </script>
@endsection
