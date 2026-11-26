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
            window.location.replace('<?php echo base_url().'index.php/sub_report/showlist/'.$this->uri->segment(3);?>');
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
        <th colspan="11">
            <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="100" height="100">

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
    <tr>
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
    </tr>
    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo DOCUMENTNUMBER;?></th>
        <th align="left"><?php echo TICKETNAME;?></th>
        <th align="left"><?php echo PASSENGERNAME;?></th>
        <th align="left"><?php echo TRAVELSETUPDETAILS;?></th>
        <th align="left"><?php echo LPO;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo BALANCE;?></th>
    </tr>
<!--    <tr>
        <th colspan="11" align="center"><?php echo OPENINGBALANCE;?></th>
   		</tr>
-->	
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){

        $i = 1;
		$chkAccount = array();
        foreach($list_value as $key=>$item):
		    if(!in_array($item->vACode,$chkAccount)):
				if( $i !=1 ):
			?>
					<tr>
						<td colspan="8" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
						<td><?php echo $totalDebit_per_group ;?></td>
						<td><?php echo $totalCredit_per_group ;?></td>
						<td><?php echo $totalBalance_per_group ;?></td>
					</tr>
				<?php
					$totalDebit_per_group 	= 0;
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
						if($drAccountName != '' || !empty($drAccountName)) { 
						?>
						<tr>
							<td colspan="18"><strong><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></td>
						</tr>
						<?php
					}
					else { 
					?>
						<tr>
							<td colspan="18"><strong><?php echo $subAccName; ?></strong></td>
						</tr>
						<?php 
					}												
					
					?>
                
            <?php endif;


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

                case "SJ":
					$sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" );
					if($sales_refund == 'S'){
	                    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'
 									AND vType = '" .	$item->vType ."' AND vTktNumber = " .$item->vNumber ." "); 						
						$lpo = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nSalesCloseId='".$item->tableId."' 
									AND vType = '" .	$item->vType ."' AND vTktNumber = " .$item->vNumber ." "); 						
    	                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
					}
					else {
	                    $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$item->tableId."'
										AND vType = '" .	$item->vType ."' AND nTicketNumber = " .$item->vNumber ." " ); 												
						$lpo = $this->common_model->get_field_name('RefundClosingDetails','vLPO'," WHERE nRefundCloseId='".$item->tableId."'
										AND vType = '" .	$item->vType ."' AND nTicketNumber = " .$item->vNumber ." " ); 						
    	                $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$salesDetId.'/'.$item->tableId.'';
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
                    break;

                case "Cr":
                    $debit = 0;
                    $credit = $item->debit;
                    break;

                default:
                    $debit = ($item->debit==' ')?0:$item->debit;
                    $credit = ($item->credit==' ')?0:$item->credit;
                    break;
            }
			$balance = $balance + $debit - $credit;

            ?>
        <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo date("d-M-y", strtotime($item->date));?></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'"));?></td>
            <td><a href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
            <td><?php echo $ticketNum; ?></td>
            <td style="word-break:break-all;"><?php echo $passenger; ?></td>
            <td><?php echo $description;?></td>
            <td><?php echo $lpo;?></td>
            <td><?php echo $debit;?></td>
            <td><?php echo $credit;?></td>
            <td><?php echo $balance;?></td>
        </tr>
            <?php $i = $i + 1;
            $chkAccount[] 			= $item->vACode;
		    $totalDebit_per_group   = $totalDebit_per_group+$debit;
            $totalCredit_per_group  = $totalCredit_per_group+$credit;
            $totalBalance_per_group = $balance;
			
            $totalDebit   = $totalDebit+$debit;
            $totalCredit  = $totalCredit+$credit;
            $totalBalance = $totalBalance+$balance;
        endforeach;?>
    <tr>
        <td colspan="8" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
        <td><?php echo $totalDebit_per_group ;?></td>
        <td><?php echo $totalCredit_per_group ;?></td>
        <td><?php echo $totalBalance_per_group ;?></td>
    </tr>
    <tr>
        <td colspan="8" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><?php echo $totalDebit;?></td>
        <td><?php echo $totalCredit;?></td>
        <td><?php echo $totalBalance;?></td>
    </tr>

    <?php }
    else{ ?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="16">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>