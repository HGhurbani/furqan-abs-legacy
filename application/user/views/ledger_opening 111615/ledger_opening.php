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
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/ledger_opening/editLedgeropening/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWOPENINGBALANCE;?></a></h3>
    <?php endif;?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/ledger_opening/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/ledger_opening/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/ledger_opening/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open(base_url().'index.php/ledger_opening/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
		   <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo LEDGERNAME;?></th>
		   <th align="left"><?php echo DIVISIONNAME;?></th>
		   <th align="left"><?php echo LOCATION;?></th>
		   <th align="left"><?php echo DEBIT;?></th>
		   <th align="left"><?php echo CREDIT;?></th>
           <th align="left"><?php echo OPENINGDETAILS;?></th>
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
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nOBId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo  date('d-m-Y',strtotime($item->dDate));?></td>
            <td align="left"><?php echo ($item->vLedger == 'SL')?'Sub Ledger':'General Ledger';?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$item->nDivision."'");?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$item->vLocation."'");?></td>
            <td align="left"><?php echo $item->nTotalD;?></td>
            <td align="left"><?php echo $item->nTotalC;?></td>
			<td align="left"><a class="popup-link-<?php echo $item->nOBId;?>"><?php echo VIEW;?></a></td>
            <td align="left">
            <!-- Icons -->
                <?php //user access 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && ($action_access->vAccess == 'F' ||  $action_access->vAccess == 'U')): ?>
                <a href="<?php echo base_url();?>index.php/ledger_opening/editLedgeropening/<?php echo $this->uri->segment(3).'/e/'.$item->nOBId.'/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php endif;?>
                <?php //user access 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/ledger_opening/delledgeropening/<?php echo $this->uri->segment(3).'/'.$item->nOBId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
		<!-- Start of Sticky Note -->
            <div class="popup-box" id="popup-box-<?php echo $item->nOBId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->ledger_opening_model->showDetails($item->nOBId);?>
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
</div>
</form>
</div>
</div>
  <div id="blackout"></div>
