<?php
class Gds_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getAllGds()
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC
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
	
	function insertGds()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vActive' => $this->input->post('chkActive'));
						
		$this->db->insert('{PRE}Gds', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("gds/editGds/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("gds/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if

	}//end function
	
	function getCorresGds()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Gds WHERE nGId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateGds()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtTitle'),'vActive' => $this->input->post('chkActive'));
		$this->db->where('nGId',$this->input->post('nGId'));
		$this->db->update('{PRE}Gds', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("gds/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteGds()
	{
		$this->db->delete('{PRE}Gds', array('nGId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}Hotel', array('nService' => $this->uri->segment(4))); 
		redirect(site_url("gds/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllGds()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nGId',$val);
					$this->db->update('{PRE}Gds', $data); 
				}//end foreach
				redirect(site_url("gds/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nGId',$val);
					$this->db->update('{PRE}Gds', $data); 
				}//end foreach
				redirect(site_url("gds/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Gds', array('nGId' => $val)); 
					$this->db->delete('{PRE}Hotel', array('nService' => $val)); 
				}//end foreach
				redirect(site_url("gds/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterGdsName($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Gds ".$extraCheck."
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