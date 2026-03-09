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

        /* 3D Tour iframe responsive */
        .property-3d-tour-wrapper iframe {
            position: absolute !important;
            top: 0 !important;
            left: 0 !important;
            width: 100% !important;
            height: 100% !important;
            border: 0 !important;
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
                @if(!empty($property->more_info->video_url))
                <a href="{{$property->more_info->video_url}}" data-fancybox="gallery2" class="box-icon">
                    <span class="icon icon-play2"></span>
                </a>
                @endif
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
                                @php
                                    $currency = $data_settings['currency'] ?? $property->price->currency ?? 'JOD';
                                    $periodLabels = [0 => __('day'), 1 => __('week'), 2 => __('month'), 3 => __('year')];
                                    $periodLabel = ($property->type == 0 && isset($periodLabels[$property->price->period ?? 2])) ? $periodLabels[$property->price->period ?? 2] : ($property->type == 1 ? __('Total') : __('month'));
                                @endphp
                                <h4>{{ $currency }} {{ number_format($property->price->price) }}</h4>
                                <span class="body-1 text-variant-1">/{{ $periodLabel }}</span>
                            </div>
                        </div>
                        <div class="content-bottom">
                            @php $info = $property->more_info ?? null; @endphp
                            @if($info && ($info->bedrooms || $info->bathrooms || $info->size))
                            <div class="info-box">
                                <div class="label">{{__('FEATUREs')}}:</div>
                                <ul class="meta">
                                    @if($info->bedrooms !== null && $info->bedrooms !== '')<li class="meta-item"><span class="icon icon-bed"></span> {{ $info->bedrooms }} {{ __('Bedrooms') }}</li>@endif
                                    @if($info->bathrooms !== null && $info->bathrooms !== '')<li class="meta-item"><span class="icon icon-bathtub"></span> {{ $info->bathrooms }} {{ __('Bathrooms') }}</li>@endif
                                    @if($info->size)<li class="meta-item"><span class="icon icon-ruler"></span> {{ $info->size }} m²</li>@endif
                                </ul>
                            </div>
                            @endif

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
                        @php $info = $property->more_info ?? null; @endphp
                        <ul class="info-box">
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-house-line"></i></a>
                                <div class="content">
                                    <span class="label">{{__('ID')}}:</span>
                                    <span>{{ $property->id + 1000 }}</span>
                                </div>
                            </li>
                            @if($property->category)
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-arrLeftRight"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Type')}}:</span>
                                    <span>{{ $property->category->name }}</span>
                                </div>
                            </li>
                            @endif
                            @if($info && ($info->bedrooms !== null && $info->bedrooms !== ''))
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-bed"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Bedrooms')}}:</span>
                                    <span>{{ $info->bedrooms }}</span>
                                </div>
                            </li>
                            @endif
                            @if($info && ($info->bathrooms !== null && $info->bathrooms !== ''))
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-bathtub"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Bathrooms')}}:</span>
                                    <span>{{ $info->bathrooms }}</span>
                                </div>
                            </li>
                            @endif
                            @if($info && ($info->size || $info->size_max))
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-ruler"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Size')}}:</span>
                                    <span>{{ $info->size ?? 0 }}{{ ($info->size_max && $info->size_max != $info->size) ? ' - ' . $info->size_max : '' }} m²</span>
                                </div>
                            </li>
                            @endif
                            @if($info && ($info->land_area || $info->land_area_min || $info->land_area_max))
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-crop"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Land Size')}}:</span>
                                    <span>{{ $info->land_area ?? $info->land_area_min ?? 0 }}{{ (($info->land_area_max ?? 0) && ($info->land_area_max != ($info->land_area_min ?? $info->land_area ?? 0))) ? ' - ' . $info->land_area_max : '' }} m²</span>
                                </div>
                            </li>
                            @endif
                            @if($info && !empty($info->year_built))
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-hammer"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Year Built')}}:</span>
                                    <span>{{ $info->year_built }}</span>
                                </div>
                            </li>
                            @endif
                            @if($info && !empty($info->building_age))
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-house-line"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Building Age')}}:</span>
                                    <span>{{ $info->building_age == 'new' ? __('New') : $info->building_age }}</span>
                                </div>
                            </li>
                            @endif
                            @if($info && isset($info->furnished) && $info->furnished !== null && $info->furnished !== '')
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-arrLeftRight"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Furnished')}}:</span>
                                    <span>{{ $info->furnished ? __('Furnished') : __('Unfurnished') }}</span>
                                </div>
                            </li>
                            @endif
                            @if($info && !empty($info->floor))
                            @php
                                $floorLabelsOv = ['mezzanine_2'=>'Mezzanine 2','mezzanine_1'=>'Mezzanine 1','ground'=>'Ground','first'=>'First','second'=>'Second','third'=>'Third','fourth'=>'Fourth','roof'=>'Roof'];
                            @endphp
                            <li class="item">
                                <a href="#" class="box-icon w-52"><i class="icon icon-ruler"></i></a>
                                <div class="content">
                                    <span class="label">{{__('Floor')}}:</span>
                                    <span>{{ isset($floorLabelsOv[$info->floor]) ? __($floorLabelsOv[$info->floor]) : $info->floor }}</span>
                                </div>
                            </li>
                            @endif
                        </ul>
                    </div>
                    @if(!empty($property->more_info->video_url))
                    <div class="single-property-element single-property-video">
                        <div class="h7 title fw-7">{{__('Video')}}</div>
                        <div class="img-video">
                            <img src="{{asset('site/images/banner/img-video.jpg')}}" alt="img-video">
                            <a href="{{$property->more_info->video_url}}" data-fancybox="gallery2" class="btn-video"> <span class="icon icon-play2"></span></a>
                        </div>
                    </div>
                    @endif
                    @if(!empty($property->featured_3d_tour_iframe) && $property->is_3d_tour_featured && $property->featured_3d_tour_until && \Carbon\Carbon::parse($property->featured_3d_tour_until)->isFuture())
                    <div class="single-property-element single-property-3dtour">
                        <div class="h7 title fw-7">{{ __('3D Tour') }}</div>
                        <div class="property-3d-tour-wrapper" style="position: relative; width: 100%; padding-bottom: 56.25%; height: 0; overflow: hidden; border-radius: 8px; background: #f5f5f5;">
                            {!! $property->featured_3d_tour_iframe !!}
                        </div>
                    </div>
                    @endif
                    <div class="single-property-element single-property-info">
                        <div class="h7 title fw-7">{{__('Property Details')}}</div>
                        @php
                            $info = $property->more_info ?? null;
                            $currency = $data_settings['currency'] ?? $property->price->currency ?? 'JOD';
                            $periodLabels = [0 => __('day'), 1 => __('week'), 2 => __('month'), 3 => __('year')];
                            $pricePeriod = ($property->type == 0 && isset($periodLabels[$property->price->period ?? 2])) ? '/' . $periodLabels[$property->price->period ?? 2] : ($property->type == 1 ? ' (' . __('Total') . ')' : '/month');
                        @endphp
                        <div class="row">
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property ID')}}:</span>
                                    <div class="content fw-7">{{ $property->id + 1000 }}</div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Price')}}:</span>
                                    <div class="content fw-7">{{ $currency }} {{ number_format($property->price->price) }}<span class="caption-1 fw-4 text-variant-1">{{ $pricePeriod }}</span></div>
                                </div>
                            </div>
                            @if($property->category)
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property Type')}}:</span>
                                    <div class="content fw-7">{{ $property->category->name }}</div>
                                </div>
                            </div>
                            @endif
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property Status')}}:</span>
                                    <div class="content fw-7">
                                        @if($property->status==0){{__('Not available')}}
                                        @elseif($property->status==1){{__('Preparing selling')}}
                                        @elseif($property->status==2){{__('Selling')}}
                                        @elseif($property->status==3){{__('sold')}}
                                        @elseif($property->status==4){{__('Renting')}}
                                        @elseif($property->status==5){{__('Rented')}}
                                        @elseif($property->status==6){{__('Building')}}
                                        @else{{__('Unknown')}}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @if($info && ($info->bedrooms !== null && $info->bedrooms !== ''))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Bedrooms')}}:</span>
                                    <div class="content fw-7">{{ $info->bedrooms }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && ($info->bathrooms !== null && $info->bathrooms !== ''))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Bathrooms')}}:</span>
                                    <div class="content fw-7">{{ $info->bathrooms }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && ($info->size || $info->size_max))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Property Size')}}:</span>
                                    <div class="content fw-7">{{ $info->size ?? 0 }}{{ ($info->size_max && $info->size_max != ($info->size ?? 0)) ? ' - ' . $info->size_max : '' }} m²</div>
                                </div>
                            </div>
                            @endif
                            @if($info && !empty($info->year_built))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Year built')}}:</span>
                                    <div class="content fw-7">{{ $info->year_built }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && ($info->land_area || $info->land_area_min || $info->land_area_max))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Land Size')}}:</span>
                                    <div class="content fw-7">{{ $info->land_area ?? $info->land_area_min ?? 0 }}{{ (($info->land_area_max ?? 0) && ($info->land_area_max != ($info->land_area_min ?? $info->land_area ?? 0))) ? ' - ' . $info->land_area_max : '' }} m²</div>
                                </div>
                            </div>
                            @endif
                            @if($info && !empty($info->building_age))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Building Age')}}:</span>
                                    <div class="content fw-7">{{ $info->building_age == 'new' ? __('New') : $info->building_age }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && isset($info->furnished) && $info->furnished !== null && $info->furnished !== '')
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Furnished')}}:</span>
                                    <div class="content fw-7">{{ $info->furnished ? __('Furnished') : __('Unfurnished') }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && !empty($info->floor))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Floor')}}:</span>
                                    <div class="content fw-7">
                                        @php
                                            $floorLabels = ['mezzanine_2' => 'Mezzanine 2', 'mezzanine_1' => 'Mezzanine 1', 'ground' => 'Ground', 'first' => 'First', 'second' => 'Second', 'third' => 'Third', 'fourth' => 'Fourth', 'roof' => 'Roof'];
                                            echo isset($floorLabels[$info->floor]) ? __($floorLabels[$info->floor]) : $info->floor;
                                        @endphp
                                    </div>
                                </div>
                            </div>
                            @endif
                            @if($info && ($info->rooms !== null && $info->rooms !== ''))
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Number of Rooms')}}:</span>
                                    <div class="content fw-7">{{ $info->rooms }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && !empty($info->zoning))
                            @php
                                $zoningLabels = ['residential_a'=>'Residential A','residential_b'=>'Residential B','residential_c'=>'Residential C','residential_d'=>'Residential D','offices'=>'Offices','commercial'=>'Commercial','light_industry'=>'Light Industry','industrial'=>'Industrial','agricultural'=>'Agricultural','outside_planning'=>'Outside Planning','tourism'=>'Tourism','rural'=>'Rural','private_residential'=>'Private Residential'];
                            @endphp
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Zoning')}}:</span>
                                    <div class="content fw-7">{{ __($zoningLabels[$info->zoning] ?? ucfirst(str_replace('_', ' ', $info->zoning))) }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && !empty($info->land_type))
                            @php
                                $landTypeLabels = ['rocky'=>'Rocky','red_soil'=>'Red Soil','sloping'=>'Sloping','flat'=>'Flat','mountainous'=>'Mountainous'];
                            @endphp
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Land Type')}}:</span>
                                    <div class="content fw-7">{{ __($landTypeLabels[$info->land_type] ?? ucfirst($info->land_type)) }}</div>
                                </div>
                            </div>
                            @endif
                            @if($info && !empty($info->services))
                            @php
                                $servicesLabels = ['all_connected'=>'All Services Connected','near_services'=>'Near Services'];
                            @endphp
                            <div class="col-md-6">
                                <div class="inner-box">
                                    <span class="label">{{__('Services')}}:</span>
                                    <div class="content fw-7">{{ __($servicesLabels[$info->services] ?? ucfirst(str_replace('_', ' ', $info->services))) }}</div>
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                    @php
                        $extraFeaturesLabels = ['pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well', 'balcony' => 'Balcony', 'maid_room' => 'Maid Room', 'storage_room' => 'Storage Room', 'laundry_room' => 'Laundry Room', 'central_ac' => 'Central AC', 'car_parking' => 'Car Parking'];
                        $extraFeat = $property->more_info->extra_features ?? [];
                        $hasExtraFeatures = is_array($extraFeat) && count($extraFeat) > 0;
                    @endphp
                    @if(($categoriesWithFeatures && count($categoriesWithFeatures) > 0) || $hasExtraFeatures)
                    <div class="single-property-element single-property-feature">
                        <div class="h7 title fw-7">{{__('Amenities and features')}}</div>
                        <div class="wrap-feature">
                            @if($categoriesWithFeatures && count($categoriesWithFeatures) > 0)
                            @foreach($categoriesWithFeatures as $categoryName => $features)
                                <div class="box-feature">
                                    <div class="fw-7">{{ $categoryName }}:</div>
                                    <ul>
                                        @foreach($features as $feature)
                                            <li class="feature-item">
                                                <span class="fa-solid {{ $feature['icon'] ?? '' }}"></span>
                                                {{ $feature['name'] ?? '' }}
                                            </li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endforeach
                            @endif
                            @if($hasExtraFeatures)
                            <div class="box-feature">
                                <div class="fw-7">{{__('Extra Features')}}:</div>
                                <ul>
                                    @foreach($extraFeat as $key)
                                        @if(isset($extraFeaturesLabels[$key]))
                                        <li class="feature-item"><span class="fa-solid fa-check"></span> {{ __($extraFeaturesLabels[$key]) }}</li>
                                        @endif
                                    @endforeach
                                </ul>
                            </div>
                            @endif
                        </div>
                    </div>
                    @endif
                    <div class="single-property-element single-property-map">
                        <div id="map"></div>
                        <ul class="info-map">
                            <li>
                                <div class="fw-7">{{__('Address')}}</div>
                                <span class="mt-4 text-variant-1">
                                    {{ $property->address?->display_address ?? '-' }}
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

                    @if($property->facilities->isNotEmpty())
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
                                    <span class="fw-7">
                                        @php
                                            $d = $facility->distance ?? '';
                                            $num = preg_replace('/[^\d.]/', '', $d) ?: '-';
                                            $unit = (stripos($d, 'km') !== false || preg_match('/\d+\s*k\b/i', $d)) ? __('distance_km') : __('distance_m');
                                        @endphp
                                        {{ $num }} {{ $unit }}
                                    </span>
                                </li>
                                @endforeach

                            </ul>
                            <ul class="box-right">
                                @foreach($facilitiesRight as $facility)
                                    <li class="item-nearby">
                                        <span class="label">{{$facility->facility->name}}:</span>
                                        <span class="fw-7">
                                            @php
                                                $d = $facility->distance ?? '';
                                                $num = preg_replace('/[^\d.]/', '', $d) ?: '-';
                                                $unit = (stripos($d, 'km') !== false || preg_match('/\d+\s*k\b/i', $d)) ? __('distance_km') : __('distance_m');
                                            @endphp
                                            {{ $num }} {{ $unit }}
                                        </span>
                                    </li>
                                @endforeach

                            </ul>
                        </div>
                    </div>
                    @endif
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
                                                    <a href="{{ route('site.property.show', $item->slug) }}" data-toggle="tooltip" data-placement="top" title="{{ __('Visit') }}">
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
                                                {{ $item->address?->display_address ?? '-' }}
                                            </p></div>
                                        <ul class="meta-list">
                                            @if($item->more_info && ($item->more_info->bedrooms !== null && $item->more_info->bedrooms !== ''))<li class="item"><i class="icon icon-bed"></i><span>{{ $item->more_info->bedrooms }}</span></li>@endif
                                            @if($item->more_info && ($item->more_info->bathrooms !== null && $item->more_info->bathrooms !== ''))<li class="item"><i class="icon icon-bathtub"></i><span>{{ $item->more_info->bathrooms }}</span></li>@endif
                                            @if($item->more_info && $item->more_info->size)<li class="item"><i class="icon icon-ruler"></i><span>{{ $item->more_info->size }} m²</span></li>@endif
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
                                        @php
                                            $itemCurrency = $data_settings['currency'] ?? $item->price->currency ?? 'JOD';
                                            $itemPeriodLabels = [0 => __('day'), 1 => __('week'), 2 => __('month'), 3 => __('year')];
                                            $itemPeriod = ($item->type == 0 && isset($itemPeriodLabels[$item->price->period ?? 2])) ? '/' . $itemPeriodLabels[$item->price->period ?? 2] : ($item->type == 1 ? '' : '/month');
                                        @endphp
                                        <h6>{{ $itemCurrency }} {{ number_format($item->price->price) }}</h6>
                                        <span class="text-variant-1">{{ $itemPeriod }}</span>
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
    </script>
    <!-- Make sure you put this AFTER Leaflet's CSS -->
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
            integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo="
            crossorigin=""></script>
    <!-- Toastr JS -->

    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        @php
            $addr = $property->address ?? null;
            $lat = $addr && is_numeric($addr->latitude) ? $addr->latitude : 31.9454;
            $lng = $addr && is_numeric($addr->longitude) ? $addr->longitude : 35.9284;
        @endphp
        var map = L.map('map').setView([{{ $lat }}, {{ $lng }}], 14);
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="{{config('app.url')}}">{{config('app.name')}}</a> contributors'
        }).addTo(map);
        var marker = L.marker([{{ $lat }}, {{ $lng }}]).addTo(map);

        // Define the HTML content for the popup
        var popupContent = `
        <div class="map-listing-item">
            <div class="inner-box">
                <div class="image-box">
                    <a href="{{ route('site.property.show', $property->slug) }}">
                        <img data-bb-lazy="true" loading="lazy" src="{{ $property->images->first() ? asset($property->images->first()->img) : '' }}" alt="{{ Str::limit($property->title, 20) }}" class="entered loaded">
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

                       {{ $property->address?->display_address ?? '-' }}
                    </p>
                    <div class="title">
                        <a href="{{ route('site.property.show', $property->slug) }}" title="{{ Str::limit($property->title, 20) }}">{{ Str::limit($property->title, 20) }}</a>
                    </div>
                    <div class="price"> {{ $data_settings['currency'] ?? 'JOD' }} {{ number_format($property->price->price) }}</div>
                    <ul class="list-info">
                        @if($property->more_info && ($property->more_info->bedrooms !== null && $property->more_info->bedrooms !== ''))<li><i class="icon icon-bed"></i> {{ $property->more_info->bedrooms }}</li>@endif
                        @if($property->more_info && ($property->more_info->bathrooms !== null && $property->more_info->bathrooms !== ''))<li><i class="icon icon-bathtub"></i> {{ $property->more_info->bathrooms }}</li>@endif
                        @if($property->more_info && $property->more_info->size)<li><i class="icon icon-ruler"></i> {{ $property->more_info->size }} m²</li>@endif
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
            $('select').niceSelect();
            $('#per_page, #sort_by').on('change', function() {
                $('#filterForm').submit();
            });
            // Jordan location cascading (filter)
            $('#filter_governorate').on('change', function() {
                var id = $(this).val();
                $('#filter_department, #filter_village').empty().append('<option value="">{{__('Select')}}</option>');
                if (id) {
                    $.get('{{ url('/jordan/departments') }}/' + id, function(data) {
                        $('#filter_department').empty().append('<option value="">{{__('Select Department')}}</option>');
                        $.each(data, function(i, item) {
                            $('#filter_department').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        if (typeof $('select').niceSelect === 'function') $('select').niceSelect('update');
                    });
                }
            });
            $('#filter_department').on('change', function() {
                var id = $(this).val();
                $('#filter_village').empty().append('<option value="">{{__('Select')}}</option>');
                if (id) {
                    $.get('{{ url('/jordan/villages') }}/' + id, function(data) {
                        $('#filter_village').empty().append('<option value="">{{__('Select Village')}}</option>');
                        $.each(data, function(i, item) {
                            $('#filter_village').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        if (typeof $('select').niceSelect === 'function') $('select').niceSelect('update');
                    });
                }
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
