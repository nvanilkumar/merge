@extends('layouts.dashboard')

@section('content')
<div class="white">
    <div class="container">
        <div class="section">
            <div class="row">
                <div class="col s12 m12">
                    <h5 class="dashboard-heading">Dashboard</h5>
                    <p class="dashboard-content">Welcome <strong>{{Session::get("user_name")}}</strong>, to the dashboard of the MTC web portal. Use the "+" quick access menu to add a new group, user, event, survey or a link on the fly. Below. you can find  updates related to the message board, chats and other sections. Clicking on the corresponding links will lead you to the respective sections.</p>
                    <div class="dashboard-box">
                        <div class="col s12 m7 left-divider">
                            <h5 class="sub-heading">Message Board</h5>
                            <div class="col s6 m4 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">comment</i> <span class="bluecolor1">{{$details->topiccount}}</span></p>
                                <p class="box-tile-text">Topics</p>
                            </div>
                            <div class="col s6 m4 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">forum</i> <span class="bluecolor2">{{$details->activecomments}}</span></p>
                                <p class="box-tile-text">Comments</p>
                            </div>
                            <div class="col s6 m4 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">flag</i> <span class="bluecolor3">{{isset($details->reviewcomments)?
                                    $details->reviewcomments:0}}</span></p>
                                <p class="box-tile-text">Flagged Comments</p>
                            </div>
                        </div>
                        <div class="col s12 m5 pad-l-40">
                            <h5 class="sub-heading">Surveys</h5>
                            <div class="col s6 m6 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">check_circle</i> <span class="bluecolor4">{{$details->surveycount}}</span></p>
                                <p class="box-tile-text">Surveys</p>
                            </div>

                        </div>
                    </div>
                    <div class="dashboard-box2">
                        <div class="col s12 m12">
                            <h5 class="sub-heading">Other Updates</h5>
                            <div class="col s3 m2 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">group_work</i> <span class="bluecolor1">{{$details->groupcount}}</span></p>
                                <p class="box-tile-text">Groups</p>
                            </div>
                            <div class="col s6 m2 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">person</i> <span class="bluecolor2">{{$details->usercount}}</span></p>
                                <p class="box-tile-text">Users</p>
                            </div>
                            <div class="col s6 m2 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">event</i> <span class="bluecolor3">{{$details->eventcount}}</span></p>
                                <p class="box-tile-text">Events</p>
                            </div>
                          
                            <div class="col s6 m2 pad-l-0 pad-r-15">
                                <p class="box-icon-text"><i class="material-icons">link</i> <span class="bluecolor5">{{$details->linkcount}}</span></p>
                                <p class="box-tile-text">Links</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

