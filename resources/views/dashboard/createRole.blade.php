@extends('layouts.app')

@section('title', 'Dashboard - Create Role')

@section('custom-css', 'dashboard/createRole.css')

@section('content')
    <div class="card">
        <div class="card-header">
            Create Role
        </div>
        <div class="card-body">
            <form method="POST" action="{{route('dashboard.role.store')}}" id="createRoleForm">
                <div class="mb-3">
                    <label for="roleName" class="form-label">Role Name<span id="requiredField">*</span></label>
                    <input type="text" class="form-control" id="roleName" name="name">
                </div>
                <div class="mb-3">
                    <label>Select Permissions<span id="requiredField">*</span></label><br><br>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Permission Groups</th>
                                <th>Permissions</th>
                            </tr>
                        </thead>
                        <tbody id="permissionsTable">
                            <!-- Permissions will be loaded here dynamically -->
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

@endsection

@section('custom-js', 'dashboard/createRole.js')
