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
                    }//end if
                    if($('#ddlAction').val()=='no')
                    {
                        var msg="<?php echo AREYOUSUREWANTTOACTIVE;?> "+$('#ddlAction').val()+"?";
                    }//end if
                    if($('#ddlAction').val()=='delete')
                    {
                        var msg="<?php echo AREYOUSURE;?> "+$('#ddlAction').val()+"?";
                    }//end if
                    if (confirm(msg))
                    {
                        return true;
                    }//end if
                    else
                    {
                        return false;
                    }//end else
                }//end if
                else
                {
                    alert("<?php echo PLEASECHECKITEM;?>");
                    return false;
                }//end else
            }//end else

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
                url:'<?php echo base_url().'index.php/sales_closing/closedFilterList/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#result').html(response);
                }
            });
        });
			
        $('#toPopup4').hide();

        //fetch ticket
        $('#fetchXLTkt').click(function(e) {
                $('#toPopup4').show();
        });

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
        });

        <?php
        if(intval($this->uri->segment(5)) != 0 ):
            if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'): ?>
                window.open('<?php echo base_url().'index.php/sales_closing/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
            <?php endif;
        endif;?>

    });
</script>
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet"> 

<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>


<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<div class="tab-btn-container">
    <div class="inner">
        <a href="<?php echo base_url();?>index.php/sales_closing/showUnclosedTicket/4" class="tab-button"><?php echo UNCLOSEDTKT;?></a> <a href="<?php echo base_url();?>index.php/sales_closing/showlist/4" class="tab-button active"><?php echo CLOSEDTKT;?></a>
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
echo $this->common_model->successMsg($message);
?>
<?php $this->load->view('general/admin_menu');?>

    <div class="content-box-export">
        <div class="inner">
            <a href="<?php echo base_url().'index.php/export_xl/sales_closing/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
            <a href="<?php echo base_url().'index.php/export_word/sales_closing/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
            <a href="<?php echo base_url().'index.php/export_pdf/sales_closing/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
        </div>
    </div> <!--.content-box-export-->

<table>
	<th><?php echo AIRLINE;?></th>
	<th><?php echo TICKETNAME;?></th>
	<th><?php echo INVOICE;?></th>
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
				<th align="left"><?php echo INVOICE;?></th>
				<th align="left"><?php echo INVDATE;?></th>
				<th align="left"><?php echo ACCNAME;?></th>
				<th align="left"><?php echo SALETYPE;?></th>
				<th align="left"><?php echo AIRLINECODE;?></th>
				<th align="left"><?php echo TICKETNAME;?></th>
				<th align="left"><?php echo ISSUEDBY;?></th>
				<th align="left"><?php echo ISSUEDDATE;?></th>
				<th align="left"><?php echo LPO;?></th>
				<th align="left"><?php echo PASSENGER;?></th>
                <th align="left"><?php echo NETAMT;?></th>
				<th align="left"><?php echo SALESAMT;?></th>
				<th align="left"><?php echo PROFIT;?></th>
				<th align="left"><?php echo DETAILS;?></th>
				<th align="left"><?php echo ACTION;?></th>
			</tr>
			</thead>

            <tbody>
            <?php $i = 1;?>
            <?php foreach($list_value as $key=>$item): ?>
                <tr>
                    <td align="left">
