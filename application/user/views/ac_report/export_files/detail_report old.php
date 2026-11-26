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
            window.location.replace('<?php echo base_url().'index.php/ac_report/showlist/'.$this->uri->segment(3);?>');
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
            <th align="center" colspan="9">
                <!-- <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="100" height="100"> -->

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
		
<!-- <tr><td style="border:0"></td></tr> -->
		<tr>
            <td colspan="9" style="border:0" height="25px" align="left"><strong><?php echo DETAILREPORT;?></strong></td>
        </tr>
            <!-- <tr><td style="border:0"></td></tr> -->
            <tr>
                <th align="left"><?php echo FROMDATE;?></th>
                <th align="left"><?php echo $fromDate;?></th>
                <th align="center"><?php echo TODATE;?></th>
                <th align="left"><?php echo $toDate;?></th>
                <!-- <th align="left" colspan="9">&nbsp;</th> -->
            </tr>
			<?php
    $chkAccount_acc = array();
    $chkAccount_subacc = array();
//echo print_r($list_value);
    if(!empty($list_value)){
        $tablArray = array();?>

    <tbody>

        <?php
    $i = 1;
        foreach($list_value as $key=>$item):
            ?>

<?php
        $display_acc = 0;
        if(!in_array($item->account,$chkAccount_acc)){
            $chkAccount_acc[] = $item->account;
            $chkAccount_subacc[] = $item->subacc;
            $display_acc = 1;
        }
        else{
            if(!in_array($item->subacc,$chkAccount_subacc)){
                $chkAccount_subacc[] = $item->subacc;
                $display_acc = 1;
            }
            else {
                $display_acc = 0;

            }
        }

        if($display_acc == 1): 

            $display_acc = 0; 
            if($i !=1):
            ?>
			        <tr id="bg">
                    <td colspan="5" align="right" height="25"><strong><?php echo TOTALAMOUNT;?></strong></td>
                    <td align="right"><?php echo number_format((float)$totalDebit_perAccc, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$totalCredit_perAccc, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$openDebit, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$openCredit, 3, ".", "");?></td>
                </tr>
        <?php
                $totalopenDebit += $openDebit;
                $totalopenCredit  += $openCredit;

                $totalDebit_perAccc = 0;
                $totalCredit_perAccc = 0;
                $openVal = 0;
            endif;

            $accSubName = '';
            $accSubCode = '';
            switch($item->vTable)
            {
                case "C":
                    $accSubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->subacc."'");
                    break;

                case "V":
                    $accSubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->subacc."'");
                    break;

                case "AG":
                    $accSubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->subacc."'");
                    break;

                case "O":
                    $accSubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->subacc."'");
                    $accSubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->subacc."'");
                    break;
            }

            ?>
                <!-- <tr> <td height="25px" colspan="10"> &nbsp; </td> </tr> -->
            <tr>
                <!-- <th colspan="2" align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," where nId='".$item->account."'");?></th> -->
                <td align="left" colspan="2"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," where nId='".$item->account."'");?></td>
                <!-- <td height="25px" colspan="6"><?php echo $accSubCode; ?></td> -->
                <td colspan="2" style="border-right:0" height=25px" align="left"><?php echo $accSubName;?> </td>
                <td align="center" colspan="1">&nbsp;</td>
                <td align="center" colspan="2"><?php echo transaction;?></td>
                <td align="center" colspan="2"><?php echo balance;?></td>
            </tr>
            <!-- <tr><td></td></tr> -->
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
td{
    border: 1px solid black;
border-collapse: collapse;
}
</style>
		<tr id="bg">
            <td height="25px" align="center"><?php echo SRNO;?></td>
            <td align="center"><?php echo CUSTDATE;?></td>
            <!-- <td align="left"><?php echo LOCATIONNAME;?></td> -->
            <td align="center"><?php echo DOCUMENTNUMBER;?></td>
            <td align="center"><?php echo TICKETNAME;?></td>
            <td align="center"><?php echo DESCRIPTION;?></td>
            <td align="center"><?php echo DEBIT;?></td>
            <td align="center"><?php echo CREDIT;?></td>
            <td align="center"><?php echo DEBIT;?></td>
            <td align="center"><?php echo CREDIT;?></td>
        </tr>

    
            <tr>
                <td colspan="5" height="25" align="right"><strong><?php echo YEARCLOSING;?></strong></td>
                <td align="right"><?php echo number_format((float)$year_closing['debit'], 3, ".", "");?></td>
                <td align="right"><?php echo number_format((float)$year_closing['credit'], 3, ".", "");?></td>
                <td align="right"><?php echo number_format((float)($year_closing['debit'] - $year_closing['credit']), 3, ".", "");?></td>
                <td>&nbsp;</td>
            </tr>
    <?php
            $this->session->unset_userdata('newDebit');
            //$openVal = $this->ac_report_model->getCalDebit($open_list_value['debit'],$open_list_value['credit']);

            $open_list_value = array();
            $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($item->account, $item->subacc,$item->vTable);

            $totalDebit_perAccc +=  $open_list_value['debit'];
            $totalCredit_perAccc += $open_list_value['credit'];
            $totalDebit +=  $open_list_value['debit'];
            $totalCredit += $open_list_value['credit'];

            $openVal = round($openVal,2) + (round($open_list_value['debit'],2) - round($open_list_value['credit'],2));
        
                if($openVal >= 0):
                    $openCredit = 0;
                    $openDebit = $openVal;
                endif;

                if($openVal <= 0):
                    $openCredit = substr($openVal,1,strlen($openVal));
                    $openDebit = 0;
                endif;

    ?>
            <tr>
                <td colspan="5" height="25" align="right"><strong><?php echo OPENINGBALANCE;?></strong></td>
                <td align="right"><?php echo number_format((float)$open_list_value['debit'], 3, ".", "");?></td>
                <td align="right"><?php echo number_format((float)$open_list_value['credit'], 3, ".", "");?></td>

                <td align="right"><?php echo number_format((float)$openDebit, 3, ".", "");?></td>
                <td align="right"><?php echo number_format((float)$openCredit, 3, ".", "");?></td>
            </tr>
            </thead>
        <?php endif; ?>


                <?php
            switch($item->mode)
            {
                case "Dr":
                    $debit = $item->debit;
                    $credit = '';
                    break;

                case "Cr":
                    $debit = '';
                    $credit = $item->debit;
                    break;

                default:
                    $debit = ($item->debit==' ')?'':$item->debit;
                    $credit = ($item->credit==' ')?'':$item->credit;
                    break;
            }

 
        if(!(($credit == '' || $credit == 0) && ($debit =='' || $debit ==0))){
            switch($item->tblName)
            {
                case "CR":
                    $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "CP":
                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "BR":
                    $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "BP":
                    $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "JV":
                    $path = base_url().'index.php/jv/editJv/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "SJ":
/*                    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'");
                    $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
                    $getTktValue  = $this->ac_report_model->getTktNumberValues($item->detId,$item->tableId);
                    $description = $getTktValue['desc'];
                    $ticketNum = $getTktValue['tktVal'];
                    break;
*/
                    $sj_type = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" );
                    if($sj_type == 'S'){
                        $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'");
                        $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
                    }
                    else {
                        $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$item->tableId."'"); 
                        $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$item->tableId.'';
                    }
                    if($item->subacc == 0){
                      $drAcc = $item->account;
                      $vTable = "A";
                    }
                    else{
                      $drAcc = $item->subacc;
                      $vTable = $item->vTable;
                    }
                    $getTktValue  = $this->ac_report_model->getTktNumberValues($item->vType,$item->vNumber,$item->detId,$item->tableId,$drAcc,$vTable);
                    $description = $getTktValue['desc'];
                    $ticketNum = $getTktValue['tktVal'];
//echo $item->vType.'-'.$item->vNumber.'-'.$item->detId.'-'.$item->tableId.'-'.$drAcc.'-'.$vTable."<br>";
                    break;

                case "CV":
                    $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "DV":
                    $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "AC":
                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;

                case "AD":
                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->tableId;
                    $description = $item->description;
                    $ticketNum   = '&nbsp';
                    break;
            }//end switch

           //$openVal = $this->ac_report_model->getCalDebit($debit,$credit);
            $openVal = round($openVal,2) + (round($debit,2) - round($credit,2));

            if($openVal >= 0):
                $openCredit = 0;
                $openDebit = $openVal;
            endif;

            if($openVal <= 0):
                $openCredit = substr($openVal,1,strlen($openVal));
                $openDebit = 0;
            endif;

            ?>
                <tr>
                    <td height="25" align="center"><?php echo $key+1;?></td>
                    <td align="center"><?php echo date("d-M-y", strtotime($item->date));?></td>
                    <!-- <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td> -->
                    <td align="center"><?php echo $item->document;?></td>
                    <td align="center"><?php echo $ticketNum;?></td>
                    <td><?php echo $description;?></td>
                    <td align="right"><?php echo $debit;?></td>
                    <td align="right"><?php echo $credit;?></td>
                    <td align="right"><?php echo number_format((float)$openDebit, 3, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$openCredit, 3, ".", "");?></td>
                </tr>
                <?php 
        }//end if credit debit 0
                $i = $i + 1;
                $totalDebit += $debit;
                $totalCredit += $credit;
                //$totalopenDebit = $openDebit;
                //$totalopenCredit = $openCredit;

                $totalDebit_perAccc += $debit;
                $totalCredit_perAccc += $credit;
                $totalopenDebit_perAccc = $openDebit;
                $totalopenCredit_perAccc = $openCredit;

                if(!in_array($item->document,$tablArray))
                {
                    switch($item->mainMode)
                    {
                        case "Dr":
                            $cashDebit += $item->mainAmount;
                            break;

                        case "Cr":
                            $cashCredit += $item->mainAmount;
                            break;
                    }//end swtich
                    ?>
                <?php
                }//end if
            $tablArray[]     = $item->document; ?>

