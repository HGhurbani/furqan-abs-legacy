<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Refund_closing extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('refund_closing_model');
	}

	function showClosed()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
// User access aa
	$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');
	//$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),$this->uri->segment(1).'/'.$this->uri->segment(2));
		
		$countCreditVoucher   =  $this->refund_closing_model->getAllRefund('closed',1);
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showClosed/'.$this->uri->segment(3));
		$data['list_value']   = $this->refund_closing_model->getAllRefund('closed',0);
		$this->count_rows     = count($data['list_value']);

    $data['openclose'] = 'Closed';
    $this->session->unset_userdata('backURL2');


//    $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_closing');
		$this->load->view('general/template_bottom');
	}//end function

   function showAllRefund()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();

		$countCreditVoucher   =  $this->refund_closing_model->getAllRefund('all',1);
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showAllRefund/'.$this->uri->segment(3));
		$data['list_value']   = $this->refund_closing_model->getAllRefund('all',0);
		$this->count_rows     = count($data['list_value']);

    $data['openclose'] = 'All';
//    $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

        
    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

    $this->session->unset_userdata('backURL2');


		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_tkts');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editRefundClosing()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
$id = $this->uri->segment(4);
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url($this->uri->segment(1)."/showlist/".$this->uri->segment(3).'/no_access'));
    }

		$data['list_div_value']   		 = $this->refund_closing_model->getActiveDivision();
		$data['list_type_value']	 	 = $this->refund_closing_model->getTypeValue();
		$data['list_trans_value']    	 = $this->refund_closing_model->getActiveTransactions();
		$data['list_credit']      	  	 = $this->refund_closing_model->getActiveFinance();
        $data['list_dracc']	      	  	 = $this->refund_closing_model->getActiveCustomer();
        $data['list_employee_value']	 = $this->refund_closing_model->getActiveEmployees();
		$data['list_curr_value']	  	 = $this->refund_closing_model->getActiveCurrency();
        $data['list_agent_value']        = $this->refund_closing_model->getAgentAccounts();
        $data['list_type']	 	         = $this->refund_closing_model->getTicketType();
        $data['list_airline_value']      = $this->refund_closing_model->getActiveAirlines();
        $data['list_tkt_value']          = $this->refund_closing_model->getActiveTickets();
        $data['list_card_value']         = $this->refund_closing_model->getActiveCards();
		$data['list_invoice_status']	 = $this->refund_closing_model->getInvoiceStatus();
		$data['list_mode_value']  	     = $this->refund_closing_model->getModeType();
		$data['list_custacc_value']      = $this->refund_closing_model->getActiveCustomerAccounts();
		$data['list_acc_value']          = $this->refund_closing_model->getAcAccounts();
		$data['list_agentacc_value']     = $this->refund_closing_model->getAgentAccounts();


		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle   		  = ADDNEWREFUNDCLOSING;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
		        $data['list_loc_value']   = $this->refund_closing_model->getActiveLocations();
				$this->ddlDivision 		  = $this->input->post('ddlDivision');
				$this->ddlLocation 		  = $this->input->post('ddlLocation');
				$this->txtDate      	  = date('d-m-Y');
				$this->ddlType      	  = $this->input->post('ddlType');
				$this->txtDocNumber  	  = $this->input->post('txtDocNumber');
				$this->txtPassenger  	  = $this->input->post('txtPassenger');
				$this->txtLpoMain      	  = $this->input->post('txtLpoMain');
				$this->ddlDrAccMain    	  = $this->input->post('ddlDrAccMain');
				$this->txtCPV       	  = $this->input->post('txtCPV');
				$this->txtNewTkt       	  = $this->input->post('txtNewTkt');
                $this->ddlIssued  		  = $this->input->post('ddlIssued');
                $this->ddlAgentAc  		  = $this->input->post('ddlAgentAc');
                $this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
                $this->ddlCurrency 	      = $this->common_model->get_field_name('Currency','nId'," WHERE nId='".$this->compCurr."'");
                $this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->compCurr."'");

                $this->txtTotalNetAmount   = $this->input->post('txtTotalNetAmount');
                $this->txtTotalServPercent = $this->input->post('txtTotalServPercent');
                $this->txtTotalServAmount  = $this->input->post('txtTotalServAmount');
                $this->txtTotalAgentAmt    = $this->input->post('txtTotalAgentAmt');
                $this->txtTotalPaidAmount  = $this->input->post('txtTotalPaidAmount');

				$this->ddlInvoice         = $this->input->post('ddlInvoice');
				$this->txtRemarks         = $this->input->post('txtRemarks');
				$this->txtCreditRemarks   = $this->input->post('txtCreditRemarks');

				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
			    $data['chkType']    	  = array();
                $data['chkTktNumber'] 	  = array();
				$data['chkEmployee'] 	  = array();
				$data['chkAirline'] 	  = array();
				$data['chkNetAmt'] 		  = array();
				$data['chkServPercent']   = array();
				$data['chkServAmt'] 	  = array();
				$data['chkSalesAmt'] 	  = array();
				$data['chkDiscAmt'] 	  = array();
				$data['chkAgentAmt']      = array();
				$data['chkCollected']     = array();
				$data['chkFare']          = array();
				$data['chkTax']           = array();
				$data['chkCp']            = array();
				$data['chkOffCp']         = array();
				$data['chkRfdAmt']        = array();
				$data['chkPaidAmt']       = array();
				$data['chkDetailID']      = array();
			break;
			
			case "e":
				$this->showTitle      	  = EDITREFUNDCLOSING;
				$this->btnVal	      	  = UPDATEREFUNDCLOSING;
				$this->catId	      	  = $this->uri->segment(5);
