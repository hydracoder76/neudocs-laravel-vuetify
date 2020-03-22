<div id="neu-header" class="neu-container">
    <div class="neu-row col-12">


                <div class=""><img class="neu-logo" alt="Logo" src="{{asset('/images/SRM_logo3-04.png')}}"/></div>


    <div class="col"><div class="neu-logo-label">service request management</div></div>




            <div id="neu-header-actions" dusk="neu-header-actions">

            @if(\Auth::check())
                    <a href="{{ route('user.updateprofile') }}"><i class="fas fa-user-circle"></i></a>
                    <span class="neu-header-text"><a href="{{ route('user.updateprofile') }}">{{ $username }}</a></span>
                    <span class="neu-header-text">&nbsp;|&nbsp;</span>
                @endif
                <a href="mailto:{{ config('srm.contact_email') }}">Contact</a>


                @if(!\Auth::check() && \Route::getCurrentRoute()->getName() != 'login.form.view')
                    <a href="{{ route('login.form.view') }}">Login</a>
                @elseif(\Auth::check())
                    <a href="{{ route('logout') }}">Logout</a>
                @endif
            </div>


    </div>
</div>