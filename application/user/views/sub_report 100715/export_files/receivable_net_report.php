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
            window.location.replace('<?php echo base_url().'index.php/sub_report/showlist/'.$this->uri->segment(3);?>');
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
    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/receivable_net_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/receivable_net_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/receivable_net_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
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
        <th colspan="20">
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
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
    <tr>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left">&nbsp;</th>
        <th align="left" colspan="5">&nbsp;</th>
        <th align="center" colspan="3"><?php echo SALESDETAILS;?></th>
        <th align="center" colspan="6"><?php echo NETDETAILS ;?></th>
        <th align="center" colspan="2"><?php echo MARGIN;?></th>
    </tr>
    <tr>
        <th align="left"><?php echo SRNO;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo DOCUMENTNUMBER;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo VCH;?></th>
        <th align="left"><?php echo CONF;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo PASSENGERNAME;?></th>
        <th align="left"><?php echo TRAVELSETUPDETAILS;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
        <th align="left"><?php echo RCVD;?></th>
        <th align="left"><?php echo BALANCE;?></th>
        <th align="left"><?php echo ACCCODENAME;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>
        <th align="left"><?php echo BALANCE;?></th>
        <th align="left"><?php echo RCVDT;?></th>
        <th align="left"><?php echo RECEIVE;?></th>
        <th align="left"><?php echo PROFIT;?></th>
        <th align="left"><?php echo PROFITPERCENTAGE;?></th>
    </tr>
<!--    <tr>
        <th colspan="20" align="center"><?php echo OPENINGBALANCE;?></th>
    </tr>
