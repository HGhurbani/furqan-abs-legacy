<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Transactions extends CI_Controller {

	function __construct()
	{
		parent::__construct();
		$this->load->model('transaction_model');
	}

	function showlist()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSMANAGEMENT;
		$this->common_model->check_admin_authorization();

		
		if($this->uri->segment('5') != '')
			$this->ddlType = $this->common_model->searchKeyword33('5');
		else if($this->uri->segment('4') != '' && $this->uri->segment('4') == 'del_suc')
			$this->ddlType = $this->common_model->searchKeyword33('3');
		else
			$this->ddlType = $this->common_model->searchKeyword3('ddlType');
		$this->ddlType = (trim($this->uri->segment(3)) == '')?0:$this->ddlType;
		switch($this->ddlType)
		{
			 case "0":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlType');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;

			 case "":
					$extraCheck = " ";
					$this->session->unset_userdata('ddlType');
					$this->session->unset_userdata('5');
					$this->session->unset_userdata('3');
			 break;
		}//end switch

		//site Name
		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Transactions','nId', $extraCheck), $target,'transactions/showlist/'.$this->uri->segment(3));
		$data['list_value']   = $this->transaction_model->getAllTransactions($extraCheck);
		$this->count_rows     = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'transactions/showlist');

        $this->load->view('general/template_top',$data);
		$this->load->view('transaction/transactions');
		$this->load->view('general/template_bottom');
	}//end function

	function editTransactions()
	{
		$this->common_model->check_admin_authorization();
		//site Name
		$data['title'] 	   = $this->common_model->get_site_title();
		$this->load->helper(array('form', 'url'));
		$this->load->library('form_validation');
		$this->form_validation->set_rules('txtTitle', 'Transaction Name', 'required');

    $id = $this->uri->segment(4);
    //User Access aa
    $action_access = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'transactions/showlist');
    $data['action_access'] = $action_access;
    if(!(($id == 'a' && ( $action_access->vAccess == 'E' ||  $action_access->vAccess == 'F' )) || ($id == 'e' &&  ( $action_access->vAccess == 'U' || $action_access->vAccess == 'F' ||  $action_access->vAccess == 'Q')))){
      if(trim($this->session->userdata('backURL')) != '')
        redirect(site_url($this->session->userdata('backURL')));
      else
        redirect(site_url("transactions/showlist/".$this->uri->segment(3).'/no_access'));
    }

		//get active transaction type
		$data['list_year']		    = $this->transaction_model->getDropTransactionYear();
		$data['list_loc_value']     = $this->transaction_model->getActiveLocations();
		switch($this->uri->segment(4))
		{
			case "a":
				$this->showTitle  	    = ADDNEWTRANS;
				$this->btnVal	  	    = ADDCONTINUE;
				$this->btnVal2	  	    = SAVEEXIT;
				$this->txtTitle	  	    = $this->input->post('txtTitle');
				$this->chkValYes  	    = 'checked="checked"';
				$this->chkValNo	  	    = '';
				$this->chkValTypeN      = 'checked="checked"';
				$this->chkValTypeVE	    = '';
				$this->chkValYearlyYes  = 'checked="checked"';
				$this->chkValYearlyNo	= '';
				$this->catId	  	    = '';
			break;

			case "e":
				$this->showTitle  = EDITTRANS;
				$this->btnVal	  = UPDATETRANS;
				$this->catId	  = $this->uri->segment(5);
				if(intval($this->input->post('nId')) != 0)
				{
					$this->catId  = intval($this->input->post('nId'));
				}//end if

				$showVal	        = $this->transaction_model->getCorresTrans();
				$this->txtTitle	    = $showVal->vTitle;
				$this->txtCode	    = $showVal->vCode;
                $this->txtPrefix    = $showVal->vPrefix;
				$this->ddlYear	    = $showVal->vYear;
				$this->txtMindigit  = $showVal->vMindigit;
				$this->txtSampledoc = $showVal->vSampledoc;
				$this->ddlLocation  = $showVal->nLocation;
			
				if($showVal->vYrlyreset == "Y")
				{
					$this->chkValYearlyYes   = 'checked="checked"';
				}//end if
				else
				{
					$this->chkValYearlyNo   = 'checked="checked"';
				}//end else

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
			$this->load->view('transaction/edit_transaction');
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

			//check name already exits or not
			$chkTitle = $this->db->query("SELECT vTitle FROM {PRE}Transactions WHERE vTitle='".mysql_real_escape_string($this->input->post('txtTitle'))."' ".$this->extraCond);
            if ($chkTitle->num_rows() > 0)
			{
				$data['error_value'] = TRANSEXIST;
				$this->load->view('general/template_top',$data);
				$this->load->view('transaction/edit_transaction');
				$this->load->view('general/template_bottom');
			}//end if
			else
			{
				//checking segment
				switch($this->uri->segment(4))
				{
					case "a":
						//insert into db
						$this->transaction_model->insertTransactions();
					break;

					case "e":
						//Update into db
						$this->transaction_model->updateTransactions();
					break;
				}//end switch
				redirect(site_url("transactions/showlist/".$this->uri->segment(3).'/'.$msgVal));
			}//end else
		}//end else
	}//end function

	function deltransaction()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSTYPEMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->transaction_model->deleteTransaction();
	}//end function

	function delrecords()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSMANAGEMENT;
		$this->common_model->check_admin_authorization();
		$this->transaction_model->deleteAllTransaction();
	}//end function
	function filterTransaction()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSMANAGEMENT;

		$this->common_model->check_admin_authorization();
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtTracode');
			$this->txtTracode = $this->common_model->searchKeyword('txtTracode');
		}//end if

		if(!empty($this->txtTracode))
		{
			$extraCheck = " AND (vTitle LIKE '".$this->txtTracode." %' OR vTitle LIKE '% ".$this->txtTracode."'
							OR vTitle LIKE '%".$this->txtTracode."%'
							OR vCode LIKE '".$this->txtTracode." %' OR vCode LIKE '% ".$this->txtTracode."'
							OR vCode LIKE '%".$this->txtTracode."%')";
		}//end if
		else
		{
            $extraCheck = "";
		}//end else

		if(!empty($this->ddlType))
        {
            switch($this->ddlType)
			{
                case "0":
						$extraCheck .= " ";
				 break;
			}//end switch
		}//end if

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Transactions','nId'," WHERE nParentId!='0' ".$extraCheck.""), $target,'transactions/filterTransaction2/'.$this->uri->segment(3));
		$data['list_value'] = $this->transaction_model->getFilterTransaction($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'transactions/showlist');

		$this->load->view('transaction/transactions_filter',$data);
	}//end function

	function filterTransaction2()
	{
		$this->load->helper(array('form', 'url'));
		$this->showTitle = TRANSMANAGEMENT;
		

		$this->common_model->check_admin_authorization();
		$data['list_type']		   = $this->transaction_model->getDropTypeSearch();
		$data['list_transmode']	   = $this->transaction_model->getDropTransactionMode();
		$data['list_year']		   = $this->transaction_model->getDropTransactionYear();
		$data['list_yearly_reset'] = $this->transaction_model->getDropTransYearlyReset();

		$this->ddlType = $this->common_model->searchKeyword('ddlType');
		if($this->input->post('btnSearch') == 'Go')
		{
			$this->session->unset_userdata('txtTracode');
			$this->txtTracode = $this->common_model->searchKeyword('txtTracode');
		}//end if

		if(!empty($this->txtTracode))
		{
			$extraCheck = " AND (vTitle LIKE '".$this->txtTracode." %' OR vTitle LIKE '% ".$this->txtTracode."'
							OR vTitle LIKE '%".$this->txtTracode."%'
							OR vCode LIKE '".$this->txtTracode." %' OR vCode LIKE '% ".$this->txtTracode."'
							OR vCode LIKE '%".$this->txtTracode."%')";
		}//end if
		else
		{
			$extraCheck = "";
		}//end else

		if(!empty($this->ddlType))
		{
			switch($this->ddlType)
			{
				 case "0":
						$extraCheck .= " ";
				 break;
			}//end switch
		}//end if

		$target = '.';
		$this->common_model->_paging($this->common_model->get_total_num_rows('Transactions','nId'," WHERE nParentId!='0' ".$extraCheck.""), $target,'transactions/filterTransaction2/'.$this->uri->segment(3));
		$data['list_value'] = $this->transaction_model->getFilterTransaction($extraCheck);
		$this->count_rows   = count($data['list_value']);

