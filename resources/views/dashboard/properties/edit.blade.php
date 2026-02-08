
@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
    {{--<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />--}}

    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
    <link rel="stylesheet" href="https://unpkg.com/dropzone@5/dist/min/dropzone.min.css" type="text/css" />

    <style>
        .loading {
            background: url('https://i.gifer.com/ZZ5H.gif') no-repeat right center;
            background-size: 20px 20px;
        }
        .is-invalid {
            border-color: red;
        }
    </style>
    <style>
        .hint-message {
            font-style: italic;
            color: gray;
        }
        #user-list {
            cursor: pointer;
            padding: 5px;
            border: 1px solid #ccc;
            margin: 2px 0;
        }
        #user-list div:hover {
            background-color: #f0f0f0;
        }
        /* Add this to your CSS file */
        /* CSS for making the Select2 dropdown look disabled */
        /* CSS to show that select is disabled */
        .select2-container--default .select2-selection--single.disabled {
            background-color: #e9ecef; /* Light grey background */
            cursor: not-allowed;
            pointer-events: none; /* Disable clicks */
        }

        .loading-indicator {
            font-size: 14px;
            color: #245da0;
            margin-top: 5px;
        }
        .dropzone .dz-preview .dz-image img {
            display: block;
            width: 120px;
            height: 120px;
        }
    </style>
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
                    <a href="{{route('admin.properties.index',1)}}">{{__('Properties')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Edit Property')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="row">
            <div class="col-xl">
                <div class="card mb-4">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">{{__('Edit Property')}}</h5>
                    </div>
                    <div class="card-body">
                        <form id="mainAdd" method="post" action="javascript:void(0)" >
                            @csrf

                            <div class="row">
                                <div class="col-md-8">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">

                                                <div class="card-body">
                                                    <div class="row">

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
                                                                                    <input type="text" class="form-control" name="name[{{ $locale }}]" id="name_{{ $locale }}" value="{{ $property->getTranslation('title', $locale) }}" placeholder="{{ __('Name in ') . strtoupper($locale) }}" required>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="description_{{ $locale }}">{{ __('Description') }} ({{ strtoupper($locale) }})</label>
                                                                                    <textarea class="form-control" name="description[{{ $locale }}]" id="description_{{ $locale }}" rows="5" required>{{ $property->getTranslation('description', $locale) }}</textarea>
                                                                                </div>

                                                                                <div class="form-group">
                                                                                    <label class="form-label" for="content_{{ $locale }}">{{ __('Content') }} ({{ strtoupper($locale) }})</label>
                                                                                    <div id="editor-container-{{ $locale }}" class="editor-container">
                                                                                        <!-- Quill editor will be initialized here -->
                                                                                    </div>
                                                                                    <textarea name="content[{{ $locale }}]" id="content_{{ $locale }}" class="d-none">{{ $property->more_info->getTranslation('content', $locale) }}</textarea>
                                                                                </div>
                                                                                <div class="col-md-12">
                                                                                    <label class="form-label" for="slug_{{ $locale }}">{{ __('Permalink') }} ({{ strtoupper($locale) }})</label>
                                                                                    <div class="input-group input-group-merge">
                                                                                        <span class="input-group-text" id="slug_{{ $locale }}">{{ config('app.url') }}/property/</span>
                                                                                        <input type="text" id="slug_{{ $locale }}" name="slug[{{ $locale }}]" value="{{$property->getTranslation('slug', $locale)}}" class="form-control" aria-describedby="slug" readonly>
                                                                                        <div id="slug-feedback">
                                                                                            <i class="fa fa-check text-success d-none"></i>
                                                                                            <i class="fa fa-times text-danger d-none"></i>
                                                                                        </div>
                                                                                        <!-- Loading Spinner -->
                                                                                        <div id="loading-spinner" class="d-none">
                                                                                            <i class="fa fa-spinner fa-spin"></i>
                                                                                        </div>
                                                                                    </div>
                                                                                </div>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                @endforeach
                                                            </div>

                                                            <!-- Rest of your form here -->
                                                        </div>




                                                        <div class="col-12  mt-2">
                                                            <div class="form-group">
                                                                <label class="form-label" for="type">{{__('Property Type')}}</label>

                                                                <select name="type" id="type" class="form-select">
                                                                    <option value="0" {{ (old('type', $property->type ?? 0) == 0) ? 'selected' : '' }}>{{__('Rent')}}</option>
                                                                    <option value="1" {{ (old('type', $property->type ?? 0) == 1) ? 'selected' : '' }}>{{__('Sold')}}</option>
                                                                </select>
                                                            </div>
                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>




                                    </div>



                                    <div class="row mt-2">
                                        <!-- Images -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{__('Images')}}</h5>
                                                <div class="card-body">
                                                    <div class="form-group row">
                                                        <div class="col-lg-12 col-sm-12">




                                                            <div id="dpz-multiple-files" class="dropzone dropzone-area">
                                                                <div class="dz-message">{{__('You can upload more than one photo here.')}}</div>
                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Images -->
                                    </div>
                                    <div class="row mt-2">
                                        <!-- address -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{ __('Address') }}</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="country">{{ __('Country') }}:</label>
                                                                <select id="country" required name="country_id" class="form-control select2">
                                                                    <option value="">{{ __('Select Country') }}</option>
                                                                    @foreach($countries as $country)
                                                                        <option value="{{ $country->id }}" {{ (old('country_id', $property->address->country_id ?? 0) == $country->id ) ? 'selected' : '' }}>{{ $country->name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="state">{{ __('State') }}:</label>
                                                                <select id="state" required name="state_id" class="form-control select2">
                                                                    <option value="">{{ __('Select State') }}</option>
                                                                    <option value="{{ $property->address->state_id}}" selected>{{ $property->address->state->name }}</option>

                                                                </select>
                                                                <div id="state-loading" class="loading-indicator" style="display: none;">Loading states...</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="city">{{ __('City') }}:</label>
                                                                <select id="city" required name="city_id" class="form-control select2">
                                                                    <option value="">{{ __('Select City') }}</option>
                                                                    <option value="{{ $property->address->city_id}}" selected>{{ $property->address->city->name }}</option>
                                                                </select>
                                                                <div id="city-loading" class="loading-indicator" style="display: none;">Loading cities...</div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="full_address">{{ __('Full Address') }}</label>
                                                                <input type="text" required class="form-control" name="full_address" id="full_address" value="{{ old('full_address', $property->address->full_address ?? '') }}" placeholder="{{ __('Full Address') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="latitude">{{ __('Latitude') }}</label>
                                                                <input type="text" class="form-control" name="latitude" id="latitude" placeholder="{{ __('Ex: 1.462260') }}">
                                                                <a class="form-hint" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">{{ __('Go here to get Latitude from address.') }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="longitude">{{ __('Longitude') }}</label>
                                                                <input type="text" class="form-control" name="longitude" id="longitude" placeholder="{{ __('Ex: 1.462260') }}">
                                                                <a class="form-hint" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">{{ __('Go here to get Longitude from address.') }}</a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- /address -->
                                    </div>
                                    <div class="row mt-2">
                                        <!-- Price -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{__('Price')}}</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="price">{{__('Price')}}</label>
                                                                <input type="text" required value="{{ old('price', $property->price->price ?? '') }}" class="form-control" name="price" id="price" placeholder="" >

                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="currency">{{__('Currency')}}:</label>
                                                                <select id="currency" required name="currency" class="select2 form-select">
                                                                    @foreach($uniqueCurrencies as $currency)
                                                                        <option value="{{ $currency }}" {{ $currency == $property->price->currency ? 'selected' : '' }}>
                                                                            {{ $currency }}
                                                                        </option>
                                                                    @endforeach
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="period">{{__('Period')}}:</label>
                                                                <select id="period" required name="period" class="form-control form-select">
                                                                    <option value="0" {{ 0 == $property->price->period ? 'selected' : '' }}>{{__('Daily') }}</option>
                                                                    <option value="1" {{ 1 == $property->price->period ? 'selected' : '' }}>{{__('Weekly') }}</option>
                                                                    <option value="2" {{ 2 == $property->price->period ? 'selected' : '' }}>{{__('Monthly') }}</option>
                                                                    <option value="3" {{ 3 == $property->price->period ? 'selected' : '' }}>{{__('Yearly') }}</option>
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-md-6 p-6">
                                                            <label class="switch">
                                                                <input type="checkbox"   name="never_expired" id="never_expired" class="switch-input" {{ 1 == $property->price->never_expired ? 'checked' : '' }} />
                                                                <span class="switch-toggle-slider">
                                                              <span class="switch-on"></span>
                                                              <span class="switch-off"></span>
                                                            </span>
                                                                <span class="switch-label">{{__('Never expired?')}}</span>
                                                            </label>
                                                        </div>

                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-12">
                                                            <div class="form-group">
                                                                <label for="private_notes">{{__('Private notes')}}:</label>
                                                                <textarea name="private_notes" id="private_notes" class="form-control" rows="3">{{ old('private_notes', $property->price->private_notes ?? '') }}</textarea>
                                                                <small class="form-hint">{{__("Private notes are only visible to owner. It won't be shown on the frontend")}}.</small>
                                                            </div>
                                                            <div class="form-group">
                                                                <label class="switch">
                                                                    <input type="checkbox" name="auto_renew" id="auto_renew" class="switch-input"{{ 1 == $property->price->auto_renew ? 'checked' : '' }} />
                                                                    <span class="switch-toggle-slider">
                    <span class="switch-on"></span>
                    <span class="switch-off"></span>
                </span>
                                                                    <span class="switch-label">{{__('Renew automatically (you will be charged again in 45 days)?')}}</span>
                                                                </label>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Price -->
                                    </div>
                                    <div class="row mt-2">
                                        <!-- Additional Information -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{__('Additional Information')}}</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @foreach([
                                                            'size' => __('Size (m²)'),
                                                            'land_area' => __('Land Area (m²)'),
                                                            'rooms' => __('Rooms'),
                                                            'bedrooms' => __('Bedrooms'),
                                                            'bathrooms' => __('Bathrooms'),
                                                            'garages' => __('Garages'),
                                                            'garages_size' => __('Garages Size (m²)'),
                                                            'floors' => __('Floors'),
                                                            'year_built' => __('Year Built')
                                                        ] as $field => $label)
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label" for="{{ $field }}">{{ $label }}</label>
                                                                    <input type="number" class="form-control" name="{{ $field }}" id="{{ $field }}"
                                                                           placeholder="" value="{{ old($field, $property->more_info->$field ?? '') }}" required>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Additional Information -->
                                    </div>
                                    <div class="row mt-2">
                                        <!-- Amenities -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{ __('Amenities & Features') }}</h5>
                                                <div class="card-body">
                                                    <div class="row">
                                                        @php
                                                            // Collect feature IDs from property
                                                            $selectedFeatureIds = $property->features->pluck('feature_id')->toArray();
                                                        @endphp

                                                        @foreach($feature_categories as $f_category)
                                                            @if($f_category->features->isNotEmpty())
                                                                <div class="col-md-4 p-6">
                                                                    <small class="text-black fw-bold">{{ $f_category->name }}</small>
                                                                    @foreach($f_category->features as $feature)
                                                                        <div class="form-check">
                                                                            <input class="form-check-input" type="checkbox"
                                                                                   value="{{ $feature->id }}" id="defaultCheck{{ $feature->id }}"
                                                                                   name="property_features[]"
                                                                                {{ in_array($feature->id, $selectedFeatureIds) ? 'checked' : '' }}>
                                                                            <label class="form-check-label" for="defaultCheck{{ $feature->id }}">
                                                                                {{ $feature->name }}
                                                                            </label>
                                                                        </div>
                                                                    @endforeach
                                                                </div>
                                                            @endif
                                                        @endforeach

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Amenities -->
                                    </div>

                                    <div class="row mt-2">
                                        <!-- Distance key between facilities -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{__('Distance key between facilities')}}</h5>
                                                <div class="card-body">
                                                    <div class="row">


                                                        <div class="col-md-12">
                                                            <div class="row">
                                                                <div class="col-md-12">
                                                                    <div class='repeater'>
                                                                        <div data-repeater-list="facilities" id="facilities_div">
                                                                            @foreach($property->facilities as $item)
                                                                                <div data-repeater-item class="row mb-3  ">

                                                                                    <div class="col-md-4 ">
                                                                                        <div class="form-group ">
                                                                                            <select name="facilities[{{$loop->index}}][facility_id]" required id="" class="form-select">
                                                                                                <option value="">{{__('Select Facilities')}}</option>
                                                                                                @foreach($facilities as $facility)
                                                                                                    <option value="{{$facility->id}}" {{$item->facility_id==$facility->id?'selected':''}}>{{$facility->name}}</option>

                                                                                                @endforeach
                                                                                            </select>


                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="col-md-6 ">
                                                                                        <div class="form-group">

                                                                                            <input type="text"  required id="distance" value="{{$item->distance}}"  name="facilities[{{$loop->index}}][distance]" class="form-control"
                                                                                                   placeholder="{{__('Distance (E.g: 200m, 1km...)')}}"  >




                                                                                        </div>
                                                                                    </div>
                                                                                    <div class="col-md-2  ">
                                                                                        <div class="form-group  ">
                                                                                            <button type="button" class="btn btn-label-danger  " data-repeater-delete=" " data-id=" ">
                                                                                                <i class="ti ti-x ti-xs me-1"></i>

                                                                                            </button>

                                                                                        </div>

                                                                                    </div>



                                                                                </div>
                                                                            @endforeach



                                                                        </div>
                                                                        <div class="mb-0">
                                                                            <button type="button" class="btn btn-primary" data-repeater-create>
                                                                                <i class="ti ti-plus me-1"></i>
                                                                                <span class="align-middle">Add</span>
                                                                            </button>
                                                                        </div>
                                                                    </div>

                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>



                                                </div>
                                            </div>
                                        </div>
                                        <!-- /Distance key between facilities -->
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-12">
                                            <div class="card">
                                                <div class="card-header">{{ __('Status') }}</div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <select name="status" required id="status" class="form-select">
                                                            <option value="0" {{ $property->status == 0 ? 'selected' : '' }}>{{ __('Not available') }}</option>
                                                            <option value="1" {{ $property->status == 1 ? 'selected' : '' }}>{{ __('Preparing selling') }}</option>
                                                            <option value="2" {{ $property->status == 2 ? 'selected' : '' }}>{{ __('Selling') }}</option>
                                                            <option value="3" {{ $property->status == 3 ? 'selected' : '' }}>{{ __('Sold') }}</option>
                                                            <option value="4" {{ $property->status == 4 ? 'selected' : '' }}>{{ __('Renting') }}</option>
                                                            <option value="5" {{ $property->status == 5 ? 'selected' : '' }}>{{ __('Rented') }}</option>
                                                            <option value="6" {{ $property->status == 6 ? 'selected' : '' }}>{{ __('Building') }}</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                        @php
                                            $moderationStatuses = [
                                                0 => __('Pending'),
                                                1 => __('Published'),
                                                2 => __('Draft')
                                            ];


                                        @endphp

                                        <div class="col-12 mt-2">
                                            <div class="card">
                                                <div class="card-header">{{ __('Moderation Status') }}</div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <select name="moderation_status" required id="moderation_status" class="form-select">
                                                            @foreach($moderationStatuses as $value => $status)
                                                                <option value="{{ $value }}" {{ $value == old('moderation_status', $property->moderation_status) ? 'selected' : '' }}>
                                                                    {{ $status }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-2">
                                            <div class="card">
                                                <div class="card-header">{{ __('Categories') }}</div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <select name="category_id" required id="category_id" class="form-select select2">
                                                            @foreach($categories as $category)
                                                                <option value="{{ $category->id }}" {{ $category->id == old('category_id', $property->category_id) ? 'selected' : '' }}>
                                                                    {{ $category->name }}
                                                                </option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="col-12 mt-2">
                                            <div class="card">
                                                <div class="card-header">{{ __('Video URL') }}</div>
                                                <div class="card-body">
                                                    <div class="form-group">
                                                        <input type="text" class="form-control" name="video_url" id="video_url" placeholder="{{ __('https://youtu.be/xxxx') }}" value="{{ old('video_url', $property->more_info->video_url) }}">
                                                        <small class="form-hint">{{ __('Use the YouTube video link to be able to watch the video directly on the website.') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>


                                        <div class="col-12 mt-2">
                                            <div class="card">
                                                <div class="card-header">{{__('Account')}}</div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        {{--                                                            <div id="hint-message" class="hint-message">Please enter 1 or more characters</div>--}}

                                                        <select id="user-list" name="user_id" class="form-select " >
                                                            <option value=""></option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
    <!-- / Content -->

@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->

    <script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
    {{--    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>--}}

    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

    <script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
    <!-- Page JS -->
    <!-- END: Page JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.5.0/dropzone.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            // Loop through each language input field to set up event listeners
            @foreach ($lang as $locale)
            const nameInput_{{ $locale }} = document.querySelector('input[name="name[{{ $locale }}]"]');
            const slugInput_{{ $locale }} = document.querySelector('input[name="slug[{{ $locale }}]"]');
            const checkIcon_{{ $locale }} = document.querySelector('#slug_{{ $locale }} .fa-check');
            const falseIcon_{{ $locale }} = document.querySelector('#slug_{{ $locale }} .fa-times');
            const loadingSpinner_{{ $locale }} = document.getElementById('loading-spinner');

            // Event listener for name input
            nameInput_{{ $locale }}.addEventListener('input', function () {
                const name = nameInput_{{ $locale }}.value;
                if (name) {
                    // Show the loading spinner
                    loadingSpinner_{{ $locale }}.classList.remove('d-none');

                    fetch('{{ route('admin.properties.generate.slug') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ name: name, lang: '{{ $locale }}' }) // Use the current locale
                    })
                        .then(response => response.json())
                        .then(data => {
                            slugInput_{{ $locale }}.value = data.slug;

                            // Hide the loading spinner
                            loadingSpinner_{{ $locale }}.classList.add('d-none');

                            // Keep the slug input read-only after generation
                            slugInput_{{ $locale }}.readOnly = true;

                            // Show the check icon if the slug is valid
                            slugInput_{{ $locale }}.style.borderColor = 'green';
                            checkIcon_{{ $locale }}.classList.remove('d-none');
                            falseIcon_{{ $locale }}.classList.add('d-none');
                        })
                        .catch(() => {
                            // Hide the loading spinner
                            loadingSpinner_{{ $locale }}.classList.add('d-none');

                            // Allow editing if there's an issue
                            slugInput_{{ $locale }}.readOnly = false;

                            // Show the error icon if there's an issue
                            slugInput_{{ $locale }}.style.borderColor = 'red';
                            checkIcon_{{ $locale }}.classList.add('d-none');
                            falseIcon_{{ $locale }}.classList.remove('d-none');
                        });
                } else {
                    slugInput_{{ $locale }}.value = '';
                    loadingSpinner_{{ $locale }}.classList.add('d-none');
                    checkIcon_{{ $locale }}.classList.add('d-none');
                    falseIcon_{{ $locale }}.classList.add('d-none');
                }
            });
            @endforeach
        });
    </script>



    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typeSelect = document.getElementById('type');
            var periodDiv = document.getElementById('period').closest('.col-md-4');

            function togglePeriod() {
                if (typeSelect.value === '0') { // Sold
                    periodDiv.style.display = 'block';
                } else { //  Rent
                    periodDiv.style.display = 'none';
                }
            }

            typeSelect.addEventListener('change', togglePeriod);

            // Initial check
            togglePeriod();
        });
    </script>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var neverExpiredCheckbox = document.getElementById('never_expired');
            var autoRenewDiv = document.getElementById('auto_renew').closest('.form-group');

            function toggleAutoRenew() {
                if (neverExpiredCheckbox.checked) {
                    autoRenewDiv.style.display = 'block';
                } else {
                    autoRenewDiv.style.display = 'none';
                }
            }

            neverExpiredCheckbox.addEventListener('change', toggleAutoRenew);

            // Initial check
            toggleAutoRenew();
        });
    </script>



    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize all Select2 elements
            $('.select2').select2();

            // Initialize Select2 for user list with AJAX
            $('#user-list').select2({
                placeholder: 'Search for a user',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('admin.get_users') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        return {
                            query: params.term
                        };
                    },
                    processResults: function(data) {
                        return {
                            results: data
                        };
                    },
                    cache: true
                }
            });

            // Set the selected user after loading the Select2 options
            function setSelectedUser(userId) {
                if (userId) {
                    $('#user-list').val(userId).trigger('change');
                }
            }

            // Example: Replace this with the actual user ID from your data
            let selectedUserId = "{{ $property->user_id ?? '' }}";
            setSelectedUser(selectedUserId);

            // Event handling for user-list
            $('#user-list').on('select2:open', function() {
                $('#hint-message').show();
            });

            $('#user-list').on('select2:close', function() {
                $('#hint-message').hide();
            });

            $('#user-list').on('select2:selecting', function(e) {
                // Handle the selection of a user
                let selectedUserName = e.params.args.data.text;
                $('#user-list').val(selectedUserName).trigger('change');
                $('#hint-message').hide();
            });
            // Event handling for country selection
            // Handle country change
            $('#country').change(function() {
                let countryId = $(this).val();
                if (countryId) {
                    $('#state').prop('disabled', true);
                    $('#state-loading').show();

                    $.ajax({
                        url: '{{ route('admin.get_states', '') }}/' + countryId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            let $stateSelect = $('#state');
                            $stateSelect.prop('disabled', false).empty().append('<option value="">{{ __('Select State') }}</option>');
                            $.each(data, function(key, state) {
                                $stateSelect.append('<option value="' + state.id + '">' + state.name + '</option>');
                            });
                            $('#city').empty().append('<option value="">{{ __('Select City') }}</option>');
                            $('#state').trigger('change.select2');
                            $('#state-loading').hide();
                        },
                        error: function() {
                            $('#state').prop('disabled', false);
                            $('#state-loading').hide();
                        }
                    });
                } else {
                    $('#state, #city').empty().append('<option value="">{{ __('Select State') }}</option>');
                    $('#city').append('<option value="">{{ __('Select City') }}</option>');
                    $('#state, #city').trigger('change.select2');
                }
            });

            // Handle state change
            $('#state').change(function() {
                let stateId = $(this).val();
                if (stateId) {
                    $('#city').prop('disabled', true);
                    $('#city-loading').show();

                    $.ajax({
                        url: '{{ route('admin.get_cities', '') }}/' + stateId,
                        type: 'GET',
                        dataType: 'json',
                        success: function(data) {
                            let $citySelect = $('#city');
                            $citySelect.prop('disabled', false).empty().append('<option value="">{{ __('Select City') }}</option>');
                            $.each(data, function(key, city) {
                                $citySelect.append('<option value="' + city.id + '">' + city.name + '</option>');
                            });
                            $('#city').trigger('change.select2');
                            $('#city-loading').hide();
                        },
                        error: function() {
                            $('#city').prop('disabled', false);
                            $('#city-loading').hide();
                        }
                    });
                } else {
                    $('#city').empty().append('<option value="">{{ __('Select City') }}</option>');
                    $('#city').trigger('change.select2');
                }
            });



        });
    </script>




    <script>
        function readURL(input) {
            console.log(input.files);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $(".img-preview").attr("src", e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
        var uploadedDocumentMap = {}
        Dropzone.options.dpzMultipleFiles = {
            paramName: "dzfile", // The name that will be used to transfer the file
            //autoProcessQueue: false,
            // maxFilesize: 5, // MB

            maxFilesize: 5, // MB (0.5 MB)
            clickable: true,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            dictFallbackMessage: " المتصفح الخاص بكم لا يدعم خاصيه تعدد الصوره والسحب والافلات ",
            dictInvalidFileType: "لايمكنك رفع هذا النوع من الملفات ",
            dictCancelUpload: "الغاء الرفع ",
            dictCancelUploadConfirmation: " هل انت متاكد من الغاء رفع الملفات ؟ ",
            dictRemoveFile: "حذف الصوره",
            dictMaxFilesExceeded: "لايمكنك رفع عدد اكثر من هضا ",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ route('admin.images.store') }}", // Set the url
            success: function(file, response) {
                $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">')
                uploadedDocumentMap[file.name] = response.name
            },
            removedfile: function(file) {
                file.previewElement.remove()
                var name = ''
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name
                } else {
                    name = uploadedDocumentMap[file.name]
                }
                $('form').find('input[name="images[]"][value="' + name + '"]').remove()
            },
            // previewsContainer: "#dpz-btn-select-files", // Define the container to display the previews

            init: function() {
                // Pre-existing images
                var existingImages = {!! json_encode($property->images) !!};
                for (var i in existingImages) {
                    var file = existingImages[i];
                    var mockFile = { name: file.img.split('/').pop(), size: 12345 }; // Mock file object
                    this.options.addedfile.call(this, mockFile);
                    this.options.thumbnail.call(this, mockFile, file.img);
                    mockFile.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="images[]" value="' + file.img + '">');
                }
            }
        }

        $.validator.addMethod('filesize', function(value, element, param) {
            return this.optional(element) || (element.files[0].size <= param);
        }, 'يجيب ان يكون حجم المرفق اقل من 5 ميجا بايت');







    </script>
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
                    reader.readAsDatas(files[0]);
                });
            }
        });
    </script>
    <script>
        $(document).ready(function() {
            const fullToolbar = [
                [{ font: [] }, { size: [] }],
                ['bold', 'italic', 'underline', 'strike'],
                [{ color: [] }, { background: [] }],
                [{ script: 'super' }, { script: 'sub' }],
                [{ header: '1' }, { header: '2' }, 'blockquote', 'code-block'],
                [{ list: 'ordered' }, { list: 'bullet' }, { indent: '-1' }, { indent: '+1' }],
                ['direction', { align: [] }],
                ['link', 'image', 'video', 'formula'],
                ['clean']
            ];

            // Object to store Quill editors for different languages
            const editors = {};

            // Initialize Quill editors for visible language sections and set content
            function initQuillEditor(locale) {
                if (!editors[locale]) {
                    // Initialize Quill editor for this locale
                    editors[locale] = new Quill(`#editor-container-${locale}`, {
                        bounds: `#editor-container-${locale}`,
                        placeholder: 'Type Something...',
                        modules: {
                            formula: true,
                            toolbar: fullToolbar
                        },
                        theme: 'snow'
                    });

                    // Set initial content from the textarea
                    const initialContent = document.getElementById(`content_${locale}`).value;
                    editors[locale].root.innerHTML = initialContent;
                }
            }

            // Initialize Quill editors for all locales when the document is ready
            @foreach ($lang as $index => $locale)
            initQuillEditor('{{ $locale }}');

            // Initialize Quill editor for each language when the accordion is shown
            $('#accordion{{ $locale }}').on('shown.bs.collapse', function () {
                initQuillEditor('{{ $locale }}');
            });
            @endforeach

            // Handle form submission
            $('#add_form').click(function(e) {
                e.preventDefault();
                var form = $(this.form);

                // Validate form before submission
                if (!form.valid()) {
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                // Prepare form data
                var postData = new FormData(this.form);

                // Append Quill content for each language
                @foreach ($lang as $locale)
                var quillContent = editors["{{ $locale }}"].root.innerHTML; // Get Quill content
                postData.append('content[{{ $locale }}]', quillContent); // Append content to form data
                @endforeach

                // Show loading spinner
                $('#add_form').html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                    '<span class="ml-25 align-middle">{{ __('Saving') }}...</span>');

                // Perform AJAX request to update the property
                $.ajax({
                    url: '{{ route('admin.properties.update', $property->id) }}',
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        $('#add_form').html('{{ __('Save') }}');
                        setTimeout(function() {
                            toastr.success(response.success, {
                                closeButton: true,
                                tapToDismiss: false
                            });
                        }, 200);
                        $('.custom-error').remove(); // Remove existing errors
                    },
                    error: function(data) {
                        $('.custom-error').remove();
                        $('#add_form').html('{{ __('Save') }}');
                        var response = data.responseJSON;
                        if (data.status == 422 && response.errors) {
                            $.each(response.errors, function(key, value) {
                                var error_message = '<div class="custom-error"><p style="color: red">' + value[0] + '</p></div>';
                                $('[name="' + key + '"]').closest('.form-group').append(error_message);
                            });
                        } else {
                            swal.fire({
                                icon: 'error',
                                title: response.message
                            });
                        }
                    }
                });
            });
        });
    </script>



    <script>
        $(document).ready(function() {
            'use strict';
            window.id = 0;
            $('.repeater').repeater({
                // (Optional)
                // start with an empty list of repeaters. Set your first (and only)
                // "data-repeater-item" with style="display:none;" and pass the
                // following configuration flag
                initEmpty: false,
                // (Optional)
                // "defaultValues" sets the values of added items.  The keys of
                // defaultValues refer to the value of the input's name attribute.
                // If a default value is not specified for an input, then it will
                // have its value cleared.
                defaultValues: {
                    'id': window.id,
                },
                // (Optional)
                // "show" is called just after an item is added.  The item is hidden
                // at this point.  If a show callback is not given the item will
                // have $(this).show() called on it.
                show: function() {
                    $(this).slideDown();



                },
                // (Optional)
                // "hide" is called when a user clicks on a data-repeater-delete
                // element.  The item is still visible.  "hide" is passed a function
                // as its first argument which will properly remove the item.
                // "hide" allows for a confirmation step, to send a delete request
                // to the server, etc.  If a hide callback is not given the item
                // will be deleted.
                hide: function(deleteElement) {
                    if (confirm('Are you sure you want to delete this element?')) {
                        window.id--;
                        $('#cat-id').val(window.id);

                        $(this).slideUp(deleteElement);
                        console.log($('.repeater').repeaterVal());
                    }
                },
                // (Optional)
                // You can use this if you need to manually re-index the list
                // for example if you are using a drag and drop library to reorder
                // list items.
                ready: function(setIndexes) {},
                // (Optional)
                // Removes the delete button from the first list item,
                // defaults to false.
                isFirstItemUndeletable: true
            })
        });
    </script>
@endsection
