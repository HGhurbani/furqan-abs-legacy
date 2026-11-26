<?php
class Bank_reconciliation_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
        ini_set('memory_limit','120M');
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

  function getActiveAccount()
  {
    $typeGroupRow = array();
    $typeGroup    = $this->db->query("SELECT aca.*,aca.nId as ahaId,ah.nAId,ah.vActive,ahc.vSubLedgerType
                      FROM {PRE}AccountHeadAccounts aca LEFT JOIN {PRE}AccountHead ah
                      ON aca.nHAID = ah.nAId LEFT JOIN {PRE}AccountHeadControls ahc ON ahc.nActHId = aca.nId
                        LEFT JOIN {PRE}CustomerMaster v ON v.nFId = ahc.nId
                      WHERE ah.vActive = '1' AND ahc.vSubSale = 'B' ORDER BY vTitle ASC");
    if ($typeGroup->num_rows() > 0)
    {
//      $typeGroupRow[0] = SELECT;
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


    function getActiveLocations()
    {
        $typeGroupRow    = array();
        $typeGroupRow[0] = SELECT;
        return ($typeGroupRow);
    }//end function

    function filterLocation($division)
    {
											//".$this->common_model->getUserLoc()."
        $locGroup    = $this->db->query("SELECT nId,vCode,vTitle FROM {PRE}Location WHERE vActive = '1'
											 AND nDivision = '".$division."' 
										 AND nId IN ".$this->session->userdata('accessLoc') ." ORDER BY nPosition DESC");
        $locGroupRow = array();
        if ($locGroup->num_rows() > 0)
        {				$locGroupRow[0]=SELECT;
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

    function getAllReconciliation($extraCheck_cr,$extraCheck_cp,$extraCheck_bb,$extraCheck_bp,$extraCheck_jv,$extraCheck_cv,$extraCheck_dv,$extraCheck_sj,$extraCheck_ac,$extraCheck_ad,$extraCheck_bb_top_acc,$extraCheck_bp_top_acc,$orderby,$group_select, $groupby)
    {
      $list ='';
      $get_acc_list =  $this->db->query("SELECT nActHId FROM {PRE}AccountHeadControls WHERE vSubLedgerType != 'N'"); 
        if ($get_acc_list->num_rows() > 0)
        {
            foreach ($get_acc_list->result() as $key=>$row)
            {
              $list .= $list!='' ? ',' :'';
                $list .= "'" . $row->nActHId . "'";
               // $adminGroupRow[] = $row;
            }//end for each
        } //end if
        $list = "(" . $list . ")";

        $sj_query = "(SELECT sj.dDate as date,sj.nLocation as location,sj.nInvoice as document,sjd.vDes as description,
              IF(sjd.nAccHeadId = 0, sjd.nDrAccount, sjd.nAccHeadId) as account, 
              IF(sjd.nAccHeadId NOT IN ". $list ." ,0, IF(sjd.nDrAccount = '', 
                  IF ( (( SELECT rrd.vNewOld FROM tbl_RefundRegisterDetails rrd
                      WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O' ) IN ('O') ) ,
                  ( SELECT rr.nAccount FROM tbl_RefundRegister rr
                    LEFT JOIN tbl_RefundRegisterDetails rrd ON rr.nRId = rrd.nRefundId
                    WHERE sjd.vNumber= rrd.nTktNum AND sjd.vType = vTktType AND rrd.vNewOld = 'O') ,
                  (CASE
                   WHEN sjd.vType = 'Tkt' THEN
                   (SELECT nAcc FROM {PRE}TicketFareDetails WHERE
                    nTicketId = sjd.vNumber)
                   WHEN sjd.vType = 'Htl' THEN
                   (SELECT nAcCode FROM {PRE}HotelVoucher WHERE
                    nHVId = sjd.vNumber)
                   ELSE
                   (SELECT nPassengerAC FROM {PRE}XoBooking WHERE
                    nXId = sjd.vNumber) END)),
                  sjd.nDrAccount))as subacc,
              IF(sjd.vTable = '','V', sjd.vTable ) as vTable, 
              sjd.nDebit as debit,sjd.nCredit as credit,
              sj.nSalesCId as tableId,'SJ' as tblName,'' as mode,sjd.nSjDId as detId,sjd.vType as vType,
              sjd.vNumber as vNumber
              FROM {PRE}SystemJv sj
              LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId WHERE '1' = '1'  ".$extraCheck_sj." ) ";

      $cr_query = " (SELECT cr.dDate as date,cr.vLocation as location,cr.vDocNumber as document,cr.vRemarks as description,
               IF(crd.vAcSubCode !='0' , crd.vAcSubCode, crd.vACode) as account,
               IF(crd.vAcSubCode !='0' , crd.vACode, crd.vAcSubCode) as subacc, crd.vTable  as vTable,
               IF( crd.vMode = 'Dr' , crd.nAmount ,0)as debit,
               IF( crd.vMode = 'Cr' , crd.nAmount ,0)as credit,
               cr.nCRId as tableId,'CR' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CashReceipt cr LEFT JOIN {PRE}CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
               ".$extraCheck_cr." ) "  ;


     $cp_query = " (SELECT cp.dDate as date,cp.vLocation as location,cp.vDocNumber as document,cp.vRemarks as description,
               IF(cpd.vAcSubCode !='0' , cpd.vAcSubCode, cpd.vACode) as account,
               IF(cpd.vAcSubCode !='0' , cpd.vACode, cpd.vAcSubCode) as subacc, cpd.vTable  as vTable,
               IF( cpd.vMode = 'Dr' , cpd.nAmount ,0)as debit,
               IF( cpd.vMode = 'Cr' , cpd.nAmount ,0)as credit,
               cp.nCPId as tableId,'CP' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CashPayment cp LEFT JOIN {PRE}CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId WHERE cp.vActive='1'
               ".$extraCheck_cp." ) "  ;


     $bb_query = " (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as description,
              bb.nAcCode as account,bb.vSubCode as subacc, '' as vTable,
              IF( bb.vTransMode = 'Dr' , bb.vAmountDC,0)as debit,
              IF( bb.vTransMode = 'Cr' , bb.vAmountDC,0)as credit,
              bb.nBBId as tableId,'BRB' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}BankBook bb WHERE bb.vActive='1'
            ".$extraCheck_bb_top_acc." )
              UNION ALL
              (SELECT bb.dDate as date,bb.vLocation as location,bb.vDocNumber as document,bb.vRemarks as description,
               IF(bbd.vAcSubCode !='0' , bbd.vAcSubCode, bbd.vACode) as account,
               IF(bbd.vAcSubCode !='0' , bbd.vACode, bbd.vAcSubCode) as subacc, bbd.vTable  as vTable,
               IF( bbd.vMode = 'Dr' , bbd.nAmount ,0)as debit,
               IF( bbd.vMode = 'Cr' , bbd.nAmount ,0)as credit,
               bb.nBBId as tableId,'BR' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}BankBook bb LEFT JOIN {PRE}BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1'
               ".$extraCheck_bb." ) "  ;


     $bp_query = " (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as description,
              bp.nAcCode as account,bp.vSubCode as subacc, '' as vTable,
              IF( bp.vTransMode = 'Dr' , bp.vAmountDC,0)as debit,
              IF( bp.vTransMode = 'Cr' , bp.vAmountDC,0)as credit,
              bp.nBPId as tableId,'BPB' as tblName,
              '' as mode,'' as detId,
              '' as vType,'' as vNumber FROM
              {PRE}BankBookPayment bp WHERE bp.vActive='1'
            ".$extraCheck_bp_top_acc." )
              UNION ALL
              (SELECT bp.dDate as date,bp.vLocation as location,bp.vDocNumber as document,bp.vRemarks as description,
               IF(bpd.vAcSubCode !='0' , bpd.vAcSubCode, bpd.vACode) as account,
               IF(bpd.vAcSubCode !='0' , bpd.vACode, bpd.vAcSubCode) as subacc, bpd.vTable  as vTable,
               IF( bpd.vMode = 'Dr' , bpd.nAmount ,0)as debit,
               IF( bpd.vMode = 'Cr' , bpd.nAmount ,0)as credit,
               bp.nBPId as tableId,'BP' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}BankBookPayment bp LEFT JOIN {PRE}BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1'
               ".$extraCheck_bp." ) "  ;


$cv_query = " (SELECT cv.dDate as date,cv.vLocation as location,cv.vDocNumber as document,cv.vRemarks as description,
               IF(cvd.vAcSubCode !='0' , cvd.vAcSubCode, cvd.vACode) as account,
               IF(cvd.vAcSubCode !='0' , cvd.vACode, cvd.vAcSubCode) as subacc, cvd.vTable  as vTable,
               IF( cvd.vMode = 'Dr' , cvd.nAmount ,0)as debit,
               IF( cvd.vMode = 'Cr' , cvd.nAmount ,0)as credit,
               cv.nCVId as tableId,'CV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}CreditVoucher cv LEFT JOIN {PRE}CreditVoucherDetails cvd ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1'
               ".$extraCheck_cv." ) "  ;

