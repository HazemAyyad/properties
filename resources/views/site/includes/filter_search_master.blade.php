<div class="flat-tab flat-tab-form">
    <ul class="nav-tab-form style-1 justify-content-center" role="tablist">
        <li class="nav-tab-item" role="presentation">
            <a href="#rent" class="nav-link-item {{ request('tab', 'rent') == 'rent' ? 'active' : '' }}"  data-bs-toggle="tab">{{__('For Rent')}}</a>
        </li>
        <li class="nav-tab-item" role="presentation">
            <a href="#sale" class="nav-link-item {{ request('tab', 'rent') == 'sale' ? 'active' : '' }}" data-bs-toggle="tab">{{__('For Sale')}}</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade active show" role="tabpanel">
            <div class="form-sl">
                <form method="GET" action="{{ route('site.properties') }}">
                    <input type="hidden" name="tab"  id="selectedTab" value="{{ request('tab') }}">
                    <div class="wd-find-select">
                        <div class="inner-group">
                            <div class="form-group-1 search-form form-style">
                                <label>{{__('Keyword')}}</label>
                                <input type="text" class="form-control" placeholder="{{__('Search Keyword.')}}" value="{{ request('keyword') }}" name="keyword" title="Search for"  >
                            </div>
                            <div class="form-group-2 form-style">
                                <label>{{__('Location')}}</label>
                                <div class="group-ip">
                                    <input type="text" class="form-control" placeholder="{{__('Search Location')}}" value="{{ request('location') }}" name="location" id="home_location_input" title="Search for">
                                    <a href="javascript:void(0)" class="icon icon-location" id="open_location_popup" title="{{__('Select Location')}}"></a>
                                    <input type="hidden" name="governorate_id" id="home_governorate_id" value="{{ request('governorate_id') }}">
                                    <input type="hidden" name="department_id" id="home_department_id" value="{{ request('department_id') }}">
                                    <input type="hidden" name="village_id" id="home_village_id" value="{{ request('village_id') }}">
                                </div>
                            </div>
                            <div class="form-group-3 form-style">
                                <label>{{__('Type')}}</label>
                                <div class="group-select">
                                    <select name="category_id" class="form-control">
                                        <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-group-4 box-filter">
                                <a class="filter-advanced pull-right">
                                    <span class="icon icon-faders"></span>
                                    <span class="text-1">{{__('Advanced')}}</span>
                                </a>
                            </div>
                        </div>
                        <button type="submit" class="tf-btn primary" href="#">{{__('Search')}}</button>
                    </div>
                    <div class="wd-search-form">
                        <div class="grid-2 group-box group-price">
                            <div class="widget-price">
                                <div class="box-title-price">
                                    <span class="title-price">{{ __('Price Range') }}</span>
                                    <div class="caption-price">
                                        <span>{{ __('from') }}</span>
                                        <span id="slider-range-value1" class="fw-7">{{ request('min-value', '0') }}</span>
                                        <span>{{ __('to') }}</span>
                                        <span id="slider-range-value2" class="fw-7">{{ request('max-value', '1000000') }}</span>
                                    </div>
                                </div>
                                <div id="slider-range"></div>
                                <div class="slider-labels">
                                    <input type="hidden" name="min-value" value="{{ request('min-value', '0') }}">
                                    <input type="hidden" name="max-value" value="{{ request('max-value', '1000000') }}">
                                </div>
                            </div>

                            <div class="widget-price">
                                <div class="box-title-price">
                                    <span class="title-price">{{ __('Size Range') }}</span>
                                    <div class="caption-price">
                                        <span>{{ __('from') }}</span>
                                        <span id="slider-range-value01" class="fw-7">{{ request('min-value2', '0') }}</span>
                                        <span>{{ __('to') }}</span>
                                        <span id="slider-range-value02" class="fw-7">{{ request('max-value2', '10000') }}</span>
                                    </div>
                                </div>
                                <div id="slider-range2"></div>
                                <div class="slider-labels">
                                    <input type="hidden" name="min-value2" value="{{ request('min-value2', '0') }}">
                                    <input type="hidden" name="max-value2" value="{{ request('max-value2', '10000') }}">
                                </div>
                            </div>

                        </div>
                        <div class="grid-2 group-box">
                            <div class="group-select grid-2">
                                <div class="box-select">
                                    <label class="title-select text-variant-1">{{__('Rooms')}}</label>
                                    <select name="rooms" class="form-control">
                                        <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ request('rooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                                <div class="box-select">
                                    <label class="title-select text-variant-1">{{__('Bathrooms')}}</label>
                                    <select name="bathrooms" class="form-control">
                                        <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>
                            <div class="group-select grid-2">
                                <div class="box-select">
                                    <label class="title-select text-variant-1">{{__('Bedrooms')}}</label>
                                    <select name="bedrooms" class="form-control">
                                        <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                                        @for ($i = 1; $i <= 10; $i++)
                                            <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                        @endfor
                                    </select>
                                </div>
                            </div>

                        </div>

                        <div class="group-checkbox">
                            <div class="text-1">{{__('Amenities:')}}</div>
                            <div class="group-amenities mt-8 grid-6">
                                @php
                                    $quarter = ceil($features->count() / 4);
                                    $featuresFirst = $features->take($quarter);
                                    $featuresSecond = $features->skip($quarter)->take($quarter);
                                    $featuresThird = $features->skip($quarter * 2)->take($quarter);
                                    $featuresFourth = $features->skip($quarter * 3);
                                @endphp
                                <div class="box-amenities">

                                    @foreach($featuresFirst  as $feature)
                                        <fieldset class="amenities-item">
                                            <input type="checkbox" class="tf-checkbox style-1" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, request('features', [])) ? 'checked' : '' }}>
                                            <label for="feature_{{ $feature->id }}" class="text-cb-amenities">{{ $feature->name }}</label>
                                        </fieldset>
                                    @endforeach

                                </div>
                                <div class="box-amenities">

                                    @foreach($featuresSecond as $feature)
                                        <fieldset class="amenities-item">
                                            <input type="checkbox" class="tf-checkbox style-1" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, request('features', [])) ? 'checked' : '' }}>
                                            <label for="feature_{{ $feature->id }}" class="text-cb-amenities">{{ $feature->name }}</label>
                                        </fieldset>
                                    @endforeach

                                </div>
                                <div class="box-amenities">

                                    @foreach($featuresThird as $feature)
                                        <fieldset class="amenities-item">
                                            <input type="checkbox" class="tf-checkbox style-1" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, request('features', [])) ? 'checked' : '' }}>
                                            <label for="feature_{{ $feature->id }}" class="text-cb-amenities">{{ $feature->name }}</label>
                                        </fieldset>
                                    @endforeach

                                </div>
                                <div class="box-amenities">

                                    @foreach($featuresFourth as $feature)
                                        <fieldset class="amenities-item">
                                            <input type="checkbox" class="tf-checkbox style-1" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, request('features', [])) ? 'checked' : '' }}>
                                            <label for="feature_{{ $feature->id }}" class="text-cb-amenities">{{ $feature->name }}</label>
                                        </fieldset>
                                    @endforeach

                                </div>
                            </div>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- Location Popup Modal - moved to body to fix backdrop z-index -->
    <div class="modal fade" id="locationPopupModal" tabindex="-1" data-bs-backdrop="true">
        <div class="modal-dialog modal-dialog-centered modal-sm">
            <div class="modal-content">
                <div class="modal-header py-2">
                    <h6 class="modal-title">{{__('Select Location')}}</h6>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body py-2">
                    <div class="mb-2">
                        <label class="form-label small">{{__('Governorate')}}</label>
                        <select id="popup_governorate" class="form-control form-control-sm select2-popup">
                            <option value="">{{__('Select Governorate')}}</option>
                            @isset($governorates)
                                @foreach($governorates as $gov)
                                    <option value="{{ $gov->governorate_id }}">{{ app()->getLocale() == 'ar' ? $gov->governorate_name_ar : ($gov->governorate_name_en ?? $gov->governorate_name_ar) }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">{{__('Department')}}</label>
                        <select id="popup_department" class="form-control form-control-sm select2-popup">
                            <option value="">{{__('Select Department')}}</option>
                        </select>
                    </div>
                    <div class="mb-2">
                        <label class="form-label small">{{__('Village')}}</label>
                        <select id="popup_village" class="form-control form-control-sm select2-popup">
                            <option value="">{{__('Select Village')}}</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary btn-sm w-100" id="apply_location_popup">{{__('Apply')}}</button>
                </div>
            </div>
        </div>
    </div>
</div>