//        $this->common_model->getPHPSelf2($_SERVER['PHP_SELF']);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if

        $this->common_model->getPHPSelf2($_SERVER['PHP_SELF']);
				
				$showVal	               	  = $this->refund_closing_model->getCorresRefundClosing();
				$this->ddlDivision	      	  = $showVal->nDivision;
				$this->ddlLocation	      	  = $showVal->vLocation;
				$this->txtDate          	  = date('d-m-Y',strtotime($showVal->dDate));
				$this->ddlType          	  = $showVal->vTransMode;
				$this->txtDocNumber  	      = $showVal->vDocNumber;
				$this->txtPassenger  	  = $showVal->vPassenger;
                $this->txtLpoMain       	  = $showVal->vLPO;
                $this->ddlDrAccMain       	  = $showVal->nDrAccount;
                $this->txtCPV              	  = $showVal->vCPV;
                $this->txtNewTkt           	  = $showVal->vNewTkt;
                $this->txtPV            	  = $showVal->vPV;
                $this->ddlIssued           	  = $showVal->nIssued;
                $this->ddlAgentAc             = $showVal->nAgentAccount;
                $this->ddlCurrency            = $showVal->nCurrency;
                $this->txtRate                = $showVal->vRate;
				
                $this->txtTotalNetAmount      = $showVal->vTotalNetAmount;
                $this->txtTotalServPercent    = $showVal->vTotalServPercent;
                $this->txtTotalServAmount     = $showVal->vTotalServAmount;
                $this->txtTotalDiscAmount     = $showVal->vTotalSalesAmt;
                $this->txtTotalAgentAmt       = $showVal->vTotalAgentAmt;
                $this->txtTotalPaidAmt        = $showVal->vTotalPaidAmt;

                $this->ddlInvoice             = $showVal->nInvoiceStatus;
                $this->txtRemarks             = $showVal->vRemarks;

		        $data['list_loc_value']       = $this->refund_closing_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                     = $showVal->nRCId;

                if($showVal->vActive == '1')
                {
                    $this->chkValYes  = 'checked="checked"';
                }//end if
                else
                {
                    $this->chkValNo   = 'checked="checked"';
                }//end else
				
				//get selected details code
				$accValArray              = $this->refund_closing_model->getCorresRefundClosingDetails();
			    $data['chkType']    	  = $accValArray ['sdType'];
                $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
                $data['chkAirline'] 	  = $accValArray ['sdAirline'];
                $data['chkORG']      	  = $accValArray ['sdORG'];
                $data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
                $data['chkServPercent']   = $accValArray ['sdServPercent'];
                $data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
                $data['chkSalesAmt'] 	  = $accValArray ['sdSalesAmt'];
                $data['chkAgentAmt']      = $accValArray ['sdAgentAmt'];
                $data['chkPaidAmt']       = $accValArray ['sdPaidAmt'];
                $data['chkDrAcc']         = $accValArray ['sdDrAcc'];
                $data['chkLPO']           = $accValArray ['sdLPO'];
				$data['chkDetailID']      = $accValArray ['sdID'];

				//get selected refund account details
				$showBillingVal			   = $this->refund_closing_model->getCorresRefundClosingAccountsDetails();
				$this->ddlMode       	   = $showBillingVal->vModeofpay;
				$this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
				$this->ddlAcAgentAc        = $showBillingVal->nAgentAc;
				$this->txtAcAgentAmt       = $showBillingVal->vAgentAmt;
				$this->txAcLPO             = $showBillingVal->vLPO;
				$this->txtAcSalesAmt       = $showBillingVal->vSalesAmt;
				$this->txtAcDiscount       = $showBillingVal->vDiscount;
				$this->txtAcDiscountAmt    = $showBillingVal->vDiscountAmt;
				$this->txtAcCollected      = $showBillingVal->vCollected;
				$this->txtAcServicePer     = $showBillingVal->vServicePercent;
				$this->txtAcServiceAmt     = $showBillingVal->vServiceAmt;
           break;
		}//end switch
		
// User access aa
		if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
		{
			$this->load->view('general/template_top',$data);
			$this->load->view('refund_closing/edit_refund_closing');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->refund_closing_model->insertRefundClosing();
				break;
				
				case "e":
					//Update into db
					$this->refund_closing_model->updateRefundClosing();
				break;
			}//end switch
			redirect(site_url("refund_closing/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

	function deleteRefundClosing()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->refund_closing_model->deleterefundClosing();
	}//end function 

	function deleteRefund()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->refund_closing_model->deleterefund();
	}//end function 


//for bulk actions	
	function delRecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->refund_closing_model->deleteAllRefundClosing();
	}//end function

//for bulk actions	
  function delRecordsRefund()
  {
    $this->load->helper(array('form', 'url'));
    $this->showTitle = REFUNDMANAGEMENT;
    $this->common_model->check_admin_authorization();
    $this->refund_closing_model->deleteAllRefund();
  }//end function


    function getTktNumber()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlTktType = $this->input->post('ddlTktType');
        $data['list_tkt_value'] = $this->refund_closing_model->getTicket($this->ddlTktType);
        $this->load->view('refund_closing/show_tkt',$data);
    }//end function


  function getRefTktNumber()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));

    $this->common_model->check_admin_authorization();
    //site Name
    $this->ddlTktType       = $this->input->post('ddlTktType');
    $data['list_tkt_value'] = $this->refund_closing_model->getRefundTicket($this->ddlTktType);
    $this->load->view('refund_closing/show_tkt',$data);
  }//end function

  function getTktValues()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));

    $this->common_model->check_admin_authorization();
    $this->ddlTkt     = $this->input->post('ddlTkt');
    $this->ddlTktType = $this->input->post('ddlTktType');

    $tktDetails 	  = $this->refund_closing_model->getTicketDetails($this->ddlTkt,$this->ddlTktType);

    echo json_encode($tktDetails);
  }//end function


  function getRefundTktValues()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));

    $this->common_model->check_admin_authorization();
    $this->ddlTkt     = $this->input->post('ddlTkt');
    $this->ddlTktType = $this->input->post('ddlTktType');

    $tktDetails 	  = $this->refund_closing_model->getRefundTicketDetails($this->ddlTkt,$this->ddlTktType);

    echo json_encode($tktDetails);
  }//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->refund_closing_model->filterLocation($this->ddlDivision);
		$this->load->view('refund_closing/show_location',$data);
	}//end function
	
	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey = $this->input->post('q');
		$items     = $this->refund_closing_model->searchActiveFinance($searchKey);
		echo json_encode($items);
	}//end function
	
	//get cash receipt details according to the A/c code selected
	function getCreditVoucherDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtAcccode = $this->input->post('txtAcccode');
		$this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE vACode='".$this->txtAcccode."'"); 
		echo $this->txtTitle;
	}//end function
	
	//get Trans Desc
	function getTranDesc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$this->txtRefName  = $this->input->post('txtRefName');
		$this->txtRemarks  = $this->input->post('txtRemarks'); 
		$this->txtTranDesc = ($this->txtRefName.' - '.$this->txtRemarks);
		echo $this->txtTranDesc;
	}//end function

	//generate subledger no according to the account head selected
	function generateSubLedgerNo()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$this->ddlFinance      = $this->input->post('ddlFinance');
		if($this->ddlFinance != '0')
			$this->txtSubCode1 = $this->refund_closing_model->getSubledgerNo($this->ddlFinance);
		
		echo $this->txtSubCode1;
	}//end function
	
 	function getCurrencyDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//fetch currency details
		$this->ddlCurrency = $this->input->post('ddlCurrency');
		$this->transId	   = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->ddlCurrency."'");
		$this->txtRate     = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->transId."'");
		
		$this->load->view('refund_closing/show_currency_details');
	}//end function
	
	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency = $this->input->post('ddlCurrency');
        $this->txtExchange = $this->refund_closing_model->getRate($this->ddlCurrency);

        echo $this->txtExchange;
    }//end function
	
	function convertCurrency()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		//fetch currency details
		$this->fcAmount         = $this->input->post('fcAmt');
		$this->ddlCurrencyMain  = $this->input->post('selCurrency');
		//selected currency code
		$this->selCurrency      = $this->common_model->currencyCode($this->input->post('selCurrency'));
		//default currency code
		$this->defCurrency      = $this->common_model->currencyCode($this->common_model->getCompanyCurr());
		
		//calculate rate
		$convRate               = $this->refund_closing_model->onlineCurrencyConverstion();
		
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
		
		$this->refund_closing_model->deleteSubAndDetails($this->subDetID);
	}//end function


