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
                <li class="breadcrumb-item active">{{__('Edit Settings')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Edit Settings')}}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)">
                            @csrf

                            <div class="row">
                                @foreach ($settings as $setting)
                                    @if($setting->key == 'whatsapp')
                                        <div class="form-group">
                                            <label class="form-label" for="whatsapp">{{__('Whatsapp')}}</label>
                                            <input type="text" class="form-control" id="whatsapp" value="{{ $setting->value }}" name="whatsapp"  placeholder="{{__('Whatsapp')}}" required/>
                                        </div>
                                    @elseif($setting->key == 'youtube')
                                        <div class="form-group">
                                            <label class="form-label" for="youtube">{{__('Youtube')}}</label>
                                            <input type="text" class="form-control" id="youtube" value="{{ $setting->value }}" name="youtube"  placeholder="{{__('youtube')}}" required/>
                                        </div>
                                    @elseif($setting->key == 'twitter')
                                        <div class="form-group">
                                            <label class="form-label" for="twitter">{{__('twitter')}}</label>
                                            <input type="text" class="form-control" id="twitter" value="{{ $setting->value }}" name="twitter"  placeholder="{{__('twitter')}}" required/>
                                        </div>
                                    @elseif($setting->key == 'facebook')
                                        <div class="form-group">
                                            <label class="form-label" for="facebook">{{__('facebook')}}</label>
                                            <input type="text" class="form-control" id="facebook" value="{{ $setting->value }}" name="facebook"  placeholder="{{__('facebook')}}" required/>
                                        </div>
                                    @elseif($setting->key == 'instagram')
                                        <div class="form-group">
                                            <label class="form-label" for="instagram">{{__('instagram')}}</label>
                                            <input type="text" class="form-control" id="instagram" value="{{ $setting->value }}" name="instagram"  placeholder="{{__('instagram')}}" required/>
                                        </div>
                                    @elseif($setting->key == 'linkedin')
                                        <div class="form-group">
                                            <label class="form-label" for="linkedin" >{{__('linkedin')}}</label>
                                            <input type="text" class="form-control" id="linkedin" value="{{ $setting->value }}" name="linkedin"  placeholder="{{__('linkedin')}}" required/>
                                        </div>
                                    @elseif($setting->key == 'slogan')
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="slogan">{{ __('Slogan (EN)') }}</label>
                                            <textarea id="slogan" name="slogan" class="form-control" rows="5">{{ $setting->value }}</textarea>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="slogan_ar">{{ __('Slogan (AR)') }}</label>
                                            <textarea id="slogan_ar" name="slogan_ar" class="form-control" rows="5">{{ $setting->value_ar }}</textarea>
                                        </div>
                                    @elseif($setting->key == 'contact_us')
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="contact_us">{{ __('Contact Us (EN)') }}</label>
                                            <textarea id="contact_us" name="contact_us" class="form-control" rows="5">{{ $setting->value }}</textarea>
                                        </div>
                                        <div class="form-group mb-3">
                                            <label class="form-label" for="contact_us_ar">{{ __('Contact Us (AR)') }}</label>
                                            <textarea id="why_choose_us_ar" name="contact_us_ar" class="form-control" rows="5">{{ $setting->value_ar }}</textarea>
                                        </div>
                                    @elseif($setting->key == 'address')
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="address">{{ __('Address (EN)') }}</label>
                                                <input type="text" class="form-control" id="address" value="{{ $setting->value }}" name="address"  placeholder="{{__('address')}}" required/>

                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group mb-3">
                                                <label class="form-label" for="address_ar">{{ __('Address (AR)') }}</label>
                                                <input type="text" class="form-control" id="address_ar" value="{{ $setting->value_ar }}" name="address_ar"  placeholder="{{__('address')}}" required/>

                                            </div>
                                        </div>


                                    @elseif($setting->key == 'main_logo')
                                        <div class="col-md-6">
                                            <!-- Main Logo Section -->
                                            <div class="form-group">
                                                <label class="form-label" for="main_logo">{{__('Main Logo')}}</label>

                                                <!-- Display the current logo -->
                                                @if(isset($setting->value) && !empty($setting->value))
                                                    <div class="mb-3">
                                                        <img id="main_logo_preview" src="{{ asset($setting->value) }}" alt="Main Logo" style="max-width: 150px;">
                                                    </div>
                                                @else
                                                    <div class="mb-3">
                                                        <img id="main_logo_preview" src="#" alt="Main Logo" style="max-width: 150px; display: none;">
                                                    </div>
                                                @endif

                                                <!-- Input for uploading a new logo -->
                                                <input type="file" class="form-control" id="main_logo" name="main_logo" onchange="previewImage('main_logo', 'main_logo_preview')" />
                                            </div>

                                        </div>
                                    @elseif($setting->key == 'secondary_logo')

                                        <div class="col-md-6">
                                            <!-- Secondary Logo Section -->
                                            <div class="form-group">
                                                <label class="form-label" for="secondary_logo">{{__('Secondary Logo')}}</label>

                                                <!-- Display the current logo -->
                                                @if(isset($setting->value) && !empty($setting->value))
                                                    <div class="mb-3">
                                                        <img id="secondary_logo_preview" src="{{ asset($setting->value) }}" alt="Secondary Logo" style="max-width: 150px;">
                                                    </div>
                                                @else
                                                    <div class="mb-3">
                                                        <img id="secondary_logo_preview" src="#" alt="Secondary Logo" style="max-width: 150px; display: none;">
                                                    </div>
                                                @endif

                                                <!-- Input for uploading a new logo -->
                                                <input type="file" class="form-control" id="secondary_logo" name="secondary_logo" onchange="previewImage('secondary_logo', 'secondary_logo_preview')" />
                                            </div>
                                        </div>
                                    @endif
                                @endforeach




                            </div>

                            <div class="row mt-3">
                                <div class="col-12 text-center">
                                    <button type="submit" class="btn btn-primary " id="add_form" name="submit" value="Submit" >
                                        {{__('Save Changes')}}
                                    </button>
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
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <!-- END: Page JS-->

    <script>
        // Function to preview the image before uploading
        function previewImage(inputId, imgId) {
            var input = document.getElementById(inputId);
            var imgPreview = document.getElementById(imgId);

            // Check if a file is selected
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    // Show the image and set the src attribute to the selected file
                    imgPreview.style.display = "block";
                    imgPreview.src = e.target.result;
                }

                reader.readAsDataURL(input.files[0]); // Convert the file to a data URL
            }
        }
    </script>

    <script>

        var data_update_url='{{ route("admin.settings.page.update_settings", $settings->first()->page) }}'

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
                        url: data_update_url,
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
                            // document.getElementById("mainAdd").reset();
                            $('.custom-error').remove();
                            {{--window.location.href='{{route('dashboard')}}'--}}

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
