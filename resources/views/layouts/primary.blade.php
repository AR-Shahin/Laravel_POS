<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="author" content="">

    <title>@yield('title',' Admin') | Admin</title>

    <!-- Bootstrap core CSS -->
    <link href="{{asset('backend')}}/css/bootstrap.min.css" rel="stylesheet">
    <link href="{{asset('backend')}}/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="{{asset('backend')}}/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />

    <!--right slidebar-->
    <link href="{{asset('backend')}}/css/slidebars.css" rel="stylesheet">
    <!--dynamic table-->
    <link href="{{asset('backend')}}/assets/advanced-datatable/media/css/demo_page.css" rel="stylesheet" />
    <link href="{{asset('backend')}}/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
    <link rel="stylesheet" href="{{asset('backend')}}/assets/data-tables/DT_bootstrap.css" />
    <!-- Custom styles for this template -->
    <link href="{{asset('backend')}}/css/style.css" rel="stylesheet">
    <link href="{{asset('backend')}}/css/style-responsive.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/css/select2.min.css" rel="stylesheet" />
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-beta.1/dist/js/select2.min.js"></script>
    <script type="text/javascript" language="javascript" src="{{asset('backend')}}/assets/advanced-datatable/media/js/jquery.dataTables.js"></script>
    <script type="text/javascript" src="{{asset('backend')}}/assets/data-tables/DT_bootstrap.js"></script>
    <!--dynamic table initialization -->
    <script src="{{asset('backend')}}/js/dynamic_table_init.js"></script>
</head>

<body>

<section id="container" class="">
    @yield('main_section')
</section>

<!-- js placed at the end of the document so the pages load faster -->
{{--<script src="{{asset('backend')}}/js/jquery.js"></script>--}}
<script src="{{asset('backend')}}/js/bootstrap.bundle.min.js"></script>
<script class="include" type="text/javascript" src="{{asset('backend')}}/js/jquery.dcjqaccordion.2.7.js"></script>
<script src="{{asset('backend')}}/js/jquery.scrollTo.min.js"></script>
<script src="{{asset('backend')}}/js/slidebars.min.js"></script>
<script src="{{asset('backend')}}/js/jquery.nicescroll.js" type="text/javascript"></script>
<script src="{{asset('backend')}}/js/respond.min.js" ></script>

<!--common script for all pages-->
<script src="https://cdn.jsdelivr.net/npm/handlebars@latest/dist/handlebars.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@10"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/notify/0.4.2/notify.min.js"></script>
<script src="{{asset('backend')}}/js/common-scripts.js"></script>
<script src="{{asset('backend')}}/ajax.js"></script>

@stack('script')

</body>
</html>
