@extends('site.layouts.app')
@section('style')
@endsection
@section('content')
        <!-- Slider -->
        <section class="flat-slider home-1">
            <div class="flat-slider home-1" style="background-image: url('{{ asset($sliders->where('key', 'slider_img')->first()->getTranslation('value', app()->getLocale())) }}'); background-attachment: fixed;">
                <div class="container relative">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="slider-content">
                                <div class="heading text-center">
                                    <h1 class="text-white animationtext slide">
                                        {{ __('Find Your') }}
                                        <span class="tf-text s1 cd-words-wrapper">
                                <span class="item-text is-visible">
                                    {{ $sliders->where('key', 'slider_text_1')->first()->getTranslation('value', app()->getLocale()) }}
                                </span>
                                <span class="item-text is-hidden">
                                    {{ $sliders->where('key', 'slider_text_2')->first()->getTranslation('value', app()->getLocale()) }}
                                </span>
                                <span class="item-text is-hidden">
                                    {{ $sliders->where('key', 'slider_text_3')->first()->getTranslation('value', app()->getLocale()) }}
                                </span>
                            </span>
                                    </h1>
                                    <p class="subtitle text-white body-1 wow fadeIn" data-wow-delay=".8s" data-wow-duration="2000ms">
                                        {{ $sliders->where('key', 'description')->first()->getTranslation('value', app()->getLocale()) }}
                                    </p>
                                </div>

                                @include('site.includes.filter_search_master')
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="overlay"></div>
        </section>
        <!-- End Slider -->
        @if(isset($data_settings['gallery_properties']) && $data_settings['gallery_properties'] == 1)
        <!-- Recommended -->
        <section class="flat-section flat-recommended">
            <div class="container">
                <div class="text-center wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="text-subtitle text-primary">{{__('Featured Properties')}}</div>
                    <h4 class="mt-4">{{__('Recommended For You')}}</h4>
                </div>
                <div class="flat-tab-recommended wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <ul class="nav-tab-recommended justify-content-center" role="tablist">
                        <li class="nav-tab-item" role="presentation">
                            <a href="#viewAll" class="nav-link-item"  data-bs-toggle="tab">{{__('View All')}}</a>
                        </li>
                        @foreach($categories as $category)
                             <li class="nav-tab-item" role="presentation">
                                <a href="#{{$category->slug}}" class="nav-link-item  {{$loop->first?'active':''}}" data-bs-toggle="tab">{{$category->name}}</a>
                            </li>
                        @endforeach

                    </ul>
                    <div class="tab-content">


                            <div class="tab-pane fade" id="viewAll" role="tabpanel">
                                <div class="row">
                                    @foreach($categories as $category)
                                    @foreach($category->properties as $property)
                                        <div class="col-xl-4 col-lg-6 col-md-6">
                                            <div class="homeya-box">
                                                <div class="archive-top">
                                                    @php
                                                        $imagePath = asset($property->images[0]->img);
                                                        $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                                    @endphp
                                                    <div   class="images-group">
                                                        <div class="images-style">
                                                            <img src="{{$correctedImagePath}}" alt="img">
                                                        </div>
                                                        <div class="top">
                                                            <ul class="d-flex gap-8">
                                                                @if($property->is_featured==1)
                                                                    <li class="flag-tag success">{{__('Featured')}}</li>
                                                                @endif
                                                                <li class="flag-tag style-1">{{__('For')}}
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
                                                                </li>
                                                            </ul>
                                                            <ul class="d-flex gap-4">
{{--                                                                <li class="box-icon w-32">--}}
{{--                                                                    <span class="icon icon-arrLeftRight"></span>--}}
{{--                                                                </li>--}}
                                                                <li class="box-icon w-32">
                                                                    <a href="javascript:"  data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $property->id }})">
                                                                        <span id="favorite-icon-{{ $property->id }}" class="icon {{ $property->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                                                    </a>
                                                                </li>
                                                                <li class="box-icon w-32">
                                                                    <a href="{{ route('site.property.show', $property->slug) }}"  data-toggle="tooltip" data-placement="top" title="Visit" onclick="toggleFavorite({{ $property->id }})">
                                                                        <span class="icon icon-eye"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="bottom">
                                                            <span class="flag-tag style-2">{{$category->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="content">
                                                        <div class="h7 text-capitalize fw-7">
                                                            <a href="{{ route('site.property.show', $property->slug) }}" class="link">
                                                                {{$property->title}}
                                                            </a>
                                                        </div>
                                                        <div class="desc"><i class="fs-16 icon icon-mapPin"></i>
                                                            <p>{{$property->address->full_address}}, {{$property->address->city->name}},
                                                                {{$property->address->state->name}},{{$property->address->country->name}}</p>
                                                        </div>
                                                        <ul class="meta-list">
                                                            <li class="item">
                                                                <i class="icon icon-bed"></i>
                                                                <span>{{$property->more_info->bedrooms}}</span>
                                                            </li>
                                                            <li class="item">
                                                                <i class="icon icon-bathtub"></i>
                                                                <span>{{$property->more_info->bathrooms}}</span>
                                                            </li>
                                                            <li class="item">
                                                                <i class="icon icon-ruler"></i>
                                                                <span>{{$property->more_info->size}} m²</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="archive-bottom d-flex justify-content-between align-items-center">
                                                    <div class="d-flex gap-8 align-items-center">
                                                        <div class="avatar avt-40 round">
                                                            @if($property->user_id!=null)
                                                                <img src="{{asset($property->user->photo)}}" alt="avt">

                                                            @else
                                                                <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                                            @endif

                                                        </div>
                                                        <span>
                                                            @if($property->user_id!=null)
                                                                {{$property->user->name}}
                                                            @else
                                                                {{config('app.name')}}

                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <h6>{{$property->price->price}}</h6>
                                                        <span class="text-variant-1">/m²</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach
                                    @endforeach

                                </div>
                                <div class="text-center">
                                    <a href="{{route('site.properties')}}" target="_blank" class="tf-btn primary size-1">{{__('View All Properties')}}</a>
                                </div>
                            </div>

                        @foreach($categories as $category)
                            <div class="tab-pane fade {{$loop->first?'active show':''}}" id="{{$category->slug}}" role="tabpanel">
                                <div class="row">
                                    @foreach($category->properties as $property)
                                        <div class="col-xl-4 col-lg-6 col-md-6">
                                            <div class="homeya-box">
                                                <div class="archive-top">
                                                    @php
                                                        $imagePath = asset($property->images[0]->img);
                                                        $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                                    @endphp
                                                    <div   class="images-group">
                                                        <div class="images-style">
                                                            <img src="{{$correctedImagePath}}" alt="img">
                                                        </div>
                                                        <div class="top">
                                                            <ul class="d-flex gap-8">
                                                                @if($property->is_featured==1)
                                                                    <li class="flag-tag success">{{__('Featured')}}</li>
                                                                @endif
                                                                <li class="flag-tag style-1">{{__('For')}}
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
                                                                </li>
                                                            </ul>
                                                            <ul class="d-flex gap-4">
{{--                                                                <li class="box-icon w-32">--}}
{{--                                                                    <span class="icon icon-arrLeftRight"></span>--}}
{{--                                                                </li>--}}
                                                                <li class="box-icon w-32">
                                                                    <a href="javascript:"  data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $property->id }})">
                                                                        <span id="favorite-icon-{{ $property->id }}" class="icon {{ $property->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                                                    </a>
                                                                </li>
                                                                <li class="box-icon w-32">
                                                                    <a href="{{ route('site.property.show', $property->slug) }}"  data-toggle="tooltip" data-placement="top" title="Visit" onclick="toggleFavorite({{ $property->id }})">
                                                                        <span class="icon icon-eye"></span>
                                                                    </a>
                                                                </li>
                                                            </ul>
                                                        </div>
                                                        <div class="bottom">
                                                            <span class="flag-tag style-2">{{$category->name}}</span>
                                                        </div>
                                                    </div>
                                                    <div class="content">
                                                        <div class="h7 text-capitalize fw-7">
                                                            <a href="{{ route('site.property.show', $property->slug) }}" class="link">
                                                                {{$property->title}}
                                                            </a>
                                                        </div>
                                                        <div class="desc"><i class="fs-16 icon icon-mapPin"></i>
                                                            <p>{{$property->address->full_address}}, {{$property->address->city->name}},
                                                                {{$property->address->state->name}},{{$property->address->country->name}}</p>
                                                        </div>
                                                        <ul class="meta-list">
                                                            <li class="item">
                                                                <i class="icon icon-bed"></i>
                                                                <span>{{$property->more_info->bedrooms}}</span>
                                                            </li>
                                                            <li class="item">
                                                                <i class="icon icon-bathtub"></i>
                                                                <span>{{$property->more_info->bathrooms}}</span>
                                                            </li>
                                                            <li class="item">
                                                                <i class="icon icon-ruler"></i>
                                                                <span>{{$property->more_info->size}} m²</span>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="archive-bottom d-flex justify-content-between align-items-center">
                                                    <div class="d-flex gap-8 align-items-center">
                                                        <div class="avatar avt-40 round">
                                                            @if($property->user_id!=null)
                                                                <img src="{{asset($property->user->photo)}}" alt="avt">

                                                            @else
                                                                <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                                            @endif

                                                        </div>
                                                        <span>
                                                            @if($property->user_id!=null)
                                                                {{$property->user->name}}
                                                            @else
                                                                {{config('app.name')}}

                                                            @endif
                                                        </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <h6>{{$property->price->price}}</h6>
                                                        <span class="text-variant-1">/m²</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    @endforeach


                                </div>
                                <div class="text-center">
                                    <a href="{{config('app.url')}}/property-category/{{$category->slug}}" target="_blank" class="tf-btn primary size-1">{{__('View All Properties')}}</a>
                                </div>
                            </div>
                        @endforeach


                    </div>
                </div>

            </div>

        </section>
        <!-- End Recommended -->
        @endif
        @if(isset($data_settings['cities']) && $data_settings['cities'] == 1)

            <!-- Location -->
        <section class="flat-section-v3 flat-location bg-surface">
            <div class="container-full">
                <div class="box-title text-center wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="text-subtitle text-primary">{{__('Explore Cities')}}</div>
                    <h4 class="mt-4">{{__('Our Location For You')}}</h4>
                </div>
                <div class="wow fadeInUpSmall" data-wow-delay=".4s" data-wow-duration="2000ms">
                    <div class="swiper tf-sw-location overlay" data-preview-lg="4.1" data-preview-md="3" data-preview-sm="2" data-space="30" data-centered="true" data-loop="true">
                        <div class="swiper-wrapper">
                            @foreach($locations as $location)
                                <div class="swiper-slide">
                                    <a href="{{route('site.properties.city',$location['slug'])}}" class="box-location">
                                        <div class="image">
                                            @php
                                                $imagePath = asset($location['photo']);
                                                $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                            @endphp
                                            <img src="{{$correctedImagePath}}" alt="image-location">
                                        </div>
                                        <div class="content">
{{--                                            <span class="sub-title">321 Property</span>--}}
                                            <h6 class="title">{{$location['name']}}</h6>
                                        </div>
                                    </a>
                                </div>
                            @endforeach


                        </div>
                        <div class="box-navigation">
                            <div class="navigation swiper-nav-next nav-next-location"><span class="icon icon-arr-l"></span></div>
                            <div class="navigation swiper-nav-prev nav-prev-location"><span class="icon icon-arr-r"></span></div>
                        </div>
                    </div>
                </div>

            </div>
        </section>
        <!-- End Location -->
        @endif
        @if(isset($data_settings['services']) && $data_settings['services'] == 1)

            <!-- Service & Counter  -->
        <section class="flat-section">
            <div class="container">
                <div class="box-title style-1 wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="box-left">
                        <div class="text-subtitle text-primary">{{__('Our Services')}}</div>
                        <h4 class="mt-4">{{__('What We Do?')}}</h4>
                    </div>
                    <a href="{{route('site.services')}}" class="btn-view"><span class="text">{{__('View All Services')}}</span> <span class="icon icon-arrow-right2"></span> </a>
                </div>
                <div class="flat-service wrap-service wow fadeInUpSmall" data-wow-delay=".4s" data-wow-duration="2000ms">
                    @foreach($services as $service)
                        <div class="box-service hover-btn-view">
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
                                <a href="#" class="btn-view style-1"><span class="text">{{__('Learn More')}}</span> <span class="icon icon-arrow-right2"></span> </a>
                            </div>
                        </div>
                    @endforeach


                </div>
                <div class="flat-counter tf-counter wrap-counter wow fadeInUpSmall" data-wow-delay=".4s" data-wow-duration="2000ms">
                    <div class="counter-box">
                        <div class="count-number">
                            <div class="number" data-speed="2000" data-to="85" data-inviewport="yes">85</div>
                        </div>
                        <div class="title-count">{{__('Satisfied Clients')}}</div>
                    </div>
                    <div class="counter-box">
                        <div class="count-number">
                            <div class="number" data-speed="2000" data-to="112" data-inviewport="yes">112</div>
                        </div>
                        <div class="title-count">{{__('Awards Received')}}</div>
                    </div>
                    <div class="counter-box">
                        <div class="count-number">
                            <div class="number" data-speed="2000" data-to="32" data-inviewport="yes">32</div>
                        </div>
                        <div class="title-count">{{__('Successful Transactions')}}</div>
                    </div>
                    <div class="counter-box">
                        <div class="count-number">
                            <div class="number" data-speed="2000" data-to="66" data-inviewport="yes">66</div>
                        </div>
                        <div class="title-count">{{__('Monthly Traffic')}}</div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Service & Counter -->
        @endif
        @if(isset($data_settings['benefits']) && $data_settings['benefits'] == 1)

            <!-- Benefit -->
        <section class="flat-section flat-benefit bg-surface">
            <div class="container">
                <div class="box-title text-center wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="text-subtitle text-primary">{{__('Our Benefits')}}</div>
                    <h4 class="mt-4">{{__('Why Choose')}} {{config('app.name')}}</h4>
                </div>
                <div class="wrap-benefit wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    @foreach($benefits as $benefit)
                        <div class="box-benefit">
                            <div class="icon-box">
                                @php
                                    $imagePath = asset($benefit->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <img class="icon" src="{{$correctedImagePath}}" alt="" style="height: 80px;width: 80px" >
                            </div>
                            <div class="content text-center">
                                <h6 class="title">{{$benefit->title}}</h6>
                                <p class="description">{{$benefit->description}}</p>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </section>
        <!-- End Benefit -->
        @endif


        @if(isset($data_settings['4_top']) && $data_settings['4_top'] == 1)
        <!-- Property  -->
        <section class="flat-section flat-property">
            <div class="container">
                <div class="box-title style-1 wow fadeInUpSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="box-left">
                        <div class="text-subtitle text-primary">{{__('Top Properties')}}</div>
                        <h4 class="mt-4">{{__('Best Property Value')}}</h4>
                    </div>
                    <a href="{{route('site.properties')}}" class="tf-btn primary size-1">{{__('View All')}}</a>
                </div>
                <div class="wrap-property">
                    <!-- resources/views/top-properties.blade.php -->
                    @foreach($top_properties as $property)
                        @if($loop->first)
                            <!-- Left Box -->
                            <div class="box-left wow fadeInLeftSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                                <div class="homeya-box lg">
                                    <div class="archive-top">
                                        @php
                                            $imagePath = asset($property->images[0]->img);
                                            $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                        @endphp
                                        <div  class="images-group">
                                            <div class="images-style">
                                                <img src="{{ $correctedImagePath}}" alt="img">
                                            </div>
                                            <div class="top">
                                                <ul class="d-flex gap-8">
                                                    @if($property->is_featured==1)
                                                    <li class="flag-tag success style-3">
                                                         {{__('Featured')}}
                                                    </li>
                                                    @endif
                                                    <li class="flag-tag style-1 style-3">For
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
                                                    </li>
                                                </ul>
                                                <ul class="d-flex gap-4">
                                                    <li class="box-icon w-40">
                                                        <a href="javascript:"  data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $property->id }})">
                                                            <span id="favorite-icon-{{ $property->id }}" class="icon {{ $property->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                                        </a>
                                                    </li>
                                                    <li class="box-icon w-40">
                                                        <a href="{{ route('site.property.show', $property->slug) }}"  data-toggle="tooltip" data-placement="top" title="Visit" onclick="toggleFavorite({{ $property->id }})">
                                                            <span class="icon icon-eye"></span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <div class="bottom">
                                                <span class="flag-tag style-2">{{ $property->category->name }}</span>
                                            </div>
                                        </div>
                                        <div class="content">
                                            <h5 class="text-capitalize">
                                                <a href="{{ route('site.property.show', $property->slug) }}" class="link">
                                                    {{ $property->title }}
                                                </a>
                                            </h5>
                                            <div class="desc">
                                                <i class="icon icon-mapPin"></i>
                                                <p>
                                                    {{$property->address->full_address}}, {{$property->address->city->name}},
                                                    {{$property->address->state->name}},{{$property->address->country->name}}
                                                </p>
                                            </div>
                                            <p class="note">{{ $property->more_info->description }}</p>
                                            <ul class="meta-list">
                                                <li class="item">
                                                    <i class="icon icon-bed"></i>
                                                    <span>{{ $property->more_info->bedrooms }}</span>
                                                </li>
                                                <li class="item">
                                                    <i class="icon icon-bathtub"></i>
                                                    <span>{{ $property->more_info->bathrooms }}</span>
                                                </li>
                                                <li class="item">
                                                    <i class="icon icon-ruler"></i>
                                                    <span>{{ $property->more_info->size }} m²</span>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="archive-bottom d-flex justify-content-between align-items-center">
                                        <div class="d-flex gap-8 align-items-center">
                                            <div class="avatar avt-40 round">

                                                @if($property->user_id!=null)
                                                    <img src="{{asset($property->user->photo)}}" alt="avt">

                                                @else
                                                    <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                                @endif

                                            </div>
                                            <span class="body-2">
                                            @if($property->user_id!=null)
                                                    {{$property->user->name}}
                                                @else
                                                    {{config('app.name')}}

                                                @endif
                                            </span>
                                        </div>
                                        <div class="d-flex align-items-center">
                                            <h6> {{$data_settings['currency']}} {{ $property->price->price }}</h6>
                                            <span class="text-variant-1">/{{__('month')}}</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endif

                    @endforeach
                    <div class="box-right wow fadeInRightSmall" data-wow-delay=".2s" data-wow-duration="2000ms">
                        @foreach($top_properties as $property)
                            @if($loop->first)

                            @endif
                            <!-- Right Boxes -->
                                <div class="homeya-box list-style-1">
                                    @php
                                        $imagePath = asset($property->images[0]->img);
                                        $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                    @endphp
                                    <a href="{{ route('site.property.show', $property->slug) }}" class="images-group images-group-sp">
                                        <div class="images-style">
                                            <img src="{{ $correctedImagePath }}" alt="img">
                                        </div>
                                        <div class="top">
                                            <ul class="d-flex gap-4 flex-wrap flex-column">
                                                @if($property->is_featured==1)
                                                    <li class="flag-tag success">

                                                        {{__('Featured')}}
                                                    </li>
                                                @endif
                                                <li class="flag-tag style-1">For
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
                                                </li>
                                            </ul>
                                            <ul class="d-flex gap-4">

                                                <li class="box-icon w-28">
                                                    <span class="icon icon-heart"></span>
                                                </li>
                                                <li class="box-icon w-28">
                                                    <span class="icon icon-eye"></span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="bottom">
                                            <span class="flag-tag style-2">{{ $property->category->name }}</span>
                                        </div>
                                    </a>
                                    <div class="content">
                                        <div class="archive-top">
                                            <div class="h7 text-capitalize fw-7">
                                                <a href="{{ route('site.property.show', $property->slug) }}" class="link">
                                                    {{ $property->title }}
                                                </a>
                                            </div>
                                            <div class="desc">
                                                <i class="icon icon-mapPin"></i>
                                                <p>
                                                    {{$property->address->full_address}}, {{$property->address->city->name}},
                                                    {{$property->address->state->name}},{{$property->address->country->name}}
                                                </p>
                                            </div>
                                            <ul class="meta-list">
                                                <li class="item">
                                                    <i class="icon icon-bed"></i>
                                                    <span>{{ $property->more_info->bedrooms }}</span>
                                                </li>
                                                <li class="item">
                                                    <i class="icon icon-bathtub"></i>
                                                    <span>{{ $property->more_info->bathrooms }}</span>
                                                </li>
                                                <li class="item">
                                                    <i class="icon icon-ruler"></i>
                                                    <span>{{ $property->more_info->size }} m²</span>
                                                </li>
                                            </ul>
                                        </div>
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div class="d-flex gap-8 align-items-center">
                                                <div class="avatar avt-40 round">
                                                    @if($property->user_id!=null)
                                                        <img src="{{asset($property->user->photo)}}" alt="avt">

                                                    @else
                                                        <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                                    @endif
                                                </div>
                                                <span>
                                                    @if($property->user_id!=null)
                                                        {{$property->user->name}}
                                                    @else
                                                        {{config('app.name')}}

                                                    @endif
                                                    </span>
                                            </div>
                                            <div class="d-flex align-items-center">
                                                <div class="h7 fw-7"> {{$data_settings['currency']}} {{ $property->price->price }}</div>
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
        <!-- End Property  -->
        @endif
        @if(isset($data_settings['people_says']) && $data_settings['people_says'] == 1)
        <!-- Testimonial -->
        <section class="flat-section-v3 bg-surface flat-testimonial">
            <div class="cus-layout-1">
                <div class="row align-items-center">
                    <div class="col-lg-3">
                        <div class="box-title">
                            <div class="text-subtitle text-primary">{{__('Top Properties')}}</div>
                            <h4 class="mt-4">{{__('Clients feedback')}}</h4>
                        </div>
                        <p class="text-variant-1 p-16">{{__('Our seasoned team excels in real estate with years of successful market navigation, offering informed decisions and optimal results.')}}</p>
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

                        </div>

                    </div>

                </div>
            </div>
        </section>
        <!-- End Testimonial -->
        @endif
        @if(isset($data_settings['agents']) && $data_settings['agents'] == 1)
        <!-- Agents -->
        <section class="flat-section flat-agents" id="agents">
            <div class="container">
                <div class="box-title text-center wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="text-subtitle text-primary">{{__('Our Teams')}}</div>
                    <h4 class="mt-4">{{__('Meet Our Agents')}}</h4>
                </div>
                <div class="row">
                    @foreach($agents as $agent)
                        <div class="box col-lg-3 col-sm-6">
                            <div class="box-agent hover-img wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
                                @php
                                    $imagePath = asset($agent->photo);
                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                @endphp
                                <div href="#" class="box-img img-style">
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
                                <div href="#" class="content">
                                    <div class="info">
                                        <h6 class="link">{{$agent->name}}</h6>
                                        <p class="mt-4 text-variant-1">{{$agent->position}}</p>
                                    </div>
                                    <a href="tel:{{$agent->phone}}" target="_blank"><span class="icon-phone"></span></a>
                                </div>
                            </div>
                        </div>
                    @endforeach


                </div>
            </div>
        </section>
        <!-- End Agents -->
        @endif
        @if(isset($data_settings['blogs']) && $data_settings['blogs'] == 1)
        <!-- Latest New -->
        <section class="flat-section-v3 flat-latest-new bg-surface">
            <div class="container">
                <div class="box-title text-center wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
                    <div class="text-subtitle text-primary">{{__('Latest New')}}</div>
                    <h4 class="mt-4">{{__('Helpful')}} {{config('app.name')}} {{__('Guides')}}</h4>
                </div>
                <div class="row">
                    @foreach($blogs as $blog)
                        <div class="box col-lg-4 col-md-6">
                            <a href="{{route('site.blog.show',$blog->slug)}}" class="flat-blog-item hover-img wow fadeIn" data-wow-delay=".2s" data-wow-duration="2000ms">
                                <div class="img-style">
                                    @php
                                        $imagePath = asset($blog->photo);
                                        $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                    @endphp
                                    <img src="{{$correctedImagePath}}" alt="img-blog">
                                    <span class="date-post">{{ \Carbon\Carbon::parse($blog->created_at)->format('F j, Y') }}</span>
                                </div>
                                <div class="content-box">
                                    <div class="post-author">
                                        <span class="fw-6">{{$blog->user->name}}</span>
                                        <span>{{$blog->category->name}}</span>
                                    </div>
                                    <h6 class="title">{{$blog->title_en}}</h6>
                                    <p class="description">{{ \Illuminate\Support\Str::limit($blog->short_description_en, 90, '...') }}</p>
                                </div>

                            </a>
                        </div>
                    @endforeach


                </div>
            </div>
        </section>
        <!-- End Latest New -->
        @endif
        @if(isset($data_settings['partners']) && $data_settings['partners'] == 1)
        <!-- partner -->
        <section class="flat-section-v4 flat-partner">
            <div class="container">
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
        <!-- End partner -->
        @endif

