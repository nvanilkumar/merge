<html>
<head>
<title><?php echo $pageTitle ?></title>
</head>
<body>
<div class="rightArea">
    <?php if(isset($errors)){
        print_r($errors[0]);
    }else{?>
      <div class="heading float-left">
        <h2>Terms and Conditions: <span><?php echo $eventName;?></span></h2>
      </div>
    <div>
    I declare,confirm and agree as follows:
    <div>
        <?php echo $tncDetails;?>
    </div>
    </div> 
  <?php } ?>
 </div>
</body>
</html>