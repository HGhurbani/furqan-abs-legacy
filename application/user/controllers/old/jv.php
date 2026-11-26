<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Jv extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('jv_model');
        $this->load->model('cron_job_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = JVMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->cron_job_model->checkAllocationTable();
        $this->session->unset_userdata('backURL');

        $countJv =  $this->jv_model->getCountJv();

        $target 			  = '.';
        $this->common_model->_paging($countJv, $target,'jv/showlist/'.$this->uri->segment(3));
        $data['list_value']   = $this->jv_model->getAllJv();
        $this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'jv/showlist');
        $this->load->view('general/template_top',$data);
        $this->load->view('jv/jv');
        $this->load->view('general/template_bottom');
    }//end function

    function editJv()
    {
        $this->common_model->check_admin_authorization();
        $data['title'] 	   		  = $this->common_model->get_site_title();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ddlDivision', 'Division', 'required');
        $id 					   	     = ($this->uri->segment(4) != '') ? $this->uri->segment(4) : 'a';

//User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'jv/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("jv/showlist/".$this->uri->segment(3).'/no_access'));
    }


        $data['list_div_value']   		 = $this->jv_model->getActiveDivision();
        $data['list_trans_value']    	 = $this->jv_model->getActiveTransactions();
        $data['list_account']	  	  	 = $this->jv_model->getActiveAccount();
        $data['list_sub_acc']	  	  	 = $this->jv_model->getActiveSubAccounts();
        $data['list_account_multiple']   = $this->common_model->getAllAccounts();
        $data['list_subacc_multiple']    = $this->jv_model->getSubAccountMultipleValues();
        $data['list_debit_mode']         = $this->jv_model->getDebitModeType();
        $data['list_mode']        	     = $this->jv_model->getModeType();
        $data['list_curr_value']	  	 = $this->jv_model->getActiveCurrency();
        $data['list_mloc_value']  		 = $this->jv_model->getMultipleLocation();
        switch($id)
        {
            case "a":
                $this->showTitle   		  = ADDNEWJV;
                $this->btnVal	   		  = ADDCONTINUE;
                $this->btnVal2	   		  = SAVEEXIT;
                $data['list_loc_value']   = $this->jv_model->getLocation();
                $this->ddlDivision	  	  = $this->input->post('ddlDivision');
                $this->ddlLocation	  	  = $this->input->post('ddlLocation');
                $this->txtDate1      	  = date('d-m-Y');
                $this->ddlTransaction  	  = $this->input->post('ddlTransaction');
                $this->txtDocNumber  	  = '';// $this->getDocumentNumber();
                $this->compCurr			  = $this->common_model->get_field_name('Company','nCurrency'," ");
                $this->ddlCurrencyMain 	  = $this->common_model->get_field_name('Currency','nId'," WHERE nId = '".$this->compCurr."'");
                $this->txtRate 	      	  = $this->common_model->get_field_name('Currency','vRate'," WHERE nId = '".$this->compCurr."'");
                $this->txtRefNumberMain   = $this->input->post('txtRefNumberMain');
                $this->txtRefDate     	  = $this->input->post('txtRefDate');
                $this->txtAprNum		  = $this->input->post('txtAprNum');
                $this->txtAprDate   	  = $this->input->post('txtAprDate');
                $this->txtAmountDC        = $this->input->post('txtAmountDC');
                $this->txtAmountFCMain 	  = $this->input->post('txtAmountFCMain');
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
                $data['chkCurr'] 	      = array();
                $data['chkRefNumber'] 	  = array();
                $data['chkAmountFC'] 	  = array();
                $data['chkExchangeRate']  = array();
                $data['chkAmount']        = array();
                $data['chkDetailID']      = array();
                $data['chkLoc']           = array();
                $data['chkTable']         = array();
                break;
            case "e":
                $this->showTitle      	  = EDITJV;
                $this->btnVal	      	  = UPDATEJV;
                $this->catId	      	  = $this->uri->segment(5);
                if(intval($this->input->post('nId')) != 0)
                {
                    $this->catId      	  = intval($this->input->post('nId'));
                }//end if
                $showVal	          	  = $this->jv_model->getCorresJv();
                $this->ddlDivision	  	  = $showVal->nDivision;
                $this->ddlLocation	  	  = $showVal->vLocation;
                $this->txtDate1             = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
                $this->ddlTransaction  	  = $showVal->nTransaction;
                $this->txtDocNumber  	  = $showVal->vDocNumber;
                $this->ddlCurrencyMain 	  = $showVal->nCurrency;
                $this->txtRate  		  = $showVal->vRate;
                $this->txtRefNumberMain   = $showVal->vRefNumber;
                $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' )?'':date('d-m-Y',strtotime($showVal->dRefDate));
                $this->txtAprNum		  = $showVal->vApprNumber;
                $this->txtAprDate = ( $showVal->dApprDate == '0000-00-00'  || $showVal->dApprDate == '' )?'':date('d-m-Y',strtotime($showVal->dApprDate));
                $this->txtAmountDC        = $showVal->vAmountDC;
                $this->txtAmountFCMain 	  = $showVal->vAmountFC;
                $this->txtRefName         = $showVal->vRefName;
                $this->txtEntered         = $showVal->vEnteredBy;
                $this->txtRemarks         = $showVal->vRemarks;
                $data['list_loc_value']   = $this->jv_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                 = $showVal->nJVId;
                //get selected details code
                $accValArray              = $this->jv_model->getCorresJvDetails();
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
        if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate1')))) != 1))
        {
            $this->load->view('general/template_top',$data);
            $this->load->view('jv/edit_jv');
            $this->load->view('general/template_bottom');
        }//end if
        else
        {
            //checking segment
            switch($this->uri->segment(4))
            {
                case "a":
                    //insert into db
                    $this->jv_model->insertJv();
                break;

                case "e":
                    //Update into db
                    $this->jv_model->updateJv();
                    break;
            }//end switch
            redirect(site_url("jv/showlist/".$this->uri->segment(3).'/'.$msgVal));
        }//end else
    }//end function
