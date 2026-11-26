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
            window.location.replace('<?php echo base_url().'index.php/trial_report/showlist/'.$this->uri->segment(3);?>');
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

<?php

    $list_value =  $this->trial_report_model->getAllDetails($search_accGrp,$search_Acc,$list_value);
if(!empty($list_value)){
    $j = 0;
    $flag = false;
    $chkGroupId = $chkHeadId = $chkAccountId = array();
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

<thead>
      
<tr>
    <th align="center" colspan="8">

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
            <td colspan="8" style="border:0" height="25px" align="left"><strong><?php echo TBWITHACCHEADREPORT;?></strong></td>
        </tr>
<tr>
    <th colspan="2" style="color:#3BB3C2;" align="right"><strong><?php echo FROMDATE;?></strong></th>
    <th colspan="2" align="left"><?php echo $fromDate;?></th>
    <th style="color:#3BB3C2;" align="right"><strong><?php echo TODATE;?></strong></th>
    <th align="left"><?php echo $toDate;?></th>
    <th align="left" colspan="7">&nbsp;</th>
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

</thead>
<thead>

<tr>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <td colspan="2" align="center"><strong><?php echo OPENING;?></strong></td>
    <td colspan="2" align="center"><strong><?php echo TRANSACTION;?></strong></td>
    <td colspan="2" align="center"><strong><?php echo BALANCE;?></strong></td>
</tr>
<tr>
    <td align="center"><strong><?php echo ACCODE;?></strong></td>
    <td align="center"><strong><?php echo ACCCODENAME;?></strong></td>
    <td align="center"><strong><?php echo DEBIT;?></strong></td>
    <td align="center"><strong><?php echo CREDIT;?></strong></td>
    <td align="center"><strong><?php echo DEBIT;?></strong></td>
    <td align="center"><strong><?php echo CREDIT;?></strong></td>
    <td align="center"><strong><?php echo DEBIT;?></strong></td>
    <td align="center"><strong><?php echo CREDIT;?></strong></td>
</tr>
</thead>
    <tbody>
    <?php
    foreach($list_value as $key => $item):
        ?>

        <?php
        if(!in_array($item->HeadId,$chkHeadId)):?>
            <?php if($j != 0):?>
            <tr>
                <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
                <td><strong><?php echo number_format((float)$totalOpenDebitLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalOpenCreditLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalDebitLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalCreditLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalBalanceDebitLoop, 3, ".", "");?></strong></td>
                        <td><strong><?php echo number_format((float)$totalBalanceCreditLoop, 3, ".", "");?></strong></td>
            </tr>
            <tr>
                <td colspan="8" align="right">&nbsp;</td>
            </tr>
                <?php $flag = true; endif;?>
        <tr>
            <td align="left"><strong><?php echo $item->HeadCode;?></strong></td>
            <td align="left"><strong><?php echo $item->HeadName;?></strong></td>
            <td colspan="6" align="center">&nbsp;</td>
        </tr>
            <?php
            $j = 1;
        endif;
        ?>
        <?php
        if(!in_array($item->AccountId,$chkAccountId)):?>
        <tr>
            <?php
            $getValues      = $this->trial_report_model->getAccValues($item->AccountId);
            //$getOpenValues  = $this->trial_report_model->getOpenAccValues($item->AccountId);
                            if($item->openDebit != 0 || $item->openCredit != 0){
                                $openingbalance['debit'] = $item->openDebit;
                                $openingbalance['credit'] = $item->openCredit;
                            }
else{
    $openingbalance =  $this->trial_report_model->getOpeningBalForAcc($item->AccountId,'','');
}

//            $openingbalance =  $this->trial_report_model->getOpeningBalForAcc($item->AccountId,'','');

            $getBalance     = ($openingbalance['debit']-$openingbalance['credit'])+($getValues['debit']-$getValues['credit']);

            if($getBalance > 0):
                $balanceDebit = $getBalance;
                $balanceCredit = '';
            else:
                $balanceDebit = '';
                $balanceCredit = substr($getBalance,1,strlen($getBalance));
            endif;

            ?>


            <td><?php echo $item->AccountCode;?></td>
            <td><?php echo $item->AccountName;?></td>
<!--            <td><?php echo $getOpenValues['openDebit'];?></td>
            <td><?php echo $getOpenValues['openCredit'];?></td> -->
            <td><?php echo number_format((float)$openingbalance['debit'], 3, ".", "");?></td>
                            <td><?php echo number_format((float)$openingbalance['credit'], 3, ".", "");?></td>
                            <td><?php echo number_format((float)$getValues['debit'], 3, ".", "");?></td>
                            <td><?php echo number_format((float)$getValues['credit'], 3, ".", "");?></td>
                            <td><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td>
                            <td><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td>
        </tr>
            <?php
            //$totalOpenDebit     += $getOpenValues['openDebit'];
            //$totalOpenCredit    += $getOpenValues['openCredit'];
            $totalOpenDebit     +=  $openingbalance['debit']; 
            $totalOpenCredit    +=  $openingbalance['credit'];
            $totalDebit         += $getValues['debit'];
            $totalCredit        += $getValues['credit'];
            $totalBalanceDebit  += $balanceDebit;
            $totalBalanceCredit += $balanceCredit;

            if($flag == true):
                $totalOpenDebitLoop  = '';
                $totalOpenCreditLoop = '';
                $totalDebitLoop      = '';
                $totalCreditLoop     = '';
                $totalBalanceDebitLoop = '';
                $totalBalanceCreditLoop = '';
                $flag                = false;
            endif;

            $chkGroupId[]            = $item->GroupId;
            $chkHeadId[]             = $item->HeadId;
            $chkAccountId[]          = $item->AccountId;

            //$totalOpenDebitLoop     += $getOpenValues['openDebit'];
            //$totalOpenCreditLoop    += $getOpenValues['openCredit'];
            $totalOpenDebitLoop     += $openingbalance['debit'];
            $totalOpenCreditLoop    += $openingbalance['credit'];
            $totalDebitLoop         += $getValues['debit'];
            $totalCreditLoop        += $getValues['credit'];
            $totalBalanceDebitLoop  += $balanceDebit;
            $totalBalanceCreditLoop += $balanceCredit;
        endif;

        if(($key+1) == count($list_value)): ?>
        <tr>
            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
            <td><strong><?php echo number_format((float)$totalOpenDebitLoop, 3, ".", "");?></strong></td>
                            <td><strong><?php echo number_format((float)$totalOpenCreditLoop, 3, ".", "");?></strong></td>
                            <td><strong><?php echo number_format((float)$totalDebitLoop, 3, ".", "");?></strong></td>
                            <td><strong><?php echo number_format((float)$totalCreditLoop, 3, ".", "");?></strong></td>
                            <td><strong><?php echo number_format((float)$totalBalanceDebitLoop, 3, ".", "");?></strong></td>
                            <td><strong><?php echo number_format((float)$totalBalanceCreditLoop, 3, ".", "");?></strong></td>
        </tr>
            <?php endif;
    endforeach;?>

<tr>
    <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
    <td><strong><?php echo number_format((float)$totalOpenDebit, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalOpenCredit, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalDebit, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalCredit, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalBalanceDebit, 3, ".", "");?></strong></td>
                <td><strong><?php echo number_format((float)$totalBalanceCredit, 3, ".", "");?></strong></td>
</tr>

    <?php }
else{
    ?>
<tr>
    <td colspan="8" align="center"><?php echo SORRYNORECORD;?></td>
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
    
    <td colspan="7">
        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr>
</tbody>
</table>
