<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Duplicate_invoice extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('duplicate_invoice_model');
        $this->load->model('sales_closing_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle 	  = DUPLICATEINVOICEMANAGEMENT;
        $this->common_model->check_admin_authorization();

        $countDuplicateInvoice    =  $this->duplicate_invoice_model->getCountDuplicateInvoice();
        $target 			  = '.';
        $this->common_model->_paging($countDuplicateInvoice, $target,'duplicate_invoice/showlist/'.$this->uri->segment(3));
        $data['list_value']   = $this->duplicate_invoice_model->getAllDuplicateInvoice();
        $this->count_rows     = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'duplicate_invoice/showlist');

        $this->load->view('general/template_top',$data);
        $this->load->view('duplicate_invoice/duplicate_invoice');
        $this->load->view('general/template_bottom');
    }//end function

    function editDuplicateInvoice()
    {
        $this->common_model->check_admin_authorization();
        $data['title'] 	   		  = $this->common_model->get_site_title();
        $this->load->helper(array('form', 'url'));
        $this->load->library('form_validation');
        $this->form_validation->set_rules('ddlDivision', 'Division', 'required');
        $id = $this->uri->segment(4);

        //User Access aa
        $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'duplicate_invoice/showlist');
        $data['action_access'] = $action_access;
        if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
          if(trim($this->session->userdata('backURL')) != '')
            redirect(site_url($this->session->userdata('backURL')));
          else
            redirect(site_url("duplicate_invoice/showlist/".$this->uri->segment(3).'/no_access'));
        }

        $data['list_div_value']   		 = $this->duplicate_invoice_model->getActiveDivision();
        $data['list_type_value']	 	 = $this->duplicate_invoice_model->getTypeValue();
        $data['list_agent_value']        = $this->duplicate_invoice_model->getAgentAccounts();
        $data['list_type']	 	         = $this->duplicate_invoice_model->getTicketType();
        $data['list_tkt_value']          = $this->duplicate_invoice_model->getActiveTickets();
        $data['list_acc_type']	 	     = $this->duplicate_invoice_model->getAccTicketType();
        $data['list_acc_tkt_value']      = $this->duplicate_invoice_model->getAccActiveTickets();
        $data['list_card_value']         = $this->duplicate_invoice_model->getActiveCards();
        $data['list_invoice_status']	 = $this->duplicate_invoice_model->getInvoiceStatus();
        $data['list_acchead_value']	     = $this->duplicate_invoice_model->getAccountHead();
        $data['list_dracc']	             = $this->duplicate_invoice_model->getActiveCustomer();

        switch($this->uri->segment(4))
        {
            case "a":
                $this->showTitle   		  = ADDNEWDUPLICATEINVOICE;
                $this->btnVal	   		  = ADDCONTINUE;
                $this->btnVal2	   		  = SAVEEXIT;
                $data['list_loc_value']   = $this->duplicate_invoice_model->getLocation();
                $data['list_doc']         = $this->duplicate_invoice_model->getDoc();
                $this->ddlDivision 		  = $this->input->post('ddlDivision');
                $this->ddlLocation 		  = $this->input->post('ddlLocation');
                $this->txtDate      	  = date('d-m-Y');
                $this->ddlType      	  = $this->input->post('ddlType');
                $this->ddlTransaction  	  = $this->input->post('ddlTransaction');
                $this->txtDocNumber       = $this->input->post('txtDocNumber');
                //$this->txtDocNumber  	  = $this->getDocNumber();
                $this->txtLpoMain      	  = $this->input->post('txtLpoMain');

                $this->ddlIssued  		  = $this->input->post('ddlIssued');
                $this->ddlCurrency    	  = $this->input->post('ddlCurrency');
                $this->txtRate        	  = $this->input->post('txtRate');
                $this->ddlAgentAc  		  = $this->input->post('ddlAgentAc');

                $this->txtTotalNetAmount   = $this->input->post('txtTotalNetAmount');
                $this->txtTotalServPercent = $this->input->post('txtTotalServPercent');
                $this->txtTotalDiscAmount  = $this->input->post('txtTotalDiscAmount');
                $this->txtTotalCard		   = $this->input->post('txtTotalCard');
                $this->txtTotalCommPercent = $this->input->post('txtTotalCommPercent');
                $this->txtTotalCommAmt     = $this->input->post('txtTotalCommAmt');
                $this->txtTotalSalesAmt    = $this->input->post('txtTotalSalesAmt');
                $this->txtTotalCollected   = $this->input->post('txtTotalCollected');

                $this->ddlInvoice         = $this->input->post('ddlInvoice');
                $this->txtRemarks         = $this->input->post('txtRemarks');

                $this->chkSelect2  		  = 'checked="checked"';
                $this->chkSelect   		  = 'checked="checked"';
                $this->chkValYes   		  = 'checked="checked"';
                $this->chkValNo	   		  = '';
                $this->catId	   		  = '';
                $data['chkType']    	  = array();
                $data['chkAirline'] 	  = array();
                $data['chkTktNumber'] 	  = array();
                $data['chkFare'] 		  = array();
                $data['chkTax'] 		  = array();
                $data['chkNetAmt'] 		  = array();
                $data['chkServPercent']   = array();
                $data['chkServAmt'] 	  = array();
                $data['chkDiscAmt'] 	  = array();
                $data['chkCard']  		  = array();
                $data['chkComm']          = array();
                $data['chkCommAmt']       = array();
                $data['chkSalesAmt']      = array();
                $data['chkCollected']     = array();
                $data['chkAgentAmt']      = array();
                $data['chkDrAcc']         = array();
                $data['chkLPO']           = array();
                $data['chkDetailID']      = array();

                $data['chkAccType']    	  = array();
                $data['chkAccTktNumber']  = array();
                $data['chkDrAccMain'] 	  = array();
                $data['chkCard']    	  = array();
                $data['chkCardComm'] 	  = array();
                $data['chkCardCommAmt']	  = array();
                $data['chkAmt']	          = array();
                $data['chkCRV'] 		  = array();
                $data['chkRefTkt'] 		  = array();
                $data['chkAcHead']        = array();
                $data['chkAcId']          = array();
                break;

            case "e":
                $this->showTitle      	  = EDITDUPLICATEINVOICE;
                $this->btnVal	      	  = UPDATEDUPLICATEINVOICE;
                $this->catId	      	  = $this->uri->segment(5);
                if(intval($this->input->post('nId')) != 0)
                {
                    $this->catId = intval($this->input->post('nId'));
                }//end if

                $showVal	          	   = $this->duplicate_invoice_model->getCorresDuplicateInvoice();
                $this->ddlDivision	  	   = $showVal->nDivision;
                $this->ddlLocation	  	   = $showVal->vLocation;
                $this->txtDate      	   = date('d-m-Y', strtotime($showVal->dDate));
                $this->ddlType      	   = $showVal->vTransMode;
                $this->txtDocNumber  	   = $showVal->vInvoiceNumber;
                $data['list_doc']          = $this->duplicate_invoice_model->filterType($this->ddlType);

                $this->txtLpoMain      	   = $showVal->vLPO;
                $this->txtCRV        	   = $showVal->vCRV;
                $this->ddlIssued  		   = $showVal->vIssuedBy;
                $this->ddlCurrency    	   = $showVal->nCurrency;
                $this->txtRate        	   = $showVal->vRate;
                $this->ddlAgentAc  		   = $showVal->nAgentAc;

                $this->txtTotalFace        = $showVal->vTotalFace;
                $this->txtTotalTax         = $showVal->vTotalTax;
                $this->txtTotalNetAmount   = $showVal->vTotalNetAmount;
                $this->txtTotalServPercent = $showVal->vTotalServPercent;
                $this->txtTotalServAmount  = $showVal->vTotalServAmount;
                $this->txtTotalDiscAmount  = $showVal->vTotalDiscAmount;
                $this->txtTotalCard		   = $showVal->vTotalCard;
                $this->txtTotalCommPercent = $showVal->vTotalCommPercent;
                $this->txtTotalCommAmt     = $showVal->vTotalCommAmt;
                $this->txtTotalSalesAmt    = $showVal->vTotalSalesAmt;
                $this->txtTotalCollected   = $showVal->vTotalCollected;
                $this->txtTotalAgentAmt    = $showVal->vTotalAgentAmt;

                $this->txtPassName         = $showVal->vPassName;
                $this->txtSector1          = $showVal->vSector1;
                $this->txtSector2          = $showVal->vSector2;
                $this->txtSector3          = $showVal->vSector3;
                $this->txtSector4          = $showVal->vSector4;
                $this->txtSector5          = $showVal->vSector5;
                $this->txtSegment          = $showVal->vSegment;
                $this->txtDestination      = $showVal->vDestination;
                $this->txtGds              = $showVal->vGds;
                $this->ddlInvoice          = $showVal->nInvoiceStatus;
                $this->txtRemarks          = $showVal->vRemarks;
                $this->txtInvoiceTo        = $showVal->vInvoiceTo;
                $data['list_loc_value']    = $this->duplicate_invoice_model->getCorrActiveLocation($this->ddlDivision);
                $this->Id                  = $showVal->nDId;

                $this->chkSelect2  		  = 'checked="checked"';
                $this->chkSelect   		  = 'checked="checked"';

                if($showVal->vActive == '1')
                {
                    $this->chkValYes  = 'checked="checked"';
                }//end if
                else
                {
                    $this->chkValNo   = 'checked="checked"';
                }//end else

                //get selected details code
                $accValArray              = $this->duplicate_invoice_model->getCorresDuplicateInvoiceDetails();
                $data['chkType']    	  = $accValArray ['sdType'];
                $data['chkAirline'] 	  = $accValArray ['sdAirline'];
                $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
                $data['chkEmployee'] 	  = $accValArray ['sdEmployee'];
                $data['chkFare'] 		  = $accValArray ['sdFace'];
                $data['chkTax'] 		  = $accValArray ['sdTax'];
                $data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
                $data['chkServPercent']   = $accValArray ['sdServPercent'];
                $data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
                $data['chkDiscAmt'] 	  = $accValArray ['sdDiscAmt'];
                $data['chkCard']          = $accValArray ['sdCard'];
                $data['chkComm']          = $accValArray ['sdCommPer'];
                $data['chkCommAmt']    	  = $accValArray ['sdCommAmt'];
                $data['chkSalesAmt']  	  = $accValArray ['sdSalesAmt'];
                $data['chkCollected']  	  = $accValArray ['sdCollected'];
                $data['chkAgentAmt']   	  = $accValArray ['sdAgentAmt'];
                $data['chkDrAcc']    	  = $accValArray ['sdDrAcc'];
                $data['chkLPO']        	  = $accValArray ['sdLPO'];
                $data['chkDetailID']      = $accValArray ['sdID'];

                //get selected Account Details

                $saleValArray             = $this->duplicate_invoice_model->getCorresDuplicateInvoiceAccounts();
                $data['chkDrAccMain'] 	  = $saleValArray['AcAccMain'];
                $data['chkAcHead']        = $saleValArray['AcAcHead'];
                $data['chkCard']    	  = $saleValArray['AcCard'];
                $data['chkCardComm'] 	  = $saleValArray['AcCardComm'];
                $data['chkCardCommAmt']	  = $saleValArray['AcCardCommAmt'];
                $data['chkAmt']	          = $saleValArray['AcAmt'];
                $data['chkCRV'] 		  = $saleValArray['AcCRV'];
                $data['chkRefTkt'] 		  = $saleValArray['AcRefTkt'];
                $data['chkAcTable']       = $saleValArray['AcTable'];
                $data['chkAcId']          = $saleValArray['AcID'];
                break;
        }//end switch

