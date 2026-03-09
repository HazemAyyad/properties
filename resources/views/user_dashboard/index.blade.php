@extends('user_dashboard.layouts.app')
@section('style')
    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .plan-limit-notice { background: linear-gradient(135deg, #fff9e8 0%, #fff5d6 100%); border: 1px solid #e5d4a1; border-radius: 14px; padding: 1.25rem 1.5rem; display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 1.25rem; color: #5a4a1a; margin-bottom: 1.5rem; box-shadow: 0 2px 8px rgba(184, 134, 11, 0.08); }
        .plan-limit-notice .plan-info { font-size: 1rem; line-height: 1.6; max-width: 600px; }
        .plan-limit-notice .plan-info strong { color: #b8860b; font-weight: 600; }
    </style>
@endsection
@section('content')
    @if(isset($planLimit) && !$planLimit['allowed'])
    <div class="plan-limit-notice">
        <div class="plan-info">
            {{ __('Your plan') }} <strong>{{ $planLimit['plan'] ? $planLimit['plan']->title : __('None') }}</strong> {{ __('has reached its limit') }}. {{ __('Upgrade your account') }} {{ __('to add more properties') }}.
        </div>
        <a href="{{ route('user.profile.upgrade') }}" class="tf-btn primary">{{ __('Upgrade your account') }}</a>
    </div>
    @endif
    <div class="flat-counter-v2 tf-counter">
        <div class="counter-box">
            <div class="box-icon w-68 round">
                <span class="icon icon-list-dashes"></span>
            </div>
            <div class="content-box">
                <div class="title-count">{{__('your Listing')}}</div>
                <div class="d-flex align-items-end">
                    @if(isset($planLimit))
                    <h6 class="number" data-speed="2000" data-to="{{ $planLimit['used'] }}" data-inviewport="yes">{{ $planLimit['used'] }}</h6>
                    <span class="fw-7 text-variant-2">/ {{ $planLimit['limit'] === -1 ? __('Unlimited') : $planLimit['limit'] }}@if($planLimit['remaining'] !== null && $planLimit['remaining'] > 0) ({{ __('remaining') }}: {{ $planLimit['remaining'] }})@endif</span>
                    @else
                    <h6 class="number" data-speed="2000" data-to="{{ $properties->total() }}" data-inviewport="yes">{{ $properties->total() }}</h6>
                    @endif
                </div>

            </div>
        </div>
        <div class="counter-box">
            <div class="box-icon w-68 round">
                <span class="icon icon-clock-countdown"></span>
            </div>
            <div class="content-box">
                <div class="title-count">{{__('Pending')}}</div>
                <div class="d-flex align-items-end">
                    <h6 class="number" data-speed="2000" data-to="{{ $pendingCount ?? 0 }}" data-inviewport="yes">{{ $pendingCount ?? 0 }}</h6>
                </div>

            </div>
        </div>
        <div class="counter-box">
            <div class="box-icon w-68 round">
                <span class="icon icon-bookmark"></span>
            </div>
            <div class="content-box">
                <div class="title-count">{{__('Favorite')}}</div>
                <div class="d-flex align-items-end">
                    <h6 class="number" data-speed="2000" data-to="{{ $favoritesCount ?? 0 }}" data-inviewport="yes">{{ $favoritesCount ?? 0 }}</h6>
                </div>

            </div>
        </div>
        <div class="counter-box">
            <div class="box-icon w-68 round">
                <span class="icon icon-review"></span>
            </div>
            <div class="content-box">
                <div class="title-count">{{__('Reviews')}}</div>
                <div class="d-flex align-items-end">
                    <h6 class="number" data-speed="2000" data-to="{{ $reviewsCount ?? 0 }}" data-inviewport="yes">{{ $reviewsCount ?? 0 }}</h6>
                </div>

            </div>
        </div>
    </div>
    <div class="wrapper-content row">
        <div class="col-xl-9">
            <div class="widget-box-2 wd-listing">
                <h6 class="title">{{__('New Listing')}}</h6>
                @include('user_dashboard.includes.filter')
                <div class="d-flex gap-4"><span class="text-primary fw-7">{{$properties->count()}}</span><span class="text-variant-1">{{__('Results found')}}</span></div>
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
                            <tbody>

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
                                                        <a href="{{config('app.url')}}/property/{{$property->slug}}" class="link">
                                                            {{$property->title}}
                                                        </a>
                                                    </div>
                                                    <div class="text-date">
                                                        {{ $property->address?->display_address ?? '-' }}
                                                    </div>
                                                    <div class="text-1 fw-7">{{$property->price->price}}</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <span>{{ \Carbon\Carbon::parse($property->created_at)->format('F j, Y') }}</span>
                                        </td>

                                        <td>
                                            <ul class="list-action">
                                                <li><a href="{{route('user.properties.edit',$property->id)}}" class="item"><i class="icon icon-edit"></i>Edit</a></li>
                                                <li><a class="item" href="javascript:void(0)" onclick="soldItem({{$property->id}})"><i class="icon icon-sold"></i>Sold</a></li>
                                                <li><a class="item" href="javascript:void(0)" onclick="deleteItem({{$property->id}})"><i class="icon icon-trash"></i>Delete</a></li>
                                            </ul>
                                        </td>
                                    </tr>
                                @endforeach
                            @else
                                <tr class="file-delete"  >
                                    <td>
                                        {{__('not found any things')}}
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

        </div>
        <div class="col-xl-3">
            <div class="widget-box-3 mess-box">
                <h6>{{__("Messages ")}}</h6>
                <span class="text-variant-1">{{__('No message')}}</span>
            </div>
            <div class="widget-box-3 recent-box">
                <h6>{{__('Recent Reviews')}}</h6>

                @if($reviews->isEmpty())
                    <div class="box-tes-item">
                        <span class="text-variant-1">{{__('No Reviews')}}</span>
                    </div>
                @else
                    @foreach($reviews as $review)
                            <div class="box-tes-item">
                                <div class="box-avt d-flex align-items-center gap-12">
                                    <div class="avatar avt-40 round">
                                        <img src="{{ $review->user->avatar_url }}" alt="{{ $review->user->name }}" loading="lazy">
                                    </div>
                                    <p>{{ \Carbon\Carbon::parse($review->created_at)->format('F d, Y') }}</p>
                                </div>
                                <p class="note p-16">
                                    {{ $review->comment }}
                                </p>
                                <ul class="list-star">
                                    @for($i = 0; $i < 5; $i++)
                                        <li class="icon icon-star{{ $i < $review->star ? '' : '-outline' }}"></li>
                                    @endfor
                                </ul>
                            </div>
                    @endforeach
                @endif
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
    <script !src="">
        $(document).ready(function() {
            $('#datepicker1, #datepicker2').datepicker({
                dateFormat: 'yy-mm-dd'
            });


        });
        $(document).ready(function() {
            $('select').niceSelect(); // If you're using niceSelect for styling

            // Handle dropdown changes

        });
    </script>


@endsection
