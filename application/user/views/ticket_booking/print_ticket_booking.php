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
<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
<script>
$(document).ready(function() {
	$('#email').hide();
    $('#btnPrint').click(function(e) {
        window.print() ;
    });
});
</script>
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
         */ ?>

<!--            <tr>
                <td colspan="2">
                <table width="100%" border="0" cellspacing="10" cellpadding="10">
                <tr>
<td>
        <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">               
</td>
                <td>
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
        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTACCOUNTPAGE;?>" class="btnGeneral">
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


      <td>
          <span style="font-size: 14px;font-weight: bold;"><?php echo TRAVELLINGITINERARY;?></span>
        </td>
   <td align="right" >
          <span style="font-size: 14px;font-weight: bold;"><?php echo ACCCOUNTSCOPY;?></span>
      </td>
</tr>
<tr>
      <td colspan="3">
          <table>
              <tr>
                  <td><strong><?php echo CUSDATE;?></strong></td>
                  <td><label>
                      <div class="smallBox">
                          <?php echo $this->txtDate; ?>
                      </div>
                  </label></td>


                  <td><strong><?php echo GDS;?></strong></td>
                  <td><label>
                      <div class="smallBox">
                          <?php echo $this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$this->ddlGDS."'");?>
                      </div>
                  </label></td>

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
                  <td><strong><?php echo TRAVELLERS;?></strong></td>
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
                <td><?php echo stripslashes(date('d-m-Y', strtotime($this->txtTravelDate1)));?></td>
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
                <td><?php echo stripslashes(date('d-m-Y', strtotime($this->txtTravelDate2)));?></td>
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
                <td><?php echo stripslashes(date('d-m-Y', strtotime($this->txtTravelDate3)));?></td>
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
                <td><?php echo stripslashes(date('d-m-Y', strtotime($this->txtTravelDate4)));?></td>
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
    <td colspan="3">
        <span style="font-size: 12px;font-weight: bold;"><?php echo FAREDETAILS;?></span>
    </td>
</tr>
<tr>
    <td colspan="3">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo FARE;?></td>
                <td><?php echo TKTFARECODE;?></td>
                <td><?php echo FCFARE?></td>
                <td><?php echo FCTAX?></td>
                <td><?php echo FACEFARE;?></td>
                <td><?php echo MARKETFARE;?></td>
                <td><?php echo COMM;?></td>
                <td><?php echo COMMAMT;?></td>
                <td><?php echo GSAPERCENT;?></td>
                <td><?php echo COMMAMT;?></td>
                <td><?php echo ORCPERCENT;?></td>
                <td><?php echo COMMAMT;?></td>
                <td><?php echo TAX;?></td>
                <td><?php echo TAX;?></td>
                <td><?php echo TAX;?></td>
                <td><?php echo TOTALTAX;?></td>
            </tr>
            <?php
            switch($this->ddlFare)
            {
                case "N":
                    $fareType = 'Normal';
                    break;
                case "S":
                    $fareType = 'Special';
                    break;
            }//end switch
            ?>
            <tr>
                <td><label>
                    <?php echo $fareType;?>
                </label></td>

                <td><label>
                    <?php echo $this->ddlFareCode;?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtFcFare);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtFcTax);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtFace);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtMktFare);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtCommTax);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtCommAmt);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtGSATax);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtGSAAmt);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtORCTax);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtORCAmt);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtFcTax1);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtFcTax2);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtFcTax3);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtTotalTax);?>
                </label></td>
            </tr>
            <tr>
                <td colspan="2"><?php echo SUBCODE;?></td>

                <td colspan="4"><label>
                    <?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlAccname."'");?>
                </label></td>

                <td colspan="2"><?php echo ACCHEAD;?></td>
                <td colspan="3"><label>
                    <?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->ddlAccHead."'");?>
                </label></td>

                <td colspan="3"><?php echo NETTOVENDOR;?></td>

                <td colspan="2"><label>
                    <?php echo stripslashes($this->txtNetVendor);?>
                </label></td>
            </tr>
        </table>
    </td>
</tr>
<tr>
     <td colspan="3">
         <span style="font-size: 12px;font-weight: bold;"><?php echo BILLINGDETAILS;?></span>
     </td>
 </tr>
<tr>
    <td colspan="3">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo MODEOFPAYMENT;?></td>
                <td><?php echo CUSTACCNAME;?></td>
                <td><?php echo AGENTAC;?></td>
                <td><?php echo AGENTAMT;?></td>
<!--                <td><?php echo LPO;?></td> -->
            </tr>
            <?php
            switch($this->ddlMode)
            {
                case "Cr":
                    $mode = 'Credit';
                    break;
                case "Ca":
                    $mode = 'Cash';
                    break;
                case "KN":
                    $mode = 'Card';
                    break;
            }//end switch
            ?>
            <tr>
                <td><label>
                    <?php echo $mode;?>
                </label></td>

                <td><label>
                    <?php echo $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlCustomerAc."'");?>
                </label></td>

                <td><label>
                    <?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->ddlAgentAc."'");?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtAgentAmt);?>
                </label></td>

<!--                <td><label>
                    <?php if($this->txtLPO != 0 ) {echo stripslashes($this->txtLPO);}?>
                </label></td> -->
            </tr>
            <tr>
                <td><?php echo SALESAMT;?></td>
                <td><?php echo DISCOUNT;?></td>
                <td><?php echo DISCOUNTAMT;?></td>
                <td><?php echo COLECTED;?></td>
                <td><?php echo SERVICEPERCENT;?></td>
                <td><?php echo SERVICEAMT;?></td>
            </tr>
            <tr>
                <td><label>
                    <?php echo stripslashes($this->txtSalesAmt1);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtDiscount);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtDiscountAmt);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtCollected);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtServicePerc);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtServiceAmt);?>
                </label></td>
            </tr>
        </table>
    </td>
</tr>
</tbody>
</table>
</body>
                </html>
