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

            window.location.replace('<?php echo base_url() . 'index.php/sales_report/showlist/' . $this->uri->segment(3);?>');

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
        <th align="center" colspan="16">
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
        <td colspan="16" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Sales Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="2" align="center"><?php echo FROMDATE;?></th>
        <th align="left"><?php echo $this->txtToDate;?></th>
        <th align="center"><?php echo TODATE;?></th>
        <th align="left"><?php echo $this->txtFromDate;?></th>
        <th align="left" colspan="16">&nbsp;</th>
    </tr>
   
<style type="text/css">
#br{
	display:none;}
body{
  -webkit-print-color-adjust:exact;
}
  table { font-size: 9px; }
</style>
<style media="print">
  #bg{ background-color:#CCC !important; }
 #br{ display:block !important;}
</style>

 <tr id="bg" style="">

        <th height="20px" align="center">#</th>
 		<th style="width:8%" align="left"><?php echo ISSUEDDATE;?></th>
  		<th align="center"><?php echo PNR;?></th>
   		<th align="center"><?php echo AIRCODE;?></th>
   		<th style="width:8%" align="center"><?php echo 'TICKET&nbsp;#';?></th>
        <th align="center"><?php echo PASSENGERNAME;?></th>
		<th align="center"><?php echo CLOSINGDATE;?></th>
        <th align="center"><?php echo NETAMT;?></th>
		<th align="center" style="word-break:break-all;"><?php echo SALESAMOUNT;?></th>
		<th align="center"><?php echo PROFIT;?></th>		
		<th align="center" style="word-break:break-all;"><?php echo PROFITPERCENTAGE;?></th>		
		<th align="center"><?php echo SUPPLIER;?></th>				
		<th align="center"><?php echo ACCOUNT;?></th>				



    </tr>

    </thead>

    <tbody>

	

	    <?php

    if(!empty($list_value)){

        $i= 1;
$prev=0;
        foreach($list_value as $key=>$item):?>

			

            <tr>

				<td height="20px" align="center"><?php echo $this->common_model->_offset + $i;?></td>
                <td align="center"><?php echo stripslashes($item->dDate_txh); ?></td>
                
                <td align="center" ><?php echo stripslashes($item->vGdspnr);?></td>
                 <td align="center"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAlpha'," WHERE nArId='".$item->airline."'"));?></td>
                 <td align="left"><?php $cur=$item->tktNumber; echo stripslashes($item->tktNumber);?></td>

                <td align="left" style="word-break:break-all;"><?php echo stripslashes($item->vPassenger);?></td>

               <td align="center"><?php echo stripslashes($item->dDate); ?></td>

                


                
<?php if($prev!=$cur){?>
                <td align="right"><?php echo stripslashes($item->netAmt);?></td>

                <td align="right"><?php echo stripslashes($item->salesAmount);?></td>

                <td align="right"><?php echo stripslashes($item->Profit);?></td>

                <td align="right"><?php echo round(stripslashes($item->ProfitPercent),3); echo " %";?></td>
 
                

                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->account."'"));?></td>
<?php }else{?>


<td align="right"><?php $a=$a+$item->netAmt;?></td>

                <td align="right"><?php $b=$b+$item->salesAmount;?></td>

                <td align="right"><?php $c=$c+$item->Profit;?></td>

                <td align="right"><?php $d=$d+$item->ProfitPercent;?></td>
 				
                

                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->account."'"));?></td>
                <?php }$prev=$item->tktNumber;?>
                <td align="left"><?php 

								if($item->refundtype == "sales"){

									$accounts = $this->sales_report_model->returnSalesClosedAccounts($item->closedId);

									$delimeter = '';

									foreach($accounts as $key_a => $item_a):

										switch($item_a->vTable)

										{

											case "C":

												$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item_a->nDrAccount  ."'");

											break;

											case "V":

												$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item_a->nDrAccount  ."'");

											break;

											case "AG":

												$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item_a->nDrAccount  ."'");

											break;

											case "O":

												$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item_a->nDrAccount  ."'");

											break;

											default:

												$item_a->nAcHead = $item_a->nDrAccount ;

												$drAccountName = "";

											break;

										}//end switch

								

										$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item_a->nAcHead."'");

										?>				

								

										<?php 

												if($drAccountName != '' || !empty($drAccountName)) { echo $delimeter . $drAccountName; }  

												else { echo $delimeter . $subAccName ; }												

												$delimeter = ", <br> "; 



									endforeach;

								}

								

								else if ($item->refundtype == "refund") {

																	

									switch($item->vTable)

									{

										case "C":

											$drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->nDrAccount  ."'");

										break;

										case "V":

											$drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->nDrAccount  ."'");

										break;

										case "AG":

											$drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->nDrAccount  ."'");

										break;

										case "O":

											$drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->nDrAccount  ."'");

										break;

										default:

											$item->nAcHead = $item->nDrAccount ;

											$drAccountName = "";

										break;

									}//end switch

							

									$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->nAcHead."'");

									?>				

							

									<?php if($drAccountName != '' || !empty($drAccountName)) { echo $drAccountName; }  

												else { echo $subAccName ; }												

									

								} ?>

								

								</td>

            </tr>

            <?php $i = $i + 1;

				$netAmt_per_itemgrp			= $netAmt_per_itemgrp+$item->netAmt;

				$totSalesAmt_per_itemgrp	= $totSalesAmt_per_itemgrp+$item->salesAmount;

				$totProfit_per_itemgrp		= $totProfit_per_itemgrp+$item->Profit;

				$totProfitPercent_per_itemgrp		= $totProfitPercent_per_itemgrp+$item->ProfitPercent; ?>

            <?php endforeach;

                $totProfitPercent_per_itemgrp = ($totProfit_per_itemgrp / $netAmt_per_itemgrp ) * 100;

            ?>

	<tr>

        <td align="right" colspan="7"><strong><?php echo GRANDTOTALAMT;?></strong></td>

		<td align="right"><?php echo $netAmt_per_itemgrp-$a;?></td>

		<td align="right"><?php echo $totSalesAmt_per_itemgrp;?></td>

		<td align="right"><?php echo $totProfit_per_itemgrp;?></td>

		<td align="right"><?php echo round( $totProfitPercent_per_itemgrp,2);  echo " %";?></td> 

		<td>&nbsp;</td>

    </tr>

	

	

	<?php }

    else{

        ?>

    <tr>

        <td style="border:0" colspan="17" align="center"><?php echo SORRYNORECORD;?></td>

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
        <td style="border:0" colspan="13">

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>

