<!-- footer -->
<footer class="footer">
    <div class="top-footer">
        <div class="container">
            <div class="content-footer-top">
                <div class="footer-logo">
                    <img src="{{asset($data_settings['secondary_logo'])}}" alt="logo-footer" >
                </div>
                <div class="wd-social">
                    <span>{{__('Follow Us')}}:</span>
                    <ul class="list-social d-flex align-items-center">
                        <li><a href="{{$data_settings['facebook']}}" class="box-icon w-40 social"><i class="icon icon-facebook"></i></a></li>
                        <li><a href="{{$data_settings['linkedin']}}" class="box-icon w-40 social"><i class="icon icon-linkedin"></i></a></li>
                        <li><a href="{{$data_settings['twitter']}}" class="box-icon w-40 social">
                                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <g clip-path="url(#clip0_748_6323)">
                                        <path d="M9.4893 6.77491L15.3176 0H13.9365L8.87577 5.88256L4.8338 0H0.171875L6.28412 8.89547L0.171875 16H1.55307L6.8973 9.78782L11.1659 16H15.8278L9.48896 6.77491H9.4893ZM7.59756 8.97384L6.97826 8.08805L2.05073 1.03974H4.17217L8.14874 6.72795L8.76804 7.61374L13.9371 15.0075H11.8157L7.59756 8.97418V8.97384Z" fill="white"/>
                                    </g>
                                    <defs>
                                        <clipPath id="clip0_748_6323">
                                            <rect width="16" height="16" fill="white"/>
                                        </clipPath>
                                    </defs>
                                </svg>
                            </a></li>

                        <li><a href="{{$data_settings['instagram']}}" class="box-icon w-40 social"><i class="icon icon-instagram"></i></a></li>
                        <li><a href="{{$data_settings['youtube']}}" class="box-icon w-40 social"><i class="icon icon-youtube"></i></a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    <div class="inner-footer">
        <div class="container">
            <div class="row">
                <div class="col-lg-4 col-md-6">
                    <div class="footer-cl-1">

                        <p class="text-variant-2">{{__('Specializes in providing high-class tours for those in need. Contact Us')}}</p>
                        <ul class="mt-12">
                            <li class="mt-12 d-flex align-items-center gap-8">
                                <i class="icon icon-mapPinLine fs-20 text-variant-2"></i>
                                <p class="text-white">{{$data_settings['address']}}</p>
                            </li>
                            <li class="mt-12 d-flex align-items-center gap-8">
                                <i class="icon icon-phone2 fs-20 text-variant-2"></i>
                                <a href="tel:{{$data_settings['phone']}}" class="text-white caption-1">{{$data_settings['phone']}}</a>
                            </li>
                            <li class="mt-12 d-flex align-items-center gap-8">
                                <i class="icon icon-mail fs-20 text-variant-2"></i>
                                <p class="text-white">{{$data_settings['email']}}</p>
                            </li>
                        </ul>

                    </div>
                </div>
                <div class="col-lg-2 col-md-6 col-6">
                    <div class="footer-cl-2">
                        <div class="fw-7 text-white">{{__('Categories')}}</div>
                        <ul class="mt-10 navigation-menu-footer">
{{--                            <li> <a href="pricing.html" class="caption-1 text-variant-2">{{__('Pricing Plans')}}</a> </li>--}}

                            <li> <a href="{{route('site.services')}}" class="caption-1 text-variant-2">{{__('Our Services')}}</a> </li>

                            <li> <a href="{{route('site.about-us')}}" class="caption-1 text-variant-2">{{__('About Us')}}</a> </li>

                            <li> <a href="{{route('site.contact')}}" class="caption-1 text-variant-2">{{__('Contact Us')}}</a> </li>

                        </ul>
                    </div>
                </div>
                <div class="col-lg-2 col-md-4 col-6">
                    <div class="footer-cl-3">
                        <div class="fw-7 text-white">{{__('Our Company')}}</div>
                        <ul class="mt-10 navigation-menu-footer">
                            <li> <a href="{{ route('site.properties', ['tab' => 'sale']) }}" class="caption-1 text-variant-2">{{__('Property For Sale')}}</a> </li>

                            <li> <a href="{{ route('site.properties', ['tab' => 'rent']) }}" class="caption-1 text-variant-2">{{__('Property For Rent')}}</a> </li>

                            <li> <a href="{{route('site.index')}}#agents" class="caption-1 text-variant-2">{{__('Our Agents')}}</a> </li>
                        </ul>
                    </div>
                </div>
                <div class="col-lg-4 col-md-6">
                    <div class="footer-cl-4">
                        <div class="fw-7 text-white">
                            {{__('News letter')}}
                        </div>
                        <p class="mt-12 text-variant-2">{{__('Your Weekly/Monthly Dose of Knowledge and Inspiration')}}</p>
                        <form class="mt-12" id="subscribe-form" method="post" action="javascript:void(0)" accept-charset="utf-8" data-mailchimp="true">
                            <div id="subscribe-content" class="form-group">
                                <span class="icon-left icon-mail"></span>
                                <input type="email" name="email" id="subscribe-email" placeholder="{{__('Your email address')}}"/>
                                <button type="button" id="subscribe-button" class="button-subscribe"><i class="icon icon-send"></i></button>
                            </div>
                            <div id="subscribe-msg"></div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <div class="bottom-footer">
        <div class="container">
            <div class="content-footer-bottom">
                <div class="copyright">©2024 {{config('app.name')}}. {{__('All Rights Reserved')}}.</div>

                <ul class="menu-bottom">
                    <li><a href="{{route('site.services')}}">{{__('Terms Of Services')}}</a> </li>

                    <li><a href="{{route('site.privacy-policy')}}">{{__('Privacy Policy')}}</a> </li>
{{--                    <li><a href="contact.html">{{__('Cookie Policy')}}</a> </li>--}}

                </ul>
            </div>
        </div>
    </div>
