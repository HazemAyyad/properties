@extends('dashboard.layouts.app')
@section('style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
    <style>.card { padding: 1.5rem !important; }</style>
@endsection
@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{ __('Home') }}</a></li>
                <li class="breadcrumb-item active">{{ __('Plan Upgrade Requests') }}</li>
            </ol>
        </nav>
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">{{ __('Plan Upgrade Requests') }}</h5>
            </div>
            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table">
                    <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('User') }}</th>
                        <th>{{ __('Email') }}</th>
                        <th>{{ __('Requested Plan') }}</th>
                        <th>{{ __('Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Action') }}</th>
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
    <script>
        var data_url = '{{ route("admin.plan-upgrade-requests.data") }}';
        $(function () {
            $('.datatables-basic').DataTable({
                searching: true,
                processing: true,
                serverSide: true,
                responsive: true,
                ajax: data_url,
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'user_email', name: 'user_email' },
                    { data: 'plan_name', name: 'plan_name' },
                    { data: 'created_at_formatted', name: 'created_at' },
                    { data: 'status_badge', name: 'status', orderable: false, searchable: false },
                    { data: 'action', name: 'action', orderable: false, searchable: false },
                ],
                order: [[4, 'desc']],
                displayLength: 10,
                lengthMenu: [10, 25, 50, 100],
            });
        });
    </script>
@endsection
