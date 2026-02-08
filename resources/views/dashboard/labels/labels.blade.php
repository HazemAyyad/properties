@extends('dashboard.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}"/>
    <link rel="stylesheet"
          href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}"/>
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}"/>

    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}"/>
    {{--    <link rel="stylesheet" href="{{asset('assets/vendor/libs/formvalidation/dist/css/formValidation.min.css')}}" />--}}
    <link rel="stylesheet" href="{{asset('assets/css/form-validation.css')}}"/>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('dashboard')}}">{{__('Home')}}</a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{route('shipment_form.labels',$package->id)}}">{{__('Labels')}}</a>
                </li>
                <li class="breadcrumb-item active">{{__('Labels')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="container-fluid">
            <div class="container-fluid">

                <div class="container">


                    <!-- Main content -->
                    <div class="row">
                        <div class="col-lg-12">
                            <!-- Details -->

                            <!-- Title -->
                            <div class="card mb-4">
                                <h5 class="card-header">Rates</h5>
                                <div class="card-body">
                                    <form action="javascript:void(0)" name="add_order" id="add_order" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2>Fedex Rates</h2>
                                            </div>
                                            @if(isset($fedex_rates['rate_response']))
                                                @foreach($fedex_rates['rate_response']['rates'] as $rate)
                                                    <div class="col-md-4  {{$loop->first?'mb-md-0 mb-2':''}}">
                                                        <div class="form-check form-group custom-option custom-option-basic mb-2">
                                                            <label class="form-check-label custom-option-content"
                                                                   for="serviceName_{{$loop->index+1}}">
                                                                <input name="serviceName" class="form-check-input" type="radio"
                                                                       value="{{$rate['service_code']}}" onclick="shippingCompany('FEDEX')" id="serviceName_{{$loop->index+1}}" required>
                                                                <span class="custom-option-header">
                                                        <span class="h6 mb-0">{{$rate['service_type']}}</span>
                                                      </span>
                                                                <span class="custom-option-body">
                                                        <small>${{$rate['shipping_amount']['amount']+$rate['other_amount']['amount']}}</small>
                                                  </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @foreach($fedex_rates['errors'] as $item)
                                                    {{$item['message']}}<br>
                                                @endforeach
                                            @endif
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2>UPS Rates</h2>
                                            </div>
                                            @if(isset($ups_rates['rate_response']))
                                                @foreach($ups_rates['rate_response']['rates'] as $rate)
                                                    <div class="col-md-4  {{$loop->first?'mb-md-0 mb-2':''}}">
                                                        <div class="form-check form-group custom-option custom-option-basic mb-2">
                                                            <label class="form-check-label custom-option-content"
                                                                   for="serviceNameUps_{{$loop->index+1}}">
                                                                <input name="serviceName" class="form-check-input" type="radio"
                                                                       value="{{$rate['service_code']}}" onclick="shippingCompany('UPS')" id="serviceNameUps_{{$loop->index+1}}" required>
                                                                <span class="custom-option-header">
                                                        <span class="h6 mb-0">{{$rate['service_type']}}</span>
                                                      </span>
                                                                <span class="custom-option-body">
                                                        <small>${{$rate['shipping_amount']['amount']}}</small>
                                                  </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @foreach($ups_rates['errors'] as $item)
                                                    {{$item['message']}}<br>
                                                @endforeach
                                            @endif
                                            {{--                                            <input type="hidden" name="shipping_company" id="shipping_company">--}}
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <h2>DHL Rates</h2>
                                            </div>
                                            @if(isset($dhl_rates['rate_response']))
                                                @foreach($dhl_rates['rate_response']['rates'] as $rate)
                                                    <div class="col-md-4  {{$loop->first?'mb-md-0 mb-2':''}}">
                                                        <div class="form-check form-group custom-option custom-option-basic mb-2">
                                                            <label class="form-check-label custom-option-content"
                                                                   for="serviceNameDhl_{{$loop->index+1}}">
                                                                <input name="serviceName" class="form-check-input" type="radio"
                                                                       value="{{$rate['service_code']}}" onclick="shippingCompany('DHL')" id="serviceNameDhl_{{$loop->index+1}}" required>
                                                                <span class="custom-option-header">
                                                        <span class="h6 mb-0">{{$rate['service_type']}}</span>
                                                      </span>
                                                                <span class="custom-option-body">
                                                        <small>${{$rate['shipping_amount']['amount']}}</small>
                                                  </span>
                                                            </label>
                                                        </div>
                                                    </div>
                                                @endforeach
                                            @else
                                                @foreach($dhl_rates['errors'] as $item)
                                                    {{$item['message']}}<br>
                                                @endforeach
                                            @endif
                                            <input type="hidden" name="shipping_company" id="shipping_company">
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="from-group">
                                                    <select class="form-select address-select" id="reason_sending" name="reason_sending" required>
                                                        <option value="" {{$package->reason_sending==null?'selected':''}}>{{__('Select a reason')}}</option>
                                                        <option value="1" {{$package->reason_sending==1?'selected':''}}>{{__('It is a gift')}}</option>
                                                        <option value="2" {{$package->reason_sending==2?'selected':''}}>{{__('I sold this')}}</option>
                                                        <option value="3" {{$package->reason_sending==3?'selected':''}}>{{__('These are documents')}}</option>
                                                        <option value="4" {{$package->reason_sending==4?'selected':''}}>{{__('This needs to be repaired')}}</option>
                                                        <option value="5" {{$package->reason_sending==5?'selected':''}}>{{__('These are samples')}}</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12 mt-4 text-center">
                                                <button type="submit" class="btn btn-primary waves-effect waves-light " id="add_form"   >
                                                    {{__('Create Label')}}
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                            @if(isset($package->tracking['events']))
                                <div class="card mb-4">
                                    <h5 class="card-header">Tracking</h5>
                                    <div class="card-body pb-0">
                                        <ul class="timeline mb-0">
                                            @foreach($package->tracking['events'] as $item)
                                                <li class="timeline-item timeline-item-transparent {{$loop->last?'border-0':''}}">
                                                    <span class="timeline-point timeline-point-primary"></span>
                                                    <div class="timeline-event">
                                                        <div class="timeline-header mb-1">
                                                            <h6 class="mb-0">
                                                                @if($item['status_code']=='AC')
                                                                    {{__('Accepted')}}
                                                                @elseif($item['status_code']=='IT')
                                                                    {{__('In Transit')}}
                                                                @elseif($item['status_code']=='DE')
                                                                    {{__('Delivered')}}
                                                                @elseif($item['status_code']=='EX')
                                                                    {{__('Exception')}}
                                                                @elseif($item['status_code']=='UN')
                                                                    {{__('Unknown')}}
                                                                @elseif($item['status_code']=='AT')
                                                                    {{__('Delivery Attempt')}}
                                                                @elseif($item['status_code']=='NY')
                                                                    {{__('Not Yet In System')}}
                                                                @elseif($item['status_code']=='SP')
                                                                    {{__('Delivered To The Collection Location')}}
                                                                @else
                                                                    {{__('Unknown')}}
                                                                @endif
                                                            </h6>
                                                            <small class="text-muted">{{\Carbon\Carbon::parse($item['occurred_at'])->format('Y-m-d H:i:s')}}</small>
                                                        </div>
                                                        <p class="mb-2">{{$item['description']}}</p>
                                                        <p class="mb-2">{{$item['city_locality']}}</p>

                                                    </div>
                                                </li>
                                            @endforeach


                                        </ul>
                                    </div>
                                </div>
                            @endif
                        </div>
                     </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-datatable table-responsive pt-0">
                                    <div class="table-responsive mb-3">
                                        <table class="table datatable border-top">
                                            <thead>
                                            <tr>
                                                <th>tracking number</th>
                                                <th>carrierCode</th>
                                                <th>shipmentCost</th>
                                                <th>Status</th>
                                                <th>Create Date</th>
                                                <th>Action</th>
                                            </tr>
                                            </thead>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>

        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{asset('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-buttons/datatables-buttons.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-rowgroup/datatables.rowgroup.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.js')}}"></script>

    {{--    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/FormValidation.min.js')}}"></script>--}}
    {{--    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/Bootstrap5.min.js')}}"></script>--}}
    {{--    <script src="{{asset('assets/vendor/libs/formvalidation/dist/js/plugins/AutoFocus.min.js')}}"></script>--}}
    {{--    <script src="{{asset('/assets/js/app-user-view-security.js')}}"></script>--}}
    <script src="{{asset('assets/js/jquery.validate.min.js')}}"></script>
    @if($package->address_going->full_phone==null)
        <script>
            swal.fire({
                icon: 'error',
                title: 'Phone going to not found'
            });
        </script>
    @endif

    <script>

        var data_url_table = '{{ route('warehouse_orders.get_labels',$package->id)}}'

        var dt;
        $(function () {

            dt = $('.datatable').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: data_url_table,
                columns: [
                    {data: 'tracking_number', name: 'tracking_number'},
                    {data: 'carrier_code', name: 'carrier_code'},
                    {data: 'shipment_cost', name: 'shipment_cost'},
                    {data: 'status', name: 'status'},
                    {data: 'created_at', name: 'created_at'},
                    {data: 'action', name: 'action', orderable: false, searchable: false},


                ],
                displayLength: 10,
                lengthMenu: [7, 10, 25, 50, 75, 100],

                language: {
                    "lengthMenu": "{{__('Show')}} _MENU_ {{__('entries')}}",
                    "processing": "{{__('Processing...')}}",
                    "search": "{{__('Search:')}}",
                    "info": "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
                    "zeroRecords": "{{__('No matching records found')}}",
                    "emptyTable": "{{__('No data available in table')}}",
                    "infoEmpty": "{{__('Showing')}} 0 {{__('to')}} 0 {{__('of')}} 0 {{__('entries')}}",
                    "infoFiltered": "({{__('filtered from')}} _MAX_ {{__('total entries')}} )",
                    paginate: {
                        // remove previous & next text from pagination
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                },
            });

        });


    </script>
    {{--    create labels--}}
    <script>

        var data_url_user = '{{ route('warehouse_orders.create-labels',$package->id)}}'

        $(document).ready(function () {
            function myHandel(obj, id) {
                if ('responseJSON' in obj)
                    obj.messages = obj.responseJSON;
                $('input,select,textarea').each(function () {
                    var parent = "";
                    if ($(this).parents('.fv-row').length > 0)
                        parent = $(this).parents('.fv-row');
                    if ($(this).parents('.input-group').length > 0)
                        parent = $(this).parents('.input-group');
                    var name = $(this).attr("name");
                    if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                        var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>'
                        parent.append(error_message);
                    }
                });
            }

            $("#add_order").submit(function () {
                let myform = $('#add_order');

                if (!myform.valid()) {
                    return false
                }
                ;
                if (myform.valid()) {
                    var postData = new FormData($('form#add_order')[0]);
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }

                    });
                    $('#add_form').html('');
                    $('#add_form').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                        '<span class="ml-25 align-middle">{{__('creating')}}...</span>');
                    $.ajax({
                        url: data_url_user,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('#add_form').html('{{__('Save')}}');
                            setTimeout(function () {
                                toastr['success'](
                                    response.success,
                                    {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);
                            $('.datatable').DataTable().ajax.reload();
                            $('.custom-error').remove();

                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $('#add_form').empty();
                            $('#add_form').html('{{__('Save')}}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (typeof (response.responseJSON) != "undefined") {
                                    myHandel(response);
                                    setTimeout(function () {
                                        toastr['error'](
                                            response.message,
                                            {
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
    <script>

        $(document).on("change", "#status_order", function() {
            var data_url_status='{{route('warehouse_orders.status')}}'
            // e.preventDefault()
            swal.fire({
                title: "{{__('Change?')}}",
                text: "{{__('Please confirm approval')}}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "{{__('Yes, Change!')}}",
                cancelButtonText: "{{__('No, back off!')}}",
                confirmButtonColor: "#DD6B55",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }

                    });
                    var postData_order = new FormData($( 'form#form_status_order' )[ 0 ]);

                    $.ajax({
                        url: data_url_status,
                        type: "POST",
                        data: postData_order,
                        processData: false,
                        contentType: false,
                        beforeSend() {
                            $('.layout-container').block({
                                message:
                                    '<div class="d-flex justify-content-center"><p class="mb-0">Please wait...</p> <div class="sk-wave m-0"><div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div> <div class="sk-rect sk-wave-rect"></div></div> </div>',
                                // timeout: 1000,
                                css: {
                                    backgroundColor: 'transparent',
                                    color: '#fff',
                                    border: '0'
                                },
                                overlayCSS: {
                                    opacity: 0.5
                                }
                            });

                        },

                        success: function (response) {
                            // $('.datatable').DataTable().ajax.reload();
                            {{--$('#change').html('{{__('Save')}}');--}}
                            setTimeout(function () {
                                toastr['success'](
                                    response.success,
                                    {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);

                            $('.custom-error').remove();
                            var status=$('#status_order').val();
                            window.location.href="{{route('warehouse_orders.labels',[''] )}}"+'/'+{{$package->id}};

                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $(this).empty();
                            $(this).html('{{__('Save')}}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (typeof (response.responseJSON) != "undefined") {
                                    myHandel(response);
                                    setTimeout(function () {
                                        toastr['error'](
                                            response.message,
                                            {
                                                closeButton: true,
                                                tapToDismiss: false
                                            }
                                        );
                                    }, 200);
                                }
                            } else {
                                setTimeout(function () {
                                    toastr['error'](
                                        response.message,
                                        {
                                            closeButton: true,
                                            tapToDismiss: false
                                        }
                                    );
                                }, 200);
                            }
                        }
                    });
                }else {
                    document.querySelectorAll("#status_order").forEach(v => {
                        v.value = "{{$package->status}}";
                    })
                }


            }, function (dismiss) {


                return false;
            })

        });
    </script>
    <script>
        function shippingCompany($company){
            $('#shipping_company').val($company)
        }
    </script>
    <script>
        $(document).on("change", "#status", function() {
            var postData = new FormData(this.form);
            var data_url_create_section='{{route('warehouse_orders.status_label')}}'
            swal.fire({
                title: "{{__('Change?')}}",
                text: "{{__('Please confirm approval')}}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "{{__('Yes, Change!')}}",
                cancelButtonText: "{{__('No, back off!')}}",
                confirmButtonColor: "#DD6B55",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    $.ajaxSetup({
                        headers: {
                            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                        }

                    });


                    $.ajax({
                        url: data_url_create_section,
                        type: "POST",
                        data: postData,
                        processData: false,
                        contentType: false,
                        success: function (response) {
                            $('.datatable').DataTable().ajax.reload();
                            {{--$('#change').html('{{__('Save')}}');--}}
                            setTimeout(function () {
                                toastr['success'](
                                    response.success,
                                    {
                                        closeButton: true,
                                        tapToDismiss: false
                                    }
                                );
                            }, 200);

                            $('.custom-error').remove();

                        },
                        error: function (data) {
                            $('.custom-error').remove();
                            $(this).empty();
                            $(this).html('{{__('Save')}}');
                            var response = data.responseJSON;
                            if (data.status == 422) {
                                if (typeof (response.responseJSON) != "undefined") {
                                    myHandel(response);
                                    setTimeout(function () {
                                        toastr['error'](
                                            response.message,
                                            {
                                                closeButton: true,
                                                tapToDismiss: false
                                            }
                                        );
                                    }, 200);
                                }
                            } else {
                                setTimeout(function () {
                                    toastr['error'](
                                        response.message,
                                        {
                                            closeButton: true,
                                            tapToDismiss: false
                                        }
                                    );
                                }, 200);
                            }
                        }
                    });
                }else {
                    $('.datatable').DataTable().ajax.reload();
                }


            }, function (dismiss) {


                return false;
            })

        });
    </script>
@endsection
