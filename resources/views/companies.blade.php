@extends('layouts.master')
@section('content')
    <div class="neu-width-100">
        <company company-uri="{{ route('company.index')}}"
                 company-load="{{ route('company.index') }}"
                 submit-to="{{ route('login.do') }}"
                 company-search="{{route('company.search')}}">
    </div>
@stop
