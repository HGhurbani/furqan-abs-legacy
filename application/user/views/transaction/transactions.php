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
		var nVal  = $('#ddlType').val();
		var nVal1 = $('#txtTracode').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlType:nVal,txtTracode:nVal1,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/transactions/filterTransaction/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					
					$('#result').html(response);
				}
		  });
	});	$('#btnSearch1').click(function()	{			var nVal  = $('#txtYearCode').val();				$.ajax(		  {			 type:'POST',			 data:{ddlType:nVal,btnSearch:'Update'},			 url:'<?php echo base_url().'index.php/transactions/yearupdate/'.$this->uri->segment(3);?>',			 dataType:'html',							success: function(response) {						$('#test').empty();					$('#test').append("Updated   ");									}		  });	});

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
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/transactions/editTransactions/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWTRANS;?></a></h3>
    <?php endif;?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/transactions/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/transactions/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/transactions/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
<style>#test{	color: red !important;}</style><p align="right"><?php  if($this->session->userdata('AbsAdmin')==1){?>
<label id="test" ></label><label><?php echo Year;?></label>&nbsp;<input type="text" name="txtYearCode" id="txtYearCode" value="" class="txtSearch"/>&nbsp;<input type="button" name="btnSearch" value="Update" id="btnSearch1" class="buttonGo"/>   <?php }?><label><?php echo TRANSNAMECODE;?></label>&nbsp;<input type="text" name="txtTracode" id="txtTracode" value="<?php echo $this->txtTracode;?>" class="txtSearch"/>&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo"/></p>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open(base_url().'index.php/transactions/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo TRANSCODE;?></th>
           <th align="left"><?php echo TRANSNAME;?></th>
		   <th align="left"><?php echo VIEW;?></th>
           <th align="left"><?php echo SETORDER;?></th>
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
    <?php foreach($list_value as $key=>$item):?>
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo stripslashes($item->vCode);?></td>
            <td align="left"><?php echo stripslashes($item->vTitle);?></td>
            <?php 
			//fetch transaction mode
			switch($item->vTranmode)
			{
				case "Cr":
					$transmode		= 'Credit';
				break;
				
				case "Dr":
					$transmode		= 'Debit';
				break;
			}//end switch
            ?>
			<td align="left"><a class="popup-link-<?php echo $item->nId;?>"><?php echo VIEW;?></a></td>
            <td align="left"><?php if($key+1!=1){?>
             <a href='<?php echo base_url();?>index.php/transactions/position/<?php echo $this->uri->segment(3) ?>/up/<?php echo $item->nId;?>/<?php echo $item->nPosition;?>'>
             <?php } ?>
             <img src="<?php echo base_url();?>/static/images/up_arrow.gif" alt="Up" border="0"></a>
            
             <?php if($this->count_rows!=$key+1){?>
             <a href="<?php echo base_url();?>index.php/transactions/position/<?php echo $this->uri->segment(3) ?>/down/<?php echo $item->nId;?>/<?php echo $item->nPosition;?>">
             <?php } ?>
             <img src="<?php echo base_url();?>/static/images/down_arrow.gif" alt="Down" border="0"></a>
            </td>
            <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
            <td align="left">
            <!-- Icons -->
                <?php // User Access aa
                if($action_access->vAccess == 'F' || $action_access->vAccess == 'U'): ?>
                <a href="<?php echo base_url();?>index.php/transactions/editTransactions/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php endif;?>
                <?php //user access 
                if($action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/transactions/deltransaction/<?php echo $this->uri->segment(3).'/'.$item->nId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
			  <!-- Start of Sticky Note -->
                <div class="popup-box" id="popup-box-<?php echo $item->nId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	 <?php echo $this->transaction_model->showDetails($item->nId);?>
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
