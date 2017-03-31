<script src="<?php echo $this->config->item('js_public_path').'jQuery'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'typeahead'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'jQuery-ui'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'mordernizer'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'bootstrap'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'me-ie7'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'index'.$this->config->item('js_gz_extension'); ?>"></script>
<script src="<?php echo $this->config->item('js_angular_path').'angular'.$this->config->item('js_gz_extension');?>"></script>
<script src="<?php echo $this->config->item('js_public_path').'analytics'.$this->config->item('js_gz_extension'); ?>"></script>

<?php
	if(isset($jsArray) && is_array($jsArray)) {
		foreach($jsArray as $js) {
                  	echo '<script src="'.$js.$this->config->item('js_gz_extension').'"></script>';
                        echo "\n";
                }
	}
?>