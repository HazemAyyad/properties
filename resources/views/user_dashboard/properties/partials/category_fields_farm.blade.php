{{-- مزرعة --}}
@php $info = $info ?? null; @endphp
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Building Age') }}</label>
            <input type="text" name="building_age" class="form-control" value="{{ old('building_age', optional($info)->building_age ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bedrooms') }}</label>
            <input type="number" name="bedrooms" class="form-control" value="{{ old('bedrooms', optional($info)->bedrooms ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Farm Area (m²)') }}</label>
            <input type="number" step="0.01" name="size" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bathrooms') }}</label>
            <input type="number" name="bathrooms" class="form-control" value="{{ old('bathrooms', optional($info)->bathrooms ?? '') }}">
        </div>
    </div>
</div>
<div class="row">
    <div class="col-12"><h6 class="mb-2">{{ __('Extra Features') }}</h6></div>
    @php $exFeat = old('extra_features', optional($info)->extra_features ?? []); if (!is_array($exFeat)) $exFeat = []; @endphp
    @foreach(['pool' => 'Pool', 'sewage_connected' => 'Sewage Connected', 'water_well' => 'Water Well'] as $key => $labelKey)
        <div class="col-md-4">
            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="extra_features[]" value="{{ $key }}" id="farm_{{ $key }}" {{ in_array($key, $exFeat) ? 'checked' : '' }}>
                <label class="form-check-label" for="farm_{{ $key }}">{{ __($labelKey) }}</label>
            </div>
        </div>
    @endforeach
</div>
