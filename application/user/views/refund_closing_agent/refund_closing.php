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
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
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
    $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
    $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});

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
        var nVal  = $('#txtAirline').val();
        var nVal2 = $('#txtTktName').val();
        var nVal3 = $('#txtInvoice').val();
        var nVal4 = $('#txtIssuedBy').val();
        var nVal5 = $('#txtPassName').val();
        var nVal6 = $('#txtFromDate').val();
        var nVal7 = $('#txtToDate').val();
        var nVal8 = $('#txtAccname').val();
        $.ajax({
            type:'POST',
			data:{txtAirline:nVal,txtTktName:nVal2,txtInvoice:nVal3,txtIssuedBy:nVal4,txtPassName:nVal5,txtFromDate:nVal6,
                txtToDate:nVal7,txtAccname:nVal8,btnSearch:'Go'},
			url:'<?php echo base_url().'index.php/refund_closing/filterList/'.$this->uri->segment(3);?>',
			dataType:'html',
			beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
			},
			success: function(response) {
					$('#result').html(response);
			}
		});
	});

    var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset();
    $('#maincontent').scroll(function() {
        console.log(contentBoxExportPos);
        if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
            contentBoxExport.addClass('fixed')
        }
        else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
            contentBoxExport.removeClass('fixed')
        }
    });

    <?php
    if(intval($this->uri->segment(5)) != 0 ):
        if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'): ?>
            window.open('<?php echo base_url().'index.php/refund_closing/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
        <?php endif;
    endif;?>

});
</script>
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

<div class="tab-btn-container">
    <div class="inner">
        <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
        <a href="<?php echo base_url().'index.php/refund_closing/editRefundClosing/'.$this->uri->segment(3);?>/a" class="tab-button"><?php echo ADDNEWREFUNDCLOSING;?></a>
        <?php endif;?>
        <a href="<?php echo base_url();?>index.php/refund_closing/showlist/4"  class="tab-button active"><?php echo CLOSEDTKT;?></a>
        <a href="<?php echo base_url();?>index.php/refund_closing/showUnclosed/4" class="tab-button"><?php echo UNCLOSEDTKT;?></a>
    </div>
</div>

<div id="boxes">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<?php $this->load->view('general/admin_menu');?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/refund_closing/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/refund_closing/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/refund_closing/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->
    <table>
        <th><?php echo AIRLINE;?></th>
        <th><?php echo TICKETNAME;?></th>
        <th><?php echo CREDITNOTE;?></th>
        <th><?php echo ISSUEDBY;?></th>
        <th><?php echo PASSENGER;?></th>
        <th><?php echo ACCFROMDATE;?></th>
        <th><?php echo ACCTODATE;?></th>
        <th><?php echo ACCNAME;?></th>

        <tr>
            <td><input type="text" name="txtAirline" id="txtAirline" value="<?php echo $this->txtAirline;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtInvoice" id="txtInvoice" value="<?php echo $this->txtInvoice;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtIssuedBy" id="txtIssuedBy" value="<?php echo $this->txtIssuedBy;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtPassName" id="txtPassName" value="<?php echo $this->txtPassName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtFromDate" id="txtFromDate" value="<?php echo $this->txtFromDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtToDate" id="txtToDate" value="<?php echo $this->txtToDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtAccname" id="txtAccname" value="<?php echo $this->txtAccname;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
        </tr>
    </table><br />
<div class="formHead">
    <h4><?php echo $this->showTitle1;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/refund_closing/delRecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
            <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
            <th align="left">#</th>
            <th align="left"><?php echo POSTDT;?></th>
            <th align="left"><?php echo ACCNAME;?></th>
            <th align="left"><?php echo SALETYPE;?></th>
			<th align="left"><?php echo AIRLINECODE;?></th>
            <th align="left"><?php echo REFUNDNUMBER;?></th>
            <th align="left"><?php echo REFUNDDT;?></th>
            <th align="left"><?php echo LPO;?></th>
            <th align="left"><?php echo PASSENGER;?></th>
            <th align="left"><?php echo PAIDAMT;?></th>
            <th align="left"><?php echo NETAMT;?></th>
            <th align="left"><?php echo PROFIT;?></th>
            <th align="left"><?php echo DETAILS;?></th>
            <th align="left"><?php echo ACTIVE;?></th>
            <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="18">
                <?php if(!empty($list_value))
                      {
                ?>
                <div class="bulk-actions align-left">
                    <select name="ddlAction" id="ddlAction">
                        <option value=""><?php echo BULKACTIONS;?></option>
                        <option value="yes"><?php echo ACTIVEYES;?></option>
                        <option value="no"><?php echo ACTIVENO;?></option>
                        <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'D' || $action_access->vAccess == 'F'):?>
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
    <?php $i = 1;?>
    <?php foreach($list_value as $key=>$item):
        $getAccountDetails  = $this->refund_closing_model->returnAccountDetails($item->nRCId);
        $getTypeDetails     = $this->refund_closing_model->returnTypeDetails($item->nRCId);
        $getTktDetails      = $this->refund_closing_model->returnTktDetails($item->nRCId);
        $getAirlineDetails  = $this->refund_closing_model->returnAirlineDetails($item->nRCId);
        $getLpoDetails      = $this->refund_closing_model->returnLpoDetails($item->nRCId);
        $getDateDetails     = $this->refund_closing_model->returnDateDetails($item->nRCId);
        $getPassDetails     = $this->refund_closing_model->returnPassengerDetails($item->nRCId);
        $getPaidDetails     = $this->refund_closing_model->returnPaidDetails($item->nRCId);
        $getNetDetails      = $this->refund_closing_model->returnNetDetails($item->nRCId);
        $getProfitDetails   = $this->refund_closing_model->returnProfitDetails($item->nRCId);
        ?>
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nRCId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
            <td align="left"><?php echo stripslashes($getAccountDetails);?></td>
            <td align="left"><?php echo stripslashes($getTypeDetails);?></td>
            <td align="left"><?php echo stripslashes($getAirlineDetails);?></td>
            <td align="left">
                <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                    <a href="<?php echo base_url();?>index.php/refund_closing/editRefundClosing/<?php echo $this->uri->segment(3).'/e/'.$item->nRCId;?>" title="<?php echo EDIT;?>">         <?php echo stripslashes($item->vDocNumber);?>             </a>
                <?php
                else:
                    echo stripslashes($item->vDocNumber);
                endif;?>
            </td>
            <td align="left"><?php echo stripslashes($getDateDetails);?></td>
            <td align="left"><?php echo stripslashes($getLpoDetails);?></td>
            <td align="left"><?php echo stripslashes($getPassDetails);?></td>
            <td align="left"><?php echo stripslashes($getPaidDetails);?></td>
            <td align="left"><?php echo stripslashes($getNetDetails);?></td>
            <td align="left"><?php echo stripslashes($getProfitDetails);?></td>
			<td align="left"><a class="popup-link-<?php echo $item->nRCId;?>"><?php echo VIEW;?></a></td>
            <td align="left"><?php echo ($item->vActive == '1')?'Yes':'No';?></td>
            <td align="left">
            <!-- Icons -->
                <?php //user access 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/refund_closing/deleteRefundClosing/<?php echo $this->uri->segment(3).'/'.$item->nRCId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
		<!-- Start of Sticky Note -->
            <div class="popup-box" id="popup-box-<?php echo $item->nRCId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->refund_closing_model->showDetails($item->nRCId);?>
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

</div>
</form>
</div>
</div>
<div id="blackout"></div>
