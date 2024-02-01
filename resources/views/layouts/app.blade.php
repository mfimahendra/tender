<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Salwa | Tender</title>
    <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
    
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">    
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/fontawesome-free/css/all.min.css') }}">        
    <link rel="stylesheet" href="{{ asset('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}">    
    <link rel="stylesheet" href="{{ asset('adminlte/dist/css/adminlte.min.css') }}">
    <style>
        .container {
            max-width: 100% !important;
        }
    </style>
    @yield('styles')
</head>

@guest        
        <section class="mt-4 py-4">
            @yield('content')
        </section>

    @else

        <body class="hold-transition sidebar-mini layout-fixed">    
            <div class="wrapper">        
                @include('components.navbar')
                @include('components.sidebar')
                
                <div class="content-wrapper">            
                    <section class="content-header">
                        @yield('content-header')
                    </section>

                    <!-- Main content -->
                    <section class="content">
                        @yield('content')
                    </section>
                    <!-- /.content -->
                </div>
                <!-- /.content-wrapper -->

                <footer class="main-footer" style="padding:2px 1rem;">
                    @include('components.footer')
                </footer>
                
                {{-- <aside class="control-sidebar control-sidebar-dark">            
                </aside> --}}
                
            </div>

    @endguest
            
        <script src="{{ asset('adminlte/plugins/jquery/jquery.min.js') }}"></script>    
        <script src="{{ asset('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>            
        <script src="{{ asset('adminlte/plugins/overlayScrollbars/js/jquery.overlayScrollbars.min.js') }}"></script>
        <script src="{{ asset('adminlte/dist/js/adminlte.js') }}"></script>

        @yield('scripts')

        <script>
        // sidebar nav_link isOpening when open that page
        $(document).ready(function() {
            var url = window.location;
            console.log(url);
            // for sidebar menu entirely but not cover treeview
            $('ul.nav-sidebar a').filter(function() {
                return this.href == url;
            }).addClass('active');
            // for treeview
            $('ul.nav-treeview a').filter(function() {
                return this.href == url;
            }).parentsUntil(".nav-sidebar > .nav-treeview").addClass('menu-open').prev('a').addClass('active');
        });


        </script>
    </body>

</html>
