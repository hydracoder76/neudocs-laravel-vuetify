@extends('layouts.master')
@section('content')
    <div dusk="neu-heading" class="panel-heading text-center">
        <h2>Reset Password</h2>
    </div>
    <hr>
    <div class="neu-width-100">
        <reset-password
            reset-pass-url="{{route("resetpassword.double")}}"></reset-password>
    </div>
@stop
