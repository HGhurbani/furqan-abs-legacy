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
    function checkAll()
    {
        if(document.getElementById('chkAll').checked==false)
        {
            uncheckAll();
        }//end if
        else
        {
            for (var i = 0; i < document.frmGroup.elements.length; i++)
            {
                var e = document.frmGroup.elements[i];
                if (e.name == "del[]")
                {
                    e.checked = true;
                }//end if
            }//end for loop
        }//end else
    }//end function
    function uncheckAll()
    {
        for (var i = 0; i < document.frmGroup.elements.length; i++)
        {
            var e = document.frmGroup.elements[i];
            if (e.name == "del[]")
            {
                e.checked = false;
            }//end if
        }//end for loop
    }//end function

</script>

<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
    <tr>
        <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
        <th align="left">#</th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo TREFSTATUS;?></th>
        <th align="left"><?php echo DOCNUMBER;?></th>
        <th align="left"><?php echo TYPENAME;?></th>
        <th align="left"><?php echo AIRLINECODE;?></th>
        <th align="left"><?php echo TICKETNAME;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo PAIDAMOUNT;?></th>
        <th align="left"><?php echo GDS;?></th>
        <th align="left"><?php echo DETAILS;?></th>
        <th align="left"><?php echo ACTIVE;?></th>
        <th align="left"><?php echo STATUS;?></th>
        <th align="left"><?php echo ACTION;?></th>
    </tr>
    </thead>

    <tfoot>
    <tr class="formButs">
        <td colspan="15">
            <?php if(!empty($list_value))
        {
            ?>
            <div class="bulk-actions align-left">
                <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                <input type="submit" value="<?php echo INVOICEBULK;?>" class="button" name="btnGo" id="btnGo"/>
                <?php endif;?>
            </div>
            <?php
        }//end if
            ?>
        </td>
    </tr>
    </tfoot>

    <tbody>
    <?php $i= 1;
    if(!empty($list_value)) {?>
        <?php foreach($list_value as $key=>$item):
            $getAirlineDetails  = $this->refund_register_model->returnAirlineDetails($item->nRId);
            $getTktDetails      = $this->refund_register_model->returnTktDetails($item->nRId);
            $getNetDetails      = $this->refund_register_model->returnNetDetails($item->nRId);
            $getTicketType      = $this->refund_register_model->returnTicketType($item->nRId);
            $getPaidAmount      = $this->refund_register_model->returnPaidAmount($item->nRId);
            ?>
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nRId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
            <td align="left"><?php echo ($item->vType=='R')?REFUND:REFUNDVOID;?></td>
            <td align="left">
                <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                <a href="<?php echo base_url();?>index.php/refund_register/editRefund/<?php echo $this->uri->segment(3).'/e/'.$item->nRId;?>" title="<?php echo EDIT;?>"><?php echo $item->vDocNumber;?></a>
                <?php endif;?>
            </td>
            <td align="left"><?php echo $getTicketType;?></td>
            <td align="left"><?php echo $getAirlineDetails;?></td>
            <td align="left"><?php echo $getTktDetails;?></td>
            <td align="left"><?php echo $getPaidAmount;?></td>
            <td align="left"><?php echo $getNetDetails;?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$item->nGDS."'");?></td>
            <td align="left"><a class="popup-link-<?php echo $item->nRId;?>"><?php echo VIEW;?></a></td>
            <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
            <td align="left">&nbsp;</td>
            <td align="left">
                <!-- Icons -->
                <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                <a href="<?php echo base_url();?>index.php/refund_register/delxobooking/<?php echo $this->uri->segment(3).'/'.$item->nRId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
                <!-- Start of Sticky Note -->
                <div class="popup-box" id="popup-box-<?php echo $item->nRId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                        <div class="wa">
                            <?php echo $this->refund_register_model->showDetails($item->nRId);?>
                        </div>
                    </div>
                </div>
                <!-- End of Sticky Note -->
            </td>
        </tr>
            <?php $i= $i+1;?>
            <?php endforeach;?>
        <?php }?>
    <?php if(empty($list_value))
    {
        ?>
    <tr>
        <td colspan="15" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>