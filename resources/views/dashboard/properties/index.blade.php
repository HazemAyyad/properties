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
                        <a href="{{route('admin.dashboard')}}">{{__('Home')}}</a>
                    </li>
{{--                    <li class="breadcrumb-item">--}}
{{--                        <a href="javascript:void(0);">Library</a>--}}
{{--                    </li>--}}
                    <li class="breadcrumb-item active">{{__('Properties')}}</li>
                    <!-- Basic table -->


                    <!--/ Basic table -->
                </ol>
            </nav>
            <div class="card">
                <div class="card-datatable table-responsive pt-0">
                    <table class="datatables-basic table">
                        <thead>
                        <tr>
                            <th>Name</th>
                            <th>Views</th>
                            <th>Created AT</th>
                            <th>Status</th>
                            <th>Moderation status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
        <!-- / Content -->
        <!-- Bootstrap Modal -->
        <div class="modal fade" id="moderationStatusModal" tabindex="-1" aria-labelledby="moderationStatusModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="moderationStatusModalLabel">Change Moderation Status</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form id="moderationStatusForm">
                            <div class="mb-3">
                                <label for="moderation_status" class="form-label">Select Status</label>
                                <select class="form-select" id="moderation_status" name="moderation_status">
                                    <option value="0">Pending</option>
                                    <option value="1">Approved</option>
                                    <option value="2">Rejected</option>
                                </select>
                            </div>
                            <input type="hidden" id="property_id">
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="saveModerationStatus">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

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

        var data_url='{{ route('admin.properties.get_properties',$status)}}'

        var dt;
        $(function() {

            dt= $('.datatables-basic').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: data_url,
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'views', name: 'views' },
                    { data: 'created_at', name: 'created_at' },
                    { data: 'status', name: 'status' },
                    { data: 'moderation_status', name: 'moderation_status' },
                    {data: 'action', name: 'action', orderable: false, searchable: false},
                ],
                dom:
                    '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>><"d-flex justify-content-between align-items-center mx-0 row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>t<"d-flex justify-content-between mx-0 row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',
                // dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6 d-flex justify-content-center justify-content-md-end"f>>t<"row"<"col-sm-12 col-md-6"i><"col-sm-12 col-md-6"p>>',

                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                buttons: [
                    {
                        text: '<i class="ti ti-plus me-sm-1"></i> ' + '{{__("Add New Property")}}',
                        className: 'create-new btn btn-primary',
                        attr: {
                            'onclick':"window.location.href='{{route('admin.properties.create')}}'",
                        },
                        init: function (api, node, config) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
            });

        });


    </script>
    <script type="text/javascript">
        function deleteItem(id) {

            var data_url_delete='{{ route('admin.properties.delete','')}}'+'/'+id

            swal.fire({
                title: "{{__('Delete?')}}",
                text: "{{__('Please confirm approval')}}",
                type: "warning",
                showCancelButton: !0,
                confirmButtonText: "{{__('Yes, delete!')}}",
                cancelButtonText: "{{__('No, back off!')}}",
                confirmButtonColor: "#DD6B55",
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'delete',
                        url: data_url_delete,
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
    <script !src="">
        $(function () {
            $('[data-toggle="tooltip"]').tooltip(); // Initialize tooltips
        });

    </script>
    <script>
        function changeModerationStatus(element) {
            var propertyId = $(element).data('id');
            var currentStatus = $(element).data('status');

            // Set the current property ID and moderation status in the modal
            $('#property_id').val(propertyId);
            $('#moderation_status').val(currentStatus); // Set the selected value

            // Show the Bootstrap modal
            var myModal = new bootstrap.Modal(document.getElementById('moderationStatusModal'));
            myModal.show();
        }

        // Handle the form submission
        $('#saveModerationStatus').click(function() {
            var propertyId = $('#property_id').val();
            var newStatus = $('#moderation_status').val();
            $('#saveModerationStatus').html('');
            $('#saveModerationStatus').append('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                '<span class="ml-25 align-middle">{{ __('Saving') }}...</span>');

            $.ajax({
                url: '{{ route("admin.properties.updateModerationStatus") }}',
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    id: propertyId,
                    moderation_status: newStatus
                },
                success: function(response) {

                    $('#saveModerationStatus').html('{{ __('Save changes') }}');
                    if (response.success) {
                        Swal.fire(
                            'Updated!',
                            'Moderation status has been updated.',
                            'success'
                        );

                        // Hide the modal after successful update
                        var myModalEl = document.getElementById('moderationStatusModal');
                        var modal = bootstrap.Modal.getInstance(myModalEl);
                        modal.hide();

                        // Reload DataTable without refreshing the entire page
                        $('.datatables-basic').DataTable().ajax.reload(null, false); // Use the DataTable ID you initialized
                    } else {
                        $('#saveModerationStatus').html('{{ __('Save changes') }}');
                        Swal.fire(
                            'Error!',
                            'Failed to update moderation status.',
                            'error'
                        );
                    }
                },
                error: function() {
                    Swal.fire(
                        'Error!',
                        'An error occurred while updating the moderation status.',
                        'error'
                    );
                }
            });
        });
    </script>



@endsection
