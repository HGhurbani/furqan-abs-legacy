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

        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset();
        $('#maincontent').scroll(function() {
            console.log(contentBoxExportPos);
            if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
                contentBoxExport.addClass('fixed')
            }
            else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
                contentBoxExport.removeClass('fixed')
            }
        });

    });
</script>

<div class="content-box-export">
    <div class="inner">
        <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/receivable_detail_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/receivable_detail_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/receivable_detail_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
    <th colspan="15">
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
    <th align="left" colspan="11">&nbsp;</th>
</tr>
<?php
        switch($this->session->userdata('tableType'))
        {
            case "C":
                $SubHeadName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->session->userdata('ddlSubAccount')."'");
                $SubHeadCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$this->session->userdata('ddlSubAccount')."'");
                break;

            case "V":
                $SubHeadName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->session->userdata('ddlSubAccount')."'");
                $SubHeadCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$this->session->userdata('ddlSubAccount')."'");
                break;

            case "A":
                $SubHeadName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlSubAccount')."'");
                $SubHeadCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlSubAccount')."'");
                break;

            case "O":
                $SubHeadName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$this->session->userdata('ddlSubAccount')."'");
                $SubHeadCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$this->session->userdata('ddlSubAccount')."'");
                break;

            case "AG":
                $SubHeadName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$this->session->userdata('ddlSubAccount')."'");
                $SubHeadCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$this->session->userdata('ddlSubAccount')."'");
                break;
        }//end switch
?>
<tr>
    <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'");?></th>
    <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'");?></th>
    <th align="left"><?php echo $SubHeadCode;?></th>
    <th align="left"><?php echo $SubHeadName;?></th>
    <th colspan="4">&nbsp;</th>
    <th colspan="5"><?php echo AGEINGSLAB;?></th>
    <th rowspan="2"><?php echo UNAllOCATE;?></th>
    <th rowspan="2"><?php echo BALANCE;?></th>
</tr>
<tr>
    <th align="left"><?php echo SRNO;?></th>
    <th align="left"><?php echo CUSTDATE;?></th>
    <th align="left"><?php echo DOCUMENTNUMBER;?></th>
    <th align="left"><?php echo LOCATIONNAME;?></th>
    <th align="left"><?php echo TICKETNO;?></th>
    <th align="left"><?php echo PASSENGERNAME;?></th>
    <th><?php echo TRAVELSETUPDETAILS;?></th>
    <th><?php echo LPO;?></th>
    <th><?php echo '30' . DAYS;?></th>
    <th><?php echo '31-60';?></th>
    <th><?php echo '61-90';?></th>
    <th><?php echo '91-120';?></th>
    <th><?php echo ABOVE . '120';?></th>
</tr>
<tr>
    <td colspan="15" align="center"><strong><?php echo OPENINGBALANCE;?></strong></td>
</tr>
</thead>

<tbody>

