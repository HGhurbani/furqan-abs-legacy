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
			}//end if
			else
			{
				alert("<?php echo PLEASECHECKITEM;?>");
				return false;
			}//end else
		}//end else

	});

	$('#btnSearch').click(function()
	{
		var nVal = $('#txtUserAccess').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtUserAccess:nVal,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/user_role/filterByUserrole/'.$this->uri->segment(3);?>',
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
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

<div id="boxes">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<?php $this->load->view('general/admin_menu');?>
    <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/user_role/editUserrole/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWACCESS;?></a></h3>
    <?php endif;?>
<p align="right"><?php echo USERACCESSROLE;?>:&nbsp;<input type="text" name="txtUserAccess" id="txtUserAccess" value="<?php echo $this->txtUserAccess;?>" class="txtSearch" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/user_role/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo USERROLENAME;?></th>
           <th align="left"><?php echo USER;?></th>
           <th align="left"><?php echo USERROLEACCESS;?></th>
           <th align="left"><?php echo USERROLEACCESS;?></th>
           <th align="left"><?php echo ACTIVE;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="9">
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
    <?php foreach($list_value as $key=>$item):
	// For Super Administrator
				if ($item->uId=='1')
				{	
					$accessDetail = "<b>".FULLACCESS."</b>";
					$SUPER = TRUE;
				}//end if
				else
				{
					if ($item->nGIdModules==$module_total)
					{
						$accessDetail = "<b>".FULLACCESS."</b>";
					}//end if
					else
					{
						$accessDetail = ACCESSTO." <b>".$item->totModules."</b> ".OUTOF." <b>".$module_total."</b> ".MODULES;
					}//end else
					$SUPER = FALSE;
				}//end else
	?>
    <?php //$module_total = $this->common_model->get_total_num_rows('Transactions','count(uId)'," WHERE nParentId = '".$item->nTrType."'");?>
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->uId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo $item->vGroupTitle;?></td>
            <td align="left"><?php echo $this->common_model->get_total_num_rows('Admin','nId '," WHERE nGroupId='".$item->uId."'");?></td>
            <td align="left"><?php echo $accessDetail;//ACCESSTO." <b>".$item->totModules."</b> ".OUTOF." <b>".$this->user_model->getTotalModuleCount()."</b> ".MODULES;?></td>
            <td align="left"><a class="popup-link-<?php echo $item->uId;?>"><?php echo VIEW;?></a></td>
            <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
            <td align="left">
            <?php
					if (!$SUPER)
					{ 
					?>
            <!-- Icons -->
                        <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                        <a href="<?php echo base_url();?>index.php/user_role/editUserrole/<?php echo $this->uri->segment(3).'/e/'.$item->uId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                        <?php endif;?>
                        <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                        <a href="<?php echo base_url();?>index.php/user_role/deluserrole/<?php echo $this->uri->segment(3).'/'.$item->uId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                        <?php endif;?>
               <?php
					}//end else
					?>         
                        <!-- Start of Sticky Note -->
            <div class="popup-box" id="popup-box-<?php echo $item->uId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	 <?php echo $this->user_model->showDetails($item->uId);?>
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
    <td colspan="9" align="center"><?php echo SORRYNORECORD;?></td>
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
 <div id="blackout"></div>
</div>
</div>