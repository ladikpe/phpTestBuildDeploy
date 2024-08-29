
<!DOCTYPE html>
<html class="no-js css-menubar" lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta name="description" content="bootstrap material admin template">
    <meta name="author" content="">

    <title>Maintenance | HCMatrix</title>

    <link rel="apple-touch-icon" href="{{ asset('assets/images/apple-touch-icon.png') }}">
    <link rel="shortcut icon" href="{{ asset('assets/images/logo.png') }}">
    <!-- Stylesheets -->
    <link rel="stylesheet" href="{{ asset('global/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/css/bootstrap-extend.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/site.css') }}">
    <!-- Plugins -->
    <link rel="stylesheet" href="{{ asset('global/vendor/animsition/animsition.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/asscrollable/asScrollable.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/switchery/switchery.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/intro-js/introjs.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/slidepanel/slidePanel.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/flag-icon-css/flag-icon.css') }}">
    <link rel="stylesheet" href="{{ asset('global/vendor/waves/waves.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/examples/css/pages/maintenance.min.css') }}">
    <!-- Fonts -->
    <link rel="stylesheet" href="{{ asset('global/fonts/material-design/material-design.min.css') }}">
    <link rel="stylesheet" href="{{ asset('global/fonts/brand-icons/brand-icons.min.css') }}">

    <link rel='stylesheet' href="http://fonts.googleapis.com/css?family=Roboto:400,400italic,700">
    <style>
        .text-center {
            text-align: center!important;
        }
    </style>

    <!--[if lt IE 9]>
    <script src="../../global/vendor/html5shiv/html5shiv.min.js?v4.0.1"></script>
    <![endif]-->

    <!--[if lt IE 10]>
    <script src="../../global/vendor/media-match/media.match.min.js?v4.0.1"></script>
    <script src="../../global/vendor/respond/respond.min.js?v4.0.1"></script>
    <![endif]-->

    <!-- Scripts -->
    <script src="../../global/vendor/breakpoints/breakpoints.min.js?v4.0.1"></script>
    <script>
        Breakpoints();
    </script>
</head>
<body class="animsition site-navbar-small page-maintenance layout-full">
<!--[if lt IE 8]>
<p class="browserupgrade">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> to improve your experience.</p>
<![endif]-->


<!-- Page -->
<div class="page vertical-align text-center" data-animsition-in="fade-in" data-animsition-out="fade-out">>
    <div class="page-content vertical-align-middle">
        <img  src="{{ asset('hcm.png') }}">
        <br/>
{{--        <i class="icon md-close  page-maintenance-icon" aria-hidden="true"></i>--}}
        <h2>Subscription Expired</h2>
        <p>PLEASE CONTACT SNAPNET LIMITED</p>
        <p>sales@snapnet.com.ng</p>
        <p>09090004652</p>

        <footer class="page-copyright">

            <p>© {{date('Y')}}. All RIGHT RESERVED.</p>

        </footer>
    </div>
</div>
<!-- End Page -->


<!-- Core  -->
<script src="{{ asset('global/vendor/babel-external-helpers/babel-external-helpers.js') }}"></script>
<script src="{{ asset('global/vendor/jquery/jquery.js') }}"></script>
<script src="{{ asset('global/vendor/tether/tether.js') }}"></script>
<script src="{{ asset('global/vendor/bootstrap/bootstrap.js') }}"></script>
<script src="{{ asset('global/vendor/animsition/animsition.js') }}"></script>
<script src="{{ asset('global/vendor/mousewheel/jquery.mousewheel.js') }}"></script>
<script src="{{ asset('global/vendor/asscrollbar/jquery-asScrollbar.js') }}"></script>
<script src="{{ asset('global/vendor/asscrollable/jquery-asScrollable.js') }}"></script>
<script src="{{ asset('global/vendor/waves/waves.js') }}"></script>
<!-- Plugins -->
<script src="{{ asset('global/vendor/switchery/switchery.min.js') }}"></script>
<script src="{{ asset('global/vendor/intro-js/intro.js') }}"></script>
<script src="{{ asset('global/vendor/screenfull/screenfull.js') }}"></script>
<script src="{{ asset('global/vendor/slidepanel/jquery-slidePanel.js') }}"></script>
<script src="{{ asset('global/vendor/jquery-placeholder/jquery.placeholder.js') }}"></script>
<!-- Scripts -->
<script src="{{ asset('global/js/State.js') }}"></script>
<script src="{{ asset('global/js/Component.js') }}"></script>
<script src="{{ asset('global/js/Plugin.js') }}"></script>
<script src="{{ asset('global/js/Base.js') }}"></script>
<script src="{{ asset('global/js/Config.js') }}"></script>
<script src="{{ asset('assets/js/Section/Menubar.js') }}"></script>
<script src="{{ asset('assets/js/Section/Sidebar.js') }}"></script>
<script src="{{ asset('assets/js/Section/PageAside.js') }}"></script>
<script src="{{ asset('assets/js/Plugin/menu.js') }}"></script>
<script src="{{ asset('global/js/config/colors.js') }}"></script>
<script src="{{ asset('assets/js/config/tour.js') }}"></script>
<script>
    Config.set('assets', '{{ asset('assets') }}');
</script>
<script src="{{ asset('assets/js/Site.js') }}"></script>
<script src="{{ asset('global/js/Plugin/asscrollable.js') }}"></script>
<script src="{{ asset('global/js/Plugin/slidepanel.js') }}"></script>
<script src="{{ asset('global/js/Plugin/switchery.js') }}"></script>
<script src="{{ asset('global/js/Plugin/jquery-placeholder.js') }}"></script>
<script src="{{ asset('global/js/Plugin/material.js') }}"></script>
<script>
    Config.set('assets', '../assets');
</script>

<!-- Page -->

<script>
    (function(document, window, $) {
        'use strict';

        var Site = window.Site;
        $(document).ready(function() {
            Site.run();
        });
    })(document, window, jQuery);
</script>



</body>

</html>