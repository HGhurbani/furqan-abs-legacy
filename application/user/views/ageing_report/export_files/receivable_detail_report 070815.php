<table width="100%" border="0" cellspacing="10" cellpadding="10" class="reportForm" id="reportForm">
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
        <th colspan="16">

            <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
    <tr><td></td></tr>
    <tr>
        <th style="color:#3BB3C2;" align="right"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $fromDate;?></th>
        <th style="color:#3BB3C2;" align="right"><?php echo TODATE;?></th>
        <th align="left"><?php echo $toDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
    <tr><td></td></tr>

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
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo DOCUMENTNUMBER;?></th>
        <th align="left"><?php echo TICKETNAME;?></th>
        <th align="left"><?php echo PASSENGERNAME;?></th>
        <th align="left"><?php echo TRAVELSETUPDETAILS;?></th>
        <th align="left"><?php echo LPO;?></th>
        <th align="left"><?php echo $slab1 . " " .DAYS;?></th>
        <th align="left"><?php echo ($slab1+1) . "-" . $slab2 ." " . DAYS;?></th>
        <th align="left"><?php echo ($slab2+1) . "-" . $slab3 . " " . DAYS;?></th>
        <th align="left"><?php echo ($slab3+1) . "-" . $slab4 . " " . DAYS;?></th>
        <th align="left"><?php echo ABOVE . " " . $slab4 . " " .DAYS;?></th>
        <th align="left"><?php echo UNALLOCATED;?></th>
        <th align="left"><?php echo BALANCE;?></th>
    </tr>
<!--    <tr>
        <th colspan="11" align="center"><?php echo OPENINGBALANCE;?></th>
   		</tr>