@endsection
@section('scripts')
    <script>
        $(document).ready(function() {
            $('select').niceSelect(); // If you're using niceSelect for styling

            // Handle dropdown changes
            $('#per_page, #sort_by').on('change', function() {
                $('#filterForm').submit();
            });
        });

        // $(function() {
        //     $("#slider-range").slider({
        //         range: true,
        //         min: 0,
        //         max: 1000000,
        //         values: [$("#slider-range-value1").text(), $("#slider-range-value2").text()],
        //         slide: function(event, ui) {
        //             $("#slider-range-value1").text(ui.values[0]);
        //             $("#slider-range-value2").text(ui.values[1]);
        //             $("input[name='min-value']").val(ui.values[0]);
        //             $("input[name='max-value']").val(ui.values[1]);
        //         }
        //     });
        //
        //     $("#slider-range2").slider({
        //         range: true,
        //         min: 0,
        //         max: 10000,
        //         values: [$("#slider-range-value01").text(), $("#slider-range-value02").text()],
        //         slide: function(event, ui) {
        //             $("#slider-range-value01").text(ui.values[0]);
        //             $("#slider-range-value02").text(ui.values[1]);
        //             $("input[name='min-value2']").val(ui.values[0]);
        //             $("input[name='max-value2']").val(ui.values[1]);
        //         }
        //     });
        // });

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

    </script>
@endsection
