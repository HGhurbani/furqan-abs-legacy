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
            window.location.replace('<?php echo base_url().'index.php/alloc_report/showlist/'.$this->uri->segment(3);?>');
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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/payable_allocation_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/payable_allocation_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/payable_allocation_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <th colspan="14">


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

        <td colspan="6" style="border:0" height="25px" align="left">&nbsp;<strong><?php echo "ALLOCATION SEARCH";?></strong></td>

    

        <td colspan="6" style="border:0" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>

    </tr>

   <!-- <tr>

        <th align="left"><?php echo FROMDATE;?></th>

        <th align="left"><?php echo $this->txtFromDate;?></th>

        <th align="center"><?php echo TODATE;?></th>

        <th align="left"><?php echo $this->txtToDate;?></th>

        <th align="left" colspan="16">&nbsp;</th>

    </tr>-->
	<?php 
	//echo $ddlAccount."---".$ddlSubAccount."---".$ddltype."<br>";		
	switch($ddltype){

					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $ddlAccount  ."'"); break;

					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $ddlAccount  ."'"); break;

					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $ddlAccount  ."'"); break;

					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $ddlAccount  ."'"); break;

					default: $ddlSubAccount = $ddlAccount; $drAccountName = ""; break;

				}//end switch

				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$ddlSubAccount."'");


                    

						if($drAccountName != '' || !empty($drAccountName)) { 

						?>

						<tr>

							<td height="25px" colspan="9"><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></td>

						</tr>

						<?php

					}

					else { 

					?>

						<tr>

							<td colspan="9"><?php echo $subAccName; ?></strong></td>

						</tr>

						<?php 

					}													

					



