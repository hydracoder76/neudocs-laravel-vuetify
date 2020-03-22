@extends('layouts.master')
@section('content')
    <div class="neu-container neu-default-body">
        <div class="neu-row">
            <div class="neu-bump-d-md"></div>
            <div class="neu-row offset-4">
                <h2>Please verify your MFA token to remain logged in</h2>
            </div>
        </div>
        <div class="neu-bump-d-md"></div>
        <div class="neu-row">
            <mfa-form mfa-submission-uri="{{ route('login.do.refresh') }}"
                      :current-attempts="{{ $currentAttempts }}"
                      :max-attempts="{{ $maxAttempts }}"
                      email="{{ \Auth::user()->email }}"></mfa-form>
        </div>
    </div>
@stop