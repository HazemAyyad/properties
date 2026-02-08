@extends('dashboard.layouts.app')
@section('style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/css/form-validation.css') }}" />
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<style>
.form-select {
    border-radius: 0.375rem;
    padding: 6px 10px;
    min-width: 100px;
    max-width: 400px;
}

.select2-container--bootstrap-5 .select2-selection--single {
    border-radius: 5px !important;
    min-width: 100px !important;
    max-width: 500px !important;
}

.btn:hover {
    border-color: #fff;
}

button.accordion-button {
    border-radius: 20px !important;
    padding: 13px;
}

.accordion-button:not(.collapsed) {
    color: #000000;
}

.accordion-button:not(.collapsed) {
    background-color: #f0f0f0;
}

.accordion-button:focus {
    box-shadow: none;
    background-color: #f0f0f0;
    color: #000;
    font-weight: bold;
}

.accordion-button {
    position: relative;
    display: flex;
    align-items: center;
    width: 100%;
    padding: var(--bs-accordion-btn-padding-y) var(--bs-accordion-btn-padding-x);
    font-size: 1rem;
    color: var(--bs-accordion-btn-color);
    text-align: left;
    background-color: #f0f0f0;
    border: 0;
    border-radius: 0;
    overflow-anchor: none;
    transition: var(--bs-accordion-transition);
}

@media (max-width:991px) {
    .accordion-body {
        padding: 0px;
    }
}
</style>
@endsection
@section('content')

<!-- Content -->

