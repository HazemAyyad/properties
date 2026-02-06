@extends('site.layouts.app')


@section('style')
    <!-- Add your styles here if needed -->
    <link rel="stylesheet" href="{{asset('site/css/jquery.fancybox.min.css')}}">
    <link rel="stylesheet" href="{{asset('site/css/animate.css')}}">
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />

    <style>

        /* component */

        .star-rating {

            display:flex;
            flex-direction: row-reverse;
            font-size:3em;
            justify-content:space-around;
            padding:0 .2em;
            text-align:center;
            width:5em;
        }

        .star-rating input {
            display:none;
        }

        .star-rating label {
            color:#ccc;
            cursor:pointer;
        }

        .star-rating :checked ~ label {
            color:#f90;
        }

        .star-rating label:hover,
        .star-rating label:hover ~ label {
            color:#fc0;
        }
        #map {
            height: 500px;
            width: 100%;
        }

        /* Allow the popup to take its natural width */
        .leaflet-popup-content-wrapper {
            width: auto !important;
            max-width: none !important;
        }

        /* Ensure content inside the popup is displayed properly */
        .leaflet-popup-content {
            /*margin:0 !important;*/
            margin:0 !important;
            width: 410px !important;
            /*width: auto  !important;*/
            max-width: none;
            white-space: normal;
        }
    </style>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
          integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY="
          crossorigin=""/>

@endsection

