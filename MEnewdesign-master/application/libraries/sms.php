<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

class Sms {

    public $CI;

    public function __construct() {
        $this->CI = &get_instance();
    }

    function functionSendSMS($Mobile, $Message, $RtrnMsg) {
        if ($_SERVER['HTTP_HOST'] == "www.meraevents.com") {
            $ch = curl_init();
            $user = "srinivasrp@cbizsoftindia.com:Mera123";
            $receipientno = $Mobile;
            $senderID = "MERAEN";
            $msgtxt = $Message;
            curl_setopt($ch, CURLOPT_URL, "http://api.mVaayoo.com/mvaayooapi/MessageCompose");
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, "user=$user&senderID=$senderID&receipientno=$receipientno&msgtxt=$msgtxt");
            $buffer = curl_exec($ch);
            if (empty($buffer)) { //echo " buffer is empty "; 
            } else { //echo $buffer; 
            }
            curl_close($ch);

            /* $url = "http://sms.bulkalerts.info/sendurlcomma.asp?user=20014038&pwd=gi64au&senderid=meraevnt&mobileno=".$Mobile."&msgtext=".$Message;
              $url = "http://api.mvaayoo.com/mvaayooapi/MessageCompose?user=srinivasrp@cbizsoftindia.com:Mera123&senderID=MeraEvnt&receipientno=".$Mobile."&msgtxt=".$Message;

              echo '<iframe src="'.$url.'" width="1" height="1" style="display:none;"></iframe>';
              if($RtrnMsg==1)
              {
              echo '<font color="#006600">SMS Sent</font>';
              } */
        }
    }

    

}
