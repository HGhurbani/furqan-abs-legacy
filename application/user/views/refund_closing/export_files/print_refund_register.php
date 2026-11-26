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
        <td>
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
        <td>
        </td>
        <td colspan="6">
            <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTACCOUNTPAGE;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

        </td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><div class="smallBox"><?php echo $this->txtDate;?></div></td>
        <td colspan="6">&nbsp;</td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><div class="smallBox"><?php echo $this->txtDocNumber;?></td>
        <td colspan="6"><span style="font-size: 14px;font-weight: bold;"><?php echo REFUND;?></span></td>
    </tr>

    <tr>
        <td><?php echo RECEIVEDFROM?></td>
        <td colspan="7"><?php echo $this->txtAmountFCMain;?></td>
    </tr>

    <tr>
        <td><?php echo REMARKS;?></td>
        <td colspan="7"><?php echo $this->txtAmountFCMain;?></td>
    </tr>

    <tr>
        <td>
            <table class="reportForm" style="width:180%;">
                <tr>
                    <td><?php echo SRNO;?></td>
                    <td><?php echo TYPE;?></td>
                    <td colspan="2"><?php echo TICKETNO;?></td>
                    <td colspan="2"><?php echo PASSENGER;?></td>
                    <td colspan="2"><?php echo DETAILS;?></td>
                </tr>

                <?php
                if(!empty($chkType))
                {
                    foreach($chkType as $key => $aitem):
                        switch($chkType[$key])
                        {
                            case "Tkt":
                                $typeVal = 'Ticket';
                            if($ddlYesNo[$key] == 'O') {
                                $tktVal = $chkTktNumber[$key];
                                $detVal  = '';
                            }
                            else {
                                $tktVal  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTktNumber[$key]."'");
                                $detVal  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector3'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector4'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$chkTktNumber[$key]."'");
                            }
                            break;
                            case "Xo":
                                $typeVal = 'Xo';
                            if($ddlYesNo[$key] == 'O') {
                                $tktVal = $chkTktNumber[$key];
                                $detVal  = '';
                            }
                            else {
                                $tktVal  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$chkTktNumber[$key]."'");
                                $detVal  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$chkTktNumber[$key]."'");
                            }
                            break;
                            case "Htl":
                                $typeVal   = 'Hotel';
                            if($ddlYesNo[$key] == 'O') {
                                $tktVal = $chkTktNumber[$key];
                                $detVal  = '';
                            }
                            else {
                                $tktVal    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                $detVal    = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'-'.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$chkTktNumber[$key]."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$chkTktNumber[$key]."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$chkTktNumber[$key]."'");
                            }
                            break;
                        }//end switch
                    ?>
                        <tr>
                            <td><?php echo $key+1;?></td>
                            <td><?php echo $typeVal;?></td>
                            <td colspan="2"><?php echo $tktVal;?></td>
                            <td colspan="2"><?php echo $txtPass[$key];?></td>
                            <td colspan="2"><?php echo $detVal;?></td>
                        </tr>
                        <?php
                    endforeach;
                }//end if
                ?>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>

    <tr>
        <td colspan="4"><?php echo PREPAREDBY;?></td>
        <td colspan="4"><?php echo MANAGER;?></td>
    </tr>

    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
</tbody>
</table>

<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('refund_register/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

</body>
</html>