function refundChecks(){
        if(!empty($this->txtAirline))
        {
            $extraCheck = " AND(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'
                OR ar.vTitle LIKE '%".$this->txtAirline."%'
              OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
                OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %'
              OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
                OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND(tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%' OR ( rrd.nTktNum LIKE '%".$this->txtTktName."%' AND rrd.vNewOld = 'O') )";
 
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND(rr.vDocNumber LIKE '".$this->txtInvoice." %' OR rr.vDocNumber LIKE '% ".$this->txtInvoice."'
									OR rr.vDocNumber LIKE '%".$this->txtInvoice."%')";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND(tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								    OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' 
                 OR rrd.vPassenger LIKE '%".$this->txtPassName."%' )";
        }//end if

        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck.= " AND rr.dDate >= '".$txtFrom."' AND rr.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND(aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								    OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '".$this->txtAccname." %'
								 OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '% ".$this->txtAccname."'
								    OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '%".$this->txtAccname."%')";
        }//end if
      return $extraCheck;

} //end function


function refundClosingChecks(){

        if(!empty($this->txtAirline))
        {
            $extraCheck = " AND(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'
								OR ar.vTitle LIKE '%".$this->txtAirline."%'
							OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %'
							OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
								OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND(tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%'
                  OR ( SELECT rrd_t.nTktNum FROM {PRE}RefundRegisterDetails rrd_t WHERE rrd_t.nTktNum LIKE '%".$this->txtTktName."%' AND rrd_t.vNewOld = 'O' AND rrd_t.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd_t.vTktType)  )";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND(rc.vDocNumber LIKE '".$this->txtInvoice." %' OR rc.vDocNumber LIKE '% ".$this->txtInvoice."'
									OR rc.vDocNumber LIKE '%".$this->txtInvoice."%')";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND(tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								    OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' OR 
                 ( SELECT rrd_t.nTktNum FROM {PRE}RefundRegisterDetails rrd_t WHERE rrd_t.vPassenger LIKE '%".$this->txtPassName."%' AND rrd_t.nTktNum = rcd.nTicketNumber AND rcd.vType = rrd_t.vTktType)  )";
        }//end if

        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck.= " AND rc.dDate >= '".$txtFrom."' AND rc.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND(aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								    OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '".$this->txtAccname." %'
								 OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '% ".$this->txtAccname."'
								    OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '%".$this->txtAccname."%')";
        }//end if

      return $extraCheck;
} //end function


	//search by division or and doc number
	function filterListRefund()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
        $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
        $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
        $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
        $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
        $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
        $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtAirline');
            $this->session->unset_userdata('txtTktName');
            $this->session->unset_userdata('txtInvoice');
            $this->session->unset_userdata('txtIssuedBy');
            $this->session->unset_userdata('txtPassName');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtAccname');
            $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
            $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
            $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
            $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
            $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
            $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
        }//end if

        $extraCheckRefund = $this->refundChecks();

		$countFilterList =  $this->refund_closing_model->getAllRefund('open',1,$extraCheckRefund,'');
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'refund_register/filterListRefund/'.$this->uri->segment(3));

		$data['list_value']   = $this->refund_closing_model->getAllRefund('open',0,$extraCheckRefund,'');
    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

		$this->count_rows   = count($data['list_value']);

        
	$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

		$this->load->view('refund_closing/refund_register_filter',$data);
	}//end function
		
	//search by division or and doc number
	function filterListRefundClosing()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
        $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
        $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
        $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
        $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
        $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
        $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtAirline');
            $this->session->unset_userdata('txtTktName');
            $this->session->unset_userdata('txtInvoice');
            $this->session->unset_userdata('txtIssuedBy');
            $this->session->unset_userdata('txtPassName');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtAccname');
            $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
            $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
            $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
            $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
            $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
            $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
        }//end if

        $extraCheckRefund = $this->refundClosingChecks();

		$countFilterList =  $this->refund_closing_model->getAllRefund('closed',1,'',$extraCheckRefund);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'refund_register/filterListRefundClosing/'.$this->uri->segment(3));

		$data['list_value']   = $this->refund_closing_model->getAllRefund('closed',0,'',$extraCheckRefund);
		$this->count_rows   = count($data['list_value']);

        
	$data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

		$this->load->view('refund_closing/refund_closing_filter',$data);
	}//end function
	

	//search by division or and doc number
	function filterListAllRefund()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
        $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
        $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
        $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
        $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
        $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
        $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtAirline');
            $this->session->unset_userdata('txtTktName');
            $this->session->unset_userdata('txtInvoice');
            $this->session->unset_userdata('txtIssuedBy');
            $this->session->unset_userdata('txtPassName');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtAccname');
            $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
            $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
            $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
            $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
            $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
            $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
        }//end if

        $extraCheckRefund = $this->refundChecks();
        $extraCheckRefundClosing = $this->refundClosingChecks();

		$countFilterList =  $this->refund_closing_model->getAllRefund('all',1,$extraCheckRefund,$extraCheckRefundClosing);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'refund_register/filterListAllRefund/'.$this->uri->segment(3));

		$data['list_value']   = $this->refund_closing_model->getAllRefund('all',0,$extraCheckRefund,$extraCheckRefundClosing);
		$this->count_rows   = count($data['list_value']);

        
    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

		$this->load->view('refund_closing/refund_tkts_filter',$data);
	}//end function
	
