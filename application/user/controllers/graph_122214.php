<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Graph extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('graph_model');
		$this->load->model('manage_info_model');
	}

	function gdsBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$data['graph_value']  = $this->graph_model->gdsBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function salesBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->salesBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function airlineBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->airlineBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function hotelBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->hotelBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function locBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->locBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function empBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$data['graph_value']  = $this->graph_model->empBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function cusBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->cusBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function

    function ventBarGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$data['graph_value']  = $this->graph_model->ventBarGraph();

		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	function drawGraph()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$data['graph_value']  = $this->graph_model->drawBarGraph();
		 
		$this->load->view('manage_info/show_graph',$data);
	}//end function
	
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
