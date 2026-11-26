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
            window.location.replace('<?php echo base_url().'index.php/cash_bank_report/showlist/'.$this->uri->segment(3);?>');
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
        <th colspan="12">
           

            <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
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
    <tr>
        <td height="25px" align="center"><?php echo SRNO;?></td>
        <td align="center"><?php echo LOCATIONNAME;?></td>
        <td align="center"><?php echo CUSTDATE;?></td>
        <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo ACCODE;?></td>
        <td align="center"><?php echo ACCCODENAME;?></td>
        <td align="center"><?php echo SUBCODE;?></td>
        <td align="center"><?php echo SUBNAME;?></td>
        <td align="center"><?php echo REFNUMBER;?></td>
        <td align="center"><?php echo REFDATE;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):

            switch($item->type)
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

                default:
                    $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");
                    $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");
                break;
            }

            $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");
            $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");

            $showCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->subacc."'");
            $showName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->subacc."'");

            ?>
            <tr>
                <td height="25px" align="center"><?php echo $key+1;?></td>
                <td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                <td align="center"><?php echo date("d-M-y", strtotime($item->date));?></td>
                <td align="center"><?php echo $item->document;?></td>
                <td align="center"><?php echo $SubAccountCode;?></td>
                <td><?php echo $SubAccountName;?></td>
                <td align="center"><?php echo $showCode;?></td>
                <td><?php echo $showName;?></td>
                <td align="center"><?php echo $item->vRefNumber;?></td>
                <?php if($item->dRefDate =='0000-00-00'){?>
                <td>&nbsp;</td>
                <?php }else{?>
                 <td align="center"><?php echo date("d-M-y", strtotime($item->dRefDate));?></td>
                <?php }?>
                <td align="right"><?php echo $item->debit;?></td>
                <td align="right"><?php echo $item->credit;?></td>
            </tr>
            <?php

            $getDetails = $this->cash_bank_report_model->getDetailValues($item->type,$item->tableId);
            if(!empty($getDetails)):
                foreach($getDetails as $key2=>$row2):
                    switch($row2->vTable)
                    {
                        case "C":
                            $SubAccountName2 = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$row2->vACode."'");
                            $SubAccountCode2 = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$row2->vACode."'");
                            break;

                        case "V":
                            $SubAccountName2 = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$row2->vACode."'");
                            $SubAccountCode2 = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$row2->vACode."'");
                            break;

                        case "A":
                            $SubAccountName2 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row2->vACode."'");
                            $SubAccountCode2 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row2->vACode."'");
                            break;

                        case "O":
                            $SubAccountName2 = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$row2->vACode."'");
                            $SubAccountCode2 = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$row2->vACode."'");
                            break;

                        case "AG":
                            $SubAccountName2 = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$row2->vACode."'");
                            $SubAccountCode2 = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$row2->vACode."'");
                            break;

                        default:
                            $SubAccountCode2 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row2->vACode."'");
                            $SubAccountName2 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row2->vACode."'");
                            break;
                    }

                    $showCode2 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$row2->vAcSubCode."'");
                    $showName2 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$row2->vAcSubCode."'");
                    ?>
                    <tr>
                        <td colspan="4"></td>
                        <td height="25px" align="center"><?php echo $SubAccountCode2;?></td>
                        <td><?php echo $SubAccountName2;?></td>
                        <td align="center"><?php echo $showCode2;?></td>
                        <td><?php echo $showName2;?></td>
                        <td colspan="2"></td>
                        <td align="right"><?php echo $row2->debitVal;?></td>
                        <td align="right"><?php echo $row2->creditVal;?></td>
                    </tr>
                <?php
                    $totalDebit   += $row2->debitVal;
                    $totalCredit  += $row2->creditVal;
                endforeach;
            endif;
            $totalDebit   += $item->debit;
            $totalCredit  += $item->credit;
        endforeach;?>
    <tr>
        <td height="25px" colspan="10" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
        <td align="right"><?php echo $totalDebit;?></td>
        <td align="right"><?php echo $totalCredit;?></td>
    </tr>
    <tr>
        <td height="25px" colspan="10" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="right"><?php echo $totalDebit;?></td>
        <td align="right"><?php echo $totalCredit;?></td>
    </tr>
        <?php }
    else{
        ?>
    <tr>
        <td style="border:0" colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
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
       
        <td style="border:0" colspan="12">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
