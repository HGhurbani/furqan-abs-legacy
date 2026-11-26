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
                <td colspan="8">
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

    <td colspan="2">
    </td>
</tr>
<tr>
      <td colspan="3">
          <span style="font-size: 14px;font-weight: bold;"><?php echo SERVICEVOUCHER;?></span>
      </td>
</tr>
<tr>
    <td><?php echo VOUCHERID;?></td>
    <td>
        <div class="smallBox"><?php echo $this->txtTktName;?></div>

    </td>


    <td><?php echo CUSTDATE;?></td>
    <td><label>
        <div class="smallBox"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div>

    </label></td>

    <td><?php echo SERVICES;?></td>
    <td><label>
        <div class="smallBox"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
            <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
            <?php echo stripslashes($this->txtExtraServ);?></div>

    </label></td>
</tr>

<tr>
    <td><?php echo HOTELREF;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtHotelConfId);?></div></td>

    <td><?php echo AGENTREF;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtAgentConfId);?></div></td>

    <td><?php echo TOURFILE;?></td>
    <td><div class="smallBox"><?php echo stripslashes($this->txtBookingId);?></div></td>
</tr>

<tr>
    <td><?php echo LEADNAMES;?></td>
    <td colspan="5"><div class="smallBox"> <?php echo stripslashes($this->txtPassenger);?>  </div></td>
</tr>

<tr>
    <td><?php echo TOBEPRESENTED;?></td>
    <td colspan="5"><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?> </div></td>
</tr>

<tr>
    <td><?php echo COUNTRYNAME;?></td>
    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>

    <td><?php echo CITYNAME;?></td>
    <td colspan="2"><div class="smallBox"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></div></td>
</tr>

<tr>
    <td><?php echo ADDRESS;?></td>
    <td>
        <div class="smallBox">
            <?php echo $this->common_model->get_field_name('Hotel','vAddress'," WHERE nHId='".$this->ddlHotel."'");?>
        </div>
    </td>

    <td><?php echo PHONE;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>

    <td><?php echo EMAIL;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td>
</tr>

<tr>
    <td colspan="6">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo CHECKIN;?></td>
                <td><?php echo CHECKOUT;?></td>
                <td><?php echo NIGHTS;?></td>
                <td><?php echo ADULT;?></td>
                <td><?php echo CHILD;?></td>
                <td><?php echo ROOMTYPE;?></td>
                <td><?php echo NOOFROOMS;?></td>
            </tr>

            <tr>
                <td><label>
                    <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckIn)));?>
                </label></td>

                <td><label>
                    <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckOut)));?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtNights);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtAdult);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtChild);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtRoomType);?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtNoOfUnit);?>
                </label></td>
            </tr>
        </table>
    </td>
</tr>
<!--
<tr>
    <td><?php echo SERVICES;?></td>
    <td colspan="5"><label>
        <div class="smallBox">
            <?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
            <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
            <?php echo stripslashes($this->txtExtraServ);?>
        </div>
    </label></td>
</tr>
-->
<!--
<tr>
    <td colspan="6">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo ARRFROM;?></td>
                <td><?php echo FLIGHT;?></td>
                <td><?php echo ARRTIME;?></td>
                <td><?php echo DEPTO;?></td>
                <td><?php echo FLIGHT;?></td>
                <td><?php echo DEPTIME;?></td>
            </tr>

            <tr>
                <td><label>
                    <?php echo $this->txtArrFrom;?>
                </label></td>
                <td><label>
                    <?php echo $this->txtArrFlight;?>
                </label></td>

                <td><label>
                    <?php echo $this->txtArrTime;?>
                </label></td>

                <td><label>
                    <?php echo $this->txtDepTo;?>
                </label></td>

                <td><label>
                    <?php echo $this->txtDepFlight;?>
                </label></td>

                <td><label>
                    <?php echo $this->txtDepTime;?>
                </label></td>
            </tr>
        </table>
    </td>
