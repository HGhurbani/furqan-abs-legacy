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
            window.location.replace('<?php echo base_url().'index.php/balasheet_report/showlist/'.$this->uri->segment(3);?>');
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
        <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/general_report_summary";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/general_report_summary";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/general_report_summary";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">

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
        <thead>
        <tr>
            <th colspan="9"><h3><?php echo BALANCESHEETSUMMARY;?></h3></th>
        </tr>
        <tr>
            <th colspan="4">
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
            <th style="color:#3BB3C2;" align="right"><?php echo ASON;?></th>
            <th align="left"><?php echo $toDate;?></th>
            <th align="left" colspan="7">&nbsp;</th>
        </tr>
        <tr><td></td></tr>

        </thead>
        <thead>

        <tr>
            <th><?php echo ACCODE;?></th>
            <th><?php echo ACCCODENAME;?></th>
            <th><?php echo LASTYEAR;?></th>
            <th><?php echo CURRENTYEAR;?></th>
        </tr>
        </thead>
        <?php
        foreach($list_value as $key => $item):

            $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");
            if($mainId=='1'):
                $mainCode = '1';
                $mainName = 'Asset';
            elseif($mainId=='2'):
                $mainCode = '2';
                $mainName = 'Liabilities';
            endif;

            if(!in_array($mainId,$chkAccountId)):?>
                <?php if($j != 0):?>
                    <tr>
                        <td colspan="2" align="right"><strong><?php echo 'Asset '.TOTAL;?></strong></td>
                        <td><strong><?php echo $totalPreviousLoop;?></strong></td>
                        <td><strong><?php echo $totalCurrentLoop;?></strong></td>
                    </tr>
                    <tr>
                        <td colspan="4" align="right">&nbsp;</td>
                    </tr>
                    <?php $flag = true; endif;?>

                <tr>
                    <td align="left"><h6><?php echo $mainCode;?></h6></td>
                    <td align="left"><h6><?php echo $mainName;?></h6></td>
                    <td colspan="2" align="center">&nbsp;</td>
                </tr>
                <?php
                $j = 1;
            endif;
            ?>

            <?php
            if(!in_array($item->HeadId,$chkHeadId)):
                $getValues          = $this->balasheet_report_model->getAccHeadValues($item->HeadId);
                $getOpenValues      = $this->balasheet_report_model->getOpenHeadAccValues($item->HeadId);
                $balanceDebit       = ($getOpenValues['openDebit']-$getOpenValues['openCredit'])+($getValues['debit']-$getValues['credit']);
            endif;
            if($mainId==2)
                $balanceDebit = $balanceDebit*-1;
            if($balanceDebit != 0 ) {

                if(!in_array($item->GroupId,$chkGroupId)):?>
                    <tr>
                        <td align="left"><strong><?php echo $item->GroupCode;?></strong></td>
                        <td align="left"><strong><?php echo $item->GroupName;?></strong></td>
                        <td colspan="2" align="center">&nbsp;</td>
                        </tr>
                        <?php endif;
            }
            ?>
            <tbody>
                <?php
            if(!in_array($item->HeadId,$chkHeadId)):?>
                    <?php
                    if($balanceDebit != 0 ) {

                   ?>

               <tr>
                    <td align="left"><?php echo $item->HeadCode;?></td>
                    <td align="left"><?php echo $item->HeadName;?></td>
<!--                    <td><?php //echo $getOpenValues['openDebit'];?></td> -->
                    <td>&nbsp;</td>
                    <td><?php echo $balanceDebit;?></td>
                </tr>
                <?php
                }
                if($flag == true):
                    $totalPreviousLoop  = '';
                    $totalCurrentLoop   = '';
                    $flag               = false;
                endif;

                $chkGroupId[]            = $item->GroupId;
                $chkHeadId[]             = $item->HeadId;
                $chkAccountId[]          = $mainId;

                $totalPrevious          += $getOpenValues['openDebit'];
                $totalCurrent           += $balanceDebit;
                $totalPreviousLoop      += $getOpenValues['openDebit'];
                $totalCurrentLoop       += $balanceDebit;
            endif;

            if(($key+1) == count($list_value)):
                $getPL  = $this->balasheet_report_model->getPLValues();
                ?>
            <tr>
                <td align="left"><strong><?php echo $getPL['acCode'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['acName'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['previous'];?></strong></td>
                <td align="left"><strong><?php echo $getPL['current'];?></strong></td>
            </tr>
            <tr>
                <td colspan="2" align="right"><strong><?php echo $mainName.' '.TOTAL;?></strong></td>
                <td><strong><?php echo $totalPreviousLoop+$getPL['previous'];?></strong></td>
                <td><strong><?php echo $totalCurrentLoop+$getPL['current'];?></strong></td>
            </tr>
                <?php endif;
        endforeach;?>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="4" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="3">
        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr>
</tbody>
</table>
