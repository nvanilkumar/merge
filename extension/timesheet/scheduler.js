$(document).ready(function () {
    var userId = GetURLParameter('data');
    var timeSheetList = projectList = "";
    $.ajax({
        url: "http://localhost/timesheet/web/app_dev.php/api/v1/user/getList",
        type: "POST",
        data: "userId=" + userId,
        success: function (response)
        {
            if (response.status == "success")
            {
                projectList = response.projectList;
                timeSheetList = response.timeSheetList;
                init(timeSheetList, projectList);
            } else {
                init(timeSheetList, projectList);
            }

        }});
    
       $('.prev-day').on("click", function () {
            scheduler.updateView(new Date(2016,11,6));
             init2(timeSheetList, projectList);
        });
     $('.today-v').on("click", function () {
        
            scheduler.setCurrentView(new Date());
//             init2(timeSheetList, projectList);
        });


});

function GetURLParameter(sParam)
{
    var sPageURL = window.location.search.substring(1);
    var sURLVariables = sPageURL.split('&');
    for (var i = 0; i < sURLVariables.length; i++)
    {
        var sParameterName = sURLVariables[i].split('=');
        if (sParameterName[0] == sParam)
        {
            return sParameterName[1];
        }
    }
}

function init(timeSheetList, projectList) {
    scheduler.config.xml_date = "%Y-%m-%d %H:%i";
    scheduler.config.time_step = 30;
    scheduler.config.multi_day = true;
    scheduler.locale.labels.section_subject = "Subject";

    scheduler.config.limit_time_select = true;
    scheduler.config.details_on_dblclick = true;
    scheduler.config.details_on_create = true;



    //preparing the projects list
    var project = [{key: '', label: 'Select'}, ];
    for (var i = 0, l = projectList.length; i < l; i++) {
        obj = {};
        obj['key'] = projectList[i].project_id;
        obj['label'] = projectList[i].project_title;
        project.push(obj);
    }

//Event title
    scheduler.templates.event_text = function (start, end, ev) {
        var pr= project.filter(function(item) { 
            if(item.key == ev.subject ){
//                 console.log(ev.subject);
//                console.log(5555555);
//                console.log(item);
               return item.label;  
            }
             
        });
        console.log(pr);
        var title='Subject: ' + ev.text +" <br/>Project Title:"+pr[0].label;
        return title;
    };

    //preparing the todays tasks list
    var list = [];
    for (var i = 0, l = timeSheetList.length; i < l; i++) {
        obj = {};
        obj['start_date'] = new Date(timeSheetList[i].scheduler_start_time);
        obj['end_date'] = new Date(timeSheetList[i].scheduler_end_time);
        obj['text'] = timeSheetList[i].scheduler_description;
        obj['subject'] = timeSheetList[i].project_id;
        obj['scheduler_id'] = timeSheetList[i].scheduler_id;

        list.push(obj);
    }


    scheduler.config.lightbox.sections = [
        {name: "Description", height: 43, map_to: "text", type: "textarea", focus: true},
        {name: "Project", height: 20, type: "select", options: project, map_to: "subject"},
        {name: "time", height: 72, type: "time", map_to: "auto"},
        {name: "textarea", height: 72, type: "textarea", map_to: "scheduler_id"}

    ];

    scheduler.init('scheduler_here', new Date(), "day");
    if (list.length > 0) {
        scheduler.parse(list, "json");
    }


    scheduler.attachEvent("onEventSave", function (id, ev, is_new) {


        var userId = GetURLParameter('data');




        if (!ev.text) {
            alert("Text must not be empty");
            return false;
        }
        if (!ev.subject) {
            alert("Please select the project");
            return false;
        }
        if (ev.text.length < 20) {

            alert("Text too small");
            return false;
        }
        var sText, eText, schedulerId = 0;
        sText = timeToText(ev.start_date);
        eText = timeToText(ev.end_date);

        if (ev.scheduler_id > 0) {
            schedulerId = ev.scheduler_id;
        }
        $.ajax({
            url: "http://localhost/timesheet/web/app_dev.php/api/v1/user/insertTimeSheet",
            type: "POST",
            data: "userId=" + userId + "&projectId=" + ev.subject + "&description=" + ev.text + "&startTime=" + sText + "&endTime=" + eText + "&schedulerId=" + schedulerId,
            success: function (response)
            {
                if (response.status == "success")
                {
                    ev.scheduler_id = response.timesheet_number;
                    scheduler.getEvent(id).scheduler_id = response.timesheet_number;
                    scheduler.updateEvent(id);

                }

            }});


        return true;
    });


    scheduler.attachEvent("onLightbox", function (id) {
        $(".dhx_cal_larea").css({'height': 'auto'});
        $(".dhx_cal_larea .dhx_wrap_section").siblings().eq(3).hide();
    });


}


function timeToText(dateText) {
    var startData = dateText;
    var day = startData.getDate();
    var month = startData.getMonth() + 1;
    var year = startData.getFullYear();


    return starDateText = year + "-" + month + "-" + day + " " + startData.getHours() + ":" + startData.getMinutes() + ":" + startData.getSeconds();
}


function init2(timeSheetList, projectList) {
 
 
    //preparing the todays tasks list
    var list = [];
    for (var i = 0, l = timeSheetList.length; i < l; i++) {
        obj = {};
        obj['start_date'] = new Date(timeSheetList[i].scheduler_start_time);
        obj['end_date'] = new Date(timeSheetList[i].scheduler_end_time);
        obj['text'] = timeSheetList[i].scheduler_description;
        obj['subject'] = timeSheetList[i].project_id;
        obj['scheduler_id'] = timeSheetList[i].scheduler_id;

        list.push(obj);
    }
 
 console.log(3333);
//    scheduler.init('scheduler_here', new Date(), "day");
    if (list.length > 0) {
         console.log(444);
       
    }

 

}