</tr>
-->
<tr>
    <td><?php echo REMARKS;?></td>
    <td colspan="5"><label>
        <div class="smallBox"><?php echo stripslashes($this->txtRemarks);?></div>
    </label></td>
</tr>

<tr>
    <td colspan="6">
        <table class="reportForm" width="100%">

            <?php
            if(!empty($txtPayRoomType))
            {
                ?>
                <tr>
                    <td><?php echo ROOMTYPE;?></td>
                    <td><?php echo INDATE;?></td>
                    <td><?php echo OUTDATE;?></td>
                    <td><?php echo DAYS;?></td>
                    <td><?php echo NOOFROOMS;?></td>
                    <td><?php echo RATENETFC;?></td>
                    <td><?php echo TOTALINFC;?></td>
                    <td><?php echo RATENETDC;?></td>
                    <td><?php echo TOTALINDC;?></td>
                    <td><?php echo EXCHRATE;?></td>
                    <td><?php echo CURRENCY;?></td>
                </tr>

                <?php
                $currency_name =  $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId=" . stripslashes($this->ddlCurrency));
                foreach($txtPayRoomType as $key => $aitem):
                    ?>
                    <tr  class="re">
                        <td><?php echo stripslashes($txtPayRoomType[$key]);?></td>
                        <td><?php echo stripslashes(date('d-m-Y',strtotime($txtChkinDate[$key])));?></td>
                        <td><?php echo stripslashes(date('d-m-Y',strtotime($txtChkoutDate[$key])));?></td>
                        <td><?php echo stripslashes($txtNight[$key]);?></td>
                        <td><?php echo stripslashes($txtNoofRooms[$key]);?></td>
                        <td><?php echo stripslashes($txtRateNetFc[$key]);?></td>
                        <td><?php echo stripslashes($txtTotalinFc[$key]);?></td>
                        <td><?php echo stripslashes($txtRateNetDc[$key]);?></td>
                        <td><?php echo stripslashes($txtTotalinDc[$key]);?></td>
                        <td><?php echo stripslashes($txtExchangeRate[$key]);?></td>
                        <td><?php echo $currency_name; ?></td>
                    </tr>
                    <?php
                endforeach; ?>

                <tr>
                    <td colspan="4">&nbsp;</td>
                    <td><?php echo TOTALAMT;?></td>
                    <td><?php echo $this->txtRateNetFcTotal;?></td>
                    <td><?php echo $this->txtTotalFc;?></td>
                    <td><?php echo $this->txtRateNetDcTotal;?></td>
                    <td><?php echo $this->txtTotalDc;?></td>
                    <td>&nbsp;</td>
                </tr>

                <?php
            }//end if
            ?>
        </table>
    </td>
</tr>

<tr>
    <td><?php echo AGENTAC;?></td>
<!--    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$this->ddlAgentAc."'");?></div></td> -->
     <td><div class="smallBox"><?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$this->ddlAcCode."' ");?></div></td> 
    <td><?php echo CUSTACCNAME;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$this->ddlCustomerAc ."'");?></div></td>
    <td><?php echo SALESAMT;?></td>
    <td><label>
        <div class="smallBox"><?php echo stripslashes($this->txtSalesAmt);?></div>
    </label></td>
</tr>


<tr>
    <td><?php echo BOOKEDBY;?></td>
    <td><div class="smallBox"><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlBooked."'");?></div></td>
    <td colspan="2"></td>
    <td><?php echo SERVICEAMT;?></td>
    <td><label>
        <div class="smallBox"><?php echo stripslashes($this->txtServiceAmt);?></div>
    </label></td>
</tr>
<tr>
    <td colspan="4"></td>
    <td><?php echo SERVPERCENT;?></td>
    <td><label>
        <div class="smallBox"><?php echo stripslashes($this->txtServicePerc) . " % ";?></div>
    </label></td>
</tr>


<tr>
    <td>&nbsp;</td>
</tr>

</tbody>
</table>
</body>
</html>
