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
// +----------------------------------------------------------------------+                                                     ini_set('memory_limit', '-1'); 
?>
<!-- <link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css"> -->

<script type="text/javascript" src="<?php echo base_url();?>static/js/date.f-0.5.0-min.js"></script>
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});

    $(function() {
    function format(acVal) {
        return acVal.name;
    }

    $(".chkSuggestion").keypress(function(e)
    {
        var nId  = this.id;
        var nVal = $('#'+this.id).val();
        $('#'+this.id).autocomplete('<?php echo base_url();?>index.php/ticket_booking/suggestionlist', {
            multiple: false,
            dataType: "json",
            parse: function(data) {
                return $.map(data, function(row) {
                    return {
                        data: row,
                        value: row.name,
                        result: row.name
                    }
                });
            },
            formatItem: function(item) {
                return format(item);
            }
        })
    });

	$('#txtTimeDep1').keyup(function(e) {
		var tVal = 'txtTimeDep1';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeDep2').keyup(function(e) {
		var tVal = 'txtTimeDep2';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeDep3').keyup(function(e) {
		var tVal = 'txtTimeDep3';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeDep4').keyup(function(e) {
		var tVal = 'txtTimeDep4';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeArr1').keyup(function(e) {
		var tVal = 'txtTimeArr1';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeArr2').keyup(function(e) {
		var tVal = 'txtTimeArr2';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeArr3').keyup(function(e) {
		var tVal = 'txtTimeArr3';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	$('#txtTimeArr4').keyup(function(e) {
		var tVal = 'txtTimeArr4';
		$('#'+tVal).val(getTimeFormat(tVal));
    });

	function getTimeFormat(tVal)
	{
		if($('#'+tVal).val().length == 2)
		{
			var newVal = $('#'+tVal).val()+':';
			return newVal;
		}//end if
	}//end function

    $(".chkAirlineSuggestion").keypress(function(e)
    {
        var nId  = this.id;
        var nVal = $('#'+this.id).val();
        $('#'+this.id).autocomplete('<?php echo base_url();?>index.php/ticket_booking/airlinesuggestionlist', {
            multiple: false,
            dataType: "json",
            parse: function(data) {
                return $.map(data, function(row) {
                    return {
                        data: row,
                        value: row.name,
                        result: row.name
                    }
                });
            },
            formatItem: function(item) {
                return format(item);
            }
        })
    });

});


function accountValues() {
/*    var nVal = ($('#txtSalesAmt1').val() != '' ? $('#txtSalesAmt1').val() : 0);
    var nVal2 = ($('#txtDiscount').val() != '' ? $('#txtDiscount').val() : 0);
    var nVal3 = ($('#txtNetVendor').val() != '' ? $('#txtNetVendor').val() : 0);
    var DisAmt = parseFloat(nVal2 / 100) * parseFloat(nVal);
    var CollectedAmt = parseFloat(nVal) - parseFloat(DisAmt);
    var ServiceAmt = parseFloat(CollectedAmt) - parseFloat(nVal3);
    var ServicePer = (parseFloat(ServiceAmt) / parseFloat(nVal3)) * 100;

    $('#txtDiscountAmt').val(DisAmt);
    $('#txtCollected').val(CollectedAmt);
    $('#txtServiceAmt').val(ServiceAmt);
    $('#txtServicePerc').val(ServicePer);
*/

    var nVal = ($('#txtSalesAmt1').val() != '' ? $('#txtSalesAmt1').val() : 0);
    var nVal2 = ($('#txtNetVendor').val() != '' ? $('#txtNetVendor').val() : 0);
    var SerAmt = parseFloat(nVal) - parseFloat(nVal2);
    $('#txtServiceAmt').val(SerAmt);
    var SerPercent = parseFloat((SerAmt/nVal2)*100);
    $('#txtServicePerc').val(SerPercent);
}

function changeAllAccount(){
     var loc  = $('#ddlLocation').val();
     var div = $('#ddlDivision').val();

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/ticket_booking/getIssuedBy/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var issued = $('#ddlIssued').val();
                 $('#Issued').html(response);
                 $('#ddlIssued').val(issued);
         }
     });

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/ticket_booking/getBookedBy/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var issued = $('#ddlBooked').val();
                 $('#Booked').html(response);
                 $('#ddlBooked').val(issued);
         }
     });
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/ticket_booking/getVendorAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
			 console.log(loc);
                 var acc = $('#ddlAccname').val();
                // $('#AccName').html(response);
				if(loc==4){
					 $('#ddlAccname').val(213);
				}
				if(loc==5){
					 $('#ddlAccname').val(212);
				}
				if(loc==1){
					 $('#ddlAccname').val(1);
				}
                
         }
     });
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/ticket_booking/getCustAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var Custacc = $('#ddlCustomerAc').val();
                 $('#CustAc').html(response);
                 $('#ddlCustomerAc').val(Custacc);
         }
     });

}

