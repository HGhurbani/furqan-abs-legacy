<?php

class Group_report_model extends CI_Model {



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



    function getActiveLocations()

    {

        $typeGroupRow    = array();

        $typeGroupRow[0] = SELECT;

        return ($typeGroupRow);

    }//end function



    function getPayableAccont()

    {

        $empGroup    = $this->db->query("SELECT nVId,nFId,vName,vCode FROM {PRE}VendorMaster WHERE vActive = '1' ORDER BY vCode ASC");

        $empGroupRow = array();

        if ($empGroup->num_rows() > 0)

        {

            $empGroupRow[0] = SELECT;

            foreach ($empGroup->result() as $key => $row)

            {

                $empGroupRow[$row->nVId] = stripslashes($row->vName).' ('.stripslashes($row->vCode).')';

            }//end for each

        }//end if

        else

        {

            $empGroupRow[0] = NOVALUE;

        }//end else

        return ($empGroupRow);

    }//end function



    function getActiveAirline()

    {

        $empGroup    = $this->db->query("SELECT nArId,vAirlineNo,vTitle FROM {PRE}Airline WHERE vActive = '1' ORDER BY vTitle ASC");

        $empGroupRow = array();



        if ($empGroup->num_rows() > 0)

        {

            $empGroupRow[0] = SELECT;

            foreach ($empGroup->result() as $key => $row)

            {

                $empGroupRow[$row->nArId] = stripslashes($row->vTitle).' ('.stripslashes($row->vAirlineNo).')';

            }//end for each

        }//end if

        else

        {

            $empGroupRow[0] = NOVALUE;

        }//end else

        return ($empGroupRow);

    }//end function



    function getActiveCountry()

    {

        $countryGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '0'

												ORDER BY vName ASC");

        $countryGroupRow = array();

        if ($countryGroup->num_rows() > 0)

        {

            $countryGroupRow[0] = SELECT;

            foreach ($countryGroup->result() as $key => $row)

            {

                $countryGroupRow[$row->nId] = stripslashes($row->vName);

            }//end for each

        }//end if

        else

        {

            $countryGroupRow[0] = NOVALUE;

        }//end else

        return ($countryGroupRow);

    }//end function



    function getCorrActiveCity()

    {

        $corrCityGroupRow[0] = NOVALUE;

        return ($corrCityGroupRow);

    }//end function



    function getActiveEmployee()

