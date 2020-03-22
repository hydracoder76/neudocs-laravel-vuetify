@extends('layouts.master')
@section('content')
    <div class="neu-width-100">
        <project-management project-uri="{{ route('projects.index')}}"
            project-load="{{ route('projects.index') }}?page=1"
            user-load="{{ route('user.get.bycompany', '') }}"
            company-load="{{ route('company.all') }}"
            project-sort="{{route('projects.sort')}}"
            media-type-load="{{ route('media.type.all') }}">
        </project-management>
    </div>
@stop
