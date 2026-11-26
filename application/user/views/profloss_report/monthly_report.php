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

            window.location.replace('<?php echo base_url().'index.php/profloss_report/showlist/'.$this->uri->segment(3);?>');

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

            <td colspan="18" style="border:0" align="left"><strong><?php echo PLMONTHLYREPORT;?></strong></td>

        </tr>

    

   

        <tr>
                <th colspan="3" align="left"><?php echo FROMDATE; echo "&nbsp"; echo ":"; echo "&nbsp"; echo $fromDate;?></th>
                <!--<th align="left"><?php echo $fromDate;?></th>-->
                <th colspan="3" align="left"><?php echo TODATE; echo "&nbsp"; echo ":"; echo "&nbsp"; echo $toDate;?></th>
                <!--<th align="left"><?php echo $toDate;?></th>-->
                <!-- <th align="left" colspan="9">&nbsp;</th> -->
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

        <td height="20px" align="center"><?php echo ACCODE;?></td>

        <td colspan="2" align="center"><?php echo ACCCODENAME;?></td>

        <td align="center"><?php echo OPENING;?></td>

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

    </tr>

    </thead>

    <tbody>



    <?php

        foreach($list_value as $key => $item):



            $mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");

            if($mainId=='3'):

                $mainCode = '3';

                $mainName = 'Income';

            elseif($mainId=='4'):

                $mainCode = '4';

                $mainName = 'Expense'; 

            endif;



            if(!in_array($mainId,$chkGroupId)):?>

                <?php if($j != 0):?>

                <tr id="bg">

                    <td height="20px" colspan="3" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

                     <td align="center"><?php echo number_format((float)$totalOpening, 2, ".", "");?></td>

                     <td align="center"><?php echo number_format((float)$total1, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total2, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total3, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total4, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total5, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total6, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total7, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total8, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total9, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total10, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total11, 2, ".", "");?></td>

                <td align="center"><?php echo number_format((float)$total12, 2, ".", "");?></td>

                </tr>

                

                    <?php $flag = true; 



                        $incomeOpening = $totalOpening;

						$income1 = $total1;

						$income2 = $total2;

						$income3 = $total3;

						$income4 = $total4;

						$income5 = $total5;

						$income6 = $total6;

						$income7 = $total7;

						$income8 = $total8;

						$income9 = $total9;

						$income10 = $total10;

						$income11 = $total11;

						$income12 = $total12;



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

                <td align="left"><strong><?php echo $mainCode;?></strong></td>

                <td colspan="2" align="left"><strong><?php echo $mainName;?></strong></td>

                <td colspan="16" align="center">&nbsp;</td>

            </tr>

                <?php

                $j = 1;

            endif;

            ?>



            <tbody>

                <?php

            if(!in_array($item->AccountId,$chkAccountId)):

                $getOpenValues = $this->profloss_report_model->getOpeningBalForAcc($item->AccountId,'',''); 

				$mainId = $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nId='".$item->mainId."'");

                if($mainId=='3') 

				{

                $jan = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-01')))); 

                $feb = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-02'))));

                $mar = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-03'))));

                $apr = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-04'))));

                $may = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-05'))));

                $jun = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-06'))));

                $jul = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-07'))));

                $aug = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-08'))));

                $sep = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-09'))));

                $oct = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-10'))));

                $nov = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-11'))));

                $dec = $this->profloss_report_model->getMonthlyAccValues($item->AccountId,date('Y-m',strtotime(date('Y-12'))));

				}

				else{

				$jan = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-01')))); 

                $feb = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-02'))));

                $mar = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-03'))));

                $apr = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-04'))));

                $may = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-05'))));

                $jun = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-06'))));

                $jul = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-07'))));

                $aug = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-08'))));

                $sep = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-09'))));

                $oct = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-10'))));

                $nov = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-11'))));

                $dec = $this->profloss_report_model->getMonthlyAccValues1($item->AccountId,date('Y-m',strtotime(date('Y-12'))));

					

				}

                if($getOpenValues['openDebit'] != 0 || $jan != 0 || $feb != 0 || $mar!=0 || $apr!=0 || $may!=0 || $jun!=0 || $jul!=0 || $aug!=0 || $sep!=0 || $oct!=0 || $nov!=0 || $dec!=0 ){

            ?>

            <tr>

                <td><?php echo $item->AccountCode;?></td>

                <td colspan="2"><?php echo $item->AccountName;?></td>
				<?php if($mainId==3){
					$getOpenValues['openDebit']=-$getOpenValues['openDebit'];
				}else{
					$getOpenValues['openDebit']=$getOpenValues['openDebit'];
				}?>
                <td align="center"><?php $totalOpening += $getOpenValues['openDebit'];

                          echo number_format($getOpenValues['openDebit'],2);?>

              </td>



                <td align="center">

				<?php

					$total1 += $jan; 

				 	echo number_format($jan,2);

				?>

               </td>

                 <td align="center">

				<?php

					$total2 += $feb; 

				 	echo number_format($feb,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total3 += $mar; 

				 	echo number_format($mar,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total4 += $apr; 

				 	echo number_format($apr,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total5 += $may; 

				 	echo number_format($may,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total6 += $jun; 

				 	echo number_format($jun,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total7 += $jul; 

				 	echo number_format($jul,2);

				?>

               </td>

                <td align="center">

				<?php

					$total8 += $aug; 

				 	echo number_format($aug,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total9 += $sep; 

				 	echo number_format($sep,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total10 += $oct; 

				 	echo number_format($oct,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total11 += $nov; 

				 	echo number_format($nov,2); 

				?>

               </td>

                <td align="center">

				<?php

					$total12 += $dec; 

				 	echo number_format($dec,2); 

				?>

               </td>

            </tr>

                <?php

                }

/*

                if($flag == true):

                    $totalPreviousLoop  = '';

                    $totalCurrentLoop   = '';

                    $flag               = false;

                endif;

*/

                $chkGroupId[]            = $mainId;

                $chkHeadId[]             = $item->HeadId;

                $chkAccountId[]          = $item->AccountId;

/*

                $totalPrevious          += $getOpenValues['openDebit'];

                $totalCurrent           += $getValues['debit'];

                $totalPreviousLoop      += $getOpenValues['openDebit'];

                $totalCurrentLoop       += $getValues['debit'];

*/

            endif;



            if(($key+1) == count($list_value)):?>

            <tr id="bg">

                <td height="20px" colspan="3" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

                <td align="center"><?php echo number_format($totalOpening,2);?></td>

                <td align="center"><?php echo number_format($total1,2);?></td>

                <td align="center"><?php echo number_format($total2,2);?></td>

                <td align="center"><?php echo number_format($total3,2);?></td>

                <td align="center"><?php echo number_format($total4,2);?></td>

                <td align="center"><?php echo number_format($total5,2);?></td>

                <td align="center"><?php echo number_format($total6,2);?></td>

                <td align="center"><?php echo number_format($total7,2);?></td>

                <td align="center"><?php echo number_format($total8,2);?></td>

                <td align="center"><?php echo number_format($total9,2);?></td>

                <td align="center"><?php echo number_format($total10,2);?></td>

                <td align="center"><?php echo number_format($total11,2);?></td>

                <td align="center"><?php echo number_format($total12,2);?></td>

            </tr>

                <?php endif;

        endforeach;

		?>

            

         <tr id="bg">

                <td height="20px" colspan="3" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

                <td align="center"><?php echo number_format($incomeOpening - $totalOpening,2);?></td>

                <td align="center"><?php echo number_format($income1 - ($total1),2);?></td>

                <td align="center"><?php echo number_format($income2 - ($total2),2);?></td>

                <td align="center"><?php echo number_format($income3 - ($total3),2);?></td>

                <td align="center"><?php echo number_format($income4 - ($total4),2);?></td>

                <td align="center"><?php echo number_format($income5 - ($total5),2);?></td>

                <td align="center"><?php echo number_format($income6 - ($total6),2);?></td>

                <td align="center"><?php echo number_format($income7 - ($total7),2);?></td>

                <td align="center"><?php echo number_format($income8 - ($total8),2);?></td>

                <td align="center"><?php echo number_format($income9 - ($total9),2);?></td>

                <td align="center"><?php echo number_format($income10 - ($total10),2);?></td>

                <td align="center"><?php echo number_format($income11 -($total11),2);?></td>

                <td align="center"><?php echo number_format($income12 - ($total12),2);?></td>

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

