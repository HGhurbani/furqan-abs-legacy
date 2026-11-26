<?php
class Division_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************Company Division********************************/

	function getAllDivision()
	{
		$adminGroupRow  	    = array();
		$sqlDivisionQuery   	= "SELECT * FROM {PRE}Division 
									ORDER BY nPosition DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlDivisionQuery;	
		$adminGroupList 		= $this->db->query($sqlDivisionQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function 
	
	function insertDivision($dataEdit)
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
						'vDivCode' => mysql_real_escape_string($this->input->post('txtDivCode')),
						'nCurrency' => $this->input->post('ddlCurrency'),
						'vAddress' => mysql_real_escape_string($this->input->post('txtAddress')),
                        'vPhone' => $this->input->post('txtPhone'),
						'vMobile' => mysql_real_escape_string($this->input->post('txtMobile')),
                        'vEmail' => $this->input->post('txtEmail'),'vWeb' => mysql_real_escape_string($this->input->post('txtWeb')),
                        'vActive' => $this->input->post('chkActive'),'nPosition' => $this->common_model->maxPos('Division'));

        $dataEdit = array_merge((array)$data, (array)$dataEdit);
        $this->db->insert('{PRE}Division', $dataEdit);
        $msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("division/editDivision/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("division/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	function getCorresDivision()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Division WHERE nId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateDivision($dataEdit)
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vTitle' => mysql_real_escape_string($this->input->post('txtTitle')),
                        'vDivCode' => mysql_real_escape_string($this->input->post('txtDivCode')),
                        'nCurrency' => $this->input->post('ddlCurrency'),
						'vAddress' => mysql_real_escape_string($this->input->post('txtAddress')),
                        'vPhone' => $this->input->post('txtPhone'),
						'vMobile' => mysql_real_escape_string($this->input->post('txtMobile')),
                        'vEmail' => $this->input->post('txtEmail'),'vWeb' => mysql_real_escape_string($this->input->post('txtWeb')),
                        'vActive' => $this->input->post('chkActive'),'nPosition' => $this->common_model->maxPos('Division'));

        $dataEdit = array_merge((array)$data, (array)$dataEdit);
        $this->db->where('nId',$this->input->post('nId'));
		$this->db->update('{PRE}Division', $dataEdit);
		$msgVal = 'edit_suc';
		redirect(site_url("division/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteDivision()
	{
        $rowImg	= $this->common_model->get_field_name('Division','vImg'," WHERE nId ='".$this->uri->segment(4)."'");
        @unlink('./upload/division/'.$rowImg);

		$this->db->delete('{PRE}Division', array('nId' => $this->uri->segment(4))); 
		redirect(site_url("division/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllDivision()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Division', $data); 
				}//end foreach
				redirect(site_url("division/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nId',$val);
					$this->db->update('{PRE}Division', $data); 
				}//end foreach
				redirect(site_url("division/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
                    $rowImg	= $this->common_model->get_field_name('Division','vImg'," WHERE nId ='".$val."'");
                    @unlink('./upload/division/'.$rowImg);
					$this->db->delete('{PRE}Division', array('nId' => $val)); 
				}//end foreach
				redirect(site_url("division/showlist/".$this->input->post('nCId').'/del_suc'));	
			break;
		}//end switch
	}//end function 
	
	function getFilterDivision($extraCheck)
	{
		$adminGroupRow  		= array();
		$sqlDivisionFilterQuery = "SELECT * FROM {PRE}Division ".$extraCheck."
									ORDER BY nPosition DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlDivisionFilterQuery;	
		$adminGroupList 		= $this->db->query($sqlDivisionFilterQuery);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	function getActiveCurrency()
	{
		$typeGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Currency WHERE vActive = '1' 
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
    function showDetails($divId)
    {
        $adminGroupList     = $this->db->query("SELECT * FROM {PRE}Division WHERE nId = '".$divId."'");
        $showVal	        = $adminGroupList->row();
        $content            = '<table class="reportForm">
			<tr>
				<td><strong>'.DIVISIONNAME.' :</strong></td>
				<td>'.$showVal->vTitle.'</td>
				<td><strong>'.CODE.' :</strong></td>
				<td>'.$showVal->vDivCode.'</td>
				<td><strong>'.CURRNAME.' :</strong></td>
				<td>'.$this->common_model->get_field_name('Currency','vTitle'," WHERE nId = '".$showVal->nCurrency."'").'</td>
			</tr>
			<tr>
				<td><strong>'.LOCATIONADDR.' :</strong></td>
				<td>'.$showVal->vAddress.'</td>
				<td><strong>'.LOCATIONPHONE.' :</strong></td>
				<td>'.$showVal->vPhone.'</td>
				<td><strong>'.MOBILE.' :</strong></td>
				<td>'.$showVal->vMobile.'</td>
			</tr>
			<tr>
				<td><strong>'.EMAIL.' :</strong></td>
				<td>'.$showVal->vEmail.'</td>
				<td><strong>'.WEB.' :</strong></td>
				<td>'.$showVal->vWeb.'</td>
				<td><strong>'.YEARDATE.' :</strong></td>
				<td>'.$showVal->dYearDate.'</td>
			</tr>
			<tr>
				<td><strong>'.YEARTO.' :</strong></td>
				<td>'.$showVal->dYearTo.'</td>
				<td><strong>'.IMAGE.' :</strong></td>
				<td colspan="3"><img src="'.base_url().'upload/division/'.$showVal->vImg.'" width="100px" height="100px"/></td>
			</tr>
		 </table>';

        return($content);
    }//end function
}//end class
?>