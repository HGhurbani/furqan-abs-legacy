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
<tr>
    <td colspan="2">
        <img src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>" alt="<?php echo $this->common_model->getCompanyName();?>">
    </td>
    <td colspan="2">
        <span style="font-size: 16px;font-weight: bold;"><?php echo $this->common_model->getCompanyName();?></span><br>
        <span style="font-size: 12px;font-weight: bold;"><?php echo $this->common_model->getCompanyLocation();?></span><br>
        <?php echo $this->common_model->getCompanyAddress();?>
    </td>
    <td colspan="2">
        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTAGENTPAGE;?>" class="btnGeneral">
<br><br>

    </td>
</tr>
<tr>
    <td colspan="6">&nbsp;</td>
</tr>
<tr>
    <td colspan="6" align="center"><h5><?php if($this->ddlType == '1') echo CASHRECEIPT;else echo PRINTINVOICE;?></h5></td>
</tr>
<tr>
    <td><?php echo CUSTDATE;?></td>
    <td><label>
        <?php echo $this->txtDate;?>
    </label></td>
    <td><?php echo TONAME;?></td>
    <td><?php echo $this->txtInvoiceTo;?></td>
    <td colspan="2">&nbsp;</td>
</tr>
<tr>
    <td><?php echo DOCNUMBER;?></td>
    <td><label>
        <?php echo $this->txtDocNumber;?>
    </label></td>
    <td colspan="4"></td>
</tr>
<tr>
    <td><?php echo LPO;?></td>
    <td><label>
        <?php echo $this->txtLpoMain;?>
    </label></td>
    <td colspan="4"></td>
</tr>
<tr>
    <td colspan="6">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo SRNO;?></td>
                <td><?php echo TTYPE ;?></td>
                <td><?php echo AIRLINE;?></td>
                <td><?php echo TICKETNAME;?></td>
                <td><?php echo PASSENGER;?></td>
            </tr>
            <?php
            if(!empty($chkType))
            {
                foreach($chkType as $key => $aitem):
                    ?>
                    <tr class="re">

                        <?php
                        switch($chkType[$key])
                        {
                            case "Xo" :
                                $type   = 'Xo';
                                $ticket  = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$chkTktNumber[$key]."'");
                                $passenger  = $this->common_model->get_field_name('XoBooking','vPassengerId'," WHERE nXId='".$chkTktNumber[$key]."'");
                                break;

                            case "Tkt" :
                                $type   = 'Ticket';
                                $ticket  = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTktNumber[$key]."'");
                                $passenger  = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$chkTktNumber[$key]."'");
                                break;

                            case "Htl" :
                                $type   = 'Hotel';
                                $ticket  = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                $passenger  = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                break;
                        }//end switch
                        ?>

                        <td>
                            <?php echo $key+1;?>
                        </td>

                        <td><label>
                            <?php echo stripslashes($type);?>
                        </label></td>

                        <td>
                            <?php echo stripslashes($chkAirline[$key]);?>
                        </td>

                        <td>
                            <?php echo $ticket;?>
                        </td>

                        <td>
                            <?php echo $passenger;?>
                        </td>

                    </tr>
                    <?php
                endforeach;
            }//end if
            ?>
        </table>
    </td>
</tr>
<tr>
    <td colspan="2"><?php echo PREPAREDBY;?></td>
    <td colspan="2"><?php echo APPROVEDBY;?></td>
    <td colspan="2"><?php echo RECEIVEDBY;?></td>
</tr>
<tr>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
    <td colspan="2">&nbsp;</td>
</tr>
</tbody>
</table>

<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('sales_closing/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

</body>
</html>
