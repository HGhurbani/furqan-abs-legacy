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


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">

    <thead>

    <?php

    if(!empty($this->ddlLocation)):

        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");

        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");

        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");

        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");

        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");

        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");

        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");

        $link   = base_url().'upload/location/'.$logo;

    elseif(!empty($this->ddlLocation2)):

        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation2."'");

        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation2."'");

        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation2."'");

        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation2."'");

        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation2."'");

        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation2."'");

        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation2."'");

        $link   = base_url().'upload/location/'.$logo;

    else:

        $logo   = $this->common_model->get_field_name('Division','vImg'," WHERE nId='".$this->ddlDivision."'");

        $name   = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$this->ddlDivision."'");

        $addr   = $this->common_model->get_field_name('Division','vAddress'," WHERE nId='".$this->ddlDivision."'");

        $phone  = $this->common_model->get_field_name('Division','vPhone'," WHERE nId='".$this->ddlDivision."'");

        $mobile = $this->common_model->get_field_name('Division','vMobile'," WHERE nId='".$this->ddlDivision."'");

        $email  = $this->common_model->get_field_name('Division','vEmail'," WHERE nId='".$this->ddlDivision."'");

        $web    = $this->common_model->get_field_name('Division','vWeb'," WHERE nId='".$this->ddlDivision."'");

        $link   = base_url().'upload/division/'.$logo;

    endif;

    ?>

    

    <tr>

        

<th align="center" colspan="18">

            <div ></div>

                <span style="font-size: 11px;"><?php echo $name;?></span><br>

                <?php echo $addr;?><br>

                <?php echo PHONE;?>: <?php echo $phone;?>

                <?php echo MOBILE;?>: <?php echo $mobile;?><br>

                <?php echo EMAIL;?>: <?php echo $email;?>,

                <?php echo WEB;?>: <?php echo $web;?>

            </th>

			

            </tr>

    


<tr>

        <td colspan="6" style="border:none; " height="25px" align="left">&nbsp;<strong><?php echo SUBLEDGERDETAILREPORT;?></strong></td>

    

       <td colspan="6" style="border:none;" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>

    </tr>
    

    <tr>
<td colspan="12" style="border:none; " height="25px" align="left">&nbsp;<?php echo FROMDATE; echo" : ";?><?php echo $this->txtFromDate;?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo TODATE; echo" : ";?><?php echo $this->txtToDate;?> </td>

        
		
    </tr>

   

<?php
header_remove('Set-Cookie');
ini_set('memory_limit', '-1');
	
    if(!empty($list_value2)){

        $recoed_present = 1;

        $i = 1;

		$chkAccount = array();

		$balance = 0;
		$abs=0;
$open_list_value = array();
		//$open_list_value =  $this->sub_report_model->getOpeningBalForAcc(39,'','A', 'receivable');
		
        foreach($list_value2 as $key=>$item):
		

                    if($item->OutReport == 'O' ) { $openBalName  = "receivable_outstanding"; } else { $openBalName = "receivable";}
	//echo "test";
		//echo"<pre>";
		//echo $item->vTable.$item->account."---".$item->vAcSubCode."-------".$item->vACode."<br>";
		//var_dump($open_list_value);
//echo"</pre>";		
if($item->account==39){
	$item->vAcSubCode=39;
	$item->vACode=39;
}
if($item->account==55){
	$item->vAcSubCode=55;
	$item->vACode=55;
}
if($item->account==211){
	$item->vAcSubCode=211;
	$item->vACode=211;
}
if($abs==0){
		$open_list_value =  $this->sub_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable, $openBalName);
$abs=1;}
            $sj_cr_dr = '';

