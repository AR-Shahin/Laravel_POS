@extends('layouts.primary')
@include('includes.header')
@include('includes.sidebar')
@section('main_section')
    <section id="main-content">
        <section class="wrapper site-min-height">
            @yield('main_content')
        </section>
    </section>
@stop
@include('includes.footer')