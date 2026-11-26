<?php
class Note_reprint_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
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
    function filterSubAccount($accGroup,$table)
    {
        if($accGroup>0):
            switch($table)
            {
                case "C" :
                    $accNameId       = $this->common_model->get_field_name('CustomerMaster','nFId'," WHERE nCId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                            FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                           ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                            LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                           WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' AND v.nFId = '".$accNameId."'
                            ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "V" :
                    $accNameId       = $this->common_model->get_field_name('VendorMaster','nFId'," WHERE nVId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                            FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                           ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                            LEFT JOIN {PRE}VendorMaster v ON v.nFId = ahc.nId
                           WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'V' AND v.nFId = '".$accNameId."'
                            ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "AG" :
                    $accNameId       = $this->common_model->get_field_name('Agent','nFId'," WHERE nAId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                            FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                           ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                            LEFT JOIN {PRE}Agent v ON v.nFId = ahc.nId
                           WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'A' AND v.nFId = '".$accNameId."'
                            ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "O" :
                    $accNameId       = $this->common_model->get_field_name('Other','nFId'," WHERE nOId = '".$accGroup."' ");
                    $accheadGroupRow = array();
                    $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                            FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                           ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                            LEFT JOIN {PRE}Other v ON v.nFId = ahc.nId
                           WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'O' AND v.nFId = '".$accNameId."'
                            ORDER BY nId DESC");
                    if ($accheadGroup->num_rows() > 0)
                    {
                        foreach ($accheadGroup->result() as $key=>$row)
                        {
                            $accheadGroupRow[$row->ahaId] = $row->vTitle;
                        }//end for each
                    }//end if
                    else
                    {
                        $accheadGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "A" :
                    $accheadGroupRow    = array();
                    $accheadGroupRow[0] = NOVALUE;
                    break;
            }//end switch
        else:
            $accheadGroupRow    = array();
            $accheadGroupRow[0] = NOVALUE;
        endif;
        return ($accheadGroupRow);
    }//end function

    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getActiveAcName()
    {
        $typeGroupRow    = array();
        $vendorGroupRow  = array();
        $AgentGroupRow   = array();
        $OtherGroupRow   = array();
        $accheadGroupRow = array();

        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY nCId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if

        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
        if ($vendorGroup->num_rows() > 0)
        {
            foreach ($vendorGroup->result() as $key=>$row)
            {
                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);

        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY nAId DESC");
        if ($AgentGroup->num_rows() > 0)
        {
            foreach ($AgentGroup->result() as $key=>$row)
            {
                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($AgentGroupRow,$resultArray);

        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
        if ($OtherGroup->num_rows() > 0)
        {
            foreach ($OtherGroup->result() as $key=>$row)
            {
                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;
            }//end for each
        }//end if
        $resultArray = array_merge($OtherGroupRow,$resultArray);

        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										  	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
												WHERE ah.vActive = '1' AND aca.nId
										  	 NOT IN('72','73','74','75','76','77','78','79','80','81','82','83','84','85','86','87','88','89','90','91','92','93','94','95','96','97','98','99','100','101')
										   		ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key1=>$row1)
            {
                $accheadGroupRow[$row1->ahaId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;
            }//end for each
        }//end if

        else
        {
            $accheadGroupRow[0] = SELECT;
        }
        $resultArray = array_merge($accheadGroupRow,$resultArray);
        return ($resultArray);
    }//end function

    function getActiveSubAccounts()
    {
/*        $accheadGroupRow = array();
        $accheadGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
												FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
										   	 ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										  	    WHERE ah.vActive = '1' AND ahc.vSubLedgerType = 'C' ORDER BY nId DESC");
        if ($accheadGroup->num_rows() > 0)
        {
            $accheadGroupRow[0] = SELECT;
            foreach ($accheadGroup->result() as $key=>$row)
            {
                $accheadGroupRow[$row->ahaId] = $row->vTitle;
            }//end for each
        }//end if
        else
        {
            $accheadGroupRow[0] = NOVALUE;
        }//end else
*/

      $accheadGroupRow[0] = NOVALUE;
        return ($accheadGroupRow);
    }//end function

    function getActiveEmployees()
    {
        $empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'
											ORDER BY nEId DESC");
        $empGroupRow = array();

        if ($empGroup->num_rows() > 0)
        {
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
            {
                $empGroupRow[$row->nEId] = (stripslashes($row->vName).' ('.stripslashes($row->vCode).') ');
            }//end for each
        }//end if
        else
        {
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function

    function getSales($extraCheck)
    {
        $adminGroupListQuery  = "SELECT sc.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails
                                 scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                 sad ON sc.nSCId = sad.nSalesCloseId WHERE sc.vActive='1' ".$extraCheck." GROUP BY sc.nSCId
                                 ORDER BY sc.nSCId DESC";
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

    function getRefund($extraCheck)
    {
        $adminGroupListQuery  = "SELECT sc.* FROM {PRE}RefundClosing sc LEFT JOIN {PRE}RefundClosingDetails
                                 scd ON sc.nRCId = scd.nRefundCloseId WHERE sc.vActive='1' ".$extraCheck." GROUP BY sc.nRCId
                                 ORDER BY sc.nRCId DESC";
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

    function getTktDetails($scId)
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}SalesClosingDetails WHERE nSalesCloseId='".$scId."' ORDER BY nId DESC";
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

    function getAccountDetails($scId)
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}SalesAccountDetails WHERE nSalesCloseId='".$scId."' ORDER BY nId DESC";
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

    function getRefundDetails($scId)
    {
        $adminGroupListQuery  = "SELECT * FROM {PRE}RefundClosingDetails WHERE nRefundCloseId='".$scId."' ORDER BY nId DESC";
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
