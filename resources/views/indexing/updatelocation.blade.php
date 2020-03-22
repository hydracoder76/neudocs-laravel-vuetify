@extends('layouts.master')
@section('content')

    <div class="neu-row justify-content-center">   
        <h2 class="panel-heading">Update Location</h2>
        <update-location location-uri="{{ route('box.location')}}"   submit-to="{{ route('login.do') }}">

        </update-location>
    </div>



@stop