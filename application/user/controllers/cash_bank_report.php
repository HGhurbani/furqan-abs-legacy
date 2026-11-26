<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Cash_bank_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('cash_bank_report_model');
		$this->load->model('alloc_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = CASHBANKREGISTERY;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value']      = $this->cash_bank_report_model->getActiveDivision();
        $data['list_loc_value']      = $this->cash_bank_report_model->getActiveLocations();
        $data['list_acc_group']      = $this->cash_bank_report_model->getAccountGroup();
        $data['list_acc_value']      = $this->cash_bank_report_model->getActiveAcName();
        $data['list_sub_acc']        = $this->cash_bank_report_model->getActiveSubAccounts();
        $data['list_customer_value'] = $this->cash_bank_report_model->getActiveCustomerValue();
        $data['list_emp_value']      = $this->cash_bank_report_model->getActiveEmployee();
        $data['list_agent']          = $this->cash_bank_report_model->getAgentAccounts();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlLocation2  = $this->session->userdata('ddlLocation2');
            $this->txtToDate     = $this->session->userdata('txtToDate');
            $this->txtFromDate   = $this->session->userdata('txtFromDate');
            $this->txtFromYear   = $this->session->userdata('txtFromYear');
            $this->txtToYear     = $this->session->userdata('txtToYear');
            $this->txtTillDate   = $this->session->userdata('txtTillDate');
            $this->ddlAccGroup   = $this->session->userdata('ddlAccGroup');
            $this->ddlCustomer   = $this->session->userdata('ddlCustomer');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');
            $this->tableType     = $this->session->userdata('tableType');
            $this->ddlEmployee   = $this->session->userdata('ddlEmployee');
            $this->ddlAgent      = $this->session->userdata('ddlAgent');
            $this->ddlReport     = $this->session->userdata('ddlReport');
            $this->ddlTransaction= $this->session->userdata('ddlTransaction');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlTransaction');
            $this->session->unset_userdata('pageName');
        }

		$this->load->view('general/template_top');
		$this->load->view('cash_bank_report/cash_bank_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->cash_bank_report_model->filterLocation($this->ddlDivision);
		$this->load->view('cash_bank_report/show_location',$data);
	}//end function

    function getRegister()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlTransaction');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlCustomer       = $this->common_model->searchKeyword3('ddlCustomer');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlTransaction    = $this->common_model->searchKeyword3('ddlTransaction');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
            $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
            $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
            $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
            $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
            $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
            $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
            $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
            $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
        }//end if

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_ac .= " AND ac.dDate >= '" .$this->txtFromDate . "' AND ac.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_ad .= " AND ad.dDate >= '" .$this->txtFromDate . "' AND ad.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND ac.YEAR(dDate) >= '" .$this->txtFromYear . "' AND ac.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND ad.YEAR(dDate) >= '" .$this->txtFromYear . "' AND ad.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        /*if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND cr.nAcCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlAccount."'";
            $extraCheck_ac .= " AND ac.nAcCode='".$this->ddlAccount."'";
            $extraCheck_ad .= " AND ad.nAcCode='".$this->ddlAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND cr.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_ac .= " AND ac.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_ad .= " AND ad.vSubCode='".$this->ddlSubAccount."'";
        }//end if*/

        if (!empty($this->ddlAccount)) {
            if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (adm.vAcSubCode='".$this->ddlAccount."' OR adm.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND adm.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (adm.vAcSubCode='".$this->ddlAccount."' OR adm.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND adm.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->ddlReport)
        {
            case "Details":
                $page = 'register_details_report';
                break;

            case "Summary":
                $page = 'register_summary_report';
                break;
        }//end switch

        switch($this->ddlTransaction)
        {
            case "Cash Receipt":
                $data['list_value'] = $this->cash_bank_report_model->getCashReceiptRegister($extraCheck_cr);
                break;

            case "Cash Payment":
                $data['list_value'] = $this->cash_bank_report_model->getCashPaymentRegister($extraCheck_cp);
                break;

            case "Bank Deposit":
                $data['list_value'] = $this->cash_bank_report_model->getBankDepositRegister($extraCheck_bb);
                break;

            case "Bank Payment":
                $data['list_value'] = $this->cash_bank_report_model->getBankPaymentRegister($extraCheck_bp);
                break;

            case "Credit Note":
                $data['list_value'] = $this->cash_bank_report_model->getCreditVoucherRegister($extraCheck_cv);
                break;

            case "Debit Note":
                $data['list_value'] = $this->cash_bank_report_model->getDebitoucherRegister($extraCheck_dv);
                break;

            case "Journal Voucher":
                $data['list_value'] = $this->cash_bank_report_model->getJournalVoucherRegister($extraCheck_jv);
                break;

            case "ACM":
                $data['list_value'] = $this->cash_bank_report_model->getACMRegister($extraCheck_ac);
                break;

            case "ADM":
                $data['list_value'] = $this->cash_bank_report_model->getADMRegister($extraCheck_ad);
            break;

            default:
                $data['list_value'] = $this->cash_bank_report_model->getFullRegister($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad);
            break;
        }//end switch
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
       // $mydata = $this->load->view("cash_bank_report/export_files/".$page, $data,true);
       // $_SESSION[$page] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('cash_bank_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('cash_bank_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getTransaction()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = CUSTOMERREPORT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtFromYear');
            $this->session->unset_userdata('txtToYear');
            $this->session->unset_userdata('txtTillDate');
            $this->session->unset_userdata('ddlAccGroup');
            $this->session->unset_userdata('ddlCustomer');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlSubAccount');
            $this->session->unset_userdata('tableType');
            $this->session->unset_userdata('ddlEmployee');
            $this->session->unset_userdata('ddlAgent');
            $this->session->unset_userdata('ddlReport');
            $this->session->unset_userdata('ddlTransaction');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');
        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtFromYear       = $this->common_model->searchKeyword3('txtFromYear');
        $this->txtToYear         = $this->common_model->searchKeyword3('txtToYear');
        $this->txtTillDate       = $this->common_model->searchKeyword3('txtTillDate');
        $this->ddlAccGroup       = $this->common_model->searchKeyword3('ddlAccGroup');
        $this->ddlCustomer       = $this->common_model->searchKeyword3('ddlCustomer');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');
        $this->ddlEmployee       = $this->common_model->searchKeyword3('ddlEmployee');
        $this->ddlAgent          = $this->common_model->searchKeyword3('ddlAgent');
        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
        $this->ddlTransaction    = $this->common_model->searchKeyword3('ddlTransaction');
        $this->tableType         = $this->common_model->searchKeyword3('tableType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck_cr = " AND cr.nDivision='".$this->ddlDivision."'";
            $extraCheck_cp = " AND cp.nDivision='".$this->ddlDivision."'";
            $extraCheck_bb = " AND bb.nDivision='".$this->ddlDivision."'";
            $extraCheck_bp = " AND bp.nDivision='".$this->ddlDivision."'";
            $extraCheck_jv = " AND jv.nDivision='".$this->ddlDivision."'";
            $extraCheck_cv = " AND cv.nDivision='".$this->ddlDivision."'";
            $extraCheck_dv = " AND dv.nDivision='".$this->ddlDivision."'";
            $extraCheck_ac = " AND ac.nDivision='".$this->ddlDivision."'";
            $extraCheck_ad = " AND ad.nDivision='".$this->ddlDivision."'";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck_cr .= " AND cr.vLocation='".$this->ddlLocation."'";
            $extraCheck_cp .= " AND cp.vLocation='".$this->ddlLocation."'";
            $extraCheck_bb .= " AND bb.vLocation='".$this->ddlLocation."'";
            $extraCheck_bp .= " AND bp.vLocation='".$this->ddlLocation."'";
            $extraCheck_jv .= " AND jv.vLocation='".$this->ddlLocation."'";
            $extraCheck_cv .= " AND cv.vLocation='".$this->ddlLocation."'";
            $extraCheck_dv .= " AND dv.vLocation='".$this->ddlLocation."'";
            $extraCheck_ac .= " AND ac.vLocation='".$this->ddlLocation."'";
            $extraCheck_ad .= " AND ad.vLocation='".$this->ddlLocation."'";
        }//end if

        /*if (!empty($this->ddlLocation2)) {
            $extraCheck_cr .= " OR cr.vLocation='".$this->ddlLocation2."'";
            $extraCheck_cp .= " OR cp.vLocation='".$this->ddlLocation2."'";
            $extraCheck_bb .= " OR bb.vLocation='".$this->ddlLocation2."'";
            $extraCheck_bp .= " OR bp.vLocation='".$this->ddlLocation2."'";
            $extraCheck_jv .= " OR jv.vLocation='".$this->ddlLocation2."'";
            $extraCheck_cv .= " OR cv.vLocation='".$this->ddlLocation2."'";
            $extraCheck_dv .= " OR dv.vLocation='".$this->ddlLocation2."'";
        }//end if*/

        if ($this->txtToDate != '' && $this->txtFromDate !='') {
            $extraCheck_cr .= " AND cr.dDate >= '" .$this->txtFromDate . "' AND cr.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cp .= " AND cp.dDate >= '" .$this->txtFromDate . "' AND cp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bb .= " AND bb.dDate >= '" .$this->txtFromDate . "' AND bb.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_bp .= " AND bp.dDate >= '" .$this->txtFromDate . "' AND bp.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_jv .= " AND jv.dDate >= '" .$this->txtFromDate . "' AND jv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_cv .= " AND cv.dDate >= '" .$this->txtFromDate . "' AND cv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_dv .= " AND dv.dDate >= '" .$this->txtFromDate . "' AND dv.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_ac .= " AND ac.dDate >= '" .$this->txtFromDate . "' AND ac.dDate <= '" . $this->txtToDate . "'";
            $extraCheck_ad .= " AND ad.dDate >= '" .$this->txtFromDate . "' AND ad.dDate <= '" . $this->txtToDate . "'";
        }//end if

        if ($this->txtToYear != '' && $this->txtFromYear !='') {
            $extraCheck_cr .= " AND cr.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cr.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cp .= " AND cp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bb .= " AND bb.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bb.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_bp .= " AND bp.YEAR(dDate) >= '" .$this->txtFromYear . "' AND bp.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_jv .= " AND jv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND jv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_cv .= " AND cv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND cv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_dv .= " AND dv.YEAR(dDate) >= '" .$this->txtFromYear . "' AND dv.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ac .= " AND ac.YEAR(dDate) >= '" .$this->txtFromYear . "' AND ac.YEAR(dDate) <= '" . $this->txtToYear . "'";
            $extraCheck_ad .= " AND ad.YEAR(dDate) >= '" .$this->txtFromYear . "' AND ad.YEAR(dDate) <= '" . $this->txtToYear . "'";
        }//end if

        if (!empty($this->ddlCustomer)) {

        }//end if

        if (!empty($this->ddlAccGroup)) {

        }//end if

        /*if (!empty($this->ddlAccount)) {
            $extraCheck_cr .= " AND cr.nAcCode='".$this->ddlAccount."'";
            $extraCheck_cp .= " AND cp.nAcCode='".$this->ddlAccount."'";
            $extraCheck_bb .= " AND bb.nAcCode='".$this->ddlAccount."'";
            $extraCheck_bp .= " AND bp.nAcCode='".$this->ddlAccount."'";
            $extraCheck_ac .= " AND ac.nAcCode='".$this->ddlAccount."'";
            $extraCheck_ad .= " AND ad.nAcCode='".$this->ddlAccount."'";
        }//end if

        if (!empty($this->ddlSubAccount)) {
            $extraCheck_cr .= " AND cr.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_cp .= " AND cp.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_bb .= " AND bb.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_bp .= " AND bp.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_ac .= " AND ac.vSubCode='".$this->ddlSubAccount."'";
            $extraCheck_ad .= " AND ad.vSubCode='".$this->ddlSubAccount."'";
        }//end if*/

        if (!empty($this->ddlAccount)) {
            if ($this->ddlAccount == 32 || $this->ddlAccount == 34 || $this->ddlAccount == 35):
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (adm.vAcSubCode='".$this->ddlAccount."' OR adm.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND adm.vACode='".$this->ddlSubAccount."'";
                endif;
            else:
                $extraCheck_cr .= " AND (crd.vAcSubCode='".$this->ddlAccount."' OR crd.vACode='".$this->ddlAccount."'";
                $extraCheck_cp .= " AND (cpd.vAcSubCode='".$this->ddlAccount."' OR cpd.vACode='".$this->ddlAccount."'";
                $extraCheck_bb .= " AND (bbd.vAcSubCode='".$this->ddlAccount."' OR bbd.vACode='".$this->ddlAccount."'";
                $extraCheck_bp .= " AND (bpd.vAcSubCode='".$this->ddlAccount."' OR bpd.vACode='".$this->ddlAccount."'";
                $extraCheck_jv .= " AND (jvd.vAcSubCode='".$this->ddlAccount."' OR jvd.vACode='".$this->ddlAccount."')";
                $extraCheck_cv .= " AND (cvd.vAcSubCode='".$this->ddlAccount."' OR cvd.vACode='".$this->ddlAccount."')";
                $extraCheck_dv .= " AND (dvd.vAcSubCode='".$this->ddlAccount."' OR dvd.vACode='".$this->ddlAccount."')";
                $extraCheck_ac .= " AND (acd.vAcSubCode='".$this->ddlAccount."' OR acd.vAcSubCode='".$this->ddlAccount."')";
                $extraCheck_ad .= " AND (adm.vAcSubCode='".$this->ddlAccount."' OR adm.vAcSubCode='".$this->ddlAccount."')";

                $extraCheck_cr .= " OR cr.nAcCode='".$this->ddlAccount."')";
                $extraCheck_cp .= " OR cp.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bb .= " OR bb.nAcCode='".$this->ddlAccount."')";
                $extraCheck_bp .= " OR bp.nAcCode='".$this->ddlAccount."')";

                if (!empty($this->ddlSubAccount)):
                    $extraCheck_cr .= " AND crd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cp .= " AND cpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bb .= " AND bbd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_bp .= " AND bpd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_jv .= " AND jvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_cv .= " AND cvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_dv .= " AND dvd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ac .= " AND acd.vACode='".$this->ddlSubAccount."'";
                    $extraCheck_ad .= " AND adm.vACode='".$this->ddlSubAccount."'";
                endif;
            endif;
        }//end if

        switch($this->ddlReport)
        {
            case "Details":
                $page = 'transaction_details_report';
                break;

            case "Summary":
                $page = 'transaction_summary_report';
                break;
        }//end switch

        switch($this->ddlTransaction)
        {
            case "Cash Receipt":
                $data['list_value'] = $this->cash_bank_report_model->getCashReceiptTransaction($extraCheck_cr);
                break;

            case "Cash Payment":
                $data['list_value'] = $this->cash_bank_report_model->getCashPaymentTransaction($extraCheck_cp);
                break;

            case "Bank Deposit":
                $data['list_value'] = $this->cash_bank_report_model->getBankDepositTransaction($extraCheck_bb);
                break;

            case "Bank Payment":
                $data['list_value'] = $this->cash_bank_report_model->getBankPaymentTransaction($extraCheck_bp);
                break;

            case "Credit Note":
                $data['list_value'] = $this->cash_bank_report_model->getCreditVoucherTransaction($extraCheck_cv);
                break;

            case "Debit Note":
                $data['list_value'] = $this->cash_bank_report_model->getDebitoucherTransaction($extraCheck_dv);
                break;

            case "Journal Voucher":
                $data['list_value'] = $this->cash_bank_report_model->getJournalVoucherTransaction($extraCheck_jv);
                break;

            case "ACM":
                $data['list_value'] = $this->cash_bank_report_model->getACMTransaction($extraCheck_ac);
                break;

            case "ADM":
                $data['list_value'] = $this->cash_bank_report_model->getADMTransaction($extraCheck_ad);
                break;

            default:
                $data['list_value'] = $this->cash_bank_report_model->getFullTransaction($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_cv,$extraCheck_dv,$extraCheck_jv,$extraCheck_ac,$extraCheck_ad);
                break;
        }//end switch
        $data['fromDate'] = $this->txtFromDate;
        $data['toDate'] = $this->txtToDate;
       // $mydata = $this->load->view("cash_bank_report/export_files/".$page, $data,true);
       // $_SESSION['$page'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('cash_bank_report/'.$page, $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('cash_bank_report/'.$page, $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function filterAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccGroup');

        $this->ddlAccount    = $this->session->userdata('ddlAccount');

        $data['list_acc_value'] = $this->cash_bank_report_model->filterAccount($accName);
        $this->load->view('cash_bank_report/show_account', $data);
    }//end function

    function filterSubAccount()
    {
        $this->load->helper('url');
        $this->load->helper(array('form', 'url'));

        $this->common_model->check_admin_authorization();
        $accName = $this->input->post('ddlAccount');

        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');
        $this->tableType = $this->session->userdata('tableType');

        $data['list_sub_acc'] = $this->cash_bank_report_model->filterSubAccount($accName);
        $this->load->view('cash_bank_report/show_sub', $data);
    }//end function
    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['list_emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('cash_bank_report/show_issuedBy',$data);
    }


}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
