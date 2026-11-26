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
    <script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
    <link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">
    <link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>
    </head>
    <body>

    <script>
    $(document).ready(function() {

    $('#toPopup4').hide();
    $('#email').click(function(e) {
        $('#toPopup4').show();
    });

        $('#btnPrint').click(function(e) {
            $('#email').hide();
            window.print() ;
            $('#email').show();
        });
    });
    </script>
<?php if($this->uri->segment(4) == 'U') { ?>
<span class="warning"> <?php echo "Failed To Send Email"; ?></span>
<?php } ?>

    <table width="100%" border="0" cellspacing="0" cellpadding="0"> <!-- class="printForm"> -->
        <tbody>
            <tr>
                <td colspan="4" align="left"><h4>&nbsp;<?php echo TAXINVOICE;?></h4></td>
                <td colspan="2" align="center">
                    <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTINVOICEPAGE;?>" class="btnGeneral">
<br><br>
<div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>

                </td>
 
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
                <td colspan="4" align="left">
<!--                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo"> -->

                    <span style="font-size: 14px;font-weight: bold;padding-left:4px;"><?php echo $name;?></span><br>
                        &nbsp;<?php echo $addr;?><br>
                        &nbsp;<?php echo PHONE;?>: <?php echo $phone;?>
                        &nbsp;<?php echo MOBILE;?>: <?php echo $mobile;?><br>
                        &nbsp;<?php echo EMAIL;?>: <?php echo $email;?><br>
                        &nbsp;<?php echo WEB;?>: <?php echo $web;?>
                </td>

                <td colspan="2" align="center">
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

 
                    <h6><strong><span style="background:#d6d6d6;">&nbsp;<?php echo PNRNUMBER;?> &nbsp;</span><br> <?php echo $pnr_disp; ?></strong></h6>
                </td>
            </tr>
            <tr>
                <td colspan="6">&nbsp;</td>
            </tr>
<!--           <tr>
                <td><?php echo TONAME;?></td>
                <td><div class="smallBox"><?php echo $this->txtInvoiceTo;?></div></td>
                <td><?php echo CUSTDATE;?></td>
                <td><div class="smallBox"><?php echo $this->txtDate;?></div></td>
                <td colspan="2">&nbsp;</td>
            </tr>
            <tr>
                <td><?php echo DOCNUMBER;?></td>
                <td><div class="smallBox"><?php echo $this->txtDocNumber;?></div></td>
                <td colspan="4"></td>
            </tr>
            <tr>
                <td><?php echo LPO;?></td>
                <td><div class="smallBox"><?php echo $this->txtLpoMain;?></div></td>
                <td colspan="4"></td>
            </tr>
    -->        <tr>
                <td colspan="6">
                    <table width="100%"  class="flyworldForm">
                        <tr><th colspan='4'><?php echo PASSINFO;?></th> </tr>

                        <tr style="background:#fbfbfb;">
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

                                    <td>
                                        <?php echo $passenger;?>
                                    </td>

                                    <td>
                                        <?php echo $ticket;?>
                                    </td>


                                    <td>
                                        <?php echo date('d-m-Y',strtotime($issueDate));?>
                                    </td>

                                    <td><label>
                                        <?php echo stripslashes($chkCollected[$key]);?>
                                    </label></td>
                                </tr>
                                <?php
                            endforeach;
                        }//end if
                        ?>
<!--                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table width="100%" class="reportForm">
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
-->
                                <?php
/*                                $valCur = $this->common_model->getCompanyCurr();
                            $strCurCode = $this->common_model->currencyCode($valCur);
                            $strCurCoin = $this->common_model->currencyCoin($valCur);
                            $strCurDecimal = $this->common_model->currencyDecimal($valCur);
                            $multiple = '1';
                            for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
                            $decimal_part = ($total - floor($total)) * $multiple;

if($decimal_part == 0)
{
?>
                                <td colspan="1" align="right"><?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . "  " .  ONLY;?></td>  
<?php
} else {
?>
                                <td colspan="1" align="right"><?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " .  ONLY;?></td>
<?php }*/ ?>



                                <td style="background:#e3e3e3;"align='right' colspan='3'><strong><?php echo TOTALAMTFORALLPASS;?></strong></td>
                                <td style="background:#e3e3e3;">
                                <strong><?php echo $total;?></strong>
                                </td>
                                </tr>

                                <?php
                        } //end if
                        ?>
                    </table>
                </td>
            </tr>
            <tr><td>&nbsp;</td></tr>
            <tr>
                <td colspan="6">
                    <table width="100%" class="flyworldForm2">
                        <tr><th colspan="4"><?php echo FAREINFO; ?> </th></tr>

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
                    </table>                   
                </td>
            </tr>
           <tr>
                <td colspan="6">&nbsp;</td>
            </tr>

            <tr>
                <td colspan="6">
                    <table width="100%" class="flyworldForm2">
                        <tr> <th colspan='2'><?php echo BANKDETAILS;?></th> </tr>
                        <tr> <td colspan='2'><?php echo "Please remit payments in the folllowing bank account";?></td>  </tr>
                        <tr> <td><?php echo "Bank";?></td> <td> <?php echo "Commonwealth Bank of Australia";?> </tr>
                        <tr> <td><?php echo "BSP";?></td> <td> <?php echo "062 611";?> </tr>
                        <tr> <td><?php echo "Account Number";?></td> <td> <?php echo "10492179";?> </tr>
                        <tr> <td><?php echo "Account Name";?></td> <td> <?php echo "Flyworld International Travel and Tourism Pty Ltd";?> </tr>
                        <tr> <td colspan='2' style="font-weight:bold;font-size:12px"><b><strong><?php echo "Please use PNR number followed by your name as remittance reference";?></strong></b></td>  </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td colspan="6">
                    <table width="100%" class="flyworldForm2">
                        <tr> <th colspan='2'><?php echo NOTES;?></th> </tr>
                        <tr> <td colspan='2'><?php echo "*Amounts are displayed in Australian Dollars (AUD)";?></td>  </tr>
                        <tr> <td><?php echo "GST is included for taxable sales";?> </tr>
                        <tr> <td><?php echo "Fees may apply to some booking charges, ticket reissues and consultant-assisted services.";?> </tr>
                    </table>
                </td>
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
