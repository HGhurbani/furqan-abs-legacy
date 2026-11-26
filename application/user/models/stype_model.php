<?php
class Stype_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getAllStype()
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}ServiceType ORDER BY nSId DESC
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
	
	function insertStype()
	{
		$data 	= array('vName' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vActive' => $this->input->post('chkActive'));
						
		$this->db->insert('{PRE}ServiceType', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("stype/editStype/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("stype/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if

	}//end function
	
	function getCorresStype()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}ServiceType WHERE nSId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateStype()
	{
		$data 	= array('vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nSId',$this->input->post('nSId'));
		$this->db->update('{PRE}ServiceType', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("stype/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteStype()
	{
		$this->db->delete('{PRE}ServiceType', array('nSId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}Hotel', array('nService' => $this->uri->segment(4))); 
		redirect(site_url("stype/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllStype()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nSId',$val);
					$this->db->update('{PRE}ServiceType', $data); 
				}//end foreach
				redirect(site_url("stype/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nSId',$val);
					$this->db->update('{PRE}ServiceType', $data); 
				}//end foreach
				redirect(site_url("stype/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}ServiceType', array('nSId' => $val)); 
					$this->db->delete('{PRE}Hotel', array('nService' => $val)); 
				}//end foreach
				redirect(site_url("stype/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterStypeName($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}ServiceType ".$extraCheck."
											 ORDER BY vName ASC
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