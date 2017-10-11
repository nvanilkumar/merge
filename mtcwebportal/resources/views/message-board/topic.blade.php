@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                    @include ('layouts.partials._server_error')

                   
                @if ( $topic == "")
                     {{ Form::open(array('action' => 'TopicController@createTopic','id'=>'create_topic')) }}
                 @else   
                    {{ Form::open(array('action' => 'TopicController@updateTopic','id'=>'create_topic')) }}
                     <input type="hidden" class="with-gap" name="topic_id" id="topic_id" value="{{$topic['topic_id']}}" >
                 @endif
                    <div class="card-content">
                        <h5 class="main-heading">{{$title}}</h5>
                         @if (session('status'))
                                <div class="alert alert-success">
                                    <div id="card-alert" class="card blue">
                                        <div class="card-content alertmsg white-text">

                                            {{ session('status') }}
                                        </div>
                                        <button type="button" class="close alertClose white-text" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">×</span>
                                        </button>
                                    </div>

                                </div>
                         @endif
                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Category Name<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <select name="category_id" id="category_id">
                                    <option value="" disabled selected>Select the category name</option>
                                    @if(count($categories) > 0)
                                        @foreach ($categories as $category)  
                                            <option value="{{$category->category_id}}">{{$category->category_name}}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Topic Title<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" class="validate" name="topic_title" id="topic_title">
                                <label for="topic_title">Topic Title</label>
                            </div>
                        </div>
                        <div class="row">
                            <div class="input-field col s12 m4">
                                Message<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">                              
                                <textarea id="topic_description" class="materialize-textarea" name="topic_description" id="topic_description"></textarea>
                                <label for="topic_description">Description</label>
                            </div>
                        </div>
                       
                         <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('TopicController@getTopicList')}}">cancel</a>
                                <input type="submit" name="done" value="done" class="themeblue waves-effect waves-light btn"/>

                                

                            </div>
                        </div>
                    </div>
                    {{ Form::close() }}
                </div>
            </div>
            <div class="col s12 m3"></div>
        </div>
    </div>
</div>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/topics.js') }}"></script>
<script>
     disableMessage();
    var topicDetails= <?php echo json_encode($topic); ?>;
    
</script>    


@endsection