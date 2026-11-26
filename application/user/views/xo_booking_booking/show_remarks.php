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
<span class="clears"></span>
<div class="listcontnts">
<table id="dataControl" title="dataControl">
 <tr>
    <td><?php echo REMARKS;?> <span class="warning">*</span></td>
    <td colspan="22"><label>
     <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="10"><?php echo stripslashes($this->txtRemarks);?></textarea> 
    </label></td>
 </tr>
 
 <tr>
    <td><?php echo GDSPNR;?> <span class="warning">*</span></td>
    <td><label>
     <input name="txtGdsPNR" id="txtGdsPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtGdsPNR);?>">
    </label></td>
    
    <td><?php echo AIRPNR;?> <span class="warning">*</span></td>
    <td colspan="20"><label>
     <input name="txtAirPNR" id="txtAirPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAirPNR);?>">
    </label></td>
 </tr>

</table>
</div>

