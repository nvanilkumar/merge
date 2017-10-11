@extends('layouts.dashboard')

@section('content')

<div class="chat">

    <div class="container">
        <div class=" section">
            <div class="row">
                <div id="contact-list" class="col s10 m4 l4 z-depth-1">

                    <div class="card-panel-left msg_container_base">
                        <ul class="collection height-400">

                            @if( isset($usersList) && count($usersList) > 0)
                            @foreach ($usersList as $index => $user)
                            <li class="collection-item avatar waves-effect waves-block waves-light {{($index == 0)?'chat-user-select':'' }}" 
                                data-user-id='{{$user->user_id}}'>
                                <span class="circle red darken-1">{{strtoupper(substr($user->first_name,0,1))}}</span>
                                <span class="contact-title">{{$user->first_name." ".$user->last_name}}</span>
                                <span class="inactive-user"></span>
                            </li>          
                            @endforeach
                            @endif


                        </ul>
                    </div>
                </div>
                <div class="col s12 m7 l8 card-panel fixed-height height-400" style="padding: 0px!Important;">
                    <div class="panel panel-default">

                        <div class="row send-wrap">
                            <div class="send-to">
                                <ul class="collection height-400">
                                    @if( isset($usersList) && count($usersList) > 0)
                                    <li class="collection-item avatar waves-effect waves-block waves-light selected" id="select-usert-text">
                                        <span class="circle red darken-1">{{substr($usersList[0]->first_name,0,1)}}</span>
                                        <span class="contact-title">{{$usersList[0]->first_name." ".$usersList[0]->last_name}}</span>
                                    </li>
                                    @endif

                                </ul>
                            </div>
                        </div>

                        <div class="panel-body msg_container_base hieght-350" id="messagePanel">





                        </div>

                        <div class="row send-wrap">
                            <div class="send-message">
                                <div class="message-text">
                                    <textarea class="no-resize-bar form-control" 
                                              rows="2" 
                                              id="msgText"
                                              placeholder="Write a message..."></textarea>
                                </div>
                                <div class="send-button">
                                    <input id="sendMsg" type="button" class="themeblue waves-effect waves-light btn right" value="send"/>

                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

    </div>
</div>
@if( isset($usersList) && count($usersList) > 0)
    <input type="hidden" name="selected-user-channel" value="user-id-{{$usersList[0]->user_id}}" id="selected-user-channel"/>
@endif

<script type="text/javascript" src="{{ asset('/js/pubnub.4.0.11.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/moment.js') }}"></script>
<script type="text/javascript" src="{{ asset('/js/livestamp.min.js') }}"></script>
<script>

$(function () {
    //On User Select add select class and show history
    $(".collection-item").click(function () {

        $(".collection-item").removeClass('chat-user-select');
        var val = "user-id-" + $(this).data("user-id");
        $("#selected-user-channel").val(val);
        $(this).addClass('chat-user-select');
        //Show the Selected user details
        $("#select-usert-text").children().eq(0).html($(this).children().eq(0).html());
        $("#select-usert-text").children().eq(1).html($(this).children().eq(1).html());

        //Empty the Message Area Append New user related History
        $("#messagePanel").empty();
        displyaChatHistory();


    });



});


var pubnub, myLastMessage, theirLastMessage;
var userId = '{{Session::get("user_name")."-".Session::get("user_id")}}';
var channelName = '{{"user-id-".Session::get("user_id")}}',
        userChannelName = $("#selected-user-channel").val();
var chatHistory = [];
bindUiEvents();
initPubNub(channelName);

/**
 * PUB NUb intialization
 * @type String
 */
function initPubNub(channelName) {
    pubnub = new PubNub({
        "publishKey": "{{ env('PUBNUB_PUBLISHKEY') }}",
        "subscribeKey": "{{ env('PUBNUB_SUBSCRIBEKEY') }}",
        "uuid": userId
    });

    pubnub.addListener({
        presence: function (presenceEvent) {
            getPresence();
        }
    });

    //unsubscribe as user leaves the page
    window.onbeforeunload = function () {
        pubnub.unsubscribeAll();
    };

    pubnub.hereNow({
        "channels": [channelName],
        state: true,

    }, function (status, response)
    {
        if (typeof response == 'undefined')
            return;
        if (response.totalOccupancy > 1)
            return;

        pubnub.subscribe({
            "channels": [channelName],
            "withPresence": true,

        });
        var pnMessageHandlers = {
            '{{"user-id-".Session::get("user_id")}}': writeMessageHtml,
            '{{"user-id-".Session::get("user_id")}}-receipts': readMessageHtml
        };
        pubnub.addListener({
            "message": function (m) {
                pnMessageHandlers[m.subscribedChannel](m.message);
            }
        });

        //write messages
        showHistory();
    });
}

function displyaChatHistory()
{
    chatHistory.forEach(function (messageObject) {
        writeMessageHtml(messageObject,'false');
    });
}