/*            if($item->OutReport == 'O' && $item->debit == 0 &&  $item->credit == 0 && ($item->tblName == 'CR' || $item->tblName == 'CP' || $item->tblName == 'BR' || $item->tblName == 'BP' || $item->tblName == 'JV' || $item->tblName == 'CV' || $item->tblName == 'DV'))  {

echo $item->debit . " --- ".  $item->credit;

                continue;

            } */



        if($item->tblName == 'SJ' || $item->tblName == 'SJ_R'){

            $type = explode('-',$item->tableId);

            $tableId_1 = $type[0];

            $sj_type = $type[1];

        }

        else {

            $tableId_1 = $item->tableId;

        }



        if(($item->tblName == 'SJ' || $item->tblName == 'SJ_R')&& $item->OutReport == 'O'):

               switch($item->vType) { 

                    case "Xo" : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $item->vNumber."'"); break;

                    case "Tkt" : $tktNum = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='". $item->vNumber."'"); break;

                   case "Htl" : $tktNum = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='". $item->vNumber."'"); break; 

               }

            if($tktNum=='') $tktNum = $item->vNumber;

                $sj_cr_dr = $this->common_model->get_field_name('CashAllocation','MIN(vBalAmt)'," WHERE nSalesCloseId = " . $tableId_1 . " AND vAllTktNumber = '" . $tktNum . "' AND vTable = '" . $item->vTable . "' AND nDrAccount = " . $item->vACode . " GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount "); 

                if(!empty($sj_cr_dr) && $sj_cr_dr == 0) {

                    continue;

                }

            endif; 





		    if(!in_array($item->vACode,$chkAccount)  || !in_array($item->vAcSubCode,$chkAccountSub) ):

				if( $i !=1 ):

            if(!($totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):



			?>

					<tr id="bg">

						<td height='25px' colspan="5" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>

						<td align="right"><?php echo number_format((float)$totalDebit_per_group, 3, ".", "");?></td>

						<td align="right"><?php echo number_format((float)$totalCredit_per_group, 3, ".", "");?></td>

						<td align="right"><?php echo number_format((float)$totalBalance_per_group, 3, ".", "");?></td>

					</tr>

				<?php

                endif;

					$totalDebit_per_group 	= 0;

					$totalCredit_per_group 	= 0;

					$totalBalance_per_group	= 0;

					$balance = 0;

				?>

				<?php endif; 

	
	  
//echo $item->vACode."--".$item->vTable."--".$item->vAcSubCode."<br>";
				switch($item->vTable){
					
					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); break;

					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'"); break;

					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode  ."'"); break;

					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->vACode  ."'"); break;

					default: $item->vAcSubCode = $item->vACode ; $drAccountName = ""; break;

				}//end switch
				
				if($item->vAcSubCode==0){
					$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");
				
				}
				else{
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");
				}

                    $balance = $balance + $open_list_value['debit'] - $open_list_value['credit'];

                    if( $open_list_value['debit'] ==0 && $open_list_value['credit'] ==0 && $item->debit ==0 && $item->credit == 0) continue;

                    $recoed_present +=1;

						if($drAccountName != '' || !empty($drAccountName)) { 

						?>

						<tr style="border:0px;">

							<td id="ac" style="border:0px; padding-bottom: 8px;" colspan="9"><strong><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></td>

						</tr>

						<?php

					}

					else { 

					?>

						<tr style="border:0px;">

							<td id="ac" style="border:0px; padding-bottom: 8px;" colspan="9"><strong><?php echo $subAccName; ?></strong></td>

						</tr>

						<?php 

					}													

					



?>



<style type="text/css">

body{

  -webkit-print-color-adjust:exact;

}

  table { font-size: 9px; }

</style>

<style media="print">

@media print {
    @page {
		margin-left: 0.5cm;
		
		margin-right: 0.5cm;
     
    }
   
}

@media print
{
  table { page-break-inside:auto !important; }
    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
}
#dt{
width:8%!important;	
}
#doc{
width:15%!important;	
}
#txt{
	text-transform: capitalize;
}
#ac{
	text-transform: capitalize;
}
  #bg{  }
/*#des{
	width:25%!important;
}*/
 

</style>

<style>
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
}
td{font-size: 12px;

    border: 1px solid black;

border-collapse: collapse;

}

