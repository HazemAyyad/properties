@extends('user_dashboard.layouts.app')
@section('style')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <style>
        .favorites-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 24px; }
        .favorite-card { border: 1px solid #e4e4e4; border-radius: 12px; overflow: hidden; background: #fff; transition: box-shadow 0.2s; }
        .favorite-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,0.08); }
        .favorite-card .card-img-wrap { height: 180px; overflow: hidden; background: #f0f0f0; position: relative; }
        .favorite-card .card-img-wrap img { width: 100%; height: 100%; object-fit: cover; }
        .favorite-card .card-body { padding: 16px; }
        .favorite-card .card-title { font-size: 17px; font-weight: 600; margin-bottom: 8px; line-height: 1.4; }
        .favorite-card .card-title a { color: #161e2d; text-decoration: none; }
        .favorite-card .card-title a:hover { color: #1779A7; }
        .favorite-card .card-meta { font-size: 13px; color: #5c6368; margin-bottom: 8px; }
        .favorite-card .card-meta i { margin-right: 4px; }
        .favorite-card .card-price { font-size: 18px; font-weight: 700; color: #161e2d; }
        .favorite-card .card-footer { padding: 12px 16px; border-top: 1px solid #eee; display: flex; justify-content: space-between; align-items: center; }
        .favorite-card .btn-view { padding: 6px 14px; font-size: 14px; }
    </style>
@endsection
@section('content')
    <div class="widget-box-2 wd-listing">
        <h6 class="title mb-4">{{__('My Favorites')}}</h6>
        <div id="favorites-content">
        @if($properties->count() > 0)
            <div class="favorites-grid" id="property-list">
                @foreach($properties as $property)
                    @php
                        $currency = $data_settings['currency'] ?? ($property->price->currency ?? 'JOD');
                        $periodLabels = [0 => __('day'), 1 => __('week'), 2 => __('month'), 3 => __('year')];
                        $pricePeriod = ($property->type == 0 && isset($property->price->period)) ? '/' . ($periodLabels[$property->price->period] ?? __('month')) : ($property->type == 1 ? ' (' . __('Total') . ')' : '/month');
                    @endphp
                    <div class="favorite-card" id="property-{{ $property->id }}">
                        <div class="card-img-wrap">
                            @if($property->images->isNotEmpty())
                                @php
                                    $imgPath = ltrim(str_replace('/public', '', $property->images[0]->img), '/');
                                    $imgUrl = asset($imgPath);
                                    $imgUrl = str_replace('/public/public/', '/public/', $imgUrl);
                                @endphp
                                <img src="{{ $imgUrl }}" alt="{{ $property->title }}" loading="lazy">
                            @else
                                <div class="d-flex align-items-center justify-content-center h-100 text-muted">
                                    <i class="icon icon-house-line" style="font-size: 48px;"></i>
                                </div>
                            @endif
                        </div>
                        <div class="card-body">
                            <h5 class="card-title">
                                <a href="{{ route('site.property.show', $property->slug) }}">{{ $property->title }}</a>
                            </h5>
                            <div class="card-meta">
                                @if($property->category)
                                    <span class="badge bg-light text-dark me-2">{{ $property->category->name }}</span>
                                @endif
                                @if($property->address)
                                    <div class="mt-1"><i class="icon icon-mapPin"></i> {{ $property->address->display_address ?? '-' }}</div>
                                @endif
                                @if($property->more_info)
                                    <div class="mt-1">
                                        @if($property->more_info->bedrooms)
                                            <span class="me-3"><i class="icon icon-bed"></i> {{ $property->more_info->bedrooms }}</span>
                                        @endif
                                        @if($property->more_info->bathrooms)
                                            <span class="me-3"><i class="icon icon-bathtub"></i> {{ $property->more_info->bathrooms }}</span>
                                        @endif
                                        @if($property->more_info->size)
                                            <span><i class="icon icon-ruler"></i> {{ $property->more_info->size }} m²</span>
                                        @endif
                                    </div>
                                @endif
                            </div>
                            <div class="card-price mt-2">
                                {{ $currency }} {{ number_format($property->price->price ?? 0) }}<span class="text-muted small fw-normal">{{ $pricePeriod }}</span>
                            </div>
                        </div>
                        <div class="card-footer">
                            <a href="{{ route('site.property.show', $property->slug) }}" class="tf-btn primary btn-view">{{__('View')}}</a>
                            <a href="javascript:void(0)" onclick="deleteItem({{ $property->id }})" class="text-danger" title="{{__('Remove from favorites')}}">
                                <i class="icon icon-trash"></i> {{__('Remove')}}
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
            <div class="pagination-wrapper mt-4">
                {{ $properties->links('pagination::bootstrap-5') }}
            </div>
        @else
            <div class="text-center py-5" id="favorites-empty">
                <i class="icon icon-heart" style="font-size: 64px; color: #ddd;"></i>
                <p class="mt-3 text-muted">{{__('You have no favorites yet.')}}</p>
                <a href="{{ route('site.properties') }}" class="tf-btn primary mt-2">{{__('Browse Properties')}}</a>
            </div>
        @endif
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
                                if (typeof toastr !== 'undefined') {
                                    toastr.success('{{ __("Removed from favorites") }}');
                                } else {
                                    Swal.fire("Done!", response.msg, "success");
                                }
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
            if (!url) return;
            $.ajax({
                url: url,
                type: "GET",
                dataType: "html",
                success: function(data) {
                    var $data = $(data);
                    var content = $data.find('#favorites-content').html();
                    if (content) $('#favorites-content').html(content);
                },
                error: function() {
                    Swal.fire('Error!', '{{ __("Failed to load properties.") }}', 'error');
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
