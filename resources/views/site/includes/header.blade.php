<!DOCTYPE html>

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en-US" lang="en-US">

<head>
    <meta charset="utf-8">
    <title>{{config('app.name')}}</title>

    <meta name="author" content="themesflat.com">
    <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- font -->
    <link rel="stylesheet" href="{{asset('/site/fonts/fonts.css')}}">
    <!-- Icons -->
    <link rel="stylesheet" href="{{asset('/site/fonts/font-icons.css')}}">
    <link rel="stylesheet" href="{{asset('/site/css/bootstrap.min.css')}}">
    <link rel="stylesheet" href="{{asset('/site/css/swiper-bundle.min.css')}}">
    <link rel="stylesheet" href="{{asset('/site/css/animate.css')}}">
    <link rel="stylesheet" href="{{asset('/site/css/bootstrap.min.css')}}">
{{--    <link rel="stylesheet" href="{{asset('/site/css/apexcharts.css')}}">--}}
    <link rel="stylesheet"type="text/css" href="{{asset('/site/css/jqueryui.min.css')}}"/>
    @yield('style')
    @if (App::isLocale('en'))
    <link rel="stylesheet"type="text/css" href="{{asset('/site/css/styles.css')}}"/>
    @else
    <link rel="stylesheet"type="text/css" href="{{asset('/site/css-rtl/styles.css')}}"/>
    @endif
    <!-- Favicon and Touch Icons  -->
    <link rel="shortcut icon" href="{{asset('/site/images/logo/favicon.png')}}">
    <link rel="apple-touch-icon-precomposed" href="{{asset('/site/images/logo/favicon.png')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.6.0/css/all.min.css" integrity="sha512-Kc323vGBEqzTmouAECnVceyQqyqdsSiqLQISBL29aUW4U/M7pSPA/gEUZQqv1cwx4OnYxTxve5UMg5GT6L4JJg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
{{--    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.css" rel="stylesheet">--}}

    <style>
          @font-face {
            font-family: 'Bahij TheSansArabic';
            src: url({{asset('site/font/BahijTheSansArabic-Plain.eot')}}');
            src: url({{asset('site/font/BahijTheSansArabic-Plain.eot?#iefix')}}) format('embedded-opentype'),
                url({{asset('site/font/BahijTheSansArabic-Plain.woff2')}}) format('woff2'),
                url({{asset('site/font/BahijTheSansArabic-Plain.woff')}}) format('woff'),
                url({{asset('site/font/BahijTheSansArabic-Plain.ttf')}}) format('truetype'),
                url({{asset('site/font/BahijTheSansArabic-Plain.svg#BahijTheSansArabic-Plain')}}) format('svg');
            font-weight: 400;
            font-style: normal;
            font-display: swap;
        }
        @font-face {
            font-family: 'Bahij Bold';
            src: url({{asset('site/font/BahijTheSansArabic-Bold.eot')}}');
            src: url({{asset('site/font/BahijTheSansArabic-Bold.eot?#iefix')}}) format('embedded-opentype'),
                url({{asset('site/font/BahijTheSansArabic-Bold.woff2')}}) format('woff2'),
                url({{asset('site/font/BahijTheSansArabic-Bold.woff')}}) format('woff'),
                url({{asset('site/font/BahijTheSansArabic-Bold.ttf')}}) format('truetype'),
                url({{asset('site/font/BahijTheSansArabic-Bold.svg#BahijTheSansArabic-Bold')}}) format('svg');
            font-weight: 600;
            font-style: normal;
            font-display: swap;
        }
        * {
            font-family: 'Bahij TheSansArabic';
            font-style: normal;

        }

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
<style>
    .fa-heart{
        color: #ed2027 !important;
    }
    .header-property-detail .content-bottom .icon-box .item:hover .fa-heart {
        color: #FFFFFF !important;
    }
    .homeya-box .images-group .box-icon:hover .fa-heart{
        color: #FFFFFF !important;
    }
</style>
    <style>
        .menu-language {
            position: relative;
            display: inline-block;
        }

        .menu-language .menu-trigger {
            font-family: "Josefin Sans", sans-serif;
            display: inline-block;
            text-align: center;
            line-height: 26px;
            font-weight: 600;
            padding: 15px 20px;
            color: #161e2d;
            font-size: 16px;
            text-transform: capitalize;
            background-color: #f8f9fa;
            border: 1px solid #ced4da;
            border-radius: 5px;
            transition: all 300ms ease;
            text-decoration: none; /* Make sure it looks like a link */
        }

        .menu-language .menu-trigger::before {
            content: "";
            width: 0;
            height: 2px;
            bottom: 0;
            left: 0;
            position: absolute;
            background: #ED2027;
            transition: width 0.3s ease;
        }

        .menu-language .menu-trigger:hover::before {
            width: 100%;
        }

        .menu-language .dropdown-content {
            position: absolute;
            top: 100%;
            left: 0;
            width: 100%;
            z-index: 1000;
            background-color: #ffffff;
            border: 1px solid #e4e4e4;
            border-radius: 5px;
            opacity: 0;
            visibility: hidden;
            transform: scaleY(0);
            transform-origin: top;
            transition: opacity 0.3s ease, transform 0.3s ease;
        }

        .menu-language:hover .dropdown-content {
            opacity: 1;
            visibility: visible;
            transform: scaleY(1);
        }

        .menu-language .dropdown-item {
            padding: 10px 15px;
            font-size: 16px;
            color: #5c6368;
            text-transform: capitalize;
            text-decoration: none;
            display: block;
            transition: background-color 0.3s ease;
        }

        .menu-language .dropdown-item:hover {
            background-color: #f1f1f1;
            color: #000;
        }



    </style>
</head>

<body class="body counter-scroll">

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
    <div id="pagee" class="clearfix">

        <!-- Main Header -->
        <header class="main-header fixed-header">
            <!-- Header Lower -->
            <div class="header-lower">
                <div class="row">
                    <div class="col-lg-12">
                        <div class="inner-container d-flex justify-content-between align-items-center">
                            <!-- Logo Box -->
                            <div class="logo-box">
                                <div class="logo"><a href="{{route('site.index')}}"><img src="{{asset($data_settings['main_logo'])}}" alt="logo" width="60" height="60"></a></div>
                            </div>
                            <div class="nav-outer">
                                <!-- Main Menu -->
                                <nav class="main-menu show navbar-expand-md">
                                    <div class="navbar-collapse collapse clearfix" id="navbarSupportedContent">
                                        <ul class="navigation clearfix">
                                            <li class=" home current"><a href="{{route('site.index')}}#">{{__('Home')}}</a>
{{--                                                <ul>--}}
{{--                                                    <li class="current"><a href="{{route('site.index')}}">{{__('Home')}}</a></li>--}}


{{--                                                </ul>--}}
                                            </li>
                                            <li class=" "><a href="{{route('site.properties')}}">{{__('Properties')}}</a>

                                            </li>


                                            <li class=" "><a href="{{route('site.blogs')}}">{{__('Blogs')}}</a>

                                            </li>

                                            <li class="dropdown2"><a href="#">{{__('More')}}</a>
                                                <ul>
                                                    <li><a href="{{route('site.faqs')}}">{{__('FAQs')}}</a></li>
                                                    <li><a href="{{route('site.about-us')}}">{{__('About Us')}}</a></li>
                                                    <li><a href="{{route('site.pricing-plans')}}">{{__('Pricing')}}</a></li>

                                                </ul>
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
                                    <ul class="dropdown-menu" aria-labelledby="languageDropdown" >
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






                                <div class="register">
                                    <ul class="d-flex">
                                        <li><a href="#modalLogin" data-bs-toggle="modal">{{__('Login')}}</a></li>
                                        <li>/</li>
                                        <li><a href="#modalRegister" data-bs-toggle="modal">{{__('Register')}}</a></li>
                                    </ul>
                                </div>
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
                    <div class="nav-logo"><a href="{{route('site.index')}}"><img src="{{asset('/site/images/logo/logo@2x.png')}}" alt="nav-logo" width="174" height="44"></a></div>
                    <div class="bottom-canvas">

                        <div class="login-box flex align-items-center">
                            <a href="#modalLogin" data-bs-toggle="modal">{{__('Login')}}</a>
                            <span>/</span>
                            <a href="#modalRegister" data-bs-toggle="modal">{{__('Register')}}</a>
                        </div>
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
        <!-- End Main Header -->
