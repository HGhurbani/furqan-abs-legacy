<?php
class Transaction_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/*********************Transactions******************************/

	function getAllTransactions($extraCheck)
	{		
		//active groups
		$adminGroupRow  		= array();
		$sqlTransQuery   	    = "SELECT * FROM {PRE}Transactions ".$extraCheck."
									 ORDER BY nPosition DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlTransQuery;	
		$adminGroupList 		= $this->db->query($sqlTransQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	function getCorresTrans()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Transactions WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertTransactions()
	{
		$data = array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),'vCode' => $this->input->post('txtCode'),
					  'nLocation' => $this->input->post('ddlLocation'),'vPrefix' => $this->input->post('txtPrefix'),
					  'vYear' => $this->input->post('ddlYear'),'vMindigit' => $this->input->post('txtMindigit'),
					  'vSampledoc' => $this->input->post('txtSampledoc'),'vYrlyreset' => $this->input->post('chkYearlyReset'),
					  'vActive' => $this->input->post('chkActive'),'nPosition' => $this->common_model->maxPos('Transactions'));
		$this->db->insert('{PRE}Transactions', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("transactions/editTransactions/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("transactions/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));		
		}//end if
	}//end class
	
	function updateTransactions()
	{
		$data 	= array('vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),'vCode' => $this->input->post('txtCode'),
						'nLocation' => $this->input->post('ddlLocation'),'vPrefix' => $this->input->post('txtPrefix'),
						'vYear' => $this->input->post('ddlYear'),'vMindigit' => $this->input->post('txtMindigit'),
						'vSampledoc' => $this->input->post('txtSampledoc'),'vYrlyreset' => $this->input->post('chkYearlyReset'),
                        'nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Transactions', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("transactions/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlType'));	
	}//end function		
	
	function deleteTransaction()
	{
		$transactionId = $this->common_model->get_field_name('Transactions','nParentId'," WHERE nId='".$this->uri->segment(4)."'");
		$this->db->delete('{PRE}Transactions', array('nId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}Document', array('nTransaction' => $this->uri->segment(4)));
		redirect(site_url("transactions/showlist/".$this->uri->segment(3).'/del_suc/'.$transactionId));	
	}//end function 
	
	function deleteAllTransaction()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Transactions', $data); 
				}//end foreach
				redirect(site_url("transactions/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Transactions', $data); 
				}//end foreach
				redirect(site_url("transactions/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$transactionId	    =   $this->common_model->get_field_name('Transactions','nParentId'," WHERE nId='".$val."'");
					$this->db->delete('{PRE}Transactions', array('nId' => $val)); 
					$this->db->delete('{PRE}Document', array('nTransaction' => $val));
				}//end foreach
				redirect(site_url("transactions/showlist/".$this->input->post('nCId').'/del_suc/'.$transactionId));	
			break;
		}//end switch
	}//end function 

	function getActiveLocations()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' ORDER BY nId DESC");
		$typeGroupRow = array();
		
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else
		return ($typeGroupRow);
	}//end function 
	
	function getFilterTransaction($extraCheck)
	{
		$adminGroupRow  		= array();
		$sqlTransFilterQuery   	= "SELECT * FROM {PRE}Transactions WHERE nParentId !='0' ".$extraCheck."
									ORDER BY nPosition DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlTransFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlTransFilterQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function
	
	function getDropTransactionMode()
	{
		$typeGroupRow    = array("0" => SELECT, "Cr" => "Credit", "Dr" => "Debit");
		return ($typeGroupRow);
	}//end function 
	
	function getDropTransactionYear()
	{
		$typeGroupRow    = array("0" => SELECT, "YY" => "YY", "YYYY" => "YYYY");
		return ($typeGroupRow);
	}//end function 
	
	function getDropTransYearlyReset()
	{
		$typeGroupRow    = array("0" => SELECT,"Y" => "Yes", "N" => "No");
		return ($typeGroupRow);
	}//end function
	
	//fetching single details
	function showDetails($transid)
	{
		$adminGroupList     = $this->db->query("SELECT * FROM {PRE}Transactions WHERE nId = '".$transid."'"); 
		$showVal	        = $adminGroupList->row();
		//fetch transaction mode
		switch($showVal->vTranmode)
		{
			case "Cr":
				$transmode  = 'Credit';
			break;
			
			case "Dr":
				$transmode	= 'Debit';
			break;
		}//end switch
		
		//fetch yearly reset
		switch($showVal->vYrlyreset)
		{
			case "Y":
				$reset		= 'Yes';
			break;
			
			case "N":
				$reset		= 'No';
			break;
		}//end switch

		$content            = '<table class="reportForm">
			<tr>
				<td><strong>'.TRANSCODE.' :</strong></td>
				<td>'.$showVal->vCode.'</td>
				<td><strong>'.TRANSNAME.' :</strong></td>
				<td>'.$showVal->vTitle.'</td>
				<td><strong>'.TRANSPREFIX.' :</strong></td>
				<td>'.$showVal->vPrefix.'</td>
				<td><strong>'.TRANSYEAR.' :</strong></td>
				<td>'.$showVal->vYear.'</td>
			</tr>
			<tr>
				<td><strong>'.TRANSMINDIGIT.' :</strong></td>
				<td>'.$showVal->vMindigit.'</td>
				<td><strong>'.TRANSSAMPLEDOC.' :</strong></td>
				<td>'.$showVal->vSampledoc.'</td>
				<td><strong>'.TRANSYEARLYRESET.' :</strong></td>
				<td colspan="5">'.$reset.'</td>
			</tr>
		 </table>';

		return($content);
	}//end function
 
}//end class
?>