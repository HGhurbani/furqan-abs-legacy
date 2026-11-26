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
	$('#email').hide();
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
        <?php /*
            $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");
            $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");
            $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");
            $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");
            $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");
            $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");
            $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");
            $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");
            $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");
         */  ?>

<!--            <tr>
                <td colspan="2">
                <table width="100%" border="0" cellspacing="10" cellpadding="10">
                <tr><td>                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">
                </td><td>
                <div class="print_desc">
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
                </div>
                </td></tr></table>
                </td>
    <td colspan="2">
        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTAGENTPAGE;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

    </td>


</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr> -->


<tr>

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
                <td colspan="4">
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
</tr<tr>
    <td colspan="2">
        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTPAGE;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
      <td colspan="3">
          <span style="font-size: 14px;font-weight: bold;"><?php echo TRAVELLINGITINERARY;?></span>
      </td>
</tr>

<tr>
    <td colspan="3">
        <table>
            <tr>

                  <td><strong><?php echo CUSDATE;?></strong></td>
                  <td>
                      <div class="smallBox">
                          <?php echo $this->txtDate; ?>
                      </div>
                  </td>
                <td><strong><?php echo GDS;?></strong></td>
                <td>
                    <div class="smallBox">
                        <?php echo $this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$this->ddlGDS."'");?>
                    </div>

           </td>

                <td><strong><?php echo REFERENCE;?></strong></td>
                <td width="100px">
                    <div class="smallBox">

                        <?php echo stripslashes($this->txtGdsPNR);?>
                    </div>
                </td>
            </tr>

            <tr>
                <td colspan="4"><?php echo TEXTDISPLAY;?></td>
            </tr>

            <tr>
                <td><strong><?php echo TRAVELLER;?></strong></td>
                <td><label>
                    <?php echo stripslashes($this->txtPassenger);?>
                </label></td>

                <td><strong><?php echo TICKETNAME;?></strong></td>
                <td>
                    <?php echo $this->common_model->get_field_name('Airline','vAirlineNo'," WHERE nArId='".$this->ddlAirline."'").'-'.$this->txtTktName;?>
                </td>
            </tr>
        </table>
    </td>
</tr>
<tr>
    <td colspan="3">
        <table width="100%" class="reportForm">

            <tr>
                <td><?php echo FLIGHTNO;?></td>
                <td><?php echo TKTCLASS;?></td>
                <td><?php echo DEPARTUREDATE;?></td>
                <td><?php echo FROM;?></td>
                <td><?php echo DEPARTURETIME;?></td>
                <td><?php echo TO;?></td>
                <td><?php echo ARRIVALTIME;?></td>
                <td><?php echo FLIGHTDURATION;?></td>
                <td><?php echo BOOKINGSTATUS;?></td>
                <td><?php echo AIRLINEBOOKINGREFERENCE;?></td>
            </tr>

            <?php if($this->txtFlightNo1 !='') { ?>
            <tr>
                <td><?php echo stripslashes($this->txtFlightNo1);?></td>
                <td><?php echo stripslashes($this->txtClass1);?></td>
                <td><?php echo stripslashes($this->txtTravelDate1);?></td>
                <td><?php echo stripslashes($this->txtSector1);?></td>
                <td><?php echo stripslashes($this->txtTimeDep1);?></td>
                <td><?php echo stripslashes($this->txtSector12);?></td>
                <td><?php echo stripslashes($this->txtTimeArr1);?></td>
                <td>&nbsp;</td>
                <td><?php echo stripslashes($this->txtStatus1);?></td>
                <td><?php echo stripslashes($this->txtAirPNR);?></td>
            </tr>
            <?php } ?>
            <?php if($this->txtFlightNo2 !='') { ?>
            <tr>
                <td><?php echo stripslashes($this->txtFlightNo2);?></td>
                <td><?php echo stripslashes($this->txtClass2);?></td>
                <td><?php echo stripslashes($this->txtTravelDate2);?></td>
                <td><?php echo stripslashes($this->txtSector2);?></td>
                <td><?php echo stripslashes($this->txtTimeDep2);?></td>
                <td><?php echo stripslashes($this->txtSector22);?></td>
                <td><?php echo stripslashes($this->txtTimeArr2);?></td>
                <td>&nbsp;</td>
                <td><?php echo stripslashes($this->txtStatus2);?></td>
                <td><?php echo stripslashes($this->txtAirPNR);?></td>
            </tr>
            <?php } ?>
            <?php if($this->txtFlightNo3 !='') { ?>
            <tr>
                <td><?php echo stripslashes($this->txtFlightNo3);?></td>
                <td><?php echo stripslashes($this->txtClass3);?></td>
                <td><?php echo stripslashes($this->txtTravelDate3);?></td>
                <td><?php echo stripslashes($this->txtSector3);?></td>
                <td><?php echo stripslashes($this->txtTimeDep3);?></td>
                <td><?php echo stripslashes($this->txtSector33);?></td>
                <td><?php echo stripslashes($this->txtTimeArr3);?></td>
                <td>&nbsp;</td>
                <td><?php echo stripslashes($this->txtStatus3);?></td>
                <td><?php echo stripslashes($this->txtAirPNR);?></td>
            </tr>
            <?php } ?>
            <?php if($this->txtFlightNo4 !='') { ?>
            <tr>
                <td><?php echo stripslashes($this->txtFlightNo4);?></td>
                <td><?php echo stripslashes($this->txtClass4);?></td>
                <td><?php echo stripslashes($this->txtTravelDate4);?></td>
                <td><?php echo stripslashes($this->txtSector4);?></td>
                <td><?php echo stripslashes($this->txtTimeDep4);?></td>
                <td><?php echo stripslashes($this->txtSector44);?></td>
                <td><?php echo stripslashes($this->txtTimeArr4);?></td>
                <td>&nbsp;</td>
                <td><?php echo stripslashes($this->txtStatus4);?></td>
                <td><?php echo stripslashes($this->txtAirPNR);?></td>
            </tr>
            <?php } ?>
        </table>
    </td>
</tr>
<tr>
    <td colspan="3">
        <span style="font-size: 12px;font-weight: bold;"><?php echo REMARKS;?></span>
    </td>
</tr>
<tr>
    <td colspan="3"><label>
        <div class="smallBox">

            <?php echo stripslashes($this->txtRemarks);?>
        </div>
    </label></td>
</tr>
<tr>
    <td colspan="3">
        <span style="font-size: 12px;font-weight: bold;"><?php echo YOURTRAVELCONSULTANT;?></span>
    </td>
</tr>

<tr>
    <td colspan="3">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo NAME;?></td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td><?php echo CONTACTPHONE;?></td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vPhone'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td><?php echo CONTACTMOBILE;?></td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vMobile'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td><?php echo CONTACTEMAIL;?></td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vEmail'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td colspan="3"><strong><?php echo THANKYOU.' '.$this->common_model->getCompanyName();?></strong></td>
</tr>

<tr>
    <td colspan="3">
        <div class="bigBox">
            <?php echo CONTENTDETAILS;?><br>
            <strong><?php echo NOTICE;?></strong><?php echo ' : '.NOTICEDETAILS;?>
        </div>
    </td>
</tr>

</tbody>
</table>

<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('ticket_booking/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

</body>
</html>
