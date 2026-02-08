@extends('dashboard.layouts.app')
@section('style')

    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />

    <!-- Row Group CSS -->
    <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}" />
@endsection
@section('content')
    <!-- BEGIN: Content-->
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                </li>
                {{--                    <li class="breadcrumb-item">--}}
                {{--                        <a href="javascript:void(0);">Library</a>--}}
                {{--                    </li>--}}
                <li class="breadcrumb-item active">{{__('Roles')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table">
                    <thead>
                                    <tr>
                                        <th>{{__('Name')}}</th>
                                        <th>{{__('Action')}}</th>
                                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
    <!-- / Content -->
@endsection
@section('scripts')
    <!-- BEGIN: Page Vendor JS-->
    <script src="{{asset('assets/vendor/libs/datatables/jquery.dataTables.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-buttons/datatables-buttons.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-rowgroup/datatables.rowgroup.js')}}"></script>
    <script src="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.js')}}"></script>
    <!-- END: Page Vendor JS-->

    <script>


        var data_url='{{ route('admin.roles.data')}}'

        var dt;
        $(function() {

            dt= $('.datatables-basic').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: data_url,
                columns: [
                    { data: 'name', name: 'name' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},

                ],
                dom:
                    '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                // dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                buttons: [
                    {
                        text: '<i class="ti ti-plus me-sm-1"></i> ' + '{{__("Add New Role")}}',
                        className: 'create-new btn btn-primary',
                        attr: {
                            'onclick':"window.location.href='{{route('admin.roles.create')}}'",
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
                language: {
                    "lengthMenu": "{{__('Show')}} _MENU_ {{__('entries')}}",
                    "processing":     "{{__('Processing...')}}",
                    "search":         "{{__('Search:')}}",
                    "info":           "{{__('Showing')}} _START_ {{__('to')}} _END_ {{__('of')}} _TOTAL_ {{__('entries')}}",
                    "zeroRecords":    "{{__('No matching records found')}}",
                    "emptyTable":     "{{__('No data available in table')}}",
                    "infoEmpty":      "{{__('Showing')}} 0 {{__('to')}} 0 {{__('of')}} 0 {{__('entries')}}",
                    "infoFiltered":   "({{__('filtered from')}} _MAX_ {{__('total entries')}} )",
                    paginate: {
                        // remove previous & next text from pagination
                        previous: '&nbsp;',
                        next: '&nbsp;'
                    }
                },
            });

        });


    </script>
    <script type="text/javascript">
        function deleteRow(id) {
            swal.fire({
                title: "{{__('Delete?')}}",
                text: "{{__('Please confirm approval')}}",
                type: "error",
                icon: 'warning',
                showCancelButton: !0,
                confirmButtonText: "{{__('Yes, delete done!')}}",
                cancelButtonText: "{{__('No, back off!')}}",
                cancelButtonClass: 'btn-primary',
                confirmButtonClass: 'btn-danger',
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'delete',
                        url: "{{ url("admin/roles/delete/")}}/" + id,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (response) {

                            if (response.status === true) {
                                swal.fire("Done!", response.msg, "success");
                                $('.datatables-basic').DataTable().ajax.reload();

                            } else {
                                swal.fire("Error!", response.msg, "error");
                            }
                        }
                    });

                } else {
                    e.dismiss;
                }

            }, function (dismiss) {
                return false;
            })
        }

    </script>


@endsection