-->	

    <tbody>

    <tr><td></td></tr>
    <?php
    if(!empty($list_value)){
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
						<td colspan="8" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
						<td><?php echo $totalS1_per_group ;?></td>
						<td><?php echo $totalS2_per_group ;?></td>
						<td><?php echo $totalS3_per_group ;?></td>
						<td><?php echo $totalS4_per_group ;?></td>
						<td><?php echo $totalS5_per_group ;?></td>
						<td><?php echo $totalCredit_per_group ;?></td>
						<td><?php echo $totalBalance_per_group ;?></td>
					</tr>
                    <tr><td></td></tr>
				<?php
                endif;

                $totalDebit += $totalDebit_per_group;
                $totalS1 += $totalS1_per_group;
                $totalS2 += $totalS2_per_group;
                $totalS3 += $totalS3_per_group;
                $totalS4 += $totalS4_per_group;
                $totalS5 += $totalS5_per_group;
                $totalCredit += $totalCredit_per_group;
                $totalBalance += $totalBalance_per_group;

                $totalDebit_per_group 	= 0;
                $totalS1_per_group 	= 0;
                $totalS2_per_group 	= 0;
                $totalS3_per_group 	= 0;
                $totalS4_per_group 	= 0;
                $totalS5_per_group 	= 0;
                $totalCredit_per_group 	= 0;
                $totalBalance_per_group	= 0;
                $balance = 0;
				?>
				<?php endif; 
	
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
                    $totalS1_per_group   += $open_list_value['s1'];
                    $totalS2_per_group   += $open_list_value['s2'];
                    $totalS3_per_group   += $open_list_value['s3'];
                    $totalS4_per_group   += $open_list_value['s4'];
                    $totalS5_per_group   += $open_list_value['s5'];
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
                <th colspan="8" align="right"><strong><?php echo OPENINGBALANCE?></strong></th>
                <th><?php echo $open_list_value['s1'];?></th>
                <th><?php echo $open_list_value['s2'];?></th>
                <th><?php echo $open_list_value['s3'];?></th>
                <th><?php echo $open_list_value['s4'];?></th>
                <th><?php echo $open_list_value['s5'];?></th>
                <th><?php echo $open_list_value['alloc_credit'];?></th>
                <th><?php echo $balance;?></th>
            </tr>
 
					
            <?php                

            //$totalBalance = $open_list_value['debit']- $open_list_value['credit'];
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
    	                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
					}
					else {
	                    $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$item->tableId."'");					
						$lpo = $this->common_model->get_field_name('RefundClosingDetails','vLPO'," WHERE nRefundCloseId='".$item->tableId."'
										AND vType = '" .	$item->vType ."' AND nTicketNumber = " .$item->vNumber ." " ); 						
    	                $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$salesDetId.'/'.$item->tableId.'';
					}

                    $getTktValue  = $this->ageing_report_model->getTktNumberValues($item->vType,$item->vNumber);
                    $description = $getTktValue['desc'];
					$conf =	$getTktValue['conf'];	 
					$issued =	$getTktValue['issued'];	 					
					$passenger = $getTktValue['pass'];
					$currency =	'';//$getTktValue['currency'];	 					
					$fc = '';//$getTktValue['fc'];					
					$exchangerate = '';//$getTktValue['exchangerate'];					
                    $ticketNum = $getTktValue['tktVal'];
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
                    $credit = $item->debit;
                    break;

                default:
                    if('O' == $item->OutReport && ( $item->tblName == 'SJ' || $item->tblName == 'SJ_R' )&& !empty($sj_cr_dr)){
                        if($item->tblName == 'SJ'){
                            $debit = ($item->debit==0)?0:$sj_cr_dr;
                            $credit = ($item->credit==0)?0:$sj_cr_dr;
                        }
                        if($item->tblName == 'SJ_R'){
                            $sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" ); 
                            if($sales_refund == 'S'){ $debit =$sj_cr_dr; $credit = 0;}
                            else { $debit = 0; $credit = $sj_cr_dr; }
                        }
                    } 
                    else {
                        if($item->tblName == 'SJ_R') {
                            $sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" ); 
                            if($sales_refund == 'S'){
                                //$debit = $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber ='".$item->vNumber."'");	
                                $debit = $this->common_model->get_field_name('SalesClosingDetails','vAllocation'," WHERE vTktNumber ='".$item->vNumber."' AND vType = '" . $item->vType . "'");	
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
            $s1 = $s2 = $s3 = $s4 = '';
            if($debit != 0 || $credit != 0){
                if($debit!=0){
                    $slab =  $this->db->query("SELECT DATEDIFF('".date('Y-m-d',strtotime($toDate)) ."','" . date('Y-m-d',strtotime($item->date)) . "') AS Days");
                    $slab_days = $slab->row(); 
                    $slab_days = $slab_days->Days;
                    if($slab_days <= $slab1 )     { $s1 = $debit; }
                    else if($slab1 < $slab_days && $slab_days <= $slab2){ $s2 = $debit; }
                    else if($slab2 < $slab_days && $slab_days <= $slab3){ $s3 = $debit; }
                    else if($slab3 < $slab_days && $slab_days <= $slab4){ $s4 = $debit; }
                    else if($slab_days < $slab4){ $s5 = $debit; }
                }
            ?>
                <tr>
                    <td><?php echo $key+1;?></td>
                    <td><?php echo date("d-M-y", strtotime($item->date));?></td>
                    <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'"));?></td>
                    <td><a href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
                    <td><?php echo $ticketNum; ?></td>
                    <td style="word-break:break-all;"><?php echo $passenger; ?></td>
                    <td style="width:200px"><?php echo $description;?></td>
                    <td><?php echo $lpo;?></td>
                    <td><?php echo $s1;?></td>
                    <td><?php echo $s2;?></td>
                    <td><?php echo $s3;?></td>
                    <td><?php echo $s4;?></td>
                    <td><?php echo $s5;?></td>
                    <td><?php echo $credit;?></td>
                    <td><?php echo $balance;?></td>
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
        <td colspan="8" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
        <td><?php echo $totalS1_per_group ;?></td>
        <td><?php echo $totalS2_per_group ;?></td>
        <td><?php echo $totalS3_per_group ;?></td>
        <td><?php echo $totalS4_per_group ;?></td>
        <td><?php echo $totalS5_per_group ;?></td>
        <td><?php echo $totalCredit_per_group ;?></td>
        <td><?php echo $totalBalance_per_group ;?></td>
    </tr>
    <?php endif; endif;

        $totalDebit += $totalDebit_per_group;
        $totalS1 += $totalS1_per_group;
        $totalS2 += $totalS2_per_group;
        $totalS3 += $totalS3_per_group;
        $totalS4 += $totalS4_per_group;
        $totalS5 += $totalS5_per_group;
        $totalCredit += $totalCredit_per_group;
        $totalBalance += $totalBalance_per_group;
        ?>
            <tr><td>&nbsp;</td></tr>
    <tr>
        <td colspan="8" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><?php echo $totalS1;?></td>
        <td><?php echo $totalS2;?></td>
        <td><?php echo $totalS3;?></td>
        <td><?php echo $totalS4;?></td>
        <td><?php echo $totalS5;?></td>
        <td><?php echo $totalCredit;?></td>
        <td><?php echo $totalBalance;?></td>
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
    </tbody>
</table>
