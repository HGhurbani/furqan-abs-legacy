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

        <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/sub_account_trial";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

        <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/sub_account_trial";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

        <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/sub_account_trial";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

    </div>

</div> <!--.content-box-export-->

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

            <td colspan="8" style="border:0" height="25px" align="left"><strong><?php echo TBWITHACCGROUPREPORT;?></strong></td>

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

td{font-size: 12px;

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
$ac=0; $a24=0;  $a25=0; $a26=0; $a27=0; $a33=0;$a52=0;$a53=0;$a54=0;$a55=0;$a56=0;$a57=0;
    foreach($list_value as $key => $item):


        if(!in_array($item->HeadId,$chkHeadId)):

            $chkHeadId[]             = $item->HeadId; ?>

            <?php if($j != 0 && $item->HeadId!=1):
			
			?>
           <tr id="bg">

            <td colspan="2" align="right"><strong><?php   echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo number_format($totalOpenDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalOpenCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceCreditLoop,3);?></strong></td>

        </tr>

			<?php ?>

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


if($accSubCode!='' && $item->HeadId!=''){
	if($item->HeadId!=5 && $item->HeadId !=8 && $item->HeadId!=14 ){
            ?>



             <td><?php echo $item->HeadId; echo"--"; echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValues['debit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValues['credit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$item->debit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$item->credit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td>
 </tr>
       
	<?php }else{ 
	 $accSubCode =  $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='". $item->AccountId . "'");
	 $accSubName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='". $item->AccountId . "'");
	 
	 
	 ?>
	  <?php
	 if($item->AccountId==23 && $ac==0){
		 $ac=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit23']-$getOpenValue['credit23'])+($list_value['debit23']-$list_value['credit23']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php  echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit23'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit23'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit23'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit23'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?> 
	  <?php
	 if($item->AccountId==24 && $a24==0){
		 $a24=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['tdebit']-$getOpenValue['ycredit'])+($list_value['tdebit']-$list_value['ycredit']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['tdebit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['ycredit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['tdebit'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['ycredit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?> 
	<?php
	 if($item->AccountId==25 && $a25==0){
		 $a25=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit25']-$getOpenValue['credit25'])+($list_value['debit25']-$list_value['credit25']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit25'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit25'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit25'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit25'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==26 && $a26==0){
		 $a26=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit26']-$getOpenValue['credit26'])+($list_value['debit26']-$list_value['credit26']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit26'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit26'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit26'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit26'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==27 && $a27==0){
		 $a27=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit27']-$getOpenValue['credit27'])+($list_value['debit27']-$list_value['credit27']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit27'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit27'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit27'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit27'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==33 && $a33==0){
		 $a33=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit33']-$getOpenValue['credit33'])+($list_value['debit33']-$list_value['credit33']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit33'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit33'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit33'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit33'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==52 && $a52==0){
		 $a52=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit52']-$getOpenValue['credit52'])+($list_value['debit52']-$list_value['credit52']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit52'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit52'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit52'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit52'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==53 && $a53==0){
		 $a53=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit53']-$getOpenValue['credit53'])+($list_value['debit53']-$list_value['credit53']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit53'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit53'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit53'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit53'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==54 && $a54==0){
		 $a54=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit54']-$getOpenValue['credit54'])+($list_value['debit54']-$list_value['credit54']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit54'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit54'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit54'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit54'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==55 && $a55==0){
		 $a55=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit55']-$getOpenValue['credit55'])+($list_value['debit55']-$list_value['credit55']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit55'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit55'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit55'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit55'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==56 && $a56==0){
		 $a56=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit56']-$getOpenValue['credit56'])+($list_value['debit56']-$list_value['credit56']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit56'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit56'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit56'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit56'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php
	 if($item->AccountId==57 && $a57==0){
		 $a57=1;
		 $getOpenValue=  $this->trial_report_model->getOpeningBalForAcc1($item->AccountId, $item->subacc,$item->vTable);
		$getBalance     = ($getOpenValue['debit57']-$getOpenValue['credit57'])+($list_value['debit57']-$list_value['credit57']);



            if($getBalance > 0):

                $balanceDebit1 = $getBalance;

                $balanceCredit1 = '';

            else:

                $balanceDebit1 = '';

                $balanceCredit1 = substr($getBalance,1,strlen($getBalance));

            endif;
	
	
	?>
		
		<td><?php echo $accSubCode;?></td>

            <td><?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValue['debit57'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValue['credit57'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$list_value['debit57'], 3, ".", ""); ?></td>

            <td><?php echo number_format((float)$list_value['credit57'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceDebit1, 3, ".", "");?></td>

            <td><?php echo number_format((float)$balanceCredit1, 3, ".", "");?></td>
		 </tr>

	<?php 
	} ?>
	<?php }

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

}

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
echo $search_acc." ".$search_subAcc." ".$chkAccountId." ".$chkSubAccountId;
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



            <tr>

                <td colspan="8" align="right">&nbsp;</td>

            </tr>-->

                <?php $flag = true; endif;?>



      <!--  <tr>

            <td align="left"><h6><?php echo $item['HeadCode'];?></h6></td> 

            <td align="left"><h6><?php echo $item['HeadName'];?></h6></td>

            <td colspan="6" align="center">&nbsp;</td>

        </tr>-->

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

            <td style="display:none;"><?php echo $item['subAccountCode'];?></td>

            <td style="display:none;"><?php echo $item['subAccountName'];?></td>

            <td style="display:none;"><?php echo number_format((float)$item['debit'], 3, ".", "");?></td>

            <td style="display:none;"><?php echo number_format((float)$item['credit'], 3, ".", "");?></td>

            <td style="display:none;">&nbsp;</td>

            <td style="display:none;">&nbsp;</td>

            <td style="display:none;"><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td></td>

            <td style="display:none;"><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td></td>

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

