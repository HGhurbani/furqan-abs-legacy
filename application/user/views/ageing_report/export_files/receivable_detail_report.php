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
<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $('#btnCancel').click(function()
        {
            window.location.replace('<?php echo base_url().'index.php/ageing_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('.print_logo').hide();
        $('.print_desc').hide();

        $('#btnPrint').click(function(e) {
            $('.print_logo').show();
            $('.print_desc').show();
            $('#reportForm').printElement();
            $('.print_logo').hide();
            $('.print_desc').hide();
        });

        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()
        $('#maincontent').scroll(function() {
            console.log(contentBoxExportPos)
            if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
                contentBoxExport.addClass('fixed')
            }
            else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
                contentBoxExport.removeClass('fixed')
            }
        })

    });
</script>


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
        <th colspan="16"><h3><?php echo AGEINGDETAILREPORT;?></h3></th>
    </tr>
    <tr>
                <div class="print_desc">
                <span style="font-size: 11px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?>,
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
	<style type="text/css">
  table { font-size: 9px; }
</style>

  <style>
td{
    border: 1px solid black;
border-collapse: collapse;
}
</style>
    <tr><td style="border:0"></td></tr>
    <tr>
        <th colspan="2" align="center"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $fromDate;?></th>
        <th align="center"><?php echo TODATE;?></th>
        <th align="left"><?php echo $toDate;?></th>
        <th align="left" colspan="12">&nbsp;</th>
    </tr>
   

<!--    <tr>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left" colspan="7">&nbsp;</th>
    </tr>
    <tr>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left" colspan="7">&nbsp;</th>
    </tr> -->
    <tr>
        <td height="25px" align="center"><?php echo SRNO;?></td>
        <td align="center"><?php echo CUSTDATE;?></td>
        <!--<td align="center"><?php echo LOCATIONNAME;?></td>-->
        <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo TICKETNAME;?></td>
        <td align="center"><?php echo PASSENGERNAME;?></td>
        <td align="center"><?php echo TRAVELSETUPDETAILS;?></td>
        <td align="center"><?php echo LPO;?></td>
        <td align="center"><?php echo $slab1 . " " .DAYS;?></td>
        <td align="center"><?php echo ($slab1+1) . "-" . $slab2 ." " . DAYS;?></td>
        <td align="center"><?php echo ($slab2+1) . "-" . $slab3 . " " . DAYS;?></td>
        <td align="center"><?php echo ($slab3+1) . "-" . $slab4 . " " . DAYS;?></td>
        <td align="center"><?php echo ABOVE . " " . $slab4 . " " .DAYS;?></td>
        <td align="center"><?php echo UNALLOCATED;?></td>
        <td align="center"><?php echo BALANCE;?></td>
    </tr>
<!--    <tr>
        <th colspan="11" align="center"><?php echo OPENINGBALANCE;?></th>
   		</tr>
-->	
    </thead>

    <tbody>

    
    <?php
    if(!empty($list_value)){
		//var_dump($list_value);
        $recoed_present = 1;
        $i = 1;
		$chkAccount = array();
		$balance = 0;
        foreach($list_value as $key=>$item):
            $sj_cr_dr = '';
/*            if($item->OutReport == 'O' && $item->debit == 0 &&  $item->credit == 0 && ($item->tblName == 'CR' || $item->tblName == 'CP' || $item->tblName == 'BR' || $item->tblName == 'BP' || $item->tblName == 'JV' || $item->tblName == 'CV' || $item->tblName == 'DV'))  {
echo $item->debit . " --- ".  $item->credit;
                continue;
            } */
            if(($item->tblName == 'SJ' || $item->tblName == 'SJ_R')&& $item->OutReport == 'O'):
               switch($item->vType) { 
                    case "Xo" : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $item->vNumber."'"); break;
                    case "Tkt" : $tktNum = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='". $item->vNumber."'"); break;
                    case "Htl" : $tktNum = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='". $item->vNumber."'"); break; 
               }
            if($tktNum=='') $tktNum = $item->vNumber;
