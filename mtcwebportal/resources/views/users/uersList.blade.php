@extends('layouts.dashboard')

@section('content')
<div class="container min-height">
    <div class="section">
        <div class="row">

           @include ('layouts.partials._notifications')


            <div class="col s12 m12">

                <div class="card">

                    <div class="card-content">

                        <div class="row mar-b-0">
                            <div class="col s12 m4">
                                <h5 class="main-heading">{{$title}}</h5>
                            </div>



                            <div class="col s12 m8">
                                @if($type == "student")
                                <input type="button" class="themeblue waves-effect waves-light btn right"
                                       id="assign_users_button" value="Add To Group"> 
                                @endif

                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('UserController@createUserView')}}">Create User</a>
                            </div>
                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    <th></th>
                                    <th>Name </th>
                                    <th>User Name</th>
                                    <th>Email Id</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>

                                @if(count($usersList) > 0)
                                @foreach ($usersList as $user)
                                <tr>
                                    <td>
                                            <input type="checkbox" class="filled-in selectedUsers" 
                                                   value="{{$user->user_id}}" data-user-name="{{$user->first_name ." ".$user->last_name}}"  id="filled-in-box{{$user->user_id}}">
                                            <label for="filled-in-box{{$user->user_id}}"></label>


                                    </td>
                                    <td>{{$user->first_name ." ".$user->last_name}} </td>
                                    <td>{{$user->username}} </td>
                                    <td>{{$user->email}} </td>


                                    <td>

                                        <a href="{{url('/').'/users/update/'.$user->user_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('users.deactivate', $user->user_id)}}">
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

@include ('layouts.partials._delete_notification', ['title' => 'User'])				

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

    $("#assign_users_button").click(function () {
        selectdUsers();
    });

    @if (!session('status'))
        $("#card-alert").hide();
    @endif
});
</script>    
@endsection
