@extends('site.layouts.app')
@section('style')
@endsection
@section('content')
    <div class="container">
        <div class="container">
            <div class="row justify-content-center py-5">
                <div class="col-xl-6 col-lg-8">
                    <div class="card bg-body-tertiary border-0 auth-card">

                        <div class="card-header bg-body-tertiary border-0 p-5 pb-0">
                            <div class="d-flex flex-column flex-md-row align-items-start gap-3">
                                <div class="bg-white p-3 rounded">
                                    <svg class="icon text-primary svg-icon-ti-ti-lock" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                        <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                        <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path>
                                        <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
                                        <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
                                    </svg>                                </div>
                                <div>
                                    <h3 class="fs-4 mb-1">{{__('Login to your account')}}</h3>
                                    <p class="text-muted">{{__('Your personal data will be used to support your experience throughout this website, to manage access to your account.')}}</p>
                                </div>
                            </div>
                        </div>
                        <div class="card-body p-5 pt-3">
                            <form method="POST" action="https://homzen.botble.com/login" accept-charset="UTF-8" id="botble-real-estate-forms-fronts-auth-login-form" class="js-base-form dirty-check" icon="ti ti-lock" heading="Login to your account" description="Your personal data will be used to support your experience throughout this website, to manage access to your account." novalidate="novalidate"><input name="_token" type="hidden" value="UmhfFxlISBhpSYwj3xUlN3YshMMIzrlUSJHaWzyI">







                                <div class="mb-3 position-relative">

                                    <label for="email" class="form-label">{{__('Email')}}</label>

                                    <div class="position-relative"><span class="auth-input-icon input-group-text"><svg class="icon  svg-icon-ti-ti-mail" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
  <path d="M3 7a2 2 0 0 1 2 -2h14a2 2 0 0 1 2 2v10a2 2 0 0 1 -2 2h-14a2 2 0 0 1 -2 -2v-10z"></path>
  <path d="M3 7l9 6l9 -6"></path>
</svg></span>

                                        <input class="form-control ps-5" data-counter="60" placeholder="Email address" name="email" type="email" id="email">

                                    </div>



                                </div>



                                <div class="mb-3 position-relative">

                                    <label for="password" class="form-label">{{__('Password')}}</label>

                                    <div class="position-relative"><span class="auth-input-icon input-group-text"><svg class="icon  svg-icon-ti-ti-lock" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
  <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
  <path d="M5 13a2 2 0 0 1 2 -2h10a2 2 0 0 1 2 2v6a2 2 0 0 1 -2 2h-10a2 2 0 0 1 -2 -2v-6z"></path>
  <path d="M11 16a1 1 0 1 0 2 0a1 1 0 0 0 -2 0"></path>
  <path d="M8 11v-4a4 4 0 1 1 8 0v4"></path>
