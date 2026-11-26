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
            window.location.replace('<?php echo base_url().'index.php/alloc_report/showlist/'.$this->uri->segment(3);?>');
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

<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/payable_allocation_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/payable_allocation_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/payable_allocation_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
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
        <th colspan="14">


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
	<tr>

        <td colspan="6" style="border:0" height="25px" align="left">&nbsp;<strong><?php echo "ALLOCATION Report";?></strong></td>

    

        <td colspan="6" style="border:0" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>

    </tr>

    <tr>

        <th align="left"><?php echo FROMDATE;?></th>

        <th align="left"><?php echo $this->txtFromDate;?></th>

        <th align="center"><?php echo TODATE;?></th>

        <th align="left"><?php echo $this->txtToDate;?></th>

        <th align="left" colspan="16">&nbsp;</th>

    </tr>
	<?php 
	//echo $ddlAccount."---".$ddlSubAccount."---".$ddltype."<br>";		
	switch($ddltype){

					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $ddlAccount  ."'"); break;

					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $ddlAccount  ."'"); break;

					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $ddlAccount  ."'"); break;

					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $ddlAccount  ."'"); break;

					default: $ddlSubAccount = $ddlAccount; $drAccountName = ""; break;

				}//end switch

				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$ddlSubAccount."'");


                    

						if($drAccountName != '' || !empty($drAccountName)) { 

						?>

						<tr>

							<td height="25px" colspan="9"><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></td>

						</tr>

						<?php

					}

					else { 

					?>

						<tr>

							<td colspan="9"><?php echo $subAccName; ?></strong></td>

						</tr>

						<?php 

					}													

					



?>
   <!-- <tr>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left" colspan="10" rowspan="2">&nbsp;</th>
    </tr>

    <tr>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
    </tr>-->
<style type="text/css">
body{
  -webkit-print-color-adjust:exact;
}
  table { font-size: 9px; }
</style>
<style media="print">
  #bg{ background-color:#CCC !important; }
 
</style>
<style>
td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>
    <tr>
        <td height="25px" colspan="5" align="center"><?php echo PAIDDETAILS;?></td>
        <td colspan="6" align="center"><?php echo ALLOCATEDDETAILS;?></td>
    </tr>

    <tr id="bg">
        <td height="25px" align="center"><?php echo CUSTDATE;?></td>
       
        <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo DESCRIPTION;?></td>
        <td align="center"><?php echo DEBITAMOUNT;?></td>
        <td align="center"><?php echo ALLOCATEDAMT;?></td>
		 <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo TICKETNAME;?></td>
        <td align="center"><?php echo CREDITAMOUNT;?></td>
        <td align="center"><?php echo ALLOCATEDAMT;?></td>
        <td align="center"><?php echo BALANCEAMT;?></td>
       
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):

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

                case "CV":
                    $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$item->tableId;
                    break;

                case "DV":
                    $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$item->tableId;
                    break;

                case "ACM":
                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->tableId;
                    break;

                case "ADM":
                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->tableId;
                    break;
            }			
			if($ddlAccount==''){				
			$acc=$ddlSubAccount;			
			}else{				
			$acc=$ddlAccount;			
			}
	
            $getAlloc = $this->common_model->getAllocatedDetails($item->type,$item->idVal,$ddlAccount,$ddltype);
			if($item->allocated!=0){
            ?>
            <tr>
                <td height="25px" align="center"><?php echo date("d-M-y", strtotime($item->dDate));?></td>
                
                <td align="center"><a style="text-decoration: none;" href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
                <td width="20%"><?php echo $item->remarks;?></td>
                <td align="center"><?php $item->credit=$item->creditAmt; echo $item->credit;?></td>
                <td align="center"><?php echo $item->allocated;?></td>
                <td colspan="8">&nbsp;</td>
            </tr>
            <?php } //var_dump($getAlloc);			
			if(!empty($getAlloc)){
            foreach($getAlloc as $key1=>$allocItem): 

			?>
            <tr>
                <td colspan="5">&nbsp;</td>
                <td align="center"><?php 
				if($allocItem->salesCloseType =='OP'){ echo "OPENING";}
				if($allocItem->salesCloseType =='SC' || $allocItem->salesCloseType =='RCR'){ echo $allocItem->vInvoiceNumber; }else{  echo $allocItem->vTktNumber;}   //$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$allocItem->nSalesCloseId."'");?></td>
                <td><?php
				if($allocItem->salesCloseType !='OP'){
				$tktId   =''; $tktType ='';
                    $tktId   = $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE nId = '".$allocItem->detIdVal."'");
                    $tktType = $this->common_model->get_field_name('SalesClosingDetails','vType'," WHERE nId = '".$allocItem->detIdVal."'");
           $tktNumber='';
		   if($allocItem->vTktNumber!=''){        
				   switch($allocItem->tktType)
                    {
                        case "Tkt" :
                            $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$allocItem->vTktNumber."'");
                            break;

                        case "Xo" :
                            $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$allocItem->vTktNumber."'");
                            break;

                        case "Htl" :
                            $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$allocItem->vTktNumber."'");
                            break;
                    }//end switch
			}else{
				switch($tktType)
                    {
                        case "Tkt" :
                            $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$tktId."'");
                            break;

                        case "Xo" :
                            $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$tktId."'");
                            break;

                        case "Htl" :
                            $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$tktId."'");
                            break;
                    }//end switch
				
			}
				echo $tktNumber; }$tktNumber='';?>
                </td>
                <td height="25px" align="center"><?php echo $allocItem->vDebitAmt;?></td>
                <td align="center"><?php echo $allocItem->vAllAmt;?></td>
                <td align="center"><?php echo $allocItem->vBalAmt;?></td>
                
            </tr>
                <?php
                $totalDebit = $totalDebit+$allocItem->vDebitAmt;
                $totalAlloc = $totalAlloc+$allocItem->vAllAmt;
                $totalBal   = $totalBal+$allocItem->vBalAmt;
            endforeach;?>
        <tr id="bg">
            <td colspan="6"><strong>&nbsp;</strong></td>
            <td height="25px" colspan="" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
            <td align="center"><strong><?php echo number_format((float)$totalDebit, 3, ".", "");?></strong></td>
                        <td align="center"><strong><?php echo number_format((float)$totalAlloc, 3, ".", "");?></strong></td>
                        <td align="center"><strong><?php echo number_format((float)$totalBal, 3, ".", "");?></strong></td>
            
        </tr>
            <?php }			
            $i = $i + 1;
            $credited += $item->credit;
            $allocated += $item->allocated;

            $GrandTotalDebit += $totalDebit;
            $GrandTotalAlloc += $totalAlloc;
            $GrandTotalBal   += $totalBal;			$totalDebit = 0;                $totalAlloc = 0;                $totalBal   = 0;
            ?>
            <?php endforeach;?>
    <tr>
    <tr id="bg">
        <td height="25px" colspan="3" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$credited, 3, ".", "");?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$allocated, 3, ".", "");?></strong></td>
        <td colspan="2">&nbsp;</td>
        <td align="center"><strong><?php echo number_format((float)$GrandTotalDebit, 3, ".", "");?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$GrandTotalAlloc, 3, ".", "");?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$GrandTotalBal, 3, ".", "");?></strong></td>
        
    </tr>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="14" style="border:0" align="center"><?php echo SORRYNORECORD;?></td>
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
        <td style="border:0">&nbsp;</td>
        <td style="border:0" colspan="16">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>