<?php
if(!empty($list_value)){
    $i = 1;
    $chkSubCodeArray = $chkTypeArray = array();
    $srNo = 1;
    $dateTotal1 = '';
    $dateTotal2 = '';
    $dateTotal3 = '';
    $dateTotal4 = '';
    $dateTotal5 = '';
    $debitTotal = '';
    $balanceTotal = '';
    foreach($list_value as $key=>$item):

        switch($item->tblName)
        {
            case "CR":
                $salesType   = 'Finance';
                $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "CP":
                $salesType   = 'Finance';
                $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "BR":
                $salesType   = 'Finance';
                $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "BP":
                $salesType   = 'Finance';
                $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "JV":
                $salesType   = 'Finance';
                $path = base_url().'index.php/jv/editJv/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "SJ":
                $salesType   = 'Sales';
                $path        = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$item->detId.'/'.$item->tableId.'/cl';
                $getTktValue = $this->ageing_report_model->getTktNumberValues($item->detId);
                $description = $getTktValue['desc'];
                $ticketNum   = $getTktValue['tktVal'];
                $passenger   = $getTktValue['passVal'];
                $lpoVal      = $this->common_model->get_field_name('SalesClosingDetails','vDLPO'," WHERE nId='".$item->detId."'");
                break;

            case "CV":
                $salesType   = 'Finance';
                $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "DV":
                $salesType   = 'Finance';
                $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "ACM":
                $salesType   = 'Finance';
                $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "ADM":
                $salesType   = 'Finance';
                $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;
        }//end switch

        if($item->account != '')
        {
            switch($item->vTable)
            {
                case "C":
                    $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->account."'");
                    $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->account."'");
                    break;

                case "V":
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->account."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->account."'");
                    break;

                case "A":
                    $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");
                    $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");
                    break;

                case "O":
                    $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->account."'");
                    $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->account."'");
                    break;

                case "AG":
                    $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->account."'");
                    $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->account."'");
                    break;
            }
        }
        else
        {
            if($item->subacc == 32 && trim($item->account) == ''):
                $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$item->vNumber."'");
                $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
            elseif($item->subacc == 34 && trim($item->account) == ''):
                $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$item->vNumber."'");
                $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
            elseif($item->subacc == 35 && trim($item->account) == ''):
                $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$item->vNumber."'");
                $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
            else:
                $SubAccountName = '&nbsp';
                $SubAccountCode = '&nbsp';
            endif;
        }
        $datePerd = $this->ageing_report_model->getAgeingDateDiff($item->date);
            ?>
            <tr>
                <td><?php echo $srNo;?></td>
                <td><?php echo date("d-M-y", strtotime($item->date));?></td>
                <td><?php echo $item->document;?></td>
                <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td>
                <td><?php echo $ticketNum;?></td>
                <td><?php echo $passenger;?></td>
                <td><?php echo $description;?></td>
                <td><?php echo $lpoVal;?></td>
                <?php if($salesType=='Sales'):
                    $date1 = ($datePerd <= 30)?$item->allocAmt:'';
                    $date2 = ($datePerd >= 31 && $datePerd <= 60)?$item->allocAmt:'';
                    $date3 = ($datePerd >= 61 && $datePerd <= 90)?$item->allocAmt:'';
                    $date4 = ($datePerd >= 91 && $datePerd <= 120)?$item->allocAmt:'';
                    $date5 = ($datePerd > 120)?$item->allocAmt:'';
                    $balance = $date1+$date2+$date3+$date4+$date5;
                    $dateTotal1 += $date1;
                    $dateTotal2 += $date2;
                    $dateTotal3 += $date3;
                    $dateTotal4 += $date4;
                    $dateTotal5 += $date5;?>
                    <td><?php echo $date1;?></td>
                    <td><?php echo $date2;?></td>
                    <td><?php echo $date3;?></td>
                    <td><?php echo $date4;?></td>
                    <td><?php echo $date5;?></td>
                    <td>&nbsp;</td>
                <?php else:
                    $wdebit = $item->allocAmt;
                    $balance = -1*$wdebit;
                    $debitTotal += $wdebit;?>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td>&nbsp;</td>
                    <td><?php echo $wdebit;?></td>
                <?php endif;?>
                <td><?php echo $balance;?></td>
            </tr>
            <?php
            $balanceTotal += $balance;
            $srNo++;
    endforeach;?>
<tr>
    <td colspan="8" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
    <td><?php echo $dateTotal1;?></td>
    <td><?php echo $dateTotal2;?></td>
    <td><?php echo $dateTotal3;?></td>
    <td><?php echo $dateTotal4;?></td>
    <td><?php echo $dateTotal5;?></td>
    <td><?php echo $debitTotal;?></td>
    <td><?php echo $balanceTotal;?></td>
</tr>

<tr>
    <td colspan="8" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
    <td><?php echo $dateTotal1;?></td>
    <td><?php echo $dateTotal2;?></td>
    <td><?php echo $dateTotal3;?></td>
    <td><?php echo $dateTotal4;?></td>
    <td><?php echo $dateTotal5;?></td>
    <td><?php echo $debitTotal;?></td>
    <td><?php echo $balanceTotal;?></td>
</tr>

    <?php }
else{
    ?>
<tr>
    <td colspan="15" align="center"><?php echo SORRYNORECORD;?></td>
</tr>
    <?php
}//end if
?>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="14">
        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr>
</tbody>
</table>