<?php
error_reporting(0);
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

            window.location.replace('<?php echo base_url() . 'index.php/profit_report/showlist/' . $this->uri->segment(3);?>');

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

    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/sector_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/sector_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/sector_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

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

        <th align="left">#</th>

        <th align="left"><?php echo CUSTDATE;?></th>

        <th align="left"><?php echo LOCATIONNAME;?></th>

<!--        <th align="left"><?php echo AIRPNR;?></th> -->

        <th align="left"><?php echo AIRCODE;?></th>

        <th align="left"><?php echo TICKETNAME;?></th>

        <th align="left"><?php echo ISSUEDBY;?></th>

        <th align="left"><?php echo PASSENGERNAME;?></th>

        <th align="left"><?php echo SECTOR;?></th>

        <th align="left"><?php echo FARE;?></th>

        <th align="left"><?php echo TAX;?></th>

        <th align="left"><?php echo COMMAMT;?></th>

        <th align="left"><?php echo NETAMT;?></th>

        <th align="left"><?php echo PROFIT;?></th>

        <th align="left"><?php echo PROFITPERCENTAGE;?></th>

<!--        <th align="left"><?php echo STATUS;?></th> -->

    </tr>

    </thead>

    <tbody>



    <?php

    if(!empty($list_value)){

        $i= 1;

        foreach($list_value as $key=>$item):



            switch($item->sale_type)

            {

                case "Ticket":

                    $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$item->tableId;

                    break;



                case "Xo":

                    $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$item->tableId;

                    break;



                case "Hotel":

                    $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$item->tableId;

                    break;

            }//end switch



            $profit           = $item->netAmt-$item->salesAmt;

            if($profit >0 && $item->netAmt >0)

                $profitPercentage = round((($profit*100)/$item->netAmt),2);

            else

                $profitPercentage = 0;

            ?>

            <tr>

                <td align="left"><?php echo $this->common_model->_offset + $i;?></td>

                <td align="left"><?php echo stripslashes($item->dDate);?></td>

                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'"));?></td>

<!--                <td align="left"><?php echo stripslashes($item->vAirpnr);?></td> -->

                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$item->airline."'"));?></td>

                <td align="left"><a href="<?php echo $path;?>"><?php echo stripslashes($item->tktNumber);?></a></td>

                <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vName', " WHERE nEId='".$item->vIssuedBy."'"));?></td>

                <td align="left"><?php echo stripslashes($item->vPassenger);?></td>

                <td align="left"><?php echo stripslashes($item->sector);?></td>

                <td align="left"><?php echo stripslashes($item->fareVal);?></td>

                <td align="left"><?php echo stripslashes($item->taxTotal);?></td>

                <td align="left"><?php echo stripslashes($item->cmtAmount);?></td>

                <td align="left"><?php echo stripslashes($item->netAmt);?></td>

                <td align="left"><?php echo stripslashes($profit);?></td>

                <td align="left"><?php echo stripslashes($profitPercentage);?></td>

<!--                <td align="left"><?php echo stripslashes($item->vStatus);?></td> -->

            </tr>

            <?php $i = $i + 1;

            $fareVal        = $fareVal+$item->fareVal;

            $taxTotal       = $taxTotal+$item->taxTotal;

            $cmtAmount      = $cmtAmount+$item->cmtAmount;

            $netAmt         = $netAmt+$item->netAmt;

            $totProfit      = $totProfit+$profit;

            $totProfitPer   = $totProfitPer+$profitPercentage;

        endforeach;?>

        <tr>

            <td align="right" colspan="9"><strong><?php echo TOTALAMT;?></strong></td>

            <td align="left"><?php echo $fareVal;?></td>

            <td align="left"><?php echo $taxTotal;?></td>

            <td align="left"><?php echo $cmtAmount;?></td>

            <td align="left"><?php echo $netAmt;?></td>

            <td align="left"><?php echo $totProfit;?></td>

            <td align="left"><?php echo $totProfitPer;?></td>

            <td align="left">&nbsp;</td>

        </tr>

    <?php }

    else{

        ?>

    <tr>

        <td colspan="7" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

        <?php

    }//end if

    ?>

    <tr class="formButs">

        <td>&nbsp;</td>

        <td colspan="15">

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>