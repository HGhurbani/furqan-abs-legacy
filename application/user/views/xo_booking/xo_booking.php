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

date_default_timezone_set("Asia/Dubai");   
?>
<style>
table.entryForm th{
	padding: 4px 0px !important;
}
.txtSearch{
	width: 68%;
    
	
}
.txtSearch2{
	width: 68%;
    
	
}
.ddlSearch{
	width: 68%;
    
	
}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">

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
//		var nVal9  = $('#txtSector').val();
		var nVal10 = $('#ddlType').val();
        var nVal_loc  = $('#ddlLocation').val();

		
		$.ajax({
            type:'POST',
            data:{txtAirline:nVal,txtTktName:nVal2,txtGds:nVal3,txtIssuedBy:nVal4,txtPassName:nVal5,txtFromDate:nVal6,
                    txtToDate:nVal7,txtPnr:nVal8,ddlType:nVal10,ddlLocation:nVal_loc,btnSearch:'Go'},
            url:'<?php echo base_url().'index.php/xo_booking/filterList/'.$this->uri->segment(3);?>',
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
    if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc' ):?>
	
	window.open('<?php echo base_url().'index.php/xo_booking/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
				
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
    <?php  
if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/xo_booking/editXobooking/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWXOBOOKING;?></a></h3>
    <?php endif;?>

<?php  //print $item->vIssuedBy;
	$em=$this->common_model->get_field_name('Admin', 'nEmpId', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");
	$gp=$this->common_model->get_field_name('Admin', 'nGroupId', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");
			//$no=$this->session->userdata('AbsAdmin');
			//$this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F');
			?>
<table>
<th><?php echo AIRLINE;?></th>
<th><?php echo LOCATION;?></th>
<th><?php echo XONO;?></th>
<th><?php echo GDSNAME;?></th>
<th><?php echo ISSUEDBY;?></th>
<th><?php echo PASSENGER;?></th>
<th><?php echo ACCFROMDATE;?></th>
<th><?php echo ACCTODATE;?></th>
<th><?php echo TYPE;?></th>
<th><?php echo PNRNAME;?></th>


<tr>
<td><?php echo form_dropdown('"txtAirline"', $list_airline_value, $this->txtAirline,'id="txtAirline" class="txtSearch chosen-select"');?>&nbsp;&nbsp;</td>
<td><?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlSearch chosen-select"');?>&nbsp;&nbsp;</td>
<td><input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><?php echo form_dropdown('txtGds', $list_gds_value, $this->txtGds,'id="txtGds" class="txtSearch2 chosen-select"');?>&nbsp;&nbsp;</td>
<?php if($gp!=44){?>
			<td><?php echo form_dropdown('txtIssuedBy', $list_emp_value, $this->txtIssuedBy,'id="txtIssuedBy" class="ddlSearch"');?>&nbsp;&nbsp;</td>
            <?php }else{?>
			<td>
			<select name="txtIssuedBy" id="txtIssuedBy" class="ddlSearch">
	<option value="<?php echo $em;?>"><?php echo $this->common_model->get_field_name('Admin', 'vUsername', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");?></option>

</select></td>
			<?php } ?><td><input type="text" name="txtPassName" id="txtPassName" value="<?php echo $this->txtPassName;?>" class="txtSearch2 textfield5" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtFromDate" id="txtFromDate" value="<?php echo $this->txtFromDate;?>" class="txtSearch2 textfield5" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtToDate" id="txtToDate" value="<?php echo $this->txtToDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType,'id="ddlType" class="ddlSearch chosen-select"');?>&nbsp;&nbsp;</td>
<td><input type="text" name="txtPnr" id="txtPnr" value="<?php echo $this->txtPnr;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<!-- <td><input type="text" name="txtSector" id="txtSector" value="<?php echo $this->txtSector;?>" class="txtSearch2" />&nbsp;&nbsp;</td> -->
<td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
</tr>
</table><br />
			
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/xo_booking/delrecords',$attributes); ?>
<?php echo form_hidden('nXId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           
		    <?php  // User access aa
            if($action_access->vAccess == 'F' && $this->session->userdata('AbsAdmin')==1):?>
			<th align="left"></th><?php endif;?>
		    <th align="left">#</th>
           <th align="left">Print</th>
<?php /*?>           <th align="left"><?php echo TRAVELLER;?></th>
<?php */?>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo TRAVELLER;?></th>
           <th align="left"><?php echo AIRLINECODE;?></th>
           <th align="left"><?php echo XONO;?></th>
           <th align="left"><?php echo ISSUED;?></th>
           <th align="left"><?php echo GDS;?></th>
           <th align="left"><?php echo STATUS;?></th>
           <th align="left"><?php echo LOCATION;?></th>
           <th align="left"><?php echo OPENCLOSED;?></th>
           <th align="left"><?php echo FACEFARE;?></th>
           <th align="left"><?php echo TAX;?></th>
           <th align="left"><?php echo NETTOVENDOR;?></th>
<!--           <th align="left"><?php echo XOBOOKINGDETAILS;?></th> -->
<!--           <th align="left"><?php echo ACTIVE;?></th> -->
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
                        <?php 
						if($aid==1 || $aid==47 || $aid==46 ){
							if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                        <option value="delete"><?php echo DELETE;?></option>
                        <?php endif; }?>
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
    
    <?php $i = 1; 
	if(!empty($list_value)) {?>
    <?php foreach($list_value as $key=>$item):?>
	<?php  //print $item->vIssuedBy;
	$em=$this->common_model->get_field_name('Admin', 'nEmpId', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");
			//$no=$this->session->userdata('AbsAdmin');
			//$this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F');
			if($item->vIssuedBy==$em ||$gp!=44){?>
	
	
	
	<tr>
    <?php 
	$status_s_x =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->nXId."' and vType = 'Xo' ");

    if(!empty($status_s_x)){
       $c=1; 
    }
    else {
       $c=0;
    }?>
		<?php if($c==0){?>
        <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nXId;?>" class="chcktbl"/></td>
        <?php }else{?>
        <td align="left"></td>
         <?php }?>
        
		<!-- Icons -->
            <?php  // User access aa
            if($action_access->vAccess == 'F' && $this->session->userdata('AbsAdmin')==1):?>
			<td align="left">
            <a href="<?php echo base_url();?>index.php/xo_booking/editXobooking/<?php echo $this->uri->segment(3).'/c/'.$item->nXId;?>" title="<?php echo EDIT;?>"><?php echo "COPY";?></a>
            </td><?php endif;?> 
		<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
          <td align="left">
          <a target="_blank" href="<?php echo base_url();?>index.php/xo_booking/printPreview/<?php echo $item->nXId;?>">
                        <input id="ttt" type="button" value="Print">
                   </a>  
          
		           </td> 
            <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
<?php /*?>		<td align="left"><?php echo $item->vPassenger;?></td>
<?php */?>	
		<td align="left"><?php  echo substr($item->vPassenger,0,20);?></td>
		<td align="left"><?php echo $item->airlineNo;?></td>
		<td align="left">
		<!-- Icons -->
            <?php  // User access aa
            //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/xo_booking/editXobooking/<?php echo $this->uri->segment(3).'/e/'.$item->nXId;?>" title="<?php echo EDIT;?>"><?php echo $item->vTicktManual;?></a>
            <?php //endif;?> </td>

            <td align="left"><?php //echo $item->vIssuedBy;
			
			echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$item->vIssuedBy."'"));?></td>
		<td align="left"><?php echo $item->gdsName;?></td>
		<td align="left"><?php echo $item->xoType;?></td>
		<td align="left"><?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId='".$item->nLocation."'");?></td>
<td align="left"><?php 
/*
$status_r_c = $this->common_model->get_field_name('RefundClosingDetails','nRefundCloseId'," WHERE nTicketNumber = '".$item->nXId."' and 

vType = 'Xo'");
  if(!empty($status_r_c)){
	echo "REFUNDCLOSED";
  }
  else {
	$status_r_o =  $this->common_model->get_field_name('RefundRegisterDetails','nRefundId'," WHERE nTktNum = '".$item->nXId."' and 

vTktType= 'Xo'");
 
      if(!empty($status_r_o)){
	echo "REFUNDOPEN";
     }
     else {
*/
    $status_s_c =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->nXId."' and vType = 'Xo' ");

    if(!empty($status_s_c)){
        echo "CLOSED";
    }
    else {
        echo "OPEN";
    }
//       }
// }
?></td>

<td align="left"><?php echo  $item->vGrandFare; ?> </td>
<td align="left"><?php echo  $item->vGrandToalTax; ?> </td>
<td align="left"><?php echo  $item->vGrandNetFare; ?> </td>
<!--	    <td align="left"><a class="popup-link-<?php echo $item->nXId;?>"><?php echo VIEW;?></a></td> -->
<!--		<td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td> -->
		
        
        <?php if($c==0){?>
        <td align="left">
		<!-- Icons -->
<!--            <?php //User access aa // if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/xo_booking/editXobooking/<?php echo $this->uri->segment(3).'/e/'.$item->nXId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
            <?php //endif;?> -->
            <?php // User access aa
            if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): 
			$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
				if($aid==1 || $aid==47 || $aid==46 || $aid==45){?> 
            <a href="<?php echo base_url();?>index.php/xo_booking/delxobooking/<?php echo $this->uri->segment(3).'/'.$item->nXId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
				<?php } endif;?>
			<!-- Start of Sticky Note 
			<div class="popup-box" id="popup-box-<?php echo $item->nXId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	 <?php echo $this->xo_booking_model->showDetails($item->nXId);?>
                        </div>
                    </div>
                </div>
			 End of Sticky Note -->
		</td>
        <?php }else{?>
        <td align="left">
		<!-- Icons -->
<!--            <?php //User access aa // if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/xo_booking/editXobooking/<?php echo $this->uri->segment(3).'/e/'.$item->nXId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
            <?php //endif;?> -->
            <?php // User access aa
            if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'):
$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
				if($aid==1 || $aid==47 || $aid==46 || $aid==45){			?> 
            <img style="cursor:pointer;" onclick="return confirm('Closed XO');" src="http://abskw.com/demo/static/images/cross.png" alt="Delete" border="0">
				<?php } endif;?>
			<!-- Start of Sticky Note 
			<div class="popup-box" id="popup-box-<?php echo $item->nXId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	 <?php echo $this->xo_booking_model->showDetails($item->nXId);?>
                        </div>
                    </div>
                </div>
			 End of Sticky Note -->
		</td>
        <?php }?>
        
	</tr>
	<?php $i= $i+1;?>
			<?php } endforeach;?>
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
