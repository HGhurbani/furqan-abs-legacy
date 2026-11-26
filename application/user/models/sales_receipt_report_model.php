<?php

class sales_receipt_report_model extends CI_Model {



    function __construct()

    {

        // Call the Model constructor

        parent::__construct();

    }


	function docType()
	{
		$docArray = array('UD' => 'Documents','UDS' => 'Documents By Staff',
								'UDD' => 'Documents By Date');

	    return ($docArray);
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



    function getActiveLocations()

    {

        $locGroupRow[0] = NOVALUE;

        return($locGroupRow);

    }//end function



    function getActiveEmployees()

    {

        $empGroup    = $this->db->query("SELECT nId,vUsername FROM {PRE}Admin WHERE vActive = '1' ORDER BY nId DESC");

        $empGroupRow = array();


 
        if ($empGroup->num_rows() > 0)

        {

            $empGroupRow[0] = SELECT;

            foreach ($empGroup->result() as $key => $row)

            {

                $empGroupRow[$row->nId] = stripslashes($row->vUsername) ;

            }//end for each

        }//end if

        else

        {

            $empGroupRow[0] = NOVALUE;

        }//end else

        return ($empGroupRow);

    }//end function



    function getActiveAcName()

    {

        $typeGroupRow = array();

        $typeGroup    = $this->db->query("(SELECT aca.nId as ahaId, aca.vTitle, ahc.vSubSale 

            FROM {PRE}AccountHeadAccounts aca 

            LEFT JOIN {PRE}AccountHead ah ON aca.nHAID = ah.nAId 

            LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId

            WHERE ah.vActive = '1' AND ahc.vSubSale IN ('B','C')  ORDER BY  ahc.vSubSale DESC, aca.vTitle )

            UNION ALL ( SELECT aca.nId as ahaId, aca.vTitle, 'A' as vSubSale  FROM {PRE}AccountHeadAccounts aca WHERE  aca.nId = 408 ) ORDER BY vSubSale DESC, vTitle

            ");

        if ($typeGroup->num_rows() > 0)

        {
			 $typeGroupRow[0] = SELECT;

            foreach ($typeGroup->result() as $key=>$row)

            {



              $cardeName = '';

              $tabCount = $this->db->query(" SELECT GROUP_CONCAT(DISTINCT (SELECT vName FROM tbl_Card WHERE nCId = nCardId_g )  ORDER BY nCardId_g DESC SEPARATOR ' / ') as cardName FROM 

                  (SELECT nCardId as nCardId_g FROM tbl_SalesReceiptDetails WHERE vACodeDr = ".$row->ahaId." 

                   UNION ALL (SELECT nCId as nCardId_g FROM tbl_Card WHERE nCardAc =  ".$row->ahaId." ) ) as T1 ");

              $rowValue = $tabCount->row_array();

              $cardName = $rowValue['cardName'];



              if($cardName){

                $cardName = " - (". $cardName .")";

                $typeGroupRow[$row->ahaId] = $row->vTitle . $cardName ." ";

              }

              //if($row->ahaId == '408')

              //  $typeGroupRow[$row->ahaId.'-'.$row->vSubSale] = $row->vTitle;

            }//end for each

        }//end if

        else

        {

            $typeGroupRow[0] = NOVALUE;

        }//end else

        return ($typeGroupRow);

    }//end function



    function getTktNumberValues($vType,$vNumber,$detId,$tableId,$drAcc,$vTable)

    {

      if($vNumber != 0){

        switch($vType)

        {

          case "Xo" :

            $tktnumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$vNumber."'");

          if($tktnumber == '') break;

          $newArray['desc'] =  "XO: " . $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$vNumber."'") . "  " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");

          $airline        = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId='".$vNumber."'");

          $newArray['tktVal']  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$tktnumber;

          break;



          case "Tkt" :

            $tktnumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$vNumber."'");

          if($tktnumber == '') break;

          $newArray['desc'] =  "Tkt: ". $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$vNumber."'") . "  ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");

