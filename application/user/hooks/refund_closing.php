<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Refund_closing extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('refund_closing_model');
		$this->load->model('refund_register_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		
		$countCreditVoucher   =  $this->refund_closing_model->getCountRefundClosing();
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->refund_closing_model->getAllRefundClosing();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_closing');
		$this->load->view('general/template_bottom');
	}//end function

    function showUnclosed()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle1 	  = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();

		$countCreditVoucher   =  $this->refund_closing_model->getCountUnclosedRefund();
		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'refund_closing/showUnclosed/'.$this->uri->segment(3));
		$data['list_value']   = $this->refund_closing_model->getAllUnclosedRefund();
		$this->count_rows     = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_register');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editRefundClosing()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
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
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId = intval($this->input->post('nId'));
				}//end if
				
				$showVal	               	  = $this->refund_closing_model->getCorresRefundClosing();
				$this->ddlDivision	      	  = $showVal->nDivision;
				$this->ddlLocation	      	  = $showVal->vLocation;
				$this->txtDate          	  = date('d-m-Y',strtotime($showVal->dDate));
				$this->ddlType          	  = $showVal->vTransMode;
				$this->txtDocNumber  	      = $showVal->vDocNumber;
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
		
		if ($this->form_validation->run() == FALSE)
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
	
	function delRecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = REFUNDCLOSINGMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->refund_closing_model->deleteAllRefundClosing();
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

	//search by division or and doc number
	function filterList()
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
            $extraCheck 	.= " AND(tb.vTicketNumber LIKE '".$this->txtTktName." %' OR tb.vTicketNumber LIKE '% ".$this->txtTktName."'
									OR tb.vTicketNumber LIKE '%".$this->txtTktName."%'
								 OR xo.vXoNumber LIKE '".$this->txtTktName." %' OR xo.vXoNumber LIKE '% ".$this->txtTktName."'
									OR xo.vXoNumber LIKE '%".$this->txtTktName."%'
								 OR hv.vVoucherId LIKE '".$this->txtTktName." %' OR hv.vVoucherId LIKE '% ".$this->txtTktName."'
									OR hv.vVoucherId LIKE '%".$this->txtTktName."%')";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND(rc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR rc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR rc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%')";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND(tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								    OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' )";
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
		echo $extraCheck;
		$countFilterList	=  $this->refund_closing_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'credit_voucher/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->refund_closing_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('refund_closing/refund_closing_filter',$data);
	}//end function
	
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
            $extraCheck 	.= " AND(tb.vTicketNumber LIKE '".$this->txtTktName." %' OR tb.vTicketNumber LIKE '% ".$this->txtTktName."'
									OR tb.vTicketNumber LIKE '%".$this->txtTktName."%'
								 OR xo.vXoNumber LIKE '".$this->txtTktName." %' OR xo.vXoNumber LIKE '% ".$this->txtTktName."'
									OR xo.vXoNumber LIKE '%".$this->txtTktName."%'
								 OR hv.vVoucherId LIKE '".$this->txtTktName." %' OR hv.vVoucherId LIKE '% ".$this->txtTktName."'
									OR hv.vVoucherId LIKE '%".$this->txtTktName."%')";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND(rc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR rc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR rc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%')";
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

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

		$this->load->view('general/template_top',$data);
		$this->load->view('refund_closing/refund_closing');
		$this->load->view('general/template_bottom');
	}//end function

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
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	               	  = $this->refund_closing_model->getCorresRefundClosing();
        $this->ddlDivision	      	  = $showVal->nDivision;
        $this->ddlLocation	      	  = $showVal->vLocation;
        $this->txtDate          	  = $showVal->dDate;
        $this->ddlType          	  = $showVal->vTransMode;
        $this->txtDocNumber  	      = $showVal->vDocNumber;
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

        $this->load->view('refund_closing/print_refund_closing',$data);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>