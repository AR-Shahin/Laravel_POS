@extends('layouts.primary')
@section('main_section')
    @include('includes.header')
    @include('includes.sidebar')
    <section id="main-content">
        <section class="wrapper site---min-height" style="margin-top: 70px">
            @yield('main_content')
        </section>
    </section>
    @include('includes.footer')
@stop
