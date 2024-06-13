@extends('layouts.app')

@section('title', 'Register')

@section('custom-css', 'auth/register.css')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center vw-100 vh-100 bg-info-subtle bg-gradient">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class=" d-flex align-items-center justify-content-center w-100 pb-3 " id="logo">
                    <i class="fa-brands fa-pied-piper fs-1 text-info"></i>
                </div>
                <form method="POST" action="{{route('auth.register.submit')}}" id="registerForm">
                    @csrf
                    <div class="mb-3">
                        <label for="name" class="form-label">Full Name<span id="requiredField">*</span></label>
                        <input type="text" class="form-control" id="name" name="name" autocomplete="name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Email<span id="requiredField">*</span></label>
                        <input type="email" class="form-control" id="email" name="email" autocomplete="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password<span id="requiredField">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" name="password" aria-label="password"
                                aria-describedby="password-hide-and-show-addon" autocomplete="password">
                            <span class="input-group-text" id="password-hide-and-show-addon">
                                <i class="fa-solid fa-eye" id="passwordEyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">Confirm Password<span id="requiredField">*</span></label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" aria-label="confirmPassword"
                                aria-describedby="confirm-password-hide-and-show-addon" autocomplete="confirmPassword">
                            <span class="input-group-text" id="confirm-password-hide-and-show-addon">
                                <i class="fa-solid fa-eye" id="confirmPasswordEyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-info text-white">Register</button>
                    </div>
                </form>
                <a href="{{route('auth.login')}}" id="link">Already have an account</a>
            </div>
        </div>
    </div>
@endsection

@section('custom-js', 'auth/register.js')
