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
             <?php echo form_dropdown('ddlCustomerAc', $list_custacc_value, $this->ddlCustomerAc,'id="ddlCustomerAc"');?>
           </label></td>
           
           <td colspan="8"><label>
            <?php echo form_dropdown('ddlAcAgentAc', $list_agentacc_value, $this->ddlAcAgentAc,'id="ddlAcAgentAc"');?>
           </label></td>
        </tr>
        
		<tr>
        	<td><?php echo AGENTAMT;?></td>
            <td><label>
            <input name="txtAcAgentAmt" id="txtAcAgentAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcAgentAmt);?>">
           </label></td>
           
          <td><?php echo LPO;?></td>
          <td colspan="8"><label>
            <input name="txAcLPO" id="txAcLPO" class="textfield5" type="text" value="<?php echo stripslashes($this->txAcLPO);?>">
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
             <input name="txtAcSalesAmt" id="txtAcSalesAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcSalesAmt);?>">
            </label></td>
           
           <td><label>
            <input name="txtAcDiscount" id="txtAcDiscount" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcDiscount);?>">
           </label></td>
           
           <td><label>
            <input name="txtAcDiscountAmt" id="txtAcDiscountAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcDiscountAmt);?>">
            </label></td>
           
           <td><label>
            <input name="txtAcCollected" id="txtAcCollected" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcCollected);?>">
           </label></td>
           
           <td><label>
            <input name="txtAcServicePer" id="txtAcServicePer" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcServicePer);?>">
           </label></td>
           
           <td><label>
            <input name="txtAcServiceAmt" id="txtAcServiceAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAcServiceAmt);?>">
           </label></td>
        </tr>
                </table>
            </div>
        </div>
    </td>
</tr>           
</table>