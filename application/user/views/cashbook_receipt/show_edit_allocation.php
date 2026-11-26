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
		for (var i = 0; i < document.frmGroup.elements.length; i++)
		{
			var e = document.frmGroup.elements[i];
			if (e.name == "chkAllBox[]")
			{
				e.checked = false;
			}//end if
		}//end for loop
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
		var txtTotal  = Math.round(txtTotal).toFixed(2);
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
		var txtTotal  = Math.round(txtTotal).toFixed(2);
        $('#txtTotalAlloAmt').val(txtTotal);
        $('#dcAmt<?php echo $this->idVal;?>').val(txtTotal);
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
		var txtTotal  = Math.round(txtTotal).toFixed(2);
        $('#txtTotalBalAmt').val(txtTotal);
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
			var debAmt = $('#txtDebitAmt' + getId[1]).val();
			var balVal = parseFloat(debAmt) - parseFloat(allAmt);
			$('#txtBalanceAmt'+getId[1]).val(balVal);
			
			getTotalAllocatedAmt();
            getTotalBalAmt();
		});
	
		$('.txtBalanceAmt').keyup(function () {
			getTotalBalAmt();
		});
		
		$('#btnFinish').click(function () {
            var aid;
            var checked    = [];
            var dDate      = [];
            var vTktNumber = [];
            var nDebit     = [];
            var nAllocated = [];
            var nBalance   = [];
            var nLocation  = [];
            var idArr      = [];
            var status     = false;
            $("input[name='chkAllBox[]']:checked").each(function ()
            {
				//alert($(this).val());
                aid        = $(this).val().split("-");
                idArr.push(aid[0]);
                dDate.push($('#txtDate'+aid[0]).val());
                vTktNumber.push($('#txtTkt'+aid[0]).val());
                nDebit.push($('#txtDebitAmt'+aid[0]).val());
                nAllocated.push($('#txtAllocatedAmt'+aid[0]).val());
                nBalance.push($('#txtBalanceAmt'+aid[0]).val());
                nLocation.push($('#txtLocation'+aid[0]).val());
                status = true;
            });

            if(status == true)
            {
                $.ajax({
                    type:'POST',
                    data:{aid:idArr,dDate:dDate,vTktNumber:vTktNumber,nDebit:nDebit,nAllocated:nAllocated,nBalance:nBalance,nLocation:nLocation},
                    url:'<?php echo base_url().'index.php/cashbook_receipt/insertAllocation/'.$this->uri->segment(3);?>',
                    dataType:'html',
                    success: function(response) {
                        $('#toPopup1').hide();
                    }
                });
            }//end if
			
			$('#txtRemarks').val(vTktNumber);
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
						$('.txtInvoice').val(obj.invoice);
						$('.txtInvDate').val(obj.invDate);
						$("input[name='chkFinishSelect']").val(obj.salesID);
					}
                });
		});
	});
</script>
<table class="entryForm">
    <?php if(!empty($list_allc)){
    ?>
        <tr>
            <td><?php echo CUSTDATE;?></td>
            <td><?php echo TICKETNO;?></td>
            <td><?php echo DEBITAMOUNT;?></td>
            <td><?php echo ALLOCATEDAMT;?></td>
            <td><?php echo BALANCEAMT;?></td>
            <td><?php echo LOCATIONNAME;?></td>
            <td>&nbsp;</td>
        </tr>
        <?php foreach($list_allc as $key => $aitem){ 
			switch($aitem->vType)
			{
				case "Tkt" :
					$tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$aitem->vTktNumber."'");
				break;
	
				case "Xo" :
					$tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$aitem->vTktNumber."'");
				break;
	
				case "Htl" :
					$tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$aitem->vTktNumber."'");
				break;
			}//end switch?>
            <tr>
                <td><input name="txtDate[]" id="txtDate<?php echo $aitem->nId;?>" class="textfield5 txtDate" type="text" value="<?php echo $chkAllDate[$key];?>"></td>
                <td><input name="txtTkt[]" id="txtTkt<?php echo $aitem->nId;?>" class="textfielddoc txtTkt" type="text" value="<?php echo $chkAllTkt[$key];?>" ></td>
                <td><input name="txtDebitAmt[]" id="txtDebitAmt<?php echo $aitem->nId;?>" class="textfield4 txtDebitAmt" type="text" value="<?php echo $chkAllDebit[$key];?>"></td>
                <td><input name="txtAllocatedAmt[]" id="txtAllocatedAmt<?php echo $aitem->nId;?>" class="textfield4 txtAllocatedAmt" type="text" value="<?php echo $chkAllAllocated[$key];?>"></td>
                <td><input name="txtBalanceAmt[]" id="txtBalanceAmt<?php echo $aitem->nId;?>" class="textfield4 txtBalanceAmt" type="text" value="<?php echo $chkAllBal[$key];?>"></td>
                <td><input name="txtLocation[]" id="txtLocation<?php echo $aitem->nId;?>" class="textfield txtLocation" type="text" value="<?php echo $chkAllLoc[$key];?>"></td>
                <td><input type="checkbox" onclick="return true" name="chkAllBox[]" id="chkAllBox[]<?php echo $aitem->nId;?>" class="chkAllBox" value="<?php echo $allDetailID[$key];?>-<?php echo $aitem->nSCId;?>"></td>
            </tr>
        <?php }//end foreach ?>
        
			<tr>
				<td>&nbsp;</td>
				<td><?php echo TOTALAMOUNT;?></td>
				<td><input name="txtTotalDebitAmt" class="textfield4 currentvalue" id="txtTotalDebitAmt" type="text" value="<?php echo $this->txtTotalDebitAmt;?>" readonly="readonly"></td>
				<td><input name="txtTotalAlloAmt" class="textfield4 currentvalue" id="txtTotalAlloAmt" type="text" value="<?php echo $this->txtTotalAlloAmt;?>" readonly="readonly"></td>
				<td><input name="txtTotalBalAmt" class="textfield4 currentvalue" id="txtTotalBalAmt" type="text" value="<?php echo $this->txtTotalBalAmt;?>" readonly="readonly"></td>
			</tr>

            <tr>
                <td><?php echo INVOICE;?></td>
                <td><label>
                <input name="txtInvoice" id="txtInvoice" type="text" value="" class="textfield txtInvoice">
                </label></td>
        
                <td><?php echo INVOICEDATE;?></td>
                <td><label>
                <input name="txtInvDate" id="txtInvDate" type="text" value="" class="textfield5 txtInvDate">
                </label></td>

                <td><input type="checkbox" onclick="checkAllocation();" name="chkFinishSelect" id="chkFinishSelect" class="chkFinishSelect" value=""></td>                
            </tr>    

            <tr class="formButs">
                <td>&nbsp;</td>
                <td colspan="6"><label>
                    <input type="button" name="btnFinish" id="btnFinish" value="<?php echo $this->finishbtnVal;?>" />
                </label></td>
            </tr>
    <?php }//end if 
	else {?>
        <tr>
        	<td><?php echo SORRYNORECORD;?></td>
        </tr>
    <?php }//end else?>
			
</table>
