@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            @include ('layouts.partials._notifications')

            <div class="col s12 m12">

                <div class="card">

                    <div class="card-content">

                        <div class="row">
                            <div class="col s12 m4">
                                <h5 class="main-heading">{{$title}}</h5>
                            </div>



                            <div class="col s12 m8">
 

                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('EventController@createEventView')}}">Create Event</a>
                            </div>
                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    
                                    <th>Event Name </th>
                                   
                                    <th>Event Start Date</th>
                                    <th>Event End Date</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>

                                @if(count($eventsList) > 0)
                                @foreach ($eventsList as $event)
                                <tr>
                                    
                                    <td width="60%">{{$event->event_name}} </td>
                                 
                                    <td>
                                        {{App\Helpers\DateHelper::uiDateTime($event->event_start_date)}}
                                    </td>
                                    <td>
                                        {{App\Helpers\DateHelper::uiDateTime($event->event_end_date)}}
                                    </td>


                                    <td>

                                        <a href="{{url('/').'/events/update/'.$event->event_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('events.deactivate', $event->event_id)}}">
                                            <i class="material-icons left">delete</i>
                                        </a>

                                    </td>
                                </tr>

                                @endforeach
                                @endif

                            </tbody>
                        </table>

                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

@include ('layouts.partials._delete_notification', ['title' => 'Event'])				
<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

});
</script>    
@endsection
