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
                                                            <input type="text" class="form-control style-1 style-1" name="name" value="{{ old('title', $property->title ?? '') }}" id="name" placeholder="{{__('Name')}}" required>
                                                        </div>
                                                    </div>

                                                    <div class="col-md-12">
                                                        <div class="form-group box-fieldset">
                                                            <label class="form-label" for="slug">{{ __('Permalink') }}
                                                                <span>*</span>
                                                            </label>
                                                            <div class="input-group input-group-merge">
                                                                <span class="input-group-text" id="slug">{{ config('app.url') }}/property/</span>
                                                                <input type="text" id="slug" name="slug" class="form-control" value="{{ old('slug', $property->slug ?? '') }}" aria-describedby="slug" readonly>
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
                                                    <div class="col-12  ">
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

                                            <h6 class="title">{{__('Address')}}</h6>
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
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Additional Information')}}</h6>
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
                                        <div class="card widget-box-2">

                                             <h6 class="title">{{__('Amenities & Features')}}<span>*</span></h6>

                                            <div class="card-body">
                                                <div class="row box-amenities-property">
                                                    @php
                                                        // Collect feature IDs from property
                                                        $selectedFeatureIds = $property->features->pluck('feature_id')->toArray();
                                                    @endphp

                                                    @foreach($feature_categories as $f_category)
                                                        @if($f_category->features->isNotEmpty())
                                                            <div class="col-md-4 box-amenities">
                                                                <small class="title-amenities fw-7">{{ $f_category->name }}</small>
                                                                @foreach($f_category->features as $feature)
                                                                    <div class="amenities-item">
                                                                        <input class="tf-checkbox style-1 primary" type="checkbox"
                                                                               value="{{ $feature->id }}" id="defaultCheck{{ $feature->id }}"
                                                                               name="property_features[]"
                                                                            {{ in_array($feature->id, $selectedFeatureIds) ? 'checked' : '' }}>
                                                                        <label class="text-cb-amenities" for="defaultCheck{{ $feature->id }}">
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

                                            <h6 class="title">{{__('Categories')}}</h6>
                                            <div class="card-body">
                                                <div class="form-group mb-3">
                                                    <select name="category_id" id="category_id" class="form-select select2">

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
                                        <div class="card widget-box-2">

                                            <h6 class="title">{{__('Video URL')}}</h6>
                                            <div class="card-body">
                                                <div class="form-group  ">
                                                    <input type="text" class="form-control style-1" name="video_url" id="video_url" placeholder="{{__('https://youtu.be/xxxx')}}" >
                                                    <small class="form-hint"> {{__('Use the Youtube video link to be able to watch the video directly on the website.')}} </small>
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
            // Initialize Select2 elements
            $('.select2').select2({
                theme: 'bootstrap-5',
            });
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
                $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">');
                uploadedDocumentMap[file.name] = response.name;
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

            $('#add_form').click(function(e) {

                var form = $(this.form);

                if (!form.valid()) {
                    return false;
                }
                if (form.valid()) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var postData = new FormData(this.form);
                    var quillContent = fullEditor.root.innerHTML;
                    postData.append('content', quillContent);

                    $('#add_form').html('');
                    $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{ __('Saving') }}...</span>');

                    $.ajax({
                        url: '{{ route('user.properties.update',$property->id) }}',
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#add_form').html('{{ __('Save') }}');
                            setTimeout(function () {
                                toastr.success(response.success, {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            }, 200);
                            // $('#mainAdd')[0].reset();
                            $('.custom-error').remove();
                            // fullEditor.root.innerHTML = '';
                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#add_form').html('{{ __('Save') }}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (response && response.errors) {
                                    $.each(response.errors, function (key, value) {
                                        var error_message = '<div class="custom-error"><p style="color: red">' + value[0] + '</p></div>';
                                        $('[name="' + key + '"]').closest('.form-group').append(error_message);
                                    });
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
