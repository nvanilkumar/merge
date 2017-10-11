@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col m2">

            </div>
            <div class="col s12 m8">
                <div class="card">
                    @include ('layouts.partials._server_error')

                    @if ( $category == "")
                    {{ Form::open(array('action' => 'CategoryController@createCategory','id'=>'create_category')) }}
                    @else   
                    {{ Form::open(array('action' => 'CategoryController@updateCategory','id'=>'create_category')) }}
                    <input type="hidden" class="with-gap" name="category_id" value="{{$category['category_id']}}" >

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

                        <div class="row">
                            <div class="input-field col s12 m3">
                                Category Name<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m9">
                                <input type="text" class="validate" 
                                       name="category_name" id="category_name">

                            </div>
                        </div>

                     

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                 <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('CategoryController@getCategoryList')}}">cancel</a>
                                <input type="submit" name="done" value="done" class="themeblue waves-effect waves-light btn"/>
                            </div>
                        </div>

                    </div>

                    {{ Form::close() }}
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    var categoryDetails = <?php echo json_encode($category); ?>;
    disableMessage();
</script>

<script type="text/javascript" src="{{ asset('/js/jquery.validate.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/category.js') }}"></script>

@endsection