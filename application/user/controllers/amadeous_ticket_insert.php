<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Amadeous_ticket_insert extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('amadeous_ticket_insert_model');
	}

	function index()
	{
		$ticktResultArray = $this->amadeous_ticket_insert_model->getResponse();				$ticktResultArray_len = sizeof($ticktResultArray);		if($ticktResultArray[$ticktResultArray_len-1] == "")			$decduct = 1;		else			$decduct = 0;			$this->amadeous_ticket_insert_model->insertTickets($ticktResultArray,$decduct);

        
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
