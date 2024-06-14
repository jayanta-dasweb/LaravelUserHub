@extends('layouts.app')

@section('title', 'Dashboard - Users')

@section('custom-css', 'dashboard/users.css')

@section('content')
    <div class="card">
        <div class="card-header">
            All Users
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Role</th>
                        @if (Auth::user()->can('edit user') || Auth::user()->can('delete user') )
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>
                            <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                            @if (Auth::user()->can('edit user') || Auth::user()->can('delete user'))
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        @can('edit user')
                                            <button type="button" class="btn btn-sm btn-warning edit-user"
                                                data-id="{{ $user->id }}">
                                                Edit
                                            </button>
                                        @endcan

                                        @can('delete user')
                                            <button type="button" class="btn btn-sm btn-danger delete-user"
                                                data-id="{{ $user->id }}">
                                                Delete
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
                        <th>Role</th>
                        @if (Auth::user()->can('edit user') || Auth::user()->can('delete user') )
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
                    <form method="POST" action="{{ route('dashboard.user.edit', 0) }}" id="editDataForm">
                        @csrf
                        <div class="mb-3">
                            <label for="editName" class="form-label">Name<span id="requiredField">*</span></label>
                            <input type="text" class="form-control" id="editName" name="name">
                        </div>
                        <div class="mb-3">
                            <label for="editEmail" class="form-label">Email<span id="requiredField">*</span></label>
                            <input type="email" class="form-control" id="editEmail" name="email">
                        </div>
                        <div class="mb-3">
                            <label for="roleSelect" class="form-label">Select Role<span id="requiredField">*</span></label><br>
                            <select class="form-select w-100" id="roleSelect" name="role">
                            </select>
                        </div>
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
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('custom-js', 'dashboard/users.js')
