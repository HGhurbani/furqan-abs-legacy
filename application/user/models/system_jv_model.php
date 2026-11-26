<?php
class System_jv_model extends CI_Model {

    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }

/*----------------------Refund closing Details-----------------*/

	function getAllSystemJv()
	{
		//active groups
		$adminGroupRow  		= array();
		$sqlRefundclosingQuery  = "SELECT sj.*,sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,
											aha.vTitle as accName,aha.vACode as accCode,
										sad.vTable,sad.nDrAccount,l.vTitle as loc,d.vTitle as dName,
										l.vTitle as lName,
										CASE 
											WHEN sad.vTable = 'C' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE 
										 			nCId = sad.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE 
										 			nId = sad.nDrAccount)
										END as subCode
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId 
										LEFT JOIN {PRE}SalesAccountDetails sad 
								   ON sad.nSalesCloseId  = sj.nSalesCId 
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation
                  WHERE sj.nLocation IN ". $this->session->userdata('accessLoc') ." 
		                          			 GROUP BY sj.nId ORDER BY sj.nId DESC 
								   		LIMIT ".$this->common_model->_offset." , 
									".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlRefundclosingQuery;
		$adminGroupList 		= $this->db->query($sqlRefundclosingQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function

	function getCountSystemJv()
	{
		$tabCount = $this->db->query("SELECT sj.*,sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,
											aha.vTitle as accName,aha.vACode as accCode,
										sad.vTable,sad.nDrAccount,
										CASE 
											WHEN sad.vTable = 'C' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE 
										 			nCId = sad.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE 
										 			nId = sad.nDrAccount)
										END as subCode
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId 
										LEFT JOIN {PRE}SalesAccountDetails sad 
								   ON sad.nSalesCloseId  = sj.nSalesCId 
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation 
                  WHERE sj.nLocation IN ". $this->session->userdata('accessLoc') ."
		                            	GROUP BY sj.nId ORDER BY sj.nId DESC");
		return ($tabCount->num_rows());
	}//end function
	
	function getSystemJvDetails($id)
	{
		//active groups
		$adminGroupRow  		= array();
		$sqlRefundclosingQuery  = "SELECT sj.*,sj.vType as RSType, sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,sjd.vNumber,
											aha.vTitle as accName,aha.vACode as accCode,sjd.nSjDId,
										sad.vTable,sad.nDrAccount,l.vTitle as loc,d.vTitle as dName,
										l.vTitle as lName,sjd.nAccHeadId, aha.nHAId  as acHead,
										CASE 
											WHEN sjd.vTable = 'C' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE 
										 			nCId = sjd.nDrAccount)
											WHEN sjd.vTable = 'V' THEN
										 		(SELECT vName FROM {PRE}VendorMaster WHERE 
										 			 nVId = sjd.nDrAccount)
											WHEN sjd.vTable = 'AG' THEN
										 		(SELECT vName FROM {PRE}Agent WHERE 
										 			 nAId = sjd.nDrAccount)
											WHEN sjd.vTable = 'O' THEN
										 		(SELECT vName FROM {PRE}Other WHERE 
										 			 nOId = sjd.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE 
										 			nId = sjd.nDrAccount)
										END as subCode,IF( 	
										(CASE 
											WHEN sjd.vType = 'Tkt' THEN
										 		(SELECT vTicktManual FROM {PRE}TicketBooking WHERE 
										 			nTId = sjd.vNumber)
											WHEN sjd.vType = 'Htl' THEN
										 		(SELECT vTicktManual FROM {PRE}HotelVoucher WHERE 
										 			nHVId = sjd.vNumber)
											ELSE
												(SELECT vTicktManual FROM {PRE}XoBooking WHERE 
										 			nXId = sjd.vNumber)
										END) != '' , (CASE
                      WHEN sjd.vType = 'Tkt' THEN
                        (SELECT vTicktManual FROM {PRE}TicketBooking WHERE
                          nTId = sjd.vNumber)
                      WHEN sjd.vType = 'Htl' THEN
                        (SELECT vTicktManual FROM {PRE}HotelVoucher WHERE
                          nHVId = sjd.vNumber)
                      ELSE
                        (SELECT vTicktManual FROM {PRE}XoBooking WHERE
                          nXId = sjd.vNumber)
                    END ), sjd.vNumber)  as tNumber,sjd.vClosedId
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId 
										LEFT JOIN {PRE}SalesAccountDetails sad 
								   ON sad.nSalesCloseId  = sj.nSalesCId 
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation
		                          			 WHERE sj.nId = '".$id."' GROUP BY sjd.nSjDId
									ORDER BY sj.nId ";
		$adminGroupList 		= $this->db->query($sqlRefundclosingQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			 $i = 1;$debitTotal = $creditTotal = 0;$chkSubCodeArray = array('101','91','111');
			 $debit = (intval($item->nDebit) != 0)?$item->nDebit:'';
			 
			 
			 $content = '<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
							<thead>
								<tr>
									<th align="left">#</th>
									<th align="left">'.LOCATIONNAME.'</th>
									<th align="left">'.ACCODE.'</th>
									<th align="left">'.SUBCODE.'</th>
									<th align="left">'.REFNUMBER.'</th>
									<th align="left">'.DESCRIPTION.'</th>
									<th align="left">'.DEBIT.'</th>
									<th align="left">'.CREDIT.'</th>
								</tr>
							</thead><tbody>';
			foreach ($adminGroupList->result() as $key=>$item)
			{
				switch($item->vType)
				{
					case "Tkt":
						$sType = 'Ticket';
					break;
					
					case "Xo":
						$sType = 'Xo';
					break;
					
					case "Htl":
						$sType = 'Hotel';
					break;
				}//end swithc
			
				
				$showTktNo       = ($item->vNumber != 0)?($sType.'-'.$item->tNumber):'';
				
					
				//check subcode in accound head controls
				$chkCode  = $this->common_model->get_field_name('AccountHeadControls','vSubLedgerNo',
												" WHERE nActHId = '".$item->nAccHeadId."' AND 
														nHAId = '".$item->acHead."'");
				$chkCode = '';
				if(trim($chkCode) != ''):
					$showSubCode = $chkCode;
				else:
					if($item->vNumber != 0):
						$showSubCode = '';
					else:
						if(in_array($item->nAccHeadId,$chkSubCodeArray)):
							$showSubCode = '';
						else:
							$chkTable = @split("-",$item->vClosedId);
							if($chkTable[1] == 'C')
								$showSubCode = $this->common_model->get_field_name('CustomerMaster','vName',
												" WHERE nCId = '".$chkTable[0]."'");
							elseif($chkTable[1] == 'A')
								$showSubCode = $this->common_model->get_field_name('AccountHeadAccounts',
											'vTitle'," WHERE nId = '".$chkTable[0]."'");
							elseif($chkTable[1] == 'AG')
								$showSubCode = $this->common_model->get_field_name('Agent',
									'vName'," WHERE nAId = '".$chkTable[0]."'");
							else
								$showSubCode = $item->subCode;
							
						endif;
					endif;
				endif;
					
				$debitTotal      += $item->nDebit;
				$creditTotal     += $item->nCredit;
				$locCode         = $this->common_model->get_field_name('Location','vCode', " WHERE 
											nId = '".$item->nLocation."'");
/*				
				if($item->accName == 'BSP Payable' && trim($showSubCode) == ''):
					$getCode     = $this->common_model->get_field_name('TicketFareDetails','nAcc',
											" WHERE nTicketId  = '".$item->vNumber."'");
					$showSubCode = $this->common_model->get_field_name('VendorMaster','vName',
											" WHERE nVId  = '".$getCode."'");
				elseif($item->accName == 'Tour Agent & Hotels Payable' && trim($showSubCode) == ''):
					$getCode     = $this->common_model->get_field_name('HotelVoucher','nAcCode',
											" WHERE nHVId  = '".$item->vNumber."'");
					$showSubCode = $this->common_model->get_field_name('VendorMaster','vName',
											" WHERE nVId  = '".$getCode."'");
				elseif($item->accName == 'XO Payable' && trim($showSubCode) == ''):
					$getCode     = $this->common_model->get_field_name('XoBooking','nPassengerAC',
											" WHERE nXId = '".$item->vNumber."'");
					$showSubCode = $this->common_model->get_field_name('VendorMaster','vName',
											" WHERE nVId  = '".$getCode."'");
        elseif($item->accName == 'TAX PAYABLE' && trim($showSubCode) == ''):
          $chkTable = @split("-",$item->vClosedId);
          $showSubCode =  $this->common_model->get_field_name('Agent','vName',
                        " WHERE nAId = '".$chkTable[0]."'");
				else:
					$showSubCode = $showSubCode;
				endif;
*/

        $nAccHeadId_temp = '';
        if($item->vNumber != 0){
          $oldNew = $this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE nTktNum = " . $item->vNumber ." AND vTktType = '" . $item->vType. "'");
          if($oldNew != 'O' || $itme->RSType == 'S') {
              switch($item->vType)
              {
              case "Tkt":
              $getCode_temp     = $this->common_model->get_field_name('TicketFareDetails','nAcc',
                " WHERE nTicketId  = '".$item->vNumber."'");
              $showSubCode_temp = $this->common_model->get_field_name('VendorMaster','vName',
                " WHERE nVId  = '".$getCode_temp."'");
              $nAccHeadId_temp = $this->common_model->get_field_name('VendorMaster','nFId',
                " WHERE nVId  = '".$getCode_temp."'");
              break;

              case "Xo":
              $getCode_temp     = $this->common_model->get_field_name('XoBooking','nPassengerAC',
                " WHERE nXId = '".$item->vNumber."'");
              $showSubCode_temp = $this->common_model->get_field_name('VendorMaster','vName',
                " WHERE nVId  = '".$getCode_temp."'");
              $nAccHeadId_temp = $this->common_model->get_field_name('VendorMaster','nFId',
                " WHERE nVId  = '".$getCode_temp."'");
              break;

              case "Htl":
                $getCode_temp     = $this->common_model->get_field_name('HotelVoucher','nAcCode',
                    " WHERE nHVId  = '".$item->vNumber."'");
              $showSubCode_temp = $this->common_model->get_field_name('VendorMaster','vName',
                  " WHERE nVId  = '".$getCode_temp."'");
              $nAccHeadId_temp = $this->common_model->get_field_name('VendorMaster','nFId',
                  " WHERE nVId  = '".$getCode_temp."'");
              break;

              }//end swithc
          }
          else{
            $RefId = $this->common_model->get_field_name('RefundRegisterDetails','nRefundId',
                " WHERE  nTktNum = " .$item->vNumber ." AND vTktType = '" . $item->vType. "'");
            $getCode_temp = $this->common_model->get_field_name('RefundRegister','nAccount'," WHERE nRId = " .$RefId ." ");
            $showSubCode_temp = $this->common_model->get_field_name('VendorMaster','vName',
                " WHERE nVId  = '".$getCode_temp."'");
            $nAccHeadId_temp = $this->common_model->get_field_name('VendorMaster','nFId',
                " WHERE nVId  = '".$getCode_temp."'");


          }
          if($nAccHeadId_temp == $item->nAccHeadId)
          {
            $getCode = $getCode_temp;
            $showSubCode = $showSubCode_temp;
          } 
        }
        $content   .= '<tr>
								<td align="left">'.$i.'</td>
								<td align="left">'.$locCode.'</td>
								<td align="left">'.stripslashes($item->accName).'</td>
								<td align="left">'.$showSubCode.'</td>
								<td align="left">'.$showTktNo.'</td>
								<td align="left">'.stripslashes($item->vDes).'</td>
								<td align="left">'.stripslashes($item->nDebit).'</td>
								<td align="left">'.stripslashes($item->nCredit).'</td>
        						</tr>';
				$i= $i+1;
			}//end for each
		} //end if
        else
        {
        	$content = '<tr>
    						<td colspan="18" align="center">'.SORRYNORECORD.'</td>
						</tr>';
        }//end else
        
        $content .= '<tr>
							<td align="right" colspan="6"><strong>Total</strong></td>
							<td align="left">'.$debitTotal.'</td>
							<td align="left">'.$creditTotal.'</td>
							</tr>
							</tbody></table>';
        
		return ($content);
	}//end function

	function getFilterList($extraCheck)
	{
		//active groups
		$adminGroupRow  		= array();
		$sqlRefundclosingQuery  = "SELECT sj.*,sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,
											aha.vTitle as accName,aha.vACode as accCode,
										sad.vTable,sad.nDrAccount,l.vTitle as loc,d.vTitle as dName,
										l.vTitle as lName,
										CASE 
											WHEN sad.vTable = 'C' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE 
										 			nCId = sad.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE 
										 			nId = sad.nDrAccount)
										END as subCode
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId 
										LEFT JOIN {PRE}SalesAccountDetails sad 
								   ON sad.nSalesCloseId  = sj.nSalesCId 
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation 
		                          		WHERE
                                  sj.nLocation IN ". $this->session->userdata('accessLoc') ." 
                                  ".$extraCheck." GROUP BY sj.nId ORDER BY sj.nId DESC
								   		LIMIT ".$this->common_model->_offset." , 
									".$this->common_model->_limit;
		session_start();
		$_SESSION['sess_query'] = $sqlRefundclosingQuery;
		$adminGroupList 		= $this->db->query($sqlRefundclosingQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$row)
			{
				$adminGroupRow[] = $row;
			}//end for each
		} //end if
		return ($adminGroupRow);
	}//end function
	
	function getCountfilterList($extraCheck)
	{
		$tabCount = $this->db->query("SELECT sj.*,sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,
											aha.vTitle as accName,aha.vACode as accCode,
										sad.vTable,sad.nDrAccount,l.vTitle as loc,d.vTitle as dName,
										l.vTitle as lName,
										CASE 
											WHEN sad.vTable = 'C' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE 
										 			nCId = sad.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE 
										 			nId = sad.nDrAccount)
										END as subCode
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId 
										LEFT JOIN {PRE}SalesAccountDetails sad 
								   ON sad.nSalesCloseId  = sj.nSalesCId 
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation 
		                          		WHERE
                                  sj.nLocation IN ". $this->session->userdata('accessLoc') ." 
                                  ".$extraCheck." GROUP BY sj.nId ORDER BY sj.nId DESC ");
		return ($tabCount->num_rows());
	}//end function
	
	//get subcode name
	function getSubCode($id)
	{
		$acCode  = $this->common_model->get_field_name('AccountHeadAccounts','nHAId'," WHERE nId = '".$id."'");
		$subCode = $this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId = '".$acCode."'");
		
		return ($subCode);
	}//end function
	
		//get total debit n credit
