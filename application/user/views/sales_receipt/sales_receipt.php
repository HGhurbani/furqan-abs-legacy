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
		var nVal = $('#txtSearch').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtSearch:nVal,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/sales_receipt/filterList/'.$this->uri->segment(3);?>',
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
        if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>

        window.open('<?php echo base_url().'index.php/sales_receipt/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=1100, scrollbars=yes,toolbar=no,menubar=yes");


    <?php endif;endif;?>

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
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/sales_receipt/editSalesreceipt/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWSALESRECEIPT;?></a></h3>
    <?php endif;?>


<p align="right"><?php echo DIVISIONNAMEORDOCNUMBER;?>&nbsp;<input type="text" name="txtSearch" id="txtSearch" value="<?php echo $this->txtSearch;?>" class="txtSearch" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open(base_url().'index.php/sales_receipt/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
		   <th align="left">Print </th>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo DOCNUMBER;?></th>
		   <th align="left"><?php echo EMP;?></th>
		   <th align="left"><?php echo REFNAME;?></th>
           <th align="left"><?php echo TICKET;?></th>
           <th align="left"><?php echo CREDITCARD;?></th>
           <th align="left"><?php echo AMOUNT;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="12">
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
	<form target = '_blank' action="<?php echo base_url();?>index.php/sales_receipt/printPreview/<?php echo $item->nSRId;?>" title="<?php echo EDIT;?>">

       
	   </form>
    <?php $i= 1;
	
            $chkdoc = array();
    ?>
    <?php foreach($list_value as $key=>$item):?><?php  //print $item->vIssuedBy;	//$em=$this->common_model->get_field_name('Admin', 'nEmpId', " WHERE nId='".$this->session->userdata('AbsAdmin')."'");			$em=$this->session->userdata('AbsAdmin');			//$this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F');			?>
        <tr>
		
	   
<?php
$tktNumber = '';//print $item->vIssuedBy;
if($item->vEnteredBy==$this->session->userdata('AbsAdmin')|| $this->session->userdata('AbsAdmin')==1){		


switch($item->vType){
case 'Tkt':
$status_s_k =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->vTktNumber."' and vType = 'Tkt' ");

$tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId=".$item->vTktNumber." ");
$pass = $this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$item->vTktNumber."'");
break;
case 'Htl':
$status_s_k =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->vTktNumber."' and vType = 'Htl' ");

$tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId=".$item->vTktNumber." ");
$pass = $this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId = ".$item->vTktNumber." ");
break;
case 'Xo':
$status_s_k =  $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE vTktNumber= '".$item->vTktNumber."' and vType = 'Xo' ");

$tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId=".$item->vTktNumber." ");
$pass = $this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId = ".$item->vTktNumber." ");
break;

}// end case

?>

 <?php 
	$adm=$this->session->userdata('AbsAdmin');
	
               if(!empty($status_s_k)){
	
	$b=1;
	
              }
              else {

	$b=0;
               }?>
        <?php if(!in_array($item->vDocNumber,$chkdoc)){ ?>
           
<?php if($b==0){ ?>
        <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nSRId;?>" class="chcktbl"/></td>
           <?php } else{?>
        <td align="left"></td>
        	<?php }?>

		    <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
			<td align="left">

					    <form target = '_blank' action="<?php echo base_url();?>index.php/sales_receipt/printPreview/<?php echo $item->nSRId;?>" title="<?php echo EDIT;?>">

                        <input type="submit" value="Print">

                        </form>

					</td>
<td align="left"><?php echo  date('d-m-Y',strtotime($item->dDate)); ?></td>
            <td align="left">
			<?php if($b==0 || $adm==1){ ?>
                <a href="<?php echo base_url();?>index.php/sales_receipt/editSalesreceipt/<?php echo $this->uri->segment(3).'/e/'.$item->nSRId;?>" title="<?php echo EDIT;?>"> <?php echo stripslashes($item->vDocNumber);?></a>
				<?php } else{?>
        <?php echo stripslashes($item->vDocNumber);?>
        	<?php }?>
 
 <?php //endif;?>
</td>
             <td align="left">
			<?php echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$item->vEnteredBy."'");  ?>
			</td>
            <?php }else{ ?>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
				<td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <?php } ?>
			<td align="left"><?php echo stripslashes($pass);?></td>
            <td> <?php echo stripslashes($tktNumber);?></td>
            <td> <?php echo stripslashes($this->common_model->get_field_name('Card', 'vName'," WHERE  nCId = " . $item->nCardId." "));?></td>
            <td> <?php echo stripslashes($item->nAmountDr);?></td>
        <?php if(!in_array($item->vDocNumber,$chkdoc)){ ?>
            <td align="left">
            <!-- Icons -->
			<?php if($b==0){ ?>
			
			
               <?php //user access 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/sales_receipt/delSalesreceipt/<?php echo $this->uri->segment(3).'/'.$item->nSRId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
				
			<?php } else{?>
			<?php //user access 
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <img style="cursor:pointer;" onclick="return confirm('Closed Tickets');" src="http://abskw.com/demo/static/images/cross.png" alt="Delete" border="0">
            <?php endif;?>
			<?php }?>
                </td>
                <?php } ?>
<?php $i= $i+1;
$chkdoc[] = $item->vDocNumber; ?>
<?php } endforeach;?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
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
