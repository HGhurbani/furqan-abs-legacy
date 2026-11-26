<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
    <tbody>
    <tr>
        <td colspan="2">
                <table width="100%" border="0" cellspacing="10" cellpadding="10">
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
        </td>
        <td colspan="2">

        </td>
    </tr>
    <tr>
        <td colspan="6">&nbsp;</td>
    </tr>
    <tr>
        <td colspan="6" align="center"><h5><?php echo CREDITNOTE;?></h5></td>
    </tr>
    <tr>
        <td><?php echo CUSTDATE;?></td>
        <td><label>
            
                <?php echo $this->txtDate;?>
            
        </label></td>
        <td><?php echo TONAME;?></td>
        <td colspan="3"></td>
    </tr>
    <tr>
        <td><?php echo DOCNUMBER;?></td>
        <td><label>
            
                <?php echo $this->txtDocNumber;?>
            
        </label></td>
        <td colspan="4"></td>
    </tr>
    <tr>
        <td colspan="6">
            <table width="100%" class="reportForm">
                <tr>
                    <td><?php echo TYPE;?></td>
                    <td><?php echo AIRLINE;?></td>
                    <td><?php echo TICKETNAME;?></td>
                    <td><?php echo PASSENGER;?></td>
                    <td><?php echo OTHERDETAILS;?></td>
                    <td><?php echo REFUNDAMT;?></td>
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

                            <td>

                            </td>

                            <td><label>
                                <?php echo $chkPaidAmt[$key];?>
                            </label></td>
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
</body>
</html>
