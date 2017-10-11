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
                                   href="{{action('CategoryController@createCategoryView')}}">create Category</a>
                            </div>
                        </div>
                        <div class="row">
                            @include ('layouts.partials._notifications')
                        </div>

                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    <th>Category Name</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>
                                @if(count($categoryList) > 0)
                                @foreach ($categoryList as $category) 
                                <tr> 
                                    <td>{{$category->category_name}} </td>


                                    <td>

                                        <a href="{{url('/').'/categories/update/'.$category->category_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('categories.deactivate', $category->category_id)}}">
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
@include ('layouts.partials._delete_notification', ['title' => 'Category'])

<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

});
</script>    
@endsection
