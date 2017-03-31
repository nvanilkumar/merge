<?php $this->load->view('includes/header');
?><div class="container"><?php
    $this->load->view('promoter/left_menu.php');

    $this->load->view($content);
    ?></div> 
</div><!-- wrap div -->
<!--<link rel="stylesheet" type="text/css" href="<?php// echo $this->config->item('css_public_path'); ?>cssmenu.css">-->
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'common'.$this->config->item('css_gz_extension'); ?>">
<script>
var api_dashboardEventchangeStatus = '<?php echo commonHelperGetPageUrl('api_dashboardEventchangeStatus');?>';
var api_reportsExportTransactions = '<?php echo commonHelperGetPageUrl('api_reportsExportTransactions');?>';
</script>
<script src="<?php echo $this->config->item('js_public_path').'dashboard/cssmenu'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'dashboard/customscripts'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'dashboard/main'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'common'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'dashboard/fs-custom'.$this->config->item('js_gz_extension');  ?>"></script>
<?php
if (isset($jsArray) && is_array($jsArray)) {
    foreach ($jsArray as $js) {
        echo '<script src="' . $js .$this->config->item('js_gz_extension'). '"></script>';
        echo "\n";
    }
}
?>
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