/*
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
        $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
        $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
        $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
        $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
        $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
        $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
        $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
        $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');

        if($this->input->post('btnSearch') == 'Go')
        {
            $this->session->unset_userdata('txtAirline');
            $this->session->unset_userdata('txtTktName');
            $this->session->unset_userdata('txtInvoice');
            $this->session->unset_userdata('txtIssuedBy');
            $this->session->unset_userdata('txtPassName');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtAccname');
            $this->txtAirline  = $this->common_model->searchKeyword('txtAirline');
            $this->txtTktName  = $this->common_model->searchKeyword('txtTktName');
            $this->txtInvoice  = $this->common_model->searchKeyword('txtInvoice');
            $this->txtIssuedBy = $this->common_model->searchKeyword('txtIssuedBy');
            $this->txtPassName = $this->common_model->searchKeyword('txtPassName');
            $this->txtFromDate = $this->common_model->searchKeyword('txtFromDate');
            $this->txtToDate   = $this->common_model->searchKeyword('txtToDate');
            $this->txtAccname  = $this->common_model->searchKeyword('txtAccname');
        }//end if

        if(!empty($this->txtAirline))
        {
            $extraCheck = " AND(ar.vTitle LIKE '".$this->txtAirline." %' OR ar.vTitle LIKE '% ".$this->txtAirline."'
								OR ar.vTitle LIKE '%".$this->txtAirline."%'
							OR ar.vAirlineNo LIKE '".$this->txtAirline." %' OR ar.vAirlineNo LIKE '% ".$this->txtAirline."'
								OR ar.vAirlineNo LIKE '%".$this->txtAirline."%' OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '".$this->txtAirline." %'
							OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '% ".$this->txtAirline."'
								OR CONCAT(ar.vTitle, '(', ar.vAirlineNo, ')') LIKE '%".$this->txtAirline."%')";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND(tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vTicktManual LIKE '".$this->txtTktName." %' OR xo.vTicktManual LIKE '% ".$this->txtTktName."'
									OR xo.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR hv.vTicktManual LIKE '".$this->txtTktName." %' OR hv.vTicktManual LIKE '% ".$this->txtTktName."'
									OR hv.vTicktManual LIKE '%".$this->txtTktName."%')";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND(rc.vDocNumber LIKE '".$this->txtInvoice." %' OR rc.vDocNumber LIKE '% ".$this->txtInvoice."'
									OR rc.vDocNumber LIKE '%".$this->txtInvoice."%')";
        }//end if

        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND(em.vName LIKE '".$this->txtIssuedBy." %' OR em.vName LIKE '% ".$this->txtIssuedBy."'
									OR em.vName LIKE '%".$this->txtIssuedBy."%' OR em.vCode LIKE '".$this->txtIssuedBy." %'
								 OR em.vCode LIKE '% ".$this->txtIssuedBy."' OR em.vCode LIKE '%".$this->txtIssuedBy."%'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '".$this->txtIssuedBy." %'
								 OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '% ".$this->txtIssuedBy."'
								    OR CONCAT(em.vName, '(', em.vCode, ')') LIKE '%".$this->txtIssuedBy."%')";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND(tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								   OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' )";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck .= " AND rc.dDate >= '".$txtFrom."' AND rc.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND(aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								    OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '".$this->txtAccname." %'
								 OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '% ".$this->txtAccname."'
								    OR CONCAT(aha.vTitle, '(', aha.vACode, ')') LIKE '%".$this->txtAccname."%')";
        }//end if

        $countFilterList	=  $this->refund_closing_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'credit_voucher/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->refund_closing_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

        

		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_closing');
		$this->load->view('general/template_bottom');
	}//end function
*/
  function getDocumentNumber($type = '')
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $countVal = $this->refund_closing_model->getDocumentNumber();
    echo $countVal;
  }//end function

