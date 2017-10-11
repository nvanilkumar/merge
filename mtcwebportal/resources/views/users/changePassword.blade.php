@extends('layouts.dashboard')

@section('content')

<div class="container mar-t-20">
    <div class="section">
        <div class="row">
            <div class="col s12 m3"></div>
            <div class="col s12 m6">
                <div class="card">                                      
{{ Form::open(array('action' => 'UserController@userUpdatePassword','id'=>'change_password')) }}
                    <div class="card-content">
                        <h5 class="main-heading">{{ $pageHeading }}</h5>
                        @if (count($errors) > 0)
                        <div class="alert alert-success">
                            <div id="card-alert" class="card red">
                                <div class="card-content alertmsg white-text">
                                    
                                    @foreach ($errors as $error)
                                    <p>{{ $error }}</p>
                                    @endforeach
                                    
                                </div>
                                <button type="button" class="close alertClose white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                        </div>
                        @endif
                        @if (session('status'))
                        <div class="alert alert-success">
                            <div id="card-alert" class="card blue">
                                <div class="card-content alertmsg white-text">
                                    {{ session('status') }}
                                </div>
                                <button type="button" class="close alertClose white-text" data-dismiss="alert" aria-label="Close">
                                    <span aria-hidden="true">×</span>
                                </button>
                            </div>

                        </div>
                        @endif
                        <div class="row">
                             
                           <div class="input-field col s12 m12">
                               <input type="password" name="old_password" 
                                       id="old_password" class="validate">
                                <label for="old_password" class="">Old Password</label>
                            </div> 
                            <div class="input-field col s12 m12" style="clear:both;">
                                <input type="password" name="new_password" 
                                       id="new_password" class="validate">
                                <label for="new_password" class="">New Password</label>
                            </div>
                           <div class="input-field col s12 m12">
                                <input type="password" name="confirm_password" 
                                       id="confirm_password" class="validate">
                                <label for="confirm_password" class="">Confirm Password</label>
                            </div>

                        </div>

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                  <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('UserController@dashboard')}}">cancel</a>
                                <input type="submit" name="done" value="done" class="themeblue waves-effect waves-light btn"/>
                            </div>
                        </div>

                    </div>

                    {{ Form::close() }}
                </div>
            </div>
            <div class="col s12 m3"></div>
        </div>
    </div>
</div>
<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script>
  disableMessage();
 $('#change_password').validate({
        rules: {
            old_password: {
                required: true
            },
            new_password: {
                required: true
            },
            confirm_password: {
                required: true
            }
        },
        messages: {
            old_password: {
                required: "Please enter your old password"
            },
            
            new_password: {
                required: "Please enter your new password",
            },         
            confirm_password: {
                required: "Please enter confirm password",
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
</script>
 
@endsection