$(document).ready(function()
{
    $('#btnPrintPopup').click(function(e) {
            window.open('<?php echo base_url().'index.php/ticket_booking/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
			window.open('<?php echo base_url().'index.php/ticket_booking/printAgentPreview/'.$this->uri->segment(5);?>','PrintAgentWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");

    });

    $('#btnPrint').click(function(e) {
        var html = '<table width="3000px"><tr><td></td></tr><tr><td>';
        html += $('.reportForm').html();
        html += '</td></tr></table>';
		
        window.print();
        return false;
    });
$('#bac').click(function(e) {

         
			window.location.replace('<?php echo base_url().'index.php/'.$this->session->userdata('backURL2');?>');

		return false;


    });
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/ticket_booking/showlist/'.$this->uri->segment(3);?>');
		return false;
	});

	jQuery.validator.addMethod(
	  "selectNone",
		  function(value, element) {
			if (element.value == "0")
			{
			  return false;
			}
			else return true;
		  },
		  "<?php echo PLEASESELECTANOPTION;?>"
	);

	$("#txtDate").datepick({ dateFormat: 'dd-mm-yyyy'});

    $("#txtTravelDate1").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDate1").val());
        var sDate = myDate.f("EE");
        $('#txtDay1').val(sDate);
    }});

    $("#txtTravelDate2").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDate2").val());
        var sDate = myDate.f("EE");
        $('#txtDay2').val(sDate);
    }});

    $("#txtTravelDate3").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDate3").val());
        var sDate = myDate.f("EE");
        $('#txtDay3').val(sDate);
    }});
    $("#txtTravelDate4").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDate4").val());
        var sDate = myDate.f("EE");
        $('#txtDay4').val(sDate);
    }});

    $("#txtTravelDateTkt1").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDateTkt1").val());
        var sDate = myDate.f("EE");
        $('#txtDayTkt1').val(sDate);
    }});

    $("#txtTravelDateTkt2").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDateTkt2").val());
        var sDate = myDate.f("EE");
        $('#txtDayTkt2').val(sDate);
    }});

    $("#txtTravelDateTkt3").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDateTkt3").val());
        var sDate = myDate.f("EE");
        $('#txtDayTkt3').val(sDate);
    }});
    $("#txtTravelDateTkt4").datepick({ dateFormat:'dd-mm-yyyy',onSelect:function(){
        var myDate = new Date($("#txtTravelDateTkt4").val());
        var sDate = myDate.f("EE");
        $('#txtDayTkt4').val(sDate);
    }});

	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			 ddlDivision: {
					selectNone : true
					},
			 ddlLocation: {
					selectNone : true
					},
			 ddlAirline: {
				 	selectNone : true
			 		},
			<?php if($this->uri->segment(4) == 'a'):?>		
			 ddlTktname: {
				 	selectNone : true
			 		},
			<?php endif;?>
			 txtTicketName: {
				 	required : true,
					minlength: 10
					 },
			 ddlGDS: {
					selectNone : true
					},
			 txtGdsPNR: {
				 	maxlength: 6
			 		},
			 txtAirPNR: {
				 	maxlength: 6
			 		},
			 ddlBooked: {
					selectNone : true
					},
			 ddlIssued: {
					selectNone : true
					},
			 txtPassenger: {
					required : true
					}
		},
		messages: {
			ddlDivision: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			ddlLocation: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			ddlAirline: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			<?php if($this->uri->segment(4) == 'a'):?>		
			ddlTktname: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			<?php endif;?>
			txtTicketName: {
					required : "<?php echo TICKETNAME;?><?php echo CANTBEBLANK;?>"
					},
			ddlGDS: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			ddlBooked: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			ddlIssued: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
			txtPassenger: {
					required : "<?php echo PASSENGER;?><?php echo CANTBEBLANK;?>"
					}
		},
	 success: function(label2) {
            // set   as text for IE
            label2.html("").addClass("checked");
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             }

	});

	<?php if($this->uri->segment(4) == 'e')
	{?>
/*		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCurrency:'<?php echo $this->ddlCurrency;?>'},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateCurrRate/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					 var obj = jQuery.parseJSON(response);
					$('#txtRate').val(obj.rate);
					//$('#txtDecimal').val(obj.decimal);
				}
		  });*/
	<?php }//end if
	else {?>
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCurrency:'<?php echo $this->ddlCurrency;?>'},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateCurrRate/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					 var obj = jQuery.parseJSON(response);
					$('#txtRate').val(obj.rate);
					//$('#txtDecimal').val(obj.decimal);
				}
		  });
	<?php }//end else ?>
	
    $('#ddlCurrency').change(function()
	{
		var nVal  = $('#ddlCurrency').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCurrency:nVal},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateCurrRate/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#txtRate').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					 var obj = jQuery.parseJSON(response);
					$('#txtRate').val(obj.rate);
					//$('#txtDecimal').val(obj.decimal);
				}
		  });
	});

    function CommTax() {
        var nVal = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
        var nVal2 = ($('#txtCommTax').val() != '' ? $('#txtCommTax').val() : 0);
        var Amt = parseFloat(nVal2 / 100) * parseFloat(nVal);
        $('#txtCommAmt').val(Amt);
    }

    $('#txtCommTax').keyup(function(){
        CommTax();
    });

    $('#txtGSATax').keyup(function(){
        var nVal  = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
        var nVal2 = ($('#txtGSATax').val() != '' ? $('#txtGSATax').val() : 0);
        var Amt   = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtGSAAmt').val(Amt);
    });

    $('#txtORCTax').keyup(function(){
        var nVal  = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
        var nVal2 = ($('#txtORCTax').val() != '' ? $('#txtORCTax').val() : 0);
        var Amt   = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtORCAmt').val(Amt);
    });

	$('#txtFcTax1').keyup(function()
	{
		var nVal1   = ($('#txtFcTax1').val() != '' ? $('#txtFcTax1').val() : 0);
		var nVal2   = ($('#txtFcTax2').val() != '' ? $('#txtFcTax2').val() : 0);
		var nVal3   = ($('#txtFcTax3').val() != '' ? $('#txtFcTax3').val() : 0);
		var nTotal  = parseFloat(nVal1)+parseFloat(nVal2)+parseFloat(nVal3);
		$('#txtTotalTax').val(nTotal);

		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
        accountValues();
		
	});

	$('#txtFcTax2').keyup(function()
	{
		var nVal1   = ($('#txtFcTax1').val() != '' ? $('#txtFcTax1').val() : 0);
		var nVal2   = ($('#txtFcTax2').val() != '' ? $('#txtFcTax2').val() : 0);
		var nVal3   = ($('#txtFcTax3').val() != '' ? $('#txtFcTax3').val() : 0);
		var nTotal  = parseFloat(nVal1)+parseFloat(nVal2)+parseFloat(nVal3);
		$('#txtTotalTax').val(nTotal);

		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
	    accountValues();	
	});

	$('#txtFcTax3').keyup(function()
	{
		var nVal1   = ($('#txtFcTax1').val() != '' ? $('#txtFcTax1').val() : 0);
		var nVal2   = ($('#txtFcTax2').val() != '' ? $('#txtFcTax2').val() : 0);
		var nVal3   = ($('#txtFcTax3').val() != '' ? $('#txtFcTax3').val() : 0);
		var nTotal  = parseFloat(nVal1)+parseFloat(nVal2)+parseFloat(nVal3);
		$('#txtTotalTax').val(nTotal);

		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
/*		else
			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);
*/
		$('#txtNetVendor').val(netTotal);
        accountValues();
		
	});

	$('#txtFace').keyup(function()
	{
		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
        accountValues();
	});

	$('#txtCommTax').keyup(function()
	{
		var nVal1   = ($('#txtFcTax1').val() != '' ? $('#txtFcTax1').val() : 0);
		var nVal2   = ($('#txtFcTax2').val() != '' ? $('#txtFcTax2').val() : 0);
		var nVal3   = ($('#txtFcTax3').val() != '' ? $('#txtFcTax3').val() : 0);
		var nTotal  = parseFloat(nVal1)+parseFloat(nVal2)+parseFloat(nVal3);
		$('#txtTotalTax').val(nTotal);

		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
        accountValues();
		
	});

	$('#txtGSATax').keyup(function()
	{
		var nVal1   = ($('#txtFcTax1').val() != '' ? $('#txtFcTax1').val() : 0);
		var nVal2   = ($('#txtFcTax2').val() != '' ? $('#txtFcTax2').val() : 0);
		var nVal3   = ($('#txtFcTax3').val() != '' ? $('#txtFcTax3').val() : 0);
		var nTotal  = parseFloat(nVal1)+parseFloat(nVal2)+parseFloat(nVal3);
		$('#txtTotalTax').val(nTotal);

		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
	    accountValues();	
	});

	$('#txtORCTax').keyup(function()
	{
		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
        accountValues();
	});

	$('#txtFcFare').keyup(function()
	{
		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);
		$('#txtNetVendor').val(netTotal);
        accountValues();
	});

	$('#txtFcTax').keyup(function()
	{
		var nVal4   = ($('#txtTotalTax').val() != '' ? $('#txtTotalTax').val() : 0);
		var nVal5   = ($('#txtFace').val() != '' ? $('#txtFace').val() : 0);
		var nVal6   = ($('#txtCommAmt').val() != '' ? $('#txtCommAmt').val() : 0);
		var nVal7   = ($('#txtGSAAmt').val() != '' ? $('#txtGSAAmt').val() : 0);
		var nVal8   = ($('#txtORCAmt').val() != '' ? $('#txtORCAmt').val() : 0);
		var nVal9   = ($('#txtFcFare').val() != '' ? $('#txtFcFare').val() : 0);
		var nVal10  = ($('#txtFcTax').val() != '' ? $('#txtFcTax').val() : 0);
		var nVal11  = ($('#txtRate').val() != '' ? $('#txtRate').val() : 0);

//	    if($('#txtFcFare').val() == '')
			var netTotal  = (parseFloat(nVal5)+parseFloat(nVal4))-(parseFloat(nVal6)+parseFloat(nVal7)+parseFloat(nVal8));
//		else
//			var netTotal  = (parseFloat(nVal9)+parseFloat(nVal10))*parseFloat(nVal11);

		$('#txtNetVendor').val(netTotal);
        accountValues();
	});

	//load location default
	<?php if($this->uri->segment(4) == 'e')
    {?>
		var tktId = '<?php echo $this->ddlTktname;?>';
		var nVal  = $('#ddlLocation').val();
	
		$.ajax(
			{
				type:'POST',
				data:{ddlLocation:nVal,ddlTktname:tktId},
				url:'<?php echo base_url().'index.php/ticket_booking/getStock/'.$this->uri->segment(3);?>',
				dataType:'html',
				success: function(response) {
					$('#locStock').html(response);
				}
			});
	<?php }//end if?>
	
	$('#ddlFare').change(function()
	{
		var nVal  = $('#ddlFare').val();
		var nVal1 = $('#ddlFareCode').val();
		var nVal2 = $('#ddlAirline').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlFare:nVal,ddlFareCode:nVal1,ddlAirline:nVal2},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateFareCode/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#farecode').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#farecode').html(response);
				}
		  });
	});

	<?php if($this->uri->segment(4) == 'e')
	{?>
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlFare:'<?php echo $this->ddlFare;?>',ddlFareCode:'<?php echo $this->ddlFareCode;?>'},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateFareCode/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#farecode').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#farecode').html(response);
				}
		  });
	<?php }//end if?>

	$('#ddlFareCode').change(function()
	{
		var nVal = $('#ddlFareCode').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlFareCode:nVal},
			 url:'<?php echo base_url().'index.php/ticket_booking/generateFareValue/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#txtFace').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtFace').val(response);
				}
		  });
	});

	$('.chkSegment').keyup(function(e) {
		$('#txtSegment').val(getSegmentTotal());
    });


	$('.chkCnjSegment').keyup(function(e) {
		$('#txtSegmentCnjTkt').val(getSegmentCnjTotal());
    });

	//fucntion to get segment total
	function getSegmentTotal()
	{
		var carr1 = $('#txtCarrier1').val();
		var carr2 = $('#txtCarrier2').val();
		var carr3 = $('#txtCarrier3').val();
		var carr4 = $('#txtCarrier4').val();

		carr1     = (carr1 == '')?0:1;
		carr2     = (carr2 == '')?0:1;
		carr3     = (carr3 == '')?0:1;
		carr4     = (carr4 == '')?0:1;

		var total = parseInt(carr1) + parseInt(carr2) + parseInt(carr3) + parseInt(carr4);

		return (total);
	}//end funciton

	//function to get sector value
	function getSectorVal()
	{
		var sec1 = $('#txtSector12').val();
		var sec2 = $('#txtSector22').val();
		var sec3 = $('#txtSector33').val();
		var sec4 = $('#txtSector44').val();

		sec1     = (sec1 == '')?0:sec1;
		sec2     = (sec2 == '')?0:sec2;
		sec3     = (sec3 == '')?0:sec3;
		sec4     = (sec4 == '')?0:sec4;

		var segment = getSegmentTotal();
		var depVal;

		switch(segment)
		{
			case 1:
				depVal = sec1;
			break;

			case 2:
				depVal = sec2;
			break;

			case 3:
				depVal = sec3;
			break;

			case 4:
				depVal = sec2;
			break;
		}//end switch

		return (depVal);
	}//end function

	//fucntion to get conjuction segment total
	function getSegmentCnjTotal()
	{
		var carr1 = $('#txtCarrierTkt1').val();
		var carr2 = $('#txtCarrierTkt2').val();
		var carr3 = $('#txtCarrierTkt3').val();
		var carr4 = $('#txtCarrierTkt4').val();

		carr1     = (carr1 == '')?0:1;
		carr2     = (carr2 == '')?0:1;
		carr3     = (carr3 == '')?0:1;
		carr4     = (carr4 == '')?0:1;

		var total = parseInt(carr1) + parseInt(carr2) + parseInt(carr3) + parseInt(carr4);

		return (total);
	}//end funciton

	//function to get conjuction sector value
	function getSectorCnjVal()
	{
		var sec1 = $('#txtSectorTkt12').val();
		var sec2 = $('#txtSectorTkt22').val();
		var sec3 = $('#txtSectorTkt33').val();
		var sec4 = $('#txtSectorTkt44').val();

		sec1     = (sec1 == '')?0:sec1;
		sec2     = (sec2 == '')?0:sec2;
		sec3     = (sec3 == '')?0:sec3;
		sec4     = (sec4 == '')?0:sec4;

		var segment = getSegmentCnjTotal();
		var depVal;

		switch(segment)
		{
			case 1:
				depVal = sec1;
			break;

			case 2:
				depVal = sec2;
			break;

			case 3:
				depVal = sec3;
			break;

			case 4:
				depVal = sec2;
			break;
		}//end switch

		return (depVal);
	}//end function

    $('#txtCopyTkt').click(function()
	{
        var nVal  = $('#txtCopyTkt').val();
        $.ajax({
            type:'POST',
            data:{txtCopyTkt:nVal},
            url:'<?php echo base_url().'index.php/ticket_booking/checkTicketDetails/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#copyref').html(response);
            }
        });
	});

	$('#toPopup1').hide();
	$('#toPopup2').hide();
	$('#toPopup3').hide();

	$('.topopup').click(function(){
		var id = this.id;
		$('#toPopup'+id).show();
	});

	$('#ddlAirline').change(function() {
		var nVal 		= $('#ddlAirline').val();
		//var airlineCode = $("#txtAirlineName").val().split("-");
		$.ajax(
		  {
			 type:'POST',
			 data:{txtAirlinecode:nVal},
			 url:'<?php echo base_url().'index.php/ticket_booking/checkAirlineAlpha/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#txtCarrier1').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtCarrier1').val(response);
				}
		  });
	});


    $('#txtSalesAmt1').keyup(function(){
        $('#txtSalesAmt').val($('#txtSalesAmt1').val());
        accountValues();
    });
