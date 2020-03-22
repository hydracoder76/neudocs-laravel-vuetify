@extends('layouts.master')
@section('content')

    <div class="neu-container">
        <div class="neu-row">
            <div class="neu-bump-d-md"></div>
            <div class="neu-row offset-4">
            </div>
        </div>
        <div class="neu-bump-d-md"></div>
            <login-form submit-to="{{ route('login.do') }}"
                        verify-at="{{ route('login.do.verify') }}"
                        forgot-url="mailto:{{ config('srm.contact_email') }}"
                        dusk="login-form">
            </login-form>
    </div>

@stop