<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Fetch_excel_tickets extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('fetch_excel_tickets_model');
	}

/*	function index()
	{
		$ticktResultArray = $this->automatic_ticket_insert_model->getResponse();

        $ticktResultArray = $this->automatic_ticket_insert_model->arrangeArray($ticktResultArray);

        //intsert into db
		$this->automatic_ticket_insert_model->insertTickets($ticktResultArray);
	}//end function
*/

	function index()
	{
        $this->fetch_excel_tickets_model->insertTickets();
	}//end function

	function insertTkts()
	{
    $message = $this->fetch_excel_tickets_model->insertTickets();
    session_start();
    $_SESSION['fetchExcelMsg'] = $message;
    redirect($this->input->post('link'));

	}//end function


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
