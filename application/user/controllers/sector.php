<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sector extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('sector_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = SECTORMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$countSector   =  $this->sector_model->getCountSector();
		$target 			  = '.';
		$this->common_model->_paging($countSector, $target,'sector/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->sector_model->getAllSector();
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sector/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('sector/sector');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editSector()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('txtCode', 'Region Code', 'required');
		$this->form_validation->set_rules('txtName', 'Name', 'required');
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sector/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("sector/showlist/".$this->uri->segment(3).'/no_access'));
    }

		$data['list_country']	  	         = $this->sector_model->getActiveCountry();
		
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWSECTOR;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
				$this->txtCode  	      = $this->input->post('txtCode');				
				$this->txtName  	      = $this->input->post('txtName');
								
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				$data['chkCountry'] 	  = array();
				$data['chkSector'] 	      = array();
				$data['chkDes'] 	      = array();
				$data['chkDomestic']      = array();
				$data['chkSecID']    	  = array();
			break;
			
			case "e":
				$this->showTitle      	  = EDITSECTOR;
				$this->btnVal	      	  = UPDATESECTOR;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->sector_model->getCorresSector();
				$this->txtCode  	      = $showVal->vRegCode;				
				$this->txtName  	      = $showVal->vName;
				
				//get selected details code
				$accValArray              = $this->sector_model->getCorresSectorDetails();
				$data['chkCountry']       = $accValArray ['scCountry'];
				$data['chkSector'] 	      = $accValArray ['scSector'];
				$data['chkDes'] 	      = $accValArray ['scDes'];
				$data['chkDomestic']      = $accValArray ['scDomestic']; 
				$data['chkSecID']    	  = $accValArray ['scId'];

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
		
// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' )
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('sector/edit_sector');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->sector_model->insertSector();
				break;
				
				case "e":
					//Update into db
					$this->sector_model->updateSector();
				break;
			}//end switch
			redirect(site_url("sector/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function delsectorentry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SECTORMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->sector_model->deleteSector();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SECTORMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->sector_model->deleteAllSector();
	}//end function 
	
	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->sector_model->deleteSubAndDetails($this->subDetID);
	}//end function

	//search by division or and doc number
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SECTORMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(vRegCode LIKE '".$this->txtSearch." %' OR vRegCode LIKE '% ".$this->txtSearch."'  
									OR vRegCode LIKE '%".$this->txtSearch."%'
									OR vName LIKE '".$this->txtSearch." %' OR vName LIKE '% ".$this->txtSearch."'
								OR vName LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->sector_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'sector/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->sector_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sector/showlist');

		$this->load->view('sector/sector_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SECTORMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(vRegCode LIKE '".$this->txtSearch." %' OR vRegCode LIKE '% ".$this->txtSearch."'  
									OR vRegCode LIKE '%".$this->txtSearch."%'
									OR vName LIKE '".$this->txtSearch." %' OR vName LIKE '% ".$this->txtSearch."'
								OR vName LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->sector_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'sector/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->sector_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'sector/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('sector/sector');
		$this->load->view('general/template_bottom');
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
