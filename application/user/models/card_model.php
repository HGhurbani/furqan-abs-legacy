<?php
class Card_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getCardType(){
      $cardTypeArray = array("C" => "CASH","B" => "BANK" ,"CR" => "Credit");
      return ($cardTypeArray);
    }//end function

	function getCard($extraCheck)
	{		
		//active groups
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Card ORDER BY vName
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
	
	function getCorresCard()
	{
		$fetchTitle 	  = $this->db->query("SELECT * FROM {PRE}Card WHERE nCId='".$this->catId."'");
		$showVal	      = $fetchTitle->row();
		return $showVal;
	}//end function 
	
	function insertCard()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtName'),'vActive' => $this->input->post('chkActive'),
            'vCardType' => $this->input->post('ddlCardType'),
						'vCode' => $this->input->post('txtCode'), 'nCommission' => $this->input->post('txtCommission'),
						'nCardAc' => $this->input->post('ddlCardAc'), 'vSubAc' => $this->input->post('txtSubCode'), 
						'vSubName' => $this->input->post('txtSubName'),'nCommisionAc' => $this->input->post('ddlCommision') , 
						'vSubAc2' => $this->input->post('txtSubCode2'), 'vSubName2' => $this->input->post('txtSubName2'));
		$this->db->insert('{PRE}Card', $data); 
		$msgVal = 'add_suc';
		
		if($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
			redirect(site_url("card/editcard/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));	
		}//end if
		if($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
			redirect(site_url("card/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCardAc'));		
		}//end if
	}//end function
	
	function updateCard()
	{
		$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vName' => $this->input->post('txtName'),'vActive' => $this->input->post('chkActive'),
            'vCardType' => $this->input->post('ddlCardType'),
						'vCode' => $this->input->post('txtCode'), 'nCommission' => $this->input->post('txtCommission'),
						'nCardAc' => $this->input->post('ddlCardAc'), 'vSubAc' => $this->input->post('txtSubCode'), 
						'vSubName' => $this->input->post('txtSubName'),'nCommisionAc' => $this->input->post('ddlCommision') , 
						'vSubAc2' => $this->input->post('txtSubCode2'), 'vSubName2' => $this->input->post('txtSubName2'));
		$this->db->where('nCId',$this->input->post('nId'));
		$this->db->update('{PRE}Card', $data); 
		$msgVal = 'edit_suc';
		redirect(site_url("card/showlist/".$this->uri->segment(3).'/'.$msgVal).'/'.$this->input->post('ddlCardAc'));	
	}//end function
	
	function deleteCard()
	{
		$this->db->delete('{PRE}Card', array('nCId' => $this->uri->segment(4))); 
		
		redirect(site_url("card/showlist/".$this->uri->segment(3).'/del_suc/'.$countryId));	
	}//end function 
	
	function deleteCardMultiple()
	{
		switch($this->input->post('ddlAction'))
		{
			case "yes":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '1');
					$this->db->where('nCId',$val);
					$this->db->update('{PRE}Card', $data); 
				}//end foreach
				redirect(site_url("card/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;
			
			case "no":
				foreach($this->input->post('del') as $val)
				{
					//update into db
					$data 	= array('nUserId' => $this->session->userdata('AbsAdmin'),'vActive' => '0');
					$this->db->where('nCId',$val);
					$this->db->update('{PRE}Card', $data); 
				}//end foreach
				redirect(site_url("card/showlist/".$this->input->post('nCId').'/edit_suc'));	
			break;

			case "delete":
				foreach($this->input->post('del') as $val)
				{
					$this->db->delete('{PRE}Card', array('nCId' => $val)); 
				}//end foreach
				redirect(site_url("card/showlist/".$this->input->post('nCId').'/del_suc/'.$countryId));	
			break;
		}//end switch
	}//end function 

	function getBankDocuments($cardType)
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
											WHERE ah.vActive = '1' 
										  AND ahc.vSubSale = '". $cardType . "' ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
//			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 
	
	function getCommDocuments()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
											FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah 
										  ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId 
											WHERE ah.vActive = '1' 
										  AND ahc.vSubSale = 'CM' ORDER BY nId DESC");

		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->ahaId] = $row->vACode.' - '.$row->vTitle;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 

	function getBankSubaccounts()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,o.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca 
										 LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls 
										    ahc ON ahc.nActHId = aca.nId LEFT JOIN {PRE}Other o ON ahc.nId = o.nFId
										 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'o' AND ahc.vSubSale = 'B'
										    ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nOId] = $row->vCode.' - '.$row->vName;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 
	
	function getCommSubaccounts()
	{
		$typeGroupRow = array();
		$typeGroup    = $this->db->query("SELECT aca.*,o.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType 
											FROM {PRE}AccountHeadAccounts aca 
										 LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls 
										    ahc ON ahc.nActHId = aca.nId LEFT JOIN {PRE}Other o ON ahc.nId = o.nFId
										 WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'o' AND ahc.vSubSale = 'CM'
										    ORDER BY nId DESC");
		if ($typeGroup->num_rows() > 0)
		{
			$typeGroupRow[0] = SELECT;
			foreach ($typeGroup->result() as $key=>$row)
			{
				$typeGroupRow[$row->nOId] = $row->vCode.' - '.$row->vName;
			}//end for each
		}//end if
		else
		{
			$typeGroupRow[0] = NOVALUE;
		}//end else

		return ($typeGroupRow);
	}//end function 
	
	function getFilterCard($extraCheck)
	{
		$adminGroupRow = array();
		
		$adminGroupList = $this->db->query("SELECT * FROM {PRE}Card " .$extraCheck."
											 ORDER BY vName
											LIMIT ".$this->common_model->_offset." , ".$this->common_model->_limit);
		
		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end foreach
		}//end if
		return ($adminGroupRow);
	}//end function

    function showDetails($modid)
    {
        $viewAccessRow   = array();
        $viewAccessList  = $this->db->query("SELECT * FROM {PRE}Card WHERE nCId = '".$modid."' AND vActive='1' ORDER BY nCId DESC");

        foreach ($viewAccessList->result() as $key=>$row)
        {
            $content = '<table class="reportForm">
                <tr>
                    <td><label><b>'.CARDCODE.' :</b></td>
                    <td>'.$row->vCode.'</label></td>
                    <td><label><b>'.CARDNAME.' :</b></td>
                    <td>'.$row->vName.'</label></td>
                    <td><label><b>'.COMMISSION.' :</b></td>
                    <td>'.$row->nCommission.'</label></td>
                    <td><label><b>'.BANKAC.' :</b></td>
                </tr>
                <tr>
                    <td>'.$row->nCardAc.'</label></td>
                    <td><label><b>'.SUBAC.' :</b></td>
                    <td>'.$row->vSubAc.'&nbsp;'.$row->vSubName.'</label></td>
                    <td><label><b>'.COMMISSIONAC.' :</b></td>
                    <td>'.$row->nCommisionAc.'</label></td>
                    <td><label><b>'.SUBAC.' :</b></td>
                    <td>'.$row->vSubAc2.'&nbsp;'.$row->vSubName2.'</label></td>
                </tr>
            </table>';
        }//end foreach
        return($content);
    }//end function

}//end class
?>
