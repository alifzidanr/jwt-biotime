@extends('layouts.app')

@section('content')
    <div class="container-fluid">
        <h1 class="h3 mb-4 text-gray-800">Transaction List</h1>
        <div class="card shadow mb-4">
            <div class="card-header py-3">
                <h6 class="m-0 font-weight-bold text-primary">Transactions Data</h6>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table id="transactions-table" class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Employee Code</th>
                                <th>First Name</th>
                                <th>Last Name</th>
                                <th>Department</th>
                                <th>Position</th>
                                <th>Punch Time</th>
                                <th>Punch State</th>
                                <th>Verify Type</th>
                                <th>Terminal SN</th>
                                <th>Upload Time</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($transactions['data'] as $transaction)
                                <tr>
                                    <td>{{ $transaction['id'] }}</td>
                                    <td>{{ $transaction['emp_code'] }}</td>
                                    <td>{{ $transaction['first_name'] }}</td>
                                    <td>{{ $transaction['last_name'] }}</td>
                                    <td>{{ $transaction['department'] }}</td>
                                    <td>{{ $transaction['position'] }}</td>
                                    <td>{{ $transaction['punch_time'] }}</td>
                                    <td>{{ $transaction['punch_state_display'] }}</td>
                                    <td>{{ $transaction['verify_type_display'] }}</td>
                                    <td>{{ $transaction['terminal_sn'] }}</td>
                                    <td>{{ $transaction['upload_time'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection
