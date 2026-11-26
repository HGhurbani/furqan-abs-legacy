<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class hotel_insert extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('hotel_insert_model');
	}

	function index()
	{

		$retString = $this->hotel_insert_model->getResponse();
		echo $retString;
		
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