/*
    $('#txtDiscount').keyup(function(){
        accountValues();
    });

    $('#txtDiscount').keyup(function(){
        var nVal  = ($('#txtSalesAmt1').val() != '' ? $('#txtSalesAmt1').val() : 0);
        var nVal2 = ($('#txtDiscount').val() != '' ? $('#txtDiscount').val() : 0);
        var Amt   = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtDiscountAmt').val(Amt);
    });

    $('#txtDiscountAmt').keyup(function(){
        var nVal  = ($('#txtSalesAmt1').val() != '' ? $('#txtSalesAmt1').val() : 0);
        var nVal2 = ($('#txtDiscountAmt').val() != '' ? $('#txtDiscountAmt').val() : 0);
        var Amt   = parseFloat(nVal)-parseFloat(nVal2);
        $('#txtCollected').val(Amt);

        var discount   = parseFloat(nVal2/parseFloat(nVal))*100;
        $('#txtDiscount').val(discount);
    });

    $('#txtCollected').keyup(function(){
        var nVal  = ($('#txtCollected').val() != '' ? $('#txtCollected').val() : 0);
        var nVal2 = ($('#txtNetVendor').val() != '' ? $('#txtNetVendor').val() : 0);
        var Amt   = parseFloat(nVal)-parseFloat(nVal2);
        $('#txtServiceAmt').val(Amt);
    });
*/
    $('#txtServiceAmt').keyup(function(){
        var nVal  = ($('#txtServiceAmt').val() != '' ? $('#txtServiceAmt').val() : 0);
        var nVal2 = ($('#txtNetVendor').val() != '' ? $('#txtNetVendor').val() : 0);
        var Amt   = (parseFloat(nVal)/parseFloat(nVal2))*100;
        $('#txtServicePerc').val(Amt);
    });

    <?php if($this->uri->segment(4) == 'a')
    {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:$('#ddlDivision').val(),ddlTktname:0},
        url:'<?php echo base_url().'index.php/ticket_booking/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
            changeAllAccount();

        }
    });

    <?php }//end if
    else {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:'<?php echo $this->ddlDivision;?>',ddlLocation:'<?php echo $this->ddlLocation;?>',ddlTktname:'<?php echo $this->ddlTktname;?>'},
        url:'<?php echo base_url().'index.php/ticket_booking/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
            changeAllAccount();
        }
    });
    <?php }//end else?>

    $('#ddlDivision').change(function()
    {
        var nVal  = $('#ddlDivision').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlDivision:nVal,ddlTktname:0},
                url:'<?php echo base_url().'index.php/ticket_booking/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#loc').html(response);
                    changeAllAccount();
                }
            });
    });

    $('#ddlLocation').change(function(e) {
        var nVal  = $('#ddlLocation').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlLocation:nVal},
                url:'<?php echo base_url().'index.php/ticket_booking/getStock/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#locStock').html(response);
                }
            });
        changeAllAccount();

    });
    $('#loc').change(function(){
          changeAllAccount();
            });

	$('#txtTktName').keyup(function(e) {
        var nVal  = $('#txtTktName').val();
		var sVal  = $('#ddlTktname').val();
		sVal      = sVal.split("-");
		sVal	  = sVal[2];
		sVal      = sVal.split(",");
		
		$.each(sVal, function(key, value) {
   			if($.trim(value) == $.trim(nVal))
			{
				alert('Already used');
				$('#txtTktName').val('');
			}//end if
		});
    });
	
    $('#ddlAccname').change(function(e) {
        var nVal  = $('#ddlAccname').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAccname:nVal},
                url:'<?php echo base_url().'index.php/ticket_booking/getAccountHead/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#accHead').html(response);
                }
            });
    });

    $('#txtCarrier2').click(function(){
        $('#txtCarrier2').val($('#txtCarrier1').val());
    });

    $('#txtCarrier3').click(function(){
        $('#txtCarrier3').val($('#txtCarrier1').val());
    });

    $('#txtCarrier4').click(function(){
        $('#txtCarrier4').val($('#txtCarrier1').val());
    });

	$('#ddlTktname').change(function(e) {
		var sVal  = $('#ddlTktname').val();
		//var sVal2 = $("#ddlTktname option[value='"+sVal+"']").text();
		sVal     = sVal.split("-");
		
		$('#txtTktName').val(sVal[1]);
    });
	
	/*$("#selectId > option").each(function() {
    alert(this.text + ' ' + this.value);
});*/


});
</script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/jquery.bgiframe.min.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/jquery.ajaxQueue.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/lib/thickbox-compressed.js'></script>
<script type='text/javascript' src='<?php echo base_url();?>static/plugins/auto_complete/jquery.autocomplete.js'></script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>static/plugins/auto_complete/jquery.autocomplete.css" />
<link rel="stylesheet" type="text/css" href="<?php echo base_url();?>static/plugins/auto_complete/lib/thickbox.css" />

