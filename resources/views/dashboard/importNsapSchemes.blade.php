@extends('layouts.app')

@section('title', 'Dashboard - Import NSAP Schemes')

@section('custom-css', 'dashboard/importNsapSchemes.css')

@section('content')
    <div class="card">
        <div class="card-header">
            Import NSAP Schemes
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('dashboard.import.nsapScheme.store') }}" id="importNsapSchemeDataForm">
                @csrf
                <div class="mb-3">
                    <label for="excelFile" class="form-label">Select Excel file<span id="requiredField">*</span></label>
                    <input type="file" class="form-control" id="excelFile" name="excelFile" accept=".xls,.xlsx,.csv">
                </div>
                <div class="mb-3" id="alert">
                    <!-- Here show all removed records with reasons and point wise -->
                </div>
                <div class="mb-3" >
                    <table id="dataTable" class="table table-striped table-bordered">
                        <thead id="tableHeader">
                        </thead>
                        <tbody id="tableBody">
                        </tbody>
                    </table>
                </div>
                <button type="submit" class="btn btn-primary" id="submitBtn" disabled>Submit</button>
            </form>
        </div>

        <!-- Edit Modal -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit NSAP Scheme Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body" id="editModalBody">
                       <form  method="POST" id="updateExcelDataForm">
                       @csrf
                       <div id="dynamicFormContent"></div>
                        </form>
                    </div>
                    
                </div>
            </div>
        </div>
    @endsection

@section('custom-js', 'dashboard/importNsapSchemes.js')
