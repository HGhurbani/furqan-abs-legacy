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
/*
        //fetch ticket
        $('#fetchTkt').click(function(e) {

            $.ajax({
                type:'POST',
                url:'<?php echo base_url();?>index.php/automatic_ticket_insert',
                dataType:'html',
                success: function(response) {
                    alert(response);
                }
            });
        });

        //fetch ticket
        $('#fetchAmds').click(function(e) {

            $.ajax({
                type:'POST',
                url:'<?php echo base_url();?>index.php/amadeous_ticket_insert',
                dataType:'html',
                success: function(response) {
                    alert(response);
                }
            });
        });
 */
    <?php
    if(intval($this->uri->segment(5)) != 0 ):
        if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'): ?>
            window.open('<?php echo base_url().'index.php/sales_closing/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
            <?php endif;
    endif;?>

    });
</script>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
    <tr>
        <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
		<th align="left"></th>
        <th align="left">#</th>
		<th align="left">Print Invoice</th>
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
        <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nSCId;?>" class="chcktbl"/></td>
		
		            <!-- adding print button here for printing each invoice=the same as update_sales_closing button-->
                    <td align="left">
                        <form target = '_blank' action="<?php echo base_url();?>index.php/sales_closing/printPreview/<?php echo $item->nSCId;?>" title="<?php echo EDIT;?>">
                        </form>
					</td>
		
		
		
        <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
		<!-- adding print button here for printing each invoice=the same as update_sales_closing button-->
		<td align="left">
            <form target = '_blank' action="<?php echo base_url();?>index.php/sales_closing/printPreview/<?php echo $item->nSCId;?>" title="<?php echo EDIT;?>">
            <input type="submit" value="Print">
            </form>
		</td>
		
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
		
		//calculating total amt,total sales ,total profit
		$totalnet=0;
		$totalsale=0;
		$totalprofit=0;
		
		
        $getDetails = $this->sales_closing_model->getSalesDetails($item->nSCId);
        if(!empty($getDetails)){
            foreach($getDetails as $key1=>$detailItem):
                $getSalesAirDetails    = $this->sales_closing_model->returnSalesAirClosingDetails($detailItem->nId);
                $getSalesDateDetails   = $this->sales_closing_model->returnSalesDateClosingDetails($detailItem->nId);
                $getSalesPassDetails   = $this->sales_closing_model->returnSalesPassClosingDetails($detailItem->nId);
                $getSalesNetDetails    = $this->sales_closing_model->returnSalesNetClosingDetails($detailItem->nId);
                $getSalesSaleDetails   = $this->sales_closing_model->returnSalesSaleClosingDetails($detailItem->nId);
                $getSalesProfitDetails = $this->sales_closing_model->returnSalesProfitClosingDetails($detailItem->nId);
				
				
				//calculating total amt,total sales ,total profit
				$totalnet+=$getSalesNetDetails;
				$totalsale+=$getSalesSaleDetails;
				$totalprofit+=$getSalesProfitDetails;
				
				

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
                <td align="left" colspan="7">&nbsp;</td>
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
                <?php endforeach;?>
				
			<tr>
                <td align="left" colspan="7">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
                <td align="left"><strong>Total</strong></td>
                <td align="left" ><strong><?php echo $totalnet ?><strong></td>
                <td align="left" ><strong><?php echo $totalsale ?></strong></td>
                <td align="left" ><strong><?php echo $totalprofit ?><strong></td>
                <td align="left">&nbsp;</td>
                <td align="left">&nbsp;</td>
            </tr>
				
				
		
        
   <?php }
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
<div id="blackout"></div>
