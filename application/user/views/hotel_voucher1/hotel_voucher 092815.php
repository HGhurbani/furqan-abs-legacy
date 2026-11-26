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
<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">

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
			}//end if
			else
			{
				alert("<?php echo PLEASECHECKITEM;?>");
				return false;
			}//end else
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
    });

    <?php
	if(intval($this->uri->segment(5)) != 0 ):
    if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>
	
	            window.open('<?php echo base_url().'index.php/hotel_voucher/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
				
				window.open('<?php echo base_url().'index.php/hotel_voucher/printAgentPreview/'.$this->uri->segment(5);?>','PrintAgentWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
    <?php endif;endif;?>


    $('#btnSearch').click(function()
   {
        var txtVoucher     = $('#txtVoucher').val();
        var txtVendor      = $('#txtVendor').val();
        var txtPassName    = $('#txtPassName').val();
        var txtConf        = $('#txtConf').val();
        var txtCity        = $('#txtCity').val();
		var txtFromDate    = $('#txtFromDate').val();
		var txtToDate    = $('#txtToDate').val();
        var txtLocation    = $('#txtLocation').val();
        var txtIssuedBy    = $('#txtIssuedBy').val();
        $.ajax(
            {
                type:'POST',
                data:{txtVoucher:txtVoucher,txtVendor:txtVendor,txtPassName:txtPassName,txtConf:txtConf,txtCity:txtCity,txtFromDate:txtFromDate,txtToDate:txtToDate,txtLocation:txtLocation,txtIssuedBy:txtIssuedBy,btnSearch:'Go'},
                url:'<?php echo base_url().'index.php/hotel_voucher/filterHotelVoucher/'.$this->uri->segment(3);?>',
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
    <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/hotel_voucher/editHotelvoucher/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWHOTELVOUCHER;?></a></h3>
    <?php endif;?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/hotel_voucher/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/hotel_voucher/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/hotel_voucher/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->

    <table>
        <th><?php echo VOUCHERID;?></th>
        <th><?php echo VENDORNAME;?></th>
        <th><?php echo PASSENGER;?></th>
        <th><?php echo HOTELCONFID;?></th>
        <th><?php echo CITY;?></th>
        <th><?php echo ACCFROMDATE;?></th>
        <th><?php echo ACCTODATE;?></th>
        <th><?php echo LOCATIONNAME;?></th>
        <th><?php echo ISSUEDBY;?></th>

        <tr>
            <td><input type="text" name="txtVoucher" id="txtVoucher" value="<?php echo $this->txtVoucher;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><?php echo form_dropdown('txtVendor', $list_vendor_value, $this->txtVendor,'id="txtVendor" class="ddlSearch chosen-select"');?>&nbsp;&nbsp;</td>
            <td><input type="text" name="txtPassName" id="txtPassName" value="<?php echo $this->txtPassName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtConf" id="txtConf" value="<?php echo $this->txtConf;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><?php echo form_dropdown('txtCity', $list_city_value, $this->txtCity,'id="txtCity" class="ddlSearch chosen-select"');?>&nbsp;&nbsp;</td>
            <td><input type="text" name="txtFromDate" id="txtFromDate" value="<?php echo $this->txtFromDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtToDate" id="txtToDate" value="<?php echo $this->txtToDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><?php echo form_dropdown('txtLocation', $list_loc_value, $this->txtLocation,'id="txtLocation" class="ddlSearch"');?>&nbsp;&nbsp;</td>
            <td><?php echo form_dropdown('txtIssuedBy', $list_emp_value, $this->txtIssuedBy,'id="txtIssuedBy" class="ddlSearch chosen-select"');?>&nbsp;&nbsp;</td>
            <td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
        </tr>
    </table>
    <br />

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/hotel_voucher/delrecords',$attributes); ?>
<?php echo form_hidden('nHVId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
            <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
            <th align="left">#</th>
            <th align="left"><?php echo CUSTDATE;?></th>
            <th align="left"><?php echo PASSENGER;?></th>
            <th align="left"><?php echo VOUCHERID;?></th>
            <th align="left"><?php echo ISSUEDBY;?></th>
            <th align="left"><?php echo HOTELNAME;?></th>
            <th align="left"><?php echo COUNTRYNAME;?></th>
            <th align="left"><?php echo CITYNAME;?></th>
            <th align="left"><?php echo VENDORNAME;?></th>
            <th align="left"><?php echo LOCATIONNAME;?></th>
            <th align="left"><?php echo OPENCLOSED;?></th>
            <th align="left"><?php echo NETAMT;?></th>
<!--            <th align="left"><?php echo HOTELVOUCHERDETAILS;?></th> -->
<!--            <th align="left"><?php echo ACTIVE;?></th> -->
            <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="16">
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
    <?php foreach($list_value as $key=>$item):?>
	<tr>
		<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nHVId;?>" class="chcktbl"/></td>
		<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
		<td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
		<td align="left"><?php echo $item->vPassenger;?></td>
		<td align="left">
		<!-- Icons -->

            <?php //User access aa //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/hotel_voucher/editHotelvoucher/<?php echo $this->uri->segment(3).'/e/'.$item->nHVId;?>" title="<?php echo EDIT;?>"><?php echo $item->vTicktManual;?></a>
            <?php //endif;?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('EmployeeMaster','vCode'," WHERE nEId='".$item->vIssuedBy."'");?></td>
		<td align="left"><?php echo $item->hotelName;?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$item->nCountry."' ");?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId = '".$item->nCity."' ");?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$item->nAcCode."' ");?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'");?></td>
<td align="left"><?php 
/*
$status_r_c = $this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nTicketNumber = '".$item->nHVId."' and 

vType = 'Htl'");
  if(!empty($status_r_c)){
	echo "REFUNDCLOSED";
  }
  else {
	$status_r_o =  $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE nTktNum = '".$item->nHVId."' and 

vTktType= 'Htl'");
 
      if(!empty($status_r_o)){
	echo "REFUNDOPEN";
     }
     else {
*/
    $status_s_c =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->nHVId."' and vType = 'Htl' ");

    if(!empty($status_s_c)){
        echo "CLOSED";
    }
    else {
        echo "OPEN";
    }
//       }
// }
?></td>
		<td align="left"><?php echo $item->vTotalDC;?></td>
<!--	    <td align="left"><a class="popup-link-<?php echo $item->nHVId;?>"><?php echo VIEW;?></a></td> -->
<!--		<td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td> -->
		<td align="left">
		<!-- Icons -->
<!--            <?php //User access aa removing edit //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/hotel_voucher/editHotelvoucher/<?php echo $this->uri->segment(3).'/e/'.$item->nHVId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
            <?php //endif;?> -->
            <?php // /User access aa 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
            <a href="<?php echo base_url();?>index.php/hotel_voucher/delhotelvoucher/<?php echo $this->uri->segment(3).'/'.$item->nHVId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
            <?php endif;?>
			<!-- Start of Sticky Note 
			<div class="popup-box" id="popup-box-<?php echo $item->nHVId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->hotel_voucher_model->showDetails($item->nHVId);?>
                        </div>
                    </div>
                </div>
		     End of Sticky Note -->
		</td>
	</tr>
	<?php $i= $i+1;?>
    <?php endforeach;?>
    <?php }?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="16" align="center"><?php echo SORRYNORECORD;?></td>
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
<script src="<?php echo base_url();?>static/plugins/dropdown/chosen.jquery.js" type="text/javascript"></script>
  <script type="text/javascript">
    var config = {
      '.chosen-select'           : {},
      '.chosen-select-deselect'  : {allow_single_deselect:true},
      '.chosen-select-no-single' : {disable_search_threshold:10},
      '.chosen-select-no-results': {no_results_text:'Oops, nothing found!'},
      '.chosen-select-width'     : {width:"95%"}
    }
    for (var selector in config) {
      $(selector).chosen(config[selector]);
    }
  </script>

</div>
</div>
<div id="blackout"></div>
