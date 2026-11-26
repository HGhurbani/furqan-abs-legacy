<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class sabre_ticket_insert extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('sabre_ticket_insert_model');
	}

/*	function index()
	{
		$ticktResultArray = $this->sabre_ticket_insert_model->getResponse();

        $ticktResultArray = $this->sabre_ticket_insert_model->arrangeArray($ticktResultArray);

        //intsert into db
		$this->sabre_ticket_insert_model->insertTickets($ticktResultArray);
	}//end function
*/

	function index()
	{
        $this->sabre_ticket_insert_model->insertTickets();
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
