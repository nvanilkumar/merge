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
 

                                <a class="themeblue waves-effect waves-light btn right" 
                                   href="{{action('SurveyController@createSurveyView')}}">Create Survey</a>
                            </div>
                        </div>
                        <table id="data-table" class="striped demo1 floatThead-table" width="100%">

                            <thead>
                                <tr>
                                    
                                    <th>Survey Name </th>
                                    <th>Survey Code </th>
                                   
                                    <th>Survey Start Date</th>
                                    <th>Survey End Date</th>
                                    <th>Options</th>
                                </tr>
                            </thead>

                            <tbody>

                                @if(count($surveyList) > 0)
                                @foreach ($surveyList as $survey)
                                <tr>
                                    
                                    <td>{{$survey->survey_name}} </td>
                                    <td>{{$survey->survey_code}} </td>
                                 
                                    <td>
                                        {{App\Helpers\DateHelper::uiDate($survey->survey_start_date)}}
                                    </td>
                                    <td>
                                        {{App\Helpers\DateHelper::uiDate($survey->survey_end_date)}}
                                    </td>


                                    <td>

                                        <a href="{{url('/').'/surveys/update/'.$survey->survey_id}}">
                                            <i class="material-icons left">edit</i>
                                        </a>
                                        <a href="#modal"  class="delete-link" data-link-url="{{route('surveys.deactivate', $survey->survey_id)}}">
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

@include ('layouts.partials._delete_notification', ['title' => 'Survey'])
<script type="text/javascript" src="{{ asset('/js/jquery.dataTables.min.js') }}"></script>

<script>
$(document).ready(function () {
    listInitialization();

});
</script>    
@endsection
