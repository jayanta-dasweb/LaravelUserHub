@extends('layouts.app')

@section('title', 'Dashboard - Import Users')

@section('custom-css', 'dashboard/imposrtUsers.css')

@section('content')
    <div class="card">
        <div class="card-header">
            Import Users
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('dashboard.import.users.store')}}" id="importUsersDataForm">
                <div class="mb-3">
                    <label for="excelFile" class="form-label">Select excel file<span id="requiredField">*</span></label>
                    <input type="file" class="form-control" id="excelFile" >
                </div>
                <div class="mb-3">
                   <here i want to display excel data in data table formate >
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

@endsection

@section('custom-js', 'dashboard/imposrtUsers.js')
