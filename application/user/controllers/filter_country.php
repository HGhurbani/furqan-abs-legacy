<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Filter_country extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('student_model');
	}

	function filterState()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		
		$this->common_model->check_admin_authorization();
		//site Name
		 
		$data['title'] 	   = $this->common_model->get_site_title(); 
		
		$this->ddlState    = $this->input->post('ddlCountry');
		
		$data['list_state_value'] = $this->student_model->filterstate();
		
		$this->load->view('state/show_state',$data);
	}//end function
	
	/*function filterlocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->showTitle = 'Location Management';
		
		
		$this->common_model->check_admin_authorization();
		//site Name
		
		$this->ddlCountry =$this->input->post('ddlCountry');
		$this->ddlState   = $this->input->post('ddlState');
	
		switch($this->ddlCountry)
		{
			case "0":
				$extraCheck = '';
			break;
			
			default:
				$extraCheck = " AND nCountry ='".$this->ddlCountry."' AND nState ='".$this->ddlState."'";
			break;
		}//end switch
		//echo $extraCheck;die();
		
//active countries
		$locArr = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive='1' ".$extraCheck."
													ORDER BY vName ASC");
													
		$locArrRow = array();
		
		$locArrRow[0] = '--Select--';
		if ($locArr->num_rows() > 0)
		{
			foreach ($locArr->result() as $key=>$row)
			{
				$locArrRow[$row->nLId] = $row->vName;
			}//end for each
		} //end if
		else
		{
			$locArrRow[0] = 'No Value';
		}//end else
		
		$data['list_loc_value'] = $locArrRow;
		
		$this->load->view('state/show_location',$data);
		
	}//end function
*/
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>