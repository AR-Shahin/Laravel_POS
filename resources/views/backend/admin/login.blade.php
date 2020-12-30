@extends('layouts.primary')
@section('title','Login')
@section('main_section')
    <div class="d-flex align-items-center justify-content-center bg-sl-primary ht-100v mt-5">

        <div class="login-wrapper wd-300 wd-xs-350 pd-25 pd-xs-40 bg-white mt-5 pt-5">
            <form action="{{route('admin.login')}}" method="post">
                @csrf
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Enter your email" name="email" value="{{old('email')}}">
                    <span class="text-danger">{{($errors->has('email'))? ($errors->first('email')) : ''}}</span>
                </div><!-- form-group -->
                <div class="form-group">
                    <input type="password" class="form-control" placeholder="Enter your password" name="password">
                    <span class="text-danger">{{($errors->has('password'))? ($errors->first('password')) : ''}}</span>

                </div><!-- form-group -->
                <button type="submit" class="btn btn-info btn-block">Sign In</button>
            </form>
        </div><!-- login-wrapper -->
    </div>
@stop

