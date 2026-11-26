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
$(document).ready(function()
{
	$('#btnGo').click(function()
	{
		if($('#ddlAction').val() == 0)
		{
			alert("Please select action");
			return false;
		}//end if
		
		else
		{
			var length = $('.chcktbl:checked').length; 
			if (length > 0) 
			{
				if (confirm("Are you sure you want to approve?")) 
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				alert("Please check the item.. ");
				return false;
			}
		}//end else

	});
});

</script>
<div class="rhtCol">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
	<h2 class="pageHead"><?php echo $this->showTitle;?></h2>
	<?php $this->load->view('general/admin_menu');?>
	<div class="tabcont">
	<h3 class="content-box-header">View All Details</h3>
	<div class="listing">
	<?php echo form_open(base_url().'index.php/approve_profile/profile_approve/'.$uri,$attributes); ?>
	<?php echo form_hidden('nCId', $this->uri->segment(3));?>
	<span id="result">
	<table>
				<thead>
					<tr>
					   <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
					   <th align="left">#</th>
					   <th align="left">Name</th>
					   <th align="left">Email</th>
					   <th align="left">Mobile</th>
					   <th align="left">Type</th>
					   <th align="left">Phone</th>
					</tr>
				</thead>
				<tfoot>
					<tr>
						<td colspan="7">
							<?php if(!empty($list_value))
							{
							?>
							<div class="bulk-actions align-left">
								<select name="ddlAction" id="ddlAction">
									<option value="">Bulk Actions</option>
									<option value="approve">Approve</option>
								</select>
								<input type="submit" value="Apply Action" class="button" name="btnGo" id="btnGo"/>
							</div>
							<?php
							}//end if
							?>						
							<div class="pagination">
							<?php echo $this->common_model->p->show();?>
							</div> <!-- End .pagination -->
							<div class="clear"></div>
						</td>
					</tr>
				</tfoot>
			 
				<tbody>
				<?php $i=1;?>
				<?php foreach($list_value as $key=>$item):?>
					<tr>
						<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>
						<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
						<td align="left"><?php echo $item->vName;?></td>
						<td align="left"><?php echo $item->vEmail;?></td>
						<td align="left"><?php echo $item->vMobile;?></td>
						<td align="left"><?php echo $item->vType;?></td>
						<td align="left"><?php echo $item->vPhone;?></td>
					</tr>
					<?php $i= $i+1;?>
					<?php endforeach;?>
					<?php if(empty($list_value))
					{
					?>
						<tr>
							<td colspan="7" align="center">Sorry, no records found.</td>
						</tr>
					<?php }//end if
					?>
				</tbody>
	  </table></span>
	  </form>
	  </div>
	</div>
	
</div>