<div class="container-xxl flex-grow-1 container-p-y">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">
                <a href="{{ route('dashboard') }}">{{ __('Home') }}</a>
            </li>
            <li class="breadcrumb-item">
                <a href="{{ route('shipment_form.index') }}">{{ __('Shipment Form') }}</a>
            </li>
            <li class="breadcrumb-item active">{{ __('Edit Shipment Form') }}</li>
            <!-- Basic table -->


            <!--/ Basic table -->
        </ol>
    </nav>
    <div class="row">
        <div class="col-xl">
            <div class="card mb-4">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ __('Edit Shipment Form') }}</h5>
                </div>
                <div class="card-body">
                    <form id="mainAdd" method="post" action="javascript:void(0)">
                        <div class="row justify-content-center">
                            <div class="col-12 col-md-12 col-lg-12 form-info ">
                                <label>{{ __('EMAIL') }}</label>
                                <input type="email" class="form-control" value="{{ $shipment_form->mail }}"
                                    placeholder="Email" id="mail" name="mail" />
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-6 col-12">
                                <div class="accordion accordion-flush mt-3" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingOne">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseOne" aria-expanded="true"
                                                aria-controls="collapseOne">
                                                <h6 class="mb-1">{{ __('SENDER DATA') }}</h6>
                                            </button>
                                        </h2>
                                        <div id="collapseOne" class="accordion-collapse collapse show"
                                            aria-labelledby="headingOne" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label for="editable-select">{{ __('Name') }}</label>

                                                                <input type="text" class="form-control" name="s-name"
                                                                    value="{{ $shipment_form->s_name }}" id="s-name"
                                                                    autocomplete="no_name" required>

                                                            </div>
                                                        </div>
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Country') }}</label>
                                                                <select required
                                                                    class="form-control js-example-basic-single1 "
                                                                    id="s-country" name="s-country">
                                                                    @if ($countries)
                                                                    <option></option>
                                                                    @foreach ($countries as $country)
                                                                    <option value="{{ $country->Country }}" @if
                                                                        ($shipment_form->s_country ===
                                                                        $country->Country) selected @endif>
                                                                        {{ $country->Country }}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Address') }}</label>
                                                                <input type="text" class="form-control" id="s-address"
                                                                    value="{{ $shipment_form->s_address }}"
                                                                    name="s-address" required />
                                                            </div>

                                                        </div>


                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('City') }}</label>
                                                                <input type="text" class="form-control" id="s-city"
                                                                    name="s-city" value="{{ $shipment_form->s_city }}"
                                                                    required />
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-6 col-lg-6 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('State') }}</label>
                                                                <input type="text" class="form-control" id="s-state"
                                                                    value="{{ $shipment_form->s_state }}" name="s-state"
                                                                    required />
                                                            </div>

                                                        </div>

                                                        <div class="col-12 col-md-6 col-lg-6 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Zip Code') }}</label>
                                                                <input type="text" class="form-control" id="s-code"
                                                                    value="{{ $shipment_form->s_code }}" name="s-code"
                                                                    required />
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Phone') }}</label>
                                                                <input type="number" class="form-control" id="s-phone"
                                                                    value="{{ $shipment_form->s_phone }}" name="s-phone"
                                                                    required />
                                                            </div>

                                                        </div>

                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6 col-12">
                                <div class="accordion accordion-flush mt-3" id="accordionExample">
                                    <div class="accordion-item">
                                        <h2 class="accordion-header" id="headingTwo">
                                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                                data-bs-target="#collapseTwo" aria-expanded="true"
                                                aria-controls="collapseTwo">
                                                <h6 class="mb-1">{{ __('RECEIVER DATA') }}</h6>
                                            </button>
                                        </h2>
                                        <div id="collapseTwo" class="accordion-collapse collapse show"
                                            aria-labelledby="headingTwo" data-bs-parent="#accordionExample">
                                            <div class="accordion-body">
                                                <div class="col-md-12">
                                                    <div class="row">
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label for="editable-select-1">{{ __('Name') }}</label>

                                                                <input type="text" class="form-control" name="r-name"
                                                                    id="r-name" autocomplete="no_name"
                                                                    value="{{ $shipment_form->r_name }}" required>

                                                            </div>


                                                        </div>
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Country') }}</label>
                                                                <select required
                                                                    class="form-control js-example-basic-single1 "
                                                                    id="r-country" name="r-country">
                                                                    @if ($countries)
                                                                    <option></option>
                                                                    @foreach ($countries as $country)
                                                                    <option value="{{ $country->Country }}" @if
                                                                        ($shipment_form->r_country ===
                                                                        $country->Country) selected @endif>
                                                                        {{ $country->Country }}</option>
                                                                    @endforeach
                                                                    @endif
                                                                </select>
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Address') }}</label>
                                                                <input type="text" class="form-control" id="r-address"
                                                                    value="{{ $shipment_form->r_address }}"
                                                                    name="r-address" required />
                                                            </div>

                                                        </div>


                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('City') }}</label>
                                                                <input type="text" class="form-control" id="r-city"
                                                                    value="{{ $shipment_form->r_city }}" name="r-city"
                                                                    required />
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-6 col-lg-6 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('State') }}</label>
                                                                <input type="text" class="form-control" id="r-state"
                                                                    value="{{ $shipment_form->r_state }}" name="r-state"
                                                                    required />
                                                            </div>

                                                        </div>

                                                        <div class="col-12 col-md-6 col-lg-6 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Zip Code') }}</label>
                                                                <input type="text" class="form-control" id="r-code"
                                                                    value="{{ $shipment_form->r_code }}"
                                                                    name="r-code" />
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-12 col-lg-12 form-info">
                                                            <div class="mb-2">
                                                                <label>{{ __('Phone') }}</label>
                                                                <input type="number" class="form-control" id="r-phone"
                                                                    value="{{ $shipment_form->r_phone }}" name="r-phone"
                                                                    required />
                                                            </div>

                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>

                            <div class="accordion accordion-flush mt-3" id="accordionExample">

                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingThree">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseThree" aria-expanded="true"
                                            aria-controls="collapseThree">
                                            <h6 class="mb-1">{{ __('Package Details') }}</h6>
                                        </button>
                                    </h2>
                                    <div id="collapseThree" class="accordion-collapse collapse show"
                                        aria-labelledby="headingThree" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="col-md-12 mt-3 text-end">
                                                <div class="col-md-12 mb-4" id="add_item_btn" style="display: none">
                                                    <button type="button" class="btn btn-success" data-bs-toggle="modal"
                                                        data-bs-target="#newItem">
                                                        Add New Package
                                                    </button>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="row">
                                                    <div class="col-md-12">
                                                        <div class="table-responsive">
                                                            <table class="table" id="package_items">
                                                                <thead>
                                                                    <tr>
                                                                        <th class="text-center">#</th>
                                                                        <th class="text-center">Type</th>
                                                                        <th class="text-center">Weight</th>
                                                                        <th class="text-center">Dimensions </th>
                                                                        <th class="text-center">Insurance</th>
                                                                        <th class="text-center">Custom ItemTotal</th>
                                                                        <th class="text-center">Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody class="text-center">
                                                                    @foreach ($shipment_form->shipments_form_packages as
                                                                    $package)
                                                                    <td>{{ $loop->iteration }}</td>
                                                                    <td>{{ $package->pkg_type }}</td>
                                                                    <td>{{ $package->weight }}</td>
                                                                    <td>{{ $package->length . '*' . $package->width . '*' . $package->height }}
                                                                    </td>
                                                                    <td>{{ $package->insurance }}</td>
                                                                    <td>{{ $package->final_total }}</td>
                                                                    <td class='text-center'><a href='javascript:;'
                                                                            data-bs-toggle="modal"
                                                                            data-bs-target="#updateItem{{$package->id}}"
                                                                            class='  text-success text-decoration-none   me-2 btn-update-tr '><i
                                                                                class='fa fa-edit'></i></a></td>
                                                                    <div class="modal fade"
                                                                        id="updateItem{{$package->id}}" tabindex="-1"
                                                                        role="dialog" aria-labelledby="basicModal"
                                                                        aria-hidden="true">
                                                                        <div
                                                                            class="modal-dialog modal-dialog-centered modal-lg">
                                                                            <div class="modal-content modal-video">
                                                                                <div class="modal-header text-center"
                                                                                    style="border-bottom: 0px;">
                                                                                    <div class="row"
                                                                                        style="width: 100%;">
                                                                                        <div class="col-12 text-center">
                                                                                            <h4 class="modal-title text-center fw-bold"
                                                                                                id="myModalLabel">
                                                                                                {{ __('Update Package') }}
                                                                                            </h4>
                                                                                        </div>
                                                                                    </div>
                                                                                    <button type="button"
                                                                                        class="btn-close"
                                                                                        data-bs-dismiss="modal"
                                                                                        aria-label="Close"></button>

                                                                                </div>
                                                                                <div class="modal-body  ">

                                                                                    <form action="javascript:void(0)"
                                                                                        id="form_update_package"
                                                                                        name="form_update_package">
                                                                                        <input type="hidden"
                                                                                            name="update_index"
                                                                                            id="update_index">
                                                                                        <div id="sections" class="row">
                                                                                            <div
                                                                                                class="section col-md-12">
                                                                                                <div class="row">

                                                                                                    <div
                                                                                                        class="col-12 col-md-6 col-lg-6 form-info">
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label
                                                                                                                for="pkg_type">
                                                                                                                {{ __('Type') }}
                                                                                                            </label>

                                                                                                            <select
                                                                                                                name="pkg_type"
                                                                                                                id="pkg_type"
                                                                                                                class="form-select pkg_type">
                                                                                                                <option
                                                                                                                    value="Package"
                                                                                                                    selected>
                                                                                                                    Package
                                                                                                                </option>

                                                                                                                <option
                                                                                                                    value="Documents"
                                                                                                                    id="document">
                                                                                                                    Document
                                                                                                                </option>

                                                                                                                <option
                                                                                                                    value="Pallet">
                                                                                                                    Pallet
                                                                                                                </option>
                                                                                                            </select>

                                                                                                        </div>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-12 col-md-6 col-lg-6  form-info">
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label>{{ __('Weight') }}</label>
                                                                                                            <div
                                                                                                                class="input-group mb-3">
                                                                                                                <input
                                                                                                                    type="number"
                                                                                                                    min="0"
                                                                                                                    class="form-control"
                                                                                                                    value="0"
                                                                                                                    id="weight"
                                                                                                                    name="weight"
                                                                                                                    value="{{$package->weight}}"
                                                                                                                    required>
                                                                                                                <div
                                                                                                                    class="input-group-prepend dropdown">
                                                                                                                    <a class="btn btn-outline-secondary dropdown-toggle "
                                                                                                                        id="button_unit_weight"
                                                                                                                        style="background-color: #2f2f2f;color: #fff;"
                                                                                                                        type="button"
                                                                                                                        data-bs-toggle="dropdown"
                                                                                                                        aria-haspopup="true"
                                                                                                                        aria-expanded="false">lbs</a>
                                                                                                                    <div class="dropdown-menu"
                                                                                                                        style="width: 50%;">
                                                                                                                        <a class="dropdown-item"
                                                                                                                            onclick="($('#unit_weight').val('lbs'),$('#button_unit_weight').text('Lbs'),$(this).parent().removeClass('show'))"
                                                                                                                            href="javascript:void(0)">lbs</a>
                                                                                                                        <a class="dropdown-item"
                                                                                                                            onclick="($('#unit_weight').val('kg'),$('#button_unit_weight').text('KG'),$(this).parent().removeClass('show'))"
                                                                                                                            href="javascript:void(0)">Kg</a>
                                                                                                                        <input
                                                                                                                            type="text"
                                                                                                                            name="unit_weight"
                                                                                                                            id="unit_weight"
                                                                                                                            value="lbs"
                                                                                                                            hidden>
                                                                                                                    </div>
                                                                                                                </div>
                                                                                                            </div>
                                                                                                        </div>

                                                                                                    </div>
                                                                                                    <div class="col-12 col-md-12 col-lg-12 form-info"
                                                                                                        id="dimension">
                                                                                                        <div
                                                                                                            class="mb-2">
                                                                                                            <label>{{ __('Dimensions') }}</label>
                                                                                                            <div
                                                                                                                class="input-group mb-3">
                                                                                                                <input
                                                                                                                    type="number"
                                                                                                                    min="0"
                                                                                                                    id="height"
                                                                                                                    name="height"
                                                                                                                    value="{{$package->height}}"
                                                                                                                    class="form-control"
                                                                                                                    aria-label="Text input with dropdown button"
                                                                                                                    required>
                                                                                                                <input
                                                                                                                    type="number"
                                                                                                                    min="0"
                                                                                                                    id="width"
                                                                                                                    name="width"
                                                                                                                    value="{{$package->width}}"
                                                                                                                    class="form-control"
                                                                                                                    aria-label="Text input with dropdown button"
                                                                                                                    required>
                                                                                                                <input
                                                                                                                    type="number"
                                                                                                                    min="0"
                                                                                                                    id="length"
                                                                                                                    name="length"
                                                                                                                    value="{{$package->length}}"
                                                                                                                    class="form-control"
                                                                                                                    aria-label="Text input with dropdown button"
                                                                                                                    required>
                                                                                                                <div
                                                                                                                    class="input-group-prepend dropdown">
                                                                                                                    <a class="btn btn-outline-secondary dropdown-toggle "
                                                                                                                        id="button_unit_height"
                                                                                                                        style="background-color: #2f2f2f;color: #fff;"
                                                                                                                        type="button"
                                                                                                                        data-bs-toggle="dropdown"
                                                                                                                        aria-haspopup="true"
                                                                                                                        aria-expanded="false">inches</a>
                                                                                                                    <div class="dropdown-menu"
                                                                                                                        style="width: 50%;">
                                                                                                                        <a class="dropdown-item"
                                                                                                                            onclick="($('#unit_height').val('inches'),$('#button_unit_height').text('inches'),$(this).parent().removeClass('show'))"
                                                                                                                            href="javascript:void(0)">inches</a>
                                                                                                                        <a class="dropdown-item"
                                                                                                                            onclick="($('#unit_height').val('cm'),$('#button_unit_height').text('cm'),$(this).parent().removeClass('show'))"
                                                                                                                            href="javascript:void(0)">CM</a>
                                                                                                                        <input
                                                                                                                            type="text"
                                                                                                                            name="unit_height"
                                                                                                                            id="unit_height"
                                                                                                                            value="inches"
                                                                                                                            hidden>
                                                                                                                    </div>
                                                                                                                </div>

                                                                                                            </div>
                                                                                                        </div>

                                                                                                        <a href="#"
                                                                                                            class="btn btn-danger remove hidden"
                                                                                                            id="remove"><i
                                                                                                                class="fa fa-trash"
                                                                                                                aria-hidden="true"></i></a>
                                                                                                    </div>
                                                                                                    <div
                                                                                                        class="col-12 col-md-12 col-lg-12">
                                                                                                        <h4
                                                                                                            class="fs-2 fw-bold mb-4">
                                                                                                            {{ __("What's in the Box?") }}
                                                                                                        </h4>
                                                                                                    </div>
                                                                                                    <div id="sections_items"
                                                                                                        class="col-12 col-md-12 col-lg-12 form-info">
                                                                                                        <div
                                                                                                            class="section_item">
                                                                                                            <div
                                                                                                                class="row">
                                                                                                                <div
                                                                                                                    class="col-12 col-md-4 col-lg-4 form-info">
                                                                                                                    <div
                                                                                                                        class="mb-2">
                                                                                                                        <label>{{ __('Content') }}</label>

                                                                                                                        <input
                                                                                                                            type="text"
                                                                                                                            class="form-control item-content"
                                                                                                                            id="content"
                                                                                                                            value="{{$package->shipments_form_items[0]['content']}}"
                                                                                                                            name="items[0][content]"
                                                                                                                            required />

                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="col-12 col-md-2 col-lg-2 form-info">
                                                                                                                    <div
                                                                                                                        class="mb-2">
                                                                                                                        <label>{{ __('Value') }}</label>
                                                                                                                        <div
                                                                                                                            class="input-group mb-3">
                                                                                                                            <input
                                                                                                                                type="number"
                                                                                                                                min="0"
                                                                                                                                class="form-control item-value"
                                                                                                                                id="value"
                                                                                                                                value="{{$package->shipments_form_items[0]['value']}}"
                                                                                                                                onchange="update_calculate_total(0)"
                                                                                                                                name="items[0][value]"
                                                                                                                                aria-label=""
                                                                                                                                aria-describedby="basic-addon2"
                                                                                                                                required>
                                                                                                                            <div
                                                                                                                                class="input-group-append">
                                                                                                                                <span
                                                                                                                                    class="input-group-text form-control w-100"
                                                                                                                                    style="background: #2f2f2f; color: #ffff;"
                                                                                                                                    id="basic-addon2">$</span>
                                                                                                                            </div>
                                                                                                                        </div>
                                                                                                                    </div>

                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="col-12 col-md-2 col-lg-2 form-info">
                                                                                                                    <div
                                                                                                                        class="mb-2">
                                                                                                                        <label>{{ __('Quantity') }}</label>

                                                                                                                        <input
                                                                                                                            type="number"
                                                                                                                            min="0"
                                                                                                                            class="form-control item-quantity"
                                                                                                                            id="quantity"
                                                                                                                            value="{{$package->shipments_form_items[0]['quantity']}}"
                                                                                                                            onchange="update_calculate_total(0)"
                                                                                                                            name="items[0][quantity]"
                                                                                                                            required />

                                                                                                                    </div>
                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="col-12 col-md-2 col-lg-2 form-info">
                                                                                                                    <div
                                                                                                                        class="mb-2">
                                                                                                                        <label>{{ __('total') }}</label>
                                                                                                                        <input
                                                                                                                            type="number"
                                                                                                                            readonly
                                                                                                                            min="0"
                                                                                                                            class="form-control final_total item-total"
                                                                                                                            id="total"
                                                                                                                            value="{{$package->shipments_form_items[0]['total']}}"
                                                                                                                            name="items[0][total]"
                                                                                                                            required />
                                                                                                                    </div>

                                                                                                                </div>
                                                                                                                <div
                                                                                                                    class="col-12 col-md-1 col-lg-1 form-info">
                                                                                                                    <div
                                                                                                                        class="mb-2">
                                                                                                                        <label
                                                                                                                            style="opacity: 0;">
                                                                                                                        </label>
                                                                                                                        <a href="#"
                                                                                                                            class="btn btn-danger removeItemUpdate hidden"
                                                                                                                            id="removeItem"
                                                                                                                            data-total="0"><i
                                                                                                                                class="fa fa-trash"
                                                                                                                                aria-hidden="true"></i></a>
                                                                                                                    </div>

                                                                                                                </div>
                                                                                                            </div>
                                                                                                            <hr>
                                                                                                        </div>
                                                                                                        <div
                                                                                                            class="btn_item">

                                                                                                            <div
                                                                                                                class="package">
                                                                                                                <a href="javascript:;"
                                                                                                                    class="addsectionItemUpdate"
                                                                                                                    id="addNewItemUpdate"
                                                                                                                    style="color: #e2342b;"><br />
                                                                                                                    <button
                                                                                                                        type="submit"
                                                                                                                        class="btn btn-success waves-effect waves-light">
                                                                                                                        {{ __('Add Another Item') }}
                                                                                                                    </button>
                                                                                                                </a>
                                                                                                            </div>
                                                                                                        </div>
                                                                                                    </div>






                                                                                                </div>
                                                                                                <hr>
                                                                                            </div>
                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div
                                                                                                class="col-12 col-md-6 col-lg-6  ">
                                                                                                <div class="mb-2">
                                                                                                    <label>{{ __('Insurance') }}</label>
                                                                                                    <div
                                                                                                        class="input-group mb-3">
                                                                                                        <input
                                                                                                            type="number"
                                                                                                            min="0"
                                                                                                            class="form-control"
                                                                                                            id="insurance"
                                                                                                            name="insurance"
                                                                                                            value="{{$package->insurance}}"
                                                                                                            value="0"
                                                                                                            aria-label=""
                                                                                                            aria-describedby="basic-addon2"
                                                                                                            required>
                                                                                                        <div
                                                                                                            class="input-group-append">
                                                                                                            <span
                                                                                                                class="input-group-text form-control w-100"
                                                                                                                style="background: #2f2f2f; color: #ffff;"
                                                                                                                id="basic-addon2">$</span>
                                                                                                        </div>
                                                                                                    </div>
                                                                                                </div>



                                                                                            </div>
                                                                                            <div
                                                                                                class="col-12 col-md-6 col-lg-6  ">
                                                                                                <div class="mb-2">
                                                                                                    <label>{{ __('Final Total') }}</label>
                                                                                                    <input
                                                                                                        id="final_total"
                                                                                                        name="final_total"
                                                                                                        type="text"
                                                                                                        value="{{$package->final_total}}"
                                                                                                        readonly
                                                                                                        class="form-control ">

                                                                                                </div>



                                                                                            </div>

                                                                                        </div>
                                                                                        <div class="row">
                                                                                            <div class="col-md-12">
                                                                                                <div
                                                                                                    class="d-flex justify-content-center   mt-4 ">
                                                                                                    <button
                                                                                                        class="btn btn-main btn-continue btn-success">{{ __('Add ') }}</button>
                                                                                                </div>
                                                                                            </div>
                                                                                        </div>
                                                                                    </form>


                                                                                </div>
                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    @endforeach
                                                                </tbody>
                                                            </table>
                                                        </div>


                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <hr>


                            <div class="accordion accordion-flush mt-3" id="accordionExample">
                                <div class="accordion-item">
                                    <h2 class="accordion-header" id="headingFour">
                                        <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                            data-bs-target="#collapseFour" aria-expanded="true"
                                            aria-controls="collapseFour">
                                            <h6 class="mb-1">{{ __('Pickup Date And Time') }}</h6>
                                        </button>
                                    </h2>
                                    <div id="collapseFour" class="accordion-collapse collapse show"
                                        aria-labelledby="headingFour" data-bs-parent="#accordionExample">
                                        <div class="accordion-body">
                                            <div class="col-md-12 mt-4">
                                                <div class="form-group">
                                                    <h3>{{ __('Pickup Date And Time') }}</h3>
                                                    <div class="row">
                                                        <div class="col-12 col-md-4 col-lg-4 form-info">
                                                            <div class="mb-2">
                                                                <label for="pickup_date">{{ __('Pickup Date') }}</label>
                                                                <div class="input-group">
                                                                    <input type="text"
                                                                        class="form-control docs-date flat_date"
                                                                        name="pickup_date"
                                                                        value="{{ $shipment_form->pickup_date }}" id=""
                                                                        placeholder="Pick a date">
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary docs-datepicker-trigger">
                                                                            <i class="fa fa-calendar"
                                                                                aria-hidden="true"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                            {{-- <input type="text" class="form-control" id="pickup_date" data-toggle="datepicker" name="pickup_date" required/> --}}
                                                        </div>
                                                        <div class="col-12 col-md-4 col-lg-4 form-info">
                                                            <div class="mb-2">
                                                                <label
                                                                    for="start_time">{{ __('Start Pickup Time') }}</label>
                                                                <div class="input-group">
                                                                    <input
                                                                        class="flatpickr flatpickr-input form-control active flat_time"
                                                                        name="start_time" id="start_time" type="text"
                                                                        value="{{ $shipment_form->start_time }}"
                                                                        placeholder="pick time" data-id="timePicker"
                                                                        readonly="readonly">
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-toggle="start_time">
                                                                            <i class="far fa-clock"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>

                                                        </div>
                                                        <div class="col-12 col-md-4 col-lg-4 form-info">
                                                            <div class="mb-2">
                                                                <label
                                                                    for="end_time">{{ __('End Pickup Time') }}</label>
                                                                <div class="input-group">
                                                                    <input
                                                                        class="flatpickr flatpickr-input form-control active flat_time"
                                                                        name="end_time"
                                                                        value="{{ $shipment_form->end_time }}"
                                                                        id="end_time" type="text"
                                                                        placeholder="pick time" data-id="timePicker"
                                                                        readonly="readonly">
                                                                    <div class="input-group-append">
                                                                        <button type="button"
                                                                            class="btn btn-outline-secondary"
                                                                            data-toggle="end_time">
                                                                            <i class="far fa-clock"></i>
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
                                </div>
                            </div>


                            <hr>
                            <button type="submit" id="add_form" style="width:30%; margin:auto"
                                class="btn btn-send-now btn-success d-inline-flex">{{ __('Send Now') }} <i
                                    class="fa-regular fa-paper-plane m-1"></i>
                            </button>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
