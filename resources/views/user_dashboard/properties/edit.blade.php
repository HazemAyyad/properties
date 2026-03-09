@extends('user_dashboard.layouts.app')
@section('style')
{{--    <link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />--}}
<!-- Styles -->
 <link rel="stylesheet" href="{{asset('site/select2/select2.min.css')}}" />
<link rel="stylesheet" href="{{asset('site/select2/select2-bootstrap-5-theme.min.css')}}" />
<!-- Or for RTL support -->
{{--<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2-bootstrap-5-theme@1.3.0/dist/select2-bootstrap-5-theme.rtl.min.css" />--}}

<!-- Scripts -->
<!-- Toastr CSS -->
<link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">


    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}" />
    <link rel="stylesheet" href="{{asset('site/quill/quill.snow.css')}}" />
    <link rel="stylesheet" href="{{asset('site/dropzone/dropzone.min.css')}}" type="text/css" />




<style>
    .loading {
        background: url('https://i.gifer.com/ZZ5H.gif') no-repeat right center;
        background-size: 20px 20px;
    }
    .is-invalid {
        border-color: red;
    }
    .invalid-feedback {
        display: block !important;
        width: 100%;
        margin-top: 0.25rem;
        font-size: 0.875rem;
        color: #dc3545;
    }
    .select2-container .select2-selection.is-invalid {
        border-color: #dc3545;
    }
    .form-group.has-error .form-control,
    .form-group.has-error .select2-selection {
        border-color: #dc3545;
    }
    #dpz-multiple-files.is-invalid {
        border: 2px solid #dc3545 !important;
        border-radius: 4px;
        background-color: #fff5f5;
    }
    .custom-error {
        display: block !important;
        width: 100%;
        margin-top: 0.75rem;
        margin-bottom: 0.5rem;
        padding: 0.5rem;
        background-color: #fff5f5;
        border-left: 3px solid #dc3545;
        border-radius: 4px;
    }
    .custom-error p {
        margin: 0;
        color: #dc3545;
        font-size: 0.875rem;
        font-weight: 500;
    }
    #general-errors {
        margin-bottom: 1rem;
    }
    .card .title[style*="color: rgb(220, 53, 69)"] {
        font-weight: 600;
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
    a.lat-long-link {
        color: #0d6efd;
        font-size: 0.9rem;
    }
    a.lat-long-link:hover {
        color: #0a58ca;
        text-decoration: underline;
    }
    .dropzone .dz-preview .dz-image img {
        display: block;
        width: 120px;
        height: 120px;
    }
    /* بالعربي: التشيك بوكس قبل الكلام - التنسيق الصحيح */
    .extra-features-rtl .form-check {
        direction: ltr !important;
        text-align: right;
    }
    .extra-features-rtl .form-check .form-check-input {
        float: right;
        margin-right: -1.5em;
    }
    /* توحيد ارتفاع الانبت مع السلكت على نفس الخط */
    .form-control,
    .form-select,
    select.form-control,
    select.form-select {
        box-sizing: border-box;
        min-height: 38px;
        height: 38px;
        line-height: 1.5;
        vertical-align: middle;
    }
    textarea.form-control {
        height: auto;
        min-height: 120px;
    }
    .form-select {
        appearance: none;
        -webkit-appearance: none;
        -moz-appearance: none;
        padding: 5px 16px;
        padding-right: 2.25rem;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3e%3cpath fill='none' stroke='%23343a40' stroke-linecap='round' stroke-linejoin='round' stroke-width='2' d='M2 5l6 6 6-6'/%3e%3c/svg%3e");
        background-repeat: no-repeat;
        background-position: right 16px center;
        background-size: 16px 12px;
        min-width: 6em;
    }
    [dir="rtl"] .form-select {
        padding: 5px 16px;
        padding-left: 2.25rem;
        background-position: left 16px center;
        text-align: right;
    }
    .select2-container--default .select2-selection--single {
        min-height: 38px !important;
        height: 38px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
    }
</style>
@endsection
@section('content')


    <div class="row">
        <div class="col-xl">

                    <form id="mainAdd" method="post" action="javascript:void(0)" >
                        @csrf

                        <div class="row">
                            <div class="col-md-8">
                                <div class="row">
                                    <div class="col-12">
                                        <div class="card widget-box-2">

                                            <div class="card-body">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="form-group box-fieldset">
                                                            <label class="form-label" for="name">{{__('Name')}}
                                                                <span>*</span>
                                                            </label>
                                                            @php
                                                                $editTitle = is_string($property->title ?? null) ? $property->title : (method_exists($property, 'getTranslation') ? ($property->getTranslation('title', app()->getLocale()) ?: $property->getTranslation('title', 'en')) : '');
                                                            @endphp
                                                            <input type="text" class="form-control style-1 style-1" name="name" value="{{ old('name', $editTitle ?? '') }}" id="name" placeholder="{{__('Name')}}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group box-fieldset">
                                                            <label class="form-label" for="slug">{{ __('Permalink') }}
                                                                <span>*</span>
                                                            </label>
                                                            @php
                                                                $editSlug = is_string($property->slug ?? null) ? $property->slug : (method_exists($property, 'getTranslation') ? ($property->getTranslation('slug', app()->getLocale()) ?: $property->getTranslation('slug', 'en')) : '');
                                                            @endphp
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text" id="slug">{{ config('app.url') }}/property/</span>
                                                                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $editSlug ?? '') }}" aria-describedby="slug" readonly>
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
                                                    <div class="col-md-12">
                                                        <div class="form-group  ">
                                                            <label class="form-label" for="description">{{__('Description')}}</label>
                                                            <textarea class="form-control style-1" name="description"  id="description"  rows="5">{{ old('description', $property->description ?? '') }}</textarea>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group box-fieldset">
                                                            <label class="form-label" for="contact_name">{{__('Person Name')}} <span>*</span></label>
                                                            <input type="text" class="form-control style-1" name="contact_name" value="{{ old('contact_name', $property->contact_name ?? '') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-6">
                                                        <div class="form-group box-fieldset">
                                                            <label class="form-label" for="contact_phone">{{__('Phone Number')}} <span>*</span></label>
                                                            <input type="text" class="form-control style-1" name="contact_phone" value="{{ old('contact_phone', $property->contact_phone ?? '') }}" required>
                                                        </div>
                                                    </div>
                                                    <div class="col-12  ">
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
                                    <!-- Full Editor -->
                                    <div class="col-12">
                                        <div class="card widget-box-2">
                                            <h6 class="title">{{__('Content')}}</h6>
                                            <div class="card-body">
                                                <div id="full-editor">
                                                    {!! old('content', $property->more_info->content ?? '')  !!}
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Full Editor -->
                                </div>
                                <div class="row mt-2">
                                    <!-- Images -->
                                    <div class="col-12">
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Images')}}</h6>
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
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Address')}} - {{__('Jordan')}}</h6>
                                            <div class="card-body position-relative">
                                                <div id="address-loader" class="position-absolute top-0 start-0 w-100 h-100 d-flex align-items-center justify-content-center" style="background: rgba(255,255,255,0.9); z-index: 5;">
                                                    <div class="spinner-border text-primary" role="status"></div>
                                                </div>
                                                <div class="row">
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
                                                            <div id="department-loading" class="loading-indicator" style="display: none;">{{__('Loading...')}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="village">{{ __('Village') }}:</label>
                                                            <select id="village" required name="village_id" class="form-control select2">
                                                                <option value="">{{ __('Select Village') }}</option>
                                                            </select>
                                                            <div id="village-loading" class="loading-indicator" style="display: none;">{{__('Loading...')}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="hod">{{ __('Hod') }}:</label>
<select id="hod" name="hod_id" class="form-control select2">
                                                            <option value="">{{ __('Select Hod') }}</option>
                                                        </select>
                                                            <div id="hod-loading" class="loading-indicator" style="display: none;">{{__('Loading...')}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="hay">{{ __('Hay') }}:</label>
<select id="hay" name="hay_id" class="form-control select2">
                                                            <option value="">{{ __('Select Hay') }}</option>
                                                        </select>
                                                            <div id="hay-loading" class="loading-indicator" style="display: none;">{{__('Loading...')}}</div>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group">
                                                            <label for="plot_number">{{ __('Plot Number') }}:</label>
                                                            <input type="text" class="form-control" name="plot_number" id="plot_number" value="{{ old('plot_number', $property->address->plot_number ?? '') }}" required>
                                                        </div>
                                                    </div>
                                                </div>

                                                <div class="row">
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            @php
                                                                $fa = optional($property->address)->full_address;
                                                                $fullAddressVal = old('full_address');
                                                                if ($fullAddressVal === null && $fa !== null) {
                                                                    $fullAddressVal = is_string($fa) ? $fa : (is_array($fa) ? ($fa[app()->getLocale()] ?? $fa['en'] ?? $fa['ar'] ?? '') : '');
                                                                }
                                                            @endphp
                                                            <label class="form-label" for="full_address">{{ __('Full Address') }}</label>
                                                            <input type="text" class="form-control" name="full_address" id="full_address" value="{{ $fullAddressVal ?? '' }}" placeholder="{{ __('Full Address') }}">
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="latitude">{{ __('Latitude') }}</label>
                                                            <input type="text" class="form-control" name="latitude" id="latitude" value="{{ old('latitude', optional($property->address)->latitude ?? '') }}" placeholder="{{ __('Ex: 1.462260') }}">
                                                            <a class="form-hint lat-long-link" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">{{ __('Go here to get Latitude from address.') }}</a>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-4">
                                                        <div class="form-group mb-3">
                                                            <label class="form-label" for="longitude">{{ __('Longitude') }}</label>
                                                            <input type="text" class="form-control" name="longitude" id="longitude" value="{{ old('longitude', optional($property->address)->longitude ?? '') }}" placeholder="{{ __('Ex: 1.462260') }}">
                                                            <a class="form-hint lat-long-link" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow">{{ __('Go here to get Longitude from address.') }}</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <!-- /Images -->
                                </div>
                                <div class="row mt-2">
                                    <!-- Price -->
                                    <div class="col-12">
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Price')}}</h6>
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
                                                            <input type="text" class="form-control" value="{{ __('Jordanian Dinar') }} (JOD)" readonly>
                                                            <input type="hidden" name="currency" value="JOD">
                                                            <small class="form-hint">{{ __('Properties in Jordan use Jordanian Dinar only.') }}</small>
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
                                        <div class="card widget-box-2">
                                            <h6 class="title">{{ __('Categories') }}</h6>
                                            <div class="card-body">
                                                <div class="form-group mb-0">
                                                    <label class="form-label">{{ __('Property Type') }}</label>
                                                    <select name="category_id" id="category_id" class="form-control select2">
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
                                        <div class="card widget-box-2">
                                            <h6 class="title">{{ __('Property Details') }} <span class="text-muted">({{ __('by property type') }})</span></h6>
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
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Distance key between facilities')}}</h6>
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
                                                                                        <select name="facilities[{{$loop->index}}][facility_id]" required class="form-control form-select facility-select">
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
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('status')}}</h6>
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <select name="status" id="status" class="form-select">
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
                                    <div class="col-12 mt-2">
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Video URL')}}</h6>
                                            <div class="card-body">
                                                <div class="form-group  ">
                                                    <input type="text" class="form-control style-1" name="video_url" id="video_url" placeholder="{{__('https://youtu.be/xxxx')}}" value="{{ old('video_url', $property->more_info->video_url ?? '') }}">
                                                    <small class="form-hint"> {{__('Use the Youtube video link to be able to watch the video directly on the website.')}} </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    @php
                                                $hasReceipt = !empty($property->featured_listing_receipt);
                                                $hasUntil = !empty($property->featured_listing_until);
                                                $untilFuture = $hasUntil && \Carbon\Carbon::parse($property->featured_listing_until)->isFuture();
                                                $isPending = $hasReceipt && !$hasUntil && $property->is_featured;
                                                $isRejected = $hasReceipt && !$hasUntil && !$property->is_featured;
                                                $isActive = $hasReceipt && $untilFuture;
                                                $isExpired = $hasReceipt && $hasUntil && !$untilFuture;
                                                $canSubmitNew = !$isPending;
                                            @endphp
                                    <div class="col-12 mt-2">
                                        <div class="card widget-box-2 border-primary">
                                            <h6 class="title text-primary">{{ __('Featured Listing') }}</h6>
                                            <div class="card-body">
                                                <p class="small text-muted mb-2">{{ __('Show your listing first. Monthly fee until sold.') }}</p>
                                                <p class="mb-2"><strong>{{ __('Monthly subscription fee') }}:</strong> {{ $data_settings['currency'] ?? 'JOD' }} {{ $data_settings['featured_listing_price'] ?? '50' }} / {{ __('month') }}</p>
                                                @if($isPending)
                                                    <p class="text-warning mb-2"><strong>{{ __('Request under review') }}</strong> - {{ __('Pending admin approval.') }}</p>
                                                    <p class="small text-muted">{{ __('You cannot submit another request until the current one is approved or rejected.') }}</p>
                                                @elseif($isRejected)
                                                    <p class="text-danger mb-2"><strong>{{ __('Rejected') }}</strong></p>
                                                @elseif($isActive)
                                                    <p class="text-success mb-2"><strong>{{ __('Active until') }}: {{ $property->featured_listing_until }}</strong></p>
                                                @elseif($isExpired)
                                                    <p class="text-muted mb-2">{{ __('Expired. Renew below.') }}</p>
                                                @endif
                                                @if(!$isPending && !$isActive)
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="featured_listing" id="featured_listing" value="1" {{ $isExpired ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="featured_listing">{{ __('Enable Featured Listing') }}</label>
                                                </div>
                                                <div id="featured_listing_receipt_box" style="display: {{ $isExpired ? 'block' : 'none' }};">
                                                    <div class="form-group">
                                                        <label class="form-label" for="featured_listing_receipt">{{ __('Payment receipt') }} <span class="text-muted">({{ __('renewal or new') }})</span></label>
                                                        <input type="file" class="form-control style-1" name="featured_listing_receipt" id="featured_listing_receipt" accept="image/*,.pdf">
                                                        <small class="form-hint">{{ __('Upload proof of payment. Leave empty to keep current.') }}</small>
                                                    </div>
                                                </div>
                                                @else
                                                    <input type="hidden" name="featured_listing" value="1">
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @php
                                                $has3dReceipt = !empty($property->featured_3d_tour_receipt);
                                                $has3dUntil = !empty($property->featured_3d_tour_until);
                                                $until3dFuture = $has3dUntil && \Carbon\Carbon::parse($property->featured_3d_tour_until)->isFuture();
                                                $is3dPending = $has3dReceipt && !$has3dUntil && $property->is_3d_tour_featured;
                                                $is3dRejected = $has3dReceipt && !$has3dUntil && !$property->is_3d_tour_featured;
                                                $is3dActive = $has3dReceipt && $until3dFuture;
                                                $is3dExpired = $has3dReceipt && $has3dUntil && !$until3dFuture;
                                            @endphp
                                    <div class="col-12 mt-2">
                                        <div class="card widget-box-2 border-primary">
                                            <h6 class="title text-primary">{{ __('3D Tour') }}</h6>
                                            <div class="card-body">
                                                <p class="small text-muted mb-2">{{ __('Add a 3D virtual tour to your property. Monthly fee until sold.') }}</p>
                                                <p class="mb-2"><strong>{{ __('Monthly subscription fee') }}:</strong> {{ $data_settings['currency'] ?? 'JOD' }} {{ $data_settings['featured_3d_tour_price'] ?? '30' }} / {{ __('month') }}</p>
                                                @if($is3dPending)
                                                    <p class="text-warning mb-2"><strong>{{ __('Request under review') }}</strong> - {{ __('Pending admin approval.') }}</p>
                                                    <p class="small text-muted">{{ __('You cannot submit another request until the current one is approved or rejected.') }}</p>
                                                @elseif($is3dRejected)
                                                    <p class="text-danger mb-2"><strong>{{ __('Rejected') }}</strong></p>
                                                @elseif($is3dActive)
                                                    <p class="text-success mb-2"><strong>{{ __('Active until') }}: {{ $property->featured_3d_tour_until }}</strong></p>
                                                @elseif($is3dExpired)
                                                    <p class="text-muted mb-2">{{ __('Expired. Renew below.') }}</p>
                                                @endif
                                                @if(!$is3dPending && !$is3dActive)
                                                <div class="form-check form-switch mb-3">
                                                    <input class="form-check-input" type="checkbox" name="featured_3d_tour" id="featured_3d_tour" value="1" {{ $is3dExpired ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="featured_3d_tour">{{ __('Enable Featured 3D Tour') }}</label>
                                                </div>
                                                <div id="featured_3d_tour_receipt_box" style="display: {{ $is3dExpired ? 'block' : 'none' }};">
                                                    <div class="form-group">
                                                        <label class="form-label" for="featured_3d_tour_receipt">{{ __('Payment receipt') }} <span class="text-muted">({{ __('renewal or new') }})</span></label>
                                                        <input type="file" class="form-control style-1" name="featured_3d_tour_receipt" id="featured_3d_tour_receipt" accept="image/*,.pdf">
                                                        <small class="form-hint">{{ __('Upload proof of payment. Leave empty to keep current.') }}</small>
                                                    </div>
                                                </div>
                                                @else
                                                    <input type="hidden" name="featured_3d_tour" value="1">
                                                @endif
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
@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->

    <script src="{{asset('site/select2/select2.full.min.js')}}"></script>

    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>

    <script src="{{asset('site/quill/quill.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
    <!-- Page JS -->
    <!-- END: Page JS-->
    <script src="{{asset('site/dropzone/dropzone.js')}}"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nameInput = document.querySelector('input[name="name"]');
            const slugInput = document.querySelector('input[name="slug"]');
            const checkIcon = document.querySelector('#slug-feedback .fa-check');
            const falseIcon = document.querySelector('#slug-feedback .fa-times');
            const loadingSpinner = document.getElementById('loading-spinner');

            nameInput.addEventListener('input', function () {
                const name = nameInput.value;
                if (name) {
                    // Show the loading spinner
                    loadingSpinner.classList.remove('d-none');

                    fetch('{{ route('admin.properties.generate.slug') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                        },
                        body: JSON.stringify({ name: name, lang: 'ar' }) // Assuming Arabic
                    })
                        .then(response => response.json())
                        .then(data => {
                            slugInput.value = data.slug;

                            // Hide the loading spinner
                            loadingSpinner.classList.add('d-none');

                            // Keep the slug input read-only after generation
                            slugInput.readOnly = true;

                            // Show the check icon if the slug is valid
                            slugInput.style.borderColor = 'green';
                            checkIcon.classList.remove('d-none');
                            falseIcon.classList.add('d-none');
                        })
                        .catch(() => {
                            // Hide the loading spinner
                            loadingSpinner.classList.add('d-none');

                            // Allow editing if there's an issue
                            slugInput.readOnly = false;

                            // Show the error icon if there's an issue
                            slugInput.style.borderColor = 'red';
                            checkIcon.classList.add('d-none');
                            falseIcon.classList.remove('d-none');
                        });
                } else {
                    slugInput.value = '';
                    loadingSpinner.classList.add('d-none');
                    checkIcon.classList.add('d-none');
                    falseIcon.classList.add('d-none');
                }
            });
        });




    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var typeSelect = document.getElementById('type');
            var periodDiv = document.getElementById('period').closest('.col-md-4');
            var neverExpiredDiv = document.getElementById('never_expired').closest('.col-md-6');
            var autoRenewDiv = document.getElementById('auto_renew').closest('.form-group');

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


    <script type="text/javascript">
        $(document).ready(function() {
            // Initialize Select2 elements
            $('.select2').select2({
                theme: 'bootstrap-5',
            });

            // Initialize jQuery Validation for the form
            $('#mainAdd').validate({
                rules: {
                    name: {
                        required: true
                    },
                    slug: {
                        required: true
                    },
                    contact_name: {
                        required: true
                    },
                    contact_phone: {
                        required: true
                    },
                    registration_document: {
                        required: false // Optional in edit
                    },
                    governorate_id: {
                        required: true
                    },
                    department_id: {
                        required: true
                    },
                    village_id: {
                        required: true
                    },
                    hod_id: {
                        required: true
                    },
                    hay_id: {
                        required: true
                    },
                    plot_number: {
                        required: true
                    },
                    featured_listing_receipt: {
                        required: function() {
                            return $('#featured_listing').is(':checked');
                        }
                    }
                },
                messages: {
                    name: {
                        required: "{{ __('Name is required') }}"
                    },
                    slug: {
                        required: "{{ __('Permalink is required') }}"
                    },
                    contact_name: {
                        required: "{{ __('Person Name is required') }}"
                    },
                    contact_phone: {
                        required: "{{ __('Phone Number is required') }}"
                    },
                    registration_document: {
                        required: "{{ __('Registration document is required') }}"
                    },
                    governorate_id: {
                        required: "{{ __('Please select Governorate') }}"
                    },
                    department_id: {
                        required: "{{ __('Please select Department') }}"
                    },
                    village_id: {
                        required: "{{ __('Please select Village') }}"
                    },
                    hod_id: {
                        required: "{{ __('Please select Hod') }}"
                    },
                    hay_id: {
                        required: "{{ __('Please select Hay') }}"
                    },
                    plot_number: {
                        required: "{{ __('Plot Number is required') }}"
                    },
                    featured_listing_receipt: {
                        required: "{{ __('Payment receipt is required when Featured Listing is enabled') }}"
                    }
                },
                errorElement: 'div',
                errorPlacement: function(error, element) {
                    error.addClass('invalid-feedback');
                    error.css('display', 'block');
                    error.css('color', '#dc3545');
                    error.css('font-size', '0.875rem');
                    error.css('margin-top', '0.25rem');
                    
                    // Handle Select2 fields
                    if (element.hasClass('select2-hidden-accessible')) {
                        error.insertAfter(element.next('.select2-container'));
                    } 
                    // Handle input-group fields
                    else if (element.closest('.input-group').length) {
                        error.insertAfter(element.closest('.input-group'));
                    }
                    // Handle regular form-group fields
                    else if (element.closest('.form-group').length) {
                        error.insertAfter(element);
                    } 
                    // Default fallback
                    else {
                        error.insertAfter(element);
                    }
                },
                highlight: function(element) {
                    $(element).addClass('is-invalid').removeClass('is-valid');
                    $(element).closest('.form-group').addClass('has-error');
                    
                    // Handle Select2 styling
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').find('.select2-selection').addClass('is-invalid');
                    }
                },
                unhighlight: function(element) {
                    $(element).removeClass('is-invalid').addClass('is-valid');
                    $(element).closest('.form-group').removeClass('has-error');
                    
                    // Handle Select2 styling
                    if ($(element).hasClass('select2-hidden-accessible')) {
                        $(element).next('.select2-container').find('.select2-selection').removeClass('is-invalid');
                    }
                }
            });

            // Trigger validation on Select2 change
            $('.select2').on('change', function() {
                $(this).valid();
            });

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

            // Jordan location hierarchy
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
                    $('#governorate').empty().append('<option value="">' + {!! json_encode(__('Select Governorate')) !!} + '</option>');
                    $.each(data, function(i, item) { $('#governorate').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                    if (govId) $('#governorate').val(govId);
                    if (cb) cb();
                }).fail(hideAddressLoaderOnce);
            }
            loadGovernorates(function() {
                if (!govId) { hideAddressLoaderOnce(); return; }
                $.get('{{ route('admin.jordan.departments', '') }}/' + govId).done(function(data) {
                    $('#department').empty().append('<option value="">' + {!! json_encode(__('Select Department')) !!} + '</option>');
                    $.each(data, function(i, item) { $('#department').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                    if (deptId) $('#department').val(deptId);
                    if (!deptId) { hideAddressLoaderOnce(); return; }
                    $.get('{{ route('admin.jordan.villages', '') }}/' + deptId).done(function(data2) {
                        $('#village').empty().append('<option value="">' + {!! json_encode(__('Select Village')) !!} + '</option>');
                        $.each(data2, function(i, item) { $('#village').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                        if (villId) $('#village').val(villId);
                        if (!villId) { hideAddressLoaderOnce(); return; }
                        $.get('{{ url('/jordan/hods') }}/' + deptId + '/' + villId).done(function(data3) {
                            $('#hod').empty().append('<option value="">' + {!! json_encode(__('Select Hod')) !!} + '</option>');
                            $.each(data3, function(i, item) { $('#hod').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                            if (hodId) $('#hod').val(hodId);
                            if (!hodId) { hideAddressLoaderOnce(); return; }
                            $.get('{{ url('/jordan/hays') }}/' + deptId + '/' + villId + '/' + hodId)
                                .done(function(data4) {
                                    var $hay = $('#hay');
                                    try { if ($hay.hasClass('select2-hidden-accessible')) $hay.select2('destroy'); } catch(e) {}
                                    $hay.empty().append('<option value="">' + {!! json_encode(__('Select Hay')) !!} + '</option>');
                                    if (Array.isArray(data4)) { $.each(data4, function(i, item) { $hay.append('<option value="' + String(item.id) + '">' + item.name + '</option>'); }); }
                                    var valToSet = (hayId !== '' && hayId !== undefined && hayId !== null) ? ((hayId === 0 || hayId === '0') ? '0' : String(hayId)) : '';
                                    if (valToSet !== '') {
                                        $hay.val(valToSet);
                                        $hay.find('option').prop('selected', false).filter('[value="' + valToSet + '"]').prop('selected', true);
                                    }
                                    $hay.select2({ theme: 'bootstrap-5' });
                                    if (valToSet !== '') { $hay.val(valToSet).trigger('change'); }
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
                $('#department, #village, #hod, #hay').empty().append('<option value="">' + {!! json_encode(__('Select')) !!} + '</option>');
                if (id) $.get('{{ route('admin.jordan.departments', '') }}/' + id, function(data) {
                    $('#department').empty().append('<option value="">' + {!! json_encode(__('Select Department')) !!} + '</option>');
                    $.each(data, function(i, item) { $('#department').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
            });
            $('#department').change(function() {
                var id = $(this).val();
                $('#village, #hod, #hay').empty().append('<option value="">' + {!! json_encode(__('Select')) !!} + '</option>');
                if (id) $.get('{{ route('admin.jordan.villages', '') }}/' + id, function(data) {
                    $('#village').empty().append('<option value="">' + {!! json_encode(__('Select Village')) !!} + '</option>');
                    $.each(data, function(i, item) { $('#village').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
            });
            $('#village').change(function() {
                var d = $('#department').val(), v = $(this).val();
                $('#hod, #hay').empty().append('<option value="">' + {!! json_encode(__('Select')) !!} + '</option>');
                if (d && v) $.get('{{ url('/jordan/hods') }}/' + d + '/' + v, function(data) {
                    $('#hod').empty().append('<option value="">' + {!! json_encode(__('Select Hod')) !!} + '</option>');
                    $.each(data, function(i, item) { $('#hod').append('<option value="' + item.id + '">' + item.name + '</option>'); });
                });
            });
            $('#hod').change(function() {
                var d = $('#department').val(), v = $('#village').val(), h = $(this).val();
                $('#hay').empty().append('<option value="">' + {!! json_encode(__('Select')) !!} + '</option>');
                if (d && v && h) $.get('{{ url('/jordan/hays') }}/' + d + '/' + v + '/' + h, function(data) {
                    $('#hay').empty().append('<option value="">' + {!! json_encode(__('Select Hay')) !!} + '</option>');
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
                $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">');
                uploadedDocumentMap[file.name] = response.name;
                
                // Clear images validation error when at least one image is uploaded
                if ($('input[name="images[]"]').length > 0) {
                    $('#dpz-multiple-files').removeClass('is-invalid');
                    $('#dpz-multiple-files').css({
                        'border': '',
                        'background-color': ''
                    });
                    $('#dpz-multiple-files').siblings('.custom-error').remove();
                    $('#dpz-multiple-files').closest('.card-body').find('.custom-error').remove();
                    $('#dpz-multiple-files').closest('.card').find('.title').css({
                        'color': '',
                        'font-weight': ''
                    });
                }
            },
            removedfile: function(file) {
                console.log(file.file_name)
                console.log(file.name)
                file.previewElement.remove(); // Remove the file preview from the UI

                var name = '';
                if (typeof file.file_name !== 'undefined') {
                    name = file.file_name; // Use file name if it's already defined
                } else {
                    name = uploadedDocumentMap[file.name]; // Otherwise, get it from the map
                }

                // Remove the corresponding hidden input
                $('form').find('input[type="hidden"][value="' + name + '"]').remove();
                delete uploadedDocumentMap[file.name]; // Also remove from the map
            },

            // previewsContainer: "#dpz-btn-select-files", // Define the container to display the previews

            init: function() {
                var existingImages = {!! json_encode($property->images->map(function($img) {
                    $path = $img->img;
                    // Fix path: remove /public if exists at the beginning
                    $path = preg_replace('#^/public#', '', $path ?? '');
                    $path = ltrim($path, '/');
                    // Create correct URL using asset() - it will add /public/ automatically
                    $imagePath = asset($path);
                    // Fix double /public/public/ if exists
                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                    return ['img' => $img->img, 'img_url' => $correctedImagePath];
                })->values()) !!};
                for (var i in existingImages) {
                    var file = existingImages[i];
                    var thumbUrl = file.img_url || '';
                    if (!thumbUrl && file.img) {
                        // Fallback: fix path manually
                        var imgPath = file.img.replace(/^\/?public\/?/, '').replace(/^\/+/, '');
                        thumbUrl = '{{ url("/") }}/' + imgPath;
                        // Fix double /public/ if exists
                        thumbUrl = thumbUrl.replace('/public/public/', '/public/');
                    }
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
                    reader.readAsDataURL(files[0]);
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

            const fullEditor = new Quill('#full-editor', {
                bounds: '#full-editor',
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });

            // Load existing content into Quill editor
            var existingContent = $('#full-editor').html().trim();
            if (existingContent && existingContent !== '<p><br></p>') {
                fullEditor.root.innerHTML = existingContent;
            }

            $('#featured_listing').on('change', function() {
                $('#featured_listing_receipt_box').toggle(this.checked);
                if (!this.checked) {
                    $('#featured_listing_receipt').val('');
                    $('#featured_listing_receipt').valid();
                } else {
                    $('#featured_listing_receipt').valid();
                }
            });
            $('#featured_3d_tour').on('change', function() {
                $('#featured_3d_tour_receipt_box').toggle(this.checked);
                if (!this.checked) {
                    $('#featured_3d_tour_receipt').val('');
                }
            });

            // Custom validation method for images
            $.validator.addMethod("imagesRequired", function(value, element) {
                var imageInputs = $('input[name="images[]"]');
                return imageInputs.length > 0;
            }, {!! json_encode(__('Images are required')) !!});

            $('#mainAdd').submit(function(e) {
                e.preventDefault();

                // Clear previous images errors
                $('#dpz-multiple-files').removeClass('is-invalid');
                $('#dpz-multiple-files').css({
                    'border': '',
                    'background-color': ''
                });
                $('#dpz-multiple-files').siblings('.custom-error').remove();
                $('#dpz-multiple-files').closest('.card-body').find('.custom-error').remove();
                $('#dpz-multiple-files').closest('.card').find('.title').css({
                    'color': '',
                    'font-weight': ''
                });

                // Validate images before form validation
                var imageInputs = $('input[name="images[]"]');
                var hasImages = imageInputs.length > 0;
                
                if (!hasImages) {
                    // Show error on Dropzone
                    var errorText = {!! json_encode(__('Images are required')) !!};
                    var error_message = '<div class="custom-error"><p>' + errorText + '</p></div>';
                    
                    // Remove existing errors first
                    $('#dpz-multiple-files').siblings('.custom-error').remove();
                    $('#dpz-multiple-files').closest('.col-lg-12').find('.custom-error').remove();
                    $('#dpz-multiple-files').closest('.form-group').find('.custom-error').remove();
                    $('#dpz-multiple-files').closest('.card-body').find('.custom-error').remove();
                    
                    // Add error message after the col-lg-12 div (which contains Dropzone)
                    var colDiv = $('#dpz-multiple-files').closest('.col-lg-12');
                    if (colDiv.length) {
                        colDiv.after(error_message);
                    } else {
                        // Fallback: add after form-group
                        var formGroup = $('#dpz-multiple-files').closest('.form-group');
                        if (formGroup.length) {
                            formGroup.after(error_message);
                        } else {
                            $('#dpz-multiple-files').after(error_message);
                        }
                    }
                    
                    // Also add at end of card-body for extra visibility
                    var imagesCard = $('#dpz-multiple-files').closest('.card-body');
                    if (imagesCard.length) {
                        // Check if error already exists in card-body
                        if (!imagesCard.find('.custom-error').length) {
                            imagesCard.append(error_message.clone());
                        }
                    }
                    
                    // Add visual styling
                    $('#dpz-multiple-files').addClass('is-invalid');
                    $('#dpz-multiple-files').css({
                        'border': '2px solid #dc3545',
                        'border-radius': '4px',
                        'background-color': '#fff5f5'
                    });
                    
                    // Highlight title
                    var titleElement = $('#dpz-multiple-files').closest('.card').find('.title');
                    if (titleElement.length) {
                        titleElement.css({
                            'color': '#dc3545',
                            'font-weight': '600'
                        });
                    }
                    
                    // Scroll to images section
                    $('html, body').animate({
                        scrollTop: $('#dpz-multiple-files').offset().top - 150
                    }, 500);
                    
                    // Show toast notification
                    toastr.error({!! json_encode(__('Please upload at least one image')) !!}, {!! json_encode(__('Validation Error')) !!}, {
                        closeButton: true,
                        timeOut: 5000
                    });
                    
                    return false;
                }

                // Validate other form fields
                if (!$(this).valid()) {
                    return false;
                }

                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var postData = new FormData(this);
                    var quillContent = fullEditor.root.innerHTML;
                    postData.append('content', quillContent);

                    $('#add_form').html('');
                    $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">' + {!! json_encode(__('Saving')) !!} + '...</span>');

                    $.ajax({
                        url: '{{ route('user.properties.update',$property->id) }}',
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#add_form').html({!! json_encode(__('Save')) !!});
                            
                            // Show success message
                            toastr.success(response.success || {!! json_encode(__('Property updated successfully')) !!}, {
                                closeButton: true,
                                tapToDismiss: false,
                                timeOut: 2000
                            });
                            
                            // Redirect to properties list after 1.5 seconds
                            setTimeout(function() {
                                window.location.href = '{{ route('user.properties.index') }}';
                            }, 1500);
                        },
                        error: function(xhr, status, error) {
                            $('.custom-error').remove();
                            $('.invalid-feedback').remove();
                            $('#general-errors').remove();
                            $('#add_form').html({!! json_encode(__('Save')) !!});
                            
                            var response = xhr.responseJSON;
                            
                            if (xhr.status == 422) {
                                if (response && response.errors) {
                                    $.each(response.errors, function(key, value) {
                                        var errorText = Array.isArray(value) ? value[0] : value;
                                        var error_message = '<div class="custom-error"><p>' + errorText + '</p></div>';
                                        
                                        // Handle special case for images field (Dropzone)
                                        if (key === 'images') {
                                            // Remove any existing error messages for images
                                            $('#dpz-multiple-files').siblings('.custom-error').remove();
                                            $('#dpz-multiple-files').closest('.col-lg-12').find('.custom-error').remove();
                                            $('#dpz-multiple-files').closest('.form-group').find('.custom-error').remove();
                                            $('#dpz-multiple-files').closest('.card-body').find('.custom-error').remove();
                                            
                                            // Add error message after the col-lg-12 div (which contains Dropzone)
                                            var colDiv = $('#dpz-multiple-files').closest('.col-lg-12');
                                            if (colDiv.length) {
                                                colDiv.after(error_message);
                                            } else {
                                                var formGroup = $('#dpz-multiple-files').closest('.form-group');
                                                if (formGroup.length) {
                                                    formGroup.after(error_message);
                                                } else {
                                                    $('#dpz-multiple-files').after(error_message);
                                                }
                                            }
                                            
                                            // Also add at end of card-body
                                            var imagesCard = $('#dpz-multiple-files').closest('.card-body');
                                            if (imagesCard.length) {
                                                if (!imagesCard.find('.custom-error').length) {
                                                    imagesCard.append(error_message.clone());
                                                }
                                            }
                                            
                                            // Add visual styling
                                            $('#dpz-multiple-files').addClass('is-invalid');
                                            $('#dpz-multiple-files').css({
                                                'border': '2px solid #dc3545',
                                                'border-radius': '4px',
                                                'background-color': '#fff5f5'
                                            });
                                            
                                            // Highlight title
                                            var titleElement = $('#dpz-multiple-files').closest('.card').find('.title');
                                            if (titleElement.length) {
                                                titleElement.css({
                                                    'color': '#dc3545',
                                                    'font-weight': '600'
                                                });
                                            }
                                        }
                                        // Handle regular input fields
                                        else if ($('[name="' + key + '"]').length) {
                                            var field = $('[name="' + key + '"]');
                                            var formGroup = field.closest('.form-group');
                                            
                                            if (formGroup.length) {
                                                formGroup.append(error_message);
                                            } else if (field.closest('.input-group').length) {
                                                field.closest('.input-group').after(error_message);
                                            } else {
                                                field.after(error_message);
                                            }
                                            field.addClass('is-invalid');
                                            
                                            // Handle Select2 fields
                                            if (field.hasClass('select2-hidden-accessible')) {
                                                field.next('.select2-container').find('.select2-selection').addClass('is-invalid');
                                            }
                                        }
                                        // Handle fields that might not exist (show general error)
                                        else {
                                            if ($('#general-errors').length === 0) {
                                                $('#mainAdd').prepend('<div id="general-errors" class="alert alert-danger" role="alert"><strong>' + {!! json_encode(__('Please fix the following errors:')) !!} + '</strong></div>');
                                            }
                                            $('#general-errors').append('<p style="margin-bottom: 5px; margin-left: 10px;">' + errorText + '</p>');
                                        }
                                    });
                                    
                                    // Scroll to first error
                                    var imagesError = $('#dpz-multiple-files').siblings('.custom-error').first();
                                    var firstError = imagesError.length ? imagesError : $('.custom-error').first();
                                    
                                    if (firstError.length) {
                                        $('html, body').animate({
                                            scrollTop: firstError.offset().top - 150
                                        }, 500);
                                    } else if ($('#general-errors').length) {
                                        $('html, body').animate({
                                            scrollTop: $('#general-errors').offset().top - 100
                                        }, 500);
                                    }
                                    
                                    // Show toast notification
                                    toastr.error({!! json_encode(__('Please check the form for errors')) !!}, {!! json_encode(__('Validation Error')) !!}, {
                                        closeButton: true,
                                        timeOut: 5000
                                    });
                                } else {
                                    toastr.error({!! json_encode(__('Please fill in all required fields correctly.')) !!}, {!! json_encode(__('Validation Error')) !!}, {
                                        closeButton: true,
                                        timeOut: 5000
                                    });
                                }
                            } else {
                                // Show general error message
                                var errorMsg = {!! json_encode(__('An error occurred. Please try again.')) !!};
                                if (response && response.message) {
                                    errorMsg = response.message;
                                }
                                Swal.fire({
                                    icon: 'error',
                                    title: {!! json_encode(__('Error')) !!},
                                    text: errorMsg
                                });
                            }
                        }
                    });
                }
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
