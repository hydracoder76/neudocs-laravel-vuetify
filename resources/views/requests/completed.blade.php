@extends('layouts.master')
@section('content')
    <div class="neu-width-100">
        <completed
            list-url="{{route('request.todo.completed.list')}}"
            filter-url="{{route('request.todo.completed.filter')}}"
            download-uri="{{ route('requests.download.part') }}">
            <neu-data-dropdown
                    dusk="neu-dropdown"
                    placeholder="Select a project"
                    data-source-uri="{{route('projects.all')}}/0"
                    initial="{{ Auth::user()->default_project_id }}"
                    update-default-project-url="{{route('user.update.default.project')}}"></neu-data-dropdown>
        </completed>
    </div>
@stop