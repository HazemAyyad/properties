@extends('site.layouts.app')

@section('style')
    <!-- Add your styles here if needed -->
@endsection

@section('content')
    <section class="flat-section-v6 flat-recommended flat-sidebar">
        <div class="container">
            <div class="box-title-listing">
                <h5>{{__('Property Listing')}}</h5>
                <div class="box-filter-tab">
                    <!-- Filter Tab -->
                    <ul class="nav-tab-filter" role="tablist">
                        <li class="nav-tab-item" role="presentation">
                            <a href="#gridLayout" class="nav-link-item active" data-bs-toggle="tab"><i class="icon icon-grid"></i></a>
                        </li>
                        <li class="nav-tab-item" role="presentation">
                            <a href="#listLayout" class="nav-link-item" data-bs-toggle="tab"><i class="icon icon-list"></i></a>
                        </li>
                    </ul>
                    <!-- Per Page and Sort Dropdowns in Form -->
                    <form id="filterForm" method="GET" action="{{ route('site.properties') }}">
                        <div class="d-flex gap-3 align-items-center">
                            <select name="per_page" class="list-page" id="per_page">
                                @foreach([10, 11, 12] as $perPage)
                                    <option value="{{ $perPage }}" {{ request()->get('per_page') == $perPage ? 'selected' : '' }}>{{ $perPage }} {{__('Per Page')}}</option>
                                @endforeach
                            </select>

                            <select name="sort_field" class="list-sort-field" id="sort_field">
                                <option value="created_at" {{ request()->get('sort_field') == 'created_at' ? 'selected' : '' }}>{{ __('Sort by Date') }}</option>
                                <option value="price" {{ request()->get('sort_field') == 'price' ? 'selected' : '' }}>{{ __('Sort by Price') }}</option>
                            </select>

                            <select name="sort_by" class="list-sort" id="sort_by">
                                <option value="desc" {{ request()->get('sort_by') == 'desc' ? 'selected' : '' }}>{{__('High to Low')}}</option>
                                <option value="asc" {{ request()->get('sort_by') == 'asc' ? 'selected' : '' }}>{{__('Low to High')}}</option>
                            </select>

                        </div>
                    </form>
                </div>
            </div>

            <div class="row">
                <!-- Sidebar -->
                <div class="col-xl-4 col-lg-5">
                    <div class="widget-sidebar fixed-sidebar">
                        @include('site.properties.partials.filter_search') <!-- Extract the search form into a partial -->
                        @include('site.properties.partials.latest_properties', ['latestProperties' => $latestProperties]) <!-- Pass latest properties to partial -->
                    </div>
                </div>

                <!-- Property Listing -->
                <div class="col-xl-8 col-lg-7">
                    <div class="tab-content">
                        <!-- Grid Layout -->
                        <div class="tab-pane fade active show" id="gridLayout" role="tabpanel">
                            <div class="row">
                                @foreach($properties as $property)
                                    <div class="col-md-6">
                                        <div class="homeya-box">
                                            <div class="archive-top">
                                                @php
                                                    $imagePath = asset($property->images[0]->img);
                                                    $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                                @endphp
                                                <div   class="images-group">
                                                    <div class="images-style-sp images-style">
                                                        <img src="{{$correctedImagePath}}" alt="{{ $property->title }}">
                                                    </div>
                                                    <div class="top">
                                                        <ul class="d-flex gap-8">
                                                            @if($property->is_featured)
                                                                <li class="flag-tag success">Featured</li>
                                                            @endif
                                                            <li class="flag-tag style-1">
                                                                @if($property->status==0)
                                                                    {{__('Not available')}}
                                                                @elseif($property->status==1)
                                                                    {{__('Preparing selling')}}
                                                                @elseif($property->status==2)
                                                                    {{__('Selling')}}
                                                                @elseif($property->status==3)
                                                                    {{__('sold')}}
                                                                @elseif($property->status==4)
                                                                    {{__('Renting')}}
                                                                @elseif($property->status==5)
                                                                    {{__('Rented')}}
                                                                @elseif($property->status==6)
                                                                    {{__('Building')}}
                                                                @else
                                                                    {{__('Unknown')}}
                                                                @endif
                                                            </li>
                                                        </ul>
                                                        <ul class="d-flex gap-4">

                                                            <li class="box-icon w-32">
                                                                <a href="javascript:"  data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $property->id }})">
                                                                    <span id="favorite-icon-{{ $property->id }}" class="icon {{ $property->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                                                </a>
                                                            </li>
                                                            <li class="box-icon w-32">
                                                                <a href="{{ route('site.property.show', $property->slug) }}"  data-toggle="tooltip" data-placement="top" title="Visit" onclick="toggleFavorite({{ $property->id }})">
                                                                    <span class="icon icon-eye"></span>
                                                                </a>
                                                            </li>
                                                        </ul>
                                                    </div>
                                                    <div class="bottom">
                                                        <span class="flag-tag style-2">{{ $property->category->name }}</span>
                                                    </div>
                                                </div>
                                                <div class="content">
                                                    <div class="h7 text-capitalize fw-7">
                                                        <a href="{{ route('site.property.show', $property->slug) }}" class="link">{{ $property->title }}</a>
                                                    </div>
                                                    <div class="desc"><i class="fs-16 icon icon-mapPin"></i><p>
                                                            {{$property->address->full_address}}, {{$property->address->city->name}},
                                                            {{$property->address->state->name}},{{$property->address->country->name}}
                                                        </p></div>
                                                    <ul class="meta-list">
                                                        <li class="item">
                                                            <i class="icon icon-bed"></i>
                                                            <span>{{ $property->more_info->bedrooms }}</span>
                                                        </li>
                                                        <li class="item">
                                                            <i class="icon icon-bathtub"></i>
                                                            <span>{{ $property->more_info->bathrooms }}</span>
                                                        </li>
                                                        <li class="item">
                                                            <i class="icon icon-ruler"></i>
                                                            <span>{{ $property->more_info->size }} m²</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </div>
                                            <div class="archive-bottom d-flex justify-content-between align-items-center">
                                                <div class="d-flex gap-8 align-items-center">
                                                    <div class="avatar avt-40 round">
                                                        @if($property->user_id!=null)
                                                            <img src="{{$property->user->photo}}" alt="{{$property->user->name}}">

                                                        @else
                                                            <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                                        @endif
                                                    </div>
                                                    <span>
                                                    @if($property->user_id!=null)
                                                            {{$property->user->name}}
                                                        @else
                                                            {{config('app.name')}}

                                                        @endif
                                                    </span>
                                                </div>
                                                <div class="d-flex align-items-center">
                                                    <h6> {{$data_settings['currency']}} {{ $property->price->price }}</h6>
                                                    <span class="text-variant-1">/m²</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Pagination Links -->
                            <div class="pagination-wrapper">
                                {{ $properties->links('pagination::bootstrap-5') }}
                            </div>
                        </div>

                        <!-- List Layout -->
                        <div class="tab-pane fade" id="listLayout" role="tabpanel">
                            <div class="row">
                                @foreach($properties as $property)
                                    <div class="col-md-12">
                                        <div class="homeya-box list-style-1 list-style-2">
                                            @php
                                                $imagePath = asset($property->images[0]->img);
                                                $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                            @endphp
                                            <div   class="images-group">
                                                <div class="images-style images-style-sp">
                                                    <img src="{{$correctedImagePath}}" alt="{{ $property->title }}">
                                                </div>
                                                <div class="top">
                                                    <ul class="d-flex gap-4 flex-wrap">
                                                        <li class="flag-tag style-1">
                                                            @if($property->status==0)
                                                                {{__('Not available')}}
                                                            @elseif($property->status==1)
                                                                {{__('Preparing selling')}}
                                                            @elseif($property->status==2)
                                                                {{__('Selling')}}
                                                            @elseif($property->status==3)
                                                                {{__('sold')}}
                                                            @elseif($property->status==4)
                                                                {{__('Renting')}}
                                                            @elseif($property->status==5)
                                                                {{__('Rented')}}
                                                            @elseif($property->status==6)
                                                                {{__('Building')}}
                                                            @else
                                                                {{__('Unknown')}}
                                                            @endif
                                                        </li>
                                                    </ul>
                                                    <ul class="d-flex gap-4">

                                                        <li class="box-icon w-32">
                                                            <a href="javascript:"  data-toggle="tooltip" data-placement="top" title="Toggle Favorite" onclick="toggleFavorite({{ $property->id }})">
                                                                <span id="favorite-icon-{{ $property->id }}" class="icon {{ $property->isFavorited() ? 'fa-solid fa-heart' : 'icon-heart' }}"></span>
                                                            </a>
                                                        </li>
                                                        <li class="box-icon w-32">
                                                            <a href="{{ route('site.property.show', $property->slug) }}"  data-toggle="tooltip" data-placement="top" title="Visit" onclick="toggleFavorite({{ $property->id }})">
                                                                <span class="icon icon-eye"></span>
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="bottom">
                                                    <span class="flag-tag style-2">{{ $property->category->name  }}</span>
                                                </div>
                                            </div>
                                            <div class="content">
                                                <div class="archive-top">
                                                    <div class="h7 text-capitalize fw-7"><a href="{{ route('site.property.show', $property->slug) }}" class="link">{{ $property->title }}</a></div>
                                                    <div class="desc"><i class="icon icon-mapPin"></i><p>
                                                            {{$property->address->full_address}}, {{$property->address->city->name}},
                                                            {{$property->address->state->name}},{{$property->address->country->name}}
                                                        </p> </div>
                                                    <ul class="meta-list">
                                                        <li class="item">
                                                            <i class="icon icon-bed"></i>
                                                            <span>{{ $property->more_info->bedrooms }}</span>
                                                        </li>
                                                        <li class="item">
                                                            <i class="icon icon-bathtub"></i>
                                                            <span>{{ $property->more_info->bathrooms }}</span>
                                                        </li>
                                                        <li class="item">
                                                            <i class="icon icon-ruler"></i>
                                                            <span>{{ $property->more_info->size }} m²</span>
                                                        </li>
                                                    </ul>
                                                </div>
                                                <div class="d-flex justify-content-between align-items-center archive-bottom">
                                                    <div class="d-flex gap-8 align-items-center">
                                                        <div class="avatar avt-40 round">
                                                            @if($property->user_id!=null)
                                                                <img src="{{$property->user->photo}}" alt="{{$property->user->name}}">

                                                            @else
                                                                <img src="https://images.ctfassets.net/lh3zuq09vnm2/yBDals8aU8RWtb0xLnPkI/19b391bda8f43e16e64d40b55561e5cd/How_tracking_user_behavior_on_your_website_can_improve_customer_experience.png" alt="avt">
                                                            @endif
                                                        </div>
                                                        <span>
                                                    @if($property->user_id!=null)
                                                                {{$property->user->name}}
                                                            @else
                                                                {{config('app.name')}}

                                                            @endif
                                                    </span>
                                                    </div>
                                                    <div class="d-flex align-items-center">
                                                        <h6> {{$data_settings['currency']}} {{ $property->price->price }}</h6>
                                                        <span class="text-variant-1">/m²</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                            <!-- Pagination Links -->
                            <div class="pagination-wrapper">
                                {{ $properties->links('pagination::bootstrap-5') }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    <script>

    </script>
    <script>

        $(document).ready(function() {
            $('select').niceSelect(); // If you're using niceSelect for styling

            // Handle dropdown changes
            $('#per_page, #sort_by').on('change', function() {
                $('#filterForm').submit();
            });
        });


        document.addEventListener('DOMContentLoaded', function () {
            var tabs = document.querySelectorAll('.nav-link-item');
            var selectedTabInput = document.getElementById('selectedTab');

            // Set default value to 'rent' if selectedTab is not already set
            if (!selectedTabInput.value) {
                selectedTabInput.value = 'rent'; // or the actual value corresponding to your 'Rent' tab
            }

            tabs.forEach(function (tab) {
                tab.addEventListener('click', function () {
                    var selectedTab = this.getAttribute('href').substring(1);
                    selectedTabInput.value = selectedTab;
                });
            });
        });

    </script>
@endsection
