<div class="flat-tab flat-tab-form widget-filter-search widget-box bg-surface">
    <div class="h7 title fw-7">{{__('Search')}}</div>
    <ul class="nav-tab-form" role="tablist">
        <li class="nav-tab-item" role="presentation">
            <a href="#rent" class="nav-link-item {{ request('tab', 'rent') == 'rent' ? 'active' : '' }}" data-bs-toggle="tab">{{__('For Rent')}}</a>
        </li>
        <li class="nav-tab-item" role="presentation">
            <a href="#sale" class="nav-link-item {{ request('tab', 'rent') == 'sale' ? 'active' : '' }}" data-bs-toggle="tab">{{__('For Sale')}}</a>
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
                                <label class="title-select">{{__('Keyword')}}</label>
                                <input type="text" class="form-control" placeholder="{{__('Search Keyword')}}" value="{{ request('keyword') }}" name="keyword" title="Search for">
                            </div>
                            <div class="form-style">
                                <label class="title-select">{{__('Location')}}</label>
                                <div class="group-ip ip-icon">
                                    <input type="text" class="form-control" placeholder="{{__('Search Location')}}" value="{{ request('location') }}" name="location" id="filter_location_input" title="Search for" readonly>
                                    <a href="#" class="icon-right icon-location" id="filter_open_location_popup" title="{{__('Select Location')}}"></a>
                                    <input type="hidden" name="governorate_id" id="filter_governorate_id" value="{{ request('governorate_id') }}">
                                    <input type="hidden" name="department_id" id="filter_department_id" value="{{ request('department_id') }}">
                                    <input type="hidden" name="village_id" id="filter_village_id" value="{{ request('village_id') }}">
                                    <input type="hidden" name="hod_id" id="filter_hod_id" value="{{ request('hod_id') }}">
                                    <input type="hidden" name="hay_id" id="filter_hay_id" value="{{ request('hay_id') }}">
                                    <input type="hidden" name="plot_number" id="filter_plot_number" value="{{ request('plot_number') }}">
                                </div>
                            </div>
                            <div class="form-style">
                                <label class="title-select">{{__('Type')}}</label>
                                <div class="group-select">
                                    <select name="category_id" id="filter_category_id" class="form-control">
                                        <option value="" data-slug="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                                        @foreach($categories as $category)
                                            @php $catSlug = $category->getTranslation('slug', 'en') ?: $category->getTranslation('slug', 'ar'); @endphp
                                            <option value="{{ $category->id }}" data-slug="{{ $catSlug }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div id="filter-category-fields" style="width:100%;">
                                <p class="filter-hint-empty text-muted small mb-2" style="display:none;">{{__('Select property type above to show relevant filters.')}}</p>
                                @include('site.properties.partials.filter_category_sections')
                            </div>
                            <div class="form-style widget-price">
                                <div class="box-title-price">
                                    <span class="title-price">{{__('Price Range')}}</span>
                                    <div class="caption-price">
                                        <span>{{__('from')}}</span>
                                        <span id="slider-range-value1" class="fw-7">{{ request('min-value', '0') }}</span>
                                        <span>{{__('to')}}</span>
                                        <span id="slider-range-value2" class="fw-7">{{ request('max-value', '1000000') }}</span>
                                    </div>
                                </div>
                                <div id="slider-range"></div>
                                <div class="slider-labels">

                                    <!-- Hidden inputs for price range -->
                                    <input type="hidden" name="min-value" value="{{ request('min-value', 0) }}">
                                    <input type="hidden" name="max-value" value="{{ request('max-value', 1000000) }}">



                                </div>
                            </div>
                            <div class="form-style widget-price wd-price-2">
                                <div class="box-title-price">
                                    <span class="title-price">{{__('Size Range')}}</span>
                                    <div class="caption-price">
                                        <span>{{__('from')}}</span>
                                        <span id="slider-range-value01" class="fw-7">{{ request('min-value2', '0') }}</span>
                                        <span>{{__('to')}}</span>
                                        <span id="slider-range-value02" class="fw-7">{{ request('max-value2', '10000') }}</span>
                                    </div>
                                </div>
                                <div id="slider-range2"></div>
                                <div class="slider-labels">
                                    <!-- Hidden inputs for size range -->
                                    <input type="hidden" name="min-value2" value="{{ request('min-value2', 0) }}">
                                    <input type="hidden" name="max-value2" value="{{ request('max-value2', 10000) }}">
                                </div>
                            </div>
                            <div class="form-style btn-show-advanced">
                                <a class="filter-advanced pull-right">
                                    <span class="icon icon-faders"></span>
                                    <span class="text-advanced">{{__('Show Advanced')}}</span>
                                </a>
                            </div>
                            <div class="form-style btn-hide-advanced">
                                <a class="filter-advanced pull-right">
                                    <span class="icon icon-faders"></span>
                                    <span class="text-advanced">{{__('Hide Advanced')}}</span>
                                </a>
                            </div>
                            <div class="form-style">
                                <button type="submit" class="tf-btn primary">{{__('Find Properties')}}</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

    </div>
