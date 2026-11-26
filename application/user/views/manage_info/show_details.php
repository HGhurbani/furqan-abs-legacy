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
            window.location.replace('<?php echo base_url().'index.php/manage_info/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('.print_logo').hide();
        $('.print_desc').hide();

        $('#btnPrint2').click(function(e) {
            $('.print_logo').show();
            $('.print_desc').show();
            $('.formButs').hide();
            $('.printPop').printElement();
            $('.formButs').show();
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm printPop" id="reportForm">
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

    <tr style="border:none !important;">
        <th style="border:none !important;" align="center" colspan="20">
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
	<!--<tr> <b> &nbsp; Airline Report </b> </tr>-->
	<tr>
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Account Detail Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="3" align="right"><?php echo FROMDATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtFromDate;?></th>
        <th colspan="2" align="right"><?php echo TODATE;?></th>
        <th colspan="2" align="left"><?php echo $this->txtToDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
	<style media="print">
  #bg{   
 -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
table {  
display:table-cell !important;}
</style>
<style media="print">

  @media print {
    @page {
		margin-left: 0.5cm;
		
		margin-right: 0.5cm;
     
    }
	@page:left{
  @bottom-left {
    content: "Page " counter(page) " of " counter(pages);
  }
}
   
}

@media print
{
  table { page-break-inside:auto !important; }
    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
}


</style>
<style>
table {  
width:100% !important;
font-size: 9px; }
td{
    border: 0px solid black;
border-collapse: collapse;
}
</style>
<?php 
$echo="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$echo1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
    <tr id="bg">
        <th align="center"><?php echo SRNO;?></th>
        <th align="center"><?php echo CUSTDATE;?></th>
        <th align="center"><?php echo LOCATIONNAME;?></th>
        <th align="center"><?php echo ACCODE;?></th>
        <th align="center"><?php echo ACCCODENAME;?></th>
        <th align="center"><?php echo SUBCODE;?></th>
        <th align="center"><?php echo SUBNAME;?></th>
        <th align="center"><?php echo DOCUMENTNUMBER;?></th>
        <th align="center"><?php echo TICKETNAME;?></th>
        <th align="center"><?php echo DESCRIPTION;?></th>
        <th align="center"><?php echo DEBIT;?></th>
        <th align="center"><?php echo CREDIT;?></th>
    </tr>
    </thead>

    <tbody>

    <?php
    $sqlArray = $this->db->query($_SESSION['sess_query']); 
    if ($sqlArray->num_rows() > 0)
    {
        $i = $j = 0;
        $srNo = 1;
        $flag = false;
        $chkAccountId = $mainChk = array();
        foreach($sqlArray->result() as $key => $item):

            if($item->account != '')
            {
                switch($item->vTable)
                {
                    case "C":
                        $SubAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->account."'");
                        break;

                    case "V":
                        $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->account."'");
                        break;

                    case "A":
                        $SubAccountName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->account."'");
                        break;

                    case "O":
                        $SubAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->account."'");
                        break;

                    case "AG":
                        $SubAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->account."'");
                        $SubAccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->account."'");
                        break;
                }
            }
            else
            {
                if($item->subacc == 32 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('TicketFareDetails','nAcc'," WHERE nTicketId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 34 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('HotelVoucher','nAcCode'," WHERE nHVId  = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                elseif($item->subacc == 35 && trim($item->account) == ''):
                    $getCode        = $this->common_model->get_field_name('XoBooking','nPassengerAC'," WHERE nXId = '".$item->vNumber."'");
                    $SubAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId  = '".$getCode."'");
                    $SubAccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId  = '".$getCode."'");
                else:
                    $SubAccountName = '&nbsp';
                    $SubAccountCode = '&nbsp';
                endif;
            }

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

            $tktNumber   = $this->manage_info_model->returnTktNumber($item->tableId,$item->tblName);

            if(!in_array($SubAccountName,$chkAccountId)): ?>
                <?php if($j != 0):?>
                    <tr>
                        <td colspan="10" align="right"><strong><?php  $tx=$this->common_model->get_field_name('OpeningBalanceDetails','vBalDC'," WHERE vACode='".$item->subacc."'AND vAcSubCode='".$item->account."'");
						 echo TOTALAMOUNT;?></strong></td>
                        <td align="right"><?php echo number_format((float)$totalDebit+$xa, 3, ".", "");?></td>
                        <td align="right"><?php  $xa='';$totalDebit=''; echo number_format((float)$totalCreditLoop, 3, ".", "");?></td>
                    </tr>
                    <tr>
                        <td colspan="12" align="right">&nbsp;</td>
                    </tr>
                <?php $flag = true; endif;?>
                    <tr>
                        <td colspan="12"><strong><?php echo $SubAccountName;?></strong></td>
                    </tr>
                    
                  <?php  if($this->common_model->get_field_name('OpeningBalanceDetails','vBalDC'," WHERE vACode='".$item->subacc."'AND vAcSubCode='".$item->account."'")!=''){?> 
                  <tr><td colspan="10">Opening Balance</td>
                        <td colspan=""><strong><?php $op='';$op= $this->common_model->get_field_name('OpeningBalanceDetails','vBalDC'," WHERE vACode='".$item->subacc."'AND vAcSubCode='".$item->account."'"); $xa=$op; echo $op;?></strong></td>
                    </tr>
            <?php }$j = 1;endif;?>

            <tr>
                <td align="center"><?php echo $key+1;?></td>
                <td align="center"><?php echo date('d-m-Y',strtotime($item->date));?></td>
                <td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                <td align="center"><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->subacc."'");?></td>
                <td><?php echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->subacc."'");?></td>
                <td align="center"><?php echo $SubAccountCode;?></td>
                <td><?php echo $SubAccountName;?></td>
                <td><?php echo $item->document;?></td>
                <td><?php echo $tktNumber;?></td>
                <td><?php echo $item->description;?></td>
                <td align="right"><?php echo $debit;?></td>
                <td align="right"><?php echo $credit;?></td>
            </tr>
			<?php
				//check any main accoutn exists
				$getDebitCredit = $this->manage_info_model->getMainAccountValDetails($SubAccountCode,$sqlArray->result(),$item->account);

			if($getDebitCredit['show'] == true && !in_array($item->account,$mainChk)): 
				$credit = $credit + $getDebitCredit['wcredit'];
          		$debit  = $debit  + $getDebitCredit['wdebit'];
			?>
            <tr>
                <td align="center"><?php echo $key+1;?></td>
                <td align="center"><?php echo $getDebitCredit['date'];?></td>
                <td align="center"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'");?></td>
                <td align="center"><?php //echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->subacc."'");?></td>
                <td><?php //echo ($item->subacc=='0')?'&nbsp':$this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->subacc."'");?></td>
                <td align="center"><?php echo $SubAccountCode;?></td>
                <td><?php echo $SubAccountName;?></td>
                <td><?php echo $getDebitCredit['doc'];?></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td align="right"><?php echo $getDebitCredit['wdebit'];?></td>
                <td align="right"><?php echo $getDebitCredit['wcredit'];?></td>
            </tr>
            <?php
				$mainChk[] = $item->account;
				endif;
            if($flag == true):
                $totalDebitLoop  = 0;
                $totalCreditLoop = 0;
                $totalopenDebitLoop = 0;
                $totalopenCreditLoop = 0;
                $flag            = false;
            endif;
            $i               = $i + 1;
            $totalDebit     += $debit;
			$totalDebit1     += $debit;
            $totalCredit    += $credit;
            $chkAccountId[]  = $SubAccountName;

            $totalDebitLoop   += $debit;
            $totalCreditLoop  += $credit;
			$opp +=$op;
			$op='';
            if(($key+1) == $sqlArray->num_rows()):
                ?>
            <tr>
                <td colspan="10" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>
                <td align="right"><?php echo number_format((float)$totalDebitLoop+($this->common_model->get_field_name('OpeningBalanceDetails','vBalDC'," WHERE vACode='".$item->subacc."'AND vAcSubCode='".$item->account."'")), 3, ".", "");?></td>
                <td align="right"><?php echo number_format((float)$totalCreditLoop, 3, ".", "");?></td>
            </tr>
                <?php endif;
        endforeach;?>

        <tr>
            <td colspan="10" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>
            <td align="right"><?php echo number_format((float)$totalDebit1+$opp, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$totalCredit, 3, ".", "");?></td>
        </tr>

        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="11">
                <input type="button" name="btnPrint2" value="<?php echo PRINTPAGE;?>" id="btnPrint2">
            </td>
        </tr>

        <?php }
    else{
        ?>
    <tr>
        <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>

    </tbody>
</table>