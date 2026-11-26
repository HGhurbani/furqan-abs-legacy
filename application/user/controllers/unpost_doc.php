<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Unpost_doc extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('unpost_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle    = UNPOST;
        $this->btnVal       = REPORT;
		$this->common_model->check_admin_authorization();

        if($this->session->userdata('pageName') == $this->uri->segment(1))
        {
            $this->ddlDivision  = $this->session->userdata('ddlDivision');
            $this->ddlLocation  = $this->session->userdata('ddlLocation');
            $this->ddlLocation2 = $this->session->userdata('ddlLocation2');
            $this->txtFromDate  = $this->session->userdata('txtFromDate');
            $this->txtToDate    = $this->session->userdata('txtToDate');
            $this->ddlEmpl      = $this->session->userdata('ddlEmpl');
            $this->ddlType      = $this->session->userdata('ddlType');
            $this->ddlSalesType = $this->session->userdata('ddlSalesType');
            $this->ddlDocType   = $this->session->userdata('ddlDocType');
        }
        else
        {
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('ddlEmpl');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlDocType');
            $this->session->unset_userdata('pageName');
        }

		$data['division']        = $this->unpost_model->getActiveDivision();
		$data['location']        = $this->unpost_model->getActiveLocation();
		$data['emp_value']       = $this->unpost_model->getActiveEmployee();
		$data['sales_type']      = $this->unpost_model->salesType();
		$data['doc_type']        = $this->unpost_model->docType();
		$data['list_type_value'] = $this->unpost_model->getTypeValue();

        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3));

        $this->load->view('general/template_top');
		$this->load->view('unpost/unpost',$data);
		$this->load->view('general/template_bottom');
	}//end function

	function filterDivision()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();

		$this->ddlDivision = $this->input->post('ddlDivision');
		$this->ddlLocation = $this->input->post('ddlLocation');

		$data['list_loc_value'] = $this->unpost_model->filterLocation($this->ddlDivision);
		$this->load->view('unpost/show_location',$data);
	}//end function



  function extra_check_htl(){
    if (!empty($this->ddlDivision)) {
      $extraCheck_hv = " AND hv.nDivision='".$this->ddlDivision."'";
    }//end if
/*
    if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
      $extraCheck_hv .= " AND (hv.nLocation='".$this->ddlLocation."' OR hv.nLocation='".$this->ddlLocation2."')";
    }//end if
    else{
      if (!empty($this->ddlLocation)) {
        $extraCheck_hv .= " AND hv.nLocation='".$this->ddlLocation."'";
      }//end if

      if (!empty($this->ddlLocation2)) {
        $extraCheck_hv .= " AND hv.nLocation='".$this->ddlLocation2."'";
      }//end if
    }//end else
*/
    if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2)))
      {
        $extraCheck_hv .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      }

    if (!empty($this->ddlEmpl)) {
      $extraCheck_hv .= " AND hv.vIssuedBy = '".$this->ddlEmpl."'";
    } //end if

    switch($this->ddlType)
    {
      case "0" :
        //$extraCheck_hv .= " ";
		$extraCheck_hv .= " AND hv.vType NOT IN('V','C') AND hv.vTotalDC NOT IN('0.000')";
      break;

      default:
      $extraCheck_hv .= " AND hv.vType = '".$this->ddlType."'";
      break;
    }//END SWITCH