</footer>
<!-- end footer -->
</div>
<!-- /#page -->

</div>



<!-- go top -->
<div class="progress-wrap">
    <svg class="progress-circle svg-content" width="100%" height="100%" viewBox="-1 -1 102 102">
        <path d="M50,1 a49,49 0 0,1 0,98 a49,49 0 0,1 0,-98" style="transition: stroke-dashoffset 10ms linear 0s; stroke-dasharray: 307.919, 307.919; stroke-dashoffset: 286.138;"></path>
    </svg>
</div>

<!-- popup login -->
<div class="modal fade" id="modalLogin">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account bg-surface">
                <h3 class="title text-center">{{__('Log In')}}</h3>
                <span class="close-modal icon-close2" data-bs-dismiss="modal"></span>
                <form action="{{ route('login') }}" method="POST">
                    @csrf
                    <fieldset class="box-fieldset">
                        <label for="name">{{__('Your Email')}}<span>*</span>:</label>
                        <input type="text" class="form-contact style-1 @error('email') is-invalid @enderror" name="email" value="user@user.com">
                        @error('email')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="email-username" data-validator="notEmpty">{{ $message }}</div>
                        </div>
                        @enderror
                    </fieldset>
                    <fieldset class="box-fieldset">
                        <label for="pass">{{__('Password')}}<span>*</span>:</label>
                        <div class="box-password">
                            <input type="password" name="password" class="form-contact style-1 password-field @error('password') is-invalid @enderror" placeholder="{{__('Password')}}">
                            <span class="show-pass">
                                    <i class="icon-pass icon-eye"></i>
                                    <i class="icon-pass icon-eye-off"></i>
                                </span>
                        </div>
                        @error('password')
                        <div class="fv-plugins-message-container invalid-feedback">
                            <div data-field="password" data-validator="stringLength">{{ $message }}</div>
                        </div>
                        @enderror
                    </fieldset>
                    <div class="d-flex justify-content-between flex-wrap gap-12">
                        <fieldset class="d-flex align-items-center gap-6">
                            <input type="checkbox" class="tf-checkbox style-2" id="cb1" {{ old('remember') ? 'checked' : '' }}>
                            <label for="cb1" class="caption-1 text-variant-1">{{__('Remember me')}}</label>
                        </fieldset>
                        <a href="#" class="caption-1 text-primary">{{__('Forgot password?')}}</a>
                    </div>
                    <div class="text-variant-1 auth-line">{{__('or sign up with')}}</div>
                    <div class="login-social">

                        <a href="{{ route('site.auth.social','google') }}" class="btn-login-social">
                            <img src="{{asset('/site/images/logo/google.jpg')}}" alt="img">
                            {{__('Continue with Google')}}
                        </a>
                        <a href="{{ route('site.auth.social','twitter') }}" class="btn-login-social">
                            <img src="{{asset('/site/images/logo/tw.jpg')}}" alt="img">
                            {{__('Continue with Twitter')}}
                        </a>
                    </div>
                    <button type="submit" class="tf-btn primary w-100">{{__('Login')}}</button>
                    <div class="mt-12 text-variant-1 text-center noti">{{__('Not registered yet?')}}<a href="#modalRegister" data-bs-toggle="modal" class="text-black fw-5">{{__('Sign Up')}}</a> </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- popup register -->
