<?php
class Document_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
/********************Transaction Document Setup********************************/

	function getAllDocument()
	{
		$adminGroupRow          = array();
		$sqlDocumentQuery       = "SELECT d.*,dd.* FROM {PRE}Document d LEFT JOIN {PRE}DocumentDetails dd
									ON d.nDId = dd.nDocId GROUP BY dd.nDocId ORDER BY d.nDId DESC
								   LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlDocumentQuery;	
		$adminGroupList 		= $this->db->query($sqlDocumentQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		}//end if
		return ($adminGroupRow);
	}//end function 
	
	function getActiveTransactions()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT * FROM {PRE}Transactions WHERE vActive = '1' 
											ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[] = $row;
			}//end for each
		}//end if
		return ($typeGroupRow);
	}//end function 
	
	function insertDocument()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vYearref' => $this->input->post('txtYearref'),'vLocation' => @join(",",$this->input->post('ddlLocation')),
						'vActive' => $this->input->post('chkActive'));
		$this->db->insert('{PRE}Document', $data);
		$id	    = $this->db->insert_id();	
		
		foreach($this->input->post('ddlTrans') as $transid)
		{
			$prefix				= 'txtPrefix'.$transid;
			$this->txtPrefix 	= $this->input->post($prefix);
			
			$minDigit			= 'txtMindigit'.$transid;
			$this->txtMindigit 	= $this->input->post($minDigit);
			
			$number 			= 'txtNumber'.$transid;
			$this->txtNumber    = $this->input->post($number);
			
			$sampleDoc			= 'txtSampledoc'.$transid;
			$this->txtSampledoc = $this->input->post($sampleDoc);

		   //insert into Employee Details table
			$data 	= array('nDocId' => $id ,'nTransaction' => $transid,'vPrefix' => $this->txtPrefix,
							'vMindigit' => $this->txtMindigit,'nNumber' => $this->txtNumber,
							'vSampledoc' => $this->txtSampledoc,'vActive' => $this->input->post('chkActive'));
			$this->db->insert('{PRE}DocumentDetails', $data);
		}//end foreach
 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("document_setup/editDocument/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("document_setup/showlist/".$this->uri->segment(3).'/'.$msgVal));		
		}//end if
	}//end function
	
	//get selected transactions
	function getSelectedTrans()
	{
		$selTransGroupRow   = array();
		$selTransGroup      = $this->db->query("SELECT nTransaction FROM {PRE}DocumentDetails WHERE nDocId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nTransaction DESC");
		if ($selTransGroup->num_rows() > 0)
		{
			foreach ($selTransGroup->result() as $key=>$row)
			{
				$selTransGroupRow[] = $row->nTransaction;
			}//end for each
		}//end if
		return ($selTransGroupRow);
	}//end function 

	//get selected document prefix
	function getSelectedPrefix()
	{
		$selPrefixGroupRow  = array();
		$selPrefixGroup     = $this->db->query("SELECT nTransaction,vPrefix FROM {PRE}DocumentDetails WHERE nDocId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nTransaction DESC");
		if ($selPrefixGroup->num_rows() > 0)
		{
			foreach ($selPrefixGroup->result() as $key=>$row)
			{
				$selPrefixGroupRow[$row->nTransaction] = $row->vPrefix;
			}//end for each
		}//end if
		return ($selPrefixGroupRow);
	}//end function
	
	//get selected document mindigit
	function getSelectedMindigit()
	{
		$selMindigitGroupRow = array();
		$selMindigitGroup    = $this->db->query("SELECT nTransaction,vMindigit FROM {PRE}DocumentDetails WHERE nDocId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nTransaction DESC");
		if ($selMindigitGroup->num_rows() > 0)
		{
			foreach ($selMindigitGroup->result() as $key=>$row)
			{
				$selMindigitGroupRow[$row->nTransaction] = $row->vMindigit;
			}//end for each
		}//end if
		return ($selMindigitGroupRow);
	}//end function
	
	//get selected document number
	function getSelectedNumber()
	{
		$selNumberGroupRow = array();
		$selNumberGroup    = $this->db->query("SELECT nTransaction,nNumber FROM {PRE}DocumentDetails WHERE nDocId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nTransaction DESC");
		if ($selNumberGroup->num_rows() > 0)
		{
			foreach ($selNumberGroup->result() as $key=>$row)
			{
				$selNumberGroupRow[$row->nTransaction] = $row->nNumber;
			}//end for each
		}//end if
		return ($selNumberGroupRow);
	}//end function
	
	//get selected document sample
	function getSelectedSampledoc()
	{
		$selSampledocGroupRow = array();
		$selSampledocGroup    = $this->db->query("SELECT nTransaction,vSampledoc FROM {PRE}DocumentDetails WHERE nDocId = '".$this->catId."' 
													AND vActive = '1' ORDER BY nTransaction DESC");
		if ($selSampledocGroup->num_rows() > 0)
		{
			foreach ($selSampledocGroup->result() as $key=>$row)
			{
				$selSampledocGroupRow[$row->nTransaction] = $row->vSampledoc;
			}//end for each
		}//end if
		return ($selSampledocGroupRow);
	}//end function 
 
	function getCorresDocument()
	{
		$fetchTitle 	  = $this->db->query("SELECT d.*,dd.* FROM {PRE}Document d LEFT JOIN {PRE}DocumentDetails dd
												ON d.nDId = dd.nDocId
											  WHERE d.nDId='".$this->catId."'");
											  //echo  $this->db->last_query(); die();
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function updateDocument()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vYearref' => $this->input->post('txtYearref'),'vLocation' => @join(",",$this->input->post('ddlLocation')),
						'vActive' => $this->input->post('chkActive'));
		$this->db->where('nDId',$this->input->post('nId'));
		$this->db->update('{PRE}Document', $data); 
		$id	        		 = $this->input->post('nId');
		
		foreach($this->input->post('ddlTrans') as $transid)
		{
			$prefix				= 'txtPrefix'.$transid;
			$this->txtPrefix 	= $this->input->post($prefix);
			
			$minDigit			= 'txtMindigit'.$transid;
			$this->txtMindigit 	= $this->input->post($minDigit);
			
			$number 			= 'txtNumber'.$transid;
			$this->txtNumber 	= $this->input->post($number);
			
			$sampleDoc			= 'txtSampledoc'.$transid;
			$this->txtSampledoc = $this->input->post($sampleDoc);

			$chkValU   = $this->common_model->get_field_name('DocumentDetails','nId', " WHERE nTransaction ='".$transid."' AND nDocId = '".$id."'");
			if(intval($chkValU) == 0)
			{
				$prefix				= 'txtPrefix'.$transid;
				$this->txtPrefix 	= $this->input->post($prefix);
				
				$minDigit			= 'txtMindigit'.$transid;
				$this->txtMindigit 	= $this->input->post($minDigit);
				
				$number 			= 'txtNumber'.$transid;
				$this->txtNumber 	= $this->input->post($number);
				
				$sampleDoc			= 'txtSampledoc'.$transid;
				$this->txtSampledoc = $this->input->post($sampleDoc);
				
				$data 	= array('nDocId' => $id ,'nTransaction' => $transid,'vPrefix' => $this->txtPrefix,
							    'vMindigit' => $this->txtMindigit,'nNumber' => $this->txtNumber,'vSampledoc' => $this->txtSampledoc,
								'vActive' => $this->input->post('chkActive'));
				$this->db->insert('{PRE}DocumentDetails', $data);
			}//end if
			else
			{
				$data 	= array('nDocId' => $id ,'nTransaction' => $transid,'vPrefix' => $this->txtPrefix,
							    'vMindigit' => $this->txtMindigit,'nNumber' => $this->txtNumber,'vSampledoc' => $this->txtSampledoc,
								'vActive' => $this->input->post('chkActive'));
				$this->db->where('nId',$chkValU);
				$this->db->update('{PRE}DocumentDetails', $data);
				//echo $this->db->last_query(); die();
			}//end else
			
		}//end foreach

		$msgVal = 'edit_suc';
		redirect(site_url("document_setup/showlist/".$this->uri->segment(3).'/'.$msgVal));					
	}//end function 
	
	function deleteDocument()
	{
		$this->db->delete('{PRE}Document', array('nDId' => $this->uri->segment(4))); 
		$this->db->delete('{PRE}DocumentDetails', array('nDocId' => $this->uri->segment(4))); 
		redirect(site_url("document_setup/showlist/".$this->uri->segment(3).'/del_suc'));	
	}//end function
	
	function deleteAllDocument()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '1');
					$this->db->where('nDId',$val);
					$this->db->update('{PRE}Document', $data); 
				}//end foreach
				redirect(site_url("document_setup/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('vActive' => '0');
					$this->db->where('nDId',$val);
					$this->db->update('{PRE}Document', $data); 
				}//end foreach
				redirect(site_url("document_setup/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Document', array('nDId' => $val)); 
					$this->db->delete('{PRE}DocumentDetails', array('nDocId' => $val)); 
				}//end foreach
				redirect(site_url("document_setup/showlist/".$this->input->post('nCId').'/del_suc'));	
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
	function showDetails($docId)
	{
		$viewAccessRow   = array();
		$viewAccessList  = $this->db->query("SELECT d.*,dd.*
												 FROM {PRE}Document d LEFT JOIN {PRE}DocumentDetails dd ON d.nDId=dd.nDocId
											 WHERE d.nDId ='".$docId."' ORDER BY d.nDId DESC");
		foreach ($viewAccessList->result() as $key=>$row)
		{
			$locId     = array_filter(@split(',',$this->common_model->get_field_name('Document','vLocation'," WHERE nDId='".$docId."'")));
			$locName   = $this->getLocationName($locId);

			$content .= '<table class="reportForm">
			<label><b>'.DOCDETAILS.'</strong></b>
			<tr>
				<td><strong>'.LOCATIONNAME.'</strong></td>
				<td>'.$locName.'</td>
		   </tr>
			<tr>
				<td><strong>'.TRANSNAME.'</strong></td>
				<td>'.$this->common_model->get_field_name('Transactions','vTitle'," WHERE nId='".$row->nTransaction."'").'</td>
			</tr>
			<tr>
				<td><strong>'.TRANSPREFIX.'</strong></td>
				<td>'.$row->vPrefix.'</td>
			</tr>
			<tr>
				<td><strong>'.DOCUMENTNUMBER.'</strong></td>
				<td>'.$row->nNumber.'</td>
			</tr>
			<tr>
				<td><strong>'.TRANSMINDIGIT.'</strong></td>
				<td>'.$row->vMindigit.'</td>
			</tr>
			<tr>
				<td><strong>'.TRANSSAMPLEDOC.'</strong></td>
				<td>'.$row->vSampledoc.'</td>
			</tr>
		 </table>';
		}//end foreach
		return($content);
	}//end function
	
 	//get location name
	function getLocationName($locArray)
	{
		$locName         = array();
		//fetch location name
		foreach($locArray as $val)
		{
			$locName[]   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$val."'");	
		}//end foreach
		
		return(@join(",",$locName));
	}//end function

}//end class
?>