@extends('dashboard.layouts.app')
@section('style')

        <!-- BEGIN: Vendor CSS-->

        <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
        <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />

        <!-- Row Group CSS -->
        <link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.css')}}" />
        <!-- Form Validation -->
        <style>
            .card{
                padding: 1.5rem !important;
            }
        </style>
        @if(App::isLocale('en'))
    @else

    @endif
@endsection
@section('content')

        <!-- Content -->

        <div class="container-xxl flex-grow-1 container-p-y">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item">
                        <a href="{{route('dashboard')}}">{{__('Home')}}</a>
                    </li>
{{--                    <li class="breadcrumb-item">--}}
{{--                        <a href="javascript:void(0);">Library</a>--}}
{{--                    </li>--}}
                    <li class="breadcrumb-item active">{{__('How It Work')}}</li>
                    <!-- Basic table -->


                    <!--/ Basic table -->
                </ol>
            </nav>
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-basic table">
                        <thead>
                        <tr>
                            <th>Description</th>
                            <th>Action</th>
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
    <!-- BEGIN: Page JS-->
    {{--    <script src="{{asset('../app-assets/js/scripts/tables/table-datatables-basic.js"></script>--}}
    <script>

        var data_url='{{ route('how-it-works')}}'

        var dt;
        $(function() {

            dt= $('.datatables-basic').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: data_url,
                columns: [
                    { data: 'description', name: 'description' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                // dom:
                    // '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                // dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                {{--buttons: [--}}
                {{--    {--}}
                {{--        text: '<i class="ti ti-plus me-sm-1"></i> ' + '{{__("Add New Slider")}}',--}}
                {{--        className: 'create-new btn btn-primary',--}}
                {{--        attr: {--}}
                {{--            'onclick':"window.location.href='{{route('sliders.create')}}'",--}}
                {{--        },--}}
                {{--        init: function (api, node, config) {--}}
                {{--            $(node).removeClass('btn-secondary');--}}
                {{--        }--}}
                {{--    }--}}
                {{--],--}}
            });

        });


    </script>


@endsection
