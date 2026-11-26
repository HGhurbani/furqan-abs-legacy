<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Group_report extends CI_Controller {



	function __construct()

	{

		parent::__construct();

		$this->load->model('group_report_model');

	}



	function showlist()

	{

		$this->load->helper(array('form', 'url'));

		$this->showTitle = "GROUP REPORT";

        $this->btnVal    = REPRINT;

		$this->common_model->check_admin_authorization();



        $data['list_div_value']     = $this->group_report_model->getActiveDivision();

        $data['list_loc_value']     = $this->group_report_model->getActiveLocations();

        $data['list_payable']       = $this->group_report_model->getPayableAccont();


        $data['list_acc_value']     = $this->group_report_model->getActiveAcName();

        $data['list_sub_acc']       = $this->group_report_model->getActiveSubAccounts();
		$data['list_stk']       = $this->group_report_model->getActive_stocks();
		$data['list_sub_stk']       = $this->group_report_model->getActiveSubAccounts();
		





        if($this->session->userdata('pageName') == $this->uri->segment(1))

        {

            $this->ddlDivision      = $this->session->userdata('ddlDivision');

            $this->ddlLocation      = $this->session->userdata('ddlLocation');

            $this->ddlLocation2     = $this->session->userdata('ddlLocation2');

            $this->txtToDate        = $this->session->userdata('txtToDate');

            $this->txtFromDate      = $this->session->userdata('txtFromDate');

            

            $this->ddlPayableAccount = $this->session->userdata('ddlPayableAccount');

            $this->ddlAccount       = $this->session->userdata('ddlAccount');

            $this->ddlSubAccount    = $this->session->userdata('ddlSubAccount');

            
            $this->ddlReport        = $this->session->userdata('ddlReport');



      }

        else

        {

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');


            $this->session->unset_userdata('ddlPayableAccount');

            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');


            $this->session->unset_userdata('ddlReport');

            $this->session->unset_userdata('pageName');

        }



    if($this->ddlDivision != ""){

		$data['list_loc_value'] = $this->group_report_model->filterLocation($this->ddlDivision);
		$data['list_sub_stk']       = $this->group_report_model->filterSubstk($this->ddlReport);
		$data['list_sub_acc']       = $this->group_report_model->filterSubAccount($this->ddlAccount);

    }

    else {

      reset($data['list_div_value']);

      $first_key = key($data['list_div_value']);

      $data['list_loc_value'] = $this->group_report_model->filterLocation($first_key);

    }









		$this->load->view('general/template_top');

		$this->load->view('group_report/group_report',$data);

		$this->load->view('general/template_bottom');

	}//end function



    function filterDivision()

	{

		$this->load->helper('url');

		$this->load->helper(array('form', 'url'));



		$this->common_model->check_admin_authorization();



		$this->ddlDivision = $this->input->post('ddlDivision');

		$this->ddlLocation = $this->input->post('ddlLocation');



		$data['list_loc_value'] = $this->group_report_model->filterLocation($this->ddlDivision);

		$this->load->view('group_report/show_location',$data);

	}//end function



    function filterSubAccount()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = 237;//$this->input->post('ddlAccount');



        $this->ddlSubAccount = $this->session->userdata('ddlSubAccount').'-'.$this->session->userdata('tableType');

        $this->tableType = $this->session->userdata('tableType');



        $data['list_sub_acc'] = $this->group_report_model->filterSubAccount($accName);

        $this->load->view('group_report/show_sub', $data);

    }//end function
	
	
	function filterSubstock()

    {

        $this->load->helper('url');

        $this->load->helper(array('form', 'url'));



        $this->common_model->check_admin_authorization();

        $accName = $this->input->post('ddlReport');



        $data['list_sub_stk'] = $this->group_report_model->filterSubstk($accName);

        $this->load->view('group_report/show_sub1', $data);

    }//end function









		

   function filterList()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = "Group Report";



        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');


            $this->session->unset_userdata('ddlPayableAccount');


            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

  
            $this->session->unset_userdata('ddlReport');
			$this->session->unset_userdata('ddlSubstk');

            $this->session->unset_userdata('pageName');

        endif;



        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');



        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');



        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
		$this->ddlSubstk         = $this->common_model->searchKeyword3('ddlSubstk');



        $this->session->set_userdata('pageName',$this->uri->segment(1));

		
		

		

		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 

	  	{

				$extraCheck_finance_gr .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

		}

		

		if ($this->txtToDate != '' && $this->txtFromDate !='') {

			$extraCheck_finance_gr .= " AND STR_TO_DATE( gr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' )";

        }//end if



		if (!empty($this->ddlSubAccount)) {

			 $extraCheck_finance_gr .= " AND gr.acc='".$this->ddlSubAccount."'";
			}//end if



		if (!empty($this->ddlAccount)) {

			$extraCheck_finance_gr .= " AND gr.acc_head='".$this->ddlAccount."'";

        }//end if

       

		if (!empty($this->ddlReport)) {
			$ddl=$this->ddlReport;
			$dd=$this->ddlReport;
			
			$ddll=$this->ddlReport;
			$ddlll 			= @split('-',$ddll);
			$ddlll=$ddlll[0];
			$extraCheck_finance_gr .= " AND (gr.t_g='".$ddlll."' OR gr.h_g='".$ddlll."') ";

        }//end if
		 $data['list_value'] = $this->group_report_model->getgroupReport($extraCheck_finance_gr);
		if (!empty($this->ddlSubstk)) {
			$dsl=$this->ddlSubstk;
			$ddl=$this->ddlSubstk;
			
			$extraCheck_finance_gr .= " AND (gr.stk_t='".$this->ddlSubstk."' OR gr.stk_h='".$this->ddlSubstk."') ";

        }//end if

		$data['list_value1'] = $this->group_report_model->getgroupReport1($dd,$dsl);
        

        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('group_report/show_group_report', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('group_report/show_group_report', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function
	
	function filterList_stk()

    {

        $this->load->helper(array('form', 'url'));

        $this->showTitle = "Group Report";



        $this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')

            $this->session->unset_userdata('backURL');



        if(trim($this->session->userdata('backURL')) == ''):

            $this->session->unset_userdata('ddlDivision');

            $this->session->unset_userdata('ddlLocation');

            $this->session->unset_userdata('ddlLocation2');

            $this->session->unset_userdata('txtToDate');

            $this->session->unset_userdata('txtFromDate');


            $this->session->unset_userdata('ddlPayableAccount');


            $this->session->unset_userdata('ddlAccount');

            $this->session->unset_userdata('ddlSubAccount');

  
            $this->session->unset_userdata('ddlReport');
			$this->session->unset_userdata('ddlSubstk');

            $this->session->unset_userdata('pageName');

        endif;

 

        $this->ddlDivision       = $this->common_model->searchKeyword3('ddlDivision');

        $this->ddlLocation       = $this->common_model->searchKeyword3('ddlLocation');

        $this->ddlLocation2      = $this->common_model->searchKeyword3('ddlLocation2');

        $this->txtToDate         = $this->common_model->searchKeyword3('txtToDate');

        $this->txtFromDate       = $this->common_model->searchKeyword3('txtFromDate');



        $this->ddlPayableAccount = $this->common_model->searchKeyword3('ddlPayableAccount');

        $this->ddlAccount        = $this->common_model->searchKeyword3('ddlAccount');

        $this->ddlSubAccount     = $this->common_model->searchKeyword3('ddlSubAccount');



        $this->ddlRefund         = $this->common_model->searchKeyword3('ddlRefund');

        $this->ddlReport         = $this->common_model->searchKeyword3('ddlReport');
		$this->ddlSubstk         = $this->common_model->searchKeyword3('ddlSubstk');



        $this->session->set_userdata('pageName',$this->uri->segment(1));

		
		

		

		if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2))) 

	  	{

				$extraCheck_finance_gr .= " AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";

		}

		

		if ($this->txtToDate != '' && $this->txtFromDate !='') {

			$extraCheck_finance_gr .= " AND STR_TO_DATE( gr.dDate, '%Y-%m-%d' )  BETWEEN STR_TO_DATE('" . $this->txtToDate . "', '%Y-%m-%d' ) AND STR_TO_DATE('" .$this->txtFromDate . "', '%Y-%m-%d' )";

        }//end if



		if (!empty($this->ddlSubAccount)) {

			$extraCheck_finance_gr .= " AND gr.acc='".$this->ddlSubAccount."'";
			}//end if



		if (!empty($this->ddlAccount)) {

			$extraCheck_finance_gr .= " AND gr.acc_head='".$this->ddlAccount."'";

        }//end if
		
		if (!empty($this->ddlReport)) {
			$ddl=$this->ddlReport;
			$dd=$this->ddlReport;
			
			$ddll=$this->ddlReport;
			$ddlll 			= @split('-',$ddll);
			$ddlll=$ddlll[0];
			$extraCheck_finance_gr .= " AND (gr.t_g='".$ddlll."' OR gr.h_g='".$ddlll."') ";

        }//end if
		
		if (!empty($this->ddlSubstk)) {
			$dsl=$this->ddlSubstk;
			$ddl=$this->ddlSubstk;
			
			$extraCheck_finance_gr .= " AND (gr.stk_t='".$this->ddlSubstk."' OR gr.stk_h='".$this->ddlSubstk."') ";

        }//end if

        $data['list_value'] = $this->group_report_model->getgroupReport_stk($extraCheck_finance_gr,$dd);
		$data['list_value1'] = $this->group_report_model->getgroupReport1($dd,$dsl);

        

        if(trim($this->session->userdata('backURL')) != ''):

            $this->load->view('general/template_top');

            $this->load->view('group_report/show_stock_report', $data);

            $this->load->view('general/template_bottom');

        else:

            $this->load->view('group_report/show_stock_report', $data);

        endif;



        $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

    }//end function

    



 





}//end class



/* End of file welcome.php */

/* Location: ./application/controllers/welcome.php */

?>

