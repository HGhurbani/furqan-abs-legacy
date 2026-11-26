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

<!DOCTYPE html>

<head>

<title>Print Accounts Copy</title>

                <link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">

                <link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">

                <link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">

                </head>

                <body>

<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />

<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>



<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>

<script>

$(document).ready(function() {



    $('#toPopup4').hide();

    $('#email').click(function(e) {

        $('#toPopup4').show();

    });



    $('#btnPrint').click(function(e) {

        window.print() ;

    });

});

</script>

<?php if($this->uri->segment(4) == 'U') { ?>

<span class="warning"> <?php echo "Failed To Send Email"; ?></span>

<?php } ?>



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">

<tbody>

        <?php

            $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");

            $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");

            $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");

            $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");

            $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");

            $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");

            $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");

            $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");

            $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");

            ?>

            <tr>

                <td colspan="8">

                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">

                    <div class="print_desc">

                    <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>

                        <?php echo $addr;?><br>

                        <?php echo PHONE;?>: <?php echo $phone;?>,

                        <?php echo FAX;?>: <?php echo $mobile;?><br>

                        <?php echo EMAIL;?>: <?php echo $email;?>,

                        <?php echo WEB;?>: <?php echo $web;?>

                    </div>

                </td>



    <td colspan="2">

        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTACCOUNTPAGE;?>" class="btnGeneral">

<br><br>



    </td>

</tr>

      <td colspan="3">&nbsp;

          

      </td>

<tr>

      <td colspan="3">

          <span style="font-size: 14px;font-weight: bold;"><?php echo SERVICEVOUCHER;?></span>

      </td>

</tr>



<tr>

    <td><?php echo VOUCHERID;?></td>

    <td colspan="1">

        <div class="smallBox"><?php echo $this->txtTktName;?></div>



    </td>





    <td><?php echo CUSTDATE;?></td>

    <td><label>

        <div class="smallBox"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div>

    </label></td>



    <td><?php echo SERVICES;?></td>

    <td><label>

        <div class="smallBox"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?></div>

    </label></td>



</tr>

<tr>

    <td><?php echo HOTELREF;?></td>

    <td><div class="smallBox"><?php echo stripslashes($this->txtHotelConfId);?></div></td>



    <td><?php echo AGENTREF;?></td>

    <td><div class="smallBox"><?php echo stripslashes($this->txtAgentConfId);?></div></td>



<td><?php echo TRANSFER;?></td>

    <td><label>

	<div class="smallBox"><?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?></div>

</tr>



<tr>

    <td><?php echo LEADNAMES;?></td>

    <td colspan="6"><div class="smallBox"> <?php echo stripslashes($this->txtPassenger);?></div></td>

</tr>

<tr>

    <td><?php echo TOBEPRESENTED;?></td>

     <td colspan="6"><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?></div></td>

</tr>



<tr>

    <td><?php echo COUNTRYNAME;?></td>

    <td colspan="6"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>

</tr>

<tr>

    <td><?php echo CITYNAME;?></td>

    <td colspan="6"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></div></td>

</tr>



<tr>

    <td><?php echo ADDRESS;?></td>

    <td colspan="6">

        <div class="smallBox">

            <?php echo $this->common_model->get_field_name('Hotel','vAddress'," WHERE nHId='".$this->ddlHotel."'");?>

        </div>

    </td></tr>

<tr>

    <td><?php echo PHONE;?></td>

    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>



    <td><?php echo EMAIL;?></td>

    <td colspan="3"><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>

</tr>



<tr>

    <td colspan="12">

        <table width="100%" class="reportForm">

            <tr>

                <td><?php echo CHECKIN;?></td>

                <td><?php echo CHECKOUT;?></td>

                <td><?php echo NIGHTS;?></td>

                <td><?php echo ADULT;?></td>

                <td><?php echo CHILD;?></td>

                <td><?php echo ROOMTYPE;?></td>

                <td><?php echo NOOFROOMS;?></td>

            </tr>



            <tr>

                <td><label>

                    <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckIn)));?>

                </label></td>



                <td><label>

                    <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckOut)));?>

                </label></td>



                <td><label>

                    <?php echo stripslashes($this->txtNights);?>

                </label></td>



                <td><label>

                    <?php echo stripslashes($this->txtAdult);?>

                </label></td>



                <td><label>

                    <?php echo stripslashes($this->txtChild);?>

                </label></td>



                <td><label>

                    <?php echo stripslashes($this->txtRoomType);?>

                </label></td>



                <td><label>

                    <?php echo stripslashes($this->txtNoOfUnit);?>

                </label></td>

            </tr>

        </table>

    </td>

