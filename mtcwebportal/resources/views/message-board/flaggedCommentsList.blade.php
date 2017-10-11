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


                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">
                            <thead>
                                <tr>

                                    <th>Topic Title</th>
                                    <th width="35%">Comment Text</th>
                                    <th>Flagged By</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($topicList) > 0)
                                @foreach ($topicList as $topic)
                                <tr>

                                    <td>{{$topic->topic_title}} </td>
                                    <td id="commentText{{$topic->comment_id}}">{{$topic->comment_text}} </td>
                                    <td>{{$topic->first_name."".$topic->last_name}} </td>

                                    <td>
                                        <a href="#editModal"  data-commnet-id="{{$topic->comment_id}}" 
                                           data-topic-id="{{$topic->topic_id}}" 
                                           class="edit-link">
                                            <i class="material-icons left">edit</i>
                                        </a>

                                        <a href="#modal"  class="delete-link" 
                                           data-link-url="{{route('comments.deactivate', $topic->comment_id)."?&flagged_status=flagged"}}">
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
            <div class="col s12 m3"></div>
        </div>
    </div>
</div>

<div id="modal" class="modal alertModal">
    <div class="modal-content">
        <p><b>Delete Topic</b></p>
        <input type="hidden" id="delete-link-value" value=""/>
        <div>Are you sure you want to delete it?</div>
        <div class="right-align">
            <a class="waves-effect waves-light modal-action modal-close themeblue btn" id="model-ok">OK</a>
            <a href="javascript:void(0);" class="modal-action modal-close waves-effect waves-green grey lighten-1 btn">Cancel</a>
        </div>
    </div>
</div>

<div id="editModal" class="modal alertModal">
    <div class="modal-content">
        <p><b>Edit Comment</b></p>
        {{ Form::open(array('action' => 'CommentController@updateComment','id'=>'update_comment', 'name'=>'update_comment')) }}
        <input type="hidden" id="comment_id" value=""  name="comment_id"/>
        <input type="hidden" value="" name="topic_id" id ="topic_id"/>
        <input type="hidden" value="flagged" name="flagged_status"/>
        <textarea id="selectedCommentedText" name="comment_description"></textarea>

        <div >
            <input type="submit" name="submit" value="Send" class="themeblue waves-effect waves-light btn"/>

            <a href="javascript:void(0);" class="modal-action modal-close waves-effect waves-green grey lighten-1 btn">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

    $("#category").change(function () {

        var catId = $(this).val();
        window.location.href = mtcBaseUrl + "/topics/list/" + catId;
    });

    //edit link
    $(".edit-link, .approve-link").click(function () {
        var commentId = $(this).data("commnet-id");
        $("#comment_id").val(commentId);
        $("#topic_id").val($(this).data("topic-id"));
        $("#selectedCommentedText").html($("#commentText" + commentId).html());
    });

});
</script> 

@endsection