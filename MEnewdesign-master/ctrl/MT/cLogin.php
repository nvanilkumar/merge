<?php

include_once("cGlobali.php");

class cLogin {

    public $UserName = "";
    public $Password = "";
    public $Id = 0;
    public $UserId = 0;

//----------------------------------------------------------------------------------------------------

    public function __construct($sUserName, $sPassword) {
        $this->UserName = $sUserName;
        $this->Password = $sPassword;
        $this->Email = $sUserName;
    }

//----------------------------------------------------------------------------------------------------

    public function IsUser() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "SELECT Id, UserName, Password FROM user WHERE (UserName = ? or Email = ?)  AND Password = ? LIMIT 1";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object


            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("sss", $this->UserName, $this->Email, $this->Password);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->Id, $this->UserName, $this->Password); // Bind the result parameters


            $stmt->fetch(); //Fetch values actually into bound fields of the result
            //echo $this->Id;	
            if ($this->Id > 0) {
                //making login function more secured -pH
                // Get the user-agent string of the user.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $user_id = preg_replace("/[^0-9]+/", "", $this->Id);
                $_SESSION['uid'] = $user_id;
                // XSS protection as we might print this value
                //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $this->UserName);
                $username=$this->UserName;
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', $this->Password . $user_browser);
                // Login successful.
                //return $this->Id;
                return true;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//---------------------------------------------------------------------------------------------------------------

	public function IsFBUser($uid,$fbid) {
		
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "SELECT Id, UserName, Password FROM user WHERE `Id`=? LIMIT 1";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object


            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("d", $uid);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->Id, $this->UserName, $this->Password); // Bind the result parameters


            $stmt->fetch(); //Fetch values actually into bound fields of the result
            //echo $this->Id;	
            if ($this->Id > 0) {
                //making login function more secured -pH
                // Get the user-agent string of the user.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $user_id = preg_replace("/[^0-9]+/", "", $this->Id);
                
                //If the user login in our sys with meraevent user details
                $login_urserid = $_SESSION['uid'];
                if (empty($login_urserid)) {
                    $_SESSION['uid'] = $user_id;
                    // XSS protection as we might print this value
                    //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $this->UserName);
                    $username = $this->UserName;
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', $this->Password . $user_browser);
                    $_SESSION['facebook_loggedin'] = $fbid;
                }
                // Login successful.
                //return $this->Id;
                return true;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    
		
	}
//----------------------------------------------------------------------------------------------------
//

//---------------------------------------------------------------------------------------------------------------

	public function IsTwitterGoogleLogin($uid) {
		
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "SELECT Id, UserName, Password FROM user WHERE `Id`=? LIMIT 1";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object


            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("d", $uid);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->Id, $this->UserName, $this->Password); // Bind the result parameters


            $stmt->fetch(); //Fetch values actually into bound fields of the result
            //echo $this->Id;	
            if ($this->Id > 0) {
                //making login function more secured -pH
                // Get the user-agent string of the user.
                $user_browser = $_SERVER['HTTP_USER_AGENT'];
                // XSS protection as we might print this value
                $user_id = preg_replace("/[^0-9]+/", "", $this->Id);
                $_SESSION['uid'] = $user_id;
                // XSS protection as we might print this value
                //$username = preg_replace("/[^a-zA-Z0-9_\-]+/", "", $this->UserName);
                  $username = $this->UserName;
                $_SESSION['username'] = $username;
                $_SESSION['login_string'] = hash('sha512', $this->Password . $user_browser);
                // Login successful.
                //return $this->Id;
                return true;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    
		
	}
//----------------------------------------------------------------------------------------------------
//



    //---------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
//


    //---------------------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------
    public function IsOrganizer() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "Select UserId from organizer where UserId = ? ";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->UserId); // Bind the result parameters

            $stmt->fetch(); //Fetch values actually into bound fields of the result
            $NumOfRows = $stmt->num_rows;

            if ($this->UserId > 0) {
                return $this->UserId;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------

    public function IsDelegate() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "Select UserId from delegate where UserId = ? ";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->UserId); // Bind the result parameters

            $stmt->fetch(); //Fetch values actually into bound fields of the result
            $NumOfRows = $stmt->num_rows;

            if ($this->UserId > 0) {
                return $this->UserId;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//----------------------------------------------------------------------------------------------------
//----------------------------------------------------------------------------------------------------

    public function IsTeamMember() {
        $Success = FALSE;
        $this->UserId = $sUserId;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "Select UserId from TeamMember where UserId = ? ";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();  //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->UserId); // Bind the result parameters

            $stmt->fetch(); //Fetch values actually into bound fields of the result
            $NumOfRows = $stmt->num_rows;

            if ($this->UserId > 0) {
                return $this->UserId;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
    //----------------------------------------------------------------------------------------------------

    public function IsServiceProvider() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "Select UserId from serviceprovider where UserId = ? ";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();        //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->UserId);    // Bind the result parameters

            $stmt->fetch();    //Fetch values actually into bound fields of the result
            $NumOfRows = $stmt->num_rows;

            if ($this->UserId > 0) {
                return $this->UserId;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//----------------------------------------------------------------------------------------------------

    public function IsEditor() {
        $Success = FALSE;

        try {
            $Globali = new cGlobali();
            //$Globali->dbconn = new mysqli($Global->DBServerNameOnly, $Global->DBUserName,$Global->DBPassword,$Global->DBIniCatalog,$Global->portNumber);
            //$Globali->dbconn->connect();

            if ($Globali->dbconn->errno > 0) {
                throw new Exception("Could not connect to DB. Error: " . $Globali->dbconn->error);
            }

            if (!$Globali->dbconn) {
                throw new Exception("Could not connect to DB");
            }


            $myQuery = "Select UserId from editor  where UserId = ? ";


            $stmt = $Globali->dbconn->stmt_init();    // Create a statement object

            $Success = $stmt->prepare($myQuery);    // Prepare the statement for execution
            if (!$Success)
                throw new Exception("Statement couldn't be prepared. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_param("i", $this->Id);    // Bind the parameters

            if (!$Success)
                throw new Exception("Parameters couldn't be bound. Error: " . $Globali->dbconn->error);


            $Success = $stmt->execute();        //Execute Statement
            if (!$Success)
                throw new Exception("Statement couldn't be Executed. Error: " . $Globali->dbconn->error);

            $Success = $stmt->bind_result($this->UserId);    // Bind the result parameters

            $stmt->fetch();    //Fetch values actually into bound fields of the result
            $NumOfRows = $stmt->num_rows;

            if ($this->UserId > 0) {
                return $this->UserId;
            } else {
                return false;
            }
            $Globali->dbconn->close();
            $stmt->close();
        } catch (Exception $Ex) {
            throw $Ex;
        }
    }

//Save
//----------------------------------------------------------------------------------------------------
}

?>