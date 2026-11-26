<?php
class Transfer_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getTransfer($extraCheck)
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Transfer ORDER BY vName
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
	
	function getCorresTransfer()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Transfer WHERE nTId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertTransfer()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
						'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Transfer', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("transfer_type/editTransfertype/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("transfer_type/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function updateTransfer()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vCode' => $this->input->post('txtCode'),'vName' => $this->input->post('txtName'),
						'vActive' => $this->input->post('chkActive'));
		$this->db->where('nTId',$this->input->post('nId'));
		$this->db->update('{PRE}Transfer', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("transfer_type/showlist/".$this->uri->segment(3).'/'.$msgVal));	
	}//end function
	
	function deleteTransfer()
	{
		$this->db->delete('{PRE}Transfer', array('nCId' => $this->uri->segment(4))); 
		
		redirect(site_url("transfer_type/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function 
	
	function deleteTransferMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nTId',$val);
					$this->db->update('{PRE}Transfer', $data); 
				}//end foreach
				redirect(site_url("transfer_type/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nTId',$val);
					$this->db->update('{PRE}Transfer', $data); 
				}//end foreach
				redirect(site_url("transfer_type/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Transfer', array('nCId' => $val)); 
				}//end foreach
				redirect(site_url("transfer_type/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterTransfer($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Transfer ".$extraCheck."
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