<div class="modal fade" id="modalRegister">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="flat-account bg-surface">
                <h3 class="title text-center">{{__('Register')}}</h3>
                <span class="close-modal icon-close2" data-bs-dismiss="modal"></span>
                <form   name="register" id="register" method="POST" action="javascript:void(0)">
                    @csrf
                    <fieldset class="box-fieldset">
                        <label for="name">{{__('Name')}}<span>*</span>:</label>
                        <input type="text" name="name" required class="form-contact style-1 @error('name') has-error @enderror"  >
                        @error('name')
                        <label id="name-error" class="error" for="name">{{ $message }}</label>
                        @enderror
                    </fieldset>
                    <fieldset class="box-fieldset">
                        <label for="name">{{__('Email address')}}<span>*</span>:</label>
                        <input type="text" name="email" required class="form-contact style-1 @error('email') has-error @enderror"  >
                        @error('email')
                        <label id="email-error" class="error" for="email">{{ $message }}</label>
                        @enderror
                    </fieldset>
                    <fieldset class="box-fieldset">
                        <label for="password">{{__('Password')}}<span>*</span>:</label>
                        <div class="box-password">
                            <input type="password" name="password" id="password" required class="form-contact style-1 password-field @error('password') has-error @enderror" placeholder="{{__('Password')}}">
                            <span class="show-pass">
                                    <i class="icon-pass icon-eye"></i>
                                    <i class="icon-pass icon-eye-off"></i>
                                </span>
                        </div>
                        @error('password')
                        <label id="password-error" class="error" for="password">{{ $message }}</label>
                        @enderror
                    </fieldset>
                    <fieldset class="box-fieldset">
                        <label for="password_confirmation">{{__('Confirm Password')}}<span>*</span>:</label>
                        <div class="box-password">
                            <input type="password" id="password_confirmation" name="password_confirmation"  class="form-contact style-1 password-field2" placeholder="{{__('Password')}}">
                            <span class="show-pass2">
                                    <i class="icon-pass icon-eye"></i>
                                    <i class="icon-pass icon-eye-off"></i>
                                </span>
                        </div>
                    </fieldset>
                    <fieldset class="d-flex align-items-center gap-6">
                        <input type="checkbox"  name="term_conditions" required class="tf-checkbox style-2" id="cb1">
                        <label for="cb1" class="caption-1 text-variant-1">{{__('I agree to the')}} <a href="{{ route('site.privacy-policy') }}" class="fw-5 text-black">{{__('Terms of User')}}</a></label>
                    </fieldset>

                    <button type="submit" class="tf-btn primary w-100"  id="btn-register">{{__('Register')}}</button>
                    <div class="mt-12 text-variant-1 text-center noti">{{__('Already have an account?')}}<a href="#modalLogin" data-bs-toggle="modal" class="text-black fw-5">{{__('Login Here')}}</a> </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Javascript -->
