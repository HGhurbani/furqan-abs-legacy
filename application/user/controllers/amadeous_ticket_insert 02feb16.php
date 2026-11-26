<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Amadeous_ticket_insert extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('amadeous_ticket_insert_model');
	}

	function index()
	{
//        $All_tickets = $this->amadeous_ticket_insert_model->getResponse();
//        $All_tickets = $this->amadeous_ticket_insert_model->arrangeArray($All_tickets);

//        $All_tickets = array_unique($All_tickets);
//        $All_tickets = array_slice($All_tickets, 0);

        $this->amadeous_ticket_insert_model->insertTickets($All_tickets);
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
