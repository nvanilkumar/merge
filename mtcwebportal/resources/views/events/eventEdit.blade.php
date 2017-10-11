@extends('layouts.dashboard')

@section('content')

<div class="container mar-t-20">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                    @include ('layouts.partials._server_error')

                    {{ Form::open(array('action' => 'EventController@updateEvent','id'=>'edit_event', 'class'=>'mtcevents')) }}
                    <input type="hidden" class="with-gap" name="event_id" id="event_id" value="{{$event['event_id']}}" >
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
                                Event Name<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" class="validate" name='event_name' id='event_name' value="{{$event['event_name']}}"  data-length="100">
                                <label for="event_name">Event Name</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="col s12 m4">
                                Event Description<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <textarea id="textarea1" class="materialize-textarea" name="event_description" 
                                          id="event_description" >{{$event['event_description']}}</textarea>
                                <label for="event_description">Event Description</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="col s12 m4">
                                Event Dates<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text"  id="event_start_date" value="" name="event_start_date">
                                <label for="event_start_date" class="">Event Start Date</label>
                            </div>
                            <div class="input-field col s12 m1"> to</div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="event_end_date" value="" name="event_end_date">
                                <label for="event_end_date" class="">Event End Date</label>
                            </div>
                        </div> 


                        <div class="row">
                            <div class="col s12 m4">
                                Select Users to add to this event
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple name="users_list[]" id="users_list">
                                    <option value="" disabled selected>Selected users</option>
                                    <?php
                                    if (count($usersList) > 0) {
                                        $userIds = array_key_exists("user_ids", $selectedUsers) ? $selectedUsers['user_ids'] : [];
                                        foreach ($usersList as $user) {
                                            $status = "";
                                            if (in_array($user->user_id, $userIds)) {
                                                $status = "disabled='disabled'";
                                            }
                                            ?>
                                            <option value="{{$user->user_id}}" {{$status}} >{{$user->first_name." ".$user->last_name}}</option>
                                            <?php
                                        }
                                    }
                                    ?>







                                </select>
                            </div>
                        </div> 

                        <div class="row">
                            @if (array_key_exists('accepted',$selectedUsers))
                            <div class="col s12 m4">
                                <div class="card-panel">
                                    <ul class="cardlist">

                                        @foreach ($selectedUsers['accepted'] as $user)
                                        <li><i class="material-icons green-text">done</i>{{$user}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            @if (array_key_exists('rejected',$selectedUsers))
                            <div class="col s12 m4">
                                <div class="card-panel">
                                    <ul class="cardlist">
                                        @foreach ($selectedUsers['rejected'] as $user)
                                        <li><i class="material-icons red-text">close</i>{{$user}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif

                            @if (array_key_exists('pending',$selectedUsers))
                            <div class="col s12 m4">
                                <div class="card-panel">
                                    <ul class="cardlist">
                                        @foreach ($selectedUsers['pending'] as $user)
                                        <li><i class="material-icons orange-text">warning</i>{{$user}}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                            @endif
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







        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-ui.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/events.js') }}"></script>

<script>

$(function () {
disableMessage();      
       
        $("#event_start_date").datetimepicker({
        value:'{{App\Helpers\DateHelper::uiDateTime($event['event_start_date'])}}',
                format: 'm/d/Y H:i',
                });
        $("#event_end_date").datetimepicker({
        value:'{{App\Helpers\DateHelper::uiDateTime($event['event_end_date'])}}',
                format: 'm/d/Y H:i',
                });
        });

</script>


@endsection