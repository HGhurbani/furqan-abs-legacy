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
header_remove('Set-Cookie');
		ini_set('memory_limit', '-1');                                               
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

	/*$('#btnGo').click(function()
	{
        if($('#ddlAction').val()!='')
        {
            var msg="<?php echo AREYOUSURE;?> "+"?";
        }
        if (confirm(msg))
        {
            return true;
        }
        else
        {
            return false;
        }

	});*/
$('#ddlLocation1').change(function()	{		var nVal9  = $('#ddlLocation').val();		var nVal  = $('#txtAirline').val();		var nVal2 = $('#txtTktName').val();		var nVal3 = $('#txtInvoice').val();		var nVal4 = $('#txtIssuedBy').val();		var nVal5 = $('#txtPassName').val();		var nVal6 = $('#txtFromDate').val();		var nVal7 = $('#txtToDate').val();		var nVal8 = $('#txtAccname').val();		$.ajax(			{				type:'POST',				data:{txtAirline:nVal,txtTktName:nVal2,txtInvoice:nVal3,txtIssuedBy:nVal4,txtPassName:nVal5,txtFromDate:nVal6,					  txtToDate:nVal7,txtAccname:nVal8,txtLocation:nVal9,btnSearch:'Go'},				url:'<?php echo base_url().'index.php/sales_closing/filterList/'.$this->uri->segment(3);?>',				dataType:'html',				beforeSend: function() {					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');				},				success: function(response) {					$('#result').html(response);				}			});	});
	$('#btnSearch').click(function()
	{		var nVal9  = $('#txtLocation').val();
		var nVal  = $('#txtAirline').val();
		var nVal2 = $('#txtTktName').val();
		var nVal3 = $('#txtInvoice').val();
		var nVal4 = $('#txtIssuedBy').val();
		var nVal5 = $('#txtPassName').val();
		var nVal6 = $('#txtFromDate').val();
		var nVal7 = $('#txtToDate').val();
		var nVal8 = $('#txtAccname').val();

		$.ajax(
			{
				type:'POST',
				data:{txtAirline:nVal,txtTktName:nVal2,txtInvoice:nVal3,txtIssuedBy:nVal4,txtPassName:nVal5,txtFromDate:nVal6,
					  txtToDate:nVal7,txtAccname:nVal8,txtLocation:nVal9,btnSearch:'Go'},
				url:'<?php echo base_url().'index.php/sales_closing/filterList/'.$this->uri->segment(3);?>',
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
        if($(this).scrollTop() > (contentBoxExportPos.top - 120) && contentBoxExport.css('position') == 'static'){
            contentBoxExport.addClass('fixed')
        }
        else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
            contentBoxExport.removeClass('fixed')
        }
    });

    var contentBoxButton = $('.tab-btn-container'), contentBoxButtonPos = contentBoxButton.offset();
    $('#maincontent').scroll(function() {
        console.log(contentBoxButtonPos);
        if($(this).scrollTop() > (contentBoxButtonPos.top - 80) && contentBoxButton.css('position') == 'static'){
            contentBoxButton.addClass('fixed');
            contentBoxButton.find('.inner').css('top', contentBoxButtonPos.top+36);
        }
        else if($(this).scrollTop() <= contentBoxButtonPos.top && contentBoxButton.hasClass('fixed')){
            contentBoxButton.removeClass('fixed')
        }
    });

        $('#toPopup4').hide();

        //fetch ticket
        $('#fetchXLTkt').click(function(e) {
                $('#toPopup4').show();
        });
/*        $('#btnProcess').click(function(e) {

             $.ajax({
                type:'POST',
                url:'<?php echo base_url();?>index.php/fetch_excel_tickets',
                dataType:'html',
                success: function(response) {
                    alert(response);
                    $('#toPopup4').hide();
                }
            });
        });
*/

	//fetch ticket
    $('#fetchTkt').click(function(e) {

        $.ajax({
            type:'POST',
            url:'<?php echo base_url();?>index.php/automatic_ticket_insert',
            dataType:'html',
            success: function(response) {
                    alert("Galelio: " +response);

            }
        });

        $.ajax({
            type:'POST',
            url:'<?php echo base_url();?>index.php/amadeous_ticket_insert',
            dataType:'html',
            success: function(response) {
                    alert("Amadeous: " + response);

            }
        });
		
		$.ajax({
            type:'POST',
            url:'<?php echo base_url();?>index.php/sabre_ticket_insert',
            dataType:'html',
            success: function(response) {
                    alert("Sabre: " + response);

            }
        });
		
		
		
    });

<?php if($fetchExcelMsg != ''){ ?>
        alert('<?php echo "Excel " . $fetchExcelMsg; ?>');
<?php } ?>

});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>


<div class="tab-btn-container">
    <div class="inner">
        <a href="<?php echo base_url();?>index.php/sales_closing/showUnclosedTicket/4" class="tab-button active"><?php echo UNCLOSEDTKT;?></a> <a href="<?php echo base_url();?>index.php/sales_closing/showlist/4" class="tab-button"><?php echo CLOSEDTKT;?></a>
        <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
        <a class="tab-button" href="#" id="fetchTkt"><?php echo FETCHIATA;?></a>
        <a href="#" class="topopup tab-button" id="fetchXLTkt"><?php echo FETCHEXCELTICKET;?></a>
        <a href="<?php echo base_url().'index.php/sales_closing/editSalesClosing/'.$this->uri->segment(3);?>/a" class="tab-button"><?php echo ADDNEWSALESCLOSING;?></a>
        <?php endif;?>
    </div>
</div>

<div id="boxes">
<?php
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
if($message != '')
echo ($message);

?>
<?php $this->load->view('general/admin_menu');?>

<!-- <div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/sales_unclosed/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/sales_unclosed/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/sales_unclosed/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div> -->
</div> <!--.content-box-export-->
<?php ?>
<table>
    <tr>
        <th><?php echo AIRLINE;?></th>
        <th><?php echo "Ticket #";?></th>
        <th><?php echo INVOICE;?></th>
        <th><?php echo ISSUEDBY;?></th>
        <th><?php echo PASSENGER;?></th>
        <th><?php echo ACCFROMDATE;?></th>
        <th><?php echo ACCTODATE;?></th>
        <th><?php echo ACCNAME;?></th>		
		<th><?php echo LOCATION;?></th>
    </tr>

    <tr>
        <td><?php echo form_dropdown('"txtAirline"', $list_airline_value, $this->txtAirline,'id="txtAirline" class="txtSearch2"');?>&nbsp;&nbsp;</td>
        <td><input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
        <td><input type="text" name="txtInvoice" id="txtInvoice" value="<?php echo $this->txtInvoice;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
        <td>
		<?php echo form_dropdown('txtIssuedBy', $list_emp_value, $this->txtIssuedBy,'id="txtIssuedBy" class="txtSearch2"');?>&nbsp;&nbsp;</td>
        <td><input type="text" name="txtPassName" id="txtPassName" value="<?php echo $this->txtPassName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
        <td><input type="text" name="txtFromDate" id="txtFromDate" value="<?php echo $this->txtFromDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
        <td><input type="text" name="txtToDate" id="txtToDate" value="<?php echo $this->txtToDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
        <td><input type="text" name="txtAccname" id="txtAccname" value="<?php echo $this->txtAccname;?>" class="txtSearch2" />&nbsp;&nbsp;</td>	
		<td><?php echo form_dropdown('txtLocation', $list_loc_value, $this->txtLocation,'id="txtLocation" class="txtSearch2"');?>&nbsp;&nbsp;</td>
        <td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>		
    </tr>
</table><br />
<style>
.txtSearch2{
	width: 98%;
    
	
}
</style>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/sales_closing/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
    <tr>
        <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
        <th align="left">#</th>
        <th align="left"><?php echo "Ticket #";?></th>
<!--        <th align="left"><?php echo INVOICE;?></th>
        <th align="left"><?php echo INVDATE;?></th>
        <th align="left"><?php echo ACCNAME;?></th> -->
        <th align="left"><?php echo LOCATION;?></th>
        <th align="left"><?php echo SALETYPE;?></th>
        <th align="left"><?php echo AIRLINECODE;?></th>
        <th align="left"><?php echo ISSUEDBY;?></th>
        <th align="left"><?php echo ISSUEDDATE;?></th>
        <th align="left"><?php echo GDS ?></th>
        <th align="left"><?php echo PASSENGER;?></th>
        <th align="left"><?php echo FACEFARE;?></th>
        <th align="left"><?php echo TAX;?></th>
        <th align="left"><?php echo NETAMT;?></th>
        <th align="left"><?php echo SALESAMT;?></th>
    </tr>
    </thead>

    <tfoot>
    <tr class="formButs">
        <td colspan="19">
        <?php if(!empty($list_value))
        {
        ?>
        <div class="bulk-actions align-left">
            <?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F' || $action_access->vAccess == 'E'):?>
            <input type="submit" value="<?php echo INVOICEBULK;?>" class="button" name="btnGo" id="btnGo"/>
            <?php endif;?>
        </div>
        <?php
        }//end if ?>
        </td>
    </tr>
    </tfoot>

    <tbody>
    <?php $i= 1;?>
    <?php foreach($list_value as $key=>$item):
	    //var_dump($list_value);
        switch($item->sale_type)
        {
            case "Ticket":
                $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$item->tableId;
                break;

            case "Xo":
                $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$item->tableId;
                break;

            case "Hotel":
                $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$item->tableId;
                break;
        }//end switch
        ?>
    <tr>
        <td align="left">
            <input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->tableId;?>-<?php echo $item->sale_type;?>" class="chcktbl"/>
            <input type="hidden" name="txtTktType<?php echo $item->tableId;?>" id="txtTcktType<?php echo $item->tableId;?>" value="<?php echo $item->sale_type;?>" />
        </td>
        <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
        <td align="left"><a href="<?php echo $path;?>"><?php echo stripslashes($item->tktNumber);?></a></td>
<!--         <td align="left"></td>
        <td align="left"></td>
        <td align="left"></td> -->
        <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Location','vCode', " WHERE nId = '".$item->nLocation."'"));?></td>
        <td align="left"><?php echo $item->sale_type;?></td>
        <td align="left"><?php echo stripslashes($this->common_model->get_field_name('Airline','vAirlineNo', " WHERE nArId = '".$item->airlineName."'"));?></td>
        <td align="left"><?php echo stripslashes($this->common_model->get_field_name('EmployeeMaster','vCode', " WHERE nEId = '".$item->vIssuedBy."'"));?></td>
        <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
        <td align="left"><?php 
		if($item->lpo==0){
			echo"Non Bsp";}
			elseif($item->lpo==1){
				echo"Amadeus";}
				elseif($item->lpo==2){
					echo"Sabre";}
					elseif($item->lpo==3){
						echo"Galileo";}
		?></td>
        <td align="left"><?php echo stripslashes($item->vPassenger);?></td>
        <td align="left"><?php echo $item->fFare;?></td>
        <td align="left"><?php echo $item->totTax;?></td>
        <td align="left"><?php echo stripslashes($item->netAmt);?></td>
        <td align="left"><?php echo stripslashes($item->vSalesAmt);?></td>
    </tr>
        <?php $i = $i+1;?>
        <?php endforeach;?>

    <?php if(empty($list_value))
    {?>
    <tr>
        <td colspan="17" align="center"><?php echo SORRYNORECORD;?></td>
    </tr>
    <?php }//end if?>
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

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php echo form_open_multipart('fetch_excel_tickets/insertTkts',$attributes); ?>
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
        <tbody>
        <tr>
            <td>
            <input type="file" name="txtFile" id="txtFile" />
            </td>
        </tr>
        <tr><td>&nbsp;</td></tr>
        <tr class="formButs">
            <td>&nbsp;
            <input type="submit" name="btnProcess" value="<?php echo FETCH;?>" id="btnProcess"/>
            </td>
        </tr>
<tr><td>        <input type="hidden" name="link" id="link" value="<?php  echo base_url() . 'index.php/'.$this->uri->segment(1).'/'.$this->uri->segment(2).'/'.$this->uri->segment(3); ?>" ></td></tr>
        </tbody>
        </table>
    </div>
    <!-- End of Sticky Note -->
</div>

