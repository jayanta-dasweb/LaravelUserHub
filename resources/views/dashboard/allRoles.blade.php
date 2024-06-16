@extends('layouts.app')

@section('title', 'Dashboard - All Roles')

@section('custom-css', 'dashboard/allRoles.css')

@section('content')
    <div class="card">
        <div class="card-header">
            All Roles
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Date</th>
                        @if (Auth::user()->can('edit role'))
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($roles as $role)
                        <tr>
                            <td>{{ $role->name }}</td>
                            <td>
                                @foreach ($role->permissions->groupBy(function ($item) {
                                    if (str_contains($item->name, 'new user') || str_contains($item->name, 'assign role')) {
                                        return 'New User';
                                    } elseif (str_contains($item->name, 'user') && !str_contains($item->name, 'bulk data')) {
                                        return 'User Management';
                                    } elseif (str_contains($item->name, 'role')) {
                                        return 'Role Management';
                                    } elseif (str_contains($item->name, 'users bulk data')) {
                                        return 'Import Bulk Users Data';
                                    }elseif (str_contains($item->name, 'NSAP')) {
                                        return 'NSAP Scheme Management';
                                    }
                                    return ucfirst(explode(' ', $item->name)[1]);
                                }) as $group => $permissions)
                                    <strong>{{ $group }}:</strong>
                                    {{ $permissions->pluck('name')->map(function ($perm) {
                                        $parts = explode(' ', $perm);
                                        if (strtolower($perm) === 'assign role') {
                                            return 'Assign Role';
                                        } elseif (strtolower($perm) === 'create users bulk data') {
                                            return 'Create';
                                        }
                                        return ucfirst($parts[0]);
                                    })->join(', ') }}<br>
                                @endforeach
                            </td>
                            <td>{{ $role->created_at->format('d M Y, h:i A') }}</td>
                            @if (Auth::user()->can('edit role'))
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        @can('edit role')
                                            <button type="button" class="btn btn-sm btn-warning edit-role"
                                                data-id="{{ $role->id }}">
                                                Edit
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
                        <th>Role Name</th>
                        <th>Permissions</th>
                        <th>Date</th>
                        @if (Auth::user()->can('edit role'))
                            <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Edit Role Data BS5 Modal -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDataModalLabel">Edit Role</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" id="editRoleForm">
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
        </div>
    </div>
@endsection

@section('custom-js', 'dashboard/allRoles.js')
