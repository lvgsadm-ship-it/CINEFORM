<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        @include('template.head')
    </head>
    <body >
        <div class="wrapper">
            
            <div style="width:100%; margin-top:0px" class="main-panel">
                

                <div style="margin-top:0px" class="container container-menu">
                    <div class="page-inner">
                        
                        {{-- Start from here. --}}
                        @yield('content')
                    </div>
                </div>

                <footer class="footer">
                    @include('template.footer')
                </footer>
            </div>

            <!-- Custom template | don't include it in your project! -->
            
            <!-- End Custom template -->
        </div>

        @include('template.script')
    </body>
</html>
