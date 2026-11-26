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
 window.location.replace('<?php echo base_url().'index.php/open_report/showlist/'.$this->uri->segment(3);?>');
 return false;        });       
 $('.print_logo').hide();        
 $('.print_desc').hide();        
 
 $('#btnPrint').click(function(e) {   
//alert('test'); 
 //$('.print_logo').show();           
 $('.print_desc').show();            
 $('#reportForm').printElement();            
 $('.print_logo').hide();           
 $('.print_desc').hide();       
 });

 }); 
 
 </script>
 
<div class="content-box-export">
    <div class="inner">
    <a href="<?php echo base_url().'index.php/export_xl/open_balance_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/open_balance_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/open_balance_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <th colspan="7">
            <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="100" height="100">

            <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?>,
                <?php echo WEB;?>: <?php echo $web;?>
            </div>
        </th>
    </tr>
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
td{font-size: 11px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>
    <tr id="bg">
        <td height="25px" align="center"><?php echo SRNO;?></td>
        <td align="center"><?php echo ACCODE;?></td>
        <td align="center"><?php echo ACCCODENAME;?></td>
        <td align="center"><?php echo SUBCODE;?></td>
        <td align="center"><?php echo SUBNAME;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):
             $getTableType = $this->open_report_model->getTableType($item->account,$item->subacc)
            ?>
        <tr>
            <td height="25px" align="center"><?php echo $key+1;?></td>
            <td align="center"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");?></td>
            <td><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");?></td>
            <td align="center"><?php echo $getTableType['subCode'];?></td>
            <td><?php echo $getTableType['subName'];?></td>
            <td align="right"><?php echo number_format((float)$item->debit, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$item->credit, 2, ".", "");?></td>
        </tr>
            <?php $i = $i + 1;
            $totalDebit  =  $totalDebit+ $item->debit;
            $totalCredit =  $totalCredit+ $item->credit;
            ?>
            <?php endforeach;?>
        <?php }
    else{
        ?>
    <tr>
        <td colspan="7" style="border:0" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
    <tr>
        <td height="25px" colspan="5" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
        <td align="right"><?php echo number_format((float)$totalDebit, 2, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totalCredit, 2, ".", "");?></td>
    </tr>
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
        <td style="border:0" colspan="6">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
