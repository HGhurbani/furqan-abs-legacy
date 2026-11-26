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
        <th align="left"><?php echo "Ticket #";?></th>
<!--        <th align="left"><?php echo INVOICE;?></th>
        <th align="left"><?php echo INVDATE;?></th>
        <th align="left"><?php echo ACCNAME;?></th> -->
        <th align="left"><?php echo LOCATION;?></th>
        <th align="left"><?php echo SALETYPE;?></th>
        <th align="left"><?php echo AIRLINECODE;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo ISSUEDDATE;?></th>
        <th align="left"><?php echo LPO;?></th>
        <th align="left"><?php echo PASSENGER;?></th>
        <th align="left"><?php echo FACEFARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
    </tr>
    </thead>

    <tfoot>
    <tr class="formButs">
        <td colspan="19">
            <?php if(!empty($list_value))
        {
            ?>
            <div class="bulk-actions align-left">
                <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                <input type="submit" value="<?php echo INVOICEBULK;?>" class="button" name="btnGo" id="btnGo"/>
                <?php endif;?>
            </div>
            <?php
        }//end if ?>
        </td>
    </tr>
    </tfoot>

    <tbody>
    <?php $i= 1;?>
    <?php foreach($list_value as $key=>$item):
        switch($item->sale_type)
        {
            case "Ticket":
                $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$item->tableId;
                break;

            case "Xo":
                $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$item->tableId;
                break;

            case "Hotel":
                $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$item->tableId;
                break;
        }//end switch
        ?>
    <tr>
        <td align="left">
            <input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->tableId;?>-<?php echo $item->sale_type;?>" class="chcktbl"/>
            <input type="hidden" name="txtTktType<?php echo $item->tableId;?>" id="txtTcktType<?php echo $item->tableId;?>" value="<?php echo $item->sale_type;?>" />
        </td>
        <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
        <td align="left"><a href="<?php echo $path;?>"><?php echo stripslashes($item->tktNumber);?></a></td>
<!--        <td align="left"></td>
        <td align="left"></td>
        <td align="left"></td> -->
        <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode', " WHERE nId = '".$item->nLocation."'"));?></td>
        <td align="left"><?php echo $item->sale_type;?></td>
        <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId = '".$item->airlineName."'"));?></td>
        <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId = '".$item->vIssuedBy."'"));?></td>
        <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
        <td align="left"><?php echo stripslashes($item->lpo);?></td>
        <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
        <td align="left"><?php echo $item->fFare;?></td>
        <td align="left"><?php echo $item->totTax;?></td>
        <td align="left"><?php echo stripslashes($item->netAmt);?></td>
        <td align="left"><?php echo stripslashes($item->vSalesAmt);?></td>
    </tr>
        <?php $i = $i+1;?>
        <?php endforeach;?>

    <?php if(empty($list_value))
    {?>
    <tr>
        <td colspan="17" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if?>
    </tbody>
</table>
<div class="pagination">
    <?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
