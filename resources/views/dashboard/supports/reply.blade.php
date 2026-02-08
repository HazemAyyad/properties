@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css')}}" />
    <style>
        table.dataTable{
            width: 100% !important;
        }
        .dataTables_paginate  .page-item.active .page-link {
            z-index: 3;
            color: #fff;
            background-color: #df3a27;
            border-color: #df3a27;
        }
        .card-status {
            background-color: #fff;
            border-radius: 10px;
            border: none;
            position: relative;
            margin-bottom: 30px;
            box-shadow: 0 0.46875rem 2.1875rem rgba(90,97,105,0.1), 0 0.9375rem 1.40625rem rgba(90,97,105,0.1), 0 0.25rem 0.53125rem rgba(90,97,105,0.12), 0 0.125rem 0.1875rem rgba(90,97,105,0.1);
        }
        .l-bg-shipped-out-old {
            background: linear-gradient(to right, #493240, #f09) !important;
            color: #fff;
        }

        .l-bg-delivered {
            background: linear-gradient(to right, #373b44, #4286f4) !important;
            color: #fff;
        }

        .l-bg-pending {
            background: linear-gradient(to right, #df3a27, #ff1a00bf) !important;
            color: #fff;
        }

        .l-bg-canceled {
            background: linear-gradient(to right, #a86008, #ffba56) !important;
            color: #fff;
        }

        .card-status .card-statistic-3 .card-icon-large .fas, .card-status .card-statistic-3 .card-icon-large .far, .card-status .card-statistic-3 .card-icon-large .fab, .card .card-statistic-3 .card-icon-large .fal {
            font-size: 110px;
        }

        .card-status .card-statistic-3 .card-icon {
            text-align: center;
            line-height: 50px;
            margin-left: 15px;
            color: #000;
            position: absolute;
            right: 10px;
            top: 20px;
            opacity: 0.1;
        }

        .l-bg-cyan {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }

        .l-bg-shipped-out {
            background: linear-gradient(135deg, #23bdb8 0%, #43e794 100%) !important;
            color: #fff;
        }

        .l-bg-orange {
            background: linear-gradient(to right, #f9900e, #ffba56) !important;
            color: #fff;
        }

        .l-bg-need-pay {
            background: linear-gradient(135deg, #289cf5, #84c0ec) !important;
            color: #fff;
        }
        /*=============*/
        .nav-tabs .nav-item.show .nav-link, .nav-tabs .nav-link.active {
            color: #f44a37;
            background-color: #fff;
            border-color: #f44a37 #f44a37 #fff;
        }
        .nav-tabs {
            border-bottom: 1px solid #f44a37;
            justify-content: center;
        }
        .nav-link {

            color: #000000;
            font-weight: 600;

        }
        .accordion-item{
            border-radius: 10px !important;
            margin-top: 2%;
            margin-bottom: 2%;
            border: 1px solid rgba(0,0,0,.125) !important;
        }
        .accordion-item .accordion-button {
            border-radius: 10px !important;

        }
        .accordion-button{
            color: #000000;
            font-weight: 600;
            background-color: #f4f4f4;
        }
        .accordion-button:focus {
            z-index: 3;
            border-color: #f73b39;
            outline: 0;
            box-shadow: unset;
        }
        .accordion-button:not(.collapsed){
            color: #000000;
            font-weight: 600;
            background-color: #f4f4f4;
        }
        .accordion-button:not(.collapsed) {
            color: #000000;
            font-weight: 600;
            background-color: #f4f4f4;
        }
        .accordion-button .link{
            margin-top: 0;
        }
    </style>
@endsection
@section('content')

    <!-- Content -->

    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}">{{__('Home')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('contact.index')}}">{{__('Contact')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Support Reply')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="app-chat card overflow-hidden mb-5">
                <div class="row g-0">
                    <!-- Chat History -->
                    <div class="col app-chat-history  bg-body">
                        <div class="chat-history-wrapper">
                            <div class="chat-history-body bg-body" style="height: 100%;">
                                <ul class="list-unstyled chat-history">
                                    <div class="text-center">
                                        <div class="card-status l-bg-delivered">
                                            <div class="card-statistic-3 p-4">
                                                {{--                                        <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>--}}
                                                <div class=" ">
                                                    <h3 class="card-title mb-0 text-white">
                                                        @if($support->type==1)
                                                            {{__('General')}}
                                                        @elseif($support->type==2)
                                                            {{__('Payment')}}
                                                        @elseif($support->type==3)
                                                            {{__('Business')}}
                                                        @elseif($support->type==4)
                                                            {{__('Shipping Rate')}}
                                                        @elseif($support->type==5)
                                                            {{__('problem in website')}}
                                                        @elseif($support->type==6)
                                                            {{__('Claims')}}
                                                        @else
                                                            <span class="badge bg-secondary text-success">{{__('Other')}}</span>

                                                        @endif
                                                    </h3>
                                                </div>

                                            </div>
                                        </div>

                                    </div>
                                        <div class="text-center"> <span>{{$support->created_at->format('M-d-y')}}</span></div>


                                                <li class="chat-message">
                                                    <div class="d-flex overflow-hidden">
                                                        <div class="user-avatar flex-shrink-0 me-3">
                                                            <div class="avatar avatar-sm">
                                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                                     @php
                                                                         $userName = $support->user->name;
                                                                         $words = explode(' ', $userName);
                                                                     @endphp
                                                                    @foreach($words as $word)
                                                                        {{ strtoupper(substr(strtok($word, ' '), 0, 1)) }}
                                                                    @endforeach
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="chat-message-wrapper flex-grow-1">
                                                            <div class="chat-message-text">
                                                                <p class="mb-0">{{ $support->subject }}</p>
                                                                <hr class="">
                                                                <p class="mb-0">{!! nl2br(e($support->details)) !!}</p>

                                                            </div>
                                                            <div class="text-muted mt-1">
                                                                <small>{{ $support->created_at->format('H:i A') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>




                                </ul>
                            </div>
                            <!-- Chat message form -->
                        </div>
                    </div>
                    <!-- /Chat History -->
                </div>
            </div>
            <div class="app-chat card overflow-hidden">
                <div class="row g-0">
                    <!-- Chat History -->
                    <div class="col app-chat-history bg-body">
                        <div class="chat-history-wrapper">
                            <div class="chat-history-header border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="d-flex overflow-hidden align-items-center">
                                        <i
                                            class="ti ti-menu-2 ti-sm cursor-pointer d-lg-none d-block me-2"
                                            data-bs-toggle="sidebar"
                                            data-overlay
                                            data-target="#app-chat-contacts"
                                        ></i>
                                        <div class="flex-shrink-0 avatar">
                                            <span class="avatar-initial rounded-circle bg-label-primary">
                                                @php
                                                    $userName = $support->user->name;
                                                    $words = explode(' ', $userName);
                                                @endphp
                                                @foreach($words as $word)
                                                    {{ strtoupper(substr(strtok($word, ' '), 0, 1)) }}
                                                @endforeach
                                            </span>
                                            <i
                                                class="ti ti-x ti-sm cursor-pointer close-sidebar"
                                                data-bs-toggle="sidebar"
                                                data-overlay
                                                data-target="#app-chat-sidebar-left"
                                            ></i>
                                        </div>
                                        <div class="chat-contact-info flex-grow-1 ms-2">
                                            <h6 class="m-0">{{$support->user->name}}</h6>
                                            <small class="user-status text-muted">member since :{{ $support->user->created_at->format('Y-m-d') }}</small>
                                        </div>
                                    </div>
                                    <div class="d-flex align-items-center">
{{--                                        <i class="ti ti-phone-call cursor-pointer d-sm-block d-none me-3"></i>--}}
{{--                                        <i class="ti ti-video cursor-pointer d-sm-block d-none me-3"></i>--}}
{{--                                        <i class="ti ti-search cursor-pointer d-sm-block d-none me-3"></i>--}}
                                        <div class="dropdown">
                                            <i
                                                class="ti ti-dots-vertical cursor-pointer"
                                                id="chat-header-actions"
                                                data-bs-toggle="dropdown"
                                                aria-haspopup="true"
                                                aria-expanded="false"
                                            >
                                            </i>
                                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="chat-header-actions">
                                                <a class="dropdown-item" href="{{route('users.edit',$support->user_id)}}">View User</a>
                                                <a class="dropdown-item" href="{{route('supports.status',[$support->id,0])}}">Convert To Pending</a>
                                                <a class="dropdown-item" href="{{route('supports.status',[$support->id,1])}}">Convert To Open</a>
                                                <a class="dropdown-item" href="{{route('supports.status',[$support->id,2])}}">Convert To Closed</a>
                                                <a class="dropdown-item" href="{{route('supports.status',[$support->id,3])}}">Convert To Solved</a>

                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="chat-history-body bg-body">
                                <ul class="list-unstyled chat-history">
                                    @foreach($messages as $date => $message_item )
                                        <div class="text-center"> <span>{{$date}}</span></div>
                                        @foreach($message_item as $message)
                                            @if($message->type=='admin')
                                                <li class="chat-message chat-message-right">
                                                    <div class="d-flex overflow-hidden">
                                                        <div class="chat-message-wrapper flex-grow-1">
                                                            <div class="chat-message-text">
                                                                <p class="mb-0">{!! nl2br(e($message->details)) !!}</p>
                                                                @if($message->image!=null)
                                                                    <p class="mb-0">
                                                                        <a href="{{env('SITE_URL').'public'.$message->image}}" class="text-white" target="_blank"><i class="fas fa-download"></i></a>
                                                                    </p>
                                                                @endif

                                                            </div>
                                                            <div class="text-end text-muted mt-1">
                                                                <i class="ti ti-checks ti-xs me-1 text-success"></i>
                                                                <small>{{ $message->created_at->format('H:i A') }}</small>
                                                            </div>
                                                        </div>
                                                        <div class="user-avatar flex-shrink-0 ms-3">
                                                            <div class="avatar avatar-sm">
                                                                {{--                                                    <img src="../../assets/img/avatars/1.png" alt="Avatar" class="rounded-circle" />--}}
                                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                                @php
                                                                    $teamName = $message->team->name;
                                                                    $words = explode(' ', $teamName);
                                                                @endphp
                                                                    @foreach($words as $word)
                                                                        {{ strtoupper(substr(strtok($word, ' '), 0, 1)) }}
                                                                    @endforeach
                                                                </span>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                            @if($message->type=='user')
                                                <li class="chat-message">
                                                    <div class="d-flex overflow-hidden">
                                                        <div class="user-avatar flex-shrink-0 me-3">
                                                            <div class="avatar avatar-sm">
                                                                <span class="avatar-initial rounded-circle bg-label-primary">
                                                                     @php
                                                                         $userName = $support->user->name;
                                                                         $words = explode(' ', $userName);
                                                                     @endphp
                                                                    @foreach($words as $word)
                                                                        {{ strtoupper(substr(strtok($word, ' '), 0, 1)) }}
                                                                    @endforeach
                                                                </span>
                                                            </div>
                                                        </div>
                                                        <div class="chat-message-wrapper flex-grow-1">
                                                            <div class="chat-message-text">
                                                                <p class="mb-0">{!! nl2br(e($message->details)) !!}</p>
                                                                @if($message->image!=null)
                                                                    <p class="mb-0">
                                                                        <a href="{{env('SITE_URL').'public'.$message->image}}" class="text-black" target="_blank"><i class="fas fa-download"></i></a>
                                                                    </p>
                                                                @endif
                                                            </div>
                                                            <div class="text-muted mt-1">
                                                                <small>{{ $message->created_at->format('H:i A') }}</small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            @endif
                                        @endforeach
                                    @endforeach



                                </ul>
                            </div>
                            <!-- Chat message form -->
                            <div class="chat-history-footer shadow-sm">
                                <form class="form-send-message row"  id="mainAdd" method="post" action="javascript:void(0)">
                                    <div class="fv-row mb-7 fv-plugins-icon-container col-md-10">
                                        <!--begin::Label-->
                                        <label class="required fw-bold fs-6 mb-2" for="details">{{__('Description')}}</label>
                                        <!--end::Label-->
                                        <!--begin::Input-->
                                        <textarea  id="details_text" class="form-control" rows="7" name="details"></textarea>
                                        <!--end::Input-->
                                    </div>
                                        <div id="filePreview" style="width: 100px; height: 100px;"></div>
{{--                                    <input class="form-control message-input border-0 me-3 shadow-none" placeholder="Type your message here">--}}
                                    <div class="message-actions d-flex align-items-center col-md-2">
{{--                                        <i class="speech-to-text ti ti-microphone ti-sm cursor-pointer"></i>--}}
                                        <label for="attach-doc" class="form-label mb-0">
                                            <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                                            <input type="file" name="image" id="attach-doc" hidden/>
                                        </label>
                                        <button class="btn btn-primary d-flex send-msg-btn" type="submit"  id="add_form">
                                            <i class="ti ti-send me-md-1 me-0"></i>
                                            <span class="align-middle d-md-inline-block d-none">Send</span>
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                    <!-- /Chat History -->
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
    <script src="{{asset('assets/js/app-chat.js')}}"></script>
    <!-- END: Page JS-->
    <script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
    <script type="text/javascript">
        {{--CKEDITOR.replace('details', {--}}
        {{--    filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['_token' => csrf_token() ])}}",--}}
        {{--    filebrowserUploadMethod: 'form'--}}
        {{--});--}}
    </script>
    <script>
    
////////////////////////
        
    document.getElementById('attach-doc').addEventListener('change', function(event) {
      var fileInput = event.target;
      var file = fileInput.files[0];
    
      if (file) {
        displayFilePreview(file);
      } else {
        alert('Please select a file');
      }
    });
    
    function displayFilePreview(file) {
      var reader = new FileReader();
      reader.onload = function(event) {
        var filePreview = document.getElementById('filePreview');
        filePreview.innerHTML = '<img src="' + event.target.result + '" alt="Uploaded file" style="width: 100%; height: 100%;">';
      };
      reader.readAsDataURL(file);
    }/////////////

        var data_url='{{ route('supports.send_reply',$support->id)}}'

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
                    // postData.append('details', CKEDITOR.instances['details'].getData());
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
                            location.reload()
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
