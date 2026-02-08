{{-- Shared category-specific filter sections (matches create form) --}}
{{-- شقة Apartment --}}
<div class="filter-category-section" data-category="apartment" style="display:none; width:100%;">
    <div class="group-select mb-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Building Age')}}</label>
            <select name="building_age" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="new" {{ request('building_age') == 'new' ? 'selected' : '' }}>{{__('New')}}</option>
                @foreach(['5-10','10-11','11-20','20+'] as $v) <option value="{{ $v }}" {{ request('building_age') == $v ? 'selected' : '' }}>{{ $v }}</option> @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bedrooms')}}</label>
            <select name="bedrooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="studio" {{ request('bedrooms') == 'studio' ? 'selected' : '' }}>{{__('Studio')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('bedrooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bathrooms')}}</label>
            <select name="bathrooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('bathrooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Floor')}}</label>
            <select name="floor" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @foreach(['mezzanine_2'=>'Mezzanine 2','mezzanine_1'=>'Mezzanine 1','ground'=>'Ground','first'=>'First','second'=>'Second','third'=>'Third','fourth'=>'Fourth','roof'=>'Roof'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('floor') == $v ? 'selected' : '' }}>{{ __($l) }}</option>
                @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Furnished')}}</label>
            <select name="furnished" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="0" {{ request('furnished') === '0' ? 'selected' : '' }}>{{__('Unfurnished')}}</option>
                <option value="1" {{ request('furnished') === '1' ? 'selected' : '' }}>{{__('Furnished')}}</option>
            </select>
        </div>
    </div>
    <div class="group-checkbox" style="width: 100%;">
        <div class="text-1">{{__('Extra Features')}}</div>
        <div class="group-amenities mt-8" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 8px; width: 100%;">
            @php $aptFeat = ['pool'=>'Pool','sewage_connected'=>'Sewage Connected','water_well'=>'Water Well','balcony'=>'Balcony','maid_room'=>'Maid Room','storage_room'=>'Storage Room','laundry_room'=>'Laundry Room','central_ac'=>'Central AC','car_parking'=>'Car Parking']; $exF = request('extra_features',[]); @endphp
            @foreach($aptFeat as $k=>$l)
                <fieldset class="amenities-item">
                    <input type="checkbox" class="tf-checkbox style-1" name="extra_features[]" value="{{ $k }}" id="apt_ef_{{ $k }}" {{ in_array($k, (array)$exF) ? 'checked' : '' }}>
                    <label for="apt_ef_{{ $k }}" class="text-cb-amenities">{{ __($l) }}</label>
                </fieldset>
            @endforeach
        </div>
    </div>
</div>
{{-- فيلا Villa --}}
<div class="filter-category-section" data-category="villa" style="display:none; width:100%;">
    <div class="group-select mb-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Building Age')}}</label>
            <select name="building_age" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="new" {{ request('building_age') == 'new' ? 'selected' : '' }}>{{__('New')}}</option>
                @foreach(['5-10','10-11','11-20','20+'] as $v) <option value="{{ $v }}" {{ request('building_age') == $v ? 'selected' : '' }}>{{ $v }}</option> @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bedrooms')}}</label>
            <select name="bedrooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="studio" {{ request('bedrooms') == 'studio' ? 'selected' : '' }}>{{__('Studio')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('bedrooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('bedrooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bathrooms')}}</label>
            <select name="bathrooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('bathrooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Furnished')}}</label>
            <select name="furnished" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="0" {{ request('furnished') === '0' ? 'selected' : '' }}>{{__('Unfurnished')}}</option>
                <option value="1" {{ request('furnished') === '1' ? 'selected' : '' }}>{{__('Furnished')}}</option>
            </select>
        </div>
    </div>
    <div class="group-checkbox" style="width: 100%;">
        <div class="text-1">{{__('Extra Features')}}</div>
        <div class="group-amenities mt-8" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 8px; width: 100%;">
            @foreach(['pool'=>'Pool','sewage_connected'=>'Sewage Connected','water_well'=>'Water Well'] as $k=>$l)
                <fieldset class="amenities-item">
                    <input type="checkbox" class="tf-checkbox style-1" name="extra_features[]" value="{{ $k }}" id="villa_ef_{{ $k }}" {{ in_array($k, (array)request('extra_features',[])) ? 'checked' : '' }}>
                    <label for="villa_ef_{{ $k }}" class="text-cb-amenities">{{ __($l) }}</label>
                </fieldset>
            @endforeach
        </div>
    </div>
