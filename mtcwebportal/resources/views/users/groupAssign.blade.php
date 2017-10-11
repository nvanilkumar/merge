@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m3"></div>
            <div class="col s12 m6">
                <div class="card">
                   @include ('layouts.partials._server_error')	



                    {{ Form::open(array('action' => 'UserController@assignGroupUsers','id'=>'assign_group',
                               "onSubmit" =>"FALSE")) }}
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
                            <div class="col s12 m4">

                            </div>
                        </div> 
                        
                            <div class="row">
                                <div class="input-field col s12 m4">
                                    Groups
                                </div>
                                <div class="input-field col s12 m8">
                                    <select name="group_id" id="group_id">
                                        <option value="">Select the group name</option>
                                        @if(count($groups) > 0)
                                        @foreach ($groups as $group)  
                                        <option value="{{$group->group_id}}">{{$group->group_name}}</option>
                                        @endforeach
                                        @endif
                                    </select>
                                </div>
                            </div> 

                            <div class="row">
                                <div class="input-field col s12 m4">
                                    Users  
                                </div>
                                <div class="col s12 m8">
                                    @if(count($userData) > 0)
                                    @foreach ($userData as $user) 

                                    <div class="chip" >{{$user->user_name}}<i class="close material-icons">close</i>
                                        <input type="hidden" name="user_ids[]" value="{{$user->user_id}}"/>
                                    </div>
                                    @endforeach
                                    @endif



                                </div>
                            </div> 


                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('UserController@getUserList',['student'])}}" id="cancel_button">cancel</a>
                                <input type="submit" name="done" value="done" class="themeblue waves-eff       ect waves-light btn"/>



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

<script type="text/javascript" src="{{ asset('/js/groupAssign.js') }}"></script>


@endsection