function showHistory()
{

    pubnub.history({
        "channel": channelName,
        reverse: true,
    },
            function (status, response) {
                if (!response.messages)
                    return;
//                console.log(response);
//                chatHistory = response.messages;
                response.messages.forEach(function (messageObject) {
                    chatHistory.push(messageObject.entry);
                    writeMessageHtml(messageObject.entry,'false');
                });
            });
            
}
function bindUiEvents() {
    var textbox = document.getElementById("msgText");
    var sendButton = document.getElementById("sendMsg");

    sendButton.addEventListener("click", function () {
        var selectUserChannelName = $("#selected-user-channel").val();
        var messageText = textbox.value;
        if (messageText.length > 0) {
            publishMessage(textbox.value, selectUserChannelName);
            textbox.value = "";
        }
    });

}

//create message element and add it to the page
function writeMessageHtml(message,type= true) {

    var selectedChannel = $("#selected-user-channel").val();

    if ((selectedChannel != message.rc && selectedChannel != message.sc)) {
        
        //Only New Chat messages are pushing to the history object
        if(type == true)
        { 
            chatHistory.push(message);
        }
        
        return true;
    }
 
    var who = "";
    var timeDiff = '<time data-livestamp="' + message.timestamp + '"></time>';

    if (message.sc === channelName) {
        who = "Me";

        var meMessageDiv = '<div class="row msg_container base_sent">\
                                <div class="col s8">\
                                    <div class="messages msg_sent">\
                                        <p id="' + message.id + '">' + who + ": " + message.message + '</p>' + timeDiff + '\
                                    </div>\
                                </div>\
                            </div>';
        messageDiv = meMessageDiv;

    } else {
        who = getUserName(message.sc);
        var othersMessageDiv = ' <div class="row msg_container base_receive">\
                                <div class="col s8">\
                                    <div class="messages msg_receive">\
                                       <p id="' + message.id + '">' + who + ": " + message.message + '</p>' + timeDiff + '\
                                    </div>\
                                </div>\
                            </div>';
        messageDiv = othersMessageDiv;
    }

    $("#messagePanel").append(messageDiv);
    var d = $("#messagePanel");
    d.scrollTop(d.prop("scrollHeight"));


}
function readMessageHtml(message) {
    //remove existing read UI
    var read = document.getElementById("readNotification");
    read && read.id !== message.lastSeen && message.userId !== userId ? read.remove() : null;
    //update read UI
    read = document.createElement("span");
    read.id = "readNotification";
    read.innerHTML = " ** Last Read **";
    var messageElement = document.getElementById(myLastMessage);
    messageElement && message.userId !== userId ? messageElement.appendChild(read) : null;
}

function publishMessage(message, userChannel) {

    myLastMessage = PubNub.generateUUID();
    var nowDate = moment().unix();
    var createMessage = {
        "message": message,
        "id": myLastMessage,
        rc: userChannel,
        sc: channelName,
        type: 0,
        timestamp: nowDate

    };

    chatHistory.push(createMessage);
    pubnub.publish({
        "channel": channelName,
        "message": createMessage
    });

    //Send message to selected user channel & and push notification
    pubnub.publish({
        "channel": userChannel,
        "message": {
            "message": message,
            "id": myLastMessage,
            rc: userChannel,
            sc: channelName,
            type: 0,
            timestamp: nowDate,
            "pn_apns": {
                "aps": {
                    "alert": message,
                    "badge": 2,
                    "sound": "melody"
                },
                "c": "3"
            },
            "pn_gcm": {
                "data": {
                    msg: message,
                    type: 'chat'
                }
            },

        }
    });

}

function onMessageRead(messageId, userId) {
    pubnub.publish({
        "channel": channelName,
        "message": {
            "lastSeen": messageId,
            "userId": userId
        }
    });
}

function getUserName(channelName) {
    var compareId = channelName.replace("user-id-", "");

    var usersLi = $(".collection-item");
    var name = "";
    $(usersLi).each(function (index) {
        if ($(this).data("user-id") == compareId) {
            name = $(this).children().eq(1).html();
        }
    });
    return name;
}


/**
 * To Get the user presence
 * @returns {undefined}
 */
function getPresence() {

    var userChannelsList = getUserChannels();

    var activeChannels = [];
    pubnub.hereNow(
            {
                channels: userChannelsList,
            },
            function (status, response) {
                if (response && response.channels)
                {
                    activeChannels = response.channels;
                    setActivePresence(activeChannels);
                }

            }
    );

}

/**
 * TO bRING THE ALL CHANNEL NAMES
 * @returns {Array|getUserChannels.userChannelsList}
 */
function getUserChannels()
{
    var users = $(".collection-item");
    var userChannelsList = [];
    $(users).each(function (value) {
        if ($(this).data("user-id")) {
            userChannelsList.push("user-id-" + $(this).data("user-id"));
        }

    });

    return userChannelsList;
}

/**
 * To set the active flag to users list
 * @type window.$|$
 */
function setActivePresence(activeChannels) {
    $.each(activeChannels, function (index, value) {
        var channelName = value.name;
        var userId = channelName.replace("user-id-", "");
        $('li[data-user-id="' + userId + '"]').children().eq(2).addClass('active-user').removeClass('inactive-user');
    });
}
</script>
@endsection