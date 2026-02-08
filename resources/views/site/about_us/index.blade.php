@extends('site.layouts.app')

@section('style')
    <style>
        .icon-box{
            width: 80px;
            height: 80px;
        }
        .icon-box img{
            width: 80px;
            height: 80px;
        }
    </style>
    <!-- Add your styles here if needed -->
@endsection

@section('content')
    <!-- Page Title -->
    <section class="flat-title-page style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{route('site.index')}}">{{__('Home')}}</a></li>
                <li>/ {{__('Pages')}}</li>
                <li>/ {{__('About Us')}}</li>
            </ul>
            <h2 class="text-center">{{__('About The')}} {{config('app.name')}}</h2>
        </div>
    </section>
    <!-- End Page Title -->

    <!-- banner video -->
    <section class="flat-section flat-banner-about">
        <div class="container">
            <div class="row">
                <div class="col-md-5">
                    <h3>{{__('Welcome To The')}} <br> {{config('app.name')}}</h3>
                </div>
                <div class="col-md-7 hover-btn-view">
                    <P class="body-2 text-variant-1">
                        @php
                            $description = collect($aboutUs)->firstWhere('key', 'description');
                        @endphp

                        @if (App::isLocale('en'))
                            {{ $description['value'] ?? 'Default description text' }}
                        @else
                            {{ $description['value_ar'] ?? 'Default description text' }}
                        @endif
                    </P>
                    <a href="#" class="btn-view style-1"><span class="text">{{__('Learn More')}}</span> <span class="icon icon-arrow-right2"></span> </a>

                </div>

            </div>
            <div class="banner-video">
                @php
                    $img_video = collect($aboutUs)->firstWhere('key', 'img-video');
                    $video = collect($aboutUs)->firstWhere('key', 'video');
                @endphp

                <img src="{{$img_video['value']}}" alt="img-video">
                <a href="{{$video['value']}}" data-fancybox="gallery2" class="btn-video"> <span class="icon icon-play"></span></a>
            </div>
        </div>
    </section>
    <!-- end banner video -->
    <!-- Service -->
    <section class="flat-section-v3 flat-service-v2 bg-surface">
        <div class="container">
            <div class="row wrap-service-v2">
                <div class="col-lg-6">
                    <div class="box-left">
                        <div class="box-title">
                            <div class="text-subtitle text-primary">{{__('Why Choose Us')}}</div>
                            <h4 class="mt-4">{{__('Discover What Sets Our Real Estate Expertise Apart')}}</h4>
                        </div>
                        @php
                            $why_choose_us = collect($aboutUs)->firstWhere('key', 'why_choose_us');

                        @endphp
                        <p>

                            @if (App::isLocale('en'))
                                {{ $why_choose_us['value'] ?? 'Default description text' }}
                            @else
                                {{ $why_choose_us['value_ar'] ?? 'Default description text' }}
                            @endif
                        </p>
                        <ul class="list-view">
                            @foreach($benefits as $benefit)
                            <li>
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M8 15.9947C12.4183 15.9947 16 12.4154 16 8C16 3.58462 12.4183 0.00524902 8 0.00524902C3.58172 0.00524902 0 3.58462 0 8C0 12.4154 3.58172 15.9947 8 15.9947Z" fill="#198754"/>
                                    <path d="M7.35849 12.2525L3.57599 9.30575L4.65149 7.9255L6.97424 9.735L10.8077 4.20325L12.2462 5.19975L7.35849 12.2525Z" fill="white"/>
                                </svg>
                                {{$benefit->title}}
                            </li>
                            @endforeach

                        </ul>
                        <a href="{{route('site.contact')}}" class="btn-view"><span class="text">{{__('Contact Us')}}</span> <span class="icon icon-arrow-right2"></span> </a>
                    </div>

                </div>
                <div class="col-lg-6">
                    <div class="box-right">
                        @foreach($services as $service)
                        <div class="box-service style-1 hover-btn-view">
                            <div class="icon-box" >
                                @php
                                    $imagePath = asset($service->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <img class="icon" src="{{$correctedImagePath}}" alt="" style="height: 80px;width: 80px" >
                            </div>
                            <div class="content">
                                <h6 class="title">{{$service->title}}</h6>
                                <p class="description">
                                    {{ substr($service->description, 0, 55) }}</p>
                                <a href="{{route('site.services')}}" class="btn-view style-1"><span class="text">{{__('Learn More')}}</span> <span class="icon icon-arrow-right2"></span> </a>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- End Service -->
    <!-- Testimonial -->
    <section class="flat-section flat-testimonial-v4">
        <div class="container">
            <div class="box-title text-center">
                <div class="text-subtitle text-primary">{{__('Our Testimonials')}}</div>
                <h4 class="mt-4">{{__('Clients feedback')}}</h4>
            </div>
            <div class="swiper tf-sw-testimonial" data-preview-lg="2" data-preview-md="2" data-preview-sm="2" data-space="30">
                <div class="swiper-wrapper">
                    @foreach($people_says as $people_say)
                        <div class="swiper-slide">
                            <div class="box-tes-item wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
                                <ul class="list-star">
                                    @for ($i = 1; $i <=$people_say->rating; $i++)
                                        <li class="icon icon-star"></li>
                                    @endfor


                                </ul>
                                <p class="note body-1">
                                    {{$people_say->description}}
                                </p>
                                @php
                                    $imagePath = asset($people_say->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <div class="box-avt d-flex align-items-center gap-12">
                                    <div class="avatar avt-60 round">
                                        <img src="{{$correctedImagePath}}" alt="avatar">
                                    </div>
                                    <div class="info">
                                        <div class="h7 fw-7">{{$people_say->name}}</div>
                                        <p class="text-variant-1 mt-4">{{$people_say->position}}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach

                </div>
                <div class="sw-pagination sw-pagination-testimonial"></div>

            </div>
            <div class="wrap-partner swiper tf-sw-partner" data-preview-lg="5" data-preview-md="4" data-preview-sm="3" data-space="80">
                <div class="swiper-wrapper">

                    @foreach($partners as $partner)
                        <div class="swiper-slide">
                            <div  class="partner-item">
                                @php
                                    $imagePath = asset($partner->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <img src="{{$correctedImagePath}}" alt="">
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>
    <!-- End Testimonial -->
    <!-- Contact -->
    <section class="flat-section-v3 flat-slider-contact">
        <div class="container">
            <div class="row content-wrap">
                <div class="col-lg-7">
                    <div class="content-left">
                        <div class="box-title">
                            <div class="text-subtitle text-white">{{__('Contact Us')}}</div>
                            <h4 class="mt-4 fw-6 text-white">{{__("We're always eager to hear from you!")}}</h4>
                        </div>
                        <p class="body-2 text-white">{{__('Sed ullamcorper nulla egestas at. Aenean eget tortor nec elit sagittis molestie. Pellentesque tempus massa in.r nulla egestas at. Aenean eget tortor nec elit sagittis mole')}}</p>
                    </div>

                </div>
                <div class="col-lg-5">
                    <form action="#" class="box-contact-v2">
                        <div class="box">
                            <label for="name" class="label">{{__('Name')}}:</label>
                            <input type="text" class="form-control" value="Tony Nguyen |">
                        </div>
                        <div class="box">
                            <label for="email" class="label">{{__('Email')}}:</label>
                            <input type="text" class="form-control" value="hi.themesflat@mail.com">
                        </div>
                        <div class="box">
                            <label for="message" class="label">{{__('Message')}}:</label>
                            <textarea name="message" class="form-control" cols="30" rows="10" placeholder="{{__('Write comment')}}"></textarea>
                        </div>
                        <div class="box">
                            <button class="tf-btn primary">{{__('Contact Us')}}</button>
                        </div>
                    </form>
                </div>
            </div>

        </div>
        <div class="overlay"></div>

    </section>
    <!-- End Contact -->
    <!-- Agents -->
    <section class="flat-section flat-agents">
        <div class="container">
            <div class="box-title text-center">
                <div class="text-subtitle text-primary">{{__('Our Teams')}}</div>
                <h4 class="mt-4">{{__('Meet Our Agents')}}</h4>
            </div>
            <div class="row">
                @foreach($agents as $agent)
                    <div class="box col-lg-4 col-sm-6">
                        <div class="box-agent style-1 hover-img">
                            <div class="box-img img-style">
                                @php
                                    $imagePath = asset($agent->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <img src="{{$correctedImagePath}}" alt="image-agent">
                                <ul class="agent-social">
                                    <li>
                                        <a href="{{$agent->facebook}}" target="_blank">
                                            <span class="icon icon-facebook"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{$agent->linkedin}}" target="_blank">
                                            <span class="icon icon-linkedin"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{$agent->twitter}}" target="_blank">
                                            <span class="icon icon-twitter"></span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="{{$agent->instagram}}" target="_blank">
                                            <span class="icon icon-instagram"></span>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <a href="#" class="content">
                                <div class="info">
                                    <h6 class="link">{{$agent->name}}</h6>
                                    <p class="mt-4 text-variant-1">{{$agent->position}}</p>
                                </div>
                                <span class="icon-phone"></span>
                            </a>
                         </div>
                    </div>
                 @endforeach


            </div>
        </div>
    </section>
    <!-- End Agents -->
    <!-- banner -->
    <section class="flat-section pt-0 flat-banner">
        <div class="container">
            <div class="wrap-banner bg-surface">
                <div class="box-left">
                    <div class="box-title">
                        <div class="text-subtitle text-primary">{{__('Become Partners')}}</div>
                        <h4 class="mt-4">{{__('List your Properties on')}} {{config('app.name')}}, {{__('join Us Now!')}}</h4>
                    </div>
                    <a href="#" class="tf-btn primary size-1">{{__('Become A Hosting')}}</a>
                </div>
                <div class="box-right">
                    <img src="{{asset('site/images/banner/banner.png')}}" alt="image">
                </div>
            </div>
        </div>
    </section>
    <!-- end banner -->
@endsection

@section('scripts')
    <!-- Add your scripts here if needed -->
@endsection
