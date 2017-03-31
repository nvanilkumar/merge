<?php
error_reporting('-1');
include_once '../../ctrl/MT/cGlobali.php';
include_once '../../ctrl/includes/common_functions.php';
$commonFunctions= new functions();
//print_r($_POST);
//exit();
$sent="no";
if(isset($_POST['Submit']) && $_POST['Submit']=='Register')
{                                       //$to="samuel.phinny@qison.com";
                                        $to="kumard@meraevents.com,sreekanthp@meraevents.com";
                                        $from=$_POST['email'];
                                        $name=$_POST['name'];
                                        $mobile=$_POST['mobile'];
                                        $messageFromUser=$_POST['message'];
                                        $Message="The person's <br/><br/>
                                                        <strong>Name</strong>:$name,<br/><br/>
                                                        <strong>Mobile</strong>:$mobile,<br/><br/>
                                                        <strong>Email</strong>:$from,<br/><br/>
                                                        <strong>Message</strong>:$messageFromUser.";
                                       // $Message=$_POST['message'];
                                        $subject='"Show me how" mail from ticket sales accelerator';
					$cc=NULL;
					$replyto=NULL;
					$conent=NULL;
					$filename=NULL;
					
					$bcc=NULL;
					//$from=$dtlEMailMsgs[0]['SendThruEMailId'];
					
					
				$sucess=	$commonFunctions->sendEmail($to,$cc,$bcc,$from,$replyto,$subject,$Message,$conent,$filename, "event-ticketsales-accelerator");
			if($sucess){
                            $sent="yes";
                        }
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<meta name="description" content="">
<meta name="author" content="">
<title>MeraEvents</title>
<link href="<?php echo $microsite_path;?>/css/bootstrap.css" rel="stylesheet">
<!-- Custom style -->
<link href="<?php echo $microsite_path;?>/css/style.css" rel="stylesheet">

</head>
<body class="RegisterBG">
<!--class="RegisterBG"--> 
<!-- Start home -->
<header>
  <div class="navbar">
    <div class="container"> <a class="navbar-brand" href="#"><img src="<?php echo $microsite_path;?>/img/meraevents-logo.png" alt="" /> </a> </div>
  </div>
</header>
<!-- End header --> 

<!-- Start contact -->
<section id="contact">
  <div class="container">
    <div class="row">
      <div class="col-lg-7">
        <h1 class="toptext">Show me how</h1>
        <form id="contactform" action="register.php" method="post" class="validateform" name="leaveContact">
          <div id="sendmessage" class=" <?php if($sent=="yes"){ echo "show" ;} ?>">
            <div class="alert alert-info marginbot35"> <strong>Your message has been sent. Thank you!</strong><br />
            </div>
          </div>
          <ul class="listForm">
            <li>
              <label>Your name <span>&#40; Required &#41;</span></label>
              <input class="form-control" type="text" name="name" data-rule="maxlen:4" data-msg="Please enter at least 4 chars" />
              <div class="validation"></div>
            </li>
            <li>
              <label>Your email <span>&#40; Required &#41;</span></label>
              <input class="form-control" type="text" name="email" data-rule="email" data-msg="Please enter a valid email" />
              <div class="validation"></div>
            </li>
            <li>
              <label>Your mobile no </label>
              <input class="form-control" type="text" name="mobile" data-rule="" data-msg="Please enter a mobile no" />
              <div class="validation"></div>
            </li>
            <li>
              <label>Your message <span>&#40; Required &#41;</span></label>
              <textarea class="form-control" rows="9" name="message" data-rule="required" data-msg="Please write something for us"></textarea>
              <div class="validation"></div>
            </li>
            <li>
              <input type="submit" value="Register" class="btn btn-dark btn-lg btn-block" name="Submit" />
            </li>
          </ul>
        </form>
      </div>
      <div class="col-lg-5">
        <div class="location">
          <div class="location-address">
            <h6>Versant Online Solutions Pvt.Ltd</h6>
            <p> <strong>Address :</strong> <br />
              2nd Floor, 3 Cube Towers<br>
              Whitefield Road, Kondapur<br>
              Hyderabad, Andhra Pradesh-500084 </p>
            <span class="location-arrow"></span> </div>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- End contact -->

<footer>
  <div class="container">
    <div class="row">
      <div class="col-lg-12"> <span class="copyright">2014 &copy; Copyright <a href="#">Versant Online Solutions Pvt.Ltd.</a></span> </div>
    </div>
  </div>
</footer>
<!-- End footer --> 
<!-- JavaScript plugins (requires jQuery) --> 
<script src="<?php echo $microsite_path;?>/js/jquery.js"></script> 
<!-- Contact validation js --> 
<script src="<?php echo $microsite_path;?>/js/validation.js"></script>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-41640740-3', 'meraevents.com');
  ga('send', 'pageview');

</script>
</body>
</html>