/*
	function getDocumentNumber()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
        $this->ddlType          = $this->input->post('ddlType');
        $this->ddlLocation      = $this->input->post('ddlLocation');

        if(!empty($this->ddlType)):
            $this->ddlType = $this->ddlType;
        else:
            $this->ddlType = 24;
        endif;

        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$this->ddlType."'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RefundClosing WHERE
                                                    vTransMode = '".$this->ddlType."' ORDER BY nRCId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$this->ddlType."'");
            $docNumber 			= @split('-',$this->txtDocNumber);
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        echo $countVal;
	}//end function
*/
    function generateCurrRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $valArray			 = array();
        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtRate		 = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->ddlCurrency."'");
        $this->txtDecimal	 = $this->common_model->get_field_name('Currency','vDecimal'," WHERE nId='".$this->ddlCurrency."'");
        $valArray['rate']    = $this->txtRate;
        $valArray['decimal'] = str_repeat(0,$this->txtDecimal);

        echo json_encode($valArray);
    }//end function

    function printPreview()
    {
      session_start();
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	               	  = $this->refund_closing_model->getCorresRefundClosing();
        $this->ddlDivision	      	  = $showVal->nDivision;
        $this->ddlLocation	      	  = $showVal->vLocation;
        $this->txtDate          	  = $showVal->dDate;
        $this->ddlType          	  = $showVal->vTransMode;
        $this->txtDocNumber  	      = $showVal->vDocNumber;
				$this->txtPassenger  	  = $showVal->vPassenger;
        $this->txtLpoMain       	  = $showVal->vLPO;
        $this->ddlDrAccMain       	  = $showVal->nDrAccount;
        $this->txtCPV              	  = $showVal->vCPV;
        $this->txtNewTkt           	  = $showVal->vNewTkt;
        $this->txtPV            	  = $showVal->vPV;
        $this->ddlIssued           	  = $showVal->nIssued;
        $this->ddlAgentAc             = $showVal->nAgentAccount;
        $this->ddlCurrency            = $showVal->nCurrency;
        $this->txtRate                = $showVal->vRate;

        $this->txtTotalNetAmount      = $showVal->vTotalNetAmount;
        $this->txtTotalServPercent    = $showVal->vTotalServPercent;
        $this->txtTotalServAmount     = $showVal->vTotalServAmount;
        $this->txtTotalDiscAmount     = $showVal->vTotalSalesAmt;
        $this->txtTotalAgentAmt       = $showVal->vTotalAgentAmt;
        $this->txtTotalPaidAmt        = $showVal->vTotalPaidAmt;

        $this->ddlInvoice             = $showVal->nInvoiceStatus;
        $this->txtRemarks             = $showVal->vRemarks;

        $data['list_loc_value']       = $this->refund_closing_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                     = $showVal->nRCId;

        if($showVal->vActive == '1')
        {
            $this->chkValYes  = 'checked="checked"';
        }//end if
        else
        {
            $this->chkValNo   = 'checked="checked"';
        }//end else

        //get selected details code
        $accValArray              = $this->refund_closing_model->getCorresRefundClosingDetails();
        $data['chkType']    	  = $accValArray ['sdType'];
        $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
        $data['chkAirline'] 	  = $accValArray ['sdAirline'];
        $data['chkORG']      	  = $accValArray ['sdORG'];
        $data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
        $data['chkServPercent']   = $accValArray ['sdServPercent'];
        $data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
        $data['chkSalesAmt'] 	  = $accValArray ['sdSalesAmt'];
        $data['chkAgentAmt']      = $accValArray ['sdAgentAmt'];
        $data['chkPaidAmt']       = $accValArray ['sdPaidAmt'];
        $data['chkDrAcc']         = $accValArray ['sdDrAcc'];
        $data['chkLPO']           = $accValArray ['sdLPO'];
        $data['chkDetailID']      = $accValArray ['sdID'];

        //get selected refund account details
        $showBillingVal			   = $this->refund_closing_model->getCorresRefundClosingAccountsDetails();
        $this->ddlMode       	   = $showBillingVal->vModeofpay;
        $this->ddlCustomerAc       = $showBillingVal->nCustomerAc;
        $this->ddlAcAgentAc        = $showBillingVal->nAgentAc;
        $this->txtAcAgentAmt       = $showBillingVal->vAgentAmt;
        $this->txAcLPO             = $showBillingVal->vLPO;
        $this->txtAcSalesAmt       = $showBillingVal->vSalesAmt;
        $this->txtAcDiscount       = $showBillingVal->vDiscount;
        $this->txtAcDiscountAmt    = $showBillingVal->vDiscountAmt;
        $this->txtAcCollected      = $showBillingVal->vCollected;
        $this->txtAcServicePer     = $showBillingVal->vServicePercent;
    
        $this->txtAcServiceAmt     = $showBillingVal->vServiceAmt;

        $mydata = $this->load->view('refund_closing/export_files/print_refund_closing',$data,true);
        $_SESSION['print_refund_closing'] = $mydata;
        $_SESSION['print_refund_closing_pgSz'] = 'A3';
        $_SESSION['print_refund_closing_lp'] = 'P';

        $this->load->view('refund_closing/print_refund_closing',$data);
    }//end function

    function getAllAccount_main()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_dracc'] = $this->common_model->getAllAccounts();
      $this->load->view('refund_closing/show_allAccounts_main',$data);

    }

    function getAllAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_dracc'] = $this->common_model->getAllAccounts();
      $this->load->view('refund_closing/show_allAccounts',$data);

    }

    function getAgentAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_agent_value'] = $this->common_model->getAgentAccounts();
      $this->load->view('refund_closing/show_agentAccounts',$data);

    }

    function getIssuedBy()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_employee_value'] = $this->common_model->getIssuedBy();
      $this->load->view('refund_closing/show_issuedBy',$data);
    }


  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('refund_closing/show_emailDetails',$data);

  }
  function getAgentAmount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $ddlAgentAc = $this->input->post('ddlAgentAc');
    echo $this->refund_closing_model->getAgentAmount($ddlAgentAc);
  }

