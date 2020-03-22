@extends('layouts.master')
@section('content')
	<div class="neu-width-100">
		<h2 dusk="neu-heading" class="panel-heading">{{$pageTitle}}</h2>

		<todo request-uri="{{ route('requests.project')}}"
			  submit-to="{{ route('login.do') }}"
			  username="{{$username}}"
				nav-uri="{{route('request.todo.nav')}}"
                upload-uri="{{route('todo.upload')}}"
              scan-uri="{{route('todo.scan')}}"
				todo-search="{{route('todo.search')}}"
                media-type-load="{{ route('media.type.all') }}"
                part-media-type-load="{{ route('part.media.type.all') }}"
				fulfill-uri="{{route('request.todo.fulfill')}}">
			<neu-data-dropdown dusk="neu-dropdown"
							   placeholder="Select a project"
							   data-source-uri="{{ route('projects.all') }}/2"
							   slot="neu-todo-content"
							   initial="{{ Auth::user()->default_project_id }}"
							   update-default-project-url="{{route('user.update.default.project')}}"
                              ></neu-data-dropdown>
		</todo>
	</div>
@stop
