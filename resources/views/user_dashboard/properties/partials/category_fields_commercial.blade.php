{{-- مخازن/عقارات تجارية --}}
@php $info = $info ?? null; @endphp
<div class="row">
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Building Age') }}</label>
            <select name="building_age" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                <option value="new">{{ __('New') }}</option>
                <option value="5-10" {{ old('building_age', optional($info)->building_age) == '5-10' ? 'selected' : '' }}>5-10</option>
                <option value="10-11" {{ old('building_age', optional($info)->building_age) == '10-11' ? 'selected' : '' }}>10-11</option>
                <option value="11-20" {{ old('building_age', optional($info)->building_age) == '11-20' ? 'selected' : '' }}>11-20</option>
                <option value="20+" {{ old('building_age', optional($info)->building_age) == '20+' ? 'selected' : '' }}>20+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Bathrooms') }}</label>
            <select name="bathrooms" class="form-control form-select">
                <option value="">{{ __('Select') }}</option>
                @for($i = 1; $i <= 5; $i++) <option value="{{ $i }}">{{ $i }}</option> @endfor
                <option value="5+" {{ old('bathrooms', optional($info)->bathrooms) == '5+' ? 'selected' : '' }}>5+</option>
            </select>
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Storage Area (m²)') }} ({{ __('from') }})</label>
            <input type="number" step="0.01" name="size" class="form-control">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Storage Area (m²)') }} ({{ __('to') }})</label>
            <input type="number" step="0.01" name="size_max" class="form-control" value="{{ old('size_max', optional($info)->size_max ?? '') }}">
        </div>
    </div>
    <div class="col-md-4">
        <div class="form-group mb-3">
            <label class="form-label">{{ __('Floor') }}</label>
            <input type="text" name="floor" class="form-control" value="{{ old('floor', optional($info)->floor ?? '') }}">
        </div>
    </div>
</div>
