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

<div class="content-box-export">
    <div class="inner">
        <a href="<?php echo base_url().'index.php/export_xl/sub_account_trial/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_word/sub_account_trial/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_pdf/sub_account_trial/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">

    <?php

//    $list_value =  $this->trial_report_model->getAllDetails($search_accGrp,$search_Acc,$list_value);


    if(!empty($list_value)){
        $j = 0;
        $flag = false;
        $chkGroupId = $chkHeadId = $chkAccountId = $chkSubAccount = array();
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
            if(!in_array($item->HeadId,$chkHeadId)):
                if($j != 0):?>
                    <tr>
                        <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
                        <td><strong><?php echo $totalOpenBalDebitLoop;?></strong></td>
                        <td><strong><?php echo $totalOpenBalCreditLoop;?></strong></td>
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
                    <td align="left"><h6><?php echo $item->HeadCode;?></h6></td>
                    <td align="left"><h6><?php echo $item->HeadName;?></h6></td>
                    <td colspan="6" align="center">&nbsp;</td>
                </tr>
                <?php
                $j = 1;
            endif;
            ?>
        <tbody>
        <?php
            if(!in_array($item->AccountId,$chkAccountId)):?>

                <?php
                $getSubValues   = $this->trial_report_model->getAllSubAccValues($item->AccountId);
                $chkGroupId = $chkHeadId = $chkAccounts = array();
                $chkAccounts = $chkAccountId ;


//echo print_r($getSubValues) . " <br><br><br>";
                //$getOpenValues  = $this->trial_report_model->getAllSubOpenValues($item->AccountId);

                if(!empty($getSubValues)):

                    $chkDebit   = $getSubValues['debit'];
                    $chkCredit  = $getSubValues['credit'];
                    $chkSubId 	= $getSubValues['sub'];
                    $chkTable 	= $getSubValues['table'];
                    //$openCredit = $getOpenValues['openCredit'];
                    //$openDebit 	= $getOpenValues['openDebit'];
					$tnumber    = $getSubValues['tnumber'];
					$totalOpenDebit2     = 0;
					$totalOpenCredit2    = 0;
					$totalDebit2         = 0;
					$totalCredit2        = 0;
					$totalBalanceDebit2  = 0;
					$totalBalanceCredit2 = 0;

                    foreach($chkDebit as $key2 => $row):

                        if($chkSubId[$key2] !=0):
                            switch($chkTable[$key2])
                            {
                                case "C":
                                    $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$chkSubId[$key2]."'");
                                    $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$chkSubId[$key2]."'");
                                    break;

                                case "V":
                                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$chkSubId[$key2]."'");
                                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$chkSubId[$key2]."'");
                                    break;

                                case "A":
                                    $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$chkSubId[$key2]."'");
                                    $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$chkSubId[$key2]."'");
                                    break;

                                case "O":
                                    $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$chkSubId[$key2]."'");
                                    $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$chkSubId[$key2]."'");
                                    break;

                                case "AG":
                                    $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$chkSubId[$key2]."'");
                                    $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$chkSubId[$key2]."'");
                                    break;

                                default:
                                    $SubAccountName = $item->AccountName;
                                    $SubAccountCode = $item->AccountCode;
                                    break;
                            }//end switch
                        else:
                            $SubAccountName = $item->AccountName;
                            $SubAccountCode = $item->AccountCode;
                        endif;

						if($SubAccountName == 'BSP Payable'):
							$getCode     = $this->common_model->get_field_name('TicketFareDetails','nAcc',
													" WHERE nTicketId  = '".$tnumber[$key2]."'");
							$SubAccountName = $this->common_model->get_field_name('VendorMaster','vName',
													" WHERE nVId  = '".$getCode."'");
						elseif($SubAccountName == 'Tour Agent & Hotels Payable'):
							$getCode     = $this->common_model->get_field_name('HotelVoucher','nAcCode',
													" WHERE nHVId  = '".$tnumber[$key2]."'");
							$SubAccountName = $this->common_model->get_field_name('VendorMaster','vName',
													" WHERE nVId  = '".$getCode."'");
						elseif($SubAccountName == 'XO Payable'):
							$getCode     = $this->common_model->get_field_name('XoBooking','nPassengerAC',
													" WHERE nXId = '".$tnumber[$key2]."'");
							$SubAccountName = $this->common_model->get_field_name('VendorMaster','vName',
													" WHERE nVId  = '".$getCode."'");
						else:
							$SubAccountName = $SubAccountName;
						endif;

                        $openingbalance =  $this->trial_report_model->getOpeningBalForAcc($item->AccountId, $chkSubId[$key2],$chkTable[$key2]);
                        $getBalance     = ( $openingbalance['debit']- $openingbalance['credit'])+($chkDebit[$key2]-$chkCredit[$key2]);
                        if($getBalance > 0):
                            $balanceDebit = $getBalance;
                            $balanceCredit = '';
                        else:
                            $balanceDebit = '';
                            $balanceCredit = substr($getBalance,1,strlen($getBalance));
                        endif;




						//$totalOpenDebit2     += $openDebit[$key2];
						//$totalOpenCredit2    += $openCredit[$key2];
						$totalDebit2         += $chkDebit[$key2];
						$totalCredit2        += $chkCredit[$key2];
						$totalBalanceDebit2  += $balanceDebit;
						$totalBalanceCredit2 += $balanceCredit;
                        ?>


                        <tr>
                            <td><?php echo ($chkName != $SubAccountName)?$SubAccountCode:$SubAccountCode;?></td>
                            <td><?php echo ($chkName != $SubAccountName)?$SubAccountName:$SubAccountName;?></td>
                            <td><?php echo $openingbalance['debit']; ?></td>
                            <td><?php echo $openingbalance['credit']; ?></td>

