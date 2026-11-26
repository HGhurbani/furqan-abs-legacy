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
    $(document).ready(function () {
        $('#btnCancel').click(function () {
            window.location.replace('<?php echo base_url().'index.php/proflossbug_report/showlist/'.$this->uri->segment(3);?>');
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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/account_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/account_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/account_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">
    <thead>
    <?php
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
    <tr>
        <th colspan="10">
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
            <th colspan="2">&nbsp;</th>
            <th colspan="4"><?php echo CURRENTMONTH;?></th>
            <th colspan="4"><?php echo YEARTODATE;?></th>
        </tr>
        <tr>
            <th><?php echo ACCODE;?></th>
            <th><?php echo ACCCODENAME;?></th>
            <th><?php echo AMOUNT;?></th>
            <th><?php echo BUDGET;?></th>
            <th><?php echo LASTYEAR;?></th>
            <th><?php echo VARPER;?></th>
            <th><?php echo AMOUNT;?></th>
            <th><?php echo BUDGET;?></th>
            <th><?php echo LASTYEAR;?></th>
            <th><?php echo VARPER;?></th>
        </tr>
    </thead>
    <tbody>

    <?php
        foreach($list_value as $key => $item):

            $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
            if($mainId=='3'):
                $mainCode = '3';
                $mainName = 'Income';
            elseif($mainId=='4'):
                $mainCode = '4';
                $mainName = 'Expense';
            endif;

            if(!in_array($mainId,$chkGroupId)):?>
            <tr>
                <td align="left"><strong><?php echo $mainName;?></strong></td>
                <td align="left" colspan="8">&nbsp;</td>
            </tr>
            <?php  $j = 1;endif;?>
              <?php
            if(!in_array($item->HeadId,$chkHeadId)):?>
            <tr>
                <td align="left"><strong><?php echo $item->HeadName;?></strong></td>
                <td colspan="3" align="center">&nbsp;</td>
            </tr>
            <?php endif;?>
             <?php
            if(!in_array($item->AccountId,$chkAccountId)):
				$getValues          = $this->proflossbug_report_model->getAccValues($item->AccountId);
                $getOpenValues      = $this->proflossbug_report_model->getOpenAccValues($item->AccountId);
			?>
            <tr>
                <td align="left"><?php echo $item->AccountCode;?></td>
                <td align="left"><?php echo $item->AccountName;?></td>
                <td align="left"><?php echo $getOpenValues['openDebit'];?></td>
                <td align="left"><?php echo $getValues['debit'];?></td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left"><?php echo ($getOpenValues['openDebit']+$getValues['debit']);?></td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            <?php 

                if($flag == true):
                    $totalPreviousLoop  = '';
                    $totalCurrentLoop   = '';
                    $flag               = false;
                endif;

                $chkGroupId[]            = $mainId;
                $chkHeadId[]             = $item->HeadId;
                $chkAccountId[]          = $item->AccountId;

                $totalPrevious          += $getOpenValues['openDebit'];
                $totalCurrent           += $getValues['debit'];
                $totalPreviousLoop      += $getOpenValues['openDebit'];
                $totalCurrentLoop       += $getValues['debit'];
            endif;
        endforeach;
    }?>
    <?php
    if(!empty($blist_value)){
        $i = 1;
		echo ' <tr>
                <td align="left"><strong>Budget</strong></td>
                <td align="left" colspan="8">&nbsp;</td>
            </tr>';
        foreach($blist_value as $bkey => $bitem):
            ?>
            <tr>
                <td align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$bitem->account."'");?></td>
                <td align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$bitem->account."'");?></td>
                <td align="left"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$bitem->location."'");?></td>
                <td align="left"><?php echo $bitem->nAmount;?></td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
            <?php $i = $i + 1;
            $total += $bitem->nAmount;
            ?>
            <?php endforeach;?>
        <?php }?>
        
        <?php  if(empty($list_value) && empty($blist_value)):?>
         	<tr>
        		<td colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
    		</tr>
       <?php endif;?>

    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="13">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>