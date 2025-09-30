@extends('client.layouts.master')

@section('content')
    <div class="auth-container mt-5">
        <div class="auth-card">
            <div class="row g-0">
                <div class="col-lg-5 d-none d-lg-block">
                    <div class="auth-image">
                        <div>
                            <h2>Join Our Family</h2>
                            <p>Create an account to enjoy faster checkout, track your orders, and receive exclusive
                                offers.</p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-7">
                    <div class="auth-content">
                        <div class="auth-title">
                            <h2>Create Account</h2>
                            <p>Fill in your details to register</p>
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

                        <form method="POST" action="{{ route('register.post') }}">
                            @csrf

                            <div class="mb-3">
                                <input type="text" class="form-control @error('name') is-invalid @enderror"
                                       name="name" placeholder="Name" value="{{ old('name') }}" >
                                @error('name')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="email" class="form-control @error('email') is-invalid @enderror"
                                       name="email" placeholder="Email Address" value="{{ old('email') }}" >
                                @error('email')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control @error('password') is-invalid @enderror"
                                       name="password" placeholder="Password" >
                                @error('password')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <input type="password" class="form-control @error('password_confirmation') is-invalid @enderror"
                                       name="password_confirmation" placeholder="Confirm Password" >
                                @error('password_confirmation')
                                    <div class="invalid-feedback">
                                        {{ $message }}
                                    </div>
                                @enderror
                            </div>

                            <button type="submit" class="btn btn-spice">Register</button>

                            <div class="auth-footer">
                                <p>Already have an account? <a href="{{ route('login') }}">Login here</a></p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