<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<?php /*?><script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"> </script>
<?php */?><script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCountry', 'id' => 'frmValidate');
if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('ticket_booking/editTicketbooking/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nCId',$this->conjId);?>
<?php //echo form_hidden('txtDecimal','','','id="txtDecimal"');?>
<!--<input type="hidden" name="txtDecimal" id="txtDecimal" value="" />-->
<div id="copyref">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>
            </label></td>

            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><label><span id="loc">
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>
            </span></label></td>

            <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtDate" id="txtDate" class="textfield4" type="text" value="<?php echo $this->txtDate;?>">
            </label></td>
        </tr>

        <tr>
            <td><?php echo DOCNUMBER;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text" value="<?php echo $this->txtDocNumber;?>">
            </label></td>

            <td><?php echo STATUS;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType,'id="ddlType" class="singleDrop"');?>
            </label></td>

            <td><?php echo GDS;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS,'id="ddlGDS" class="singleDrop"');?>
            </label></td>
        </tr>

        <tr>
            <td><?php echo TICKETNAME;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
				<?php echo form_dropdown('ddlAirline', $list_airline_value, $this->ddlAirline,'id="ddlAirline" class="multipleDrop"');?></label>&nbsp;
                <span id="locStock">
                 <?php echo form_dropdown('ddlTktname', $list_tkt_value, $this->ddlTktname,'id="ddlTktname" class="multipleDrop"');?>
                </span> &nbsp;
                   <input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" class="textfield"/>
            </td>
          
                     <td><?php echo ORIGINALTKT;?></td>
            <td><label>
            <input name="txtOriginalTkt" id="txtOriginalTkt" class="textfield4 chkSuggestion" type="text" value="<?php echo stripslashes($this->txtOriginalTkt);?>">
            </label></td>
        </tr>

        <tr>
            <td><?php echo PASSENGER;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtPassenger" id="txtPassenger" class="textfield" type="text" value="<?php echo stripslashes($this->txtPassenger);?>">
            </label></td>

            <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
            <td><label>
            <span id="Issued">
             <?php if(!empty($this->ddlIssued)){
                       echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued,'id="ddlIssued" class="multipleDrop"');
                       } else {
                              $login_id = $this->session->userdata('AbsAdmin');
                              $emp_id= $this->common_model->get_field_name('Admin','nEmpId'," WHERE nId = '".$login_id."' ");
                              echo form_dropdown('ddlIssued', $list_emp_value, $emp_id,'id="ddlIssued" class="multipleDrop"');
                       }
             ?>
             </span>
            </label></td>


        <td><?php echo BOOKEDBY;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
        <span id="Booked">
            <?php  if(!empty($this->ddlBooked)){
                        echo form_dropdown('ddlBooked', $list_emp_value, $this->ddlBooked,'id="ddlBooked" class="multipleDrop"');
                       } else {
                              $login_id = $this->session->userdata('AbsAdmin');
                              $emp_id= $this->common_model->get_field_name('Admin','nEmpId'," WHERE nId = '".$login_id."' ");
                              echo form_dropdown('ddlBooked', $list_emp_value,$emp_id ,'id="ddlBooked" class="multipleDrop"');
                       }
             ?>
            </span>
        </label></td>

        <tr>
            <td><?php echo GDSPNR;?></td>
            <td><label>
             <input name="txtGdsPNR" id="txtGdsPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtGdsPNR);?>">
            </label></td>

            <td><?php echo AIRPNR;?></td>
            <td><label>
             <input name="txtAirPNR" id="txtAirPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAirPNR);?>">
            </label></td>
        </tr>

        <tr>
            <td><?php echo SEGMENT;?></td>
            <td><label>
            <input name="txtSegment" id="txtSegment" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSegment);?>">
            </label></td>

            <td><?php echo DESTINATION;?></td>
            <td><label>
            <input name="txtDestination" id="txtDestination" class="textfield3" type="text" value="<?php echo stripslashes($this->txtDestination);?>">
            </label></td>

            <td><?php echo COPYTKT;?></td>
            <td><label>
            <input name="txtCopyTkt" id="txtCopyTkt" class="textfield4 chkSuggestion" type="text" value="<?php echo stripslashes($this->txtCopyTkt);?>">
            </label></td>
        </tr>

        <tr>
            <td colspan="7">
            <div>
                <span style="font-size: 12px;font-weight: bold;"><?php echo TICKETDETAILS;?></span>
                <span class="clears"></span>
                <table id="dataControl" title="dataControl">
                    <tr>
                        <td><?php echo SRNO;?></td>
                        <td><?php echo CARRIER;?></td>
                        <td><?php echo FLIGHTNO;?></td>
                        <td><?php echo TKTCLASS;?></td>
                        <td><?php echo TRAVELDATE;?></td>
                        <td align="center">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<?php echo SECTOR;?></td>
                        <td>&nbsp;</td>
                        <td><?php echo STATUS;?></td>
                        <td><?php echo TIMEDEP;?></td>
                        <td><?php echo TIMEARR;?></td>
                        <td><?php echo DAY;?></td>
                    </tr>

                    <tr>
                        <td>1</td>
                        <td><input name="txtCarrier1" id="txtCarrier1" class="chkSegment" type="text" value="<?php echo stripslashes($this->txtCarrier1);?>"></td>
                        <td><input name="txtFlightNo1" id="txtFlightNo1" class="textfield4" type="text" value="<?php echo stripslashes($this->txtFlightNo1);?>"></td>
                        <td><input name="txtClass1" id="txtClass1" class="textfield4" type="text" value="<?php echo stripslashes($this->txtClass1);?>"></td>
                        <td><input name="txtTravelDate1" id="txtTravelDate1" class="textfield4 chkDate" type="text" value="<?php echo stripslashes($this->txtTravelDate1);?>"></td>
                        <td><input name="txtSector1" id="txtSector1" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector1);?>"></td>
                        <td><input name="txtSector12" id="txtSector12" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector12);?>"></td>
                        <td><input name="txtStatus1" id="txtStatus1" class="textfield4" type="text" value="<?php echo stripslashes($this->txtStatus1);?>"></td>
                        <td><input name="txtTimeDep1" id="txtTimeDep1" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeDep1);?>"></td>
                        <td><input name="txtTimeArr1" id="txtTimeArr1" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeArr1);?>"></td>
                        <td><input name="txtDay1" id="txtDay1" class="chkDay" type="text" value="<?php echo stripslashes($this->txtDay1);?>"></td>
                   </tr>

                   <tr>
                        <td>2</td>
                        <td><input name="txtCarrier2" id="txtCarrier2" class="chkSegment" type="text" value="<?php echo stripslashes($this->txtCarrier2);?>"></td>
                        <td><input name="txtFlightNo2" id="txtFlightNo2" class="textfield4" type="text" value="<?php echo stripslashes($this->txtFlightNo2);?>"></td>
                        <td><input name="txtClass2" id="txtClass2" class="textfield4" type="text" value="<?php echo stripslashes($this->txtClass2);?>"></td>
                        <td><input name="txtTravelDate2" id="txtTravelDate2" class="textfield4 chkDate" type="text" value="<?php echo stripslashes($this->txtTravelDate2);?>"></td>
                        <td><input name="txtSector2" id="txtSector2" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector2);?>"></td>
                        <td><input name="txtSector22" id="txtSector22" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector22);?>"></td>
                        <td><input name="txtStatus2" id="txtStatus2" class="textfield4" type="text" value="<?php echo stripslashes($this->txtStatus2);?>"></td>
                        <td><input name="txtTimeDep2" id="txtTimeDep2" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeDep2);?>"></td>
                        <td><input name="txtTimeArr2" id="txtTimeArr2" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeArr2);?>"></td>
                        <td><input name="txtDay2" id="txtDay2" class="chkDay" type="text" value="<?php echo stripslashes($this->txtDay2);?>"></td>
                   </tr>

                   <tr>
                        <td>3</td>
                        <td><input name="txtCarrier3" id="txtCarrier3" class="chkSegment" type="text" value="<?php echo stripslashes($this->txtCarrier3);?>"></td>
                        <td><input name="txtFlightNo3" id="txtFlightNo3" class="textfield4" type="text" value="<?php echo stripslashes($this->txtFlightNo3);?>"></td>
                        <td><input name="txtClass3" id="txtClass3" class="textfield4" type="text" value="<?php echo stripslashes($this->txtClass3);?>"></td>
                        <td><input name="txtTravelDate3" id="txtTravelDate3" class="textfield4 chkDate" type="text" value="<?php echo stripslashes($this->txtTravelDate3);?>"></td>
                        <td><input name="txtSector3" id="txtSector3" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector3);?>"></td>
                        <td><input name="txtSector33" id="txtSector33" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector33);?>"></td>
                        <td><input name="txtStatus3" id="txtStatus3" class="textfield4" type="text" value="<?php echo stripslashes($this->txtStatus3);?>"></td>
                        <td><input name="txtTimeDep3" id="txtTimeDep3" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeDep3);?>"></td>
                        <td><input name="txtTimeArr3" id="txtTimeArr3" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeArr3);?>"></td>
                        <td><input name="txtDay3" id="txtDay3" class="chkDay" type="text" value="<?php echo stripslashes($this->txtDay3);?>"></td>
                   </tr>

                   <tr>
                        <td>4</td>
                        <td><input name="txtCarrier4" id="txtCarrier4" class="chkSegment" type="text" value="<?php echo stripslashes($this->txtCarrier4);?>"></td>
                        <td><input name="txtFlightNo4" id="txtFlightNo4" class="textfield4" type="text" value="<?php echo stripslashes($this->txtFlightNo4);?>"></td>
                        <td><input name="txtClass4" id="txtClass4" class="textfield4" type="text" value="<?php echo stripslashes($this->txtClass4);?>"></td>
                        <td><input name="txtTravelDate4" id="txtTravelDate4" class="textfield4 chkDate" type="text" value="<?php echo stripslashes($this->txtTravelDate4);?>"></td>
                        <td><input name="txtSector4" id="txtSector4" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector4);?>"></td>
                        <td><input name="txtSector44" id="txtSector44" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSector44);?>"></td>
                        <td><input name="txtStatus4" id="txtStatus4" class="textfield4" type="text" value="<?php echo stripslashes($this->txtStatus4);?>"></td>
                        <td><input name="txtTimeDep4" id="txtTimeDep4" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeDep4);?>"></td>
                        <td><input name="txtTimeArr4" id="txtTimeArr4" class="textfield4" type="text" value="<?php echo stripslashes($this->txtTimeArr4);?>"></td>
                        <td><input name="txtDay4" id="txtDay4" class="chkDay" type="text" value="<?php echo stripslashes($this->txtDay4);?>"></td>
                   </tr>
                </table>
                </div>
            </td>
        </tr>
