<!DOCTYPE html>
<html>
    <head>

        <!--Import materialize.css-->
        <link type="text/css" rel="stylesheet" href="{{ asset('/css/materialize.min.css') }}"  media="screen,projection"/>

        <!--Let browser know website is optimized for mobile-->
        <meta name="viewport" content="width=device-width, initial-scale=1.0"/>

        <!--Import custom styles.css-->
        <link type="text/css" rel="stylesheet" href="{{ asset('/css/custom-styles.css') }}"  media="screen,projection"/>
        <script type="text/javascript" src="{{ asset('/js/jquery-2.1.1.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('/js/materialize.min.js') }}"></script>
        <script>
            var mtcBaseUrl="<?php echo url('/'); ?>";
        </script>
    </head>

    <body class="login-bg">
        <div class="container">
            @yield('content')
        </div>

        <!--Import jQuery before materialize.js-->
        
        <script type="text/javascript" src="{{ asset('/js/materialize.min.js') }}"></script>
        
        
    </body>
</html>