//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
/////////////////////   REFUN REGISTER      /////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////
//////////////////////////////////////////////////////////////////////////////////////////////////

  function showlist()
  {
    $this->load->helper(array('form', 'url'));
    $this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
    $this->common_model->check_admin_authorization();

    $countCreditVoucher   =  $this->refund_closing_model->getAllRefund('open',1);
    $target 			  = '.';
    $this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showUnclosed/'.$this->uri->segment(3));
    $data['list_value']   = $this->refund_closing_model->getAllRefund('open',0);
    $this->count_rows     = count($data['list_value']);

    $data['openclose'] = 'Open';
    $this->session->unset_userdata('backURL');
    $this->session->unset_userdata('backURL2');
//    $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);



    $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');

    $this->load->view('general/template_top',$data);
    $this->load->view('refund_closing/refund_register');
    $this->load->view('general/template_bottom');
  }//end function



  function editRefund()
  {
    $this->common_model->check_admin_authorization();
    //site Name
    $data['title'] 	            = $this->common_model->get_site_title();
    $this->load->helper(array('form', 'url'));
    $this->load->library('form_validation');
    $this->form_validation->set_rules('ddlDivision', 'Division', 'required');
    $id = $this->uri->segment(4);

    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'refund_closing/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("refund_closing/showUnclosed/".$this->uri->segment(3).'/no_access'));
    }

    $data['list_div_value']	            = $this->refund_closing_model->getActiveDivision();
    $data['list_gds_value']             = $this->refund_closing_model->getActiveGDS();
    $data['list_type_value']            = $this->refund_closing_model->getActiveType();
    $data['list_emp_value']             = $this->refund_closing_model->getActiveEmployees();
    $data['list_NewOld']                = $this->refund_closing_model->getNewOld();
    $data['list_country']               = $this->refund_closing_model->getCountry();
    $data['list_hotel']                 = $this->refund_closing_model->getHotel();
    $data['list_curr_value']            = $this->refund_closing_model->getActiveCurrency();
    $data['list_mode_value']  	        = $this->refund_closing_model->getModeType();
    $data['list_agentacc_value']        = $this->refund_closing_model->getAgentAccounts();
    $data['list_account_value']         = $this->refund_closing_model->getAccounts();
    $data['list_accountapid_value']     = $this->refund_closing_model->getAPIDAccounts();
    $data['list_type']	 	            = $this->refund_closing_model->getTicketType();
    $data['list_tkt_value']             = $this->refund_closing_model->getActiveTickets();
    $data['list_custaccount_value']     = $this->refund_closing_model->getAPIDAccounts();

    switch($this->uri->segment(4))
    {
      case "a":
        $this->showTitle  	  	  = ADDNEWREFUND;
      $this->btnVal	  	  	  = ADDCONTINUE;
      $this->btnVal2	  	  	  = SAVEEXIT;
      $this->btnVal3	      	  = SAVECLOSE;

      $data['list_accounthead'] = $this->refund_closing_model->getAccountHead();
      $data['list_loc_value']   = $this->refund_closing_model->getLocation();
      $data['list_city']        = $this->refund_closing_model->getCity1();
      $this->ddlDivision 	      = $this->input->post('ddlDivision');
      $this->ddlLocation  	  = $this->input->post('ddlLocation');
      $this->txtDate  	      = date('d-m-Y');
      $this->txtDocNumber  	  = $this->input->post('txtDocNumber');
      $this->ddlType  	  	  = $this->input->post('ddlType');
      $this->txtPassenger	  	  = $this->input->post('txtPassenger');
      $this->ddlMode   	  	  = $this->input->post('ddlMode');
      $this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
      $this->ddlCurrency 	      = $this->common_model->get_field_name('Currency','nId'," WHERE nId='".$this->compCurr."'");
      $this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->compCurr."'");
      $this->ddlAccount		  = $this->input->post('ddlAccount');
      $this->ddlGDS  	  	  	  = $this->input->post('ddlGDS');
      $this->txtSector 	  	  = $this->input->post('txtSector');
      $this->txtSegment 	  	  = $this->input->post('txtSegment');
      $this->txtDestination 	  = $this->input->post('txtDestination');
      $this->txtSdt    	      = $this->input->post('txtSdt');
      $this->txtRdt    	      = $this->input->post('txtRdt');

      //get billing details
      $this->txtInvoice 	  	  = $this->input->post('txtInvoice');
      $this->ddlMode2	  		  = $this->input->post('ddlMode2');
      $this->txtAcccode 	  	  = $this->input->post('txtAcccode');
      $this->txtSubCode 	  	  = $this->input->post('txtSubCode');
      $this->ddlAgentAc 	  	  = $this->input->post('ddlAgentAc');
      $this->txtAgentAmt 		  = $this->input->post('txtAgentAmt');
      $this->txtLPO          	  = $this->input->post('txtLPO');
      $this->txtSalesAmt        = $this->input->post('txtSalesAmt');
      $this->txtDiscount        = $this->input->post('txtDiscount');
      $this->txtDiscountAmt  	  = $this->input->post('txtDiscountAmt');
      $this->txtCollected 	  = $this->input->post('txtCollected');
      $this->txtServicePerc 	  = $this->input->post('txtServicePerc');
      $this->txtServiceAmt 	  = $this->input->post('txtServiceAmt');

      //get Refund vendor details
      $this->txtFare 	      	  = $this->input->post('txtFare');
      $this->txtUsedFare 	  	  = $this->input->post('txtUsedFare');
      $this->txtSaleAmt  	  	  = $this->input->post('txtSaleAmt');
      $this->txtServiceAmt2 	  = $this->input->post('txtServiceAmt2');
      $this->ddlCountry  	  	  = $this->input->post('ddlCountry');
      $this->txtTax    	  	  = $this->input->post('txtTax');
      $this->txtUsedTax 	  	  = $this->input->post('txtUsedTax');
      $this->txtOCharge  	  	  = $this->input->post('txtOCharge');
      $this->txtDiscAmt 	  	  = $this->input->post('txtDiscAmt');
      $this->ddlCity 	  	  	  = $this->input->post('ddlCity');
      $this->txtComm1   	  	  = $this->input->post('txtComm1');
      $this->txtComm2	  	  	  = $this->input->post('txtComm2');
      $this->txtCreditAmt	  	  = $this->input->post('txtCreditAmt');
      $this->txtOthAdj  	  	  = $this->input->post('txtOthAdj');
      $this->txtOthAdj2  	  	  = $this->input->post('txtOthAdj2');
      $this->ddlHotel  	  	  = $this->input->post('ddlHotel');
      $this->txtOtherAmt	  	  = $this->input->post('txtOtherAmt');
      $this->txtVendorAmt  	  = $this->input->post('txtVendorAmt');
      $this->txtOthAdj3  	  	  = $this->input->post('txtOthAdj3');
      $this->txtAgentAmt2	  	  = $this->input->post('txtAgentAmt2');
      $this->ddlIssued	  	  = $this->input->post('ddlIssued');
      $this->txtNetAmt1  	  	  = $this->input->post('txtNetAmt1');
      $this->txtNetAmt2  	  	  = $this->input->post('txtNetAmt2');
      $this->txtPaidTo  	  	  = $this->input->post('txtPaidTo');
      $this->ddlYesNo			  = ($this->input->post('ddlYesNo') != '')?$this->input->post('ddlYesNo'):'N';
      $this->chkValYes  	  	  = 'checked="checked"';
      $this->chkValNo  	  	  = '';								
      break;

      case "e":
        $this->showTitle  	  	  = EDITREFUND;
      $this->btnVal	      	  = UPDATEREFUND;
      if(!trim($this->session->userdata('backURL2')))
        $this->btnVal3	      	  = UPDATECLOSE;
      $this->catId	      	  = $this->uri->segment(5);

