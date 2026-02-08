@extends('site.layouts.app')
@section('style')
<style>
    .auth-page-card {
        background: #fff;
        border-radius: 8px;
        box-shadow: 0 2px 15px rgba(0,0,0,0.08);
        border: 1px solid #eee;
    }
    .auth-page-card .card-body {
        padding: 2rem;
    }
    .auth-page-card .form-control {
        padding: 12px 15px;
        border-radius: 6px;
    }
    .auth-page-card .btn-primary {
        padding: 12px 24px;
        border-radius: 6px;
    }
    .login-social-btns a {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        padding: 10px 20px;
        margin: 5px;
        border-radius: 6px;
        text-decoration: none;
        color: #333;
        border: 1px solid #ddd;
        transition: all 0.2s;
    }
    .login-social-btns a:hover {
        background: #f8f9fa;
        border-color: #ccc;
    }
    .login-social-btns a img {
        width: 20px;
        height: 20px;
    }
</style>
@endsection
@section('content')
    <div class="container">
        <div class="row justify-content-center py-5">
            <div class="col-xl-6 col-lg-8">
                <div class="card auth-page-card border-0 shadow-sm">
                    <div class="card-body">
                        <h3 class="mb-2">{{ __('Login to your account') }}</h3>
                        <p class="text-muted mb-4">{{ __('Your personal data will be used to support your experience throughout this website, to manage access to your account.') }}</p>

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="email" class="form-label">{{ __('Email or Username') }}</label>
                                <input type="text" class="form-control @error('email') is-invalid @enderror" id="email" name="email"
                                       placeholder="{{ __('Enter your email or username') }}" value="{{ old('email') }}" required autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="mb-3">
                                <label for="password" class="form-label">{{ __('Password') }}</label>
                                <input type="password" class="form-control @error('password') is-invalid @enderror" id="password" name="password"
                                       placeholder="{{ __('Password') }}" required>
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="row mb-3">
                                <div class="col-6">
                                    <div class="form-check">
                                        <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">{{ __('Remember me') }}</label>
                                    </div>
                                </div>
                                <div class="col-6 text-end">
                                    <a href="{{ route('password.request') }}" class="text-decoration-none">{{ __('Forgot password?') }}</a>
                                </div>
                            </div>
                            <div class="d-grid mb-3">
                                <button type="submit" class="btn btn-primary">{{ __('Login') }}</button>
                            </div>
                            <div class="text-center mb-4">
                                {{ __("Don't have an account?") }} <a href="{{ route('register') }}" class="text-decoration-none fw-semibold">{{ __('Register now') }}</a>
                            </div>
                            <div class="border-top pt-3">
                                <p class="text-muted small text-center mb-3">{{ __('or sign up with') }}</p>
                                <div class="login-social-btns d-flex justify-content-center flex-wrap">
                                    <a href="{{ route('site.auth.social', 'google') }}">
                                        <img src="{{ asset('site/images/logo/google.jpg') }}" alt="Google">
                                        {{ __('Continue with Google') }}
                                    </a>
                                    <a href="{{ route('site.auth.social', 'twitter') }}">
                                        <img src="{{ asset('site/images/logo/tw.jpg') }}" alt="Twitter">
                                        {{ __('Continue with Twitter') }}
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
@endsection
