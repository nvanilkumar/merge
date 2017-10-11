@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                    @include ('layouts.partials._server_error')	
                 @if ( $user == "")
                    {{ Form::open(array('action' => 'UserController@createUser','id'=>'create_user')) }}
                 @else   
                    {{ Form::open(array('action' => 'UserController@updateUser','id'=>'update_user')) }}
                     <input type="hidden" class="with-gap" name="user_id" id="user_id" value="{{$user['user_id']}}" >
                 @endif
                    
                    <div class="card-content">
                        <h5 class="main-heading">{{ $pageHeading }}</h5>
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

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m2">
                                User Type
                            </div>
                            <div class="input-field col s12 m5">

                                <input class="with-gap" name="role_type" value="student" type="radio" id="student">
                                <label for="student">Student</label>
                                <input class="with-gap" name="role_type" type="radio" value="staff" id="staff">
                                <label for="staff">TechHire Staff</label>

                            </div>

                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m2">
                                User Details<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text" name="first_name" 
                                       id="first_name" class="validate">
                                <label for="first_name" class="">First Name</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text" name="last_name"
                                       id="last_name" class="validate">
                                <label for="last_name" class="">Last Name</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="col s12 m2">
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text"  name="username" id="username"  class="validate">
                                <label for="username" class="">User Name</label>
                                
                            </div>
                            <div class="input-field col s12 m5">
                                <input  type="password" name="password" 
                                       id="password" class="validate">
                                <label for="password" class="">Password</label>
                                 <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="javascript:void(0);" id="change-pasword">Change Password</a>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text" name="email" id="email" class="validate">
                                <label for="email" class="">Email</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m2">
                                Additional Info
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text"  name="mobileno" 
                                       id="mobileno" class="validate">
                                <label for="mobileno" class="">Mobile Number</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text" name="address" id="address" class="validate">
                                <label for="address" class="">Address</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text" name="country"  id="country" class="validate">
                                 <label for="country" class="">Country</label>
                            </div>
                            <div class="input-field col s12 m5">
                                <input type="text" name="pincode" id="pincode" 
                                       class="validate">
                                 <label for="pincode" class="">Pincode</label>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('UserController@getUserList',['student'])}}">cancel</a>
                                <input type="submit" name="done" value="done" class="themeblue waves-effect waves-light btn"/>
                                


                            </div>
                        </div>

                    </div>

                    {{ Form::close() }}
                </div>
            </div>
        </div>
    </div>
</div>
 
<script>
    var userCheckUrl = '{{ action('UserController@userNameCheck')}}';
    var userDetails= <?php echo json_encode($user); ?>;
    disableMessage();
 
</script>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/user.js') }}"></script>
@endsection