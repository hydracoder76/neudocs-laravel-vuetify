@extends('layouts.master')
@section('content')
    <div class="neu-width-100">
    <requestcomponent
        username="{{$username}}"
        filter-url="{{ route('requests.filter') }}"
        download-url="{{route('requests.download')}}"
        new-request-url="{{route('requests.new', '')}}"
    >
        <neu-data-dropdown
            dusk="neu-dropdown"
            placeholder="Select a project"
            data-source-uri="{{route('projects.all')}}/0"
            initial="{{ Auth::user()->default_project_id }}"
            update-default-project-url="{{route('user.update.default.project')}}"></neu-data-dropdown>
    </requestcomponent>
    </div>
@stop