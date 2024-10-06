@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Employee List</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Employees Data</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="employees-table" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee Code</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Hire Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($employees['data'] as $employee)
                                <tr>
                                    <td>{{ $employee['id'] }}</td>
                                    <td>{{ $employee['emp_code'] }}</td>
                                    <td>{{ $employee['first_name'] }}</td>
                                    <td>{{ $employee['last_name'] }}</td>
                                    <td>{{ $employee['department']['dept_name'] }}</td>
                                    <td>{{ $employee['hire_date'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection


