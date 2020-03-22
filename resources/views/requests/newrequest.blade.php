@extends('layouts.master')
@section('content')
 <div class="neu-width-100">
    <new-request
                indexes-json='@json($indexes)'
                project-id="{{$projectID}}"
                filter-url="{{ route('requests.search') }}"
                new-request-url="{{route('requests.newRequest')}}"
                request-url="{{route('requests')}}"
                auto-url="{{route('requests.auto.part')}}"
                ></new-request>
</div>
    
@stop