<?php        endforeach;?>


            <?php 
            $totalopenDebit += $openDebit;
            $totalopenCredit  += $openCredit;
            $totBal = $totalopenDebit - $totalopenCredit;
            $totalopenDebit = ($totBal > 0) ? $totBal : 0;
            $totalopenCredit = ($totBal < 0) ? $totBal * -1 : 0;
        ?>
        <tr id="bg">
            <td colspan="5" align="right" height="25"><strong><?php echo TOTALAMOUNT;?></strong></td>
            <td align="right"><?php echo number_format((float)$totalDebit_perAccc, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalCredit_perAccc, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$openDebit, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$openCredit, 3, ".", "");?></td>
        </tr>
        <!-- <tr> <td colspan='10'> &nbsp; </td> </tr> -->

        <?php }
 
//displaying remaining accounts
            $account_list = $this->ac_report_model->accountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc);
        foreach($account_list as $key => $row){
            $open_list_value = array();
            $openDebit = $openCredit = $openVal = 0;
            $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($row['accountId'],$row['subAccountId'],$row['vTable']);
            if( $open_list_value['debit'] != 0 ||  $open_list_value['credit'] != 0){

                $totalDebit_perAccc +=  $open_list_value['debit'];
                $totalCredit_perAccc += $open_list_value['credit'];
                $totalDebit +=  $open_list_value['debit'];
                $totalCredit += $open_list_value['credit'];


                $openVal = round($openVal,2) + (round($open_list_value['debit'],2) - round($open_list_value['credit'],2));

                if($openVal >= 0):
                    $openCredit = 0;
                $openDebit = $openVal;
                endif;

                if($openVal <= 0):
                    $openCredit = substr($openVal,1,strlen($openVal));
                $openDebit = 0;
                endif;
                $totalopenDebit += $openDebit;
                $totalopenCredit  += $openCredit;
                $totBal = $totalopenDebit - $totalopenCredit;
                $totalopenDebit = ($totBal > 0) ? $totBal : 0;
                $totalopenCredit = ($totBal < 0) ? $totBal * -1 : 0;
 
?>
            <thead>
            <tr>
                <th align="left"><?php echo $row['accountCode']; ?></th>
                <th align="left"><?php echo $row['accountName']; ?></th>
                <th align="left"><?php echo $row['subAccountCode'];?></th>
                <th align="left"><?php echo $row['subAccountName'];?></th>
                <th align="left" colspan="2">&nbsp;</th>
                <th align="center" colspan="2"><?php echo transaction;?></th>
                <th align="center" colspan="2"><?php echo balance;?></th>
            </tr>
             <tr>
                <td colspan="6" align="center"><strong><?php echo YEARCLOSING;?></strong></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>
            <tr>
                <td colspan="6" align="center"><strong><?php echo OPENINGBALANCE;?></strong></td>
                <td><?php echo number_format((float)$open_list_value['debit'], 3, ".", "");?></td>
                <td><?php echo number_format((float)$open_list_value['credit'], 3, ".", "");?></td>

                <td><?php echo number_format((float)$openDebit, 3, ".", "");?></td>
                <td><?php echo number_format((float)$openCredit, 3, ".", "");?></td>
            </tr>
            </thead>
                <td colspan='10'>&nbsp;</td>
 


<?php

            }//endif
        }// end foreach 
    if(!($totalDebit ==0 && $totalCredit ==0 && $totalopenDebit ==0 && $totalopenCredit == 0)){

            ?>

        <tr id="bg">
            <td colspan="5" align="right" height="25"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td align="right"><?php echo number_format((float)$totalDebit, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalCredit, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalopenDebit, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalopenCredit, 3, ".", "");?></td>
        </tr>
<?php }else { ?>
   <tr>
        <td style="border:0" colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
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
        <td style="border:0" colspan="9">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
