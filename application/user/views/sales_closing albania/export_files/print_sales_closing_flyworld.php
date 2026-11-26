    <table width="100%" border="0" cellspacing="10" cellpadding="10"> <!-- class="printForm"> -->
        <tbody>
            <tr>
                <td colspan="6" align="left"><h4><?php echo TAXINVOICE;?></h4></td>
            </tr>
 
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
                <table width="100%" border="0" cellspacing="10" cellpadding="10">
                <tr>
                <td>
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>
                <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?>
                </td>

                <td align="center"  style="font-size: 14px;font-weight: bold;">
<?php                           
                                $pnr_list = array();
                                foreach($chkType as $key => $aitem):
                                    switch($chkType[$key])
                                    {
                                        case "Xo" :
                                            $pnr = $this->common_model->get_field_name('XoBooking','vGdspnr'," WHERE nXId='".$chkTktNumber[$key]."'");
                                            break;

                                        case "Tkt" :
                                            $pnr = $this->common_model->get_field_name('TicketBooking','vGdspnr'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            break;
                                    }//end switch
                                    if(!in_array($pnt,$pnr_list)) $pnr_list[]=$pnr;
                                endforeach;
                               $pnr_disp = implode(' ', $pnr_list); 
                                    ?>

 
                    <strong><span style="background:#d6d6d6;">&nbsp;<?php echo PNRNUMBER;?> &nbsp;</span><br> <?php echo $pnr_disp; ?></strong>
                </td>
            </tr>
            </table>
            </td></tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width="100%"  class="flyworldForm" cellspacing="0" cellpadding="0">
                        <tr><th colspan='4' style="background:#d6d6d6;;"><?php echo PASSINFO;?></th> </tr>

                        <tr style="background:#e3e3e3;">
                            <td style="background:#e3e3e3;"><?php echo PASSENGER;?></td>
                            <td style="background:#e3e3e3;"><?php echo TICKETNAME;?></td>
                            <td style="background:#e3e3e3;"><?php echo ISSUEDATE;?></td>
                            <td style="background:#e3e3e3;"><?php echo TICKETTOTAL;?></td>
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
                                            $type      = 'Xo';
                                            $ticket    = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('XoBooking','vPassengerId'," WHERE nXId='".$chkTktNumber[$key]."'");
                                            $issueDate = $this->common_model->get_field_name('XoBooking','dDate'," WHERE nXId='".$chkTktNumber[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$chkTktNumber[$key]."'")));
                                            break;

                                        case "Tkt" :
                                            $type      = 'Ticket';
                                            $ticket    = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            $issueDate= $this->common_model->get_field_name('TicketBooking','dDate'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$chkTktNumber[$key]."'")));
                                            break;

                                        case "Htl" :
                                            $type      = 'Hotel';
                                            $ticket    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $issueDate= $this->common_model->get_field_name('HotelVoucher','dDate'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").' - '.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").' - '.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$chkTktNumber[$key]."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$chkTktNumber[$key]."'"))).' - Nights:'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$chkTktNumber[$key]."'").' - Rooms:'.$this->common_model->get_field_name('HotelVoucher','vNoOfUnit'," WHERE nHVId='".$chkTktNumber[$key]."'") ;
                                            break;
                                    }//end switch
                                    ?>

                                    <td style="background:#f4f4f4;">
                                        <?php echo $passenger;?>
                                    </td>

                                    <td style="background:#f4f4f4;">
                                        <?php echo $ticket;?>
                                    </td>


                                    <td style="background:#f4f4f4;">
                                        <?php echo date('d-m-Y',strtotime($issueDate));?>
                                    </td>

                                    <td style="background:#f4f4f4;"><label>
                                        <?php echo stripslashes($chkCollected[$key]);?>
                                    </label></td>
                                </tr>
                                <?php
                            endforeach;
                        }//end if
                        ?>
                               <tr>
                                <td align='right' colspan='3' style="background:#d6d6d6;"><strong><?php echo TOTALAMTFORALLPASS;?>&nbsp;</strong></td>
                                <td style="background:#d6d6d6;;">
                                <strong><?php $total = $this->txtTotalCollected + $this->txtTotalAgentAmt; echo $total;?></strong>
                                </td>
                                </tr>

            <tr><td></td></tr>
           <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

                        <tr style="background:#d6d6d6;" ><td colspan='4'><?php echo FAREINFO; ?> </td></tr>

                        <tr>
                            <td> <?php echo TICKETCHARGES; ?> </td>
                            <td> <?php echo CHARGES . "*"; ?> </td>
                            <td> <?php echo GST . "*"; ?> </td>
                            <td> <?php echo TOTAL . "*"; ?> </td>
                        </tr>

                        <tr>
                            <td> <?php echo FARE; ?> </td>
                            <td> <?php echo $this->txtTotalSalesAmt; ?> </td>
                            <td> <?php echo $this->txtTotalAgentAmt; ?> </td>
                            <td> <?php echo $total; ?> </td>
                        </tr>
           <tr>
                <td colspan="4">&nbsp;</td>
            </tr>

                        <tr> <td colspan='4' style="background:#d6d6d6;"><?php echo BANKDETAILS;?></td> </tr>
                        <tr> <td colspan='4'><?php echo "Please remit payments in the folllowing bank account";?></td>  </tr>
                        <tr> <td><?php echo "Bank";?></td> <td colspan='2'> <?php echo "Commonwealth Bank of Australia";?></td> </tr>
                        <tr> <td><?php echo "BSP";?></td> <td colspan='2'> <?php echo "062 611";?></td> </tr>
                        <tr> <td><?php echo "Account Number";?></td> <td colspan='2'> <?php echo "10492179";?></td> </tr>
                        <tr> <td><?php echo "Account Name";?></td> <td colspan='2'> <?php echo "Flyworld International Travel and Tourism Pty Ltd";?></td> </tr>
                        <tr> <td colspan='4'><?php echo "Please use PNR number followed by your name as remittance reference";?></td>  </tr>
                        <tr> <td>&nbsp; </td><td></td></tr>
                        <tr style="background:#d6d6d6;"> <td colspan='4'><?php echo NOTES;?></td> </tr>
                        <tr> <td colspan='4'><?php echo "*Amounts are displayed in Australian Dollars (AUD)";?></td>  </tr>
                        <tr> <td colspan='4'><?php echo "GST is included for taxable sales";?></td> </tr>
                        <tr> <td colspan='4'><?php echo "Fees may apply to some booking charges, ticket reissues and consultant-assisted services.";?></td> </tr>
                    </table>
                </td>
            </tr>

        </tbody>
    </table>


