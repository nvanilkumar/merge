<?php
include_once("includes/common_functions.php");
$commonFunctions=new functions();
?>
<script language="javascript"> 
  function show(ele) {
      var srcElement = document.getElementById(ele);
      if(srcElement != null) {
		  if(srcElement.style.display == "block") {
			srcElement.style.display= 'none';
		  }
		  else {
			srcElement.style.display='block';
		  }
	  }
      //return false;
  }
  function sustain()
  {
  	document.getElementById(ans2).style.display='block';
  }
  
</script>
<div id="divLeftbox" style="padding-left:5px; padding-right:5px; padding-top:10px;">
<?php
if(isset($_SESSION['uid']))
{
?>
<table cellpadding="0" cellspacing="0" style="table-layout:fixed; width:185px; margin-top:10px;">
	<tr style="font-size:1pt">
		<td class="menucorner"><img src="images/tl.gif" style="display:block" /></td>
		<td class="menutop">&nbsp;</td>
		<td class="menucorner"><img src="images/tr.gif" style="display:block" /></td>
	</tr>
	<tr>
		<td class="menuleft">&nbsp;</td>
		<td style="background-color:#000080">
			<table width="100%" cellpadding="0" cellspacing="0">
					
                                  <?php
                                if($commonFunctions->isSuperAdmin())
                                {
                                 ?>
                                         <tr><td>&nbsp;</td></tr>
                                         
                                        <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans9');return false;" >Super Admin Features</a></td>
					</tr>
                     <tr>
                    <td align="left">
                        <div id= "ans9" align="justify" style="display:none;" >
                        <ul>
                            
                                <li><a class="menuitem" href="editeventsignup.php">Edit EventSignup Details</a></li>
                               
                        <li><a class="menuitem" href="login_org_event.php">login_eventid</a></li>
                        
                       <li><a class="menuitem" href="globalcommisions.php">Global Commissions</a></li>
                        <li><a class="menuitem" href="cancelevent.php">Cancel Event</a></li>
                        <li><a class="menuitem" href="add_gateways_text.php">Add Payment Gateway Text</a></li>
			<li><a class="menuitem" href="addpromotionaltext.php">Add Promotional Text</a></li>
                         </ul>
                         </div>
                        </td></tr>
                      <?php
                                }
                                if($commonFunctions->isSupportTeam()){                                  
                                ?>
                     <tr><td>&nbsp;</td></tr>
                     <tr>
						<td align="left"><a class="menuitem" href="editeventsignup.php">Edit EventSignup Details</a></td>
					</tr>
                                <?php }?>
					<tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a class="menuitem" href="admin.php" onClick="show('ans2');return false;" >Master Management</a></td>
					</tr>
                    <tr>
                    <td align="left">
                        <div id= "ans2" align="justify" style="display:none;" >
                        <ul>
                            <li><a class="menuitem" href="editcountry.php" onClick="sustain();">Country Management</a></li>
                            <li><a class="menuitem" href="editstate.php">State Management</a></li>
                            <li><a class="menuitem" href="editcities.php">City Management</a></li>
                            <!--li><a class="menuitem" href="editdesignation.php">Designation Management</a></li-->
                              <li><a class="menuitem" href="editspcategory.php">Event Category</a></li>
                            <li><a class="menuitem" href="editspsubcategory.php">Event SubCategory</a></li>
                            <!--li><a class="menuitem" href="editsplocation.php">SP Locations</a></li-->
<!--                            <li><a class="menuitem" href="compare_attendees_invoice.php">Compare Attendees/Invoice</a></li>-->
                            <li><a class="menuitem" href="currencies.php">Currency Management</a></li>
                            <li><a class="menuitem" href="user_status.php">User Status</a></li>
                            <li><a class="menuitem" href="auto_suggest_review.php">Auto Suggestion Review</a></li>
                        <!--    <li><a class="menuitem" href="user_registration.php">Excel User Registration </a></li> -->
                        	
                                    
                         </ul>
                         </div>
                        </td></tr>
                        
                        <tr><td>&nbsp;</td></tr> 
                                <tr><td align="left"><a class="menuitem" onClick="show('anstax');return false;" href="taxes.php">Taxes Management</a>                            </td>
                                </tr>
                                <tr><td>
                            <div id= "anstax" align="justify" style="display:none;" >
                        <ul>
                             <li><a class="menuitem" href="taxlabels.php">Tax Labels</a></li>
                            <li><a class="menuitem" href="taxvalues.php">Tax Values</a></li>
                        </ul>
                            </div>
                            </td>
                                </tr>
                         <tr><td>&nbsp;</td></tr> 
                        
                          <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans22');return false;" >Management Report</a></td>
					</tr>
                     <tr>
                    <td align="left">
                        <div id= "ans22"  style="display:none;" >
                        <ul>
                        <li><a class="menuitem" href="report_sales_organizer.php">Sales By Organizer</a></li>
                        <li><a class="menuitem" href="search_by_organizer.php">Search By Organizer</a></li>
                        <li><a class="menuitem" href="report_sales_city.php">Sales By City</a></li>
                        <li><a class="menuitem" href="Transactionreport_Consolidate.php">City SalesPerson</a></li>
                        <li><a class="menuitem" href="report_category_city.php">Events By Category</a></li>
                        <li><a class="menuitem" href="report_cat_cre_city.php">Events Created By Category</a></li>
                        <li><a class="menuitem" href="report_upcoming_events.php">Upcoming Events Report</a></li>
                        <li><a class="menuitem" href="report_date_event_organizer.php">Events Added By Date</a></li>
						 <li><a class="menuitem" href="report_sales_revenue.php">Events Sales and Revenue</a></li>
						  <li><a class="menuitem" href="report_sales_category.php">Sales By Category</a></li>
						   <li><a class="menuitem" href="report_sales_revenue_upcoming.php">Upcoming Events Sales and Revenue</a></li>
                       
							
                         </ul>
                         </div>
                        </td></tr>
                        <tr><td>&nbsp;</td></tr> 
                         
                         <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans21');return false;" >Manage SEO Data</a></td>
					</tr>
                     <tr>
                    <td align="left">
                        <div id= "ans21" align="justify" style="display:none;" >
                        <ul>
                        	<li><a class="menuitem" href="addSEO.php">Add SEO</a></li>
                                 <?php if($commonFunctions->isDeveloper()){ ?>
                                <li><a class="menuitem" href="mappingSEO.php">Mapping SEO</a></li>
                                <?php } ?>
                         </ul>
                         </div>
                        </td></tr>
                        
					<!--tr><td>&nbsp;</td></tr>
					<tr>
						<td align="left"><a class="menuitem" href="" onClick="show('ans3');return false;" >User Management</a></td>
					</tr>
                     <tr>
                    <td align="left">
                        <div id= "ans3" align="justify" style="display:none;" >
                        <ul>
                        	<!--<li><a class="menuitem" href="user.php">User Management HomeManagement</a></li>
                            <li><a class="menuitem" href="edituser.php">Manage Admin-Users</a></li>
                            <li><a class="menuitem" href="editpartners.php">Manage Partners</a></li>
                            <li><a class="menuitem" href="manageuser.php">Manage End Users</a></li>
                            <li><a class="menuitem" href="manageorganisers.php">Manage Organisers</a></li>
                              <li><a class="menuitem" href="manageservicep.php">Manage SP's</a></li>
							<li><a class="menuitem" href="dateReportRegn.php">Registration Report</a></li>
                            <!--li><a class="menuitem" href="view_teammembers.php">Manage Team-Members</a></li>
                         </ul>
                         </div>
                        </td></tr-->
					<!--tr><td>&nbsp;</td></tr>
					
					<tr>
						<td align="left"><a class="menuitem" href="CancelTrans.php?Status=Open">Cancel Transactions</a></td>
					</tr-->
                                   <tr><td>&nbsp;</td></tr>  
                                   <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans4');return false;" >Transactions</a></td>
					</tr>
                     <tr>
                    <td align="left">
                        <div id= "ans4"  style="display:none;" >
                        <ul>
                        <li><a href="Transactionreport.php"  class="menuitem">Transaction Reports</a></li>
                        <li><a class="menuitem" href="OnlyCancelTrans.php?Status=Open">Incomplete Transactions</a></li>
                        <li><a class="menuitem" href="CheckTrans.php">Verify Transactions</a></li>
                       <!--li><a class="menuitem" href="CheckCODTrans.php">Verify COD's</a></li-->
                        <li><a class="menuitem" href="TransbyEvent_new.php?Status=Pending&compeve=1">Gateway Transactions</a></li>
                        <!--li><a class="menuitem" href="duplicateListAdmin.php">Duplicate Transactions</a></li-->
                        <li><a class="menuitem" href="AmountDeposited.php">EBS Settlements</a></li>
                        <li><a class="menuitem" href="AmtDepPaypal.php">Paypal Settlements</a></li>
                        <li><a class="menuitem" href="AmountDepositedCOD.php">Gharpay Settlements</a></li>
                        <li><a class="menuitem" href="AmtDepPaytm.php">Paytm Settlements</a></li>
                        <li><a class="menuitem" href="AmtDepMobikwik.php">Mobikwik Settlements</a></li>
                        <li><a class="menuitem" href="paymentsummary.php">Payment Summary</a></li>
                        <li><a class="menuitem" href="currencyConversion.php">Currency Conversion</a></li>
                        <li><a class="menuitem" href="capture_verify.php">Capture to verify</a></li>
                        <li><a class="menuitem" href="capture_verify_paytm.php">Capture to verify paytm</a></li>
                        <li><a class="menuitem" href="capture_verify_mobikwik.php">Capture to verify mobikwik</a></li>
                        <li><a class="menuitem" href="spot_registration_reports.php">Spot Registration Reports</a></li> 
						<li><a class="menuitem" href="metoorgefforts.php">ME efforts to Org</a></li>
                        </ul>
                         </div>
                        </td></tr>
                         <tr><td>&nbsp;</td></tr> 
                         
                         <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans5');return false;" >Registrations</a></td>
					</tr>
                     <tr>
                    <td align="left">
                        <div id= "ans5" align="justify" style="display:none;" >
                        <ul>
                        <li><a class="menuitem" href="CheckReg.php">Check Registration</a></li>
<!--                        <li><a class="menuitem" href="send_reg_mails.php">Send Registration mails</a></li>-->
                        <li><a class="menuitem" href="spot_registration.php">Spot Registration </a></li>
<!--                        <li><a class="menuitem" href="send_vh1_regemail.php">Send Vh1 Registration mails</a></li>-->
							
                         </ul>
                         </div>
                        </td></tr>
                        <tr><td>&nbsp;</td></tr> 
                        
                        <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans6');return false;" >Event Details</a></td>
					</tr>
               <tr>
               <td align="left">
               <div id= "ans6" align="justify" style="display:none;" >
               <ul>
               <li><a class="menuitem" href="MoveEvents.php">Move Events</a></li>
<!--               <li><a class="menuitem" href="sunburn_events.php">Sunburn Events</a></li>-->
               <li><a class="menuitem" href="EventTickets.php">Event Tickets</a></li>
               <li><a class="menuitem" href="paymentInvoice_new.php">Payment Advice</a></li>
               <li><a class="menuitem" href="paymentInvoice_newyear.php">PaymentAdvice NewYear</a></li>
               <li><a class="menuitem" href="paymentInvoice_new_accounts.php">PaymentAdvice Accounts</a></li>
               <li><a href="future_soldout.php"  class="menuitem">Future SoldOut Events</a></li>
               <li><a href="orgscore.php?Status=Famous"  class="menuitem">Event Score Board</a></li>
               <!--li><a href="eventreminder.php"  class="menuitem">Event Reminder</a></li-->
               <li><a class="menuitem" href="events_of_month.php">Events Of The Month</a></li>
              <li><a class="menuitem" href="events_commision.php">Events Percentages</a></li>
             
              <!--<li><a class="menuitem" href="events_background.php">Events Background</a></li>-->
              <li><a class="menuitem" href="events_seating.php">Theater Seating</a></li>
			  <li><a class="menuitem" href="customTermsAndConditions.php">Terms and Conditions</a></li>
                          <li><a class="menuitem" href="add_event_gateway_text.php">Add Event Payment Gateway Text</a></li>
                          <li><a class="menuitem" href="add_microsite_url.php">Add Microsite URL</a></li>   
                          <li><a class="menuitem" href="apiEvent.php">API Enabling</a></li>     
                           <li><a class="menuitem" href="delete_request.php">Delete Requests</a></li>
                          <!--li><a class="menuitem" href="addpromotions.php">Add promotions</a></li-->
                         
			</ul>
               </div>
               </td></tr>
                 <tr><td>&nbsp;</td></tr> 
                  <tr>
						<td align="left"><a href="" class="menuitem" onClick="show('ans13');return false;">Events Assigning</a></td>
					</tr>
					<tr>
						<td align="left">
                        <div id= "ans13" align="justify" style="display:none;" >
                        <ul>
                        	<li><a class="menuitem" href="sales.php">Add/Edit SalesPerson</a></li>
                            <li><a class="menuitem" href="assignevent.php">AssignEvent</a></li>
                            <li><a class="menuitem" href="monthlytrans.php">MonthlyTransactions</a></li>
                            <li><a class="menuitem" href="qchecking.php">QualityChecking</a></li>
                            <li><a class="menuitem" href="eventchk.php">CheckEventInfo</a></li>
                            <li><a class="menuitem" href="addedevents.php">EventAdded by Date</a></li>
                            <li><a class="menuitem" href="eventsbyenddate.php">Events by EndDate</a></li>


                             
                         </ul>
                         </div>
                        </td>
					</tr>
					<tr><td>&nbsp;</td></tr>
                    
                    	<tr>
						<td align="left"><a class="menuitem" href="SMSEMailManagement.php" onClick="show('ans10');return false;">SMS / EMail Management</a></td>
					</tr>
                    <tr>
						<td align="left">
							<div id= "ans10" align="justify" style="display:none;" >
							<ul>
								<li><a class="menuitem" href="smsCMSManagement.php" onClick="sustain('ans10');">SMS Management</a></li>
								<li><a class="menuitem" href="smsReport.php">SMS Report</a></li>
								<li><a class="menuitem" href="emailCMSManagement.php">EMail Management</a></li>
								<li><a class="menuitem" href="emailReport.php">EMail Report</a></li>
							</ul>
							</div>
						</td>
					</tr>
					<tr><td>&nbsp;</td></tr>
  
                      <tr>
						<td align="left"><a class="menuitem" href="SMSEMailManagement.php" onClick="show('ans20');return false;">Manage Organizers</a></td>
					</tr>
                    <tr>
						<td align="left">
							<div id= "ans20" align="justify" style="display:none;" >
							<ul>
								<li><a class="menuitem" href="manage_organizersnames.php">Organizer Names</a></li>
                                <li><a class="menuitem" href="addorganizer.php">Convert to Organizer</a></li>
								<!--li><a class="menuitem" href="manage_organizersinfo.php">Organizer Info</a></li-->
							</ul>
							</div>
						</td>
					</tr>
                     <tr><td>&nbsp;</td></tr>
                     <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans7');return false;" >Organizer Login</a></td>
					</tr>
               <tr>
               <td align="left">
               <div id= "ans7" align="justify" style="display:none;" >
               <ul>
               <li><a class="menuitem" href="login_org_email.php">login_email</a></li>
               <li><a class="menuitem" href="login_org.php">login_dates</a></li>
               <li><a class="menuitem" href="login_org_event.php">login_eventid</a></li>
               
               </ul>
               </div>
               </td></tr>
                <!--tr><td>&nbsp;</td></tr>
                     <tr>
<td align="left"><a class="menuitem" href="" onClick="show('ans8');return false;" >Venues</a></td>
					</tr>
               <tr>
               <td align="left">
               <div id= "ans8" align="justify" style="display:none;" >
               <ul>
               <li><a class="menuitem" href="venuebanners.php">Banner&Priority List</a></li>
               <li><a class="menuitem" href="MoveVenues.php">Move Venues</a></li>
               <li><a class="menuitem" href="properties.php">Property List</a></li>
               <li><a class="menuitem" href="login_org_event.php">login_eventid</a></li>
               
               </ul>
               </div>
               </td></tr-->
                  <tr><td>&nbsp;</td></tr>   

<tr>
						<td align="left"><a class="menuitem" href="extracharges.php">Extra Charges</a></td>
					</tr>
   <tr><td>&nbsp;</td></tr>   

				
					
				 <!--tr>
						<td align="left"><a class="menuitem" href="export_user.php">Export User List</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr--> 
					  
					
                               
					<!--tr>
						<td align="left"><a class="menuitem" href="<?=_HTTP_SITE_ROOT;?>/PrintBadges.php">Print Badges</a></td>
					</tr>
                                   <tr><td>&nbsp;</td></tr-->
					
					
							
								
				
					<tr>
						<td align="left"><a class="menuitem" href="" onClick="show('ans12');return false;">Manage Banner</a></td>
					</tr>
                                        <tr>
               <td align="left">
               <div id= "ans12" align="justify" style="display:none;" >
               <ul>
               <li><a class="menuitem" href="manage_banner.php">Current Banners</a></li>
               <li><a class="menuitem" href="manage_banner.php?q=past">Past Banners</a></li>               
               </ul>
               </div>
               </td></tr>
			   
               
              <tr><td>&nbsp;</td></tr>
               <tr><td align="left"><a class="menuitem" href="" onClick="show('ans68');return false;">New Year / Holi</a></td></tr>
               
               <tr><td align="left">
               <div id= "ans68" align="justify" style="display:none;" >
               <ul>
                
<!--               <li><a class="menuitem" href="newyeardiscounts.php?btype=newyear">New Year Discounts</a></li>
               <li><a class="menuitem" href="manage_newyear_banner.php?btype=holi">Holi Banners</a></li>-->
               <li><a class="menuitem" href="newyeardiscounts.php?btype=holi">Holi Discounts</a></li>                 
               </ul>
               </div>
               </td></tr>
               
               
               
               

<!--					<tr><td>&nbsp;</td></tr>-->

                                    <!--tr>
						<td align="left"><a class="menuitem" href="manage_sidebanner.php" onClick="show('ans17');return false;">Manage SideBanner</a></td>
					</tr>
                                        <tr>
               <td align="left">
               <div id= "ans17" align="justify" style="display:none;" >
               <ul>
               <li><a class="menuitem" href="manage_sidebanner.php">Current SideBanners</a></li>
               <li><a class="menuitem" href="manage_sidebanner.php?q=past">Past SideBanners</a></li>               
               </ul>
               </div>
               </td></tr-->
					<tr><td>&nbsp;</td></tr>

                               

                      <tr>
                        <td align="left"><a class="menuitem" href="custom_field_event.php">Export CustomFields</a></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>   
                
<!--                    <tr>
                        <td align="left"><a class="menuitem" href="editmoozup.php">MoozupConnect</a></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>   
                    <tr>
                        <td align="left"><a class="menuitem" href="auto_newletter.php">AutoNewsletter</a></td>
                    </tr>
                    <tr><td>&nbsp;</td></tr>   -->
                    
                    <!--this feature is disabled sales team need to provide folder to us we need t upload folder using test/upload controller -->
<!--                   <tr>
                        <td align="left"><a class="menuitem" href="uploademailer.php">Upload Emailer</a></td>
                    </tr>-->
                   
                    
                    
                    <tr><td>&nbsp;</td></tr>                   
                            <tr>
                <td align="left"><a class="menuitem" href="" onClick="show('ans25');return false;" >Digital Marketing</a></td>
        </tr>
            <tr>
                <td align="left">
                    <div id= "ans25" align="justify" style="display:none;" >
                        <ul>
                            <li><a class="menuitem" href="digital_monthly_reports.php">Monthly Transaction Reports</a></li>
                            <li><a class="menuitem" href="uploademailer.php">Upload Emailer</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
            
            
<!--                    <tr><td>&nbsp;</td></tr> -->
<!--                    <tr>
                        <td align="left"><a class="menuitem" href="addeditor.php">Create Editor</a></td>
                    </tr>-->

                    <tr><td>&nbsp;</td></tr>
<!--					<tr>
						<td align="left"><a class="menuitem" href="change_pass.php">Change Password</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>-->
                   
                    <tr>
<!--			<td align="left"><a class="menuitem" href="updateTES.php">Add/Delete TES</a></td>-->
		    </tr>
                    <tr><td>&nbsp;</td></tr>
                    
                    
                     <!--<tr>
						<td align="left"><a class="menuitem" href="pmimembers.php">Add/Delete PMI</a></td>
					</tr>
                    <tr><td>&nbsp;</td></tr>-->
                    
                                  
                        <tr><td>&nbsp;</td></tr> 
				</table>
																
		</td>
		<td class="menuright">&nbsp;</td>
	</tr>

	<tr style="font-size:1pt">
		<td class="menucorner"><img src="images/bl.gif" style="display:block" /></td>
		<td class="menubottom">&nbsp;</td>
		<td class="menucorner"><img src="images/br.gif" style="display:block" /></td>
	</tr>
        
        
        <tr><td>&nbsp;</td></tr>

</table>
<?php
}
else
{
?>
	<script>
		window.location="login.php";
	</script>
<?php
}
?>	
</div>

