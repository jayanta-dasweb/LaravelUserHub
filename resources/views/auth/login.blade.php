@extends('layouts.app')

@section('title', 'Login')

@section('custom-css', 'auth/login.css')

@section('content')
    <div class="container-fluid d-flex align-items-center justify-content-center vw-100 vh-100 bg-info-subtle bg-gradient">
        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class=" d-flex align-items-center justify-content-center w-100 pb-3 " id="logo">
                    <i class="fa-brands fa-pied-piper fs-1 text-info"></i>
                </div>
                <form method="POST" action="{{route('auth.login.submit')}}" id="loginForm">
                    @csrf
                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" class="form-control" id="email" autocomplete="email">
                    </div>
                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control" id="password" aria-label="password"
                                aria-describedby="password-hide-and-show-addon" autocomplete="password">
                            <span class="input-group-text" id="password-hide-and-show-addon">
                                <i class="fa-solid fa-eye" id="passwordEyeIcon"></i>
                            </span>
                        </div>
                    </div>
                    <div class="d-grid">
                        <button type="submit" class="btn btn-info text-white">Login <i
                                class="fa-solid fa-right-to-bracket"></i></button>
                    </div>
                </form>
                <a href="{{route('auth.register')}}" id="link">Not Register Yet?</a>
            </div>
        </div>
    </div>
@endsection

@section('custom-js', 'auth/login.js')
