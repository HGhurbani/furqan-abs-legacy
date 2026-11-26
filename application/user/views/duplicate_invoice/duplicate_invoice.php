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
                url:'<?php echo base_url().'index.php/duplicate_invoice/closedFilterList/'.$this->uri->segment(3);?>',
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
            if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'): ?>
                window.open('<?php echo base_url().'index.php/duplicate_invoice/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
                <?php endif;
        endif;?>

    });
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">

<div id="boxes">
    <?php
    $attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
    $this->load->model('common_model');
    echo $this->common_model->successMsg($this->uri->segment(4));
    ?>
    <?php  //user access
    if($action_access->vAccess == 'F' || $action_access->vAccess == 'E'): ?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/duplicate_invoice/editDuplicateInvoice/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWDUPLICATEINVOICE; ?></a></h3>
    <?php endif; ?>
    
    <table>
        <th><?php echo AIRLINE;?></th>
        <th><?php echo TICKETNAME;?></th>
<!--        <th><?php //echo INVOICE;?></th> -->
        <th><?php echo ISSUEDBY;?></th>
        <th><?php echo PASSENGER;?></th>
        <th><?php echo ACCFROMDATE;?></th>
        <th><?php echo ACCTODATE;?></th>
        <th><?php echo ACCNAME;?></th>

        <tr>
            <td><input type="text" name="txtAirline" id="txtAirline" value="<?php echo $this->txtAirline;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
<!--            <td><input type="text" name="txtInvoice" id="txtInvoice" value="<?php echo $this->txtInvoice;?>" class="txtSearch2" />&nbsp;&nbsp;</td> -->
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
        <?php echo form_open(base_url().'index.php/duplicate_invoice/delrecords',$attributes); ?>
        <?php echo form_hidden('nCId', $this->uri->segment(3));?>
        <div id="result">
            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
                <thead>
                <tr>
				<th align="left"></th>
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
                                <option value="delete"><?php echo DELETE;?></option>
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
                <?php foreach($list_value as $key=>$item):?>

                    <tr>
					<form target = '_blank' action="<?php echo base_url();?>index.php/sales_closing/printPreview/<?php echo $item->nSCId;?>" title="<?php echo EDIT;?>">

                        </form>
                        <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nDId;?>" class="chcktbl"/></td>
                        <td align="left">

					    <form target = '_blank' action="<?php echo base_url();?>index.php/duplicate_invoice/printPreview/<?php echo $item->nDId;?>" title="<?php echo EDIT;?>">

                        <input type="submit" value="Print">

                        </form>

					</td>
						<td align="left"><?php echo $this->common_model->_offset+$i;?></td>
                        <td align="left">
                            <?php//user access aa // if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                            <a href="<?php echo base_url();?>index.php/duplicate_invoice/editDuplicateInvoice/<?php echo $this->uri->segment(3).'/e/'.$item->nDId;?>" title="<?php echo EDIT;?>"><?php echo $this->common_model->get_field_name('SalesClosing','vInvoiceNumber'," WHERE nSCId = '".$item->vInvoiceNumber."'");?></a>
                            <?php// endif;?>
                        </td>
                        <td align="left"><?php echo date('d-m-Y',strtotime($item->dDate));?></td>
                        <td align="left"><?php 
                        $drAccount = $this->common_model->get_field_name('DuplicateAccountDetails', 'nDrAccount' , " WHERE nDupId= " . $item->nDId . " ORDER BY nDrAccount ");
                        $vTable = $this->common_model->get_field_name('DuplicateAccountDetails', 'vTable' , " WHERE nDupId= " . $item->nDId . " ORDER BY nDrAccount ");

if($vTable=='C'){echo $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$drAccount."'");}else{echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$drAccount."'");}?></td>
                        <td colspan="10"></td>
                        <td align="left"><a class="popup-link-<?php echo $item->nDId;?>"><?php echo VIEW;?></a>
                            <div class="popup-box" id="popup-box-<?php echo $item->nDId;?>">
                                <div class="close">X</div>
                                <div class="top">
                                    <h2>Details</h2>
                                </div>
                                <div class="bottom">
                                    <div class="wa">
                                        <?php echo $this->duplicate_invoice_model->showDetails($item->nDId);?>
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td align="left">
                            <?php //user access 
                            if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                            <a href="<?php echo base_url();?>index.php/duplicate_invoice/delsalesclosing/<?php echo $this->uri->segment(3).'/'.$item->nDId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                            <?php endif;?>
                        </td>
                    </tr>
                    
                    <?php
                        $getDetails = $this->duplicate_invoice_model->getSalesDetails($item->nDId);
                        if(!empty($getDetails)):
                            foreach($getDetails as $key1=>$detailItem):
                                $getSalesAccDetails    = $this->duplicate_invoice_model->returnSalesAccClosingDetails($detailItem->nId);
                                $getSalesAirDetails    = $this->duplicate_invoice_model->returnSalesAirClosingDetails($detailItem->nId);
                                $getSalesDateDetails   = $this->duplicate_invoice_model->returnSalesDateClosingDetails($detailItem->nId);
                                $getSalesPassDetails   = $this->duplicate_invoice_model->returnSalesPassClosingDetails($detailItem->nId);
                                $getSalesNetDetails    = $this->duplicate_invoice_model->returnSalesNetClosingDetails($detailItem->nId);
                                $getSalesSaleDetails   = $this->duplicate_invoice_model->returnSalesSaleClosingDetails($detailItem->nId);
                                $getSalesProfitDetails = $this->duplicate_invoice_model->returnSalesProfitClosingDetails($detailItem->nId);
                                switch($detailItem->vType)
                                {
                                    case "Tkt" :
                                        $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual', " WHERE nTId = '".$detailItem->vTktNumber."'");
                                        break;

                                    case "Xo" :
                                        $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual', " WHERE nXId = '".$detailItem->vTktNumber."'");
                                        break;

                                    case "Htl" :
                                        $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual', " WHERE nHVId = '".$detailItem->vTktNumber."'");
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
                        endif;
                    $i = $i+1;
                endforeach;?>
                <?php if(empty($list_value))
                {?>
                <tr>
                    <td colspan="18" align="center"><?php echo SORRYNORECORD;?></td>
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
