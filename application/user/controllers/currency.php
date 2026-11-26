<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Currency extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('currency_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CURRMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Currency','nId'," "), $target,'currency/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->currency_model->getAllCurrencies();
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'currency/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('currency/currency');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editCurrencies()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Currency Name', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'currency/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("currency/showlist/".$this->uri->segment(3).'/no_access'));
    }

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  = ADDNEWCURR;
				$this->btnVal	  = ADDCONTINUE;
				$this->btnVal2	  = SAVEEXIT;
				$this->txtTitle	  = $this->input->post('txtTitle');
				$this->chkValYes  = 'checked="checked"';
				$this->chkValNo	  = '';
				$this->catId	  = '';
			break;
			
			case "e":
				$this->showTitle  = EDITCURR;
				$this->btnVal	  = UPDATECURR;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	        = $this->currency_model->getCorresCurrency();
				$this->txtTitle	    = $showVal->vTitle;
				$this->txtCode	    = $showVal->vCurrcode;
				$this->txtCoin      = $showVal->vCoin;
				$this->txtRate	    = $showVal->vRate;
				$this->txtDecimal	= $showVal->vDecimal;
				
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
			$this->load->view('currency/edit_currency');
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
					$this->extraCond  = " AND nId!='".$this->input->post('nId')."'";
				break;
			}//end switch
			
			//check name already exist or not
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Currency WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."'".$this->extraCond);
			if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = CURREXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('currency/edit_currency');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->currency_model->insertCurrencies();
					break;
					
					case "e":
						//Update into db
						$this->currency_model->updateCurrencies();
					break;
				}//end switch
			}//end else
		}//end else	
	}//end function

	function delcurrency()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CURRMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->currency_model->deleteCurrency();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CURRMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->currency_model->deleteAllCurrency();
	}//end function 

	function filterCurrency()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CURRMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('currname');
		}//end if
		$this->currname = $this->common_model->searchKeyword('currname'); 
		
		switch($this->currname)
		{
			case "0":
				$extraCheck = '';
			break;
			
			default:
				$extraCheck	= " WHERE (vTitle LIKE '".$this->currname." %' OR vTitle LIKE '% ".$this->currname."'  
								OR vTitle LIKE '%".$this->currname."%'
								OR vCurrcode LIKE '".$this->currname." %' OR 	vCurrcode LIKE '% ".$this->currname."'
								OR vCurrcode LIKE '%".$this->currname."%')";
			break;
		}//end switch
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Currency','nId',$extraCheck), $target,'currency/filterCurrency2/'.																																													                                                                              $this->uri->segment(3));
		
		$data['list_value'] = $this->currency_model->getFilterCurrency($extraCheck);
		$this->count_rows   = count($data['list_value']);
// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'currency/showlist');

		$this->load->view('currency/currency_filter',$data);
	}//end function 
	
	function filterCurrency2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CURRMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('currname');
		}//end if
		$this->currname = $this->common_model->searchKeyword('currname');
		
		switch($this->ddlType)
		{
			case "0":
				$extraCheck = '';
			break;
			
			default:
			$extraCheck = " WHERE (vTitle LIKE '".$this->currname." %' OR vTitle LIKE '% ".$this->currname."'  
							OR vTitle LIKE '%".$this->currname."%'
							OR vCurrcode LIKE '".$this->currname." %' OR 	vCurrcode LIKE '% ".$this->currname."'
							OR vCurrcode LIKE '%".$this->currname."%')";
			break;
		}//end switch
		
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Currency','nId',$extraCheck), $target,'currency/filterCurrency2/'.                                                                              $this->uri->segment(3));
		
		$data['list_value'] = $this->currency_model->getFilterCurrency($extraCheck);
		$this->count_rows   = count($data['list_value']);
// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'currency/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('currency/currency');
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
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('Currency','vTitle'," WHERE vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond."");
		if($txtTitle    == $this->txtTitle)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function checkCurrcode()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;
			
			default:
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;
		}//end switch
		$this->txtCurrcode 		= $this->input->post('txtCode');
		$txtCurrcode       	    = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE  vCurrcode='".mysql_real_escape_string($this->txtCurrcode)."' ".$extraCond." ");
		if($txtCurrcode    == $this->txtCurrcode)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function getCurrentRate()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		//parent currency
		$this->pCurrency = $this->currency_model->getIpDetails();
		
		//user selected country
		$this->sCurrency = $this->input->post('curCode');
		echo $this->currency_model->getCurrentExhRate();
	}//end function
}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
