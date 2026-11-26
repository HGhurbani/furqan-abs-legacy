<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Sub_account_report extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('sub_account_report_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = SUBACCOUNT;
        $this->btnVal    = REPRINT;
		$this->common_model->check_admin_authorization();

        $data['list_div_value'] = $this->sub_account_report_model->getActiveDivision();
		$data['list_loc_value'] = $this->sub_account_report_model->getActiveLocation();
        $data['list_acc_value'] = $this->sub_account_report_model->getActiveAcName();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision   = $this->session->userdata('ddlDivision');
            $this->ddlLocation   = $this->session->userdata('ddlLocation');
            $this->ddlAccount    = $this->session->userdata('ddlAccount');
            $this->ddlType       = $this->session->userdata('ddlType');
            $this->pageName      = $this->session->userdata('pageName');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('pageName');
        }

        $this->load->view('general/template_top');
		$this->load->view('sub_account_report/sub_account_report',$data);
		$this->load->view('general/template_bottom');
	}//end function

    function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->sub_account_report_model->filterLocation($this->ddlDivision);
		$this->load->view('sub_account_report/show_location',$data);
	}//end function

    function getSubAccount()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SUBACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlType           = $this->common_model->searchKeyword3('ddlType');

//print    $this->ddlLocation;
     $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = "";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= "AND FIND_IN_SET(  ".$this->ddlLocation.", vLocation )";//" AND vLocation = '".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlAccount)) {
           
		   $extraCheck .= " AND nFId = '".$this->ddlAccount."'";
        }//end if
		$loc=$this->ddlLocation;
        $data['list_value'] = $this->sub_account_report_model->getSubAccount($this->ddlType,$extraCheck,$loc);

       //$mydata = $this->load->view("sub_account_report/export_files/sub_account_list", $data,true);
        //$_SESSION['sub_account_list'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_account_report/sub_account_list', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_account_report/sub_account_list', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getAirline()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SUBACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlType           = $this->common_model->searchKeyword3('ddlType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " ";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND vLocation = '".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND nFId = '".$this->ddlAccount."'";
        }//end if

        $data['list_value'] = $this->sub_account_report_model->getAirline();
        //$mydata = $this->load->view("sub_account_report/export_files/airline_list", $data,true);
        //$_SESSION['airline_account_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_account_report/airline_list', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_account_report/airline_list', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getHotel()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SUBACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlType           = $this->common_model->searchKeyword3('ddlType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " ";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND vLocation = '".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND nFId = '".$this->ddlAccount."'";
        }//end if

        $data['list_value'] = $this->sub_account_report_model->getHotel();
        //$mydata = $this->load->view("sub_account_report/export_files/hotel_list", $data,true);
        //$_SESSION['hotel_account_report'] = $mydata;

        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_account_report/hotel_list', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_account_report/hotel_list', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

    function getCountry()
    {
        $this->load->helper(array('form', 'url'));
        $this->showTitle = SUBACCOUNT;

        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlAccount');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('pageName');
        endif;

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');
        $this->ddlType           = $this->common_model->searchKeyword3('ddlType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        if (!empty($this->ddlDivision)) {
            $extraCheck = " ";
        }//end if

        if (!empty($this->ddlLocation)) {
            $extraCheck .= " AND vLocation = '".$this->ddlLocation."'";
        }//end if

        if (!empty($this->ddlAccount)) {
            $extraCheck .= " AND nFId = '".$this->ddlAccount."'";
        }//end if

        $data['list_value'] = $this->sub_account_report_model->getCountry();

        //$mydata = $this->load->view("sub_account_report/country_list", $data,true);
        //$_SESSION['country_account_report'] = $mydata;


        if(trim($this->session->userdata('backURL')) != ''):
            $this->load->view('general/template_top');
            $this->load->view('sub_account_report/country_list', $data);
            $this->load->view('general/template_bottom');
        else:
            $this->load->view('sub_account_report/country_list', $data);
        endif;

        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
    }//end function

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
