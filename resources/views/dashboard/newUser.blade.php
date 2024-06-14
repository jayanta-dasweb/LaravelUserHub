@extends('layouts.app')

@section('title', 'Dashboard - New Users')

@section('custom-css', 'dashboard/newUsers.css')

@section('content')
    <div class="card">
        <div class="card-header">
            All New Users
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        @if (Auth::user()->can('edit new user') || Auth::user()->can('delete new user') || Auth::user()->can('assign role'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->created_at->format('d M Y, h:i A') }}</td>
                            @if (Auth::user()->can('edit new user') || Auth::user()->can('delete new user') || Auth::user()->can('assign role'))
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        @can('edit new user')
                                            <button type="button" class="btn btn-sm btn-warning edit-user"
                                                data-id="{{ $user->id }}">
                                                Edit
                                            </button>
                                        @endcan

                                        @can('delete new user')
                                            <button type="button" class="btn btn-sm btn-danger delete-user"
                                                data-id="{{ $user->id }}">
                                                Delete
                                            </button>
                                        @endcan

                                        @can('assign role')
                                            <button type="button" class="btn btn-sm btn-success assign-role"
                                                data-id="{{ $user->id }}">
                                                Assign Role
                                            </button>
                                        @endcan

                                    </div>
                                </td>
                            @endif
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Date</th>
                        @if (Auth::user()->can('edit new user') || Auth::user()->can('delete new user') || Auth::user()->can('assign role'))
                            <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Edit User Data BS5 Modal -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDataModalLabel">Edit User</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('dashboard.user.new.edit', 0) }}" id="editDataForm">
                        @csrf
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name<span id="requiredField">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email<span id="requiredField">*</span></label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Role assign modal -->
    <div class="modal fade" id="roleAssignModal" tabindex="-1" aria-labelledby="roleAssignModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="roleAssignModalLabel">Assign Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="" id="assignRoleForm">
                        @csrf
                        <div class="mb-3">
                            <label for="roleSelect" class="form-label">Select Role<span
                                    id="requiredField">*</span></label><br>
                            <select class="form-select w-100" id="roleSelect" name="role">

                            </select>
                            <table class="table table-bordered mt-3">
                                <thead>
                                    <tr>
                                        <th>Permission Groups</th>
                                        <th>Permissions</th>
                                    </tr>
                                </thead>
                                <tbody id="permissionsTableForRoleSelect">
                                    <!-- Permissions will be loaded here dynamically -->
                                </tbody>
                            </table>
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection


@section('custom-js', 'dashboard/newUsers.js')