          $airline        = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$vNumber."'");

          $newArray['tktVal']  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$tktnumber;

          break;



          case "Htl" :

            $tktnumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$vNumber."'");

          if($tktnumber == '') break;

          $cityName       = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$vNumber."'");

          $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");

          $newArray['desc'] = "Htl: " . $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$vNumber."'")."  " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");

          $newArray['tktVal']  = $tktnumber; 

          break;

        }//end switch

        if($tktnumber == ''){ 

          $newArray['pass'] = $this->common_model->get_field_name('RefundRegisterDetails','vPassenger', " WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");

          $cityName       = $this->common_model->get_field_name('RefundRegisterDetails','nCity'," WHERE  nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");

          $hotel          = $this->common_model->get_field_name('RefundRegisterDetails','nHotel'," WHERE  nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'");

          if($cityName != '0' || $hotel != '0'){

            $newArray['desc'] = "Htl: " .$newArray['pass'] . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'");

          }

          else {

            $newArray['desc'] = $vType .": " . $newArray['pass'] ;

          }

          $newArray['tktVal'] =  $this->common_model->get_field_name('RefundRegisterDetails','vAirline', " WHERE nTktNum = " .$vNumber. " AND vNewOld = 'O' AND vTktType = '".$vType."'") ."-".$vNumber ;

        }

      }

      else{

        $sj_type = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$tableId."'" );

        if($sj_type == 'S')

          return $this->getTktNumberValues_old($detId,$tableId);

        else{

          $rrGroup = $this->db->query("SELECT rrd.vTktType, rrd.vNewOld,rcd.nAirline, rrd.vPassenger, rcd.nTicketNumber FROM {PRE}RefundClosingDetails rcd LEFT JOIN {PRE}RefundRegisterDetails rrd ON rcd.nTicketNumber = rrd.nTktNum AND rrd.vTktType = vType WHERE nRefundCloseId =".$tableId." AND nDrAcc = " . $drAcc . " AND vTable = '" .$vTable ."'");

          $newArray['tktVal'] = '';

          $newArray['desc'] = '';

          $i = 0;

          if ($rrGroup->num_rows() > 0)

          {

            foreach ($rrGroup->result() as $key => $row)

            {

              if($i !=0) {

                $newArray['tktVal'] .= "<br>";

                $newArray['desc'] .= "<br>";

              }

              if($row->vNewOld =='N')

              {

                switch($row->vTktType){

                  case 'Tkt': $tktNum = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$row->nTicketNumber."'"); 

                              break;

                  case 'Htl' : $tktNum = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$row->nTicketNumber."'");

                               break;

                  case 'Xo' : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$row->nTicketNumber."'");

                              break;

                }// end switch

              } // end if

              else

                $tktNum = $row->nTicketNumber;

              $newArray['tktVal'] .=  $row->nAirline . "-" . $tktNum ;

              $newArray['desc'] .= $row->vPassenger  ;

              $i++;

            }//end foreach

          }//endif 

        }//end else

          

      }

      return $newArray;

    } //end functions





    function getReports($extraCheck_sr,$orderby,$group_select, $groupby)

    {

       $adminGroupListQuery = " SELECT 'index.php/sales_receipt/editSalesreceipt/5/e/' as path,sr.dDate,sr.nSRId, sr.nDivision, sr.vLocation, sr.dDate, 

          sr.vDocNumber, sr.vTotalAmountDC, sr.vEnteredBy, sr.vRefName, sr.vRemarks,

          srd.nId, (SELECT vName FROM {PRE}Card WHERE nCId = srd.nCardId) as CardName, srd.vACodeDr, srd.nAmountDr, srd.vACodeCr, srd.nAmountCr, srd.vType, srd.vTktNumber, 



          CASE WHEN srd.vType = 'Tkt' THEN (SELECT CONCAT(tb.vTicktManual) FROM {PRE}TicketBooking tb WHERE tb.nTId = srd.vTktNumber)

          WHEN srd.vType = 'Xo' THEN (SELECT CONCAT(xo.vTicktManual) FROM {PRE}XoBooking xo WHERE xo.nXId = srd.vTktNumber)

          WHEN srd.vType = 'Htl' THEN (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE nHVId = srd.vTktNumber)

          ELSE '' END as Number,
		   CASE WHEN srd.vType = 'Tkt' THEN (SELECT vIssuedBy FROM {PRE}TicketBooking tb WHERE tb.nTId = srd.vTktNumber)

          WHEN srd.vType = 'Xo' THEN (SELECT vIssuedBy FROM {PRE}XoBooking xo WHERE xo.nXId = srd.vTktNumber)

          WHEN srd.vType = 'Htl' THEN (SELECT vIssuedBy FROM {PRE}HotelVoucher WHERE nHVId = srd.vTktNumber)

          ELSE '' END as staff,
CASE WHEN srd.vType = 'Tkt' THEN (SELECT CONCAT( (SELECT ar.vAirlineNo FROM {PRE}Airline ar WHERE ar.nArId = tb.nAirline)) FROM {PRE}TicketBooking tb WHERE tb.nTId = srd.vTktNumber)

          WHEN srd.vType = 'Xo' THEN (SELECT CONCAT((SELECT ar.vAirlineNo FROM {PRE}Airline ar WHERE ar.nArId = xo.nAirline)) FROM {PRE}XoBooking xo WHERE xo.nXId = srd.vTktNumber)

          WHEN srd.vType = 'Htl' THEN (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE nHVId = srd.vTktNumber)

          ELSE '' END as air,


          CASE WHEN srd.vType = 'Tkt' THEN (SELECT vPassenger FROM {PRE}TicketBooking WHERE nTId = srd.vTktNumber)

          WHEN srd.vType = 'Xo' THEN (SELECT vPassenger FROM {PRE}XoBooking WHERE nXId = srd.vTktNumber)

          WHEN srd.vType = 'Htl' THEN (SELECT vPassenger FROM {PRE}HotelVoucher WHERE nHVId = srd.vTktNumber)

          ELSE sr.vRefName END as passName,



        IF(srd.vTktNumber = 0, (SELECT scd.nId FROM {PRE}SalesClosingDetails scd LEFT JOIN {PRE}SalesAccountDetails sad ON sad.nSalesCloseId = scd.nSalesCloseId WHERE sr.vDocNumber = sad.vCRV LIMIT 1), (SELECT scd.nId FROM {PRE}SalesClosingDetails scd WHERE scd.vTktNumber = srd.vTktNumber AND scd.vType =  srd.vType)) AS salesClosedId  

          FROM {PRE}SalesReceipt sr 

          LEFT JOIN {PRE}SalesReceiptDetails srd ON sr.nSRId = srd.nSalesRId 

          LEFT JOIN {PRE}Location loc ON sr.vLocation = loc.nId

          WHERE sr.vActive = '1'

               ".$extraCheck_sr;



        session_start();

        $_SESSION['sess_query'] = $adminGroupListQuery;

        $adminGroupList 		= $this->db->query($adminGroupListQuery);

        $adminGroupRow = array();

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