</div>
{{-- مكتب Office --}}
<div class="filter-category-section" data-category="office" style="display:none; width:100%;">
    <div class="group-select mb-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Building Age')}}</label>
            <select name="building_age" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="new" {{ request('building_age') == 'new' ? 'selected' : '' }}>{{__('New')}}</option>
                @foreach(['5-10','10-11','11-20','20+'] as $v) <option value="{{ $v }}" {{ request('building_age') == $v ? 'selected' : '' }}>{{ $v }}</option> @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Number of Rooms')}}</label>
            <select name="rooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('rooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('rooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bathrooms')}}</label>
            <select name="bathrooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('bathrooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Floor')}}</label>
            <input type="text" name="floor" class="form-control" placeholder="{{__('Floor')}}" value="{{ request('floor') }}">
        </div>
    </div>
</div>
{{-- تجاري Commercial --}}
<div class="filter-category-section" data-category="commercial" style="display:none; width:100%;">
    <div class="group-select mb-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Building Age')}}</label>
            <select name="building_age" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="new" {{ request('building_age') == 'new' ? 'selected' : '' }}>{{__('New')}}</option>
                @foreach(['5-10','10-11','11-20','20+'] as $v) <option value="{{ $v }}" {{ request('building_age') == $v ? 'selected' : '' }}>{{ $v }}</option> @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bathrooms')}}</label>
            <select name="bathrooms" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @for ($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ request('bathrooms') == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ request('bathrooms') == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Floor')}}</label>
            <input type="text" name="floor" class="form-control" placeholder="{{__('Floor')}}" value="{{ request('floor') }}">
        </div>
    </div>
</div>
{{-- مزرعة Farm --}}
<div class="filter-category-section" data-category="farm" style="display:none; width:100%;">
    <div class="group-select mb-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Building Age')}}</label>
            <input type="text" name="building_age" class="form-control" placeholder="{{__('Building Age')}}" value="{{ request('building_age') }}">
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bedrooms')}}</label>
            <input type="number" name="bedrooms" class="form-control" placeholder="{{__('Bedrooms')}}" value="{{ request('bedrooms') }}">
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Bathrooms')}}</label>
            <input type="number" name="bathrooms" class="form-control" placeholder="{{__('Bathrooms')}}" value="{{ request('bathrooms') }}">
        </div>
    </div>
    <div class="group-checkbox" style="width: 100%;">
        <div class="text-1">{{__('Extra Features')}}</div>
        <div class="group-amenities mt-8" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(160px, 1fr)); gap: 8px; width: 100%;">
            @foreach(['pool'=>'Pool','sewage_connected'=>'Sewage Connected','water_well'=>'Water Well'] as $k=>$l)
                <fieldset class="amenities-item">
                    <input type="checkbox" class="tf-checkbox style-1" name="extra_features[]" value="{{ $k }}" id="farm_ef_{{ $k }}" {{ in_array($k, (array)request('extra_features',[])) ? 'checked' : '' }}>
                    <label for="farm_ef_{{ $k }}" class="text-cb-amenities">{{ __($l) }}</label>
                </fieldset>
            @endforeach
        </div>
    </div>
</div>
{{-- أرض Land --}}
<div class="filter-category-section" data-category="land" style="display:none; width:100%;">
    <div class="group-select mb-2" style="display: grid; grid-template-columns: repeat(auto-fill, minmax(180px, 1fr)); gap: 12px; width: 100%;">
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Zoning')}}</label>
            <select name="zoning" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @foreach(['residential_a'=>'Residential A','residential_b'=>'Residential B','residential_c'=>'Residential C','residential_d'=>'Residential D','offices'=>'Offices','commercial'=>'Commercial','light_industry'=>'Light Industry','industrial'=>'Industrial','agricultural'=>'Agricultural','outside_planning'=>'Outside Planning','tourism'=>'Tourism','rural'=>'Rural','private_residential'=>'Private Residential'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('zoning') == $v ? 'selected' : '' }}>{{ __($l) }}</option>
                @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Land Type')}}</label>
            <select name="land_type" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                @foreach(['rocky'=>'Rocky','red_soil'=>'Red Soil','sloping'=>'Sloping','flat'=>'Flat','mountainous'=>'Mountainous'] as $v=>$l)
                    <option value="{{ $v }}" {{ request('land_type') == $v ? 'selected' : '' }}>{{ __($l) }}</option>
                @endforeach
            </select>
        </div>
        <div class="box-select form-style">
            <label class="title-select text-variant-1">{{__('Services')}}</label>
            <select name="services" class="form-control">
                <option value="" data-display="{{__('select')}}">{{__('Nothing')}}</option>
                <option value="all_connected" {{ request('services') == 'all_connected' ? 'selected' : '' }}>{{__('All Services Connected')}}</option>
                <option value="near_services" {{ request('services') == 'near_services' ? 'selected' : '' }}>{{__('Near Services')}}</option>
            </select>
        </div>
    </div>
</div>
