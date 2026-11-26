<table width="100%" border="0" cellspacing="10" cellpadding="10" class="reportForm" id="reportForm">

<?php

//$list_value =  $this->trial_report_model->getAllDetails($search_accGrp,$search_Acc,$list_value);
echo print_r($list_value);
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
    <th colspan="8">
        

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
</thead>
<thead>

<tr>
    <th>&nbsp;</th>
    <th>&nbsp;</th>
    <th colspan="2" align="center"><?php echo OPENING;?></th>
    <th colspan="2" align="center"><?php echo TRANSACTION;?></th>
    <th colspan="2" align="center"><?php echo BALANCE;?></th>
</tr>
<tr>
    <th><?php echo ACCODE;?></th>
    <th><?php echo ACCCODENAME;?></th>
    <th><?php echo DEBIT;?></th>
    <th><?php echo CREDIT;?></th>
    <th><?php echo DEBIT;?></th>
    <th><?php echo CREDIT;?></th>
    <th><?php echo DEBIT;?></th>
    <th><?php echo CREDIT;?></th>
</tr>
</thead>
    <?php
    foreach($list_value as $key => $item):

        if(!in_array($item->HeadId,$chkHeadId)):?>
            <?php if($j != 0):?>
            <tr>
                <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
                <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>
                <td><strong><?php echo $totalOpenCreditLoop;?></strong></td>
                <td><strong><?php echo $totalDebitLoop;?></strong></td>
                <td><strong><?php echo $totalCreditLoop;?></strong></td>
                <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>
                <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>
            </tr>
            <tr>
                <td colspan="8" align="right">&nbsp;</td>
            </tr>
                <?php $flag = true; endif;?>

        <tr>
            <td align="left"><h6><?php echo $this->common_model->get_field_name('AccountHead','vHCode'," WHERE nAId = " . $item->HeadId . " ");?></h6></td>
            <td align="left"><h6><?php echo $this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId = " . $item->HeadId . " ");?></h6></td>
            <td colspan="6" align="center">&nbsp;</td>
        </tr>
            <?php
            $j = 1;
        endif;
        ?>

    <tbody>
        <?php
        if(!in_array($item->subacc,$chkSubAccountId)):

?>
        <tr>
            <?php

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

        $getOpenValues=  $this->ac_report_model->getOpeningBalForAcc($item->AccountId, $item->subacc,$item->vTable);
        $getBalance     = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($item->debit-$item->credit);

            if($getBalance > 0):
                $balanceDebit = $getBalance;
                $balanceCredit = '';
            else:
                $balanceDebit = '';
                $balanceCredit = substr($getBalance,1,strlen($getBalance));
            endif;

            ?>

            <td><?php echo $accSubCode;?></td>
            <td><?php echo $accSubName;?></td>
            <td><?php echo $getOpenValues['openDebit'];?></td>
            <td><?php echo $getOpenValues['openCredit'];?></td>
            <td><?php echo $item->debit;?></td>
            <td><?php echo $item->credit;?></td>
            <td><?php echo $balanceDebit;?></td>
            <td><?php echo $balanceCredit;?></td>
        </tr>
            <?php
            $totalOpenDebit     += $getOpenValues['openDebit'];
            $totalOpenCredit    += $getOpenValues['openCredit'];
            $totalDebit         += $item->debit;
            $totalCredit        += $item->credit;
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

            $chkHeadId[]             = $item->HeadId;
            $chkSubAccountId[]       = $item->subacc;

            $totalOpenDebitLoop     += $getOpenValues['openDebit'];
            $totalOpenCreditLoop    += $getOpenValues['openCredit'];
            $totalDebitLoop         += $item->debit;
            $totalCreditLoop        += $item->credit;
            $totalBalanceDebitLoop  += $balanceDebit;
            $totalBalanceCreditLoop += $balanceCredit;
        endif;

        if(($key+1) == count($list_value)): ?>
        <tr>
            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
            <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>
            <td><strong><?php echo $totalOpenCreditLoop;?></strong></td>
            <td><strong><?php echo $totalDebitLoop;?></strong></td>
            <td><strong><?php echo $totalCreditLoop;?></strong></td>
            <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>
            <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>
        </tr>
            <?php endif;
    endforeach;?>

<tr>
    <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
    <td><strong><?php echo $totalOpenDebit;?></strong></td>
    <td><strong><?php echo $totalOpenCredit;?></strong></td>
    <td><strong><?php echo $totalDebit;?></strong></td>
    <td><strong><?php echo $totalCredit;?></strong></td>
    <td><strong><?php echo $totalBalanceDebit;?></strong></td>
    <td><strong><?php echo $totalBalanceCredit;?></strong></td>
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

</tbody>
</table>
