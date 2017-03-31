<?php

include_once("cGlobali.php");

class cBanners {

    private $TableNm = "Banners";
    public $Id = 0;
    public $Title = '';
    public $EventId = 0;
    public $StartDt = 0;
    public $EndDt = 0;
    public $FileName = '';
    public $URL = '';
    public $Active = 0;
    public $SeqNo = 0;
    public $Main = 0;
    public $Banglore = 0;
    public $Chennai = 0;
    public $Delhi = 0;
    public $Hyderabad = 0;
    public $Mumbai = 0;
    public $Pune = 0;
    public $Kolkata = 0;
    public $Goa = 0;
    public $Jaipur = 0;
    public $Ahmedabad = 0;
    public $AllCities = 0;
    public $OtherCities = 0;
    public $CategoryId = 0;
    public $Entertainment = 0;
    public $Professional = 0;
    public $Training = 0;
    public $Campus = 0;
    public $Spiritual = 0;
    public $TradeShows = 0;
    public $SpecialOccasion = 0;
    public $AllCategories = 0;
    public $AllCitiesAllCategories = 0;
    public $clickscount = 0;
    public $conversion = 0;

    //----------------------------------------------------------------------------------------------------

    public function __construct($lId, $sTitle, $sEventId, $sStartDt, $sEndDt, $sFileName, $sURL, $sActive, $sSeqNo, $sMain, $sBanglore, $sChennai, $sDelhi, $sHyderabad, $sMumbai, $sPune, $sKolkata, $sJaipur, $sGoa, $sAhmedabad, $sAllCities, $sOtherCities, $sCategoryId, $sEntertainment, $sProfessional, $sTraining, $sCampus, $sSpiritual, $sTradeShows, $sSpecialOccasion, $sAllCategories, $sAllCitiesAllCategories, $clickscount = 0, $conversion = 0) {
        $this->Id = $lId;
        $this->Title = $sTitle;
        $this->EventId = $sEventId;
        $this->StartDt = $sStartDt;
        $this->EndDt = $sEndDt;
        $this->FileName = $sFileName;
        $this->URL = $sURL;
        $this->Active = $sActive;
        $this->SeqNo = $sSeqNo;
        $this->Main = $sMain;
        $this->Banglore = $sBanglore;
        $this->Chennai = $sChennai;
        $this->Delhi = $sDelhi;
        $this->Hyderabad = $sHyderabad;
        $this->Mumbai = $sMumbai;
        $this->Pune = $sPune;
        $this->Kolkata = $sKolkata;
        $this->Jaipur = $sJaipur;
        $this->Goa = $sGoa;
        $this->Ahmedabad = $sAhmedabad;
        $this->AllCities = $sAllCities;
        $this->OtherCities = $sOtherCities;
        $this->CategoryId = $sCategoryId;
        $this->Entertainment = $sEntertainment;
        $this->Professional = $sProfessional;
        $this->Training = $sTraining;
        $this->Campus = $sCampus;
        $this->Spiritual = $sSpiritual;
        $this->TradeShows = $sTradeShows;
        $this->SpecialOccasion = $sSpecialOccasion;
        $this->AllCategories = $sAllCategories;
        $this->AllCitiesAllCategories = $sAllCitiesAllCategories;
        $this->clickscount = $clickscount;
        $this->conversion = $conversion;
    }

    //----------------------------------------------------------------------------------------------------

    public function Save() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();


            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            if ($this->Id > 0) {  //Edited Record: Update
                $EditedRecord = TRUE;
            } else {
                $EditedRecord = FALSE;
            }

            $myQuery = " Title = ?, EventId = ?, StartDt = ?, EndDt = ?, FileName = ?, URL = ?, Active = ?, SeqNo = ?, Main = ? , Banglore = ? , Chennai = ? , Delhi = ? , Hyderabad = ? , Mumbai = ? , Pune = ?, Kolkata = ?,Goa=?,Ahmedabad = ?,Jaipur = ?,AllCities = ?, OtherCities = ?, CategoryId=?,Entertainment=?,Professional=?,Training=?,Campus=?,Spiritual=?,TradeShows=?,SpecialOccasion=?,AllCategories=?,AllCitiesAllCategories=?, clickscount=?, conversion=? ";

