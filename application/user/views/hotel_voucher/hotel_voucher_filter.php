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
        <th align="left"><?php echo PASSENGER;?></th>
        <th align="left"><?php echo VOUCHERID;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo HOTELNAME;?></th>
       
        <th align="left"><?php echo VENDORNAME;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo OPENCLOSED;?></th>
        <th align="left"><?php echo NETAMT;?></th>
<!--        <th align="left"><?php echo HOTELVOUCHERDETAILS;?></th> -->
<!--        <th align="left"><?php echo ACTIVE;?></th> -->
        <th align="left"><?php echo ACTION;?></th>
    </tr>
    </thead>

    <tfoot>
    <tr class="formButs">
        <td colspan="16">
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
    if(!empty($list_value)) {?>
        <?php foreach($list_value as $key=>$item):?>
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nHVId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
            <td align="left"><?php echo substr($item->vPassenger,0,20);?></td>
		<td align="left">
		<!-- Icons -->
            <?php //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/hotel_voucher/editHotelvoucher/<?php echo $this->uri->segment(3).'/e/'.$item->nHVId;?>" title="<?php echo EDIT;?>"><?php echo $item->vTicktManual;?></a>
            <?php //endif;?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$item->vIssuedBy."'");?></td>
            <td align="left"><?php  echo substr($item->hotelName,0,25);
		
		echo"<br>Country:".$this->common_model->get_field_name('Country','vName'," WHERE nId = '".$item->nCountry."' ");
		echo "<br>City:".$this->common_model->get_field_name('Country','vName'," WHERE nId = '".$item->nCity."' ");?></td>
            <td align="left"><?php echo substr($this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$item->nAcCode."' "),0,15);?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'");?></td>
<td align="left"><?php 
/*
$status_r_c = $this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nTicketNumber = '".$item->nHVId."' and 

vType = 'Htl'");
  if(!empty($status_r_c)){
	echo "REFUNDCLOSED";
  }
  else {
	$status_r_o =  $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE nTktNum = '".$item->nHVId."' and 

vTktType= 'Htl'");
 
      if(!empty($status_r_o)){
	echo "REFUNDOPEN";
     }
     else {
*/
        $status_s_c =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->nHVId."' and vType = 'Htl' ");

        if(!empty($status_s_c)){
            echo "CLOSED";
        }
        else {
            echo "OPEN";
               }
//       }
// }
?></td>
            <td align="left"><?php echo $item->vTotalDC;?></td>
<!--             <td align="left"><a class="popup-link-<?php echo $item->nHVId;?>"><?php echo VIEW;?></a></td> -->
<!--            <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td> -->
            <td align="left">
                <!-- Icons -->
<!--                <?php //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                <a href="<?php echo base_url();?>index.php/hotel_voucher/editHotelvoucher/<?php echo $this->uri->segment(3).'/e/'.$item->nHVId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php //endif;?> -->
                <?php if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): 				$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");				if($aid==1 || $aid==47 || $aid==46 || $aid==45){					?>
                <a href="<?php echo base_url();?>index.php/hotel_voucher/delhotelvoucher/<?php echo $this->uri->segment(3).'/'.$item->nHVId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php } endif;?>
                <!-- Start of Sticky Note 
                <div class="popup-box" id="popup-box-<?php echo $item->nHVId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                        <div class="wa">
                            <?php echo $this->hotel_voucher_model->showDetails($item->nHVId);?>
                        </div>
                    </div>
                </div>
                End of Sticky Note -->
            </td>
        </tr>
            <?php $i= $i+1;?>
            <?php endforeach;?>
        <?php }?>
    <?php if(empty($list_value))
    {
        ?>
    <tr>
        <td colspan="16" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