// Jeenu 03/28/15 added quotes for $tktNum
                $sj_cr_dr = $this->common_model->get_field_name('CashAllocation','MIN(vBalAmt)'," WHERE nSalesCloseId = " . $item->tableId . " AND vAllTktNumber = '" . $tktNum . "' AND vTable = '" . $item->vTable . "' AND nDrAccount = " . $item->vACode . " GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount "); 
                if(!empty($sj_cr_dr) && $sj_cr_dr == 0) {
                    continue;
                }
            endif; 


		    if(!in_array($item->vACode,$chkAccount)  || !in_array($item->vAcSubCode,$chkAccountSub) ):
				if( $i !=1 ):
            if(!($totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):

			?>
					<tr>
						<td colspan="7" height='25px' align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
						<td align="center"><?php echo number_format((float)$totalS1_per_group, 3, ".", "");?></td>
						<td align="center"><?php echo number_format((float)$totalS2_per_group, 3, ".", "");?></td>
						<td align="center"><?php echo number_format((float)$totalS3_per_group, 3, ".", "");?></td>
						<td align="center"><?php echo number_format((float)$totalS4_per_group, 3, ".", "");?></td>
						<td align="center"><?php echo number_format((float)$totalS5_per_group, 3, ".", "");?></td>
						<td align="center"><?php echo number_format((float)$totalCredit_per_group, 3, ".", "");?></td>
						<td align="center"><?php echo number_format((float)$totalBalance_per_group, 3, ".", "");?></td>
					</tr>
                   
				<?php
                endif;

                $totalDebit   += $totalDebit_per_group;
                $totalS1      += $totalS1_per_group;
                $totalS2      += $totalS2_per_group;
                $totalS3 	  += $totalS3_per_group;
                $totalS4 	  += $totalS4_per_group;
                $totalS5 	  += $totalS5_per_group;
                $totalCredit  += $totalCredit_per_group;
                $totalBalance += $totalBalance_per_group;

                $totalDebit_per_group 	= 0;
                $totalS1_per_group 	    = 0;
                $totalS2_per_group 	    = 0;
                $totalS3_per_group 	    = 0;
                $totalS4_per_group 	    = 0;
                $totalS5_per_group   	= 0;
                $totalCredit_per_group 	= 0;
                $totalBalance_per_group	= 0;
                $balance     			= 0;
				?>
				<?php endif; 
                    echo $item->vTable .$item->vAcSubCode  . ' - ' . $item->vACode;
	
				switch($item->vTable){
					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); break;
					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'"); break;
					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode  ."'"); break;
					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->vACode  ."'"); break;
					default: $item->vAcSubCode = $item->vACode ; $drAccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");

                    $open_list_value = array();
                    if($item->OutReport == 'O' ) { $openBalName  = "receivable_outstanding"; } else { $openBalName = "receivable";}
                    $open_list_value =  $this->ageing_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable, $openBalName );
                    $balance = $open_list_value['alloc_debit'] - $open_list_value['alloc_credit'];
					//$balance = (float)$balance;
					//print_r($balance);
                    $totalS1_per_group     += $open_list_value['s1'];
                    $totalS2_per_group     += $open_list_value['s2'];
                    $totalS3_per_group     += $open_list_value['s3'];
                    $totalS4_per_group     += $open_list_value['s4'];
                    $totalS5_per_group     += $open_list_value['s5'];
                    $totalCredit_per_group += $open_list_value['alloc_credit']; 

                    if( $open_list_value['alloc_debit'] ==0 && $open_list_value['alloc_credit'] ==0 && $item->debit ==0 && $item->credit == 0) continue;
                    $recoed_present +=1;
						if($drAccountName != '' || !empty($drAccountName)) { 
						?>
						<tr>
							<th colspan="18" align="left"><strong><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></th>
						</tr>
						<?php
					}
					else { 
					?>
						<tr>
							<th colspan="18" align="left"><strong><?php echo $subAccName; ?></strong></th>
						</tr>
						<?php 
					}												
					