?>
   <!-- <tr>
        <th align="left"><?php echo ACCODE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo SUBCODE;?></th>
        <th align="left"><?php echo SUBNAME;?></th>
        <th align="left" colspan="10" rowspan="2">&nbsp;</th>
    </tr>

    <tr>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
    </tr>-->
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
    <tr>
        <td height="25px" colspan="3" align="center"><?php echo PAIDDETAILS;?></td>
        <td colspan="6" align="center"><?php echo ALLOCATEDDETAILS;?></td>
    </tr>

    <tr id="bg">
        <td height="25px" align="center"><?php echo CUSTDATE;?></td>
       
        <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo DESCRIPTION;?></td>
        
		 <td align="center"><?php echo DOCUMENTNUMBER;?></td>
        <td align="center"><?php echo TICKETNAME;?></td>
        <td align="center"><?php echo CREDITAMOUNT;?></td>
        <td align="center"><?php echo ALLOCATEDAMT;?></td>
        <td align="center"><?php echo BALANCEAMT;?></td>
       
    </tr>
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){
        $i = 1;
        foreach($list_value as $key=>$item):

            switch($item->vAllType)
            {
                case "CR":
					$document=$this->common_model->get_field_name('CashReceipt','vDocNumber'," WHERE nCRId = '".$item->nReceiptDId."'");
					$path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$item->nReceiptDId;
                    break;

                case "CP":
					$document=$this->common_model->get_field_name('CashPayment','vDocNumber'," WHERE nCPId = '".$item->nReceiptDId."'");
				
                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$item->nReceiptDId;
                    break;

                case "BR":
					$document=$this->common_model->get_field_name('BankBook','vDocNumber'," WHERE nBBId = '".$item->nReceiptDId."'");
					$path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$item->nReceiptDId;
                    break;

                case "BP":
					$document=$this->common_model->get_field_name('BankBookPayment','vDocNumber'," WHERE nBPId = '".$item->nReceiptDId."'");
					$path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$item->nReceiptDId;
                    break;

                case "JV":
					$document=$this->common_model->get_field_name('Jv','vDocNumber'," WHERE nJVId = '".$item->nReceiptDId."'");
					$path = base_url().'index.php/jv/editJv/5/e/'.$item->nReceiptDId;
                    break;

                

                case "ACM":
					$document=$this->common_model->get_field_name('AcmEntry','vDocNumber'," WHERE nACId = '".$item->nReceiptDId."'");
					
                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$item->nReceiptDId;
                    break;
				case "SC":
					if($item->nDrAccount==55){
						$sad=$this->common_model->get_field_name('SalesAccountDetails','nSalesCloseId'," WHERE nId = '".$item->nReceiptDId."'");
					
						$document=$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$sad."'");
					
					}else{
						$document=$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$item->nReceiptDId."'");
					
					}
					$path = '';
                    break;
				case "RC":
					$rc=$this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nId = '".$item->nReceiptDId."'");
					
					$document=$this->common_model->get_field_name('RefundClosing','vDocNumber'," WHERE nRCId = '".$rc."'");
					
                    break;	
					case "RCC":
					$rc=$this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nId = '".$item->nReceiptDId."'");
					
					$document=$this->common_model->get_field_name('RefundClosing','vDocNumber'," WHERE nRCId = '".$rc."'");
					
                    break;	
				case "OP": $document="OPENING"; break;
                case "ADM":
					$document=$this->common_model->get_field_name('AdmEntry','vDocNumber'," WHERE nADId = '".$item->nReceiptDId."'");
					
                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$item->nReceiptDId;
                    break;
            }//end switch


			switch($item->vSalesCloseType)
            {
				case "OP": $idocument="OPENING"; break;
                case "CR":
					$idocument=$this->common_model->get_field_name('CashReceipt','vDocNumber'," WHERE nCRId = '".$item->nSalesCloseId."'");
					 break;
					 

                case "CP":
					$idocument=$this->common_model->get_field_name('CashPayment','vDocNumber'," WHERE nCPId = '".$item->nSalesCloseId."'");
				
                    break;

                case "BR":
					$idocument=$this->common_model->get_field_name('BankBook','vDocNumber'," WHERE nBBId = '".$item->nSalesCloseId."'");
					
                    break;

                case "BP":
					$idocument=$this->common_model->get_field_name('BankBookPayment','vDocNumber'," WHERE nBPId = '".$item->nSalesCloseId."'");
					
                    break;

                case "JV":
					$idocument=$this->common_model->get_field_name('Jv','vDocNumber'," WHERE nJVId = '".$item->nSalesCloseId."'");
					
                    break;

                case "ACM":
					$idocument=$this->common_model->get_field_name('AcmEntry','vDocNumber'," WHERE nACId = '".$item->nSalesCloseId."'");
					
                    break;
				case "SC":
					$idocument=$this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$item->nSalesCloseId."'");
					
                    break;

                case "ADM":
					$idocument=$this->common_model->get_field_name('AdmEntry','vDocNumber'," WHERE nADId = '".$item->nSalesCloseId."'");
					
                    break;
				case "RCR":
					$idocument=$this->common_model->get_field_name('RefundClosing','vDocNumber'," WHERE nRCId = '".$item->nSalesCloseId."'");
					
                    break;
				case "RC":
					$idocument=$this->common_model->get_field_name('RefundClosing','vDocNumber'," WHERE nRCId = '".$item->nSalesCloseId."'");
					
                    break;
					
				case "RCC":
				$rc=$this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nId = '".$item->nSalesCloseId."'");
					
					$idocument=$this->common_model->get_field_name('RefundClosing','vDocNumber'," WHERE nRCId = '".$rc."'");
					
                    break;
            }	
			
			

				switch($item->vTable){

					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->nDrAccount  ."'"); break;

					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->nDrAccount  ."'"); break;

					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->nDrAccount  ."'"); break;

					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->nDrAccount  ."'"); break;
					case "A": $drAccountName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->nDrAccount."'");break;

					
				}//end switch
		
            //$getAlloc = $this->alloc_report_model->getAllocatedDetails_search($item->type,$item->idVal,$item->detIdVal);
            ?>
            <tr>
                <td height="25px" align="center"><?php echo date("d-M-y", strtotime($item->dDate));?></td>
                
                <td align="center"><a style="text-decoration: none;" href="<?php echo $path;?>"><?php echo $document;?></a></td>
                <td width="20%"><?php echo $drAccountName; //echo "--".$item->vAllType."--".$item->vSalesCloseType;?></td>
				<td height="25px" align="center"><?php echo $idocument;?></td>
                <td height="25px" align="center"><?php echo $item->vAllTktNumber;?></td>
                <td height="25px" align="center"><?php echo $item->vDebitAmt;?></td>
                <td align="center"><?php echo $item->vAllAmt;?></td>
                <td align="center"><?php echo $item->vBalAmt;?></td>
                
            </tr>
        
            <?php endforeach;?>
   
    
        <?php }
    else{
        ?>
    <tr>
        <td colspan="14" style="border:0" align="center"><?php echo SORRYNORECORD;?></td>
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
        <td style="border:0" colspan="16">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>