@section('content')
    <section class="flat-gallery-single">
        @foreach($property->images as $image)
            @php
                $imagePath = asset($image->img);
                $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
            @endphp
        @if($loop->first)


        <div class="item1 box-img">

            <img src="{{$correctedImagePath}}" alt="img-gallery">
            <div class="box-btn">
                <a href="{{$property->more_info->video_url}}" data-fancybox="gallery2" class="box-icon">
                    <span class="icon icon-play2"></span>
                </a>
                <a href="{{$correctedImagePath}}"  data-fancybox="gallery" class="tf-btn primary">{{__('View All Photos')}}</a>
            </div>
        </div>
            @else
        <a href="{{$correctedImagePath}}" class="item{{$image->id}} box-img" data-fancybox="gallery">
            <img src="{{$correctedImagePath}}" alt="img-gallery">
        </a>
            @endif
        @endforeach
    </section>

    <section class="flat-section-v6 flat-property-detail-v3">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <div class="header-property-detail">
                        <div class="content-top d-flex justify-content-between align-items-center">
                            <div class="box-name">
                                <a href="#" class="flag-tag primary">{{__('For')}}
                                    @if($property->status==0)
                                        {{__('Not available')}}
                                    @elseif($property->status==1)
                                        {{__('Preparing selling')}}
                                    @elseif($property->status==2)
                                        {{__('Selling')}}
                                    @elseif($property->status==3)
                                        {{__('sold')}}
                                    @elseif($property->status==4)
                                        {{__('Renting')}}
                                    @elseif($property->status==5)
                                        {{__('Rented')}}
                                    @elseif($property->status==6)
                                        {{__('Building')}}
                                    @else
                                        {{__('Unknown')}}
                                    @endif
                                </a>
                                <h4 class="title link">{{$property->title}}</h4>
                            </div>
                            <div class="box-price d-flex align-items-center">
                                <h4>${{$property->price->price}}</h4>
                                <span class="body-1 text-variant-1">/{{__('month')}}</span>
                            </div>
                        </div>
                        <div class="content-bottom">
                            <div class="info-box">
                                <div class="label">{{__('FEATUREs')}}:</div>
                                <ul class="meta">
                                    <li class="meta-item"><span class="icon icon-bed"></span> {{$property->more_info->bedrooms}} Bedroom</li>
                                    <li class="meta-item"><span class="icon icon-bathtub"></span> {{$property->more_info->bathrooms}} Bathroom</li>
                                    <li class="meta-item"><span class="icon icon-ruler"></span> {{$property->more_info->size}} m²</li>
                                </ul>
                            </div>

                            <ul class="icon-box">

                                <!-- Share Icon -->
{{--                                <li>--}}
{{--                                    <a href="javascript:" class="item" data-toggle="tooltip" data-placement="top" title="Share" onclick="copyToClipboard('{{ url()->current() }}')">--}}
{{--                                        <span class="icon icon-share"></span>--}}
{{--                                    </a>--}}
{{--                                </li>--}}
                                <!-- Favorite Icon -->
                                <li>
                                    <a href="javascript:" class="item" data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $property->id }})">
                                        <span id="favorite-icon-{{ $property->id }}" class="icon {{ $property->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                    </a>
                                </li>
                            </ul>




                        </div>
                    </div>
                    <div class="single-property-element single-property-desc">
                        <div class="h7 title fw-7">{{__('Description')}}</div>
                        {!! $property->more_info->content !!}
                    </div>
                    <div class="single-property-element single-property-overview">
                        <div class="h7 title fw-7">{{__('Overview')}}</div>
                        <ul class="info-box">
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-house-line"></i></a>
                                <div class="content">
                                    <span class="label">{{__('ID')}}:</span>
                                    <span>{{$property->id+1000}}</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-arrLeftRight"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Type')}}:</span>
                                    <span>{{$property->category->name}}</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-bed"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Bedrooms')}}:</span>
                                    <span>{{$property->more_info->bedrooms}} {{__('Rooms')}}</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-bathtub"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Bathrooms')}}:</span>
                                    <span>{{$property->more_info->bathrooms}} {{__('Rooms')}}</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-garage"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Garages')}}:</span>
                                    <span>{{$property->more_info->garagess}} {{__('Rooms')}}</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-ruler"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Size')}}:</span>
                                    <span>{{$property->more_info->size}} m²</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-crop"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Land Size')}}:</span>
                                    <span>{{$property->more_info->land_size}} m²</span>
                                </div>
                            </li>
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-hammer"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Year Built')}}:</span>
                                    <span>{{$property->more_info->year_built}} </span>
                                </div>
                            </li>
                        </ul>
                    </div>
                    <div class="single-property-element single-property-video">
                        <div class="h7 title fw-7">{{__('Video')}}</div>
                        <div class="img-video">
                            <img src="{{asset('site/images/banner/img-video.jpg')}}" alt="img-video">
                            <a href="{{$property->more_info->video_url}}" data-fancybox="gallery2" class="btn-video"> <span class="icon icon-play2"></span></a>
                        </div>
                    </div>
                    <div class="single-property-element single-property-info">
                        <div class="h7 title fw-7">{{__('Property Details')}}</div>
                        <div class="row">
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property ID')}}:</span>
                                    <div class="content fw-7">{{$property->id+1000}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Bedrooms')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->bedrooms}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Price')}}:</span>
                                    <div class="content fw-7">${{$property->price->price}}<span class="caption-1 fw-4 text-variant-1">/month</span></div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Bedrooms')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->bedrooms}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property Size')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->size}} m²</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Bathrooms')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->bathrooms}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Year built')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->year_built}}</div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property Type')}}:</span>
                                    <div class="content fw-7">{{$property->category->name}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Garage')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->garages}}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property Status')}}:</span>
                                    <div class="content fw-7">For
                                        @if($property->status==0)
                                            {{__('Not available')}}
                                        @elseif($property->status==1)
                                            {{__('Preparing selling')}}
                                        @elseif($property->status==2)
                                            {{__('Selling')}}
                                        @elseif($property->status==3)
                                            {{__('sold')}}
                                        @elseif($property->status==4)
                                            {{__('Renting')}}
                                        @elseif($property->status==5)
                                            {{__('Rented')}}
                                        @elseif($property->status==6)
                                            {{__('Building')}}
                                        @else
                                            {{__('Unknown')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Garage Size')}}:</span>
                                    <div class="content fw-7">{{$property->more_info->garages_size}} m²</div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="single-property-element single-property-feature">
                        <div class="h7 title fw-7">{{__('Amenities and features')}}</div>
                        <div class="wrap-feature">

                            @foreach($categoriesWithFeatures as $categoryName => $features)
                                <div class="box-feature">
                                    <div class="fw-7">{{ $categoryName }}:</div>
                                    <ul>
                                        @foreach($features as $feature)
                                            <li class="feature-item">
                                                <span class="fa-solid {{ $feature['icon'] }}"></span>
                                                {{ $feature['name'] }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach



                        </div>
                    </div>
                    <div class="single-property-element single-property-map">
                        <div id="map"></div>
                        <ul class="info-map">
                            <li>
                                <div class="fw-7">{{__('Address')}}</div>
                                <span class="mt-4 text-variant-1">
                                    {{$property->address->full_address}}, {{$property->address->city->name}},
                                                            {{$property->address->state->name}},{{$property->address->country->name}}
                                </span>
                            </li>
{{--                            <li>--}}
{{--                                <div class="fw-7">Downtown</div>--}}
{{--                                <span class="mt-4 text-variant-1">5 min</span>--}}

{{--                            </li>--}}
{{--                            <li>--}}
{{--                                <div class="fw-7">FLL</div>--}}
{{--                                <span class="mt-4 text-variant-1">15 min</span>--}}

{{--                            </li>--}}
                        </ul>
                    </div>

                    <div class="single-property-element single-property-loan">
                        <div class="h7 title fw-7">{{__('Loan Calculator')}}</div>
                        <form id="loanCalculator" class="box-loan-calc">
                            <div class="box-top">
                                <div class="item-calc">
                                    <label for="totalAmount" class="label">{{__('Total Amount')}}:</label>
                                    <input type="number" id="totalAmount" value="{{$property->price->price}}" readonly placeholder="10,000" class="form-control">
                                </div>
                                <div class="item-calc">
                                    <label for="downPayment" class="label">{{__('Down Payment')}}:</label>
                                    <input type="number" id="downPayment" placeholder="3000" class="form-control">
                                </div>
                                <div class="item-calc">
                                    <label for="amortizationPeriod" class="label">{{__('Amortization Period (months)')}}:</label>
                                    <input type="number" id="amortizationPeriod" placeholder="12" class="form-control">
                                </div>
                                <div class="item-calc">
                                    <label for="interestRate" class="label">{{__('Interest rate (%)')}}':</label>
                                    <input type="number" id="interestRate" placeholder="5" class="form-control">
                                </div>
                            </div>
                            <div class="box-bottom">
                                <button type="button" id="calculateButton" class="tf-btn primary">{{__('Calculate')}}</button>
                                <div class="d-flex gap-4">
                                    <span class="h7 fw-7">{{__('Monthly Payment')}}:</span>
                                    <span id="monthlyPayment" class="result h7 fw-7">$0.00</span>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="single-property-element single-property-nearby">
                        <div class="h7 title fw-7">{{__("What’s nearby?")}}</div>
                        <p class="body-2">{{__("Explore nearby amenities to precisely locate your property and identify surrounding conveniences, providing a comprehensive overview of the living environment and the property's convenience")}}.</p>
                        <div class="grid-2 box-nearby">
                            @php
                                $facilities = $property->facilities;
                                $half = ceil($facilities->count() / 2);
                                $facilitiesLeft = $facilities->take($half);
                                $facilitiesRight = $facilities->skip($half);
                            @endphp

                            <ul class="box-left">
                                @foreach($facilitiesLeft as $facility)
                                <li class="item-nearby">
                                    <span class="label">{{$facility->facility->name}}:</span>
                                    <span class="fw-7">{{$facility->distance}}</span>
                                </li>
                                @endforeach

                            </ul>
                            <ul class="box-right">
                                @foreach($facilitiesRight as $facility)
                                    <li class="item-nearby">
                                        <span class="label">{{$facility->facility->name}}:</span>
                                        <span class="fw-7">{{$facility->distance}}</span>
                                    </li>
                                @endforeach

                            </ul>
                        </div>

                    </div>
                    @include('site.properties.partials.section_reviews')
                </div>
                <div class="col-lg-4">
                    <div class="widget-sidebar fixed-sidebar wrapper-sidebar-right">
                        @include('site.properties.partials.contact_seller') <!-- Extract the search form into a partial -->

                        @include('site.properties.partials.filter_search',['features'=>$all_features]) <!-- Extract the search form into a partial -->

                        <div class="widget-box single-property-whychoose bg-surface">
                            <div class="h7 title fw-7">{{__('Why Choose Us?')}}</div>
                            <ul class="box-whychoose">
                                <li class="item-why">
                                    <i class="icon icon-secure"></i>
                                    {{__('Secure Booking')}}
                                </li>
                                <li class="item-why">
                                    <i class="icon icon-guarantee"></i>
                                    {{__('Best Price Guarantee')}}
                                </li>
                                <li class="item-why">
                                    <i class="icon icon-booking"></i>
                                    {{__('Easy Booking Process')}}
                                </li>
                                <li class="item-why">
                                    <i class="icon icon-support"></i>
                                    {{__('Available Support 24/7')}}
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </section>
    <section class="flat-section pt-0 flat-latest-property">
        <div class="container">
            <div class="box-title">
                <div class="text-subtitle text-primary">{{__('Featured properties')}}</div>
                <h4 class="mt-4">{{__('The Most Recent Estate')}}</h4>
            </div>
            <div class="swiper tf-latest-property" data-preview-lg="3" data-preview-md="2" data-preview-sm="2" data-space="30" data-loop="true">
                <div class="swiper-wrapper">
                    @foreach($latestProperties as $item)
                        <div class="swiper-slide">
                            <div class="homeya-box style-2">
                                <div class="archive-top">
                                    @php
                                        $imagePath = asset($item->images[0]->img);
                                        $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                    @endphp
                                    <div href="javascript:" class="images-group">
                                        <div class="images-style">
                                            <img src="{{$correctedImagePath}}" alt="{{ $item->title }}">
                                        </div>
                                        <div class="top">
                                            <ul class="d-flex gap-8">
                                                @if($item->is_featured)
                                                    <li class="flag-tag success">Featured</li>
                                                @endif
                                                <li class="flag-tag style-1">
                                                    @if($item->status==0)
                                                        {{__('Not available')}}
                                                    @elseif($item->status==1)
                                                        {{__('Preparing selling')}}
                                                    @elseif($item->status==2)
                                                        {{__('Selling')}}
                                                    @elseif($item->status==3)
                                                        {{__('sold')}}
                                                    @elseif($item->status==4)
                                                        {{__('Renting')}}
                                                    @elseif($item->status==5)
                                                        {{__('Rented')}}
                                                    @elseif($item->status==6)
                                                        {{__('Building')}}
                                                    @else
                                                        {{__('Unknown')}}
                                                    @endif
                                                </li>
                                            </ul>
                                            <ul class="d-flex gap-4">
                                                <!-- Favorite Icon -->
                                                <li class="box-icon w-32" >

                                                    <a href="javascript:"  data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $item->id }})">
                                                        <span id="favorite-icon-{{ $item->id }}" class="icon {{ $item->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                                    </a>

                                                </li>

                                                <li class="box-icon w-32"  >
                                                    <a href="{{ route('site.property.show', $item->slug) }}"  data-toggle="tooltip" data-placement="top" title="Visit" onclick="toggleFavorite({{ $item->id }})">
                                                        <span class="icon icon-eye"></span>
                                                    </a>

                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom">
                                            <span class="flag-tag style-2">{{ $item->category->name }}</span>
                                        </div>
                                    </div>
                                    <div class="content">
                                        <div class="h7 text-capitalize fw-7">
                                            <a href="{{ route('site.property.show', $item->slug) }}" class="link">{{ $item->title }}</a>
                                        </div>
                                        <div class="desc"><i class="fs-16 icon icon-mapPin"></i><p>
                                                {{$item->address->full_address}}, {{$item->address->city->name}},
                                                {{$item->address->state->name}},{{$item->address->country->name}}
                                            </p></div>
                                        <ul class="meta-list">
                                            <li class="item">
                                                <i class="icon icon-bed"></i>
                                                <span>{{ $item->more_info->bedrooms }}</span>
                                            </li>
                                            <li class="item">
                                                <i class="icon icon-bathtub"></i>
                                                <span>{{ $item->more_info->bathrooms }}</span>
                                            </li>
                                            <li class="item">
                                                <i class="icon icon-ruler"></i>
                                                <span>{{ $item->more_info->size }} m²</span>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="archive-bottom d-flex justify-content-between align-items-center">
                                    <div class="d-flex gap-8 align-items-center">
                                        <div class="avatar avt-40 round">
                                            @if($item->user_id!=null)
                                                <img src="{{asset($item->user->photo)}}" alt="{{$item->user->name}}">

                                            @else
                                                <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                            @endif
                                        </div>
                                        <span>
                                                    @if($item->user_id!=null)
                                                {{$item->user->name}}
                                            @else
                                                {{config('app.name')}}

                                            @endif
                                                    </span>
                                    </div>
                                    <div class="d-flex align-items-center">
                                        <h6>${{ $item->price->price }}</h6>
                                        <span class="text-variant-1">/m²</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </div>
    </section>

