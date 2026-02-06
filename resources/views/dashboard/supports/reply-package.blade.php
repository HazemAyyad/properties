@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/css/pages/app-chat.css')}}" />
    <link href="{{asset('assets/magnify-master/magnify-master/dist/jquery.magnify.min.css')}}" rel="stylesheet">

    <style>
        .warehouse_package {
            /*min-height: 441px;*/
            /*border: 1px solid rgba(223, 58, 39, 0.4) !important;*/
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            border-radius: 8px;
            overflow: hidden;
        }

        .warehouse_package .card-header {
            padding: 0;
            position: relative;
        }

        .warehouse_package .card-header-tabs {
            display: flex;
            flex-flow: column;
            position: absolute;
            top: 0;
            margin: 0;
            background-color: #FFFFFF;
            height: 100%;
            opacity: 0.8;
        }

        .warehouse_package .card-header-tabs .nav-item {
            height: 50%;

        }

        .warehouse_package .card-header-tabs .nav-item .nav-link {
            height: 100%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #df3a27;
            border: 0 !important;

        }

        .warehouse_package .card-header-tabs .nav-item:first-child {
            border-bottom: 1px solid #de3c29;
        }

        .warehouse_package .card-header-tabs .nav-item .nav-link.active:hover {
            color: #df3a27;
        }

        .warehouse_package .card-header-tabs .nav-item .nav-link:hover {
            color: #FFFFFF;
        }

        .warehouse_package .card-header-tabs .nav-item .nav-link i {
            font-size: 30px;

        }

        .warehouse_package img {
            width: 100%;
            min-height: 200px;
            max-height: 200px;
        }

        #link_open_1 {
            display: block;
            /*border: 1px solid #df3a27;*/
            background-color: #df3a27;
            color: #fff;
            padding: 4px;
            text-align: center;
            text-transform: capitalize;
        }

        .content-package p {
            margin: 0;
        }

        .warehouse_package .custom-checkbox > [type="checkbox"]:not(:checked) + label, .warehouse_package .custom-checkbox > [type="checkbox"]:checked + label {
            position: absolute;
            right: 5px;
            top: 13px;
        }

        .warehouse_package .custom-checkbox > [type="checkbox"]:not(:checked) + label:before, .warehouse_package .custom-checkbox > [type="checkbox"]:checked + label:before {
            width: 22px;
            height: 22px;
        }

        .warehouse_package .custom-checkbox > [type="checkbox"]:not(:checked) + label:after, .warehouse_package .custom-checkbox > [type="checkbox"]:checked + label:after {
            left: 6px;
        }

        @media (min-width: 992px) {
            .modal-lg, .modal-xl {
                max-width: 800px !important;
            }
        }

        @media (min-width: 576px) {
            .modal-dialog {
                max-width: 500px;
                margin: 1.75rem auto;
            }
        }

        #add_price p {
            margin: 0;
        }

        .tab a {
            background-color: #FADFDC;
            /* float: left; */
            text-align: center;
            border: none;
            outline: none;
            cursor: pointer;
            padding: 10px 10px;
            transition: 0.3s;
            font-size: 14px;
            margin: 10px;
            width: 160px;
            border-radius: 25px;
            color: #DF3A27;
        }

        .tab a.active {
            color: #fff;
            background-color: #DF3A27;
        }

        .tab a:hover {
            color: #fff;
            background-color: #DF3A27;
        }

    </style>
    <style>
        .copy-button {
            /*height: 25px;*/
            /*display: flex;*/
            /*justify-content: center;*/
            /*align-items: center;*/
            /*position: relative*/
        }

        .tip {
            background-color: #263646;
            padding: 0 14px;
            line-height: 27px;
            position: absolute;
            border-radius: 4px;
            z-index: 100;
            color: #fff;
            font-size: 12px;
            animation-name: tip;
            animation-duration: .6s;
            animation-fill-mode: both
        }

        .tip:before {
            content: "";
            background-color: #263646;
            height: 10px;
            width: 10px;
            display: block;
            position: absolute;
            transform: rotate(45deg);
            top: -4px;
            left: 17px
        }

        #copied_tip {
            animation-name: come_and_leave;
            animation-duration: 1s;
            animation-fill-mode: both;
            bottom: 10px;
            /*left:2px*/
        }

        .text-line {
            font-size: 14px
        }

        .us_address {
            padding-left: 12%;
        }

        @media (max-width: 767px) {
            .us_address {
                padding-left: 20%;
            }
        }

        .alert .card {
            color: #000000;
        }
        .navbar-warehouse .tab a{
            position: relative;

        }
        .navbar-warehouse .tab a .count{
            position: absolute;
            top: -5px;
            left: 0;
        }
    </style>
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

        .l-bg-ticket {
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
    <style>
        .modal-lg, .modal-xl {
            max-width: 800px;
        }

    </style>
    <style>


        .chat ::-webkit-scrollbar {
            width: 10px
        }

        .chat ::-webkit-scrollbar-track {
            background: #eee
        }

        .chat  ::-webkit-scrollbar-thumb {
            background: #888
        }

        .chat ::-webkit-scrollbar-thumb:hover {
            background: #555
        }

        .chat .wrapper {
            height: 100%;
            width: 100%;
            display: flex;
            justify-content: center;
            align-items: center;
            /*background-color: #651FFF*/
        }

        .chat .main {
            background-color: #eee;
            width: 100%;
            height: 100%;
            position: relative;
            border-radius: 8px;
            box-shadow: 0 4px 8px 0 rgba(0, 0, 0, 0.2), 0 6px 20px 0 rgba(0, 0, 0, 0.19);
            padding: 6px 0px 0px 0px
        }

        .chat  .scroll {
            overflow-y: scroll;
            scroll-behavior: smooth;
            height: 100%;
            padding: 2rem 1.5rem !important;
        }

        /*.chat-message-text {*/
        /*    background-color: #fff;*/
        /*    box-shadow: 0 0.125rem 0.25rem rgba(165, 163, 174, 0.3);*/
        /*}*/
    </style>
    <style>
        .avatar {
            position: relative;
            width: 2.375rem;
            height: 2.375rem;
            cursor: pointer;
        }
        .avatar .avatar-initial {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            text-transform: uppercase;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #fff;
            background-color: #c9c8ce;
            font-weight: 600;
        }
        .avatar-sm .avatar-initial {
            font-size: 0.75rem;
        }
        .bg-label-primary {
            background-color: #eae8fd !important;
            color: #7367f0 !important;
        }
        .rounded-circle {
            border-radius: 50% !important;
        }
        .cursor-pointer {
            cursor: pointer !important;
        }
    </style>
    {{--    <link rel="stylesheet" href="{{ asset('assets/css/core.css')}}" />--}}
    <link rel="stylesheet" href="{{ asset('assets/css/app-chat.css')}}" />
    <style>
        #title_div_ticket{
            height: 100%;
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
        <div class="container-xxl flex-grow-1 container-p-y chat">
            <div class="row mb-5">
                <div class="col-md-5 mt-5">
                    <div class="card warehouse_package">
                        <div class="card-header">
                             <ul class="nav nav-tabs card-header-tabs m-0" data-bs-tabs="tabs">
                                <li class="nav-item">
                                    <a class="nav-link active" aria-current="true"
                                       data-bs-toggle="tab" href="#open{{$package->id}}">
                                        <i class="fa-solid fa-box-open"></i>
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" data-bs-toggle="tab"
                                       href="#close{{$package->id}}">
                                        <i class="fa-solid fa-box"></i>
                                    </a>
                                </li>
                            </ul>
                            <div class="tab-content p-0">
                                <div class="tab-pane active" id="open{{$package->id}}">


                                    @if($package->open_package_photos!=null)
                                        <a class="fw-bold" data-magnify="gallery"
                                           data-caption="box open"
                                           href="{{$package->open_package_photos[0]['photo']}}"
                                           id="link_open_1">
                                            more Photo
                                        </a>
                                        @foreach($package->open_package_photos as $iem)
                                            @if($loop->first)
                                                <a data-magnify="gallery"
                                                   data-caption="box open"
                                                   data-group="open_{{$package->id}}"
                                                   href="{{$iem->photo}}">
                                                    <img src="{{$iem->photo}}">
                                                </a>
                                            @else
                                                <a data-magnify="gallery"
                                                   data-caption="box open"
                                                   data-group="open_{{$package->id}}"
                                                   href="{{$iem->photo}}" style="display: none">
                                                    <img src="{{$iem->photo}}">
                                                </a>
                                            @endif
                                        @endforeach

                                    @endif

                                </div>
                                <div class="tab-pane" id="close{{$package->id}}">

                                    @if($package->close_package_photos!=null)
                                        <a class="fw-bold" data-magnify="gallery"
                                           data-caption="box close"
                                           href="{{$package->close_package_photos[0]['photo']}}"
                                           id="link_open_1">
                                            more Photo
                                        </a>
                                        @foreach($package->close_package_photos as $iem)
                                            @if($loop->first)
                                                <a data-magnify="gallery"
                                                   data-caption="box close"
                                                   data-group="close_{{$package->id}}"
                                                   href="{{$iem->photo}}">
                                                    <img src="{{$iem->photo}}">
                                                </a>
                                            @else
                                                <a data-magnify="gallery"
                                                   data-caption="box close"
                                                   data-group="close_{{$package->id}}"
                                                   href="{{$iem->photo}}" style="display: none">
                                                    <img src="{{$iem->photo}}">
                                                </a>
                                            @endif
                                        @endforeach

                                    @endif
                                </div>
                            </div>

                        </div>
                        <div class="card-body">
                            <h5 class="text-center fw-bold"> {{$package->name_item}}</h5>
                            <div class="content-package text-center">
                                <p class="package_no"><span
                                        class="fw-bold">Package :</span>#{{$package->order_no}}
                                </p>
                                <p class="arrived_date"><span class="fw-bold">Received Date :</span>
                                    ({{$package->created_at->format('m/d/Y')}})</p>
                                <p class="arrived_date"><span class="fw-bold">arrived :</span>
                                    ({{$package->remain_days}} days left)</p>
                                <p class="track_no"><span class="fw-bold">Track No :</span><br>
                                    {{$package->track_no}}</p>


                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-7 mt-5">
                    <div class=" " id="title_div_ticket">
                        <div class="wrapper app-chat-history">
                            <div class="main chat-history-wrapper">
                                <div class="px-2 scroll chat-history-body" style="height: 100%">
                                    <ul class="list-unstyled chat-history">

                                        <div class="text-center">
                                            <div class="card-status l-bg-ticket">
                                                <div class="card-statistic-3 p-4">
                                                    {{--                                        <div class="card-icon card-icon-large"><i class="fas fa-ticket-alt"></i></div>--}}
                                                    <div class=" ">
                                                        <h3 class="card-title mb-0">

                                                            {{__('Received Packages')}}


                                                        </h3>
                                                    </div>

                                                </div>
                                            </div>

                                        </div>
                                        <div class="text-center"> <span>{{ $support->created_at->format('M-d-y') }}</span></div>


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
                                                        <h3 class="mb-0">{{$support->subject}}


                                                        </h3>
                                                        <hr>
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

                            </div>
                        </div>
                    </div>
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
{{--                                    <input class="form-control message-input border-0 me-3 shadow-none" placeholder="Type your message here">--}}
                                    <div class="message-actions d-flex align-items-center col-md-2">
{{--                                        <i class="speech-to-text ti ti-microphone ti-sm cursor-pointer"></i>--}}
                                        <label for="attach-doc" class="form-label mb-0">
                                            <i class="ti ti-photo ti-sm cursor-pointer mx-3"></i>
                                            <input type="file"  name="image" id="attach-doc" hidden />
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
    <script src="{{asset('assets/magnify-master/magnify-master/dist/jquery.magnify.js')}}"></script>
    <script type="text/javascript">
        {{--CKEDITOR.replace('details', {--}}
        {{--    filebrowserUploadUrl: "{{route('ckeditor.image-upload', ['_token' => csrf_token() ])}}",--}}
        {{--    filebrowserUploadMethod: 'form'--}}
        {{--});--}}
    </script>
    <script>

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
