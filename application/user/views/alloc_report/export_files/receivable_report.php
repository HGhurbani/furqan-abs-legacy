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
    <tr>
        <td height="25px" colspan="6" align="center"><?php echo RECEIVEDDETAILS;?></td>
        <td colspan="8" align="center"><?php echo ALLOCATEDDETAILS;?></td>
    </tr>

    <tr>
        <td height="25px" align="center"><?php echo CUSTDATE;?></td>
        <td align="center"><?php echo LOCATIONNAME;?></td>
        <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo DESCRIPTION;?></td>
        <td align="center"><?php echo CREDITAMOUNT;?></td>
        <td align="center"><?php echo ALLOCATEDAMT;?></td>
        <td align="center"><?php echo SRNO;?></td>
        <td align="center"><?php echo CUSTDATE;?></td>
        <td align="center"><?php echo LOCATIONNAME;?></td>
        <td align="center"><?php echo TICKETNAME;?></td>
        <td align="center"><?php echo DEBITAMOUNT;?></td>
        <td align="center"><?php echo ALLOCATEDAMT;?></td>
        <td align="center"><?php echo BALANCEAMT;?></td>
        <td align="center"><?php echo DOCUMENTNUMBER;?></td>
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
            }//end switch

            $getAlloc = $this->alloc_report_model->getAllocation($item->tableId,$item->tblName);
            ?>
            <tr>
                <td height="25px" align="center"><?php echo date("d-M-y", strtotime($item->date));?></td>
                <td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                <td align="center"><?php echo $item->document;?></td>
                <td><?php echo $item->description;?></td>
                <td align="center"><?php echo $item->credit;?></td>
                <td align="center"><?php echo $item->allocated;?></td>
                <td colspan="8">&nbsp;</td>
            </tr>
            <?php  if(!empty($getAlloc)){
                foreach($getAlloc as $key1=>$allocItem):  ?>
                    <tr>
                        <td colspan="6">&nbsp;</td>
                        <td height="25px" align="center"><?php echo $key1+1;?></td>
                        <td align="center"><?php echo date("d-M-y", strtotime($allocItem->date));?></td>
                        <td align="center"><?php echo $allocItem->vLocation;?></td>
                        <td><?php
                            $tktId   = $this->common_model->get_field_name('SalesClosingDetails','vTktNumber'," WHERE nId = '".$allocItem->nSalesCloseDetId."'");
                            $tktType = $this->common_model->get_field_name('SalesClosingDetails','vType'," WHERE nId = '".$allocItem->nSalesCloseDetId."'");
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
                            echo $tktNumber;?>
                        </td>
                        <td height="25px" align="center"><?php echo $allocItem->vDebitAmt;?></td>
                        <td align="center"><?php echo $allocItem->vAllAmt;?></td>
                        <td align="center"><?php echo $allocItem->vBalAmt;?></td>
                        <td align="center"><?php echo $this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$allocItem->nSalesCloseId."'");?></td>
                    </tr>
                <?php
                    $totalDebit = $totalDebit+$allocItem->vDebitAmt;
                    $totalAlloc = $totalAlloc+$allocItem->vAllAmt;
                    $totalBal   = $totalBal+$allocItem->vBalAmt;
                endforeach;?>
                    <tr>
                        <td colspan="6"><strong>&nbsp;</strong></td>
                        <td height="25px" colspan="4" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
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
            $GrandTotalBal   += $totalBal;
            ?>
        <?php endforeach;?>
    <tr>
    <tr>
        <td height="25px" colspan="4" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$credited, 3, ".", "");?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$allocated, 3, ".", "");?></strong></td>
        <td colspan="4">&nbsp;</td>
        <td align="center"><strong><?php echo number_format((float)$GrandTotalDebit, 3, ".", "");?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$GrandTotalAlloc, 3, ".", "");?></strong></td>
        <td align="center"><strong><?php echo number_format((float)$GrandTotalBal, 3, ".", "");?></strong></td>
       
    </tr>
        <?php }
    else{
        ?>
    <tr>
        <td style="border:0" colspan="14" align="center"><?php echo SORRYNORECORD;?></td>
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