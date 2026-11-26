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
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});

$(document).ready(function()
{
	$("#txtArrDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	$("#txtDepDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	$('#txtPickupDate').datepick({ dateFormat: 'dd-mm-yyyy'});
});
</script>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tr>
	<td colspan="8"><label><strong><?php echo TRANSFERDETAILS;?></strong></label></td>
</tr>

<tr>
    <td><?php echo CUSTDATE;?></td>
    <td><label>
     <input name="txtArrDate" id="txtArrDate" class="textfield3" type="text" value="<?php echo $this->txtArrDate;?>" size="20">
    </label></td>

    <td><?php echo ARRFROM;?></td>
    <td><label>
     <input name="txtArrFrom" id="txtArrFrom" class="textfield3" type="text" value="<?php echo $this->txtArrFrom;?>" size="20">
    </label></td>

    <td><?php echo FLIGHT;?></td>
    <td><label>
     <input name="txtArrFlight" id="txtArrFlight" class="textfield3" type="text" value="<?php echo $this->txtArrFlight;?>" size="20">
    </label></td>

    <td><?php echo ARRTIME;?></td>
    <td><label>
     <input name="txtArrTime" id="txtArrTime" class="textfield3" type="text" value="<?php echo $this->txtArrTime;?>" size="20">
    </label></td>
</tr>

<tr>
    <td><?php echo DATE;?></td>
    <td><label>
     <input name="txtDepDate" id="txtDepDate" class="textfield3" type="text" value="<?php echo $this->txtDepDate;?>" size="20">
    </label></td>

    <td><?php echo DEPTO;?></td>
    <td><label>
     <input name="txtDepTo" id="txtDepTo" class="textfield3" type="text" value="<?php echo $this->txtDepTo;?>" size="20">
    </label></td>

    <td><?php echo FLIGHT;?></td>
    <td><label>
     <input name="txtDepFlight" id="txtDepFlight" class="textfield3" type="text" value="<?php echo $this->txtDepFlight;?>" size="20">
    </label></td>

    <td><?php echo DEPTIME;?></td>
    <td><label>
     <input name="txtDepTime" id="txtDepTime" class="textfield3" type="text" value="<?php echo $this->txtDepTime;?>" size="20">
    </label></td>
</tr>

<tr>
    <td><?php echo NOOFGUEST;?></td>
    <td><label>
     <input name="txtNoofGuest" id="txtNoofGuest" class="textfield3" type="text" value="<?php echo $this->txtNoofGuest;?>" size="20">
    </label></td>

    <td><?php echo VEHICLE;?></td>
    <td><label>
     <input name="txtVehicle" id="txtVehicle" class="textfield3" type="text" value="<?php echo $this->txtVehicle;?>" size="20">
    </label></td>
</tr>

<tr>

    <td><?php echo DRIVERCONTACT;?></td>
    <td><label>
     <input name="txtDriverCont" id="txtDriverCont" class="textfield3" type="text" value="<?php echo $this->txtDriverCont;?>" size="20">
    </label></td>

    <td><?php echo EMERGENCYCONTACT;?></td>
    <td><label>
     <input name="txtEmrContact" id="txtEmrContact" class="textfield3" type="text" value="<?php echo $this->txtEmrContact;?>" size="20">
    </label></td>
</tr>

<tr>
	<td colspan="8"><label><strong><?php echo TRANSPORTDETAILS;?></strong></label></td>
</tr>

<tr>
    <td><?php echo PICKUP;?></td>
    <td><label>
     <input name="txtPickup" id="txtPickup" class="textfield3" type="text" value="<?php echo $this->txtPickup;?>" size="20">
    </label></td>

    <td><?php echo PICKUPDATE;?></td>
    <td><label>
     <input name="txtPickupDate" id="txtPickupDate" class="textfield4" type="text" value="<?php echo $this->txtPickupDate;?>" size="20">
    </label></td>
</tr>

<tr>
    <td><?php echo DROP;?></td>
    <td><label>
     <input name="txtDrop" id="txtDrop" class="textfield3" type="text" value="<?php echo $this->txtDrop;?>" size="20">
    </label></td>

    <td><?php echo REPORTINGHOTEL;?></td>
    <td><label>
     <input name="txtRepHtl" id="txtRepHtl" class="textfield3" type="text" value="<?php echo $this->txtRepHtl;?>" size="20">
    </label></td>
</tr>

<tr>
    <td><?php echo CITYTOUR;?></td>
    <td><label>
     <input name="txtCityTour" id="txtCityTour" class="textfield3" type="text" value="<?php echo $this->txtCityTour;?>" size="20">
    </label></td>

    <td><?php echo DURATIONTIME;?></td>
    <td><label>
     <input name="txtDuration" id="txtDuration" class="textfield3" type="text" value="<?php echo $this->txtDuration;?>" size="20">
    </label></td>
</tr>

<tr>

    <td><?php echo REMARKS;?></td>
    <td><label>
     <textarea name="txtTransferRemarks" id="txtTransferRemarks" rows="2" cols="1"><?php echo $this->txtTransferRemarks;?></textarea>
    </label></td>
</tr>

</table>
