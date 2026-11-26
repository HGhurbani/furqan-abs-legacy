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
<title>Print Agent Copy</title>
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
                        <?php echo PHONE;?>: <?php echo $phone;?>
                        <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                        <?php echo EMAIL;?>: <?php echo $email;?><br>
                        <?php echo WEB;?>: <?php echo $web;?>
                    </div>
                </td>

    <td colspan="2">
        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTAGENTPAGE;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

    </td>
</tr>
<tr>
      <td colspan="3">
          &nbsp;
      </td>
</tr>
<tr>
      <td colspan="3">
          <span style="font-size: 14px;font-weight: bold;"><?php echo SERVICEVOUCHER;?></span>
      </td>
</tr>
<tr>
    <td><?php echo VOUCHERID;?></td>
    <td>
        <div class="smallBox"><?php echo $this->txtTktName;?></div>

    </td>

    <td><?php echo CUSTDATE;?></td>
    <td><label>
        <div class="smallBox"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div>

    </label></td>

    <td><?php echo SERVICES;?></td>
    <td><label>
        <div class="smallBox"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
            <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
            <?php echo stripslashes($this->txtExtraServ);?></div>

    </label></td>

</tr>
<tr>
    <td><?php echo HOTELREF;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtHotelConfId);?></div></td>

    <td><?php echo AGENTREF;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtAgentConfId);?></div></td>

    <td><?php echo TOURFILE;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtBookingId);?></div></td>
</tr>

<tr>
    <td><?php echo LEADNAMES;?></td>
    <td colspan="5"><div class="smallBox"> <?php echo stripslashes($this->txtPassenger);?>  </div></td>
</tr>

<tr>
    <td><?php echo TOBEPRESENTED;?></td>
     <td colspan="5"><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?> </div></td>
</tr>

<tr>
    <td><?php echo COUNTRYNAME;?></td>
    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>

    <td><?php echo CITYNAME;?></td>
    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></div></td>
</tr>

<tr>
    <td><?php echo ADDRESS;?></td>
    <td>
        <div class="smallBox">
            <?php echo $this->common_model->get_field_name('Hotel','vAddress'," WHERE nHId='".$this->ddlHotel."'");?>
        </div>
    </td>

    <td><?php echo PHONE;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>

    <td><?php echo EMAIL;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>
</tr>

<tr>
    <td><?php echo REMARKS;?></td>
    <td colspan="5"><div class="smallBox"><label>
        <?php echo stripslashes($this->txtRemarks);?>
    </label></div></td>
</tr>

<tr>
    <td colspan="6">
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
<!--
            <tr>
                <td><?php echo SERVICES;?></td>
                <td colspan="5"><label>
                    <?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
                    <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
                    <?php echo stripslashes($this->txtExtraServ);?>
                </label></td>
            </tr>
-->
        </table>
    </td>
</tr>

<!--
<tr>
    <td><?php echo NOTES;?></td>
    <td colspan="5"><div class="bigBox"></div></td>
</tr>
-->
<tr>
<!--    <td><?php echo BOOKEDBY;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlBooked."'");?></div></td>
-->  

    <td colspan="4"></td>
    <td colspan="2"><div class="bigBox">
        <?php echo ISSUEDBY;?> : <br>
        <?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?><br>
        <?php echo STAMPANDSIGN;?>
    </div></td>

</tr>


</tbody>
</table>




<hr>





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
                        <?php echo PHONE;?>: <?php echo $phone;?>
                        <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                        <?php echo EMAIL;?>: <?php echo $email;?><br>
                        <?php echo WEB;?>: <?php echo $web;?>
                    </div>
                </td>

    <td colspan="2">
      
    </td>
</tr>

<tr>
      <td colspan="3">
          <span style="font-size: 14px;font-weight: bold;"><?php echo SERVICEVOUCHER;?></span>
      </td>
</tr>

<tr>
    <td><?php echo VOUCHERID;?></td>
    <td>
        <div class="smallBox"><?php echo $this->txtTktName;?></div>

    </td>

    <td><?php echo CUSTDATE;?></td>
    <td><label>
        <div class="smallBox"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div>

    </label></td>

    <td><?php echo SERVICES;?></td>
    <td><label>
        <div class="smallBox"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
            <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
            <?php echo stripslashes($this->txtExtraServ);?></div>

    </label></td>

</tr>
<tr>
    <td><?php echo HOTELREF;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtHotelConfId);?></div></td>

    <td><?php echo AGENTREF;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtAgentConfId);?></div></td>

    <td><?php echo TOURFILE;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtBookingId);?></div></td>
</tr>

<tr>
    <td><?php echo LEADNAMES;?></td>
    <td colspan="5"><div class="smallBox"> <?php echo stripslashes($this->txtPassenger);?>  </div></td>
</tr>

<tr>
    <td><?php echo TOBEPRESENTED;?></td>
     <td colspan="5"><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?> </div></td>
</tr>

<tr>
    <td><?php echo COUNTRYNAME;?></td>
    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>

    <td><?php echo CITYNAME;?></td>
    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></div></td>
</tr>

<tr>
    <td><?php echo ADDRESS;?></td>
    <td>
        <div class="smallBox">
            <?php echo $this->common_model->get_field_name('Hotel','vAddress'," WHERE nHId='".$this->ddlHotel."'");?>
        </div>
    </td>

    <td><?php echo PHONE;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>

    <td><?php echo EMAIL;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>
</tr>

<tr>
    <td><?php echo REMARKS;?></td>
    <td colspan="5"><div class="smallBox"><label>
        <?php echo stripslashes($this->txtRemarks);?>
    </label></div></td>
</tr>

<tr>
    <td colspan="6">
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
<!--
            <tr>
                <td><?php echo SERVICES;?></td>
                <td colspan="5"><label>
                    <?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
                    <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
                    <?php echo stripslashes($this->txtExtraServ);?>
                </label></td>
            </tr>
-->
        </table>
    </td>
</tr>

<!--
<tr>
    <td><?php echo NOTES;?></td>
    <td colspan="5"><div class="bigBox"></div></td>
</tr>
-->
<tr>
<!--    <td><?php echo BOOKEDBY;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlBooked."'");?></div></td>
-->  

    <td colspan="4"></td>
    <td colspan="2"><div class="bigBox">
        <?php echo ISSUEDBY;?> : <br>
        <?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?><br>
        <?php echo STAMPANDSIGN;?>
    </div></td>

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
