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
<script type="text/javascript">
$(document).ready(function()
{

    $('#ddlAcCode').change(function(e) {
        var nVal  = $('#ddlAcCode').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAcCode:nVal},
                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountHead/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#accHead').html(response);
                }
            });
    });
});
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tr>
    <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlAcCode', $list_accode_value, $this->ddlAcCode,'id="ddlAcCode"');?> 
    </label></td>

    <td><?php echo ACCHEAD;?></td>
    <td><label>
    <span id="accHead">
     <?php echo form_dropdown('ddlAccHead', $list_acchead_value, $this->ddlAccHead,'id="ddlAccHead"');?>
    </span>
    </label></td>

    <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
    <td><label>
     <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency"');?> 
    </label></td>
    
    <td><?php echo CURRRATE;?> <span class="warning">*</span></td>
    <td colspan="5"><label>
     <input name="txtRate" id="txtRate" class="textfield3" type="text" value="<?php echo $this->txtRate;?>" size="20">
    </label></td>
</tr>

<tr>
  <td colspan="7">
        <span class="clears"></span>
        <table id="dataTable" title="dataTable">
        <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
        <tr>
            <td><?php echo SRNO;?></td>
            <td><?php echo ROOMTYPE;?></td>
            <td><?php echo INDATE;?></td>
            <td><?php echo OUTDATE;?></td>
            <td><?php echo NIGHTS;?></td>
            <td><?php echo NOOFROOMS;?></td>
            <td><?php echo RATENETFC;?></td>
            <td><?php echo TOTALINFC;?></td>
            <td><?php echo RATENETDC;?></td>
            <td><?php echo TOTALINDC;?></td>
            <td colspan="8"><?php echo EXCHRATE;?></td>
        </tr>
        
        <?php
        if(!empty($txtPayRoomType))
        {
            foreach($txtPayRoomType as $key => $aitem):
            ?>
            <tr  class="re">
                <td><?php echo ($key+1);?></td>
                <td><input name="txtPayRoomType[]" id="txtPayRoomType<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($txtPayRoomType[$key]);?>"></td>
                <td><input name="txtChkinDate[]" id="txtChkinDate<?php echo $key;?>" class="textfield5 txtChkinDate" type="text" value="<?php echo stripslashes($txtChkinDate[$key]);?>"></td>
                <td><input name="txtChkoutDate[]" id="txtChkoutDate<?php echo $key;?>" class="textfield5 txtChkoutDate" type="text" value="<?php echo stripslashes($txtChkoutDate[$key]);?>"></td>
                <td><input name="txtNight[]" id="txtNight<?php echo $key;?>" class="textfield3 txtNight" type="text" value="<?php echo stripslashes($txtNight[$key]);?>"></td>
                <td><input name="txtNoofRooms[]" id="txtNoofRooms<?php echo $key;?>" class="textfield3" type="text" value="<?php echo stripslashes($txtNoofRooms[$key]);?>"></td>
                <td><input name="txtRateNetFc[]" id="txtRateNetFc<?php echo $key;?>" class="textfield3 rateNetFc" type="text" value="<?php echo stripslashes($txtRateNetFc[$key]);?>"></td>
                <td><input name="txtTotalinFc[]" id="txtTotalinFc<?php echo $key;?>" class="textfield3 totalFc" type="text" value="<?php echo stripslashes($txtTotalinFc[$key]);?>"></td>
                <td><input name="txtRateNetDc[]" id="txtRateNetDc<?php echo $key;?>" class="textfield3 rateNetDc" type="text" value="<?php echo stripslashes($txtRateNetDc[$key]);?>"></td>
                <td><input name="txtTotalinDc[]" id="txtTotalinDc<?php echo $key;?>" class="textfield3 totalDc" type="text" value="<?php echo stripslashes($txtTotalinDc[$key]);?>"></td>
                <td><input name="txtExchangeRate[]" id="txtExchangeRate<?php echo $key;?>" class="txtExchangeRate textfield3" type="text" value="<?php echo stripslashes($txtExchangeRate[$key]);?>"></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkHotelID[$key];?>" title="Delete"/></td>
            </tr>
    <?php 
        endforeach;
    }//end if
    else
    {?>
            <tr class="re">
                <td>#</td>
                <td><input name="txtPayRoomType[]" id="txtPayRoomType" class="textfield5" type="text" value=""></td>
                <td><input name="txtChkinDate[]" id="txtChkinDate" class="textfield5 txtChkinDate" type="text" value=""></td>
                <td><input name="txtChkoutDate[]" id="txtChkoutDate" class="textfield5 txtChkoutDate" type="text" value=""></td>
                <td><input name="txtNight[]" id="txtNight" class="textfield3 txtNight" type="text" value=""></td>
                <td><input name="txtNoofRooms[]" id="txtNoofRooms" class="textfield3" type="text" value=""></td>
                <td><input name="txtRateNetFc[]" id="txtRateNetFc" class="textfield3 rateNetFc" type="text" value=""></td>
                <td><input name="txtTotalinFc[]" id="txtTotalinFc" class="textfield3 totalFc" type="text" value=""></td>
                <td><input name="txtRateNetDc[]" id="txtRateNetDc" class="textfield3 rateNetDc" type="text" value=""></td>
                <td><input name="txtTotalinDc[]" id="txtTotalinDc" class="textfield3 totalDc" type="text" value=""></td>
                <td><input name="txtExchangeRate[]" id="txtExchangeRate" class="txtExchangeRate textfield3" type="text" value=""></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>
            </tr>
        <?php
            }//end else
        ?>

            <tr>
                <td colspan="4">&nbsp;</td>
                <td colspan="2"><?php echo TOTALAMT;?></td>
                <td><input name="txtRateNetFcTotal" class="textfield3" id="txtRateNetFcTotal" type="text" value="<?php echo $this->txtRateNetFcTotal;?>"></td>
                <td><input name="txtTotalFc" class="textfield3" id="txtTotalFc" type="text" value="<?php echo $this->txtTotalFc;?>"></td>
                <td><input name="txtRateNetDcTotal" class="textfield3" id="txtRateNetDcTotal" type="text" value="<?php echo $this->txtRateNetDcTotal;?>"></td>
                <td><input name="txtTotalDc" class="textfield3" id="txtTotalDc" type="text" value="<?php echo $this->txtTotalDc;?>"></td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
            </tr>

        </table>
    </td>
</tr>



</table>