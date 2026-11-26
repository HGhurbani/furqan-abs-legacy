<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Account_head extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('accounthead_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCHEADMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		//site Name
		$target = '.';
		$this->common_model->_paging($this->accounthead_model->getAllAccheadCount(), $target,'account_head/showlist/'.$this->uri->segment(3));
		$data['list_value']     = $this->accounthead_model->getAllAcchead();
        $data['list_div_value']	= $this->accounthead_model->getActiveDivision();
        $data['list_loc_value'] = $this->accounthead_model->filterLocation($this->ddlDivision);
		$this->count_rows       = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('finance/account_head');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editAcchead()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlAccgroup', 'Account Group Name', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("account_head/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//get active account grouping
		$data['list_acc_group']	  = $this->accounthead_model->getAccgrouping();
		$data['list_div_value']   = $this->accounthead_model->getActiveDivision();

        $data['list_all_flag']    = $this->accounthead_model->getAllocationFlag();
        $data['list_refund_flag'] = $this->accounthead_model->getRefundFlag();

		$data['list_sub_sale']    = $this->accounthead_model->getSubsidarySale();
		$data['list_sub_ledger']  = $this->accounthead_model->getSubsidaryLedger();
		$data['list_sub_type']    = $this->accounthead_model->getSubsidaryType();
		$data['list_sale_type']   = $this->accounthead_model->getSalesType();
		$data['list_lpo']         = $this->accounthead_model->getLpo();
		$data['list_sub_auto']    = $this->accounthead_model->getSubsidaryAuto();

		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  	  	  = ADDNEWACCHEAD;
				$this->btnVal	  	  	  = ADDCONTINUE;
				$this->btnVal2	  	  	  = SAVEEXIT;
		        $data['list_loc_value']   = $this->accounthead_model->getActiveLocation();
				$this->ddlAccgroup 	      = $this->input->post('ddlAccgroup');
				$this->txtClscode 	  	  = $this->input->post('txtClscode');
				$this->txtAcchead 	  	  = $this->input->post('txtAcchead');
				$this->ddlDivision 	  	  = $this->input->post('ddlDivision');
				$this->ddlLocation 	  	  = $this->input->post('ddlLocation');
				$this->txtAccCode 	  	  = $this->input->post('txtAccCode');
				$this->txtAccTitle 	  	  = $this->input->post('txtAccTitle');
				$this->ddlSubsale 	  	  = $this->input->post('ddlSubsale');
				$this->ddlSubledger   	  = $this->input->post('ddlSubledger');
				$this->ddlSubtype     	  = $this->input->post('ddlSubtype');
				$this->ddlLpo   	  	  = $this->input->post('ddlLpo');
				$this->ddlSubAuto     	  = $this->input->post('ddlSubAuto');
				$this->txtPrefix 	  	  = $this->input->post('txtPrefix');
				$this->txtMindigit 	  	  = $this->input->post('txtMindigit');
				$this->txtSublno 	  	  = $this->input->post('txtSublno');

				$this->chkValYes  	  	  = 'checked="checked"';
				$this->chkValNo	  	  	  = '';
				$this->catId	  	  	  = '';
				$data['chkAccCode']       = array();
				$data['chkAccTitle']      = array();
			break;
			
			case "e":
				$this->showTitle  	  	  = EDITACCHEAD;
				$this->btnVal	      	  = UPDATEACCHEAD;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  	  	  = intval($this->input->post('nId'));
				}//end if
				
				$showVal	          	  = $this->accounthead_model->getCorresAcchead();
				$this->ddlAccgroup	  	  = $showVal->nAGroup;
				$this->txtClscode	  	  = $showVal->vHCode;
				$this->txtAcchead	  	  = $showVal->vAcname;
				$this->txtAccCode	  	  = $showVal->vACode;
				$this->txtAccTitle	  	  = $showVal->vTitle;
