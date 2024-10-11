@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Employee Log Data</h1>

    <div class="table-responsive">
        <table id="employeeLogTable" class="table table-bordered">
            <thead>
                <tr>
                    <th>Employee Code</th>
                    <th>First Name</th>
                    <th>Department</th>
                    <th>Below 5mins Late</th>
                    <th>Above 5mins Late</th>
                    <th>Below 5mins Signout</th>
                    <th>Above 5mins Signout</th>
                    <th>Attendance</th>
                    <th>Clock In</th>
                    <th>Clock Out</th>
                    <th>UKK</th>
                </tr>
            </thead>
            <tbody id="employee-log-list">
                @foreach ($employeeLogs as $log)
                    <tr>
                        <td>{{ $log->emp_code }}</td>
                        <td>{{ $log->first_name }}</td>
                        <td>{{ $log->department }}</td>
                        <td>{{ $log->below5mins_late }}</td>
                        <td>{{ $log->above5mins_late }}</td>
                        <td>{{ $log->below5mins_signout }}</td>
                        <td>{{ $log->above5mins_signout }}</td>
                        <td>{{ $log->attendance }}</td>
                        <td>{{ $log->clock_in }}</td>
                        <td>{{ $log->clock_out }}</td>
                        <td>{{ $log->ukk }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <div id="loading" class="text-center" style="display: none;">
            <p>Loading more logs...</p>
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

    function loadMoreLogs() {
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
            if (data.employeeLogs && data.employeeLogs.length > 0) {
                data.employeeLogs.forEach(log => {
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td>${log.emp_code}</td>
                        <td>${log.first_name}</td>
                        <td>${log.department}</td>
                        <td>${log.below5mins_late}</td>
                        <td>${log.above5mins_late}</td>
                        <td>${log.below5mins_signout}</td>
                        <td>${log.above5mins_signout}</td>
                        <td>${log.attendance}</td>
                        <td>${log.clock_in}</td>
                        <td>${log.clock_out}</td>
                        <td>${log.ukk}</td>
                    `;
                    document.getElementById('employee-log-list').appendChild(row);
                });

                page++; // Increment the page number for the next fetch
                hasMoreData = !!data.next; // Check if more data is available
            } else {
                hasMoreData = false; // No more data to load
            }

            loading = false;
            document.getElementById('loading').style.display = 'none';
        })
        .catch(() => {
            loading = false;
            document.getElementById('loading').style.display = 'none';
        });
    }

    // Detect when the user scrolls near the bottom of the page
    window.addEventListener('scroll', function() {
        if ((window.innerHeight + window.scrollY) >= document.body.offsetHeight - 500) {
            loadMoreLogs();
        }
    });
</script>
@endsection