</tr>



<tr>

    <td><?php echo REMARKS;?></td>

    <td colspan="6"><div class="smallBox"><label>

<?php echo stripslashes($this->txtExtraServ);?>,

<?php echo stripslashes($this->txtRemarks);?>

    </label></div></td>

</tr>

<tr>

    <td><?php echo AGENTAC;?></td>

     <td colspan="6"><div class="smallBox"><?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$this->ddlAcCode."' ");?></div></td>



</tr>





<tr>

    <td colspan="12">

        <table class="reportForm" width="100%">



            <?php

            if(!empty($txtPayRoomType))

            {

                ?>

                <tr>

                    <td><?php echo ROOMTYPE;?></td>

                    <td ><?php echo INDATE;?></td>

                    <td><?php echo OUTDATE;?></td>

                    <td><?php echo DAYS;?></td>

                    <td><?php echo NOOFROOMS;?></td>

                    <td><?php echo RATENETFC;?></td>

                    <td><?php echo TOTALINFC;?></td>

                    <td><?php echo RATENETDC;?></td>

                    <td><?php echo TOTALINDC;?></td>

                    <td><?php echo EXCHRATE;?></td>

                    <td><?php echo CURRENCY;?></td>

                </tr>



                <?php

                $currency_name =  $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId=" . stripslashes($this->ddlCurrency));

                foreach($txtPayRoomType as $key => $aitem):

                    ?>

                    <tr>

                        <td><?php echo stripslashes($txtPayRoomType[$key]);?></td>

                        <td ><?php echo stripslashes(date('d-m-Y',strtotime($txtChkinDate[$key])));?></td>

                        <td ><?php echo stripslashes(date('d-m-Y',strtotime($txtChkoutDate[$key])));?></td>

                        <td><?php echo stripslashes($txtNight[$key]);?></td>

                        <td><?php echo stripslashes($txtNoofRooms[$key]);?></td>

                        <td><?php echo number_format($txtRateNetFc[$key],3);?></td>

                        <td><?php echo number_format($txtTotalinFc[$key],3);?></td>

                        <td><?php echo stripslashes($txtRateNetDc[$key]);?></td>

                        <td><?php echo stripslashes($txtTotalinDc[$key]);?></td>

                        <td><?php echo stripslashes($txtExchangeRate[$key]);?></td>

                        <td><?php echo $currency_name; ?></td>

                    </tr>

                    <?php

                endforeach; ?>



                <tr>

                    <td colspan="2">&nbsp;</td>

                    <td colspan="3" align="right"><?php echo TOTALAMT;?></td>

                    <td><?php echo number_format($this->txtRateNetFcTotal,3);?></td>

                    <td><?php echo number_format($this->txtTotalFc,3);?></td>

                    <td><?php echo $this->txtRateNetDcTotal;?></td>

                    <td><?php echo $this->txtTotalDc;?></td>

                    

                </tr>



                <?php

            }//end if

            ?>

        </table>

    </td>

</tr>



<tr>

    <td><?php echo CUSTACCNAME;?></td>

    <td colspan="6"><div class="smallBox"><?php echo $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlCustomerAc ."'");?></div></td>

</tr>

<tr>

    <td><?php echo SALESAMT;?></td>

    <td><label>

        <div class="smallBox"><?php echo stripslashes($this->txtCollected);?></div>

    </label></td>

<td><?php echo SERVICEAMT;?></td>

    <td><label>

        <div class="smallBox"><?php echo stripslashes($this->txtServiceAmt);?></div>

    </label></td>

    <td><?php echo SERVPERCENT;?></td>

    <td><label>

        <div class="smallBox"><?php echo stripslashes($this->txtServicePerc) . "%";?></div>

    </label></td>

</tr>





<tr>

    <td><?php echo BOOKEDBY;?></td>

    <td colspan="6"><div class="smallBox"><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlBooked."'");?></div></td>

    <td colspan="2"></td>

    

</tr>

<tr>

    <td>&nbsp;</td>

</tr>



</tbody>

</table>



<!-- Start of 4 Sticky Note -->



<div id="toPopup4" class="toPopup">

    <div class="close"></div>

    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

    <div id="popup_content">

        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('hotel_voucher/show_emailDetails',$data);?>

    </div>

<!-- End of Sticky Note -->

</div>



</body>

</html>

