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

            window.location.replace('<?php echo base_url() . 'index.php/monthly_info/showlist/' . $this->uri->segment(3);?>');

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

    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/employee_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/employee_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/employee_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

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
        <td colspan="20" style="border:0" height="25px" align="left">&nbsp;<strong><b> &nbsp; Employee Monthly Report </b></strong></td>
    </tr>
	<tr>
        <th colspan="0" align="right"><?php echo FROMDATE;?></th>
        <th colspan="1" align="left"><?php echo $this->txtFromDate;?></th>
        <th colspan="1" align="right"><?php echo TODATE;?></th>
        <th colspan="4" align="left"><?php echo $this->txtToDate;?></th>
        
    </tr>
	<style media="print">
  #bg{   
 -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
table {  
display:table-cell !important;}
</style>
<style>
table {  
width:100% !important;
font-size: 9px; }
td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>
<?php 
$echo="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
$echo1="&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;";
?>
    <tr id="bg">

        <!--<th align="center">#</th>-->
       <th align="left"><?php echo $echo;echo NAME; echo $echo;?></th>
       
        <th align="center"><?php echo NETAMT;?></th>
		<th align="center"><?php echo SALESAMOUNT;?></th>
		<th align="center"><?php echo "Net Refund";?></th>
		<th align="center"><?php echo "Paid Amt";?></th>
		<th align="center"><?php echo PROFIT;?></th>
		<th align="center"><?php echo PROFIT; echo"%";?></th>
				


    </tr>

    </thead>

    <tbody>



    <?php
	

    if(!empty($list_value)){
			ini_set('memory_limit', '-1');
	$sortArray = array(); 

/*foreach($list_value as $person){ 
    foreach($person as $key=>$value){ 
        if(!isset($sortArray[$key])){ 
            $sortArray[$key] = array(); 
        } 
        $sortArray[$key][] = $value; 
    } 
} */

//$orderby = "employee"; //change this to whatever key you want from the array 
//array_multisort($sortArray[$orderby],SORT_ASC,$list_value); 


        $i= 1;

        $chkEmployee = array();
		$heading_refund_type = '';

        foreach($list_value as $key=>$item): 
		if($item->employee!=''){
		$issue='';
		$issue=$this->common_model->get_field_name('SalesClosingDetails','issue'," WHERE nSalesCloseId='".$item->nSalesCloseId."'");
			
			if(!in_array($item->employee,$chkEmployee)):
				$heading_refund_type = '';

				if( $i !=1 ):
					?>
					<tr id="bg">
					<td align="right" colspan="1"><strong><?php echo TOTALAMT;?></strong></td>
					<td align="right"><?php echo number_format((float)$netAmt_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totSalesAmt_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt_r_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totSalesAmt_r_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php
				
				echo number_format((float)($totSalesAmt_per_itemgrp+$netAmt_r_per_itemgrp)-$netAmt_per_itemgrp-$totSalesAmt_r_per_itemgrp, 3, ".", "");?></td>
			<td align="right"><?php
				$per1=($netAmt_per_itemgrp+$totSalesAmt_r_per_itemgrp);
				$pert=(($totSalesAmt_per_itemgrp+$netAmt_r_per_itemgrp)-$netAmt_per_itemgrp-$totSalesAmt_r_per_itemgrp)/$per1*100;
				echo number_format((float)$pert, 2, ".", "");?></td>
			
													
		
					<?php
							
							$netAmt_per_itemgrp			= 0;
							$totSalesAmt_per_itemgrp	= 0;
							$netAmt_r_per_itemgrp			= 0;
							$totSalesAmt_r_per_itemgrp	= 0;
							$totProfit_per_itemgrp=0;
							$profit=0;

		
					?>
					
					</tr>
				<?php endif; ?>


                <tr id="bg">

                   <td colspan="4"><strong><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$item->vIssuedBy."'"));?></strong></td>

         

            <?php endif;
				
				$netAmt_per_itemgrp			= $netAmt_per_itemgrp+$item->netAmt;
				$totSalesAmt_per_itemgrp	= $totSalesAmt_per_itemgrp+$item->salesAmount;
				$netAmt_r_per_itemgrp			= $netAmt_r_per_itemgrp+(-$item->netAmt_r);
				$totSalesAmt_r_per_itemgrp	= $totSalesAmt_r_per_itemgrp+-($item->salesAmount_r);
				$totProfit_per_itemgrp		= $totProfit_per_itemgrp+$profit;
				

			?>


		
				<?php
					if( $heading_refund_type != $item->refundtype) {
						?>
							 
						<?php
					}
				?>
			</tr>
			
            <tr>


                <!--<td align="center"><?php echo $this->common_model->_offset + $i;?></td>-->
                <td align="left"><?php 
				
				$dateObj   = DateTime::createFromFormat('!m', $item->dDate);
				echo $monthName = $dateObj->format('F');?></td>
                

				<?php 		
				
				?>
                <td align="right"><?php echo stripslashes(number_format($item->netAmt,3));?></td>
                <td align="right"><?php echo stripslashes(number_format($item->salesAmount,3));?></td>
				<td align="right"><?php echo stripslashes(number_format(-$item->netAmt_r,3));$item->netAmt_r=-$item->netAmt_r;?></td>
                <td align="right"><?php echo stripslashes(number_format(-$item->salesAmount_r,3)); $item->salesAmount_r=-$item->salesAmount_r;?></td>
				<td align="right"><?php $profit=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r; echo stripslashes($profit);?></td>
            				
				<td align="right"><?php $prof=$item->netAmt+$item->salesAmount_r; $per=($profit/$prof)*100; echo stripslashes(number_format($per,2));?></td>
            	

				

            </tr>

            <?php $i = $i + 1;

            $chkEmployee[] =$item->employee;

            
			$totSalesAmt=$totSalesAmt+$item->salesAmount;
            $netAmt         = $netAmt+$item->netAmt;
			$totSalesAmt_r=$totSalesAmt_r+($item->salesAmount_r);
            $netAmt_r         = $netAmt_r+($item->netAmt_r);
			$totProfit        = $totProfit+$profit;

            ?>

		<?php } endforeach;?>
		<tr id="bg" >
			<td align="right" colspan="1"><strong><?php echo TOTALAMT;?></strong></td>
			
				<td align="right"><?php echo number_format((float)$netAmt_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totSalesAmt_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$netAmt_r_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)$totSalesAmt_r_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php echo number_format((float)($totSalesAmt_per_itemgrp+$netAmt_r_per_itemgrp)-$netAmt_per_itemgrp-$totSalesAmt_r_per_itemgrp, 3, ".", "");?></td>
				<td align="right"><?php
				$per1=($netAmt_per_itemgrp+$totSalesAmt_r_per_itemgrp);
				$pert=(($totSalesAmt_per_itemgrp+$netAmt_r_per_itemgrp)-$netAmt_per_itemgrp-$totSalesAmt_r_per_itemgrp)/$per1*100;
				echo number_format((float)$pert, 2, ".", "");?></td>

		</tr>
		

    <tr id="bg">

        <td align="right" colspan="1"><strong><?php echo GRANDTOTALAMT;?></strong></td>
      
            <td align="right"><?php echo number_format((float)$netAmt, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totSalesAmt, 3, ".", "");?></td>
		<td align="right"><?php echo number_format((float)$netAmt_r, 3, ".", "");?></td>
        <td align="right"><?php echo number_format((float)$totSalesAmt_r, 3, ".", "");?></td>
		<td align="right"><?php echo number_format((float)$totProfit, 3, ".", "");?></td>		
		<td align="right"><?php 
		$p=$netAmt+$totSalesAmt_r;
		$gp=($totProfit/$p)*100;
		echo number_format((float)$gp, 2, ".", "");?></td>		
      


    </tr>

    <?php }

    else{

        ?>

    <tr> 
        <td style="border:0" colspan="20" align="center"><?php echo SORRYNORECORD;?></td> 
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
                <td style="border:0" colspan="20"> 

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>