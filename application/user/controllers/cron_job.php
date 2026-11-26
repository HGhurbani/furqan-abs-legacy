
<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cron_job extends CI_Controller {

	function __construct()
	{
		parent::__construct();
        $this->load->model('cron_job_model');
	}

	function index()
	{
		$this->load->helper(array('form', 'url'));
        $this->cron_job_model->checkAllocationTable();
        $this->cron_job_model->checkReconciliation();
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>