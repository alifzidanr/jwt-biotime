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
                        <tbody id="employee-list">
                            @if (!empty($employees) && count($employees) > 0)
                                @foreach ($employees as $employee)
                                    <tr>
                                        <td>{{ $employee['id'] }}</td>
                                        <td>{{ $employee['emp_code'] }}</td>
                                        <td>{{ $employee['first_name'] ?? 'N/A' }}</td>
                                        <td>{{ $employee['last_name'] ?? 'N/A' }}</td>
                                        <td>{{ $employee['department']['dept_name'] ?? 'N/A' }}</td>
                                        <td>{{ $employee['hire_date'] }}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="6" class="text-center">No employee data available.</td>
                                </tr>
                            @endif
                        </tbody>
                    </table>
                    <div id="loading" class="text-center" style="display: none;">
                        <p>Loading more employees...</p>
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

        function loadMoreEmployees() {
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
                    data.data.forEach(employee => {
                        const row = document.createElement('tr');
                        row.innerHTML = `
                            <td>${employee.id}</td>
                            <td>${employee.emp_code}</td>
                            <td>${employee.first_name ?? 'N/A'}</td>
                            <td>${employee.last_name ?? 'N/A'}</td>
                            <td>${employee.department ? employee.department.dept_name : 'N/A'}</td>
                            <td>${employee.hire_date}</td>
                        `;
                        document.getElementById('employee-list').appendChild(row);
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
                loadMoreEmployees();
            }
        });
    </script>
@endsection
