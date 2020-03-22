@extends('layouts.master')
@section('content')
    <div class="neu-width-100">
        <h2>Upload box files</h2>
        <div class="neu-bump-d-md"></div>
        <upload-page
                parts-json='@json($parts)'
                request-parts-json='@json($requestParts)'
                unlock-api="{{route('request.todo.unlock')}}"
                prev-url="{{route('todo.home')}}"
                upload-api="{{route('do.upload')}}"
                delete-api="{{route('do.remove.upload')}}"
                :packet-size="{{ (int) config('srm.upload_packet_size') }}"
                :max-upload-size="{{ (int) config('srm.max_upload_size') }}"
                download-url="{{route('requests.download.box')}}"
                project-id="{{$projectId}}"
                media-type-load="{{ route('media.type.all') }}"
                part-media-type-load="{{ route('part.media.type.all') }}"

        ></upload-page>
    </div>
@stop
