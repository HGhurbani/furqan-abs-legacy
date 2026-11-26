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
        <th colspan="16"><h3><?php echo SUBLEDGERDETAILREPORT;?></h3></th>
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

<!--
    <tr>
        <th align="left">&nbsp;</th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left" colspan="10">&nbsp;</th>
    </tr>
    <tr>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left" colspan="10">&nbsp;</th>
    </tr> -->
    <tr>
        <th align="left" colspan="8">&nbsp;</th>
        <th align="center" colspan="5"><?php echo FOREIGNCURRENCY;?></th>
        <th align="center" colspan="3"><?php echo DOMESTICCURRENCY;?></th>
    </tr>

    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo DOCUMENTNUMBER;?></th>
        <th align="left"><?php echo VCH;?></th>
        <th align="left"><?php echo CONF;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo PASSENGERNAME;?></th>
        <th align="left"><?php echo TRAVELSETUPDETAILS;?></th>
        <th align="left"><?php echo CURRNAME;?></th>
        <th align="left"><?php echo EXCHANGERATE;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo BALANCE;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo BALANCE;?></th>
    </tr>
<!--    <tr>
        <th colspan="13" align="center"><?php echo OPENINGBALANCE;?></th>
    </tr>
-->	
    </thead>

    <tbody>

    <?php

    if(!empty($list_value1)){
        $recoed_present = 1;
        $i = 1;
		$chkAccount = array();
		$chkAccountSub = array();
		$balance = 0;
		$fcBalance = 0;
        foreach($list_value1 as $key=>$item):
             $sj_cr_dr = '';
/*             if($item->OutReport == 'O' && $item->debit == 0 && ($item->tblName == 'CR' || $item->tblName == 'CP' || $item->tblName == 'BR' || $item->tblName == 'BP' || $item->tblName == 'JV' || $item->tblName == 'CV' || $item->tblName == 'DV'))  {
                continue;
            }*/
        if($item->tblName == 'SJ' || $item->tblName == 'SJ_R'){
            $type = explode('-',$item->tableId);
            $item->tableId = $type[0];
            $sj_type = $type[1];
        }


            if(($item->tblName == 'SJ' || $item->tblName == 'SJ_R') && $item->OutReport == 'O'):
                switch($item->vType) { 
                    case "Xo" : $tktNum = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='". $item->vNumber."'"); break;
                    case "Tkt" : $tktNum = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='". $item->vNumber."'"); break;
                    case "Htl" : $tktNum = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='". $item->vNumber."'"); break;
                }
             if($tktNum=='') $tktNum = $item->vNumber;        
            $sj_cr_dr = $this->common_model->get_field_name('CashAllocation','MIN(vBalAmt)'," WHERE nSalesCloseId = " . $item->tableId . " AND vAllTktNumber = '" . $tktNum . "' AND vTable = '" . $item->vTable . "' AND nDrAccount = " . $item->vACode . " GROUP BY nSalesCloseId, vAllTktNumber, vTable, nDrAccount ");
            if(!empty($sj_cr_dr) && $sj_cr_dr == 0) {
                    continue;
            }
            endif; 


            if(!in_array($item->vACode,$chkAccount) || !in_array($item->vAcSubCode,$chkAccountSub)):
				if( $i !=1 ):
                    if(!($totalfcDebit_per_group == 0 && $totalfcCredit_per_group ==0 && $totalfcBalance_per_group == 0 && $totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):
			?>
					<tr>
						<td colspan="10" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
						<td><?php echo $totalfcDebit_per_group ;?></td>
						<td><?php echo $totalfcCredit_per_group ;?></td>
						<td><?php echo $totalfcBalance_per_group ;?></td>
						<td><?php echo $totalDebit_per_group ;?></td>
						<td><?php echo $totalCredit_per_group ;?></td>
						<td><?php echo $totalBalance_per_group ;?></td>
					</tr>
				<?php
                endif;
					$totalfcDebit_per_group 	= 0;
					$totalfcCredit_per_group 	= 0;
					$totalfcBalance_per_group	= 0;
					$totalDebit_per_group 	= 0;
					$totalCredit_per_group 	= 0;
					$totalBalance_per_group	= 0;
					$balance = 0;
					$fcBalance = 0;
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
                    if($item->OutReport == 'O'){ $openBalName = "fcdc_outstanding"; } else { $openBalName = "fcdc"; }
                    $open_list_value =  $this->sub_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable, $openBalName );
                    $balance = $balance + $open_list_value['debit'] - $open_list_value['credit'];
                    $fcBalance +=  $open_list_value['vFCAmount'] - $open_list_value['vFCAmount_credit'];

                    if( $open_list_value['debit'] ==0 && $open_list_value['credit'] ==0 && $item->debit==0 && $item->credit ==0 ) continue;
 $recoed_present +=1;
						if($drAccountName != '' || !empty($drAccountName)) { 
						?>
						<tr>
							<td colspan="18"><strong><?php echo $subAccName . "  -  " . $drAccountName ; ?></strong></td>
						</tr>
						<?php
					}
					else { 
					?>
						<tr>
							<td colspan="18"><strong><?php  echo $subAccName; ?></strong></td>
						</tr>
						<?php 
					}												

                    ?>
                        <tr>
                        <td colspan="10" align="center"><strong><?php echo OPENINGBALANCE;?></strong></td>
                        <td><?php echo $open_list_value['vFCAmount'];?></td>
                        <td><?php echo $open_list_value['vFCAmount_credit'];?></td>
                        <td><?php echo $fcBalance;?></td>
                        <td><?php echo $open_list_value['debit'];?></td>
                        <td><?php echo $open_list_value['credit'];?></td>
                        <td><?php echo $balance;?></td>
                        </tr>

            <?php                
		    $totalfcDebit_per_group   += $open_list_value['vFCAmount'];
            $totalfcCredit_per_group  += $open_list_value['vFCAmount_credit'];
            $totalDebit_per_group   = $totalDebit_per_group+$open_list_value['debit'];
            $totalCredit_per_group  = $totalCredit_per_group+$open_list_value['credit'];

            $totalfcDebit  += $open_list_value['vFCAmount'];
            $totalfcCredit += $open_list_value['vFCAmount_credit'];
            $totalfcBalance = $totalfcDebit - $totalfcCredit;
            $totalDebit   = $totalDebit+$open_list_value['debit'];
            $totalCredit  = $totalCredit+$open_list_value['credit'];
            $totalBalance = $totalDebit - $totalCredit;
            endif;


			$exchangerate = $item->vExchange;
			$currency 	 = $item->nCurrency;
			$fc 		 = $item->vFCAmount;
			$passenger 	 = '&nbsp';
			$conf		 = '&nbsp';
			$issued		 = '&nbsp';					
			$ticketNum   = '&nbsp';
            $description = $item->description;
			$passenger 	 = $item->vRefName;
			
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
    	                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
					}
					else {
	                    $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$item->tableId."'");
    	                $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$item->tableId.'';
    	                //$path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$salesDetId.'/'.$item->tableId.'';
					}
                    $getTktValue  = $this->sub_report_model->getTktNumberValues($item->vType,$item->vNumber);
                    $description = $getTktValue['desc'];
					$conf =	$getTktValue['conf'];	 
					$issued =	$getTktValue['issued'];	 					
					$passenger = $getTktValue['pass'];
					$currency =	$getTktValue['currency'];	 					
					$fc = $getTktValue['fc'];					
					$exchangerate = $getTktValue['exchangerate'];					
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
                    $fcDebit = $fc;
                    $fcCredit = 0;
                    break;

                case "Cr":
                    $debit = 0;
                    $credit = $item->debit;
                    $fcDebit = 0;
                    $fcCredit = $fc;
                    break;

                default:
                    if('O' == $item->OutReport && ( $item->tblName == 'SJ' ||  $item->tblName == 'SJ_R' ) && !empty($sj_cr_dr)){
                        if($item->tblName == 'SJ'){
                            $debit = ($item->debit==0)?0:$sj_cr_dr;
                            $credit = ($item->credit==0)?0:$sj_cr_dr;
                            $fcDebit = ($item->debit==0)?0:$fc;
                            $fcCredit = ($item->credit==0)?0:$fc;
                        }
                        if($item->tblName == 'SJ_R'){
                            $sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" ); 
                            if($sales_refund == 'S'){ $debit = $sj_cr_dr; $credit = 0; $fcDebit = $fc;  $fcCredit = 0;}
                            else { $debit = 0; $credit = $sj_cr_dr;  $fcDebit = 0; $fcCredit = $fc; }
                        }
                    } 
                    else {
                        if($item->tblName == 'SJ_R') {
                            $sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" ); 
                            if($sales_refund == 'S'){
                                //$debit = $this->common_model->get_field_name('SalesClosingDetails','vCollected'," WHERE vTktNumber ='".$item->vNumber."'");	
                                $debit = $this->common_model->get_field_name('SalesClosingDetails','vAllocation'," WHERE vTktNumber ='".$item->vNumber."' AND vType = '" . $item->vType . "'");	
                                $credit = 0;
                                $fcDebit = $fc;  $fcCredit = 0;
                            }
                            else {
                                $credit = $this->common_model->get_field_name('RefundClosingDetails','vPaidAmt'," WHERE nTicketNumber ='".$item->vNumber."' AND vType = '" . $item->vType . "'");
                                $debit = 0;
                                $fcDebit = 0; $fcCredit = $fc;
                            }

                        }else {
                            $debit = ($item->debit==' ')?0:$item->debit;
                            $credit = ($item->credit==' ')?0:$item->credit;
                            $fcDebit = ($item->debit==0)?0:$fc;
                            $fcCredit = ($item->credit==0)?0:$fc;
                        }
                    }
                    break;
            }
			$balance = $balance + $debit - $credit;
			$fcBalance += $fcDebit - $fcCredit;
            if($debit != 0 || $credit != 0){
            ?>

        <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo date("d-M-y", strtotime($item->date));?></td>
            <td><a href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
            <td><?php echo $ticketNum; ?></td>
            <td><?php echo $conf; ?></td>
            <td><?php echo $this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='". $issued ."'"); ?></td>
            <td style="word-break:break-all;"><?php echo $passenger; ?></td>
            <td><?php echo $description;?></td>
            <td><?php echo $this->common_model->get_field_name('Currency', 'vCurrcode', " WHERE nId='".$currency."'");?></td>
            <td><?php echo $exchangerate;?></td>						
            <td><?php echo $fcDebit;?></td>
            <td><?php echo $fcCredit;?></td>
            <td><?php echo $fcBalance;?></td>
            <td><?php echo $debit;?></td>
            <td><?php echo $credit;?></td>
            <td><?php echo $balance;?></td>
        </tr>
            <?php $i = $i + 1;
            }
            $chkAccount[] 			= $item->vACode;
            $chkAccountSub[] 		= $item->vAcSubCode;
		    $totalfcDebit_per_group   = $totalfcDebit_per_group+$fcDebit;
            $totalfcCredit_per_group  = $totalfcCredit_per_group+$fcCredit;
            $totalfcBalance_per_group = $fcBalance;
		    $totalDebit_per_group   = $totalDebit_per_group+$debit;
            $totalCredit_per_group  = $totalCredit_per_group+$credit;
            $totalBalance_per_group = $balance;
			
            $totalfcDebit   = $totalfcDebit+$fcDebit;
            $totalfcCredit  = $totalfcCredit+$fcCredit;
            $totalfcBalance = $totalfcDebit - $totalfcCredit;
            $totalDebit   = $totalDebit+$debit;
            $totalCredit  = $totalCredit+$credit;
            $totalBalance = $totalDebit - $totalCredit;
        endforeach;
        if($recoed_present !=1): if($i!=1): 

                    if(!($totalfcDebit_per_group == 0 && $totalfcCredit_per_group ==0 && $totalfcBalance_per_group == 0 && $totalDebit_per_group == 0 && $totalCredit_per_group == 0 && $totalBalance_per_group ==0)):?>
    <tr>
        <td colspan="10" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
        <td><?php echo $totalfcDebit_per_group ;?></td>
        <td><?php echo $totalfcCredit_per_group ;?></td>
        <td><?php echo $totalfcBalance_per_group ;?></td>
        <td><?php echo $totalDebit_per_group ;?></td>
        <td><?php echo $totalCredit_per_group ;?></td>
        <td><?php echo $totalBalance_per_group ;?></td>
    </tr>
    <?php endif; endif; ?>
<tr><td>&nbsp;</td></tr>
    <tr>
        <td colspan="10" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><?php echo $totalfcDebit;?></td>
        <td><?php echo $totalfcCredit;?></td>
        <td><?php echo $totalfcBalance;?></td>
        <td><?php echo $totalDebit;?></td>
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
        <td colspan="14" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
   </tbody>
</table>


