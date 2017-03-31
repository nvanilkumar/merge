<?php
session_start();

include_once("MT/cGlobali.php");
include 'loginchk.php';

	
$Global = new cGlobali();
$hostname = strtolower($_SERVER['HTTP_HOST']);

$event_ids=array('75024');

$MsgCountryExist = '';
$reg = $_REQUEST[regid];
$email = $_REQUEST[email];
$recptno = $_REQUEST[recptno];
if ($_REQUEST[submit] == "Save") {
    
    $Global->startTrasnaction();
    
   $updateEventSignup = "update eventsignup set paymenttransactionid='" . $_REQUEST['PaymentTransId'] . "', transactionstatus='" . $_REQUEST[PaymentStatus] . "', paymentgatewayid='" . $_REQUEST[PaymentGateway] . "'".$modifiedByString." where id=" . $reg;
    $ResUpEventSignup = $Global->transactionQuery($updateEventSignup);
    if ($_REQUEST[PaymentTransId] != 'A1') {
        //Updateding all ticket details qty
        $sqltck = "select ticketid AS TicketId, ticketquantity AS NumOfTickets from eventsignupticketdetail where eventsignupid=" . $reg;
        $ressqlreg = $Global->SelectQuery($sqltck);
        $ticketCompareValue=0;
        $totalTransactionStatus=true;
        for ($i = 0; $i < count($ressqlreg); $i++) {
            
           
            
            //get the ticket max qty value
           $ticketQuery="select quantity,totalsoldtickets,soldout from ticket where id=". $ressqlreg[$i]['TicketId'];
            $ticketQueryStatus = $Global->SelectQuery($ticketQuery);
            
            //get the temp tickets details
           $tempTicketQuery="select sum(quantity) as total from sessionlock where ticketid=". $ressqlreg[$i]['TicketId']." and deleted=0 and endtime >=now()";
            $tempticketQueryStatus = $Global->SelectQuery($tempTicketQuery);
            $tempTicketValue=0;
            if(isset($tempticketQueryStatus[0]['total']) && $tempticketQueryStatus[0]['total'] > 0){
                $tempTicketValue=$tempticketQueryStatus[0]['total'];
            }
            
           $ticketCompareValue=$ticketQueryStatus[0]['totalsoldtickets']+$ressqlreg[$i][NumOfTickets]+$tempTicketValue;
        
            //checking tickets avilability count before changing the status
            if($ticketQueryStatus[0]['quantity'] > $ticketCompareValue){
              $updateAtt = "update ticket set totalsoldtickets=" .($ticketQueryStatus[0]['totalsoldtickets']
                    + $ressqlreg[$i][NumOfTickets]) . $modifiedByString." where id=" . $ressqlreg[$i]['TicketId'];
                $transactionStatus = $Global->transactionQuery($updateAtt);
            }else if($ticketQueryStatus[0]['quantity'] == $ticketCompareValue){//all tickets sold change the sold out status
               $updateAtt = "update ticket set totalsoldtickets=" .($ticketQueryStatus[0]['totalsoldtickets']
                    + $ressqlreg[$i][NumOfTickets]) . ", soldout=1".$modifiedByString." where id=" . $ressqlreg[$i]['TicketId'];
                $transactionStatus = $Global->transactionQuery($updateAtt);
                
            }else{
                $totalTransactionStatus=false;
            }
        }
        
        //commit the all trasactions
        if($totalTransactionStatus){
            $Global->commitTransactionQuery();
        }
    }
    if ($totalTransactionStatus) {
        ?>
        <script>
            window.location = "CheckReg.php?recptno=<?php echo  $recptno; ?>&email=<?php echo  $email; ?>&msg=Upated Successfully";
        </script>
        <?php } else {
        ?>
        <script>
            window.location = "CheckReg.php?recptno=<?php echo  $recptno; ?>&email=<?php echo  $email; ?>&msg=Unable To Update";
        </script>
    <?php
    }
}


if (isset($_REQUEST[regid]) && $_REQUEST[regid] != "") {
    $signid = " and s.id=" . $_REQUEST[regid];

    //Display list of Successful Transactions
    $TransactionQuery = "SELECT  s.id AS Id, s.signupdate AS SignupDt,e.id as event_id, "
            . "e.title AS Title , s.quantity AS Qty, (s.totalamount/s.quantity) AS Fees, "
            . "s.paymenttransactionid AS PaymentTransId,s.transactionstatus AS PaymentStatus, "
            . "s.paymentgatewayid AS PaymentGateway,c.code currencyCode "
            . "FROM eventsignup AS s INNER JOIN event AS e ON s.eventid = e.id "
            . "INNER JOIN currency c ON c.id=s.fromcurrencyid "
            . "WHERE 1  AND e.deleted = 0 " . $signid . " ";
    $TransactionRES = $Global->SelectQuery($TransactionQuery);
}


$querypaymentGateways = "SELECT `id`, `name` FROM paymentgateway WHERE deleted = 0";
$querypaymentGatewaysRes = $Global->SelectQuery($querypaymentGateways);


$input_status = "";
$select_box_status = "";
if ($TransactionRES[0][PaymentTransId] != 'A1') {
    $input_status = "readonly";
    $select_box_status = "disabled";
}

include 'templates/CheckReg_edit.tpl.php';
?>