$dv_query = " (SELECT dv.dDate as date,dv.vLocation as location,dv.vDocNumber as document,dv.vRemarks as description,
               IF(dvd.vAcSubCode !='0' , dvd.vAcSubCode, dvd.vACode) as account,
               IF(dvd.vAcSubCode !='0' , dvd.vACode, dvd.vAcSubCode) as subacc, dvd.vTable  as vTable,
               IF( dvd.vMode = 'Dr' , dvd.nAmount ,0)as debit,
               IF( dvd.vMode = 'Cr' , dvd.nAmount ,0)as credit,
               dv.nDVId as tableId,'DV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}DebitVoucher dv LEFT JOIN {PRE}DebitVoucherDetails dvd ON dvd.nDebitId = dv.nDVId WHERE dv.vActive='1'
               ".$extraCheck_dv." ) "  ;


     $ac_query = " (SELECT ac.dDate as date,ac.vLocation as location,ac.vDocNumber as document,ac.vRemarks as description,
               IF(acd.vAcSubCode !='0' , acd.vAcSubCode, acd.vACode) as account,
               IF(acd.vAcSubCode !='0' , acd.vACode, acd.vAcSubCode) as subacc, acd.vTable  as vTable,
               IF( acd.vMode = 'Dr' , acd.nAmount ,0)as debit,
               IF( acd.vMode = 'Cr' , acd.nAmount ,0)as credit,
               ac.nACId as tableId,'AC' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}AcmEntry ac LEFT JOIN {PRE}AcmEntryDetails acd ON acd.nAcId = ac.nACId WHERE ac.vActive='1'
               ".$extraCheck_ac." ) "  ;

            $ad_query = " (SELECT ad.dDate as date,ad.vLocation as location,ad.vDocNumber as document,ad.vRemarks as description,
               IF(ade.vAcSubCode !='0' , ade.vAcSubCode, ade.vACode) as account,
               IF(ade.vAcSubCode !='0' , ade.vACode, ade.vAcSubCode) as subacc, ade.vTable  as vTable,
               IF( ade.vMode = 'Dr' , ade.nAmount ,0)as debit,
               IF( ade.vMode = 'Cr' , ade.nAmount ,0)as credit,
               ad.nADId as tableId,'AD' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}AdmEntry ad LEFT JOIN {PRE}AdmEntryDetails ade ON ade.nAdId = ad.nADId WHERE ad.vActive='1'
               ".$extraCheck_ad." ) "  ;

            $jv_query = " (SELECT jv.dDate as date,jv.vLocation as location,jv.vDocNumber as document,jv.vRemarks as description,
               IF(jvd.vAcSubCode !='0' , jvd.vAcSubCode, jvd.vACode) as account,
               IF(jvd.vAcSubCode !='0' , jvd.vACode, jvd.vAcSubCode) as subacc, jvd.vTable  as vTable,
               IF( jvd.vMode = 'Dr' , jvd.nAmount ,0)as debit,
               IF( jvd.vMode = 'Cr' , jvd.nAmount ,0)as credit,
               jv.nJVId as tableId,'JV' as tblName,
               '' as mode,'' as detId,
               '' as vType,'' as vNumber FROM
               {PRE}Jv jv LEFT JOIN {PRE}JvDetails jvd ON jvd.nJournalId = jv.nJVId WHERE jv.vActive='1'
               ".$extraCheck_jv." ) "  ;
