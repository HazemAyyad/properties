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
                        <a href="{{route('admin.properties.index',0)}}">{{__('Properties')}}</a>
                    </li>
                    <li class="breadcrumb-item active">{{__('Create Property')}}</li>
                    <!-- Basic table -->


                    <!--/ Basic table -->
                </ol>
            </nav>
            <div class="row">
                <div class="col-xl">
                    <div class="card mb-4">
                        <div class="card-header d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">{{__('Create Property')}}</h5>
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
                                                                                        <input type="text" class="form-control" name="name[{{ $locale }}]" id="name_{{ $locale }}" placeholder="{{ __('Name in ') . strtoupper($locale) }}" required>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="description_{{ $locale }}">{{ __('Description') }} ({{ strtoupper($locale) }})</label>
                                                                                        <textarea class="form-control" name="description[{{ $locale }}]" id="description_{{ $locale }}" rows="5" required></textarea>
                                                                                    </div>

                                                                                    <div class="form-group">
                                                                                        <label class="form-label" for="content_{{ $locale }}">{{ __('Content') }} ({{ strtoupper($locale) }})</label>
                                                                                        <div id="editor-container-{{ $locale }}" class="editor-container">
                                                                                            <!-- Quill editor will be initialized here -->
                                                                                        </div>
                                                                                        <textarea name="content[{{ $locale }}]" id="content_{{ $locale }}" class="d-none"></textarea>
                                                                                    </div>
                                                                                    <div class="col-md-12">
                                                                                        <label class="form-label" for="slug_{{ $locale }}">{{ __('Permalink') }} ({{ strtoupper($locale) }})</label>
                                                                                        <div class="input-group input-group-merge">
                                                                                            <span class="input-group-text" id="slug_{{ $locale }}">{{ config('app.url') }}/property/</span>
                                                                                            <input type="text" id="slug_{{ $locale }}" name="slug[{{ $locale }}]"   class="form-control" aria-describedby="slug" readonly>
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





                                                            <div class="col-md-6 mt-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="contact_name">{{__('Person Name')}} <span>*</span></label>
                                                                    <input type="text" class="form-control" name="contact_name" id="contact_name" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-6 mt-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="contact_phone">{{__('Phone Number')}} <span>*</span></label>
                                                                    <input type="text" class="form-control" name="contact_phone" id="contact_phone" required>
                                                                </div>
                                                            </div>
                                                            <div class="col-12  mt-2">
                                                                <div class="form-group">
                                                                    <label class="form-label" for="type">{{__('Property Type')}}</label>

                                                                    <select name="type" id="type" class="form-select">
                                                                        <option value="0">{{__('For Rent')}}</option>
                                                                        <option value="1">{{__('For Sale')}}</option>
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
                                                    <h5 class="card-header">{{__('Address')}} - {{__('Jordan')}}</h5>
                                                    <div class="card-body">
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
                                                                    <select id="hod" required name="hod_id" class="form-control select2">
                                                                        <option value="">{{ __('Select Hod') }}</option>
                                                                    </select>
                                                                    <div id="hod-loading" class="loading-indicator" style="display: none;">{{__('Loading...')}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="hay">{{ __('Hay') }}:</label>
                                                                    <select id="hay" required name="hay_id" class="form-control select2">
                                                                        <option value="">{{ __('Select Hay') }}</option>
                                                                    </select>
                                                                    <div id="hay-loading" class="loading-indicator" style="display: none;">{{__('Loading...')}}</div>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="plot_number">{{ __('Plot Number') }}:</label>
                                                                    <input type="text" class="form-control" name="plot_number" id="plot_number" required>
                                                                </div>
                                                            </div>
                                                        </div>


                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label" for="full_address">{{__('Full Address')}}</label>
                                                                    <input type="text" required class="form-control" name="full_address" id="full_address" placeholder="{{__('Full Address')}}" >
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label" for="latitude">{{__('Latitude')}}</label>
                                                                    <input type="text" class="form-control" name="latitude" id="latitude" placeholder="{{__('Ex: 1.462260')}}" >
                                                                    <a class="form-hint" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow"> {{__('Go here to get Latitude from address.')}} </a>
                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label" for="longitude">{{__('Longitude')}}</label>
                                                                    <input type="text" class="form-control" name="longitude" id="longitude" placeholder="{{__('Ex: 1.462260')}}" >
                                                                    <a class="form-hint" href="https://www.latlong.net/convert-address-to-lat-long.html" target="_blank" rel="nofollow"> {{__('Go here to get Longitude from address.')}} </a>
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
                                                <div class="card">
                                                    <h5 class="card-header">{{__('Price')}}</h5>
                                                    <div class="card-body">
                                                        <div class="row">
                                                            <div class="col-md-4">
                                                                <div class="form-group mb-3">
                                                                    <label class="form-label" for="price">{{__('Price')}}</label>
                                                                    <input type="text" required class="form-control" name="price" id="price" placeholder="" >

                                                                </div>
                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="currency">{{__('Currency')}}:</label>
                                                                    <select id="currency" required name="currency" class="select2 form-select">
                                                                        @foreach($uniqueCurrencies as $currency)

                                                                            <option value="{{ $currency}}">{{$currency}}</option>
                                                                        @endforeach
                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-4">
                                                                <div class="form-group">
                                                                    <label for="period">{{__('Period')}}:</label>
                                                                    <select id="period" required name="period" class="form-control form-select">
                                                                        <option value="0">{{__('Daily') }}</option>
                                                                        <option value="1">{{__('Weekly') }}</option>
                                                                        <option value="2">{{__('Monthly') }}</option>
                                                                        <option value="3">{{__('Yearly') }}</option>
                                                                    </select>
                                                                </div>

                                                            </div>
                                                            <div class="col-md-6 p-6">
                                                                <label class="switch">
                                                                    <input type="checkbox" required name="never_expired" id="never_expired" class="switch-input" />
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
                                                                    <textarea name="private_notes" id="private_notes"  class="form-control" rows="3"></textarea>
                                                                    <small class="form-hint"> {{__("Private notes are only visible to owner. It won't be shown on the frontend")}}. </small>
                                                                </div>
                                                                <div class="form-group">
                                                                    <label class="switch">
                                                                        <input type="checkbox" name="auto_renew" id="auto_renew" class="switch-input" />
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
                                                                    <option value="{{ $category->id }}" data-slug="{{ $catSlug }}">{{ $category->name }}</option>
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
                                                            @include('user_dashboard.properties.partials.category_fields_apartment')
                                                        </div>
                                                        <div id="category-fields-villa" class="category-fields-section" data-category="villa" style="display:none;">
                                                            @include('user_dashboard.properties.partials.category_fields_villa')
                                                        </div>
                                                        <div id="category-fields-office" class="category-fields-section" data-category="office" style="display:none;">
                                                            @include('user_dashboard.properties.partials.category_fields_office')
                                                        </div>
                                                        <div id="category-fields-commercial" class="category-fields-section" data-category="commercial" style="display:none;">
                                                            @include('user_dashboard.properties.partials.category_fields_commercial')
                                                        </div>
                                                        <div id="category-fields-farm" class="category-fields-section" data-category="farm" style="display:none;">
                                                            @include('user_dashboard.properties.partials.category_fields_farm')
                                                        </div>
                                                        <div id="category-fields-land" class="category-fields-section" data-category="land" style="display:none;">
                                                            @include('user_dashboard.properties.partials.category_fields_land')
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

                                                                                <div data-repeater-item class="row mb-3  ">

                                                                                    <div class="col-md-4 ">
                                                                                        <div class="form-group ">
                                                                                            <select name="facility_id" required id="" class="form-select">
                                                                                                <option value="">{{__('Select Facilities')}}</option>
                                                                                                @foreach($facilities as $facility)
                                                                                                    <option value="{{$facility->id}}">{{$facility->name}}</option>

                                                                                                @endforeach
                                                                                            </select>


                                                                                        </div>

                                                                                    </div>
                                                                                    <div class="col-md-6 ">
                                                                                        <div class="form-group">

                                                                                            <input type="text"  required id="distance" value=""  name="distance" class="form-control"
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
                                                    <div class="card-header">{{__('status')}}</div>
                                                    <div class="card-body">
                                                        <div class="form-group mb-3">
                                                             <select name="status" required id="status" class="form-select">
                                                                <option value="0">{{__('Not available')}}</option>
                                                                <option value="1">{{__('Preparing selling')}}</option>
                                                                <option value="2">{{__('Selling')}}</option>
                                                                <option value="3">{{__('sold')}}</option>
                                                                <option value="4">{{__('Renting')}}</option>
                                                                <option value="5">{{__('Rented')}}</option>
                                                                <option value="6">{{__('Building')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="card">
                                                    <div class="card-header">{{__('Moderation status')}}</div>
                                                    <div class="card-body">
                                                        <div class="form-group mb-3">
                                                             <select name="moderation_status" required id="moderation_status" class="form-select">
                                                                <option value="0">{{__('Pending')}}</option>
                                                                <option value="1">{{__('Published')}}</option>
                                                                <option value="2">{{__('draft')}}</option>
                                                            </select>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 mt-2">
                                                <div class="card">
                                                    <div class="card-header">{{__('Video URL')}}</div>
                                                    <div class="card-body">
                                                        <div class="form-group  ">
                                                            <input type="text" class="form-control" name="video_url" id="video_url" placeholder="{{__('https://youtu.be/xxxx')}}" >
                                                            <small class="form-hint"> {{__('Use the Youtube video link to be able to watch the video directly on the website.')}} </small>
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
            // Initialize Select2 elements
            $('.select2').select2();

            // Show/hide category-specific fields when category changes
            function showCategoryFields() {
                var slug = $('#category_id option:selected').data('slug') || '';
                $('#category-fields-hint').toggle(!slug);
                $('.category-fields-section').hide();
                if (slug && $('#category-fields-' + slug).length) {
                    $('#category-fields-' + slug).show();
                }
            }
            $('#category_id').on('change', showCategoryFields);
            showCategoryFields();

            // Initialize Select2 for user list with AJAX and allowClear option
            $('#user-list').select2({
                placeholder: 'Search for a user',
                allowClear: true,
                minimumInputLength: 1,
                ajax: {
                    url: '{{ route('admin.get_users') }}',
                    dataType: 'json',
                    delay: 250,
                    data: function(params) {
                        $('#hint-message').hide();
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

            // Event handling for user-list
            $('#user-list').on('select2:open', function() {
                $('#hint-message').show();
            });

            $('#user-list').on('select2:close', function() {
                $('#hint-message').hide();
            });

            $('#user-list').on('select2:selecting', function(e) {
                let selectedUserName = e.params.args.data.text;
                $('#user-list').val(selectedUserName).trigger('change');
                $('#hint-message').hide();
            });

            // Jordan location hierarchy
            $.get('{{ route('admin.jordan.governorates') }}', function(data) {
                $('#governorate').empty().append('<option value="">{{__('Select Governorate')}}</option>');
                $.each(data, function(i, item) {
                    $('#governorate').append('<option value="' + item.id + '">' + item.name + '</option>');
                });
            });

            $('#governorate').change(function() {
                var id = $(this).val();
                $('#department, #village, #hod, #hay').empty().append('<option value="">{{__('Select')}}</option>');
                if (id) {
                    $('#department-loading').show();
                    $.get('{{ route('admin.jordan.departments', '') }}/' + id, function(data) {
                        $('#department').empty().append('<option value="">{{__('Select Department')}}</option>');
                        $.each(data, function(i, item) {
                            $('#department').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $('#department-loading').hide();
                    });
                } else $('#department-loading').hide();
            });

            $('#department').change(function() {
                var id = $(this).val();
                $('#village, #hod, #hay').empty().append('<option value="">{{__('Select')}}</option>');
                if (id) {
                    $('#village-loading').show();
                    $.get('{{ route('admin.jordan.villages', '') }}/' + id, function(data) {
                        $('#village').empty().append('<option value="">{{__('Select Village')}}</option>');
                        $.each(data, function(i, item) {
                            $('#village').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $('#village-loading').hide();
                    });
                } else $('#village-loading').hide();
            });

            $('#village').change(function() {
                var deptId = $('#department').val();
                var villId = $(this).val();
                $('#hod, #hay').empty().append('<option value="">{{__('Select')}}</option>');
                if (deptId && villId) {
                    $('#hod-loading').show();
                    $.get('{{ url('/jordan/hods') }}/' + deptId + '/' + villId, function(data) {
                        $('#hod').empty().append('<option value="">{{__('Select Hod')}}</option>');
                        $.each(data, function(i, item) {
                            $('#hod').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $('#hod-loading').hide();
                    });
                } else $('#hod-loading').hide();
            });

            $('#hod').change(function() {
                var deptId = $('#department').val();
                var villId = $('#village').val();
                var hodId = $(this).val();
                $('#hay').empty().append('<option value="">{{__('Select')}}</option>');
                if (deptId && villId && hodId) {
                    $('#hay-loading').show();
                    $.get('{{ url('/jordan/hays') }}/' + deptId + '/' + villId + '/' + hodId, function(data) {
                        $('#hay').empty().append('<option value="">{{__('Select Hay')}}</option>');
                        $.each(data, function(i, item) {
                            $('#hay').append('<option value="' + item.id + '">' + item.name + '</option>');
                        });
                        $('#hay-loading').hide();
                    });
                } else $('#hay-loading').hide();
            });
        });
    </script>




    <script>
        function readURL(input) {
            console.log(input.files);
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function(e) {
                    $(".img-preview").attr("src", e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        var uploadedDocumentMap = {};
        Dropzone.options.dpzMultipleFiles = {
            paramName: "dzfile", // The name that will be used to transfer the file
            maxFilesize: 5, // MB (0.5 MB)
            clickable: true,
            addRemoveLinks: true,
            acceptedFiles: 'image/*',
            dictFallbackMessage: "المتصفح الخاص بكم لا يدعم خاصيه تعدد الصوره والسحب والافلات",
            dictInvalidFileType: "لا يمكنك رفع هذا النوع من الملفات",
            dictCancelUpload: "الغاء الرفع",
            dictCancelUploadConfirmation: "هل انت متأكد من الغاء رفع الملفات؟",
            dictRemoveFile: "حذف الصورة",
            dictMaxFilesExceeded: "لا يمكنك رفع عدد اكثر من هذا",
            headers: {
                'X-CSRF-TOKEN': "{{ csrf_token() }}"
            },
            url: "{{ route('admin.images.store') }}", // Set the url
            success: function(file, response) {
                $('form').append('<input type="hidden" name="images[]" value="' + response.name + '">');
                uploadedDocumentMap[file.name] = response.name;
            },
            removedfile: function(file) {
                file.previewElement.remove();
                var name = uploadedDocumentMap[file.name];
                $('form').find('input[name="images[]"][value="' + name + '"]').remove();
            },
            init: function() {
                @if (isset($event) && $event->document)
                var files = {!! json_encode($event->document) !!};
                for (var i in files) {
                    var file = files[i];
                    this.options.addedfile.call(this, file);
                    file.previewElement.classList.add('dz-complete');
                    $('form').append('<input type="hidden" name="images[]" value="' + file.file_name + '">');
                }
                @endif
            }
        };

        // Client-side validation for image field
        $("form[name='my-form']").validate({
            rules: {
                title: { required: true },
                description: { required: true },
                'images[]': { required: true }, // Ensure at least one image is required
                project_type_id: { required: true }
            },
            messages: {
                title: { required: "العنوان مطلوب" },
                description: { required: "الوصف مطلوب" },
                'images[]': { required: "الصورة مطلوبة" }, // Custom error message for images
                project_type_id: { required: "نوع المشروع مطلوب" }
            },
            // Custom placement for error messages
            errorPlacement: function(error, element) {
                if (element.attr("name") === "images[]") {
                    error.appendTo('#dpz-multiple-files'); // Append the error to the Dropzone div
                } else {
                    error.insertAfter(element); // Default behavior for other fields
                }
            },
            submitHandler: function(form) {
                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }
                });

                var data = new FormData(document.getElementById("my-form"));
                data.append('description', CKEDITOR.instances['description'].getData());

                // Show the spinner
                $("#spinner").show();

                $.ajax({
                    url: '', // Your form submission URL here
                    type: "POST",
                    data: data,
                    dataType: 'JSON',
                    contentType: false,
                    cache: false,
                    processData: false,
                    success: function(response) {
                        $("#spinner").hide();
                        if (response.status) {
                            Swal.fire({
                                position: 'center',
                                icon: 'success',
                                title: response.message,
                                showConfirmButton: false,
                                timer: 1000
                            });
                            setTimeout(function() {
                                window.location.replace('');
                            }, 2000);
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Oops...',
                                text: response.message
                            });
                        }
                    },
                    error: function(response) {
                        // Hide the spinner
                        $("#spinner").hide();

                        var errors = response.responseJSON.errors;
                        if (errors) {
                            var errorText = "";
                            $.each(errors, function(key, value) {
                                errorText += value + "\n";
                                if (key === 'images[]') {
                                    $('#dpz-multiple-files').append('<div style="color:red">' + value[0] + '</div>');
                                } else {
                                    $('.' + key).text(value);
                                }
                            });
                            Swal.fire({
                                icon: 'error',
                                title: 'Validation Errors',
                                text: errorText
                            });
                        }
                    }
                });
            }
        });
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

            // Initialize Quill editors for each language section in the accordion
            @foreach ($lang as $locale)
            var quill{{ ucfirst($locale) }} = new Quill('#editor-container-{{ $locale }}', {
                bounds: '#editor-container-{{ $locale }}',
                placeholder: 'Type Something...',
                modules: {
                    formula: true,
                    toolbar: fullToolbar
                },
                theme: 'snow'
            });
            @endforeach

            $('#add_form').click(function(e) {
                e.preventDefault();

                var form = $(this.form); // Get the form reference

                if (!form.valid()) {
                    return false;
                }

                if (form.valid()) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }
                    });

                    var postData = new FormData(form[0]); // Use form[0] to get the HTMLFormElement

                    // Collect content from all Quill editors
                    @foreach ($lang as $locale)
                    var quillContent{{ ucfirst($locale) }} = quill{{ ucfirst($locale) }}.root.innerHTML;
                    postData.append('content[{{ $locale }}]', quillContent{{ ucfirst($locale) }});
                    @endforeach

                    $('#add_form').html('');
                    $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{ __('Saving') }}...</span>');

                    $.ajax({
                        url: '{{ route('admin.properties.store') }}',
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
                            $('.custom-error').remove();
                            // Clear editors after successful save
                            @foreach ($lang as $locale)
                                quill{{ ucfirst($locale) }}.root.innerHTML = '';
                            @endforeach
                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#add_form').html('{{ __('Save') }}');

                            var response = data.responseJSON;
                            // console.log(response)
                            // console.log(response.responseJSON)

                            if (data.status == 422) { // Validation error
                                if (response && response.responseJSON) {
                                    var errorMessages = '';

                                    $.each(response.responseJSON, function (key, value) {
                                        errorMessages += '<p>' + value[0] + '</p>';

                                        var error_message = '<div class="custom-error"><p style="color: red">' + value[0] + '</p></div>';
                                        if (key === 'images') {
                                            $('#image-upload-section').append(error_message);
                                        } else {
                                            $('[name="' + key + '"]').closest('.form-group').append(error_message);
                                        }
                                    });

                                    Swal.fire({
                                        icon: 'error',
                                        title: '{{ __("Validation Errors") }}',
                                        html: errorMessages,
                                        confirmButtonText: '{{ __("OK") }}'
                                    });
                                }
                            } else {
                                Swal.fire({
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
