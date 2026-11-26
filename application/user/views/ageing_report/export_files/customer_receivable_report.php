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

<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/customer_receivable_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/customer_receivable_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/customer_receivable_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <th colspan="11">
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
        <th><?php echo ACCODE;?></th>
        <th><?php echo ACCCODENAME;?></th>
        <th><?php echo SUBCODE;?></th>
        <th><?php echo SUBNAME;?></th>
        <th colspan="7">&nbsp;</th>
    </tr>
    <tr>
        <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlAccount')."'");?></th>
        <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlAccount')."'");?></th>
        <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$this->session->userdata('ddlSubAccount')."'");?></th>
        <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->session->userdata('ddlSubAccount')."'");?></th>
        <th colspan="7">&nbsp;</th>
    </tr>
    <tr>
        <th rowspan="2"><?php echo SRNO;?></th>
        <th rowspan="2"><?php echo LOCATIONNAME;?></th>
        <th rowspan="2"><?php echo CUSTDATE;?></th>
        <th rowspan="2"><?php echo DOCUMENTNUMBER;?></th>
        <th colspan="5"><?php echo AGEINGSLAB;?></th>
        <th rowspan="2"><?php echo UNAllOCATE;?></th>
        <th rowspan="2"><?php echo BALANCE;?></th>
    </tr>
    <tr>
        <th><?php echo '30' . DAYS;?></th>
        <th><?php echo '31-60';?></th>
        <th><?php echo '61-90';?></th>
        <th><?php echo '91-120';?></th>
        <th><?php echo ABOVE . '120';?></th>
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):
			$datePerd = $this->ageing_report_model->getAgeingDateDiff($item->dDate);
      ?>
        <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->nLocation."'");?></td>
            <td><?php echo date("d-M-y", strtotime($item->dDate));?></td>
            <td><?php echo $item->vJVNo;?></td>
            <td><?php echo $date1 = ($datePerd <= 30)?$item->nDebit:'';
						$dateTotal1 += $date1;
			?></td>
            <td><?php echo $date2 = ($datePerd >= 31 && $datePerd <= 60)?$item->nDebit:'';
						$dateTotal2 += $date2;
			?></td>
            <td><?php echo $date3 = ($datePerd >= 61 && $datePerd <= 90)?$item->nDebit:'';
						$dateTotal3 += $date3;
			?></td>
            <td><?php echo $date4 = ($datePerd >= 91 && $datePerd <= 120)?$item->nDebit:'';
						$dateTotal4 += $date4;
			?></td>
            <td><?php echo $date5 = ($datePerd > 120)?$item->nDebit:'';
						$dateTotal5 += $date5;
			?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
            <?php $i = $i + 1;
            ?>
            <?php endforeach;?>
        <tr>
            <td colspan="4" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
            <td><?php echo $dateTotal1;?></td>
            <td><?php echo $dateTotal2;?></td>
            <td><?php echo $dateTotal3;?></td>
            <td><?php echo $dateTotal4;?></td>
            <td><?php echo $dateTotal5;?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
        </tr>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="10">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>