@extends('layouts.app')

@section('title', 'Register')

@section('content')
    <form method="POST" action="{{ route('register') }}">
        @csrf
        <!-- Registration form fields -->
    </form>
@endsection
