@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                    <div class="card-content">
                        <div class="row">
                            <div class="col s12 m6">

                                <h5 class="main-heading">{{$title}}</h5>

                            </div>



                            <div class="col s12 m6">

                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('GroupController@createGroupView')}}">create Group</a>
                            </div>
                        </div>
                        @include ('layouts.partials._notifications')
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    <th>Group Name</th>
                                    <th>Created By</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($groupList) > 0)
                                @foreach ($groupList as $group) 
                                <tr> 
                                    <td>{{$group->group_name}} </td>
                                    <td>{{$group->first_name ." ".$group->last_name}} </td>

                                    <td>

                                        <a href="{{url('/').'/groups/update/'.$group->group_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('groups.deactivate', $group->group_id)}}">
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
@include ('layouts.partials._delete_notification', ['title' => 'Group'])	

<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

});
</script>    
@endsection
