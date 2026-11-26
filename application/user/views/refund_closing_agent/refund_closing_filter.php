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
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
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
        <th align="left"><?php echo POSTDT;?></th>
        <th align="left"><?php echo ACCNAME;?></th>
        <th align="left"><?php echo SALETYPE;?></th>
        <th align="left"><?php echo AIRLINECODE;?></th>
        <th align="left"><?php echo REFUNDNUMBER;?></th>
        <th align="left"><?php echo REFUNDDT;?></th>
        <th align="left"><?php echo LPO;?></th>
        <th align="left"><?php echo PASSENGER;?></th>
        <th align="left"><?php echo PAIDAMT;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo PROFIT;?></th>
        <th align="left"><?php echo DETAILS;?></th>
        <th align="left"><?php echo ACTIVE;?></th>
        <th align="left"><?php echo ACTION;?></th>
    </tr>
    </thead>

    <tfoot>
    <tr class="formButs">
        <td colspan="18">
            <?php if(!empty($list_value))
        {
            ?>
            <div class="bulk-actions align-left">
                <select name="ddlAction" id="ddlAction">
                    <option value=""><?php echo BULKACTIONS;?></option>
                    <option value="yes"><?php echo ACTIVEYES;?></option>
                    <option value="no"><?php echo ACTIVENO;?></option>
                    <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'D' || $action_access->vAccess == 'F'):?>
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
    <?php $i = 1;?>
    <?php foreach($list_value as $key=>$item):
        $getTypeDetails     = $this->refund_closing_model->returnTypeDetails($item->nRCId);
        $getTktDetails      = $this->refund_closing_model->returnTktDetails($item->nRCId);
        $getAirlineDetails  = $this->refund_closing_model->returnAirlineDetails($item->nRCId);
        $getLpoDetails      = $this->refund_closing_model->returnLpoDetails($item->nRCId);
        $getDateDetails     = $this->refund_closing_model->returnDateDetails($item->nRCId);
        $getPassDetails     = $this->refund_closing_model->returnPassengerDetails($item->nRCId);
        $getPaidDetails     = $this->refund_closing_model->returnPaidDetails($item->nRCId);
        $getNetDetails      = $this->refund_closing_model->returnNetDetails($item->nRCId);
        $getProfitDetails   = $this->refund_closing_model->returnProfitDetails($item->nRCId);

        switch($item->vTable)
        {
            case "A":
                $account = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId = '".$item->nDrAcc."'");
                $accCode = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId = '".$item->nDrAcc."'");
                break;
            case "C":
                $account = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '".$item->nDrAcc."'");
                $accCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '".$item->nDrAcc."'");
                break;
            case "V":
                $account = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$item->nDrAcc."'");
                $accCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '".$item->nDrAcc."'");
                break;
            case "O":
                $account = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '".$item->nDrAcc."'");
                $accCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId = '".$item->nDrAcc."'");
                break;
            case "AG":
                $account = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '".$item->nDrAcc."'");
                $accCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId = '".$item->nDrAcc."'");
                break;
        }//end switch
        ?>
    <tr>
        <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nRCId;?>" class="chcktbl"/></td>
        <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
        <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
        <td align="left"><?php echo stripslashes($account).'('.stripslashes($accCode).')';?></td>
        <td align="left"><?php echo stripslashes($getTypeDetails);?></td>
        <td align="left"><?php echo stripslashes($getAirlineDetails);?></td>
        <td align="left">
            <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo $this->uri->segment(3).'/e/'.$item->nRCId;?>" title="<?php echo EDIT;?>">
                <?php echo stripslashes($item->vDocNumber);?>
            </a>
            <?php
        else:
            echo stripslashes($item->vDocNumber);
        endif;?>
        </td>
        <td align="left"><?php echo stripslashes($getDateDetails);?></td>
        <td align="left"><?php echo stripslashes($getLpoDetails);?></td>
        <td align="left"><?php echo stripslashes($getPassDetails);?></td>
        <td align="left"><?php echo stripslashes($getPaidDetails);?></td>
        <td align="left"><?php echo stripslashes($getNetDetails);?></td>
        <td align="left"><?php echo stripslashes($getProfitDetails);?></td>
        <td align="left"><a class="popup-link-<?php echo $item->nRCId;?>"><?php echo VIEW;?></a></td>
        <td align="left"><?php echo ($item->vActive == '1')?'Yes':'No';?></td>
        <td align="left">
            <!-- Icons -->
            <?php //user access 
            if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
            <a href="<?php echo base_url();?>index.php/refund_closing/deleteRefundClosing/<?php echo $this->uri->segment(3).'/'.$item->nRCId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
            <?php endif;?>
            <!-- Start of Sticky Note -->
            <div class="popup-box" id="popup-box-<?php echo $item->nRCId;?>">
                <div class="close">X</div>
                <div class="top">
                    <h2>Details</h2>
                </div>
                <div class="bottom">
                    <div class="wa">
                        <?php echo $this->refund_closing_model->showDetails($item->nRCId);?>
                    </div>
                </div>
            </div>
            <!-- End of Sticky Note -->

        </td>
    </tr>
        <?php $i= $i+1;?>
        <?php endforeach;?>
    <?php if(empty($list_value))
    {
        ?>
    <tr>
        <td colspan="18" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