/*
    if ($this->txtFromDate !='') {
      $extraCheck_hv .= " AND hv.dDate >= '".$this->txtFromDate."'";
    }//end if

    if ($this->txtToDate !='') {
      $extraCheck_hv .= " AND hv.dDate <= '".$this->txtToDate."'";
    } //end if
*/

    if ($this->txtToDate != '' && $this->txtFromDate !='') {
      $extraCheck_hv .= " AND hv.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND hv.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
    } //end if


    return $extraCheck_hv;

  }// end function

  function extra_check_tkt(){
    if (!empty($this->ddlDivision)) {
      $extraCheck_tb = " AND tb.nDivision='".$this->ddlDivision."'";
    }//end if
/*
    if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
      $extraCheck_tb .= " AND (tb.nLocation='".$this->ddlLocation."' OR tb.nLocation='".$this->ddlLocation2."')";
    }//end if
    else{
      if (!empty($this->ddlLocation)) {
        $extraCheck_tb .= " AND tb.nLocation='".$this->ddlLocation."'";
      }//end if

      if (!empty($this->ddlLocation2)) {
        $extraCheck_tb .= " AND tb.nLocation='".$this->ddlLocation2."'";
      }//end if
    }//end else
*/
    if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2)))
      {
        $extraCheck_tb .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      }

    if (!empty($this->ddlEmpl)) {
      $extraCheck_tb .= " AND tb.vIssuedBy = '".$this->ddlEmpl."'";
    } //end if

	if($this->ddlType =='N'){
		  $extraCheck_tb .= "AND tb.vType NOT IN('V','C') AND tfd.nNetVendor NOT IN('0.000')";
	  }elseif($this->ddlType =='0'){
		 $extraCheck_tb .= "AND tb.vType NOT IN('V','C') AND tfd.nNetVendor NOT IN('0.000')";
	  }else{
	  $extraCheck_tb .= " AND tb.vType = '".$this->ddlType."'";
      }
   /* switch($this->ddlType)
    {
      case "0" :
	 $extraCheck_tb .= " AND tb.vType NOT IN('V','C') AND tfd.nNetVendor NOT IN('0.000')";
	 // $extraCheck_tb .= " AND tfd.nNetVendor NOT IN('0.000')"; 
        //$extraCheck_tb .= " AND tb.vType NOT IN('V','C')"; nNetVendor
      break;
      

      default:
      if($this->ddlType =='N'){
		 $extraCheck_tb .= " AND tb.vType = '".$this->ddlType."'";
	  }else{
	  $extraCheck_tb .= " AND tb.vType = '".$this->ddlType."'";
      }
	  break;
    }//END SWITCH*/
/*
    if ($this->txtFromDate !='') {
      $extraCheck_tb .= " AND tb.dDate >= '".$this->txtFromDate."'";
    }//end if

    if ($this->txtToDate !='') {
      $extraCheck_tb .= " AND tb.dDate <= '".$this->txtToDate."'";
    } //end if
*/
    if ($this->txtToDate != '' && $this->txtFromDate !='') {
      $extraCheck_tb .= " AND tb.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND tb.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
    } //end if

    return $extraCheck_tb;

  }// end function

  function extra_check_xo(){

    if (!empty($this->ddlDivision)) {
      $extraCheck_xo = " AND xo.nDivision='".$this->ddlDivision."'";
    }//end if
    if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2)))
      {
        $extraCheck_xo .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      }

/*
    if (!empty($this->ddlLocation) && !empty($this->ddlLocation2)) {
      $extraCheck_xo .= " AND (xo.nLocation='".$this->ddlLocation."' OR xo.nLocation='".$this->ddlLocation2."')";
    }//end if
    else{
      if (!empty($this->ddlLocation)) {
        $extraCheck_xo .= " AND xo.nLocation='".$this->ddlLocation."'";
      }//end if

      if (!empty($this->ddlLocation2)) {
        $extraCheck_xo .= " AND xo.nLocation='".$this->ddlLocation2."'";
      }//end if
    }//end else
*/
    if (!empty($this->ddlEmpl)) {
      $extraCheck_xo .= " AND xo.vIssuedBy = '".$this->ddlEmpl."'";
    } //end if

    switch($this->ddlType)
    {
      case "0" :
        $extraCheck_xo .= " AND xo.vType NOT IN('V','C') AND xpd.nNetVendor NOT IN('0.000')";
      break;

      default:
      $extraCheck_xo .= " AND xo.vType = '".$this->ddlType."'";
      break;
    }//END SWITCH