            if ($EditedRecord) {
                $myQuery = "UPDATE " . $this->TableNm . " SET " . $myQuery . " WHERE Id = ?";
            } else { //New Record: Insert
                $myQuery = "INSERT INTO " . $this->TableNm . " SET " . $myQuery;
            }

            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);


            if ($EditedRecord) {
                $Success = $stmt->bind_param("sissssiiiiiiiiiiiiiiiiiiiiiiiiiiii", $this->Title, $this->EventId, $this->StartDt, $this->EndDt, $this->FileName, $this->URL, $this->Active, $this->SeqNo, $this->Main, $this->Banglore, $this->Chennai, $this->Delhi, $this->Hyderabad, $this->Mumbai, $this->Pune, $this->Kolkata, $this->Goa, $this->Ahmedabad, $this->Jaipur, $this->AllCities, $this->OtherCities, $this->CategoryId, $this->Entertainment, $this->Professional, $this->Training, $this->Campus, $this->Spiritual, $this->TradeShows, $this->SpecialOccasion, $this->AllCategories, $this->AllCitiesAllCategories, $this->clickscount, $this->conversion, $this->Id);    // Bind the parameters
            } else {
                $Success = $stmt->bind_param("sissssiiiiiiiiiiiiiiiiiiiiiiiiiii", $this->Title, $this->EventId, $this->StartDt, $this->EndDt, $this->FileName, $this->URL, $this->Active, $this->SeqNo, $this->Main, $this->Banglore, $this->Chennai, $this->Delhi, $this->Hyderabad, $this->Mumbai, $this->Pune, $this->Kolkata, $this->Goa, $this->Ahmedabad, $this->Jaipur, $this->AllCities, $this->OtherCities, $this->CategoryId, $this->Entertainment, $this->Professional, $this->Training, $this->Campus, $this->Spiritual, $this->TradeShows, $this->SpecialOccasion, $this->AllCategories, $this->AllCitiesAllCategories, $this->clickscount, $this->conversion);
                // Bind the parameters
            }

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

            $Success = $stmt->execute();  //Execute Statement


            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

            $AffectedRows = $stmt->affected_rows;
            if ($AffectedRows > 0) {
                if (!$EditedRecord)
                    $this->Id = $Conn->insert_id;
            }
            else {
                $this->Id = 0;
            }

            $Globali->dbconn->close();
            $stmt->close();

            return $this->Id;
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
    //---------------------------------------------------------------------------------------------------------------

    public function Load() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();


            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            //echo $this->Id;

            $myQuery = "SELECT Id, Title, EventId, StartDt, EndDt, FileName, URL, Active, SeqNo, Main, Banglore, Chennai, Delhi, Hyderabad , Mumbai, Pune, Kolkata,AllCities,OtherCities, CategoryId,Entertainment,Professional,Training,Campus,Spiritual,TradeShows,SpecialOccasion,AllCategories,AllCitiesAllCategories,clickscount, conversion  FROM " . $this->TableNm . " WHERE Id = ?";
            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);

            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

            $Success = $stmt->bind_result($this->Id, $this->Title, $this->EventId, $this->StartDt, $this->EndDt, $this->FileName, $this->URL, $this->Active, $this->SeqNo, $this->Main, $this->Banglore, $this->Chennai, $this->Delhi, $this->Hyderabad, $this->Mumbai, $this->Pune, $this->Kolkata, $this->AllCities, $this->OtherCities, $this->CategoryId, $this->Entertainment, $this->Professional, $this->Training, $this->Campus, $this->Spiritual, $this->TradeShows, $this->SpecialOccasion, $this->AllCategories, $this->AllCitiesAllCategories, $this->clickscount, $this->conversion); // Bind the result parameters

            if ($stmt->fetch()) {  //Fetch values actually into bound fields of the result
                $Success = TRUE;
            } else {
                $Success = FALSE;
            }

            $Globali->dbconn->close();
            $stmt->close();

            return $Success;
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

    //----------------------------------------------------------------------------------------------------

    public function Delete() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();


            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }

            $myQuery = "DELETE FROM " . $this->TableNm . " WHERE Id = ?";

            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Conn->error);


            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters
            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Conn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Conn->error);

            $AffectedRows = $stmt->affected_rows;
            if ($AffectedRows > 0) {
                $Success = TRUE;
            } else {
                $Success = FALSE;
            }

            $Globali->dbconn->close();
            $stmt->close();

            return $Success;
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Delete
    //---------------------------------------------------------------------------------------------------------------
}

//Class
?>