<!--
        <tr>
        	<td><a href="#" class="topopup" id="1"><span style="font-size: 12px;font-weight: bold;"><?php echo CONJUCTIONDETAILS;?></span></a></td>

            <td colspan="6"><a href="#" class="topopup" id="3"><span style="font-size: 12px;font-weight: bold;"><?php echo PASSENGERCONTACT;?></span></a></td>
        </tr>
-->
        <tr>
            <td colspan="7">
            <div>

                <table id="dataControl" title="dataControl">
                	<tr>
                		<td colspan="10">
			                <span style="font-size: 12px;font-weight: bold;"><?php echo FAREDETAILS;?></span>
			                <span class="clears"></span>
                		</td>
                	</tr>

                    <tr>
                      <td><?php echo FARE;?></td>
                      <td><?php echo TKTFARECODE;?></td>
                      <td><?php echo FACEFARE;?></td>
                      <td><?php echo MARKETFARE;?></td>
                      <td><?php echo TAX;?></td>
                      <td><?php echo TAX;?></td>
                      <td><?php echo TAX;?></td>
                      <td><?php echo TOTALTAX;?></td>
                      <td><?php echo FCFARE?></td>
                      <td><?php echo FCTAX?></td>
                      <td></td>
                    </tr>

                    <tr>
                        <td valign="top"><label>
                         <?php echo form_dropdown('ddlFare', $list_fare_value, $this->ddlFare,'id="ddlFare" class="singleDrop"');?>
                        </label></td>

 						<td valign="top" id="farecode"></td>

                       <td valign="top"><label>
                         <input name="txtFace" id="txtFace" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFace);?>">
                        </label></td>

                       <td valign="top"><label>
                        <input name="txtMktFare" id="txtMktFare" class="textfield5" type="text" value="<?php echo stripslashes($this->txtMktFare);?>">
                       </label></td>

                      <td valign="top"><label>
                         <input name="txtFcTax1" id="txtFcTax1" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFcTax1);?>">
                        </label></td>

                      <td valign="top"><label>
                         <input name="txtFcTax2" id="txtFcTax2" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFcTax2);?>">
                        </label></td>

                      <td valign="top"><label>
                         <input name="txtFcTax3" id="txtFcTax3" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFcTax3);?>">
                      </label></td>

                      <td valign="top"><label>
                         <input name="txtTotalTax" id="txtTotalTax" class="textfield5" type="text" value="<?php echo stripslashes($this->txtTotalTax);?>">
                        </label></td>

                      <td valign="top"><label>
                         <input name="txtFcFare" id="txtFcFare" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFcFare);?>">
                        </label></td>

                      <td valign="top"><label>
                         <input name="txtFcTax" id="txtFcTax" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFcTax);?>">
                        </label></td>
                  </tr>
 	                </table>
                </div>
            </td>
        </tr>


        <tr> 
          <td colspan = "7">
          <table id="dataControl" title="dataControl">
          <tr>
          <td><?php echo CURRNAME;?></td>
          <td><label>
            <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency"');?>
          </label></td>

          <td><?php echo CURRRATE;?></td>
          <td><label>
            <input name="txtRate" id="txtRate" class="textfield3" type="text" value="<?php echo $this->txtRate;?>">
          </label></td>

          <td><?php echo SUBCODE;?></td>
          <td><label>
          <?php
		  //var_dump($list_acc_value);

		  echo form_dropdown('ddlAccname', $list_acc_value, $this->ddlAccname,'id="ddlAccname" ');?>
          </label></td>

          <td><?php echo ACCHEAD;?></td>
          <td><label>
          <span id="accHead">
          <?php echo form_dropdown('ddlAccHead', $list_acchead_value, $this->ddlAccHead,'id="ddlAccHead" readonly="readonly"');?>
          </span>
          </label></td>

          </table>
          </tr>
          </td>

        </tr>

         <tr>
            <td colspan="7">
                <table id="dataControl" title="dataControl">
                    <tr>
                      <td><?php echo COMM;?></td>
                      <td><?php echo COMMAMT;?></td>
                      <td><?php echo GSAPERCENT;?></td>
                      <td><?php echo COMMAMT;?></td>
                      <td><?php echo ORCPERCENT;?></td>
                      <td><?php echo COMMAMT;?></td>
                      <td><?php echo NETTOVENDOR;?></td>
                      <td>&nbsp;</td>
                    </tr>

                    <tr>
                       <td><label>
                         <input name="txtCommTax" id="txtCommTax" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCommTax);?>">
                        </label></td>

                      <td><label>
                        <input name="txtCommAmt" id="txtCommAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCommAmt);?>">
                       </label></td>

                      <td><label>
                        <input name="txtGSATax" id="txtGSATax" class="textfield5" type="text" value="<?php echo stripslashes($this->txtGSATax);?>">
                       </label></td>

                      <td><label>
                        <input name="txtGSAAmt" id="txtGSAAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtGSAAmt);?>">
                       </label></td>

                      <td><label>
                        <input name="txtORCTax" id="txtORCTax" class="textfield5" type="text" value="<?php echo stripslashes($this->txtORCTax);?>">
                       </label></td>

                      <td><label>
                        <input name="txtORCAmt" id="txtORCAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtORCAmt);?>">
                       </label></td>
                     <td>
                      <input name="txtNetVendor" id="txtNetVendor" class="textfield5" type="text" value="<?php echo stripslashes($this->txtNetVendor);?>">
                    </td>
                 </tr>

                 <tr>
                    </tr>
                    <td colspan="10">
			                <span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span>
			                <span class="clears"></span>
                		</td>

                <tr>
                <td><?php echo CUSTACCNAME;?></td>
                <td><label>
                <span id="CustAc">
                <?php echo form_dropdown('ddlCustomerAc', $list_custacc_value, $this->ddlCustomerAc,'id="ddlCustomerAc" ');?>
                </span>
                </label></td>

                <td><?php echo SALESAMT;?></td>
                <td><label>
                <input name="txtSalesAmt1" id="txtSalesAmt1" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCollected);?>">
                <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="hidden" value="<?php echo stripslashes($this->txtCollected);?>">