-->	
    </thead>

    <tbody>

    <?php
    if(!empty($list_value)){
        $i = 1;
		$chkAccount = array();
        foreach($list_value as $key=>$item):
		    if(!in_array($item->vACode_s,$chkAccount)):
				if( $i !=1 ):
			?>
					<tr>
						<td colspan="9" align="right"><strong><?php echo AMOUNT;?></strong></td>
						<td><?php echo $Debit_per_group ;?></td>
						<td><?php echo $Credit_per_group ;?></td>
						<td><?php echo $Balance_per_group ;?></td>
						<td>&nbsp;  </td>
						<td><?php echo $slsamt_per_group ;?></td>
						<td>&nbsp;  </td>
						<td>&nbsp;  </td>
						<td>&nbsp;  </td>
						<td>&nbsp;  </td>																								
						<td><?php echo $prof_per_group ;?></td>
						<td><?php echo $profpercent_per_group ;?></td>												
					</tr>
				<?php
					$Debit_per_group 	= 0;
					$Credit_per_group 	= 0;
					$Balance_per_group	= 0;
					$slsamt_per_group	= 0;
					$prof_per_group 		= 0;
					$profpercent_per_group =0;
					$balance = 0;
				?>
				<?php endif; 
	
				switch($item->vTable_s){
					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode_s  ."'"); break;
					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode_s  ."'"); break;
					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode_s  ."'"); break;
					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->vACode_s  ."'"); break;
					default: $item->vAcSubCode_s = $item->vACode_s ; $drAccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode_s."'");
				if($drAccountName != '' || !empty($drAccountName)) { ?>
						<tr> <td colspan="18"><strong><?php echo $subAccName . "  -  " . $drAccountName; ?></strong></td> </tr>
				<?php }
				else { 	?>
						<tr> <td colspan="18"><strong><?php echo $subAccName; ?></strong></td> </tr>
				<?php }												
					
			
			
			endif;

			switch($item->vTable){
				case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); break;
				case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'"); break;
				case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode  ."'"); break;
				case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->vACode  ."'"); break;
				default: $item->vAcSubCode = $item->vACode ; $drAccountName = ""; break;
			}//end switch
			$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");
			if($drAccountName != '' || !empty($drAccountName)) { 
				  $payableaccount = $subAccName . "  -  " . $drAccountName; 
			}
			else { 
				 $payableaccount = $subAccName; 
			}												
			

			$exchangerate = $item->vExchange;
			$currency 	 = $item->nCurrency;
			$fc 		 = $item->vFCAmount;
			$passenger 	 = '&nbsp';
			$conf		 = '&nbsp';
			$issued		 = '&nbsp';					
			$ticketNum   = '&nbsp';
            $description = $item->description;
			$passenger 	 = $item->vRefName;
			
            if($item->tblName == 'SJ')
			{
            		$sales_refund = $this->common_model->get_field_name('SystemJv', 'vType', " WHERE nSalesCId='".$item->tableId."'" );

					$getAccountDetails  = $this->sub_report_model->getReceivableAccountValues($item->vType,$item->vNumber,$item->tableId,$sales_refund);
//					$acc = $getAccountDetails['accounts'];
					$prof = $getAccountDetails['profit'];										
					$profpercent = $getAccountDetails['profitpercent'];							
										
					if($sales_refund == 'S'){
						$debit = $getAccountDetails['collected'];					
	            	    $salesDetId  = $this->common_model->get_field_name('SalesClosingDetails','nId'," WHERE nSalesCloseId='".$item->tableId."'");
    	                $path = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$item->tableId.'/cl';
					}
					else {
						$credit = $getAccountDetails['collected'];					
	                    $salesDetId  = $this->common_model->get_field_name('RefundClosingDetails','nId'," WHERE nRefundCloseId='".$item->tableId."'");
    	                $path = base_url().'index.php/refund_closing/editRefundClosing/4/e/'.$salesDetId.'/'.$item->tableId.'';
					}
					
                    $getTktValue  = $this->sub_report_model->getTktNumberValues($item->vType,$item->vNumber);
                    $description = $getTktValue['desc'];
					$conf =	$getTktValue['conf'];	 
					$issued =	$getTktValue['issued'];	 					
					$passenger = $getTktValue['pass'];
					$currency =	$getTktValue['currency'];	 					
					$fc = $getTktValue['fc'];					
					$exchangerate = $getTktValue['exchangerate'];					
                    $ticketNum = $getTktValue['tktVal'];
								
					
            }//end if


            switch($item->mode)
            {
                case "Dr": $slsamt = $item->debit; break;
                case "Cr": $slsamt = -$item->debit; break;
                default:
                    $slsamt = ($item->debit==' ')?0:$item->debit;
                    $slsamt = -(($item->credit==' ')?0:$item->credit);
                    break;

            }
			$balance = $balance + $debit - $credit;

            ?>
        <tr>
            <td><?php echo $i;?></td>
            <td><?php echo date("d-M-y", strtotime($item->date));?></td>
            <td><a href="<?php echo $path;?>"><?php echo $item->document;?></a></td>
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->location."'"));?></td>
            <td><?php echo $ticketNum; ?></td>
            <td><?php echo $conf; ?></td>
            <td><?php echo $this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='". $issued ."'"); ?></td>
            <td style="word-break:break-all;"><?php echo $passenger; ?></td>
            <td><?php echo $description;?></td>
            <td><?php echo $debit;?></td>
            <td><?php echo $credit;?></td>
            <td><?php echo $balance;?></td>
            <td><?php echo $payableaccount; ?></td>
            <td><?php echo $slsamt; ?></td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td>&nbsp;</td>
            <td><?php echo $prof;  ?></td>
            <td><?php echo $profpercent;  ?></td>
	
        </tr>
            <?php $i = $i + 1;
            $chkAccount[] 			= $item->vACode_s;
		    $Debit_per_group   = $Debit_per_group+$debit;
            $Credit_per_group  = $Credit_per_group+$credit;
            $Balance_per_group = $balance;
			$slsamt_per_group = $slsamt +  $slsamt_per_group;
			$prof_per_group = $prof + $prof_per_group;
			$profpercent_per_group = $profpercent + $profpercent_per_group;
			
            $Debit   = $Debit+$debit;
            $Credit  = $Credit+$credit;
            $Balance = $Debit - $Credit;
			$Slsamt = $Slsamt + $slsamt;
			$Prof = $Prof + $prof ;
			$Profpercent = $Profpercent + $profpercent;
	       endforeach;?>
    <tr>
        <td colspan="9" align="right"><strong><?php echo AMOUNT;?></strong></td>
        <td><?php echo $Debit_per_group ;?></td>
        <td><?php echo $Credit_per_group ;?></td>
        <td><?php echo $Balance_per_group ;?></td>
		<td>&nbsp;  </td>
		<td><?php echo $slsamt_per_group ;?></td>
		<td>&nbsp;  </td>
		<td>&nbsp;  </td>
		<td>&nbsp;  </td>
		<td>&nbsp;  </td>																								
		<td><?php echo $prof_per_group ;?></td>
		<td><?php echo $profpercent_per_group ;?></td>												
	</tr>
		
    <tr>
        <td colspan="9" align="right"><strong><?php echo GRAND;?></strong></td>
        <td><?php echo $Debit;?></td>
        <td><?php echo $Credit;?></td>
        <td><?php echo $Balance;?></td>
		<td>&nbsp;  </td>
		<td><?php echo $Slsamt ;?></td>
		<td>&nbsp;  </td>
		<td>&nbsp;  </td>
		<td>&nbsp;  </td>
		<td>&nbsp;  </td>																								
		<td><?php echo $Prof;?></td>
		<td><?php echo $Profpercent;?></td>												
	</tr>

        <?php }
    else{
        ?>
    <tr>
        <td colspan="20" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php
    }//end if
    ?>
    <tr class="formButs">
        <td>&nbsp;</td>
        <td colspan="20">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