<!-- / Content -->
<div class="modal fade" id="newItem" tabindex="-1" role="dialog" aria-labelledby="basicModal" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content modal-video">
            <div class="modal-header text-center" style="border-bottom: 0px;">
                <div class="row" style="width: 100%;">
                    <div class="col-12 text-center">
                        <h4 class="modal-title text-center fw-bold" id="myModalLabel">{{ __('Add Package') }}</h4>
                    </div>
                </div>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>

            </div>
            <div class="modal-body  ">

                <form action="javascript:void(0)" id="form_add_package" name="form_add_package">
                    <div id="sections" class="row">
                        <div class="section col-md-12">
                            <div class="row">

                                <div class="col-12 col-md-6 col-lg-6 form-info">
                                    <div class="mb-2">
                                        <label for="pkg_type"> {{ __('Type') }} </label>

                                        <select name="pkg_type" id="pkg_type" class="form-select pkg_type">
                                            <option value="Package" selected>Package</option>

                                            <option value="Documents" id="document">Document</option>

                                            <option value="Pallet">Pallet</option>
                                        </select>

                                    </div>
                                </div>
                                <div class="col-12 col-md-6 col-lg-6  form-info">
                                    <div class="mb-2">
                                        <label>{{ __('Weight') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="number" min="0" class="form-control" value="0" id="weight"
                                                name="weight" required>
                                            <div class="input-group-prepend dropdown">
                                                <a class="btn btn-outline-secondary dropdown-toggle "
                                                    id="button_unit_weight"
                                                    style="background-color: #2f2f2f;color: #fff;" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">lbs</a>
                                                <div class="dropdown-menu" style="width: 50%;">
                                                    <a class="dropdown-item"
                                                        onclick="($('#unit_weight').val('lbs'),$('#button_unit_weight').text('Lbs'),$(this).parent().removeClass('show'))"
                                                        href="javascript:void(0)">lbs</a>
                                                    <a class="dropdown-item"
                                                        onclick="($('#unit_weight').val('kg'),$('#button_unit_weight').text('KG'),$(this).parent().removeClass('show'))"
                                                        href="javascript:void(0)">Kg</a>
                                                    <input type="text" name="unit_weight" id="unit_weight" value="lbs"
                                                        hidden>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>
                                <div class="col-12 col-md-12 col-lg-12 form-info" id="dimension">
                                    <div class="mb-2">
                                        <label>{{ __('Dimensions') }}</label>
                                        <div class="input-group mb-3">
                                            <input type="number" min="0" id="height" name="height" class="form-control"
                                                aria-label="Text input with dropdown button" required>
                                            <input type="number" min="0" id="width" name="width" class="form-control"
                                                aria-label="Text input with dropdown button" required>
                                            <input type="number" min="0" id="length" name="length" class="form-control"
                                                aria-label="Text input with dropdown button" required>
                                            <div class="input-group-prepend dropdown">
                                                <a class="btn btn-outline-secondary dropdown-toggle "
                                                    id="button_unit_height"
                                                    style="background-color: #2f2f2f;color: #fff;" type="button"
                                                    data-bs-toggle="dropdown" aria-haspopup="true"
                                                    aria-expanded="false">inches</a>
                                                <div class="dropdown-menu" style="width: 50%;">
                                                    <a class="dropdown-item"
                                                        onclick="($('#unit_height').val('inches'),$('#button_unit_height').text('inches'),$(this).parent().removeClass('show'))"
                                                        href="javascript:void(0)">inches</a>
                                                    <a class="dropdown-item"
                                                        onclick="($('#unit_height').val('cm'),$('#button_unit_height').text('cm'),$(this).parent().removeClass('show'))"
                                                        href="javascript:void(0)">CM</a>
                                                    <input type="text" name="unit_height" id="unit_height"
                                                        value="inches" hidden>
                                                </div>
                                            </div>

                                        </div>
                                    </div>

                                    <a href="#" class="btn btn-danger remove hidden" id="remove"><i class="fa fa-trash"
                                            aria-hidden="true"></i></a>
                                </div>
                                <div class="col-12 col-md-12 col-lg-12">
                                    <h4 class="fs-2 fw-bold mb-4">{{ __("What's in the Box?") }}</h4>
                                </div>
                                <div id="sections_items" class="col-12 col-md-12 col-lg-12 form-info">
                                    <div class="section_item">
                                        <div class="row">
                                            <div class="col-12 col-md-4 col-lg-4 form-info">
                                                <div class="mb-2">
                                                    <label>{{ __('Content') }}</label>

                                                    <input type="text" class="form-control item-content" id="content"
                                                        name="items[0][content]" required />

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-2 col-lg-2 form-info">
                                                <div class="mb-2">
                                                    <label>{{ __('Value') }}</label>
                                                    <div class="input-group mb-3">
                                                        <input type="number" min="0" class="form-control item-value"
                                                            id="value" onchange="calculate_total(0)"
                                                            name="items[0][value]" aria-label=""
                                                            aria-describedby="basic-addon2" required>
                                                        <div class="input-group-append">
                                                            <span class="input-group-text form-control w-100"
                                                                style="background: #2f2f2f; color: #ffff;"
                                                                id="basic-addon2">$</span>
                                                        </div>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="col-12 col-md-2 col-lg-2 form-info">
                                                <div class="mb-2">
                                                    <label>{{ __('Quantity') }}</label>

                                                    <input type="number" min="0" class="form-control item-quantity"
                                                        id="quantity" onchange="calculate_total(0)"
                                                        name="items[0][quantity]" required />

                                                </div>
                                            </div>
                                            <div class="col-12 col-md-2 col-lg-2 form-info">
                                                <div class="mb-2">
                                                    <label>{{ __('total') }}</label>
                                                    <input type="number" readonly min="0"
                                                        class="form-control final_total item-total" id="total"
                                                        name="items[0][total]" required />
                                                </div>

                                            </div>
                                            <div class="col-12 col-md-1 col-lg-1 form-info">
                                                <div class="mb-2">
                                                    <label style="opacity: 0;"> </label>
                                                    <a href="#" class="btn text-danger removeItem hidden"
                                                        id="removeItem" data-total="0"><i class="fa fa-trash"
                                                            aria-hidden="true"></i></a>
                                                </div>

                                            </div>
                                        </div>
                                        <hr>
                                    </div>
                                    <div class="btn_item">

                                        <div class="package">
                                            <a href="javascript:;" class="addsectionItem" id="addNewItem"
                                                style="color: #e2342b;"><br />
                                                <button type="submit" class="btn btn-success waves-effect waves-light">
                                                    {{ __('Add Another Item') }}
                                                </button>
                                            </a>
                                        </div>
                                    </div>
                                </div>






                            </div>
                            <hr>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12 col-md-6 col-lg-6  ">
                            <div class="mb-2">
                                <label>{{ __('Insurance') }}</label>
                                <div class="input-group mb-3">
                                    <input type="number" min="0" class="form-control" id="insurance" name="insurance"
                                        value="0" aria-label="" aria-describedby="basic-addon2" required>
                                    <div class="input-group-append">
                                        <span class="input-group-text form-control w-100"
                                            style="background: #2f2f2f; color: #ffff;" id="basic-addon2">$</span>
                                    </div>
                                </div>
                            </div>



                        </div>
                        <div class="col-12 col-md-6 col-lg-6  ">
                            <div class="mb-2">
                                <label>{{ __('Final Total') }}</label>
                                <input id="final_total" name="final_total" type="text" readonly class="form-control ">

                            </div>



                        </div>

                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="d-flex justify-content-center   mt-4 ">
                                <button class="btn btn-main btn-continue btn-success">{{ __('Add ') }}</button>
                            </div>
                        </div>
                    </div>
                </form>


            </div>
        </div>
    </div>
</div>

@endsection
@section('scripts')
<!-- BEGIN: Page Vendor JS-->

<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/js/jquery.validate.min.js') }}"></script>
<!-- END: Page JS-->
<script src="https://cdn.ckeditor.com/4.20.0/standard/ckeditor.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"
    integrity="sha512-rstIgDs0xPgmG6RX1Aba4KV5cWJbAMcvRCVmglpam9SoHZiUCyQVDdH2LPlxoHtrv17XWblE/V/PP+Tr04hbtA=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- <script>
        $(document).ready(function() {
            $('#s-country').select2({
                theme: 'bootstrap-5',
                placeholder: "Select a country",
            });
            $('#r-country').select2({
                theme: 'bootstrap-5',
                placeholder: "Select a country",
            });
        });
    </script> --}}