<!--tr>
						<td align="left"><a class="menuitem" href="banner.php">Ad Banner Management</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr-->
					
					<!--<tr>
						<td align="left"><a class="menuitem" href="payment_approval.php">Payment Approval</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>-->
					
                    <!--tr>
						<td align="left"><a href="" class="menuitem" onClick="show('ans1');return false;">Events</a></td>
					</tr>
					<tr>
						<td align="left">
                        <div id= "ans1" align="justify" style="display:none;" >
                        <ul>
                        	<li><a class="menuitem" href="eventapproval.php">Event Approval</a></li>
                            <li><a class="menuitem" href="event_cancellation.php">Event Cancellation</a></li>
                             <li><a class="menuitem" href="recommended_events.php">Recommended Events</a></li>
                             <li><a class="menuitem" href="popular_events.php">Popular Events</a></li>
                         </ul>
                         </div>
                        </td>
					</tr>
					<tr><td>&nbsp;</td></tr-->
					
					<!--<tr>
						<td align="left"><a class="menuitem" href="" onClick="show('ans4');return false;">Promotion Code</a></td>
					</tr>
					<tr>
						<td align="left">
                        <div id= "ans4" align="justify" style="display:none;" >
                        <ul>
                        	<li><a class="menuitem" href="generate_promo.php">Manage Promo Code</a></li>
                            <li><a class="menuitem" href="search_promo.php">Search Promo Code</a></li>
                         </ul>
                         </div>
                       </td>
					</tr>
					<tr><td>&nbsp;</td></tr>-->
					
					<!--<tr>
						<td align="left"><a class="menuitem" href="transaction.php">Transaction Report</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>-->
					
					<!--<tr>
						<td align="left"><a class="menuitem" href="pendingstatus.php">Pending Status Report</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>-->
                    	<!--<tr>
						<td align="left"><a class="menuitem" href="sms_manage.php">SMS Settings</a></td>
					</tr>
					<tr><td>&nbsp;</td></tr>-->
                    <!--<tr>
						<td align="left"><a class="menuitem" href="manage_plan.php">Manage Plans</a></td>
					</tr>

					<tr><td>&nbsp;</td></tr>-->