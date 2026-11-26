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
    $('#ddlMode').change(function()
    {
        var nVal  = $('#ddlMode').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlMode:nVal},
                url:'<?php echo base_url().'index.php/hotel_voucher/filterCustomerAcc/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#accCust').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#accCust').html(response);
                }
            });
    });

	<?php if($this->uri->segment(4) == 'e') {?>
	 var nVal  = '<?php echo $this->ddlMode;?>';
        $.ajax(
            {
                type:'POST',
                data:{ddlMode:nVal,ddlCustomerAc:'<?php echo $this->ddlCustomerAc;?>'},
                url:'<?php echo base_url().'index.php/hotel_voucher/filterCustomerAcc/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#accCust').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#accCust').html(response);
                }
            });
	<?php }//end if?>


});
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tr>
            <td colspan="7">
            <div>
                <span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span>
                <span class="clears"></span>
                <div class="listcontnts">
                <table id="dataControl" title="dataControl">
                    <tr>
        <tr>
          <td><?php echo MODEOFPAYMENT;?></td>
          <td><?php echo CUSTACCNAME;?></td>
          <td colspan="8"><?php echo AGENTAC;?></td>
        </tr>
        
        <tr>
            <td><label>
             <?php echo form_dropdown('ddlMode', $list_mode_value, $this->ddlMode,'id="ddlMode" class="ddlSearch"');?>
            </label></td>
           
           <td><label>
           <span id="accCust">
             <?php echo form_dropdown('ddlCustomerAc', $list_custacc_value, $this->ddlCustomerAc,'id="ddlCustomerAc"');?>
           </span>
           </label></td>
           
           <td colspan="8"><label>
            <?php echo form_dropdown('ddlAgentAc', $list_agentacc_value, $this->ddlAgentAc,'id="ddlAgentAc"');?>
           </label></td>
        </tr>
        
		<tr>
        	<td><?php echo AGENTAMT;?></td>
            <td><label>
            <input name="txtAgentAmt" id="txtAgentAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAgentAmt);?>">
           </label></td>
           
          <td><?php echo LPO;?></td>
          <td colspan="8"><label>
            <input name="txtLPO" id="txtLPO" class="textfield5" type="text" value="<?php echo stripslashes($this->txtLPO);?>">
           </label></td>
        </tr>
               
        <tr>
          <td><?php echo SALESAMT;?></td>
          <td><?php echo DISCOUNT;?></td>
          <td><?php echo DISCOUNTAMT;?></td>
          <td><?php echo COLECTED;?></td>
          <td><?php echo SERVICEPERCENT;?></td>
          <td colspan="8"><?php echo SERVICEAMT;?></td>
        </tr>
        
        <tr>
            <td><label>
             <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSalesAmt);?>">
            </label></td>
           
           <td><label>
            <input name="txtDiscount" id="txtDiscount" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDiscount);?>">
           </label></td>
           
           <td><label>
            <input name="txtDiscountAmt" id="txtDiscountAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDiscountAmt);?>">
            </label></td>
           
           <td><label>
            <input name="txtCollected" id="txtCollected" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCollected);?>">
           </label></td>
           
           <td><label>
            <input name="txtServicePerc" id="txtServicePerc" class="textfield5" type="text" value="<?php echo stripslashes($this->txtServicePerc);?>">
           </label></td>
           
           <td><label>
            <input name="txtServiceAmt" id="txtServiceAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtServiceAmt);?>">
           </label></td>
        </tr>
                </table>
            </div>
        </div>
    </td>
</tr>           
</table>
