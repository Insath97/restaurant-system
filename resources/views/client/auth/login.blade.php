@extends('client.layouts.master')

@section('content')
    <div class="auth-container mt-5">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-image">
                        <div>
                            <h2>Welcome Back</h2>
                            <p>Login to access your account, track orders, and manage reservations.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-content">
                        <div class="auth-title">
                            <h2>Login to Your Account</h2>
                            <p>Enter your credentials to continue</p>
                        </div>

                        @if ($errors->any())
                            <div class="alert alert-danger">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        @if (session('success'))
                            <div class="alert alert-success">
                                {{ session('success') }}
                            </div>
                        @endif

                        @if (session('status'))
                            <div class="alert alert-info">
                                {{ session('status') }}
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login.post') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" placeholder="Email Address" value="{{ old('email') }}">
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" placeholder="Password">
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between mb-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="rememberCheck" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label" for="rememberCheck">
                                        Remember me
                                    </label>
                                </div>
                                <div>
                                    <a href="{{ route('password.request') }}" class="text-decoration-none" style="color: var(--spice-red);">Forgot password?</a>
                                </div>
                            </div>

                            <button type="submit" class="btn btn-spice">Login</button>

                            <div class="auth-footer">
                                <p>Don't have an account? <a href="{{ route('register') }}">Register here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
