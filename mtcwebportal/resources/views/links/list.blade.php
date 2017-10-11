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
                                <input type="button" class="themeblue waves-effect waves-light btn right"
                                       id="change_order" value="Change Link Order"> 
                                <input type="button" class="themeblue waves-effect waves-light btn right" 
                                       id="save_order" value="Save Link Order">
                                <input type="button" class="themeblue waves-effect waves-light btn right" 
                                       id="cancel_order" value="Cancel">
                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('LinkController@creatLinkView')}}">create link</a>
                            </div>
                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    <th>Link Name</th>
                                    <th width="50%">Link Url</th>
                                    <th>Link Order</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($linkList) > 0)
                                @foreach ($linkList as $link)
                                <tr>
                                    <td>{{$link->link_name}} </td>
                                    <td>{{$link->link_url}} </td>

                                    <td> 
                                        <span class="link_position" data-link-id="{{$link->link_id}}">{{$link->menu_position}}</span>
                                    </td>
                                    <td>

                                        <a href="{{url('/').'/links/update/'.$link->link_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('links.deactivate', $link->link_id)}}">
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
@include ('layouts.partials._delete_notification', ['title' => 'Link'])	

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/links.js') }}"></script>
<script>
$(document).ready(function () {
    listInitialization();
     
    @if (!session('status'))
        $("#card-alert").hide();
    @endif 
        
});
</script>    
@endsection
