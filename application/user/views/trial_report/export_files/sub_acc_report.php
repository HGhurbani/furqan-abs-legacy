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



//$list_value =  $this->trial_report_model->getAllSubAccountDetails($search_accGrp,$search_Acc,$list_value);



    $j = 0;

    $flag = false;

    $chkGroupId = $chkHeadId = $chkAccountId = $chkSubAccountId = array();

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

            <td colspan="8" style="border:0" height="25px" align="left"><strong><?php echo TBWITHSUBACCREPORT;?></strong></td>

        </tr>

<tr>

    <th colspan="2" style="color:#3BB3C2;" align="right"><strong><?php echo FROMDATE;?></strong></th>

    <th colspan="2" align="left"><?php echo $fromDate;?></th>

    <th style="color:#3BB3C2;" align="right"><strong><?php echo TODATE;?></strong></th>

    <th align="left"><?php echo $toDate;?></th>

    <th align="left" colspan="7">&nbsp;</th>

</tr>

<style media="print">



  #bg{   -webkit-print-color-adjust:exact;

 background-color:#CCC !important; }

 

</style>

<style>

table { font-size: 9px; }

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

<tr id="bg">

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

    <?php

    foreach($list_value as $key => $item):



        if(!in_array($item->HeadId,$chkHeadId)):

            $chkHeadId[]             = $item->HeadId; ?>

            <?php if($j != 0):?>
           <tr id="bg">

            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo number_format($totalOpenDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalOpenCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceCreditLoop,3);?></strong></td>

        </tr>



            <tr>

                <td colspan="8" align="right">&nbsp;</td>

            </tr>

                <?php $flag = true; endif;?>



        <tr id="bg">

            <td align="left"><strong><?php echo $this->common_model->get_field_name('AccountHead','vHCode'," WHERE nAId = '" . $item->HeadId . "' ");?></strong></td>

            <td align="left"><strong><?php echo $this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId = '" . $item->HeadId . "' ");?></strong></td>

            <td colspan="6" align="center">&nbsp;</td>

        </tr>

            <?php

            $j = 1;

        endif;

        ?>



    <tbody>

        <?php