<script type="text/javascript" src="{{asset('/site/js/bootstrap.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/jquery.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/swiper-bundle.min.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/carousel.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/plugin.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/jquery.nice-select.min.js')}}"></script>
{{--<script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.7.0/nouislider.min.js"></script>--}}

<script type="text/javascript" src="{{asset('/site/js/countto.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/shortcodes.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/chart.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/chart-init.js')}}"></script>
<script type="text/javascript" src="{{asset('/site/js/animation_heading.js')}}"></script>
{{--<script type="text/javascript" src="{{asset('/site/js/main.js')}}"></script>--}}
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>

<!-- Toastr JS -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    window.AppData = {
        currency: @json($data_settings['currency']),
        min_currency: @json($data_settings['min_currency']),
        max_currency: @json($data_settings['max_currency']),
        unit_size: @json($data_settings['unit_size']),
        min_size: @json($data_settings['min_size']),
        max_size: @json($data_settings['max_size']),
    };
    console.log(window.AppData)
</script>
<script type="text/javascript" src="{{asset('/site/js/rangle-slider.js')}}"></script>

<script>
    $(function() {
        // Initialize form validation on the registration form.
        // It has the name attribute "registration"
        jQuery.validator.setDefaults({
            // where to display the error relative to the element
            errorPlacement: function(error, element) {
                console.log(element)
                if (element.parent().hasClass('input-group')) {
                    error.insertAfter(element.parent());
                } else if (element.parent().hasClass('form-group')) {
                    error.insertAfter(element.parent());
                } else if (element.parent()) {
                    error.insertAfter(element.parent().parent());
                } else {
                    error.insertAfter(element);
                }
            }
        });
        $("form[name='register']").validate({
            // Specify validation rules
            rules: {
                // The key name on the left side is the name attribute
                // of an input field. Validation rules are defined
                // on the right side
                // us_zip_code: "required",


                email: "required",

                // marketing: "required",
                term_conditions: "required",

                password: {
                    required: true,
                    // strong_password: true,
                    minlength: 8
                },
                password_confirmation: {
                    required: true,
                    equalTo: "#password"
                },
            },
            highlight: function(element) {
                // console.log($(element).parent());
                if ($(element).parent().hasClass('input-group')) {
                    $(element).parent().addClass('has-error');
                } else if ($(element).parent().hasClass('form-group')) {
                    $(element).parent().addClass('has-error');
                } else {
                    $(element).parent().addClass('has-error');
                }

            },
            unhighlight: function(element) {

                if ($(element).parent().hasClass('input-group')) {
                    $(element).parent().removeClass('has-error');
                } else if ($(element).parent().hasClass('form-group')) {
                    console.log($(element).parent())
                    $(element).parent().removeClass('has-error');
                } else {
                    $(element).parents().removeClass('has-error');
                }

            },
            // Specify validation error messages
            messages: {
                // us_zip_code: "Please enter your us_zip_code",
                name: "{{ __('Please enter your name') }}",
                password: {
                    required: "{{ __('Please enter your Password') }}",
                    strong_password: "{{ __('The password must contain upper and lower case letters, numbers and symbols!') }}",

                },

                email: "{{ __('Please enter a valid email address') }}",
                country: "{{ __('Please enter your Prefix Country') }}",
                first_name: {
                    lettersonly: "Please Enter Letters only ",
                }
            },

            // Make sure the form is submitted to the destination defined
            // in the "action" attribute of the form when valid
            submitHandler: function(form) {
                form.submit();
            }
        });



        jQuery.validator.addMethod("us_code", function(value, element) {
            return /(^\d{5}$)|(^\d{5}-\d{4}$)/.test(value);
        }, "Please specify a valid US zip code.");
        jQuery.validator.addMethod("lettersonly", function(value, element) {
            return this.optional(element) || /^[a-zA-Z]+$/i.test(value);
        }, "Letters only please");
        jQuery.validator.addMethod("strong_password", function(value, element) {
                let password = value;
                if (!(/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*[@#$%&])(.{8,20}$)/.test(password))) {
                    return false;
                }
                return true;
            },
            function(value, element) {
                let password = $(element).val();
                if (!(/^(.{8,20}$)/.test(password))) {
                    return '{{ __('Password must be between 8 to 20 characters long.') }}';
                } else if (!(/^(?=.*[A-Z])/.test(password))) {
                    return '{{ __('Password must contain at least one uppercase.') }}';
                } else if (!(/^(?=.*[a-z])/.test(password))) {
                    return '{{ __('Password must contain at least one lowercase.') }}';
                } else if (!(/^(?=.*[0-9])/.test(password))) {
                    return '{{ __('Password must contain at least one digit.') }}';
                } else if (!(/^(?=.*[@#$%&])/.test(password))) {
                    return "{{ __('Password must contain special characters from @#$%&.') }}";
                }
                return false;
            });

    });
</script>
<script>
    $(document).ready(function() {
        function myHandel(obj, id) {
            if ('responseJSON' in obj) {
                obj.messages = obj.responseJSON;
            }

            $('input, select, textarea').each(function () {
                var parent = $(this).parents('.fv-row, .input-group').first();

                var name = $(this).attr("name");
                if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                    var error_message = '<div class="custom-error"><p>' + obj.messages[name][0] + '</p></div>';

                    if (parent.length > 0) {
                        parent.append(error_message);
                    } else {
                        $(this).after(error_message);
                    }

                    // Add error class to input
                    $(this).addClass('error');
                }
            });
        }
        $("#register").submit(function() {
            let myform = $('#register');

            if (!myform.valid()) {
                return false
            };
            if (myform.valid()) {

                var postData = new FormData($('form#register')[0]);


                $.ajaxSetup({
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    }

                });

                $.ajax({
                    url: "{{ route('register') }}",
                    type: "POST",
                    data: postData,
                    processData: false,
                    contentType: false,
                    beforeSend() {
                        // $('.u-loading').fadeIn(10);

                    },
                    complete: function() {
                        // $('.u-loading').fadeOut();
                    },

                    success: function(response) {

                        setTimeout(function() {
                            window.location = '{{ route('user.dashboard') }}';
                            // swal.fire("Success!", response.message, "success").then(
                            //     function() {
                            //         {{-- $('#send').html('{{__('Verify')}}'); --}}

                            //     });
                        }, 200);
                    },
                    error: function(data) {
                        $('.custom-error').remove();

                        var response = data.responseJSON;
                        // console.log(data)
                        if (data.status == 422) {
                            if (typeof(response.responseJSON) != "undefined") {
                                myHandel(response);
                                swal.fire("Error!", response.message, "error");

                            }
                        } else {
                            swal.fire("Error!", response.message, "error");
                        }
                    }
                });

            };
        });

    });
</script>
<script>
    // Assuming you have a global variable that indicates if the user is authenticated
    var isAuthenticated = {{ auth()->guard('web')->check() ? 'true' : 'false' }};

    function toggleFavorite(propertyId) {
        if (!isAuthenticated) {
            // Show the login modal if the user is not authenticated
            $('#modalLogin').modal('show');
            return;
        }

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
            url: '{{ route('site.favorites.toggle') }}',
            method: 'POST',
            data: {
                property_id: propertyId,
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                let icon = $('#favorite-icon-' + propertyId);
                if (response.status === 'added') {
                    icon.removeClass('icon-heart').addClass('fa-solid fa-heart');
                    alert('Property added to favorites.');
                } else if (response.status === 'removed') {
                    icon.removeClass('fa-solid fa-heart').addClass('icon-heart');
                    alert('Property removed from favorites.');
                }
            },
            error: function() {
                alert('There was an error. Please try again.');
            },
            complete: function() {
                // Hide and remove the loader
                $('#loader').hide();
                $('#loader').remove();
            }
        });
    }
</script>

<script>
    var data_url_subscribe='http://properties.test/newsletter/subscribe'

    $(document).ready(function() {
        function myHandel(obj, id) {
            if ('responseJSON' in obj)
                obj.messages = obj.responseJSON;

            $('input,select,textarea').each(function () {
                var parent = $(this).closest('.form-group, .input-group');
                var name = $(this).attr("name");
                if (obj.messages && obj.messages[name] && ($(this).attr('type') !== 'hidden')) {
                    var error_message = '<div class="col-md-8 offset-md-3 custom-error"><p style="color: red">' + obj.messages[name][0] + '</p></div>';
                    parent.append(error_message);
                }
            });
        }

        $(document).on("click", "#subscribe-button", function() {
            var form = $('#subscribe-form');

            // Check if form is valid
            if (!form.valid()) {
                return false;
            }

            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var postData = new FormData(form[0]);

            $('#subscribe-button').html('<span class="spinner-border spinner-border-sm align-middle ms-2"></span>' +
                '<span class="ml-25 align-middle"></span>');

            $.ajax({
                url: data_url_subscribe,
                type: "POST",
                data: postData,
                processData: false,
                contentType: false,
                success: function (response) {
                    $('#subscribe-button').html('<button type="button" id="subscribe-button" class="button-subscribe"><i class="icon icon-send"></i></button>');
                    setTimeout(function () {
                        toastr['success'](response.success, {
                            closeButton: true,
                            tapToDismiss: false
                        });
                    }, 200);
                    form[0].reset();
                    $('.custom-error').remove();
                },
                error: function (data) {
                    $('.custom-error').remove();
                    $('#subscribe-button').html('<button type="button" id="subscribe-button" class="button-subscribe"><i class="icon icon-send"></i></button>');
                    var response = data.responseJSON;
                    if (data.status == 422) {
                        if (typeof (response.responseJSON) != "undefined") {
                            myHandel(response);
                            setTimeout(function () {
                                toastr['error'](response.message, {
                                    closeButton: true,
                                    tapToDismiss: false
                                });
                            }, 200);
                        }
                    } else {
                        swal.fire({
                            icon: 'error',
                            title: response.message
                        });
                    }
                }
            });
        });
    });
</script>

  @yield('scripts')
</body>

</html>
