<!DOCTYPE html>
<html>
<head>
    <title>Admin Dashboard</title>
</head>
<body>
    @if(!Auth::guard('admin')->check())
        <script>window.location.href = "{{ route('admin.login') }}";</script>
    @endif

    <h2>Welcome, Admin</h2>
    <form method="POST" action="{{ route('admin.logout') }}">
        @csrf
        <button type="submit">Logout</button>
    </form>
</body>
</html>