//      $this->common_model->getPHPSelf2($_SERVER['PHP_SELF']);

      if(intval($this->input->post('nId')) != 0)
      {
        $this->catId  	  	  = intval($this->input->post('nId'));
      }//end if

      if(trim($this->input->post('nCheck')) != '')
      {
        $this->nCheck  	  	  = $this->input->post('nCheck');
      }//end if

      $showVal	          	  = $this->refund_closing_model->getCorresRefund();
      $this->ddlDivision 	      = $showVal->nDivision;
      $this->ddlLocation  	  = $showVal->nLocation;
      $this->txtDate  	      = date('d-m-Y', strtotime($showVal->dDate));
      $this->txtDocNumber  	  = $showVal->vDocNumber;
      $this->ddlType  	  	  = $showVal->vType;
      $this->txtPassenger 	  = $showVal->vPassenger;
      $this->ddlMode   	  	  = $showVal->vMode;
      $this->ddlCurrency 	      = $showVal->nCurrency;
      $this->txtRate 	      	  = $showVal->nRate;
      $this->ddlAccount		  = $showVal->nAccount;
      $this->ddlGDS  	  	  	  = $showVal->nGDS;
      $this->ddlAccApId 	  	  = $showVal->vAcAPID;
      $this->txtSector 	  	  = $showVal->vSector;
      $this->txtSegment 	  	  = $showVal->vSegment;
      $this->txtDestination 	  = $showVal->vDestination;
      $this->txtSdt    	      = date('d-m-Y', strtotime($showVal->vSalesDet));
      $this->txtRdt    	      = date('d-m-Y', strtotime($showVal->vRefundDet));
      $secVal 				  = @split("-",$this->txtSector);
      $this->txtSector1 	  	  = $secVal[0];
      $this->txtSector2 	  	  = $secVal[1];
      $this->txtSector3 	  	  = $secVal[2];
      $this->txtSector4 	  	  = $secVal[3];
      $this->txtSector5 	  	  = $secVal[4];

      $data['list_loc_value']   = $this->refund_closing_model->getCorrActiveLocation($this->ddlDivision);

      //get selected Refund billing details
      $showBillingVal			  = $this->refund_closing_model->getCorresBillingDetails();
      $this->txtInvoice         = $showBillingVal->vInvoice;
      $this->ddlMode2       	  = $showBillingVal->vMode;
      $this->ddlAccountClose 	  = $showBillingVal->nAcCode;
      $this->ddlAccountHead 	  = $showBillingVal->vAcSubCode;
      $this->ddlAgentAc 	      = $showBillingVal->nAgentAc;
      $this->txtAgentAmt 	      = $showBillingVal->vAgentAmt;
      $this->txtLPO             = $showBillingVal->vLPO;
      $this->txtSalesAmt        = $showBillingVal->vSalesAmt;
      $this->txtDiscount        = $showBillingVal->vDiscount;
      $this->txtDiscountAmt     = $showBillingVal->vDiscountAmt;
      $this->txtCollected       = $showBillingVal->vCollected;
      $this->txtServicePerc     = $showBillingVal->vServicePercent;
      $this->txtServiceAmt      = $showBillingVal->vServiceAmt;

      $data['list_accounthead'] = $this->refund_closing_model->getActiveAccountHead($this->ddlAccountClose);
      $data['list_city']        = $this->refund_closing_model->getCity1();

      $RefundValArray           = $this->refund_closing_model->getCorresRefundDetails();

      $data['chkType']  	      = $RefundValArray['Type'];
      $data['ddlYesNo']  	      = $RefundValArray['YesNo'];
      $data['chkTktNumber']     = $RefundValArray['TktNum'];
      $data['txtORGTkt'] 	      = $RefundValArray['ORGTkt'];
      $data['txtAirline'] 	  = $RefundValArray['Airline'];
      $data['txtTkt'] 	  = $RefundValArray['TktNum'];
      $data['txtPass'] 	      = $RefundValArray['Passenger'];
      $data['txtRemarks'] 	  = $RefundValArray['Remarks'];
      $data['chkDetailID']      = $RefundValArray ['ID'];
      $data['chkAccID']    	  = $RefundValArray['ID'];

      $data['chkFare']  	      = $RefundValArray['Fare'];
      $data['chkUsedFare']  	  = $RefundValArray['UsedFare'];
      $data['chkSaleAmt']       = $RefundValArray['SaleAmt'];
      $data['chkServiceAmt2']   = $RefundValArray['ServiceAmt2'];
      $data['chkCountry'] 	  = $RefundValArray['Country'];
      $data['chkTax'] 	      = $RefundValArray['Tax'];
      $data['chkUsedTax'] 	  = $RefundValArray['UsedTax'];
      $data['chkOCharge'] 	  = $RefundValArray['OCharge'];
      $data['chkDiscAmt'] 	  = $RefundValArray['DiscAmt'];
      $data['chkCity'] 	      = $RefundValArray['City'];
      $data['chkComm1'] 	      = $RefundValArray['Comm1'];
      $data['chkComm2'] 	      = $RefundValArray['Comm2'];
      $data['chkCreditAmt'] 	      = $RefundValArray['CreditAmt'];
      $data['chkOthAdj'] 	      = $RefundValArray['OthAdj'];
      $data['chkOthAdj2'] 	  = $RefundValArray['OthAdj2'];
      $data['chkHotel'] 	      = $RefundValArray['Hotel'];
      $data['chkOtherAmt'] 	  = $RefundValArray['OtherAmt'];
      $data['chkVendorAmt'] 	  = $RefundValArray['VendorAmt'];
      $data['chkOthAdj3'] 	  = $RefundValArray['OthAdj3'];
      $data['chkAgentAmt2'] 	  = $RefundValArray['AgentAmt2'];
      $data['chkIssued'] 	      = $RefundValArray['Issued'];
      $data['chkNetAmt1'] 	  = $RefundValArray['NetAmt1'];
      $data['chkNetAmt2'] 	  = $RefundValArray['NetAmt2'];
      $data['chkPaidTo'] 	      = $RefundValArray['PaidTo'];

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
    if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
    {
      $this->load->view('general/template_top',$data);
      $this->load->view('refund_closing/edit_refund_register');
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
          $this->refund_closing_model->insertRefund();
        break;

        case "e":
          //Update into db
          $this->refund_closing_model->updateRefund();
        break;
      }//end switch
      redirect(site_url("refund_closing/showUnclosed/".$this->uri->segment(3).'/'.$msgVal));
    }//end else
  }//end function


    function printPreviewRefund()
    {
      session_start();
        $this->load->helper(array('form', 'url'));
        $this->catId   = $this->uri->segment(3);

        $showVal	          	  = $this->refund_closing_model->getCorresRefund();
        $this->ddlDivision 	      = $showVal->nDivision;
        $this->ddlLocation  	  = $showVal->nLocation;
        $this->txtDate  	      = date('d-m-Y', strtotime($showVal->dDate));
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlType  	  	  = $showVal->vType;
        $this->txtPassenger 	  = $showVal->vPassenger;
        $this->ddlMode   	  	  = $showVal->vMode;
        $this->ddlCurrency 	      = $showVal->nCurrency;
        $this->txtRate 	      	  = $showVal->nRate;
        $this->ddlAccount		  = $showVal->nAccount;
        $this->ddlGDS  	  	  	  = $showVal->nGDS;
        $this->ddlAccApId 	  	  = $showVal->vAcAPID;
        $this->txtSector 	  	  = $showVal->vSector;
        $this->txtSegment 	  	  = $showVal->vSegment;
        $this->txtDestination 	  = $showVal->vDestination;
        $this->txtSdt    	      = date('d-m-Y', strtotime($showVal->vSalesDet));
        $this->txtRdt    	      = date('d-m-Y', strtotime($showVal->vRefundDet));
        $secVal 				  = @split("-",$this->txtSector);
        $this->txtSector1 	  	  = $secVal[0];
        $this->txtSector2 	  	  = $secVal[1];
        $this->txtSector3 	  	  = $secVal[2];
        $this->txtSector4 	  	  = $secVal[3];
        $this->txtSector5 	  	  = $secVal[4];

        $data['list_loc_value']   = $this->refund_closing_model->getCorrActiveLocation($this->ddlDivision);

        //get selected Refund billing details
        $showBillingVal			  = $this->refund_closing_model->getCorresBillingDetails();
        $this->txtInvoice         = $showBillingVal->vInvoice;
        $this->ddlMode2       	  = $showBillingVal->vMode;
        $this->ddlAccountClose 	  = $showBillingVal->nAcCode;
        $this->ddlAccountHead 	  = $showBillingVal->vAcSubCode;
        $this->ddlAgentAc 	      = $showBillingVal->nAgentAc;
        $this->txtAgentAmt 	      = $showBillingVal->vAgentAmt;
        $this->txtLPO             = $showBillingVal->vLPO;
        $this->txtSalesAmt        = $showBillingVal->vSalesAmt;
        $this->txtDiscount        = $showBillingVal->vDiscount;
        $this->txtDiscountAmt     = $showBillingVal->vDiscountAmt;
        $this->txtCollected       = $showBillingVal->vCollected;
        $this->txtServicePerc     = $showBillingVal->vServicePercent;
        $this->txtServiceAmt      = $showBillingVal->vServiceAmt;

        $data['list_accounthead'] = $this->refund_closing_model->getActiveAccountHead($this->ddlAccountClose);
        $data['list_city']        = $this->refund_closing_model->getCity1();

        $RefundValArray           = $this->refund_closing_model->getCorresRefundDetails();

        $data['chkType']  	      = $RefundValArray['Type'];
        $data['ddlYesNo']  	      = $RefundValArray['YesNo'];
        $data['chkTktNumber']     = $RefundValArray['TktNum'];
        $data['txtORGTkt'] 	      = $RefundValArray['ORGTkt'];
        $data['txtAirline'] 	  = $RefundValArray['Airline'];
        $data['txtTkt'] 	  = $RefundValArray['TktNum'];
        $data['txtPass'] 	      = $RefundValArray['Passenger'];
        $data['txtRemarks'] 	  = $RefundValArray['Remarks'];
        $data['chkDetailID']      = $RefundValArray ['ID'];
        $data['chkAccID']    	  = $RefundValArray['ID'];

        $data['chkFare']  	      = $RefundValArray['Fare'];
        $data['chkUsedFare']  	  = $RefundValArray['UsedFare'];
        $data['chkSaleAmt']       = $RefundValArray['SaleAmt'];
        $data['chkServiceAmt2']   = $RefundValArray['ServiceAmt2'];
        $data['chkCountry'] 	  = $RefundValArray['Country'];
        $data['chkTax'] 	      = $RefundValArray['Tax'];
        $data['chkUsedTax'] 	  = $RefundValArray['UsedTax'];
        $data['chkOCharge'] 	  = $RefundValArray['OCharge'];
        $data['chkDiscAmt'] 	  = $RefundValArray['DiscAmt'];
        $data['chkCity'] 	      = $RefundValArray['City'];
        $data['chkComm1'] 	      = $RefundValArray['Comm1'];
        $data['chkComm2'] 	      = $RefundValArray['Comm2'];
        $data['chkCreditAmt'] 	      = $RefundValArray['CreditAmt'];
        $data['chkOthAdj'] 	      = $RefundValArray['OthAdj'];
        $data['chkOthAdj2'] 	  = $RefundValArray['OthAdj2'];
        $data['chkHotel'] 	      = $RefundValArray['Hotel'];
        $data['chkOtherAmt'] 	  = $RefundValArray['OtherAmt'];
        $data['chkVendorAmt'] 	  = $RefundValArray['VendorAmt'];
        $data['chkOthAdj3'] 	  = $RefundValArray['OthAdj3'];
        $data['chkAgentAmt2'] 	  = $RefundValArray['AgentAmt2'];
        $data['chkIssued'] 	      = $RefundValArray['Issued'];
        $data['chkNetAmt1'] 	  = $RefundValArray['NetAmt1'];
        $data['chkNetAmt2'] 	  = $RefundValArray['NetAmt2'];
        $data['chkPaidTo'] 	      = $RefundValArray['PaidTo'];

        $mydata = $this->load->view('refund_closing/export_files/print_refund_register',$data,true);
        $_SESSION['print_refund_register'] = $mydata;
        $_SESSION['print_refund_register_pgSz'] = 'A4';
        $_SESSION['print_refund_register_lp'] = 'P';

        $this->load->view('refund_closing/print_refund_register',$data);
    }//end function
    


    function getAllAccount_Subcode()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_custaccount_value'] = $this->common_model->getAllAccounts();
      $this->load->view('refund_closing/show_allAccounts_subcode',$data);

    }

   function getVendorAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_account_value'] = $this->common_model->getVendorMasterAccounts();
      $this->load->view('refund_closing/show_vendorAccounts',$data);

    }

    function getAccountHead()
    {
        $this->load->helper(array('form', 'url'));

        $accName                  = $this->input->post('ddlAccountClose');
        $data['list_accounthead'] = $this->refund_closing_model->getActiveAccountHead($accName);
        $this->load->view('refund_closing/show_acchead',$data);
    }//end function

    //get document number when selected transaction
    function generateDocNo()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlType          = $this->input->post('ddlType');
        $this->ddlLocation      = $this->input->post('ddlLocation');

        if($this->ddlType == 'R'):
            $type = 3;
        elseif($this->ddlType == 'RV'):
            $type = 23;
        endif;

        $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
        $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");
        $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister WHERE
                                                    vType = '".$this->ddlType."' AND nLocation = ". $this->ddlLocation." ORDER BY  CAST(SUBSTRING_INDEX(vDocNumber,'-',-1) AS UNSIGNED) DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber = $totalNum->vDocNumber;
            $docNumber      = @split('-',$this->txtDocNumber);
            $fill         = $this->txtMinDigit;
            $number       = ($docNumber[3]+1);
            $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");
            $docNumber      = @split('-',$this->txtDocNumber);
            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        echo $countVal;
    }//end function

 
    function getNewOld()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));

      $this->common_model->check_admin_authorization();
      //site Name
      $data['list_NewOld'] = $this->refund_closing_model->getNewOld();
      $this->load->view('refund_closing/show_oldNew',$data);
    }//end function

    function getTktType()
    {
      $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Nameo
        $data['list_type'] = $this->refund_closing_model->getTicketType();
        $this->load->view('refund_closing/show_tktType',$data);
    }//end function


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
