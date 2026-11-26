<table width="100%" border="0" cellspacing="10" cellpadding="10" class="printForm"><style>td{	text-transform:uppercase;}</style>
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
                <table align="center" width="100%" border="0" cellspacing="10" cellpadding="10">
                <tr><td align="center" style="text-transform:lowercase";>
				
				<img align="center" align="center" style="width:25% !important;" src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo">
                </td></tr>
				<tr>
				<td colspan="8">
                <div align="center" class="print_desc" ><br>
                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>
                <?php echo $addr;?><br>
                <?php echo PHONE;?>: <?php echo $phone;?>,
                <?php echo FAX;?>: <?php echo $mobile;?><br>
                <?php echo EMAIL;?>: <?php echo $email;?><br>
                <?php echo WEB;?>: <?php echo $web;?> 
                </div>
                </td></tr></table> </td>
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
	<td>:</td>
    <td>
        <?php echo $this->txtTktName;?>

    </td></tr>
<tr>
    <td><?php echo CUSTDATE;?></td><td>:</td>
    <td><label>
        <?php echo date('d-m-Y',strtotime($this->txtDate));?>

    </label></td>
</tr><tr>
    <td><?php echo SERVICES;?></td><td>:</td>
    <td><label>
        <?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?>,
            <?php echo $this->common_model->get_field_name('Transfer','vName'," WHERE nTId='".$this->ddlTransfer."'");?>,
            <?php echo stripslashes($this->txtExtraServ);?>

    </label></td>

</tr>

<tr>
    <td><?php echo LEADNAMES;?></td><td>:</td>
    <td colspan="5"> <?php echo stripslashes($this->txtPassenger);?>  </td>
</tr>

<tr>
    <td><?php echo TOBEPRESENTED;?></td><td>:</td>
     <td colspan="5"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?> </td>
</tr>

<tr>
    <td><?php echo COUNTRYNAME;?></td><td>:</td>
    <td><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></td>
</tr><tr>
    

    <td><?php echo CITYNAME;?></td><td>:</td>
    <td><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></td>
</tr>

<tr>
    <td><?php echo ADDRESS;?></td><td>:</td>
    <td>
        
            <?php echo $this->common_model->get_field_name('Hotel','vAddress'," WHERE nHId='".$this->ddlHotel."'");?>
        
    </td>
</tr><tr>
    <td><?php echo PHONE;?></td><td>:</td>
    <td><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></td>
</tr>

<tr>
    <td><?php echo REMARKS;?></td><td>:</td>
    <td colspan="5"><label>
        <?php echo stripslashes($this->txtRemarks);?>
    </label></td>
</tr>

<tr>
    <td colspan="7">
        <table width="100%" class="reportForm" style="border-collapse:collapse;" border="1">
            <tr>
                <td><?php echo CHECKIN;?></td>
                <td><?php echo CHECKOUT;?></td>
                <td><?php echo NIGHTS . "&nbsp;";?></td>
                <td><?php echo ADULT . " &nbsp;";?></td>
                <td><?php echo CHILD . " &nbsp;";?></td>
                <td><?php echo ROOMTYPE . " &nbsp;";?></td>
                <td><?php echo NOOFROOMS . " &nbsp;";?></td>
            </tr>

            <tr>
                <td><label>
                    <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckIn))) . " &nbsp;";?>
                </label></td>

                <td><label>
                    <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckOut))) . " &nbsp;";?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtNights) . " &nbsp;";?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtAdult) . " &nbsp;";?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtChild) . " &nbsp;";?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtRoomType) . " &nbsp;";?>
                </label></td>

                <td><label>
                    <?php echo stripslashes($this->txtNoOfUnit) . " &nbsp;";?>
                </label></td>
            </tr>

        </table>
    </td>
</tr>



<tr>    <td colspan="4">        <?php echo"<br><br><br>";echo ISSUEDBY;?> : <br>        <?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?></td></tr>
</tbody> 
</table>

</body>
</html>
