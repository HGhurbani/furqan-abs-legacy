<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Graph extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('graph_model');
	}

	function showBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->showBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>