<?php
/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */
// +----------------------------------------------------------------------+
// | PHP version 4/5                                                      |
// +----------------------------------------------------------------------+
// | Copyright (c) 2004-2010 ABS Technologies                             |
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
		var pass   = $('#txtPassName').val();
		var doc  = $('#txtdoc').val();
		var file  = $('#txtfile').val();
		var stk = $('#txtstk').val();
		
		
		$.ajax({
            type:'POST',
            data:{txtPassName:pass,txtdoc:doc,txtfile:file,txtstk:stk,btnSearch:'Go'},
            url:'<?php echo base_url().'index.php/visa_entry/filterList/'.$this->uri->segment(3);?>',
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
            contentBoxExport.addClass('fixed');
        }
        else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
            contentBoxExport.removeClass('fixed');
        }
    });

	<?php
		if(intval($this->uri->segment(5)) != 0 ):
		if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>
		
				//window.open('<?php echo base_url().'index.php/visa_entry/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
				
				
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
    <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/visa_entry/editVisa/'.$this->uri->segment(3);?>/a"><?php echo "ADD NEW VISA ENTRY";?></a></h3>
    <?php endif;?>


<table>
<tr>
<th>PASSANGER</th> 
<th>DOCUMENT</th>
<th>FILE NUMBER</th>
<th>VISA TYPE</th>
<th></th>
</tr>
<tr>
<td><input type="text" name="txtPassName" id="txtPassName" value="" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtdoc" id="txtdoc" value="" class="txtSearch2" />&nbsp;&nbsp;</td> 
<td><input type="text" name="txtfile" id="txtfile" value="" class="txtSearch2" />&nbsp;&nbsp;</td>
<td>
 <?php echo form_dropdown('txtstk', $this->visa_entry_model->visatype(), $this->ddlReport, ' id="txtstk" class="txtSearch2"');?>
&nbsp;&nbsp;</td>
<td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
</tr>
<tr>

</table><br>


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
           
		   <th align="left"></th>
		   <th align="left">#</th>
		   <th align="left">Print</th>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo DOCUMENT;?></th>
         
           <th align="left"><?php echo "Visa File";?></th>
		   <th align="left"><?php echo "Passanger";?></th>
           <th align="left"><?php echo "Staff";?></th>

           <th align="left"><?php echo STATUS;?></th>
           <th align="left"><?php echo LOCATION;?></th>
           <th align="left"><?php echo "Visa Type";?></th>
           <th align="left"><?php echo NETAMT;?></th>
         <th align="left"><?php echo SLSAMT;?></th>
		 <th align="left"><?php echo PROFIT;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    
 
    <tbody>
    <?php $i= 1;
	if(!empty($list_value)) {
		?>
    <?php foreach($list_value as $key=>$item):
	
			
	?>
	<tr>
		<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nVId;?>" class="chcktbl"/></td>
		<td align="left">
			    <form target = '_blank' action="<?php echo base_url();?>index.php/visa_entry/printPreview/<?php echo $item->nVId;?>" title="<?php echo EDIT;?>">			 
                </form>
			</td>
		<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
		<td align="left">
			    <form target = '_blank' action="<?php echo base_url();?>index.php/visa_entry/printPreview/<?php echo $item->nVId;?>" title="<?php echo EDIT;?>">
                <input type="submit" value="Print">
		        </form>
			</td>
            <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
		<td align="left"><?php echo $item->vDocNumber;?></td>
		
		<td align="left">
            <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/visa_entry/editVisa/<?php echo $this->uri->segment(3).'/e/'.$item->nVId;?>" title="<?php echo EDIT;?>"><?php echo $item->vTicktManual;?></a>
            <?php endif;?>
        </td>
		<td align="left"><?php echo $this->common_model->get_field_name('VisaEntryPassDetails','vPassName'," WHERE nVId='".$item->nVId."'");?></td> 
		
            <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));?></td>
		
        <td align="left"><?php echo $this->common_model->get_field_name('VisaSetup','vVisaItem'," WHERE nVId='".$item->vStatus."'"); ?> </td>
		
		<td align="left"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'");?></td>
        <td align="left"><?php echo $this->common_model->get_field_name('VisaSetup','vVisaItem'," WHERE nVId='".$item->nVisaType."'");?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('VisaEntryAmtDetails','sum(netamt)'," WHERE vid='".$item->nVId."'");?></td> 
		<td align="left"><?php echo $this->common_model->get_field_name('VisaEntryAmtDetails','sum(slsamt)'," WHERE vid='".$item->nVId."'");?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('VisaEntryAmtDetails','sum(profit)'," WHERE vid='".$item->nVId."'");?></td>
		<td align="left">
            
                <?php //user access 
				$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
			if($aid!=45){
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/visa_entry/delvisaentry/<?php echo $this->uri->segment(3).'/'.$item->nVId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
			<?php endif; }?>
		    

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
