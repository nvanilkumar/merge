<!--important-->
<style type="text/css">
.ApiDevelopers_Container { font-size:16px;}
.ApiDevelopers_Container h4{ color: #252525; margin:25px 0 10px 0; font-size: 22px; font-weight: 600;}
.ApiDevelopers_Container h5{ color: #252525; margin:25px 0 0px 0; font-size: 18px; font-weight: 600;}
.ApiDevelopers_Container p { font-size:16px; padding:8px 20px 10px 0px; }
.ApiDevelopers_Container p.tcpad { font-size:16px;padding:10px 20px 10px 30px;  }
ul.apilistitems { margin-left: 20px; margin-top:0; margin-bottom: 10px; float: left; width: 100%; padding:0px 0 10px 10px; }
ul.apilistitems li{ list-style-type:decimal; width: auto; padding:3px 0 10px 0; margin-left: 15px; }
ul.apilistitems li > ul{ list-style-type:decimal; width: auto; padding:3px 0 10px 0; margin:10px 0 0px 40px; }
ul.apilistitems li > ul li{ list-style-type:lower-alpha; width: auto; padding:8px 0 5px 0; }
.lastmargin {margin-bottom: 50px;}

.tandc_heading_1 { }
.tandc_center {text-align: center;}

.ApiDevelopers { background: #ebeced; }
.ApiDevelopers .container .nav-tabs { border-bottom: none; }
.ApiDevelopers .container .nav { border: none !important; margin-top: 22px; margin-left: 54px;}
.ApiDevelopers .container .nav>li {/*width: 230px;*/ width: 33%; margin: 0; padding: 10px 80px 0 0; }
.ApiDevelopers .container .nav>li>a {color: #595959; font-size: 25px; text-align: center; font-weight: 500; text-transform: capitalize; margin: 0; padding: 0 0 10px 0; line-height: normal; border-bottom: 4px solid #ebeced; }
.ApiDevelopers .container .nav-tabs>li.active>a, .ApiDevelopers .container.nav-tabs>li.active>a:hover, .ApiDevelopers .container .nav-tabs>li.active>a:focus {color: #9036cd; cursor: default; background: none !important; border: none; border-bottom: 4px solid #9036cd; font-size: 25px; text-align: center; font-weight: 500; text-transform: capitalize; padding: 0 0 10px 0; }
.ApiDevelopers .container .nav>li>a:hover {color: #9036cd; border-bottom: 4px solid #9036cd; padding: 0 0 10px 0; }

#apilibraries .panel-heading {padding:5px;}
#apilibraries h4.panel-title { margin: 5px 10px;}
#apilibraries h4.panel-title a { font-size:18px; display: inherit;}
#apilibraries .panel-body p {font-size: 14px;}


#apilibraries table {float: left; width: 100%; border: 1px solid #e1e1e1; border-spacing: 0; width: 100%; }
#apilibraries .tabHeading {   font-size: 15px; margin: 25px 0 10px 0; float: left; width: 100%; } #apilibraries table td {border: 1px solid #ccc; font-family: "Open Sans",sans-serif; color: #333; font-size: 13px; color: #333; font-size: 14px; padding: 10px 10px; }
#apilibraries .starmark {color: red; font-size: 12px; }
#apilibraries th {background: #ccc none repeat scroll 0 0; color: #000; font-family: "Open Sans",sans-serif; padding: 4px 5px; font-size: 15px; }
</style>
<?php $siteUrl= site_url(); ?>
<div class="page-container">
<div class="wrap">


<div class="ApiDevelopers">

<div class="container">
    <ul class="nav nav-tabs" role="tablist" id="myTab">
        <li role="presentation" class="active"><a href="#getstarted" aria-controls="getstarted" role="tab" data-toggle="tab" aria-expanded="false">Get Started</a></li>
        <li role="presentation" class=""><a href="#getapiaccess" aria-controls="getapiaccess" role="tab" data-toggle="tab" aria-expanded="true">Get API Access</a></li>
        <li role="presentation" class=""><a href="#apilibraries" aria-controls="apilibraries" role="tab" data-toggle="tab" aria-expanded="true">API & Libraries</a></li>       
    </ul>
</div>

</div>




  <div class="container newsContainer ApiDevelopers_Container tab-content faq_block">

<!--Get Started Start-->
<div role="tabpanel" class="tab-pane active" id="getstarted"> 
  <div class="row">   
    
    <div class="col-xs-12 col-sm-12 col-lg-12">
 
<h5>Introduction</h5>

<p>MeraEvents.com is a leading technology & solution platform that serves event organisers for event ticketing, registration, merchandise selling, and event management.</p>


<h5>Where to find API Access?</h5>

<p>After login to MeraEvents <?php echo $siteUrl; ?>login go to ' Developers' (Under login user name).
</p>

<h5>What are the access rights?</h5>

<p>As of now, we offer both read and write access to MeraEvents account holder for their data only. As an meraevent apps developer, we provide OAuth2 authentication, using which MeraEvents user can give access to your app for their data. We ensure our users data is 100% safe and it is only given access to those applications they decide to give access via OAuth2.</p>



<h5>Get Started</h5>
<p>MeraEvents has a modular architecture with several application serving respective services, e.g. the account application works like a gatekeeper for the account & authentication whereas event management application provide services for ticketing, registration, donation and rsvp event & management. Therefore your API use will have different MeraEvents URL depending on the services you are accessing.</p>


<h5>Create your account</h5>

<p>MeraEvents API is open to all. To create your account, go to <?php echo $siteUrl; ?>signup.</p>
 

<p>&nbsp;</p>
<p>&nbsp;</p>
    </div><!--col end-->
  </div><!--row end-->
</div><!--Tab content 1 end-->
<!--Get Started End-->


<!--Get API Access Start-->


<div role="tabpanel" class="tab-pane" id="getapiaccess"> 
  <div class="row">   
    
    <div class="col-xs-12 col-sm-12 col-lg-12">
   
    <h5>Get API Access</h5>

<p>After login to MeraEvents <?php echo $siteUrl; ?>Login, go to Developer tab (userName/Developers,Logout). Meraevents supports OAuth2 specification to enable access to APIs. MeraEvents provides 2 ways to authenticate & access.</p>

<h5>User Access</h5>

<p>Mera Events users can use this approach to directly connect and use MeraEvents API.You need ‘Access Token”, get your access token from 'Developers'</p>

<h5>App Access</h5>

<p>As an Apps developer or user wants to access API, you have to first register your application with MeraEvents. Go to ‘Developers’ page and create an application as shown in the following diagram.</p> 

<!--<p><img src="images/static/create_api.jpg" /></p>-->
 <p>You will get Application Client Id and Client secret. Save it for future use </p>
<!--<p><img src="images/static/update_api.jpg" /></p>-->

<h5>Application Integration</h5>

 <p>The following steps explain how to integrate MeraEvents OAuth2 with your application.</p>

<p><b>1. User Authorization (Get User authorize code)</b></p>

<p>In your application, show a button called 'Login with MeraEvents' and send the request to '<?php echo $siteUrl; ?>developers/client_authorize.php?&response_type=code&client_id=yourAppClientId&redirect_url=yourRedirectURL' The above request is to authorize user or to get user’s authorization code. </p>


<!--<p><img src="images/static/authorize_api.jpg" /></p>-->

 <p>After user’s successful authorization, it will return authorize code to your registered callback URL.</p>

<p><b>2.Get access token for authorized code.</b></p>

<p>To get the access token for the authorize code. You have to send POST request to the URL : '<?php echo $siteUrl; ?>developers/token.php' with following parameters.
<ul class="apilistitems">
<li>client_id : App client id</li>
<li>client_secret: app client secret</li>
<li>grant_type : authorization_code</li>
<li>code : authorization code</li>
</ul>
</p>
<p><b>Note: only POST method is allowed. </b></p>

<p><b>Example: </b><br>
 $request_string = 'client_id=appClientId&client_secret=appClientSecret&grant_type=authorization_code&code=userAuthorizeCode'; $url= http://www.meraevents.com/developers/token.php;</p>
<p>$ch= curl_init($url) ;<br>
curl_setopt($ch, CURLOPT_POSTFIELDS,$request_string);<br>
curl_setopt($ch, CURLOPT_HEADER, 0);<br>
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);<br>
curl_setopt($ch, CURLOPT_TIMEOUT,30);<br>
curl_setopt($ch, CURLOPT_POST, 1);<br>
$response = curl_exec($ch);<br>
$response_data= json_decode(urldecode($response),true); 
</p>


<p>The above $response_data contains the token. With the token, you can now call any MeraEvents API methods. The token will be valid for 3 months; in future following the same process, user could give you access to their MeraEvents data. </p>


<p><b>To access MeraEvents API you need access token.</b></p>

<p><b>NOTE :</b> Additional request headers are required when using access_tokens to use MeraEvents API: “access_token: YOUR_ACCESS_TOKEN_HERE“. </p>


<p>&nbsp;</p>
<p>&nbsp;</p>
<!-- <p class="lastmargin">If you have any questions about events/registration/passes/tickets email us at <a href="mailto:support@meraevents.com"><b>support@meraevents.com</b></a> or call us on <b>+91-9396555888, Monday through Friday 9am - 7pm.</b></p> -->

  

    </div>
  </div>
</div><!--Get API Access end-->

 

 <!--Api & Libraries Start-->
<div role="tabpanel" class="tab-pane" id="apilibraries"> 
  <div class="row">   
    
    <div class="col-xs-12 col-sm-12 col-lg-12">
  <div class="bs-example">
          <div class="panel-group" id="accordion">

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOne" class="collapsed" aria-expanded="false">Create Event</a>
                </h4>
              </div>
              <div id="collapseOne" class="panel-collapse collapse" aria-expanded="false" style="height: 0px;">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
      <div class="subcontent" id="validation t1">
                            <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                            
                            
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <colgroup>
                                    <col width="25%" class="con0">
                                    <col width="75%" class="con0">
                                </colgroup>

                                <thead><tr><th class="head0">Parameter Name</th><th class="head0">Parameter Value</th></tr></thead>

                                <tbody>

<!--                                    <tr><td>eventType (<span class="starmark">*</span>)</td><td>paidevent / freeevent / noregevent</td></tr>-->
                                    <tr><td>eventTitle (<span class="starmark">*</span>)</td><td>Event Title (between 5 and 255 charecters)</td></tr>
                                    <tr><td>eventURL</td><td>preferred Event URL</td></tr>
                                    <tr><td>eventStartDate (<span class="starmark">*</span>)</td><td>mm/dd/yyyy</td></tr>
                                    <tr><td>eventStartTime (<span class="starmark">*</span>)</td><td>hh:mm (AM / PM)  </td></tr>
                                    <tr><td>eventEndDate (<span class="starmark">*</span>)</td><td>mm/dd/yyyy</td></tr>
                                    <tr><td>eventEndTime (<span class="starmark">*</span>)</td><td>hh:mm (AM / PM)</td></tr>
                                    <tr><td>eventDescription (<span class="starmark">*</span>)</td><td>Event Description (must be above 50 charecters)</td></tr>
                                    <tr><td>isPrivateEvent </td><td>1 - Private Event (Private events wont come on Meraevents home page)<br>0 - Public Event (default)</td></tr>
                                    
                                    <tr><td>eventCategory (<span class="starmark">*</span>)</td><td>Campus / Entertainment / NewYear / Professional / Qa / Special Occasion / Spiritual / Sports / Trade Shows / Training</td></tr>
                                     
                                    <tr><td>eventBanner</td><td>Event Banner Image link (Ex: <?php echo $siteUrl; ?>download/MeaEvents_Logo_High.png)<br>Banner Image should be of Width:1140px and Height:330px for better view</td></tr>
                                    <tr><td>eventThumbnail</td><td>Event Logo Image link (Ex: <?php echo $siteUrl; ?>download/MeaEvents_Logo.png)<br>Logo Image should be of Width:250px and Height:250px for better view</td></tr>
                                    <tr><td>isWebinar</td><td>0 (default) - not Webinar Event<br>1 - Webinar Event</td></tr>
                                    <tr><td>venueAddress (<span class="starmark">*</span>)</td><td>Event Venue Address (if not Webinar Event)</td></tr>
                                    <tr><td>country (<span class="starmark">*</span>)</td><td>Event Country (if not Webinar Event)</td></tr>
                                    <tr><td>state (<span class="starmark">*</span>)</td><td>Event State (if not Webinar Event)</td></tr>
                                    <tr><td>city (<span class="starmark">*</span>)</td><td>Event City (if not Webinar Event)</td></tr>
                                    <tr><td>tgs  </td><td>ex: sports, hyderabadevent..etc</td></tr>

                                </tbody>
                            </table>


                            <h3 class="tabHeading">Request/Response:</h3>
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <colgroup>
                                    <col width="25%" class="con0">
                                    <col width="75%" class="con0">
                                </colgroup>

                                <thead><tr><th class="head0">Request/Response</th><th class="head0">Description</th></tr></thead>

                                <tbody>
                                    <tr><td>Request URL</td><td><?php echo $siteUrl; ?>developer/event/createEvent?access_token=yourAccessToken
                                        
                                        </td></tr>
                                    <tr><td>Response Type</td><td>json</td></tr>
                                    <tr><td>Response</td><td><br>Or<br>{"errors":"{"error":"true","0":{"eventDescription":["This field does not meet the minimum length of 50"]}}"}</td></tr>
                                </tbody>
                            </table>
                        </div>
    </div>

                   
                </div>
              </div>
            </div>
<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" class="collapsed" aria-expanded="false">Update Event</em></a>
                </h4>
              </div>
              <div id="collapseTwo" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                 
                  <div class="accordion-content open-content" style="display: block;">
        
           <div class="subcontent" id="validation t1">
                            <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <colgroup>
                                    <col width="25%" class="con0">
                                    <col width="75%" class="con0">
                                </colgroup>

                                <thead><tr><th class="head0">Parameter Name</th><th class="head0">Parameter Value</th></tr></thead>

                                <tbody>
                                    <tr><td>eventId (<span class="starmark">*</span>)</td><td>Event Id to update the information</td></tr>
                                    <tr><td>eventType</td><td>paidevent / freeevent / noregevent</td></tr>
                                    <tr><td>eventTitle</td><td>Event Title (between 5 and 255 charecters)</td></tr>
                                    <tr><td>eventURL</td><td>preferred Event URL starting</td></tr>
                                    <tr><td>eventStartDate</td><td>yyyy-mm-dd</td></tr>
                                    <tr><td>eventStartTime</td><td>hh:mm:ss (24 hours format)</td></tr>
                                    <tr><td>eventEndDate</td><td>yyyy-mm-dd</td></tr>
                                    <tr><td>eventEndTime</td><td>hh:mm:ss (24 hours format)</td></tr>
                                    <tr><td>eventDescription</td><td>Event Description (must be above 50 charecters)</td></tr>
                                    <tr><td>isPrivateEvent</td><td>0 - Private Event (Private events wont come on Meraevents home page)<br>1 - Public Event ()</td></tr>
                                    <tr><td>customTC</td><td>Custom terms and conditions, if any</td></tr>
                                    <tr><td>eventCategory</td><td>Campus / Entertainment / NewYear / Professional / Qa / Special Occasion / Spiritual / Sports / Trade Shows / Training</td></tr>
                                    <tr><td>useOriginalImage</td><td>0 - will resize the images<br>1 - will keep images as it is</td></tr>
                                    <tr><td>eventBanner</td><td>Event Banner Image link (Ex: <?php echo $siteUrl; ?>/download/MeaEvents_Logo_High.png)<br>Banner Image should be of Width:1140px and Height:330px for better view</td></tr>
                                    <tr><td>eventLogo</td><td>Event Logo Image link (Ex: <?php echo $siteUrl; ?>download/MeaEvents_Logo.png)<br>Logo Image should be of Width:250px and Height:250px for better view</td></tr>
                                    <tr><td>isWebinar</td><td>0 (default) - not Webinar Event<br>1 - Webinar Event</td></tr>
                                    <tr><td>venueAddress </td><td>Event Venue Address (if not Webinar Event)</td></tr>
                                    <tr><td>country  </td><td>Event Country (if not Webinar Event)</td></tr>
                                    <tr><td>state  </td><td>Event State (if not Webinar Event)</td></tr>
                                    <tr><td>city  </td><td>Event City (if not Webinar Event)</td></tr>
                                    <tr><td>locality</td><td>Event Locality</td></tr>
                                    <tr><td>ContactDetails</td><td>Event Contact Information</td></tr>
                                    <tr><td>moreEmailsForReports</td><td>Multiple mail ids, seperated by Comma (,) for reports</td></tr>
                                    <tr><td>EventWebURL</td><td>Event Web URL, if any</td></tr>
                                    <tr><td>EventFBLink</td><td>Event Facebook link, if any</td></tr>
                                    <tr><td>ticketingOption</td><td>0 - Collect one attendee information<br>1 - Collect all attendee information</td></tr>
                                </tbody>
                            </table>


                            <h3 class="tabHeading">Request/Response:</h3>
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <colgroup>
                                    <col width="25%" class="con0">
                                    <col width="75%" class="con0">
                                </colgroup>

                                <thead><tr><th class="head0">Request/Response</th><th class="head0">Description</th></tr></thead>

                                <tbody>
                                    <tr><td>Request URL</td><td><?php echo $siteUrl; ?>resource/updateEvent?access_token=yourAccessToken</td></tr>
                                    <tr><td>Response Type</td><td>json</td></tr>
                                    <tr><td>Response</td><td></td></tr>
                                </tbody>
                            </table>
                        </div>
    
    </div>


                </div>
              </div>
            </div>-->
            <div class="panel panel-default">
            <div class="panel-heading">
              <h4 class="panel-title">
                <a data-toggle="collapse" data-parent="#accordion" href="#collapseThree" class="collapsed" aria-expanded="false">Publish/Unpublish Event</a>
              </h4>
            </div>
            <div id="collapseThree" class="panel-collapse collapse" aria-expanded="false">
              <div class="panel-body">
               <div class="accordion-content open-content" style="display: block;">
     <div class="subcontent" id="validation t1">
                            <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <thead><tr><th class="head0">Parameter Name</th><th class="head0">Parameter Value</th></tr></thead>

                                <tbody>
                                    <tr><td>eventId (<span class="starmark">*</span>)</td><td>Your EventId</td></tr>
                                    <tr><td>publish (<span class="starmark">*</span>)</td><td>1 for Publish, 0 for UnPublish</td></tr>
                                </tbody>
                            </table>

                            <h3 class="tabHeading">Request/Response:</h3>
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <colgroup>
                                    <col width="25%" class="con0">
                                    <col width="75%" class="con0">
                                </colgroup>

                                <thead><tr><th class="head0">Request/Response</th><th class="head0">Description</th></tr></thead>

                                <tbody>
                                    <tr><td>Request URL</td><td><?php echo $siteUrl; ?>developer/event/publishOrUnpublishEvent?access_token=yourAccessToken</td></tr>
                                    <tr><td>Response Type</td><td>json</td></tr>
                                    <tr><td>Response</td><td>{"status":"success","message":"Successfully published the event","eventid":"33090"}<br>Or<br>{"status":"error","message":"Inalid input","eventid":"33090"}</td></tr>
                                </tbody>
                            </table>
                        </div>   
        
        
        
    </div>
              </div>
            </div>
          </div>
           

            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFour" class="collapsed" aria-expanded="false">Add Ticket Details</a>
                </h4>
              </div>
              <div id="collapseFour" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                <div class="accordion-content open-content" style="display: block;">
     <div class="subcontent" id="validation t1">
                            <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                            <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                <thead><tr><th class="head0">Parameter Name</th><th class="head0">Parameter Value</th></tr></thead>

                                <tbody>



                                    <tr> <td>eventId (<span class="starmark">*</span>)</td><td>Event Id</td></tr>
                                    <tr> <td>ticketName (<span class="starmark">*</span>)</td><td> Name of the ticket/registration</td></tr>
                                    <tr> <td>ticketDescription  </td><td>Ticket/Registration description</td></tr>
                                    <tr> <td>price (<span class="starmark">*</span>)</td><td> price (Enter 0 for Free)</td></tr>
                                    <tr> <td>currency (<span class="starmark">*</span>)</td><td>INR/USD/Free</td></tr>
                                    <tr> <td>totalTicketCount (<span class="starmark">*</span>)</td><td>Ticket/Registration Quantity</td></tr>
                                    <tr> <td>status </td><td> Active/InActive/SoldOut</td></tr>
                                    <tr> <td>startDate  (<span class="starmark">*</span>) </td><td> mm/dd/yyyy
</td></tr>
                                    <tr> <td>startTime   </td><td> hh:mm (AM / PM)</td></tr>
                                    <tr> <td>endDate (<span class="starmark">*</span>)</td><td>mm/dd/yyyy</td></tr>
                                    <tr> <td>endTime  </td><td> hh:mm (AM / PM)</td></tr>
                                    <tr> <td>minQuantity  </td><td> Minimum purchase quantity ( default :1)</td></tr>
                                    <tr> <td>maxQuantity   </td><td>Maximum purchase quantity (default : 9)</td></tr>
                                    <tr> <td>donationType   </td><td> true/false </td></tr>
                                    <tr> <td>addServiceTaxValue   </td><td>3.09 / 12.36 / 12.5 / 14 / 14.5 / 17.5 / 17.42 / 20 / 25 / 25.5<br> choose any of the above values </td></tr>
                                    <tr> <td>addEntertainmentTaxValue   </td><td> 10 / 20 / 25 <br> choose any of the above values</td></tr>



                                </tbody>
                            </table>


                            <div class="cls"></div>
                            <h3 class="tabHeading">Request/Response:</h3>

                            <div>
                                <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                                    <colgroup>
                                        <col width="25%" class="con0">
                                            <col width="75%" class="con0">
                                                </colgroup>

                                                <thead><tr><th class="head0">Request/Response</th><th class="head0">Description</th></tr></thead>
                                                <tbody>
                                                    <tr> <td>Request URL</td><td><?php echo $siteUrl; ?>developer/event/createTicket?access_token=yourAccessToken</td></tr>
                                                    <tr> <td>Response Type</td><td>json</td></tr>
                                                    <tr> <td>Response </td><td>{"status":"error","message":"errorCode""}<br>
                                                                OR<br>
                                                                    {"status":"success","message":"successfull transaction","ticketId":"TKCIGJ"}
                                                                    </td></tr>
                                                                    </tbody></table>
                                                                    </div>
                                                                    </div>  
        
        
        
    </div>
                </div>
              </div>
            </div>


<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseFive" class="collapsed" aria-expanded="false">Update Ticket Details</a>
                </h4>
              </div>
              <div id="collapseFive" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td>Event Id</td>
                      </tr>
                      <tr>
                        <td>ticketId (<span class="starmark">*</span>)</td>
                        <td>Ticket Id</td>
                      </tr>
                      <tr>
                        <td>ticketName </td>
                        <td> Name of the ticket/registration</td>
                      </tr>
                      <tr>
                        <td>ticketDescription </td>
                        <td>Ticket/Registration description</td>
                      </tr>
                      <tr>
                        <td>price </td>
                        <td> price (Enter 0 for Free)</td>
                      </tr>
                      <tr>
                        <td>currency </td>
                        <td>INR/USD/Free</td>
                      </tr>
                      <tr>
                        <td>totalTicketCount </td>
                        <td>Ticket/Registration Quantity</td>
                      </tr>
                      <tr>
                        <td>status </td>
                        <td> Active/InActive/SoldOut</td>
                      </tr>
                      <tr>
                        <td>startDate </td>
                        <td> yyyy-mm-dd (default : todays date)</td>
                      </tr>
                      <tr>
                        <td>startTime </td>
                        <td> HH:MM (current time)</td>
                      </tr>
                      <tr>
                        <td>endDate </td>
                        <td>yyyy-mm-dd (default : event end date)</td>
                      </tr>
                      <tr>
                        <td>endTime </td>
                        <td> HH:MM (default : event end time)</td>
                      </tr>
                      <tr>
                        <td>minQuantity </td>
                        <td> Minimum purchase quantity ( default :1)</td>
                      </tr>
                      <tr>
                        <td>maxQuantity</td>
                        <td>Maximum purchase quantity (default : 9)</td>
                      </tr>
                      <tr>
                        <td>addServiceTax </td>
                        <td>(Enter 0 for Free) </td>
                      </tr>
                      <tr>
                          <td>donationType   </td><td> true/false </td>
                      </tr>
                      <tr> 
                          <td>addServiceTaxValue   </td>
                          <td>3.09 / 12.36 / 12.5 / 14 / 14.5 / 17.5 / 17.42 / 20 / 25 / 25.5<br> choose any of the above values </td></tr>
                      <tr> 
                          <td>addEntertainmentTaxValue   </td>
                          <td> 10 / 20 / 25 <br> choose any of the above values</td></tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/updateTicket?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode""}<br>
                            OR<br>
                            {"status":"success","message":"successfull transaction","ticketId":"TKCIGJ"} </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>-->



<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseSix" class="collapsed" aria-expanded="false">Add Discount Details</a>
                </h4>
              </div>
              <div id="collapseSix" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td> Event Id</td>
                      </tr>
                      <tr>
                        <td>discountName (<span class="starmark">*</span>)</td>
                        <td> Name of the discount</td>
                      </tr>
                      <tr>
                        <td>discountCode (<span class="starmark">*</span>) </td>
                        <td>Discount (Numeric)</td>
                      </tr>
                      <tr>
                        <td>discountType (<span class="starmark">*</span>)</td>
                        <td>1- fixed/ 2-percentage</td>
                      </tr>
                      <tr>
                        <td>discountValue (<span class="starmark">*</span>)</td>
                        <td> enter numeric value {ex: 100}</td>
                      </tr>
                      <tr>
                        <td>startDate  (<span class="starmark">*</span>)</td>
                        <td>yyyy-mm-dd (default : todays date)</td>
                      </tr>
                      <tr>
                        <td>startTime </td>
                        <td> HH:MM (current time)</td>
                      </tr>
                      <tr>
                        <td>endDate  (<span class="starmark">*</span>)</td>
                        <td> yyyy-mm-dd (default : event end date)</td>
                      </tr>
                      <tr>
                        <td>endTime </td>
                        <td> HH:MM (default : event end time)</td>
                      </tr>
                      <tr>
                        <td>usageLimit </td>
                        <td> Discount limit ( default : ticket quantity)</td>
                      </tr>
                      <tr>
                        <td>ticketIds  (<span class="starmark">*</span>)</td>
                        <td> ticketId1-ticketid2</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/addDiscount?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode"" }<br>
                            OR<br>
                            {"status":"success","message":"successfull transaction","discountId":"DKFJ"} </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>-->



<!--             <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseSeven" class="collapsed" aria-expanded="false">Update Discount Details</a>
                </h4>
              </div>
              <div id="collapseSeven" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td> Event Id</td>
                      </tr>
                      <tr>
                        <td>discountId (<span class="starmark">*</span>)</td>
                        <td> Discount ID</td>
                      </tr>
                      <tr>
                        <td>discountName </td>
                        <td> Name of the discount</td>
                      </tr>
                      <tr>
                        <td>discountCode </td>
                        <td>Discount (Numeric)</td>
                      </tr>
                      <tr>
                        <td>discountType </td>
                        <td>1- fixed/ 2-percentage</td>
                      </tr>
                      <tr>
                        <td>discountValue </td>
                        <td> enter numeric value {ex: 100}</td>
                      </tr>
                      <tr>
                        <td>startDate </td>
                        <td>yyyy-mm-dd (default : todays date)</td>
                      </tr>
                      <tr>
                        <td>startTime </td>
                        <td> HH:MM (current time)</td>
                      </tr>
                      <tr>
                        <td>endDate </td>
                        <td> yyyy-mm-dd (default : event end date)</td>
                      </tr>
                      <tr>
                        <td>endTime </td>
                        <td> HH:MM (default : event end time)</td>
                      </tr>
                      <tr>
                        <td>usageLimt </td>
                        <td> Discount limit ( default : ticket quantity)</td>
                      </tr>
                      <tr>
                        <td>ticketIds (<span class="starmark">*</span>)</td>
                        <td> ticketId1-ticketid2</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/updateDiscount?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode"","discountId": ""}<br>
                            OR<br>
                            {"status":"success","message":"successfull transaction","discountId":"DKFJ"} </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>-->


<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseEight" class="collapsed" aria-expanded="false">Get Event Report</a>
                </h4>
              </div>
              <div id="collapseEight" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    
        <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td> Event Id</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/getEventReport?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"EventId":"33124","EventName":"airtelrun","status":"success","tickets":[{"Id":"27132","Name":"t2sdfsf","Price":"12","currency":"INR","total_tickets":"999","soldTickets":"0","canceledTickets":"0"}],"amount":0,"sold":0,"cancel":0} </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
</div>
                </div>
              </div>
            </div>-->



            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseNine" class="collapsed" aria-expanded="false">Get Specific Event Details</a>
                </h4>
              </div>
              <div id="collapseNine" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td> Event Id</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/getEvent?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode"","discountId": ""}<br>
                            OR<br>
                          {"event_detail_arr":{"Id":67596,"UserName":"Organizer Name ","StartDt":"2015-04-03 12:00:00","EndDt":"2015-12-04 14:00:00","Title":"Meraevents","Description":" Descripiton","Country":"India","State":null,"City":null,"Loc":null,"Venue":"","URL":"Meraevents","Logo":"","Banner":"","CatName":"Campus","SubCatName":"College Fest","PinCode":"0"},"message":"success"} </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>



             <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTen" class="collapsed" aria-expanded="false">Get Organizer Event List</a>
                </h4>
              </div>
              <div id="collapseTen" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    
        <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                        <tr>
                          <td>None</td>
                          <td>None</td>
                        </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/getEventList?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode"}<br>
                            OR<br>
                           [{"Id":"67602","StartDt":"2015-05-01 15:34:56","EndDt":"2015-05-01 17:34:56","Title":"Run Event"},{"Id":"67601","StartDt":"2015-04-20 22:00:00","EndDt":"2015-11-25 23:00:00","Title":"Meraevent event"}]</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
</div>
                </div>
              </div>
            </div>


            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseEleven" class="collapsed" aria-expanded="false">Get Event Attendees Details</a>
                </h4>
              </div>
              <div id="collapseEleven" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td> Event Id</td>
                      </tr>
                      <tr>
                        <td>reportType  </td>
                        <td>  detailreport / summaryreport (Default: Detail Reports)</td>
                      </tr>
                      <tr>
                        <td>modifiedDate </td>
                        <td> yyyy-mm-dd hh:ii:ss (Hours - 24 hrs format )</td>
                      </tr>
                      <tr>
                        <td>pageno </td>
                        <td> Page Number (per page 100 records, and if not provided will give you total records)</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/getEventAttendees?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode"}<br>
                            OR<br>
                             [{"EventSIgnupId":"23116011","referralDAmount":"0","Signup Date":"2015-04-15 17:30:57","PaymentGateway":"EBS","BarcodeNumber":"7596231160","PaymentTransId":"1222333","UserName":"Meraevent","attendeeId":"687185","currencyCode":"INR","Email":"meraevent@meraevent.com","Fees":"150","PaymentModeId":"1","Company":"","Phone":"000000","Mobile":"","ticket_name":"Delegate Registration INR - Three Days","ticketPrice":"150","Amount":"150","NumOfTickets":1,"TicketAmt":"150","ServiceTax":"0"}] 
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>



            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseTwelve" class="collapsed" aria-expanded="false">Get Ticket Type Details</a>
                </h4>
              </div>
              <div id="collapseTwelve" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventId (<span class="starmark">*</span>)</td>
                        <td> Event Id</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>resource/getTickets?access_token=yourAccessToken</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{"status":"error","message":"errorCode"}<br>
                            OR<br>
                            {"EventId":"67596","EventName":"Meraevents","status":"success","tickets_array":[{"Id":"27201","Name":"Delegate Registration INR - One Day","Description":"One Day","Price":"5000","currencyId":"INR"},{"Id":"27202","Name":"Delegate Registration INR - Two Days","Description":"INR2","Price":"10000","currencyId":"INR"}]}
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseList" class="collapsed" aria-expanded="false">Get Event List</a>
                </h4>
              </div>
              <div id="collapseList" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
<!--                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>-->
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>countryId  </td>
                        <td> Number (Default India related events)</td>
                      </tr>
                      <tr>
                        <td>page  </td>
                        <td> Number (Default 20 records)</td>
                      </tr>
                      <tr>
                        <td>limit  </td>
                        <td> Number ( Default 1)</td>
                      </tr>
                      <tr>
                        <td>date  </td>
                        <td> Date (format MM/dd/yyyy)</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/event/getEventList</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>"eventList": [<br>
            {<br>
                "id": 96220,<br>
                "title": "CERTIFIED SCRUM MASTER TRAINING (CSM) In Hyderabad",<br>
                "thumbImage": "http://d6wad9g39si6z.cloudfront.net/content/categorylogo/training-thumbnail1455801654.jpg",<br>
                "bannerImage": "http://d6wad9g39si6z.cloudfront.net/content/categorylogo/training-banner1455801653.jpg",<br>
                "timeZone": "ASIA",<br>
                "startDate": "2016-05-16 09:00:00",<br>
                "endDate": "2016-05-17 18:00:00",<br>
                "venueName": "Hyatt Hyderabad Gachibowli, Road No.2, IT Park, Gachibowli, Hyderabad, India",<br>
                "eventUrl": "http://dev.meraevents.com/event/CERTIFIED-SCRUM-MASTER-TRAINING-CSM-In-Hyderabad-May",<br>
                "categoryName": "Training",<br>
                "categoryIcon": "",<br>
                "themeColor": "#8c905e",<br>
                "defaultBannerImage": "http://d6wad9g39si6z.cloudfront.net/content/categorylogo/training-banner1455801653.jpg",<br>
                "defaultThumbImage": "http://d6wad9g39si6z.cloudfront.net/content/categorylogo/training-thumbnail1455801654.jpg",<br>
                "registrationType": "paid",<br>
                "cityName": "Hyderabad",<br>
                "bookMarked": 0,<br>
                "isCustomFields": 0,<br>
                "isSeatingLayout": 0,<br>
                "isMultipleAttendee": 0<br>
            }
<br>

    "statusCode": 200<br>
}

                             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseSearch" class="collapsed" aria-expanded="false">Event Search</a>
                </h4>
              </div>
              <div id="collapseSearch" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>keyword  </td>
                        <td>String</td>
                      </tr>
                      <tr>
                        <td>countryId  </td>
                        <td> Number (Default India related events)</td>
                      </tr>
                      <tr>
                        <td>customDate  </td>
                        <td> Date Time</td>
                      </tr>
                      <tr>
                        <td>page  </td>
                        <td> Number (Default 20 records)</td>
                      </tr>
                      <tr>
                        <td>limit  </td>
                        <td> Number ( Default 1)</td>
                      </tr>
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/event/eventSearch</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>"eventList": [<br>
            {<br>
                "id": 94751,<br>
                "title": "Manual Testing training",<br>
                "thumbImage": "http://d6wad9g39si6z.cloudfront.net/content/eventlogo/94751/campus-thumbnail1460009284.jpg",<br>
                "bannerImage": "http://d6wad9g39si6z.cloudfront.net/content/eventbanner/94751/campus-banner1460009284.jpg",<br>
                "startDate": "2016-05-23 16:30:00",<br>
                "endDate": "2016-05-23 17:30:00",<br>
                "venueName": "Gachibowli Stadium",<br>
                "eventUrl": "http://dev.meraevents.com/event/manual-testing-training",<br>
                "categoryName": "Training",<br>
                "categoryIcon": "",<br>
                "themeColor": "#8c905e",<br>
                "defaultBannerImage": "http://d6wad9g39si6z.cloudfront.net/content/categorylogo/training-banner1455801653.jpg",<br>
                "defaultThumbImage": "http://d6wad9g39si6z.cloudfront.net/content/categorylogo/training-thumbnail1455801654.jpg",<br>
                "registrationType": "paid",<br>
                "timeZone": "IST",<br>
                "bookMarked": 0,<br>
                "cityName": "Hyderabad"<br>
            }<br>
        ],<br>
        "page": 1,<br>
        "limit": 12,<br>
        "nextPage": false,<br>
        "keyWord": "manual",<br>
        "total": 1<br>
    },<br>
    "statusCode": 200<br>
}<br>

                             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseEvent" class="collapsed" aria-expanded="false">Get Event Details</a>
                </h4>
              </div>
              <div id="collapseEvent" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>eventId (<span class="starmark">*</span>) </td>
                        <td> Number </td>
                      </tr>
                     
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/event/getEventDetails</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>"response": {<br>
        "messages": [],<br>
        "total": 1,<br>
        "details": {<br>
            "id": 91896,<br>
            "ownerId": 264325,<br>
            "startDate": "2016-04-30 03:30:00",<br>
            "endDate": "2016-05-14 02:30:00",<br>
            "title": " Trek to Everest Base Camp",<br>
            "description": "<p>Everest Base Camp Trek<br /><br />Elevation:- 5360 meters | 17 590 feet.<br /><br />Co-ordinates:- 28&deg;0&rsquo;26&Prime;N, 86&deg;51&rsquo;34&
            "localityId": 0,<br>
            "url": "trek-to-everest-base-camp",<br>
            "thumbnailfileid": 36485,<br>
            "bannerfileid": 97875,<br>
            "categoryId": 9,<br>
            "subcategoryId": 158,<br>
            "pincode": 411029,<br>
            "registrationType": 2,<br>
            "eventMode": 0,<br>
            "timeZoneId": 1,<br>
            "venueName": "Everest Base Camp",<br>
            "private": 0,<br>
            "status": 1<br>
            "bannerPath": "http://d6wad9g39si6z.cloudfront.net/content/eventbanner/91896/Trek-b_t.jpg",<br>
            "thumbnailPath": "http://d6wad9g39si6z.cloudfront.net/content/eventlogo/91896/Trek-t_t.jpg", <br>
            "localityId": 0,<br>
            "url": "trek-to-everest-base-camp",<br>
            "thumbnailfileid": 36485,<br>
            "bannerfileid": 97875,<br>
            "categoryId": 9,<br>
            "subcategoryId": 158,<br>
            "pincode": 411029,<br>
            "registrationType": 2,<br>
            "eventMode": 0,<br>
            "timeZoneId": 1,<br>
            "venueName": "Everest Base Camp",<br>
            "private": 0,<br>
            "status": 1,<br>
            "latitude": 0,<br>
            "longitude": 0,<br>
            "bannerPath": "http://d6wad9g39si6z.cloudfront.net/content/eventbanner/91896/Trek-b_t.jpg",<br>
            "thumbnailPath": "http://d6wad9g39si6z.cloudfront.net/content/eventlogo/91896/Trek-t_t.jpg",<br>
            "eventDetails": {<br>
                "contactdetails": "",<br>
                "bookButtonValue": "Book Now",<br>
                "facebookLink": "",<br>
                "googleLink": null,<br>
                "twitterLink": null,<br>
                "tnctype": "organizer",<br>
                "meraeventstnc": null,<br>
                "organizertnc": null,<br>
                "contactWebsiteUrl": "",<br>
                "limitSingleTicketType": 0,<br>
                "salespersonid": 0,<br>
                "contactdisplay": 0,<br>
                "customvalidationfunction": null,<br>
                "customvalidationflag": null<br>
},<br>
    "statusCode": 200<br>
}

                             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseCalculation" class="collapsed" aria-expanded="false">Calculation Api</a>
                </h4>
              </div>
              <div id="collapseCalculation" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>eventId (<span class="starmark">*</span>) </td>
                        <td> Number </td>
                      </tr>
                      <tr>
                        <td>ticketArray (<span class="starmark">*</span>) </td>
                        <td> array (index is ticketid and value is selectedticketquantity) <br/>
                        Array that contains ticket ID as keys and ticket Quantities as values <br/>
	Ex: array(65595 => 1 , 65596 => 2)
                        </td>
                      </tr>
                      <tr>
                        <td>donateTicketArray   </td>
                        <td> array (index is ticketid and value is donation amount) <br/>
                        Array that contains “DONATE” ticket ID as keys and amount to donate <br/>
	Ex: array(65595 => 1 , 65596 => 2)
                        </td>
                      </tr>
                      <tr>
                        <td>discountCode (<span class="starmark">*</span>) </td>
                        <td> string <br/>
                            
                           code that the user has entered under promo code.
                        </td>
                      </tr>
                      <tr>
                        <td>referralCode (<span class="starmark">*</span>) </td>
                        <td> string </td>
                      </tr>
                     
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/event/getTicketCaluculation</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{<br>
    "response": {<br>
        "calculationDetails": {<br>
            "ticketsData": {<br>
                "84145": {<br>
                    "ticketId": 84145,<br>
                    "ticketName": "Single",<br>
                    "ticketType": "paid",<br>
                    "ticketPrice": 100,<br>
                    "selectedQuantity": 1,<br>
                    "totalAmount": 100,<br>
                    "currencyCode": "INR",<br>
                    "referralDiscount": 0,<br>
                    "normalDiscount": 10,<br>
                    "normalDiscountId": 127111,<br>
                    "discountval": 10,<br>
                    "taxes": {<br>
                        "1": {<br>
                            "label": "Service Tax",<br>
                            "id": 1,<br>
                            "value": 14.5,<br>
                            "taxmappingid": 2084,<br>
                            "taxAmount": 15.66<br>
                        },<br>
                        "2": {<br>
                            "label": "Entertainment Tax",<br>
                            "id": 2,<br>
                            "value": 20,<br>
                            "taxmappingid": 2126,<br>
                            "taxAmount": 18<br>
                        }   },<br>
    "statusCode": 200
<br>
                             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOrder" class="collapsed" aria-expanded="false">Order Id Generation</a>
                </h4>
              </div>
              <div id="collapseOrder" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>eventId (<span class="starmark">*</span>) </td>
                        <td> Number </td>
                      </tr>
                      <tr>
                        <td>ticketArray (<span class="starmark">*</span>) </td>
                        <td> array (index is ticketid and value is selectedticketquantity) <br/>
                        Array that contains ticket ID as keys and ticket Quantities as values <br/>
	Ex: array(65595 => 1 , 65596 => 2)
                        </td>
                      </tr>
                       
                      <tr>
                        <td>discountCode (<span class="starmark">*</span>) </td>
                        <td> string <br/>
                            
                           code that the user has entered under promo code.
                        </td>
                      </tr>
                      <tr>
                        <td>referralCode (<span class="starmark">*</span>) </td>
                        <td> string </td>
                      </tr>
                     
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/event/bookNow</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>Success <br>                              
                        {<br>
                            "response": {<br>
                                "orderId": "20160506CaKjLXnWHf061117",<br>
                                "messages": [<br>
                                    "Order ID generated"<br>
                                ]<br>
                            },<br>
                            "statusCode": 200<br>                             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseCustom" class="collapsed" aria-expanded="false">Get Custom  Fields</a>
                </h4>
              </div>
              <div id="collapseCustom" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>orderId (<span class="starmark">*</span>) </td>
                        <td> Number <br/>
                        Id of the Order of the event.
                        </td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/configure/getCustomFields</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{<br>
  "response": {<br>
    "customFields": [<br>
      {<br>
        "id": 69578,<br>
        "ticketName": "paid",<br>
        "formFields": [<br>
          {<br>
            "id": 574647,<br>
            "fieldname": "Full Name",<br>
            "fieldtype": "textbox",<br>
            "commonfieldid": 1,<br>
            "fieldmandatory": 1,<br>
            "defaultValue": "",<br>
            "fieldnameid": "FullName1"<br>
          },<br>
        "messages": []<br>
    },<br>
    "statusCode": 200<br>
}

                             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseCustomValues" class="collapsed" aria-expanded="false">Get Custom Field Values</a>
                </h4>
              </div>
              <div id="collapseCustomValues" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>customFieldId (<span class="starmark">*</span>) </td>
                        <td> Number <br/>
                        Id of the Order of the event.
                        </td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/configure/getCustomFieldValues</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{<br>
    "response": {<br>
        "fieldValuesInArray": [<br>
            {<br>
                "id": 46,<br>
                "customfieldid": 542793,<br>
                "value": "Morning",<br>
                "isdefault": 0<br>
            },<br>
        "total": 4,<br>
        "message": []<br>
    }<br>
}
             
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseAtt" class="collapsed" aria-expanded="false">Save Attendee Info</a>
                </h4>
              </div>
              <div id="collapseAtt" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      
                      <tr>
                        <td>orderId (<span class="starmark">*</span>) </td>
                        <td> string  
                        
                        </td>
                      </tr>
                      
                      <tr>
                        <td>custom field array (<span class="starmark">*</span>) </td>
                        <td> array  <br>
                            
                            Collect Multiple Attendee : 1
If you select ticket “T1” as 2 then the custom field list will be like<br>
FullName1 => Gautam<br>
EmailId1 => gautamdharmapuri@gmail.com<br>
MobileNo1 => 9492763255<br>
State1 => Andhrapradesh<br>
City1 => Hyderabad<br>
FullName2 => Siddu<br>
EmailId2 => gautamdharmapuri@gmail.com<br>
MobileNo2 => 9492763255<br>
State2 => Andhrapradesh<br>
City2 => Hyderabad
                        </td>
                      </tr>
                      
                      
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/booking/saveAttendeeData</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>POST</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{<br>
    "response": {<br>
        "messages": [<br>
            "Attendee data added"<br>
        ],<br>
        "totalPurchaseAmount": 100,<br>
        "eventSignupId": 521221,<br>
        "gatewayList": [<br>
            {<br>
                "id": 1,<br>
                "name": "ebs",<br>
                "description": "Credit Card/ Debit Card /Internet Banking",<br>
                "gatewaytext": ""<br>
            },<br>
            {<br>
                "id": 4,<br>
                "name": "paypal",<br>
                "description": "International Payments",<br>
                "gatewaytext": ""<br>
            },<br>
            {<br>
                "id": 5,<br>
                "name": "mobikwik",<br>
                "description": "Mobikwik Wallet",<br>
                "gatewaytext": ""<br>
            },<br>
            {<br>
                "id": 6,<br>
                "name": "paytm",<br>
                "description": "Paytm Wallet",<br>
                "gatewaytext": ""<br>
            }<br>
        ]<br>
    },<br>
    "statusCode": 200<br>
}

                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>
              
            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapsePay" class="collapsed" aria-expanded="false">Save Pay Now</a>
                </h4>
              </div>
              <div id="collapsePay" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>orderId (<span class="starmark">*</span>)</td>
                        <td> string</td>
                      </tr>
                      <tr>
                        <td>paymentGatewayId (<span class="starmark">*</span>)</td>
                        <td> string</td>
                      </tr>
                      <tr>
                        <td>returnUrl (<span class="starmark">*</span>)</td>
                        <td> string</td>
                      </tr>
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/booking/paynow</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>  {<br>
    "status": true,<br>
    "statusCode": 200,<br>
    "response": {<br>
        "messages" : "Gateway added successfully for the booking",<br>
      “paymentGatewayId” : “1”<br>
    }<br>
 }

                           
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>-->
<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapsePrint" class="collapsed" aria-expanded="false">Print Pass</a>
                </h4>
              </div>
              <div id="collapsePrint" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>eventsignupId (<span class="starmark">*</span>)</td>
                        <td> string</td>
                      </tr>
                       
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/event/printPass</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{<br>
    "eventsignupDetails": {<br>
        "id": 718981,<br>
        "userid": 148476,<br>
        "eventid": 96636,<br>
        "quantity": 2,<br>
        "barcodenumber": 6636718981,<br>
        "transactionticketids": 84145,<br>
        "fromcurrencyid": 1,<br>
        "tocurrencyid": 1,<br>
        "discount": "X",<br>
        "discountcodeid": 0,<br>
        "paymentmodeid": 1,<br>
        "paymentgatewayid": null,<br>
        "paymenttransactionid": 12321,<br>
        "referralcode": "",<br>
        "promotercode": "",<br>
        "totalamount": 287,<br>
        "eventextrachargeid": "626,627",<br>
        "eventextrachargeamount": 12.56,<br>
        "transactiontickettype": "paid",<br>
        "signupdate": "09 May,2016 04:16 PM",<br>
        "currencyCode": "INR",<br>
        "currencySymbol": "",<br>
        "extraChargeLabel": "Service Charge+Payment Gateway fee",<br>
        "paymentMode": "Card"<br>
    },

                           
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>-->
<!--            <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseOffline" class="collapsed" aria-expanded="false">Offline Booking Api</a>
                </h4>
              </div>
              <div id="collapseOffline" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                  <div class="accordion-content open-content" style="display: block;">
    <div class="subcontent" id="validation t1">
                  <h3 class="tabHeading">(<span class="starmark">*</span>)<span class="starhint"> indicates mandatory input</span></h3>
                  <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                    <thead>
                      <tr>
                        <th class="head0">Parameter Name</th>
                        <th class="head0">Parameter Value</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr>
                        <td>orderId (<span class="starmark">*</span>)</td>
                        <td> string</td>
                      </tr>
                      <tr>
                        <td>transactionID (<span class="starmark">*</span>)</td>
                        <td> string</td>
                      </tr>
                      <tr>
                        <td>isSmsEnable  </td>
                        <td> TRUE / FALSE</td>
                      </tr>
                      <tr>
                        <td>isEmailEnable  </td>
                        <td> TRUE / FALSE</td>
                      </tr>
                      
 
                       
                    </tbody>
                  </table>
                  <div class="cls"></div>
                  <h3 class="tabHeading">Request/Response:</h3>
                  <div>
                    <table cellspacing="0" cellpadding="0" border="0" class="stdtable">
                      <colgroup>
                      <col width="25%" class="con0">
                      <col width="75%" class="con0">
                      </colgroup>
                      <thead>
                        <tr>
                          <th class="head0">Request/Response</th>
                          <th class="head0">Description</th>
                        </tr>
                      </thead>
                      <tbody>
                        <tr>
                          <td>Request URL</td>
                          <td><?php echo $siteUrl; ?>developer/booking/offlineBooking</td>
                        </tr>
                        <tr>
                          <td>Response Type</td>
                          <td>json</td>
                        </tr>
                        <tr>
                          <td>Response </td>
                          <td>{<br>
    "status": true,<br>
    "statusCode": 200,<br>
    "response": {<br>
        "messages": [<br>
            "Booked"<br>
        ]<br>
    }<br>
} }<br>

                           
                           </td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
        
</div>
                </div>
              </div>
            </div>-->



             <div class="panel panel-default">
              <div class="panel-heading">
                <h4 class="panel-title">
                  <a data-toggle="collapse" data-parent="#accordion" href="#collapseThirteen" class="collapsed" aria-expanded="false">Example Api call</a>
                </h4>
              </div>
              <div id="collapseThirteen" class="panel-collapse collapse" aria-expanded="false">
                <div class="panel-body">
                 <p> $host='<?php echo $siteUrl; ?>resource/getEventList';<br>
                          $token= YOUR_ACCESS_TOKEN_HERE;<br>
                          $process = curl_init($host);<br>
                          curl_setopt($process, CURLOPT_HEADER, 0);<br>
                          curl_setopt($process, CURLOPT_TIMEOUT, 30);<br>
                          curl_setopt($process, CURLOPT_POST, 1);<br>
                          curl_setopt($process, CURLOPT_POSTFIELDS, 'access_token='.$token);<br>
                          curl_setopt($process, CURLOPT_RETURNTRANSFER, TRUE);<br>
                          $return = curl_exec($process);<br>
                          curl_close($process);<br>
                          print_r($return); <br>
                        </p>
                </div>
              </div>
            </div>


                


            <p>&nbsp;</p>
            <p>&nbsp;</p>

          </div><!--Panel Group End-->
        </div>
    </div><!--col end-->
  </div><!--row end-->
</div><!--Tab content 1 end-->
<!--Api & Libraries End-->






  <!-- /.wrap --> 
</div>
<!-- /.page-container --> 
<!-- on scroll code-->
<?php  $this->load->view("includes/elements/home_scroll_filter.php"); ?>
                        

<!-- <div id="ui-datepicker-div" class="ui-datepicker ui-widget ui-widget-content ui-helper-clearfix ui-corner-all"></div> -->
