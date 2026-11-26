<?php
class Refund_register_model extends CI_Model
{

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	
    /*********************Refund Master******************************/
    function getAllRefund()
    {
        //active groups
        $adminGroupRow  = array();
        $sqlRefundQuery = "SELECT * FROM {PRE}RefundRegister WHERE
          nLocation IN ". $this->session->userdata('accessLoc') ." 
          ORDER BY nRId DESC
						   LIMIT " . $this->common_model->_offset . " , " . $this->common_model->_limit;
        session_start();
        $_SESSION['sess_query'] = $sqlRefundQuery;
        $refundGroupList 		= $this->db->query($sqlRefundQuery);
        if ($refundGroupList->num_rows() > 0)
		{
            foreach ($refundGroupList->result() as $key => $row)
			{
                $refundGroupRow[] = $row;
            }//end for each
        }//end if
        return ($refundGroupRow);
    }//end function

    //get user refund booking count
    function getAllRefundCount()
    {
        //active groups
        $refundGroupRow  = array();
        $refundGroupList = $this->db->query("SELECT * FROM {PRE}RefundRegister WHERE
            nLocation IN ". $this->session->userdata('accessLoc') ." 
            ORDER BY nRId DESC");
        return ($refundGroupList->num_rows());
    }//end function

    function getActiveAirline()
    {
        $airlineGroupRow = array();
        $airlineGroup 	 = $this->db->query("SELECT * FROM {PRE}Airline ORDER BY nArId DESC");
        if ($airlineGroup->num_rows() > 0)
		{
            $airlineGroupRow[0] = SELECT;
            foreach ($airlineGroup->result() as $key => $row)
			{
                $airlineGroupRow[$row->nArId] = $row->vTitle . ' ( ' . $row->vAirlineNo . ' )';
            }//end for each
        }//end if
        else
		{
            $airlineGroupRow[0] = NOVALUE;
        }//end else
        return ($airlineGroupRow);
    }//end function

	function getActiveCards()
	{
        $cardGroupRow = array();
        $cardGroup 	  = $this->db->query("SELECT * FROM {PRE}Card ORDER BY nCId DESC");
        if ($cardGroup->num_rows() > 0)
		{
            $cardGroupRow[0] = SELECT;
            foreach ($cardGroup->result() as $key => $row)
			{
                $cardGroupRow[$row->nCId] = $row->vName;
            }//end for each
        }//end if
        else
		{
            $cardGroupRow[0] = NOVALUE;
        }//end else
        return ($cardGroupRow);
		
	}//end function
	
    function getActiveGDS()
    {
        $gdsGroupRow = array();
        $gdsGroup 	 = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC");
        if ($gdsGroup->num_rows() > 0)
		{
            $gdsGroupRow[0] = SELECT;
            foreach ($gdsGroup->result() as $key => $row)
			{
                $gdsGroupRow[$row->nGId] = $row->vName;
            }//end for each
        }//end if
        else
		{
            $gdsGroupRow[0] = NOVALUE;
        }//end else
        return ($gdsGroupRow);
    }//end function

	function getLocation()
	{
		$locGroupRow    = array();
		$locGroupRow[0] = SELECT;
		return ($locGroupRow);
	}//end function 

	function filterLocation($division)
	{
		$locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
		$locGroupRow = array();
		if ($locGroup->num_rows() > 0)
		{
			//$locGroupRow[0] = SELECT;
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

    function getActiveTransactions()
    {
        $transGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Transactions WHERE vActive = '1' ORDER BY nPosition DESC");
        $transGroupRow = array();

        if ($transGroup->num_rows() > 0)
		{
            $transGroupRow[0] = SELECT;
            foreach ($transGroup->result() as $key => $row)
			{
                $transGroupRow[$row->nId] = stripslashes($row->vTitle);
            }//end for each
        }//end if
        else {
            $transGroupRow[0] = NOVALUE;
        }//end else
        return ($transGroupRow);
    }//end function

    function getActiveType()
    {
        $typeGroupRow = array("R" => "Refund", "RV" => "Refund - Void");
        return ($typeGroupRow);
    } //end function

    function getActiveEmployee()
    {
        $empGroup 	 = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1' ORDER BY nEId DESC");
        $empGroupRow = array();

        if ($empGroup->num_rows() > 0)
		{
            $empGroupRow[0] = SELECT;
            foreach ($empGroup->result() as $key => $row)
			{
                $empGroupRow[$row->nEId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        }//end if
        else
		{
            $empGroupRow[0] = NOVALUE;
        }//end else
        return ($empGroupRow);
    }//end function

    function getActiveCustomer()
    {
        $custGroup 	  = $this->db->query("SELECT nCId,vCode,vName FROM {PRE}CustomerMaster WHERE vActive = '1' ORDER BY nCId DESC");
        $custGroupRow = array();

        if ($custGroup->num_rows() > 0)
		{
            $custGroupRow[0] = SELECT;
            foreach ($custGroup->result() as $key => $row)
			{
                $custGroupRow[$row->nCId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        }//end if
        else
		{
            $custGroupRow[0] = NOVALUE;
        }//end else
        return ($custGroupRow);
    }//end function

    function getNewOld()
    {
        $GroupRow = array("N" => "New", "O" => "Old");
        return ($GroupRow);
    }//end function

    function getActiveAgent()
    {
        $agentGroup    = $this->db->query("SELECT nAId,vCode,vName FROM {PRE}Agent WHERE vActive = '1' ORDER BY nAId DESC");
        $agentGroupRow = array();

        if ($agentGroup->num_rows() > 0)
		{
            $agentGroupRow[0] = SELECT;
            foreach ($agentGroup->result() as $key => $row)
			{
                $agentGroupRow[$row->nAId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        }//end if
        else
		{
            $agentGroupRow[0] = NOVALUE;
        }//end else
        return ($agentGroupRow);
    }//end function

    function getActiveCurrency()
    {
        $currGroup 	  = $this->db->query("SELECT nId,vTitle,vCurrcode FROM {PRE}Currency WHERE vActive = '1' ORDER BY nId DESC");
        $currGroupRow = array();

        if ($currGroup->num_rows() > 0)
		{
            $currGroupRow[0] = SELECT;
            foreach ($currGroup->result() as $key => $row)
			{
                $currGroupRow[$row->nId] = (stripslashes($row->vTitle) . ' (' . stripslashes($row->vCurrcode) . ') ');
            }//end for each
        }//end if
        else
		{
            $currGroupRow[0] = NOVALUE;
        }//end else
        return ($currGroupRow);
    }//end function

    function getAccounts()
    {
        $accGroupRow = array();
		$accGroup 	 = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId ASC");
        if ($accGroup->num_rows() > 0)
		{
            $accGroupRow[0] = SELECT;
            foreach ($accGroup->result() as $key => $row)
			{
                $accGroupRow[$row->nVId] = $row->vName. ' (' . $row->vCode . ' )';
            }//end for each
        }//end if		
        else
		{
            $accGroupRow[0] = NOVALUE;
        }//end else
        return ($accGroupRow);
    }//end function

    function getAPIDAccounts()
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

    function getAgentAccounts()
    {
        $agentGroup    = $this->db->query("SELECT nAId,vCode,vName FROM {PRE}Agent WHERE vActive = '1' ORDER BY nAId DESC");
        $agentGroupRow = array();

        if ($agentGroup->num_rows() > 0)
        {
            $agentGroupRow[0] = SELECT;
            foreach ($agentGroup->result() as $key => $row)
            {
                $agentGroupRow[$row->nAId] = (stripslashes($row->vName) . ' (' . stripslashes($row->vCode) . ') ');
            }//end for each
        } //end if
        else
        {
            $agentGroupRow[0] = NOVALUE;
        }//end else
        return ($agentGroupRow);
    }//end function

    function getCorrActiveLocation($division)
    {
        $corrLocGroup    = $this->db->query("SELECT nId,vTitle FROM {PRE}Location WHERE vActive = '1' AND nDivision = '".$division."'                                              
            AND nId IN ".$this->session->userdata('accessLoc') ." 
            ORDER BY nPosition DESC");
        $corrLocGroupRow = array();

        if ($corrLocGroup->num_rows() > 0)
        {
            //$corrLocGroupRow[0] = SELECT;
            foreach ($corrLocGroup->result() as $key => $row)
            {
                $corrLocGroupRow[$row->nId] = stripslashes($row->vTitle);
            }//end for each
        }//end if
        else
        {
            $corrLocGroupRow[0] = NOVALUE;
        }//end else
        return ($corrLocGroupRow);
    }//end function

    function insertRefund()
    {
        $sectorval = $this->input->post('txtSector1').'-'.$this->input->post('txtSector2').'-'.$this->input->post('txtSector3').'-'.$this->input->post('txtSector4').'-'.$this->input->post('txtSector5');
        //insert into refund table
        $data = array('nDivision' => $this->input->post('ddlDivision'), 'nLocation' => $this->input->post('ddlLocation'),
                      'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nTransaction' => $this->input->post('ddlTrans'),
                      'vDocNumber' => $this->input->post('txtDocNumber'),'nGDS' => $this->input->post('ddlGDS'),
                      'vType' => $this->input->post('ddlType'),'vPassenger' => $this->input->post('txtPassenger'),
					  'vSector' => $sectorval, 'nAccount' => $this->input->post('ddlAccount'),'nUserId' => $this->session->userdata('AbsAdmin'),
                      'vSegment' => $this->input->post('txtSegment'), 'vDestination' => $this->input->post('txtDestination'),
                      'vAcAPID' => $this->input->post('ddlAccApId'),'nCurrency' => $this->input->post('ddlCurrency'),
					  'nRate' => $this->input->post('txtRate'),'vSalesDet' => date('Y-m-d', strtotime($this->input->post('txtSdt'))),
					  'vRefundDet' => date('Y-m-d', strtotime($this->input->post('txtRdt'))),'vMode' => $this->input->post('ddlMode'),
                      'vActive' => $this->input->post('chkActive')); 
        $this->db->insert('{PRE}RefundRegister', $data); 

        $id = $this->db->insert_id();

        //insert into refund billing table
        $data1 = array('nRefundId' => $id, 'vInvoice' => $this->input->post('txtInvoice'),
                        'vMode' => $this->input->post('ddlMode2'),'nAcCode' => $this->input->post('ddlAccountClose'),
                        'vAcSubCode' => $this->input->post('ddlAccountHead'),'nAgentAc' => $this->input->post('ddlAgentAc'),
                        'vLPO' => $this->input->post('txtLPO'));
        $this->db->insert('{PRE}RefundRegisterBilling', $data1);

        $Fare           = (@array_filter($this->input->post('txtFare')) != '') ? $this->input->post('txtFare') : 0;
        $UsedFare       = (@array_filter($this->input->post('txtUsedFare')) != '') ? $this->input->post('txtUsedFare') : 0;
        $SaleAmt        = (@array_filter($this->input->post('txtSaleAmt')) != '') ? $this->input->post('txtSaleAmt') : 0;
        $ServiceAmt2    = (@array_filter($this->input->post('txtServiceAmt2')) != '') ? $this->input->post('txtServiceAmt2') : 0;
        $ServiceAmt     = (@array_filter($this->input->post('txtServiceAmt')) != '') ? $this->input->post('txtServiceAmt') : 0;
        $ServicePer2    = (@array_filter($this->input->post('txtServicePer')) != '') ? $this->input->post('txtServicePer') : 0;
        $Country        = (@array_filter($this->input->post('ddlCountry')) != '') ? $this->input->post('ddlCountry') : 0;
        $Tax            = (@array_filter($this->input->post('txtTax')) != '') ? $this->input->post('txtTax') : 0;
        $UsedTax        = (@array_filter($this->input->post('txtUsedTax')) != '') ? $this->input->post('txtUsedTax') : 0;
        $OCharge        = (@array_filter($this->input->post('txtOCharge')) != '') ? $this->input->post('txtOCharge') : 0;
        $DiscAmt        = (@array_filter($this->input->post('txtDiscAmt')) != '') ? $this->input->post('txtDiscAmt') : 0;
        $City           = (@array_filter($this->input->post('ddlCity')) != '') ? $this->input->post('ddlCity') : 0;
        $Comm1          = (@array_filter($this->input->post('txtComm1')) != '') ? $this->input->post('txtComm1') : 0;
        $Comm2          = (@array_filter($this->input->post('txtComm2')) != '') ? $this->input->post('txtComm2') : 0;
        $OthAdj         = (@array_filter($this->input->post('txtOthAdj')) != '') ? $this->input->post('txtOthAdj') : 0;
        $OthAdj2        = (@array_filter($this->input->post('txtOthAdj2')) != '') ? $this->input->post('txtOthAdj2') : 0;
        $Hotel          = (@array_filter($this->input->post('ddlHotel')) != '') ? $this->input->post('ddlHotel') : 0;
        $OtherAmt       = (@array_filter($this->input->post('txtOtherAmt')) != '') ? $this->input->post('txtOtherAmt') : 0;
        $VendorAmt      = (@array_filter($this->input->post('txtVendorAmt')) != '') ? $this->input->post('txtVendorAmt') : 0;
        $OthAdj3        = (@array_filter($this->input->post('txtOthAdj3')) != '') ? $this->input->post('txtOthAdj3') : 0;
        $AgentAmt2      = (@array_filter($this->input->post('txtAgentAmt2')) != '') ? $this->input->post('txtAgentAmt2') : 0;
        $Issued         = (@array_filter($this->input->post('ddlIssued')) != '') ? $this->input->post('ddlIssued') : 0;
        $NetAmt1        = (@array_filter($this->input->post('txtNetAmt1')) != '') ? $this->input->post('txtNetAmt1') : 0;
        $NetAmt2        = (@array_filter($this->input->post('txtNetAmt2')) != '') ? $this->input->post('txtNetAmt2') : 0;
        $PaidTo         = (@array_filter($this->input->post('txtPaidTo')) != '') ? $this->input->post('txtPaidTo') : 0;
        $type      	    = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $yesNo      	= (@array_filter($this->input->post('ddlYesNo')) != '')?$this->input->post('ddlYesNo'):0;
        $tktNum         = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $tktNum_O         = (@array_filter($this->input->post('txtTkt')) != '')?$this->input->post('txtTkt'):0;
        $oTkt     	    = (@array_filter($this->input->post('txtORGTkt')) != '')?$this->input->post('txtORGTkt'):0;
        $airline     	= (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $passenger      = (@array_filter($this->input->post('txtPass')) != '')?$this->input->post('txtPass'):0;
        $accRefNumber 	= (@array_filter($this->input->post('txtRemarks')) != '')?$this->input->post('txtRemarks'):0;


        //insert into RefundRegister Details table
        foreach ($type as $key => $item)
		{

            if($yesNo[$key] == 'N') {
              $tktVal = $tktNum[$key];
            }
            else if ($yesNo[$key] == 'O'){
              $tktVal = $tktNum_O[$key];
            }
            $data3 = array('nRefundId' => $id, 'vTktType' => $item, 'vNewOld' => $yesNo[$key],
                            'nTktNum' => $tktVal , 'vOrgTktNum' => $oTkt[$key], 'vAirline' => $airline[$key],
                            'vPassenger' => $passenger[$key], 'vRemarks' => $accRefNumber[$key],'vFareAmt' => $Fare[$key],
                            'vUsedFare' => $UsedFare[$key],'vSalesAmt' => $SaleAmt[$key],'vServicePer' => $ServicePer2[$key],
                            'vServiceAmt' => $ServiceAmt2[$key],'vServiceAmt2' => $ServiceAmt[$key],'vTax' => $Tax[$key],
                            'vOurCharge' => $OCharge[$key], 'vDiscountAmt' => $DiscAmt[$key],'nCity' => $City[$key], 'vComm1' => $Comm1[$key],
                            'vComm2' => $Comm2[$key], 'vOthA1' => $OthAdj[$key],'vOthA2' => $OthAdj2[$key],'nHotel' => $Hotel[$key],
                            'vOtherAmt' => $OtherAmt[$key],'vVendorAmt' => $VendorAmt[$key],'vOthA3' => $OthAdj3[$key],
                            'vAgentAmt' => $AgentAmt2[$key],'nCountry' => $Country[$key],'vUsedTax' => $UsedTax[$key],
                            'vIssuedBy' => $Issued[$key], 'vNetAmt1' => $NetAmt1[$key],'vNetAmt2' => $NetAmt2[$key],
                            'vPaidTo' => $PaidTo[$key]);
            $this->db->insert('{PRE}RefundRegisterDetails', $data3);
        }//end foreach
        $msgVal = 'add_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

        if ($this->input->post('btnGo') && trim($this->input->post('btnGo')) != '')
		{
            redirect(site_url("refund_register/editRefund/".$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$msgVal));
        }//end if
        if ($this->input->post('btnSaveExit') && trim($this->input->post('btnSaveExit')) != '')
		{
            redirect(site_url("refund_register/showlist/".$this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if
    }//end function

    function updateRefund()
    {
        $sectorval = $this->input->post('txtSector1').'-'.$this->input->post('txtSector2').'-'.$this->input->post('txtSector3').'-'.$this->input->post('txtSector4').'-'.$this->input->post('txtSector5');
        //update into RefundRegister table
        $data = array('nDivision' => $this->input->post('ddlDivision'), 'nLocation' => $this->input->post('ddlLocation'),
                        'dDate' => date('Y-m-d', strtotime($this->input->post('txtDate'))),'nTransaction' => $this->input->post('ddlTrans'),
                        'vDocNumber' => $this->input->post('txtDocNumber'),'nGDS' => $this->input->post('ddlGDS'),
                        'vType' => $this->input->post('ddlType'),'vPassenger' => $this->input->post('txtPassenger'),
                        'vSector' => $sectorval, 'nAccount' => $this->input->post('ddlAccount'), 'nUserId' => $this->session->userdata('AbsAdmin'),
                        'vSegment' => $this->input->post('txtSegment'), 'vDestination' => $this->input->post('txtDestination'),
                        'vAcAPID' => $this->input->post('ddlAccApId'),'nCurrency' => $this->input->post('ddlCurrency'),
                        'nRate' => $this->input->post('txtRate'),'vSalesDet' => date('Y-m-d', strtotime($this->input->post('txtSdt'))),
                        'vRefundDet' => date('Y-m-d', strtotime($this->input->post('txtRdt'))),'vMode' => $this->input->post('ddlMode'),
                        'vActive' => $this->input->post('chkActive'));
        $this->db->where('nRId', $this->input->post('nId'));
        $this->db->update('{PRE}RefundRegister', $data);
        $id = $this->input->post('nId');

        //update refund billing table
        $data1 = array('nRefundId' => $id, 'vInvoice' => $this->input->post('txtInvoice'),
                        'vMode' => $this->input->post('ddlMode2'),'nAcCode' => $this->input->post('ddlAccountClose'),
                        'vAcSubCode' => $this->input->post('ddlAccountHead'),'nAgentAc' => $this->input->post('ddlAgentAc'),
                        'vLPO' => $this->input->post('txtLPO'));
        $this->db->where('nRefundId', $this->input->post('nId'));
        $this->db->update('{PRE}RefundRegisterBilling', $data1);

        $Fare           = (@array_filter($this->input->post('txtFare')) != '') ? $this->input->post('txtFare') : 0;
        $UsedFare       = (@array_filter($this->input->post('txtUsedFare')) != '') ? $this->input->post('txtUsedFare') : 0;
        $SaleAmt        = (@array_filter($this->input->post('txtSaleAmt')) != '') ? $this->input->post('txtSaleAmt') : 0;
        $ServiceAmt2    = (@array_filter($this->input->post('txtServiceAmt2')) != '') ? $this->input->post('txtServiceAmt2') : 0;
        $ServiceAmt     = (@array_filter($this->input->post('txtServiceAmt')) != '') ? $this->input->post('txtServiceAmt') : 0;
        $ServicePer2    = (@array_filter($this->input->post('txtServicePer')) != '') ? $this->input->post('txtServicePer') : 0;
        $Country        = (@array_filter($this->input->post('ddlCountry')) != '') ? $this->input->post('ddlCountry') : 0;
        $Tax            = (@array_filter($this->input->post('txtTax')) != '') ? $this->input->post('txtTax') : 0;
        $UsedTax        = (@array_filter($this->input->post('txtUsedTax')) != '') ? $this->input->post('txtUsedTax') : 0;
        $OCharge        = (@array_filter($this->input->post('txtOCharge')) != '') ? $this->input->post('txtOCharge') : 0;
        $DiscAmt        = (@array_filter($this->input->post('txtDiscAmt')) != '') ? $this->input->post('txtDiscAmt') : 0;
        $City           = (@array_filter($this->input->post('ddlCity')) != '') ? $this->input->post('ddlCity') : 0;
        $Comm1          = (@array_filter($this->input->post('txtComm1')) != '') ? $this->input->post('txtComm1') : 0;
        $Comm2          = (@array_filter($this->input->post('txtComm2')) != '') ? $this->input->post('txtComm2') : 0;
        $OthAdj         = (@array_filter($this->input->post('txtOthAdj')) != '') ? $this->input->post('txtOthAdj') : 0;
        $OthAdj2        = (@array_filter($this->input->post('txtOthAdj2')) != '') ? $this->input->post('txtOthAdj2') : 0;
        $Hotel          = (@array_filter($this->input->post('ddlHotel')) != '') ? $this->input->post('ddlHotel') : 0;
        $OtherAmt       = (@array_filter($this->input->post('txtOtherAmt')) != '') ? $this->input->post('txtOtherAmt') : 0;
        $VendorAmt      = (@array_filter($this->input->post('txtVendorAmt')) != '') ? $this->input->post('txtVendorAmt') : 0;
        $OthAdj3        = (@array_filter($this->input->post('txtOthAdj3')) != '') ? $this->input->post('txtOthAdj3') : 0;
        $AgentAmt2      = (@array_filter($this->input->post('txtAgentAmt2')) != '') ? $this->input->post('txtAgentAmt2') : 0;
        $Issued         = (@array_filter($this->input->post('ddlIssued')) != '') ? $this->input->post('ddlIssued') : 0;
        $NetAmt1        = (@array_filter($this->input->post('txtNetAmt1')) != '') ? $this->input->post('txtNetAmt1') : 0;
        $NetAmt2        = (@array_filter($this->input->post('txtNetAmt2')) != '') ? $this->input->post('txtNetAmt2') : 0;
        $PaidTo         = (@array_filter($this->input->post('txtPaidTo')) != '') ? $this->input->post('txtPaidTo') : 0;
        $type      	    = (@array_filter($this->input->post('ddlTktType')) != '')?$this->input->post('ddlTktType'):0;
        $yesNo      	= (@array_filter($this->input->post('ddlYesNo')) != '')?$this->input->post('ddlYesNo'):0;
        $tktNum         = (@array_filter($this->input->post('ddlTkt')) != '')?$this->input->post('ddlTkt'):0;
        $tktNum_O       = (@array_filter($this->input->post('txtTkt')) != '')?$this->input->post('txtTkt'):0;
        $oTkt     	    = (@array_filter($this->input->post('txtORGTkt')) != '')?$this->input->post('txtORGTkt'):0;
        $airline     	= (@array_filter($this->input->post('txtAirline')) != '')?$this->input->post('txtAirline'):0;
        $passenger      = (@array_filter($this->input->post('txtPass')) != '')?$this->input->post('txtPass'):0;
        $accRefNumber 	= (@array_filter($this->input->post('txtRemarks')) != '')?$this->input->post('txtRemarks'):0;
        $chkAccID	    = $this->input->post('idArray');

        //insert into RefundRegister Details table
        foreach($type as $key => $item)
        {
            //checking already exists if yes updated
            if(intval($chkAccID[$key]) != 0)
            {
              if($yesNo[$key] == 'N') {
                $tktVal = $tktNum[$key];
              }
              else if ($yesNo[$key] == 'O'){
                $tktVal = $tktNum_O[$key];
              }

                $data3 	= array('nRefundId' => $id, 'vTktType' => $item, 'vNewOld' => $yesNo[$key],
                                'nTktNum' => $tktVal , 'vOrgTktNum' => $oTkt[$key], 'vAirline' => $airline[$key],
                                'vPassenger' => $passenger[$key], 'vRemarks' => $accRefNumber[$key],'vFareAmt' => $Fare[$key],
                                'vUsedFare' => $UsedFare[$key],'vSalesAmt' => $SaleAmt[$key],'vServicePer' => $ServicePer2[$key],
                                'vServiceAmt' => $ServiceAmt2[$key],'vServiceAmt2' => $ServiceAmt[$key],
                                'nCountry' => $Country[$key],'vTax' => $Tax[$key],'vUsedTax' => $UsedTax[$key],
                                'vOurCharge' => $OCharge[$key], 'vDiscountAmt' => $DiscAmt[$key],'nCity' => $City[$key], 'vComm1' => $Comm1[$key],
                                'vComm2' => $Comm2[$key], 'vOthA1' => $OthAdj[$key],'vOthA2' => $OthAdj2[$key],'nHotel' => $Hotel[$key],
                                'vOtherAmt' => $OtherAmt[$key],'vVendorAmt' => $VendorAmt[$key],'vOthA3' => $OthAdj3[$key],
                                'vAgentAmt' => $AgentAmt2[$key],
                                'vIssuedBy' => $Issued[$key], 'vNetAmt1' => $NetAmt1[$key],'vNetAmt2' => $NetAmt2[$key], 'vPaidTo' => $PaidTo[$key]);
                $this->db->where('nRDId',$chkAccID[$key]);
                $this->db->update('{PRE}RefundRegisterDetails', $data3);
            }//end if
            else
            {
                //if new insert into db
                $data3 	= array('nRefundId' => $id, 'vTktType' => $item, 'vNewOld' => $yesNo[$key],
                                'nTktNum' => $tktNum[$key], 'vOrgTktNum' => $oTkt[$key], 'vAirline' => $airline[$key],
                                'vPassenger' => $passenger[$key], 'vRemarks' => $accRefNumber[$key],'vFareAmt' => $Fare[$key],
                                'vUsedFare' => $UsedFare[$key],'vSalesAmt' => $SaleAmt[$key], 'vServicePer' => $ServicePer2[$key],
                                'vServiceAmt' => $ServiceAmt2[$key],'vServiceAmt2' => $ServiceAmt[$key],
                                'nCountry' => $Country[$key],'vTax' => $Tax[$key],'vUsedTax' => $UsedTax[$key],
                                'vOurCharge' => $OCharge[$key], 'vDiscountAmt' => $DiscAmt[$key],'nCity' => $City[$key], 'vComm1' => $Comm1[$key],
                                'vComm2' => $Comm2[$key], 'vOthA1' => $OthAdj[$key],'vOthA2' => $OthAdj2[$key],'nHotel' => $Hotel[$key],
                                'vOtherAmt' => $OtherAmt[$key],'vVendorAmt' => $VendorAmt[$key],'vOthA3' => $OthAdj3[$key],
                                'vAgentAmt' => $AgentAmt2[$key],
                                'vIssuedBy' => $Issued[$key], 'vNetAmt1' => $NetAmt1[$key],'vNetAmt2' => $NetAmt2[$key], 'vPaidTo' => $PaidTo[$key]);
                $this->db->insert('{PRE}RefundRegisterDetails', $data3);
            }//end else
        }//end foreach
        $msgVal = 'edit_suc';

        switch($this->input->post('chkPrint'))
        {
            case "1":
                $showId = '/'.$id;
                break;

            default:
                $showId = '';
                break;
        }//end switch

        if(trim($this->session->userdata('backURL')) != '')
        {
            redirect(site_url($this->session->userdata('backURL')));
        }
        else
        {
     
        redirect(site_url("refund_register/showlist/" . $this->uri->segment(3).'/'.$msgVal.$showId));
        }//end if 
    } //end function

    //get airline other details contact name
    function getAirlineName()
    {
        $airlineNameRow = array();
        $airlineName 	= $this->db->query("SELECT vName FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlineName->num_rows() > 0) {
            foreach ($airlineName->result() as $key => $row) {
                $airlineNameRow[] = $row->vName;
            }
            //end for each
        }//end if
        return (array_filter($airlineNameRow));
    }//end function

    //get airline other details contact designation
    function getAirlineDesignation()
    {
        $airlineDesRow = array();
        $airlineDes    = $this->db->query("SELECT vDesignation FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlineDes->num_rows() > 0) {
            foreach ($airlineDes->result() as $key => $row) {
                $airlineDesRow[] = $row->vDesignation;
            }//end for each
        }//end if
        return (array_filter($airlineDesRow));
    }//end function

    //get airline other details contact mobile
    function getAirlineMobile()
    {
        $airlineMobileRow = array();
        $airlineMobile 	  = $this->db->query("SELECT vMobile FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlineMobile->num_rows() > 0) {
            foreach ($airlineMobile->result() as $key => $row) {
                $airlineMobileRow[] = $row->vMobile;
            }//end for each
        }//end if
        return (array_filter($airlineMobileRow));
    }//end function

    //get airline other details contact phone
    function getAirlinePhone()
    {
        $airlinePhoneRow = array();
        $airlinePhone 	 = $this->db->query("SELECT vPhone FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlinePhone->num_rows() > 0) {
            foreach ($airlinePhone->result() as $key => $row) {
                $airlinePhoneRow[] = $row->vPhone;
            }//end for each
        }//end if
        return (array_filter($airlinePhoneRow));
    }//end function

    function getAirlineEmail()
    {
        $airlineEmailRow = array();
        $airlineEmail 	 = $this->db->query("SELECT vEmail FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlineEmail->num_rows() > 0) {
            foreach ($airlineEmail->result() as $key => $row) {
                $airlineEmailRow[] = $row->vEmail;
            }//end for each
        }//end if
        return (array_filter($airlineEmailRow));
    }//end function

    function getAirlineAddress()
    {
        $airlineAddressRow = array();
        $airlineAddress    = $this->db->query("SELECT vAddress FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlineAddress->num_rows() > 0) {
            foreach ($airlineAddress->result() as $key => $row) {
                $airlineAddressRow[] = $row->vAddress;
            }//end for each
        }//end if
        return (array_filter($airlineAddressRow));
    }//end function

    function getAirlineContactID()
    {
        $airlineCIdRow = array();
        $airlineCId    = $this->db->query("SELECT nArcId FROM {PRE}AirlineContact WHERE nAirline = '" . $this->catId . "'");

        if ($airlineCId->num_rows() > 0) {
            foreach ($airlineCId->result() as $key => $row) {
                $airlineCIdRow[] = $row->nArcId;
            }//end for each
        }//end if
        return (array_filter($airlineCIdRow));
    }//end function

    //fetch corresponding refund booking details
    function getCorresRefund()
    {
        $fetchTitle = $this->db->query("SELECT * FROM {PRE}RefundRegister WHERE nRId='" . $this->catId . "'");
        $showVal 	= $fetchTitle->row();
        return $showVal;
    }//end function

    //fetch corresponding refund billing details
    function getCorresBillingDetails()
    {
        $fetchTitle = $this->db->query("SELECT * FROM {PRE}RefundRegisterBilling WHERE nRefundId='" . $this->catId . "'");
        $showVal 	= $fetchTitle->row();
        return $showVal;
    }//end function

    function getCorresRefundDetails()
    {
        $refundDetailsRow           = array();
        $refundDetailsType          = array();
        $refundDetailsNewOld        = array();
        $refundDetailsTktNum        = array();
        $refundDetailsOrgTktNum     = array();
        $refundDetailsAirline       = array();
        $refundDetailsPassenger     = array();
        $refundDetailsRemarks       = array();
        $newValArray                = array();
        $refundDetailsFare          = array();
        $refundDetailsUsedFare      = array();
        $refundDetailsSaleAmt       = array();
        $refundDetailsServiceAmt2   = array();
        $refundDetailsServiceAmt    = array();
        $refundDetailsServicePer2   = array();
        $refundDetailsCountry       = array();
        $refundDetailsTax           = array();
        $refundDetailsUsedTax       = array();
        $refundDetailsOCharge       = array();
        $refundDetailsDiscAmt       = array();
        $refundDetailsCity          = array();
        $refundDetailsComm1         = array();
        $refundDetailsComm2         = array();
        $refundDetailsOthAdj        = array();
        $refundDetailsOthAdj2       = array();
        $refundDetailsHotel         = array();
        $refundDetailsOtherAmt      = array();
        $refundDetailsVendorAmt     = array();
        $refundDetailsOthAdj3       = array();
        $refundDetailsAgentAmt2     = array();
        $refundDetailsIssued        = array();
        $refundDetailsNetAmt1       = array();
        $refundDetailsNetAmt2       = array();
        $refundDetailsPaidTo        = array();

        $refundDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId = '".$this->catId."'");

        if ($refundDetailsList->num_rows() > 0) {
            foreach ($refundDetailsList->result() as $key => $row) {

                $refundDetailsRow[]         = $row->nRDId;
                $refundDetailsType[]        = $row->vTktType;
                $refundDetailsNewOld[]      = $row->vNewOld;
                $refundDetailsTktNum[]      = $row->nTktNum;
                $refundDetailsOrgTktNum[]   = $row->vOrgTktNum;
                $refundDetailsAirline[]     = $row->vAirline;
                $refundDetailsPassenger[]   = $row->vPassenger;
                $refundDetailsRemarks[]     = $row->vRemarks;

                $refundDetailsFare[]        = $row->vFareAmt;
                $refundDetailsUsedFare[]    = $row->vUsedFare;
                $refundDetailsSaleAmt[]     = $row->vSalesAmt;
                $refundDetailsServiceAmt2[] = $row->vServiceAmt;
                $refundDetailsServiceAmt[]  = $row->vServiceAmt2;
                $refundDetailsServicePer2[] = $row->vServicePer;
                $refundDetailsCountry[]     = $row->nCountry;
                $refundDetailsTax[]         = $row->vTax;
                $refundDetailsUsedTax[]     = $row->vUsedTax;
                $refundDetailsOCharge[]     = $row->vOurCharge;
                $refundDetailsDiscAmt[]     = $row->vDiscountAmt;
                $refundDetailsCity[]        = $row->nCity;
                $refundDetailsComm1[]       = $row->vComm1;
                $refundDetailsComm2[]       = $row->vComm2;
                $refundDetailsOthAdj[]      = $row->vOthA1;
                $refundDetailsOthAdj2[]     = $row->vOthA2;
                $refundDetailsHotel[]       = $row->nHotel;
                $refundDetailsOtherAmt[]    = $row->vOtherAmt;
                $refundDetailsVendorAmt[]   = $row->vVendorAmt;
                $refundDetailsOthAdj3[]     = $row->vOthA3;
                $refundDetailsAgentAmt2[]   = $row->vAgentAmt;
                $refundDetailsIssued[]      = $row->vIssuedBy;
                $refundDetailsNetAmt1[]     = $row->vNetAmt1;
                $refundDetailsNetAmt2[]     = $row->vNetAmt2;
                $refundDetailsPaidTo[]      = $row->vPaidTo;
            }//end for each
        }//end if

        $newValArray['ID']          = $refundDetailsRow;
        $newValArray['Type']        = $refundDetailsType;
        $newValArray['YesNo']       = $refundDetailsNewOld;
        $newValArray['TktNum']      = $refundDetailsTktNum;
        $newValArray['ORGTkt']      = $refundDetailsOrgTktNum;
        $newValArray['Airline']     = $refundDetailsAirline;
        $newValArray['Passenger']   = $refundDetailsPassenger;
        $newValArray['Remarks']     = $refundDetailsRemarks;

        $newValArray['Fare']        = $refundDetailsFare;
        $newValArray['UsedFare']    = $refundDetailsUsedFare;
        $newValArray['SaleAmt']     = $refundDetailsSaleAmt;
        $newValArray['ServiceAmt2'] = $refundDetailsServiceAmt2;
        $newValArray['ServiceAmt']  = $refundDetailsServiceAmt;
        $newValArray['ServicePer2'] = $refundDetailsServicePer2;
        $newValArray['Country']     = $refundDetailsCountry;
        $newValArray['Tax']         = $refundDetailsTax;
        $newValArray['UsedTax']     = $refundDetailsUsedTax;
        $newValArray['OCharge']     = $refundDetailsOCharge;
        $newValArray['DiscAmt']     = $refundDetailsDiscAmt;
        $newValArray['City']        = $refundDetailsCity;
        $newValArray['Comm1']       = $refundDetailsComm1;
        $newValArray['Comm2']       = $refundDetailsComm2;
        $newValArray['OthAdj']      = $refundDetailsOthAdj;
        $newValArray['OthAdj2']     = $refundDetailsOthAdj2;
        $newValArray['Hotel']       = $refundDetailsHotel;
        $newValArray['OtherAmt']    = $refundDetailsOtherAmt;
        $newValArray['VendorAmt']   = $refundDetailsVendorAmt;
        $newValArray['OthAdj3']     = $refundDetailsOthAdj3;
        $newValArray['AgentAmt2']   = $refundDetailsAgentAmt2;
        $newValArray['Issued']      = $refundDetailsIssued;
        $newValArray['NetAmt1']     = $refundDetailsNetAmt1;
        $newValArray['NetAmt2']     = $refundDetailsNetAmt2;
        $newValArray['PaidTo']      = $refundDetailsPaidTo;

        return ($newValArray);
    }//end function

    function deletePassDetails($ID)
    {
        $this->db->delete('{PRE}RefundRegisterDetails', array('nRDId' => $ID));
    }//end function

    function deleteRefund()
    {
        $this->db->delete('{PRE}RefundRegister', array('nRId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}RefundRegisterBilling', array('nRefundId' => $this->uri->segment(4)));
        $this->db->delete('{PRE}RefundRegisterDetails', array('nRefundId' => $this->uri->segment(4)));
        redirect(site_url("refund_register/showlist/" . $this->uri->segment(3) . '/del_suc'));
    }//end function

    function deleteAllRefund()
    {
        switch ($this->input->post('ddlAction')) {
            case "yes":
                foreach ($this->input->post('del') as $val) {
                    //update into db
                    $data = array('vActive' => '1');
                    $this->db->where('nRId', $val);
                    $this->db->update('{PRE}RefundRegister', $data);
                }//end foreach
                redirect(site_url("refund_register/showlist/" . $this->input->post('nRId') . '/edit_suc'));
            break;

            case "no":
                foreach ($this->input->post('del') as $val) {
                    //update into db
                    $data = array('vActive' => '0');
                    $this->db->where('nRId', $val);
                    $this->db->update('{PRE}RefundRegister', $data);
                }//end foreach
                redirect(site_url("refund_register/showlist/" . $this->input->post('nRId') . '/edit_suc'));
            break;

            case "delete":
                foreach ($this->input->post('del') as $val) {
                    $this->db->delete('{PRE}RefundRegister', array('nRId' => $val));
                    $this->db->delete('{PRE}RefundRegisterBilling', array('nRefundId' => $val));
                    $this->db->delete('{PRE}RefundRegisterDetails', array('nRefundId' => $val));
                }//end foreach
                redirect(site_url("refund_register/showlist/" . $this->input->post('nRId') . '/del_suc'));
            break;
        }//end switch
    }//end function

    //fetching single details
    function showDetails($refundId)
    {
        $viewAccessRow 	= array();
        $viewAccessList = $this->db->query("SELECT r.*,rb.*,d.vTitle as divName,
										    l.vTitle as locName,t.vTitle as transName,gds.vName as gdsName,
											CASE WHEN r.vMode = 'Ca' THEN 'Cash'
                                            	WHEN r.vMode = 'KN' THEN 'KNET' ELSE 'Credit' END as rMode,CASE WHEN rb.vMode = 'Ca'
                                            THEN 'Cash' WHEN rb.vMode = 'KN' THEN 'KNET' ELSE 'Credit' END as rbMode,
                                            CASE WHEN r.vType = 'R' THEN 'Refund' ELSE 'Refund-Void'
                                             END as rType FROM {PRE}RefundRegister r LEFT JOIN {PRE}Division d ON d.nId = r.nDivision
                                            LEFT JOIN {PRE}Location l ON l.nId = r.nLocation LEFT JOIN {PRE}Transactions t ON
                                             t.nId = r.nTransaction LEFT JOIN {PRE}Gds gds ON r.nGDS = gds.nGId 
											LEFT JOIN {PRE}RefundRegisterBilling rb ON
                                             rb.nRefundId = r.nRId WHERE nRId = '" . $refundId . "' ORDER BY nRId DESC");
        foreach ($viewAccessList->result() as $key => $row) {
            $content = '<table class="reportForm">
                <tr>
                    <td><strong>'.DIVISIONNAME.':</strong></td>
                    <td colspan="4">'.$row->divName.'</td>

                    <td><strong>'.LOCATIONNAME.':</strong></td>
                    <td colspan="5">'.$row->locName.'</td>

                    <td><strong>'.CUSTDATE.':</strong></td>
                    <td colspan="2">'.$row->dDate.'</td>
                </tr>

                <tr>
                    <td><strong>'.DOCNUMBER.':</strong></td>
                    <td colspan="5">'.$row->vDocNumber.'</td>

                    <td><strong>'.TREFSTATUS.':</strong></td>
                    <td colspan="7">'.$row->rType.'</td>
                </tr>

				<tr>
                    <td><strong>'.PASSENGER.' :</strong></td>
                    <td colspan="3">'.$row->vPassenger.'</td>

                    <td><strong>'.CURRNAME.' :</strong></td>
                    <td colspan="3">'.$this->common_model->get_field_name('Currency', 'vTitle', " WHERE nId='".$row->nCurrency."' ").'</td>

                    <td colspan="2"><strong>'.CURRRATE.' :</strong></td>
                    <td colspan="4">'.$this->common_model->get_field_name('Currency', 'vRate', " WHERE nId='".$row->nCurrency."' ").'</td>
				</tr>
				
				<tr>
				    <td><strong>'.ACCCODENAME.' :</strong></td>
                    <td colspan="4">'.$this->common_model->get_field_name('VendorMaster','vName',' WHERE nVId = "'.$row->nAccount.'"').'( '.$this->common_model->get_field_name('VendorMaster','vCode',' WHERE nVId = "'.$row->nAccount.'"').' )'.'</td>

                    <td colspan="2"><strong>'.MODEOFPAYMENT.' :</strong></td>
                    <td>'.$row->rMode.'</td>
				
				    <td colspan="2"><strong>'.SECTOR.' :</strong></td>
                    <td>'.$row->vSector.'</td>

                    <td colspan="2"><strong>'.SEGMENT.' :</strong></td>
                    <td>'.$row->vSegment.'</td>
				</tr>
				
                <tr>

                    <td><strong>'.DESTINATION.' :</strong></td>
                    <td>'.$row->vDestination.'</td>

                    <td colspan="2"><strong>'.ACAPID.' :</strong></td>
                    <td colspan="2">'.$this->common_model->get_field_name('CustomerMaster','vName',' WHERE nCId = "'.$row->vAcAPID.'"').'( '.$this->common_model->get_field_name('CustomerMaster','vCode',' WHERE nCId = "'.$row->vAcAPID.'"').' )'.'</td>
				
                    <td><strong>'.GDS.' :</strong></td>
                    <td>'.$row->nGDS.'</td>

                    <td colspan="2"><strong>'.SALESDT.' :</strong></td>
                    <td>'.$row->vSalesDet.'</td>

                    <td colspan="2"><strong>'.REFUNDDT.' :</strong></td>
                    <td>'.$row->vRefundDet.'</td>
                </tr>

                <tr>
                    <td colspan="14"><strong>'.NETFROMVENDOR.'</strong></td>
                </tr>
                <tr>
                    <td><strong>'.FARE.':</strong></td>
                    <td>'.$row->vFareAmt.'</td>

                    <td><strong>'.USEDFARE.':</strong></td>
                    <td>'.$row->vUsedFare.'</td>

                    <td><strong>'.SALESAMT.':</strong></td>
                    <td>'.$row->rvdSale.'</td>

                    <td colspan="2"><strong>'.SERVICEAMT.':</strong></td>
                    <td>'.$row->rvdServ.'</td>

                    <td colspan="2"><strong>'.COUNTRYNAME.':</strong></td>
                    <td colspan="3">'.$this->common_model->get_field_name('Country', 'vName', " WHERE nId='" . $row->nCountry . "' ").'</td>
                </tr>
                <tr>
                    <td><strong>'.TAX.':</strong></td>
                    <td>'.$row->vTax.'</td>

                    <td><strong>'.USEDTAX.':</strong></td>
                    <td>'.$row->vUsedTax.'</td>

                    <td><strong>'.OURCHARGE.':</strong></td>
                    <td>'.$row->vOurCharge.'</td>

                    <td colspan="2"><strong>'.DISCAMT.':</strong></td>
                    <td>'.$row->rvdDisc.'</td>

                    <td colspan="2"><strong>'.CITYNAME.':</strong></td>
                    <td colspan="3">'.$this->common_model->get_field_name('Country', 'vName', " WHERE nId='" . $row->nCity . "' ").'</td>
                </tr>
                <tr>
                    <td><strong>'.COMM.':</strong></td>
                    <td>'.$row->vComm1.'</td>

                    <td><strong>'.COMM.':</strong></td>
                    <td>'.$row->vComm2.'</td>

                    <td><strong>'.OTHADJ.':</strong></td>
                    <td>'.$row->vOthA1.'</td>

                    <td colspan="2"><strong>'.OTHADJ.':</strong></td>
                    <td>'.$row->vOthA2.'</td>

                    <td colspan="2"><strong>'.HOTELNAME.':</strong></td>
                    <td colspan="3">'.$this->common_model->get_field_name('Hotel', 'vName', " WHERE nHId='" . $row->nHotel . "' ").'</td>
                </tr>
                <tr>
                    <td><strong>'.OTHER.':</strong></td>
                    <td>'.$row->nOther.'</td>

                    <td><strong>'.VENDORNAME.':</strong></td>
                    <td>'.$row->nVendor.'</td>

                    <td><strong>'.OTHADJ.':</strong></td>
                    <td>'.$row->vOthA3.'</td>

                    <td colspan="2"><strong>'.AGENTAMT.':</strong></td>
                    <td>'.$row->rvdAgentAmt.'</td>

                    <td colspan="2"><strong>'.ISSUEDBY.':</strong></td>
                    <td colspan="3">'.$this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='" . $row->vIssuedBy . "' ").'</td>
                </tr>
                <tr>
                    <td colspan="2"><strong>'.NETAMT.':</strong></td>
                    <td colspan="2">'.$row->vNetAmt1.'</td>

                    <td colspan="2"><strong>'.REFUNDAMT.':</strong></td>
                    <td colspan="2">'.$row->vNetAmt2.'</td>

                    <td colspan="4"><strong>'.PAIDTO.':</strong></td>
                    <td colspan="2">'.$row->vPaidTo.'</td>
                </tr>

                <tr>
                    <td colspan="14"><strong>'.BILLINGDETAILS.'</strong></td>
                </tr>

                <tr>
                    <td colspan="2"><strong>'.MODEOFPAYMENT.':</strong></td>
                    <td>'.$row->rbMode.'</td>

                    <td colspan="2"><strong>'.ACCCODENAME.':</strong></td>
                    <td colspan="2">'.$this->common_model->get_field_name('CustomerMaster','vName',' WHERE nCId = "'.$row->nAcCode.'"').'( '.$this->common_model->get_field_name('CustomerMaster','vCode',' WHERE nCId = "'.$row->nAcCode.'"').' )'.'</td>

                    <td><strong>'.SUBCODE.':</strong></td>
                    <td>'.$this->common_model->get_field_name('AccountHeadAccounts','vTitle',' WHERE nId = "'.$row->vAcSubCode.'"').'( '.$this->common_model->get_field_name('AccountHeadAccounts','vACode',' WHERE nId = "'.$row->vAcSubCode.'"').' )'.'</td>

                    <td><strong>'.AGENTNAME.':</strong></td>
                    <td colspan="2">'.$this->common_model->get_field_name('Agent','vName',' WHERE nAId = "'.$row->nAgentAc.'"').'</td>

                    <td><strong>'.LPO.':</strong></td>
                    <td colspan="2">'.$row->vLPO.'</td>
                </tr>

                <tr>
                    <td colspan="14"><strong>'.REFUNDDETAILS.'</strong></td>
                </tr>
                <tr>
                    <td><strong>'.REFTYPE.'</strong></td>
                    <td><strong>'.NEWOLD.'</strong></td>
                    <td colspan="2"><strong>'.TICKETNO.'</strong></td>
                    <td><strong>'.ORGTKT.'</strong></td>
                    <td><strong>'.AIRLINE.'</strong></td>
                    <td><strong>'.PASSENGER.'</strong></td>
                    <td colspan="7"><strong>'.REMARKS.'</strong></td>
                </tr>';

                $getRefundDetails = $this->db->query("SELECT *,CASE WHEN vTktType = 'Tkt' THEN 'Ticket' WHEN vTktType = 'Xo' THEN 'Xo' ELSE 'Hotel'
		                                    	END as type FROM {PRE}RefundRegisterDetails WHERE nRefundId='".$refundId."'");

                foreach ($getRefundDetails->result() as $key1 => $row1) :
                    switch($row1->type)
                    {
                        case "Xo" :
                            $this->_TableName   = 'XoBooking';
                            $this->_extraField  = 'vXoNumber';
                            $this->dbId         = 'nXId';
                            break;

                        case "Ticket" :
                            $this->_TableName   = 'TicketBooking';
                            $this->_extraField  = 'vTicketNumber';
                            $this->dbId         = 'nTId';
                            break;

                        case "Hotel" :
                            $this->_TableName   = 'HotelVoucher';
                            $this->_extraField  = 'vVoucherId';
                            $this->dbId         = 'nHVId';
                            break;
                    }//end switch
                     $content .='<tr>
                        <td>'.$row1->type.'</td>
                        <td>'.$row1->vNewOld.'</td>
                        <td colspan="2">'.$this->common_model->get_field_name($this->_TableName, $this->_extraField, " WHERE ".$this->dbId." = '".$row1->nTktNum."'").'</td>
                        <td>'.$row1->vOrgTktNum.'</td>
                        <td>'.$row1->vAirline.'</td>
                        <td>'.$row1->vPassenger.'</td>
                        <td colspan="7">'.$row1->vRemarks.'</td>
                    </tr>';
                endforeach;
            $content .='</table>';
        }//end foreach
        return ($content);
    }//end function

    function getPassType()
    {
        $passTyperow = array("N" => "Normal", "S" => "Special");
        return ($passTyperow);
    }//end function

    function getModeType()
    {
        $modeTyperow = array("Cr" => "Credit", "Ca" => "Cash", "KN" => "KNET");
        return ($modeTyperow);
    }//end function

    function getOther()
    {
        $otherGroupRow = array();
        $otherGroup    = $this->db->query("SELECT * FROM {PRE}Other ORDER BY nOId DESC");
        if ($otherGroup->num_rows() > 0) 
		{
            $otherGroupRow[0] = SELECT;
            foreach ($otherGroup->result() as $key => $row) 
			{
                $otherGroupRow[$row->nOId] = $row->vName;
            }//end for each
        }//end if
        else 
		{
            $otherGroupRow[0] = SELECT;
        }//end else
        return ($otherGroupRow);
    }//end function

    function getVendor()
	{
		$vendorGroupRow = array();
		$vendorGroup 	= $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY nVId DESC");
		if ($vendorGroup->num_rows() > 0) 
		{
			$vendorGroupRow[0] = SELECT;
			foreach ($vendorGroup->result() as $key => $row) 
			{
				$vendorGroupRow[$row->nVId] = $row->vName;
			}//end for each
		}//end if
		else 
		{
			$vendorGroupRow[0] = SELECT;
		}//end else
		return ($vendorGroupRow);
	}//end function

    function getCountry()
    {
        $countryGroupRow = array();
        $countryGroup 	 = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId='0' ORDER BY nId DESC");
        if ($countryGroup->num_rows() > 0) {
            $countryGroupRow[0] = SELECT;
            foreach ($countryGroup->result() as $key => $row) {
                $countryGroupRow[$row->nId] = $row->vName;
            }//end for each
        }//end if
        else {
            $countryGroupRow[0] = SELECT;
        }//end else
        return ($countryGroupRow);
    }//end function

    function getCity1()
    {
        $cityGroupRow = array();
        $cityGroup 	  = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId !='0' ORDER BY nId DESC");
        if ($cityGroup->num_rows() > 0) {
            $cityGroupRow[0] = SELECT;
            foreach ($cityGroup->result() as $key => $row) {
                $cityGroupRow[$row->nId] = $row->vName;
            }//end for each
        }//end if
        else {
            $cityGroupRow[0] = SELECT;
        }//end else
        return ($cityGroupRow);
    }//end function

    function getCity($cid)
    {
        $cityGroupRow = array();
        $cityGroup 	  = $this->db->query("SELECT * FROM {PRE}Country WHERE nParentId='".$cid."' ORDER BY nId DESC");
        if ($cityGroup->num_rows() > 0) {
            $cityGroupRow[0] = SELECT;
            foreach ($cityGroup->result() as $key => $row) {
                $cityGroupRow[$row->nId] = $row->vName;
            }//end for each
        }//end if
        else {
            $cityGroupRow[0] = SELECT;
        }//end else
        return ($cityGroupRow);
    }//end function

    function getHotel()
    {
        $hotelGroupRow = array();
        $hotelGroup    = $this->db->query("SELECT * FROM {PRE}Hotel ORDER BY nHId DESC");
        if ($hotelGroup->num_rows() > 0) {
            $hotelGroupRow[0] = SELECT;
            foreach ($hotelGroup->result() as $key => $row) {
                $hotelGroupRow[$row->nHId] = $row->vName;
            }//end for each
        }//end if
        else {
            $hotelGroupRow[0] = SELECT;
        }//end else
        return ($hotelGroupRow);
    }//end function

    function searchActiveFinance($searchKey)
    {
        $typeGroupRow  = array();
        $extaCondition = " AND (aca.vACode LIKE ('%".$searchKey."%') OR aca.vACode LIKE ('% ".$searchKey."%') OR aca.vACode
							LIKE ( '%".$searchKey."%') OR aca.vTitle LIKE ('%".$searchKey."%') OR aca.vTitle
						   LIKE ('% ".$searchKey."%') OR aca.vTitle LIKE ( '%".$searchKey."%')) 
							AND ahc.vSubSale = 'C'";

        if(trim($searchKey) == 0)
        {
            $extaCondition = '';
        }//end if

        $typeGroup = $this->db->query("SELECT aca.*,ah.nAId,ah.vActive,ahc.vSubLedgerType FROM {PRE}AccountHeadAccounts aca
										LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId
									   LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
										WHERE ah.vActive = '1' ".$extaCondition." ORDER BY aca.nId DESC");
        if ($typeGroup->num_rows() > 0)
        {
            foreach ($typeGroup->result() as $key=>$row)
            {
                $typeGroupRow[] = $row->vACode.' - '.$row->vTitle;
            }//end for each
        }//end if

        //for json encode
        $result = array();
        foreach ($typeGroupRow as $key=>$value)
        {
            array_push($result, array(
                "name" => $value
            ));
        }//end fuction
        return ($result);
    }//end function

	function getTicketType()
	{
        $typeGroupRow = array("0" => SELECT, "Tkt" => "TICKET", "Xo" => "XO", "Htl" => "HOTEL");
        return ($typeGroupRow);
	}//end function

    function getActiveTickets()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function getTicketDetails($tkt,$type)
    {
        switch($type){
            case "Tkt":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,tbd.vSalesAmt as salesVal,tbd.vServicePercent as ServicePerVal FROM {PRE}TicketBooking tb LEFT JOIN
                                                           {PRE}TicketBillingDetails tbd ON tbd.nTicketId=tb.nTId
                                                            LEFT JOIN {PRE}TicketFareDetails tfd ON tfd.nTicketId=tb.nTId
														   WHERE tb.nTId= '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Employee']     = $row->vIssuedBy;
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
						$typeGroupRow['OrgTkt']       = $row->vOriginalTkt;
						$typeGroupRow['Remarks']      = $row->vRemarks;
                        $typeGroupRow['Fare']         = $row->vFaceValue;
                        $typeGroupRow['Tax']          = $row->vTaxTotal;
                        $typeGroupRow['NetAmt']       = $row->nNetVendor;
                        $typeGroupRow['ServPercent']  = $row->ServicePerVal;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nCommAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->salesVal;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['Account']      = $row->nAcc;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT tb.nGDS,tb.vPassenger,td.* FROM {PRE}TicketBooking tb
													LEFT JOIN {PRE}TicketDetails td
												  ON tb.nTId = td.nTicketId WHERE tb.nTId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $row1->nGDS;
                    }//end for each
                }//end if

                $typeGroup2    = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN
                                                   {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                                   sad ON sc.nSCId = sad.nSalesCloseId WHERE scd.vType = 'Tkt' AND scd.vTktNumber = '".$tkt."'
                                                   GROUP BY sc.nSCId");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['AccountApId']  = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountClose'] = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountHead']  = $row2->nAcHead;
                        $typeGroupRow['AgentAcc']     = $row2->nAgentAc;
                        $typeGroupRow['SalesDate']    = date('d-m-Y', strtotime($row2->dDate));
                    }//end for each
                }//end if
            break;

            case "Xo":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,xbd.vServicePercent as ServicePerVal,(SELECT SUM(xpd.vFCTax)
                                                    FROM {PRE}XoPassDetails xpd WHERE nXoId = '".$tkt."') as FareVal FROM
                                                    {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId = '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key=>$row)
                    {
                        $typeGroupRow['Employee']     = $row->vIssuedBy;
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->FareVal;
                        $typeGroupRow['Tax']          = $row->vGrandToalTax;
                        $typeGroupRow['NetAmt']       = $row->vGrandNetFare;
                        $typeGroupRow['ServPercent']  = $row->ServicePerVal;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nORCAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->vSalesAmt;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['Account']      = $row->nAccName;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT * FROM {PRE}XoBooking xb LEFT JOIN {PRE}XoPassDetails xpd ON
                                         					xpd.nXoId = xb.nXId LEFT JOIN {PRE}XoBillingDetails xbd ON
                                         					xbd.nXoId = xb.nXId WHERE xb.vActive='1' AND xb.nXId =  '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key=>$row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPass;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");
                    }//end for each
                }//end if

                $typeGroup2    = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN
                                                   {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                                   sad ON sc.nSCId = sad.nSalesCloseId WHERE scd.vType = 'Xo' AND scd.vTktNumber = '".$tkt."'
                                                   GROUP BY sc.nSCId");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['AccountApId']  = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountClose'] = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountHead']  = $row2->nAcHead;
                        $typeGroupRow['AgentAcc']     = $row2->nAgentAc;
                        $typeGroupRow['SalesDate']    = date('d-m-Y', strtotime($row2->dDate));
                    }//end for each
                }//end if
            break;

            case "Htl":
                $typeGroupRow = array();
                $typeGroup    = $this->db->query("SELECT *,hbd.vServicePercent as ServicePerVal FROM {PRE}HotelVoucher hv LEFT JOIN {PRE}HotelBillingDetails hbd
                                							on hbd.nHotelId = hv.nHVId WHERE hv.vActive='1'
														   AND hv.nHVId = '".$tkt."'");
                if ($typeGroup->num_rows() > 0)
                {
                    foreach ($typeGroup->result() as $key => $row)
                    {
                        $typeGroupRow['Employee']     = $row->vIssuedBy;
                        $typeGroupRow['Airline']      = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId = '".$row->nAirline."' ");
                        $typeGroupRow['Fare']         = $row->vTotalDC;
                        $typeGroupRow['Tax']          = $row->vTaxTotal;
                        $typeGroupRow['NetAmt']       = $row->nNetVendor;
                        $typeGroupRow['ServPercent']  = $row->ServicePerVal;
                        $typeGroupRow['ServAmt']      = $row->vServiceAmt;
                        $typeGroupRow['DiscAmt']      = $row->vDiscountAmt;
                        $typeGroupRow['commPer']      = $row->nComm;
                        $typeGroupRow['CommAmt']      = $row->nCommAmt;
                        $typeGroupRow['collected']    = $row->vCollected;
                        $typeGroupRow['SalesAmt']     = $row->vSalesAmt;
                        $typeGroupRow['agentAmt']     = $row->vAgentAmt;
                        $typeGroupRow['Account']      = $row->nAcCode;
                    }//end for each
                }//end if

                $typeGroup1    = $this->db->query("SELECT hv.* FROM {PRE}HotelVoucher hv
													LEFt JOIN {PRE}HotelBillingDetails hbd
												  ON hv.nHVId = hbd.nHotelId WHERE hv.nHVId = '".$tkt."'");
                if ($typeGroup1->num_rows() > 0)
                {
                    foreach ($typeGroup1->result() as $key => $row1)
                    {
                        $typeGroupRow['PassName']     = $row1->vPassenger;
                        $typeGroupRow['Sector1']      = $row1->vSector1;
                        $typeGroupRow['Sector2']      = $row1->vSector12;
                        $typeGroupRow['Sector3']      = $row1->vSector22;
                        $typeGroupRow['Sector4']      = $row1->vSector33;
                        $typeGroupRow['Sector5']      = $row1->vSector44;
                        $typeGroupRow['Segment']  	  = $row1->vSegment;
                        $typeGroupRow['Destination']  = $row1->vDestination;
                        $typeGroupRow['Gds']          = $this->common_model->get_field_name('Gds','vName'," WHERE nGId = '".$row1->nGDS."' ");

                        $typeGroupRow['checkin']      = $row1->dCheckIn;
                        $typeGroupRow['checkout']     = $row1->dCheckOut;
                        $typeGroupRow['nights']       = $row1->vNight;
                        $typeGroupRow['hotel']        = $row1->vHotelName;
                        $typeGroupRow['country']      = $row1->nCountry;
                        $typeGroupRow['city']         = $row1->nCity;
                    }//end for each
                }//end if

                $typeGroup2    = $this->db->query("SELECT sc.*,scd.*,sad.vTable,sad.nDrAccount FROM {PRE}SalesClosing sc LEFT JOIN
                                                   {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId LEFT JOIN {PRE}SalesAccountDetails
                                                   sad ON sc.nSCId = sad.nSalesCloseId WHERE scd.vType = 'Htl' AND scd.vTktNumber = '".$tkt."'
                                                   GROUP BY sc.nSCId");
                if ($typeGroup2->num_rows() > 0)
                {
                    foreach ($typeGroup2->result() as $key=>$row2)
                    {
                        $typeGroupRow['AccountApId']  = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountClose'] = $row2->nDrAccount.'-'.$row2->vTable;
                        $typeGroupRow['AccountHead']  = $row2->nAcHead;
                        $typeGroupRow['AgentAcc']     = $row2->nAgentAc;
                        $typeGroupRow['SalesDate']    = date('d-m-Y', strtotime($row2->dDate));
                    }//end for each
                }//end if
           break;
        }//end switch
        return ($typeGroupRow);
    }//end function

    function getClosedTicket($type)
    {
        if($type)
        {
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,tb.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId
														LEFT JOIN {PRE}TicketBooking tb ON scd.vTktNumber = tb.nTId WHERE scd.vType = 'Tkt'
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "Xo":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,xo.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId
														LEFT JOIN {PRE}XoBooking xo ON scd.vTktNumber = xo.nXId WHERE scd.vType = 'Xo'
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;

                case "Htl":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,hv.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId
														LEFT JOIN {PRE}HotelVoucher hv ON scd.vTktNumber = hv.nHVId WHERE scd.vType = 'Htl'
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                    break;
            }//end switch
        }//end if
        else
        {
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
    }//end function

    function getTicket($type)
    {
      $division = $this->input->post('ddlDivision');
      $location = $this->input->post('ddlLocation');
        if($type)
		{
            switch($type)
            {
                case "Tkt":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,tb.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId 
														LEFT JOIN {PRE}TicketBooking tb ON scd.vTktNumber = tb.nTId WHERE scd.vType = 'Tkt' AND sc.nDivision = " .$division . " AND sc.vLocation = " .$location ." AND
														 tb.nTId NOT IN
													    (SELECT rcd.nTktNum FROM {PRE}RefundRegisterDetails rcd WHERE rcd.vTktType = 'Tkt')
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nTId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                break;

                case "Xo":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,xo.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId 
														LEFT JOIN {PRE}XoBooking xo ON scd.vTktNumber = xo.nXId WHERE scd.vType = 'Xo'  AND sc.nDivision = " .$division . " AND sc.vLocation = "  .$location ." AND
														 xo.nXId NOT IN
													    (SELECT rcd.nTktNum FROM {PRE}RefundRegisterDetails rcd WHERE rcd.vTktType = 'Xo')
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nXId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                break;

                case "Htl":
                    $adminGroupRow  = array();
                    $adminGroupList = $this->db->query("SELECT sc.*,scd.*,hv.* FROM {PRE}SalesClosing sc LEFT JOIN {PRE}SalesClosingDetails scd
															ON sc.nSCId = scd.nSalesCloseId 
														LEFT JOIN {PRE}HotelVoucher hv ON scd.vTktNumber = hv.nHVId WHERE scd.vType = 'Htl'  AND sc.nDivision = " .$division . " AND sc.vLocation = "  .$location ." AND
														 hv.nHVId NOT IN
													    (SELECT rcd.nTktNum FROM {PRE}RefundRegisterDetails rcd WHERE rcd.vTktType = 'Htl')
														    ORDER BY nSCId DESC");
                    if ($adminGroupList->num_rows() > 0)
                    {
                        $adminGroupRow[0] = SELECT;
                        foreach ($adminGroupList->result() as $key => $row)
                        {
                            $adminGroupRow[$row->nHVId] = $row->vTicktManual;
                        }//end for each
                    } //end if
                    else
                    {
                        $adminGroupRow[0] = NOVALUE;
                    }//end else
                 break;
            }//end switch
        }//end if
        else
		{
            $adminGroupRow[0] = NOVALUE;
        }//end else
        return ($adminGroupRow);
    }//end function

    function getDocNum()
    {
		$this->txtMinDigit		= $this->common_model->get_field_name('Transactions','vMindigit'," WHERE nId='3'");
		$fetchCode          	= $this->db->query("SELECT vDocNumber FROM {PRE}RefundRegister ORDER BY nRId DESC LIMIT 0,1");
		if($fetchCode->num_rows() > 0):
			$totalNum           = $fetchCode->row();
			$this->txtDocNumber	= $totalNum->vDocNumber;
			$docNumber 			= @split('-',$this->txtDocNumber);
				
			#how many chars will be in the string
			$fill   			= $this->txtMinDigit;
			#the number
			$number 			= ($docNumber[3]+1);
			#with str_pad function the zeros will be added
			$lDigit 			= str_pad($number, $fill, '0', STR_PAD_LEFT);
			// The result: 000056
			
			$countVal 			= $docNumber[0].'-'.$docNumber[1].'-'.$docNumber[2].'-'.$lDigit;
		else:
			$this->txtDocNumber	= $this->common_model->get_field_name('Transactions','vSampledoc'," WHERE nId='3'");
			$countVal 			= $this->txtDocNumber;
		endif;
		
		return($countVal);
    }//end function

	function getVAccounts()
	{
		$accGroupRow = array();
		$accGroup    = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY nVId ASC");
		if ($accGroup->num_rows() > 0)
		{
			//$accGroupRow[0] = SELECT;
			foreach ($accGroup->result() as $key=>$row)
			{
				$accGroupRow[$row->nVId] = $row->vName.' ('.$row->vCode.' )';
			}//end for each
		}//end if
		else
		{
			$accGroupRow[0] = NOVALUE;
		}//end else
		return ($accGroupRow);
	}//end function 

    function getActiveAccountHead($accVal)
    {
        $accValSplited = @split("-",$accVal);
        $accGroup      = $accValSplited[0];
        $table         = $accValSplited[1];

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

    function getAccountHead()
    {
        $accheadGroupRow = array();
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

        return ($accheadGroupRow);
    }//end function

	function getActiveAccHead($accGroup)
	{
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
			//$accheadGroupRow[0] = SELECT;
			foreach ($accheadGroup->result() as $key=>$row)
			{
				$accheadGroupRow[$row->ahaId] = $row->vTitle;
			}//end for each
		}//end if
		else
		{
			$accheadGroupRow[0] = NOVALUE;
		}//end else

		return ($accheadGroupRow);
	}//end function

    function returnAirlineDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[]    = $row->vAirline;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnPaidAmount($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[] = $row->vNetAmt2;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnNetDetails($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                $salesCloseDetailsTicketNumber[]    = $row->vPaidTo;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnTicketType($sId)
    {
        $salesCloseDetailsTicketNumber = array();

        $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId ='".$sId."'");
        if ($creditDetailsList->num_rows() > 0)
        {
            foreach ($creditDetailsList->result() as $key=>$row)
            {
                switch($row->vTktType)
                {
                    case "Tkt":
                        $type = 'Ticket';
                        break;

                    case "Xo":
                        $type = 'Xo';
                        break;

                    case "Htl":
                        $type = 'Hotel';
                        break;
                }//end switch

                $salesCloseDetailsTicketNumber[]    = $type;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function

    function returnTktDetails($sId)
    {
      $salesCloseDetailsTicketNumber = array();

      $creditDetailsList = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId ='".$sId."'");
      if ($creditDetailsList->num_rows() > 0)
      {
        foreach ($creditDetailsList->result() as $key=>$row)
        {

          if($row->vNewOld == 'O') {
            $ticket = $row->nTktNum;
          }
          else {

            switch($row->vTktType)
            {
              case "Tkt":
                $ticket = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$row->nTktNum."'");
              break;

              case "Xo":
                $ticket = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$row->nTktNum."'");
                break;

                case "Htl":
                  $ticket = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$row->nTktNum."'");
                break;
              }//end switch
            }
                $salesCloseDetailsTicketNumber[]    = $ticket;
            }//end for each
        }//end if

        $newVal	 = @join(", ",$salesCloseDetailsTicketNumber);
        return ($newVal);
    }//end function


    function returnStatus($rId){
      //get ticket num and type
      $retStatus = array();
      $tkts = $this->db->query("SELECT * FROM {PRE}RefundRegisterDetails WHERE nRefundId ='".$rId."'");
      if($tkts->num_rows() >0){
        foreach($tkts->result() as $key=> $row){

          $status =  $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE vType = '". $row->vTktType ."' AND nTicketNumber = ". $row->nTktNum ."  ");
          if($status == '')
            $retStatus[] = 'OPEN';
          else
            $retStatus[] = 'CLOSED';


        } // end foreach

        $newVal  = @join(", ",$retStatus);
        return $newVal;
      }//end if
    }//end function

}//end class
?>
