@extends('docs.layout')

@section('content')
    <h2>Overview</h2>
    <p>Every Volta app comes with a built-in usage dashboard at <a href="/dashboard">/dashboard</a>. It provides real-time analytics on your app's credit consumption, user activity, and revenue.</p>

    <h2>Dashboard features</h2>

    <h3>Overview cards</h3>
    <ul>
        <li><strong>Total Users</strong> — number of unique end-users across all your apps</li>
        <li><strong>Credits Sold</strong> — total credits purchased by your users this month</li>
        <li><strong>API Calls</strong> — total API calls made this month</li>
        <li><strong>Revenue</strong> — total credit purchase revenue this month</li>
    </ul>

    <h3>Interactive charts</h3>
    <p>Each app has a detailed analytics view with interactive Chart.js charts showing:</p>
    <ul>
        <li><strong>API Calls</strong> over time (7d, 30d, 90d)</li>
        <li><strong>Credits Used</strong> over time</li>
        <li><strong>New Users</strong> over time</li>
    </ul>
    <p>Charts support live polling (30-second intervals) for real-time monitoring.</p>

    <h3>User management</h3>
    <p>View all end-users for each app, including:</p>
    <ul>
        <li>External User ID</li>
        <li>Current credit balance</li>
        <li>Total usage logs</li>
        <li>Last activity date</li>
    </ul>

    <h3>Model configuration</h3>
    <p>Add, view, and remove AI models for each app directly from the dashboard. Each model shows its identifier, display name, and per-call credit cost.</p>

    <h2>Accessing the dashboard</h2>
    <p>The dashboard is available at <a href="/dashboard">/dashboard</a> after logging in to your Volta account. Each app has its own detail page with dedicated analytics and settings.</p>

    <h2>API access</h2>
    <p>Dashboard data is also available via the API for building custom dashboards:</p>

    @include('docs.partials._code-block', ['language' => 'bash', 'code' => '# Get chart data for an app
curl -H "X-Volta-Key: your-app-key" \
  "https://volta.test/api/v1/apps/{id}/chart-data?range=7d"'])

    @include('docs.partials._code-block', ['language' => 'json', 'code' => '{
    "labels": ["Apr 3", "Apr 4", "Apr 5", "Apr 6", "Apr 7", "Apr 8", "Apr 9"],
    "datasets": {
        "api_calls": [124, 89, 156, 203, 178, 145, 92],
        "credits_used": [312, 201, 389, 498, 423, 356, 215],
        "new_users": [3, 1, 5, 2, 4, 1, 3]
    },
    "totals": {
        "api_calls": 987,
        "credits_used": 2394,
        "new_users": 19
    }
}'])
@endsection
