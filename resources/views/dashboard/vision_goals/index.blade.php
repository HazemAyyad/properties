@extends('dashboard.layouts.app')

@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
    <style>
        .card { padding: 1.5rem !important; }
    </style>
@endsection

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a>
                </li>
                <li class="breadcrumb-item active">{{ __('Vision Goals') }}</li>
            </ol>
        </nav>

        <div class="card">
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table">
                    <thead>
                    <tr>
                        <th>{{ __('Title') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Sort Order') }}</th>
                        <th>{{ __('Actions') }}</th>
                    </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script src="{{ asset('assets/vendor/libs/datatables/jquery.dataTables.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive/datatables.responsive.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons/datatables-buttons.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-rowgroup/datatables.rowgroup.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-rowgroup-bs5/rowgroup.bootstrap5.js') }}"></script>

    <script>
        var data_url = '{{ route('admin.vision_goals.get_goals') }}';

        var dt;
        $(function () {
            dt = $('.datatables-basic').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: data_url,
                columns: [
                    { data: 'title', name: 'title' },
                    { data: 'status', name: 'status' },
                    { data: 'sort_order', name: 'sort_order' },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                dom:
                    '<"card-header border-bottom p-1"<"head-label"><"dt-action-buttons text-right"B>>' +
                    '<"d-flex justify-content-between align-items-center mx-0 row"' +
                    '<"col-sm-12 col-md-6"l>' +
                    '<"col-sm-12 col-md-6"f>>t' +
                    '<"d-flex justify-content-between mx-0 row"' +
                    '<"col-sm-12 col-md-6"i>' +
                    '<"col-sm-12 col-md-6"p>>',
                displayLength: 7,
                lengthMenu: [7, 10, 25, 50, 75, 100],
                buttons: [
                    {
                        text: '<i class="ti ti-plus me-sm-1"></i> ' + '{{ __("Add New Goal") }}',
                        className: 'create-new btn btn-primary',
                        attr: {
                            onclick: "window.location.href='{{ route('admin.vision_goals.create') }}'",
                        },
                        init: function (api, node) {
                            $(node).removeClass('btn-secondary');
                        }
                    }
                ],
            });
        });

        function deleteItem(id) {
            var data_url_delete = '{{ route('admin.vision_goals.delete', '') }}' + '/' + id;

            swal.fire({
                title: '{{ __("Delete?") }}',
                text: '{{ __("Please confirm approval") }}',
                type: 'warning',
                showCancelButton: !0,
                confirmButtonText: '{{ __("Yes, delete!") }}',
                cancelButtonText: '{{ __("No, back off!") }}',
                confirmButtonColor: '#DD6B55',
                reverseButtons: !0
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'delete',
                        url: data_url_delete,
                        data: { _token: CSRF_TOKEN },
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.status === true) {
                                swal.fire('Done!', response.msg, 'success');
                                $('.datatables-basic').DataTable().ajax.reload();
                            } else {
                                swal.fire('Error!', response.msg, 'error');
                            }
                        }
                    });
                } else {
                    e.dismiss;
                }
            }, function () {
                return false;
            });
        }
    </script>
@endsection
