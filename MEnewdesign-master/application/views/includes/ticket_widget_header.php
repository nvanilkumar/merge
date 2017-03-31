
		<META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW">
<link
	href='http://fonts.googleapis.com/css?family=Droid+Serif:400italic'
	rel='stylesheet' type='text/css'>
<!-- <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'bootstrap'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'bootstrap-timepicker'.$this->config->item('css_gz_extension'); ?>"> -->
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'me_custom.min.css'; ?>"> <!--
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'me-ie7'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'reset'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'normalize'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'me-iconfont'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'mnd-style'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'angular/angular'.$this->config->item('css_gz_extension'); ?>"> -->
 <!--<link rel="stylesheet" type="text/css"
	  href="<?php echo $this->config->item('css_public_path').'onscroll-specific'.$this->config->item('css_gz_extension'); ?>">
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'queries'.$this->config->item('css_gz_extension'); ?>">-->
<!--<link rel="stylesheet" type="text/css"
	  href="<?php echo $this->config->item('css_public_path').'angular/autocomplete/angucomplete-alt'.$this->config->item('css_gz_extension'); ?>"> -->
<!--<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'fa-style'.$this->config->item('css_gz_extension'); ?>">-->
<script type="text/javascript">
	var site_url = '<?php echo site_url(); ?>';
	 var https_url = '<?php echo https_url();?>';
	var api_path = '<?php echo $this->config->item('api_path'); ?>';
	var client_ajax_call_api_key = '<?php echo $this->config->item('client_ajax_call_api_key'); ?>';
	var api_getTicketCalculation ='<?php echo commonHelperGetPageUrl('api_getTicketCalculation');?>';
	var api_bookNow = '<?php echo commonHelperGetPageUrl('api_bookNow');?>';
</script>
<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
<!--[if lt IE 9]>
<script src="js/html5shiv.js"></script>
<script src="js/respond.min.js"></script>
<![endif]-->


<link rel="stylesheet"
	  href="<?php echo $this->config->item('css_public_path').'jquery-ui'.$this->config->item('css_gz_extension'); ?>">
<?php
	if(isset($cssArray) && is_array($cssArray)) {
		foreach($cssArray as $cssFile) {
				echo '<link rel="stylesheet" type="text/css" href="'.$cssFile.$this->config->item('css_gz_extension').'">';
				echo "\n";
		}
	}
?>
</head>
<div id="dvLoading" class="loadingopacity" style="display:none;"><img src="<?php echo $this->config->item('images_static_path'); ?>loading.gif" class="loadingimg" /></div>