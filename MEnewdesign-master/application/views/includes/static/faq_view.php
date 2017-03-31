<style type="text/css">
.pricingdescnew {padding:20px 0 0 0;}
.pricingdescnew h4 { font-size: 22px; color: #1b1a1b; font-weight: 500;} 
.pricingdescnew p { font-size: 18px; font-weight: bold; padding-top: 20px; margin: 0;} 
.pricingdescnew ul  { margin: 0px 0 0 20px; padding: 5px 0;}
.pricingdescnew li {list-style-type: disc; margin-left: 10px; padding: 2px 0;  font-size: 15px;}
    </style>
<div class="page-container">
    <div class="wrap">
        <div class="faq-container">
            <?php (isset($pricing) && $pricing == 1) ? $pricing : '';?>
            <input type="hidden" id="pricetab" value="<?php echo $pricing; ?>"/>
            <div class="faq_top_bg">  
                <div class="container" >
                    <ul class="nav nav-tabs" role="tablist" id="myTab">
                        <li role="presentation" class="active"><a   a href="#faq" aria-controls="faq" role="tab" data-toggle="tab"><i class="icon-faq-icon"></i>FAQs</a></li>
                        <li role="presentation"><a href="#pricing" aria-controls="pricing" role="tab" data-toggle="tab"><i class="icon-pricing"></i>Pricing</a></li>
                        <!--<li role="presentation"><a href="#support" aria-controls="support" role="tab" data-toggle="tab"><i class="icon-phone"></i>Support</a></li>-->
                    </ul>
                </div>

            </div>


            <div class="tab-content container faq_block">
                <div role="tabpanel" class="tab-pane active" id="faq"> <h1 style="font-size: 28px">Common Questions</h1>
                    <ul class="cq">
                        <li class="showmenu" ><a href="javascript:;" >What is MeraEvents?</a>
                            <p class="menu" style="display: none;">MeraEvents is a one-stop platform dedicated to ticketing of events & trade fair industry. MeraEvents is an event discovery platform that lists comprehensive information about concerts, tours, plays, trade fairs, sporting events, seminars, conferences, training and workshops enabling event discovery and ticketing.<br><br>

It connects organizers and attendees seamlessly, helping organizers list, promote and sell tickets all at one place and reach out to their target audience on an easy to use digital platform. We are a proud "Go-Green" initiative and we seek to make the entire event process paperless.
</p>
                        </li>
                        <li class="showmenu"><a href="javascript:;">What events are published / listed on MeraEvents?</a>
                            <p class="menu" style="display: none;">MeraEvents lists information about concerts, plays, trade fairs, sports, seminars, conferences, spiritual events, corporate training, workshops, and the entire gamut of events. It is the go-to portal to be in the loop about what's happening, where and book tickets instantly.</p>


                        </li>
                        <li  class="showmenu"><a href="javascript:;">How much does it cost to post an event?</a>
                            <p class="menu" style="display: none;">The process of event listing is completely free </p>
                        </li>
                        <li  class="showmenu"><a href="javascript:;">How many cities is Meraevents available in?</a>
                            <p class="menu" style="display: none;">MeraEvents lists events all over India and is aggressively working towards an international presence.</p>

                        </li>
                        <li  class="showmenu"><a href="javascript:;">What are the advantages of partnering MeraEvents?</a>
                            <p class="menu" style="display: none;">MeraEvents is a unique platform dedicated entirely to events and trade-fairs industry. It covers the entire gamut of events from concerts, plays, trade fairs, seminars, conferences, training, workshops and all events happening in the city. It attracts a large number of unique visitors with high conversion rate due to effortless online ticketing; there are also innovative promotional strategies to ensure visibility on the website and across social platforms along with uniquely tailored content with embedded call to action to boost ticket sales.</p>

                        </li>
                         

                        <li  class="showmenu"><a href="javascript:;">How Can I Contact You?</a>
                            <!-- <p class="menu" style="display: none;">You can contact us on support@meraevents.com or call us on <b>+91-9396555888</b> for all your queries and redressal of grievances.</p> -->
                            <p class="menu" style="display: none;">Please vist <a href="<?php echo commonHelperGetPageUrl("contactUs"); ?>" target="_blank">Contact Us</a> Page for all your queries and redressal of grievances.</p>

                        </li>
                       
                        <li  class="showmenu"><a href="javascript:;">How do I provide feedback?</a>
                            <p class="menu" style="display: none;">We would love to make your experience even better. Our teams are looking forward to hearing from you at <a href="<?php echo commonHelperGetPageUrl("contactUs"); ?>" target="_blank">Contact Us</a>.</p>

                        </li>
                        <li  class="showmenu"><a href="javascript:;">What support does MeraEvents provide?</a>
                            <!-- <p class="menu" style="display: none;">MeraEvents provides dedicated support via email and phone. Reach out to us at <a href="mailto:support@meraevents.com">support@meraevents.com</a> or call us on <b>+91-9396555888</b> for any queries and we will get back to you at the earliest.</p> -->
                            <p class="menu" style="display: none;">MeraEvents provides dedicated support via email and phone. Please vist <a href="<?php echo commonHelperGetPageUrl("contactUs"); ?>" target="_blank">Contact Us</a></p>

                        </li>
                    </ul>
                    <h1>Delegate Questions</h1>

                   <ul class="cq">
                        <li class="showmenu" ><a href="javascript:;" >What can MeraEvents do for an Event?</a>
                            <p class="menu" style="display: none;">MeraEvents allows an organizer to include all the information about an event in an easy listing. The organizer can choose from different promotional plans to enhance visibility. It also increases conversion ratio as the user audience is highly targeted and hyper-local
</p>
                        </li>
                        <li class="showmenu"><a href="javascript:;">How secure is the information I subscribe to on MeraEvents?</a>
                            <p class="menu" style="display: none;">MeraEvents connects delegates and organizers seamlessly. All events can be accessed with one click. MeraEvents assigns a unique username and password for the delegates. Signing up, email alerts on important events are made simple and easy. Printing of ticket, Printing of event pass and subscribing to our newsletters have been simplified. Information about discounts from event organizers to delegates is accessible in one click.</p>


                        </li>
                        <li  class="showmenu"><a href="javascript:;">What happens if an Event is cancelled?</a>
                            <p class="menu" style="display: none;">In case of cancellation by the event organizer MeraEvents will update it on the event listing and a personal mail will be sent to you. If the event that you registered for is a paid event you will also be notified about the fee refund via email. However  cancellation of  registration for an event that you have registered for, will vary and depend on the Organizer's terms & conditions.</p>
                        </li>
                        <li  class="showmenu"><a href="javascript:;">Where do I find information about the event organizer?</a>
                            <p class="menu" style="display: none;">Every event that is listed on MeraEvents is curated and verified and has all the contact information about the organizer on the event listing. The information is available on the events page below the event details. To get the e-mail ID of organizer click on the name of the organizer and all the information you require is listed.</p>

                        </li>
                        <li  class="showmenu"><a href="javascript:;">Do delegates need to pay a fee to register?</a>
                            <p class="menu" style="display: none;">A Delegate is required to pay a fee in case of a paid event. The registration fee can be paid on MeraEvents using multiple payment options including e-wallets such as Mobikwik and Paytm.</p>

                        </li>
                        
                    </ul>






                    <h1>Organizer Questions</h1>

                   <ul class="cq">
                        <li class="showmenu" ><a href="javascript:;" >What does MeraEvents do for me and my organization?</a>
                            <p class="menu" style="display: none;">MeraEvents allows an organizer to include all the information about an event in an easy listing. The organizer can choose from different promotional plans to enhance visibility. It also increases conversion ratio as the user audience is highly targeted and hyper-local
</p>
                        </li>
                        <li class="showmenu"><a href="javascript:;">How secure is the information I subscribe to on MeraEvents?</a>
                            <p class="menu" style="display: none;">With your event listing in MeraEvents here are some of the  attractive benefits that come your way.<br>
- Reduce no-shows via RSVPs<br>
- Attract and retain more clients through easy online ticketing<br>
- Increase sign-ups for events using multiple promotional packages<br>
- Encourage referrals using multiple embedded social widgets such as facebook, Twitter, Linked-in, Google+<br>
- Reach out to a large number of unique visitors with higher conversion rates<br>
- Create unique promotional content that is tailored to suit your event and can be reproduced and published across social media
</p>


                        </li>
                        <li  class="showmenu"><a href="javascript:;">Do I need to have an account with the bank that MeraEvents banks with?</a>
                            <p class="menu" style="display: none;">No, you do not need to set-up a bank account near a MeraEvents office. At the time of registration, you are required to provide us with your bank details. We (MeraEvents) will send you a cheque or wire your payment as per the payment option that you have selected.</p>
                        </li>
                        <li  class="showmenu"><a href="javascript:;">Is the information that I submit secure?</a>
                            <p class="menu" style="display: none;">MeraEvents  follows the strictest industry guidelines and software practices to keep data safe. We endeavor to keep all the information that you provide secure.</p>

                        </li>
                         
                        
                    </ul>

                   
                     </div>
                <div role="tabpanel " class="tab-pane" id="pricing">

               


                    <div class="insideTab">

                    <div class="row">
                        <div class="col-lg-12 pricingdescnew">
                            <h4>What you get signing up with Do-It -Yourself Solution under general pricing?</h4>
                            <p>Ticketing solutions:</p>
                            <ul>
                                
<li>Set your own ticket types, pricing and discounts</li>
<li>Tickets Widget Integration:  Helps you integrate the MeraEvents Ticketing widget on to your sites.</li>
<li>Ticket Confirmation email/SMS and with pre-event SMS Reminders</li>
                            </ul>

                            <p>Reports:</p>
<ul>
<li>Automated daily sales report of your event(s)</li>
<li>Access to real time data of your event via MeraEvents dashboard</li>
<li>Access to view and manage event attendees data in a single view</li>
</ul>


<p>Manage your customer:</p>
<ul>
<li>Send customised emails to attendees from MeraEvents Dashboard Support services anytime to any number of attendees</li>
</ul>

<p>Support Services: </p>
<ul>
<li>Our dedicated team will handle your attendees calls and emails (during business hours).</li>
<li>Follow-up services on incomplete transactions. To enable maximum conversions</li>
<li>E-mail support (event information and queries)</li>

</ul>











                        </div>
                    </div>



                        <h1 class="">General Pricing </h1>
                        <div class="pricetable">
                            <div class="cals">
                                <h2>MERAEVENTS SERVICE CHARGE</h2>
                                <span class="pull-right">+</span>
                                <p>2%</p>
                            </div>
                            <div class="cals">
                                <h2>PAYMENT GATEWAY CHARGES</h2>
                                <span class="pull-right">=</span>
                                <p>1.99%</p>
                            </div>
                            <div class="cals">
                                <h2>TOTAL</h2>

                                <p>4.59 %</p>
                                <span>(Total 3.99% + 15% Service Tax)</span>
                            </div>
                        </div>
                        <div class="note">
                            <p>
                                <span>
                                    Ex: Your event ticket is 100. Final payout after deduction of payment gateway fee + taxes = (100 x 4.59) / 100 = INR 95.41<br>


                                </span>
                            </p>
                            <p>Note: Payments would be processed in 3-5 working days after the completion of the event.</p>
                        </div>
                        <div class="row">
                            <h1 style="margin:25px 0;">Calculate Your ticket amount</h1>
                            <div class="col-md-1 col-sm-1 col-xs-3" style="padding-left:0;padding-right: 25px;">

                                <select class="form-control" style=" ">
                                    <option>INR</option>
<!--                                    <option>USD  </option>
                                    <option>EURO</option>
                                    <option>AUSD</option>-->
                                </select>

                            </div>
                            <!--   <div  class="col-md-5 col-sm-5 col-xs-9 ticketCal" >
                               <input type="text" placeholder="Enter Amount here" class="ticketCal">
                               <a class="calculate">Calculate</a>
                               </div>-->
                            <div class="input-group col-md-5 col-sm-5 col-xs-8">
                                <input type="text" class="form-control ticketAmount" style="font-size: 28px;" placeholder="Enter Amount here" aria-describedby="basic-addon2">
                                <span class="input-group-addon" id="Caluclate">  Calculate</span>


 
                            </div>
                           
                            <!--<div class="input-group col-md-5 col-sm-5 col-xs-9 ">
                                <input type="text" class="form-control">
                                <span class="input-group-addon">.00</span>
                            </div>-->


                        </div>



                        <div class="error" style="padding-left: 110px;position: relative;"></div>
                        
                                
<div class="col-lg-12 pricingdescnew">
<p>&nbsp;</p>
<h4>MERAEVENTS PREMIUM SERVICE PRICING </h4>

<p>Boost your event with extended services from MeraEvents Ticketing Expetise, includes</p>
<ul>
<li>Account Management </li>
<li>Promotion and Marketing Advisory </li>
<li>Virtual Desk</li>
<li>Reporting and Analytics</li>
<li>Other customize solutions</li>
</ul>


</div>
                            </div>
                        </div>
                        <input type ="hidden" id="serviceCharge" value="2"/>
                        <input type ="hidden" id="gatewayFee" value="1.99"/>
                        <input type ="hidden" id="serviceTax" value="15"/>
                        

                </div>
            </div>



            <!-- /faq-container --> 
        </div> 
        <!-- /.wrap --> 
    </div>

    <!-- /.page-container --> 
    <!-- on scroll code->
    <?php  $this->load->view("includes/elements/home_scroll_filter.php"); ?>
</div>

<style type="text/css">
    .filterdiv {
        display: none;
    }
    .invite_button a{
        padding:5% 2%;
    }
    .submitrquest h2,.submitrquest p{
        margin: 15px auto;
    }

    @media (min-width:320px) and (max-width: 768px){
        .invite_button a{
            padding: 5% 2%;
            display: inline-block;
            text-align: center;
            width: 260px;
            margin: 0 auto;
            margin-left: -25px;
        }
    }

</style>
