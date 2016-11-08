<!doctype html>
<html lang="en">
<head>
    <title>Admin</title>
    <link rel="stylesheet" href="/css/bootstrap.min.css" type="text/css">
</head>
<body>

@include('admin.flash')

<div id="wrapper">
    <div style="text-align: center; padding: 5px; margin: 0px; border-bottom: 1px solid #dddddd">
        <h3>Welcome to Admin Panel</h3>
    </div>

    <div style="margin-top: 20px;">
        @yield('content')
    </div>
</div>

<script src="/js/jquery.min.js"></script>
<script src="/js/bootstrap.min.js"></script>
</body>
</html>