    {

        $empGroup    = $this->db->query("SELECT nEId,vCode,vName FROM {PRE}EmployeeMaster WHERE vActive = '1'

											ORDER BY vName ASC");

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



    function getActiveHotels()

    {

        $hotelGroup    = $this->db->query("SELECT nHId,vName FROM {PRE}Hotel WHERE vActive = '1' ORDER BY vName ASC");

        $hotelGroupRow = array();



        if ($hotelGroup->num_rows() > 0)

        {

            $hotelGroupRow[0] = SELECT;

            foreach ($hotelGroup->result() as $key => $row)

            {

                $hotelGroupRow[$row->nHId] = (stripslashes($row->vName));

            }//end for each

        }//end if

        else

        {

            $hotelGroupRow[0] = NOVALUE;

        }//end else

        return ($hotelGroupRow);

    }//end function

/*

    function getAccounts()

    {

        $typeGroupRow    = array();

        $vendorGroupRow  = array();

        $AgentGroupRow   = array();

        $OtherGroupRow   = array();

        $accheadGroupRow = array();



        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster ORDER BY vName ASC");

        if ($typeGroup->num_rows() > 0)

        {

            foreach ($typeGroup->result() as $key=>$row)

            {

                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if



        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster ORDER BY vName ASC");

        if ($vendorGroup->num_rows() > 0)

        {

            foreach ($vendorGroup->result() as $key=>$row)

            {

                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);



        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent ORDER BY vName ASC");

        if ($AgentGroup->num_rows() > 0)

        {

            foreach ($AgentGroup->result() as $key=>$row)

            {

                $AgentGroupRow[$row->nAId.'-'.'AG'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($AgentGroupRow,$resultArray);



        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other ORDER BY vName ASC");

        if ($OtherGroup->num_rows() > 0)

        {

            foreach ($OtherGroup->result() as $key=>$row)

            {

                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($OtherGroupRow,$resultArray);

 

	$accheadGroup = $this->db->query("SELECT aha.nId,aha.vTitle,aha.vACode FROM {PRE}AccountHeadAccounts aha

			INNER JOIN {PRE}AccountHeadControls ahc ON aha.nId = ahc.nActHId

			WHERE ahc.vSubLedgerType NOT IN ( 'V','C','E','A') ORDER BY aha.vTitle ");



        if ($accheadGroup->num_rows() > 0)

        {

            $accheadGroupRow[0] = SELECT;

            foreach ($accheadGroup->result() as $key1=>$row1)

            {

                $accheadGroupRow[$row1->nId.'-'.'A'] = $row1->vTitle.' - '.$row1->vACode;

            }//end for each

        }//end if



        else

        {

            $accheadGroupRow[0] = SELECT;

        }

        $resultArray = array_merge($accheadGroupRow,$resultArray);

	natcasesort($resultArray);

        return ($resultArray);

	

    }//end function

*/



    function getActiveSubAccounts()

    {

        $typeGroupRow    = array();

        $typeGroupRow[0] = NOVALUE;

        return ($typeGroupRow);

    }//end function





    function getActiveAcName()

    {

        $typeGroupRow = array();

        $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType

                                                FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah

                                              ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

                                                LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId

                                              WHERE v.nFId = '237' ORDER BY nId DESC");

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





    function filterSubAccount($accName)

    {

        $typeGroupRow    = array();

        $vendorGroupRow  = array();

        $AgentGroupRow   = array();

        $OtherGroupRow   = array();

        $typeGroupRow[0] = SELECT;



        $typeGroup       = $this->db->query("SELECT * FROM {PRE}CustomerMaster WHERE nFId = '".$accName."' ORDER BY nCId DESC");

        if ($typeGroup->num_rows() > 0)

        {

            foreach ($typeGroup->result() as $key=>$row)

            {

                $typeGroupRow[$row->nCId.'-'.'C'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if



        $vendorGroup     = $this->db->query("SELECT * FROM {PRE}VendorMaster WHERE nFId = '".$accName."' ORDER BY nVId DESC");

        if ($vendorGroup->num_rows() > 0)

        {

            foreach ($vendorGroup->result() as $key=>$row)

            {

                $vendorGroupRow[$row->nVId.'-'.'V'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($typeGroupRow,$vendorGroupRow);



        $AgentGroup      = $this->db->query("SELECT * FROM {PRE}Agent WHERE nFId = '".$accName."' ORDER BY nAId DESC");

        if ($AgentGroup->num_rows() > 0)

        {

            foreach ($AgentGroup->result() as $key=>$row)

            {

                $AgentGroupRow[$row->nAId.'-'.'A'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if

        $resultArray = array_merge($AgentGroupRow,$resultArray);



        $OtherGroup      = $this->db->query("SELECT * FROM {PRE}Other WHERE nFId = '".$accName."' ORDER BY nOId DESC");

        if ($OtherGroup->num_rows() > 0)

        {

            foreach ($OtherGroup->result() as $key=>$row)

            {

                $OtherGroupRow[$row->nOId.'-'.'O'] = $row->vName.' - '.$row->vCode;

            }//end for each

        }//end if



        $resultArray = array_merge($OtherGroupRow,$resultArray);



        if(empty($resultArray)):

            $resultArray[0] = NOVALUE;

        endif;



        return ($resultArray);

    }//end function







/*

    function getSubAccountValues()

    {

        $typeGroupRow    = array();

        $typeGroupRow[0] = SELECT;

        return($typeGroupRow);

    }//end function

*/

function filterSubstk($acc)

    {
		
		$nStocktype       = $acc;//$this->common_model->get_field_name('StockEntryDetails','nStocktype'," WHERE vCode = '".$acc."' ");
		 $custGroup    = $this->db->query("(SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='26' AND sed.nStocktype='".$nStocktype."'  )UNION ALL(SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='27' AND sed.nStocktype='".$nStocktype."')");
	

		

        $custGroupRow = array();



        if ($custGroup->num_rows() > 0) 

		{

            $custGroupRow[0] = SELECT;

            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupRow[$row->vCode] = $row->vCode ;

            }//end for each

        }//end if

        else 

		{

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);

	}

    function getActiveGDS()

    {

        $gdsGroupRow = array();

        $gdsGroup    = $this->db->query("SELECT * FROM {PRE}Gds ORDER BY nGId DESC");

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



    function mainSalesArray()

    {

        $mainSalesArrayList = array("All" => "All","Tkt" => "Ticket Sales","Xo" => "XO Sales","Htl" => "Hotel Sales");

        return ($mainSalesArrayList);

    }//end function



    function salesType()

    {

        $salesArray = array('TS' => 'Ticket Sales','TR' => 'Ticket Refunds','HS' => 'Hotel Sales','HR' => 'Hotel Refunds','XS' => 'XO Sales','XR' => 'XO Refunds','A' => 'ALL');

        return ($salesArray);

    }//end function



    function mainReportArray()

    {

        $mainReportArrayList = array("Details" => "Details","Summary" => "Summary","By Year" => "By Year","By Month" => "By Month");

        return ($mainReportArrayList);

    }//end function



    function mainRefundArray()

    {

        $mainRefundArrayList = array("All" => "All","Sales" => "Sales","Refund" => "Refund");

        return ($mainRefundArrayList);

    }//end function



    function mainStatusArray()

    {

        $mainStatusArrayList = array("Normal" => "Normal","Cunjuction" => "Cunjuction",

										"Reissued" => "Reissued","Void" => "Void", "All" => "All");

		ksort($mainStatusArrayList);

        return ($mainStatusArrayList);

    }//end function



    function filterLocation($division)

    {

        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'

											 AND nDivision = '".$division."'  AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nId ASC");

        $locGroupRow = array();

        if ($locGroup->num_rows() > 0)

        {
			if($locGroup->num_rows()==2 || $locGroup->num_rows()==3 || $locGroup->num_rows()==4){
				$locGroupRow[0] = SELECT;
			}
            

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



    function filterCity($country)

    {

        $locGroup    = $this->db->query("SELECT nId,vName FROM {PRE}Country WHERE vActive = '1' AND nParentId = '".$country."' ORDER BY nId DESC");

        $locGroupRow = array();

        if ($locGroup->num_rows() > 0)

        {

            $locGroupRow[0] = SELECT;

            foreach ($locGroup->result() as $key => $row)

            {

                $locGroupRow[$row->nId] = $row->vName;

            }//end for each

        }//end if

        else

        {

            $locGroupRow[0] = NOVALUE;

        }//end else

        return($locGroupRow);

    }//end function





// function used by hotel 

	function get_columns_hotel(){



			$columns_h = " 't1' as reportTable, scd.nSalesCloseId as nCloseId, hv.nHVId as tableId, sc.nSCId ,sc.vInvoiceNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy, ' 'as airline, hv.vAgentConfId as vGdspnr, hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(sc.dDate, '%d-%m-%Y') as dDate, hbd.vLPO as lpo,hv.vPassenger,'' as profit,'' as taxTotal,'' as cmtAmount,hv.vTotalDC as netAmt, '' as creditAmt,hv.vTotalRTDC as fareVal, scd.vCollected, scd.vServAmt as margin, sad.nDrAccount, sad.nAcHead, sad.vTable, sad.nId, 'S' as posting";

		

		return $columns_h;

	}



	function get_columns_ticket(){

	

			$columns_t = "'t1' as reportTable, scd.nSalesCloseId as nCloseId, tb.nTId as tableId, sc.nSCId ,sc.vInvoiceNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(sc.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,tb.vPassenger,'' as profit,tfd.vTaxTotal as taxTotal,tfd.nCommAmt as cmtAmount,tfd.nNetVendor as netAmt, tfd.nORCAmt as creditAmt,tfd.vFaceValue as fareVal, scd.vCollected, scd.vServAmt as margin, sad.nDrAccount, sad.nAcHead, sad.vTable, sad.nId, 'S' as posting ";

	

		return $columns_t;

	}

	function get_columns_xo(){

			$columns_x = "'t1' as reportTable, scd.nSalesCloseId as nCloseId, xo.nXId as tableId, sc.nSCId ,sc.vInvoiceNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy, xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xo.vTicktManual as tktNumber,DATE_FORMAT(sc.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,xpd.vPass as vPassenger,'' as profit,xpd.vTaxTotal as taxTotal,xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt, '' as creditAmt,xpd.vFCTax as fareVal, scd.vCollected, scd.vServAmt as margin, sad.nDrAccount, sad.nAcHead,sad.vTable, sad.nId, 'S' as posting ";

				

		return $columns_x;

	}											

									

	function get_columns_accounts()

	{

//		$columns_a = " 't2' as reportTable,sc.vLocation, sc.dDate, sad.nDrAccount, sad.nAcHead, sad.vTable, SUM( scd.vCollected ) as totalCollected, ahc.vSubSale as vSubSale ";



		$columns_a = " 't2' as reportTable,sc.vLocation, sc.dDate, sad.nDrAccount, sad.nAcHead, sad.vTable, SUM( sad.vAmt ) as totalCollected, ahc.vSubSale as vSubSale ";

		return $columns_a;

	}



// used for refund

	function get_columns_hotel_refund(){



			$columns_h = " 't1' as reportTable, rcd.nRefundCloseId as nCloseId, hv.nHVId as tableId, rc.nRCId as nSCId,rc.vDocNumber as vInvoiceNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy, ' 'as airline, hv.vAgentConfId as vGdspnr, hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate, hbd.vLPO as lpo,hv.vPassenger,'' as profit,'' as taxTotal,

			

		(select rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount, 

			

		-rcd.vNetAmt as netAmt, '' as creditAmt,-hv.vTotalRTDC as fareVal, -rcd.vPaidAmt as vCollected, -rcd.vServAmt as margin, rcd.nDrAcc as nDrAccount, 

		

		CASE 

			WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)

			WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)

			WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)

			WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)

			ELSE '0'

		END as nAcHead, 

		rcd.vTable, rcd.nId, 'R' as posting ";

		

		return $columns_h;

	}



	function get_columns_ticket_refund(){

	

			$columns_t = "'t1' as reportTable, rcd.nRefundCloseId as nCloseId,tb.nTId as tableId, rc.nRCId as nSCId,rc.vDocNumber as vInvoiceNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,tb.vPassenger,'' as profit,-tfd.vTaxTotal as taxTotal,

		

		(select rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount, 

		

		-rcd.vNetAmt as netAmt, -tfd.nORCAmt as creditAmt,-tfd.vFaceValue as fareVal, -rcd.vPaidAmt as vCollected, rcd.vServAmt as margin, rcd.nDrAcc as nDrAccount, 

		CASE 

			WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)

			WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)

			WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)

			WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)

			ELSE '0'

		END as nAcHead, 

		rcd.vTable, rcd.nId, 'R' as posting ";

	

		return $columns_t;

	}

	function get_columns_xo_refund(){

			$columns_x = "'t1' as reportTable,rcd.nRefundCloseId as nCloseId,xo.nXId as tableId, rc.nRCId as nSCId,rc.vDocNumber as vInvoiceNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy, xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus, xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xo.vTicktManual as tktNumber,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,xpd.vPass as vPassenger,'' as profit,-xpd.vTaxTotal as taxTotal, 

			

		(select rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount, 

		

		-rcd.vNetAmt as netAmt, '' as creditAmt,-xpd.vFCTax as fareVal, -rcd.vPaidAmt as vCollected, rcd.vServAmt as margin, rcd.nDrAcc as nDrAccount, 

		

		CASE 

			WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)

			WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)

			WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)

			WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)

			ELSE '0'

		END as nAcHead, 

		rcd.vTable, rcd.nId, 'R' as posting  ";

				

		return $columns_x;

	}											

									

	function get_columns_old_refund(){

			$columns_x = "'t1' as reportTable,rcd.nRefundCloseId as nCloseId,rrd.nRDId as tableId, rc.nRCId as nSCId,rc.vDocNumber as vInvoiceNumber,rr.nLocation,rrd.vTktType  as sale_type,rrd.vIssuedBy as vIssuedBy, (SELECT nArId FROM {PRE}Airline WHERE vAirlineNo = rrd.vAirline ) as airline, '' as vGdspnr, 'N' as vStatus, rrd.vSalesAmt as salesAmt,rrd.vDiscountAmt as discAmt, rrd.nTktNum  as tktNumber,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate, ' ' as lpo,rrd.vPassenger as vPassenger,'' as profit,-rrd.vTax as taxTotal, 

			

		rrd.vVendorAmt as cmtAmount, 

		

		-rcd.vNetAmt as netAmt, '' as creditAmt,-rrd.vFareAmt as fareVal, -rcd.vPaidAmt as vCollected, rcd.vServAmt as margin, rcd.nDrAcc as nDrAccount, 

		

		CASE 

			WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)

			WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)

			WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)

			WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)

			ELSE '0'

		END as nAcHead, 

		rcd.vTable, rcd.nId, 'R' as posting  ";

				

		return $columns_x;

	}		



	function get_columns_accounts_refund()

	{

		$columns_a = " 't2' as reportTable, rc.vLocation, rc.dDate, rcd.nDrAcc as nDrAccount, 

			CASE 

				WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)

				WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)

				WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)

				WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)

				ELSE '0'

			END as nAcHead, 

			rcd.vTable, SUM( -rcd.vPaidAmt ) as totalCollected, ahc.vSubSale as vSubSale ";

		return $columns_a;

	}



//used for sales and refund		

	function get_columns_credit_receipt() {

		$column_cr = " 't3' as reportTable, cr.nCRId as financeId, cr.dDate, cr.vDocNumber, cr.vRemarks, cr.vLocation, cr.vAmountDC as received, crd.vACode, crd.vAcSubCode, crd.vTable, crd.vMode, crd.nAmount, 'cashbook_receipt/editCashbookreceipt' as page , 'RECEIPT' as heading";

		return $column_cr;

	}
function get_columns_credit_receipt1() {

		$column_cr1 = " 't5' as reportTable, cr.nCRId as financeId, cr.dDate, cr.vDocNumber, cr.vRemarks, cr.vLocation, cr.vAmountDC as received, crd.vACode, crd.vAcSubCode, crd.vTable, crd.vMode, crd.nAmount, 'cashbook_receipt/editCashbookreceipt' as page , 'RECEIPT' as heading";

		return $column_cr1;

	}



	function get_columns_credit_payment() {

		$column_cp = " 't3' as reportTable, cp.nCPId as financeId, cp.dDate, cp.vDocNumber, cp.vRemarks, cp.vLocation, cp.vAmountDC as paid, cpd.vACode, cpd.vAcSubCode, cpd.vTable, cpd.vMode, cpd.nAmount,  'cashbook_payment/editCashbookpayment' as page, 'PAYMENT' as heading";

		return $column_cp;

	}
function get_columns_credit_payment1() {

		$column_cp1 = " 't5' as reportTable, cp.nCPId as financeId, cp.dDate, cp.vDocNumber, cp.vRemarks, cp.vLocation, cp.vAmountDC as paid, cpd.vACode, cpd.vAcSubCode, cpd.vTable, cpd.vMode, cpd.nAmount,ahc.vSubSale,'cashbook_payment/editCashbookpayment' as page, 'PAYMENT' as heading";

		return $column_cp1;

	}


function get_columns_bank_book() {

		$column_bd = " 't5' as reportTable, bb.nBBId as financeId,  bb.vTransMode as vMode, bb.dDate, bb.vDocNumber, bb.vRemarks, bb.vLocation, bb.vAmountDC as paid, bbd.vACode, bbd.vAcSubCode, bbd.vTable, bbd.nAmount,  'bankbook_entry/editBankbookentry' as page, 'PAYMENT' as heading";

		return $column_bd;

	}
	function get_columns_bank_book1() {

		$column_bd1 = " 't3' as reportTable, bb.nBBId as financeId,  bb.vTransMode as vMode, bb.dDate, bb.vDocNumber, bb.vRemarks, bb.vLocation, bb.vAmountDC as paid, bbd.vACode, bbd.vAcSubCode, bbd.vTable, bbd.nAmount,  'bankbook_entry/editBankbookentry' as page, 'PAYMENT' as heading";

		return $column_bd1;

	}
function get_columns_bank_payment() {

		$column_bp = " 't5' as reportTable, bp.nBPId as financeId,  bp.vTransMode as vMode, bp.dDate, bp.vDocNumber, bp.vRemarks, bp.vLocation, bp.vAmountDC as paid, bbp.vACode, bbp.vAcSubCode, bbp.vTable, bbp.nAmount,  'bank_payment/editBankbookpayment' as page, 'PAYMENT' as heading";

		return $column_bp;

	}

