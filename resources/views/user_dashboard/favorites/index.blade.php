@extends('user_dashboard.layouts.app')
@section('style')

    <!-- Toastr CSS -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

@endsection
@section('content')


    <div class="widget-box-2 wd-listing">
        <h6 class="title">{{__('My Favorites')}}</h6>
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
                                            <img src="{{$property->images[0]->img}}" alt="images">
                                        </div>
                                        <div class="content">
                                            <div class="title">
                                                <a href="{{config('app.url')}}/properties/property/{{$property->slug}}" class="link">
                                                    {{$property->title}}
                                                </a>
                                            </div>
                                            <div class="text-date">
                                                {{$property->address->full_address}}, {{$property->address->city->name}},
                                                {{$property->address->state->name}},{{$property->address->country->name}}
                                            </div>
                                            <div class="text-1 fw-7">{{$property->price->price}}$</div>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span>{{ \Carbon\Carbon::parse($property->created_at)->format('F j, Y') }}</span>
                                </td>

                                <td>
                                    <ul class="list-action">

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
            var data_url_delete = '{{ route('user.favorites.delete', '') }}' + '/' + id;

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
