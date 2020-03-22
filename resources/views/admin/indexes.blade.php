@extends('layouts.master')

@section('content')

	<div class="neu-width-100">
		<index-builder dusk="neu-field-builder"
					   index-submit-uri="{{ route('indexes.project.new') }}"
					   index-uri="{{ route('indexes.project.get.all') }}"
						delete-uri="{{route('indexes.project.delete')}}"
						edit-uri="{{route('indexes.project.edit')}}">

			<div class="container">
				<div class="neu-row">
					<div class="col-md-2"></div>
					<div class="col-md-6">
						<neu-data-dropdown dusk="neu-dropdown"
										   placeholder="Select a project"
										   data-source-uri="{{ route('projects.all') }}"
										   initial="{{ Auth::user()->default_project_id }}"
										   update-default-project-url="{{route('user.update.default.project')}}"></neu-data-dropdown>
					</div>
				</div>

			</div>


		</index-builder>
	</div>

@endsection