function get_columns_bank_payment1() {

		$column_bp1 = " 't3' as reportTable, bp.nBPId as financeId,  bp.vTransMode as vMode, bp.dDate, bp.vDocNumber, bp.vRemarks, bp.vLocation, bp.vAmountDC as paid, bbp.vACode, bbp.vAcSubCode, bbp.vTable, bbp.nAmount,  'bank_payment/editBankbookpayment' as page, 'PAYMENT' as heading";

		return $column_bp1;

	}




    function getReport($extraCheck_accounts, $extraCheck_finance_cr, $extraCheck_finance_cp, $extraCheck_accounts_r, $extraCheck_accounts_bb,$extraCheck_accounts_bp)

    {
//echo $extraCheck_accounts_bb;
		$column_h = $this->get_columns_hotel();

		$column_t = $this->get_columns_ticket();

		$column_x = $this->get_columns_xo();			



		$column_h_r = $this->get_columns_hotel_refund();

		$column_t_r = $this->get_columns_ticket_refund();

		$column_x_r = $this->get_columns_xo_refund();			

    $column_o_r = $this->get_columns_old_refund();





		$adminGroupListQuery = "(SELECT ". $column_t . ",(sad.vAmt-sad.vCommAmt)as vAmt
									FROM {PRE}TicketBooking tb 

									LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

									LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

									LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 

									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber = tb.nTId AND scd.vType = 'Tkt'

									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation

									WHERE tb.vConjStatus = 'Yes' and scd.vType = 'Tkt' AND sc.vActive = '1'"

									. $extraCheck_accounts ." )	

									

									UNION ALL (SELECT " . $column_h . " ,(sad.vAmt-sad.vCommAmt)as vAmt

									FROM {PRE}HotelVoucher hv 

									LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

									LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 

									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber = hv.nHVId AND scd.vType = 'Htl'

									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 											

									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation

									WHERE scd.vType = 'Htl' 

									AND hv.vActive='1' 

									AND sc.vActive = '1'

									".$extraCheck_accounts.")

																				

									UNION ALL (SELECT " . $column_x . ",(sad.vAmt-sad.vCommAmt) as vAmt 

									FROM {PRE}XoBooking xo

									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId 

									LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber = xo.nXId AND scd.vType = 'Xo'

									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 											

									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation

									WHERE scd.vType = 'Xo' AND sc.vActive = '1'

									".$extraCheck_accounts."  ) 



									UNION ALL (SELECT ". $column_t_r . " ,'' as vAmt

									FROM {PRE}TicketBooking tb 

									LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

									LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

									LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 

									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = tb.nTId  AND rcd.vType  = 'Tkt'

									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation

									WHERE rcd.vType = 'Tkt' 

									AND rc.vActive = '1'

									AND tb.vConjStatus = 'Yes' " 

									.$extraCheck_accounts_r." )	

									

									UNION ALL (SELECT " . $column_h_r . " ,'' as vAmt

									FROM {PRE}HotelVoucher hv 

									LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

									LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 

									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId AND rcd.vType = 'Htl'

									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation

									WHERE rcd.vType = 'Htl' 

									AND hv.vActive='1' 

									AND rc.vActive = '1'

									".$extraCheck_accounts_r.")

																				

									UNION ALL (SELECT " . $column_x_r . "  ,'' as vAmt

									FROM {PRE}XoBooking xo

									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId 

									LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = xo.nXId AND rcd.vType = 'Xo'

									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation

									WHERE rcd.vType = 'Xo' 

									AND rc.vActive = '1'

									".$extraCheck_accounts_r."  )



                  UNION ALL (SELECT " . $column_o_r . ",'' as vAmt

                  FROM {PRE}RefundRegister rr

                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'

                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId

                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline

                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

                   
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation

                    WHERE rr.vActive='1'

                  ".$extraCheck_accounts_r.")



									ORDER BY  posting DESC , vInvoiceNumber, sale_type ,nDrAccount  ASC";





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

		





		// generate table for accounts

		$column_a = $this->get_columns_accounts();

		$column_a_r = $this->get_columns_accounts_refund();

//	LEFT JOIN {PRE}SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId 

		$adminGroupListQuery4 = "SELECT  't2' as reportTable, SUM(totalCollected) as totalCollected, vLocation, 

									dDate, nDrAccount, nAcHead, vTable, vSubSale from

									(SELECT  " .  $column_a . " 

									FROM {PRE}SalesClosing sc 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 

									LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = sad.nDrAccount

 									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation 

									WHERE sc.vActive = '1' " . $extraCheck_accounts . "  

									GROUP BY nAcHead, nDrAccount, vTable, dDate, vLocation

									 

									UNION ALL 

									

									SELECT  " . $column_a_r . " 

									FROM {PRE}RefundClosing rc 

									LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation 

									LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc								

									WHERE rc.vActive = '1' " . $extraCheck_accounts_r . "

									GROUP BY nAcHead, nDrAccount, vTable, dDate, vLocation

									) as s_r

									GROUP BY nAcHead, nDrAccount, vTable, dDate, vLocation

									ORDER BY nAcHead, 

										CASE 

											WHEN vSubSale = 'C' THEN -3 

											WHEN vSubSale = 'B' THEN -2 

											ELSE nDrAccount 

										END,

									vTable, dDate, vLocation";

									



		$_SESSION['sess_query'] = $adminGroupListQuery4;

		$adminGroupList 		= $this->db->query($adminGroupListQuery4);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if





		//generate the table for finance 

		$column_cr = $this->get_columns_credit_receipt();

	    $adminGroupListQuery2 = " (Select " . $column_cr . " 

									FROM {PRE}CashReceipt cr 

									LEFT JOIN {PRE}CashReceiptDetails crd on cr.nCRId = crd.nCashId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cr.vLocation									

									WHERE cr.vActive = '1' " . $extraCheck_finance_cr . " 

									ORDER BY cr.nCRId)";

		$_SESSION['sess_query'] = $adminGroupListQuery2;

		$adminGroupList 		= $this->db->query($adminGroupListQuery2);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		
		
		$column_cr1 = $this->get_columns_credit_receipt1();

	    $adminGroupListQuery9 = " (Select " . $column_cr1 . " 

									FROM {PRE}CashReceipt cr 

									LEFT JOIN {PRE}CashReceiptDetails crd on cr.nCRId = crd.nCashId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cr.vLocation	
									
									LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = crd.vACode																		

									WHERE cr.vActive = '1' AND ahc.vSubSale='B'" . $extraCheck_finance_cr . " 								

								

									ORDER BY cr.nCRId)";

		$_SESSION['sess_query'] = $adminGroupListQuery9;

		$adminGroupList 		= $this->db->query($adminGroupListQuery9);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if

				

		$column_cp = $this->get_columns_credit_payment();

	     $adminGroupListQuery3 = " (Select " . $column_cp . " 

									FROM {PRE}CashPayment cp 

									LEFT JOIN {PRE}CashPaymentDetails cpd on cp.nCPId = cpd.nCashPId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cp.vLocation																		

									WHERE cp.vActive = '1' " . $extraCheck_finance_cp . " 

									ORDER BY cp.nCPId)";

		$_SESSION['sess_query'] = $adminGroupListQuery3;

		$adminGroupList 		= $this->db->query($adminGroupListQuery3);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
$column_cp1 = $this->get_columns_credit_payment1();

	     $adminGroupListQuery8 = " (Select " . $column_cp1 . " 

									FROM {PRE}CashPayment cp 

									LEFT JOIN {PRE}CashPaymentDetails cpd on cp.nCPId = cpd.nCashPId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cp.vLocation
									LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = cpd.vACode																		

									WHERE cp.vActive = '1' AND ahc.vSubSale='B'" . $extraCheck_finance_cp . " 

									ORDER BY cp.nCPId)";

		$_SESSION['sess_query'] = $adminGroupListQuery8;

		$adminGroupList 		= $this->db->query($adminGroupListQuery8);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if


$column_bd = $this->get_columns_bank_book();


	      $adminGroupListQuery5 = " (Select " . $column_bd . " 

									FROM {PRE}BankBook bb 

									LEFT JOIN {PRE}BankBookDetails bbd on bb.nBBId = bbd.nBankbookId 

									LEFT JOIN {PRE}Location loc ON loc.nId = bb.vLocation																		

									WHERE bb.vActive = '1'  " . $extraCheck_accounts_bb . " 

									ORDER BY bb.nBBId)";

		$_SESSION['sess_query'] = $adminGroupListQuery5;
		

		$adminGroupList 		= $this->db->query($adminGroupListQuery5);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		
		
		$column_bd1 = $this->get_columns_bank_book1();


	       $adminGroupListQuery7 = " (Select " . $column_bd1 . " 

									FROM {PRE}BankBook bb 

									LEFT JOIN {PRE}BankBookDetails bbd on bb.nBBId = bbd.nBankbookId 

									LEFT JOIN {PRE}Location loc ON loc.nId = bb.vLocation																		

									WHERE bb.vActive = '1' AND bbd.vTable='A'  " . $extraCheck_accounts_bb . " 

									ORDER BY bb.nBBId)";

		$_SESSION['sess_query'] = $adminGroupListQuery7;
//print $adminGroupListQuery7;
//echo $extraCheck_accounts_bb;
		$adminGroupList 		= $this->db->query($adminGroupListQuery7);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		
		
		$column_bp = $this->get_columns_bank_payment();


	      $adminGroupListQuery6 = " (Select " . $column_bp . " 

									FROM {PRE}BankBookPayment bp 

									LEFT JOIN {PRE}BankBookPaymentDetails bbp on bp.nBPId = bbp.nBankPaymentId 

									LEFT JOIN {PRE}Location loc ON loc.nId = bp.vLocation																		

									WHERE bp.vActive = '1' AND bp.vLocation='1'  " . $extraCheck_accounts_bp . " 

									ORDER BY bp.nBPId)";

		$_SESSION['sess_query'] = $adminGroupListQuery6;

		$adminGroupList 		= $this->db->query($adminGroupListQuery6);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		/*$column_bp1 = $this->get_columns_bank_payment1();


	      $adminGroupListQuery10 = " (Select " . $column_bp1 . " 

									FROM {PRE}BankBookPayment bp 

									LEFT JOIN {PRE}BankBookPaymentDetails bbp on bp.nBPId = bbp.nBankPaymentId 

									LEFT JOIN {PRE}Location loc ON loc.nId = bp.vLocation																		
									WHERE bp.vActive = '1' AND bbp.vTable='C' AND bp.vLocation='1'  " . $extraCheck_finance_bb . " 

									ORDER BY bp.nBPId)";

		$_SESSION['sess_query'] = $adminGroupListQuery10;

		$adminGroupList 		= $this->db->query($adminGroupListQuery10);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if*/
        return ($adminGroupRow);

    }//end function


	function getActive_stocks()

	{
		

	$custGroup    = $this->db->query("(SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName, sed.nStocktype
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='26' GROUP BY s.vName )UNION ALL(SELECT se.nSEId as seId,sed.nId as sedId,se.nLocation,se.nDivision,se.dDate,se.vDocNumber,
											 se.vYearRef,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName, sed.nStocktype
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype
											  WHERE s.nTransaction='27' GROUP BY s.vName)");
	

		

        $custGroupRow = array();



        if ($custGroup->num_rows() > 0) 

		{

            $custGroupRow[0] = SELECT;

            foreach ($custGroup->result() as $key => $row) 

			{

                $custGroupRow[$row->nStocktype] = $row->vName ;

            }//end for each

        }//end if

        else 

		{

            $custGroupRow[0] = NOVALUE;

        }//end else

        return ($custGroupRow);
		

	}//end function
	
	