//$adminGroupListQuery = $sj_query . " " . $orderby;
//return;
//if($group_select != ''){
//echo  $group_select . "  " . $cr_query. " " . $groupby . " " . $orderby . "<br><br><br><br>";
//}
              $adminGroupListQuery = $group_select . "  "  . $sj_query . " UNION ALL ". $cr_query . " UNION ALL " .$cp_query. "
                UNION ALL  " . $bb_query . " UNION ALL ". $bp_query . "
                UNION ALL ". $cv_query ." UNION ALL ". $dv_query ."
                UNION ALL " . $ac_query . " UNION ALL " . $ad_query . " 
                UNION ALL " . $jv_query . " " . $groupby . " " ;//. $orderby;
            
              //echo $adminGroupListQuery . "<br><br><br><br>";
              //return;

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
/*
    function getAllUnMatchReconciliation($extraCheck)
    {
        //active groups
        $adminGroupRow  		= array();
        $sqlCreditvoucherQuery  = "SELECT * FROM {PRE}Reconciliation WHERE vStatus='N' ".$extraCheck." ORDER BY nId ASC";
        $adminGroupList 		= $this->db->query($sqlCreditvoucherQuery);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function
*/
    function getAllMatchedReconciliation($extraCheck)
    {
        //active groups
        $adminGroupRow  		= array();
        $MatchedReconciliationQuery = "SELECT vLocation AS location, dDate AS date, vReconType AS tblName, 
          nReceiptDId AS tableId,
                      vDocNumber AS document, vDebitAmt AS debit, vCreditAmt AS credit, nAcCode AS account, '' AS subacc, vStatus, '' AS mode
                        FROM {PRE}BankReconciliation WHERE vStatus='M' ".$extraCheck." ORDER BY nId ASC";
        $adminGroupList 		= $this->db->query($MatchedReconciliationQuery);

        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
                $adminGroupRow[] = $row;
            }//end for each
        } //end if
        return ($adminGroupRow);
    }//end function

    function saveReconciled(){

      $dDate        = $this->input->post('dDate');
      $ddlLocation3 = $this->input->post('ddlLocation3');
      $vDocNum      = $this->input->post('vDocNum');
      $nDebit       = $this->input->post('nDebit');
      $nCredit      = $this->input->post('nCredit');
      $tblName      = $this->input->post('tblName');
      $tblId        = $this->input->post('tblId');
      $vStatus      = $this->input->post('vStatus');

      if(!empty($tblId))
      foreach($tblId as $key => $aitem){
        $nId = $this->common_model->get_field_name('BankReconciliation','nId'," WHERE nReceiptDId = ".$tblId[$key]." AND vReconType ='".$tblName[$key]."' ");
        if($nId != '')
        {
          //update
          $data =  array('vStatus' => $vStatus[$key]);
          $this->db->where('nId' ,$nId);
          $this->db->update('{PRE}BankReconciliation', $data);
        }
        else {
          //insert
//          $nDivision = $this->common_model->get_field_name('Location','nDivision'," WHERE nId = ".$ddlLocation3[$key]. " ");

          $data = array( 'nDivision'=> $this->ddlDivision, 'vLocation'=>$ddlLocation3[$key], 
              'dDate'=>$dDate[$key], 'vReconType'=>$tblName[$key], 'nReceiptDId'=>$tblId[$key], 
              'vDocNumber'=>$vDocNum[$key], 'vDebitAmt'=>$nDebit[$key], 'vCreditAmt'=>$nCredit[$key], 
              'nAcCode'=>$this->ddlAccount, 'vStatus'=>$vStatus[$key], 'nUserId' => $this->session->userdata('AbsAdmin'));
        $this->db->insert('{PRE}BankReconciliation', $data);
        }
      }//end foreach
      //delete all unmatched
      $this->db->delete('{PRE}BankReconciliation',array('vStatus'=>'N'));
      return "Successfully Saved";
    }//end function

    function getTktNumberValues($detId,$tableId)
    {
        $newArray = $valTktArray = $valDetArray = array();
        $salesCloseId  = $this->common_model->get_field_name('SystemJvDetails','nSjId'," WHERE nSjDId='".$detId."'");
        $sjGroup       = $this->db->query("SELECT * FROM {PRE}SystemJvDetails WHERE nSjId='".$salesCloseId."' ORDER BY nSjDId ASC");

        if ($sjGroup->num_rows() > 0)
        {
            foreach ($sjGroup->result() as $key => $row)
            {
                if($row->vNumber>0):
                    switch($row->vType)
                    {
                        case "Xo" :
                            $detVal  = $this->common_model->get_field_name('XoPassDetails','vPass'," WHERE nXoId ='".$row->vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->vNumber."'");
							if(!in_array($detVal,$valDetArray))
								$valDetArray[] = $detVal;
								
                            $airline        = $this->common_model->get_field_name('XoBooking','nAirline'," WHERE nXId='".$row->vNumber."'");
                            $tktVal  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$row->vNumber."'");
							if(!in_array($tktVal,$valTktArray))
								$valTktArray[] = $tktVal;
                            break;

                        case "Tkt" :
                            $detVal = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->vNumber."'");
							
							if(!in_array($detVal,$valDetArray))
								$valDetArray[] = $detVal;
								
                            $airline        = $this->common_model->get_field_name('TicketBooking','nAirline'," WHERE nTId='".$row->vNumber."'");
                            $tktVal  = $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$airline."'").'-'.$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$row->vNumber."'");
							if(!in_array($tktVal,$valTktArray))
								$valTktArray[] = $tktVal;
                            break;

                        case "Htl" :
                            $cityName       = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$row->vNumber."'");
                            $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->vNumber."'");
                            $detVal  = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$row->vNumber."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->vNumber."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->vNumber."'");
							if(!in_array($detVal,$valDetArray))
								$valDetArray[] = $detVal;
                            $tktVal  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$row->vNumber."'");
							
							if(!in_array($tktVal,$valTktArray))
								$valTktArray[] = $tktVal;
                            break;
                    }//end switch

                endif; 
            }//end for each
			
            $newArray['desc']   =  @join('<br>',$valDetArray);
            $newArray['tktVal'] =  @join('<br>',$valTktArray);
        }//end if
                  
        return ($newArray);
    }//end function


}//end class
?>
