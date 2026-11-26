<?php
ini_set('memory_limit', '128M');
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

            window.location.replace('<?php echo base_url().'index.php/monthly_info/showlist/'.$this->uri->segment(3);?>');

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

        <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/monthly_profit_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

        <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/monthly_profit_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

        <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/monthly_profit_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

    </div>

</div> <!--.content-box-export-->



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">

<thead>

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

        



    <tr>

        <th align="center" colspan="18">

            



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

            <td colspan="18" style="border:0" align="left"><strong><?php echo "MONTHLY REPORT";?></strong></td>

        </tr>

    

   

        <tr>
                <th colspan="3" align="left"><?php echo FROMDATE; echo "&nbsp"; echo ":"; echo "&nbsp"; echo $this->txtFromDate?></th>
                <!--<th align="left"><?php echo $fromDate;?></th>-->
                <th colspan="3" align="left"><?php echo TODATE; echo "&nbsp"; echo ":"; echo "&nbsp"; echo $this->txtToDate;?></th>
                <!--<th align="left"><?php echo $toDate;?></th>-->
                <th align="left" colspan="9">&nbsp;</th> 
            </tr>

   

    <tr><td style="border:0"></td></tr>

	
<style media="print">

  #bg{   -webkit-print-color-adjust:exact;
 background-color:#CCC !important; }
 
