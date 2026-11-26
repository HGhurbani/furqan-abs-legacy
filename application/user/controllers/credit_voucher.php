<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Credit_voucher extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('creditvoucher_model');
        $this->load->model('cron_job_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = CREDITVOUCHERMANAGEMENT;
		$this->common_model->check_admin_authorization();

        $this->cron_job_model->checkAllocationTable();
        $this->session->unset_userdata('backURL');
		$countCreditVoucher   =  $this->creditvoucher_model->getCountCreditVoucher();

		$target 			  = '.';
		$this->common_model->_paging($countCreditVoucher, $target,'credit_voucher/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->creditvoucher_model->getAllCreditVoucher();
		$this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'credit_voucher/showlist');
		$this->load->view('general/template_top',$data);
		$this->load->view('credit_voucher/credit_voucher');
		$this->load->view('general/template_bottom');
	}//end function

	function editCreditVoucher()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');

		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
    $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';

//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'credit_voucher/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("credit_voucher/showlist/".$this->uri->segment(3).'/no_access'));
    }



		$data['list_div_value']   		 = $this->creditvoucher_model->getActiveDivision();
		$data['list_trans_value']    	 = $this->creditvoucher_model->getActiveTransactions();
		$data['list_curr_value']	  	 = $this->creditvoucher_model->getActiveCurrency();
		$data['list_mode']	  	         = $this->creditvoucher_model->getActiveMode();
        $data['list_account_multiple']   = $this->creditvoucher_model->getAccountMultipleValues();
        $data['list_mloc_value']  		 = $this->creditvoucher_model->getMultipleLocation();

		switch($id)
		{
			case "a":
				$this->showTitle   		  = ADDNEWCREDITVOUCHER;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
		        $data['list_loc_value']   = $this->creditvoucher_model->getActiveLocations();
                $this->ddlDivision	  	  = $this->input->post('ddlDivision');
                $this->ddlLocation	  	  = $this->input->post('ddlLocation');
                $this->txtDate      	  = date('d-m-Y');
                $this->ddlTransaction  	  = $this->input->post('ddlTransaction');
                $this->txtDocNumber  	  = '';//$this->getDocumentNumber();
				$this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
				$this->ddlCurrencyMain 	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
				$this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");
                $this->txtCheqNumber      = $this->input->post('txtCheqNumber');
                $this->txtCheqDate    	  = $this->input->post('txtCheqDate');
                $this->txtRefNumberMain   = $this->input->post('txtRefNumberMain');
                $this->txtRefDate    	  =  $this->input->post('txtRefDate');
                $this->txtRefName         = $this->input->post('txtRefName');
                $this->txtEntered         = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$this->session->userdata('AbsAdmin')."' ");
				$this->txtRemarks         = $this->input->post('txtRemarks');

				$this->chkValYes   		  = 'checked="checked"';
				$this->chkValNo	   		  = '';
				$this->catId	   		  = '';
				$this->ddlCurrency  	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
				$this->exRate   	      = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");

			    $data['chkAccCode']  	  = array();
		        $data['chkAccTitle'] 	  = array();
				$data['chkAccSubCode'] 	  = array();
				$data['chkMode'] 		  = array();
				$data['chkRefType'] 	  = array();
				$data['chkRefNumber'] 	  = array();
				$data['chkAmountFC'] 	  = array();
				$data['chkExchangeRate']  = array();
				$data['chkAmount']        = array();
				$data['chkDes']           = array();
				$data['chkAccID']    	  = array();

				$data['list_subacc_multiple'] = $this->creditvoucher_model->getSubAccountValues();
			break;

			case "e":
				$this->showTitle      	  = EDITCREDITVOUCHER;
				$this->btnVal	      	  = UPDATECREDITVOUCHER;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if

        $data['list_subacc_multiple'] = $this->creditvoucher_model->getSubAccountValues();
        $showVal	          	  = $this->creditvoucher_model->getCorresCreditVoucher();
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->vLocation;
        if($showVal->dDate == '0000-00-00' || $showVal->dDate=='') {
          $this->txtDate          =  '';
        }
        else 
        {
          $this->txtDate      	  =  date('d-m-Y', strtotime($showVal->dDate));
        }
        $this->ddlTransaction  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlCurrencyMain 	  = $showVal->nCurrency;
        $this->txtRate  		  = $showVal->vRate;
        $this->txtCheqNumber      = $showVal->vChequeNumber;
        if ($showVal->txtCheqDate== '0000-00-00' || $showVal->txtCheqDate =='') {
          $this->txtCheqDate =  '';
        }
        else 
        {

          $this->txtCheqDate    	  =  date('d-m-Y', strtotime($showVal->dChequeDate));
        }
        $this->txtRefNumberMain   = $showVal->vRefNumber;
        if ($showVal->dRefDate == '0000-00-00' || $showVal->dRefDate =='') {
          $this->dRefDate =  '';
        }
        else 
        {
          $this->txtRefDate    	  =  date('d-m-Y', strtotime($showVal->dRefDate));
        }
        $this->txtRefName         = $showVal->vRefName;
        $this->txtEntered         = $showVal->vEnteredBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $this->txtInvoice         = $showVal->vInvoice;
        if ($showVal->dInvoiceDate == '0000-00-00' || $showVal->dInvoiceDate =='') {
          $this->dInvoiceDate=  '';
        }
        else 
        {
          $this->txtInvDate         =  date('d-m-Y', strtotime($showVal->dInvoiceDate));
        }
        $data['list_loc_value']       = $this->creditvoucher_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                 = $showVal->nCVId;

				//get selected details code
				$accValArray              = $this->creditvoucher_model->getCorresCreditVoucherDetails();
                $data['chkLoc']  	      = $accValArray ['acLoc'];
                $data['chkAccCode']  	  = $accValArray ['acCode'];
                $data['chkAccTitle'] 	  = $accValArray ['acTitle'];
                $data['chkAccSubCode'] 	  = $accValArray ['acSubCode'];
                $data['chkMode'] 		  = $accValArray ['acMode'];
                $data['chkCurr'] 	      = $accValArray ['acCurr'];
                $data['chkRefNumber'] 	  = $accValArray ['acRefNumber'];
                $data['chkAmountFC'] 	  = $accValArray ['acAmountFC'];
                $data['chkExchangeRate']  = $accValArray ['acExchangeRate'];
                $data['chkAmount']        = $accValArray ['acAmount'];
				$data['chkDes']           = $accValArray ['acDes'];
                $data['chkDetailID']      = $accValArray ['acDetailID'];
                $data['chkLoc']           = $accValArray ['acLoc'];
                $data['chkTable']         = $accValArray ['acTable'];

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
			$this->load->view('credit_voucher/edit_credit_voucher');
			$this->load->view('general/template_bottom');
		}//end if

		else
		{
		    //checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->creditvoucher_model->insertCreditVoucher();
				break;

				case "e":
					//Update into db
					$this->creditvoucher_model->updateCreditVoucher();
				break;
			}//end switch
			redirect(site_url("credit_voucher/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else
	}//end function
    //angela

    function printPreview()
    {
      session_start();
        $this->load->helper(array('form', 'url'));
        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

		$showVal	          	  = $this->creditvoucher_model->getCorresCreditVoucher();
		$this->ddlDivision	  	  = $showVal->nDivision;
		$this->ddlLocation	  	  = $showVal->vLocation;



		if($showVal->dDate == '0000-00-00' || $showVal->dDate=='') {
          $this->txtDate          =  '';
        }
        else
        {
          $this->txtDate          =  date('d-m-Y', strtotime($showVal->dDate));
        }
        if ($showVal->txtCheqDate== '0000-00-00' || $showVal->txtCheqDate =='') {
          $this->txtCheqDate =  '';
        }
        else
        {

          $this->txtCheqDate        =  date('d-m-Y', strtotime($showVal->dChequeDate));
        }
        if ($showVal->dRefDate == '0000-00-00' || $showVal->dRefDate =='' ||  $showVal->dRefDate == 0) {
          $this->dRefDate =  '';
        }
        else
        {
          $this->txtRefDate       =  date('d-m-Y', strtotime($showVal->dRefDate));
        }
		$this->ddlTransaction  	  = $showVal->nTransaction;
		$this->txtDocNumber  	  = $showVal->vDocNumber;
		$this->ddlCurrencyMain 	  = $showVal->nCurrency;
		$this->txtRate  		  = $showVal->vRate;
		$this->txtCheqNumber      = $showVal->vChequeNumber;
		$this->txtRefNumberMain   = $showVal->vRefNumber;
		$this->txtRefName         = $showVal->vRefName;
		$this->txtEntered         = $showVal->vEnteredBy;
		$this->txtRemarks         = $showVal->vRemarks;
		$this->txtInvoice         = $showVal->vInvoice;
		$this->txtInvDate         = $showVal->dInvoiceDate;
		$this->Id                 = $showVal->nCVId;

		$accValArray              = $this->creditvoucher_model->getCorresCreditVoucherDetails();
		$data['chkLoc']  	      = $accValArray ['acLoc'];
		$data['chkAccCode']  	  = $accValArray ['acCode'];
		$data['chkAccCodeName']   = $accValArray ['acCodeName'];
		$data['chkAccTitle'] 	  = $accValArray ['acTitle'];
		$data['chkAccSubCode'] 	  = $accValArray ['acSubCode'];
		$data['chkMode'] 		  = $accValArray ['acMode'];
		$data['chkCurr'] 	      = $accValArray ['acCurr'];
		$data['chkRefNumber'] 	  = $accValArray ['acRefNumber'];
		$data['chkAmountFC'] 	  = $accValArray ['acAmountFC'];
		$data['chkExchangeRate']  = $accValArray ['acExchangeRate'];
		$data['chkAmount']        = $accValArray ['acAmount'];
		$data['chkDes']           = $accValArray ['acDes'];
		$data['chkDetailID']      = $accValArray ['acDetailID'];
		$data['chkLoc']           = $accValArray ['acLoc'];
		$data['chkTable']         = $accValArray ['acTable'];

    
        $mydata = $this->load->view('credit_voucher/export_files/print_credit_voucher',$data,true);
        $_SESSION['print_credit_voucher'] = $mydata;
        $_SESSION['print_credit_voucher_pgSz'] = 'A4';
        $_SESSION['print_credit_voucher_lp'] = 'P';

        $this->load->view('general/title');
        $this->load->view('credit_voucher/print_credit_voucher',$data);

    }//end function

/*    function getDocumentNumber()
    {
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='13'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}CreditVoucher ORDER BY nCVId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);

            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='13'");
            $countVal 			= $this->txtDocNumber;
        endif;
        return($countVal);
    }//end function
*/
	function delcreditvoucher()
    {
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CREDITVOUCHERMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->creditvoucher_model->deleteCreditVoucher();
	}//end function

	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CREDITVOUCHERMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->creditvoucher_model->deleteAllCreditVoucher();
	}//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->creditvoucher_model->filterLocation($this->ddlDivision);
		$this->load->view('credit_voucher/show_location',$data);
	}//end function

	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		$searchKey =  $this->input->post('q');

		$items     = $this->creditvoucher_model->searchActiveFinance($searchKey);
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

 	function getTransactionDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		//fetch transaction details
		$this->ddlTransaction   = $this->input->post('ddlTransaction');
		$this->transId			= $this->common_model->get_field_name('Transactions','nId'," WHERE nId = '".$this->ddlTransaction."'");
		$this->txtDocNumber     = $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId = '".$this->transId."'");
		$this->txtMode          = $this->common_model->get_field_name('Transactions','vTranmode'," WHERE nId = '".$this->transId."'");

		$this->load->view('credit_voucher/show_transaction_details');

	}//end function

 	function getCurrencyDetails()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();

		//fetch currency details

		$this->ddlCurrency      = $this->input->post('ddlCurrency');
		$this->transId			= $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->ddlCurrency."'");
		$this->txtRate          = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->transId."'");
		$this->load->view('credit_voucher/show_currency_details');
	}//end function

	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtExchange 	 = $this->creditvoucher_model->getRate($this->ddlCurrency);
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
		$convRate               = $this->creditvoucher_model->onlineCurrencyConverstion();
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
		$this->creditvoucher_model->deleteSubAndDetails($this->subDetID);
	}//end function

	//search by division or and doc number
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CREDITVOUCHERMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if

		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(cv.vDocNumber LIKE '".$this->txtSearch." %' OR cv.vDocNumber LIKE '% ".$this->txtSearch."'
									OR cv.vDocNumber LIKE '%".$this->txtSearch."%'
								OR d.vTitle LIKE '".$this->txtSearch." %' OR d.vTitle LIKE '% ".$this->txtSearch."'
									OR d.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if

		$countFilterList	=  $this->creditvoucher_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'credit_voucher/filterList2/'.$this->uri->segment(3));
		$data['list_value'] = $this->creditvoucher_model->getFilterList($extraCheck);

		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'credit_voucher/showlist');
		$this->load->view('credit_voucher/credit_voucher_filter',$data);

	}//end function

	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CREDITVOUCHERMANAGEMENT;

		$this->common_model->check_admin_authorization();
		$this->txtSearch = $this->common_model->searchKeyword('txtSearch');

		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtSearch');
			$this->txtSearch = $this->common_model->searchKeyword('txtSearch');
		}//end if

		if(!empty($this->txtSearch))
		{
			$extraCheck 	= " WHERE(cv.vDocNumber LIKE '".$this->txtSearch." %' OR cv.vDocNumber LIKE '% ".$this->txtSearch."'
									OR cv.vDocNumber LIKE '%".$this->txtSearch."%'
									OR d.vTitle LIKE '".$this->txtSearch." %' OR d.vTitle LIKE '% ".$this->txtSearch."'
								OR d.vTitle LIKE '%".$this->txtSearch."%')";
		}//end if

		$countFilterList	=  $this->creditvoucher_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'credit_voucher/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->creditvoucher_model->getFilterLocation($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'credit_voucher/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('credit_voucher/credit_voucher');
		$this->load->view('general/template_bottom');
	}//end function

    function filterSubAccount()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount2');
        $accType = $this->input->post('tableType');
		$data['list_subacc_multiple'] = $this->creditvoucher_model->filterSubAccount($accName,$accType);
		$this->load->view('credit_voucher/show_sub',$data);
	}//end function

    function getAllocation()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->session->unset_userdata('AccVal');
        $this->session->unset_userdata('TableVal');

        $this->common_model->check_admin_authorization();
        $this->finishbtnVal	  = FINISH;
        $this->ddlAccount2    = $this->input->post('ddlAccount2');
        $this->ddlSubAccount2 = $this->input->post('ddlSubAccount2');
        $this->idVal          = $this->input->post('idVal');
        $this->tableVal       = $this->input->post('tableVal');

        $this->session->set_userdata('AccVal',$this->ddlAccount2);
        $this->session->set_userdata('TableVal',$this->tableVal);

        if($this->tableVal=='V'):
            if($this->ddlSubAccount2 == 32 || $this->ddlSubAccount2 == 34 || $this->ddlSubAccount2 == 35):
                $data['list_allc'] = $this->creditvoucher_model->filterAllocation($this->ddlSubAccount2,$this->ddlAccount2);
            else:
                $data['list_allc'] = $this->creditvoucher_model->filterAllocation2($this->ddlAccount2,$this->ddlSubAccount2);
            endif;
        else:
            $data['list_allc'] = $this->creditvoucher_model->filterAllocation2($this->ddlAccount2,$this->ddlSubAccount2);
        endif;

        $this->load->view('credit_voucher/show_allocation',$data);
    }//end function

    function insertAllocation()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->creditvoucher_model->insertMultipleAllocation();
    }//end function

    function getInvoiceDetails()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->salesDId         = $this->input->post('salesDetailId');
        $invDetails				= $this->creditvoucher_model->getInvoiceDetails($this->salesDId);
        echo json_encode($invDetails);
    }//end function


  function getDocumentNumber()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $this->ddlLocation      = $this->input->post('ddlLocation');
    $type = "Credit Note";

    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}CreditVoucher WHERE vLocation = '". $this->ddlLocation."'  ORDER BY nCVId DESC LIMIT 0,1");
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
    $this->load->view('credit_voucher/show_allAccounts',$data);

  }


  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('credit_voucher/show_emailDetails',$data);

  }

}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
