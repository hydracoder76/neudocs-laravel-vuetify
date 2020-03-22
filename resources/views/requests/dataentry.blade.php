@extends('layouts.master')
@section('content')

	<div class="neu-width-100">
		<dataentry request-uri="{{ route('requests.project')}}"
			  submit-to="{{ route('login.do') }}"
			  username="{{$username}}"
			  box-submission-uri="{{ route('request.dataentry.box.new')}}"
			  part-submission-uri="{{ route('request.dataentry.part.new')}}"
 	 	      part-index-submission-uri="{{ route('request.dataentry.partindex.new')}}"
			  data-entry-submission-uri="{{ route('request.dataentry.all')}}"
			  data-entry-box-submission-uri="{{ route('request.dataentry.box.project')}}"
			  box-type-def-uri="{{ route('request.boxtypedef.all')}}"
			  nav-uri="{{route('dataentry.part')}}"
			  partindex-schema-uri="{{ route('request.dataentry.partindex.box.schema','')}}"
		>
			<div class="container">
				<div class="neu-row">
					<div class="col-md-2"></div>
					<div class="col-md-6">
			<neu-data-dropdown dusk="neu-dropdown"
							   placeholder="Select a project"
							   data-source-uri="{{ route('projects.all') }}/2"
							   initial="{{ Auth::user()->default_project_id }}"
							   update-default-project-url="{{route('user.update.default.project')}}"></neu-data-dropdown>
					</div>
				</div>
			</div>
		</dataentry>


	</div>
@stop