@endsection
@section('scripts')
    <script type="text/javascript" src="{{asset('site/js/jquery.fancybox.js')}}"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyAuSiPhoDaOJ7aqtJVtQhYhLzwwJ7rQlmA"></script>
    <script src="{{asset('site/js/map-single.js')}}"></script>
    <script src="{{asset('site/js/marker.js')}}"></script>
    <script src="{{asset('site/js/infobox.min.js')}}"></script>
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    <script>
        $(document).ready(function() {
            $('#calculateButton').on('click', function() {
                // Retrieve values from the form
                var totalAmount = parseFloat($('#totalAmount').val());
                var downPayment = parseFloat($('#downPayment').val());
                var amortizationPeriod = parseFloat($('#amortizationPeriod').val());
                var interestRate = parseFloat($('#interestRate').val()) / 100;

                // Ensure that inputs are valid numbers
                if (isNaN(totalAmount) || isNaN(downPayment) || isNaN(amortizationPeriod) || isNaN(interestRate) || amortizationPeriod <= 0) {
                    alert('Please enter valid numbers for all fields.');
                    return;
                }

                // Calculate the loan amount
                var loanAmount = totalAmount - downPayment;

                // Calculate the monthly payment
                var monthlyInterestRate = interestRate / 12;
                var numberOfPayments = amortizationPeriod;
                var monthlyPayment;

                if (monthlyInterestRate > 0) {
                    monthlyPayment = (loanAmount * monthlyInterestRate) / (1 - Math.pow(1 + monthlyInterestRate, -numberOfPayments));
                } else {
                    monthlyPayment = loanAmount / numberOfPayments;
                }

                // Display the result
                $('#monthlyPayment').text('$' + monthlyPayment.toFixed(2));
            });
        });
    </script>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <!-- Toastr JS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // Initialize the map and set its view to a given geographical point and zoom level
        var map = L.map('map').setView([43.052898, -76.430518], 5);

        // Add a tile layer to the map (this example uses OpenStreetMap tiles)
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="{{config('app.url')}}">{{config('app.name')}}</a> contributors'
        }).addTo(map);

        // Add a marker to the map at the specific latitude and longitude
        var marker = L.marker([43.052898, -76.430518]).addTo(map);

        // Define the HTML content for the popup
        var popupContent = `
        <div class="map-listing-item">
            <div class="inner-box">
                <div class="image-box">
                    <a href="{{ route('site.property.show', $item->slug) }}">
                        <img data-bb-lazy="true" loading="lazy" src="{{$property->images[0]->img}}" alt="{{ Str::limit($property->title, 20) }}" class="entered loaded">
                    </a>
                    <span class="flag-tag primary">
                    @if($property->status==0)
                            {{__('Not available')}}
                            @elseif($property->status==1)
                            {{__('Preparing selling')}}
                            @elseif($property->status==2)
                            {{__('Selling')}}
                            @elseif($property->status==3)
                            {{__('sold')}}
                            @elseif($property->status==4)
                            {{__('Renting')}}
                            @elseif($property->status==5)
                            {{__('Rented')}}
                            @elseif($property->status==6)
                            {{__('Building')}}
                            @else
                            {{__('Unknown')}}
                            @endif
                    </span>
                </div>
                <div class="content">
                    <p class="location">
                    <i class="fs-16 icon icon-mapPin"></i>

                       {{$property->address->full_address}}
                    </p>
                    <div class="title">
                        <a href="{{ route('site.property.show', $property->slug) }}" title="{{ Str::limit($property->title, 20) }}">{{ Str::limit($property->title, 20) }}</a>
                    </div>
                    <div class="price"> {{$data_settings['currency']}} {{ $property->price->price }}</div>
                    <ul class="list-info">
                        <li>
                            <i class="icon icon-bed"></i>
                             {{ $property->more_info->bedrooms }}
                        </li>
                        <li>
                            <i class="icon icon-bathtub"></i>
                             {{ $property->more_info->bathrooms }}
                        </li>
                        <li>
                            <i class="icon icon-ruler"></i>
                             {{ $property->more_info->size }} m²
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    `;

        // Bind the popup to the marker with the custom HTML content
        marker.bindPopup(popupContent).openPopup();
    </script>


    <script>
        $(document).ready(function() {
            $('select').niceSelect(); // If you're using niceSelect for styling

            // Handle dropdown changes
            $('#per_page, #sort_by').on('change', function() {
                $('#filterForm').submit();
            });
        });



        document.addEventListener('DOMContentLoaded', function () {
            var tabs = document.querySelectorAll('.nav-link-item');
            var selectedTabInput = document.getElementById('selectedTab');

            // Set default value to 'rent' if selectedTab is not already set
            if (!selectedTabInput.value) {
                selectedTabInput.value = 'rent'; // or the actual value corresponding to your 'Rent' tab
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var selectedTab = this.getAttribute('href').substring(1);
                    selectedTabInput.value = selectedTab;
                });
            });
        });

        function myFunction() {
            var five = document.getElementById('1-star').checked;
            document.getElementById("one").innerHTML = five;

        }
    </script>
    <script>

        var data_url='{{ route('site.contact-seller',$property->user_id)}}'

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

                    // console.log(postData)
                    $('#add_form').html('');
                    $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{__('Sending')}}...</span>');
                    $.ajax({
                        url: data_url,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#add_form').empty();
                            $('#add_form').html('{{__('Send')}}');
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
                            $('.custom-error').remove();

                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#add_form').empty();
                            $('#add_form').html('{{__('Sending')}}');
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
    <script>

        var store_reviews_url='{{ route('user.store_reviews',$property->id)}}'


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

            $(document).on("click", "#store_reviews", function() {
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

                    // console.log(postData)
                    $('#store_reviews').html('');
                    $('#store_reviews').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{__('Sending')}}...</span>');
                    $.ajax({
                        url: store_reviews_url,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#store_reviews').empty();
                            $('#store_reviews').html('{{__('Send')}}');
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
                            $( ".wrap-form-comment" ).remove();
                            // document.getElementById("reviewForm").reset();
                            $('.custom-error').remove();

                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#store_reviews').empty();
                            $('#store_reviews').html('{{__('Sending')}}');
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
