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
  $("#txtTravelDate").datepick({ dateFormat: 'yyyy-mm-dd'});

/*------------------Custom Add new row for account------------------*/
  var i = 1;
  $("#addRow1").click(function() {
	  var m	       = $('#dataTicket tbody>tr:last').clone(true).find("input").each(function() {
					var id = this.id;
					$(this).attr({
					  'id': function(_, id2) { return id + i; },
					  'value': ''               
					});
					}).end().insertAfter('#dataTicket tbody>tr:last');
				 i++;

	  var rowCount = $("#dataTicket").attr('rows').length;
	   m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
	   return false;
});
	
/*------------------Custom delete row ------------------*/	
	
	$('.del1').live('click',function(){
		var rowCount = $("#dataTicket").attr('rows').length;
		if((rowCount)>1)
		{
			$(this).parent().parent().remove();
		    //delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/ticket_booking/deleteTicketRecord/'.$this->uri->segment(3);?>',
				 data:{subCode:dId[1]},
				 dataType:'html',
				 beforeSend: function() {
						//$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
					}
			});
		}//end if
		else
		{
			//var src = $(this).attr("src").replace("cross", "cross_grey_small");
			
			$('#del1_'+rowCount).attr('disabled', true);
           // $(this).attr("src", src);

		}//end if
			
	});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<span class="clears"></span>
<div class="listcontnts">
    <table id="dataTicket" title="dataTicket">
    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow1" class="addNew" />
        <tr>
            <td><?php echo SRNO;?></td>
            <td><?php echo CARRIER;?></td>
            <td><?php echo FLIGHTNO;?></td>
            <td><?php echo TKTCLASS;?></td>
            <td><?php echo TRAVELDATE;?></td>
            <td><?php echo SECTOR;?></td>
            <td>&nbsp;</td>
            <td><?php echo STATUS;?></td>
            <td><?php echo TIMEDEP;?></td>
            <td><?php echo TIMEARR;?></td>
            <td colspan="8"><?php echo DAY;?></td>
        </tr>
		<?php
        if(!empty($txtCarrier))
        {
            foreach($txtCarrier as $key => $aitem):
			$idCnt = $key+1;
        ?>
            <tr>
                <td><?php echo $key;?></td>
                <td><input name="txtCarrier[]" id="txtCarrier" class="textfield21" type="text" value="<?php echo stripslashes($aitem);?>"></td>
                <td><input name="txtFlightNo[]" id="txtFlightNo" class="textfield22" type="text" value="<?php echo stripslashes($txtFlightNo[$key]);?>"></td>
                <td><input name="txtClass[]" id="txtClass" class="textfield23" type="text" value="<?php echo stripslashes($txtClass[$key]);?>"></td>
                <td><input name="txtTravelDate[]" id="txtTravelDate" class="textfield24" type="text" value="<?php echo stripslashes($txtTravelDate[$key]);?>"></td>
                <td><input name="txtSector[]" id="txtSector" class="textfield25" type="text" value="<?php echo stripslashes($txtSector[$key]);?>"></td>
                <td><input name="txtSector2[]" id="txtSector2" class="textfield26" type="text" value="<?php echo stripslashes($txtSector2[$key]);?>"></td>
                <td><input name="txtStatus[]" id="txtStatus" class="textfield27" type="text" value="<?php echo stripslashes($txtStatus[$key]);?>"></td>
                <td><input name="txtTimeDep[]" id="txtTimeDep" class="textfield28" type="text" value="<?php echo stripslashes($txtTimeDep[$key]);?>"></td>
                <td><input name="txtTimeArr[]" id="txtTimeArr" class="textfield29" type="text" value="<?php echo stripslashes($txtTimeArr[$key]);?>"></td>
                <td><input name="txtDay[]" id="txtDay" class="textfield30" type="text" value="<?php echo stripslashes($txtDay[$key]);?>"></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del1" id="del_<?php echo $idCnt;?>" title="Delete"/></td>
            </tr>
    <?php 
        endforeach;
    }//end if
    else
    {?>
            <tr>
                <td>#</td>
                <td><input name="txtCarrier[]" id="txtCarrier" class="textfield21" type="text" value="<?php echo stripslashes($this->txtCarrier);?>"></td>
                <td><input name="txtFlightNo[]" id="txtFlightNo" class="textfield22" type="text" value="<?php echo stripslashes($this->txtFlightNo);?>"></td>
                <td><input name="txtClass[]" id="txtClass" class="textfield23" type="text" value="<?php echo stripslashes($this->txtClass);?>"></td>
                <td><input name="txtTravelDate[]" id="txtTravelDate" class="textfield24" type="text" value="<?php echo stripslashes($this->txtTravelDate);?>"></td>
                <td><input name="txtSector[]" id="txtSector" class="textfield25" type="text" value="<?php echo stripslashes($this->txtSector);?>"></td>
                <td><input name="txtSector2[]" id="txtSector2" class="textfield26" type="text" value="<?php echo stripslashes($this->txtSector2);?>"></td>
                <td><input name="txtStatus[]" id="txtStatus" class="textfield27" type="text" value="<?php echo stripslashes($this->txtStatus);?>"></td>
                <td><input name="txtTimeDep[]" id="txtTimeDep" class="textfield28" type="text" value="<?php echo stripslashes($this->txtTimeDep);?>"></td>
                <td><input name="txtTimeArr[]" id="txtTimeArr" class="textfield29" type="text" value="<?php echo stripslashes($this->txtTimeArr);?>"></td>
                <td><input name="txtDay[]" id="txtDay" class="textfield30" type="text" value="<?php echo stripslashes($this->txtDay);?>"></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del1" id="del_<?php echo $idCnt;?>" title="Delete"/></td>
            </tr>
	<?php
        }//end else
    ?>
    </table>
</div>
