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
                    <a href="{{route('admin.agents.index')}}">{{__('Agents')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Edit Agent')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Edit Agent')}}</h5>
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
                                                        <input type="text" class="form-control" name="name[{{ $locale }}]"  value="{{ $agent->getTranslation('name', $locale) }}" id="name_{{ $locale }}" placeholder="{{ __('Name in ') . strtoupper($locale) }}" required>
                                                    </div>

                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="position_{{ $locale }}">{{__('Position')}} ({{ strtoupper($locale) }})</label>
                                                        <input type="text" class="form-control" name="position[{{ $locale }}]" value="{{ $agent->getTranslation('position', $locale) }}" id="position_{{ $locale }}" placeholder="{{ __('Position') . strtoupper($locale) }}" required>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>

                            <div class="form-group mb-3">
                                <label class="form-label" for="facebook">{{__('Facebook')}}</label>
                                <input type="text" class="form-control" name="facebook" id="facebook" value="{{$agent->facebook}}"  placeholder="{{__('Facebook')}}" >
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="twitter">{{__('Twitter')}}</label>
                                <input type="text" class="form-control" name="twitter" id="twitter" value="{{$agent->twitter}}" placeholder="{{__('Twitter')}}" >
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="instagram">{{__('Instagram')}}</label>
                                <input type="text" class="form-control" name="instagram" id="instagram" value="{{$agent->instagram}}"  placeholder="{{__('Instagram')}}" >
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="linkedin">{{__('LinkedIn')}}</label>
                                <input type="text" class="form-control" name="linkedin" id="linkedin" value="{{$agent->linkedin}}"  placeholder="{{__('LinkedIn')}}" >
                            </div>
                            <div class="form-group mb-3">
                                <label class="form-label" for="phone">{{__('Phone')}}</label>
                                <input type="text" class="form-control" name="phone" id="phone" value="{{$agent->phone}}"  placeholder="{{__('Phone')}}" >
                            </div>
                            <div class="form-group  mb-3">
                                <label for="photo" class="form-label">photo</label>
                                <input class="form-control" name="photo" type="file" id="photo" >
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

@endsection
@section('scripts')
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

        var data_url='{{ route('admin.agents.update',$agent->id)}}'

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
