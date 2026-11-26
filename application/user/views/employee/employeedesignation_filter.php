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
        <th align="left"><?php echo CUSTAREACODE;?></th>
        <th align="left"><?php echo EMPDESNAME;?></th>
        <th align="left"><?php echo SETORDER;?></th>
        <th align="left"><?php echo ACTIVE;?></th>
        <th align="left"><?php echo ACTION;?></th>
    </tr>
    </thead>

    <tfoot>
    <tr class="formButs">
        <td colspan="7">
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
    <?php $i= 1;?>
    <?php foreach($list_value as $key=>$item):?>
    <tr>
        <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>
        <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
        <td align="left"><?php echo stripslashes($item->vCode);?></td>
        <td align="left"><?php echo stripslashes($item->vTitle);?></td>
        <td align="left"><?php if($key+1!=1){?>
				 <a href='<?php echo base_url();?>index.php/employee_designation/position/<?php echo $this->uri->segment(3) ?>/up/<?php echo $item->nId;?>/<?php echo $item->nPosition;?>'>
				<?php } ?>
            <img src="<?php echo base_url();?>/static/images/up_arrow.gif" alt="Up" border="0"></a>

            <?php if($this->count_rows!=$key+1){?>
				<a href="<?php echo base_url();?>index.php/employee_designation/position/<?php echo $this->uri->segment(3) ?>/down/<?php echo $item->nId;?>/<?php echo $item->nPosition;?>">
				<?php } ?>
            <img src="<?php echo base_url();?>/static/images/down_arrow.gif" alt="Down" border="0"></a>
        </td>
        <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
        <td align="left">
            <!-- Icons -->
            <?php // User Access aa
            if($action_access->vAccess == 'F' || $action_access->vAccess == 'U'): ?>
            <a href="<?php echo base_url();?>index.php/employee_designation/editEmployeeDes/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
            <?php endif;?>
            <?php //user access 
            if($action_access->vAccess == 'F'): ?>
            <a href="<?php echo base_url();?>index.php/employee_designation/delemployeedes/<?php echo $this->uri->segment(3).'/'.$item->nId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
            <?php endif;?>
        </td>
    </tr>
        <?php $i= $i+1;?>
        <?php endforeach;?>
    <?php if(empty($list_value))
    {
        ?>
    <tr>
        <td colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
        <?php }//end if
    ?>
    </tbody>
</table>
<div class="pagination">
    <?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