/*
    function getDocumentNumber()
    {
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='19'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}Jv ORDER BY nJVId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='19'");
            $countVal 			= $this->txtDocNumber;
        endif;
        return($countVal);
    }//end function
*/
    function deljv()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = JVMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->jv_model->deleteJv();
    }//end function

    function delrecords()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = JVMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->jv_model->deleteAllJv();
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');
        $data['list_loc_value'] = $this->jv_model->filterLocation($this->ddlDivision);
        $this->load->view('jv/show_location',$data);
    }//end function

    //get cash receipt details according to the A/c code selected
    function getCashreceiptDetails()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->txtAcccode = $this->input->post('txtAcccode');
        $this->txtTitle	  = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nACode='".$this->txtAcccode."'");
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
        $this->load->view('jv/show_currency_details');
    }//end function

    function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtRate 	 = $this->jv_model->getRate($this->ddlCurrency);
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
        $this->jv_model->deleteSubAndDetails($this->subDetID);
    }//end function

    //search by division or and doc number
    function filterList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = JVMANAGEMENT;
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
        $countFilterList	=  $this->jv_model->getCountfilterList($extraCheck);
        $target 			= '.';
        $this->common_model->_paging($countFilterList, $target,'jv/filterList2/'.$this->uri->segment(3));

        $data['list_value'] = $this->jv_model->getFilterList($extraCheck);
        $this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'jv/showlist');
        $this->load->view('jv/jv_filter',$data);
    }//end function

    function filterList2()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = JVMANAGEMENT;
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
        $countFilterList	=  $this->jv_model->getCountfilterList($extraCheck);
        $target 			= '.';
        $this->common_model->_paging($countFilterList, $target,'jv/filterList2/'.$this->uri->segment(3));
        $data['list_value'] = $this->jv_model->getFilterList($extraCheck);
        $this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'jv/showlist');
        $this->load->view('general/template_top',$data);
        $this->load->view('jv/jv');
        $this->load->view('general/template_bottom');
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount2');
        $accType = $this->input->post('tableType');

        $data['list_subacc_multiple'] = $this->jv_model->filterSubAccount($accName,$accType);
        $this->load->view('jv/show_sub',$data);
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
            if($this->ddlSubAccount2 == 32 || $this->ddlSubAccount2 == 34 || $this->ddlSubAccount2 == 35):
                $data['list_allc'] = $this->jv_model->filterAllocation($this->ddlSubAccount2,$this->ddlAccount2);
            else:
                $data['list_allc'] = $this->jv_model->filterAllocation2($this->ddlAccount2,$this->ddlSubAccount2);
            endif;
        else:
            $data['list_allc'] = $this->jv_model->filterAllocation2($this->ddlAccount2,$this->ddlSubAccount2);
        endif;

        $this->load->view('jv/show_allocation',$data);
    }//end function

    function insertAllocation()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->jv_model->insertMultipleAllocation();
    }//end function

    function getInvoiceDetails()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->salesDId         = $this->input->post('salesDetailId');
        $invDetails				= $this->jv_model->getInvoiceDetails($this->salesDId);
        echo json_encode($invDetails);
    }//end function
    function printPreview()
    {
      session_start();
        $this->load->helper(array('form', 'url'));
        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);
        $showVal	          	  = $this->jv_model->getCorresJv();
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->vLocation;
        $this->txtDate1             = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
        $this->ddlTransaction  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlCurrencyMain 	  = $showVal->nCurrency;
        $this->txtRate  		  = $showVal->vRate;
        $this->txtRefNumberMain   = $showVal->vRefNumber;
        $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' ||  $showVal->dRefDate == '0')?'':date('d-m-Y',strtotime($showVal->dRefDate));
        $this->txtAprNum		  = $showVal->vApprNumber;
        $this->txtAprDate   	  = $showVal->dApprDate;
        $this->txtAmountDC        = $showVal->vAmountDC;
        $this->txtAmountFCMain 	  = $showVal->vAmountFC;
        $this->txtRefName         = $showVal->vRefName;
        $this->txtEntered         = $showVal->vEnteredBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $data['list_loc_value']   = $this->jv_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                 = $showVal->nJVId;

        //get selected details code
        $accValArray              = $this->jv_model->getCorresJvDetails();
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
        $data['chkDetailID']      = $accValArray ['acDetailID'];
        $data['chkLoc']           = $accValArray ['acLoc'];
        $data['chkTable']         = $accValArray ['acTable'];

        $mydata = $this->load->view('jv/export_files/print_jv',$data,true);
        $_SESSION['print_jv'] = $mydata;
        $_SESSION['print_jv_pgSz'] = 'A4';
        $_SESSION['print_jv_lp'] = 'P';

        $this->load->view('general/title');
        $this->load->view('jv/print_jv',$data);
    }//end function

    //This function is also used for print privew
    function printAgentPreview()
    {
        $this->load->helper(array('form', 'url'));
      session_start();
        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	          	  = $this->jv_model->getCorresJv();
        $this->ddlDivision	  	  = $showVal->nDivision;
        $this->ddlLocation	  	  = $showVal->vLocation;
        $this->txtDate1             = ( $showVal->dDate == '0000-00-00'  || $showVal->dDate == '' )?'':date('d-m-Y',strtotime($showVal->dDate));
        $this->ddlTransaction  	  = $showVal->nTransaction;
        $this->txtDocNumber  	  = $showVal->vDocNumber;
        $this->ddlCurrencyMain 	  = $showVal->nCurrency;
        $this->txtRate  		  = $showVal->vRate;
        $this->txtRefNumberMain   = $showVal->vRefNumber;
        $this->txtRefDate = ( $showVal->dRefDate == '0000-00-00'  || $showVal->dRefDate == '' )?'':date('d-m-Y',strtotime($showVal->dRefDate));
        $this->txtAprNum		  = $showVal->vApprNumber;
        $this->txtAprDate = ( $showVal->dApprDate == '0000-00-00'  || $showVal->dApprDate == '' )?'':date('d-m-Y',strtotime($showVal->dApprDate));
        $this->txtAmountDC        = $showVal->vAmountDC;
        $this->txtAmountFCMain 	  = $showVal->vAmountFC;
        $this->txtRefName         = $showVal->vRefName;
        $this->txtEntered         = $showVal->vEnteredBy;
        $this->txtRemarks         = $showVal->vRemarks;
        $data['list_loc_value']   = $this->jv_model->getCorrActiveLocation($this->ddlDivision);
        $this->Id                 = $showVal->nJVId;

        //get selected details code

        $accValArray              = $this->jv_model->getCorresJvDetails();
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
        $data['chkDetailID']      = $accValArray ['acDetailID'];
        $data['chkLoc']           = $accValArray ['acLoc'];
        $data['chkTable']         = $accValArray ['acTable'];


        $mydata = $this->load->view('jv/export_files/print_jv',$data,true);
        $_SESSION['print_jv'] = $mydata;
        $_SESSION['print_jv_pgSz'] = 'A4';
        $_SESSION['print_jv_lp'] = 'P';


        $this->load->view('general/title');
        $this->load->view('jv/print_jv',$data);
    }//end function

	
	function getDocumentNumber()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));
		$this->common_model->check_admin_authorization();
    $this->ddlLocation      = $this->input->post('ddlLocation');
	 $this->dt      = $this->input->post('dt');
	 $date=substr($this->dt,-4);
    $type = "Journal Voucher";


    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}Jv WHERE vLocation = '". $this->ddlLocation."' AND YEAR(dDate) ='". $date."'  ORDER BY nJVId DESC,dDate DESC LIMIT 0,1");
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
    $type = "Journal Voucher";

    $this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
    $this->txtMinDigit    = $this->common_model->get_field_name('Transactions','vMindigit'," WHERE vTitle='".$type."'");
    $fetchCode            = $this->db->query("SELECT vDocNumber FROM {PRE}Jv WHERE vLocation = '". $this->ddlLocation."'  ORDER BY nJVId DESC LIMIT 0,1");
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
    $this->load->view('jv/show_allAccounts',$data);

  }



  function getEmailDetails()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['ddlLocation'] = $this->input->post('ddlLocation');
    $this->load->view('jv/show_emailDetails',$data);

  }

}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
