<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', 'Login | Wholesale MGM')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- AdminLTE CSS -->
    <link rel="stylesheet" href="{{ asset('assets/plugins/fontawesome-free/css/all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/dist/css/adminlte.min.css') }}">

    <style>
.avatar-wrapper {
    width: 160px;
    height: 160px;
    border-radius: 50%;
    overflow: hidden;
    border: 3px solid #007bff;
    background: #f4f6f9;
    display: flex;
    align-items: center;
    justify-content: center;
}

.avatar-wrapper img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    border-radius: 50%;
}

.avatar-label {
    cursor: pointer;
}
</style>

</head>

<body class="hold-transition login-page">

@yield('content')

<!-- AdminLTE JS -->
<script src="{{ asset('assets/plugins/jquery/jquery.min.js') }}"></script>
<script src="{{ asset('assets/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('assets/dist/js/adminlte.min.js') }}"></script>


{{-- SIMPLE JS FOR ROLE TOGGLE --}}
<script>
document.getElementById('roleSelect').addEventListener('change', function () {
    const supplierBox = document.getElementById('supplierFields');
    supplierBox.style.display = (this.value === 'supplier') ? 'block' : 'none';
});
</script>
</body>
</html>