<?php  if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F' || $action_access->vAccess == 'E' ): ?>
<input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nSCId;?>" class="chcktbl"/>
<?php endif; ?>
</td>
                    <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
                    <td align="left">
                        <?php //user access aa // if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                        <a href="<?php echo base_url();?>index.php/sales_closing/editSalesClosing/<?php echo $this->uri->segment(3).'/e/'.$item->nId.'/'.$item->nSCId;?>/cl" title="<?php echo EDIT;?>"><?php echo stripslashes($item->vInvoiceNumber);?></a>
                        <?php //endif;?>
                    </td>
                    <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
                    <td align="left"><?php if($item->vTable =='C'){echo $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->nDrAccount."'");}else{echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->nDrAccount."'");}?></td>
                    <td colspan="10"></td>
                    <td align="left"><a class="popup-link-<?php echo $item->nSCId;?>"><?php echo VIEW;?></a>
                        <div class="popup-box" id="popup-box-<?php echo $item->nSCId;?>">
                            <div class="close">X</div>
                            <div class="top">
                                <h2>Details</h2>
                            </div>
                            <div class="bottom">
                                <div class="wa">
                                    <?php echo $this->sales_closing_model->showDetails($item->nSCId);?>
                                </div>
                            </div>
                        </div>
                    </td>
                    <td align="left">
                        <?php //user access 
                        if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                        <a href="<?php echo base_url();?>index.php/sales_closing/delsalesclosing/<?php echo $this->uri->segment(3).'/'.$item->nSCId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                        <?php endif;?>
                    </td>
                </tr>
                <?php $i = $i+1;
                $getDetails = $this->sales_closing_model->getSalesDetails($item->nSCId);
                if(!empty($getDetails)){
                    foreach($getDetails as $key1=>$detailItem):
                        $getSalesAirDetails    = $this->sales_closing_model->returnSalesAirClosingDetails($detailItem->nId);
                        $getSalesDateDetails   = $this->sales_closing_model->returnSalesDateClosingDetails($detailItem->nId);
                        $getSalesPassDetails   = $this->sales_closing_model->returnSalesPassClosingDetails($detailItem->nId);
                        $getSalesNetDetails    = $this->sales_closing_model->returnSalesNetClosingDetails($detailItem->nId);
                        $getSalesSaleDetails   = $this->sales_closing_model->returnSalesSaleClosingDetails($detailItem->nId);
                        $getSalesProfitDetails = $this->sales_closing_model->returnSalesProfitClosingDetails($detailItem->nId);

                        switch($detailItem->vType)
                        {
                            case "Tkt" :
                                $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual', " WHERE nTId = '".$detailItem->vTktNumber."'");
                                $path = base_url().'index.php/ticket_booking/editTicketbooking/4/e/'.$detailItem->vTktNumber;
                                break;

                            case "Xo" :
                                $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual', " WHERE nXId = '".$detailItem->vTktNumber."'");
                                $path = base_url().'index.php/xo_booking/editXobooking/4/e/'.$detailItem->vTktNumber;
                                break;

                            case "Htl" :
                                $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual', " WHERE nHVId = '".$detailItem->vTktNumber."'");
                                $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$detailItem->vTktNumber;
                                break;
                        }//end switch
                        ?>
                            <tr>
                                <td align="left" colspan="5">&nbsp;</td>
                                <td align="left"><?php echo stripslashes($detailItem->vType);?></td>
                                <td align="left"><?php echo stripslashes($getSalesAirDetails);?></td>
                                <td align="left"><?php echo stripslashes($tktNumber);?></td>
                                <td align="left"><?php echo stripslashes($detailItem->vEmployee);?></td>
                                <td align="left"><?php echo stripslashes($getSalesDateDetails);?></td>
                                <td align="left"><?php echo stripslashes($detailItem->vLPO);?></td>
                                <td align="left"><?php echo stripslashes($getSalesPassDetails);?></td>
                                <td align="left"><?php echo stripslashes($getSalesNetDetails);?></td>
                                <td align="left"><?php echo stripslashes($getSalesSaleDetails);?></td>
                                <td align="left"><?php echo stripslashes($getSalesProfitDetails);?></td>
                                <td align="left">&nbsp;</td>
                                <td align="left">&nbsp;</td>
                            </tr>
                    <?php endforeach;
                }
            endforeach;?>
            <?php if(empty($list_value))
            {?>
            <tr>
                <td colspan="17" align="center"><?php echo SORRYNORECORD;?></td>
            </tr>
                <?php }//end if?>
            </tbody>

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
        </form>
    </div>
    <!-- End of Sticky Note -->
</div>

