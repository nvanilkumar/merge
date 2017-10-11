@extends('layouts.login')

@section('content')

<div class="login-page">
    <div class="col s12 z-depth-4 card-panel login-pad" >
        <div class="row">
            <div class="input-field center">
                <img src="{{ asset('/img/login-logo.png') }}" alt="" />
            </div>
        </div>

        <div id="login-page">
            <div class="row">
                <div class="input-field">
                    <input id="username" type="text" class="validate mar-b-0" autocomplete="off">
                    <label for="username">Username</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field">
                    <input id="password" type="password" autocomplete="off" class="validate">
                    <label for="password">Password</label>
                    <div class="error" id="errorMessage"></div>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s6 m6 l6 nopad">
                    <p class="login-page-links"><a href="javascript:void(0);" id="forgot-link">Forgot password?</a></p>
                </div>
                <div class="input-field col s6 m6 l6 nopad">
                    <div class="input-field col s12 nopad">
                        <a href="javascript:void(0);" id="login-link" 
                           class="btn themeblue waves-effect waves-light col s12">
                            Login
                        </a>
                    </div> 
                </div>          
            </div>
        </div>

        <div id="forgot-page">
            <div class="row">
                <p id="forgot-status-message"></p>
                <div class="input-field">
                    <input id="forgot-username" name="forgot-username" type="text">
                    <label for="email" class="center-align">Email</label>
                </div>
            </div>
            <div class="row">
                <div class="input-field col s12 nopad">
                    <a href="javascript:void(0);" id="forgot-link-submit" 
                       class="btn themeblue waves-effect waves-light col s12">Submit</a>
                </div>
            </div>

        </div>

    </div>

</div> 

<script>
    
    var loginApiUrl = "./login";
    var forgotApiUrl = '<?php echo \Config::get('view.lumen_base_url') . \Config::get('view.forgot_api') ?>';
    var authorization= '{{ env('API_USERNAME_Password') }}';
</script>
<script type="text/javascript" src="{{ asset('/js/login.js') }}"></script>

@endsection