/*
    if ($this->txtFromDate !='') {
      $extraCheck_xo .= " AND xo.dDate >= '".$this->txtFromDate."'";
    }//end if

    if ($this->txtToDate !='') {
      $extraCheck_xo .= " AND xo.dDate <= '".$this->txtToDate."'";
    } //end if
*/
    if ($this->txtToDate != '' && $this->txtFromDate !='') {
      $extraCheck_xo .= " AND xo.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND xo.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
    } //end if

    return $extraCheck_xo;

  }// end function

  function check_old_refund()
  {

        if (!empty($this->ddlDivision)) {

            $extraCheck_or = " AND rr.nDivision='".$this->ddlDivision."'";
        }//end if

    if ((!empty($this->ddlLocation)) && (!empty($this->ddlLocation2)))
      {
        $extraCheck_or .= "AND loc.vCode between (select vCode from {PRE}Location where nId=" . $this->ddlLocation . ") and (select vCode from {PRE}Location where nId=" . $this->ddlLocation2 . ")";
      }

    if ($this->txtToDate != '' && $this->txtFromDate !='') {
      $extraCheck_or .= " AND rr.dDate >= STR_TO_DATE('" .$this->txtFromDate . "', '%d-%m-%Y' ) AND rr.dDate <= STR_TO_DATE('" . $this->txtToDate . "', '%d-%m-%Y' )";
    } //end if

        if (!empty($this->ddlEmpl)) {
          $extraCheck_or .= " AND rrd.vIssuedBy='".$this->ddlEmpl."'";
        }//end if

    switch($this->ddlType)
    {
      case "0" :
        $extraCheck_or .= " ";
      break;

      default:
      $extraCheck_or .= " AND rrd.vTktType = '".$this->ddlType."'";
      break;
    }//END SWITCH
    return $extraCheck_or;
}// end function


	function filterList()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = UNPOST;

		$this->common_model->check_admin_authorization();

        if($this->input->post('sess_clear') == 'y')
            $this->session->unset_userdata('backURL');

        if(trim($this->session->userdata('backURL')) == ''):
            $this->session->unset_userdata('ddlDivision');
            $this->session->unset_userdata('ddlLocation');
            $this->session->unset_userdata('ddlLocation2');
            $this->session->unset_userdata('txtFromDate');
            $this->session->unset_userdata('txtToDate');
            $this->session->unset_userdata('ddlEmpl');
            $this->session->unset_userdata('ddlType');
            $this->session->unset_userdata('ddlSalesType');
            $this->session->unset_userdata('ddlDocType');
        endif;

        $this->ddlDivision  = $this->common_model->searchKeyword3('ddlDivision');
        $this->ddlLocation  = $this->common_model->searchKeyword3('ddlLocation');
        $this->ddlLocation2 = $this->common_model->searchKeyword3('ddlLocation2');
        $this->txtFromDate  = $this->common_model->searchKeyword3('txtFromDate');
        $this->txtToDate    = $this->common_model->searchKeyword3('txtToDate');
        $this->ddlEmpl      = $this->common_model->searchKeyword3('ddlEmpl');
        $this->ddlType      = $this->common_model->searchKeyword3('ddlType');
        $this->ddlSalesType = $this->common_model->searchKeyword3('ddlSalesType');
        $this->ddlDocType   = $this->common_model->searchKeyword3('ddlDocType');

        $this->session->set_userdata('pageName',$this->uri->segment(1));

        $extraCheck_t =  $this->extra_check_tkt();
        $extraCheck_x = $this->extra_check_xo();
        $extraCheck_h =  $this->extra_check_htl();
        $extraCheck_or = $this->check_old_refund();


        if(!empty($this->ddlDocType))
        {
          switch($this->ddlDocType)
            {
                case "UD":
                  $orderby = ' YEAR(dDate_order) ASC, MONTH(dDate_order) ASC, DAY(dDate_order) ASC, tktNumber, sale_type ASC';				  //$orderby = 'tktNumber';
                  $data['list_value'] = $this->unpost_model->getUnpostDetails($extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or,$orderby,$this->ddlSalesType);

                    if(trim($this->session->userdata('backURL')) != ''):
                        $this->load->view('general/template_top');
                        $this->load->view('unpost/unpost_filter',$data);
                        $this->load->view('general/template_bottom');
                    else:
                        $this->load->view('unpost/unpost_filter',$data);
                    endif;

                    $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
                break;

                case "UDS":
                  $orderby = ' vIssuedBy ASC';
                  $data['list_value'] = $this->unpost_model->getUnpostDetails($extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or,$orderby,$this->ddlSalesType);

                    if(trim($this->session->userdata('backURL')) != ''):
                        $this->load->view('general/template_top');
                        $this->load->view('unpost/unpost_staff_filter',$data);
                        $this->load->view('general/template_bottom');
                    else:
                        $this->load->view('unpost/unpost_staff_filter',$data);
                    endif;

                    $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);
                break;

                case "UDD":
                  $orderby = ' sale_type ASC,dDate_order ASC,vDocNumber ASC ';
                  $data['list_value'] = $this->unpost_model->getUnpostDetails($extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or,$orderby,$this->ddlSalesType);

                    if(trim($this->session->userdata('backURL')) != ''):
                        $this->load->view('general/template_top');
                        $this->load->view('unpost/unpost_date_filter',$data);
                        $this->load->view('general/template_bottom');
                    else:
                        $this->load->view('unpost/unpost_date_filter',$data);
                    endif;

                    $this->common_model->getPHPSelf($_SERVER['PHP_SELF']);

                break;
            }//end switch
        }//end function

	}//end function

    function getIssuedByMultiLoc()
    {
      $this->load->helper('url');
      $this->load->helper(array('form', 'url'));
      $this->common_model->check_admin_authorization();
      $data['emp_value'] = $this->common_model->getIssuedByMultiLoc();
      $this->load->view('unpost/show_issuedBy',$data);
    }

}//end class

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */
?>
