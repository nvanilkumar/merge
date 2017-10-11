@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col m2">

            </div>
            <div class="col s12 m8">
                <div class="card">
                   @include ('layouts.partials._notifications')

                    @if ( $group == "")
                    {{ Form::open(array('action' => 'GroupController@createGroup','id'=>'create_group')) }}
                    @else   
                    {{ Form::open(array('action' => 'GroupController@updateGroup','id'=>'update_group')) }}
                    <input type="hidden" class="with-gap" name="group_id" id="group_id" value="{{$group['group_id']}}" >

                    @endif

                    <div class="card-content">
                        <h5 class="main-heading">{{$title}}</h5>
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
                            <div class="input-field col s12 m3">
                                Group Name<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m9">
                                <input type="text" class="validate" 
                                       name="group_name" id="group_name">

                            </div>
                        </div>

                        <div class="row" id="group_users">
                            <div class="col s12 m3">

                            </div> 
                            <div class="input-field col s12 m9" >
                                <input class="with-gap" name="users_type" type="radio" checked="checked"
                                       value="later_user" id="later_user">
                                <label for="later_user">Add Users later</label>
                                <input class="with-gap" name="users_type" type="radio" value="all_users" id="all_users">
                                <label for="all_users">Add All Users</label>

                            </div>


                        </div>
                        @if(count($userData) > 0 && $userData !== "") 
                            <div class="row">
                                <div class="col s12 m3">
                                    Users  
                                </div>
                                <div class="input-field col s12 m9">
                                    @foreach ($userData as $user) 

                                    <div class="chip" >{{$user->first_name." ".$user->last_name}}<i class="close material-icons">close</i>
                                        <input type="hidden" name="user_ids[]" value="{{$user->user_id}}"/>
                                    </div>
                                    @endforeach
                                </div>
                            </div> 
                        @endif            
                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align mar-t-20">
                                <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('GroupController@getGrouopList')}}">cancel</a>
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
    var groupDetails = <?php echo json_encode($group); ?>;
    disableMessage();
</script>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/groups.js') }}"></script>

@endsection