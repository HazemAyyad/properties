@extends('user_dashboard.layouts.app')
@section('style')

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .plan-limit-box { border-radius: 14px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem; }
        .plan-limit-box.allowed { background: linear-gradient(135deg, #e8f4f8 0%, #d4ebf2 100%); border: 1px solid #b8dde8; color: #0c5460; }
        .plan-limit-box.limit-reached { background: linear-gradient(135deg, #fff9e8 0%, #fff5d6 100%); border: 1px solid #e5d4a1; color: #5a4a1a; box-shadow: 0 2px 8px rgba(184, 134, 11, 0.08); }
        .plan-limit-box .plan-info { font-size: 1rem; line-height: 1.6; }
        .plan-limit-box.allowed .plan-info strong { color: #1779A7; }
        .plan-limit-box.limit-reached .plan-info { max-width: 600px; }
        .plan-limit-box.limit-reached .plan-info strong { color: #b8860b; font-weight: 600; }
        .plan-limit-box.limit-reached .plan-meta { font-size: 0.9rem; color: #7a6a2a; margin-top: 0.35rem; }
    </style>

@endsection
@section('content')

    @if(isset($planLimit))
    <div class="widget-box-2 mb-4">
        <div class="plan-limit-box {{ $planLimit['allowed'] ? 'allowed' : 'limit-reached' }}">
            <div class="plan-info">
                @if($planLimit['allowed'])
                    <strong>{{ __('Your plan') }}</strong>: {{ $planLimit['plan'] ? $planLimit['plan']->title : __('None') }}
                    &nbsp;·&nbsp;
                    {{ __('Properties') }}: {{ $planLimit['used'] }} / {{ $planLimit['limit'] === -1 ? __('Unlimited') : ($planLimit['limit'] ?? '-') }}
                    @if($planLimit['remaining'] !== null && $planLimit['remaining'] > 0)
                        &nbsp;({{ __('remaining') }}: {{ $planLimit['remaining'] }})
                    @endif
                @else
                    <strong>{{ __('Your plan') }} {{ $planLimit['plan'] ? $planLimit['plan']->title : __('None') }}</strong> {{ __('has reached its limit') }}. {{ __('Upgrade your account') }} {{ __('to add more properties') }}.
                    <div class="plan-meta">{{ __('Properties') }}: {{ $planLimit['used'] }} / {{ $planLimit['limit'] ?? '-' }}</div>
                @endif
            </div>
            @if(!$planLimit['allowed'])
                <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary">{{ __('Upgrade your account') }}</a>
            @else
                <a href="{{ route('user.properties.create') }}" class="tf-btn primary">{{ __('Add Property') }}</a>
            @endif
        </div>
    </div>
    @endif

    <div class="widget-box-2 wd-listing">
        <h6 class="title">{{__('My Properties')}}</h6>
        <div class="wrap-table">
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>{{__('LISTING TITLE')}}</th>
                        <th>{{__('Date Published')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>'
                    @if($properties->count()>0)
                    @foreach( $properties as $property)
                        <tr class="file-delete" id="property-{{ $property->id }}">
                            <td>
                                <div class="listing-box">
                                    <div class="images">
                                        @php
                                            $imagePath = asset($property->images[0]->img);
                                            $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                        @endphp
                                        <img src="{{$correctedImagePath}}" alt="images">
                                    </div>
                                    <div class="content">
                                        <div class="title">
                                            <a href="{{config('app.url')}}/properties/property/{{$property->slug}}" class="link">
                                                {{$property->title}}
                                            </a>
                                        </div>
                                        <div class="text-date">{{ $property->address?->display_address ?? '-' }}</div>
                                        <div class="text-1 fw-7">{{$property->price->price}}</div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span>{{ \Carbon\Carbon::parse($property->created_at)->format('F j, Y') }}</span>
                            </td>

                            <td>
                                <ul class="list-action">
                                    <li><a href="{{route('user.properties.edit',$property->id)}}" class="item"><i class="icon icon-edit"></i>{{__('Edit')}}</a></li>
                                    <li><a class="item" href="javascript:void(0)" onclick="soldItem({{$property->id}})"><i class="icon icon-sold"></i>{{__('Sold')}}</a></li>
                                    <li><a class="item" href="javascript:void(0)" onclick="deleteItem({{$property->id}})"><i class="icon icon-trash"></i>{{__('Delete')}}</a></li>
                                </ul>
                            </td>
                        </tr>
                    @endforeach
                    @else
                        <tr class="file-delete"  >
                            <td>
                                {{__('Not Found Any Things')}}
                            </td>


                        </tr>
                    @endif

                    </tbody>
                </table>
            </div>

            <!-- Pagination Links -->
            <div class="pagination-wrapper">
                {{ $properties->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <!-- Toastr JS -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
    <script !src="">
        $(document).on('click', '.pagination a', function(e) {
            e.preventDefault();
            var url = $(this).attr('href');
            fetchProperties(url);
            window.history.pushState("", "", url);
        });

        function fetchProperties(url) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "html",
                success: function(data) {
                    $('#property-list').html($(data).find('#property-list').html());
                    $('.pagination-wrapper').html($(data).find('.pagination-wrapper').html());
                },
                error: function() {
                    alert('Failed to load properties.');
                }
            });
        }

    </script>

    <script type="text/javascript">
        function deleteItem(id) {
            var data_url_delete = '{{ route('user.properties.delete', '') }}' + '/' + id;

            Swal.fire({
                title: "{{__('Delete?')}}",
                text: "{{__('Please confirm approval')}}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{__('Yes, delete!')}}",
                cancelButtonText: "{{__('No, back off!')}}",
                confirmButtonColor: "#DD6B55",
                reverseButtons: true
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
                                Swal.fire("Done!", response.msg, "success");

                                // Remove the row from the table
                                $('#property-' + id).remove();

                                // Fetch the current page to refresh the data and pagination
                                var currentPage = $('.pagination-wrapper .active a').attr('href');
                                if (currentPage) {
                                    fetchProperties(currentPage);
                                }

                            } else {
                                Swal.fire("Error!", response.msg, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Failed to delete property:", xhr.responseText);
                            Swal.fire("Error!", "An error occurred: " + xhr.responseText, "error");
                        }
                    });
                } else {
                    e.dismiss;
                }
            });
        }

        function fetchProperties(url) {
            $.ajax({
                url: url,
                type: "GET",
                dataType: "html",
                success: function(data) {
                    $('#property-list').html($(data).find('#property-list').html());
                    $('.pagination-wrapper').html($(data).find('.pagination-wrapper').html());
                },
                error: function() {
                    Swal.fire('Error!', 'Failed to load properties.', 'error');
                }
            });
        }
        function soldItem(id) {
            var data_url_sold = '{{ route('user.properties.sold', '') }}' + '/' + id;

            Swal.fire({
                title: "{{__('Sold?')}}",
                text: "{{__('Please confirm approval')}}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{__('Yes, Sold!')}}",
                cancelButtonText: "{{__('No, back off!')}}",
                confirmButtonColor: "#DD6B55",
                reverseButtons: true
            }).then(function (e) {
                if (e.value === true) {
                    var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
                    $.ajax({
                        type: 'get',
                        url: data_url_sold,
                        data: {_token: CSRF_TOKEN},
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.status === true) {
                                Swal.fire("Done!", response.msg, "success");

                                // Optionally reload the DataTable or update the status in the row
                                // $('.datatables-basic').DataTable().ajax.reload();

                            } else {
                                Swal.fire("Error!", response.msg, "error");
                            }
                        },
                        error: function(xhr, status, error) {
                            console.error("Failed to mark property as sold:", xhr.responseText);
                            Swal.fire("Error!", "An error occurred: " + xhr.responseText, "error");
                        }
                    });
                } else {
                    e.dismiss;
                }
            });
        }
    </script>


@endsection