/*
        $data['chkAccCode']       = $this->accounthead_model->getAcAccounts();
        $data['chkAccTitle']      = $this->accounthead_model->getAcAccountsTitle();
        $data['chkAccTitle']      = $this->accounthead_model->getAcAccountsTitle();
        $data['chkAccTitle']      = $this->accounthead_model->getAcAccountsTitle();
        $data['chkAccID']    	  = $this->accounthead_model->getAcAccountsID();
        $data['chkAccDivision']   = $this->accounthead_model->getAcAccountsDivision();
        $data['chkAccLocation']   = $this->accounthead_model->getAcAccountsLocation();
        $data['chkAllflag']       = $this->accounthead_model->getAcAccountsAllocation();
        $data['chkRefund']        = $this->accounthead_model->getAcAccountsRefund();

        $data['chkAccSubsale']    = $this->accounthead_model->getAcSubsale();
        $data['chkAccSubledger']  = $this->accounthead_model->getAcSubledger();
        $data['chkAccSubtype']    = $this->accounthead_model->getAcSubtype();
        $data['chkAccSubAuto']    = $this->accounthead_model->getAcSubAuto();
        $data['chkAccSaleType']   = $this->accounthead_model->getAcSaleType();
        $data['chkAccPrefix']     = $this->accounthead_model->getAcPrefix();
        $data['chkAccMindigit']   = $this->accounthead_model->getAcMindigit();
        $data['chkAccSublno']     = $this->accounthead_model->getAcSublno();
        $data['chkAccID2']    	  = $this->accounthead_model->getAcAccountsID2();
*/
        $accHeadControlsVal                = $this->accounthead_model->getCorresAccheadControls();

        $data['chkAccCode']       =   $accHeadControlsVal['chkAccCode'];
        $data['chkAccTitle']      =  $accHeadControlsVal['chkAccTitle']; 
        $data['chkAccID']       =   $accHeadControlsVal['chkAccID'];
        $data['chkAccDivision']   =  $accHeadControlsVal['chkAccDivision'];
        $data['chkAccLocation']   =   $accHeadControlsVal['chkAccLocation'];
        $data['chkAllflag']       =   $accHeadControlsVal['chkAllflag'];
        $data['chkRefund']        =  $accHeadControlsVal['chkRefund'];

        $data['chkAccSubsale']    =  $accHeadControlsVal['chkAccSubsale'];
        $data['chkAccSubledger']  =  $accHeadControlsVal['chkAccSubledger'];
        $data['chkAccSubtype']    =   $accHeadControlsVal['chkAccSubtype'];
        $data['chkAccSubAuto']    =  $accHeadControlsVal['chkAccSubAuto'];
        $data['chkAccSaleType']   =   $accHeadControlsVal['chkAccSaleType'];
        $data['chkAccPrefix']     =  $accHeadControlsVal['chkAccPrefix'];
        $data['chkAccMindigit']   =  $accHeadControlsVal['chkAccMindigit'];
        $data['chkAccSublno']     =  $accHeadControlsVal['chkAccSublno'];
        $data['chkAccID2']        =  $accHeadControlsVal['chkAccID2'];
	
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
			$this->load->view('finance/edit_accounthead');
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

            //checking segment
            switch($this->uri->segment(4))
            {
                case "a":
                    //insert into db
                    $this->accounthead_model->insertAcchead();
                    $this->session->unset_userdata('ddlSubsale');
                break;

                case "e":
                    //Update into db
                    $this->accounthead_model->updateAcchead();
                break;
            }//end switch
            redirect(site_url("account_head/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function filterMaingroup()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlGroup    = $this->input->post('ddlGroup');
		$this->ddlAccgroup = $this->input->post('ddlAccgroup');
		
		$data['list_acc_group'] = $this->accounthead_model->filterAccgroup($this->ddlGroup);
		$this->load->view('finance/show_accgroup',$data);
	}//end function
	
	function filterDivision()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->accounthead_model->filterLocation($this->ddlDivision);
		$this->load->view('finance/show_location',$data);
	}//end function

	function delacchead()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCHEADMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->accounthead_model->deleteAcchead();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCHEADMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->accounthead_model->deleteAllacchead();
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
		$this->txtAcchead 		= $this->input->post('txtAcchead');
		$txtAcchead       	    = $this->common_model->get_field_name('AccountHead','vTitle'," WHERE vTitle='".mysql_real_escape_string($this->txtAcchead)."' ".$extraCond." ");
		if($txtAcchead    == $this->txtAcchead)
		{
			echo "false";
		}//end if
		else 
		{ 
			echo "true";
		}//end else
	}//end function 
	
	function viewAcchead()
	{
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		echo $this->accounthead_model->showDetails();
	}//end function
	
	function generateSubLedgerNo()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlSubAuto       = $this->input->post('ddlSubAuto');
		$this->txtPrefix        = $this->input->post('txtPrefix');
		$this->txtMindigit      = $this->input->post('txtMindigit');

		if($this->ddlSubAuto == '1')
		{
			$this->sampleDigit      = str_repeat('0',($this->txtMindigit-1)).'1';	
			$this->txtSublno        = $this->txtPrefix.$this->sampleDigit;
		}//end if
		else
		{
			$this->txtSublno        = $this->input->post('txtSublno');
		}//end else
		echo $this->txtSublno;
	}//end function

	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		
		$this->accounthead_model->deleteSubAndDetails($this->subDetID);
	}//end function

	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCHEADMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->txtSearch 	  = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck = " WHERE(ah.vHCode LIKE '".$this->txtSearch." %' OR ah.vHCode LIKE '% ".$this->txtSearch."'  
							OR ah.vHCode LIKE '%".$this->txtSearch."%' OR ah.vAcname LIKE '".$this->txtSearch." %' 
							OR ah.vAcname LIKE '% ".$this->txtSearch."'  
							OR ah.vAcname LIKE '%".$this->txtSearch."%' OR aha.vTitle LIKE '".$this->txtSearch." %' 
							OR aha.vTitle LIKE '% ".$this->txtSearch."'  
							OR aha.vTitle LIKE '%".$this->txtSearch."%'
							OR aha.vACode LIKE '".$this->txtSearch." %' OR aha.vACode LIKE '% ".$this->txtSearch."'
							OR aha.vACode LIKE '%".$this->txtSearch."%' OR fm.vTitle LIKE '".$this->txtSearch." %' 
							OR fm.vTitle LIKE '% ".$this->txtSearch."'  
							OR fm.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->accounthead_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'account_head/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->accounthead_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');

		$this->load->view('finance/account_head_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ACCHEADMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$this->txtSearch 	  = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck = " WHERE(ah.vHCode LIKE '".$this->txtSearch." %' OR ah.vHCode LIKE '% ".$this->txtSearch."'  
							OR ah.vHCode LIKE '%".$this->txtSearch."%' OR ah.vAcname LIKE '".$this->txtSearch." %' 
							OR ah.vAcname LIKE '% ".$this->txtSearch."'  
							OR ah.vAcname LIKE '%".$this->txtSearch."%' OR aha.vTitle LIKE '".$this->txtSearch." %' 
							OR aha.vTitle LIKE '% ".$this->txtSearch."'  
							OR aha.vTitle LIKE '%".$this->txtSearch."%'
							OR aha.vACode LIKE '".$this->txtSearch." %' OR aha.vACode LIKE '% ".$this->txtSearch."'
							OR aha.vACode LIKE '%".$this->txtSearch."%' OR fm.vTitle LIKE '".$this->txtSearch." %' 
							OR fm.vTitle LIKE '% ".$this->txtSearch."'  
							OR fm.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->accounthead_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'account_head/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->accounthead_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'account_head/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('finance/account_head');
		$this->load->view('general/template_bottom');
	}//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
