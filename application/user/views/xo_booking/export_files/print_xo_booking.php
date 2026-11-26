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
    <td colspan="2">
    </td>
</tr>
<tr>
    <td>&nbsp;</td>
</tr>
<tr>
      <td colspan="10" align="center">
          <span style="font-size: 18px;font-weight: bold;" ><?php echo EXCHANGEORDER;?></span>
      </td>
</tr>


<tr>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo TO;?></td>
    <td colspan="3"><label>
        
            <?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$this->ddlAccount."'");?>
        
        </label>
    </td>
    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo XONO;?></td>
    <td>
        <label><?php echo $this->txtTktName;?></label>
    </td>

    <td><?php echo GDSPNR;?></td>
    <td><?php echo stripslashes($this->txtGdsPNR);?></td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td><?php echo CUSTDATE;?></td>
    <td><?php echo $this->txtDate;?></td>

    <td><?php echo AIRPNR;?></td>
    <td><?php echo stripslashes($this->txtAirPNR);?></td>

    <td>&nbsp;</td>
    <td>&nbsp;</td>
</tr>

<tr>
    <td colspan="6"><?php echo PRINTXODETAIL;?></td>
</tr>

<tr>
    <td>
        <table width="100%" class="reportForm">
            <tr><td><?php echo PASSENGER;?></td></tr>
            <?php
                foreach ($ddlPassenger as $key1 => $pitem): ?>
                    <tr>
                        <td><label>
                            <?php echo stripslashes($ddlPassenger[$key1]);?>
                        </label></td>
                    </tr>
                <?php
                endforeach;?>
        </table>
    </td>
    <td colspan="4">
        <table width="100%" class="reportForm">
            <tr>
                <td colspan="2"><?php echo SECTOR;?></td>
                <td><?php echo CARRIER;?></td>
                <td><?php echo FLIGHTNO;?></td>
                <td><?php echo TKTCLASS;?></td>
                <td><?php echo TRAVELDATE;?></td>
                <td><?php echo TIMEDEP;?></td>
                <td><?php echo TIMEARR;?></td>
                <td><?php echo STATUS;?></td>
            </tr>
            <?php
            foreach ($txtCarrier as $key => $aitem):
                ?>
                <tr>
                    <td><?php echo stripslashes($txtSector[$key]);?></td>
                    <td><?php echo stripslashes($txtSector2[$key]);?></td>
                    <td><?php echo stripslashes($aitem);?></td>
                    <td><?php echo stripslashes($txtFlightNo[$key]);?></td>
                    <td><?php echo stripslashes($txtClass[$key]);?></td>
                    <td><?php echo stripslashes($txtTravelDate[$key]);?></td>
                    <td><?php echo stripslashes($txtTimeDep[$key]);?></td>
                    <td><?php echo stripslashes($txtTimeArr[$key]);?></td>
                    <td><?php echo stripslashes($txtStatus[$key]);?></td>
                </tr>
                <?php
            endforeach;
            ?>
        </table>
    </td>
</tr>

<tr>
    <td colspan="4">
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo TICKETNO;?></td>
                <td><?php echo FACEFARE;?></td>
                <td><?php echo COMM;?></td>
                <td><?php echo COMMAMT;?></td>
                <td><?php echo FCTAX;?></td>
                <td><?php echo FCTAX;?></td>
                <td><?php echo FCTAX;?></td>
                <td><?php echo TOTALTAX;?></td>
                <td><?php echo NETFARE;?></td>
            </tr>
            <?php
            foreach ($ddlPassenger as $key1 => $pitem): ?>
                <tr>
                    <td></td>

                    <td><label>
                        <?php echo stripslashes($txtFcTax[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtCommTax[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtORCAmt[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtFc1Tax[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtFc2Tax[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtFc3Tax[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtTotalTax[$key1]);?>
                    </label></td>

                    <td><label>
                        <?php echo stripslashes($txtNetVendor[$key1]);?>
                    </label></td>
                </tr>

                <?php
                $totFcTax = $totFcTax + $txtFcTax[$key1];
                $totCommTax = $totCommTax + $txtCommTax[$key1];
                $totORCAmt = $totORCAmt + $txtORCAmt[$key1];
                $totFc1Tax = $totFc1Tax + $txtFc1Tax[$key1];
                $totFc2Tax = $totFc2Tax + $txtFc2Tax[$key1];
                $totFc3Tax = $totFc3Tax + $txtFc3Tax[$key1];
                $totTotalTax = $totTotalTax + $txtTotalTax[$key1] ;
                $totNetVendor = $totNetVendor + $txtNetVendor[$key1] ;
                endforeach;?>
                <tr> 
                <td> <?php echo TOTAL; ?> </td>
                <td> <?php echo $totFcTax; ?> </td>
                <td> <?php echo $totCommTax; ?> </td>
                <td> <?php echo $totORCAmt; ?> </td>
                <td> <?php echo $totFc1Tax; ?> </td>
                <td> <?php echo $totFc2Tax; ?> </td>
                <td> <?php echo $totFc3Tax; ?> </td>
                <td> <?php echo $totTotalTax; ?> </td>
                <td> <?php echo $totNetVendor; ?> </td>
                </tr>
        </table>
    </td>

    <td>
        <table width="100%" class="reportForm">
            <tr>
                <td><?php echo AIRLINEREFERENCE;?></td>
            </tr>

            <tr>
                <td class="bigBox"></td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td>
        <table width="100%">
            <tr>
                <td><?php echo STAMPANDSIGN;?>&nbsp;</td>
            </tr>
            <tr>
                <td><label>
                  &nbsp;
                </label></td>
            </tr>
        </table>
    </td>

    <td colspan="5">
        <table width="100%" class="reportForm">
             <tr>
                 <td><?php echo ISSUEDBY;?> </td>
                 <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?></td>
             </tr>
            <tr>
                <td><?php echo CONTACTPHONE;?> </td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vPhone'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td><?php echo EMAIL;?> </td>
                <td><?php echo $this->common_model->get_field_name('EmployeeMaster','vEmail'," WHERE nEId='".$this->ddlIssued."'");?></td>
            </tr>
            <tr>
                <td colspan="2" class="bigBox">
                <?php echo REMARKS; echo ": " . stripslashes($this->txtRemarks);?> </td>
            </tr>
        </table>
    </td>
</tr>

<tr>
    <td><?php echo NOTES;?></td>
    <td colspan="5"></td>
</tr>
</tbody>
</table>
