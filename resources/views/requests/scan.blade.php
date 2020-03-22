@extends('layouts.master')
@section('content')
    <scan
        prev-url="{{route('todo.home')}}"
        dwt-product-key="{{config('srm.dwt_product_key')}}"
        upload-api="{{route('do.upload')}}"
        parts-json="{{$parts}}"
        unlock-api="{{route('request.todo.unlock')}}"
        media-type-load="{{ route('media.type.all') }}"
        part-media-type-load="{{ route('part.media.type.all') }}"
    >
    </scan>
@stop
