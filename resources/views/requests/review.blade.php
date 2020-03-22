@extends('layouts.master')
@section('content')
    <div class="neu-width-100">
        <review
                filter-url="{{ route('requests.filter') }}"
                review-url="{{route('requests.review.send')}}"
                download-url="{{route('requests.download')}}">
            <neu-data-dropdown
                    dusk="neu-dropdown"
                    placeholder="Select a project"
                    data-source-uri="{{route('projects.all')}}/0"
                    initial="{{ Auth::user()->default_project_id }}"
                    update-default-project-url="{{route('user.update.default.project')}}"></neu-data-dropdown>
        </review>
    </div>
@stop