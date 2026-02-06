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

                <li class="breadcrumb-item active">{{__('Shipment Form')}}</li>
                <!-- Basic table -->


                <!--/ Basic table -->
            </ol>
        </nav>
        <div class="card">
            <div class="card-body">
                <h3>Total : {{$data['total']}}</h3>
            </div>
        </div>
        <div class="card">

            <div class="card-datatable table-responsive pt-0">
                <table class="datatables-basic table">
                    <thead>
                    <tr >
                        <th>Label ID</th>
                        <th>Status</th>
                        <th>shipment ID</th>
                        <th>shipment cost</th>
                        <th>insurance cost</th>
                        <th>tracking number</th>
                        <th>service code</th>
                        <th>packages</th>
                        <th>Date</th>
                    </tr>
                    </thead>
                    <tbody>
                    @foreach ($data['labels'] as $label)
                        <tr>
                            <td>{{ $label['label_id'] }}</td>
                            <td>{{ $label['status'] }}</td>
                            <td>{{ $label['shipment_id'] }}</td>
                            <td>${{ $label['shipment_cost']['amount'] }}</td>
                            <td>${{ $label['insurance_cost']['amount'] }}</td>
                            <td>{{ $label['tracking_number'] }}</td>
                            <td>{{ $label['service_code'] }}</td>
                            <td>{{ \Carbon\Carbon::parse($label['created_at'])->format('m/d/Y') }}</td>
                            <td>
                                @foreach($label['packages'] as $package)
                                    @php
                                        // Assuming $package['label_messages']['reference1'] contains "customer reference number #1436"
                                        $referenceString = $package['label_messages']['reference1'];

                                        // Use PHP string manipulation to extract the numeric part
                                        $parts = explode('#', $referenceString);
                                        $numericPart = isset($parts[1]) ? trim($parts[1]) : '';
                                          $user=  \App\Models\User::query()->where('user_no',$numericPart)->first();
                                    @endphp
                                    <span>{{$package['weight']['value']}}lb ({{$package['dimensions']['length'].'*'.$package['dimensions']['width'].'*'.$package['dimensions']['height']}})inch </span>
                                @if($user)
                                        <span><a href="{{route('users.edit',$user->id)}}" class="text-success">#{{$user->user_no}}</a></span>
                                @else
                                        <span>{{$numericPart}}</span>
                                @endif

                                 @endforeach
                            </td>

                            <!-- Add more table cells for the data you want to display -->
                        </tr>
                    @endforeach
                    </tbody>
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


{{--
    <script type="text/javascript">
        function deleteItem(id) {

            var data_url_delete='{{ route('blogs.delete','')}}'+'/'+id

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
    </script> --}}
@endsection
