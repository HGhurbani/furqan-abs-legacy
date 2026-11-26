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
		var nVal   = $('#txtAirline').val();
		var nVal2  = $('#txtTktName').val();
		var nVal3  = $('#txtGds').val();
		var nVal4  = $('#txtIssuedBy').val();
		var nVal5  = $('#txtPassName').val();
		var nVal6  = $('#txtFromDate').val();
		var nVal7  = $('#txtToDate').val();
		var nVal8  = $('#txtPnr').val();
		var nVal9  = $('#txtSector').val();
		var nVal10 = $('#ddlType').val();
		
		$.ajax({
            type:'POST',
            data:{txtAirline:nVal,txtTktName:nVal2,txtGds:nVal3,txtIssuedBy:nVal4,txtPassName:nVal5,txtFromDate:nVal6,
                    txtToDate:nVal7,txtPnr:nVal8,txtSector:nVal9,ddlType:nVal10,btnSearch:'Go'},
            url:'<?php echo base_url().'index.php/ticket_booking/filterList/'.$this->uri->segment(3);?>',
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
    });

	<?php
		if(intval($this->uri->segment(5)) != 0 ):
		if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>
		
		$.ajax({
            type:'POST',
            data:{id:'<?php echo $this->uri->segment(5);?>'},
            url:'<?php echo base_url().'index.php/ticket_booking/printPreview/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
				var win = window.open("","PrintWindow","width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
				win.document.write('<!DOCTYPE html>');
				win.document.write('<head>');
				win.document.write('<title>Print Accounts Copy</title>');
				//win.document.write(script);
				//win.document.write('alert("s")');
				win.document.write('<link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">');
				win.document.write('<link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">');
				win.document.write('<link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">');
				win.document.write('</head>');
				win.document.write('<body>');
				win.document.write(response);
				win.document.write('</body>');
				win.document.write('</html>');
            }
        });

        $.ajax({
            type:'POST',
            data:{id:'<?php echo $this->uri->segment(5);?>'},
            url:'<?php echo base_url().'index.php/ticket_booking/printAgentPreview/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                var win = window.open("","PrintAgentWindow","width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
                win.document.write('<!DOCTYPE html>');
                win.document.write('<head>');
                win.document.write('<title>Print Agent Copy</title>');
                win.document.write('<link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">');
                win.document.write('<link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">');
                win.document.write('<link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">');
                win.document.write('</head>');
                win.document.write('<body>');
                win.document.write(response);
                win.document.write('</body>');
                win.document.write('</html>');
            }
        });
	<?php endif;endif;?>
});
</script>
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<div id="boxes">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<h3 class="content-box-header"><a href="<?php echo base_url().'index.php/ticket_booking/editTicketbooking/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWTICKETBOOKING;?></a></h3>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/ticket_booking/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/ticket_booking/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/ticket_booking/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->

<table>
<th><?php echo AIRLINE;?></th>
<th><?php echo TICKETNAME;?></th>
<th><?php echo GDSNAME;?></th>
<th><?php echo ISSUEDBY;?></th>
<th><?php echo PASSENGER;?></th>
<th><?php echo ACCFROMDATE;?></th>
<th><?php echo ACCTODATE;?></th>
<th><?php echo TYPE;?></th>
<th><?php echo PNRNAME;?></th>
<th><?php echo SECTOR;?></th>

<tr>
<td><input type="text" name="txtAirline" id="txtAirline" value="<?php echo $this->txtAirline;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtGds" id="txtGds" value="<?php echo $this->txtGds;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtIssuedBy" id="txtIssuedBy" value="<?php echo $this->txtIssuedBy;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtPassName" id="txtPassName" value="<?php echo $this->txtPassName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtFromDate" id="txtFromDate" value="<?php echo $this->txtFromDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtToDate" id="txtToDate" value="<?php echo $this->txtToDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType,'id="ddlType" class="ddlSearch"');?>&nbsp;&nbsp;</td>
<td><input type="text" name="txtPnr" id="txtPnr" value="<?php echo $this->txtPnr;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtSector" id="txtSector" value="<?php echo $this->txtSector;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
</tr>
</table><br />

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/ticket_booking/delrecords',$attributes); ?>
<?php echo form_hidden('nId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo TRAVELLER;?></th>
           <th align="left"><?php echo TICKETNO;?></th>
           <th align="left"><?php echo GDS;?></th>
           <th align="left"><?php echo STATUS;?></th>
           <th align="left"><?php echo FACEFARE;?></th>
           <th align="left"><?php echo TAX;?></th>
           <th align="left"><?php echo NETTOVENDOR;?></th>
           <th align="left"><?php echo TICKETBOOKINGDETAILS;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="11">
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
			switch($item->vType)
			{
				case "N":
					$stype = 'Normal';
				break;
				
				case "V":
					$stype = 'Void';
				break;
				
				case "C":
					$stype = 'Conjuction';
				break;
				
				case "R":
					$stype = 'Re issued';
				break;
			}//end siwthc
	?>
	<tr>
		<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nTId;?>" class="chcktbl"/></td>
		<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
		<td align="left"><?php echo $item->vPassenger;?></td>
		<td align="left"><a href="<?php echo base_url();?>index.php/ticket_booking/editTicketbooking/<?php echo $this->uri->segment(3).'/e/'.$item->nTId;?>" title="<?php echo EDIT;?>"><?php echo $item->vTicktManual;?></a></td>
		<td align="left"><?php echo $item->gdsName;?></td>
        <td align="left"><?php echo $stype;?></td>
        <td align="left"><?php echo $this->common_model->get_field_name('TicketFareDetails','vFaceValue'," WHERE nTicketId = '".$item->nTId."'");?></td>
        <td align="left"><?php echo $this->common_model->get_field_name('TicketFareDetails','vTaxTotal'," WHERE nTicketId = '".$item->nTId."'");?></td>
        <td align="left"><?php echo $this->common_model->get_field_name('TicketFareDetails','nNetVendor'," WHERE nTicketId = '".$item->nTId."'");?></td>
	    <td align="left"><a class="popup-link-<?php echo $item->nTId;?>"><?php echo VIEW;?></a></td>
		<td align="left">
		<!-- Icons -->
		  <a href="<?php echo base_url();?>index.php/ticket_booking/editTicketbooking/<?php echo $this->uri->segment(3).'/e/'.$item->nTId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
            <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
            <a href="<?php echo base_url();?>index.php/ticket_booking/delticketbooking/<?php echo $this->uri->segment(3).'/'.$item->nTId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
            <?php endif;?>
			<!-- Start of Sticky Note -->
			<div class="popup-box" id="popup-box-<?php echo $item->nTId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	 <?php echo $this->ticket_booking_model->showDetails($item->nTId);?>
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
    <td colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
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