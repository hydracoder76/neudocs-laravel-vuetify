@extends('layouts.master')
@section('content')
    <div class="neu-row justify-content-center">
        <h2 class="panel-heading">{{$pageTitle}}</h2>
    </div>
    <div class="neu-row justify-content-center text-center">
    <settings
    setting-url="{{route('do.settings.save')}}"
    get-setting-url="{{route('do.settings.get')}}">
        <neu-data-dropdown dusk="neu-dropdown"
           placeholder="Select a project"
           data-source-uri="{{ route('projects.all') }}/2"
           slot="neu-todo-content"
           initial="{{ Auth::user()->default_project_id }}"
           update-default-project-url="{{route('user.update.default.project')}}"
        ></neu-data-dropdown></settings>
    </div>
@stop