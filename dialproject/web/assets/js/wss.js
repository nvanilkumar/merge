if (connecttowss && 1==2)
{

    // Let us open a web socket
    var ws = new WebSocket("ws://192.168.36.37:2043");

    ws.onopen = function ()
    {
        // Web Socket is connected, send data using send()
        ws.send("Message to send");
        alert("Message is sent...");
    };

    ws.onmessage = function (evt)
    {
        var received_msg = evt.data;
        alert("Message is received...");
    };

    ws.onclose = function ()
    {
        // websocket is closed.
        alert("Connection is closed...");
    };


}
function getAgentStatus() {
    $.ajax({
        url: path_agent_status,
        type: "POST",
        data: "agentext=" + ext,
        success: function (response) {
            if (response.status == "success") {
                //console.log(response.response.astrisk_login);
                if (response.response.astrisk_login == 1)
                {
                    var st = '<div role="alert" class="alert alert-success"> <strong>Login Success!</strong> You have successfully logged in to Asterisk as agent. </div>';
                    $('#astrisk-status').html(st);
                }
                else {
                    var st = '<div role="alert" class="alert alert-danger"> <strong>Logout</strong> You have logout as agent. </div>';
                    $('#astrisk-status').html(st);
                }
            }
            else {
            }
        }
    });
}

//getAgentStatus();

//var interval1 = setInterval(getAgentStatus, 1000);