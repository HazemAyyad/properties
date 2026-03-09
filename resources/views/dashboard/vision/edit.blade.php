@extends('dashboard.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/css/form-validation.css') }}" />
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">Home</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Vision & Goals') }}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{ __('Edit Vision & Goals Section') }}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)">
                            @csrf

                            <div class="col-md-12">
                                <div class="accordion" id="accordionExample">
                                    @foreach ($lang as $index => $locale)
                                        <div class="card accordion-item @if ($index === 0) active @endif">
                                            <h2 class="accordion-header" id="heading{{ $locale }}">
                                                <button type="button" class="accordion-button @if ($index !== 0) collapsed @endif"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#accordion{{ $locale }}"
                                                        aria-expanded="{{ $index === 0 ? 'true' : 'false' }}"
                                                        aria-controls="accordion{{ $locale }}" role="tabpanel">
                                                    {{ strtoupper($locale) }}
                                                </button>
                                            </h2>
                                            <div id="accordion{{ $locale }}"
                                                 class="accordion-collapse collapse @if ($index === 0) show @endif"
                                                 data-bs-parent="#accordionExample">
                                                <div class="accordion-body">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="vision_title_{{ $locale }}">
                                                            {{ __('Vision Title') }} ({{ strtoupper($locale) }})
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               id="vision_title_{{ $locale }}"
                                                               name="vision_title[{{ $locale }}]"
                                                               value="{{ $vision->getTranslation('vision_title', $locale) }}"
                                                               required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="vision_description_{{ $locale }}">
                                                            {{ __('Vision Description') }} ({{ strtoupper($locale) }})
                                                        </label>
                                                        <textarea class="form-control"
                                                                  id="vision_description_{{ $locale }}"
                                                                  name="vision_description[{{ $locale }}]"
                                                                  rows="4"
                                                                  required>{{ $vision->getTranslation('vision_description', $locale) }}</textarea>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="goals_title_{{ $locale }}">
                                                            {{ __('Goals Section Title') }} ({{ strtoupper($locale) }})
                                                        </label>
                                                        <input type="text"
                                                               class="form-control"
                                                               id="goals_title_{{ $locale }}"
                                                               name="goals_title[{{ $locale }}]"
                                                               value="{{ $vision->getTranslation('goals_title', $locale) }}">
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-check form-switch mt-3">
                                <input class="form-check-input" type="checkbox"
                                       id="is_active" value="1"
                                       {{ $vision->is_active ? 'checked' : '' }}>
                                <label class="form-check-label" for="is_active">{{ __('Section Active') }}</label>
                            </div>
                            <input type="hidden" name="is_active" id="is_active_hidden" value="{{ $vision->is_active ? '1' : '0' }}">

                            <button type="submit" class="btn btn-primary mt-3" id="add_form">
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
    <script>
        var data_url = '{{ route('admin.vision.update') }}';

        $(document).ready(function () {
            function myHandel(obj) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = '';
                    if ($(this).parents('.form-group').length > 0)
                        parent = $(this).parents('.form-group');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr('name');
                    var nameForError = (typeof name === 'string') ? name.replace(/\]/g, '').replace(/\[/g, '.') : name;
                    if (obj.messages && obj.messages[nameForError] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[nameForError][0] + '</p></div>';
                        $(parent).append(error_message);
                    }
                });
            }

            $(document).on('click', '#add_form', function () {
                var form = $(this.form);
                if (!form.valid()) {
                    return false;
                }
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });
                $('#is_active_hidden').val($('#is_active').prop('checked') ? '1' : '0');
                var postData = new FormData(this.form);
                $('#add_form').html('');
                $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                    '<span class="ml-25 align-middle">{{ __("Saving") }}...</span>');
                $.ajax({
                    url: data_url,
                    type: 'POST',
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        $('#add_form').html('{{ __("Save") }}');
                        setTimeout(function () {
                            toastr['success'](response.success, {
                                closeButton: true,
                                tapToDismiss: false
                            });
                        }, 200);
                        $('.custom-error').remove();
                    },
                    error: function (data) {
                        $('.custom-error').remove();
                        $('#add_form').empty().html('{{ __("Save") }}');
                        var response = data.responseJSON;
                        if (data.status === 422) {
                            if (response && response.responseJSON) {
                                myHandel(response);
                            }
                            if (response && response.message) {
                                toastr['error'](response.message, { closeButton: true, tapToDismiss: false });
                            }
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: (response && response.message) ? response.message : 'Error'
                            });
                        }
                    }
                });
            });
        });
    </script>
@endsection
