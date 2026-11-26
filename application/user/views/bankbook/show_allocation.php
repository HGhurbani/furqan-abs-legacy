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

		var txtTotal  = Math.round(txtTotal).toFixed(2);

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

        var exVal =  $('#txtRate').val(); 

        var fcTotal = parseFloat(txtTotal) * parseFloat(exVal);

         $('#fcAmt<?php echo $this->idVal;?>').val(fcTotal);

    }//end function



    function getTotalBalAmt() 

	{
		//$('#txtTotalBalAmt').empty();
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

			var debAmt = $('#txtSAllocationAmt' + getId[1]).val();

			var balVal = parseFloat(debAmt) - parseFloat(allAmt);

			$('#txtBalanceAmt'+getId[1]).val(balVal);

			

			getTotalAllocatedAmt();

            getTotalBalAmt();

		});

	

		$('.txtBalanceAmt').keyup(function () {

			getTotalBalAmt();

		});

		

        getTotalDebitAmt();

        getTotalAllocatedAmt();

        getTotalSAllocation();

        getTotalBalAmt();



		$('#btnFinish').click(function () {

            var aid;

			var salesid;

            var checked    = [];

            var dDate      = [];

            var vTktNumber = [];

            var nDebit     = [];

            var nAllocated = [];

            var nBalance   = [];

            var nLocation  = [];

            var idArr      = [];

			var salesidArr = [];

            var status     = false;

            $("input[name='chkAllBox[]']:checked").each(function ()

            {

				//alert($(this).val());

                aid        = $(this).val().split("-");

                idArr.push(aid[0]);

				salesidArr.push(aid[1]);

                dDate.push($('#txtDate'+aid[0]).val());

                vTktNumber.push($('#txtTkt'+aid[0]).val());

                //nDebit.push($('#txtDebitAmt'+aid[0]).val());

                nDebit.push($('#txtSAllocationAmt'+aid[0]).val());

                nAllocated.push($('#txtAllocatedAmt'+aid[0]).val());

                nBalance.push($('#txtBalanceAmt'+aid[0]).val());

                nLocation.push($('#txtLocation'+aid[0]).val());

                status = true;

            });



            if(status == true)

            {

                $.ajax({

                    type:'POST',

                    data:{aid:idArr,salesid:salesidArr,dDate:dDate,vTktNumber:vTktNumber,nDebit:nDebit,nAllocated:nAllocated,nBalance:nBalance,nLocation:nLocation},

                    url:'<?php echo base_url().'index.php/bankbook_entry/insertAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5);?>',

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

                    url:'<?php echo base_url().'index.php/bankbook_entry/getInvoiceDetails/'.$this->uri->segment(3);?>',

                    dataType:'html',

					success: function(response) {

						var obj = jQuery.parseJSON(response);

						$('.txtInvoice').val(obj.invoice);

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
<script>

	
$( "input[type=checkbox]" ).click(function() {
	var n = $(this).attr("data-id");
	 var e= document.getElementById('chkAllBox[]'+n).checked ;
	
	 
	// alert(e);
	if(e==true){
	
		
  var m=$('#txtSAllocationAmt' + n).val();
  //( "input:checked" ).length;
  
  $('#txtAllocatedAmt' + n).val(m);	
  var k=0;
  $('#txtBalanceAmt'+n).val(k); 
  getTotalAllocatedAmt();

            getTotalBalAmt();
  
  }
	else{
		
		var b=0;		
		var m=$('#txtSAllocationAmt' + n).val();
		$('#txtBalanceAmt'+n).val(m);
		$('#txtAllocatedAmt'+n).val(b);
 getTotalAllocatedAmt();

            getTotalBalAmt();
  }
  //alert(m);
});
</script>

<table class="entryForm">

    <?php if(!empty($list_allc)){
 
    ?>

        <tr>

            <td><?php echo CUSTDATE;?></td>

			<td><?php echo INVOICENO;?></td>

            <td><?php echo TICKETNO;?></td>

            <td><?php echo SALESAMOUNT;?></td> 

            <td><?php echo DEBITAMOUNT;?></td>

            <td><?php echo ALLOCATEDAMT;?></td>

            <td><?php echo BALANCEAMT;?></td>

<!--            <td><?php echo LOCATIONNAME;?></td> -->

            <td>&nbsp;</td>

        </tr>

        <?php foreach($list_allc as $key => $item){

			switch($item->vType)

			{

				case "Tkt" :

					$tktNumber = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId = '".$item->vTktNumber."'");

				break;

	

				case "Xo" :

					$tktNumber = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId = '".$item->vTktNumber."'");

				break;

	

				case "Htl" :

					$tktNumber = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId = '".$item->vTktNumber."'");

				break;

			}//end switch?>

            <tr>

                <td><input name="txtDate[]" id="txtDate<?php echo $item->nId;?>" class="textfield5 txtDate" type="text" value="<?php echo $item->dDate;?>"></td>

				<td><input name="txtTkt[]" id="txtTkt<?php echo $item->nId;?>" class="textfielddoc txtTkt" type="text" value="<?php echo $item->vInvoiceNumber;?>" ></td>

                <td><input name="txtTkt[]" id="txtTkt<?php echo $item->nId;?>" class="textfielddoc txtTkt" type="text" value="<?php echo $tktNumber;?>" ></td>

                <?php if($this->uri->segment(4) == 'a')

				{?>

<?php $allocAmt =  $this->common_model->get_field_name('CashAllocation','vAllAmt'," WHERE nReceiptDId = 0 AND nSalesCloseDetId =". $item->nId . " AND nSalesCloseId = ". $item->nSCId . " AND vAllType = 'BR' AND vAllTktNumber ='" . $tktNumber ."' ");

$allocAmt = $allocAmt != '' ? $allocAmt : 0;

$sAlloc =  $item->allocAmt + $allocAmt;

$allocAmt = $allocAmt != 0 ?  $allocAmt : '';

?>

 

                    <td><input name="txtDebitAmt[]" id="txtDebitAmt<?php echo $item->nId;?>" class="textfield4 txtDebitAmt" type="text" value="<?php echo $item->vCollected;?>"  readonly="readonly" ></td>

                    <td><input name="txtSAllocationAmt[]" id="txtSAllocationAmt<?php echo $item->nId;?>" class="textfield4 txtSAllocationAmt " type="text" value="<?php echo $sAlloc;?>" readonly="readonly" ></td>

                    <td><input name="txtAllocatedAmt[]" data-id="<?php echo $item->nId;?>" id="txtAllocatedAmt<?php echo $item->nId;?>" class="textfield4 txtAllocatedAmt" type="text" value="<?php echo $allocAmt;?>"></td>

                    <td><input name="txtBalanceAmt[]" id="txtBalanceAmt<?php echo $item->nId;?>" class="textfield4 txtBalanceAmt" type="text" value="<?php echo $item->allocAmt;?>" readonly="readonly" ></td>

                    <td class="hidden"><input name="txtLocation[]" id="txtLocation<?php echo $item->nId;?>" class="textfield txtLocation" type="text" value="<?php echo $this->common_model->get_field_name('Location','vCode'," WHERE nId = '".$item->vLocation."' ");?>"></td>

                <?php }//end if

				else {?>

                    <td><input name="txtDebitAmt[]" id="txtDebitAmt<?php echo $item->nId;?>" class="textfield4 txtDebitAmt" type="text" value="<?php echo $item->vCollected;?>" readonly="readonly" ></td>

                    <td><input name="txtSAllocationAmt[]" id="txtSAllocationAmt<?php echo $item->nId;?>" class="textfield4 txtSAllocationAmt " type="text" value="<?php echo $item->vDebitAmt;?>" readonly="readonly" ></td>

                    <td><input name="txtAllocatedAmt[]" data-id="<?php echo $item->nId;?>" id="txtAllocatedAmt<?php echo $item->nId;?>" class="textfield4 txtAllocatedAmt" type="text" value="<?php echo $item->vAllAmt;?>"></td>

                    <td><input name="txtBalanceAmt[]" id="txtBalanceAmt<?php echo $item->nId;?>" class="textfield4 txtBalanceAmt" type="text" value="<?php echo $item->vBalAmt;?>" readonly="readonly" ></td>

                    <td class="hidden"><input name="txtLocation[]" id="txtLocation<?php echo $item->nId;?>" class="textfield txtLocation" type="text" value="<?php echo $item->vLocation;?>"></td>

				<?php }//end else?>

                <?php 

					$showChecked = '';

					if($this->uri->segment(4) == 'e'):

						 $showChecked = $this->bankbook_model->getAllocChecked($item->nId,$item->nSCId,$this->uri->segment(5));

				    endif;

				?>

                <td><input type="checkbox" onclick="return true" name="chkAllBox[]" id="chkAllBox[]<?php echo $item->nId;?>" class="chkAllBox" data-id="<?php echo $item->nId;?>" value="<?php echo $item->nId;?>-<?php echo $item->nSCId;?>" <?php echo $showChecked;?>></td>

            </tr>

        <?php }//end foreach ?>

			<tr>

				<td>&nbsp;</td>
<td>&nbsp;</td>
				<td><?php echo TOTALAMOUNT;?></td>

				<td><input name="txtTotalDebitAmt" class="textfield4 currentvalue" id="txtTotalDebitAmt" type="text" value="<?php echo $this->txtTotalDebitAmt;?>" readonly="readonly"></td>

				<td><input name="txtTotalSAllocationAmt" class="textfield4 currentvalue" id="txtTotalSAllocationAmt" type="text" value="<?php echo $this->txtTotalSAllocationAmt;?>" readonly="readonly"></td>

				<td><input name="txtTotalAlloAmt" class="textfield4 currentvalue" id="txtTotalAlloAmt" type="text" value="<?php echo $this->txtTotalAlloAmt;?>" readonly="readonly"></td>

				<td><input name="txtTotalBalAmt" class="textfield4 currentvalue" id="txtTotalBalAmt" type="text" value="<?php echo $this->txtTotalBalAmt;?>" readonly="readonly"></td>

			</tr>



            <tr>

                <!--<td><?php echo INVOICE;?></td>

                <td><label>

                <input name="txtAllInvoice" id="txtAllInvoice" type="text" value="<?php echo $this->txtAllInvoice;?>" class="textfield txtInvoice">

                </label></td>-->

        

               <!-- <td><?php echo INVOICEDATE;?></td>

                <td><label>

                <input name="txtAllInvDate" id="txtAllInvDate" type="text" value="<?php echo $this->txtAllInvDate;?>" class="textfield5 txtInvDate">

                </label></td>
-->


              <!--  <td><input type="checkbox" onclick="checkAllocation();" name="chkFinishSelect" id="chkFinishSelect" class="chkFinishSelect" value=""></td>           -->     

            </tr>



			<?php if($this->uri->segment(4) == 'a'):?>

            <tr class="formButs">

                <td>&nbsp;</td>

                <td colspan="6"><label>

                    <input type="button" name="btnFinish" id="btnFinish" value="<?php echo $this->finishbtnVal;?>" />

                </label></td>

            </tr>

            <?php endif;?>

    <?php }//end if 

	else {?>

        <tr>

        	<td><?php echo SORRYNORECORD;?></td>

        </tr>

    <?php }//end else?>

			

</table>

