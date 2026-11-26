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
<script type="text/javascript" language="javascript1.1">
    $.validator.setDefaults({
        submitHandler: function() {}
    });
	
	$(document).ready(function(){
        $('input[name="del_inv[]"]').bind('click', function(){
			var status = $(this).is(':checked');
			var name= this.value
			$('input[type="checkbox"],input[name="del[]"]').attr('checked', status);
		});
	});
	
	
	
	
/*function checkinvoice()
{

   if(document.getElementById('del_inv').checked==false)
   {
     uncheckAll();
  
    }else
    {

        for(var i = 0;i<document.frmGroup.elements.length; i++)
        {
         var e = document.frmGroup.elements[i];
         if(e.name = "del[]")
            {
             e.checked = true;
            }//endif
        }//endforloop
    }//end else
}//end function


function uncheckAll()
{

   for(var i = 0;i < document.frmGroup.elements.length; i++)
    {
       var e  = document.frmGroup.elemements[i];
       if(e.name == "del[]")

            {
                e.checked = false;
            }//endif
    }//end for
}//end function*/
	
	
	
	
	
	
	
	
	function checkAllocation()
	{
		var chkVal = $("input[name='chkFinishSelect']").val();
		var chkVal2;
		if(document.getElementById('chkFinishSelect').checked==false)
		{
			uncheckAllocation();
		}//end if
		else
		{
			for (var i = 0; i < document.frmGroup.elements.length; i++)
			{
				var e = document.frmGroup.elements[i];
				if (e.name == "chkAllBox[]")
				{
					chkVal2 = e.value.split("-");
					chkVal2 = chkVal2[1];
					if(chkVal2 == chkVal)
					e.checked = true;
				}//end if
			}//end for loop
		}//end else
	}//end function

	function uncheckAllocation()
	{
		var chkVal = $("input[name='chkFinishSelect']").val();
		for (var i = 0; i < document.frmGroup.elements.length; i++)
		{
			var e = document.frmGroup.elements[i];
			if (e.name == "chkAllBox[]")
			{
                chkVal2 = e.value.split("-");
                chkVal2 = chkVal2[1];
                if(chkVal2 == chkVal)
				e.checked = false;
			}//end if
		}//end for loop
	}//end function

    function getTotalSAllocation()
	{
        var txtTotal = 0;
        $('.txtSAllocationAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
		var txtTotal  = (txtTotal).toFixed(3);
        $('#txtTotalSAllocationAmt').val(txtTotal);
    }//end function

    function getTotalDebitAmt()
	{
        var txtTotal = 0;
        $('.txtDebitAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
		var txtTotal  = (txtTotal).toFixed(3);
        $('#txtTotalDebitAmt').val(txtTotal);
    }//end function

    function getTotalAllocatedAmt() 
	{
        var txtTotal = 0;
        $('.txtAllocatedAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
		var txtTotal  = (txtTotal).toFixed(3);
        $('#txtTotalAlloAmt').val(txtTotal);
        $('#dcAmt<?php echo $this->idVal;?>').val(txtTotal);
        var exVal =  $('#txtRate').val(); 
        var fcTotal = parseFloat(txtTotal) * parseFloat(exVal);
         $('#fcAmt<?php echo $this->idVal;?>').val(fcTotal);
    }//end function

    function getTotalBalAmt() 
	{
        var txtTotal = 0;
        $('.txtBalanceAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();
            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
		var txtTotal  = (txtTotal).toFixed(3);
        $('#txtTotalBalAmt').val(txtTotal);
    }//end function

    function reloadAlloc(id){
            var tktType  = $('#txtDoc' + id).attr('title');
            var type     = $('#ddlAllocation').val();
			var getDetId = $('#txtDetId'+id).val();
			var accId    = $('#ddlAccount').val();
			var getAccId = accId.split('-');
			var subacc   = $('#ddlSubAccount').val();
            var getSubAccId = subacc.split('-');
             $.ajax({
                type:'POST',
                data:{tableId:id,detTableId:getDetId,tktType:tktType,allType:type,ddlAccount:getAccId[0],tableVal:getAccId[1],ddlSubAccount:getSubAccId[0]},
                url:'<?php echo base_url().'index.php/ar_allocation/getAllocationDetails/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#txtDoc' + $("#currentId").val()).removeClass('currentvalue');
                    $("#currentId").val(id);
                    $('#txtDoc' + id).addClass('currentvalue');
                    $('#dataTableTd2').removeClass('hidden');
                    $('#showDataTable2').html(response);
                    $('#allocReceipt').val( $('#txtDoc' + id).val());
                    $('#allocReceipt_p').val( $('#txtDoc' + id).val());
                }
            });
 
    }//end function

    $(document).ready(function()
    {
		$('.txtDebitAmt').keyup(function () {
			getTotalDebitAmt();
		});
	
		$('.txtAllocatedAmt').keyup(function () {
			var id     = this.id;
			var getId  = id.split('txtAllocatedAmt');
			var allAmt = $('#txtAllocatedAmt' + getId[1]).val();
			var debAmt = $('#txtSAllocationAmt' + getId[1]).val();
			var balVal = parseFloat(debAmt) - parseFloat(allAmt);
			$('#txtBalanceAmt'+getId[1]).val(balVal);
			
			getTotalAllocatedAmt();
            getTotalBalAmt();
		});
	
        $('.txtAllocatedAmt_p').keyup(function () {
            var id     = this.id;
            var getId  = id.split('txtAllocatedAmt_p');
            var allAmt = $('#txtAllocatedAmt_p' + getId[1]).val();
            var debAmt = $('#txtSAllocationAmt_p' + getId[1]).val();
            var balVal = parseFloat(debAmt) - parseFloat(allAmt);
            $('#txtBalanceAmt_p'+getId[1]).val(balVal);
        });


		$('.txtBalanceAmt').keyup(function () {
			getTotalBalAmt();
		});
		
        getTotalDebitAmt();
        getTotalAllocatedAmt();
        getTotalSAllocation();
        getTotalBalAmt();

        $('.editAllocation').click(function () {
                var nId = this.id;
                nId = nId.split('editAllocation');
                var allAmt =  $('#txtAllocatedAmt'+nId[1]).val();
                var cashAllocId = $('#cashAllocId'+nId[1]).val();

                var nId1 = nId[1].split('-');
                var salesId = nId1[0];
                var salesDetId = nId1[1];
                var type = this.title;

                var selectedReceiptId = $("#currentId").val();
                var receiptAlloc = parseFloat($('#txtAllocatedAmt1' + selectedReceiptId).val());
                var receiptAmt = $('#txtCreditAmt' + selectedReceiptId).val()

                var allBalAmt =  $('#txtBalanceAmt'+nId[1]).val();
                // negativity check
                if( parseFloat(allBalAmt) >= 0 || parseFloat(allAmt)>=0 ){
                    $.ajax({
                        type:'POST',
                        data:{allAmt:allAmt,cashAllocId:cashAllocId,type:type,salesDetId:salesDetId,type:type,receiptAmt:receiptAmt,allBalAmt:allBalAmt},
                        url:'<?php echo base_url().'index.php/ar_allocation/editAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',
                        dataType:'html',
                        success: function(response) {
                            if(response == -1) alert("Cannot edit... value exceded..");
                            else  {
                                $('#txtAllocatedAmt1' +selectedReceiptId).val(response);
                                reloadAlloc(selectedReceiptId);
                            }
                        }
                    });
                }else{
                    alert("cannot edit");
                }
            });
		


        $('.deleteAllocation').click(function () {
                var nId = this.id;
                nId = nId.split('deleteAllocation');
                var allAmt =  $('#txtAllocatedAmt'+nId[1]).val();
                var cashAllocId = $('#cashAllocId'+nId[1]).val();

                var nId1 = nId[1].split('-');
                var salesId = nId1[0];
                var salesDetId = nId1[1];
                var type = this.title;

                var selectedReceiptId = $("#currentId").val();
                var receiptAlloc = parseFloat($('#txtAllocatedAmt1' + selectedReceiptId).val());

                //var newAllocVal = parseFloat(receiptAlloc) - parseFloat(allAmt);
                    $.ajax({
                        type:'POST',
                        data:{allAmt:allAmt,cashAllocId:cashAllocId,type:type,salesDetId:salesDetId,type:type},
                        url:'<?php echo base_url().'index.php/ar_allocation/deleteAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',
                        dataType:'html',
                        success: function(response) {
                            $('#txtAllocatedAmt1' +selectedReceiptId).val( parseFloat(receiptAlloc) - parseFloat(response));
                            reloadAlloc(selectedReceiptId);
                        }
                    });
            });
		

        $('.addAllocation_p').click(function () {

                var nId = this.id;
                nId = nId.split('addAllocation_p');
                var allAmt =  $('#txtAllocatedAmt_p'+nId[1]).val();
                var allBalAmt =  $('#txtBalanceAmt_p'+nId[1]).val();

                var selectedReceiptId = $("#currentId").val();
                var receiptAlloc = parseFloat($('#txtAllocatedAmt1' + selectedReceiptId).val());

                var newAllocVal = parseFloat(receiptAlloc) + parseFloat(allAmt);
                if( parseFloat(newAllocVal) > parseFloat($("#txtCreditAmt" + selectedReceiptId).val()) || parseFloat(allBalAmt) < 0 ||  parseFloat(allAmt)<0 ){
                alert ("Cannot allocate");
                }
                else {
                    var nId1 = nId[1].split('-');
                    var salesId = nId1[0]; 
                    var salesDetId = nId1[1]; 
                    var type = this.title;
                    var dDate = $('#txtDate_p'+nId[1]).val(); 
                    var tktNum_val = $('#txtTkt_p'+nId[1]).val();
                    /*if(type == 'SC' || type == 'RC'){
                        var tktNum_array =tktNum_val.split('-'); 
                        var tktNum = tktNum_array[1];
                    }*/
                    /*else{
                        var tktNum = tktNum_val;
                    }*/
					var tktNum = tktNum_val;
                    var allDebitAmt =  $('#txtSAllocationAmt_p'+nId[1]).val();
    //                alert(type + ' ' + dDate + ' ' +  tktNum + ' ' +  allDebitAmt + ' ' +  allAmt + ' ' +  allBalAmt);
                    $.ajax({
                        type:'POST',
                        data:{salesId:salesId,salesDetId:salesDetId,type:type,dDate:dDate,tktNum:tktNum,allDebitAmt:allDebitAmt,allAmt:allAmt,allBalAmt:allBalAmt},
                        url:'<?php echo base_url().'index.php/ar_allocation/insertAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',
                        dataType:'html',
                        success: function(response) {
                            if(response == -1) alert("Duplicate Allocation");
                            else{    $('#txtAllocatedAmt1' +selectedReceiptId).val(newAllocVal);
                                reloadAlloc(selectedReceiptId);
                            }
                        }
                    });
                }
            });
		
		$('.txtTkt').click(function(){
			var tktId   = this.id;
			var getId   = tktId.split('txtTkt');
			$.ajax({
                    type:'POST',
                    data:{salesDetailId:getId[1]},
                    url:'<?php echo base_url().'index.php/cashbook_receipt/getInvoiceDetails/'.$this->uri->segment(3);?>',
                    dataType:'html',
					success: function(response) {
						var obj = jQuery.parseJSON(response);
						//$('.txtInvoice').val(obj.invoice);
						$('.txtInvDate').val(obj.invDate);
						$("input[name='chkFinishSelect']").val(obj.salesID);
                        var chk = 1;
                        var chkVal = $("input[name='chkFinishSelect']").val();
                        for (var i = 0; i < document.frmGroup.elements.length; i++)
                        {
                            var e = document.frmGroup.elements[i];
                            if (e.name == "chkAllBox[]")
                            {
                                chkVal2 = e.value.split("-");
                                chkVal2 = chkVal2[1];
                                if(chkVal2 == chkVal)
                                    if(e.checked == false)
                                        chk = 0;
                            }//end if
                        }//end for loop
                        if(chk == 0)
                            document.getElementById('chkFinishSelect').checked=false;
                        else if (chk == 1)
                            document.getElementById('chkFinishSelect').checked=true;


					}
                });
		});

        getTotalSAllocation();

	});
</script>

<?php if(!empty($list_allc2)){?>
<table id="dataTable2" title="dataTable2" style="border:1px solid #C9C9C9;padding-bottom: 10px;">
        <tr>
            <th colspan="8" align="left"> <?php echo ALLOCATED; ?> <input type="label"  value="" id="allocReceipt"></th>
        </tr>
        <tr>
            <td><?php echo CUSTDATE;?></td>
            <td><?php echo TICKETNO;?></td>
			
            <td><?php echo SALESAMOUNT;?></td>
            <td><?php echo DEBITAMOUNT;?></td>
            <td><?php echo ALLOCATEDAMT;?></td>
            <td><?php echo BALANCEAMT;?></td>
            <td>&nbsp;</td>
        </tr>

        <?php
            foreach($list_allc2 as $key => $item){
                if($item->salesCloseType == 'SC' || $item->salesCloseType == 'RC'){
                    switch($item->tktType)
                    {
                        case "Tkt" :
                            $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$item->vTktNumber."'");
                            $tktNumber =  $tktNumber ;
                        break;

                        case "Xo" :
                            $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$item->vTktNumber."'");
                            $tktNumber = $tktNumber ;
                        break;

                        case "Htl" :
                            $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$item->vTktNumber."'");
                        break;
                    }//end switch
                }// end if
                else {
                    $tktNumber = $item->vTktNumber;
                }
                // cash allocation id - main table Id - Detail table id
                $nId = $item->idVal . "-" . $item->detIdVal . "-" . $item->type;
                ?>

                <tr>
<input name="cashAllocId" id="cashAllocId<?php echo $nId;?>" type="hidden" value="<?php echo $item->nCAId; ?>">
                
				<td><input name="txtDate[]" id="txtDate<?php echo $nId;?>" class="textfield5 txtDate" type="text" value="<?php echo $item->dDate;?>"></td>
                
				
				
				<td><input name="txtTkt[]" id="txtTkt<?php echo $nId;?>" class="textfielddoc txtTkt" type="text" value="<?php echo $tktNumber;?>"  title="<?php echo $item->type;?>" ></td>
                    <td><input name="txtDebitAmt[]" id="txtDebitAmt<?php echo $nId;?>" class="textfield4 txtDebitAmt" type="text" value="<?php echo $item->vCollected;?>" readonly="readonly" ></td>
                    <td><input name="txtSAllocationAmt[]" id="txtSAllocationAmt<?php echo $nId;?>" class="textfield4 txtSAllocationAmt " type="text" value="<?php echo $item->vDebitAmt;?>" readonly="readonly" ></td>
                    <td><input name="txtAllocatedAmt[]" id="txtAllocatedAmt<?php echo $nId;?>" class="textfield4 txtAllocatedAmt" type="text" value="<?php echo $item->vAllAmt;?>"></td>
                    <td><input name="txtBalanceAmt[]" id="txtBalanceAmt<?php echo $nId;?>" class="textfield4 txtBalanceAmt" type="text" value="<?php echo $item->vBalAmt;?>" readonly="readonly" ></td>
                    <td class="hidden"><input name="txtLocation[]" id="txtLocation<?php echo $nId;?>" class="textfield txtLocation" type="text" value="<?php echo $item->vLocation;?>"></td>

<!--                   		<?php //if($item->vBalAmt == '0'){?>
                              <td><input type="checkbox" onclick="return false" name="chkAllBox[]" id="chkAllBox[]<?php echo $item->nSalesCloseDetId;?>" class="chkAllBox" value="<?php echo $item->nSalesCloseDetId;?>-<?php echo $item->nSalesCloseId;?>" <?php echo $showChecked;?>></td>
                        <?php// }//end if
//						else {?>
					<td><input type="checkbox" onclick="return true" name="chkAllBox[]" id="chkAllBox[]<?php echo $item->nSalesCloseDetId;?>" class="chkAllBox" value="<?php echo $item->nSalesCloseDetId;?>-<?php echo $item->nSalesCloseId;?>" <?php echo $showChecked;?>></td>  -->
<!--    <td><a href="<?php echo base_url();?>index.php/ar_allocation/deleteAllocation/<?php echo $this->uri->segment(3).'/'.$item->nCAId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a> </td> -->
                    <td> 
                    <?php if($this->common_model->get_account_period_date($item->dDate) ==1 && ($action_access->vAccess == 'F' || $action_access->vAccess == 'U')) :?> 
                    <img src="<?php echo base_url();?>static/images/pencil.png" alt="Edit" border="0" id="editAllocation<?php echo $nId;?>" name='editAllocation[]' class="editAllocation"  title="<?php echo $item->type;?>">
                    <?php endif; ?>
                    </td>

                    <td> 
                    <?php if($this->common_model->get_account_period_date($item->dDate) ==1 && ($action_access->vAccess == 'F' )) :?>
                    <img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" id="deleteAllocation<?php echo $nId;?>" name='deleteAllocation[]' class="deleteAllocation"  title="<?php echo $item->type;?>">
                    <?php endif; ?>
                    </td>

                        <?php // }//end else?>                      
                    </tr>
                    <?php }//end foreach?>
                <tr>
                    <td>&nbsp;</td>
                    <td><?php echo TOTALAMOUNT;?></td>
                    <td><input name="txtTotalDebitAmt" class="textfield4 currentvalue" id="txtTotalDebitAmt" type="text" value="<?php echo $this->txtTotalDebitAmt;?>" readonly="readonly"></td>
                    <td><input name="txtTotalSAllocationAmt" class="textfield4 currentvalue" id="txtTotalSAllocationAmt" type="text" value="<?php echo $this->txtTotalSAllocationAmt;?>" readonly="readonly"></td>
                    <td><input name="txtTotalAlloAmt" class="textfield4 currentvalue" id="txtTotalAlloAmt" type="text" value="<?php echo $this->txtTotalAlloAmt;?>" readonly="readonly"></td>
                    <td><input name="txtTotalBalAmt" class="textfield4 currentvalue" id="txtTotalBalAmt" type="text" value="<?php echo $this->txtTotalBalAmt;?>" readonly="readonly"></td>
                    </tr>

                <tr>
                    <!--<td><?php echo INVOICE;?></td>
                    <td colspan='2'><label>
                    <input name="txtAllInvoice" id="txtAllInvoice" type="text" value="<?php echo $this->txtAllInvoice;?>" class="textfield txtInvoice">
                    </label></td>-->

                    <td><?php echo INVOICEDATE;?></td>
                    <td><label>
                    <input name="txtAllInvDate" id="txtAllInvDate" type="text" value="<?php echo $this->txtAllInvDate;?>" class="textfield5 txtInvDate">
                    </label></td>

<!--                    <td><input type="checkbox" onclick="checkAllocation();" name="chkFinishSelect" id="chkFinishSelect" class="chkFinishSelect" value=""></td> -->
                </tr>

<!--                <tr class="formButs">
                    <td>&nbsp;</td>
                        <input type="button" name="btnFinish" id="btnFinish" value="<?php //echo $this->finishbtnVal;?>" />   -->
<!--            <td colspan="6"><a href="#" class="topopup" id="1"><input type="button" name="btnAllocation" id="btnAllocation" value="<?php //echo ALLOCATION;?>" class="btnAllocation" /></a></td>
                </tr> -->

<tr><td> <br/><br/></td> </tr>
</table>
        <?php
    }//end if ?>

    <!-- Start of Sticky Note -->
    <div id="toPopup1" class="toPopup">
        <div class="close"></div>
        <div id="popup_content"> <!--your content start-->
        </div> <!--your content end-->
    </div>
    <!-- End of Sticky Note -->
 





