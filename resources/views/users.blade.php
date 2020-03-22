@extends('layouts.master')
@section('content')

    <div class="neu-width-100">

        <user-admin user-uri="{{ route('user.index')}}"
                    user-load="{{ route('user.index') }}?page=1"
                    company-load="{{ route('company.all') }}"
                    submit-to="{{ route('login.do') }}"
                    user-company="{{$companyName}}"
                    user-role="{{$userRole}}"
                    reset-pass-uri="{{route('resetpassword.user')}}"
                    user-search="{{route('user.search')}}">
        </user-admin>
    </div>



@stop