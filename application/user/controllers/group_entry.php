<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Group_entry extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('group_entry_model');
		$this->load->model('group_entry_model');
        $this->load->model('cron_job_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = GROUP;
		$this->common_model->check_admin_authorization();

       // $this->cron_job_model->checkAllocationTable();

        $this->session->unset_userdata('backURL');

		//$countCashbookReceipt =  $this->group_entry_model->getCountCashbookReceipt();
		//$target 			  = '.';
		//$this->common_model->_paging($countCashbookReceipt, $target,'group_entry/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->group_entry_model->getAllGroupDetails();
		$this->count_rows     = count($data['list_value']);

///User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'group_entry/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('group_entry/group_entry');
		$this->load->view('general/template_bottom');
	}//end function
	function editEntry(){
		
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlLocation', 'Location', 'required');
    $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'group_entry/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("group_entry/showlist/".$this->uri->segment(3).'/no_access'));
		    
    }
		$data['list_div_value']   		 = $this->group_entry_model->getActiveDivision();
		$data['list_trans_value']    	 = $this->group_entry_model->getActiveTransactions();
		$data['list_st_type']    	 = $this->group_entry_model->getActive_st();
		$data['list_st_type_v']    	 = $this->group_entry_model->getActive_st_v();
		$data['list_account']	  	  	 = $this->group_entry_model->getActiveCustomer();
		$data['list_account_multiple']   = $this->group_entry_model->getAccountMultipleValues();
		$data['list_sub_acc']	  	  	 = $this->group_entry_model->getActiveSubAccounts();
		$data['list_doc']	  	  	 = $this->group_entry_model->getActiveSubAccounts();
		 
		
		
		
		switch($id)
		{
			case "a":
				$this->showTitle   		  = "ADD NEW ENTRY";
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
		        $data['list_loc_value']   = $this->group_entry_model->getLocation();
                
                $this->ddlLocation	  	  = $this->input->post('ddlLocation');
                $this->txtDate1      	  = date('d-m-Y');
                $this->ddlTransaction  	  = $this->input->post('ddlTransaction');
				$this->txtDocNumber  	  = $this->input->post('txtDocNumber');
                $this->txtRefDate     	  = $this->input->post('txtRefDate');
                $this->ddlAccount		  = $this->input->post('ddlAccount');
                
                $this->txtRefName         = $this->input->post('txtRefName');
                $this->txtRemarks         = $this->input->post('txtRemarks');
				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				
                $this->chkSelect   		  = 'checked="checked"';

			    $data['chkDetailID']        = array();
				$data['chkRAmount']        = array();
				$data['chkDes']           = array();
				$data['chkdate']         = array();
                $data['chkBAmount']      = array();
                $data['chkLoc']           = array();
				 $data['chkdoc']           = array();
	break;
			
			case "e":
				$this->showTitle      	  = "EDIT GROUP ENTRY";
				$this->btnVal	      	  = "UPDATE GROUP ENTRY";
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if
                $showVal	          	  	  = $this->group_entry_model->getCorresGroups();
				$this->ddlDivision	  	  	  = 1;
				$this->ddlLocation	  	  	  = $showVal->loc;

				$this->txtDate1      	  	  = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
				$this->ddlTransaction  	  	  = $showVal->status;
				$this->txtDocNumber  	  	  = $showVal->groupno;
				$this->remarks 	  	  	  = $showVal->remarks;
				$this->stk_t 	  	  = $showVal->stk_t;
				$this->stk_h 	  	  = $showVal->stk_h;
				$this->t_bal 	  	  = $showVal->t_bal;
				$this->h_bal 	  	  = $showVal->h_bal;
				$this->doc 	  	  = $showVal->doc;
				$this->t_adt 	  	  = $showVal->t_adt;
				$this->t_cld 	  	  = $showVal->t_cld;
				$this->t_inf 	  	  = $showVal->t_inf;
				$this->tot_t  		  	  = $showVal->tot_t;			
				$this->h_sgl   	  = $showVal->h_sgl;
				$this->h_dbl   	  = $showVal->h_dbl;
				$this->h_tpl   	  = $showVal->h_tpl;
				$this->h_suite   	  = $showVal->h_suite;
				$this->h_oths   	  = $showVal->h_oths;
				$this->h_tot   	  = $showVal->h_tot;
				$this->txtadoc=$showVal->adoc;
				
        $this->txtRefDate = ( $showVal->dot == '0000-00-00'  || $showVal->dot == '' )?'':date('d-m-Y',strtotime($showVal->dot));

				$this->totalamt 	  	  = $showVal->totalamt;
				
				$this->txtRefName         	  = $showVal->paxname;
				$this->ctc1         	  = $showVal->ctc1;
				$this->ctc2         	  = $showVal->ctc2;
				$this->ctc3         	  = $showVal->ctc3;
				$this->ddlAccount         	  = $showVal->acc;
				$docNumber 			= @split('-',$this->ddlAccount);
				$this->ddlAc=$docNumber[0];
				$this->tbl=$docNumber[1];
				$this->ddlsubAccount=$showVal->acc_head;
				$this->received 	  	  = $showVal->recived;
				 $data['list_doc']   	  = $this->group_entry_model->getCRV($this->ddlAc,$this->tbl,$this->ddlLocation);
				
		       $data['list_loc_value']   	  = $this->group_entry_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                 	  = $showVal->id;

				//get selected details code
                $this->chkSelect   		  	  = 'checked="checked"';
				
				$accValArray              	  = $this->group_entry_model->getCorresGROUPsDetails();
				
				$data['chkDetailID']        = $accValArray['acDetailID'];
				$data['chkRAmount']        = $accValArray['cashDetailsRAmount'];
				$data['chkDes']           = $accValArray['acDes'];

				$data['chkdate']         = $accValArray['acDate'];
                $data['chkBAmount']      = $accValArray['cashDetailsBAmount'];
                $data['chkLoc']           = $accValArray['acDetailID'];
				 $data['chkdoc']           = $accValArray['cashDetailsdoc'];
				
			

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
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate1')))) != 1))
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('group_entry/edit_entry');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->group_entry_model->insertGroupEntry();
				break;
				
				case "e":
					//Update into db
					$this->group_entry_model->updateGroupEntry();
				break;
			}//end switch
			redirect(site_url("group_entry/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	

		
	}
	

/*    function getDocumentNumber()
    {
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='11'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}CashReceipt ORDER BY nCRId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='11'");
            $countVal 			= $this->txtDocNumber;
        endif;

        return($countVal);
    }//end function
*/
	function delgroupentry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKRECEIPTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->group_entry_model->deletegroupsentry();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKRECEIPTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->group_entry_model->deleteAllCashbookReceipt();
	}//end function 
	
	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->group_entry_model->filterLocation($this->ddlDivision);
		$this->load->view('cashbook_receipt/show_location',$data);
	}//end function
	
	function filterdoc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->txtDocNumber = $this->input->post('txtDocNumber');
		
		echo $dat = $this->group_entry_model->filterdoc($this->txtDocNumber);
		//return $dat;
		
	}//end function 
	function filtert_qan()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->stk_t = $this->input->post('stk_t');
		
		echo $dat = $this->group_entry_model->t_quat($this->stk_t);
		//return $dat;
		
	}//end function
	function filterh_qan() 
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		
		$this->stk_h = $this->input->post('stk_h');
		
		echo $dat = $this->group_entry_model->h_quat($this->stk_h);
		//return $dat;
		
	}//end function

    function filterMultipleLocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_mloc_value'] = $this->group_entry_model->filterLocation($this->ddlDivision);
		$this->load->view('cashbook_receipt/show_location_multiple',$data);
	}//end function

    function getAllocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

        $this->session->unset_userdata('AccVal');
        $this->session->unset_userdata('TableVal');

		$this->finishbtnVal	  = FINISH;
		$this->ddlAccount2    = $this->input->post('ddlAccount2');
		$this->ddlSubAccount2 = $this->input->post('ddlSubAccount2');
		$this->idVal          = $this->input->post('idVal');
        $this->tableVal       = $this->input->post('tableVal');

        $this->session->set_userdata('AccVal',$this->ddlAccount2);
        $this->session->set_userdata('TableVal',$this->tableVal);

		$data['list_allc'] = $this->group_entry_model->filterAllocation($this->ddlAccount2,$this->ddlSubAccount2);
        $this->load->view('cashbook_receipt/show_allocation',$data);
	}//end function

    function insertAllocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		$this->group_entry_model->insertMultipleAllocation();
	}//end function

    function filterSubAccount()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount2');
        $accType = $this->input->post('tableType');

		$data['list_subacc_multiple'] = $this->group_entry_model->filterSubAccount($accName,$accType);
		$this->load->view('cashbook_receipt/show_sub',$data);
	}//end function
	
	//get cash receipt details according to the A/c code selected
	function getCashreceiptDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtAcccode = $this->input->post('txtAcccode');
		$this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE vACode='".$this->txtAcccode."'"); 
		echo $this->txtTitle;
	}//end function

 	function getCurrencyDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//fetch currency details
		$this->ddlCurrencyMain  = $this->input->post('ddlCurrencyMain');
		$this->transId			= $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->ddlCurrencyMain."'");
		$this->txtRate          = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->transId."'");
		
		$this->load->view('cashbook_receipt/show_currency_details');
	}//end function
	
	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtRate 	 = $this->group_entry_model->getRate($this->ddlCurrency);

        echo $this->txtRate;
    }//end function

	function convertCurrency()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->fcAmount         = $this->input->post('fcAmt');
        $this->ddlCurrencyMain  = $this->input->post('selCurrency');
        $this->selCurrency      = $this->common_model->currencyCode($this->input->post('selCurrency'));
        //default currency code
        $this->defCurrency      = $this->common_model->currencyCode($this->common_model->getCompanyCurr());

        //calculate rate
        $convRate               = $this->sales_closing_model->onlineCurrencyConverstion();

        //print_r($convRate);
        echo json_encode($convRate);
	}//end function

	//function for update database
	function deleteRecord()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->subDetID = $this->input->post('subCode');
		$this->group_entry_model->deleteSubAndDetails($this->subDetID);
	}//end function

	//search by division or and doc number
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = "GROUPS";
		
		$this->common_model->check_admin_authorization();
		
		$this->txtPassName  = $this->common_model->searchKeyword('txtPassName');
		$this->txtdoc  = $this->common_model->searchKeyword('txtdoc');
		$this->txtfile  = $this->common_model->searchKeyword('txtfile');
		$this->txtstk  = $this->common_model->searchKeyword('txtstk');
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtPassName');
			$this->session->unset_userdata('txtdoc');
			$this->session->unset_userdata('txtfile');
			$this->session->unset_userdata('txtstk');
			
			$this->txtPassName  = $this->common_model->searchKeyword('txtPassName');
		$this->txtdoc  = $this->common_model->searchKeyword('txtdoc');
		$this->txtfile  = $this->common_model->searchKeyword('txtfile');
		$this->txtstk  = $this->common_model->searchKeyword('txtstk');
		}//end if
		
		if(!empty($this->txtPassName))
		{
			$extraCheck 	= " AND(gr.paxname  LIKE '".$this->txtPassName." %' OR gr.paxname LIKE '% ".$this->txtPassName."'
									OR gr.paxname LIKE '%".$this->txtPassName."%')";
		}//end if
		
		if(!empty($this->txtdoc))
		{
			$extraCheck 	.= " AND(gr.adoc  LIKE '".$this->txtdoc." %' OR gr.adoc LIKE '% ".$this->txtdoc."'
									OR gr.adoc LIKE '%".$this->txtdoc."%')";
		}//end if
		if(!empty($this->txtfile))
		{
			$extraCheck 	.= " AND(gr.groupno  LIKE '".$this->txtfile." %' OR gr.groupno LIKE '% ".$this->txtfile."'
									OR gr.groupno LIKE '%".$this->txtfile."%')";
		}//end if
		if(!empty($this->txtstk))
		{
			$extraCheck 	.= " AND(gr.t_g=".$this->txtstk."  ) OR (gr.h_g=".$this->txtstk."  )";
									/*			$extraCheck 	.= " AND(gr.stk_t  LIKE '".$this->txtstk." %' OR gr.stk_t LIKE '% ".$this->txtstk."'
									OR gr.stk_t LIKE '%".$this->txtstk."%') OR (gr.stk_h  LIKE '".$this->txtstk." %' OR gr.stk_h LIKE '% ".$this->txtstk."'
									OR gr.stk_h LIKE '%".$this->txtstk."%')";*/
		}//end if
		//$countFilterList	=  $this->group_entry_model->getCountfilterList($extraCheck);
		//$target 			= '.';
		//$this->common_model->_paging($countFilterList, $target,'cashbook_receipt/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->group_entry_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_receipt/showlist');

		$this->load->view('group_entry/group_entry_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKRECEIPTMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(cr.vDocNumber LIKE '".$this->txtSearch." %' OR cr.vDocNumber LIKE '% ".$this->txtSearch."'  
									OR cr.vDocNumber LIKE '%".$this->txtSearch."%'
									OR d.vTitle LIKE '".$this->txtSearch." %' OR d.vTitle LIKE '% ".$this->txtSearch."'
								OR d.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->group_entry_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'cashbook_receipt/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->group_entry_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_receipt/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('cashbook_receipt/cashbook_receipt');
		$this->load->view('general/template_bottom');
	}//end function

	function getCRV()
    {
		header_remove('Set-Cookie');
        $this->load->helper(array('form', 'url'));

        $accName            = $this->input->post('ddlDrAccMain');
        $accType            = $this->input->post('tableType');
        
		$loc         = $this->input->post('ddlloc');
		$this->doc=$this->input->post('ddldoc');
        $data['crv_list']   = $this->group_entry_model->getCRV($accName,$accType,$loc,$doc);
       $this->load->view('group_entry/show_crv',$data);
    }//end function
	function getInvoiceDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		$this->salesDId         = $this->input->post('salesDetailId');
		$invDetails				= $this->group_entry_model->getInvoiceDetails($this->salesDId);
		echo json_encode($invDetails);
	}//end function

    function printPreview()
    {
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->load->library('form_validation');
        session_start();

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

       $showVal	          	  	  = $this->group_entry_model->getCorresGroups();
				$this->ddlDivision	  	  	  = 1;
				$this->ddlLocation	  	  	  = $showVal->loc;

				$this->txtDate1      	  	  = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
				$this->ddlTransaction  	  	  = $showVal->status;
				$this->txtDocNumber  	  	  = $showVal->groupno;
				$this->remarks 	  	  	  = $showVal->remarks;
				$this->stk_t 	  	  = $showVal->stk_t;
				$this->stk_h 	  	  = $showVal->stk_h;
				$this->t_bal 	  	  = $showVal->t_bal;
				$this->h_bal 	  	  = $showVal->h_bal;
				$this->doc 	  	  = $showVal->doc;
				$this->t_adt 	  	  = $showVal->t_adt;
				$this->t_cld 	  	  = $showVal->t_cld;
				$this->t_inf 	  	  = $showVal->t_inf;
				$this->tot_t  		  	  = $showVal->tot_t;			
				$this->h_sgl   	  = $showVal->h_sgl;
				$this->h_dbl   	  = $showVal->h_dbl;
				$this->h_tpl   	  = $showVal->h_tpl;
				$this->h_suite   	  = $showVal->h_suite;
				$this->h_oths   	  = $showVal->h_oths;
				$this->h_tot   	  = $showVal->h_tot;
				$this->txtadoc=$showVal->adoc;
				$this->totalamt 	  	  = $showVal->totalamt;
				
				$this->txtRefName         	  = $showVal->paxname;
				$this->ctc1         	  = $showVal->ctc1;
				$this->ctc2         	  = $showVal->ctc2;
				$this->ctc3         	  = $showVal->ctc3;
				$this->ddlAccount         	  = $showVal->acc;
				$docNumber 			= @split('-',$this->ddlAccount);
				$this->ddlAc=$docNumber[0];
				$this->tbl=$docNumber[1];
				$this->ddlsubAccount=$showVal->acc_head;
				$this->received 	  	  = $showVal->recived;
				
				
				$data['list_doc']   	  = $this->group_entry_model->getCRV($this->ddlAc,$this->tbl,$this->ddlLocation);
				
		       $data['list_loc_value']   	  = $this->group_entry_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                 	  = $showVal->id;

				//get selected details code
                $this->chkSelect   		  	  = 'checked="checked"';
				
				$accValArray              	  = $this->group_entry_model->getCorresGROUPsDetails();
				
				$data['chkDetailID']        = $accValArray['acDetailID'];
				$data['chkRAmount']        = $accValArray['cashDetailsRAmount'];
				$data['chkDes']           = $accValArray['acDes'];

				$data['chkdate']         = $accValArray['acDate'];
                $data['chkBAmount']      = $accValArray['cashDetailsBAmount'];
                $data['chkLoc']           = $accValArray['acDetailID'];
				 $data['chkdoc']           = $accValArray['cashDetailsdoc'];
				 
				 
				 
				
        /*$data['list_loc_value']   	  = $this->group_entry_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                 	  = $showVal->nCRId;

        //get selected details code
        $this->chkSelect   		  	  = 'checked="checked"';

        $accValArray              	  = $this->group_entry_model->getCorresCashbookReceiptDetails();
        $data['chkLoc']  	      	  = $accValArray['acLoc'];
        $data['chkAccCode']  	  	  = $accValArray['acCode'];
        $data['chkAccSubCode'] 	  	  = $accValArray['acSubCode'];
        $data['chkMode'] 		  	  = $accValArray['acMode'];
        $data['chkCurr'] 	      	  = $accValArray['acCurr'];
        $data['chkRefNumber'] 	  	  = $accValArray['acRefNumber'];
        $data['chkAmountFC'] 	  	  = $accValArray['acAmountFC'];
        $data['chkExchangeRate']  	  = $accValArray['acExchangeRate'];
        $data['chkAmount']        	  = $accValArray['acAmount'];
        $data['chkDes']           	  = $accValArray['acDes'];
        $data['chkDetailID']      	  = $accValArray['acDetailID'];
        $data['chkTable']             = $accValArray['acTable'];

        $this->txtAllInvoice          = $showVal->vInvoice;
        $this->txtAllInvDate          = $showVal->dInvoiceDate;
*/
        $mydata = $this->load->view('cashbook_receipt/export_files/print_cash_receipt',$data,true);
        $_SESSION['print_cash_receipt'] = $mydata;
        $_SESSION['print_cash_receipt_pgSz'] = 'A4';
        $_SESSION['print_cash_receipt_lp'] = 'P';


        $this->load->view('general/title'); 
        $this->load->view('group_entry/print_group_receipt',$data);
    }//end function

    function printAgentPreview()
    {
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	          	  	  = $this->group_entry_model->getCorresCashbookReceipt();
        $this->ddlDivision	  	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  	  = $showVal->vLocation;
        $this->txtDate1      	  	  = $showVal->dDate;
        $this->ddlTransaction  	  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  	  = $showVal->vDocNumber;
        $this->ddlModeMain 	  	  	  = $showVal->vTransMode;
        $this->ddlCurrencyMain 	  	  = $showVal->nCurrency;
        $this->txtRate  		  	  = $showVal->vRate;
        $this->txtRefNumberMain   	  = $showVal->vRefNumber;
        $this->txtRefDate     	  	  = $showVal->dRefDate;
        $this->ddlAccount		  	  = $showVal->nAcCode;
        $this->ddlSubAccount   	  	  = $showVal->vSubCode;
        $this->txtAmountDC        	  = $showVal->vAmountDC;
        $this->txtAmountFCMain 	  	  = $showVal->vAmountFC;
        $this->txtRefName         	  = $showVal->vRefName;
        $this->txtEntered         	  = $showVal->vEnteredBy;
        $this->txtRemarks         	  = $showVal->vRemarks;
        $this->txtInvoice         	  = $showVal->vInvoice;
        $this->txtInvDate         	  = $showVal->dInvoiceDate;
        $this->txtTotalDebitAmt       = $showVal->nTotalDebit;
        $this->txtTotalAlloAmt        = $showVal->nTotalAllocated;
        $this->txtTotalBalAmt         = $showVal->nTotalBalance;

        $data['list_loc_value']   	  = $this->group_entry_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                 	  = $showVal->nCRId;

        //get selected details code
        $this->chkSelect   		  	  = 'checked="checked"';

        $accValArray              	  = $this->group_entry_model->getCorresCashbookReceiptDetails();
        $data['chkLoc']  	      	  = $accValArray['acLoc'];
        $data['chkAccCode']  	  	  = $accValArray['acCode'];
        $data['chkAccSubCode'] 	  	  = $accValArray['acSubCode'];
        $data['chkMode'] 		  	  = $accValArray['acMode'];
        $data['chkCurr'] 	      	  = $accValArray['acCurr'];
        $data['chkRefNumber'] 	  	  = $accValArray['acRefNumber'];
        $data['chkAmountFC'] 	  	  = $accValArray['acAmountFC'];
        $data['chkExchangeRate']  	  = $accValArray['acExchangeRate'];
        $data['chkAmount']        	  = $accValArray['acAmount'];
        $data['chkDes']           	  = $accValArray['acDes'];
        $data['chkDetailID']      	  = $accValArray['acDetailID'];
        $data['chkTable']             = $accValArray['acTable'];

        $this->txtAllInvoice          = $showVal->vInvoice;
        $this->txtAllInvDate          = $showVal->dInvoiceDate;

        $this->load->view('cashbook_receipt/print_agent_copy',$data);
    }//end function


	function getDocumentNumber()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
    $this->ddlLocation      = $this->input->post('ddlLocation');
	 $this->dt      = $this->input->post('dt');
	 $date=substr($this->dt,-4);
    $type = "Cash Receipt";

    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}CashReceipt WHERE vLocation = '". $this->ddlLocation."' AND YEAR(dDate) ='". $date."'  ORDER BY nCRId DESC,dDate DESC LIMIT 0,1");
	//echo "SELECT vDocNumber FROM {PRE}CashReceipt WHERE vLocation = '". $this->ddlLocation."' AND YEAR(CURDATE()) ='". $date."' AND SUBSTRING(dDate,1,4)='". $date."' ORDER BY nCRId DESC,dDate DESC LIMIT 0,1";
        
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
			
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE vTitle='".$type."'");
            $docNumber 			= @split('-',$this->txtDocNumber);
			
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        echo $countVal;

  }//end function

  function getAllAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_account_multiple'] = $this->common_model->getAllAccounts();
    $this->load->view('cashbook_receipt/show_allAccounts',$data);

  }

  function getCashAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_account'] = $this->common_model->getCashAccounts();
    $this->load->view('cashbook_receipt/show_cashAccounts',$data);

  }

  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation'); 
    $this->load->view('cashbook_receipt/show_emailDetails',$data);

  }
  
  


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