</style>



    <tr id="bg">

        <td height="25px" align="center"><?php echo 'Srno';?></td>

        <td  align="center"><?php echo CUSTDATE;?></td>

        <td  align="center"><?php echo DOCUMENTNUMBER;?></td>

        <!--<td align="center"><?php echo TICKETNO;?></td>-->

        <td align="center"><?php echo TICKETNO; echo" / ";echo DESCRIPTION;?></td>

        <td   align="center"><?php echo LPO;?></td>

        <td align="center"><?php echo DEBIT;?></td>

        <td align="center"><?php echo CREDIT;?></td>

        <td align="center"><?php echo BALANCE;?></td>

    </tr>



    </thead>



    <tbody>



    

            <tr>
			
                <td height="25px" colspan="5" align="right"><strong><?php echo OPENINGBALANCE?></strong></td>

                <td align="right"><?php  echo $open_list_value['debit'];?></td>

                <td align="right"><?php echo $open_list_value['credit'];?></td>

                <td align="right"><?php echo number_format((float)$balance, 3, ".", "");?></td>

            </tr>

 

					

            <?php                

            $totalDebit_per_group   = $totalDebit_per_group+$open_list_value['debit'];

            $totalCredit_per_group  = $totalCredit_per_group+$open_list_value['credit'];



            $totalDebit   = $totalDebit+$open_list_value['debit'];

            $totalCredit  = $totalCredit+$open_list_value['credit'];

            $totalBalance = $totalDebit - $totalCredit;

         	endif;





			$exchangerate = $item->vExchange;

			$currency 	 = $item->nCurrency;

			$fc 		 = $item->vFCAmount;

			$passenger 	 = $item->vRefName;

			$conf		 = '&nbsp';

			$issued		 = '&nbsp';					

			$ticketNum   = '&nbsp';

            $description = $item->description;

			$lpo = $item->vRefNumber;



            switch($item->tblName)

            {

                case "CR":

                    $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$tableId_1;

                    break;



                case "CP":

                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$tableId_1;

                    break;



                case "BR":

                    $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$tableId_1;
					$check="<br>ChequeNumber:".$this->common_model->get_field_name('BankBook', 'vChequeNumber', " WHERE nBBId='".$tableId_1."'" );

                    break;



                case "BP":

                    $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$tableId_1;
					$check="<br>ChequeNumber:".$this->common_model->get_field_name('BankBookPayment', 'vChequeNumber', " WHERE nBPId='".$tableId_1."'" );

                    break;



                case "JV":

                    $path = base_url().'index.php/jv/editJv/5/e/'.$tableId_1;

                    break;



                case "AC":

                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$tableId_1;

                    break;



                case "AD":

                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$tableId_1;

                    break;

                case "SJ_R":

                case "SJ":

					//$sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$tableId_1."'" );

					if($sj_type == 'S'){

	                    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$tableId_1."'");

						$lpo = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$tableId_1."' 

									AND vType = '" .	$item->vType ."' AND vTktNumber = " .$item->vNumber ." "); 						

    	                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$tableId_1.'/cl';
						$ti='';
						//$typ=$this->common_model->get_field_name('SalesClosingDetails','vType'," WHERE nSalesCloseId='".$tableId_1."'");
							$pass=''; $ticket=''; $desc  = ''; $vNumber='';
							
					if($item->vType=='Tkt'){
					if($item->vNumber=='' || $item->vNumber=='0'){
							$ti=$this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vType ='Tkt' AND nSalesCloseId='".$tableId_1."'");
					}else{
						$ti=$item->vNumber;
					}
					$ticket=$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$ti."'");
					$pass=$this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$ti."'");
					$vNumber=$ti;
					$desc  = "Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");
					}
					if($item->vType=='Xo'){
						$ti=$this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vType='Xo' AND nSalesCloseId='".$tableId_1."'");
				
					$ticket=$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$ti."'");
					$pass=$this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='".$ti."'");
					$vNumber=$ti;
					$desc  = "XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");

					}

					if($item->vType=='Htl'){
						if($item->vNumber=='' || $item->vNumber=='0'){
							$ti=$this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE vType ='Htl' AND nSalesCloseId='".$tableId_1."'");
					}else{
						$ti=$item->vNumber;
					}
					$ticket=$this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$ti."'");
					$pass=$this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$ti."'");
											$vNumber=$ti;
						$hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");

						$desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");

					}}

					else {

	                    $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$tableId_1."'");					

						$lpo = $this->common_model->get_field_name('RefundClosingDetails','vLPO'," WHERE nRefundCloseId='".$tableId_1."'

										AND vType = '" .	$item->vType ."' AND nTicketNumber = " .$item->vNumber ." " ); 						

    	                //$path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$salesDetId.'/'.$tableId_1.'';

    	                $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$tableId_1.'';

					$typ=$this->common_model->get_field_name('RefundClosingDetails','vType'," WHERE nRefundCloseId='".$tableId_1."'");
						$ti=$this->common_model->get_field_name('RefundClosingDetails','nTicketNumber'," WHERE nRefundCloseId='".$tableId_1."' AND vType='".$item->vType."'");
					$pass=$this->common_model->get_field_name('RefundClosing','vPassenger'," WHERE nRCId='".$tableId_1."'");
					
					
					if($item->vType=='Tkt'){
						$ticket=$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$ti."'");
					}
					if($item->vType=='Htl'){
					$ticket=$this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$ti."' ");
					}
					if($item->vType=='Xo'){
						$ticket=$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$ti."'  ");
					}
					if($ticket==''){
						$ticket=$ti;
					}
					/* if($typ=='Tkt'){
					$vNumber=$ti;
					$desc  = "Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");

					}
					if($typ=='Xo'){
					$ticket=$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$ti."'");
					$pass=$this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='".$ti."'");
					$vNumber=$ti;
					$desc  = "XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");

					}

					if($typ=='Htl'){
						$vNumber=$ti;
						$hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");

						$desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");

					$ticket=$this->common_model->get_field_name('HotelVouche','vTicktManual'," WHERE nHVId='".$ti."'");
					$pass=$this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$ti."'");
					}
					//$salesDetId  = $this->common_model->get_field_name('RefundRegister','nId'," WHERE nRefundCloseId='".$tableId_1."'");					
*/
					
					
					}



                    $getTktValue  = $this->sub_report_model->getTktNumberValues($item->vType,$item->vNumber);

                    $description = $getTktValue['desc'];

					$conf =	$getTktValue['conf'];	 

					$issued =	$getTktValue['issued'];	 					

					$passenger = $getTktValue['pass'];

					$currency =	'';//$getTktValue['currency'];	 					

					$fc = '';//$getTktValue['fc'];					

					$exchangerate = '';//$getTktValue['exchangerate'];					

                    //$ticketNum = $getTktValue['tktVal'];

                    break;



                case "CV":

                    $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$tableId_1;

                    break;



                case "DV":

                    $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$tableId_1;

                    break;



            }//end switch





            switch($item->mode)

            {

                case "Dr":

                    $debit = $item->debit;

                    $credit = 0;

                    break;



                case "Cr":

                    $debit = 0;

                    $credit = $item->debit;

                    break;



                default:

                    if('O' == $item->OutReport && ( $item->tblName == 'SJ' || $item->tblName == 'SJ_R' )&& !empty($sj_cr_dr)){

                        if($item->tblName == 'SJ'){

                            $debit = ($item->debit==0)?0:$sj_cr_dr;

                            $credit = ($item->credit==0)?0:$sj_cr_dr;

                        }

                        if($item->tblName == 'SJ_R'){

                            //$sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$tableId_1."'" ); 

                            if($sj_type == 'S'){ $debit =$sj_cr_dr; $credit = 0;}

                            else { $debit = 0; $credit = $sj_cr_dr; }

                        }

                    } 

                    else {

                        if($item->tblName == 'SJ_R') {

                            //$sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$tableId_1."'" ); 

                            if($sj_type == 'S'){

                                //$debit = $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber ='".$item->vNumber."'");	

                                //$debit = $this->common_model->get_field_name('SalesClosingDetails','vAllocation'," WHERE vTktNumber ='".$item->vNumber."' AND vType = '" . $item->vType . "'");	

                                $credit = 0;

                            }

                            else {

                                $credit = $this->common_model->get_field_name('RefundClosingDetails','vPaidAmt'," WHERE nTicketNumber ='".$item->vNumber."' AND vType = '" . $item->vType . "'");

                                $debit = 0;

                            }

                            

                        }else {

                            $debit = ($item->debit==' ')?0:$item->debit;

                            $credit = ($item->credit==' ')?0:$item->credit;

                        }

                    }

                    break;

            }

			$balance = $balance + $debit - $credit;



            if($debit != 0 || $credit != 0){

            ?>

                <tr>

					<td height='25px' align="center"><?php echo $key+1;?></td>

                    <td align="center" id="dt"><?php echo date("d-M-y", strtotime($item->date));?></td>

                    <td id="doc" align="center"><a style="text-decoration: none;" href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
					
					
					<td>
					<table>
					<?php 
					if($item->vType!=''){ 
				if($item->vNumber==0){
if($sj_type=='S'){					
					if($sj_type=='S'){
						
						
						$query="Select vTktNumber,vType From tbl_SalesClosingDetails WHERE nSalesCloseId='".$tableId_1."'";
					
						
					}else{
							$query="Select nTicketNumber as vTktNumber,vType From tbl_RefundClosingDetails WHERE nRefundCloseId='".$tableId_1."'";

	                   }
					$adminGroupList 		= $this->db->query($query);
        if ($adminGroupList->num_rows() > 0)
        {
            foreach ($adminGroupList->result() as $key=>$row)
            {
					?>
					<tr>
					  <td  style="width:67px; border:0px;">
					  <?php 
					  switch($row->vType) { 

                    case "Xo" : 
					
					echo $tktNum1 = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $row->vTktNumber."'"); 
					
					break;

                    case "Tkt" : echo $tktNum1 = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='". $row->vTktNumber."'"); 
					
					break;

                   case "Htl" : echo $tktNum1 = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='". $row->vTktNumber."'"); 
				   break; 

               } 
			   if($sj_type =='R'){
		echo $salesDetId  = $this->common_model->get_field_name('RefundRegisterDetails','nTktNum'," WHERE vNewOld='O' AND nTktNum='".$row->vTktNumber."'");					
		}
					  ?>
					  
					  </td>
					  <td style="border:0px;" id="txt">
					  <?php 
					   switch($row->vType) { 

                    case "Xo" : 
					
					echo $tktNum1 = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='". $row->vTktNumber."'")."<br>";
					echo $desc  = "   XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$row->vTktNumber."'")."<br>";
break;

                    case "Tkt" : echo $tktNum1 = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='". $row->vTktNumber."'")."<br>";
					echo $desc  = "  Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$row->vTktNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$row->vTktNumber."'")."<br>";
					
					break;
					

                   case "Htl" : echo $tktNum1 = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='". $row->vTktNumber."'")."<br>"; 
				   
				   $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$row->vTktNumber."'");

						echo $desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'<br> ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$row->vTktNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$row->vTktNumber."'")))."<br>";
						//'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->vTktNumber."'")

				   break; 

               } 
					  ?></td>
					  </tr>
					<?php 
					}
}}else{
	 $rdelid=$this->common_model->get_field_name('SystemJvDetails','rdelid'," WHERE nSjDId='".$item->detId."'");
				if($rdelid==0){
				$rdelid=$this->common_model->get_field_name('SystemJvDetails','sdelid'," WHERE nSjDId='".$item->detId."'");
		
				}	
					if($sj_type =='R'){
				  $vNumber  = $this->common_model->get_field_name('RefundClosingDetails','nTicketNumber'," WHERE  nId='".$rdelid."'");					
				}
					?>
					<tr>
					  <td  style="width:67px; border:0px;" id="txt">
					  <?php 
					  if($sj_type =='R'){
				  $NO  = $this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE  nTktNum='".$vNumber."'");					
				}else{
					$NO='N';
				}
					  if($NO=='N'){
					  switch($item->vType) { 

                    case "Xo" : 
					
					echo $tktNum1 = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$vNumber."'")."<br>"; 
					
					break;

                    case "Tkt" : echo $tktNum1 = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$vNumber."'")."<br>"; 
					
					break;

                   case "Htl" : echo $tktNum1 = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$vNumber."'")."<br>"; 
				   break; 

					  } }else{
						 echo $vNumber; 
					  }
			  // if($tktNum1==''){
				   
			  // }
					  ?>
					  
					  </td>
					  <td style="border:0px;" id="txt">
					  <?php 
					  if($NO=='N'){
					   switch($item->vType) { 

                    case "Xo" : 
					
					echo $tktNum1 = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='". $vNumber."'")."<br>";
					echo $desc  = "   XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'")."<br>";
break;

                    case "Tkt" : echo $tktNum1 = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='". $vNumber."'")."<br>";
					echo $desc  = "  Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'")."<br>";
					
					break;
					

                   case "Htl" : echo $tktNum1 = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='". $vNumber."'")."<br>"; 
				   
				   $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");

						echo $desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'<br> ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'")))."<br>";
						//'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$row->vTktNumber."'")

				   break; 

					  } }else{
						echo  $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE vNewOld='O' AND nTktNum='".$vNumber."'");					
		
					  }
					  ?></td></tr>
	
	
