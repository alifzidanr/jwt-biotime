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
                        <tbody id="transaction-list">
                            @if (!empty($transactions) && count($transactions) > 0)
                                @foreach ($transactions as $transaction)
                                    <tr>
                                        <td>{{ $transaction['id'] }}</td>
                                        <td>{{ $transaction['emp_code'] }}</td>
                                        <td>{{ $transaction['first_name'] ?? 'N/A' }}</td>
                                        <td>{{ $transaction['last_name'] ?? 'N/A' }}</td>
                                        <td>{{ $transaction['department'] ?? 'N/A' }}</td>
                                        <td>{{ $transaction['position'] ?? 'N/A' }}</td>
                                        <td>{{ $transaction['punch_time'] }}</td>
                                        <td>{{ $transaction['punch_state_display'] }}</td>
                                        <td>{{ $transaction['verify_type_display'] }}</td>
                                        <td>{{ $transaction['terminal_sn'] }}</td>
                                        <td>{{ $transaction['upload_time'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="11" class="text-center">No transaction data available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div id="loading" class="text-center" style="display: none;">
                        <p>Loading more transactions...</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        let page = {{ $page }};
        const pageSize = {{ $page_size }};
        let loading = false;
        let hasMoreData = {{ $next ? 'true' : 'false' }};

        function loadMoreTransactions() {
            if (loading || !hasMoreData) return;

            loading = true;
            document.getElementById('loading').style.display = 'block';

            fetch(`?page=${page + 1}&page_size=${pageSize}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.data && data.data.length > 0) {
                    data.data.forEach(transaction => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${transaction.id}</td>
                            <td>${transaction.emp_code}</td>
                            <td>${transaction.first_name ?? 'N/A'}</td>
                            <td>${transaction.last_name ?? 'N/A'}</td>
                            <td>${transaction.department ?? 'N/A'}</td>
                            <td>${transaction.position ?? 'N/A'}</td>
                            <td>${transaction.punch_time}</td>
                            <td>${transaction.punch_state_display}</td>
                            <td>${transaction.verify_type_display}</td>
                            <td>${transaction.terminal_sn}</td>
                            <td>${transaction.upload_time}</td>
                        `;
                        document.getElementById('transaction-list').appendChild(row);
                    });

                    page++; // Increment page number for next fetch
                    hasMoreData = !!data.next; // Check if there's more data to load
                } else {
                    hasMoreData = false; // Stop loading if no more data
                }

                loading = false;
                document.getElementById('loading').style.display = 'none';
            })
            .catch(() => {
                loading = false;
                document.getElementById('loading').style.display = 'none';
            });
        }

        // Detect when user scrolls near the bottom of the page
        window.addEventListener('scroll', function() {
            if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
                loadMoreTransactions();
            }
        });
    </script>
@endsection
