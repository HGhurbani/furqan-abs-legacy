<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
<tbody>
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

        </td>
    </tr>
    <tr>
        <td colspan="8">&nbsp;</td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><?php echo $this->txtDate;?></td>
        <td colspan="6">&nbsp;</td>
    </tr>

    <tr>
        <td><?php echo DOCUMENTNUMBER;?></td>
        <td><?php echo $this->txtDocNumber;?></td>
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
</body>
</html>