</svg></span>

                                        <input type="password" name="password" value="" class="form-control ps-5" data-counter="250" placeholder="Password">

                                    </div>



                                </div>



                                <div class="row g-0 mb-3">







                                    <div class="col-6">



                                        <input type="hidden" name="remember" value="0">

                                        <label class="form-check">
                                            <input type="checkbox" id="remember_dc3c99bb4e249a95567a2bf7d5f327ed" name="remember" class="form-check-input" value="1">

                                            <span class="form-check-label">
            {{__('Remember me')}}
        </span>

                                        </label>




                                    </div>



                                    <div class="col-6 text-end">



                                        <a href="https://homzen.botble.com/password/request" class="text-decoration-underline">Forgot password?</a>




                                    </div>



                                </div>







                                <div class="d-grid">









                                    <button class="btn btn-primary btn-auth-submit" type="submit">{{__('Login')}} <svg class="icon  svg-icon-ti-ti-arrow-narrow-right" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                            <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                            <path d="M5 12l14 0"></path>
                                            <path d="M15 16l4 -4"></path>
                                            <path d="M15 8l4 4"></path>
                                        </svg></button>



                                </div>







                                <div class="mt-3 text-center">Don't have an account? <a href="https://homzen.botble.com/register" class="text-decoration-underline">Register now</a></div>







                                <div class="login-options">
                                    <div class="login-options-title">
                                        <p>Login with social networks</p>
                                    </div>

                                    <ul class="social-icons social-login-lg">

                                        <li>
                                            <a class="facebook" data-bs-toggle="tooltip" data-bs-title="Sign in with Facebook" title="Sign in with Facebook" href="https://homzen.botble.com/auth/facebook">

                                                <svg class="icon  svg-icon-ti-ti-brand-facebook" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M7 10v4h3v7h4v-7h3l1 -4h-4v-2a1 1 0 0 1 1 -1h3v-4h-3a5 5 0 0 0 -5 5v2h-3"></path>
                                                </svg>        <span>Sign in with Facebook</span>
                                            </a>
                                        </li>


                                        <li>
                                            <a class="google" data-bs-toggle="tooltip" data-bs-title="Sign in with Google" title="Sign in with Google" href="https://homzen.botble.com/auth/google">

                                                <svg class="icon  svg-icon-ti-ti-brand-google" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M20.945 11a9 9 0 1 1 -3.284 -5.997l-2.655 2.392a5.5 5.5 0 1 0 2.119 6.605h-4.125v-3h7.945z"></path>
                                                </svg>        <span>Sign in with Google</span>
                                            </a>
                                        </li>


                                        <li>
                                            <a class="github" data-bs-toggle="tooltip" data-bs-title="Sign in with GitHub" title="Sign in with GitHub" href="https://homzen.botble.com/auth/github">

                                                <svg class="icon  svg-icon-ti-ti-brand-github" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M9 19c-4.3 1.4 -4.3 -2.5 -6 -3m12 5v-3.5c0 -1 .1 -1.4 -.5 -2c2.8 -.3 5.5 -1.4 5.5 -6a4.6 4.6 0 0 0 -1.3 -3.2a4.2 4.2 0 0 0 -.1 -3.2s-1.1 -.3 -3.5 1.3a12.3 12.3 0 0 0 -6.2 0c-2.4 -1.6 -3.5 -1.3 -3.5 -1.3a4.2 4.2 0 0 0 -.1 3.2a4.6 4.6 0 0 0 -1.3 3.2c0 4.6 2.7 5.7 5.5 6c-.6 .6 -.6 1.2 -.5 2v3.5"></path>
                                                </svg>        <span>Sign in with GitHub</span>
                                            </a>
                                        </li>


                                        <li>
                                            <a class="linkedin" data-bs-toggle="tooltip" data-bs-title="Sign in with Linkedin" title="Sign in with Linkedin" href="https://homzen.botble.com/auth/linkedin">

                                                <svg class="icon  svg-icon-ti-ti-brand-linkedin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                                    <path d="M8 11l0 5"></path>
                                                    <path d="M8 8l0 .01"></path>
                                                    <path d="M12 16l0 -5"></path>
                                                    <path d="M16 16v-3a2 2 0 0 0 -4 0"></path>
                                                </svg>        <span>Sign in with Linkedin</span>
                                            </a>
                                        </li>


                                        <li>
                                            <a class="linkedin-openid" data-bs-toggle="tooltip" data-bs-title="Sign in with Linkedin OpenID Connect" title="Sign in with Linkedin OpenID Connect" href="https://homzen.botble.com/auth/linkedin-openid">

                                                <svg class="icon  svg-icon-ti-ti-brand-linkedin" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                                                    <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                                                    <path d="M4 4m0 2a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v12a2 2 0 0 1 -2 2h-12a2 2 0 0 1 -2 -2z"></path>
                                                    <path d="M8 11l0 5"></path>
                                                    <path d="M8 8l0 .01"></path>
                                                    <path d="M12 16l0 -5"></path>
                                                    <path d="M16 16v-3a2 2 0 0 0 -4 0"></path>
                                                </svg>        <span>Sign in with Linkedin OpenID Connect</span>
                                            </a>
                                        </li>

                                    </ul>
                                </div>











                            </form>

                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>
@endsection
@section('scripts')
@endsection
