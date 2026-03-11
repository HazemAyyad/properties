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
        /* Pricing cards: flex layout for consistent height & button alignment */
        .flat-pricing .box-pricing {
            display: flex;
            flex-direction: column;
        }
        .flat-pricing .box-pricing .list-price {
            flex: 1;
            margin-bottom: 24px;
        }
        /* Feature items: proper alignment when text wraps */
        .flat-pricing .list-price .item {
            display: flex;
            align-items: flex-start;
            gap: 10px;
        }
        .flat-pricing .list-price .check-icon {
            flex-shrink: 0;
            margin-top: 2px;
        }
        .flat-pricing .list-price .item-text {
            flex: 1;
            min-width: 0;
            line-height: 1.5;
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
            <div class="row g-4">
                @foreach($plans as $plan)
                <div class="col-12 col-sm-6 col-lg-3 mb-4">
                    <div class="box box-pricing h-100">
                        <div class="price d-flex align-items-end">
                            <h4> {{$data_settings['currency'] ?? 'JOD'}} {{$plan->price_monthly}}</h4>
                            @if($plan->duration_months)
                                <span class="body-2 text-variant-1">/{{ $plan->duration_months == 1 ? __('month') : __('months') }}</span>
                            @else
                                <span class="body-2 text-variant-1">{{ $plan->price_monthly == 0 ? __('free') : '' }}</span>
                            @endif
                        </div>
                        <div class="box-title-price">
                            <h6 class="title">{{$plan->title}}</h6>
                            <p class="desc">{{$plan->description}}</p>
                        </div>
                        <ul class="list-price">
                            @if($plan->duration_months)
                                <li class="item">
                                    <span class="check-icon icon-tick"></span>
                                    <span class="item-text">{{ $plan->duration_months }} {{ $plan->duration_months == 1 ? __('month') : __('months') }}</span>
                                </li>
                            @endif
                            <li class="item">
                                <span class="check-icon icon-tick"></span>
                                <span class="item-text">{{ __('Properties') }}: {{ $plan->number_of_properties_display }}</span>
                            </li>
                            @foreach($plan->features->where('status', 1) as $feature)
                                <li class="item">
                                    <span class="check-icon icon-tick"></span>
                                    <span class="item-text">{{ $feature->title }}</span>
                                </li>
                            @endforeach
                        </ul>
                        <a href="https://wa.me/{{$data_settings['whatsapp'] ?? ''}}" target="_blank" class="tf-btn">{{ __('Choose The Package') }}</a>
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
