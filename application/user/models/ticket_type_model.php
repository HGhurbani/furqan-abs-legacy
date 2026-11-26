<?php
class Ticket_type_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getAllTicketType()
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}TicketType ORDER BY nTTId DESC
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
	
	function insertTicketType()
	{
		$data 	= array('vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}TicketType', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("ticket_type/editTicketType/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("ticket_type/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function getCorresTicketType()
	{
		$fetchTitle = $this->db->query("SELECT * FROM {PRE}TicketType WHERE nTTId='".$this->catId."'");
		$showVal	= $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateTicketType()
	{
		$data 	= array('vTitle' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nTTId',$this->input->post('nTTId'));
		$this->db->update('{PRE}TicketType', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("ticket_type/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteTickettype()
	{
		$this->db->delete('{PRE}TicketType', array('nTTId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}SalesClosingDetails', array('nType' => $this->uri->segment(4))); 
		redirect(site_url("ticket_type/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllTickettype()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nTTId',$val);
					$this->db->update('{PRE}TicketType', $data); 
				}//end foreach
				redirect(site_url("ticket_type/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nTTId',$val);
					$this->db->update('{PRE}TicketType', $data); 
				}//end foreach
				redirect(site_url("ticket_type/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}TicketType', array('nTTId' => $val)); 
					$this->db->delete('{PRE}SalesClosingDetails', array('nType' => $val)); 
				}//end foreach
				redirect(site_url("ticket_type/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterTickettypeName($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}TicketType ".$extraCheck."
											 ORDER BY vTitle ASC
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