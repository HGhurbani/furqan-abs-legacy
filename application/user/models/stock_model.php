<?php
class Stock_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getStock($extraCheck)
	{		
		//active groups
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Stock ORDER BY vName
													LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
			
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
			
		return ($adminGroupRow);
	}//end function
	
	function getCorresStock()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Stock WHERE nStkId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertStock()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtName'),'vActive' => $this->input->post('chkActive'),
						'vCode' => $this->input->post('txtCode'), 'nAirline' => $this->input->post('ddlAirline'),
						'nGDS' => $this->input->post('ddlGds'),'nTransaction' => $this->input->post('ddlTrans'),
                        'vPrefix' => $this->input->post('txtPrefix'));
		$this->db->insert('{PRE}Stock', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("stock/editstock/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("stock/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlAirline'));		
		}//end if
	}//end function
	
	function updateStock()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtName'),'vActive' => $this->input->post('chkActive'),
						'vCode' => $this->input->post('txtCode'), 'nAirline' => $this->input->post('ddlAirline'),
						'nGDS' => $this->input->post('ddlGds'), 'nTransaction' => $this->input->post('ddlTrans'),
                        'vPrefix' => $this->input->post('txtPrefix'));
		$this->db->where('nStkId',$this->input->post('nId'));
		$this->db->update('{PRE}Stock', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("stock/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlAirline'));	
	}//end function
	
	function deleteStock()
	{
		$this->db->delete('{PRE}Stock', array('nStkId' => $this->uri->segment(4))); 
		
		redirect(site_url("stock/showlist/".$this->uri->segment(3).'/del_suc/'.$countryId));	
	}//end function 
	
	function deleteStockMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nStkId',$val);
					$this->db->update('{PRE}Stock', $data); 
				}//end foreach
				redirect(site_url("stock/showlist/".$this->input->post('nStkId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nStkId',$val);
					$this->db->update('{PRE}Stock', $data); 
				}//end foreach
				redirect(site_url("stock/showlist/".$this->input->post('nStkId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Stock', array('nStkId' => $val)); 
				}//end foreach
				redirect(site_url("stock/showlist/".$this->input->post('nStkId').'/del_suc/'.$countryId));	
			break;
		}//end switch
	}//end function 
	
	function getTransactions()
	{
		$staffGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' AND (nId IN ('5','6','7')) ");
		$staffGroupRow = array();
		
		if ($staffGroup->num_rows() > 0)
		{
			$staffGroupRow[0] = SELECT;
			foreach ($staffGroup->result() as $key=>$row)
			{
				$staffGroupRow[$row->nId] = stripslashes($row->vTitle);
			}//end for each
		} //end if
		else
		{
			$staffGroupRow[0] = NOVALUE;
		}
			
		return ($staffGroupRow);
	}//end function 
	
	function getGds()
	{
		$staffGroup = $this->db->query("SELECT nGId,vName FROM {PRE}Gds WHERE vActive = '1'");
		$staffGroupRow = array();
		
		if ($staffGroup->num_rows() > 0)
		{
			$staffGroupRow[0] = SELECT;
			foreach ($staffGroup->result() as $key=>$row)
			{
				$staffGroupRow[$row->nGId] = stripslashes($row->vName);
			}//end for each
		} //end if
		else
		{
			$staffGroupRow[0] = NOVALUE;
		}
			
		return ($staffGroupRow);
	}//end function 

	function getAirline()
	{
		$staffGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline WHERE vActive = '1'");
		$staffGroupRow = array();
		
		if ($staffGroup->num_rows() > 0)
		{
			$staffGroupRow[0] = SELECT;
			foreach ($staffGroup->result() as $key=>$row)
			{
				$staffGroupRow[$row->nArId] = stripslashes($row->vTitle.' ('.$row->vAirlineNo.')');
			}//end for each
		} //end if
		else
		{
			$staffGroupRow[0] = NOVALUE;
		}
			
		return ($staffGroupRow);
	}//end function 
	
	function getFilterStock($extraCheck)
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Stock " .$extraCheck."
											 ORDER BY vName
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
}//end class
?>
