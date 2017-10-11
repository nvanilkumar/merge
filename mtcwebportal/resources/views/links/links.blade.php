@extends('layouts.dashboard')

@section('content')
<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m3"></div>
            <div class="col s12 m6">
                <div class="card">
                   @include ('layouts.partials._server_error')		




                    @if ( $link == "")
                    {{ Form::open(array('action' => 'LinkController@creatLinkView','id'=>'create_link')) }}
                    @else   
                    {{ Form::open(array('action' => 'LinkController@updateLink','id'=>'create_link')) }}
                    <input type="hidden" class="with-gap" name="link_id" id="link_id" value="{{$link['link_id']}}" >
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
                            <div class="col s12 m4">

                            </div>
                        </div> 

                        <div class="row">

                            <div class="input-field col s12 m12">
                                <input type="text" name="link_name" 
                                       id="link_name" class="validate">
                                <label for="link_name" class="">Link Name</label>
                            </div> 
                            <div class="input-field col s12 m12"  >
                                <input type="text" name="link_url" 
                                       id="link_url" class="validate">
                                <label for="link_url" class="">Link Url</label>
                            </div>


                        </div>

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('LinkController@getLinkList')}}">cancel</a>
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

<script>

    var linkDetails = <?php echo json_encode($link); ?>;
    disableMessage();

</script>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/links.js') }}"></script>



@endsection