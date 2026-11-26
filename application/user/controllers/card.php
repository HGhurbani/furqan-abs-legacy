<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Card extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('card_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CARDMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		if($this->uri->segment('5') != '')
			$this->ddlCardAc = $this->common_model->searchKeyword33('5');
			
		else if($this->uri->segment('4') != '' && $this->uri->segment('4') == 'del_suc')
			$this->ddlCardAc = $this->common_model->searchKeyword33('3');
			
		else
			$this->ddlCardAc = $this->common_model->searchKeyword3('ddlCardAc');
			
		$this->ddlCardAc = (trim($this->uri->segment(3)) == '')?0:$this->ddlCardAc;
		
		
		switch($this->ddlCardAc)
		{
			 case "0":
					$extraCheck = " ";
					$this->session->unset_userdata('txtCardName');
/*					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
*/			 break;
			   
			 case "":
					$extraCheck = " ";
					$this->session->unset_userdata('txtCardName');
/*					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
*/			 break;
			   
			 default:
					$extraCheck = " ";
			 break;
		}//end switch

		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Card','nCId',$extraCheck), $target,'card/showlist/'.$this->uri->segment(3));
		
		$data['list_value']   = $this->card_model->getCard($extraCheck);
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'card/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('card/card');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editCard()
	{
		$this->common_model->check_admin_authorization();
		$this->load->helper(array('form', 'url'));
		
		$this->showTitle      	 = CARDMANAGEMENT;
		$data['title'] 	      	 = $this->common_model->get_site_title(); 
		$data['cityList']     	 = array();
		
		$data['list_comm_ac'] 	 = $this->card_model->getCommDocuments();
		$data['list_bank_subac'] = $this->card_model->getBankSubaccounts();
		$data['list_comm_subac'] = $this->card_model->getCommSubaccounts();
		$data['list_card_type'] = $this->card_model->getCardType();
		
		
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtName', 'CARD Name', 'required');
		

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'card/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("card/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle     = ADDNEWCARD;
				$this->btnVal	     = ADDCONTINUE;
				$this->btnVal2	     = SAVEEXIT;
				$this->ddlCardType   = $this->input->post('ddlCardType');
				$this->txtCode	     = $this->input->post('txtCode');
				$this->txtName	     = $this->input->post('txtName');
				$this->txtCommission = $this->input->post('txtCommission');
				$this->ddlCardAc     = $this->input->post('ddlCardAc');
				$this->txtSubCode 	 = $this->input->post('txtSubCode');
				$this->txtSubName 	 = $this->input->post('txtSubName');
				$this->ddlCommision  = $this->input->post('ddlCommision');
				$this->txtSubCode2 	 = $this->input->post('txtSubCode2');
				$this->txtSubName2 	 = $this->input->post('txtSubName2');
				$this->chkValYes     = 'checked="checked"';
				$this->chkValNo	     = '';
				$this->catId	     = '';
			break;
			
			case "e":
				$this->showTitle  = EDITCARD;
				$this->btnVal	  = UPDATECARD;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if
				$showVal	         = $this->card_model->getCorresCard();
				$this->ddlCardType   = $showVal->vCardType;
				$this->txtCode	     = $showVal->vCode;
				$this->txtName	     = $showVal->vName;
				$this->txtCommission = $showVal->nCommission;
				$this->ddlCardAc     = $showVal->nCardAc;
				$this->txtSubCode 	 = $showVal->vSubAc;
				$this->txtSubName 	 = $showVal->vSubName;
				$this->ddlCommision  = $showVal->nCommisionAc;
				$this->txtSubCode2 	 = $showVal->vSubAc2;
				$this->txtSubName2 	 = $showVal->vSubName2;

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
    $this->ddlCardType  = $this->ddlCardType == '' ? 'C' : $this->ddlCardType;
    $data['list_card_ac'] 	 = $this->card_model->getBankDocuments($this->ddlCardType);
		
		if ($this->form_validation->run() == FALSE)
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('card/edit_card');
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
					$this->extraCond  = " AND nCId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//check name already exits or not
			
			$chkCode = $this->db->query("SELECT vCode FROM {PRE}Card WHERE vCode = 
											'".mysql_real_escape_string($this->input->post('txtCode'))."'".$this->extraCond);
			if ($chkCode->num_rows() > 0)
			{
				$data['error_value'] = CARDCODEEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('card/edit_card');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
			
				$chkTitle = $this->db->query("SELECT vName FROM {PRE}Card WHERE 
											vName='".mysql_real_escape_string($this->input->post('txtName'))."'".$this->extraCond);
				if ($chkTitle->num_rows() > 0)
				{
					$data['error_value'] = CARDNAMEEXIST;
					$this->load->view('general/template_top',$data);
					$this->load->view('card/edit_card');
					$this->load->view('general/template_bottom');
				}//end if
				else
				{
					//checking segment
					switch($this->uri->segment(4))
					{
						case "a":
							//insert into db
							$this->card_model->insertCard();
							
						break;
						
						case "e":
							//update into db
							$this->card_model->updateCard();
							
						break;
					}//end switch
				}//end else
			}//end else
		}//end else	
	}//end function

	function delCard()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CARDMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->card_model->deleteCard();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CARDMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->card_model->deleteCardMultiple();
	}//end function 
	
	//show city
	function showcity()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CARDMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->ddlCardAc = $this->input->post('ddlCardAc');
		$this->ddlCity    = $this->input->post('ddlCity');
		
		$data['cityList'] = $this->card_model->getCityList($this->ddlCardAc);
		
		$this->load->view('card/show_city',$data);
	}//end function
	
	function filtershowlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CARDMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtCardName');
			$this->txtCardName = $this->common_model->searchKeyword('txtCardName');
		}//end if
		
		if(!empty($this->txtCardName))
		{
			$extraCheck = " WHERE (vName LIKE '".$this->txtCardName." %' OR vName LIKE '% ".$this->txtCardName."'  
							OR vName LIKE '%".$this->txtCardName."%'
							OR vCode LIKE '".$this->txtCardName." %' OR vCode LIKE '% ".$this->txtCardName."'
							OR vCode LIKE '%".$this->txtCardName."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Card','nCId',$extraCheck.""), $target,'card/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->card_model->getFilterCard($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'card/showlist');

		$this->load->view('card/card_filter',$data);
	}//end function 
	
	function filtershowlist2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CARDMANAGEMENT;
		
		$this->common_model->check_admin_authorization();

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtCardName');
			$this->txtCardName = $this->common_model->searchKeyword('txtCardName');
		}//end if
		
		if(!empty($this->txtCardName))
		{
			$extraCheck = " WHERE (vName LIKE '".$this->txtCardName." %' OR vName LIKE '% ".$this->txtCardName."'  
							OR vName LIKE '%".$this->txtCardName."%'
							OR vCode LIKE '".$this->txtCardName." %' OR vCode LIKE '% ".$this->txtCardName."'
							OR vCode LIKE '%".$this->txtCardName."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else
		
	
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Card','nCId',$extraCheck.""), $target,'card/filterShowlist2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->card_model->getFilterCard($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'card/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('card/card');
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
				$extraCond = " AND nCId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtName 		= $this->input->post('txtName');
		$txtName       	    = $this->common_model->get_field_name('Card','vName'," WHERE  vName='".mysql_real_escape_string($this->txtName)."' ".$extraCond."");
		if($txtName    == $this->txtName)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 

	function checkCode()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nCId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCode 		= $this->input->post('txtCode');
		$txtCode       	    = $this->common_model->get_field_name('Card','vCode'," WHERE  vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond."");
		if($txtCode    == $this->txtCode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 

  function filterCardAcccounts($cardType){

    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();

    $this->ddlCardType = $this->input->post('ddlCardType');
    $data['list_card_type'] = $this->card_model->getBankDocuments($this->ddlCardType);

    $this->load->view('card/show_cardType',$data);

  }// end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php ?>*/
