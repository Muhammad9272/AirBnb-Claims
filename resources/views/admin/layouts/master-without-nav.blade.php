<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-topbar="dark" data-sidebar-image="none" data-layout-mode="light">

    <head>
    <meta charset="utf-8" />
    <title>@yield('title') | {{$gs->name}} - Admin Dashboard </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Maalik Development" name="description" />
    <meta content="Maalik9272@gmail.com" name="author" />
    <!-- App favicon -->
    <link rel="icon"  type="image/x-icon" href="{{asset('assets/images/'.$gs->favicon)}}"/>

        @include('admin.layouts.head-css')
  </head>

    @yield('body')

    @yield('content')

    @include('admin.layouts.vendor-scripts')
    </body>
</html>
