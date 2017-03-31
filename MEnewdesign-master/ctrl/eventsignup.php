<?php

 
include_once("MT/cGlobali.php");
class eventsignup {

    public $globali = '';
    public $commonCustomFieldMapping;

    public function __construct() {
        $this->globali = new cGlobali();
       $this->init();
    }
    
    public function init(){
        //CountryId
        $this->commonCustomFieldMapping = array("Full Name" => "Name",
            "Email Id" => "EMail",
            "Mobile No" => "Phone",
            "Address" => "Address",
            "State" => "StateId",
            "City" => "CityId",
            "Pin Code" => "PIN"
        );
    }
    //To get the event related ticket information
    //Check attendeeid before run this function in eventsingup
    public function getPrimaryAttendeDetails($eventSignupId) {
        $eventSignupDetails="";
        
        if ($eventSignupId > 0) {

        $query = "select c.name,ad.value,ad.id as attendeeDetailsId,es.eventid as EventId,es.signupdate,es.promotercode
                        from eventsignup as es
                        join attendeedetail as ad on ad.attendeeid =es.attendeeid
                        join commonfield as c on c.id =ad.commonfieldid
                        where  ad.commonfieldid >0 and es.deleted =0 and es.id=" . $eventSignupId;
            $eventSignupDetails = $this->globali->SelectQuery($query, MYSQLI_ASSOC);
        }
        return $eventSignupDetails;

    }
    
    //To format eventsignup details 
    public function formatEventSignupDetails($details) {
        $ouptDetails = array();
        if (count($details) > 0) {
            foreach ($details as $key => $values) {

                if ($key == 0) {
                    $ouptDetails[0]['EventId'] = $values['eventid'];
                    $ouptDetails[0]['SignupDt'] = $values['signupdate'];
                    $ouptDetails[0]['ucode'] = $values['promotercode'];
                }

                if (array_key_exists($values['name'], $this->commonCustomFieldMapping)) {
                    $arrayKey = $this->commonCustomFieldMapping[$values['name']];
                    $ouptDetails[0][$arrayKey] = $values['value'];
                    $ouptDetails[0][$arrayKey.'Id'] = $values['attendeeDetailsId'];
                }
            }
        }
        return $ouptDetails;
    }
    
    //To get the all attendee details related to given signup
    public function getAttendeeList($eventSignupId) {
        $attendeeList = "";
        if ($eventSignupId > 0) {

            $query = "select ad.value ,a.id,ad.commonfieldid
                       from attendee as a 
                       join attendeedetail as ad on ad.attendeeid=a.id
                       where a.eventsignupid=" . $eventSignupId." order by a.id" ;
            $attendeeList = $this->globali->SelectQuery($query, MYSQLI_ASSOC);
        }
        return $attendeeList;
    }
    
    //Format the getAttendeelist data
    //Id`,`Name`,`Email
    public function formatAttendeelist($list) {
        $ouptDetails = array();
        if (count($list) > 0) {
            $outputKey = 0;
            $previousId = 0;
            foreach ($list as $values) {
                if ($previousId != $values["id"]) {
                    $previousId = $ouptDetails[$outputKey]['Id'] = $values["id"];
                    $ouptDetails[$outputKey]['Name'] = $values["value"];
                }
                //Storing the email
                if ($previousId == $values["id"] && $values["commonfieldid"] == 2) {
                    $ouptDetails[$outputKey]['Email'] = $values["value"];
                    $outputKey++;
                }
            }
        }
        return $ouptDetails;
    }
    //To get the all eventsignup ids which are mathed to passed email id
    public function getPrimaryEmailEventSignupIds($email){
        $query = "select a.eventsignupid
                    from attendee as a 
                    join attendeedetail as ad on ad.attendeeid=a.id
                    where ad.value ='".$email."' and ad.commonfieldid=2 and a.primary =1
                    order by a.eventsignupid" ;
            $singupList = $this->globali->SelectQuery($query, MYSQLI_ASSOC);
            return $singupList;
        
    }
    
    public function formatEventSignupids($list){
        $singupList="";
        if(count($list) > 0){
            foreach($list as $value){
                $singupList.=$value['eventsignupid'].',';
            }
            $singupList=substr($singupList, 0, -1);
        }
        return $singupList;
    }

}

        
?>