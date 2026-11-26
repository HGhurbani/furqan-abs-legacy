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

</script>
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">
<div id="boxes">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo DOCNUMBER;?></th>
		   <th align="left"><?php echo REFNAME;?></th>
           <th align="left"><?php echo TICKET;?></th>
           <th align="left"><?php echo CREDITCARD;?></th>
           <th align="left"><?php echo AMOUNT;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="12">
                <?php if(!empty($list_value))
                      {
                ?>
                <div class="bulk-actions align-left">
                    <select name="ddlAction" id="ddlAction">
                        <option value=""><?php echo BULKACTIONS;?></option>
                        <option value="yes"><?php echo ACTIVEYES;?></option>
                        <option value="no"><?php echo ACTIVENO;?></option>
                        <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                        <option value="delete"><?php echo DELETE;?></option>
                        <?php endif;?>
                    </select>
                    <input type="submit" value="<?php echo APPLYACTION;?>" class="button" name="btnGo" id="btnGo"/>
                </div>
                <?php
                        }//end if
                ?>						
            </td>
        </tr>
    </tfoot>
 
    <tbody>
    <?php $i= 1;
            $chkdoc = array();
    ?>
    <?php foreach($list_value as $key=>$item):?>
        <tr>
<?php
$tktNumber = '';
switch($item->vType){
case 'Tkt':
$tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId=".$item->vTktNumber." ");
break;
case 'Htl':
$tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId=".$item->vTktNumber." ");
break;
case 'Xo':
$tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId=".$item->vTktNumber." ");
break;

}// end case

?>
        <?php if(!in_array($item->vDocNumber,$chkdoc)){ ?>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nSRId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
<td align="left"><?php echo  date('d-m-Y',strtotime($item->dDate)); ?></td>
            <td align="left">
                <a href="<?php echo base_url();?>index.php/sales_receipt/editSalesreceipt/<?php echo $this->uri->segment(3).'/e/'.$item->nSRId;?>" title="<?php echo EDIT;?>"> <?php echo stripslashes($item->vDocNumber);?></a>
 <?php //endif;?>
</td>
            <td align="left"><?php echo stripslashes($item->vRefName);?></td>
            <?php }else{ ?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <?php } ?>
            <td> <?php echo stripslashes($tktNumber);?></td>
            <td> <?php echo stripslashes($this->common_model->get_field_name('Card', 'vName'," WHERE  nCId = " . $item->nCardId." "));?></td>
            <td> <?php echo stripslashes($item->nAmountDr);?></td>
        <?php if(!in_array($item->vDocNumber,$chkdoc)){ ?>
            <td align="left">
            <!-- Icons -->
               <?php //user access 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/sales_receipt/delSalesreceipt/<?php echo $this->uri->segment(3).'/'.$item->nSRId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
                </td>
                <?php } ?>
<?php $i= $i+1;
$chkdoc[] = $item->vDocNumber;?>
        <?php endforeach;?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
</tr>
<?php }//end if
?>
</tbody>
</table>
<div class="pagination">
<?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
</div>
</form>
</div>
</div>
  <div id="blackout"></div>
