@extends('layouts.app')

@section('title', 'Dashboard - NSAP Scheme')

@section('custom-css', 'dashboard/nsapScheme.css')

@section('content')
    <div class="card">
        <div class="card-header">
            NSAP Schemes
        </div>
        <div class="card-body">
            <table id="dataTable" class="table table-striped" style="width:100%">
                <thead>
                    <tr>
                        <th>Scheme Code</th>
                        <th>Scheme Name</th>
                        <th>Central/State Scheme</th>
                        <th>Financial Year</th>
                        <th>State Disbursement</th>
                        <th>Central Disbursement</th>
                        <th>Total Disbursement</th>
                        @if (Auth::user()->can('edit NSAP scheme') || Auth::user()->can('delete NSAP scheme') )
                            <th>Action</th>
                        @endif
                    </tr>
                </thead>
                <tbody>
                    @foreach ($nsapSchemes as $scheme)
                        <tr>
                            <td>{{ $scheme->scheme_code }}</td>
                            <td>{{ $scheme->scheme_name }}</td>
                            <td>{{ $scheme->central_state_scheme }}</td>
                            <td>{{ $scheme->fin_year }}</td>
                            <td>&#8377; {{ indian_number_format($scheme->state_disbursement) }}</td>
                            <td>&#8377; {{ indian_number_format($scheme->central_disbursement) }}</td>
                            <td>&#8377; {{ indian_number_format($scheme->total_disbursement) }}</td>
                            @if (Auth::user()->can('edit NSAP scheme') || Auth::user()->can('delete NSAP scheme'))
                                <td>
                                    <div class="btn-group" role="group" aria-label="Basic mixed styles example">
                                        @can('edit NSAP scheme')
                                            <button type="button" class="btn btn-sm btn-warning edit-scheme"
                                                data-id="{{ $scheme->id }}">
                                                Edit
                                            </button>
                                        @endcan

                                        @can('delete NSAP scheme')
                                            <button type="button" class="btn btn-sm btn-danger delete-scheme"
                                                data-id="{{ $scheme->id }}">
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
                        <th>Scheme Code</th>
                        <th>Scheme Name</th>
                        <th>Central/State Scheme</th>
                        <th>Financial Year</th>
                        <th>State Disbursement</th>
                        <th>Central Disbursement</th>
                        <th>Total Disbursement</th>
                        @if (Auth::user()->can('edit NSAP scheme') || Auth::user()->can('delete NSAP scheme') )
                            <th>Action</th>
                        @endif
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    <!-- Edit Scheme Data BS5 Modal -->
    <div class="modal fade" id="editDataModal" tabindex="-1" aria-labelledby="editDataModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="editDataModalLabel">Edit Scheme</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form method="POST" action="{{ route('dashboard.nsapScheme.edit', 0) }}" id="editDataForm">
                        @csrf
                        <div class="mb-3">
                            <label for="editSchemeCode" class="form-label">Scheme Code<span id="requiredField">*</span></label>
                            <input type="text" class="form-control" id="editSchemeCode" name="scheme_code">
                        </div>
                        <div class="mb-3">
                            <label for="editSchemeName" class="form-label">Scheme Name<span id="requiredField">*</span></label>
                            <input type="text" class="form-control" id="editSchemeName" name="scheme_name">
                        </div>
                        <div class="mb-3">
                            <label for="editCentralStateScheme" class="form-label">Central/State Scheme<span id="requiredField">*</span></label>
                            <input type="text" class="form-control" id="editCentralStateScheme" name="central_state_scheme">
                        </div>
                        <div class="mb-3">
                            <label for="editFinYear" class="form-label">Financial Year<span id="requiredField">*</span></label>
                            <input type="text" class="form-control" id="editFinYear" name="fin_year">
                        </div>
                        <div class="mb-3">
                            <label for="editStateDisbursement" class="form-label">State Disbursement<span id="requiredField">*</span></label>
                            <input type="number" class="form-control" id="editStateDisbursement" name="state_disbursement" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="editCentralDisbursement" class="form-label">Central Disbursement<span id="requiredField">*</span></label>
                            <input type="number" class="form-control" id="editCentralDisbursement" name="central_disbursement" step="0.01">
                        </div>
                        <div class="mb-3">
                            <label for="editTotalDisbursement" class="form-label">Total Disbursement<span id="requiredField">*</span></label>
                            <input type="number" class="form-control" id="editTotalDisbursement" name="total_disbursement" step="0.01">
                        </div>
                        <button type="submit" class="btn btn-primary">Update</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('custom-js', 'dashboard/nsapScheme.js')
