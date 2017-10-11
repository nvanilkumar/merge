@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                  
                    <div class="card-content">
                        <h5 class="main-heading">{{$title}}</h5>
 

                        <div class="row mar-b-0">
                            <div class="col s12 m4">
                                Notification Message<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" class="validate" name='notification' id='event_name' readonly="readonly" value="{{$notifciationList[0]->message}}">
                                <label for="notification">Notification Message</label>
                            </div>
                        </div>

                      <div class="row">
                            @if ( count($notifciationList) > 0)
                            <div class="col s12 m4">
                                <div class="card-panel">
                                    <ul class="cardlist">

                                        @foreach ($notifciationList  as $user)
                                             @if ( strlen($user->first_name) > 0)
                                                <li><i class="material-icons green-text">done</i>{{$user->first_name." ".$user->last_name}}</li>
                                             @endif
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
                                   href="{{action('NotificationController@getNotifications')}}">BACK</a>
                                




                            </div>
                        </div>

                      

                
 

                    </div>

                  
                </div>


            </div>







        </div>
    </div>
</div>

 

 


@endsection