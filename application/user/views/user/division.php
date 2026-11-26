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
			alert("<?php echo PLEASESELECTACTION;?>");
			return false;
		}//end if
		
		else
		{
			var length = $('.chcktbl:checked').length; 
			if (length > 0) 
			{
				if (confirm("<?php echo AREYOUSUREDELETE;?>")) 
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
				alert("<?php echo PLEASECHECKITEM;?>");
				return false;
			}
		}//end else

	});

	
	$('#btnSearch').click(function()
	{
		var nVal = $('#txtUsertype').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtUsertype:nVal,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/user_type/filterByUsertype/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
				
					
					$('#result').html(response);
				}
		  });
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
    <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/user_type/editUsertype/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWUSERTYPE;?></a></h3>
    <?php endif;?>
<div class="listing">
<p align="right"><?php echo USERTYPENAME;?>:&nbsp;<input type="text" name="txtUsertype" id="txtUsertype" value="<?php echo $this->txtUsertype;?>" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" /></p>
<?php echo form_open(base_url().'index.php/user_type/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<span id="result">
<table>
			<thead>
				<tr>
				   <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
				   <th align="left">#</th>
				   <th align="left"><?php echo USERTYPENAME;?></th>
                   <th align="left"><?php echo SETORDER;?></th>
				   <th align="left"><?php echo ACTIVE;?></th>
				   <th align="left"><?php echo ACTION;?></th>
				</tr>
			</thead>
		 
			<tfoot>
				<tr>
					<td colspan="6">
						<?php if(!empty($list_value))
							  {
						?>
						<div class="bulk-actions align-left">
							<select name="ddlAction" id="ddlAction">
								<option value=""><?php echo BULKACTIONS;?></option>
                                <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                                <option value="delete"><?php echo DELETE;?></option>
                                <?php endif;?>
							</select>
							<input type="submit" value="<?php echo APPLYACTION;?>" class="button" name="btnGo" id="btnGo"/>
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
			<?php $i= 1;?>
			<?php foreach($list_value as $key=>$item):?>
				<tr>
					<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>
					<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
					<td align="left"><?php echo stripslashes($item->vTitle);?></td>
                    <td align="left"><?php if($key+1!=1){?>
                         <a href='<?php echo base_url();?>index.php/user_type/position/<?php echo $this->uri->segment(3) ?>/up/<?php echo $item->nId;?>/<?php echo $item->nPosition;?>'>
                        <?php } ?>
                        <img src="<?php echo base_url();?>/static/images/up_arrow.gif" alt="Up" border="0"></a>
                        
                        <?php if($this->count_rows!=$key+1){?>
                        <a href="<?php echo base_url();?>index.php/user_type/position/<?php echo $this->uri->segment(3) ?>/down/<?php echo $item->nId;?>/<?php echo $item->nPosition;?>">
                        <?php } ?>
                        <img src="<?php echo base_url();?>/static/images/down_arrow.gif" alt="Down" border="0"></a>
                    </td>
					<td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
					<td align="left">
					<!-- Icons -->
                        <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                        <a href="<?php echo base_url();?>index.php/user_type/editUsertype/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                        <?php endif;?>
                        <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                        <a href="<?php echo base_url();?>index.php/user_type/delusertype/<?php echo $this->uri->segment(3).'/'.$item->nId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                        <?php endif;?>
                     </td>
				</tr>
	<?php $i= $i+1;?>
	<?php endforeach;?>
	<?php if(empty($list_value))
	{
	?>
		<tr>
			<td colspan="6" align="center"><?php echo SORRYNORECORD;?></td>
		</tr>
	<?php }//end if
	?>
	</tbody>
  </table>
  </span>
  </form>
  </div>
</div>

</div>