</style>
<style>
table { font-size: 9px; }
td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>



    <tr id="bg">

        <td height="20px" align="center"><?php echo "NAME";?></td>

      

        <td align="center"><?php echo JANUARY;?></td>

        <td align="center"><?php echo FEBRUARY;?></td>

        <td align="center"><?php echo MARCH;?></td>

        <td align="center"><?php echo APRIL;?></td>

        <td align="center"><?php echo MAY;?></td>

        <td align="center"><?php echo JUNE;?></td>

        <td align="center"><?php echo JULY;?></td>

        <td align="center"><?php echo AUGUST;?></td>

        <td align="center"><?php echo SEPTEMBER;?></td>

        <td align="center"><?php echo OCTOBER;?></td>

        <td align="center"><?php echo NOVEMBER;?></td>

        <td align="center"><?php echo DECEMBER;?></td>
		<td align="center"><?php echo "Total";?></td>
		<td align="center"><?php echo " % ";?></td>

    </tr>

    </thead>

    <tbody>



    <?php
	 $chkEmployee = array();

        foreach($list_value as $key => $item):





           if(!in_array($item->employee,$chkEmployee)):?>

                <?php if($j != 0):?>

                <tr>

                    <td height="20px" colspan="1" align="right"><strong></strong></td>


                     <td align="center"><?php echo number_format((float)$total1, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total2, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total3, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total4, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total5, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total6, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total7, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total8, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total9, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total10, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total11, 3, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total12, 3, ".", "");?></td>
				<td align="center"><?php
		$alt=$total1+$total2+$total3+$total4+$total5+$total6+$total7+$total8+$total9+$total10+$total11+$total12;

				echo number_format((float)$alt, 3, ".", "");?></td>
				<td align="center"><?php
				$per1=($netAmt_per_itemgrp+$totSalesAmt_r_per_itemgrp);
				$pert=(($totSalesAmt_per_itemgrp+$netAmt_r_per_itemgrp)-$netAmt_per_itemgrp-$totSalesAmt_r_per_itemgrp)/$per1*100;
				echo number_format((float)$pert, 2, ".", "");?></td>
                </tr>

                

                    <?php $flag = true; 



                        $incomeOpening = $totalOpening;

						$income1 = $income1+$total1;

						$income2 = $income2+$total2;

						$income3 = $income3+$total3;

						$income4 =$income4+ $total4;

						$income5 = $income5+$total5;

						$income6 = $income6+$total6;

						$income7 = $income7+$total7;

						$income8 = $income8+$total8;

						$income9 = $income9+$total9;

						$income10 = $income10+$total10;

						$income11 = $income11+$total11;

						$income12 = $income12+$total12;
						$talt+$alt+$alt;

						$netAmt_per_itemgrp			= 0;
							$totSalesAmt_per_itemgrp	= 0;
							$netAmt_r_per_itemgrp			= 0;
							$totSalesAmt_r_per_itemgrp	= 0;
							
                        $totalOpening = 0;

						$total1 = 0;

						$total2 = 0;

						$total3 = 0;

						$total4 = 0;

						$total5 = 0;

						$total6 = 0;

						$total7 = 0;

						$total8 = 0;

						$total9 = 0;

						$total10 = 0;

						$total11 = 0;

						$total12 = 0;

					endif;?>

                    



            <tr>


            </tr>

                <?php

                $j = 1;

            endif;
			$netAmt_per_itemgrp			= $netAmt_per_itemgrp+$item->netAmt;
				$totSalesAmt_per_itemgrp	= $totSalesAmt_per_itemgrp+$item->salesAmount;
				$netAmt_r_per_itemgrp			= $netAmt_r_per_itemgrp+($item->netAmt_r);
				$totSalesAmt_r_per_itemgrp	= $totSalesAmt_r_per_itemgrp+($item->salesAmount_r);
				$totProfit_per_itemgrp		= $totProfit_per_itemgrp+$profit;

            ?>



            <tbody>

                <?php

				$dateObj   = DateTime::createFromFormat('!m', $item->dDate);
				$profit=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r; 
				$monthName = $dateObj->format('F');
				// echo $item->employee.$monthName.$profit."<br>";
				 if($monthName=="January"){
					  $jan=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $jan=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="February"){
					  $feb=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $feb=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="March"){
					  $mar=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $mar=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="April"){
					  $apr=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $apr=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				  if($monthName=="May"){
					  $may=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $may=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="June"){
					  $jun=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $jun=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				  if($monthName=="July"){
					  $jul=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $jul=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="August"){
					  $aug=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $aug=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="September"){
					  $sep=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $sep=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 if($monthName=="October"){
					  $oct=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $oct=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				  if($monthName=="November"){
					  $nov=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $nov=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				  if($monthName=="December"){
					  $dec=0;
					  $item->netAmt_r=-$item->netAmt_r;
					  $item->salesAmount_r=-$item->salesAmount_r;
					 $dec=($item->salesAmount+$item->netAmt_r)-$item->netAmt-$item->salesAmount_r;
				 }
				 
           if(!in_array($item->employee,$chkEmployee)){?>
<tr>

                <td colspan="18"><strong><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$item->vIssuedBy."'"))." - ". stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));?></strong></td>

</tr><?php 
		   }

              //  if( $jan != 0 || $feb != 0 || $mar!=0 || $apr!=0 || $may!=0 || $jun!=0 || $jul!=0 || $aug!=0 || $sep!=0 || $oct!=0 || $nov!=0 || $dec!=0 ){

            ?>

            <tr style="display:none;">

                <td><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$item->vIssuedBy."'"));?></strong></td>


               



                <td align="center">

				<?php
				

					$total1 += $jan; 

				 	echo  number_format($jan,3);
					$jan=0;

				?>

               </td>

                 <td align="center">

				<?php

					$total2 += $feb; 

				 	echo number_format($feb,3); 
					$feb=0;

				?>

               </td>

                <td align="center">

				<?php

					$total3 += $mar; 

				 	echo number_format($mar,3); 
					$mar=0;

				?>

               </td>

                <td align="center">

				<?php

					$total4 += $apr; 

				 	echo number_format($apr,3); 
					$apr=0;

				?>

               </td>

                <td align="center">

				<?php

					$total5 += $may; 

				 	echo number_format($may,3); 
					$may=0;

				?>

               </td>

                <td align="center">

				<?php

					$total6 += $jun; 

				 	echo number_format($jun,3); 
					$jun=0; 

				?>

               </td>

                <td align="center">

				<?php

					$total7 += $jul; 

				 	echo number_format($jul,3);
					$jul=0;

				?>

               </td>

                <td align="center">

				<?php

					$total8 += $aug; 

				 	echo number_format($aug,3); 
					$aug=0;

				?>

               </td>

                <td align="center">

				<?php

					$total9 += $sep; 

				 	echo number_format($sep,3); 
					$sep=0;

				?>

               </td>

                <td align="center">

				<?php

					$total10 += $oct; 

				 	echo number_format($oct,3); 
					$oct=0;

				?>

               </td>

                <td align="center">

				<?php

					$total11 += $nov; 

				 	echo number_format($nov,3); 
					$nov=0;

				?>

               </td>

                <td align="center">

				<?php

					$total12 += $dec; 

				 	echo number_format($dec,3);
					$dec=0;					

				?>

               </td>
			   
			   <td align="center">

				<?php

					$alt += $jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec; 

				 	echo number_format($jan+$feb+$mar+$apr+$may+$jun+$jul+$aug+$sep+$oct+$nov+$dec,3); 

				?>

               </td>
			   <td align="center">

				<?php

					$total12 += $dec; 

				 	echo number_format($dec,3); 

				?>

               </td>

            </tr>

                <?php

             //   }