</div>
<script>
(function() {
    function toggleFilterFieldsByCategory() {
        var cat = document.getElementById('filter_category_id');
        if (!cat) return;
        var opt = cat.options[cat.selectedIndex];
        var slug = (opt && opt.getAttribute('data-slug')) || '';
        var hint = document.querySelector('.filter-hint-empty');
        var visibleSection = null;
        document.querySelectorAll('.filter-category-section').forEach(function(el) {
            var show = el.getAttribute('data-category') === slug.toLowerCase();
            el.style.display = show ? '' : 'none';
            el.querySelectorAll('input, select').forEach(function(inp) { inp.disabled = !show; });
            if (show) visibleSection = el;
        });
        if (hint) hint.style.display = slug ? 'none' : '';
        if (visibleSection && typeof $ !== 'undefined' && $.fn.niceSelect) {
            $(visibleSection).find('select').niceSelect('update');
        }
    }
    function initFilterCategoryToggle() {
        var cat = document.getElementById('filter_category_id');
        if (!cat) return;
        toggleFilterFieldsByCategory();
        cat.addEventListener('change', toggleFilterFieldsByCategory);
        document.addEventListener('click', function(e) {
            if (e.target.closest && e.target.closest('.nice-select .option')) {
                setTimeout(toggleFilterFieldsByCategory, 50);
            }
        });
    }
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', initFilterCategoryToggle);
    } else {
        initFilterCategoryToggle();
    }
})();
</script>
<!-- Location Popup Modal for filter -->
<div class="modal fade" id="filterLocationPopupModal" tabindex="-1" data-bs-backdrop="true">
    <div class="modal-dialog modal-dialog-centered modal-md modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header py-2">
                <h6 class="modal-title">{{__('Select Location')}}</h6>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-2">
                <div class="mb-2">
                    <label class="form-label small">{{__('Governorate')}}</label>
                    <select id="filter_popup_governorate" class="form-control form-control-sm select2-popup">
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
                    <select id="filter_popup_department" class="form-control form-control-sm select2-popup">
                        <option value="">{{__('Select Department')}}</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label small">{{__('Village')}}</label>
                    <select id="filter_popup_village" class="form-control form-control-sm select2-popup">
                        <option value="">{{__('Select Village')}}</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label small">{{__('Hod')}}</label>
                    <select id="filter_popup_hod" class="form-control form-control-sm select2-popup">
                        <option value="">{{__('Select Hod')}}</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label small">{{__('Hay')}}</label>
                    <select id="filter_popup_hay" class="form-control form-control-sm select2-popup">
                        <option value="">{{__('Select Hay')}}</option>
                    </select>
                </div>
                <div class="mb-2">
                    <label class="form-label small">{{__('Plot Number')}}</label>
                    <input type="text" id="filter_popup_plot_number" class="form-control form-control-sm" placeholder="{{__('Plot Number')}}" value="{{ request('plot_number') }}">
                </div>
                <button type="button" class="btn btn-primary btn-sm w-100" id="filter_apply_location_popup">{{__('Apply')}}</button>
            </div>
        </div>
    </div>
</div>


