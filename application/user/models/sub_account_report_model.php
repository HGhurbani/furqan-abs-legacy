<?php
class Sub_account_report_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function
	
	function getActiveDivision()
	{
        $locGroup    = $this->db->query("SELECT * FROM {PRE}Division WHERE vActive = '1'  AND nId IN ".$this->session->userdata('accessDiv') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {
            foreach ($locGroup->result() as $key => $row)
            {
                $locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vDivCode).')';
            }//end for each
        }//end if
        else
        {
            $locGroupRow[0] = NOVALUE;
        }//end else
        return ($locGroupRow);
	}//end function

    function getActiveLocation()
	{
		$sessionLoc  = $this->db->query("SELECT vLocation FROM {PRE}Members WHERE nId = '".$this->session->userdata('AbsAdmin')."'");
		$locName     = $sessionLoc->row();


		if($sessionLoc->num_rows() == 0)
			$locId = $this->common_model->getLocLogged();
		else
			$locId = $locName->vLocation;
			
		$locGroup    = $this->db->query("SELECT * FROM {PRE}Location WHERE vActive = '1' AND nId = '".$locId."'
											ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			$locGroupRow[0] = SELECT;
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return ($locGroupRow);
	}//end function
	
	function filterLocation($division)
	{
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			foreach ($locGroup->result() as $key => $row)
			{
				$locGroupRow[$row->nId] = stripslashes($row->vTitle).' ('.stripslashes($row->vCode).')';
			}//end for each
		}//end if
		else
		{
			$locGroupRow[0] = NOVALUE;
		}//end else
		return($locGroupRow);
	}//end function

	function mainTypeArray()
	{
		$mainTypeArrayList = array("All" => "All","C" => "Customer","V" => "Vendor","A" => "Agent","E" => "Employee");
		return ($mainTypeArrayList);
	}//end function

    function getActiveAcName()
    {
        $typeGroupRow = array();
        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca
											LEFT JOIN {PRE}AccountHead ah
											ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId=aca.nId WHERE ah.vActive = '1'
											AND (ahc.vSubLedgerType = 'C' OR ahc.vSubLedgerType = 'V' OR ahc.vSubLedgerType = 'E' OR ahc.vSubLedgerType = 'A')
											ORDER BY nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            $typeGroupRow[0] = SELECT;
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $typeGroupRow[0] = NOVALUE;
        }//end else

        return ($typeGroupRow);
    }//end function

    function getCityName($country)
    {
        $adminGroupList = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId='".$country."'");

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getSubAccount($type,$extraCheck)
    {

        switch($type)
        {
            case "C":
                $adminGroupListQuery  = "SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'C' as tblName,nCId as tableId
                                         FROM {PRE}CustomerMaster WHERE vActive = '1' ".$extraCheck." ORDER BY nCId DESC";
            break;

            case "V":
                $adminGroupListQuery  = "SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'V' as tblName,nVId as tableId
                                         FROM {PRE}VendorMaster WHERE vActive = '1' ".$extraCheck." ORDER BY nVId DESC";
            break;

            case "A":
                $adminGroupListQuery  = "SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'A' as tblName,nAId as tableId
                                         FROM {PRE}Agent WHERE vActive = '1' ".$extraCheck." ORDER BY nAId DESC";
            break;

            case "E":
                $adminGroupListQuery  = "SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'E' as tblName,nEId as tableId
                                         FROM {PRE}EmployeeMaster WHERE vActive = '1' ".$extraCheck." ORDER BY nEId DESC";
            break;

            default:
                $adminGroupListQuery  = "(SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'C' as tblName,nCId as tableId
                                         FROM {PRE}CustomerMaster WHERE vActive = '1' ".$extraCheck.") UNION ALL
                                         (SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'V' as tblName,nVId as tableId
                                         FROM {PRE}VendorMaster WHERE vActive = '1' ".$extraCheck.") UNION ALL
                                         (SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'A' as tblName,nAId as tableId
                                         FROM {PRE}Agent WHERE vActive = '1' ".$extraCheck.") UNION ALL
                                         (SELECT vCode,vName,vPhone,vCreditDays,vAmount,nCurrency,'E' as tblName,nEId as tableId
                                         FROM {PRE}EmployeeMaster WHERE vActive = '1' ".$extraCheck.")";
            break;
        }//end switch

        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getAirline()
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}Airline WHERE vActive='1' ORDER BY nArId DESC";
        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getHotel()
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}Hotel WHERE vActive='1' ORDER BY nHId DESC";
        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getCountry()
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}Country WHERE nParentId='0' AND vActive='1' ORDER BY nId DESC";
        session_start();
        $_SESSION['sess_query'] = $adminGroupListQuery;
        $adminGroupList 		= $this->db->query($adminGroupListQuery);
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
