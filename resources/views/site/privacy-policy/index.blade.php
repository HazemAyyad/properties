@extends('site.layouts.app')

@section('style')
    <!-- Add your styles here if needed -->
@endsection

@section('content')
    <!-- Page Title -->
    <section class="flat-title-page style-2">
        <div class="container">
            <ul class="breadcrumb">
                <li><a href="{{ url('/') }}">{{ __('Home') }}</a></li>
                <li>/ {{ __('Pages') }}</li>
                <li>/ {{ __('Privacy Policy') }}</li>
            </ul>
            <h2 class="text-center">{{ __('Privacy Policy') }}</h2>
        </div>
    </section>
    <!-- End Page Title -->

    <section class="flat-section">
        <div class="container">
            <div class="row">
                <div class="col-lg-5">
                    <ul class="nav-tab-privacy" role="tablist">
                        @foreach ($categories as  $category)
                            <li class="nav-tab-item" role="presentation">
                                <a href="#category-{{ $category->id }}" class="nav-link-item {{ $category->id == 1 ? 'active' : '' }}" data-bs-toggle="tab">
                                    {{ $loop->iteration }}. {{ $category->title }}
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </div>

                <div class="col-lg-7">
                    <h5 class="text-capitalize title">{{ __('Terms of use') }}</h5>
                    <div class="tab-content content-box-privacy">
                        @foreach ($categories as  $category)
                            <div class="tab-pane fade {{ $category->id == 1 ? 'active show' : '' }}" id="category-{{ $category->id }}" role="tabpanel">
                                <h6>{{ $loop->iteration }}. {{ $category->title }}</h6>
                                @foreach ($policies as $policy)
                                    @if ($policy->category_id == $category->id)
                                        <p>
                                            {!! nl2br(e(App::isLocale('en') ? $policy->answer_en : $policy->answer_ar)) !!}
                                        </p>
                                    @endif
                                @endforeach
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
    <!-- Add your scripts here if needed -->
@endsection
