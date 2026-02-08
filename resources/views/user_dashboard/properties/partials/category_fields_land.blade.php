{{-- أرض --}}
@php $info = $info ?? null; @endphp
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Zoning') }}</label>
            <select name="zoning" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="residential_a" {{ old('zoning', optional($info)->zoning) == 'residential_a' ? 'selected' : '' }}>{{ __('Residential A') }}</option>
                <option value="residential_b" {{ old('zoning', optional($info)->zoning) == 'residential_b' ? 'selected' : '' }}>{{ __('Residential B') }}</option>
                <option value="residential_c" {{ old('zoning', optional($info)->zoning) == 'residential_c' ? 'selected' : '' }}>{{ __('Residential C') }}</option>
                <option value="residential_d" {{ old('zoning', optional($info)->zoning) == 'residential_d' ? 'selected' : '' }}>{{ __('Residential D') }}</option>
                <option value="offices" {{ old('zoning', optional($info)->zoning) == 'offices' ? 'selected' : '' }}>{{ __('Offices') }}</option>
                <option value="commercial" {{ old('zoning', optional($info)->zoning) == 'commercial' ? 'selected' : '' }}>{{ __('Commercial') }}</option>
                <option value="light_industry" {{ old('zoning', optional($info)->zoning) == 'light_industry' ? 'selected' : '' }}>{{ __('Light Industry') }}</option>
                <option value="industrial" {{ old('zoning', optional($info)->zoning) == 'industrial' ? 'selected' : '' }}>{{ __('Industrial') }}</option>
                <option value="agricultural" {{ old('zoning', optional($info)->zoning) == 'agricultural' ? 'selected' : '' }}>{{ __('Agricultural') }}</option>
                <option value="outside_planning" {{ old('zoning', optional($info)->zoning) == 'outside_planning' ? 'selected' : '' }}>{{ __('Outside Planning') }}</option>
                <option value="tourism" {{ old('zoning', optional($info)->zoning) == 'tourism' ? 'selected' : '' }}>{{ __('Tourism') }}</option>
                <option value="rural" {{ old('zoning', optional($info)->zoning) == 'rural' ? 'selected' : '' }}>{{ __('Rural') }}</option>
                <option value="private_residential" {{ old('zoning', optional($info)->zoning) == 'private_residential' ? 'selected' : '' }}>{{ __('Private Residential') }}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Land Type') }}</label>
            <select name="land_type" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="rocky" {{ old('land_type', optional($info)->land_type) == 'rocky' ? 'selected' : '' }}>{{ __('Rocky') }}</option>
                <option value="red_soil" {{ old('land_type', optional($info)->land_type) == 'red_soil' ? 'selected' : '' }}>{{ __('Red Soil') }}</option>
                <option value="sloping" {{ old('land_type', optional($info)->land_type) == 'sloping' ? 'selected' : '' }}>{{ __('Sloping') }}</option>
                <option value="flat" {{ old('land_type', optional($info)->land_type) == 'flat' ? 'selected' : '' }}>{{ __('Flat') }}</option>
                <option value="mountainous" {{ old('land_type', optional($info)->land_type) == 'mountainous' ? 'selected' : '' }}>{{ __('Mountainous') }}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Services') }}</label>
            <select name="services" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="all_connected" {{ old('services', optional($info)->services) == 'all_connected' ? 'selected' : '' }}>{{ __('All Services Connected') }}</option>
                <option value="near_services" {{ old('services', optional($info)->services) == 'near_services' ? 'selected' : '' }}>{{ __('Near Services') }}</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Land Area (m²)') }} ({{ __('from') }})</label>
            <input type="number" step="0.01" name="size" class="form-control" value="{{ old('size', optional($info)->size ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Land Area (m²)') }} ({{ __('to') }})</label>
            <input type="number" step="0.01" name="size_max" class="form-control" value="{{ old('size_max', optional($info)->size_max ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Price') }} ({{ __('from') }}) <span class="text-muted">({{ __('JOD') }})</span></label>
            <input type="number" step="0.01" name="price_min" class="form-control" value="{{ old('price_min', optional($info)->price_min ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Price') }} ({{ __('to') }}) <span class="text-muted">({{ __('JOD') }})</span></label>
            <input type="number" step="0.01" name="price_max" class="form-control" value="{{ old('price_max', optional($info)->price_max ?? '') }}">
        </div>
    </div>
</div>
