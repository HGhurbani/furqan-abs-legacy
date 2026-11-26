<?php
class Chart_report_model extends CI_Model {

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

    function getAccountGroup()
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}FinanceModule WHERE nParent ='0' ORDER BY vTitle";
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

    function getAccountGroupVal($finance)
    {
        $adminGroupList = $this->db->query("SELECT ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup FROM {PRE}FinanceModule fm
                                         LEFT JOIN {PRE}AccountHead ah ON fm.nId = ah.nAGroup
                                         WHERE ah.vActive = '1' AND fm.nId='".$finance."' ORDER BY ah.vHCode ASC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getAccountHeadVal($id)
    {
        $adminGroupList = $this->db->query("SELECT vACode,vTitle FROM {PRE}AccountHeadAccounts WHERE nHAId ='".$id."' ORDER BY vACode ASC");
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function getAccountHead($extraCheck)
    {
        $adminGroupListQuery  = "SELECT aha.vACode,aha.vTitle,ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup,ah.vActive,
                                    fm.vTitle as ahead, fm.nId as aid  FROM {PRE}FinanceModule fm
                                 LEFT JOIN {PRE}AccountHead ah ON fm.nId = ah.nAGroup
                                    LEFT JOIN {PRE}AccountHeadAccounts aha ON ah.nAId = aha.nHAId
                                 WHERE ah.vActive = '1' ".$extraCheck." ORDER BY aha.nId DESC";
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

    function getAccountHeadGroup()
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}FinanceModule WHERE nParent ='0' ORDER BY vTitle";
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

    function getAccountHeadGroupVal($finance)
    {
        $extraCheck = '';
        if ($this->session->userdata('ddlDivision')!='') {
            $extraCheck .= " AND aha.nDivision='".$this->session->userdata('ddlDivision')."'";
        }//end if

        if ($this->session->userdata('ddlLocation')!='') {
            $extraCheck .= " AND aha.nLocation='".$this->session->userdata('ddlLocation')."'";
        }//end if

        $adminGroupList = $this->db->query("SELECT aha.vACode,aha.vTitle,ah.vHCode,ah.vAcname,ah.nAId,ah.nAGroup,ah.vActive,
                                        fm.vTitle as ahead, fm.nId as aid  FROM {PRE}FinanceModule fm
                                     LEFT JOIN {PRE}AccountHead ah ON fm.nId = ah.nAGroup
                                        LEFT JOIN {PRE}AccountHeadAccounts aha ON ah.nAId = aha.nHAId
                                     WHERE ah.vActive = '1' AND fm.nId='".$finance."' ".$extraCheck." ORDER BY aha.nId DESC");
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