/*

                if($flag == true):

                    $totalPreviousLoop  = '';

                    $totalCurrentLoop   = '';

                    $flag               = false;

                endif;

*/

                $chkEmployee[] =$item->employee;
				$totSalesAmt=$totSalesAmt+$item->salesAmount;
            $netAmt         = $netAmt+$item->netAmt;
			$totSalesAmt_r=$totSalesAmt_r+($item->salesAmount_r);
            $netAmt_r         = $netAmt_r+($item->netAmt_r);
			$totProfit        = $totProfit+$profit;

/*

                $totalPrevious          += $getOpenValues['openDebit'];

                $totalCurrent           += $getValues['debit'];

                $totalPreviousLoop      += $getOpenValues['openDebit'];

                $totalCurrentLoop       += $getValues['debit'];

*/

           // endif;



            if(($key+1) == count($list_value)):?>

            <tr >

                <td height="20px" colspan="1" align="right"><strong></strong></td>

               

                <td align="center"><?php echo number_format($total1,3);?></td>

                <td align="center"><?php echo number_format($total2,3);?></td>

                <td align="center"><?php echo number_format($total3,3);?></td>

                <td align="center"><?php echo number_format($total4,3);?></td>

                <td align="center"><?php echo number_format($total5,3);?></td>

                <td align="center"><?php echo number_format($total6,3);?></td>

                <td align="center"><?php echo number_format($total7,3);?></td>

                <td align="center"><?php echo number_format($total8,3);?></td>

                <td align="center"><?php echo number_format($total9,3);?></td>

                <td align="center"><?php echo number_format($total10,3);?></td>

                <td align="center"><?php echo number_format($total11,3);?></td>

                <td align="center"><?php echo number_format($total12,3);?></td>
				<td align="center"><?php echo number_format($alt,3);?></td>
				<td align="center"><?php
				$per1=($netAmt_per_itemgrp+$totSalesAmt_r_per_itemgrp);
				$pert=(($totSalesAmt_per_itemgrp+$netAmt_r_per_itemgrp)-$netAmt_per_itemgrp-$totSalesAmt_r_per_itemgrp)/$per1*100;
				echo number_format((float)$pert, 2, ".", "");?></td>

            </tr>

                <?php endif;

        endforeach;

		?>

            

         <tr id="bg">

                <td height="20px" colspan="1" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

                <td align="center"><?php echo number_format($income1,3);?></td>

                <td align="center"><?php echo number_format($income2,3);?></td>

                <td align="center"><?php echo number_format($income3,3);?></td>

                <td align="center"><?php echo number_format($income4,3);?></td>

                <td align="center"><?php echo number_format($income5,3);?></td>

                <td align="center"><?php echo number_format($income6,3);?></td>

                <td align="center"><?php echo number_format($income7,3);?></td>

                <td align="center"><?php echo number_format($income8,3);?></td>

                <td align="center"><?php echo number_format($income9,3);?></td>

                <td align="center"><?php echo number_format($income10,3);?></td>

                <td align="center"><?php echo number_format($income11,3);?></td>

                <td align="center"><?php echo number_format($income12,3);?></td>
				<td align="center"><?php
				$al=$income1+$income2+$income3+$income4+$income5+$income6+$income7+$income8+$income9+$income10+$income11+$income12;
				echo number_format($al,3);?></td>
				<td align="center"><?php 
		$p=$netAmt+$totSalesAmt_r;
		//echo $netAmt."--".$totSalesAmt_r."--".$totProfit;
		$gp=($al/$p)*100;
		echo number_format((float)$gp, 2, ".", "");?></td>

            </tr>

        <?php

    }

    else{

        ?>

    <tr>

        <td colspan="16" align="center"><?php echo SORRYNORECORD;?></td>

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

