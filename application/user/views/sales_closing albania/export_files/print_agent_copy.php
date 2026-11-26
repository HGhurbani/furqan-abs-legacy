<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
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
</body>
</html>