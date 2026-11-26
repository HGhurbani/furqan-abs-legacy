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
            window.location.replace('<?php echo base_url().'index.php/ac_report/showlist/'.$this->uri->segment(3);?>');
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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/summary_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/summary_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/summary_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
            <th align="center" colspan="12">
               

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
            <td colspan="6" style="border:0" height="25px" align="left"><strong><?php echo SUMMARYREPORT;?></strong></td>
        <td colspan="6" style="border:0" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>
		</tr>
        <tr>
            <th align="left"><?php echo FROMDATE;?></th>
            <th align="left"><?php echo $fromDate;?></th>
            <th align="left"><?php echo TODATE;?></th>
            <th align="left"><?php echo $toDate;?></th>
            <th align="left" colspan="9">&nbsp;</th>
        </tr>
        <tr><td style="border:0"></td></tr>

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
td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>
    <tr >
        <td height="25px" colspan="6" align="left">&nbsp;</td>
        <td align="center" colspan="2" align="center"><?php echo OPENING;?></td>
        <td align="center" colspan="2" align="center"><?php echo TRANSACTION;?></td>
        <td align="center" colspan="2" align="center"><?php echo BALANCE;?></td>
    </tr>

    <tr id="bg">
        <td height="25px" align="center"><?php echo LOCATIONNAME;?></td>
        <td align="center"><?php echo ACCODE;?></td>
        <td align="center"><?php echo ACCCODENAME;?></td>
        <td align="center"><?php echo SUBCODE;?></td>
        <td align="center"><?php echo SUBNAME;?></td>
        <td align="center"><?php echo COUNT;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
        <td align="center"><?php echo DEBIT;?></td>
        <td align="center"><?php echo CREDIT;?></td>
    </tr>
 

    <?php
    $chkAccount_acc = array();
    $chkAccount_subacc = array();
    $totalopenDebit   = 0; 
    $totalopenCredit  = 0; 
    $totalDebit       = 0; 
    $totalCredit      = 0; 
    $totalbalDebit    = 0; 
    $totalbalCredit   = 0; 

    if(!empty($list_value)){
        $tablArray = array();?>

    <tbody>

        <?php
        $chkAccount_acc = array();
        $chkAccount_subacc = array();
        foreach($list_value as $key=>$item):
            $chkAccount_acc[] = $item->account;
            $chkAccount_subacc[] = $item->subacc;

            ?>

<?php
            $accSubName = '';
            $accSubCode = '';
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


                $open_list_value = array();
                $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($item->account, $item->subacc,$item->vTable,"location");
                $balance = $open_list_value['debit'] +  $item->debit -  $open_list_value['credit']  - $item->credit;
                $balDebit = $balCredit = 0;
                if($balance > 0 )
                    $balDebit = $balance;
                else 
                    $balCredit = $balance * -1;
           ?>
                <tr>
                    <td height="25px" align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td>
                    <td align="center"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");?></td>
                    <td align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");?></td>
                    <td align="center"><?php echo $accSubCode;?></td>
                    <td align="left"><?php echo $accSubName;?></td>
                    <td align="center"><?php echo $item->count;?></td>
                    <td align="right"><?php echo number_format((float)$open_list_value['debit'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$open_list_value['credit'], 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$item->debit, 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$item->credit, 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$balDebit, 2, ".", "");?></td>
                    <td align="right"><?php echo number_format((float)$balCredit, 2, ".", "");?></td>
                </tr>
<?php        

        $totalopenDebit   += $open_list_value['debit'];
        $totalopenCredit  += $open_list_value['credit'];
        $totalDebit       += $item->debit;
        $totalCredit      += $item->credit;
        $totalbalDebit    += $balDebit;
        $totalbalCredit   += $balCredit;

endforeach;?>

        <?php } 
//displaying remaining accounts
            $account_list = $this->ac_report_model->accountList($search_acc,$search_subAcc,$chkAccount_acc,$chkAccount_subacc);
        foreach($account_list as $key => $row){
            $open_list_value = array();
            $openDebit = $openCredit = $openVal = 0;
            $open_list_value =  $this->ac_report_model->getOpeningBalForAcc($row['accountId'],$row['subAccountId'],$row['vTable']);
            if( $open_list_value['debit'] != 0 ||  $open_list_value['credit'] != 0){
                $balDebit = $balCredit = 0;
                $balance = $open_list_value['debit'] -  $open_list_value['credit'];
                if($balance > 0 )
                    $balDebit = $balance;
                else 
                    $balCredit = $balance * -1;
 
        ?>
            <tr>
                <td>&nbsp;</td>
                <td height="25px" align="center"><?php echo $row['accountCode']; ?></td>
                <td align="left"><?php echo $row['accountName']; ?></td>
                <td align="center"><?php echo $row['subAccountCode'];?></td>
                <td align="left"><?php echo $row['subAccountName'];?></td>
                <td>&nbsp;</td>
               <td><?php echo $open_list_value['debit'];?></td>
                    <td><?php echo $open_list_value['credit'];?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right"><?php echo number_format((float)$balDebit, 2, ".", "");?></td>
                <td align="right"><?php echo number_format((float)$balCredit, 2, ".", "");?></td>
                </tr>



    <?php
        $totalopenDebit   += $open_list_value['debit'];
        $totalopenCredit  += $open_list_value['credit'];
        $totalbalDebit    += $balDebit;
        $totalbalCredit   += $balCredit;

            }//endif
        }// end foreach
    if(!($totalDebit ==0 && $totalCredit ==0 && $totalopenDebit ==0 && $totalopenCredit == 0 && $totalbalCredit ==0 && $totalbalDebit ==0)){

            ?>

        <tr id="bg">
            <td height="25px" colspan="6" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td align="right"><?php echo number_format((float)$totalopenDebit, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalopenCredit, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalDebit, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalCredit, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalbalDebit, 2, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalbalCredit, 2, ".", "");?></td>
        </tr>
<?php }else { ?>
   <tr>
        <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
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
        <td style="border:0">&nbsp;</td>
        <td style="border:0" colspan="11">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