<script !src="">
/**
 * Selects & Tags
 */

'use strict';

$(function() {
    const select2 = $('.select2');

    if (select2.length) {
        select2.each(function() {
            var $this = $(this);
            $this.wrap('<div class="position-relative"></div>').select2({
                placeholder: 'Select value',
                dropdownParent: $this.parent()
            });
        });
    }

});
</script>
<script>
var data_url = '{{ route('
shipment_form.update ', $shipment_form->id) }}'

$(document).ready(function() {
    function myHandel(obj, id) {
        if ('responseJSON' in obj)
            obj.messages = obj.responseJSON;
        $('input,select,textarea').each(function() {
            var parent = "";
            if ($(this).parents('.fv-row').length > 0)
                parent = $(this).parents('.fv-row');
            if ($(this).parents('.input-group').length > 0)
                parent = $(this).parents('.input-group');
            var name = $(this).attr("name");
            if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                var error_message =
                    '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj
                    .messages[name][0] + '</p></div>'
                parent.append(error_message);
            }
        });
    }

    $(document).on("click", "#add_form", function() {
        var form = $(this.form);
        if (!form.valid()) {
            return false
        };
        if (form.valid()) {
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }

            });
            var postData = new FormData(this.form);
            @foreach($lang as $item)
            postData.append('description_{{ $item }}', CKEDITOR.instances[
                'description_{{ $item }}'].getData());
            @endforeach
            $('#add_form').html('');
            $('#add_form').append(
                '<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                '<span class="ml-25 align-middle">{{ __('
                Saving ') }}...</span>');
            $.ajax({
                url: data_url,
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#add_form').html('{{ __('
                        Save ') }}');
                    setTimeout(function() {
                        toastr['success'](
                            response.success, {
                                closeButton: true,
                                tapToDismiss: false
                            }
                        );
                    }, 200);

                    $('.custom-error').remove();

                },
                error: function(data) {
                    $('.custom-error').remove();
                    $('#add_form').empty();
                    $('#add_form').html('{{ __('
                        Save ') }}');
                    var response = data.responseJSON;
                    if (data.status == 422) {
                        if (typeof(response.responseJSON) != "undefined") {
                            myHandel(response);
                            setTimeout(function() {
                                toastr['error'](
                                    response.message, {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);
                        }
                    } else {
                        swal.fire({
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
<script type="text/javascript">
@foreach($lang as $item)
CKEDITOR.replace('description_{{ $item }}', {
    filebrowserUploadUrl: "{{ route('ckeditor.image-upload', ['_token' => csrf_token()]) }}",
    filebrowserUploadMethod: 'form',
    allowedContent: true
});
@endforeach
</script>
<!-- Add Another Package -->
<script>
//define template
var template = $('#sections .section:first').clone();
// console.log(template)
//define counter
var sectionsCount = 0;
var counter = 1;

//add new section
$('body').on('click', '.addsection', function() {
    // var counter = $("#addNew").attr("data-number");

    //increment
    sectionsCount++;
    counter++;

    // $("#addNew").attr("data-number",counter);
    // document.getElementById("count_section").innerHTML = counter;


    // $('#number_packages').val($("#sections > div").length + 1)
    //loop through each input
    var section = template.clone().find(':input').each(function() {

        //set id to store the updated section number
        var newId = this.id + sectionsCount;
        var name = this.name.replace(/\d+/g, +sectionsCount);

        //update for label
        $(this).prev().attr('for', newId);

        //update id
        this.id = newId;
        this.name = name;
        // $('#count_section').attr('id','count_section'+sectionsCount)
        $(this).attr('onchange', 'calculate_total_section(' + sectionsCount + ')');
        // template.clone().find('h3').html('Package ' + sectionsCount + '')

    }).end()

    //inject new section
    // .prependTo('#sections');
    $('#sections').append(section);
    section.find('a').removeClass('hidden'); // remove hidden class from link
    section.find('a').attr('id', 'removeSection' + sectionsCount); // remove hidden class from link
    section.find('span').html($("#sections > div").length); // remove hidden class from link

    return false;
});

//remove section
$('#sections').on('click', '.remove', function() {
    //fade out section

    // $('#number_packages').val($("#sections > div").length - 1)
    $(this).parent().parent().parent().fadeOut(300, function() {
        //remove parent element (main section)
        $(this).remove();
        $('#sections .section').each(function(index) {

            $(this).find('span').html(index + 1);
        }).end()
        var sum = 0;
        $('.final_total').each(function() {
            sum += +$(this).val();
        });
        $('#final_total').val(sum);
        $(".pkg_type").change(function() {
            var val = $(this).val();

            if (val === "Documents") {
                $("#dimension").show();
            } else {
                $("#dimension").show();
            }
        });
        return false;
    });


    return false;
});
$('#sections').on('change', '.pkg_type', function() {
    //fade out section

    var val = $(this).val();
    if (val === "Documents") {
        $(this).parent().parent().find(':input').each(function() {

            $(this).removeAttr('required');


        })
        $(this).parent().parent().find('#dimension').hide();
    } else {
        $(this).parent().parent().find(':input').each(function() {

            $(this).attr('required');


        })
        $(this).parent().parent().find('#dimension').show();
    }

});
</script>
<!-- Add Another Item -->
<script>
//define template
var template_item = $('#sections_items .section_item:first').clone();

//define counter
var sectionsCount_item = 0;
//add new section
$('body').on('click', '.addsectionItem', function() {

    //increment
    sectionsCount_item++;


    var section_item = template_item.clone().find(':input').each(function() {

            //set id to store the updated section number
            var newId_item = this.id + sectionsCount_item;

            var name_item = this.name.replace(/\[\d+\]/g, '[' + sectionsCount_item + ']');
            //update for label
            $(this).prev().attr('for', newId_item);
            //update id
            this.id = newId_item;
            this.name = name_item;
            $(this).attr('onchange', 'calculate_total(' + sectionsCount_item + ')');

        }).end()

        //inject new section
        .insertBefore($(this).parent().parent());
    section_item.find('a').removeClass('hidden'); // remove hidden class from link

    section_item.find('a').attr('id', 'removeItem' + sectionsCount_item); // remove hidden class from link

    return false;
});

//remove section
$('#sections_items').on('click', '.removeItem', function() {


    //fade out section
    // $('#number_packages').val($("#sections_items > div").length-1)
    $(this).closest(".section_item").fadeOut(300, function() {
        //remove parent element (main section)
        $(this).remove();
        var sum = $('#final_total').val() - $(this).find(".item-total").val();

        $('#final_total').val(sum);
        return false;
    });
    return false;

});
</script>
<!-- Add Another Item Update -->
<script>
//define template
var template_item_update = $('#updateItem #sections_items .section_item:first').clone();
console.log(template_item_update)

//define counter
// var sectionsCount_item_update = 0;
//add new section
$('body').on('click', '.addsectionItemUpdate', function() {
    var sectionsCount_item_update = $("#updateItem #sections_items .section_item").length;

    //increment



    var section_item_update = template_item_update.clone().find(':input').each(function() {

            //set id to store the updated section number
            var newId_item_update = this.id + sectionsCount_item_update;

            var name_item_update = this.name.replace(/\[\d+\]/g, '[' + sectionsCount_item_update + ']');
            //update for label
            $(this).prev().attr('for', newId_item_update);
            //update id
            this.id = newId_item_update;
            this.name = name_item_update;
            $(this).attr('onchange', 'update_calculate_total(' + sectionsCount_item_update + ')');

        }).end()

        //inject new section
        .insertBefore($(this).parent().parent());
    section_item_update.find('a').removeClass('hidden'); // remove hidden class from link

    section_item_update.find('a').attr('id', 'removeItem' +
        sectionsCount_item_update); // remove hidden class from link

    return false;
});

//remove section
$('#updateItem #sections_items').on('click', '.removeItemUpdate', function() {


    //fade out section
    // $('#number_packages').val($("#sections_items > div").length-1)
    $(this).closest("#updateItem .section_item").fadeOut(300, function() {
        //remove parent element (main section)

        $(this).remove();
        var sum = $('#updateItem #final_total').val() - $(this).find(".item-total").val();

        $('#updateItem #final_total').val(sum);
        return false;
    });
    return false;

});
</script>
<script>
function calculate_total(id) {

    if (id == 0) {
        var value = $('#newItem input[name="items[0][value]"]').val();
        var qty = $('#newItem input[name="items[0][quantity]"]').val();
        if (value !== '' && qty !== '') {
            var total = value * qty;
            $('#newItem input[name="items[0][total]"]').val(total);
            $('#newItem #removeItem').attr('data-total', total); // sets
        }
    } else {
        var value = $('#newItem input[name="items[' + id + '][value]"]').val();
        var qty = $('#newItem input[name="items[' + id + '][quantity]"]').val();
        if (value !== '' && qty !== '') {
            var total = value * qty;
            $('#newItem input[name="items[' + id + '][total]"]').val(total);
            $('#newItem #removeItem' + id).attr('data-total', total); // sets
        }
    }
    var totals = [];
    var sum = 0;
    $('#newItem .final_total').each(function() {
        sum += +$(this).val();

    });
    $('#newItem #final_total').val(sum);
    $("#newItem #insurance").attr({
        "max": sum
    });
}

function update_calculate_total(id) {
    if (id == 0) {
        var value = $('#updateItem input[name="items[0][value]"]').val();
        var qty = $('#updateItem input[name="items[0][quantity]"]').val();
        if (value !== '' && qty !== '') {
            var total = value * qty;
            $('#updateItem input[name="items[0][total]"]').val(total);
            $('#removeItemUpdate').attr('data-total', total); // sets
        }
    } else {
        var value = $('#updateItem input[name="items[' + id + '][value]"]').val();
        var qty = $('#updateItem input[name="items[' + id + '][quantity]"]').val();
        if (value !== '' && qty !== '') {
            var total = value * qty;
            $('#updateItem input[name="items[' + id + '][total]"]').val(total);
            $('#updateItem #removeItemUpdate' + id).attr('data-total', total); // sets
        }
    }
    var totals = [];
    var sum = 0;
    $('#updateItem .final_total').each(function() {
        sum += +$(this).val();
    });
    $('#updateItem #final_total').val(sum);
    $("#updateItem #insurance").attr({
        "max": sum
    });
}
</script>
<script>
function calculate_total_section(id) {
    var value = $('input[name="items[' + id + '][value]"]').val();
    var qty = $('input[name="items[' + id + '][quantity]"]').val();
    if (value !== '' && qty !== '') {
        var total = value * qty;
        $('input[name="items[' + id + '][total]"]').val(total);
        $('#removeSection' + id).attr('data-total', total); // sets
        var sum = 0;
        $('.final_total').each(function() {
            sum += +$(this).val();
        });
        $('#final_total').val(sum);
        $("#insurance").attr({
            "max": sum
        });
    }

}
</script>
<script>
$(function() {
    // $('#row_dim').hide();
    $('#type').change(function() {
        if ($('#type').val() == 'parcel') {
            $('#row_dim').show();
        } else {
            $('#row_dim').hide();
        }
    });
});
</script>
{{--    valdation --}}
<script>
$(function() {
    function getWordCount(wordString) {
        var words = wordString.split(" ");
        words = words.filter(function(words) {
            return words.length > 0
        }).length;
        return words;
    }

    jQuery.validator.setDefaults({
        // where to display the error relative to the element
        errorPlacement: function(error, element) {
            if (element.parent().hasClass('input-group')) {
                error.insertAfter(element.parent());
            } else if (element.parent().hasClass('form-group')) {
                error.insertAfter(element.parent());
            } else if (element.parent()) {
                error.insertAfter(element.parent().parent());
            } else {
                error.insertAfter(element);
            }
        }
    });
    $("form[name='form_add_package']").validate({
        // Specify validation rules
        rules: {

            reason_sending: "required",
            item_description: {
                required: true,
                maxlength: 30,
                wordCount: ['5'],
                // lettersonlys:true
            },
            shipping_date: "required",


            protection: "required",
            package_dif: "required",
            item_quantity: {
                required: true,
                number: true,
            },
            item_value: {
                required: true,
                number: true,
            },
            item_country_made: "required",
            item_condition: "required",

        },
        highlight: function(element) {
            // console.log($(element).parent());
            if ($(element).parent().hasClass('input-group')) {
                $(element).parent().addClass('has-error');
            } else if ($(element).parent().hasClass('form-group')) {
                $(element).parent().addClass('has-error');
            } else {
                $(element).parent().addClass('has-error');
            }

        },
        unhighlight: function(element) {
            // console.log(element)
            if ($(element).parent().hasClass('input-group')) {
                $(element).parent().removeClass('has-error');
            } else if ($(element).parent().hasClass('form-group')) {
                // console.log($(element).parent())
                $(element).parent().removeClass('has-error');
                // $(element).parents().removeClass('has-error');
            } else {
                $(element).parents().removeClass('has-error');
            }

        },
        // Specify validation error messages
        messages: {
            card_holder_name: "Please enter your card holder name",
            billing_address: "Please select or enter your billing address",
            full_name: "Please enter your full_name",
            email: "Please enter your email",
            phone: "Please enter your phone",
            country: "Please enter your country",
            city: "Please enter your city",
            address_1: {
                required: "Please enter your address 1",
                maxlength: "Can't exceed 25 characters",
            },
            state_province_region: "Please enter your state province region",
            postal_code: "Please enter your Postal Code (Zip Code)",
            full_name_edit: "Please enter your full_name",
            email_edit: "Please enter your email",
            phone_edit: "Please enter your phone",
            country_edit: "Please enter your country",
            city_edit: "Please enter your city",
            address_1_edit: "Please enter your address 1",
            state_province_region_edit: "Please enter your state province region",
            postal_code_edit: "Please enter your Postal Code (Zip Code)",

        },

        // Make sure the form is submitted to the destination defined
        // in the "action" attribute of the form when valid
        submitHandler: function(form) {
            form.submit();
        }
    });
    jQuery.validator.addMethod("lettersonlys", function(value, element) {
        return this.optional(element) || /^[a-zA-Z ]*$/.test(value);
    }, "Only English characters");
    //add the custom validation method
    jQuery.validator.addMethod("wordCount",
        function(value, element, params) {
            var count = getWordCount(value);
            if (count <= params[0]) {
                return true;
            }
        },
        jQuery.validator.format("A maximum of {0} words is required here.")
    );
    jQuery.validator.addMethod("us_code", function(value, element) {
        return /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(value);
    }, "Please specify a valid US zip code.");
});
</script>
<script>
$(document).ready(function() {

    // Handle form submission
    $("#form_add_package").submit(function() {
        let myform_add = $('#form_add_package');

        if (!myform_add.valid()) {
            swal.fire("Error!", '{{ __('
                Please fill out all fields.
                ') }}', "error");
            return false;
        }

        if (myform_add.valid()) {
            // Create an array for items
            var add_items = [];

            // Capture the items dynamically
            var dynamicItems = [];
            $("#form_add_package #sections_items .section_item").each(function() {

                console.log(this)
                var itemData = {
                    content: $(this).find(".item-content").val(),
                    value: $(this).find(".item-value").val(),
                    quantity: $(this).find(".item-quantity").val(),
                    total: $(this).find(".item-total").val()
                };
                dynamicItems.push(itemData);

            });

            // Create an object called `formData`
            var new_formData = {};

            // Serialize the form data except for the items
            $(this).find(":input").not("[name^='items']").serializeArray().forEach(function(
                new_item) {
                new_formData[new_item.name] = new_item.value;
            });

            // Assign the items array to the formData object
            new_formData.items = dynamicItems;

            // Retrieve the existing data from local storage (if any)
            var existingData_new = localStorage.getItem("packages");

            // Initialize an empty array or create one from existing data
            var packagesArray_new = existingData_new ? JSON.parse(existingData_new) : [];



            packagesArray_new.push(new_formData);


            // Convert the updated array back to a JSON string
            var updatedData_new = JSON.stringify(packagesArray_new);

            // Store the updated JSON string back in local storage
            localStorage.setItem("packages", updatedData_new);

            // Clear the form fields (optional)
            // myform_add[0].reset();

            // Refresh the table with the updated data
            refreshTable();
            swal.fire("Success", '{{ __('
                Done.
                ') }}', "success");
            $('#newItem').modal('hide')
        }
    });

    $("#form_update_package").submit(function() {
        // Assuming your update form has the ID #form_update_package
        // Retrieve the updated data from the form
        var updatedData = {
            pkg_type: $("#form_update_package #pkg_type").val(),
            weight: $("#form_update_package #weight").val(),
            height: $("#form_update_package #height").val(),
            width: $("#form_update_package #width").val(),
            length: $("#form_update_package #length").val(),
            insurance: $("#form_update_package #insurance").val(),
            final_total: $("#form_update_package #final_total").val(),
            unit_height: $("#form_update_package #unit_height").val(),
            unit_weight: $("#form_update_package #unit_weight").val(),
            // You may need to update this part to also capture items data

        };
        // Create an array for items
        var dynamicItems_update = [];
        $("#form_update_package #sections_items .section_item").each(function() {

            console.log(this)
            var itemData_update = {
                content: $(this).find(".item-content").val(),
                value: $(this).find(".item-value").val(),
                quantity: $(this).find(".item-quantity").val(),
                total: $(this).find(".item-total").val()
            };
            dynamicItems_update.push(itemData_update);

        });

        // Create an object called `formData`


        // Serialize the form data except for the items
        $(this).find("#form_update_package :input").not("#form_update_package [name^='items']")
            .serializeArray().forEach(function(item) {
                updatedData[item.name] = item.value;
            });

        // Assign the items array to the formData object
        updatedData.items = dynamicItems_update;
        // Retrieve the existing data from local storage
        var existingData = localStorage.getItem("packages");
        var packagesArray = existingData ? JSON.parse(existingData) : [];

        // Check if you have an update index set (similar to what you do for the "Update" button click)
        var updateIndex = parseInt($("#updateItem #update_index").val());


        if (!isNaN(updateIndex) && updateIndex >= 0 && updateIndex < packagesArray.length) {
            // Update the existing item in the array
            packagesArray[updateIndex] = updatedData;

            // Convert the updated array back to a JSON string
            var updatedDataString = JSON.stringify(packagesArray);

            // Store the updated JSON string back in local storage
            localStorage.setItem("packages", updatedDataString);

            // You can perform any other necessary actions here (e.g., refresh the table)
            refreshTable();

            // Clear the form fields (optional)
            $("#form_update_package")[0].reset();

            // Close the update modal (if applicable)
            $('#updateItem').modal('hide');

            swal.fire("Success", '{{ __('
                Updated successfully.
                ') }}', "success");
        } else {
            // Handle the case where the update index is invalid
            // Display an error message or take appropriate action
            swal.fire("Error", "Invalid update index.", "error");
        }

        return false; // Prevent the form from submitting traditionally
    });

    // Handle "Delete" button click within the table using event delegation
    $("#package_items").on("click", "a.btn-delete-tr", function() {
        var row = $(this).closest("tr");
        var rowIndex = row.index();

        // Retrieve the existing data from local storage
        var existingData = localStorage.getItem("packages");
        var packagesArray = existingData ? JSON.parse(existingData) : [];

        // Check if the row index is within a valid range
        if (rowIndex >= 0 && rowIndex < packagesArray.length) {
            // Remove the corresponding item from the JSON data array
            packagesArray.splice(rowIndex, 1);

            // Update local storage with the modified JSON data
            localStorage.setItem("packages", JSON.stringify(packagesArray));
        }

        // Remove the row from the table
        row.remove();
    });

    // Handle "Update" button click within the table using event delegation
    $("#package_items").on("click", "a.btn-update-tr", function() {
        var row = $(this).closest("tr");
        var rowIndex = row.index();

        // Retrieve the existing data from local storage
        var existingData = localStorage.getItem("packages");
        var packagesArray = existingData ? JSON.parse(existingData) : [];

        // Check if the row index is within a valid range
        if (rowIndex >= 0 && rowIndex < packagesArray.length) {
            // Populate the form fields with the data from the selected row
            var selectedData = packagesArray[rowIndex];
            // console.log(selectedData)
            $("#updateItem #pkg_type").val(selectedData.pkg_type);
            $("#updateItem #weight").val(selectedData.weight);
            $("#updateItem #height").val(selectedData.height);
            $("#updateItem #width").val(selectedData.width);
            $("#updateItem #length").val(selectedData.length);
            $("#updateItem #insurance").val(selectedData.insurance);
            $("#updateItem #final_total").val(selectedData.final_total);
            // Call the function to append items to the HTML structure
            appendItemsToHTML(selectedData);
            // Populate other form fields as needed
            // ...
            $('#updateItem').modal('show');
            // Set the update index to the selected row
            $("#updateItem #update_index").val(rowIndex);


        }
    });
    // Function to append items to the HTML structure
    // Function to append items to the HTML structure
    function appendItemsToHTML(data) {


        // Check if the 'items' array exists in the data
        if (Array.isArray(data.items) && data.items.length > 0) {
            var sectionItem = $("#updateItem #sections_items"); // Get the container for items
            sectionItem.find(".section_item").remove();
            // Detach .btn_item
            var btnItem = $('#updateItem .btn_item').detach();
            var itemsData = data.items; // Get the items array
            // console.log(data.items)
            // Iterate over the items in the 'items' array
            for (var i = 0; i < itemsData.length; i++) {
                var itemData = itemsData[i];

                // Create a new item container div
                var newItem = $("<div class='section_item'><div class='row'></div><hr></div>");

                // Create and append the item's content input field
                var contentInput = $(
                    "<div class='col-12 col-md-4 col-lg-4 form-info'><div class='mb-2'><label>Content</label><input type='text' class='form-control item-content' id='content' name='items[" +
                    i + "][content]' required='' value='" + itemData.content + "'></div></div>");
                newItem.find(".row").append(contentInput);

                // Create and append the item's value input field
                var valueInput = $(
                    "<div class='col-12 col-md-2 col-lg-2 form-info'><div class='mb-2'><label>Value</label><div class='input-group mb-3'><input type='number' min='0' class='form-control item-value' id='value' onchange='update_calculate_total(" +
                    i + ")' name='items[" + i +
                    "][value]' aria-label='' aria-describedby='basic-addon2' required='' value='" +
                    itemData.value +
                    "'><div class='input-group-append'><span class='input-group-text form-control w-100' style='background: #2f2f2f; color: #ffff;' id='basic-addon2'>$</span></div></div></div></div>"
                );
                newItem.find(".row").append(valueInput);

                // Create and append the item's quantity input field
                var quantityInput = $(
                    "<div class='col-12 col-md-2 col-lg-2 form-info'><div class='mb-2'><label>Quantity</label><input type='number' min='0' class='form-control item-quantity' id='quantity' onchange='update_calculate_total(" +
                    i + ")' name='items[" + i + "][quantity]' required='' value='" + itemData.quantity +
                    "'></div></div>");
                newItem.find(".row").append(quantityInput);

                // Create and append the item's total input field
                var totalInput = $(
                    "<div class='col-12 col-md-2 col-lg-2 form-info'><div class='mb-2'><label>Total</label><input type='number' readonly='' min='0' class='form-control item-total final_total' id='total' name='items[" +
                    i + "][total]' required='' value='" + itemData.total + "'></div></div>");
                newItem.find(".row").append(totalInput);

                // Create and append the item's remove button
                var removeButton = $(
                    "<div class='col-12 col-md-1 col-lg-1 form-info'><div class='mb-2'><label style='opacity: 0;'>vd </label><a href='#' class='btn btn-danger removeItemUpdate " +
                    (i === 0 ? 'hidden' : '') +
                    "' id='removeItemUpdate' data-total='0'><i class='fa fa-trash' aria-hidden='true'></i></a></div></div>"
                );
                newItem.find(".row").append(removeButton);

                // Append the new item container to the section_item container
                sectionItem.append(newItem); // Use .append() instead of .prepend()
            }
            $('#updateItem #sections_items').append(btnItem);
        }
    }
    // Function to refresh the table with data from local storage
    function refreshTable() {
        var jsonData = JSON.parse(localStorage.getItem("packages"));
        var tbody = $("#package_items tbody");



        if (Array.isArray(jsonData)) {
            if (jsonData.length > 0) {
                tbody.empty(); // Clear the table body
                $('#add_item_btn').show()
                for (var i = 0; i < jsonData.length; i++) {
                    var row = $("<tr></tr>");
                    row.append("<td class='text-center'>" + (i + 1) + "</td>");
                    row.append("<td class='text-center'>" + jsonData[i].pkg_type + "</td>");
                    row.append("<td class='text-center'>" + jsonData[i].weight + " " + jsonData[i]
                        .unit_weight + "</td>");
                    row.append("<td class='text-center'>" + jsonData[i].height + "x" + jsonData[i].width +
                        "x" + jsonData[i].length + " " + jsonData[i].unit_height + "</td>");
                    row.append("<td class='text-center'>" + jsonData[i].insurance + "</td>");
                    row.append("<td class='text-center'>" + jsonData[i].final_total + "</td>");
                    row.append(
                        "<td class='text-center'><a href='javascript:;' class='  text-success text-decoration-none   me-2 btn-update-tr '><i class='fa fa-edit'></i></a><a href='javascript:;' class='  text-danger   text-decoration-none   btn-delete-tr '><i class='fa fa-trash'></i></a></td>"
                    );
                    tbody.append(row);
                }
            }

        }
    }

    // Initial table population when the page loads
    refreshTable();

});
</script>
<script>
flatpickr('.flat_time', {
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    disableMobile: true

});
</script>
<script>
flatpickr('.flat_date', {
    enableTime: false,
    dateFormat: "Y-m-d",
    disableMobile: true
});
</script>
@endsection