@extends('layouts.dashboard')
@section('content')  

<div class="container">
    <div class="section topics-main">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                    @if (count($errors) > 0)
                    <div class="form-group">
                        <div class="col-sm-10 col-sm-offset-1">
                            <div class="alert alert-danger">
                                <strong>Opsss !</strong> There is an error...<br /><br />
                                <ul>
                                    @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="card-content">
                        <h5 class="topic-heading">{{$topic[0]->topic_title}}</h5>
                        <p class="topic-desc">{{$topic[0]->topic_description }}</p>
                        <p class="topic-creat-info"><strong>Created</strong> {{App\Helpers\DateHelper::uiDateTime($topic[0]->topic_created_at) }}  
                            <strong>, Last updated</strong> {{App\Helpers\DateHelper::uiDateTime($topic[0]->topic_updated_at) }} 


                        </p>
                        <div class="topic-row">
                             <input type="button" name="addCommnet" id="addCommnet"
                                    value="Add Comment" class="themeblue waves-effect waves-light btn pull-right"/>

                        </div>
                        <div id="addCommentsDiv">
                            <div class="topic-head-strip pad-l-20 pad-r-20"></div>
                            {{ Form::open(array('action' => 'CommentController@createComment','id'=>'create_comment')) }}
                            <div class="row mar-t-20">
                                <div class="col s12 m2 center-align">
                                    Reply
                                </div>
                                <div class="col s12 m6">
                                    <textarea id="comment_description" name="comment_description" class="materialize-textarea"></textarea>
                                    <input type="hidden" value="{{$topic[0]->topic_id}}" name="topic_id"/>

                                </div>
                                <div >
                                    <input type="submit" name="reply" value="Reply" class="themeblue waves-effect waves-light btn"/>

                                    <a class="grey lighten-1 waves-effect waves-light btn" id="cancelButton"
                                       href="javascript:void(0);">cancel</a> 
                                </div>
                            </div>
                            {{ Form::close() }}
                        </div>


                        @foreach ($topic as $index => $value)
                        @if(strlen($value->comment_text)> 0)
                        <div class="topic-head-strip pad-l-20 pad-r-20">
                            <div class="left">{{$index+1}}</div>
                            <div class="right">Posted on  {{App\Helpers\DateHelper::uiDateTime($value->created_at) }} </div>
                        </div>
                        <div class="row mar-t-20">
                            <div class="col s12 m2                                 center-align">
                                <img src="{{ asset('/img/default-avatar.png') }}" alt="" class="circle responsive-img valign profile-image">
                                <p>{{$value->first_name." ".$value->last_name}} </p>
                            </div>
                            <div class="col s12 m10">
                                <p id="commentText{{$value->comment_id}}">{{$value->comment_text}}</p>
                                <p class="grey lighten-2 comment-box" 
                                   style="margin-top: 10px; margin-right: 35px; padding: 10px; border-radius: 2px;">


                                    @if($value->status =="review")
                                    This message has been flagged as inappropriate 
                                    <br>

                                    &nbsp;&nbsp;&nbsp;
                                    <a class="green-text edit-link" href="#editModal"
                                       data-commnet-id="{{$value->comment_id}}">Approve Comment</a>

                                    @endif
                                    <a href="#modal"  class="delete-link" 
                                       data-link-url="{{route('comments.deactivate', $value->comment_id)}}">
                                        Delete Comment</a>

                                    &nbsp;&nbsp;&nbsp;
                                    <a class="orange-text edit-link" href="#editModal" 
                                       data-commnet-id="{{$value->comment_id}}">Edit Comment</a>
                                </p>
                            </div>
                        </div>
                        @endif
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div id="modal" class="modal alertModal">
    <div class="modal-content">
        <p><b>Delete Comment</b></p>
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
        <input type="hidden" value="{{$topic[0]->topic_id}}" name="topic_id"/>

        <textarea id="selectedCommentedText" name="comment_description"></textarea>

        <div >
            <input type="submit" name="submit" value="Send" class="themeblue waves-effect waves-light btn"/>

            <a href="javascript:void(0);" class="modal-action modal-close waves-effect waves-green grey lighten-1 btn" id="editCommentCancel">Cancel</a>
        </div>

        {{ Form::close() }}

    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script>
$(document).ready(function () {

    //To disable the status Message
    disableMessage();

    //To Enable Model for delete Button
    $('.modal').modal();


    //delete link related code
    $('body').on('click', '.delete-link', function () {
        $("#delete-link-value").val($(this).data("link-url"));
    });

    $("#model-ok").on("click", function () {
        window.location.replace($("#delete-link-value").val());
    });


    //edit button click
    $(".edit-link, .approve-link").click(function () {
        var commentId = $(this).data("commnet-id");
        $("#comment_id").val(commentId);
        $("#selectedCommentedText").val($("#commentText" + commentId).html());
    });
    //Edit comment Related code
    $("#editCommentCancel").click(function () {
        $("#selectedCommentedText").val();
    });
    


    //Add comment Section 
    $("#addCommentsDiv").hide();
    $("#addCommnet").click(function () {
        $("#addCommentsDiv").show();
    });
    //Add comment cancel button
    $("#cancelButton").click(function () {
        document.getElementById('comment_description').value = ''; 
        $("#addCommentsDiv").hide();
    });
    



    //form validations

    $('#create_comment').validate({
        rules: {
            comment_description: {
                required: true
            }
        },
        messages: {
            comment_description: {
                required: "Please enter the comment text"
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });
    
        $('#update_comment').validate({
        rules: {
            comment_description: {
                required: true
            }
        },
        messages: {
            comment_description: {
                required: "Please enter the comment text"
            }
        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });

});
</script> 
@endsection

