@extends('layouts.master')
@section('content')
<div class="neu-width-100">
        <log-page log-api="{{route('log.search')}}">
        </log-page>
    </div>
@stop
