@extends('user_dashboard.layouts.app')
@section('style')

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .layout-wrap .wrap-table .listing-box .images {
            width: 90px;
            border-radius: 8px;
            overflow: hidden;
        }
        .layout-wrap .wrap-table thead tr th:nth-child(1) {
            width: 20%;
        }
    </style>
@endsection
@section('content')


    <div class="widget-box-2 wd-listing">
        <h6 class="title">{{__('My Reviews')}}</h6>
        <div class="wrap-table">
            <div class="table-responsive">
                <table>
                    <thead>
                    <tr>
                        <th>{{__('Property TITLE')}}</th>
                        <th>{{__('User Name')}}</th>
                        <th>{{__('Comment')}}</th>
                        <th>{{__('Rating')}}</th>
                        <th>{{__('Date Published')}}</th>
                        <th>{{__('Status')}}</th>
                        <th>{{__('Action')}}</th>
                    </tr>
                    </thead>
                    <tbody>
                    @if($reviews->count()>0)
                        @foreach($reviews as $review)

                            <tr class="file-delete" id="property-{{ $review->id }}">
                                <td>
                                    <div class="listing-box">
                                        <div class="images">
                                            <img src="{{$review->property->images[0]->img}}" alt="images">
                                        </div>
                                        <div class="content">
                                            <div class="title">
                                                <a href="{{config('app.url')}}/properties/property/{{$review->property->slug}}" class="link">
                                                    {{$review->property->title}}
                                                </a>
                                            </div>

                                            <div class="text-1 fw-7">{{$review->property->price->price}}$</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <div class="avatar avt-40 round">
                                        @php
                                            $imagePath = asset($review->user->photo);
                                            $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                        @endphp
                                        <img src="{{$correctedImagePath}}" alt="{{ $review->user->name }}">

                                    </div>
                                    <p class="note p-16">
                                        {{ $review->user->name }}
                                    </p>
                                </td>
                                <td>
                                    {{ $review->comment }}
                                </td>
                                <td>
                                    <ul class="list-star">
                                        @for($i = 0; $i < 5; $i++)
                                            <li class="icon icon-star{{ $i < $review->star ? '' : '-outline' }}"></li>
                                        @endfor
                                    </ul>

                                </td>
                                <td>
                                    <span>{{ \Carbon\Carbon::parse($review->created_at)->format('F j, Y') }}</span>
                                </td>
                                <td>
                                    <select name="status" class="form-select" id="status-{{ $review->id }}" onchange="updateReviewStatus({{ $review->id }}, this)">
                                        <option value="0" {{ $review->status == 0 ? 'selected' : '' }}>{{ __('Pending') }}</option>
                                        <option value="1" {{ $review->status == 1 ? 'selected' : '' }}>{{ __('Show') }}</option>
                                        <option value="2" {{ $review->status == 2 ? 'selected' : '' }}>{{ __('Not Show') }}</option>
                                    </select>
                                </td>
                                <td>
                                    <ul class="list-action">

                                        <li><a class="item" href="javascript:void(0)" onclick="deleteItem({{$review->id}})"><i class="icon icon-trash"></i>Delete</a></li>
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
                {{ $reviews->links('pagination::bootstrap-5') }}
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
            var data_url_delete = '{{ route('user.reviews.delete', '') }}' + '/' + id;

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
        function updateReviewStatus(id, selectElement) {


            var newStatus = selectElement.value;
            var CSRF_TOKEN = $('meta[name="csrf-token"]').attr('content');
            var data_url_update = '{{ route('user.reviews.update_status', '') }}' + '/' + id;

            Swal.fire({
                title: "{{ __('Update Status?') }}",
                text: "{{ __('Please confirm the status update.') }}",
                icon: "warning",
                showCancelButton: true,
                confirmButtonText: "{{ __('Yes, update!') }}",
                cancelButtonText: "{{ __('No, cancel!') }}",
                confirmButtonColor: "#DD6B55",
                reverseButtons: true
            }).then(function (e) {
                if (e.value === true) {
                    // Append the loader HTML to the body
                    $('body').append(`
            <div id="loader" class="preload preload-container">
                <div class="boxes">
                    <div class="box">
                        <div></div> <div></div> <div></div> <div></div>
                    </div>
                    <div class="box">
                        <div></div> <div></div> <div></div> <div></div>
                    </div>
                    <div class="box">
                        <div></div> <div></div> <div></div> <div></div>
                    </div>
                    <div class="box">
                        <div></div> <div></div> <div></div> <div></div>
                    </div>
                </div>
            </div>
        `);

                    // Show the loader
                    $('#loader').show();
                    $.ajax({
                        type: 'PUT', // or 'POST' if you're using a different method
                        url: data_url_update,
                        data: {
                            _token: CSRF_TOKEN,
                            status: newStatus
                        },
                        dataType: 'JSON',
                        success: function (response) {
                            if (response.status === true) {
                                Swal.fire("{{ __('Done!') }}", response.msg, "success");
                            } else {
                                Swal.fire("{{ __('Error!') }}", response.msg, "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            console.error("Failed to update review status:", xhr.responseText);
                            Swal.fire("{{ __('Error!') }}", "An error occurred: " + xhr.responseText, "error");
                        },
                        complete: function() {
                            // Hide and remove the loader
                            $('#loader').hide();
                            $('#loader').remove();
                        }
                    });
                } else {
                    e.dismiss;
                }
            });
        }


    </script>

@endsection