<!-- ************************* PENDING ALLOCATION TABLE ********************* -->
<table id="dataTable2" title="dataTable2" style="border:1px solid #C9C9C9;padding-bottom: 10px;">
    <?php if(!empty($list_pending2)){?>
        <tr>
            <th colspan="10" align="left"> <?php echo PENDING; ?> <input type="label" value="" id="allocReceipt_p"> </th>
        </tr>
        <tr>
            <td><?php echo CUSTDATE;?></td>
			<td>&nbsp;</td>
			<td><?php echo INVOICENO;?></td>
			<td>&nbsp;</td>
            <td><?php echo TICKETNO;?></td>
            <td><?php echo SALESAMOUNT;?></td>
            <td><?php echo DEBITAMOUNT;?></td>
            <td><?php echo ALLOCATEDAMT;?></td>
            <td><?php echo BALANCEAMT;?></td>
            <td>&nbsp;</td>
        </tr>

        <?php
            foreach($list_pending2 as $key => $item){
                if($item->type == 'SC'){
                    switch($item->vType)
                    {
                        case "Tkt" :
                            $tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$item->vTktNumber."'");
                            $tktNumber_a =  $tktNumber ;
                        break;

                        case "Xo" :
                            $tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$item->vTktNumber."'");
                            $tktNumber_a =  $tktNumber ;
                        break;

                        case "Htl" :
                            $tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$item->vTktNumber."'");
                            $tktNumber_a = $tktNumber;
                        break;
                   }//end switch
                    $vCollected = $item->vCollected;
                    $allocAmt =  $this->common_model->get_field_name('CashAllocation','vAllAmt'," WHERE nReceiptDId = 0 AND nSalesCloseDetId =". $item->detIdVal. " AND nSalesCloseId = ". $item->idVal. " AND vAllType = '".$item->vType."' AND vAllTktNumber ='" . $tktNumber ."' "); //add account chk
                    $allocAmt = $allocAmt != '' ? $allocAmt : 0;
                    $sAlloc =  $item->allocAmt + $allocAmt;
                    $allocAmt = $allocAmt != 0 ?  $allocAmt : '';
                    $balAllocAmt = $item->allocAmt;
                }//end if 
                else {
                    if($item->type == 'RC'){
                        $tktNumber_a =  $item->document;
                    }
                    else {
                        $tktNumber_a = $item->document;
                    }
                    $vCollected = $item->creditAmt;
/*                    $allocAmt =  $this->common_model->get_field_name('CashAllocation','vAllAmt'," WHERE nReceiptDId = 0 AND nSalesCloseDetId =". $item->nId . " AND nSalesCloseId = ". $item->nSCId . " AND vAllType = '".$item->vType."' AND vAllTktNumber ='" . $tktNumber ."' "); //add aaccount chk
                    $allocAmt = $allocAmt != '' ? $allocAmt : 0;
                    $sAlloc =  $item->allocAmt + $allocAmt;
                    $allocAmt = $allocAmt != 0 ?  $allocAmt : '';
                    $balAllocAmt = $item->allocAmt;
*/
                    $sAlloc = $item->creditAmt - $item->allocated; 
                    $allocAmt = '';
                    $balAllocAmt = $sAlloc;
                }
                // main table Id - Detail table id
                $nId = $item->idVal . "-" . $item->detIdVal . "-" . $item->type;
                ?>

            <tr>
			   
                
				<td><input name="txtDate_p[]" id="txtDate_p<?php echo $nId;?>" class="textfield5 txtDate_p" type="text" value="<?php echo $item->dDate;?>" readonly="readonly"></td>
    <td align="left"><input name="del_inv[]" type="checkbox" id="del_inv[]"  value="<?php echo $item->nSCId;?>" class="chcktbl"/></td>         
				<td><input name="txtTkt_pinv[]" id="txtTkt_pinv<?php echo $nId;?>" class="textfielddoc txtTkt_p" type="text" title="<?php echo $item->type;?>" value="<?php echo $item->vInvoiceNumber;?>" readonly="readonly"></td>
	<td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>			
				<td><input name="txtTkt_p[]" id="txtTkt_p<?php echo $nId;?>" class="textfielddoc txtTkt_p" type="text" title="<?php echo $item->type;?>" value="<?php echo $tktNumber_a;?>" readonly="readonly"></td>

                    <td><input name="txtDebitAmt_p[]" id="txtDebitAmt_p<?php echo $nId;?>" class="textfield4 txtDebitAmt_p" type="text" value="<?php echo $vCollected;?>"  readonly="readonly" ></td>
                    <td><input name="txtSAllocationAmt_p[]" id="txtSAllocationAmt_p<?php echo $nId;?>" class="textfield4 txtSAllocationAmt_p " type="text" value="<?php echo $sAlloc;?>" readonly="readonly" ></td>
                    <td><input name="txtAllocatedAmt_p[]" id="txtAllocatedAmt_p<?php echo $nId;?>" class="textfield4 txtAllocatedAmt_p" type="text" value="<?php echo $allocAmt;?>"></td>
                    <td><input name="txtBalanceAmt_p[]" id="txtBalanceAmt_p<?php echo $nId;?>" class="textfield4 txtBalanceAmt_p" type="text" value="<?php echo $balAllocAmt;?>" readonly="readonly" ></td>

                    <td>
                    <?php if($this->common_model->get_account_period_date($item->dDate) ==1 && ($action_access->vAccess == 'F' || $action_access->vAccess == 'E')) :?>
 <img src="<?php echo base_url();?>static/images/add.jpg" alt="Add" border="0" id="addAllocation_p<?php echo $nId;?>" name='addAllocation_p[]' class="addAllocation_p"  title="<?php echo $item->type;?>"></td>
                    <?php endif; ?>
 
    <?php  }//end foreach?>
    <?php  }//end if ?>

</table>


