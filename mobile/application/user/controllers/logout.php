<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Logout extends CI_Controller {

	function __construct()
	{
		parent::__construct();
	}

	function index()
	{
		$this->load->library('session');
		$this->session->sess_destroy();
		//$this->session->unset_userdata($newdata);	
		$this->db->where('nUser', $this->session->userdata('AbsAdmin'));
		$this->db->where('vType', 'Admin');
		$this->db->delete('{PRE}Online'); 				
						
		
		$this->db->delete('{PRE}Online', array('nUser' => $this->session->userdata('AbsAdmin'))); 
		
		$this->load->helper('url');
		redirect(site_url("login"));		
	}
}


/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>