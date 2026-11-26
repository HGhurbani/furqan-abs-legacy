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
			if (e.name == "ddlTrans[]") 
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
		if (e.name == "ddlTrans[]") 
		{
			e.checked = false;
		}//end if
	}//end for loop
}//end function	

</script>
	<thead>
	<?php if($this->input->post('txtId') == '0')
	{?>
	    <th align="left" width="61%"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
	<?php }//end if?>
		<th align="left" width="61%"><?php echo TRANSCODE;?></th>
		<th align="left"><?php echo TRANSNAME;?></th>
		<th align="left"><?php echo TRAVELSALESTYPE;?></th>
		<th align="left"><?php echo TRAVELFLAG;?></th>
	</thead><br />
	<?php if(!empty($travel_details))
	{
		foreach($travel_details as $key => $item):?>	
		<tr>
			<?php if($this->input->post('txtId') == '0')
			{?>
			<td align="left"><input name="ddlTrans[]" id="ddlTrans[]" type="checkbox" value="<?php echo $item->nId;?>"/></td>
			<?php }//end if?>
			<td align="left"><input name="txtTranscode" id="txtTranscode" type="text" value="<?php echo $item->vCode;?>" readonly="" /></td>
			
			<td align="left"><input name="txtTransname" id="txtTransname" type="text" value="<?php echo $item->vTitle;?>" readonly="" /></td>
			
			<td align="left"><?php echo form_dropdown('ddlSalestype'.$item->nId, $list_sales_type, $item->vGeneral,'id="ddlSalestype"');?></td>
			
			<td align="left"><?php echo form_dropdown('ddlFlag'.$item->nId, $list_flag, $item->nFlag,'id="ddlFlag"');?></td>
		</tr><br />
		<?php endforeach;
	}//end if?>
	<?php if(empty($travel_details))
	{
	?>
	<tr>
		<td colspan="5" align="center"><?php echo SORRYNORECORD;?></td>
	</tr>
	<?php }//end if
	?>
