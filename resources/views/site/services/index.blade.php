@extends('site.layouts.app')

@section('style')
    <!-- Add your styles here if needed -->
@endsection

@section('content')
    <!-- Page Title -->
    <section class="flat-title-page style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="index.html">{{__('Home')}}</a></li>
                <li>/ {{__('Pages')}}</li>
                <li>/ {{__('Our Services')}}</li>
            </ul>
            <h2 class="text-center">{{__('Our Services')}}</h2>
        </div>
    </section>
    <!-- End Page Title -->

    <!-- Service -->
    <section class="flat-section flat-service-v3">
        <div class="container">
            <div class="box-title text-center">
                <div class="text-subtitle text-primary">{{__('Our Services')}}</div>
                <h4 class="mt-4">{{__('What We Do?')}}</h4>
            </div>
            <div class="row">
                @foreach($services as $service)
                    <div class="box col-lg-4 col-md-6">
                        <div class="box-service style-2">
                            @php
                                $imagePath = asset($service->photo);
                                $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                            @endphp
                            <div class="icon-box">
                                <img class="icon" src="{{$correctedImagePath}}" alt="" style="height: 80px;width: 80px" >
                            </div>
                            <div class="content">
                                <h6 class="title">{{$service->title}}</h6>
                                <p class="description">{{$service->description}}</p>
                                <a href="{{route('site.properties')}}" class="tf-btn size-1">{{__('Find A Home')}}</a>
                            </div>
                        </div>
                    </div>
                @endforeach


            </div>
        </div>
    </section>
    <!-- End Service -->
    <!-- Testimonial -->
    <section class="flat-section-v3 bg-surface flat-testimonial">
        <div class="cus-layout-1">
            <div class="row align-items-center">
                <div class="col-lg-3">
                    <div class="box-title">
                        <div class="text-subtitle text-primary">{{__('Top Properties')}}</div>
                        <h4 class="mt-4">{{__('Clients feedback')}}</h4>
                    </div>
                    <p class="text-variant-1 p-16">{{__('Our seasoned team excels in real estate with years of successful market navigation, offering informed decisions and optimal results')}}.</p>
                    <div class="box-navigation">
                        <div class="navigation swiper-nav-next nav-next-testimonial"><span class="icon icon-arr-l"></span></div>
                        <div class="navigation swiper-nav-prev nav-prev-testimonial"><span class="icon icon-arr-r"></span></div>
                    </div>
                </div>
                <div class="col-lg-9">
                    <div class="swiper tf-sw-testimonial" data-preview-lg="2.6" data-preview-md="2" data-preview-sm="2" data-space="30">

                        <div class="swiper-wrapper">
                            @foreach($people_says as $people_say)
                                <div class="swiper-slide">
                                    <div class="box-tes-item  "  >
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
                    </div>

                </div>

            </div>
        </div>
    </section>
    <!-- End Testimonial -->
    <!-- faq -->
    <section class="flat-section">
        <div class="container">
            <div class="box-title text-center">
                <div class="text-subtitle text-primary">{{__('FAQs')}}</div>
                <h4 class="mt-4">{{__('Quick answers to questions')}}</h4>
            </div>
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="tf-faq">
                        <ul class="box-faq" id="wrapper-faq">
                            @foreach($faqs as $faq)
                                <li class="faq-item">
                                    <a href="#accordion-faq-{{$faq->id}}" class="faq-header collapsed" data-bs-toggle="collapse" aria-expanded="false" aria-controls="accordion-faq-{{$faq->id}}">
                                        @if (App::isLocale('en'))
                                            {{$faq->title_en}}
                                        @else
                                            {{$faq->title_ar}}
                                        @endif

                                    </a>
                                    <div id="accordion-faq-{{$faq->id}}" class="collapse" data-bs-parent="#wrapper-faq">
                                        <p class="faq-body">

                                            @if (App::isLocale('en'))
                                                {{$faq->answer_en}}
                                            @else
                                                {{$faq->answer_ar}}
                                            @endif
                                         </p>
                                    </div>

                                </li>
                            @endforeach


                        </ul>

                    </div>

                </div>
            </div>

        </div>
    </section>
    <!-- faq -->
    <!-- banner -->
    <section class="flat-section pt-0 flat-banner">
        <div class="container">
            <div class="wrap-banner bg-surface">
                <div class="box-left">
                    <div class="box-title">
                        <div class="text-subtitle text-primary">{{__('Become Partners')}}</div>
                        <h4 class="mt-4">{{__('List your Properties on')}} {{config('app.name')}}, {{__('join Us Now!')}}</h4>
                    </div>
                    <a href="{{route('site.index')}}" class="tf-btn primary size-1">{{__('Become A Hosting')}}</a>
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

@endsection
