<?php
class Service_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
	function getServices($extraCheck)
	{		
		//active groups
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Services 
											".$extraCheck." ORDER BY vTitle
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
	
	function getCorresServices()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Services WHERE nServId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertServices()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vCode' => $this->input->post('txtCode'),'vTitle' => $this->input->post('txtTitle'),
						'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Services', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("services/editServices/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("services/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlService'));		
		}//end if
	}//end function
	
	function updateServices()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vCode' => $this->input->post('txtCode'),'vTitle' => $this->input->post('txtTitle'),
						'vActive' => $this->input->post('chkActive'));
		$this->db->where('nServId',$this->input->post('nId'));
		$this->db->update('{PRE}Services', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("services/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlService'));	
	}//end function
	
	function deleteServices()
	{
		$servId = $this->common_model->get_field_name('Services','nService'," WHERE nServId='".$this->uri->segment(3)."'");
		$this->db->delete('{PRE}Services', array('nServId' => $this->uri->segment(4))); 
		
		redirect(site_url("services/showlist/".$this->uri->segment(3).'/del_suc/'.$servId));	
	}//end function 
	
	function deleteServiceMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nServId',$val);
					$this->db->update('{PRE}Services', $data); 
				}//end foreach
				redirect(site_url("services/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nServId',$val);
					$this->db->update('{PRE}Services', $data); 
				}//end foreach
				redirect(site_url("services/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$servId = $this->common_model->get_field_name('Services','nService'," WHERE nServId='".$this->uri->segment(3)."'");
					$this->db->delete('{PRE}Services', array('nHId' => $val)); 
				}//end foreach
				redirect(site_url("services/showlist/".$this->input->post('nCId').'/del_suc/'.$servId));	
			break;
		}//end switch
	}//end function 
	
	function getFilterServices($extraCheck)
	{
		$adminGroupRow  = array();
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Services ".$extraCheck."
											 ORDER BY vTitle
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