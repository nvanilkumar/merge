@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m3"></div>
            <div class="col s12 m6">
                <div class="card">
                    @include ('layouts.partials._notifications')

                    {{ Form::open(array('action' => 'NotificationController@createNotification','id'=>'create_notification')) }}
                    <div class="card-content">
                        <h5 class="main-heading">{{$title}}</h5>


                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Enter Message
                            </div>
                            <div class="input-field col s12 m8">
                                <textarea id="message" name="message" class="materialize-textarea"></textarea>
                                <label for="textarea1" class="">Enter Message</label>
                            </div>
                        </div>



                        <div class="row">
                            <div class="input-field col s12 m4">
                                Select Groups 
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple id="group_list" name="group_list[]" class="selectOne">
                                    <option value="" disabled selected>Select group name</option>
                                    @if(count($groupList) > 0)
                                        @foreach ($groupList as $group)
                                                    <option value="{{$group->group_id}}">{{$group->group_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div> 
                        
                       <div class="row">
                            <div class="input-field col s12 m4">
                                Select Users 
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple name="users_list[]" id="users_list" class="selectOne">
                                    <option value="" disabled selected>Select user name</option>

                                    @if(count($usersList) > 0)
                                    @foreach ($usersList as $user)  
                                    <option value="{{$user->user_id}}">{{$user->first_name." ".$user->last_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>  




                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                 <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('NotificationController@getNotifications')}}">cancel</a>
                                
                                <input type="submit" name="done" value="done" class="themeblue waves-effect waves-light btn" id="notificationSave"/>



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
<script type="text/javascript" src="{{ asset('/js/notifications.js') }}"></script>


@endsection