function getgroupReport1($ddl,$dsl){
	
	
	  if($ddl!=''){
		  
		//$nStocktype       = $this->common_model->get_field_name('StockEntryDetails','nStocktype'," WHERE vCode = '".$ddl."' ");
		 if($dsl!=''){
			 $txt= " AND g.stk_t='".$dsl."'";
			 $txh= " AND  g.stk_h='".$dsl."' ";
			 
		 }else{
			 $txt= " AND sed.nStocktype='".$ddl."' ";
			 $txh= " AND sed.nStocktype='".$ddl."' ";
		 }
		 
		  
		    $adminGroupListQuery2 = " (SELECT sum(g.tot_t) as cont,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock 
					LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype

					LEFT JOIN tbl_Groups_Entry g ON g.stk_t = sed.vCode

											  WHERE s.nTransaction='26' ".$txt." GROUP BY sed.vCode )
											  
											  
											  UNION ALL(SELECT sum(g.h_tot) as cont,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock 
					LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype

					LEFT JOIN tbl_Groups_Entry g ON g.stk_h = sed.vCode

											  WHERE s.nTransaction='27' ".$txh." GROUP BY sed.vCode)";
		  
	  }else{
		  $adminGroupListQuery2 = " (SELECT sum(g.tot_t) as cont,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock 
					LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype

					LEFT JOIN tbl_Groups_Entry g ON g.stk_t = sed.vCode

											  WHERE s.nTransaction='26' AND sed.vCode=g.stk_t GROUP BY sed.vCode)
											  
											  
											  UNION ALL(SELECT sum(g.h_tot) as cont,sed.vCode,sed.vStartNo,sed.vEndNo,sed.nQuantity,s.vName
											 FROM tbl_StockEntry se LEFT JOIN tbl_StockEntryDetails sed
											  ON se.nSEId = sed.nStock 
					LEFT JOIN tbl_Stock s ON s.nStkId = sed.nStocktype

					LEFT JOIN tbl_Groups_Entry g ON g.stk_h = sed.vCode

											  WHERE s.nTransaction='27' AND sed.vCode=g.stk_h GROUP BY sed.vCode)";
		  
		  
	  }
	  

		$_SESSION['sess_query'] = $adminGroupListQuery2;

		$adminGroupList 		= $this->db->query($adminGroupListQuery2);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		 return ($adminGroupRow);
	
}

function getgroupReport_stk($extraCheck_finance_cr,$ddl){
	
	if($ddl!=''){
		
		 $t   = $this->common_model->get_field_name('Groups_Entry','stk_t'," WHERE t_g='".$ddl."' GROUP BY stk_t");
		  $h   = $this->common_model->get_field_name('Groups_Entry','stk_h'," WHERE h_g='".$ddl."' GROUP BY stk_h");
		
		if($t!=''){
			 $adminGroupListQuery2 = " (Select gr.*,'0' as h_sgl,'0' as h_dbl,'0' as h_tpl,'0' as h_suite,'0' as h_oths,'0' as h_tot

									FROM {PRE}Groups_Entry gr 
									LEFT JOIN {PRE}Location loc ON loc.nId = gr.loc									
									
									WHERE 1 = '1' " . $extraCheck_finance_cr . " 

									ORDER BY gr.id)";
			
		}else{
			$adminGroupListQuery2 = " (Select gr.*,'0' as t_adt,'0' as t_cld,'0' as t_inf,'0' as tot_t

									FROM {PRE}Groups_Entry gr 
									LEFT JOIN {PRE}Location loc ON loc.nId = gr.loc									
									
									WHERE 1 = '1' " . $extraCheck_finance_cr . " 

									ORDER BY gr.id)";
			
		}
		
	}else{
		
		$adminGroupListQuery2 = " (Select gr.*

									FROM {PRE}Groups_Entry gr 
									LEFT JOIN {PRE}Location loc ON loc.nId = gr.loc									
									
									WHERE 1 = '1' " . $extraCheck_finance_cr . " 

									ORDER BY gr.id)";
	}
	  

		$_SESSION['sess_query'] = $adminGroupListQuery2;

		$adminGroupList 		= $this->db->query($adminGroupListQuery2);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		 return ($adminGroupRow);
	
}