// User access aa
        if ($this->form_validation->run() == FALSE  ||  $action_access->vAccess == 'Q' || ($this->common_model->get_account_period_date(date('Y-m-d', strtotime($this->input->post('txtDate')))) != 1))
        {
            $this->load->view('general/template_top',$data);
            $this->load->view('duplicate_invoice/edit_duplicate_invoice');
            $this->load->view('general/template_bottom');
        }//end if
        else
        {
            //checking segment
            switch($this->uri->segment(4))
            {
                case "a":
                    //insert into db
                    $this->duplicate_invoice_model->insertDuplicateInvoice();
                    break;

                case "e":
                    //Update into db
                    $this->duplicate_invoice_model->updateDuplicateInvoice();
                    break;
            }//end switch
            redirect(site_url("duplicate_invoice/showlist/".$this->uri->segment(3).'/'.$msgVal));
        }//end else
    }//end function

    function getClosedDetails()
    {
        $this->common_model->check_admin_authorization();
        $data['title'] 	   		  = $this->common_model->get_site_title();
        $this->load->helper(array('form', 'url'));
        $data['list_div_value']   		 = $this->duplicate_invoice_model->getActiveDivision();
        $data['list_type_value']	 	 = $this->duplicate_invoice_model->getTypeValue();
        $data['list_agent_value']        = $this->duplicate_invoice_model->getAgentAccounts();
        $data['list_type']	 	         = $this->duplicate_invoice_model->getTicketType();
        $data['list_tkt_value']          = $this->duplicate_invoice_model->getActiveTickets();
        $data['list_acc_type']	 	     = $this->duplicate_invoice_model->getAccTicketType();
        $data['list_acc_tkt_value']      = $this->duplicate_invoice_model->getAccActiveTickets();
        $data['list_card_value']         = $this->duplicate_invoice_model->getActiveCards();
        $data['list_invoice_status']	 = $this->duplicate_invoice_model->getInvoiceStatus();
        $data['list_dracc']	             = $this->duplicate_invoice_model->getActiveCustomer();

        $this->showTitle   		  = ADDNEWDUPLICATEINVOICE;
        $this->btnVal	   		  = ADDCONTINUE;
        $this->btnVal2	   		  = SAVEEXIT;
        $this->catId	      	  = $this->input->post('salesId');

        $this->ddlDivision 		   = $this->input->post('ddlDivision');
        $this->ddlLocation 		   = $this->input->post('ddlLocation');
        $this->txtDate      	   = $this->input->post('txtDate');
        $this->ddlType      	   = $this->input->post('ddlType');
        $this->ddlTransaction  	   = $this->input->post('ddlTransaction');
        $this->txtDocNumber        = $this->input->post('salesId');

        $showVal	          	   = $this->sales_closing_model->getCorresSalesClosing2();
        $this->txtLpoMain      	   = $showVal->vLPO;
        $this->ddlAgentAc  		   = $showVal->nAgentAc;

        $this->txtTotalFace        = $showVal->vTotalFace;
        $this->txtTotalTax         = $showVal->vTotalTax;
        $this->txtTotalNetAmount   = $showVal->vTotalNetAmount;
        $this->txtTotalServPercent = $showVal->vTotalServPercent;
        $this->txtTotalServAmount  = $showVal->vTotalServAmount;
        $this->txtTotalDiscAmount  = $showVal->vTotalDiscAmount;
        $this->txtTotalCard		   = $showVal->vTotalCard;
        $this->txtTotalCommPercent = $showVal->vTotalCommPercent;
        $this->txtTotalCommAmt     = $showVal->vTotalCommAmt;
        $this->txtTotalSalesAmt    = $showVal->vTotalSalesAmt;
        $this->txtTotalCollected   = $showVal->vTotalCollected;
        $this->txtTotalAgentAmt    = $showVal->vTotalAgentAmt;

        $this->txtPassName         = $showVal->vPassName;
        $this->txtSector1          = $showVal->vSector1;
        $this->txtSector2          = $showVal->vSector2;
        $this->txtSector3          = $showVal->vSector3;
        $this->txtSector4          = $showVal->vSector4;
        $this->txtSector5          = $showVal->vSector5;
        $this->txtSegment          = $showVal->vSegment;
        $this->txtDestination      = $showVal->vDestination;
        $this->txtGds              = $showVal->vGds;
        $this->ddlInvoice          = $showVal->nInvoiceStatus;
        $this->txtRemarks          = $showVal->vRemarks;
        $this->txtInvoiceTo        = $showVal->vInvoiceTo;
        $data['list_loc_value']    = $this->duplicate_invoice_model->getCorrActiveLocation($this->ddlDivision);
        $data['list_doc']          = $this->duplicate_invoice_model->filterType($this->ddlType);
        $this->Id                  = $showVal->nDId;

        $this->chkSelect2  		  = 'checked="checked"';
        $this->chkSelect   		  = 'checked="checked"';

        if($showVal->vActive == '1')
        {
            $this->chkValYes  = 'checked="checked"';
        }//end if
        else
        {
            $this->chkValNo   = 'checked="checked"';
        }//end else

        //get selected details code
        $accValArray              = $this->sales_closing_model->getCorresSalesClosingDetails2();
        $data['chkType']    	  = $accValArray ['sdType'];
        $data['chkAirline'] 	  = $accValArray ['sdAirline'];
        $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
        $data['chkEmployee'] 	  = $accValArray ['sdEmployee'];
        $data['chkFare'] 		  = $accValArray ['sdFace'];
        $data['chkTax'] 		  = $accValArray ['sdTax'];
        $data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
        $data['chkServPercent']   = $accValArray ['sdServPercent'];
        $data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
        $data['chkDiscAmt'] 	  = $accValArray ['sdDiscAmt'];
        $data['chkCard']          = $accValArray ['sdCard'];
        $data['chkComm']          = $accValArray ['sdCommPer'];
        $data['chkCommAmt']    	  = $accValArray ['sdCommAmt'];
        $data['chkSalesAmt']  	  = $accValArray ['sdSalesAmt'];
        $data['chkCollected']  	  = $accValArray ['sdCollected'];
        $data['chkAgentAmt']   	  = $accValArray ['sdAgentAmt'];
        $data['chkDrAcc']    	  = $accValArray ['sdDrAcc'];
        $data['chkLPO']        	  = $accValArray ['sdLPO'];
        $data['chkDetailID']      = $accValArray ['sdID'];

        //get selected Account Details

        $saleValArray             = $this->sales_closing_model->getCorresSalesClosingAccounts2();
        $data['chkDrAccMain'] 	  = $saleValArray['AcAccMain'];
        $data['chkAcHead']        = $saleValArray['AcAcHead'];
        $data['chkCard']    	  = $saleValArray['AcCard'];
        $data['chkCardComm'] 	  = $saleValArray['AcCardComm'];
        $data['chkCardCommAmt']	  = $saleValArray['AcCardCommAmt'];
        $data['chkAmt']	          = $saleValArray['AcAmt'];
        $data['chkCRV'] 		  = $saleValArray['AcCRV'];
        $data['chkRefTkt'] 		  = $saleValArray['AcRefTkt'];
        $data['chkAcTable']       = $saleValArray['AcTable'];
        $data['chkAcId']          = $saleValArray['AcID'];

        $this->load->view('duplicate_invoice/duplicate_invoice_details',$data);
    }//end function

    function delsalesclosing()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = DUPLICATEINVOICEMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->duplicate_invoice_model->deleteDuplicateInvoice();
    }//end function

    function delrecords()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = DUPLICATEINVOICEMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->duplicate_invoice_model->deleteAllDuplicateInvoice();
    }//end function

    function getDocNumber()
    {
        $check_sales = $this->duplicate_invoice_model->checkTicket();
        if(count($check_sales) > 0)
        {
            $x = end($check_sales);
            $x++;
            $x;
        }//end if
        return $x;
    }//end function

    function generateDocNo()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlTransaction = $this->input->post('ddlTransaction');
        $check_sales 		  = $this->duplicate_invoice_model->checkTicket();
        if(count($check_sales) > 0)
        {
            $x = end($check_sales);
            $x++;
            echo $x;
        }//end if
        else
        {
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$this->ddlTransaction."'");
        }//end else
        echo $this->txtDocNumber;
    }//end function

    function getTktNumber()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlTktType       = $this->input->post('ddlTktType');
        $data['list_tkt_value'] = $this->duplicate_invoice_model->getTicket($this->ddlTktType);
        $this->load->view('duplicate_invoice/show_tkt',$data);
    }//end function

    function getAccTktNumber()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlAccTktType        = $this->input->post('ddlAccTktType');
        $data['list_acc_tkt_value'] = $this->duplicate_invoice_model->getTicket($this->ddlAccTktType);
        $this->load->view('duplicate_invoice/show_acc_tkt',$data);
    }//end function

    function getTktValues()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlTkt     = $this->input->post('ddlTkt');
        $this->ddlTktType = $this->input->post('ddlTktType');

        $tktDetails 	  = $this->sales_closing_model->getTicketDetails($this->ddlTkt,$this->ddlTktType);

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

        $data['list_loc_value'] = $this->duplicate_invoice_model->filterLocation($this->ddlDivision);
        $this->load->view('duplicate_invoice/show_location',$data);
    }//end function

    function filterType()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlType      = $this->input->post('ddlType');
        $data['list_doc']   = $this->duplicate_invoice_model->filterType($this->ddlType);
        $this->load->view('duplicate_invoice/show_document',$data);
    }//end function

    //checking suggestion list
    function suggestionlist()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $searchKey = $this->input->post('q');
        $items     = $this->duplicate_invoice_model->searchActiveFinance($searchKey);
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
        if( $this->ddlFinance != '0')
            $this->txtSubCode1 = $this->duplicate_invoice_model->getSubledgerNo($this->ddlFinance);

        echo $this->txtSubCode1;
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

        $this->load->view('duplicate_invoice/show_transaction_details');
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

        $this->load->view('duplicate_invoice/show_currency_details');
    }//end function

    function getCurrencyRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCurrency   = $this->input->post('ddlCurrency');
        $this->txtExchange 	 = $this->duplicate_invoice_model->getRate($this->ddlCurrency);

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
        $convRate               = $this->duplicate_invoice_model->onlineCurrencyConverstion();

        //print_r($convRate);
        echo json_encode($convRate);
    }//end function

    //get card commision percentage
    function getCarddetails()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlCard     = $this->input->post('ddlCard');
        $this->txtCardComm = $this->duplicate_invoice_model->getCommAmt($this->ddlCard);

        echo $this->txtCardComm;
    }//end function

    function getDocumentNumber()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->ddlType      = $this->input->post('ddlType');
        $this->txtDocNumber = $this->duplicate_invoice_model->getDocNum($this->ddlType);

        echo $this->txtDocNumber;
    }//end function

    //function for update database
    function deleteRecord()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->subDetID = $this->input->post('subCode');

        $this->duplicate_invoice_model->deleteSubAndDetails($this->subDetID);
    }//end function

    function deleteAccountRecord()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->subDetID = $this->input->post('subCode');

        $this->duplicate_invoice_model->deleteSubAccountDetails($this->subDetID);
    }//end function

    function closedFilterList()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = DUPLICATEINVOICEMANAGEMENT;

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
            $extraCheck = " AND (scd.nAirline LIKE '".$this->txtAirline." %' OR scd.nAirline LIKE '% ".$this->txtAirline."'
									OR scd.nAirline LIKE '%".$this->txtAirline."%') ";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND (tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vXoNumber LIKE '".$this->txtTktName." %' OR xo.vXoNumber LIKE '% ".$this->txtTktName."'
									OR xo.vXoNumber LIKE '%".$this->txtTktName."%'
								 OR hv.vVoucherId LIKE '".$this->txtTktName." %' OR hv.vVoucherId LIKE '% ".$this->txtTktName."'
									OR hv.vVoucherId LIKE '%".$this->txtTktName."%') ";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND (tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								   OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' ) ";
        }//end if

        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND (scd.vEmployee LIKE '".$this->txtIssuedBy." %' OR scd.vEmployee LIKE '% ".$this->txtIssuedBy."'
									OR scd.vEmployee LIKE '%".$this->txtIssuedBy."%') ";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck.= " AND sc.dDate >= '".$txtFrom."' AND sc.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								 OR c.vName LIKE '".$this->txtAccname." %' OR c.vName LIKE '% ".$this->txtAccname."'
									OR c.vName LIKE '%".$this->txtAccname."%') ";
        }//end if

        $countFilterList	=  $this->duplicate_invoice_model->getCountclosedFilterList($extraCheck);
        $target 			= '.';
        $this->common_model->_paging($countFilterList, $target,'duplicate_invoice/closedFilterList2/'.$this->uri->segment(3));

        $data['list_value'] = $this->duplicate_invoice_model->getClosedFilterList($extraCheck);
        $this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'duplicate_invoice/showlist');

        $this->load->view('duplicate_invoice/duplicate_invoice_filter',$data);
    }//end function

    function closedFilterList2()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = DUPLICATEINVOICEMANAGEMENT;

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
            $extraCheck = " AND (scd.nAirline LIKE '".$this->txtAirline." %' OR scd.nAirline LIKE '% ".$this->txtAirline."'
									OR scd.nAirline LIKE '%".$this->txtAirline."%') ";
        }//end if

        if(!empty($this->txtTktName))
        {
            $extraCheck 	.= " AND (tb.vTicktManual LIKE '".$this->txtTktName." %' OR tb.vTicktManual LIKE '% ".$this->txtTktName."'
									OR tb.vTicktManual LIKE '%".$this->txtTktName."%'
								 OR xo.vXoNumber LIKE '".$this->txtTktName." %' OR xo.vXoNumber LIKE '% ".$this->txtTktName."'
									OR xo.vXoNumber LIKE '%".$this->txtTktName."%'
								 OR hv.vVoucherId LIKE '".$this->txtTktName." %' OR hv.vVoucherId LIKE '% ".$this->txtTktName."'
									OR hv.vVoucherId LIKE '%".$this->txtTktName."%') ";
        }//end if

        if(!empty($this->txtInvoice))
        {
            $extraCheck 	.= " AND (sc.vInvoiceNumber  LIKE '".$this->txtInvoice." %' OR sc.vInvoiceNumber  LIKE '% ".$this->txtInvoice."'
									OR sc.vInvoiceNumber  LIKE '%".$this->txtInvoice."%') ";
        }//end if

        if(!empty($this->txtPassName))
        {
            $extraCheck 	.= " AND (tb.vPassenger LIKE '".$this->txtPassName." %' OR tb.vPassenger LIKE '% ".$this->txtPassName."'
									OR tb.vPassenger LIKE '%".$this->txtPassName."%' OR hv.vPassenger LIKE '".$this->txtPassName." %'
								 OR hv.vPassenger LIKE '% ".$this->txtPassName."' OR hv.vPassenger LIKE '%".$this->txtPassName."%'
								   OR xo.vPassenger LIKE '".$this->txtPassName." %'
								 OR xo.vPassenger LIKE '% ".$this->txtPassName."' OR xo.vPassenger LIKE '%".$this->txtPassName."%' ) ";
        }//end if

        if(!empty($this->txtIssuedBy))
        {
            $extraCheck 	.= " AND (scd.vEmployee LIKE '".$this->txtIssuedBy." %' OR scd.vEmployee LIKE '% ".$this->txtIssuedBy."'
									OR scd.vEmployee LIKE '%".$this->txtIssuedBy."%') ";
        }//end if

        if(($this->common_model->searchKeyword('txtFromDate') != '' && $this->common_model->searchKeyword('txtToDate')))
        {
            //convet date to mysql format
            $txtFrom 	= $this->common_model->searchKeyword('txtFromDate');
            $txtTo   	= $this->common_model->searchKeyword('txtToDate');

            $extraCheck.= " AND sc.dDate >= '".$txtFrom."' AND sc.dDate <= '".$txtTo."'";
        }//end if

        if(!empty($this->txtAccname))
        {
            $extraCheck 	.= " AND (aha.vTitle LIKE '".$this->txtAccname." %' OR aha.vTitle LIKE '% ".$this->txtAccname."'
									OR aha.vTitle LIKE '%".$this->txtAccname."%' OR aha.vACode LIKE '".$this->txtAccname." %'
								 OR aha.vACode LIKE '% ".$this->txtAccname."' OR aha.vACode LIKE '%".$this->txtAccname."%'
								 OR c.vName LIKE '".$this->txtAccname." %' OR c.vName LIKE '% ".$this->txtAccname."'
									OR c.vName LIKE '%".$this->txtAccname."%') ";
        }//end if

        $countFilterList	= $this->duplicate_invoice_model->getCountclosedFilterList($extraCheck);
        $target 			= '.';
        $this->common_model->_paging($countFilterList, $target,'duplicate_invoice/closedFilterList2/'.$this->uri->segment(3));

        $data['list_value'] = $this->duplicate_invoice_model->getClosedFilterList($extraCheck);
        $this->count_rows   = count($data['list_value']);

