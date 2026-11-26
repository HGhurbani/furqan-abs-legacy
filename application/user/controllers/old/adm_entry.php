<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Adm_entry extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('adm_entry_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle 	  = ADMENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();

        $this->session->unset_userdata('backURL');

		$countAdmEntry   =  $this->adm_entry_model->getCountAdmEntry();
		$target 			  = '.';
		$this->common_model->_paging($countAdmEntry, $target,'adm_entry/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->adm_entry_model->getAllAdmEntry();
		$this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'adm_entry/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('adm_entry/adm_entry');
		$this->load->view('general/template_bottom');
	}//end function
	
	function editAdmEntry()
	{
		$this->common_model->check_admin_authorization();
		$data['title'] 	   		  = $this->common_model->get_site_title(); 
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('ddlDivision', 'Division', 'required');
    $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';
//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'adm_entry/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("adm_entry/showlist/".$this->uri->segment(3).'/no_access'));
    }


		$data['list_div_value']   		 = $this->adm_entry_model->getActiveDivision();
		$data['list_trans_value']    	 = $this->adm_entry_model->getActiveTransactions();
        $data['list_debit_mode']         = $this->adm_entry_model->getDebitModeType();
		$data['list_curr_value']	  	 = $this->adm_entry_model->getActiveCurrency();
		$data['list_mode']	  	         = $this->adm_entry_model->getActiveMode();
        $data['list_account_multiple']   = $this->adm_entry_model->getAccountMultipleValues();
        $data['list_mloc_value']  		 = $this->adm_entry_model->getMultipleLocation();
        $data['list_account']	  	  	 = $this->adm_entry_model->getActiveAccount();
		
		switch($id)
		{
			case "a":
				$this->showTitle   		  = ADDNEWADMENTRY;
				$this->btnVal	   		  = ADDCONTINUE;
				$this->btnVal2	   		  = SAVEEXIT;
		        $data['list_loc_value']   = $this->adm_entry_model->getActiveLocations();
                $data['list_sub_acc']     = $this->adm_entry_model->getActiveA();
                $this->ddlDivision	  	  = $this->input->post('ddlDivision');
                $this->ddlLocation	  	  = $this->input->post('ddlLocation');
                $this->txtDate      	  = date('d-m-Y');
                $this->ddlTransaction  	  = $this->input->post('ddlTransaction');
                $this->txtDocNumber  	  = '';// $this->getDocumentNumber();
				$this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
				$this->ddlCurrencyMain 	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
				$this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");
                $this->txtCheqNumber      = $this->input->post('txtCheqNumber');
                $this->txtCheqDate    	  = $this->input->post('txtCheqDate');
                $this->txtRefNumberMain   = $this->input->post('txtRefNumberMain');
                $this->txtRefDate    	  = $this->input->post('txtRefDate');
                $this->txtRefName         = $this->input->post('txtRefName');
                $this->txtEntered         = $this->common_model->get_field_name('Admin','vUsername'," WHERE nId='".$this->session->userdata('AbsAdmin')."' ");
				$this->txtRemarks         = $this->input->post('txtRemarks');
                $this->ddlModeMain    	  = $this->input->post('ddlModeMain');
                $this->txtAmountDC    	  = $this->input->post('txtAmountDC');
                $this->txtAmountFCMain    = $this->input->post('txtAmountFCMain');
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
                $data['chkTable']    	  = array();

				$data['list_subacc_multiple'] = $this->adm_entry_model->getSubAccountValues();
			break;
			
			case "e":
				$this->showTitle      	  = EDITADMENTRY;
				$this->btnVal	      	  = UPDATEADMENTRY;
				$this->catId	      	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId      	  = intval($this->input->post('nId'));
				}//end if
        $data['list_subacc_multiple'] = $this->adm_entry_model->getSubAccountValues();
        $showVal	          	  = $this->adm_entry_model->getCorresAdmEntry();
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->vLocation;
        $this->txtDate             = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
        $this->ddlTransaction  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlCurrencyMain 	  = $showVal->nCurrency;
        $this->txtRate  		  = $showVal->vRate;
        $this->txtCheqNumber      = $showVal->vChequeNumber;
        $this->txtCheqDate= ( $showVal->dChequeDate == '0000-00-00'  || $showVal->dChequeDate == '' )?'':date('d-m-Y',strtotime($showVal->dChequeDate ));
        $this->txtRefNumberMain   = $showVal->vRefNumber;
        $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' )?'':date('d-m-Y',strtotime($showVal->dRefDate));
        $this->txtRefName         = $showVal->vRefName;
        $this->txtEntered         = $showVal->vEnteredBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $this->txtInvoice         = $showVal->vInvoice;
        $this->txtInvDate = ( $showVal->dInvoiceDate == '0000-00-00'  || $showVal->dInvoiceDate == '' )?'':date('d-m-Y',strtotime($showVal->dInvoiceDate ));
        $this->ddlModeMain    	  = $showVal->vTransMode;
        $this->txtAmountDC    	  = $showVal->vAmountDC;
        $this->txtAmountFCMain    = $showVal->vAmountFC;
        $this->ddlSubAccount   	  = $showVal->vSubCode;
        $this->ddlAccount    	  = $showVal->nAcCode;
        $data['list_sub_acc']     = $this->adm_entry_model->getActiveA();

        $data['list_loc_value']       = $this->adm_entry_model->getCorrActiveLocation($this->ddlDivision);

                $this->Id                 = $showVal->nADId;
				
				//get selected details code
				$accValArray              = $this->adm_entry_model->getCorresAdmEntryDetails();
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
			$this->load->view('adm_entry/edit_adm_entry');
			$this->load->view('general/template_bottom');
		}//end if
		else
		{
			//checking segment
			switch($this->uri->segment(4))
			{
				case "a":
					//insert into db
					$this->adm_entry_model->insertAdmEntry();
				break;
				
				case "e":
					//Update into db
					$this->adm_entry_model->updateAdmEntry();
				break;
			}//end switch
			redirect(site_url("adm_entry/showlist/".$this->uri->segment(3).'/'.$msgVal));
		}//end else	
	}//end function
