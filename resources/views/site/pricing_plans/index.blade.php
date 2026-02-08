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
                <li>/ {{__('Our Pricing')}}</li>
            </ul>
            <h2 class="text-center">{{__('Pricing Plans')}}</h2>
        </div>
    </section>
    <!-- End Page Title -->

    <section class="flat-section flat-pricing">
        <div class="container">
            <div class="box-title text-center">
                <div class="text-subtitle text-primary">Pricing</div>
                <h4 class="mt-4">Our Subscription</h4>
            </div>
            <div class="row">
                @foreach($plans as $plan)


                <div class="box col-lg-4 col-md-6">
                    <div class="box-pricing">
                        <div class="price d-flex align-items-end">
                            <h4> {{$data_settings['currency']}} {{$plan->price_monthly}}</h4>
                            <span class="body-2 text-variant-1">/month</span>
                        </div>
                        <div class="box-title-price">
                            <h6 class="title">{{$plan->title}}</h6>
                            <p class="desc">{{$plan->description}}</p>
                        </div>
                        <ul class="list-price">
                            @foreach($plan->features as $feature)
                                <li class="item">
                                    <span class="check-icon icon-tick {{$feature->status ==0?'disable':''}}"></span>
                                    {{$feature->title}}
                                </li>
                            @endforeach

                        </ul>
                        <a href="https://wa.me/{{$data_settings['whatsapp']}}" target="_blank" class="tf-btn">Choose The Package</a>
                    </div>
                </div>
                @endforeach


            </div>
        </div>
    </section>
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

@endsection

@section('scripts')
    <!-- Add your scripts here if needed -->

@endsection
