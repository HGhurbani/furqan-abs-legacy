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
				if($('#ddlAction').val()=='open')
				{
					var msg="<?php echo AREYOUSURE;?> "+$('#ddlAction').val()+"?";
				}
				if($('#ddlAction').val()=='closed')
				{
					var msg="<?php echo AREYOUSURE;?> "+$('#ddlAction').val()+"?";
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

	$("#txtStartDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	$("#txtEndDate").datepick({ dateFormat: 'yyyy-mm-dd'});

	$('#btnSearch').click(function()
	{
		var nVal  = $('#txtStartDate').val();
		var nVal2 = $('#txtEndDate').val();
		var nVal3 = $('#ddlStatus').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtStartDate:nVal,txtEndDate:nVal2,ddlStatus:nVal3,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/account_period/filterList/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#result').html(response);
				}
		  });
	});

	$('.chkStatus').change(function()
	{
		var selID = this.id;
		var nVal  = $('#'+selID).val();		
		$.ajax(
		  {
			 type:'POST',
			 data:{txtId:selID,ddlStatus:nVal},
			 url:'<?php echo base_url().'index.php/account_period/changeStatus/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#result').html(response);
				}
		  });

	});

    var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()
    $('#maincontent').scroll(function() {
        console.log(contentBoxExportPos)
        if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
            contentBoxExport.addClass('fixed')
        }
        else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
            contentBoxExport.removeClass('fixed')
        }
    })

});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<?php if($action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
<h3 class="content-box-header"><a href="<?php echo base_url().'index.php/account_period/editAccountperiod/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWACCPERIOD;?></a></h3>
<?php endif;?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/account_period/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/account_period/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/account_period/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
<p align="right">
    <?php echo ACCSTARTDATE;?>&nbsp;
    <input type="text" name="txtStartDate" id="txtStartDate" value="" class="txtSearch2" />&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo ACCENDDATE;?>&nbsp;
    <input type="text" name="txtEndDate" id="txtEndDate" value="" class="txtSearch2" />&nbsp;&nbsp;&nbsp;&nbsp;
    <?php echo ACCSTATUS;?>&nbsp;
    <?php echo form_dropdown('ddlStatus', $list_status, $this->ddlStatus,' id="ddlStatus" class="ddlSearch2"');?>&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open(base_url().'index.php/account_period/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
   <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo ACCYEARREF;?></th>
           <th align="left"><?php echo ACCSTARTDATE;?></th>
           <th align="left"><?php echo ACCENDDATE;?></th>
           <th align="left"><?php echo ACCSTATUS;?></th>
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
                        <option value="open"><?php echo ACCOPEN;?></option>
                        <option value="closed"><?php echo ACCCLOSED;?></option>
                        <?php if($this->session->userdata('AbsAdmin')==1): ?>
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
        	<input type="hidden" name="txtId" id="txtId" value="<?php echo $item->nId;?>" />
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo stripslashes($item->vYearRef);?></td>
            <td align="left"><?php echo stripslashes($item->dFDate);?></td>
            <td align="left"><?php echo stripslashes($item->dTDate);?></td>
            <td align="left"><?php if($item->vStatus == 'O') echo OPEN; else echo CLOSED; ?>
            <?php /* if($item->vStatus == 'O')
                {?>
                    <a href="<?php echo base_url();?>index.php/account_period/changeStatusOpen/<?php echo $this->uri->segment(3);?>/<?php echo $item->nId;?>">Open</a>
                <?php }//end if
                else {?>
                    <a href="<?php echo base_url();?>index.php/account_period/changeStatusClosed/<?php echo $this->uri->segment(3);?>/<?php echo $item->nId;?>">Close</a>
                <?php } *///end else?>
            </td>
            <td align="left">
                <?php //if($this->session->userdata('AbsAdmin')==1): ?>
                <?php //if($item->vStatus == 'O')
                ?>
                <?php // User Access aa
                if($action_access->vAccess == 'F' || $action_access->vAccess == 'U'): ?>
                    <a href="<?php echo base_url();?>index.php/account_period/editAccountperiod/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php endif;//end else?>
                <?php //user access 
                if($action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/account_period/delaccperiod/<?php echo $this->uri->segment(3).'/'.$item->nId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
            </td>
        </tr>
	<?php $i = $i+1;?>
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

</div>
</form>
</div>
</div>

</div>