<!--                            <td><?php echo ($chkName != $SubAccountName)?$openDebit[$key2]:$totalOpenDebit2;?></td>
                            <td><?php echo ($chkName != $SubAccountName)?$openCredit[$key2]:$totalOpenCredit2;?></td>-->

                            <td><?php echo ($chkName != $SubAccountName)?$chkDebit[$key2]:$totalDebit2;?></td>
                            <td><?php echo ($chkName != $SubAccountName)?$chkCredit[$key2]:$totalCredit2;?></td>
                            <td><?php echo ($chkName != $SubAccountName)?$balanceDebit:$totalBalanceDebit2;?></td>
                            <td><?php echo ($chkName != $SubAccountName)?$balanceCredit:$totalBalanceCredit2;?></td>
                        </tr>
                        <?php
                        $totalOpenBalDebit +=  $openingbalance['debit'];
                        $totalOpenBalCredit += $openingbalance['credit'];
                        //$totalOpenDebit     += $openDebit[$key2];
                        //$totalOpenCredit    += $openCredit[$key2];
                        $totalDebit         += $chkDebit[$key2];
                        $totalCredit        += $chkCredit[$key2];
                        $totalBalanceDebit  += $balanceDebit;
                        $totalBalanceCredit += $balanceCredit;

						$chkName = $SubAccountName;

                        if($flag == true):
                            $totalOpenBalDebitLoop = '';
                            $totalOpenBalCreditLoop = '';

                            //$totalOpenDebitLoop  = '';
                            //$totalOpenCreditLoop = '';
                            $totalDebitLoop      = '';
                            $totalCreditLoop     = '';
                            $totalBalanceDebitLoop = '';
                            $totalBalanceCreditLoop = '';
                            $flag                = false;
                        endif;

                        $chkGroupId[]            = $item->GroupId;
                        $chkHeadId[]             = $item->HeadId;
                        $chkAccountId[]          = $item->AccountId;
                        $chkSubAccount[]         = $SubAccountName;

                        $totalOpenBalDebitLoop   +=  $openingbalance['debit'];
                        $totalOpenBalCreditLoop  += $openingbalance['credit'];

                        //$totalOpenDebitLoop     += $openDebit[$key2];
                        //$totalOpenCreditLoop    += $openCredit[$key2];
                        $totalDebitLoop         += $chkDebit[$key2];
                        $totalCreditLoop        += $chkCredit[$key2];
                        $totalBalanceDebitLoop  += $balanceDebit;
                        $totalBalanceCreditLoop += $balanceCredit;
                    endforeach;
                endif;
            endif;

            if(($key+1) == count($list_value)): ?>
            <tr>
                <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>
<!--                <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>
                <td><strong><?php echo $totalOpenCreditLoop;?></strong></td> -->
                <td><strong><?php echo $totalOpenBalDebitLoop;?></strong></td>
                <td><strong><?php echo $totalOpenBalCreditLoop;?></strong></td>
                <td><strong><?php echo $totalDebitLoop;?></strong></td>
                <td><strong><?php echo $totalCreditLoop;?></strong></td>
                <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>
                <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>
            </tr>
                <?php endif;
        endforeach;?>

    <tr>
        <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><strong><?php echo $totalOpenBalDebit;?></strong></td>
        <td><strong><?php echo $totalOpenBalCredit;?></strong></td>
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

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="7">
        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr>
</tbody>
</table>
