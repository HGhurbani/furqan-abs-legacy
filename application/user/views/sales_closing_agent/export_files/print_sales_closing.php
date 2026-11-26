    <table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
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
                <td colspan="4">
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

            </tr>
            <tr>
                <td colspan="4">&nbsp;</td>
            </tr>
            <tr>
                <td colspan="4" align="center"><span style="font-size: 16px;font-weight: bold;"> <u><strong><?php if($this->ddlType == '1') echo CASHRECEIPT;else echo PRINTINVOICE;?></strong></u></span></td>
            </tr>
            <tr>
                <td><?php echo TONAME;?></td>
                <td><?php echo $this->txtInvoiceTo;?></td>
                <td><?php echo CUSTDATE;?></td>
                <td><?php echo $this->txtDate;?></td>
            </tr>
            <tr>
                <td><?php echo DOCNUMBER;?></td>
                <td><?php echo $this->txtDocNumber;?></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td><?php echo LPO;?></td>
                <td><?php echo $this->txtLpoMain;?></td>
                <td colspan="2"></td>
            </tr>
            <tr>
                <td colspan="4">
                    <table width="100%"  border="0" cellspacing="15" cellpadding="15"  class="reportForm">
<tr><td colspan="3">
                    <table width="100%"  border="1" cellspacing="15" cellpadding="15"  class="reportForm" style="border-collapse:collapse;border-spacing:999;">
                        <tr>
                            <td><?php echo SRNO . " &nbsp; ";?></td>
                            <td><?php echo TTYPE . "  &nbsp;";?></td>
                            <td><?php echo AIRLINE . "  &nbsp;";?></td>
                            <td><?php echo TICKETNAME . " &nbsp; ";?></td>
                            <td><?php echo PASSENGER . " &&nbsp;";?></td>
                            <td><?php echo OTHERDETAILS . "  &nbsp;";?></td>
                            <td><?php echo SLSAMT . "  &nbsp;";?></td>
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
                                            $otherDet  = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$chkTktNumber[$key]."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('XoDetails','dTravelDate'," WHERE nXoId='".$chkTktNumber[$key]."'")));
                                            break;

                                        case "Tkt" :
                                            $type      = 'Ticket';
                                            $ticket    = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$chkTktNumber[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$chkTktNumber[$key]."'").'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('TicketDetails','dTravelDate1'," WHERE nTicketId='".$chkTktNumber[$key]."'")));
                                            break;

                                        case "Htl" :
                                            $type      = 'Hotel';
                                            $ticket    = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $passenger = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $cityName  = $this->common_model->get_field_name('HotelVoucher','nCity'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $hotel     = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                            $otherDet  = $this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").' - '.$this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").' - '.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$chkTktNumber[$key]."'"))).'-'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$chkTktNumber[$key]."'"))).' - Nights:'.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$chkTktNumber[$key]."'").' - Rooms:'.$this->common_model->get_field_name('HotelVoucher','vNoOfUnit'," WHERE nHVId='".$chkTktNumber[$key]."'") ;
                                            break;
                                    }//end switch
                                    ?>

                                    <td>
                                        <?php echo $key+1 . " &nbsp; ";?>
                                    </td>

                                    <td><label>
                                        <?php echo stripslashes($type) . " &nbsp; ";?>
                                    </label></td>

                                    <td>
                                        <?php echo stripslashes($chkAirline[$key]) . " &nbsp; ";?>
                                    </td>

                                    <td>
                                        <?php echo $ticket . " &nbsp; &nbsp;";?>
                                    </td>

                                    <td>
                                        <?php echo $passenger . " &nbsp; &nbsp;";?>
                                    </td>

                                    <td>
                                        <?php echo $otherDet . " &nbsp; &nbsp;";?>
                                    </td>

                                    <td><label>
                                        <?php echo stripslashes($chkSalesAmt[$key]) . " &nbsp; &nbsp;";?>
                                    </label></td>
                                </tr>
                                <?php
                            endforeach;
                        }//end if
                        ?>
                    </table>
                </td>
            </tr>
<!--            <tr>
                <td>  colspan="6" align="right"> -->
<!--                    <table width="100%"  border="0" cellspacing="10" cellpadding="10" class="reportForm"> -->
                        <tr>
                            <td width="53.9%">&nbsp;</td>
                            <td width="35%"><?php echo DRACCNAME;?></td>
                            <td><?php echo AMOUNT;?></td>
                        </tr>
                        <?php
                        if(!empty($chkDrAccMain))
                        {
                            $total = 0;
                            foreach($chkDrAccMain as $key2 => $pitem):

                                switch($chkAcTable[$key2])
                                {
                                    case "C" :
                                        $dracc = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "V" :
                                        $dracc = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "AG" :
                                        $dracc = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "O" :
                                        $dracc = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$chkDrAccMain[$key2]."'");
                                        break;

                                    case "A" :
                                        $dracc = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$chkDrAccMain[$key2]."'");
                                        break;
                                }//end switch

                                ?>
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>
                                        <?php echo $dracc;?>
                                    </td>

                                    <td>
                                        <?php echo $chkAmt[$key2];?>
                                    </td>
                                </tr>
                                <?php
                            $total += $chkAmt[$key2];
                            endforeach; ?>
                                <tr>
                                <?php
                                $valCur = $this->common_model->getCompanyCurr();
                            $strCurCode = $this->common_model->currencyCode($valCur);
                            $strCurCoin = $this->common_model->currencyCoin($valCur);
                            $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                            $multiple = '1';
                            for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                            $decimal_part = ($total - floor($total)) * $multiple;
if($decimal_part == 0)
{
?>

                                <td align="right"><?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . "  " .  ONLY;?></td>  
<?php
} else {
?>
                                <td align="right"><?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></td>
<?php } ?>


                                <!--                                <td align='right' width="53.9%"><strong><?php echo $this->common_model->convert_number_to_words($total).' '.$cVal.' '.ONLY;?></strong></td> -->

                                <td align='center'><strong><?php echo TOTALAMT;?></strong></td>
                                <td>
                                <strong><u><?php echo $total;?></u></strong>
                                </td>
                                </tr>

                                <?php
                        } //end if
                        ?>
<!--                    </table> 
                </td>
            </tr>-->
                    </table>
                </td>
            </tr>

            <tr>
                <td><?php echo REMARKS;?></td>
                <td colspan="5"><?php echo $this->txtRemarks;?></td>
            </tr>
           <tr>
                <td colspan="4">&nbsp;</td>
                </tr>

<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>
<tr>
<td colspan="4">
<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm">
                        <tr>
                            <td width="33%"><?php echo PREPAREDBY;?></td>
<td>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td width="33%"><?php echo APPROVEDBY;?></td>
<td>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
<td>&nbsp; &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
                            <td width="33%"><?php echo RECEIVEDBY;?></td>
                        </tr>
                        <tr>
                            <td><?php echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$this->session->userdata('AbsAdmin')."'");?></td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td>&nbsp;</td>
                            <td></td>
                        </tr>
</table>
</td>
</tr>
<tr><td>&nbsp;</td></tr>
<tr><td>&nbsp;</td></tr>

        </tbody>
    </table>
</body>
</html>
