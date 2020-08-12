<!DOCTYPE html>
<html lang="en">
<head>
    <!-- Basic -->
    <meta charset="utf-8">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Ramaqat</title>
    @include('includes.frontend.style')
</head>

<body>

<!-- layout-->
<div id="layout">
    <!--- header -->
{{--@include('includes.frontend.header')--}}
{{--    <h4>body here define</h4>--}}
<!--- sections -->
{{--    @yield('headerbody')--}}
    @yield('indexpage')
    @yield('userlogin')
    @yield('register')
   @yield('offline-course')
   @yield('online-course')

</div>
<!-- End layout-->

<!-- Script Files-->

@include('includes.frontend.scripts')
</body>
{{--@include('includes.frontend.footer')--}}
</html>