/*	function getSystemJvDrCr($id)
	{
		//active groups
		$drcrArray  		= array();
		$sqlRefundclosingQuery  = "SELECT sj.*,sjd.vType,sjd.vDes,sjd.nDebit,sjd.nCredit,sjd.vNumber,
											aha.vTitle as accName,aha.vACode as accCode,
										sad.vTable,sad.nDrAccount,l.vTitle as loc,d.vTitle as dName,
										l.vTitle as lName,sjd.nAccHeadId, aha.nHAId  as acHead,
										CASE 
											WHEN sad.vTable = 'C' THEN
										 		(SELECT vName FROM {PRE}CustomerMaster WHERE 
										 			nCId = sad.nDrAccount)
											ELSE
												(SELECT vTitle FROM {PRE}AccountHeadAccounts WHERE 
										 			nId = sad.nDrAccount)
										END as subCode, 	
										CASE 
											WHEN sjd.vType = 'Tkt' THEN
										 		(SELECT vTicktManual FROM {PRE}TicketBooking WHERE 
										 			nTId = sjd.vNumber)
											WHEN sjd.vType = 'Htl' THEN
										 		(SELECT vTicktManual FROM {PRE}HotelVoucher WHERE 
										 			nHVId = sjd.vNumber)
											ELSE
												(SELECT vTicktManual FROM {PRE}XoBooking WHERE 
										 			nXId = sjd.vNumber)
										END as tNumber
										 FROM {PRE}SystemJv sj
									LEFT JOIN {PRE}SystemJvDetails sjd ON sj.nId = sjd.nSjId 
										LEFT JOIN {PRE}AccountHeadAccounts aha ON aha.nId = sjd.nAccHeadId
									LEFT JOIN {PRE}AccountHead ah ON ah.nAId = sjd.nAGId 
										LEFT JOIN {PRE}SalesAccountDetails sad 
								   ON sad.nSalesCloseId  = sj.nSalesCId 
								   		LEFT JOIN {PRE}Division d ON d.nId = sj.nDivision
									LEFT JOIN {PRE}Location l ON l.nId = sj.nLocation
		                          			 WHERE sj.nId = '".$id."' ORDER BY sj.nId DESC";
		$adminGroupList 		= $this->db->query($sqlRefundclosingQuery);

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$item)
			{
				$debitTotal      += $item->nDebit;
				$creditTotal     += $item->nCredit;
			}//end for each
		} //end if
        
		$drcrArray['debit'] = $debitTotal;
		$drcrArray['credit'] = $creditTotal;
		
		return ($drcrArray);
	}//end function
 */
	function getSystemJvDrCr($id)
	{
		//active groups
		$drcrArray  		= array();
		$sqlRefundclosingQuery  = "SELECT SUM(sj.nCredit) as credit, SUM(sj.nDebit) as debit FROM {PRE}SystemJvDetails sj 
							WHERE nSjId = " . $id . " GROUP BY nSjId ";

		$adminGroupList 		= $this->db->query($sqlRefundclosingQuery);
		
		$debitTotal =0;
		$creditTotal = 0;

		if ($adminGroupList->num_rows() > 0)
		{
			foreach ($adminGroupList->result() as $key=>$item)
			{
				$debitTotal      += $item->debit;
				$creditTotal     += $item->credit;
			}//end for each
		} //end if
        
        
		$drcrArray['debit'] = $debitTotal;
		$drcrArray['credit'] = $creditTotal;
		
		return ($drcrArray);
	}//end function			

}//end class
?>
