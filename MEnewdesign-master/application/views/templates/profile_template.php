<?php $this->load->view('includes/header');
?><div class="container"><?php
    $this->load->view('profile/left_menu.php');

    $this->load->view('profile/' . $content);
    ?></div> 
</div><!-- wrap div -->
<!-- <link rel="stylesheet" type="text/css" href="<?php //echo $this->config->item('css_public_path'); ?>cssmenu.min.css.gz"> -->
<link rel="stylesheet" type="text/css" href="<?php echo $this->config->item('css_public_path').'common'.$this->config->item('css_gz_extension'); ?>">
<script>
var api_dashboardEventchangeStatus = '<?php echo commonHelperGetPageUrl('api_dashboardEventchangeStatus');?>';
var api_stateList = '<?php echo commonHelperGetPageUrl('api_stateList');?>';
var api_stateList = '<?php echo commonHelperGetPageUrl('api_stateList');?>';
</script>
<!-- <script src="<?php //echo $this->config->item('js_public_path'); ?>dashboard/cssmenu.min.js.gz"></script> -->
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

