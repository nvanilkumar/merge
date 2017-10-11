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
                            <h5 class="col s5 main-heading">{{$title}}</h5> 
                            <div class="col s4">
                                <select id="category" name="category">
                                    @if(count($categories) > 0)
                                    @foreach  ($categories as  $category)  
                                    {{$selected=""}}
                                    @if ($category_id == $category->category_id)
                                    {{ $selected= 'selected="select"' }}
                                    @endif
                                    <option value="{{$category->category_id}}" {{ $selected }}>{{$category->category_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div> 
                            <div class="col s3 right-align">
                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('TopicController@createTopicView')}}">Create Topic</a>
                            </div>
                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">
                            <thead>
                                <tr>
                                    <th width="30%">Topic Title</th>
                                    <th width="55%">Topic Description</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($topicList) > 0)
                                @foreach ($topicList as $topic)
                                <tr>

                                    <td>{{$topic->topic_title}} </td>
                                    <td>{{$topic->topic_description}} </td>




                                    <td>

                                        <a href="{{url('/').'/topics/update/'.$topic->topic_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('topics.deactivate', $topic->topic_id)}}">
                                            <i class="material-icons left">delete</i>
                                        </a>
                                        <a href="{{url('/').'/topics/details/'.$topic->topic_id}}">
                                            <i class="material-icons left">comment</i>
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
            <div class="col s12 m3"></div>
        </div>
    </div>
</div>
@include ('layouts.partials._delete_notification', ['title' => 'Topic'])

<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

    $("#category").change(function () {

        var catId = $(this).val();
        window.location.href = mtcBaseUrl + "/topics/list/" + catId;
    });

});
</script> 

@endsection