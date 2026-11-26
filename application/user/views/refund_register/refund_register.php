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
		var nVal = $('#txtrefund_register').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtrefund_register:nVal,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/refund_register/filterByrefund_register/'.$this->uri->segment(3);?>',
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

    <?php
    if(intval($this->uri->segment(5)) != 0 ):
        if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>

        window.open('<?php echo base_url().'index.php/refund_register/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");

        /*window.open('<?php //echo base_url().'index.php/cashbook_receipt/printAgentPreview/'.$this->uri->segment(5);?>','PrintAgentWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");*/

    <?php endif;endif;?>
});
</script>
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

<?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
<h3 class="content-box-header"><a href="<?php echo base_url().'index.php/refund_register/editRefund/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWREFUND;?></a></h3>
<?php endif;?>

<div id="boxes">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/refund_register/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/refund_register/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/refund_register/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->

<?php /*?><p align="right"><?php echo CUSTNAME;?> / <?php echo TRANSCODE;?> / <?php echo EMAIL;?>&nbsp;<input type="text" name="txtrefund_register" id="txtrefund_register" value="<?php echo $this->txtrefund_register;?>" class="txtSearch" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>
<?php */?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/refund_register/delrecords',$attributes); ?>
<?php echo form_hidden('nRId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo TREFSTATUS;?></th>
           <th align="left"><?php echo DOCNUMBER;?></th>
           <th align="left"><?php echo TYPENAME;?></th>
           <th align="left"><?php echo AIRLINECODE;?></th>
           <th align="left"><?php echo TICKETNAME;?></th>
           <th align="left"><?php echo NETAMT;?></th>
           <th align="left"><?php echo PAIDAMOUNT;?></th>
           <th align="left"><?php echo GDS;?></th>
           <th align="left"><?php echo DETAILS;?></th>
           <th align="left"><?php echo ACTIVE;?></th>
           <th align="left"><?php echo STATUS;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="15">
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
    <?php $i= 1;
	if(!empty($list_value)) {?>
    <?php foreach($list_value as $key=>$item):
            $getAirlineDetails  = $this->refund_register_model->returnAirlineDetails($item->nRId);
            $getTktDetails      = $this->refund_register_model->returnTktDetails($item->nRId);
            $getNetDetails      = $this->refund_register_model->returnNetDetails($item->nRId);
            $getTicketType      = $this->refund_register_model->returnTicketType($item->nRId);
            $getPaidAmount      = $this->refund_register_model->returnPaidAmount($item->nRId);
            $getStatus          = $this->refund_register_model->returnStatus($item->nRId);

    ?>
	<tr>
		<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nRId;?>" class="chcktbl"/></td>
		<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
        <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
		<td align="left"><?php echo ($item->vType=='R')?REFUND:REFUNDVOID;?></td>
		<td align="left">
            <?php//user access aa // if($this->common_model->get_account_period_date($item->dDate) ==1  && ($action_access->vAccess == 'F' || $action_access->vAccess == 'U')): ?>
            <a href="<?php echo base_url();?>index.php/refund_register/editRefund/<?php echo $this->uri->segment(3).'/e/'.$item->nRId;?>" title="<?php echo EDIT;?>"><?php echo $item->vDocNumber;?></a>
            <?php// endif;?>
        </td>
        <td align="left"><?php echo $getTicketType;?></td>
        <td align="left"><?php echo $getAirlineDetails;?></td>
		<td align="left"><?php echo $getTktDetails;?></td>
        <td align="left"><?php echo $getPaidAmount;?></td>
		<td align="left"><?php echo $getNetDetails;?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('Gds','vName'," WHERE nGId='".$item->nGDS."'");?></td>
	    <td align="left"><a class="popup-link-<?php echo $item->nRId;?>"><?php echo VIEW;?></a></td>
		<td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
		<td align="left"><?php echo $getStatus;?></td>
		<td align="left">
		<!-- Icons -->
            <?php //user access 
            if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
            <a href="<?php echo base_url();?>index.php/refund_register/delxobooking/<?php echo $this->uri->segment(3).'/'.$item->nRId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
            <?php endif;?>
			<!-- Start of Sticky Note -->
			<div class="popup-box" id="popup-box-<?php echo $item->nRId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->refund_register_model->showDetails($item->nRId);?>
                        </div>
                    </div>
                </div>
			<!-- End of Sticky Note -->
		</td>
	</tr>
	<?php $i= $i+1;?>
    <?php endforeach;?>
    <?php }?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="15" align="center"><?php echo SORRYNORECORD;?></td>
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
