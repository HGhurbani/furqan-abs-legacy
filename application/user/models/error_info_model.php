<?php
class Error_info_model extends CI_Model {
    function __construct()
    {
        // Call the Model constructor
        parent::__construct();
    }
	function getall()
    {
		$query="
		(SELECT 'JV' as tblName,jv.nJVId as tableId_1,'AcHead Problem' as des,jvd.vTable,jv.vLocation,jv.nDivision,jv.vDocNumber,jvd.vAcSubCode,jvd.vACode FROM tbl_Jv jv LEFT JOIN tbl_JvDetails jvd ON jvd.nJournalId = jv.nJVId where jvd.vTable IN ('V','C') AND jvd.vAcSubCode=0)
		UNION ALL (SELECT 'ACM' as tblName,ac.nACId as tableId_1,'AcHead Problem' as des,acd.vTable,ac.vLocation,ac.nDivision,ac.vDocNumber,acd.vAcSubCode,acd.vACode FROM tbl_AcmEntry ac LEFT JOIN tbl_AcmEntryDetails acd ON acd.nAcId = ac.nACId where acd.vTable IN ('V','C') AND acd.vAcSubCode=0)
		UNION ALL (SELECT 'ADM' as tblName,ad.nADId as tableId_1,'AcHead Problem' as des,adm.vTable,ad.vLocation,ad.nDivision,ad.vDocNumber,adm.vAcSubCode,adm.vACode FROM tbl_AdmEntry ad LEFT JOIN tbl_AdmEntryDetails adm ON adm.nAdId = ad.nADId where adm.vTable IN ('V','C') AND adm.vAcSubCode=0)
		UNION ALL (SELECT 'ACM' as tblName,ac.nACId as tableId_1,'AcHead MisMatch' as des,acd.vTable,ac.vLocation,ac.nDivision,ac.vDocNumber,acd.vAcSubCode,acd.vACode FROM tbl_AcmEntry ac LEFT JOIN tbl_AcmEntryDetails acd ON acd.nAcId = ac.nACId where acd.vTable IN ('A') AND acd.vAcSubCode!=0)
		UNION ALL (SELECT 'ADM' as tblName,ad.nADId as tableId_1,'AcHead MisMatch' as des,adm.vTable,ad.vLocation,ad.nDivision,ad.vDocNumber,adm.vAcSubCode,adm.vACode FROM tbl_AdmEntry ad LEFT JOIN tbl_AdmEntryDetails adm ON adm.nAdId = ad.nADId where adm.vTable IN ('A') AND adm.vAcSubCode!=0)
		UNION ALL (SELECT 'CR' as tblName,cr.nCRId as tableId_1,'AcHead Problem' as des,crd.vTable,cr.vLocation,cr.nDivision,cr.vDocNumber,crd.vAcSubCode,crd.vACode FROM tbl_CashReceipt cr LEFT JOIN tbl_CashReceiptDetails crd ON crd.nCashId = cr.nCRId where crd.vTable IN ('V','C') AND crd.vAcSubCode=0)
		UNION ALL (SELECT 'CP' as tblName,cp.nCpId as tableId_1,'AcHead Problem' as des,cpd.vTable,cp.vLocation,cp.nDivision,cp.vDocNumber,cpd.vAcSubCode,cpd.vACode FROM tbl_CashPayment cp LEFT JOIN tbl_CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId where cpd.vTable IN ('V','C') AND cpd.vAcSubCode=0)
		UNION ALL (SELECT 'BB' as tblName,bb.nBBId as tableId_1,'AcHead Problem' as des,bbd.vTable,bb.vLocation,bb.nDivision,bb.vDocNumber,bbd.vAcSubCode,bbd.vACode FROM tbl_BankBook bb LEFT JOIN tbl_BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId  where bbd.vTable IN ('V','C') AND bbd.vAcSubCode=0)
		UNION ALL (SELECT 'BP' as tblName,bp.nBPId as tableId_1,'AcHead Problem' as des,bpd.vTable,bp.vLocation,bp.nDivision,bp.vDocNumber,bpd.vAcSubCode,bpd.vACode FROM tbl_BankBookPayment bp LEFT JOIN tbl_BankBookPaymentDetails bpd ON bpd.nBankPaymentId = bp.nBPId  where bpd.vTable IN ('V','C') AND bpd.vAcSubCode=0)
		UNION ALL (SELECT 'JV' as tblName,jv.nJVId as tableId_1,'AcHead MisMatch' as des,jvd.vTable,jv.vLocation,jv.nDivision,jv.vDocNumber,jvd.vAcSubCode,jvd.vACode FROM tbl_Jv jv LEFT JOIN tbl_JvDetails jvd ON jvd.nJournalId = jv.nJVId where jvd.vTable IN ('A') AND jvd.vAcSubCode!=0)
		UNION ALL (SELECT 'CR' as tblName,cr.nCRId as tableId_1,'AcHead MisMatch' as des,crd.vTable,cr.vLocation,cr.nDivision,cr.vDocNumber,crd.vAcSubCode,crd.vACode FROM tbl_CashReceipt cr LEFT JOIN tbl_CashReceiptDetails crd ON crd.nCashId = cr.nCRId where crd.vTable IN ('A') AND crd.vAcSubCode!=0)
		UNION ALL (SELECT 'CP' as tblName,cp.nCPId as tableId_1,'AcHead MisMatch' as des,cpd.vTable,cp.vLocation,cp.nDivision,cp.vDocNumber,cpd.vAcSubCode,cpd.vACode FROM tbl_CashPayment cp LEFT JOIN tbl_CashPaymentDetails cpd ON cpd.nCashPId = cp.nCPId where cpd.vTable IN ('A') AND cpd.vAcSubCode!=0)
		UNION ALL (SELECT 'BB' as tblName,bb.nBBId as tableId_1,'AcHead MisMatch' as des,bbd.vTable,bb.vLocation,bb.nDivision,bb.vDocNumber,bbd.vAcSubCode,bbd.vACode FROM tbl_BankBook bb LEFT JOIN tbl_BankBookDetails bbd ON bbd.nBankbookId = bb.nBBId  where bbd.vTable IN ('A') AND bbd.vAcSubCode!=0)
		UNION ALL (SELECT 'SC' as tblName,sc.nSCId as tableId_1,'AcHead MisMatch' as des,sad.vTable,sc.vLocation,sc.nDivision,sc.vInvoiceNumber as vDocNumber,sad.nAcHead as vAcSubCode,sad.nDrAccount as vACode FROM tbl_SalesClosing sc LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = sc.nSCId  where sad.vTable IN ('A') AND sad.nAcHead!=0)
		UNION ALL (SELECT 'SC' as tblName,sc.nSCId as tableId_1,'AcHead Problem' as des,sad.vTable,sc.vLocation,sc.nDivision,sc.vInvoiceNumber as vDocNumber,sad.nAcHead as vAcSubCode,sad.nDrAccount as vACode FROM tbl_SalesClosing sc LEFT JOIN tbl_SalesAccountDetails sad ON sad.nSalesCloseId = sc.nSCId  where sad.vTable IN ('V','C') AND sad.nAcHead='0')
		UNION ALL (SELECT 'Htl' as tblName,hv.nHVId as tableId_1,'Hotel AcHead Problem' as des,'V' as vTable,hv.nLocation as vLocation,hv.nDivision,hv.vTicktManual as vDocNumber,hv.nAcHead as vAcSubCode,hv.nAcCode as vACode FROM tbl_HotelVoucher hv WHERE hv.nAcHead=0  )
		UNION ALL (SELECT 'Htl' as tblName,hv.nHVId as tableId_1,'Hotel AcHead Problem' as des,'V' as vTable,hv.nLocation as vLocation,hv.nDivision,hv.vTicktManual as vDocNumber,hv.nAcHead as vAcSubCode,hv.nAcCode as vACode FROM tbl_HotelVoucher hv WHERE hv.nAcCode=0  )
		";
	   $adminGroupList 		= $this->db->query($query);
                if ($adminGroupList->num_rows() > 0)
                {
                    foreach ($adminGroupList->result() as $key=>$row)
                    {
                         $adminGroupRow[] = $row;
                    }//end for each
                } //end if
        return ($adminGroupRow);
} 
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
		                          			 GROUP BY sj.nId ORDER BY sj.nId DESC 
								   		";
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
 } 
?>