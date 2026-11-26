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

<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');

if(isset($error_value) && trim($error_value)!='')
{
    ?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<?php $this->load->view('general/admin_menu');?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('ac_report/showlist/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<div class="content-box-export">
    <div class="inner">
        <a href="<?php echo base_url().'index.php/export_xl/detail_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_word/detail_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
        <a href="<?php echo base_url().'index.php/export_pdf/detail_report/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
    <th colspan="10"><h3><?php echo DETAILREPORT;?></h3></th>
</tr>
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
    <th align="left"><?php echo ACCODE;?></th>
    <th align="left"><?php echo ACCCODENAME;?></th>
    <th align="left"><?php echo SUBCODE;?></th>
    <th align="left"><?php echo SUBNAME;?></th>
    <th align="left" colspan="6">&nbsp;</th>
</tr>
<tr>
    <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$ddlAccount."'");?></th>
    <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$ddlAccount."'");?></th>
    <th align="left"><?php echo $SubCode;?></th>
    <th align="left"><?php echo $SubName;?></th>
    <th align="left" colspan="2">&nbsp;</th>
    <th align="center" colspan="2"><?php echo TRANSACTION;?></th>
    <th align="center" colspan="2"><?php echo BALANCE;?></th>
</tr>
<tr>
    <th align="left"><?php echo SRNO;?></th>
    <th align="left"><?php echo CUSTDATE;?></th>
    <th align="left"><?php echo LOCATIONNAME;?></th>
    <th align="left"><?php echo DOCUMENTNUMBER;?></th>
    <th align="left"><?php echo TICKETNAME;?></th>
    <th align="left"><?php echo DESCRIPTION;?></th>
    <th align="left"><?php echo DEBIT;?></th>
    <th align="left"><?php echo CREDIT;?></th>
    <th align="left"><?php echo DEBIT;?></th>
    <th align="left"><?php echo CREDIT;?></th>
</tr>
<tr>
    <td colspan="6" align="center"><strong><?php echo YEARCLOSING;?></strong></td>
    <td><?php echo $year_closing['debit'];?></td>
    <td><?php echo $year_closing['credit'];?></td>
    <td><?php echo ($year_closing['debit'] - $year_closing['credit']);?></td>
    <td>&nbsp;</td>
</tr>
<?php
		$this->session->unset_userdata('newDebit');
		$openVal = $this->ac_report_model->getCalDebit($open_list_value['debit'],$open_list_value['credit']);
    
            if($openVal >= 0):
                $openCredit = 0;
                $openDebit = $openVal;
            endif;

            if($openVal <= 0):
                $openCredit = substr($openVal,1,strlen($openVal));
                $openDebit = 0;
            endif;
?>
 

<tr>
    <td colspan="6" align="center"><strong><?php echo OPENINGBALANCE;?></strong></td>
    <td><?php echo $open_list_value['debit'];?></td>
    <td><?php echo $open_list_value['credit'];?></td>
    <td><?php echo $openDebit;?></td>
    <td><?php echo $openCredit;?></td>
</tr>
</thead>

<?php
if(!empty($list_value)){
    $tablArray = array();?>

    <tbody>

        <?php
    $this->session->unset_userdata('newDebit');
    $openDebit = $this->ac_report_model->getCalDebit($open_list_value['debit'],$open_list_value['credit']);
    $i = 1;

    foreach($list_value as $key=>$item):

        switch($item->tblName)
        {
            case "CR":
                $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "CP":
                $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "BR":
                $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "BP":
                $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "JV":
                $path = base_url().'index.php/jv/editJv/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "SJ":
                $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'");
                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
                $getTktValue  = $this->ac_report_model->getTktNumberValues($item->detId,$item->tableId);
                $description = $getTktValue['desc'];
                $ticketNum = $getTktValue['tktVal'];
                break;

            case "CV":
                $path = base_url().'index.php/credit_voucher/editCreditVoucher/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "DV":
                $path = base_url().'index.php/debit_voucher/editDebitVoucher/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "ACM":
                $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;

            case "ADM":
                $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->tableId;
                $description = $item->description;
                $ticketNum   = '&nbsp';
                break;
        }//end switch

        switch($item->mode)
        {
            case "Dr":
                $debit = $item->debit;
                $credit = 0;
                break;

            case "Cr":
                $debit = 0;
                $credit = $item->debit;
                break;

            default:
                $debit = ($item->debit==' ')?0:$item->debit;
                $credit = ($item->credit==' ')?0:$item->credit;
                break;
        }

        $openVal = $this->ac_report_model->getCalDebit($debit,$credit);

        if($openVal >= 0):
            $openCredit = 0;
            $openDebit = $openVal;
        endif;

        if($openVal <= 0):
            $openCredit = substr($openVal,1,strlen($openVal));
            $openDebit = 0;
        endif;
        ?>

        <tr>
            <td><?php echo $key+1;?></td>
            <td><?php echo date("d-M-y", strtotime($item->date));?></td>
            <td><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId ='".$item->location."'");?></td>
            <td><a href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
            <td><?php echo $ticketNum;?></td>
            <td><?php echo $description;?></td>
            <td><?php echo $debit;?></td>
            <td><?php echo $credit;?></td>
            <td><?php echo $openDebit;?></td>
            <td><?php echo $openCredit;?></td>
        </tr>

        <?php $i = $i + 1;
        $totalDebit += $debit;
        $totalCredit += $credit;

        $totalopenDebit = $openDebit;
        $totalopenCredit = $openCredit;

        if(!in_array($item->document,$tablArray))
        {
            switch($item->mainMode)
            {
                case "Dr":
                    $cashDebit += $item->mainAmount;
                    break;

                case "Cr":
                    $cashCredit += $item->mainAmount;
                    break;
            }//end swtich
            ?>
            <?php
        }//end if
        $tablArray[]     = $item->document;
    endforeach;?>


<tr>
    <td colspan="6" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
    <td><?php echo $totalDebit;?></td>
    <td><?php echo $totalCredit;?></td>
    <td><?php echo $totalopenDebit;?></td>
    <td><?php echo $totalopenCredit;?></td>
</tr>
<tr>
    <td colspan="6" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
    <td><?php echo $totalDebit;?></td>
    <td><?php echo $totalCredit;?></td>
    <td><?php echo $totalopenDebit;?></td>
    <td><?php echo $totalopenCredit;?></td>
</tr>
    <?php }
else{
    ?>
<tr>
    <td colspan="10" align="center"><?php echo SORRYNORECORD;?></td>
</tr>
    <?php
}//end if
?>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9">
        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
    </td>
</tr>
</tbody>
</table>
    </form>
</div>
