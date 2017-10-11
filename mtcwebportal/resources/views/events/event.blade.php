@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                   @include ('layouts.partials._server_error')

                    {{ Form::open(array('action' => 'EventController@createEvent','id'=>'create_event', 'class'=>'mtcevents')) }}
                    <div class="card-content">
                        <h5 class="main-heading">{{$title}}</h5>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Event Name<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" class="validate" name='event_name' id='event_name' data-length="100">
                                <label for="event_name">Event Name</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Event Description<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <textarea id="textarea1" class="materialize-textarea" name="event_description" 
                                          id="event_description"></textarea>
                                <label for="event_description">Event Description</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Event Dates<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text"  id="event_start_date" name="event_start_date">
                                <label for="event_start_date" class="">Event Start Date</label>
                            </div>
                            <div class="input-field col s12 m1"> to</div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="event_end_date" name="event_end_date">
                                <label for="event_end_date" class="">Event End Date</label>
                            </div>
                        </div> 
                        
                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Select Groups to add to this event
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple id="group_list" name="group_list[]">
                                    <option value="" disabled selected>Select the group name</option>
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
                                Select Users to add to this event
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple name="users_list[]" id="users_list">
                                    <option value="" disabled selected>Select the user name</option>

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
                                   href="{{action('EventController@getEventList')}}">cancel</a>
                                <input type="submit" name="done" value="done" 
                                       class="themeblue waves-effect waves-light btn"/>



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
<script type="text/javascript" src="{{ asset('/js/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/events.js') }}"></script>
@endsection