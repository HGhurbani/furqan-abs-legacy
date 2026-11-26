<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Refund_register extends CI_Controller {

    function __construct()
    {
        parent::__construct();
        $this->load->model('refund_register_model');
    }

    function showlist()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = REFUNDMANAGEMENT;
        $this->common_model->check_admin_authorization();

        //site Name
        $target 			= '.';
        $this->common_model->_paging($this->refund_register_model->getAllRefundCount(), $target,'refund_register/showlist/'.$this->uri->segment(3));
        $data['list_value'] = $this->refund_register_model->getAllRefund();
        $this->count_rows   = count($data['list_value']);

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

        $this->load->view('general/template_top',$data);
        $this->load->view('refund_register/refund_register');
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
        $data['list_div_value']	            = $this->refund_register_model->getActiveDivision();
        $data['list_gds_value']             = $this->refund_register_model->getActiveGDS();
        $data['list_type_value']            = $this->refund_register_model->getActiveType();
        $data['list_emp_value']             = $this->refund_register_model->getActiveEmployee();
        $data['list_NewOld']                = $this->refund_register_model->getNewOld();
        $data['list_country']               = $this->refund_register_model->getCountry();
        $data['list_hotel']                 = $this->refund_register_model->getHotel();
        $data['list_curr_value']            = $this->refund_register_model->getActiveCurrency();
        $data['list_mode_value']  	        = $this->refund_register_model->getModeType();
        $data['list_agentacc_value']        = $this->refund_register_model->getAgentAccounts();
        $data['list_account_value']         = $this->refund_register_model->getAccounts();
        $data['list_accountapid_value']     = $this->refund_register_model->getAPIDAccounts();
        $data['list_type']	 	            = $this->refund_register_model->getTicketType();
        $data['list_tkt_value']             = $this->refund_register_model->getActiveTickets();
		$data['list_custaccount_value']     = $this->refund_register_model->getAPIDAccounts();

        switch($this->uri->segment(4))
        {
            case "a":
                $this->showTitle  	  	  = ADDNEWREFUND;
                $this->btnVal	  	  	  = ADDCONTINUE;
                $this->btnVal2	  	  	  = SAVEEXIT;

                $data['list_accounthead'] = $this->refund_register_model->getAccountHead();
                $data['list_loc_value']   = $this->refund_register_model->getLocation();
                $data['list_city']        = $this->refund_register_model->getCity1();
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
                $this->catId	      	  = $this->uri->segment(5);

                if(intval($this->input->post('nId')) != 0)
                {
                    $this->catId  	  	  = intval($this->input->post('nId'));
                }//end if

                if(trim($this->input->post('nCheck')) != '')
                {
                    $this->nCheck  	  	  = $this->input->post('nCheck');
                }//end if

                $showVal	          	  = $this->refund_register_model->getCorresRefund();
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

                $data['list_loc_value']   = $this->refund_register_model->getCorrActiveLocation($this->ddlDivision);

                //get selected Refund billing details
                $showBillingVal			  = $this->refund_register_model->getCorresBillingDetails();
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

                $data['list_accounthead'] = $this->refund_register_model->getActiveAccountHead($this->ddlAccountClose);
                $data['list_city']        = $this->refund_register_model->getCity1();

                $RefundValArray           = $this->refund_register_model->getCorresRefundDetails();

                $data['chkType']  	      = $RefundValArray['Type'];
                $data['ddlYesNo']  	      = $RefundValArray['YesNo'];
                $data['chkTktNumber']     = $RefundValArray['TktNum'];
                $data['txtORGTkt'] 	      = $RefundValArray['ORGTkt'];
                $data['txtAirline'] 	  = $RefundValArray['Airline'];
                $data['txtPass'] 	      = $RefundValArray['Passenger'];
                $data['txtRemarks'] 	  = $RefundValArray['Remarks'];
				$data['chkDetailID']      = $RefundValArray ['ID'];
                $data['chkAccID']    	  = $RefundValArray['ID'];

                $data['chkFare']  	      = $RefundValArray['Fare'];
                $data['chkUsedFare']  	  = $RefundValArray['UsedFare'];
                $data['chkSaleAmt']       = $RefundValArray['SaleAmt'];
                $data['chkServiceAmt2']   = $RefundValArray['ServiceAmt2'];
                $data['chkServiceAmt']    = $RefundValArray['ServiceAmt'];
                $data['chkServicePer2']   = $RefundValArray['ServicePer2'];
                $data['chkCountry'] 	  = $RefundValArray['Country'];
                $data['chkTax'] 	      = $RefundValArray['Tax'];
                $data['chkUsedTax'] 	  = $RefundValArray['UsedTax'];
                $data['chkOCharge'] 	  = $RefundValArray['OCharge'];
                $data['chkDiscAmt'] 	  = $RefundValArray['DiscAmt'];
                $data['chkCity'] 	      = $RefundValArray['City'];
                $data['chkComm1'] 	      = $RefundValArray['Comm1'];
                $data['chkComm2'] 	      = $RefundValArray['Comm2'];
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

        if ($this->form_validation->run() == FALSE)
        {
            $this->load->view('general/template_top',$data);
            $this->load->view('refund_register/edit_refund_register');
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
                    $this->refund_register_model->insertRefund();
                 break;

                case "e":
                    //Update into db
                    $this->refund_register_model->updateRefund();
                break;
            }//end switch
            redirect(site_url("refund_register/showlist/".$this->uri->segment(3).'/'.$msgVal));
        }//end else
    }//end function

    //get currency rate when selected currency
    function generateCurrRate()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->ddlCurrency  = $this->input->post('ddlCurrency');
        $this->txtRate		= $this->common_model->get_field_name('Currency','vRate'," WHERE nId='".$this->ddlCurrency."'");
        echo $this->txtRate;
    }//end function

    function suggestionlist()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $searchKey = $this->input->post('q');
        $items     = $this->refund_register_model->searchActiveFinance($searchKey);
        echo json_encode($items);
    }//end function

    function filterDivision()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlDivision = $this->input->post('ddlDivision');
        $this->ddlLocation = $this->input->post('ddlLocation');

        $data['list_loc_value'] = $this->refund_register_model->filterLocation($this->ddlDivision);
        $this->load->view('refund_register/show_location',$data);
    }//end function

    function filterCity()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlCountry  = $this->input->post('ddlCountry');
        $this->ddlCity     = $this->input->post('ddlCity');

        $data['list_city'] = $this->refund_register_model->getCity($this->ddlCountry);
        $this->load->view('refund_register/show_city',$data);
    }//end function

    function delxobooking()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = REFUNDMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->refund_register_model->deleteRefund();
    }//end function

    function deleteRecord()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();

        $this->passID = $this->input->post('subCode');
        $this->refund_register_model->deletePassDetails($this->passID);
    }//end function

    function delrecords()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = REFUNDMANAGEMENT;
        $this->common_model->check_admin_authorization();
        $this->refund_register_model->deleteAllRefund();
    }//end function

    function checkEmail()
    {
        switch($this->uri->segment(3))
        {
            case "":
                $extraCond = '';
            break;

            default:
                $extraCond = " AND nCId != '".$this->uri->segment(3)."'";
            break;
        }//end switch
		
        $this->txtEmail = $this->input->post('txtEmail');
        $txtEmail       = $this->common_model->get_field_name('XoBooking','vEmail'," WHERE vEmail='".mysql_real_escape_string($this->txtEmail)."' ".$extraCond." ");
        if($txtEmail    == $this->txtEmail)
        {
            echo "false";
        }//end if
        else
        {
            echo "true";
        }//end else
    }//end function

    function checkCustcode()
    {
        switch($this->uri->segment(3))
        {
            case "":
                $extraCond = '';
            break;

            default:
                $extraCond = " AND nCId != '".$this->uri->segment(3)."'";
            break;
        }//end switch
		
        $this->txtCode 	 = $this->input->post('txtCode');
        $txtCode       	 = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." ");
        if($txtCode == $this->txtCode)
        {
            echo "false";
        }//end if
        else
        {
            echo "true";
        }//end else
    }//end function

    //function for showing xo Details
    function xodetails()
    {
        $this->load->library('session');
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $this->load->view('refund_register/show_xo_details');
    }//end function

    //function for showing pass details
    function passdetails()
    {
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $data['list_pass_value'] 	 = $this->refund_register_model->getActiveCustomer();
        $data['list_account_value']  = $this->refund_register_model->getAccounts();

        $this->catId            	 = $this->input->post('nChkIDm');
        $this->load->view('refund_register/show_pass_details',$data);
    }//end function

    //function for showing billing details
    function billingdetails()
    {
        $this->load->helper(array('form', 'url'));
        $this->common_model->check_admin_authorization();
        $data['list_mode_value']  	 = $this->refund_register_model->getModeType();
        $data['list_cust_value']  	 = $this->refund_register_model->getActiveCustomer();
        $data['list_agent_value'] 	 = $this->refund_register_model->getActiveAgent();
        $data['list_agentacc_value'] = $this->refund_register_model->getAgentAccounts();

        $this->load->view('refund_register/show_billing_details',$data);
    }//end function

    function getTktNumber()
	{
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        //site Name
        $this->ddlTktType       = $this->input->post('ddlTktType');
        $data['list_tkt_value'] = $this->refund_register_model->getTicket($this->ddlTktType);
        $this->load->view('refund_register/show_tkt',$data);
    }//end function

    function getTktValues()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $this->ddlTkt     = $this->input->post('ddlTkt');
        $this->ddlTktType = $this->input->post('ddlTktType');

        $tktDetails 	  = $this->refund_register_model->getTicketDetails($this->ddlTkt,$this->ddlTktType);
        echo json_encode($tktDetails);
    }//end function

    function getAccountHead()
    {
        $this->load->helper(array('form', 'url'));

        $accName                  = $this->input->post('ddlAccountClose');
        $data['list_accounthead'] = $this->refund_register_model->getActiveAccountHead($accName);
        $this->load->view('refund_register/show_acchead',$data);
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
        $this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='".$type."'");
        $fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister WHERE
                                                    vType = '".$this->ddlType."' ORDER BY nRId DESC LIMIT 0,1");
        if($fetchCode->num_rows() > 0):
            $totalNum           = $fetchCode->row();
            $this->txtDocNumber	= $totalNum->vDocNumber;
            $docNumber 			= @split('-',$this->txtDocNumber);
            $fill   			= $this->txtMinDigit;
            $number 			= ($docNumber[3]+1);
            $lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$lDigit;
        else:
            $this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='".$type."'");
            $docNumber 			= @split('-',$this->txtDocNumber);
            $countVal 			= $docNumber[0].'-'.$this->txtLocation.'-'.$docNumber[2].'-'.$docNumber[3];
        endif;

        echo $countVal;
    }//end function

    function printPreview()
    {
        $this->load->helper(array('form', 'url'));
        $this->catId   = $this->uri->segment(3);

        $showVal	          	  = $this->refund_register_model->getCorresRefund();
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

        $data['list_loc_value']   = $this->refund_register_model->getCorrActiveLocation($this->ddlDivision);

        //get selected Refund billing details
        $showBillingVal			  = $this->refund_register_model->getCorresBillingDetails();
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

        $data['list_accounthead'] = $this->refund_register_model->getActiveAccountHead($this->ddlAccountClose);
        $data['list_city']        = $this->refund_register_model->getCity1();

        $RefundValArray           = $this->refund_register_model->getCorresRefundDetails();

        $data['chkType']  	      = $RefundValArray['Type'];
        $data['ddlYesNo']  	      = $RefundValArray['YesNo'];
        $data['chkTktNumber']     = $RefundValArray['TktNum'];
        $data['txtORGTkt'] 	      = $RefundValArray['ORGTkt'];
        $data['txtAirline'] 	  = $RefundValArray['Airline'];
        $data['txtPass'] 	      = $RefundValArray['Passenger'];
        $data['txtRemarks'] 	  = $RefundValArray['Remarks'];
        $data['chkDetailID']      = $RefundValArray ['ID'];
        $data['chkAccID']    	  = $RefundValArray['ID'];

        $data['chkFare']  	      = $RefundValArray['Fare'];
        $data['chkUsedFare']  	  = $RefundValArray['UsedFare'];
        $data['chkSaleAmt']       = $RefundValArray['SaleAmt'];
        $data['chkServiceAmt2']   = $RefundValArray['ServiceAmt2'];
        $data['chkServiceAmt']    = $RefundValArray['ServiceAmt'];
        $data['chkServicePer2']   = $RefundValArray['ServicePer2'];
        $data['chkCountry'] 	  = $RefundValArray['Country'];
        $data['chkTax'] 	      = $RefundValArray['Tax'];
        $data['chkUsedTax'] 	  = $RefundValArray['UsedTax'];
        $data['chkOCharge'] 	  = $RefundValArray['OCharge'];
        $data['chkDiscAmt'] 	  = $RefundValArray['DiscAmt'];
        $data['chkCity'] 	      = $RefundValArray['City'];
        $data['chkComm1'] 	      = $RefundValArray['Comm1'];
        $data['chkComm2'] 	      = $RefundValArray['Comm2'];
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

        $this->load->view('refund_register/print_refund_register',$data);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>