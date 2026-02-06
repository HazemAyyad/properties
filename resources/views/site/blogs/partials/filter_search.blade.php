<div class="flat-tab flat-tab-form widget-filter-search widget-box bg-surface">
    <div class="h7 title fw-7">Search</div>
    <ul class="nav-tab-form" role="tablist">
        <li class="nav-tab-item" role="presentation">
            <a href="#rent" class="nav-link-item {{ request('tab', 'rent') == 'rent' ? 'active' : '' }}" data-bs-toggle="tab">For Rent</a>
        </li>
        <li class="nav-tab-item" role="presentation">
            <a href="#sale" class="nav-link-item {{ request('tab', 'rent') == 'sale' ? 'active' : '' }}" data-bs-toggle="tab">For Sale</a>
        </li>
    </ul>
    <div class="tab-content">
        <div class="tab-pane fade  active show" role="tabpanel" >
            <div class="form-sl">
                <form method="GET" action="{{ route('site.properties') }}">
                    <input type="hidden" name="tab" id="selectedTab" value="{{ request('tab') }}">
                    <div class="wd-filter-select">
                        <div class="inner-group inner-filter">
                            <div class="form-style">
                                <label class="title-select">Keyword</label>
                                <input type="text" class="form-control" placeholder="Search Keyword" value="{{ request('keyword') }}" name="keyword" title="Search for">
                            </div>
                            <div class="form-style">
                                <label class="title-select">Location</label>
                                <div class="group-ip ip-icon">
                                    <input type="text" class="form-control" placeholder="Search Location" value="{{ request('location') }}" name="location" title="Search for">
                                    <a href="#" class="icon-right icon-location"></a>
                                </div>
                            </div>
                            <div class="form-style">
                                <label class="title-select">Type</label>
                                <div class="group-select">
                                    <select name="category_id" class="form-control">
                                        <option value="" data-display="select">Nothing</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="form-style box-select">
                                <label class="title-select">Rooms</label>
                                <select name="rooms" class="form-control">
                                    <option value="" data-display="select">Nothing</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ request('rooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-style box-select">
                                <label class="title-select">Bathrooms</label>
                                <select name="bathrooms" class="form-control">
                                    <option value="" data-display="select">Nothing</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-style box-select">
                                <label class="title-select">Bedrooms</label>
                                <select name="bedrooms" class="form-control">
                                    <option value="" data-display="select">Nothing</option>
                                    @for ($i = 1; $i <= 10; $i++)
                                        <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option>
                                    @endfor
                                </select>
                            </div>
                            <div class="form-style widget-price">
                                <div class="box-title-price">
                                    <span class="title-price">Price Range</span>
                                    <div class="caption-price">
                                        <span>from</span>
                                        <span id="slider-range-value1" class="fw-7">{{ request('min-value', '0') }}</span>
                                        <span>to</span>
                                        <span id="slider-range-value2" class="fw-7">{{ request('max-value', '1000000') }}</span>
                                    </div>
                                </div>
                                <div id="slider-range"></div>
                                <div class="slider-labels">
                                    <input type="hidden" name="min-value" value="{{ request('min-value', '0') }}">
                                    <input type="hidden" name="max-value" value="{{ request('max-value', '1000000') }}">
                                </div>
                            </div>
                            <div class="form-style widget-price wd-price-2">
                                <div class="box-title-price">
                                    <span class="title-price">Size Range</span>
                                    <div class="caption-price">
                                        <span>from</span>
                                        <span id="slider-range-value01" class="fw-7">{{ request('min-value2', '0') }}</span>
                                        <span>to</span>
                                        <span id="slider-range-value02" class="fw-7">{{ request('max-value2', '10000') }}</span>
                                    </div>
                                </div>
                                <div id="slider-range2"></div>
                                <div class="slider-labels">
                                    <input type="hidden" name="min-value2" value="{{ request('min-value2', '0') }}">
                                    <input type="hidden" name="max-value2" value="{{ request('max-value2', '10000') }}">
                                </div>
                            </div>
                            <div class="form-style btn-show-advanced">
                                <a class="filter-advanced pull-right">
                                    <span class="icon icon-faders"></span>
                                    <span class="text-advanced">Show Advanced</span>
                                </a>
                            </div>
                            <div class="form-style wd-amenities">
                                <div class="group-checkbox">
                                    <div class="text-1">Amenities:</div>
                                    <div class="group-amenities">
                                        @foreach($features as $feature)
                                            <fieldset class="amenities-item">
                                                <input type="checkbox" class="tf-checkbox style-1" name="features[]" value="{{ $feature->id }}" id="feature_{{ $feature->id }}" {{ in_array($feature->id, request('features', [])) ? 'checked' : '' }}>
                                                <label for="feature_{{ $feature->id }}" class="text-cb-amenities">{{ $feature->name }}</label>
                                            </fieldset>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                            <div class="form-style btn-hide-advanced">
                                <a class="filter-advanced pull-right">
                                    <span class="icon icon-faders"></span>
                                    <span class="text-advanced">Hide Advanced</span>
                                </a>
                            </div>
                            <div class="form-style">
                                <button type="submit" class="tf-btn primary">Find Properties</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>