// User access aa
        $data['action_access'] = $this->common_model->get_admin_access_list($this->session->userdata('AbsAdmin'),$this->uri->segment(3),'transactions/showlist');

		$this->load->view('general/template_top',$data);
		$this->load->view('transaction/transactions');
		$this->load->view('general/template_bottom');
	}//end function
function yearupdate(){			$val=$this->input->post('ddlType');	if($val!=''){		$no=$this->common_model->get_field_name('Transactions','vSampledoc'," WHERE vYrlyreset ='Y' ORDER BY nId DESC LIMIT 0,1");		 $docNumber 			= @split('-',$no);		$typeGroup    = $this->db->query("UPDATE tbl_Transactions SET vSampledoc = REPLACE( vSampledoc,  '".$docNumber[2]."',  '".$val."' ) WHERE vYrlyreset ='Y'");			}else{				}		 }
	function checkTitle()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;

			default:
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;
		}//end switch

		$this->txtTitle 		= $this->input->post('txtTitle');
		$txtTitle       	    = $this->common_model->get_field_name('Transactions','vTitle'," WHERE  vTitle='".mysql_real_escape_string($this->txtTitle)."' ".$extraCond." ");
        if($txtTitle    == $this->txtTitle)
		{
			echo "false";
		}//end if
		else
		{
			echo "true";
		}//end else
	}//end function

	function checkCode()
	{
		switch($this->uri->segment(3))
		{
			case "":
				$extraCond = '';
			break;

			default:
				$extraCond = " AND nId != '".$this->uri->segment(3)."'";
			break;
		}//end switch

		$this->txtCode	 		= $this->input->post('txtCode');
		$txtCode	       	    = $this->common_model->get_field_name('Transactions','vCode'," WHERE  vCode='".mysql_real_escape_string($this->txtCode)."' ".$extraCond." ");
        if($txtCode    == $this->txtCode)
		{
			echo "false";
		}//end if
		else
		{
			echo "true";
		}//end else
	}//end function

	function position()
	{
		$this->load->helper('url');
		$this->load->database();
		$oldId             = $this->uri->segment(5);
		$oldPosition       = $this->uri->segment(6);
		$table             = "{PRE}Transactions";
		$PositionfieldName = 'nPosition'; //db field name
		$IdfieldName       = 'nId'; //db field name
		if($this->uri->segment(4)=='up')
		{
			$this->common_model->OrderUp($table,$oldId,$oldPosition,$PositionfieldName,$IdfieldName);
		}//end if

		if($this->uri->segment(4)=='down')
		{
			$this->common_model->OrderDown($table,$oldId,$oldPosition,$PositionfieldName,$IdfieldName);
		}//end if
		redirect(site_url("transactions/showlist/".$this->uri->segment(3).'/'.$msgVal));
	}//end function

	function generateSampledoc()
	{
		$this->load->helper('url');
		$this->load->helper(array('form', 'url'));

		$this->common_model->check_admin_authorization();
		$this->txtCode      	= $this->input->post('txtCode');
		$this->txtPrefix        = $this->input->post('txtPrefix');
		$this->txtMindigit      = $this->input->post('txtMindigit');
		$this->ddlYear          = $this->input->post('ddlYear');
		$this->ddlLocation      = $this->input->post('ddlLocation');
		$this->txtLocation      = $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$this->ddlLocation."'");
		if($this->ddlYear == 'YY' && $this->ddlYear != '')
			$this->currYear		= date('y');
		else if($this->ddlYear == 'YYYY' && $this->ddlYear != '')
			$this->currYear		= date('Y');

		$this->sampleDigit      = @str_repeat('0',($this->txtMindigit-1)).'1';
		//$this->txtSampledoc     = $this->txtLocation.'-'.$this->currYear.'-'.$this->txtPrefix.'-'.$this->txtCode.'-'.$this->sampleDigit;
		//$this->txtSampledoc     = $this->txtCode.$this->txtPrefix.$this->currYear.$this->sampleDigit;
        //$this->txtSampledoc     = $this->txtLocation.'-'.$this->currYear.'-'.$this->txtPrefix.'-'.$this->sampleDigit;
        $this->txtSampledoc     = $this->txtPrefix.'-'.$this->txtLocation.'-'.$this->currYear.'-'.$this->sampleDigit;
        echo $this->txtSampledoc;
	}//end function

}//end class
/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */

?>
