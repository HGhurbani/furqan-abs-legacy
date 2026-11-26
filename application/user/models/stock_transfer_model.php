<?php
class Stock_transfer_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/*----------------------Stock Transfer Details-----------------*/
	
	function getAllStockTransfer()
	{		
		//active groups
		$adminGroupRow          = array();
		$sqlStocktransferQuery  = "SELECT * FROM {PRE}StockTransfer ORDER BY nSTId DESC
								    LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlStocktransferQuery;	
		$adminGroupList 		= $this->db->query($sqlStocktransferQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountStockTransfer()
	{		
		$tabCount = $this->db->query("SELECT * FROM {PRE}StockTransfer ORDER BY nSTId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getCorresStockTransfer()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}StockTransfer 
										WHERE nSTId ='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertStockTransfer()
	{
		$data   = array('nLocation' => $this->input->post('ddlLocation'),'dDate' => $this->input->post('txtDate'),
						'vDocNumber' => $this->input->post('txtDocNo'),'vYearref' => $this->input->post('txtYearRef'),
						'vIssuedBy' => $this->input->post('txtIssued'),'vRemarks' => $this->input->post('txtRemarks'),
						'vToLocation' => $this->input->post('txtToLoc'),'vCustomer' => $this->input->post('txtCustomer'),
						'vIssuedTo' => $this->input->post('txtIssuedTo'),'vCode' => $this->input->post('txtCode'),
						'vStart' => $this->input->post('txtStart'),'vEnd' => $this->input->post('txtEnd'),
						'nQuantity' => $this->input->post('txtStockQty'),'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}StockTransfer', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("stock_transfer/editStocktransfer/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("stock_transfer/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end class
	
	function updateStockTransfer()
	{
		$data   = array('nLocation' => $this->input->post('ddlLocation'),'dDate' => $this->input->post('txtDate'),
						'vDocNumber' => $this->input->post('txtDocNo'),'vYearref' => $this->input->post('txtYearRef'),
						'vIssuedBy' => $this->input->post('txtIssued'),'vRemarks' => $this->input->post('txtRemarks'),
						'vToLocation' => $this->input->post('txtToLoc'),'vCustomer' => $this->input->post('txtCustomer'),
						'vIssuedTo' => $this->input->post('txtIssuedTo'),'vCode' => $this->input->post('txtCode'),
						'vStart' => $this->input->post('txtStart'),'vEnd' => $this->input->post('txtEnd'),
						'nQuantity' => $this->input->post('txtStockQty'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nSTId',$this->input->post('nId'));
		$this->db->update('{PRE}StockTransfer', $data); 

		$msgVal = 'edit_suc';
		redirect(site_url("stock_transfer/showlist/".$this->uri->segment(3).'/'.$msgVal));	
	}//end function

	function deleteStockEntry()
	{
		$this->db->delete('{PRE}StockTransfer', array('nSTId' => $this->uri->segment(4)));
		redirect(site_url("stock_transfer/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteMultipleStockEntry()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nSTId',$val);
					$this->db->update('{PRE}StockTransfer', $data); 
				}//end foreach
				redirect(site_url("stock_transfer/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nSTId',$val);
					$this->db->update('{PRE}StockTransfer', $data); 
				}//end foreach
				redirect(site_url("stock_transfer/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}StockTransfer', array('nSTId' => $val));
				}//end foreach
				redirect(site_url("stock_transfer/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function
	
	function getActiveLocations()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' 
											ORDER BY nPosition DESC");
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
	
	//fetching single details
	function showDetails($stocktransferid)
	{
		$viewAccessRow   = array();
		
		$viewAccessList  = $this->db->query("SELECT * FROM {PRE}StockTransfer 
											 WHERE nSTId='".$stocktransferid."' ORDER BY nSTId DESC");
		//single fetch
		$commVal  = $viewAccessList->row();	
		$content .= '<table class="reportForm">
						<tr>
							<td colspan="3"><strong>'.STOCKENTRYDETAILS.'</strong></td>
						</tr>
						<tr>
							<td><strong>'.LOCATIONNAME.'&nbsp;:&nbsp;</strong>'.$this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$commVal->nLocation."' ").'</td>
							<td colspan="2"><strong>'.CUSTDATE.'&nbsp;:&nbsp;</strong>'.$commVal->dDate.'</td>
						</tr>
						<tr>
							<td><strong>'.YEARREF.'&nbsp;:&nbsp;</strong>'.$commVal->vYearref.'</td>
							<td colspan="2"><strong>'.DOCNUMBER.'&nbsp;:&nbsp;</strong>'.$commVal->vDocNumber.'</td>
						</tr>
						<tr>
							<td><strong>'.ISSUEDBY.'&nbsp;:&nbsp;</strong>'.$commVal->vIssuedBy.'</td>
							<td colspan="2"><strong>'.REMARKS.'&nbsp;:&nbsp;</strong>'.$commVal->vRemarks.'</td>
						</tr>
						<tr>
							<td colspan="3"><strong>'.TRANSFERDETAILS.'</strong></td>
						</tr>
						<tr>
							<td><strong>'.TOLOCATION.'&nbsp;:&nbsp;</strong>'.$commVal->vToLocation.'</td>
							<td colspan="2"><strong>'.CUSTNAME.'&nbsp;:&nbsp;</strong>'.$commVal->vCustomer.'</td>
						</tr>
						<tr>
							<td><strong>'.ISSUEDDTO.'&nbsp;:&nbsp;</strong>'.$commVal->vIssuedTo.'</td>
							<td colspan="2"><strong>'.CODE.'&nbsp;:&nbsp;</strong>'.$commVal->vCode.'</td>
						</tr>
						<tr>
							<td><strong>'.START.'&nbsp;:&nbsp;</strong>'.$commVal->vStart.'</td>
							<td colspan="2"><strong>'.END.'&nbsp;:&nbsp;</strong>'.$commVal->vEnd.'</td>
						</tr>
						<tr>
							<td colspan="3"><strong>'.STOCKQTY.'&nbsp;:&nbsp;</strong>'.$commVal->nQuantity.'</td>
						</tr>';
						
		$content .= '</table>';
	    return($content);
	}//end function
 
	function getFilterList($extraCheck)
	{
		$adminGroupRow                = array();
		$sqlStockTransferFilterQuery  = "SELECT * FROM {PRE}StockTransfer ".$extraCheck." ORDER BY nSTId DESC
								         LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlStockTransferFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlStockTransferFilterQuery);
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountfilterList($extraCheck)
	{
		$tabCount = $this->db->query("SELECT * FROM {PRE}StockTransfer ".$extraCheck." ORDER BY nSTId DESC");
		return ($tabCount->num_rows());
	}//end function
	
}//end class
?>