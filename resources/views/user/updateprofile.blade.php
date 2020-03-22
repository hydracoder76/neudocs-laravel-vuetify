@extends('layouts.master')

@section('content')
    <div dusk="neu-heading" class="panel-heading text-center">
        Update Profile
    </div>
    <br />
    <div class="neu-width-100">
        <update-profile
                location-uri="{{ route('user.update.profile') }}"
                user="{{ $user }}"
                submit-to="{{ route('login.do') }}">
        </update-profile>
    </div>
@stop
