<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="shortcut icon" href="img/favicon.html">

    <title>@yield('title',' | Admin')</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('backend')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('backend')}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('backend')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!--right slidebar-->
    <link href="{{asset('backend')}}/css/slidebars.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="{{asset('backend')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('backend')}}/css/style-responsive.css" rel="stylesheet" />

</head>

<body>

<section id="container" class="">
    @yield('main_section')
</section>

<!-- js placed at the end of the document so the pages load faster -->
<script src="{{asset('backend')}}/js/jquery.js"></script>
<script src="{{asset('backend')}}/js/bootstrap.bundle.min.js"></script>
<script class="include" type="text/javascript" src="{{asset('backend')}}/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="{{asset('backend')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('backend')}}/js/slidebars.min.js"></script>
<script src="{{asset('backend')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="{{asset('backend')}}/js/respond.min.js" ></script>

<!--common script for all pages-->
<script src="{{asset('backend')}}/js/common-scripts.js"></script>



</body>
</html>