/*
    function getDocumentNumber()
    {
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='13'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}AdmEntry ORDER BY nADId DESC LIMIT 0,1");
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
	function delAdmEntry()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ADMENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->adm_entry_model->deleteAdmEntry();
	}//end function 
	
	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ADMENTRYMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->adm_entry_model->deleteAllAdmEntry();
	}//end function 
	
	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		
		$this->common_model->check_admin_authorization();
		//site Name
		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');
		
		$data['list_loc_value'] = $this->adm_entry_model->filterLocation($this->ddlDivision);
		$this->load->view('adm_entry/show_location',$data);
	}//end function
	
	//checking suggestion list
	function suggestionlist()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
		
		$searchKey =  $this->input->post('q');
		$items     = $this->adm_entry_model->searchActiveFinance($searchKey);
		echo json_encode($items);
	}//end function
	
	//get cash receipt details according to the A/c code selected
	function getAdmEntryDetails()
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
		
		$this->load->view('adm_entry/show_transaction_details');
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
		
		$this->load->view('adm_entry/show_currency_details');
	}//end function
	
	function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtExchange 	 = $this->adm_entry_model->getRate($this->ddlCurrency);

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
		$convRate               = $this->adm_entry_model->onlineCurrencyConverstion();
		
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
		
		$this->adm_entry_model->deleteSubAndDetails($this->subDetID);
	}//end function

	//search by division or and doc number
	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ADMENTRYMANAGEMENT;
		
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
		$countFilterList	=  $this->adm_entry_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'adm_entry/filterList2/'.$this->uri->segment(3));

		$data['list_value'] = $this->adm_entry_model->getFilterList($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'adm_entry/showlist');

		$this->load->view('adm_entry/adm_entry_filter',$data);
	}//end function
	
	function filterList2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = ADMENTRYMANAGEMENT;
		
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
		$countFilterList	=  $this->adm_entry_model->getCountfilterList($extraCheck);
		$target 			= '.';
		$this->common_model->_paging($countFilterList, $target,'adm_entry/filterList2/'.$this->uri->segment(3));
		
		$data['list_value'] = $this->adm_entry_model->getFilterLocation($extraCheck);
		$this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'adm_entry/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('adm_entry/adm_entry');
		$this->load->view('general/template_bottom');
	}//end function

    function filterSubAccount()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount2');
        $accType = $this->input->post('tableType');

		$data['list_subacc_multiple'] = $this->adm_entry_model->filterSubAccount($accName,$accType);
		$this->load->view('adm_entry/show_sub',$data);
	}//end function

    function getSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');

        $data['list_sub_acc'] = $this->adm_entry_model->getActiveAccountHead($accName);
        $this->load->view('adm_entry/show_sub_account',$data);
    }//end function

    function printPreview()
    {
      session_start();
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	          	  = $this->adm_entry_model->getCorresAdmEntry();
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->vLocation;
        $this->txtDate             = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
        $this->ddlTransaction  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlCurrencyMain 	  = $showVal->nCurrency;
        $this->txtRate  		  = $showVal->vRate;
        $this->txtCheqNumber      = $showVal->vChequeNumber;
        $this->txtCheqDate= ( $showVal->dChequeDate == '0000-00-00'  || $showVal->dChequeDate == '' )?'':date('d-m-Y',strtotime($showVal->dChequeDate ));
        $this->txtRefNumberMain   = $showVal->vRefNumber;
        $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' )?'':date('d-m-Y',strtotime($showVal->dRefDate));
        $this->txtRefName         = $showVal->vRefName;
        $this->txtEntered         = $showVal->vEnteredBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $this->txtInvoice         = $showVal->vInvoice;
        $this->txtInvDate         = $showVal->dInvoiceDate;
        $this->ddlAcCode          = $showVal->nAcCode;
        $this->ddlSubCode         = $showVal->vSubCode;
        $this->ddlModeMain    	  = $showVal->vTransMode;
        $this->txtAmountDC    	  = $showVal->vAmountDC;
        $this->txtAmountFCMain    = $showVal->vAmountFC;


        $this->Id                 = $showVal->nADId;

        //get selected details code
        $accValArray              = $this->adm_entry_model->getCorresAdmEntryDetails();
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

        $mydata = $this->load->view('adm_entry/export_files/print_adm_entry',$data,true);
        $_SESSION['print_adm_entry'] = $mydata;
        $_SESSION['print_adm_entry_pgSz'] = 'A4';
        $_SESSION['print_adm_entry_lp'] = 'P';

        $this->load->view('general/title');
        $this->load->view('adm_entry/print_adm_entry',$data);
    }//end function

function getDocumentNumber()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
    $this->ddlLocation      = $this->input->post('ddlLocation');
	 $this->dt      = $this->input->post('dt');
	 $date=substr($this->dt,-4);
    $type = "ADM";


    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}AdmEntry WHERE vLocation = '". $this->ddlLocation."' AND YEAR(dDate) ='". $date."'  ORDER BY nADId DESC,dDate DESC LIMIT 0,1");
	     
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
    $type = "ADM";

    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}AdmEntry WHERE vLocation = '". $this->ddlLocation."'  ORDER BY nADId DESC LIMIT 0,1");
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
    $this->load->view('adm_entry/show_allAccounts',$data);

  }


  function getBSPAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_account'] = $this->common_model->getBSPAccounts();
    $this->load->view('adm_entry/show_BSPAccounts',$data);

  }



  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('adm_entry/show_emailDetails',$data);

  }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
