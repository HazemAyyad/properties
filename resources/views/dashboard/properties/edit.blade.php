
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
        #address-loader.address-loader-hidden {
            display: none !important;
        }
        .dropzone .dz-preview .dz-image img {
            display: block;
            width: 120px;
            height: 120px;
        }
        /* Featured Listing & 3D Tour cards */
        .card-featured-listing, .card-3d-tour { border: 1px solid #1779A7; border-radius: 0.375rem; overflow: hidden; }
        .card-featured-listing .card-header { background-color: #1779A7 !important; border-color: #1779A7; color: #fff; padding: 0.75rem 1rem; font-weight: 600; }
        .card-featured-listing .card-body { padding: 1rem; }
        .card-featured-listing .btn-outline-primary { border-color: #1779A7; color: #1779A7; }
        .card-featured-listing .btn-outline-primary:hover { background-color: #1779A7; color: #fff; border-color: #1779A7; }
        .card-featured-listing .receipt-line { display: flex; align-items: center; gap: 0.5rem; flex-wrap: wrap; margin-bottom: 0.75rem; }
        .card-featured-listing .receipt-line strong { margin: 0 0.25rem 0 0; }
        .card-featured-listing .pending-msg { background: #fff8e6; border: 1px solid #f0c674; border-radius: 8px; padding: 0.75rem 1rem; margin-bottom: 1rem; color: #856404; font-size: 0.9375rem; line-height: 1.4; }
        .card-featured-listing .approve-form { margin-top: 0.5rem; }
        .card-featured-listing .btn-success { padding: 0.375rem 0.75rem; }
        .card-3d-tour .card-header { background-color: #28a745 !important; border-color: #28a745; color: #fff; padding: 0.75rem 1rem; font-weight: 600; }
        .card-3d-tour .card-body { padding: 1rem; }
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
            </ol>
        </nav>
        @if(session('success'))
            <div class="alert alert-success alert-dismissible" role="alert">{{ session('success') }}<button type="button" class="btn-close" data-bs-dismiss="alert"></button></div>
        @endif
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
                                                                    <option value="0" {{ (old('type', $property->type ?? 0) == 0) ? 'selected' : '' }}>{{__('For Rent')}}</option>
                                                                    <option value="1" {{ (old('type', $property->type ?? 0) == 1) ? 'selected' : '' }}>{{__('For Sale')}}</option>
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
                                        <!-- address - مثل اليوزر: العنوان للأردن -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{ __('Address') }} - {{ __('Jordan') }}</h5>
                                                <div class="card-body position-relative">
                                                    <div id="address-loader" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.85); z-index: 5;">
                                                        <div class="spinner-border text-primary" role="status"><span class="visually-hidden">{{ __('Loading...') }}</span></div>
                                                    </div>
                                                    <div id="address-fields" class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="governorate">{{ __('Governorate') }}:</label>
                                                                <select id="governorate" required name="governorate_id" class="form-control select2">
                                                                    <option value="">{{ __('Select Governorate') }}</option>
                                                                </select>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="department">{{ __('Department') }}:</label>
                                                                <select id="department" required name="department_id" class="form-control select2">
                                                                    <option value="">{{ __('Select Department') }}</option>
                                                                </select>
                                                                <div id="department-loading" class="loading-indicator" style="display: none;">{{ __('Loading...') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="village">{{ __('Village') }}:</label>
                                                                <select id="village" required name="village_id" class="form-control select2">
                                                                    <option value="">{{ __('Select Village') }}</option>
                                                                </select>
                                                                <div id="village-loading" class="loading-indicator" style="display: none;">{{ __('Loading...') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="hod">{{ __('Hod') }}:</label>
                                                                <select id="hod" name="hod_id" class="form-control select2">
                                                                    <option value="">{{ __('Select Hod') }}</option>
                                                                </select>
                                                                <div id="hod-loading" class="loading-indicator" style="display: none;">{{ __('Loading...') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="hay">{{ __('Hay') }}:</label>
                                                                <select id="hay" name="hay_id" class="form-control select2">
                                                                    <option value="">{{ __('Select Hay') }}</option>
                                                                </select>
                                                                <div id="hay-loading" class="loading-indicator" style="display: none;">{{ __('Loading...') }}</div>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group">
                                                                <label for="plot_number">{{ __('Plot Number') }}:</label>
                                                                <input type="text" class="form-control" name="plot_number" id="plot_number" value="{{ old('plot_number', $property->address?->plot_number ?? '') }}" required>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="row">
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="full_address">{{ __('Full Address') }}</label>
                                                                <input type="text" required class="form-control" name="full_address" id="full_address" value="{{ old('full_address', $property->address?->full_address ?? '') }}" placeholder="{{ __('Full Address') }}">
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="latitude">{{ __('Latitude') }}</label>
                                                                <input type="text" class="form-control" name="latitude" id="latitude" value="{{ old('latitude', $property->address?->latitude ?? '') }}" placeholder="{{ __('Ex: 1.462260') }}">
                                                                <a class="form-hint" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">{{ __('Go here to get Latitude from address.') }}</a>
                                                            </div>
                                                        </div>
                                                        <div class="col-md-4">
                                                            <div class="form-group mb-3">
                                                                <label class="form-label" for="longitude">{{ __('Longitude') }}</label>
                                                                <input type="text" class="form-control" name="longitude" id="longitude" value="{{ old('longitude', $property->address?->longitude ?? '') }}" placeholder="{{ __('Ex: 1.462260') }}">
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
                                        <!-- Categories - قبل Property Details -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{ __('Categories') }}</h5>
                                                <div class="card-body">
                                                    <div class="form-group mb-0">
                                                        <label class="form-label">{{ __('Property Type') }}</label>
                                                        <select name="category_id" id="category_id" class="form-control select2" required>
                                                            @foreach($categories as $category)
                                                                @php $catSlug = $category->getTranslation('slug', 'en') ?: $category->getTranslation('slug', 'ar'); @endphp
                                                                <option value="{{ $category->id }}" data-slug="{{ $catSlug }}" {{ $category->id == old('category_id', $property->category_id) ? 'selected' : '' }}>{{ $category->name }}</option>
                                                            @endforeach
                                                        </select>
                                                        <small class="form-hint">{{ __('Select property type to show relevant fields below.') }}</small>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row mt-2">
                                        <!-- Category-specific: Property details by type -->
                                        <div class="col-12">
                                            <div class="card">
                                                <h5 class="card-header">{{ __('Property Details') }} <span class="text-muted">({{ __('by property type') }})</span></h5>
                                                <div class="card-body">
                                                    <p id="category-fields-hint" class="text-muted small mb-3">{{ __('Select property type above to show relevant fields.') }}</p>
                                                    <div id="category-fields-apartment" class="category-fields-section" data-category="apartment" style="display:none;">
                                                        @include('user_dashboard.properties.partials.category_fields_apartment', ['info' => $property->more_info ?? null])
                                                    </div>
                                                    <div id="category-fields-villa" class="category-fields-section" data-category="villa" style="display:none;">
                                                        @include('user_dashboard.properties.partials.category_fields_villa', ['info' => $property->more_info ?? null])
                                                    </div>
                                                    <div id="category-fields-office" class="category-fields-section" data-category="office" style="display:none;">
                                                        @include('user_dashboard.properties.partials.category_fields_office', ['info' => $property->more_info ?? null])
                                                    </div>
                                                    <div id="category-fields-commercial" class="category-fields-section" data-category="commercial" style="display:none;">
                                                        @include('user_dashboard.properties.partials.category_fields_commercial', ['info' => $property->more_info ?? null])
                                                    </div>
                                                    <div id="category-fields-farm" class="category-fields-section" data-category="farm" style="display:none;">
                                                        @include('user_dashboard.properties.partials.category_fields_farm', ['info' => $property->more_info ?? null])
                                                    </div>
                                                    <div id="category-fields-land" class="category-fields-section" data-category="land" style="display:none;">
                                                        @include('user_dashboard.properties.partials.category_fields_land', ['info' => $property->more_info ?? null])
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                        <div class="col-12 mt-2">
                                            <div class="card">
                                                <div class="card-header">{{ __('Contact information') }}</div>
                                                <div class="card-body">
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="contact_name">{{ __('Contact Name') }}</label>
                                                        <input type="text" class="form-control" name="contact_name" id="contact_name" value="{{ old('contact_name', $property->contact_name ?? '') }}" required>
                                                    </div>
                                                    <div class="form-group mb-3">
                                                        <label class="form-label" for="contact_phone">{{ __('Contact Phone') }}</label>
                                                        <input type="text" class="form-control" name="contact_phone" id="contact_phone" value="{{ old('contact_phone', $property->contact_phone ?? '') }}" required>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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

                                        @if($property->is_featured || $property->featured_listing_receipt)
                                        <div class="col-12 mt-2">
                                            <div class="card card-featured-listing">
                                                <div class="card-header text-white">{{ __('Featured Listing') }}</div>
                                                <div class="card-body">
                                                    @if($property->featured_listing_receipt)
                                                        <div class="receipt-line mt-1">
                                                            <strong>{{ __('Payment receipt') }}:</strong>
                                                            <a href="{{ asset(ltrim(str_replace('/public', '', $property->featured_listing_receipt), '/')) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                                        </div>
                                                    @endif
                                                    @if($property->featured_listing_until)
                                                        @php
                                                            $until = \Carbon\Carbon::parse($property->featured_listing_until);
                                                            $active = $until->isFuture();
                                                        @endphp
                                                        <p class="mb-1"><strong>{{ __('Valid until') }}:</strong> {{ $property->featured_listing_until }}</p>
                                                        <p class="mb-2 {{ $active ? 'text-success' : 'text-muted' }}">
                                                            {{ $active ? __('Remaining') . ': ' . $until->diffForHumans(now(), true) : __('Expired') }}
                                                        </p>
                                                    @else
                                                        <div class="pending-msg">{{ __('Pending approval. Approve to activate featured listing for 1 month.') }}</div>
                                                        <div class="approve-form">
                                                            <button type="button" class="btn btn-success btn-approve-featured" data-property-id="{{ $property->id }}" data-url="{{ route('admin.properties.approve-featured', $property->id) }}">{{ __('Approve (1 month)') }}</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                        @if($property->is_3d_tour_featured || $property->featured_3d_tour_receipt)
                                        <div class="col-12 mt-2">
                                            <div class="card card-3d-tour">
                                                <div class="card-header text-white">{{ __('3D Tour') }}</div>
                                                <div class="card-body">
                                                    @if($property->featured_3d_tour_receipt)
                                                        <div class="receipt-line mt-1">
                                                            <strong>{{ __('Payment receipt') }}:</strong>
                                                            <a href="{{ asset(ltrim(str_replace('/public', '', $property->featured_3d_tour_receipt), '/')) }}" target="_blank" class="btn btn-sm btn-outline-primary">{{ __('View') }}</a>
                                                        </div>
                                                    @endif
                                                    @if($property->featured_3d_tour_until)
                                                        @php
                                                            $until3d = \Carbon\Carbon::parse($property->featured_3d_tour_until);
                                                            $active3d = $until3d->isFuture();
                                                        @endphp
                                                        <p class="mb-1"><strong>{{ __('Valid until') }}:</strong> {{ $property->featured_3d_tour_until }}</p>
                                                        <p class="mb-2 {{ $active3d ? 'text-success' : 'text-muted' }}">
                                                            {{ $active3d ? __('Remaining') . ': ' . $until3d->diffForHumans(now(), true) : __('Expired') }}
                                                        </p>
                                                        <button type="button" class="btn btn-sm btn-outline-primary btn-edit-3d-iframe" data-url="{{ route('admin.properties.update-featured-3d-iframe', $property->id) }}" data-iframe="{{ base64_encode($property->featured_3d_tour_iframe ?? '') }}" title="{{ __('Edit') }}">
                                                            <i class="ti ti-edit ti-sm me-1"></i>{{ __('Edit iframe') }}
                                                        </button>
                                                    @else
                                                        <div class="pending-msg">{{ __('Pending approval. Approve to activate 3D tour for 1 month.') }}</div>
                                                        <div class="approve-form">
                                                            <button type="button" class="btn btn-success btn-open-approve-3d-modal" data-url="{{ route('admin.properties.approve-featured-3d', $property->id) }}">{{ __('Approve (1 month)') }}</button>
                                                            <button type="button" class="btn btn-danger btn-reject-featured-3d ms-1" data-url="{{ route('admin.properties.reject-featured-3d', $property->id) }}">{{ __('Reject') }}</button>
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </div>
                                        @endif

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
                                                            <option value="">{{ __('Select user') }}</option>
                                                            @if($property->user_id && $property->user)
                                                                <option value="{{ $property->user_id }}" selected>{{ $property->user->name }}</option>
                                                            @endif
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

    <!-- Modal: Approve 3D Tour (إدخال iframe قبل الموافقة) -->
    <div class="modal fade" id="approve3dModal" tabindex="-1" aria-labelledby="approve3dModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="approve3dModalLabel">{{ __('Approve 3D Tour') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="approve3dForm">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="approve_iframe_url" class="form-label">{{ __('3D Tour iframe URL / Embed Code') }} <span class="text-danger">*</span></label>
                            <textarea class="form-control" id="approve_iframe_url" name="iframe_url" rows="4" placeholder="{{ __('Paste the iframe embed code or URL here...') }}" required></textarea>
                            <small class="text-muted">{{ __('Paste the full iframe HTML or the src URL of the 3D tour embed.') }}</small>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-success" id="approve3dSubmitBtn">
                            <i class="ti ti-check me-1"></i>{{ __('Approve (1 month)') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal: Edit 3D Tour iframe -->
    <div class="modal fade" id="edit3dIframeModal" tabindex="-1" aria-labelledby="edit3dIframeModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="edit3dIframeModalLabel">{{ __('Edit 3D Tour iframe') }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="edit3dIframeForm" method="post" action="">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="edit_iframe_url" class="form-label">{{ __('3D Tour iframe URL / Embed Code') }}</label>
                            <textarea class="form-control" id="edit_iframe_url" name="iframe_url" rows="4" placeholder="{{ __('Paste the iframe embed code or URL here...') }}"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">{{ __('Cancel') }}</button>
                        <button type="submit" class="btn btn-primary">
                            <i class="ti ti-device-floppy me-1"></i>{{ __('Save') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
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
            var neverExpiredDiv = document.getElementById('never_expired') && document.getElementById('never_expired').closest('.col-md-6');
            var autoRenewDiv = document.getElementById('auto_renew') && document.getElementById('auto_renew').closest('.form-group');

            function toggleRentOnlyFields() {
                var isForRent = (typeSelect.value === '0');
                periodDiv.style.display = isForRent ? 'block' : 'none';
                if (neverExpiredDiv) neverExpiredDiv.style.display = isForRent ? 'block' : 'none';
                if (autoRenewDiv) autoRenewDiv.style.display = isForRent ? 'block' : 'none';
            }

            typeSelect.addEventListener('change', toggleRentOnlyFields);
            toggleRentOnlyFields();
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

            // Show/hide category-specific fields when category changes
            function showCategoryFields() {
                var slug = $('#category_id option:selected').data('slug') || '';
                $('#category-fields-hint').toggle(!slug);
                $('.category-fields-section').each(function() {
                    var $sec = $(this);
                    var isVisible = slug && $sec.attr('id') === 'category-fields-' + slug;
                    $sec.toggle(isVisible);
                    $sec.find('input, select, textarea').prop('disabled', !isVisible);
                });
            }
            $('#category_id').on('change', showCategoryFields);
            showCategoryFields();

            // Initialize Select2 for user list with AJAX (مع عرض اليوزر الحالي إن وُجد)
            var selectedUserId = "{{ $property->user_id ?? '' }}";
            var selectedUserName = {!! json_encode($property->user->name ?? '') !!};
            $('#user-list').select2({
                placeholder: '{{ __("Search for a user") }}',
                allowClear: true,
                minimumInputLength: 1,
                data: selectedUserId && selectedUserName ? [{ id: selectedUserId, text: selectedUserName }] : [],
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
            if (selectedUserId) {
                $('#user-list').val(selectedUserId).trigger('change');
            }

            // Event handling for user-list
            $('#user-list').on('select2:open', function() {
                $('#hint-message').show();
            });

            $('#user-list').on('select2:close', function() {
                $('#hint-message').hide();
            });

            $('#user-list').on('select2:select', function(e) {
                $('#hint-message').hide();
            });
            // Jordan address - مثل اليوزر (جدول الاحياء؛ hay_id=0 يعتبر لا اختيار)
            var editAddress = @json($property->address ?? null);
            var govId = editAddress ? (editAddress.governorate_id || '') : '';
            var deptId = editAddress ? (editAddress.department_id || '') : '';
            var villId = editAddress ? (editAddress.village_id || '') : '';
            var hodId = editAddress ? (editAddress.hod_id || '') : '';
            var hayId = (editAddress && editAddress.hay_id !== undefined && editAddress.hay_id !== null && editAddress.hay_id !== '') ? editAddress.hay_id : '';
            function hideAddressLoader() { $('#address-loader').addClass('address-loader-hidden'); }
            var addressLoadTimeout = setTimeout(hideAddressLoader, 15000);
            function hideAddressLoaderOnce() { clearTimeout(addressLoadTimeout); hideAddressLoader(); }
            function loadGovernorates(cb) {
                $.get('{{ route('admin.jordan.governorates') }}').done(function(data) {
                    $('#governorate').empty().append('<option value="">{{ __('Select Governorate') }}</option>');
                    $.each(data, function(i, item) { $('#governorate').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                    if (govId) $('#governorate').val(govId).trigger('change.select2');
                    if (cb) cb();
                }).fail(hideAddressLoaderOnce);
            }
            loadGovernorates(function() {
                if (!govId) { hideAddressLoaderOnce(); return; }
                $.get('{{ route('admin.jordan.departments', '') }}/' + govId).done(function(data) {
                    $('#department').empty().append('<option value="">{{ __('Select Department') }}</option>');
                    $.each(data, function(i, item) { $('#department').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                    if (deptId) $('#department').val(deptId);
                    if (!deptId) { hideAddressLoaderOnce(); return; }
                    $.get('{{ route('admin.jordan.villages', '') }}/' + deptId).done(function(data2) {
                        $('#village').empty().append('<option value="">{{ __('Select Village') }}</option>');
                        $.each(data2, function(i, item) { $('#village').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                        if (villId) $('#village').val(villId);
                        if (!villId) { hideAddressLoaderOnce(); return; }
                        $.get(('{{ route("admin.jordan.hods", [0, 0]) }}').replace(/\/0\/0$/, '/' + deptId + '/' + villId)).done(function(data3) {
                            $('#hod').empty().append('<option value="">{{ __('Select Hod') }}</option>');
                            $.each(data3, function(i, item) { $('#hod').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                            if (hodId) $('#hod').val(hodId);
                            if (!hodId) { hideAddressLoaderOnce(); return; }
                            $.get(('{{ route("admin.jordan.hays", [0, 0, 0]) }}').replace(/\/0\/0\/0$/, '/' + deptId + '/' + villId + '/' + hodId))
                                .done(function(data4) {
                                    var $hay = $('#hay');
                                    try { if ($hay.hasClass('select2-hidden-accessible')) $hay.select2('destroy'); } catch(e) {}
                                    $hay.empty().append('<option value="">{{ __('Select Hay') }}</option>');
                                    if (Array.isArray(data4)) { $.each(data4, function(i, item) { $hay.append('<option value="' + String(item.id) + '">' + item.name + '</option>'); }); }
                                    var valToSet = (hayId !== '' && hayId !== undefined && hayId !== null) ? ((hayId === 0 || hayId === '0') ? '0' : String(hayId)) : '';
                                    if (valToSet !== '') { $hay.val(valToSet); $hay.find('option').prop('selected', false).filter('[value="' + valToSet + '"]').prop('selected', true); }
                                    $hay.select2();
                                    if (valToSet !== '') $hay.val(valToSet).trigger('change');
                                    hideAddressLoaderOnce();
                                })
                                .fail(hideAddressLoaderOnce)
                                .always(hideAddressLoaderOnce);
                        }).fail(hideAddressLoaderOnce);
                    }).fail(hideAddressLoaderOnce);
                }).fail(hideAddressLoaderOnce);
            });
            $('#governorate').change(function() {
                var id = $(this).val();
                $('#department, #village, #hod, #hay').empty().append('<option value="">{{ __('Select') }}</option>');
                if (id) $.get('{{ route('admin.jordan.departments', '') }}/' + id, function(data) {
                    $('#department').empty().append('<option value="">{{ __('Select Department') }}</option>');
                    $.each(data, function(i, item) { $('#department').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
            });
            $('#department').change(function() {
                var id = $(this).val();
                $('#village, #hod, #hay').empty().append('<option value="">{{ __('Select') }}</option>');
                if (id) $.get('{{ route('admin.jordan.villages', '') }}/' + id, function(data) {
                    $('#village').empty().append('<option value="">{{ __('Select Village') }}</option>');
                    $.each(data, function(i, item) { $('#village').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
            });
            $('#village').change(function() {
                var d = $('#department').val(), v = $(this).val();
                $('#hod, #hay').empty().append('<option value="">{{ __('Select') }}</option>');
                if (d && v) $.get(('{{ route("admin.jordan.hods", [0, 0]) }}').replace(/\/0\/0$/, '/' + d + '/' + v), function(data) {
                    $('#hod').empty().append('<option value="">{{ __('Select Hod') }}</option>');
                    $.each(data, function(i, item) { $('#hod').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
            });
            $('#hod').change(function() {
                var d = $('#department').val(), v = $('#village').val(), h = $(this).val();
                $('#hay').empty().append('<option value="">{{ __('Select') }}</option>');
                if (d && v && h) $.get(('{{ route("admin.jordan.hays", [0, 0, 0]) }}').replace(/\/0\/0\/0$/, '/' + d + '/' + v + '/' + h), function(data) {
                    $('#hay').empty().append('<option value="">{{ __('Select Hay') }}</option>');
                    $.each(data, function(i, item) { $('#hay').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
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
                // Pre-existing images: img_url للعرض (بدون /public في المسار)
                var existingImages = {!! json_encode($property->images->map(function($img) {
                    $path = $img->img;
                    $path = preg_replace('#^/public#', '', $path ?? '');
                    $path = ltrim($path, '/');
                    return ['img' => $img->img, 'img_url' => $path ? asset($path) : ''];
                })->values()) !!};
                for (var i in existingImages) {
                    var file = existingImages[i];
                    var thumbUrl = file.img_url || (file.img ? '{{ url("/") }}/' + (file.img).replace(/^\/?public\/?/, '').replace(/^\/+/, '') : '');
                    var mockFile = { name: (file.img || '').split('/').pop(), size: 12345 };
                    this.options.addedfile.call(this, mockFile);
                    this.options.thumbnail.call(this, mockFile, thumbUrl);
                    mockFile.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="images[]" value="' + (file.img || '').replace(/"/g, '&quot;') + '">');
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

                // تعطيل حقول الأقسام المخفية (تفاصيل العقار + ميزات إضافية) حتى FormData يلتقط القسم الظاهر فقط
                $('.category-fields-section:not(:visible)').find('input, select, textarea').prop('disabled', true);

                var postData = new FormData(this.form);

                // إعادة تفعيل الحقول
                $('.category-fields-section').find('input, select, textarea').prop('disabled', false);

                // إضافة حقول العنوان (Select2 قد لا يضمّنها في FormData)
                ['governorate_id', 'department_id', 'village_id', 'hod_id', 'hay_id'].forEach(function(name) {
                    var val = $('[name="' + name + '"]').val();
                    if (val !== undefined && val !== null && val !== '') postData.set(name, val);
                });

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

        // زر الموافقة على Featured Listing (لا نستخدم form داخل form فالإرسال عبر AJAX)
        $(document).on('click', '.btn-approve-featured', function() {
            var btn = $(this);
            var url = btn.data('url');
            var token = $('meta[name="csrf-token"]').attr('content');
            if (!url) return;
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: url,
                type: 'POST',
                headers: { 'X-CSRF-TOKEN': token },
                data: { _token: token },
                success: function() {
                    window.location.reload();
                },
                error: function() {
                    btn.prop('disabled', false).html('{{ __("Approve (1 month)") }}');
                    alert('{{ __("An error occurred. Try again.") }}');
                }
            });
        });

        // زر الموافقة على 3D Tour - يفتح modal لإدخال iframe
        $(document).on('click', '.btn-open-approve-3d-modal', function() {
            var url = $(this).data('url');
            $('#approve3dForm').attr('action', url).data('url', url);
            $('#approve_iframe_url').val('');
            var modal = new bootstrap.Modal(document.getElementById('approve3dModal'));
            modal.show();
        });

        // إرسال نموذج الموافقة على 3D Tour عبر AJAX مع loader
        $('#approve3dForm').on('submit', function(e) {
            e.preventDefault();
            var form = $(this);
            var url = form.data('url') || form.attr('action');
            if (!url) return;
            var submitBtn = $('#approve3dSubmitBtn');
            var originalHtml = submitBtn.html();
            submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                    'Accept': 'application/json'
                },
                data: form.serialize(),
                success: function() {
                    bootstrap.Modal.getInstance(document.getElementById('approve3dModal')).hide();
                    window.location.reload();
                },
                error: function(xhr) {
                    submitBtn.prop('disabled', false).html(originalHtml);
                    var msg = xhr.responseJSON && xhr.responseJSON.errors && xhr.responseJSON.errors.iframe_url ? xhr.responseJSON.errors.iframe_url[0] : '{{ __("An error occurred. Try again.") }}';
                    alert(msg);
                }
            });
        });

        // زر الرفض لـ 3D Tour
        $(document).on('click', '.btn-reject-featured-3d', function() {
            if (!confirm('{{ __("Are you sure you want to reject?") }}')) return;
            var btn = $(this);
            var url = btn.data('url');
            var token = $('meta[name="csrf-token"]').attr('content');
            if (!url) return;
            var originalHtml = btn.html();
            btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span>');
            $.ajax({
                url: url,
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                data: { _token: token },
                success: function() {
                    window.location.reload();
                },
                error: function() {
                    btn.prop('disabled', false).html(originalHtml);
                    alert('{{ __("An error occurred. Try again.") }}');
                }
            });
        });

        $(document).on('click', '.btn-edit-3d-iframe', function() {
            var url = $(this).data('url');
            var iframeB64 = $(this).data('iframe') || '';
            var iframe = '';
            try { iframe = atob(iframeB64) || ''; } catch(e) {}
            $('#edit3dIframeForm').attr('action', url);
            $('#edit_iframe_url').val(iframe);
            var modal = new bootstrap.Modal(document.getElementById('edit3dIframeModal'));
            modal.show();
        });
    </script>
@endsection
