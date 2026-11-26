<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Gds extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('gds_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = GDSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Gds','nGId'," "), $target,'gds/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->gds_model->getAllGds();

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'gds/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('gds/gds');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editGds()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Service Type', 'required');
				
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWGDS;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = $this->uri->segment(5);
			break;
			
			case "e":
				$this->showTitle  = EDITGDS;
				$this->btnVal	  = UPDATEGDS;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nGId')) != 0)
				{
					$this->catId	  = intval($this->input->post('nGId'));
				}//end if
				
				$showVal	      = $this->gds_model->getCorresGds();
				$this->txtTitle	  = $showVal->vName;
				if($showVal->vActive == '1')
				{
					$this->chkValYes  = 'checked="checked"';
				}//end if 
				else
				{
					$this->chkValNo   = 'checked="checked"';
				}//end else
			break;
		}//end switch
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('gds/edit_gds');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			switch(intval($this->input->post('nGId')))
			{
				case "0":
					$this->extraCond  = "";
				break;
				
				default:
					$this->extraCond  = " AND nGId!='".$this->input->post('nGId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vName FROM {PRE}Gds WHERE vName='".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = GDSNAMEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('gds/edit_gds');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->gds_model->insertGds();
					break;
					
					case "e":
						//Update into db
						$this->gds_model->updateGds();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delgds()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = GDSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->gds_model->deleteGds();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = GDSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->gds_model->deleteAllGds();
	}//end function 
	
	function filterByGds()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = GDSMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtGdsname = $this->common_model->searchKeyword('gdsname');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('gdsname');
			$this->txtGdsname = $this->common_model->searchKeyword('gdsname');
		}//end if
		
		if(!empty($this->txtGdsname))
		{
			$extraCheck= " WHERE (vName LIKE '".$this->txtGdsname." %' OR vName LIKE '% ".$this->txtGdsname."'  
							OR vName LIKE '%".$this->txtGdsname."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Gds','nGId', $extraCheck), $target,'gds/filterByGds2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->gds_model->getFilterGdsName($extraCheck);
		
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'gds/showlist');

		$this->load->view('gds/gds_filter',$data);
	}//end function
	
	function filterByGds2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = GDSMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtGdsname = $this->common_model->searchKeyword('gdsname');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('gdsname');
			$this->txtGdsname = $this->common_model->searchKeyword('gdsname');
		}//end if
		
		if(!empty($this->txtGdsname))
		{
			$extraCheck = " AND (vName LIKE '".$this->txtGdsname." %' OR vName LIKE '% ".$this->txtGdsname."'  
							OR vName LIKE '%".$this->txtGdsname."%')";
		}//end if
		else
		{
			$extraCheck =" ";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Gds','nGId', $extraCheck), $target,'gds/filterByGds2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->gds_model->getFilterGdsName($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'gds/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('gds/gds');
		$this->load->view('general/template_bottom');
	}//end function

	function checkTitle()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nGId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('Gds','vName'," WHERE  vName='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
		if($txtTitle    == $this->txtTitle)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
