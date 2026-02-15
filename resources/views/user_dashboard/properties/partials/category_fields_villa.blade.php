{{-- فيلا/منزل مستقل --}}
@php $info = $info ?? null; @endphp
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Building Age') }}</label>
            <select name="building_age" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="new">{{ __('New') }}</option>
                <option value="5-10">5-10</option>
                <option value="10-11">10-11</option>
                <option value="11-20">11-20</option>
                <option value="20+">20+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bedrooms') }}</label>
            <select name="bedrooms" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="studio">{{ __('Studio') }}</option>
                @for($i = 1; $i <= 5; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                <option value="5+">5+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bathrooms') }}</label>
            <select name="bathrooms" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                @for($i = 1; $i <= 5; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                <option value="5+">5+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Land Area (m²)') }} ({{ __('from') }})</label>
            <input type="number" step="0.01" name="land_area_min" class="form-control" value="{{ old('land_area_min', optional($info)->land_area_min ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Land Area (m²)') }} ({{ __('to') }})</label>
            <input type="number" step="0.01" name="land_area_max" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Villa/House Area (m²)') }} ({{ __('from') }})</label>
            <input type="number" step="0.01" name="size" class="form-control" value="{{ old('size', optional($info)->size ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Villa/House Area (m²)') }} ({{ __('to') }})</label>
            <input type="number" step="0.01" name="size_max" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Furnished') }}</label>
            <select name="furnished" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="0">{{ __('Unfurnished') }}</option>
                <option value="1">{{ __('Furnished') }}</option>
            </select>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12"><h6 class="mb-2 mt-2">{{ __('Extra Features') }}</h6></div>
</div>
<div class="row {{ app()->getLocale() === 'ar' ? 'extra-features-rtl' : '' }}" dir="{{ app()->getLocale() === 'ar' ? 'rtl' : 'ltr' }}">
    @php $exFeat = old('extra_features', optional($info)->extra_features ?? []); if (!is_array($exFeat)) $exFeat = []; @endphp
    @foreach(['pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well'] as $key => $labelKey)
        <div class="col-md-4">
            <div class="form-check {{ app()->getLocale() === 'ar' ? 'extra-features-rtl' : '' }}">
                <input class="form-check-input" type="checkbox" name="extra_features[]" value="{{ $key }}" id="villa_{{ $key }}" {{ in_array($key, $exFeat) ? 'checked' : '' }}>
                <label class="form-check-label" for="villa_{{ $key }}">{{ __($labelKey) }}</label>
            </div>
        </div>
    @endforeach
</div>
