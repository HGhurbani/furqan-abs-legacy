<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Visa_Setup extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('visa_setup_model');
	}


	function showlist()
  {
    $this->load->helper(array('form', 'url'));
    $this->showTitle = VISASETUP;
    $this->common_model->check_admin_authorization();

    $this->session->unset_userdata('backURL');

    $data['list_value'] = $this->visa_setup_model->getVisaListDetails();
// User access aa
    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'visa_setup/showlist');

    $this->load->view('general/template_top');
    $this->load->view('visa_setup/visa_setup',$data);
    $this->load->view('general/template_bottom');

  }//end function

function editVisaItem() 
{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		
		$this->showTitle      = VISASETUP;
		$data['title'] 	      = $this->common_model->get_site_title(); 
		$data['visa_category'] = $this->visa_setup_model->getVisaCategory();
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlVisaCategory', 'Visa Category Required', 'required');
		$this->form_validation->set_rules('txtVisaItem', 'Visa Item Required', 'required');
		
    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'visa_setup/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("visa_setup/showlist/".$this->uri->segment(3).'/no_access'));
    }
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle    = ADDNEWVISAITEM;
				$this->btnVal	    = ADDCONTINUE;
				$this->btnVal2	    = SAVEEXIT;
				$this->ddlVisaCategory = $this->input->post('ddlvisaCategory');
        $this->txtVisaItem =  $this->input->post('txtVisaItem');
				$this->chkValYes    = 'checked="checked"';
				$this->catId	    = '';
			break;
			
			case "e":
				$this->showTitle  = EDITVISAITEM;
				$this->btnVal	  = UPDATEVISAITEM;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId   = intval($this->input->post('nId'));
				}//end if
	

				$showVal	       = $this->visa_setup_model->getCorresVisaItem();
				$this->ddlVisaCategory = $showVal->nCategoryId;
				$this->txtVisaItem = $showVal->vVisaItem;
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
			$this->load->view('visa_setup/edit_visa_setup');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			switch(intval($this->input->post('nId')))
			{
				case "0":
					$this->extraCond  = "";
				break;
				
				default:
					$this->extraCond  = " AND nVId !=".$this->input->post('nId')." ";
				break;
			}//end switch
			
			//check name already exits or not
			
			$chkTitle = $this->db->query("SELECT vVisaItem FROM {PRE}VisaSetup WHERE vVisaItem ='".mysql_real_escape_string($this->input->post('txtVisaItem'))."' AND nCategoryId =" . mysql_real_escape_string($this->input->post('ddlVisaCategory')) . "  ".$this->extraCond) ;

			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = VISAITEMEXISTS;
				$this->load->view('general/template_top',$data);
				$this->load->view('visa_setup/edit_visa_setup');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->visa_setup_model->insertVisaItem();
						
					break;
					
					case "e":
						//update into db
						$this->visa_setup_model->updateVisaItem();
						
					break;
				}//end switch
			}//end else
		}//end else	
} //end function 

function deleteVisaItem() {
		$this->load->helper(array('form', 'url'));
		$this->showTitle = VISASETUP;
		$this->common_model->check_admin_authorization();
		$this->visa_setup_model->deleteVisaItem();
}// end function


}//end class

?>
