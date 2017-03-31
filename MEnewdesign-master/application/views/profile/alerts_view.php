<div class="rightArea">

      <?php if (isset($messages)) { ?>
        <div id="alertMessage" <?php if($status) { ?>
             class="db-alert db-alert-success flashHide" <?php } else { ?> 
             class="db-alert db-alert-danger flashHide" <?php } ?> >
            <strong>&nbsp;&nbsp;  <?php echo $messages; ?></strong> 
        </div>
    <?php } ?> 
      <div class="heading">
        <h2>Alerts</h2>
      </div>
      <!--Data Section Start-->
         <div class="tablefields">
             <form name="alerts" method="post" is="alerts" action="">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="table-center">
          <thead>
            <tr>
              <th>Alerts Option</th>
              <th>Status</th>
              <th>Change Status</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>In Complete Transactions Report
                <span class="suggestion">(If checked, you will receive mails for incomplete transactions happened.) </span>
              </td>
              <td><?php if($alertDetails['incomplete']['status'] == 1){ ?> Opted <?php } else { ?> Not opted <?php }?></td>
              <td class="alert-chkbtn"><input type="checkbox" name="incomplete" value="1"  <?php if($alertDetails['incomplete']['status'] == 1){ ?> checked="checked"<?php }?>></td>
                
            </tr>
            <tr>
              <td align="left">Daily Transaction(Summary) Report
<span class="suggestion">(If checked, you will receive mails for daily transactions happening for your events once a day.)</span></td>
              <td><?php if($alertDetails['dailytransaction']['status'] == 1){ ?> Opted <?php } else { ?> Not opted <?php }?></td>
              <td class="alert-chkbtn"><input type="checkbox" name="dailytransaction" value="1" <?php if($alertDetails['dailytransaction']['status'] == 1){ ?> checked="checked"<?php }?>></td>
               
            </tr>
            <tr>
              <td align="left">Daily Transaction Report Only When Transactions are done
<span class="suggestion">(If checked, you will receive mails for daily transactions happening for your events once a day only when at least one transaction is done.)</span></td>
              <td><?php if($alertDetails['dailysuccesstransaction']['status'] == 1){ ?> Opted <?php } else { ?> Not opted <?php }?></td>
              <td class="alert-chkbtn"><input type="checkbox" value="1" name="dailysuccesstransaction" <?php if($alertDetails['dailysuccesstransaction']['status'] == 1){ ?> checked="checked"<?php }?>></td>
               
            </tr>
            <tr>
              <td align="left">Ticket Registration Emails
<span class="suggestion">(If checked, you will receive a mail whenever a ticket is booked for your event.)</span></td>
              <td><?php if($alertDetails['ticketregistration']['status'] == 1){ ?> Opted <?php } else { ?> Not opted <?php }?></td>
              <td class="alert-chkbtn"><input type="checkbox" value="1" name="ticketregistration" <?php if($alertDetails['ticketregistration']['status'] == 1){ ?> checked="checked"<?php }?>></td>
               
            </tr>
          </tbody>
        </table>
        <div class="float-right">
          <input type="submit" class="createBtn float-right" name="alertForm" value="Save & Exit"/>
        </div>
             </form>
        
        </div>
    </div>
