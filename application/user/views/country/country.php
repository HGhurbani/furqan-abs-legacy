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
				if($('#ddlAction').val()=='yes')
				{
					var msg="<?php echo AREYOUSUREWANTTOACTIVE;?> "+$('#ddlAction').val()+"?";
				}
				if($('#ddlAction').val()=='no')
				{
					var msg="<?php echo AREYOUSUREWANTTOACTIVE;?> "+$('#ddlAction').val()+"?";
				}
				if($('#ddlAction').val()=='delete')
				{
					var msg="<?php echo AREYOUSURE;?> "+$('#ddlAction').val()+"?";
				}
				if (confirm(msg)) 
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
		var nVal = $('#countryname').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{countryname:nVal,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/country/filterByCountry/'.$this->uri->segment(3);?>',
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
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<?php $this->load->view('general/admin_menu');?>
<?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
<h3 class="content-box-header"><a href="<?php echo base_url().'index.php/country/editCountry/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWCOUNTRY;?></a></h3>
<?php endif;?>
<p align="right"><?php echo COUNTRYNAME;?> / <?php echo SHORTNAME;?>&nbsp;<input type="text" name="countryname" id="countryname" class="txtSearch" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/country/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
	<thead>
		<tr>
		   <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
		   <th align="left">#</th>
		   <th align="left"><?php echo COUNTRYNAME;?></th>
		   <th align="left"><?php echo SHORTNAME;?></th>
		   <th align="left"><?php echo ACTIVE;?></th>
		   <th align="left"><?php echo ACTION;?></th>
		</tr>
	</thead>
 
	<tfoot>
		<tr class="formButs">
			<td colspan="6">
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
			<td align="left"><?php echo stripslashes($item->vName);?></td>
			<td align="left"><?php echo stripslashes($item->vShortName);?></td>
			<td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
			<td align="left">
			<!-- Icons -->
                <?php // User Access aa
                if($action_access->vAccess == 'F' || $action_access->vAccess == 'U'): ?>
                <a href="<?php echo base_url();?>index.php/country/editCountry/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php endif;?>
                <?php //user access 
                if($action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/country/delcountry/<?php echo $this->uri->segment(3).'/'.$item->nId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
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
<div class="pagination">
<?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
</div>
</form>
</div>
</div>

</div>