<?php }
					}
					else{?>
						
						<tr>
					  <td  style="border:0px; width:67px;"><?php 
					  
					  if($sj_type =='R'){
				  $NO  = $this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE  nTktNum='".$item->vNumber."'");					
				}else{
					$NO='N';
				}
					  if($NO=='N'){
					  switch($item->vType) { 

                    case "Xo" : 
					
					echo $tktNum1 = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $item->vNumber."'")."<br>"; 
					
					break;

                    case "Tkt" : echo $tktNum1 = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='". $item->vNumber."'")."<br>"; 
					
					break;

                   case "Htl" : echo $tktNum1 = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='". $item->vNumber."'")."<br>"; 
				   break; 

               } 
			   }else{
						echo  $item->vNumber;					
		
					  }
					  ?>
					  </td>
					  <td style="border:0px;" id="txt" ><?php 
					  if($NO=='N'){
					  switch($item->vType) { 

                    case "Xo" : 
					
					echo $tktNum1 = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='". $item->vNumber."'")."<br>";
					echo $desc  = "   XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$item->vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$item->vNumber."'")."<br>";
break;

                    case "Tkt" : echo $tktNum1 = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='". $item->vNumber."'")."<br>";
					echo $desc  = "  Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$item->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$item->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$item->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$item->vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$item->vNumber."'")."<br>";
					
					break;
					

                   case "Htl" : echo $tktNum1 = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='". $item->vNumber."'")."<br>"; 
				   
				   $hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$item->vNumber."'");

						echo $desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'<br> ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$item->vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$item->vNumber."'")))."<br>";
//.'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$item->vNumber."'")
				   break; 

               } }else{
						echo  $this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE vNewOld='O' AND nTktNum='".$item->vNumber."'");					
		
					  }
					  ?>
					  
					  </td>
					  </tr>
					<?php }
					}else{?> 
					<tr>
					<td style="width:67px; border:0px;"></td>
					<td id="txt" style="border:0px; word-break: keep-all;" ><?php echo $passenger;?><?php echo "<br>";?><?php if($description!=''){ echo substr($description,0,50); }?></td>
					</tr>
					<?php }?>
					
					
					
					
				  </table></td>

				  

                    <td  align="center"><?php echo $lpo;?></td>

                    <td align="right"><?php echo $debit;?></td>

                    <td align="right"><?php echo $credit;?></td>

                    <td align="right"><?php echo number_format((float)$balance, 3, ".", "");?></td>

                </tr>

            <?php  $pass=''; $ticket=''; $desc  = ''; $vNumber='';
			$i = $i + 1;

            }

            $chkAccount[] 			= $item->vACode;

            $chkAccountSub[] 		= $item->vAcSubCode;

		    $totalDebit_per_group   = $totalDebit_per_group+$debit;

            $totalCredit_per_group  = $totalCredit_per_group+$credit;

            $totalBalance_per_group = $balance;

			

            $totalDebit   = $totalDebit+$debit;

            $totalCredit  = $totalCredit+$credit;

            $totalBalance = $totalDebit - $totalCredit;

        endforeach;

        if($recoed_present !=1):  if($i !=1):

            if(!($totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):



?>



<tr id="bg">

        <td height="25px" colspan="5" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>

        <td align="right"><?php echo number_format((float)$totalDebit_per_group, 3, ".", "");?></td>

						<td align="right"><?php echo number_format((float)$totalCredit_per_group, 3, ".", "");?></td>

						<td align="right"><?php echo number_format((float)$totalBalance_per_group, 3, ".", "");?></td>

    </tr>

   <?php endif; endif;?>



    <tr id="bg">

        <td height="25" colspan="5" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

        <td align="right"><?php echo number_format((float)$totalDebit, 3, ".", "");?></td>

        <td align="right"><?php echo number_format((float)$totalCredit, 3, ".", "");?></td>

        <td align="right"><?php echo number_format((float)$totalBalance, 3, ".", "");?></td>

    </tr>



    <?php else:?>

    <tr>

        <td style="border:0" colspan="10" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

    <?php endif;

    }

    else{ ?>

    <tr>

        <td style="border:0" colspan="10" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

        <?php

    }//end if

    ?>

	<style type="text/css">

@media print {

input#btnPrint,

input#btnCancel {

display: none;

}

}

</style>

 
    </tbody>

</table>

