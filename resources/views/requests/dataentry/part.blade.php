@extends('layouts.master')
@section('content')
	<div class="neu-width-100">
		<dataentrypart request-uri="{{ route('requests.project')}}"
			  submit-to="{{ route('login.do') }}"
			  username="{{$username}}"
			  box-submission-uri="{{ route('request.dataentry.box.new')}}"
			  part-submission-uri="{{ route('request.dataentry.part.new')}}"
 	 	      part-index-submission-uri="{{ route('request.dataentry.partindex.new')}}"
			  data-entry-submission-uri="{{ route('request.dataentry.all')}}"
			  data-entry-box-submission-uri="{{ route('request.dataentry.box.project','')}}"
			  prev-url="{{route('dataentry.home')}}"
			  part-load="{{ route('dataentry.home') }}"
			  partindex-uri="{{ route('request.dataentry.partindex.box')}}"
			  partindex-schema-uri="{{ route('request.dataentry.partindex.box.schema','')}}"
			  box-json='{{$box}}'
			  project-json='{{$project}}'
		>
		</dataentrypart>
	</div>
@stop