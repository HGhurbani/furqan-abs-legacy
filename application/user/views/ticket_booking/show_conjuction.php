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
<script type="text/javascript" language="javascript1.1">
$(document).ready(function()
{
	$('#txtTimeDepTkt1').keyup(function(e) {
		var tVal = 'txtTimeDepTkt1';
		$('#'+tVal).val(getTimeFormat(tVal));
    });
	
	$('#txtTimeDepTkt2').keyup(function(e) {
		var tVal = 'txtTimeDepTkt2';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeDepTkt3').keyup(function(e) {
		var tVal = 'txtTimeDepTkt3';
		$('#'+tVal).val(getTimeFormat(tVal));
    });
	
	$('#txtTimeDepTkt4').keyup(function(e) {
		var tVal = 'txtTimeDepTkt4';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeArrTkt1').keyup(function(e) {
		var tVal = 'txtTimeArrTkt1';
		$('#'+tVal).val(getTimeFormat(tVal));
    });
	
	$('#txtTimeArrTkt2').keyup(function(e) {
		var tVal = 'txtTimeArrTkt2';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeArrTkt3').keyup(function(e) {
		var tVal = 'txtTimeArrTkt3';
		$('#'+tVal).val(getTimeFormat(tVal));
    });
	
	$('#txtTimeArrTkt4').keyup(function(e) {
		var tVal = 'txtTimeArrTkt4';
		$('#'+tVal).val(getTimeFormat(tVal));
    });
	
	function getTimeFormat(tVal)
	{
		if($('#'+tVal).val().length == 2)
		{
			var newVal = $('#'+tVal).val()+':';
			return newVal;
		}//end if
	}//end function
});
</script>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tr>
    <td colspan="7">
    <div>
        <span style="font-size: 12px;font-weight: bold;"><?php echo CONJUCTIONDETAILS;?></span>
        <span class="clears"></span>
        <table>
            <tr>
                <td><?php echo CONJUCTIONTKT;?></td>
                <td><label>
                <input name="txtConjTkt" id="txtConjTkt" class="textfield" type="text" value="<?php echo stripslashes($this->txtConjTkt);?>">
                </label></td>
            </tr>
        </table>
        
        <table id="dataControl" title="dataControl">
            <tr>
                <td><?php echo SRNO;?></td>
                <td><?php echo CARRIER;?></td>
                <td><?php echo FLIGHTNO;?></td>
                <td><?php echo TKTCLASS;?></td>
                <td><?php echo TRAVELDATE;?></td>
                <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo SECTOR;?></td>
                <td>&nbsp;</td>
                <td><?php echo STATUS;?></td>
                <td><?php echo TIMEDEP;?></td>
                <td><?php echo TIMEARR;?></td>
                <td><?php echo DAY;?></td>
            </tr>
            
            <tr>
                <td>1</td>
                <td><input name="txtCarrierTkt1" id="txtCarrierTkt1" class="textfield3 chkCnjSegment" type="text" value="<?php echo stripslashes($this->txtCarrierTkt1);?>"></td>
                <td><input name="txtFlightNoTkt1" id="txtFlightNoTkt1" class="textfield3" type="text" value="<?php echo stripslashes($this->txtFlightNoTkt1);?>"></td>
                <td><input name="txtClassTkt1" id="txtClassTkt1" class="textfield3" type="text" value="<?php echo stripslashes($this->txtClassTkt1);?>"></td>
                <td><input name="txtTravelDateTkt1" id="txtTravelDateTkt1" class="textfield4 chkTktDate" type="text" value="<?php echo stripslashes($this->txtTravelDateTkt1);?>"></td>
                <td><input name="txtSectorTkt1" id="txtSectorTkt1" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt1);?>"></td>
                <td><input name="txtSectorTkt12" id="txtSectorTkt12" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt12);?>"></td>
                <td><input name="txtStatusTkt1" id="txtStatusTkt1" class="textfield3" type="text" value="<?php echo stripslashes($this->txtStatusTkt1);?>"></td>
                <td><input name="txtTimeDepTkt1" id="txtTimeDepTkt1" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeDepTkt1);?>"></td>
                <td><input name="txtTimeArrTkt1" id="txtTimeArrTkt1" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeArrTkt1);?>"></td>
                <td><input name="txtDayTkt1" id="txtDayTkt1" class="textfield3 chkCnjDay" type="text" value="<?php echo stripslashes($this->txtDayTkt1);?>"></td>
           </tr>
           
           <tr>
                <td>2</td>
                <td><input name="txtCarrierTkt2" id="txtCarrierTkt2" class="textfield3 chkCnjSegment" type="text" value="<?php echo stripslashes($this->txtCarrierTkt2);?>"></td>
                <td><input name="txtFlightNoTkt2" id="txtFlightNoTkt2" class="textfield3" type="text" value="<?php echo stripslashes($this->txtFlightNoTkt2);?>"></td>
                <td><input name="txtClassTkt2" id="txtClassTkt2" class="textfield3" type="text" value="<?php echo stripslashes($this->txtClassTkt2);?>"></td>
                <td><input name="txtTravelDateTkt2" id="txtTravelDateTkt2" class="textfield4 chkTktDate" type="text" value="<?php echo stripslashes($this->txtTravelDateTkt2);?>"></td>
                <td><input name="txtSectorTkt2" id="txtSectorTkt2" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt2);?>"></td>
                <td><input name="txtSectorTkt22" id="txtSectorTkt22" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt22);?>"></td>
                <td><input name="txtStatusTkt2" id="txtStatusTkt2" class="textfield3" type="text" value="<?php echo stripslashes($this->txtStatusTkt2);?>"></td>
                <td><input name="txtTimeDepTkt2" id="txtTimeDepTkt2" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeDepTkt2);?>"></td>
                <td><input name="txtTimeArrTkt2" id="txtTimeArrTkt2" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeArrTkt2);?>"></td>
                <td><input name="txtDayTkt2" id="txtDayTkt2" class="textfield3 chkCnjDay" type="text" value="<?php echo stripslashes($this->txtDayTkt2);?>"></td>
           </tr>
           
           <tr>
                <td>3</td>
                <td><input name="txtCarrierTkt3" id="txtCarrierTkt3" class="textfield3 chkCnjSegment" type="text" value="<?php echo stripslashes($this->txtCarrierTkt3);?>"></td>
                <td><input name="txtFlightNoTkt3" id="txtFlightNoTkt3" class="textfield3" type="text" value="<?php echo stripslashes($this->txtFlightNoTkt3);?>"></td>
                <td><input name="txtClassTkt3" id="txtClassTkt3" class="textfield3" type="text" value="<?php echo stripslashes($this->txtClassTkt3);?>"></td>
                <td><input name="txtTravelDateTkt3" id="txtTravelDateTkt3" class="textfield4 chkTktDate" type="text" value="<?php echo stripslashes($this->txtTravelDateTkt3);?>"></td>
                <td><input name="txtSectorTkt3" id="txtSectorTkt3" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt3);?>"></td>
                <td><input name="txtSectorTkt33" id="txtSectorTkt33" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt33);?>"></td>
                <td><input name="txtStatusTkt3" id="txtStatusTkt3" class="textfield3" type="text" value="<?php echo stripslashes($this->txtStatusTkt3);?>"></td>
                <td><input name="txtTimeDepTkt3" id="txtTimeDepTkt3" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeDepTkt3);?>"></td>
                <td><input name="txtTimeArrTkt3" id="txtTimeArrTkt3" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeArrTkt3);?>"></td>
                <td><input name="txtDayTkt3" id="txtDayTkt3" class="textfield3 chkCnjDay" type="text" value="<?php echo stripslashes($this->txtDayTkt3);?>"></td>
           </tr>

           <tr>
                <td>4</td>
                <td><input name="txtCarrierTkt4" id="txtCarrierTkt4" class="textfield3 chkCnjSegment" type="text" value="<?php echo stripslashes($this->txtCarrierTkt4);?>"></td>
                <td><input name="txtFlightNoTkt4" id="txtFlightNoTkt4" class="textfield3" type="text" value="<?php echo stripslashes($this->txtFlightNoTkt4);?>"></td>
                <td><input name="txtClassTkt4" id="txtClassTkt4" class="textfield3" type="text" value="<?php echo stripslashes($this->txtClassTkt4);?>"></td>
                <td><input name="txtTravelDateTkt4" id="txtTravelDateTkt4" class="textfield4 chkTktDate" type="text" value="<?php echo stripslashes($this->txtTravelDateTkt4);?>"></td>
                <td><input name="txtSectorTkt4" id="txtSectorTkt4" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt4);?>"></td>
                <td><input name="txtSectorTkt44" id="txtSectorTkt44" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSectorTkt44);?>"></td>
                <td><input name="txtStatusTkt4" id="txtStatusTkt4" class="textfield3" type="text" value="<?php echo stripslashes($this->txtStatusTkt4);?>"></td>
                <td><input name="txtTimeDepTkt4" id="txtTimeDepTkt4" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeDepTkt4);?>"></td>
                <td><input name="txtTimeArrTkt4" id="txtTimeArrTkt4" class="textfield3" type="text" value="<?php echo stripslashes($this->txtTimeArrTkt4);?>"></td>
                <td><input name="txtDayTkt4" id="txtDayTkt4" class="textfield3 chkCnjDay" type="text" value="<?php echo stripslashes($this->txtDayTkt4);?>"></td>
           </tr>
        </table>
        </div>
    </td>
</tr>

<tr>
    <td><?php echo SEGMENT;?> <span class="warning">*</span></td>
    <td><label>
    <input name="txtSegmentCnjTkt" id="txtSegmentCnjTkt" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSegmentCnjTkt);?>">
    </label></td>

    <td><?php echo DESTINATION;?> <span class="warning">*</span></td>
    <td><label>
    <input name="txtDestinationCnjTkt" id="txtDestinationCnjTkt" class="textfield3" type="text" value="<?php echo stripslashes($this->txtDestinationCnjTkt);?>">
    </label></td>
</tr>            
</table>