//User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'duplicate_invoice/showlist');

        $this->load->view('general/template_top',$data);
        $this->load->view('duplicate_invoice/duplicate_invoice');
        $this->load->view('general/template_bottom');
    }//end function

    function getAccountHead()
    {
        $this->load->helper(array('form', 'url'));

        $accName                    = $this->input->post('ddlDrAccMain');
        $accType                    = $this->input->post('tableType');
        $data['list_acchead_value'] = $this->duplicate_invoice_model->getActiveAccountHead($accName,$accType);
        $this->load->view('duplicate_invoice/show_acchead',$data);
    }//end function

    function printPreview()
    {
        $this->load->helper(array('form', 'url'));

        //$this->catId   = $this->input->post('id');
        $this->catId   = $this->uri->segment(3);

        $showVal	          	   = $this->duplicate_invoice_model->getCorresDuplicateInvoice();
        $this->ddlDivision	  	   = $showVal->nDivision;
        $this->ddlLocation	  	   = $showVal->vLocation;
        $this->txtDate      	   = date('d-m-Y', strtotime($showVal->dDate));
        $this->ddlType      	   = $showVal->vTransMode;
        $this->txtDocNumber  	   = $showVal->vInvoiceNumber;
        $data['list_doc']          = $this->duplicate_invoice_model->filterType($this->ddlType);

        $this->txtLpoMain      	   = $showVal->vLPO;
        $this->txtCRV        	   = $showVal->vCRV;
        $this->ddlIssued  		   = $showVal->vIssuedBy;
        $this->ddlCurrency    	   = $showVal->nCurrency;
        $this->txtRate        	   = $showVal->vRate;
        $this->ddlAgentAc  		   = $showVal->nAgentAc;

        $this->txtTotalFace        = $showVal->vTotalFace;
        $this->txtTotalTax         = $showVal->vTotalTax;
        $this->txtTotalNetAmount   = $showVal->vTotalNetAmount;
        $this->txtTotalServPercent = $showVal->vTotalServPercent;
        $this->txtTotalServAmount  = $showVal->vTotalServAmount;
        $this->txtTotalDiscAmount  = $showVal->vTotalDiscAmount;
        $this->txtTotalCard		   = $showVal->vTotalCard;
        $this->txtTotalCommPercent = $showVal->vTotalCommPercent;
        $this->txtTotalCommAmt     = $showVal->vTotalCommAmt;
        $this->txtTotalSalesAmt    = $showVal->vTotalSalesAmt;
        $this->txtTotalCollected   = $showVal->vTotalCollected;
        $this->txtTotalAgentAmt    = $showVal->vTotalAgentAmt;

        $this->txtPassName         = $showVal->vPassName;
        $this->txtSector1          = $showVal->vSector1;
        $this->txtSector2          = $showVal->vSector2;
        $this->txtSector3          = $showVal->vSector3;
        $this->txtSector4          = $showVal->vSector4;
        $this->txtSector5          = $showVal->vSector5;
        $this->txtSegment          = $showVal->vSegment;
        $this->txtDestination      = $showVal->vDestination;
        $this->txtGds              = $showVal->vGds;
        $this->ddlInvoice          = $showVal->nInvoiceStatus;
        $this->txtRemarks          = $showVal->vRemarks;
        $this->txtInvoiceTo        = $showVal->vInvoiceTo;
        $data['list_loc_value']    = $this->duplicate_invoice_model->getCorrActiveLocation($this->ddlDivision);
        $data['list_doc']          = $this->duplicate_invoice_model->filterType($this->ddlType);
        $this->Id                  = $showVal->nDId;

        $this->chkSelect2  		  = 'checked="checked"';
        $this->chkSelect   		  = 'checked="checked"';

        if($showVal->vActive == '1')
        {
            $this->chkValYes  = 'checked="checked"';
        }//end if
        else
        {
            $this->chkValNo   = 'checked="checked"';
        }//end else

        //get selected details code
        $accValArray              = $this->duplicate_invoice_model->getCorresDuplicateInvoiceDetails2();
        $data['chkType']    	  = $accValArray ['sdType'];
        $data['chkAirline'] 	  = $accValArray ['sdAirline'];
        $data['chkTktNumber'] 	  = $accValArray ['sdTktNumber'];
        $data['chkEmployee'] 	  = $accValArray ['sdEmployee'];
        $data['chkFare'] 		  = $accValArray ['sdFace'];
        $data['chkTax'] 		  = $accValArray ['sdTax'];
        $data['chkNetAmt'] 		  = $accValArray ['sdNetAmt'];
        $data['chkServPercent']   = $accValArray ['sdServPercent'];
        $data['chkServAmt'] 	  = $accValArray ['sdServAmt'];
        $data['chkDiscAmt'] 	  = $accValArray ['sdDiscAmt'];
        $data['chkCard']          = $accValArray ['sdCard'];
        $data['chkComm']          = $accValArray ['sdCommPer'];
        $data['chkCommAmt']    	  = $accValArray ['sdCommAmt'];
        $data['chkSalesAmt']  	  = $accValArray ['sdSalesAmt'];
        $data['chkCollected']  	  = $accValArray ['sdCollected'];
        $data['chkAgentAmt']   	  = $accValArray ['sdAgentAmt'];
        $data['chkDrAcc']    	  = $accValArray ['sdDrAcc'];
        $data['chkLPO']        	  = $accValArray ['sdLPO'];
        $data['chkDetailID']      = $accValArray ['sdID'];

        //get selected Account Details

        $saleValArray             = $this->duplicate_invoice_model->getCorresDuplicateInvoiceAccounts2();
        $data['chkDrAccMain'] 	  = $saleValArray['AcAccMain'];
        $data['chkAcHead']        = $saleValArray['AcAcHead'];
        $data['chkCard']    	  = $saleValArray['AcCard'];
        $data['chkCardComm'] 	  = $saleValArray['AcCardComm'];
        $data['chkCardCommAmt']	  = $saleValArray['AcCardCommAmt'];
        $data['chkAmt']	          = $saleValArray['AcAmt'];
        $data['chkCRV'] 		  = $saleValArray['AcCRV'];
        $data['chkRefTkt'] 		  = $saleValArray['AcRefTkt'];
        $data['chkAcTable']       = $saleValArray['AcTable'];
        $data['chkAcId']          = $saleValArray['AcID'];

        $this->load->view('duplicate_invoice/print_sales_closing',$data);
    }//end function
  function getAllAccount()
  {
    $this->load->helper('url');
    $this->load->helper(array('form', 'url'));
    $this->common_model->check_admin_authorization();
    $data['list_dracc'] = $this->common_model->getAllAccounts();
    $this->load->view('duplicate_invoice/show_allAccounts',$data);

  }
    function getAgentAccount()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_agent_value'] = $this->common_model->getAgentAccounts();
      $this->load->view('duplicate_invoice/show_agentAccounts',$data);

    }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
