@extends('dashboard.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
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
                    <a href="{{route('admin.settings.index')}}">{{__('Settings')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Edit Section')}}</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Edit Section Visibility')}}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)">
                            @csrf
                            <div class="row">
                                @foreach ($settings as $setting)
                                    @if(in_array($setting->key, ['gallary_properties', 'cities', 'services', 'statistics', 'benefits', '4_top', 'people_says', 'agents', 'blogs', 'partners']))
                                    <div class="form-check form-check-primary mt-4 col-md-4">
                                        <input class="form-check-input" type="checkbox" id="{{ $setting->key }}" name="{{ $setting->key }}" value="1" {{ $setting->value == 1 ? 'checked' : '' }}>
                                        <label class="form-check-label" for="{{ $setting->key }}">{{ ucfirst(str_replace('_', ' ', $setting->key)) }}</label>
                                    </div>

                                    @endif
                                @endforeach
                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary" id="add_form" name="submit" value="Submit">{{ __('Save Changes') }}</button>
                                </div>
                            </div>
                        </form>


                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection

@section('scripts')
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

    <script>
        var data_update_url = '{{ route("admin.settings.page.update_settings", "sections") }}';

        $(document).ready(function() {
            $(document).on("click", "#add_form", function() {
                var form = $(this.form);
                if (!form.valid()) {
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
                        url: data_update_url,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                            $('#add_form').html('{{__('Save')}}');
                            setTimeout(function() {
                                toastr['success'](response.success, {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            }, 200);
                        },
                        error: function(data) {
                            $('#add_form').empty();
                            $('#add_form').html('{{__('Save')}}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                setTimeout(function() {
                                    toastr['error'](response.message, {
                                        closeButton: true,
                                        tapToDismiss: false
                                    });
                                }, 200);
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