function getgroupReport($extraCheck_finance_cr){
	
	
	  $adminGroupListQuery2 = " (Select gr.*

									FROM {PRE}Groups_Entry gr 
									LEFT JOIN {PRE}Location loc ON loc.nId = gr.loc									
									
									WHERE 1 = '1' " . $extraCheck_finance_cr . " 

									ORDER BY gr.id)";

		$_SESSION['sess_query'] = $adminGroupListQuery2;

		$adminGroupList 		= $this->db->query($adminGroupListQuery2);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if
		 return ($adminGroupRow);
	
}




    function getSalesReport($extraCheck_accounts,$extraCheck_finance_cr,$extraCheck_finance_cp)

    {

		$column_h = $this->get_columns_hotel();

		$column_t = $this->get_columns_ticket();

		$column_x = $this->get_columns_xo();			



		$adminGroupListQuery = "(SELECT ". $column_t . " 

									FROM {PRE}TicketBooking tb 

									LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

									LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

									LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 

									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber = tb.nTId AND scd.vType = 'Tkt'

									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation

									WHERE tb.vConjStatus = 'Yes' and scd.vType = 'Tkt' AND sc.vActive = '1'"

									.$extraCheck_accounts." )	

									

									UNION ALL (SELECT " . $column_h . " 

									FROM {PRE}HotelVoucher hv 

									LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

									LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 

									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber = hv.nHVId AND scd.vType = 'Htl'

									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 											

									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation

									WHERE scd.vType = 'Htl' 

									AND hv.vActive='1' 

									AND sc.vActive = '1'

									".$extraCheck_accounts.")

																				

									UNION ALL (SELECT " . $column_x . "  

									FROM {PRE}XoBooking xo

									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId 

									LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

									INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber = xo.nXId AND scd.vType = 'Xo'

									LEFT JOIN {PRE}SalesClosing sc ON sc.nSCId = scd.nSalesCloseId 

									LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId 											

									LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation

									WHERE scd.vType = 'Xo' AND sc.vActive = '1'

									".$extraCheck_accounts."  ) 

									ORDER BY vInvoiceNumber, sale_type ,nDrAccount  ASC";

									

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

		





		// generate table for accounts

		$column_a = $this->get_columns_accounts();

		$adminGroupListQuery4 = " SELECT " . $column_a . " 

								FROM {PRE}SalesClosing sc

								LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

								LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = sad.nDrAccount

								LEFT JOIN {PRE}Location loc ON loc.nId = sc.vLocation								

								WHERE sc.vActive = '1' " . $extraCheck_accounts . " 

								GROUP BY sad.nAcHead, sad.nDrAccount, sad.vTable, sc.dDate, sc.vLocation

								ORDER BY sad.nAcHead,

									CASE 

										WHEN ahc.vSubSale = 'C' THEN -2 

										WHEN ahc.vSubSale = 'B' THEN -1 

										ELSE sad.nDrAccount 

									END,

									sad.vTable, sc.dDate, sc.vLocation ";

		$_SESSION['sess_query'] = $adminGroupListQuery4;

		$adminGroupList 		= $this->db->query($adminGroupListQuery4);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if





		//generate the table for finance 

		$column_cr = $this->get_columns_credit_receipt();

	    $adminGroupListQuery2 = " (Select " . $column_cr . " 

									FROM {PRE}CashReceipt cr 

									LEFT JOIN {PRE}CashReceiptDetails crd on cr.nCRId = crd.nCashId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cr.vLocation									

									WHERE cr.vActive = '1' " . $extraCheck_finance_cr . " 

									ORDER BY cr.nCRId)";

		$_SESSION['sess_query'] = $adminGroupListQuery2;

		$adminGroupList 		= $this->db->query($adminGroupListQuery2);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if

				

		$column_cp = $this->get_columns_credit_payment();

	    $adminGroupListQuery3 = " (Select " . $column_cp . " 

									FROM {PRE}CashPayment cp 

									LEFT JOIN {PRE}CashPaymentDetails cpd on cp.nCPId = cpd.nCashPId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cp.vLocation																		

									WHERE cp.vActive = '1' " . $extraCheck_finance_cp . " 

									ORDER BY cp.nCPId)";

		$_SESSION['sess_query'] = $adminGroupListQuery3;

		$adminGroupList 		= $this->db->query($adminGroupListQuery3);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if



        return ($adminGroupRow);

    }//end function







    function getRefundReport($extraCheck_accounts_r,$extraCheck_finance_cr,$extraCheck_finance_cp)

    {



		$column_h_r = $this->get_columns_hotel_refund();

		$column_t_r = $this->get_columns_ticket_refund();

    $column_o_r = $this->get_columns_old_refund();

		$column_x_r = $this->get_columns_xo_refund();			





		$adminGroupListQuery = "(SELECT ". $column_t_r . " 

									FROM {PRE}TicketBooking tb 

									LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

									LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

									LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

									LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = tfd.nAcc 

									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = tb.nTId AND rcd.vType = 'Tkt'

									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation

									WHERE rcd.vType = 'Tkt' 

									AND rc.vActive = '1'

									AND tb.vConjStatus = 'Yes' " 

									.$extraCheck_accounts_r." )	

									

									UNION ALL (SELECT " . $column_h_r . " 

									FROM {PRE}HotelVoucher hv 

									LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

									LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId

									LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = hv.nAcCode 

									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = hv.nHVId AND rcd.vType = 'Htl'

									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation

									WHERE rcd.vType = 'Htl' 

									AND hv.vActive='1' 

									AND rc.vActive = '1'

									".$extraCheck_accounts_r.")

																				

									UNION ALL (SELECT " . $column_x_r . "  

									FROM {PRE}XoBooking xo

									LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId 

									LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

									INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = xo.nXId AND rcd.vType = 'Xo'

									LEFT JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId 

									LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation

									WHERE rcd.vType = 'Xo' 

									AND rc.vActive = '1'

									".$extraCheck_accounts_r."  ) 



                  UNION ALL (SELECT "  . $column_o_r . "

                  FROM {PRE}RefundRegister rr

                  LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'

                  INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType

                   INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

                  INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

                    LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation

                    WHERE rr.vActive='1'

                  ".$extraCheck_accounts_r.")



									ORDER BY vInvoiceNumber, sale_type ,nDrAccount  ASC";

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

		





		// generate table for accounts

		// no cash on hand case for refund

		$column_a_r = $this->get_columns_accounts_refund();

		$adminGroupListQuery4 = " SELECT " . $column_a_r . " 

								FROM {PRE}RefundClosing rc

								LEFT JOIN {PRE}RefundClosingDetails rcd ON rc.nRCId = rcd.nRefundCloseId

								LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc								

								LEFT JOIN {PRE}Location loc ON loc.nId = rc.vLocation								

								WHERE rc.vActive = '1' " . $extraCheck_accounts_r . " 

								GROUP BY rcd.nDrAcc, rcd.vTable, rc.dDate, rc.vLocation

								ORDER BY 

									CASE 

										WHEN ahc.vSubSale = 'C' THEN -3 

										WHEN ahc.vSubSale = 'B' THEN -2 

										ELSE rcd.nDrAcc 

									END, 

									rcd.vTable, rc.dDate, rc.vLocation ";



		$_SESSION['sess_query'] = $adminGroupListQuery4;

		$adminGroupList 		= $this->db->query($adminGroupListQuery4);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if



		//generate the table for finance 

		$column_cr = $this->get_columns_credit_receipt();

	    $adminGroupListQuery2 = " (Select " . $column_cr . " 

									FROM {PRE}CashReceipt cr 

									LEFT JOIN {PRE}CashReceiptDetails crd on cr.nCRId = crd.nCashId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cr.vLocation									

									WHERE cr.vActive = '1' " . $extraCheck_finance_cr . " 

									ORDER BY cr.nCRId)";

		$_SESSION['sess_query'] = $adminGroupListQuery2;

		$adminGroupList 		= $this->db->query($adminGroupListQuery2);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if

				

		$column_cp = $this->get_columns_credit_payment();

	    $adminGroupListQuery3 = " (Select " . $column_cp . " 

									FROM {PRE}CashPayment cp 

									LEFT JOIN {PRE}CashPaymentDetails cpd on cp.nCPId = cpd.nCashPId 

									LEFT JOIN {PRE}Location loc ON loc.nId = cp.vLocation																		

									WHERE cp.vActive = '1' " . $extraCheck_finance_cp . " 

									ORDER BY cp.nCPId)";

		$_SESSION['sess_query'] = $adminGroupListQuery3;

		$adminGroupList 		= $this->db->query($adminGroupListQuery3);

		if ($adminGroupList->num_rows() > 0)

		{

			foreach ($adminGroupList->result() as $key=>$row)

			{

				$adminGroupRow[] = $row;

			}//end for each

		} //end if



        return ($adminGroupRow);

    }//end function







//////////////////////////////////////////////////////////////////

///////////////////////////////////////////////////////////////////////

// Special report fo flyyworld

///////////////////////////////////////////////////////////////////////////



		

// columns for sales





	function get_columns_hotel_sales(){

		$columns_h = "'' as cmtAmount,hv.vTotalDC as netAmt, hv.nHVId,hv.vDocNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate_txh, hv.nAcCode as account, hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as creditAmt,hv.vTotalRTDC as fareVal ";



		return $columns_h;

	}

	

	function get_columns_ticket_sales(){

		$columns_t = "tfd.nCommAmt as cmtAmount	,tfd.nNetVendor as netAmt, tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(tb.dDate, '%d-%m-%Y') as dDate_txh,tfd.nAcc as account, ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,tfd.vTaxTotal as taxTotal,tfd.nORCAmt as creditAmt, tfd.vFaceValue as fareVal  ";



		return $columns_t; 											

	}

	

	function get_columns_xo_sales(){

		$columns_x = "xpd.nORCAmt as cmtAmount,xpd.nNetVendor as netAmt, xo.nXId as nTId,xo.vDocNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate_txh,xo.nPassengerAc as account,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId,xpd.vTaxTotal as taxTotal,'' as creditAmt,xpd.vFCTax as fareVal  ";



		return $columns_x;

	}

	

	

