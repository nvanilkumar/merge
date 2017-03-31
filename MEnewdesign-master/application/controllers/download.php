<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * Download controller (Downloading Files)
 *
 * @package		CodeIgniter
 * @author		Qison  Dev Team
 * @copyright	Copyright (c) 2016, Meraevents.
 * @Version		Version 1.0
 * @Since       Class available since Release Version 1.0 
 * @Created     01-30-2016
 * @Last Modified On  01-30-2016
 * @Last Modified By  Raviteja
 */
require_once(APPPATH . 'handlers/reports_handler.php');
class Download extends CI_Controller {
    var $reportsHandler;
    public function __construct() {
        parent::__construct();
        $this->reportsHandler = new reports_handler();
    }
	public function downloadCsv(){
		$inputArray = $this->input->get();
		$output = $this->reportsHandler->exportTransactions($inputArray);
		$file = $output['response']['sourcepath'];
		ob_end_clean();
		header('Content-Description: File Transfer');
	    header('Content-Type: "application/octet-stream"');
	    header('Content-Disposition: attachment; filename='.basename($file));
	    header('Content-Transfer-Encoding: binary');
	    header('Expires: 0');
	    header('Pragma: public');
	   header('Content-Length: ' . filesize($file));
	    readfile($file);
	    exit;
	}
}
?>