<!--                <input name="txtSalesAmt1" id="txtSalesAmt1" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSalesAmt1);?>">
                <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="hidden" value="<?php echo stripslashes($this->txtSalesAmt);?>"> -->
                </label></td>

                <td>&nbsp;</td>
                <td><label>
                <input name="txtServicePerc" id="txtServicePerc" class="textfield5 hidden" type="text" value="<?php echo stripslashes($this->txtServicePerc);?>">
                </label></td>

                <td>&nbsp;</td>
                <td><label>
                <input name="txtServiceAmt" id="txtServiceAmt" class="textfield5 hidden" type="text" value="<?php echo stripslashes($this->txtServiceAmt);?>">
                </label></td>
               </tr>


                    </table>
                </td>
            </tr>

 <!--			<tr>
            	<td colspan="7"><a href="#" class="topopup" id="2"><span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span></a></td>
            </tr>
-->
        <?php if($this->uri->segment(4) == 'e'){?>
        <tr>
            <td>
                <label><?php echo SALESCLOSINGSTATUS?></label>
            </td>
            <td>
			<input type="hidden" name="clstatus" value="<?php if($close_status->nTId !=''){ echo "OPEN"; } else { echo "CLOSED"; } ?>" readonly="readonly">
          
                <label>
				<input type="hidden" name="clstatus" value="<?php if($close_status->nTId !=''){ echo "OPEN"; } else { echo "CLOSED"; } ?>" readonly="readonly">
           
                    <input type="text" value="<?php if($close_status->nTId !=''){ echo OPEN; } else { echo CLOSED; } ?>" readonly="readonly">
                </label>
            </td>
            <td>
                <?php if($close_status->nTId ==''){?>
                <label><?php echo INVOICE ?></label>
                <label>
                    <input type="text" value="<?php echo $invoice_number->vInvoiceNumber;?>" readonly="readonly">
                </label>
                <?php }?>
            </td>
        </tr>
        <tr>
            <td>
                <label><?php echo REFUNDCLOSINGSTATUS?></label>
            </td>
            <td>
                <label>
                    <input type="text" value="<?php if($refund_status->nTId !=''){ echo OPEN; } else { echo CLOSED; } ?>"  readonly="readonly">
                </label>
            </td>
            <td>
                <?php if($refund_status->nTId ==''){?>
                <label><?php echo CREDITNOTE ?></label>
                <label>
                    <input type="text" value="<?php echo $refund_number->vDocNumber;?>" readonly="readonly">
                </label>
                <?php }?>
            </td>
        </tr>
            <?php }//end if?>

            <tr class="formButs">
                <td>&nbsp;</td>
                <td colspan="9"><label>
                   <?php 
				   
 $val_alloc =$this->common_model->get_field_name('CashAllocation','nSalesCloseId'," WHERE vSalesCloseType ='SC' AND nSalesCloseId = '".$invoice_id->nSCId."'");
				if($val_alloc==0){
				   
				   if($this->uri->segment(4) == 'a'):?>
                    <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
                   <?php else:?>
                        <?php if($close_status->nTId !='' || trim($this->session->userdata('backURL')) != '' || $this->uri->segment(6) == 'closed'){  ?>
                            <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
                        <?php }
                        else{ ?>
                            <input type="button" name="btnClosed" id="btnClosed" value="<?php echo CLOSED;?>" disabled="disabled"/>
                        <?php } ?>
                   <?php endif;?>
				   <?php  }else{?>
			
<input type="button" id="bac" onClick="return confirm('<?php echo 'Can not Update Remove Allocation First'; ?>');" value="<?php echo $this->btnVal;?>" />
<?php   }?>

                  <?php if($this->uri->segment(4) == 'a'){?>
                  <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
                  <?php }//end if?>
                  <input type="reset" value="<?php echo RESET;?>" />
                  <input type="button" name="btnCancel" value="<?php echo BACKTOTICKETBOOKING;?>" id="btnCancel">
                   <input type="button" name="btnPrintPopup" id="btnPrintPopup" value="<?php echo PRINTPAGE;?>"/>
                       
                </label>


                   <?php if($this->uri->segment(4) == 'a'):?>
                    <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
                   <?php else:?>
                        <?php if($close_status->nTId !='' || trim($this->session->userdata('backURL')) != '' || $this->uri->segment(6) == 'closed'){  ?>
                        <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
                        <?php }
                        else{ ?>
                          <!--  <input type="button" name="btnPrintPopup" id="btnPrintPopup" value="<?php echo PRINTPAGE;?>"/>
                        --><?php } ?>
                   <?php endif;?>


            </tr>
    </tbody>
  </table>

            <!-- Start of Sticky Note -->
            <div id="toPopup1" class="toPopup">
                <div class="close"></div>
                <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
                <div id="popup_content"> <!--your content start-->
                <?php echo $this->load->view('ticket_booking/show_conjuction');?>
                </div> <!--your content end-->
            </div>
            <!-- End of Sticky Note -->

            <!-- Start of 2 Sticky Note
            <div id="toPopup2" class="toPopup">
                <div class="close"></div>
                <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
                <div id="popup_content"> 
                <?php echo $this->load->view('ticket_booking/show_acdetails');?>
                </div>  
            </div>
             End of Sticky Note -->

            <!-- Start of 2 Sticky Note -->
            <div id="toPopup3" class="toPopup">
                <div class="close"></div>
                <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
                <div id="popup_content"> <!--your content start-->
                <?php echo $this->load->view('ticket_booking/show_passdetails');?>
                </div> <!--your content end-->
            </div>
            <!-- End of Sticky Note -->

  </div>

  </form>
<!--
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
-->
  </div>
