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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/receivable_ageing_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/receivable_ageing_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/receivable_ageing_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <th colspan="15"><h3><?php echo AGEINGSUMMARYREPORT;?></h3></th>
    </tr>

    <tr>
        <th colspan="9">
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
    <tr><td></td></tr>
    <tr>
        <th style="color:#3BB3C2;" align="right"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $fromDate;?></th>
        <th style="color:#3BB3C2;" align="right"><?php echo TODATE;?></th>
        <th align="left"><?php echo $toDate;?></th>
        <th align="left" colspan="12">&nbsp;</th>
    </tr>
    <tr><td></td></tr>

    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left"><?php echo OPENINGBALANCE;?></th>
        <th align="left"><?php echo $slab1 . " " .DAYS;?></th>
        <th align="left"><?php echo ($slab1+1) . "-" . $slab2 ." " . DAYS;?></th>
        <th align="left"><?php echo ($slab2+1) . "-" . $slab3 . " " . DAYS;?></th>
        <th align="left"><?php echo ($slab3+1) . "-" . $slab4 . " " . DAYS;?></th>
        <th align="left"><?php echo ABOVE . " " . $slab4 . " " .DAYS;?></th>
        <th align="left"><?php echo UNALLOCATED;?></th>
        <th align="left"><?php echo BALANCE;?></th>
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value_new)){
        $recoed_present = 1;
        $i = 1;
		$balance = 0;
		$chkAccount = array();
        foreach($list_value_new as $key=>$item):
				switch($item->vTable){
					case "C": $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); 
		  						$AccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '". $item->vACode  ."'"); 
								break;
					case "V": $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'");
								$AccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '". $item->vACode  ."'"); 
								break;
                    case "AG": $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode  ."'");
                                $AccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId = '". $item->vACode  ."'");
                                break;
					default: $item->vAcSubCode = $item->vACode ; $AccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");
				$subAccCode	 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->vAcSubCode."'");

            $open_list_value =  $this->ageing_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable);
            $openbal = $open_list_value['alloc_debit'] - $open_list_value['alloc_credit'];
//echo $open_list_value['alloc_debit'] . " -------   " . $open_list_value['alloc_credit'];

                    if( $open_list_value['alloc_debit'] ==0 && $open_list_value['alloc_credit'] ==0 && $item->alloc_debit ==0 && $item->alloc_credit == 0) continue;
                    $recoed_present +=1;
		?>
			
        <tr>
            <td><?php echo $key+1;?></td>
			<td><?php echo $subAccCode ; ?> </td>
			<td><strong><?php echo $subAccName ; ?> </strong></td>
			<td><?php echo $AccountCode ; ?> </td>			
			<td><strong><?php echo $AccountName ; ?> </strong></td>
            <td><?php echo $openbal;?></td>
            <td><?php echo $item->s1;?></td>
            <td><?php echo $item->s2;?></td>
            <td><?php echo $item->s3;?></td>
            <td><?php echo $item->s4;?></td>
            <td><?php echo $item->s5; ?></td>
            <td><?php echo $item->alloc_credit;?></td>
			
            <td><?php 
						$balance =  $item->s1 + $item->s2 + $item->s3 + $item->s4 + $item->s5 - $item->alloc_credit + $openbal;			
						echo $balance;?></td>
        </tr>
            <?php $i = $i + 1;
            $totalopenbal  += $openbal;
            $totalCredit  = $totalCredit+$item->alloc_credit;
		    $totalS1   += $item->s1;
		    $totalS2   += $item->s2;
		    $totalS3   += $item->s3;
		    $totalS4   += $item->s4;
		    $totalS5   += $item->s5;
            $totalBalance += $balance;
        endforeach;
        if($recoed_present !=1): ?>
    <tr>
        <td colspan="5" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td><?php echo $totalopenbal;?></td>
        <td><?php echo $totalS1;?></td>
        <td><?php echo $totalS2;?></td>
        <td><?php echo $totalS3;?></td>
        <td><?php echo $totalS4;?></td>
        <td><?php echo $totalS5;?></td>
        <td><?php echo $totalCredit;?></td>
        <td><?php echo $totalBalance;?></td>
    </tr>

    <?php else:?>
    <tr>
        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
    <?php endif;
    } 
    else{ ?>
    <tr>
        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="13">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
