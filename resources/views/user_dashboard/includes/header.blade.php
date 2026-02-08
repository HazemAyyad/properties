

<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <title>{{config('app.name')}}</title>

    <meta name="author" content="{{config('app.url')}}">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- font -->
    <link rel="stylesheet" href="{{asset('/site/fonts/fonts.css')}}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('/site/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/site/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/site/css/apexcharts.css')}}">
    <link rel="stylesheet"type="text/css" href="{{asset('/site/css/jqueryui.min.css')}}"/>
    @if (App::isLocale('en'))
    <link rel="stylesheet"type="text/css" href="{{asset('/site/css/styles.css')}}"/>
    @else
    <link rel="stylesheet"type="text/css" href="{{asset('/site/css-rtl/styles.css')}}"/>
    @endif

    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{asset('/site/images/logo/favicon.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('/site/images/logo/favicon.png')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">

    <style>
        .error {
            color: #ed2027 !important;
        }
        .form-control.style-1.error,
        input.error,
        textarea.error,
        select.error {
            border-color: #ed2027 !important;
        }
        .custom-error p {
            color: #ed2027;
            margin-top: 5px;
            font-size: 0.9rem;
        }
    </style>
    @yield('style')
</head>

<body class="body bg-surface counter-scroll">

<div class="preload preload-container">
    <div class="boxes ">
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

<!-- /preload -->

<div id="wrapper">
    <div id="page" class="clearfix">
        <div class="layout-wrap">
            <!-- header -->
            <header class="main-header fixed-header header-dashboard">
                <!-- Header Lower -->
                <div class="header-lower">
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="inner-container d-flex justify-content-between align-items-center">
                                <!-- Logo Box -->
                                <div class="logo-box d-flex">
                                    <div class="logo"><a href="{{route('site.index')}}">
                                            <img src="{{asset('/site/images/logo/logo@2x.png')}}" alt="logo" width="174" height="44"></a></div>
                                    <div class="button-show-hide">
                                        <span class="icon icon-categories"></span>
                                    </div>
                                </div>
                                <div class="nav-outer">
                                    <!-- Main Menu -->
                                    <nav class="main-menu show navbar-expand-md">
                                        <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                            <ul class="navigation clearfix">
                                                <li class=" home current"><a href="{{route('site.index')}}#">{{__('Home')}}</a>
                                                </li>


                                            </ul>
                                        </div>
                                    </nav>
                                    <!-- Main Menu End-->
                                </div>
                                <div class="header-account">
                                    <div class="language-dropdown">
                                    <a class="lang-link dropdown-toggle" href="#" id="languageDropdown" data-bs-toggle="dropdown" aria-expanded="false">
                                        {{ LaravelLocalization::getSupportedLocales()[LaravelLocalization::getCurrentLocale()]['native'] }}
                                    </a>
                                    <ul class="dropdown-menu" aria-labelledby="languageDropdown">
                                        @foreach (LaravelLocalization::getSupportedLocales() as $localeCode => $properties)
                                            @if (LaravelLocalization::getCurrentLocale() !== $localeCode)
                                                <li>
                                                    <a class="dropdown-item" href="{{ LaravelLocalization::getLocalizedURL($localeCode, null, [], true) }}">
                                                        {{ $properties['native'] }}
                                                    </a>
                                                </li>
                                            @endif
                                        @endforeach
                                    </ul>
                                </div>
                                    <a href="#" class="box-avatar dropdown-toggle" data-bs-toggle="dropdown">
                                        <div class="avatar avt-40 round">
                                            @php
                                                $imagePath = asset(Auth::guard('web')->user()->photo);
                                                $correctedImagePath = str_replace('/public/public/', '/public/', $imagePath);
                                            @endphp
                                            <img src="{{$correctedImagePath}}" alt="avt">
                                        </div>
                                        <p class="name">{{ Auth::guard('web')->user()->name }}<span class="icon icon-arr-down"></span></p>
                                        <div class="dropdown-menu">
                                            <a class="dropdown-item" href="{{route('user.properties.index')}}">{{__('My Properties')}}</a>
                                            <a class="dropdown-item" href="my-invoices.html">{{__('My Invoices')}}</a>
                                            <a class="dropdown-item" href="{{route('user.favorites.index')}}">{{__('My Favorites')}}</a>
                                            <a class="dropdown-item" href="{{route('user.reviews.index')}}">{{__('Reviews')}}</a>
                                            <a class="dropdown-item" href="{{route('user.profile.index')}}">{{__('My Profile')}}</a>
                                            <a class="dropdown-item" href="{{route('user.properties.create')}}">{{__('Add Property')}}</a>

                                            <a class="dropdown-item" href="{{ route('logout') }}"
                                               onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                                <i class="ti ti-logout me-2 ti-sm"></i>
                                                <span class="align-middle">{{__('Log Out')}}</span>
                                            </a>

                                            <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                                @csrf
                                            </form>
                                        </div>
                                    </a>
                                    <div class="flat-bt-top">
                                        <a class="tf-btn primary" href="{{route('user.properties.create')}}">{{__('Submit Property')}}</a>
                                    </div>
                                </div>

                                <div class="mobile-nav-toggler mobile-button"><span></span></div>

                            </div>
                        </div>
                    </div>
                </div>
                <!-- End Header Lower -->

                <!-- Mobile Menu  -->
                <div class="close-btn"><span class="icon flaticon-cancel-1"></span></div>
                <div class="mobile-menu">
                    <div class="menu-backdrop"></div>
                    <nav class="menu-box">
                        <div class="nav-logo">
                            <a href="{{route('site.index')}}"><img src="{{asset('/siteimages/logo/logo@2x.png')}}" alt="nav-logo" width="174" height="44"></a></div>
                        <div class="bottom-canvas">
                            <div class="menu-outer"></div>
                            <div class="button-mobi-sell">
                                <a class="tf-btn primary" href="{{route('user.properties.create')}}">{{__('Submit Property')}}</a>
                            </div>
                            <div class="mobi-icon-box">
                                <div class="box d-flex align-items-center">
                                    <span class="icon icon-phone2"></span>
                                    <div>1-333-345-6868</div>
                                </div>
                                <div class="box d-flex align-items-center">
                                    <span class="icon icon-mail"></span>
                                    <div>themesflat@gmail.com</div>
                                </div>
                            </div>
                        </div>
                    </nav>
                </div>
                <!-- End Mobile Menu -->

            </header>
            <!-- end header -->
            <!-- sidebar dashboard -->
            <div class="sidebar-menu-dashboard">
                <ul class="box-menu-dashboard">
                    <li class="nav-menu-item active"><a class="nav-menu-link" href="{{route('user.dashboard')}}"><span class="icon icon-dashboard"></span> {{__('Dashboard')}}</a></li>
                    <li class="nav-menu-item"><a class="nav-menu-link" href="{{route('user.properties.index')}}"><span class="icon icon-list-dashes"></span>{{__('My Properties')}}</a></li>
                    <li class="nav-menu-item"><a class="nav-menu-link" href="my-invoices.html"><span class="icon icon-file-text"></span> {{__('My Invoices')}}</a></li>
                    <li class="nav-menu-item"><a class="nav-menu-link" href="{{route('user.favorites.index')}}"><span class="icon icon-heart"></span>{{__('My Favorites')}}</a></li>
                    <li class="nav-menu-item"><a class="nav-menu-link" href="{{route('user.reviews.index')}}"><span class="icon icon-review"></span> {{__('Reviews')}}</a></li>
                    <li class="nav-menu-item"><a class="nav-menu-link" href="{{route('user.profile.index')}}"><span class="icon icon-profile"></span> {{__('My Profile')}}</a></li>
                    <li class="nav-menu-item"><a class="nav-menu-link" href="{{route('user.properties.create')}}">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M19.5 3H4.5C4.10218 3 3.72064 3.15804 3.43934 3.43934C3.15804 3.72064 3 4.10218 3 4.5V19.5C3 19.8978 3.15804 20.2794 3.43934 20.5607C3.72064 20.842 4.10218 21 4.5 21H19.5C19.8978 21 20.2794 20.842 20.5607 20.5607C20.842 20.2794 21 19.8978 21 19.5V4.5C21 4.10218 20.842 3.72064 20.5607 3.43934C20.2794 3.15804 19.8978 3 19.5 3ZM19.5 19.5H4.5V4.5H19.5V19.5ZM16.5 12C16.5 12.1989 16.421 12.3897 16.2803 12.5303C16.1397 12.671 15.9489 12.75 15.75 12.75H12.75V15.75C12.75 15.9489 12.671 16.1397 12.5303 16.2803C12.3897 16.421 12.1989 16.5 12 16.5C11.8011 16.5 11.6103 16.421 11.4697 16.2803C11.329 16.1397 11.25 15.9489 11.25 15.75V12.75H8.25C8.05109 12.75 7.86032 12.671 7.71967 12.5303C7.57902 12.3897 7.5 12.1989 7.5 12C7.5 11.8011 7.57902 11.6103 7.71967 11.4697C7.86032 11.329 8.05109 11.25 8.25 11.25H11.25V8.25C11.25 8.05109 11.329 7.86032 11.4697 7.71967C11.6103 7.57902 11.8011 7.5 12 7.5C12.1989 7.5 12.3897 7.57902 12.5303 7.71967C12.671 7.86032 12.75 8.05109 12.75 8.25V11.25H15.75C15.9489 11.25 16.1397 11.329 16.2803 11.4697C16.421 11.6103 16.5 11.8011 16.5 12Z" fill="#A3ABB0"/>
                            </svg>
                            {{__('Add Property')}}</a></li>
                    <li class="nav-menu-item">

                        <a class="nav-menu-link" href="{{ route('logout') }}"
                           onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                            <span class="icon icon-sign-out"></span>
                            {{__('Logout')}}
                        </a>

                        <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                            @csrf
                        </form>
                    </li>
                </ul>
            </div>
            <!-- end sidebar dashboard -->
            <div class="main-content">
                <div class="main-content-inner">
                    <div class="button-show-hide show-mb">
                        <span class="body-1">{{__('Show Dashboard')}}</span>
                    </div>

