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
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
    <tr>
        <th align="left">#</th>
        <th align="left"><?php echo DIVISIONNAME;?></th>
        <th align="left"><?php echo LOCATIONNAME;?></th>
        <th align="left"><?php echo JVNUMBER;?></th>
        <th align="left"><?php echo INVOICE;?></th>
        <th align="left"><?php echo CUSTDATE;?></th>
        <th align="left"><?php echo VIEW;?></th>
        <th align="left"><?php echo DEBIT;?></th>
        <th align="left"><?php echo CREDIT;?></th>		<th align="left"></th>
    </tr>
    </thead>

    <tfoot>
    <tr>
        <td colspan="18"><hr /></td>
    </tr>
    <tr>
        <td colspan="18">
            <?php if(!empty($list_value))
        {
            ?>
            <div class="bulk-actions align-left">
                <strong><?php echo ACCCODENAME;?></strong>&nbsp;&nbsp;
                <input type="textbox" value="" class="txtSearch2" name="txtACName" id="txtACName"/>&nbsp;&nbsp;
                <strong><?php echo SUBNAME;?></strong>&nbsp;&nbsp;
                <input type="textbox" value="" class="txtSearch2" name="txtSBName" id="txtSBName"/>
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
        $getDet = $this->system_jv_model->getSystemJvDrCr($item->nId);
        ?>
    <tr>
        <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
        <td align="left"><?php echo stripslashes($item->dName);?></td>
        <td align="left"><?php echo stripslashes($item->lName);?></td>
        <td align="left"><?php echo stripslashes($item->vJVNo);?></td>
        <td align="left"><?php echo stripslashes($item->nInvoice);?></td>
        <td align="left"><?php echo stripslashes($item->dDate);?></td>
        <td align="left"><a class="popup-link-<?php echo $item->nId;?>">View</a></td>
        <td align="left"><?php echo $getDet['debit'];?></td>
        <td align="left"><?php echo $getDet['credit'];?></td>		<?php if($getDet['debit']==$getDet['credit']){?>			<td align="left"><?php echo "Equal";?></td>			<?php }else{?>			<td style="color:red;"align="left"><?php echo "Not Equal";?></td>			<?php }?>
    </tr>
    <tr>
        <td colspan="9">
            <div class="popup-box" id="popup-box-<?php echo $item->nId;?>">
                <div class="close">X</div>
                <div class="top">
                    <h2>System JV Detail - <?php echo stripslashes($item->vJVNo);?></h2>
                </div>
                <div class="bottom">
                    <div class="wa">
                        <?php echo $this->system_jv_model->getSystemJvDetails($item->nId);?>
                    </div>
                </div>
            </div>
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