//        if(($item->subacc == 0 && !in_array($item->AccountId,$chkAccountId))|| ($item->subacc != 0 && !in_array($item->subacc,$chkSubAccountId))):



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



                default: $accSubName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='". $item->AccountId . "'");

                         $accSubCode =  $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='". $item->AccountId . "'");

            }



        $getOpenValues=  $this->trial_report_model->getOpeningBalForAcc($item->AccountId, $item->subacc,$item->vTable);



        $getBalance     = ($getOpenValues['debit']-$getOpenValues['credit'])+($item->debit-$item->credit);



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

            <td><?php echo number_format((float)$getOpenValues['debit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValues['credit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$item->debit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$item->credit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td>

        </tr>

            <?php

            $totalOpenDebit     += $getOpenValues['debit'];

            $totalOpenCredit    += $getOpenValues['credit'];

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



            $chkSubAccountId[]       = $item->subacc . "-" . $item->vTable;

            $chkAccountId[] = $item->AccountId;



            $totalOpenDebitLoop     += $getOpenValues['debit'];

            $totalOpenCreditLoop    += $getOpenValues['credit'];

            $totalDebitLoop         += $item->debit;

            $totalCreditLoop        += $item->credit;

            $totalBalanceDebitLoop  += $balanceDebit;

            $totalBalanceCreditLoop += $balanceCredit;

//        endif;



        if(($key+1) == count($list_value)): ?>

       <tr id="bg">

            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo number_format($totalOpenDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalOpenCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceCreditLoop,3);?></strong></td>

        </tr>



            <?php endif;

    endforeach;?>



<!--<tr>

    <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

    <td><strong><?php echo $totalOpenDebit;?></strong></td>

    <td><strong><?php echo $totalOpenCredit;?></strong></td>

    <td><strong><?php echo $totalDebit;?></strong></td>

    <td><strong><?php echo $totalCredit;?></strong></td>

    <td><strong><?php echo $totalBalanceDebit;?></strong></td>

    <td><strong><?php echo $totalBalanceCredit;?></strong></td>

</tr>-->


<tr> <td>&nbsp;  </td> </tr>

    <?php

            $account_list = $this->trial_report_model->subAccountList($search_acc,$search_subAcc,$chkAccountId,$chkSubAccountId);

$j = 0;



    $chkHeadId = array();

    $chkAccountId = array();

            foreach($account_list as $key => $item): 



        if(!in_array($item['HeadId'],$chkHeadId)):

            $chkHeadId[]             = $item['HeadId']; ?>

            <?php if($j != 0):?>

<!--            <tr>

                <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

                <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>

                <td><strong><?php echo $totalOpenCreditLoop;?></strong></td>

                <td><strong><?php echo $totalDebitLoop;?></strong></td>

                <td><strong><?php echo $totalCreditLoop;?></strong></td>

                <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>

                <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>

            </tr>

-->

            <tr>

                <td colspan="8" align="right">&nbsp;</td>

            </tr>

                <?php $flag = true; endif;?>



        <tr>

            <td align="left"><h6><?php echo $item['HeadCode'];?></h6></td> 

            <td align="left"><h6><?php echo $item['HeadName'];?></h6></td>

            <td colspan="6" align="center">&nbsp;</td>

        </tr>

            <?php

            $j = 1;

        endif;

        ?>



    <tbody>

        <?php

//        if(!in_array($item['AccountId'],$chkAccountId)):



?>

        <tr>

<?php

        $getBalance     = ($item['debit']-$item['credit']);



            if($getBalance > 0):

                $balanceDebit = $getBalance;

                $balanceCredit = '';

            else:

                $balanceDebit = '';

                $balanceCredit = substr($getBalance,1,strlen($getBalance));

            endif;



            if($item['subAccountId'] == 0){

                $item['subAccountCode'] =  $item['accountCode'];

                $item['subAccountName'] =  $item['accountName'];

            }

?>

            <td><?php echo $item['subAccountCode'];?></td>

            <td><?php echo $item['subAccountName'];?></td>

            <td><?php echo number_format((float)$item['debit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$item['credit'], 3, ".", "");?></td>

            <td>&nbsp;</td>

            <td>&nbsp;</td>

            <td><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td></td>

            <td><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td></td>

        </tr>

            <?php

            $totalOpenDebit     += $item['debit'];

            $totalOpenCredit    += $item['credit'];

//            $totalDebit         += $item['debit'];

//            $totalCredit        += $item['credit'];

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



            $chkSubAccountId[]       = $item['subacc'];

            $chkAccountId[] = $item['accountId'];



            $totalOpenDebitLoop     += $item['debit'];

            $totalOpenCreditLoop    += $item['credit'];

//            $totalDebitLoop         += $item['debit'];

//            $totalCreditLoop        += $item['credit'];

            $totalBalanceDebitLoop  += $balanceDebit;

            $totalBalanceCreditLoop += $balanceCredit;

//        endif;



        if(($key+1) == count($list_value)): ?>

<!--        <tr>

            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>

            <td><strong><?php echo $totalOpenCreditLoop;?></strong></td>

            <td><strong><?php echo $totalDebitLoop;?></strong></td>

            <td><strong><?php echo $totalCreditLoop;?></strong></td>

            <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>

            <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>

        </tr>

-->

            <?php endif;

    endforeach;?>



<tr id="bg">

    <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

    <td><strong><?php echo number_format((float)$totalOpenDebit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalOpenCredit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalDebit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalCredit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalBalanceDebit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalBalanceCredit, 3, ".", "");?></strong></td>

</tr>





<?php



    if(empty($list_value) && empty($account_list)){



    ?>

<tr>

    <td style="border:0" colspan="8" align="center"><?php echo SORRYNORECORD;?></td>

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

    

    <td style="border:0" colspan="7">

        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

    </td>

</tr>

</tbody>

</table>

