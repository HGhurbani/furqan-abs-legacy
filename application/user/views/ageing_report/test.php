<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |
// +----------------------------------------------------------------------+
// | This source file is a part of ABS Account Balancing Solution         |
// +----------------------------------------------------------------------+
// | Authors: programmers                                                 |
// |                                                                      |
// +----------------------------------------------------------------------+                                                     
?>
(SELECT sc.dDate as date,sc.vLocation as location,'SJ' as tblName,sad.vTable,sc.nSCId as tableId,
sc.vInvoiceNumber as document,scd.nId as detId,
sad.nDrAccount as account,scd.vCollected as debit,'' as description,
CASE WHEN ca.vBalAmt > 0 THEN (scd.vCollected - (CASE WHEN
(SELECT SUM(vAllAmt) FROM tbl_CashAllocation WHERE nSalesCloseId = sc.nSCId
AND nSalesCloseDetId = scd.nId  AND nDrAccount IN(14,13,12,11,10,9,8,7,6,5) AND vTable IN(C,C,C,C,C,C,C,C,C,C)) is null THEN 0
ELSE
(SELECT SUM(vAllAmt) FROM tbl_CashAllocation WHERE nSalesCloseId = sc.nSCId
AND nSalesCloseDetId = scd.nId
AND nDrAccount IN(14,13,12,11,10,9,8,7,6,5) AND vTable IN(C,C,C,C,C,C,C,C,C,C)) END)) ELSE scd.vCollected END AS allocAmt,
CASE WHEN scd.vCollected - (CASE WHEN
(SELECT SUM(vAllAmt) FROM tbl_CashAllocation WHERE nSalesCloseId = sc.nSCId
AND nSalesCloseDetId = scd.nId
AND nDrAccount IN(14,13,12,11,10,9,8,7,6,5) AND vTable IN(C,C,C,C,C,C,C,C,C,C)) is null THEN 0
ELSE
(SELECT SUM(vAllAmt) FROM tbl_CashAllocation WHERE nSalesCloseId = sc.nSCId
AND nSalesCloseDetId = scd.nId
AND nDrAccount IN(14,13,12,11,10,9,8,7,6,5) AND vTable IN(C,C,C,C,C,C,C,C,C,C)) END) > 0 THEN 1 ELSE 0 END AS balCheck
FROM tbl_SalesClosing sc LEFT JOIN tbl_SalesClosingDetails scd ON sc.nSCId = scd.nSalesCloseId
LEFT JOIN tbl_SalesAccountDetails sad ON sc.nSCId = sad.nSalesCloseId
LEFT JOIN tbl_CashAllocation ca ON (ca.nSalesCloseId = sc.nSCId AND scd.nId = ca.nSalesCloseDetId)
WHERE sad.nAcHead = '18'   AND sc.nDivision='1' AND (sc.vLocation='2' OR sc.vLocation='2')
GROUP BY scd.nId HAVING (balCheck > 0 OR scd.nId NOT IN(SELECT nSalesCloseDetId FROM
tbl_CashAllocation)))
UNION ALL (SELECT cr.dDate as date,cr.vLocation as location,'CR' as tblName,crd.vTable,
cr.nCRId as tableId,cr.vDocNumber as document,crd.nId as detId,
crd.vACode as account,crd.nAmount as debit,cr.vRemarks as description,crd.nAmount as allocAmt,'' as balCheck FROM
tbl_CashReceipt cr LEFT JOIN tbl_CashReceiptDetails crd ON crd.nCashId = cr.nCRId WHERE cr.vActive='1'
AND cr.nDivision='1' AND (cr.vLocation='2' OR cr.vLocation='2') AND (crd.vAcSubCode='18' OR crd.vACode='18' OR cr.nAcCode='18') AND cr.nCRId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'CR'))
UNION ALL (SELECT cp.dDate as date,cp.vLocation as location,'CP' as tblName,cpd.vTable,cp.nCPId as tableId,
cp.vDocNumber as document,cpd.nId as detId,cpd.vACode as account,
cpd.nAmount as debit,cp.vRemarks as description,cpd.nAmount as allocAmt,'' as balCheck FROM
tbl_CashPayment cp LEFT
JOIN tbl_CashPaymentDetails cpd ON
cpd.nCashPId = cp.nCPId WHERE cp.vActive='1'  AND cp.nDivision='1' AND (cp.vLocation='2' OR cp.vLocation='2') AND (cpd.vAcSubCode='18' OR cpd.vACode='18' OR cp.nAcCode='18')
AND cp.nCPId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'CP'))
UNION ALL (SELECT bb.dDate as date,bb.vLocation as location,'BR' as tblName,bbd.vTable,bb.nBBId as tableId,
bb.vDocNumber as document, bbd.nId as detId,
bbd.vACode as account,
bbd.nAmount as debit,bb.vRemarks as description,bbd.nAmount as allocAmt,'' as balCheck FROM tbl_BankBook bb LEFT
JOIN tbl_BankBookDetails bbd ON
bbd.nBankbookId = bb.nBBId WHERE bb.vActive='1'  AND bb.nDivision='1' AND (bb.vLocation='2' OR bb.vLocation='2') AND (bbd.vAcSubCode='18' OR bbd.vACode='18' OR bb.nAcCode='18')
AND bb.nBBId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'BR'))
UNION ALL (SELECT bp.dDate as date,bp.vLocation as location,'BP' as tblName,bpd.vTable,bp.nBPId as tableId,
bp.vDocNumber as document,bpd.nId as detId,
bpd.vACode as account,
bpd.nAmount as debit,bp.vRemarks as description,bpd.nAmount as allocAmt,'' as balCheck FROM tbl_BankBookPayment
bp LEFT JOIN tbl_BankBookPaymentDetails
bpd ON bpd.nBankPaymentId = bp.nBPId WHERE bp.vActive='1'  AND bp.nDivision='1' AND (bp.vLocation='2' OR bp.vLocation='2') AND (bpd.vAcSubCode='18' OR bpd.vACode='18' OR bp.nAcCode='18')
AND bp.nBPId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'BP'))
UNION ALL (SELECT jv.dDate as date,jv.vLocation as location,'JV' as tblName,jvd.vTable,jv.nJVId as tableId,
jv.vDocNumber as document,jvd.nId as detId,
jvd.vACode as account,
jvd.nAmount as debit,jv.vRemarks as description,jvd.nAmount as allocAmt,'' as balCheck
FROM tbl_Jv jv LEFT JOIN tbl_JvDetails jvd ON jvd.nJournalId = jv.nJVId
WHERE jv.vActive='1'  AND jv.nDivision='1' AND (jv.vLocation='2' OR jv.vLocation='2') AND (jvd.vAcSubCode='18' OR jvd.vACode='18')
AND jv.nJVId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'JV'))
UNION ALL (SELECT cv.dDate as date,cv.vLocation as location,'CV' as tblName,cvd.vTable,cv.nCVId as tableId,
cv.vDocNumber as document,cvd.nId as detId,
cvd.vACode as account,
cvd.nAmount as debit,cv.vRemarks as description,cvd.nAmount as allocAmt,'' as balCheck FROM
tbl_CreditVoucher cv LEFT JOIN tbl_CreditVoucherDetails cvd
ON cvd.nCreditId = cv.nCVId WHERE cv.vActive='1'  AND cv.nDivision='1' AND (cv.vLocation='2' OR cv.vLocation='2') AND (cvd.vAcSubCode='18' OR cvd.vACode='18')
AND cv.nCVId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'CV'))
UNION ALL (SELECT dv.dDate as date,dv.vLocation as location,'DV' as tblName,dvd.vTable,dv.nDVId as tableId,
dv.vDocNumber as document,dvd.nId as detId,
dvd.vACode as account,
dvd.nAmount as debit,dv.vRemarks as description,dvd.nAmount as allocAmt,'' as balCheck FROM
tbl_DebitVoucher dv LEFT JOIN tbl_DebitVoucherDetails dvd ON
dvd.nDebitId = dv.nDVId WHERE dv.vActive='1'  AND dv.nDivision='1' AND (dv.vLocation='2' OR dv.vLocation='2') AND (dvd.vAcSubCode='18' OR dvd.vACode='18')
AND dv.nDVId NOT IN (SELECT nReceiptDId FROM tbl_CashAllocation WHERE vAllType = 'DV'))
UNION ALL (SELECT ac.dDate as date,ac.vLocation as location,'ACM' as tblName,acd.vTable,ac.nACId as tableId,
ac.vDocNumber as document,acd.nId as detId,
acd.vACode as account,
acd.nAmount as debit,ac.vRemarks as description,acd.nAmount as allocAmt,'' as balCheck FROM
tbl_AcmEntry ac LEFT JOIN tbl_AcmEntryDetails acd ON
acd.nAcId = ac.nACId WHERE ac.vActive='1'  AND ac.nDivision='1' AND (ac.vLocation='2' OR ac.vLocation='2') AND (acd.vAcSubCode='18' OR acd.vAcSubCode='18'))
UNION ALL (SELECT ad.dDate as date,ad.vLocation as location,'ADM' as tblName,ade.vTable,ad.nADId as tableId,
ad.vDocNumber as document,ade.nId as detId,
ade.vACode as account,
ade.nAmount as debit,ad.vRemarks as description,ade.nAmount as allocAmt,'' as balCheck FROM
tbl_AdmEntry ad LEFT JOIN tbl_AdmEntryDetails ade ON
ade.nAdId = ad.nADId WHERE ad.vActive='1'  AND ad.nDivision='1' AND (ad.vLocation='2' OR ad.vLocation='2') AND (ade.vAcSubCode='18' OR ade.vAcSubCode='18')) ORDER BY date DESC



