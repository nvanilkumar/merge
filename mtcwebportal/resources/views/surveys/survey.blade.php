@extends('layouts.dashboard')

@section('content')

<div class="container">
    <div class="section">
        <div class="row">
            <div class="col s12 m12">
                <div class="card">
                   @include ('layouts.partials._server_error')	

                    {{ Form::open(array('action' => 'SurveyController@createSurvey','id'=>'create_survey')) }}
                    <div class="card-content">
                        <h5 class="main-heading">{{$title}}</h5>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Survey Name<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" class="validate" name='survey_name' id='survey_name'>
                                <label for="survey_name">Survey Name</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Survey Description<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <textarea id="textarea1" class="materialize-textarea" name="survey_description" 
                                          id="survey_description"></textarea>
                                <label for="survey_description">Survey Description</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Survey Code<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m8">
                                <input type="text" class="validate" name='survey_code' id='survey_code'>
                                <label for="survey_code">Survey Code</label>
                            </div>
                        </div>

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Survey Dates<span class="red-text">*</span>
                            </div>
                            <div class="input-field col s12 m3">
                                <input type="text"  id="survey_start_date" name="survey_start_date">
                                <label for="survey_start_date" class="">Survey Start Date</label>
                            </div>
                            <div class="input-field col s12 m1"> to</div>
                            <div class="input-field col s12 m3">
                                <input type="text" id="survey_end_date" name="survey_end_date">
                                <label for="survey_end_date" class="">Survey End Date</label>
                            </div>
                        </div> 

                        <div class="row mar-b-0">
                            <div class="input-field col s12 m4">
                                Select Groups to add to this survey
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple id="group_list" name="group_list[]">
                                    <option value="" disabled selected>Select the group name</option>
                                    @if(count($groupList) > 0)
                                    @foreach ($groupList as $group)  
                                    <option value="{{$group->group_id}}">{{$group->group_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div> 
                        <div class="row">
                            <div class="input-field col s12 m4">
                                Select Users to add to this survey
                            </div>
                            <div class="input-field col s12 m8">
                                <select multiple name="users_list[]" id="users_list">
                                    <option value="" disabled selected>Select the user name</option>

                                    @if(count($usersList) > 0)
                                    @foreach ($usersList as $user)  
                                    <option value="{{$user->user_id}}">{{$user->first_name." ".$user->last_name}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div> 

                        <div class="row">
                            <div class="col s12 m2">
                            </div>
                            <div class="col s12 m10 right-align">
                                <a class="grey lighten-1 waves-effect waves-light btn" 
                                   href="{{action('SurveyController@getSurveyList')}}">cancel</a>
                                <input type="submit" name="done" value="done" 
                                       class="themeblue waves-effect waves-light btn"/>



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
<script type="text/javascript" src="{{ asset('/js/jquery.datetimepicker.full.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/jquery-ui.js') }}"></script>

<script>



$(function () {

    //date validations
    $.validator.addMethod("checkGraterDate", function (value, element) {

        if ($.datepicker.parseDate("mm/dd/yy", $("#survey_start_date").val()) <= $.datepicker.parseDate("mm/dd/yy", value)) {
            return true;
        }

        return false;

    }, "survey end date should be greater than the survey start date ");

    $('#create_survey').validate({
        rules: {
            survey_name: {
                required: true

            },
            survey_description: {
                required: true,
            },
            survey_code: {
                required: true,
            },
            survey_start_date: {
                required: true,

            },
            survey_end_date: {
                required: true,
                checkGraterDate: true

            }
        },
        messages: {
            survey_name: {
                required: "Please enter the survey name"
            },
            survey_description: {
                required: "Please enter the survey description",

            },
            survey_code: {
                required: "Please enter the survey code ",

            },
            survey_start_date: {
                required: "Please Select the survey start date"
            },
            survey_end_date: {
                required: "Please Select the survey end date"
            }

        },
        errorElement: "div",
        errorPlacement: function (error, element) {
            $(element).addClass("errorTxt2");
            error.appendTo(element.parent());
        }
    });

    $("#survey_start_date, #survey_end_date").datetimepicker({
        minDate: 0,
        format: 'm/d/Y',
        timepicker: false
    });
 


});



</script>


@endsection