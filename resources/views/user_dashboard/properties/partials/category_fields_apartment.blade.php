{{-- شقة - supports $info for edit --}}
@php $info = $info ?? null; @endphp
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Building Age') }}</label>
            <select name="building_age" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                @php $bAge = old('building_age', optional($info)->building_age ?? ''); @endphp
                <option value="new" {{ $bAge == 'new' ? 'selected' : '' }}>{{ __('New') }}</option>
                <option value="5-10" {{ $bAge == '5-10' ? 'selected' : '' }}>5-10</option>
                <option value="10-11" {{ $bAge == '10-11' ? 'selected' : '' }}>10-11</option>
                <option value="11-20" {{ $bAge == '11-20' ? 'selected' : '' }}>11-20</option>
                <option value="20+" {{ $bAge == '20+' ? 'selected' : '' }}>20+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bedrooms') }}</label>
            <select name="bedrooms" class="form-control form-select">
                @php $beds = old('bedrooms', optional($info)->bedrooms ?? ''); @endphp
                <option value="">{{ __('Select') }}</option>
                <option value="studio" {{ $beds == 'studio' ? 'selected' : '' }}>{{ __('Studio') }}</option>
                @for($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ $beds == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ $beds == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bathrooms') }}</label>
            <select name="bathrooms" class="form-control form-select">
                @php $baths = old('bathrooms', optional($info)->bathrooms ?? ''); @endphp
                <option value="">{{ __('Select') }}</option>
                @for($i = 1; $i <= 5; $i++) <option value="{{ $i }}" {{ $baths == $i ? 'selected' : '' }}>{{ $i }}</option> @endfor
                <option value="5+" {{ $baths == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Apartment Area (m²)') }} ({{ __('from') }})</label>
            <input type="number" step="0.01" name="size" class="form-control" value="{{ old('size', optional($info)->size ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Apartment Area (m²)') }} ({{ __('to') }})</label>
            <input type="number" step="0.01" name="size_max" class="form-control" value="{{ old('size_max', optional($info)->size_max ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Floor') }}</label>
            <select name="floor" class="form-control form-select">
                @php $fl = old('floor', optional($info)->floor ?? ''); @endphp
                <option value="">{{ __('Select') }}</option>
                <option value="mezzanine_2" {{ $fl == 'mezzanine_2' ? 'selected' : '' }}>{{ __('Mezzanine 2') }}</option>
                <option value="mezzanine_1" {{ $fl == 'mezzanine_1' ? 'selected' : '' }}>{{ __('Mezzanine 1') }}</option>
                <option value="ground" {{ $fl == 'ground' ? 'selected' : '' }}>{{ __('Ground') }}</option>
                <option value="first" {{ $fl == 'first' ? 'selected' : '' }}>{{ __('First') }}</option>
                <option value="second" {{ $fl == 'second' ? 'selected' : '' }}>{{ __('Second') }}</option>
                <option value="third" {{ $fl == 'third' ? 'selected' : '' }}>{{ __('Third') }}</option>
                <option value="fourth" {{ $fl == 'fourth' ? 'selected' : '' }}>{{ __('Fourth') }}</option>
                <option value="roof" {{ $fl == 'roof' ? 'selected' : '' }}>{{ __('Roof') }}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Furnished') }}</label>
            <select name="furnished" class="form-control form-select">
                @php $fur = old('furnished', optional($info)->furnished ?? ''); @endphp
                <option value="">{{ __('Select') }}</option>
                <option value="0" {{ $fur === 0 || $fur === '0' ? 'selected' : '' }}>{{ __('Unfurnished') }}</option>
                <option value="1" {{ $fur === 1 || $fur === '1' ? 'selected' : '' }}>{{ __('Furnished') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12"><h6 class="mb-2">{{ __('Extra Features') }}</h6></div>
    @php
        $aptFeatures = ['pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well', 'balcony' => 'Balcony', 'maid_room' => 'Maid Room', 'storage_room' => 'Storage Room', 'laundry_room' => 'Laundry Room', 'central_ac' => 'Central AC', 'car_parking' => 'Car Parking'];
        $exFeat = old('extra_features', optional($info)->extra_features ?? []);
        if (!is_array($exFeat)) $exFeat = [];
    @endphp
    @foreach($aptFeatures as $key => $labelKey)
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="extra_features[]" value="{{ $key }}" id="apt_{{ $key }}" {{ in_array($key, $exFeat) ? 'checked' : '' }}>
                <label class="form-check-label" for="apt_{{ $key }}">{{ __($labelKey) }}</label>
            </div>
        </div>
    @endforeach
</div>
