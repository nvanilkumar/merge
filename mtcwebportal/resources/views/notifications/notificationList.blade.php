@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12 m4">
                                <h5 class="main-heading">{{$title}}</h5>
                            </div>

                            <div class="col s12 m8">
                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('NotificationController@createNotificationView')}}">Create Notification</a>
                            </div>
                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    <th width="65%">Notification Message </th>
                                    <th width="20%">Created  Date </th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>

                                @if(count($notifciationList) > 0)
                                @foreach ($notifciationList as $notifciation)
                                <tr>

                                    <td>{{$notifciation->message}} </td>
                                    <td>{{App\Helpers\DateHelper::uiDate($notifciation->created_at)}} </td>
                                    <td>

                                        <a href="{{url('/').'/notifications/view/'.$notifciation->notification_id}}">
                                            <i class="material-icons left">assignment</i>
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

<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

});
</script>    
@endsection