// columns for refund





	function get_columns_hotel_refund_s(){

		$columns_h = "hv.nHVId,hv.vDocNumber,hv.nLocation,'Hotel' as sale_type,hv.vIssuedBy as vIssuedBy,' ' as airline, hv.vAgentConfId as vGdspnr , hv.vType as vStatus, hbd.vSalesAmt as salesAmt,hbd.vDiscountAmt as discAmt,hbd.vServicePercent as ServicePercent,hbd.vServiceAmt as ServiceAmt,hv.vTicktManual as tktNumber,DATE_FORMAT(hv.dDate, '%d-%m-%Y') as dDate_txh, hv.nAcCode as account, hbd.vLPO as lpo,hv.vPassenger,hbd.vCollected as collected,' ' as profit,hv.nHVId as tableId,'' as taxTotal,'' as creditAmt,-hv.vTotalRTDC as fareVal ";



		return $columns_h;

	}

	

	function get_columns_ticket_refund_s(){

		$columns_t = "tb.nTId,tb.vDocNumber,tb.nLocation,'Ticket' as sale_type,tb.vIssuedBy as vIssuedBy, tb.nAirline as airline, tb.vGdspnr, tb.vType as vStatus, tbd.vSalesAmt as salesAmt,tbd.vDiscountAmt as discAmt,tbd.vServicePercent as ServicePercent, tbd.vServiceAmt as ServiceAmt,tb.vTicktManual as tktNumber,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate_txh,tfd.nAcc as account, ' ' as lpo,tb.vPassenger,tfd.nNetVendor as collected,'' as profit,tb.nTId as tableId,-tfd.vTaxTotal as taxTotal,-tfd.nORCAmt as creditAmt,-tfd.vFaceValue as fareVal  ";



		return $columns_t; 											

	}

	

	function get_columns_xo_refund_s(){

		$columns_x = "xo.nXId as nTId,xo.vDocNumber,xo.nLocation,'Xo' as sale_type,xo.vIssuedBy as vIssuedBy,xo.nAirline as airline, xo.vGdspnr, xo.vType as vStatus,xbd.vSalesAmt as salesAmt,xbd.vDiscountAmt as discAmt,xbd.vServicePercent as ServicePercent,xbd.vServiceAmt as ServiceAmt, xo.vTicktManual as tktNumber,DATE_FORMAT(xo.dDate, '%d-%m-%Y') as dDate_txh,xo.nPassengerAc as account,' ' as lpo,xpd.vPass as vPassenger,xbd.vCollected as collected,' ' as profit,xo.nXId as tableId, -xpd.vTaxTotal as taxTotal,'' as creditAmt, -xpd.vFCTax as fareVal  ";



		return $columns_x;

	}

	

  function get_columns_old_refund_s(){

    $columns_o = "rr.nRId as nTId,'' as vDocNumber,rr.nLocation, rrd.vTktType as sale_type,rrd.vIssuedBy as vIssuedBy, ar.nArId as airline, '' as vGdspnr, 'N' as vStatus, rrd.vSalesAmt as salesAmt,rrd.vDiscountAmt as discAmt,rrd.vServicePer as ServicePercent,rrd.vServiceAmt as ServiceAmt, rrd.nTktNum as tktNumber,DATE_FORMAT(rr.dDate, '%d-%m-%Y') as dDate_txh, rr.nAccount as account,' ' as lpo,rrd.vPassenger as vPassenger,rrd.vSalesAmt as collected,' ' as profit,rrd.nRDId as tableId, -rrd.vTax as taxTotal,'' as creditAmt, -rrd.vFareAmt as fareVal  ";



    return $columns_o;

  }





	function get_columns_sales(){

			$columns_s = " 'sales' as refundtype,DATE_FORMAT(sc.dDate, '%d-%m-%Y') as dDate, scd.vCollected as salesAmount, ROUND(scd.vServAmt,3) as Profit, ROUND(scd.vServPercent,3) as ProfitPercent,

				'' as nDrAccount, '' as nAcHead, '' as vTable,

			 CONCAT('index.php/sales_closing/editSalesClosing/4/e/',scd.nId,'/',scd.nSalesCloseId,'/cl' ) as link, scd.nSalesCloseId  as closedId, ";

			

			



		return $columns_s;

	}

	

	function get_columns_refund(){

		$columns_r = " 'refund' as refundtype,DATE_FORMAT(rc.dDate, '%d-%m-%Y') as dDate, -rcd.vPaidAmt as salesAmount, ROUND(-rcd.vServAmt,3) as Profit, ROUND(-rcd.vServPercent,3) as ProfitPercent,

		

		rcd.nDrAcc as nDrAccount, 

		

		CASE 

			WHEN rcd.vTable = 'AG' THEN (select temp.nFId from {PRE}Agent temp where temp.nAId = rcd.nDrAcc)

			WHEN rcd.vTable = 'C' THEN (select temp.nFId from {PRE}CustomerMaster temp where temp.nCId = rcd.nDrAcc)

			WHEN rcd.vTable = 'V' THEN (select temp.nFId from {PRE}VendorMaster temp where temp.nVId = rcd.nDrAcc)

			WHEN rcd.vTable = 'O' THEN (select temp.nFId from {PRE}Other temp where temp.nOId = rcd.nDrAcc)

			ELSE '0'

		END as nAcHead, 

		rcd.vTable as vTable,

		

CONCAT('index.php/refund_closing/editRefundClosing/4/e/',rcd.nRefundCloseId) as link, rcd.nRefundCloseId as closedId,	(select -rrd.vVendorAmt from {PRE}RefundRegisterDetails rrd where rcd.nTicketNumber = rrd.nTktNum and rrd.vTktType = rcd.vType) as cmtAmount,

						-rcd.vNetAmt as netAmt,	";

						

		return $columns_r;

	}









    function getAllReport_sales($extraCheck_s,$extraCheck_r, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or)

    {



		$column_t_s = $this->get_columns_ticket_sales();

		$column_x_s = $this->get_columns_xo_sales();

		$column_h_s = $this->get_columns_hotel_sales();



		$column_t_r = $this->get_columns_ticket_refund_s();

		$column_x_r = $this->get_columns_xo_refund_s();

		$column_h_r = $this->get_columns_hotel_refund_s();

    $column_o_r = $this->get_columns_old_refund_s();



		$column_s = $this->get_columns_sales();

		$column_r = $this->get_columns_refund();



/*		if($extraCheck_t_or != '') $extraCheck_t = " AND " . $extraCheck_t_or ." ";

		if($extraCheck_x_or != '') $extraCheck_x = " AND " . $extraCheck_x_or ." ";

		if($extraCheck_h_or != '') $extraCheck_h = " AND " . $extraCheck_h_or ." ";

*/

                 $adminGroupListQuery = " SELECT " . $column_s . " " . $column_t_s . ",(sad.vAmt)as vAmt

										 FROM {PRE}TicketBooking tb 

										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId  AND scd.vType = 'Tkt' 

                     INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId

                  LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

		 								 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 

										 WHERE tb.vConjStatus = 'Yes' 

										" .$extraCheck_s." 

										" .$extraCheck_t." 

										

										

										UNION ALL (SELECT " . $column_s . " " . $column_x_s . " ,(sad.vAmt)as vAmt

										FROM {PRE}XoBooking xo 

										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId

                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 

										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId  AND scd.vType = 'Xo'

                     INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId

                  LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										

										WHERE xo.vActive='1' 

										" .$extraCheck_s."  

										" .$extraCheck_x." )

											

										

										UNION ALL (SELECT " . $column_s . " " . $column_h_s . ",(sad.vAmt)as vAmt 

										FROM {PRE}HotelVoucher hv 

										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 

										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId  AND scd.vType = 'Htl'

                     INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId

                  LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

 									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										

									    WHERE hv.vActive='1' 

										" .$extraCheck_s." 

										" .$extraCheck_h." ) 

										

										UNION ALL (SELECT " . $column_r . " " . $column_t_r . ",''as vAmt

										 FROM {PRE}TicketBooking tb 

										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId  AND rcd.vType  = 'Tkt'

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

 										 

									     LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation										 

 										 WHERE tb.vConjStatus = 'Yes'  

										" .$extraCheck_r." 

										" .$extraCheck_t." )

										

										

										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " ,''as vAmt

										FROM {PRE}XoBooking xo 

										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId

                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 

										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId  AND rcd.vType  = 'Xo'

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

									    LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation										 										

										WHERE xo.vActive='1' 

										" .$extraCheck_r."  

										" .$extraCheck_x." ) 

											

										

										UNION ALL (SELECT " . $column_r . " " . $column_h_r . ",''as vAmt 

										FROM {PRE}HotelVoucher hv 

										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 

										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

									    LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation										 										

									    WHERE hv.vActive='1' 

										" .$extraCheck_r."  

										" .$extraCheck_h." ) 

										

                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . ",''as vAmt

                    FROM {PRE}RefundRegister rr

                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'

                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId

                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline

                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

                    
                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation

                      WHERE rr.vActive='1'

                      " .$extraCheck_r."

                      " .$extraCheck_or.")



										  ORDER BY " . $orderby;



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









    function getSalesReport_sales($extraCheck_s, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h)

    {



										

		$column_t_s = $this->get_columns_ticket_sales();

		$column_x_s = $this->get_columns_xo_sales();

		$column_h_s = $this->get_columns_hotel_sales();



		$column_s = $this->get_columns_sales();



/*		if($extraCheck_t_or != '') $extraCheck_t = " AND " . $extraCheck_t_or ." ";

		if($extraCheck_x_or != '') $extraCheck_x = " AND " . $extraCheck_x_or ." ";

		if($extraCheck_h_or != '') $extraCheck_h = " AND " . $extraCheck_h_or ." ";

	*/			

                $adminGroupListQuery = "(SELECT " . $column_s . " " . $column_t_s . "

										 FROM {PRE}TicketBooking tb 

										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

										 INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  tb.nTId  AND scd.vType = 'Tkt' 

                     INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId

                  LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation

										 WHERE tb.vConjStatus = 'Yes'  

										" .$extraCheck_t." 

										" .$extraCheck_s.") 

										

										

										UNION ALL (SELECT " . $column_s . " " . $column_x_s . " 

										FROM {PRE}XoBooking xo 

										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId

                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 

                    INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  xo.nXId  AND scd.vType = 'Xo'

                     INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId

                    LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation

										WHERE xo.vActive='1' 

										" .$extraCheck_x." 

										" .$extraCheck_s." ) 

											

										

										UNION ALL (SELECT " . $column_s . " " . $column_h_s . " 

										FROM {PRE}HotelVoucher hv 

										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 

										INNER JOIN {PRE}SalesClosingDetails scd ON scd.vTktNumber =  hv.nHVId  AND scd.vType = 'Htl'

                     INNER JOIN {PRE}SalesClosing sc ON sc.nSCId  =  scd.nSalesCloseId

                     LEFT JOIN {PRE}SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId

										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation

									    WHERE hv.vActive='1' 

										" .$extraCheck_h." 

										" .$extraCheck_s." )

										

										 ORDER BY " . $orderby . " ";

				print $adminGroupListQuery;							

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











    function getRefundReport_sales($extraCheck_r, $orderby,$extraCheck_t,$extraCheck_x,$extraCheck_h,$extraCheck_or)

    {

										

		$column_t_r = $this->get_columns_ticket_refund_s();

		$column_x_r = $this->get_columns_xo_refund_s();

		$column_h_r  = $this->get_columns_hotel_refund_s();

    $column_o_r = $this->get_columns_old_refund_s();



		$column_r = $this->get_columns_refund();



/*		if($extraCheck_t_or != '') $extraCheck_t = " AND " . $extraCheck_t_or ." ";

		if($extraCheck_x_or != '') $extraCheck_x = " AND " . $extraCheck_x_or ." ";

		if($extraCheck_h_or != '') $extraCheck_h = " AND " . $extraCheck_h_or ." ";

*/

                $adminGroupListQuery = "(SELECT " . $column_r . " " . $column_t_r . "

										 FROM {PRE}TicketBooking tb 

										 LEFT JOIN {PRE}TicketFareDetails tfd ON tb.nTId = tfd.nTicketId 

										 LEFT JOIN {PRE}TicketBillingDetails tbd ON tb.nTId = tbd.nTicketId 

										 LEFT JOIN {PRE}Airline ar ON ar.nArId = tb.nAirline 

										 LEFT JOIN {PRE}EmployeeMaster em ON tb.vIssuedBy = em.nEId

										 INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  tb.nTId AND rcd.vType  = 'Tkt'

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

 										 INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

										 LEFT JOIN {PRE}Location loc ON loc.nId = tb.nLocation

 										 WHERE tb.vConjStatus = 'Yes'  

										" .$extraCheck_t." 

										" .$extraCheck_r." ) 

										

										

										UNION ALL (SELECT " . $column_r . " " . $column_x_r . " 

										FROM {PRE}XoBooking xo 

										LEFT JOIN {PRE}XoBillingDetails xbd ON xo.nXId = xbd.nXoId

                                        LEFT JOIN {PRE}XoPassDetails xpd ON xo.nXId = xpd.nXoId

										LEFT JOIN {PRE}EmployeeMaster em ON xo.vIssuedBy = em.nEId 

										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  xo.nXId AND rcd.vType  = 'Xo'

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

										LEFT JOIN {PRE}Location loc ON loc.nId = xo.nLocation

										WHERE xo.vActive='1' 

										" .$extraCheck_x." 

										" .$extraCheck_r." ) 

											

										

										UNION ALL (SELECT " . $column_r . " " . $column_h_r . " 

										FROM {PRE}HotelVoucher hv 

										LEFT JOIN {PRE}HotelBillingDetails hbd ON hv.nHVId = hbd.nHotelId 

										LEFT JOIN {PRE}EmployeeMaster em ON hv.vIssuedBy = em.nEId 

										INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber =  hv.nHVId AND rcd.vType  = 'Htl'

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

										INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

										LEFT JOIN {PRE}Location loc ON loc.nId = hv.nLocation

									    WHERE hv.vActive='1' 

										" .$extraCheck_h." 

										" .$extraCheck_r." ) 

										

                    UNION ALL (SELECT " . $column_r . " " . $column_o_r . "

                    FROM {PRE}RefundRegister rr

                    LEFT JOIN {PRE}RefundRegisterDetails rrd ON rrd.nRefundId = rr.nRId AND rrd.vNewOld = 'O'

                    LEFT JOIN {PRE}EmployeeMaster em ON rrd.vIssuedBy = em.nEId

                     LEFT JOIN {PRE}Airline ar ON ar.vAirlineNo = rrd.vAirline

                    INNER JOIN {PRE}RefundClosingDetails rcd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = rcd.vType

                     INNER JOIN {PRE}RefundClosing rc ON rc.nRCId = rcd.nRefundCloseId

                    INNER JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = rcd.nDrAcc

                      LEFT JOIN {PRE}Location loc ON loc.nId = rr.nLocation

                      WHERE rr.vActive='1'

                    " .$extraCheck_r." 

                    " .$extraCheck_or." ) 





										 ORDER BY " . $orderby . "  ";

										

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



    function returnSalesClosedAccounts($closedId){



      $adminGroupListQuery = "SELECT vTable, nDrAccount, nAcHead

        FROM {PRE}SalesAccountDetails sad

        WHERE sad.nSalesCloseId = " . $closedId ;



      session_start();

      $_SESSION['sess_query'] = $adminGroupListQuery;

      $adminGroupList     = $this->db->query($adminGroupListQuery);

      if ($adminGroupList->num_rows() > 0)

      {

        foreach ($adminGroupList->result() as $key=>$row)

        {

          $adminGroupRow[] = $row;

        }//end for each

      } //end if

      return ($adminGroupRow);

    }





}//end class

?>



 

