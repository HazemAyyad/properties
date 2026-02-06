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
                <li class="breadcrumb-item active">{{ __('Edit') }} Slider</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Edit') }} Slider</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)" enctype="multipart/form-data">
                            @csrf
                            @foreach ($settings as $setting)
                                {{-- Description (EN and AR) --}}
                                @if($setting->key == 'description')
                                    @foreach ($lang as $locale)
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="description_{{ $locale }}">
                                                {{ __('Description') }} ({{ strtoupper($locale) }})
                                            </label>
                                            <textarea id="description_{{ $locale }}" name="description[{{ $locale }}]" class="form-control" rows="5">{{ $setting->getTranslation('value', $locale) }}</textarea>
                                        </div>
                                    @endforeach

                                    {{-- Slider Texts --}}
                                @elseif(str_contains($setting->key, 'slider_text_'))
                                    @foreach ($lang as $locale)
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="{{ $setting->key }}_{{ $locale }}">
                                                {{ __(ucfirst(str_replace('_', ' ', $setting->key))) }} ({{ strtoupper($locale) }})
                                            </label>
                                            <input type="text" id="{{ $setting->key }}_{{ $locale }}" name="{{ $setting->key }}[{{ $locale }}]" class="form-control" value="{{ $setting->getTranslation('value', $locale) }}">
                                        </div>
                                    @endforeach

                                    {{-- Slider Images --}}
                                @elseif($setting->key == 'slider_img')
                                    @foreach ($lang as $locale)
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="slider_img_{{ $locale }}">{{ __('Slider Image') }} ({{ strtoupper($locale) }})</label>
                                            {{-- Current Image Preview --}}
                                            <div id="slider_img_preview_{{ $locale }}" class="mb-2">
                                                @if($setting->getTranslation('value', $locale))
                                                    <img src="{{ asset($setting->getTranslation('value', $locale)) }}" alt="Current Slider Image {{ strtoupper($locale) }}" style="max-width: 200px; max-height: 150px;">
                                                @endif
                                            </div>

                                            {{-- File Input for Uploading New Image --}}
                                            <input type="file" id="slider_img_{{ $locale }}" name="slider_img[{{ $locale }}]" class="form-control" onchange="previewImage(event, '{{ $locale }}')">
                                        </div>
                                    @endforeach
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
                    url: '{{ route("admin.settings.page.update_slider") }}',
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

        // Function to preview image after selecting it
        function previewImage(event, locale) {
            const reader = new FileReader();
            reader.onload = function() {
                const previewContainer = document.getElementById('slider_img_preview_' + locale);
                previewContainer.innerHTML = '<img src="' + reader.result + '" alt="Slider Image Preview" style="max-width: 200px; max-height: 150px;">';
            };
            reader.readAsDataURL(event.target.files[0]);
        }
    </script>
@endsection
