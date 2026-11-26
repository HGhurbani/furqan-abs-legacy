<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cashbook_payment extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('cashbook_payment_model');
        $this->load->model('cron_job_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = CASHBOOKPAYMENTMANAGEMENT;
		$this->common_model->check_admin_authorization();

        $this->cron_job_model->checkAllocationTable();

        $this->session->unset_userdata('backURL');

		$countCashbookPayment =  $this->cashbook_payment_model->getCountCashbookPayment();
		$target 			  = '.';
		$this->common_model->_paging($countCashbookPayment, $target,'cashbook_payment/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->cashbook_payment_model->getAllCashbookPayment();
		$this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_payment/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('cashbook_payment/cashbook_payment');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editCashbookpayment()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
        $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_payment/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("cashbook_payment/showlist/".$this->uri->segment(3).'/no_access'));
    }

		$data['list_div_value']   		 = $this->cashbook_payment_model->getActiveDivision();
		$data['list_trans_value']    	 = $this->cashbook_payment_model->getActiveTransactions();
		$data['list_account']	  	  	 = $this->cashbook_payment_model->getActiveAccount();
		$data['list_sub_acc']	  	  	 = $this->cashbook_payment_model->getActiveSubAccounts();
		$data['list_account_multiple']   = $this->common_model->getAllAccounts();
		$data['list_debit_mode']         = $this->cashbook_payment_model->getDebitModeType();
        $data['list_mode']        	     = $this->cashbook_payment_model->getModeType();
		$data['list_curr_value']	  	 = $this->cashbook_payment_model->getActiveCurrency();
		$data['list_mloc_value']  		 = $this->cashbook_payment_model->getMultipleLocation();
		
		switch($id)
		{
			case "a":
				$this->showTitle   		      = ADDNEWCASHBOOKPAYMENT;
				$this->btnVal	   		      = ADDCONTINUE;
        $this->btnVal2	   		      = SAVEEXIT;
        $data['list_loc_value']       = $this->cashbook_payment_model->getLocation();
        $this->ddlDivision	  	      = $this->input->post('ddlDivision');
        $this->ddlLocation	  	      = $this->input->post('ddlLocation');
        $this->txtDate1      	      = date('d-m-Y');
        $this->ddlTransaction  	      = $this->input->post('ddlTransaction');
        $this->txtDocNumber  	      = ''; //$this->getDocumentNumber();
        $this->ddlModeMain 	  	      = $this->input->post('ddlModeMain');
        $this->compCurr			      = $this->common_model->get_field_name('Company','nCurrency'," ");
        $this->ddlCurrencyMain 	      = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
        $this->txtRate 	      	      = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");
        $this->txtRefNumberMain       = $this->input->post('txtRefNumberMain');
        $this->txtRefDate     	      = $this->input->post('txtRefDate');
        $this->ddlAccount		      = $this->input->post('ddlAccount');
        $this->ddlSubAccount   	      = $this->input->post('ddlSubAccount');
        $this->txtAmountDC            = $this->input->post('txtAmountDC');
        $this->txtAmountFCMain 	      = $this->input->post('txtAmountFCMain');
        $this->txtRefName             = $this->input->post('txtRefName');
        $this->txtEntered             = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$this->session->userdata('AbsAdmin')."' ");
        $this->txtRemarks             = $this->input->post('txtRemarks');
        $this->chkValYes   		  	  = 'checked="checked"';
        $this->chkValNo	   		  	  = '';
        $this->catId	   		  	  = '';

				$this->ddlCurrency  	  	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
				$this->exRate   	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");
				$this->chkSelect   		      = 'checked="checked"';
								
			    $data['chkAccCode']  	  	  = array();
		        $data['chkAccTitle'] 	  	  = array();
				$data['chkAccSubCode'] 	  	  = array();
				$data['chkMode'] 		  	  = array();
				$data['chkCurr'] 	      	  = array();
				$data['chkRefNumber'] 	  	  = array();
				$data['chkAmountFC'] 	  	  = array();
				$data['chkExchangeRate']  	  = array();
				$data['chkAmount']        	  = array();
				$data['chkDes']           	  = array();
                $data['chkDetailID']      	  = array();
				$data['chkTable']             = array();
                $data['chkLoc']           	  = array();

				$data['list_subacc_multiple'] = $this->cashbook_payment_model->getSubAccountValues();
			break;
			
			case "e":
				$this->showTitle      	  = EDITCASHBOOKPAYMENT;
				$this->btnVal	      	  = UPDATECASHBOOKPAYMENT;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
                $data['list_subacc_multiple'] = $this->cashbook_payment_model->getSubAccountValues();
				$showVal	          	  = $this->cashbook_payment_model->getCorresCashbookPayment();
				$this->ddlDivision	  	  = $showVal->nDivision;
				$this->ddlLocation	  	  = $showVal->vLocation;
				$this->txtDate1      	  	  = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
				$this->ddlTransaction  	  = $showVal->nTransaction;
				$this->txtDocNumber  	  = $showVal->vDocNumber;
				$this->ddlModeMain 	  	  = $showVal->vTransMode;
				$this->ddlCurrencyMain 	  = $showVal->nCurrency;
				$this->txtRate  		  = $showVal->vRate;			
				$this->txtRefNumberMain   = $showVal->vRefNumber;

        $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' )?'':date('d-m-Y',strtotime($showVal->dRefDate));
				$this->ddlAccount		  = $showVal->nAcCode;
				$this->ddlSubAccount   	  = $showVal->vSubCode;
				$this->txtAmountDC        = $showVal->vAmountDC;
				$this->txtAmountFCMain 	  = $showVal->vAmountFC;
				$this->txtRefName         = $showVal->vRefName;
				$this->txtEntered         = $showVal->vEnteredBy;
				$this->txtRemarks         = $showVal->vRemarks;
				$this->txtInvoice         = $showVal->vInvoice;
        $this->txtInvDate = ( $showVal->dInvoiceDate == '0000-00-00'  || $showVal->dInvoiceDate == '' )?'':date('d-m-Y',strtotime($showVal->dInvoiceDate ));
		        $data['list_loc_value']   = $this->cashbook_payment_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                 = $showVal->nCPId;
                $this->chkSelect   		  = 'checked="checked"';

				//get selected details code
				$accValArray              = $this->cashbook_payment_model->getCorresCashbookPaymentDetails();
				$data['chkLoc']  	      = $accValArray['acLoc'];
			    $data['chkAccCode']  	  = $accValArray['acCode'];
		        $data['chkAccTitle'] 	  = $accValArray['acTitle'];
				$data['chkAccSubCode'] 	  = $accValArray['acSubCode'];
				$data['chkMode'] 		  = $accValArray['acMode'];
				$data['chkCurr'] 	      = $accValArray['acCurr'];
				$data['chkRefNumber'] 	  = $accValArray['acRefNumber'];
				$data['chkAmountFC'] 	  = $accValArray['acAmountFC'];
				$data['chkExchangeRate']  = $accValArray['acExchangeRate']; 
				$data['chkAmount']        = $accValArray['acAmount'];
				$data['chkDes']           = $accValArray['acDes'];
				$data['chkDetailID']      = $accValArray['acDetailID'];
				$data['chkTable']         = $accValArray['acTable'];
                $data['chkLoc']           = $accValArray['acLoc'];

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
			$this->load->view('cashbook_payment/edit_cashbook_payment');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->cashbook_payment_model->insertCashbookPayment();
				break;
				
				case "e":
					//Update into db
					$this->cashbook_payment_model->updateCashbookPayment();
				break;
			}//end switch
			redirect(site_url("cashbook_payment/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function

/*    function getDocumentNumber()
    {
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='12'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}CashPayment ORDER BY nCPId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='12'");
            $countVal 			= $this->txtDocNumber;
        endif;

        return($countVal);
    }//end function
*/
	function delcashbookpayment()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKPAYMENTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->cashbook_payment_model->deleteCashbookPayment();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKPAYMENTMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->cashbook_payment_model->deleteAllCashbookPayment();
	}//end function 
	
	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->cashbook_payment_model->filterLocation($this->ddlDivision);
		$this->load->view('cashbook_payment/show_location',$data);
	}//end function

    function filterMultipleLocation()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_mloc_value'] = $this->cashbook_payment_model->filterLocation($this->ddlDivision);
		$this->load->view('cashbook_payment/show_location_multiple',$data);
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

        if($this->tableVal=='V'):
            if($this->ddlSubAccount2 == 52 || $this->ddlSubAccount2 == 54 || $this->ddlSubAccount2 == 53):
                $data['list_allc'] = $this->cashbook_payment_model->filterAllocation($this->ddlSubAccount2,$this->ddlAccount2);
            endif;
        endif;

        $this->load->view('cashbook_payment/show_allocation',$data);
    }//end function

    function filterSubAccount()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount2');
        $accType = $this->input->post('tableType');

		$data['list_subacc_multiple'] = $this->cashbook_payment_model->filterSubAccount($accName,$accType);
		$this->load->view('cashbook_payment/show_sub',$data);
	}//end function
	
	//get cash payment details according to the A/c code selected
	function getCashpaymentDetails()
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
		
		$this->load->view('cashbook_payment/show_currency_details');
	}//end function
	
	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtRate 	 = $this->cashbook_payment_model->getRate($this->ddlCurrency);

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
		$this->cashbook_payment_model->deleteSubAndDetails($this->subDetID);
	}//end function

	//search by division or and doc number
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKPAYMENTMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(cp.vDocNumber LIKE '".$this->txtSearch." %' OR cp.vDocNumber LIKE '% ".$this->txtSearch."'  
									OR cp.vDocNumber LIKE '%".$this->txtSearch."%'
									OR d.vTitle LIKE '".$this->txtSearch." %' OR d.vTitle LIKE '% ".$this->txtSearch."'
								OR d.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->cashbook_payment_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'cashbook_payment/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->cashbook_payment_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_payment/showlist');

		$this->load->view('cashbook_payment/cashbook_payment_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBOOKPAYMENTMANAGEMENT;
		
		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if
		
		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(cp.vDocNumber LIKE '".$this->txtSearch." %' OR cp.vDocNumber LIKE '% ".$this->txtSearch."'  
									OR cp.vDocNumber LIKE '%".$this->txtSearch."%'
									OR d.vTitle LIKE '".$this->txtSearch." %' OR d.vTitle LIKE '% ".$this->txtSearch."'
								OR d.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if
		$countFilterList	=  $this->cashbook_payment_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'cashbook_payment/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->cashbook_payment_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'cashbook_payment/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('cashbook_payment/cashbook_payment');
		$this->load->view('general/template_bottom');
	}//end function

    function insertAllocation()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->cashbook_payment_model->insertMultipleAllocation();
    }//end function

    function getInvoiceDetails()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->salesDId         = $this->input->post('salesDetailId');
        $invDetails				= $this->cashbook_payment_model->getInvoiceDetails($this->salesDId);
        echo json_encode($invDetails);
    }//end function

    function printPreview()
    {
        $this->load->helper(array('form', 'url'));
        $this->catId   = $this->uri->segment(3);
 
        session_start();

        $showVal	          	  = $this->cashbook_payment_model->getCorresCashbookPayment();
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->vLocation;
				$this->txtDate1      	  	  = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
        $this->ddlTransaction  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlModeMain 	  	  = $showVal->vTransMode;
        $this->ddlCurrencyMain 	  = $showVal->nCurrency;
        $this->txtRate  		  = $showVal->vRate;
        $this->txtRefNumberMain   = $showVal->vRefNumber;
        $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' )?'':date('d-m-Y',strtotime($showVal->dRefDate));
        $this->ddlAccount		  = $showVal->nAcCode;
        $this->ddlSubAccount   	  = $showVal->vSubCode;
        $this->txtAmountDC        = $showVal->vAmountDC;
        $this->txtAmountFCMain 	  = $showVal->vAmountFC;
        $this->txtRefName         = $showVal->vRefName;
        $this->txtEntered         = $showVal->vEnteredBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $this->txtInvoice         = $showVal->vInvoice;
        $this->txtInvDate         = $showVal->dInvoiceDate;
        $this->Id                 = $showVal->nCPId;

        //get selected details code
        $accValArray              = $this->cashbook_payment_model->getCorresCashbookPaymentDetails();
        $data['chkLoc']  	      = $accValArray['acLoc'];
        $data['chkAccCode']  	  = $accValArray['acCode'];
        $data['chkAccTitle'] 	  = $accValArray['acTitle'];
        $data['chkAccSubCode'] 	  = $accValArray['acSubCode'];
        $data['chkMode'] 		  = $accValArray['acMode'];
        $data['chkCurr'] 	      = $accValArray['acCurr'];
        $data['chkRefNumber'] 	  = $accValArray['acRefNumber'];
        $data['chkAmountFC'] 	  = $accValArray['acAmountFC'];
        $data['chkExchangeRate']  = $accValArray['acExchangeRate'];
        $data['chkAmount']        = $accValArray['acAmount'];
        $data['chkDes']           = $accValArray['acDes'];
        $data['chkDetailID']      = $accValArray['acDetailID'];
        $data['chkTable']         = $accValArray['acTable'];
        $data['chkLoc']           = $accValArray['acLoc'];

        $mydata = $this->load->view('cashbook_payment/export_files/print_cash_payment',$data,true);
        $_SESSION['print_cash_payment'] = $mydata;
        $_SESSION['print_cash_payment_pgSz'] = 'A4';
        $_SESSION['print_cash_payment_lp'] = 'P';


        $this->load->view('general/title');
        $this->load->view('cashbook_payment/print_cash_payment',$data);
    }//end function

	function getDocumentNumber()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
    $this->ddlLocation      = $this->input->post('ddlLocation');
	 $this->dt      = $this->input->post('dt');
	 $date=substr($this->dt,-4);
    $type = "Cash Payment";


    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
	//echo "SELECT vDocNumber FROM {PRE}CashPayment WHERE vLocation = '". $this->ddlLocation."' AND YEAR(dDate) ='". $date."'  ORDER BY nCPId DESC,dDate DESC LIMIT 0,1";
    $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}CashPayment WHERE vLocation = '". $this->ddlLocation."' AND YEAR(dDate) ='". $date."'  ORDER BY nCPId DESC,dDate DESC LIMIT 0,1");
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
  function getDocumentNumber_old()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $this->ddlLocation      = $this->input->post('ddlLocation');
    $type = "Cash Payment";

    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}CashPayment WHERE vLocation = '". $this->ddlLocation."'  ORDER BY nCPId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber = $totalNum->vDocNumber;
            $docNumber      = @split('-',$this->txtDocNumber);
            $fill         = $this->txtMinDigit;
            $number       = ($docNumber[3]+1);
            $lDigit       = str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE vTitle='".$type."'");
            $docNumber      = @split('-',$this->txtDocNumber);
            $countVal       = $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        echo $countVal;
  }//end function


  function getAllAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_account_multiple'] = $this->common_model->getAllAccounts();
    $this->load->view('cashbook_payment/show_allAccounts',$data);

  }

  function getCashAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_account'] = $this->common_model->getCashAccounts();
    $this->load->view('cashbook_payment/show_cashAccounts',$data);

  }


  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('cashbook_payment/show_emailDetails',$data);

  }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
