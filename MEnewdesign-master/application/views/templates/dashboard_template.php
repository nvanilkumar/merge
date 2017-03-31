<?php $this->load->view('includes/header');
            if(isset($hideLeftMenu) && $hideLeftMenu!=1) {
	    ?><div class="container"><?php
		$this->load->view('dashboard/left_menu.php');
		
	}else if(isset($hideLeftMenu) && $hideLeftMenu == 1) {
            ?><div class="container"><?php
		$this->load->view('promoter/left_menu.php');
        }
    $this->load->view('dashboard/'.$content);
	if(isset($hideLeftMenu) && $hideLeftMenu!=1) {
	?></div>  <?php
	}else if(isset($hideLeftMenu) && $hideLeftMenu == 1) {
	?></div>  <?php
	}
    ?>

</div><!-- wrap div -->
<script>
var api_promotesetStatus = "<?php echo commonHelperGetPageUrl('api_promotesetStatus')?>";
var api_dashboardEventchangeStatus = '<?php echo commonHelperGetPageUrl('api_dashboardEventchangeStatus');?>';
var api_reportsDownloadImages = '<?php echo commonHelperGetPageUrl('api_reportsDownloadImages');?>';
var api_reportsGetReportDetails = "<?php echo commonHelperGetPageUrl('api_reportsGetReportDetails')?>";
var api_reportsExportTransactions = '<?php echo commonHelperGetPageUrl('api_reportsExportTransactions');?>';
var api_reportsEmailTransactions = '<?php echo commonHelperGetPageUrl('api_reportsEmailTransactions');?>';
var url_dashboardReports = '<?php echo commonHelperGetPageUrl('url_dashboardReports');?>';
var api_collaboratorAdd = '<?php echo commonHelperGetPageUrl('api_collaboratorAdd');?>';
var api_collaboratorUpdateStatus = '<?php echo commonHelperGetPageUrl('api_collaboratorUpdateStatus');?>';
var api_collaboratorUpdate = '<?php echo commonHelperGetPageUrl('api_collaboratorUpdate');?>';
var api_getEvents='<?php echo commonHelperGetPageUrl('api_getEvents');?>';
var api_copyEvent='<?php echo commonHelperGetPageUrl('api_copyEvent');?>';
</script>
<?php
if (isset($hideLeftMenu) && $hideLeftMenu != 1) {
    ?>
    <!-- <link rel="stylesheet" type="text/css" href="cssmenu.css"> -->
    <link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'common'.$this->config->item('css_gz_extension'); ?>">
    <!-- <script src="<?php //echo $this->config->item('js_public_path'); ?>dashboard/cssmenu.js"></script> -->
    <script src="<?php echo $this->config->item('js_public_path').'dashboard/fs-match-height'.$this->config->item('js_gz_extension');  ?>"></script>
    <script src="<?php echo $this->config->item('js_public_path').'dashboard/table-saw'.$this->config->item('js_gz_extension');  ?>"></script>
    <script src="<?php echo $this->config->item('js_public_path').'dashboard/customscripts'.$this->config->item('js_gz_extension');  ?>"></script>
    <script src="<?php echo $this->config->item('js_public_path').'dashboard/main'.$this->config->item('js_gz_extension');  ?>"></script>

<?php
} else {
    ?>
<!--    <link href="<?php echo $this->config->item('css_public_path').'me-iconfont'. $this->config->item('css_gz_extension'); ?>" rel="stylesheet" type="text/css">-->
    <script src="<?php echo $this->config->item('js_public_path').'tabcontent'.$this->config->item('js_gz_extension'); ?>"></script>
    <script src="<?php echo $this->config->item('js_public_path').'dashboard/main'. $this->config->item('js_gz_extension'); ?>"></script>



    <?php
}
?>
<script src="<?php echo $this->config->item('js_public_path').'common'.$this->config->item('js_gz_extension'); ?>"></script>
 
<?php
if (isset($jsArray) && is_array($jsArray)) {
    foreach ($jsArray as $js) {
        echo '<script src="' . $js .$this->config->item('js_gz_extension'). '"></script>';
        echo "\n";
    }
}
?>
<script src="<?php echo $this->config->item('js_public_path').'dashboard/fs-custom'.$this->config->item('js_gz_extension');  ?>"></script>
<script>
// $('#user-toggle').click( function(event){
//        event.stopPropagation();
//        $("#helpdropdown-menu").hide();
//        $('#dropdown-menu').toggle();
//    });
//    $("#help-toggle").click(function () {
//		event.stopPropagation();
//		$('#dropdown-menu').hide();
//        $("#helpdropdown-menu").toggle();
//    });
</script>
</body>
</html>