?>
            <tr>
                <td colspan="7" height='25px' align="right"><strong><?php echo OPENINGBALANCE?></strong></td>
                <td><?php echo number_format((float)$open_list_value['s1'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$open_list_value['s2'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$open_list_value['s3'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$open_list_value['s4'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$open_list_value['s5'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$open_list_value['alloc_credit'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$balance, 3, ".", "");?></td>
            </tr>
 
					
            <?php                

            //$totalBalance = $open_list_value['debit']- $open_list_value['credit'];
         	endif;


			$exchangerate = $item->vExchange;
			$currency 	  = $item->nCurrency;
			$fc 		  = $item->vFCAmount;
			$passenger 	  = $item->vRefName;
			$conf		  = '&nbsp';
			$issued		  = '&nbsp';					
			$ticketNum    = '&nbsp';
            $description  = $item->description;
			$lpo          = $item->vRefNumber;

            switch($item->tblName)
            {
                case "CR":
                    $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$item->tableId;
                    break;

                case "CP":
                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$item->tableId;
                    break;

                case "BR":
                    $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$item->tableId;
                    break;

                case "BP":
                    $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$item->tableId;
                    break;

                case "JV":
                    $path = base_url().'index.php/jv/editJv/5/e/'.$item->tableId;
                    break;

                case "AC":
                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->tableId;
                    break;

                case "AD":
                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->tableId;
                    break;
                case "SJ_R":
                case "SJ":
					$sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" );
					if($sales_refund == 'S'){
	                    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'");
						$lpo = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$item->tableId."' 
									                               AND vType = '" .	$item->vType ."' AND vTktNumber = " .$item->vNumber ." "); 						
    	                //$path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
					}
				else {
	                    $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$item->tableId."'");					
						$lpo = $this->common_model->get_field_name('RefundClosingDetails','vLPO'," WHERE nRefundCloseId='".$item->tableId."'
										                           AND vType = '" .	$item->vType ."' AND nTicketNumber = " .$item->vNumber ." " ); 						
    	                $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$salesDetId.'/'.$item->tableId.'';
					}

                    $getTktValue  = $this->ageing_report_model->getTktNumberValues($item->vType,$item->vNumber);
                    $description  = $getTktValue['desc'];
					$conf         =	$getTktValue['conf'];	 
					$issued       =	$getTktValue['issued'];	 					
					$passenger    = $getTktValue['pass'];
					$currency     =	'';//$getTktValue['currency'];	 					
					$fc           = '';//$getTktValue['fc'];					
					$exchangerate = '';//$getTktValue['exchangerate'];					
                    $ticketNum    = $getTktValue['tktVal'];
                    break;

                case "CV":
                    $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$item->tableId;
                    break;

                case "DV":
                    $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$item->tableId;
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
					 $credit =0;
                    $credit =$item->debit;
                    break;

                default:
                    if('O' == $item->OutReport && ( $item->tblName == 'SJ' || $item->tblName == 'SJ_R' )&& !empty($sj_cr_dr)){
                        if($item->tblName == 'SJ'){
                            $debit = ($item->debit==0)?0:$sj_cr_dr;
                            $credit = ($item->credit==0)?0:$sj_cr_dr;
                        }
                        if($item->tblName == 'SJ_R'){
                            $sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" ); 
                            if($sales_refund == 'S'){  $debit =$sj_cr_dr; $credit = 0;}
                            else { $debit = 0;  $credit = $sj_cr_dr; }
                        }
                    } 
                    else {
                        if($item->tblName == 'SJ_R') {
                            $sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" ); 
                            if($sales_refund == 'S'){
                                $debit = $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber ='".$item->vNumber."'AND vType = '" . $item->vType . "'");	
								 //$abx= $this->common_model->get_field_name('RefundClosingDetails','vPaidAmt'," WHERE nTicketNumber ='".$item->vNumber."' AND vType = '" . $item->vType . "'");	
								//$debit=$debit1-$abx;
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
            $s1 = $s2 = $s3 = $s4 = $s5 = '';
            if($debit != 0 || $credit != 0){
                if($debit!=0){
       $slab =  $this->db->query("SELECT DATEDIFF('".date('Y-m-d',strtotime($item->date)) ."','" . date('Y-m-d',strtotime($toDate)) . "') AS Days");
                    
					$slab_days1 = $slab->row(); 
                    $slab_days2 = $slab_days1->Days;
					 $slab_days = -$slab_days2;
					 //print  $slab_days."<br>"; 
                    if($slab_days <= 30 )     { $s1 = $debit; }
                    else if( $slab_days <= 60){ $s2 = $debit; }
                    else if($slab_days <= 90){ $s3 = $debit; }
                    else if($slab_days <= 120){ $s4 = $debit; }
                    else if($slab_days > 120){ $s5 = $debit; }
                }
            ?>
                <tr>
                    <td height='25px' align="center"><?php echo $key+1;?></td>
                    <td><?php echo date("d-M-y", strtotime($item->date));?></td>
                    
					<!--<td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'"));?></td>-->
             
					<td><?php echo $item->document;?></td>
					
                    <td><?php echo $ticketNum; ?></td>
                    <td style="word-break:break-all;"><?php echo $passenger; ?></td>
                    <td><?php echo $description;?></td>
                    <td><?php echo $lpo;?></td>
                    <td align="center"><?php echo $s1;?></td>
                    <td align="center"><?php echo $s2;?></td>
                    <td align="center"><?php echo $s3;?></td>
                    <td align="center"><?php echo $s4;?></td>
                    <td align="center"><?php echo $s5;?></td>
				
                    <td align="center"><?php echo $credit;?></td>
				   
                    <td align="center"><?php echo number_format((float)$balance, 3, ".", "");?></td>
                </tr>
            <?php $i = $i + 1;
            }
            $chkAccount[] 			= $item->vACode;
            $chkAccountSub[] 		= $item->vAcSubCode;
		    $totalDebit_per_group   = $totalDebit_per_group+$debit;
		    $totalS1_per_group   += $s1;
		    $totalS2_per_group   += $s2;
		    $totalS3_per_group   += $s3;
		    $totalS4_per_group   += $s4;
		    $totalS5_per_group   += $s5;
            $totalCredit_per_group  = $totalCredit_per_group+$credit;
            $totalBalance_per_group = $balance;
			
           // $totalDebit   = $totalDebit+$debit;
           // $totalCredit  = $totalCredit+$credit;
           // $totalBalance = $totalDebit - $totalCredit;
        endforeach;
        if($recoed_present !=1):  if($i !=1):
            if(!($totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):

?>

    <tr>
        <td colspan="7" height='25px' align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
        <td align="center"><?php echo number_format((float)$totalS1_per_group, 3, ".", "") ;?></td>
        <td align="center"><?php echo number_format((float)$totalS2_per_group, 3, ".", "") ;?></td>
        <td align="center"><?php echo number_format((float)$totalS3_per_group, 3, ".", "") ;?></td>
        <td align="center"><?php echo number_format((float)$totalS4_per_group, 3, ".", "") ;?></td>
        <td align="center"><?php echo number_format((float)$totalS5_per_group, 3, ".", "") ;?></td>
        <td align="center"><?php echo number_format((float)$totalCredit_per_group, 3, ".", "") ;?></td>
        <td align="center"><?php echo number_format((float)$totalBalance_per_group, 3, ".", "") ;?></td>
    </tr>
    <?php endif; endif;

        $totalDebit   += $totalDebit_per_group;
        $totalS1      += $totalS1_per_group;
        $totalS2      += $totalS2_per_group;
        $totalS3      += $totalS3_per_group;
        $totalS4      += $totalS4_per_group;
        $totalS5      += $totalS5_per_group;
        $totalCredit  += $totalCredit_per_group;
        $totalBalance += $totalBalance_per_group;
        ?>
            
    <tr>
        <td colspan="7" height='25px' align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="center"><?php echo number_format((float)$totalS1, 3, ".", "");?></td>
        <td align="center"><?php echo number_format((float)$totalS2, 3, ".", "");?></td>
        <td align="center"><?php echo number_format((float)$totalS3, 3, ".", "");?></td>
        <td align="center"><?php echo number_format((float)$totalS4, 3, ".", "");?></td>
        <td align="center"><?php echo number_format((float)$totalS5, 3, ".", "");?></td>
        <td align="center"><?php echo number_format((float)$totalCredit, 3, ".", "");?></td>
        <td align="center"><?php echo number_format((float)$totalBalance, 3, ".", "") ;?></td>
    </tr>

    <?php else:?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
    <?php endif;
    }
    else{ ?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
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
    <tr class="formButs">
      
        <td style="border:0" colspan="16">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
