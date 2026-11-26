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
<script type="text/javascript" src="<?php echo base_url();?>static/js/date.f-0.5.0-min.js"></script>
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
    submitHandler:function () {
    }
});

$(function() {
    function format(acVal) {
        return acVal.name;
    }

    $(".chkSuggestion").keypress(function(e)
    {
        var nId  = this.id;
        var nVal = $('#'+this.id).val();
        $('#'+this.id).autocomplete('<?php echo base_url();?>index.php/xo_booking/suggestionlist', {
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
        $('#'+this.id).autocomplete('<?php echo base_url();?>index.php/xo_booking/airlinesuggestionlist', {
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
function accountValues(){
    var nVal = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
    var nVal2 = ($('#txtTotalNetVendorAmount').val() != '' ? $('#txtTotalNetVendorAmount').val() : 0);
    var SerAmt = parseFloat(nVal) - parseFloat(nVal2);
    $('#txtServiceAmt').val(SerAmt);
    var SerPercent = parseFloat((SerAmt/nVal2)*100);
    $('#txtServicePerc').val(SerPercent);
}


function netVendor(id){
    var nVal  = ($('#txtTotalTax' + id).val() != '' ? $('#txtTotalTax' + id).val() : 0);
    var nVal1 = ($('#txtFcTax' + id).val() != '' ? $('#txtFcTax' + id).val() : 0);
    var nVal2 = ($('#txtORCAmt' + id).val() != '' ? $('#txtORCAmt' + id).val() : 0);
    var netTotal = parseFloat(nVal) + parseFloat(nVal1) - parseFloat(nVal2);
    $('#txtNetVendor' + id).val(netTotal);
    totalNetVendor();
}

function totalNetVendor(){
    var txtTotal = 0;
    $('.txtNetVendor').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
            nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
            });
    $('#txtTotalNetVendorAmount').val(txtTotal);
    accountValues();
    $('#txtTotalNetVendor1').val(txtTotal);
}
function totalTax() {
    var txtTotal = 0;
    $('.txtTotalTax').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
            nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
            });
    $('#txtTotalTaxAmount').val(txtTotal);
}

$(document).ready(function () {

    $('#btnPrint').click(function(e) {
        var html = '<table><tr><td></td></tr><tr><td>';
        html += $('.reportForm').html();
        html += '</td></tr></table>';

        window.print();
        return false;
    });

    $('#btnCancel').click(function () {
        window.location.replace('<?php echo base_url().'index.php/xo_booking/showlist/'.$this->uri->segment(3);?>');
        return false;
    });

    jQuery.validator.addMethod(
        "selectNone",
        function (value, element) {
            if (element.value == "0") {
                return false;
            }
            else return true;
        },
        "<?php echo PLEASESELECTANOPTION;?>"
    );

    $("#txtDate").datepick({ dateFormat:'dd-mm-yyyy'});

    $(".txtTravelDate").datepick({ dateFormat:'dd-mm-yyyy',
        onSelect:function(){
            var dId     = this.id;
            dId         = dId.split('txtTravelDate');
            var dId1    = dId[1];
            var myDate  = new Date($("#txtTravelDate"+dId1).val());
            var sDate   = myDate.f("EE");
            $('#txtDay'+dId1).val(sDate);
        }
    });

    // validate signup form on keyup and submit
    $("#frmValidate").validate({
        rules:{
            ddlDivision:{
                selectNone:true
            },
            ddlLocation:{
                selectNone:true
            },
            ddlTrans:{
                selectNone:true
            },
            txtAirlineName:{
                required:true
            },
//            ddlGDS:{
//                selectNone:true
//            },
            ddlBooked:{
                selectNone:true
            },
            ddlIssued:{
                selectNone:true
            },
            txtPassenger:{
                required:true
            }
        },
        messages:{
            ddlDivision:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlLocation:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlTrans:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            txtAirlineName:{
                required:"<?php echo PLEASESELECTANOPTION;?>"
            },
//            ddlGDS:{
//                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
//            },
            ddlBooked:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlIssued:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            txtPassenger:{
                required:"<?php echo PLEASESELECTANOPTION;?>"
            }
        },
        success:function (label2) {
            // set   as text for IE
            label2.html("").addClass("checked");
        },
        submitHandler:function (form) {
            $(form).ajaxSubmit();
        }

    });

    /*------------------Custom Add new row for account------------------*/
    var i = 1;
    $("#addRow").click(function () {
        var m = $('#dataTable tbody>tr.re:last').clone(true).find("input").each(function () {
            var id = this.id;
            $(this).attr({
                'id':function (_, id2) {
                    return id + i;
                },
                'value':''
            });
        }).end().insertAfter('#dataTable tbody>tr.re:last');
        i++;

        var rowCount = $("#dataTable").attr('rows').length;
        m.find('img[class="del"]').attr('src', '<?php echo base_url();?>static/images/cross.png');
        return false;
    });

    /*------------------Custom delete row ------------------*/

    $('.del').live('click', function () {
        var rowCount = $("#dataTable").attr('rows').length;
        if ((rowCount)>3) {
            $(this).parent().parent().remove();
            //delete from the db
            var dId = this.id;
            dId     = dId.split('_');
            //delete from db
            $.ajax(
                {
                    type:'POST',
                    url:'<?php echo base_url().'index.php/xo_booking/deleteRecord/'.$this->uri->segment(3);?>',
                    data:{subCode:dId[1]},
                    dataType:'html',
                    beforeSend:function () {
                        //$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                    },
                    success:function (response) {
                    }
                });
        }//end if
        else {
            $('#del_' + rowCount).attr('disabled', true);
        }//end if

    });

	$('#ddlAirline').change(function() {
		var nVal 		= $('#ddlAirline').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtAirlinecode:nVal},
			 url:'<?php echo base_url().'index.php/xo_booking/checkAirlineAlpha/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#txtCarrier1').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#txtCarrier1').val(response);
					$('#txtCarrier2').val(response);
					$('#txtCarrier3').val(response);
					$('#txtCarrier4').val(response);
				}
		  });
	});

    $('#ddlTrans').change(function () {
        var nVal = $('#ddlTrans').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlTrans:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/generateDocNo/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#txtDocNumber').val(response);
                }
            });
    });

    $('#ddlCurrency').change(function () {
        var nVal = $('#ddlCurrency').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlCurrency:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/generateCurrRate/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#txtRate').val(response);
                }
            });
    });

    var i = 1;
    $("#addRow2").click(function () {
        var m = $('#dataControl tbody>tr.re:last').clone(true).find("input").each(function () {
            var id = this.id;
            var cname = $('#' + id).attr('data');
            if (cname == 'txtFc1Tax') {
                var valChk = id.split('txtFc1Tax');
                valChk = valChk[1];
                valChk = valChk + i;
            }//end if

            if (cname == 'txtFc2Tax') {
                var valChk = id.split('txtFc2Tax');
                valChk = valChk[1];
                valChk = valChk + i;
            }//end if

            if (cname == 'txtFc3Tax') {
                var valChk = id.split('txtFc3Tax');
                valChk = valChk[1];
                valChk = valChk + i;
            }//end if
			
            if (cname == 'txtTotalTax') {
                var valChk = id.split('txtTotalTax');
                valChk = valChk[1];
                valChk = valChk + i;
            }//end if

            if (cname == 'txtNetVendor') {
                var valChk = id.split('txtNetVendor');
                valChk = valChk[1];
                valChk = valChk + i;
            }//end if

            $(this).attr({
                'id':function (_, id2) {
                    return id + i;
                },
                'value':'',
                'title':valChk
            });
        }).end().clone(true).find("select").each(function () {
                var id = this.id;
                $(this).attr({
                    'id':function (_, id2) {
                        return id + i;
                    },
                    'value':''
                });
            }).end().insertAfter('#dataControl tbody>tr.re:last');
        i++;

        var rowCount = $("#dataControl").attr('rows').length;
        m.find('img[class="del"]').attr('src', '<?php echo base_url();?>static/images/cross.png');
        return false;
    });

    $('.del2').live('click', function () {
        var rowCount = $("#dataControl").attr('rows').length;
        if ((rowCount) > 5) {
            $(this).parent().parent().remove();
            var dId = this.id;
            dId     = dId.split('_');
            //delete from db
            $.ajax(
                {
                    type:'POST',
                    url:'<?php echo base_url().'index.php/xo_booking/deleteRecord/'.$this->uri->segment(3);?>',
                    data:{subCode:dId[1]},
                    dataType:'html',
                    beforeSend:function () {
                        //$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                    },
                    success:function (response) {
                    }
                });
        }//end if
        else {
            $('#del2_' + rowCount).attr('disabled', true);
        }//end if
    });

    $('#txtSegment').click(function (e) {
        var rowCount = $("#dataControl").attr('rows').length;
        $('#txtSegment').val(rowCount);
    });

    $('.txtFcTax').keyup(function () {
        var id    = this.title;
        netVendor(id);

    });
    
    $('.chkTotal').click(function (e) {
        var id    = this.title;
        var nVal1 = ($('#txtFc1Tax' + id).val() != '' ? $('#txtFc1Tax' + id).val() : 0);
        var nVal2 = ($('#txtFc2Tax' + id).val() != '' ? $('#txtFc2Tax' + id).val() : 0);
        var nVal3 = ($('#txtFc3Tax' + id).val() != '' ? $('#txtFc3Tax' + id).val() : 0);
        var nTotal = parseFloat(nVal1) + parseFloat(nVal2) + parseFloat(nVal3);
        $('#txtTotalTax' + id).val(nTotal);
        netVendor(id);
        totalTax();
    });

    $('.chkNet').click(function () {
        var id    = this.title;
        var nVal  = ($('#txtTotalTax' + id).val() != '' ? $('#txtTotalTax' + id).val() : 0);
        var nVal1 = ($('#txtFcTax' + id).val() != '' ? $('#txtFcTax' + id).val() : 0);
        var nVal2 = ($('#txtORCAmt' + id).val() != '' ? $('#txtORCAmt' + id).val() : 0);
        var netTotal = parseFloat(nVal) + parseFloat(nVal1) - parseFloat(nVal2);
        $('#txtNetVendor' + id).val(netTotal);
    });

    $('.txtCommTax').keyup(function () {
        var idVal = this.id;
        var id    = idVal.split('txtCommTax');
        var nVal  = ($('#txtFcTax' + id[1]).val() != '' ? $('#txtFcTax' + id[1]).val() : 0);
        var nVal2 = ($('#txtCommTax' + id[1]).val() != '' ? $('#txtCommTax' + id[1]).val() : 0);
        var netTotal = (parseFloat(nVal2/100)*parseFloat(nVal));
        $('#txtORCAmt' + id[1]).val(netTotal);
    });
/*
    $('#txtDiscount').keyup(function(){
        var nVal  = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2  = ($('#txtDiscount').val() != '' ? $('#txtDiscount').val() : 0);
        var DiscAmt = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtDiscountAmt').val(DiscAmt);
    });

    $('#txtCollected').click(function(){
        var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2   = ($('#txtDiscountAmt').val() != '' ? $('#txtDiscountAmt').val() : 0);
		var nVal3   = ($('#txtServiceAmt').val() != '' ? $('#txtServiceAmt').val() : 0);
		var nVal4   = ($('#txtAgentAmt').val() != '' ? $('#txtAgentAmt').val() : 0);
        var collAmt = (parseFloat(nVal)-parseFloat(nVal2)-parseFloat(nVal4))+parseFloat(nVal3);
        $('#txtCollected').val(collAmt);
    });
*/
    $('#txtSalesAmt').keyup(function(){
            accountValues();
    });
/*
    $('#txtServicePerc').keyup(function(){
        var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2   = ($('#txtServicePerc').val() != '' ? $('#txtServicePerc').val() : 0);
        var servAmt = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtServiceAmt').val(servAmt);
    });
*/
    $('.txtORCAmt').keyup(function () {
        var txtTotal = 0;
        $('.txtORCAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtFareTotalAmount').val(txtTotal);
    });

    $('.txtFc1Tax').keyup(function () {
        var txtTotal = 0;
        $('.txtFc1Tax').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);

        });
        $('#txtTax1TotalAmount').val(txtTotal);

		//calculate tax total
		var id = this.id;
		id	   = id.split("txtFc1Tax");
		id     = id[1];
		
		var val1   = ($('#txtFc1Tax'+id).val() != '')?$('#txtFc1Tax'+id).val():0;
		var val2   = ($('#txtFc2Tax'+id).val() != '')?$('#txtFc2Tax'+id).val():0;
		var val3   = ($('#txtFc3Tax'+id).val() != '')?$('#txtFc3Tax'+id).val():0;

		var total = parseFloat(val1) + parseFloat(val2) + parseFloat(val3);
		$('#txtTotalTax'+id).val(total);
        netVendor(id);
        totalTax();
    });

    $('.txtFc2Tax').keyup(function () {
        var txtTotal = 0;
        $('.txtFc2Tax').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTax2TotalAmount').val(txtTotal);

		//calculate tax total
		var id = this.id;
		id	   = id.split("txtFc2Tax");
		id     = id[1];
		
		var val1   = ($('#txtFc1Tax'+id).val() != '')?$('#txtFc1Tax'+id).val():0;
		var val2   = ($('#txtFc2Tax'+id).val() != '')?$('#txtFc2Tax'+id).val():0;
		var val3   = ($('#txtFc3Tax'+id).val() != '')?$('#txtFc3Tax'+id).val():0;
		
		var total = parseFloat(val1) + parseFloat(val2) + parseFloat(val3);
		$('#txtTotalTax'+id).val(total);
        netVendor(id);
        totalTax();

		
    });

    $('.txtFc3Tax').keyup(function () {
        var txtTotal = 0;
        $('.txtFc3Tax').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtTax3TotalAmount').val(txtTotal);

		//calculate tax total
		var id = this.id;
		id	   = id.split("txtFc3Tax");
		id     = id[1];
		
		var val1   = ($('#txtFc1Tax'+id).val() != '')?$('#txtFc1Tax'+id).val():0;
		var val2   = ($('#txtFc2Tax'+id).val() != '')?$('#txtFc2Tax'+id).val():0;
		var val3   = ($('#txtFc3Tax'+id).val() != '')?$('#txtFc3Tax'+id).val():0;
		
		var total = parseFloat(val1) + parseFloat(val2) + parseFloat(val3);
		$('#txtTotalTax'+id).val(total);
        netVendor(id);
        totalTax();

		
    });

    $('.txtTotalTax').click(function () {
        totalTax();
    });

    $('.txtNetVendor').click(function () {
            totalNetVendor();
    });

    $('.txtORCAmt').keyup(function () {
        var txtTotal = 0;
        $('.txtORCAmt').each(function () { //Changed to class selector
            var nVal2 = $(this).val();

            if (nVal2 == '') {
                nVal2 = 0;
            }
            txtTotal += parseFloat(nVal2);
        });
        $('#txtFareTotalAmount').val(txtTotal);
    });

	$('.chkSegment').keyup(function(e) {
		$('#txtSegment').val(getSegmentTotal());
    });

	$('.chkDay').keyup(function(e) {
		$('#txtDestination').val(getSectorVal());
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
		var sec1 = $('#txtSector21').val();
		var sec2 = $('#txtSector22').val();
		var sec3 = $('#txtSector23').val();
		var sec4 = $('#txtSector24').val();
		
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
/*
    $('#txtDiscount').keyup(function(){
        var nVal  = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2 = ($('#txtDiscount').val() != '' ? $('#txtDiscount').val() : 0);
        var Amt   = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtDiscountAmt').val(Amt);
    });

    $('#txtCollected').click(function(){
        var nVal  = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2 = ($('#txtDiscountAmt').val() != '' ? $('#txtDiscountAmt').val() : 0);
        var Amt   = parseFloat(nVal)-parseFloat(nVal2);
        $('#txtCollected').val(Amt);
    });
*/
    $('#txtCopyXo').click(function()
	{
		var nVal  = $('#txtCopyXo').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{txtCopyXo:nVal},
			 url:'<?php echo base_url().'index.php/xo_booking/checkXoDetails/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#copyref').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#copyref').html(response);
				}
		  });
	});

    <?php if($this->uri->segment(4) == 'a')
    {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:$('#ddlDivision').val(),ddlTktname:0},
        url:'<?php echo base_url().'index.php/xo_booking/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
        }
    });

    <?php }//end if
    else {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:'<?php echo $this->ddlDivision;?>',ddlLocation:'<?php echo $this->ddlLocation;?>',ddlTktname:'<?php echo $this->ddlTktname;?>'},
        url:'<?php echo base_url().'index.php/xo_booking/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
        }
    });
    <?php }//end else?>

    $('#ddlDivision').change(function()
    {
        var nVal  = $('#ddlDivision').val();
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/xo_booking/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#loc').html(response);
            }
        });
    });

    $('#ddlLocation').change(function(e) {
        var nVal  = $('#ddlLocation').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlLocation:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/getStock/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#locStock').html(response);
                }
            });
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
				url:'<?php echo base_url().'index.php/xo_booking/getStock/'.$this->uri->segment(3);?>',
				dataType:'html',
				beforeSend: function() {
					$('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#locStock').html(response);
				}
			});
	<?php }//end if?>

    $('#ddlAccount').change(function(e) {
        var nVal  = $('#ddlAccount').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAccount:nVal},
                url:'<?php echo base_url().'index.php/xo_booking/getAccountHead/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#accHead').html(response);
                }
            });
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


	$('#ddlTktname').change(function(e) {
		var sVal  = $('#ddlTktname').val();
		//var sVal2 = $("#ddlTktname option[value='"+sVal+"']").text();
		sVal     = sVal.split("-");
		
		$('#txtTktName').val(sVal[1]);
    });

    <?php if($this->uri->segment(4) == 'a'){?>
        var m = $('#dataTable tbody>tr.re:last').clone(true).find("input").each(function () {
            var id = this.id;
            $(this).attr({
                'id':function (_, id2) {
                    return id + i;
                },
                'value':''
            });
        }).end().insertAfter('#dataTable tbody>tr.re:last');
        i++;

        var rowCount = $("#dataTable").attr('rows').length;
        m.find('img[class="del"]').attr('src', '<?php echo base_url();?>static/images/cross.png');
    <?php } ?> 

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
echo validation_errors('<p class="errorClass">', '</p>');
$attributes = array('name' => 'frmCountry', 'id' => 'frmValidate');
if (isset($error_value) && trim($error_value) != '') {
    ?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open('xo_booking/editXobooking/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6), $attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nCId', $this->catId);?>
<?php echo form_hidden('nCheck', 'frmPost');?>
<?php echo form_hidden('idArray', $chkAccID);?>
<?php echo form_hidden('idPArray', $chkPassID);?>
<?php echo form_hidden('idAGTArray', $this->ddlAgentAc);?>
<div id="copyref">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
    <tr>
        <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
        <td><label>
            <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
        </label></td>
    
        <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
        <td><label><span id="loc">
                 <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
                </span>
        </label></td>
       <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtDate" id="txtDate" class="textfield4" type="text" value="<?php echo $this->txtDate;?>">
        </label></td>
     </tr>
    
    <tr>
    
        <td><?php echo DOCNUMBER;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
            <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text" value="<?php echo $this->txtDocNumber;?>">
        </label></td>
    
        <td><?php echo STATUS;?> <span class="warning">*</span></td>
        <td><label>
            <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType, 'id="ddlType"');?>
        </label></td>
        <td></td>
        <td></td>
    </tr>
    
    <tr>
        <td><?php echo GDS;?> <!-- <span class="warning">*</span> --> </td>
        <td><label>
            <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS, 'id="ddlGDS"');?>
        </label></td>

        <td><?php echo XONAME;?> <span class="warning">*</span></td>
        <td>
        <label>
			<?php echo form_dropdown('ddlAirline', $list_airline_value, $this->ddlAirline,'id="ddlAirline" class="singleDrop"');?></label>&nbsp;<label>
        </label>
            <span id="locStock">
                <?php echo form_dropdown('ddlTktname', $list_tkt_value, $this->ddlTktname,'id="ddlTktname" class="multipleDrop"');?>
            </span>
            <input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" />
        </td>
       <td><?php echo ORGXO;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtOriginalXo" id="txtOriginalXo" class="textfield4 chkSuggestion" type="text" value="<?php echo stripslashes($this->txtOriginalXo);?>">
        </label></td>
        <td></td>
        <td></td>
    </tr>

    <tr>
 
        <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
        <td><label>
            <?php if(!empty($this->ddlIssued)){
                       echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued,'id="ddlIssued" class="multipleDrop"');
                       } else {
                              $login_id = $this->session->userdata('AbsAdmin');
                              $emp_id= $this->common_model->get_field_name('Admin','nEmpId'," WHERE nId = '".$login_id."' ");
                              echo form_dropdown('ddlIssued', $list_emp_value, $emp_id,'id="ddlIssued" class="multipleDrop"');
                       }
          ?>
        </label></td>
    
        <td><?php echo BOOKEDBY;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
            <?php  if(!empty($this->ddlBooked)){
                        echo form_dropdown('ddlBooked', $list_emp_value, $this->ddlBooked,'id="ddlBooked" class="multipleDrop"');
                       } else {
                              $login_id = $this->session->userdata('AbsAdmin');
                              $emp_id= $this->common_model->get_field_name('Admin','nEmpId'," WHERE nId = '".$login_id."' ");
                              echo form_dropdown('ddlBooked', $list_emp_value,$emp_id ,'id="ddlBooked" class="multipleDrop"');
                       }
             ?>
        </label></td>
    </tr>

    <tr>
        <td><?php echo GDSPNR;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtGdsPNR" id="txtGdsPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtGdsPNR);?>">
        </label></td>
    
        <td><?php echo AIRPNR;?> <span class="warning">*</span></td>
        <td><label>
            <input name="txtAirPNR" id="txtAirPNR" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAirPNR);?>">
        </label></td>

        <td><?php echo COPYXO;?></td>
        <td><label>
        <input name="txtCopyXo" id="txtCopyXo" class="textfield4 chkSuggestion" type="text" value="<?php echo stripslashes($this->txtCopyXo);?>">
        </label></td>
        <td colspan="6"></td>
    </tr>
       
    <tr>
        <td colspan="8"><h6><?php echo PASSDETAILS?></h6></td>
    </tr>
    
    <tr>
        <td colspan="8">
            <table id="dataControl" title="dataControl">
                <tr>
                    <td colspan="11"></td>
                </tr>
                <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow2" class="addNew"/>
                <tr>
                    <td><?php echo SRNO;?></td>
                    <td><?php echo PASSENGER;?></td>
                    <td><?php echo FACEFARE;?></td>
                    <td><?php echo COMM;?></td>
                    <td><?php echo COMMAMT;?></td>
                    <td><?php echo FCTAX;?></td>
                    <td><?php echo FCTAX;?></td>
                    <td><?php echo FCTAX;?></td>
                    <td><?php echo TOTALTAX;?></td>
                    <td><?php echo NETFARE;?></td>
                    <td> </td>
                </tr>
              
                <?php
                if (!empty($ddlPassenger)){
                    foreach ($ddlPassenger as $key1 => $pitem):
                        ?>
                        <tr class="re">
                            <td>#</td>
    
                            <td><label>
                                <input name="ddlPassenger[]" id="ddlPassenger" type="text" value="<?php echo stripslashes($ddlPassenger[$key1]);?>">
                            </label></td>
    
                            <td><label>
                                <input name="txtFcTax[]" id="txtFcTax" class="textfield5 txtFcTax txtNetCalc" type="text" value="<?php echo stripslashes($txtFcTax[$key1]);?>" data="txtFcTax">
                            </label></td>
    
                            <td><label>
                                <input name="txtCommTax[]" id="txtCommTax" class="textfield5 txtCommTax txtNetCalc" type="text" value="<?php echo stripslashes($txtCommTax[$key1]);?>" data="txtCommTax">
                            </label></td>
    
                            <td><label>
                                <input name="txtORCAmt[]" id="txtORCAmt" class="textfield5 txtORCAmt" type="text" value="<?php echo stripslashes($txtORCAmt[$key1]);?>" data="txtORCAmt">
                            </label></td>
    
                            <td><label>
                                <input name="txtFc1Tax[]" id="txtFc1Tax" class="textfield5 txtFc1Tax chkFC1Tax txtNetCalc" type="text" value="<?php echo stripslashes($txtFc1Tax[$key1]);?>" data="txtFc1Tax"> 
                            </label></td>
    
                            <td><label>
                                <input name="txtFc2Tax[]" id="txtFc2Tax" class="textfield5 txtFc2Tax chkFC2Tax txtNetCalc" type="text" value="<?php echo stripslashes($txtFc2Tax[$key1]);?>" data="txtFc2Tax">
                            </label></td>
    
                            <td><label>
                                <input name="txtFc3Tax[]" id="txtFc3Tax" class="chkTax txtFc3Tax chkFC3Tax txtNetCalc" type="text" value="<?php echo stripslashes($txtFc3Tax[$key1]);?>" title="" data="txtFc3Tax">
                            </label></td>
    
                            <td><label>
                                <input name="txtTotalTax[]" id="txtTotalTax" class="chkTotal txtTotalTax" type="text" value="<?php echo stripslashes($txtTotalTax[$key1]);?>" title="" data="txtTotalTax">
                            </label></td>
    
                            <td><label>
                                <input name="txtNetVendor[]" id="txtNetVendor" class="textfield5 txtNetVendor chkNet" title="" data="txtNetVendor" 
                                type="text" value="<?php echo stripslashes($txtNetVendor[$key1]);?>">
                            </label></td>
    
                            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_<?php echo $chkPassID[$key1];?>" title="Delete"/></td>
                            <td> </td>
                        </tr>
                        <?php
                    endforeach;
                }//end if
                else {
                    ?>
                    <tr class="re">
                        <td>#</td>
    
                        <td><label>
                            <input name="ddlPassenger[]" id="ddlPassenger" type="text" value="<?php echo stripslashes($ddlPassenger[$key1]);?>">
                        </label></td>
    
                        <td><label>
                            <input name="txtFcTax[]" id="txtFcTax" class="textfield5 txtFcTax txtNetCalc" type="text" value=""  data="txtFcTax">
                        </label></td>
    
                        <td><label>
                            <input name="txtCommTax[]" id="txtCommTax" class="textfield5 txtCommTax txtNetCalc" type="text" value="" data="txtCommTax">
                        </label></td>
    
                        <td><label>
                            <input name="txtORCAmt[]" id="txtORCAmt" class="textfield5 txtORCAmt" type="text" value="" data="txtORCAmt">
                        </label></td>
    
                        <td><label>
                            <input name="txtFc1Tax[]" id="txtFc1Tax" class="textfield5 txtFc1Tax chkFC1Tax txtNetCalc" type="text" value="" data="txtFc1Tax">
                        </label></td>
    
                        <td><label>
                            <input name="txtFc2Tax[]" id="txtFc2Tax" class="textfield5 txtFc2Tax chkFC2Tax txtNetCalc" type="text" value="" data="txtFc2Tax">
                        </label></td>
    
                        <td><label>
                            <input name="txtFc3Tax[]" id="txtFc3Tax" class="chkTax txtFc3Tax chkFC3Tax txtNetCalc" type="text" value="" title="" data="txtFc3Tax">
                        </label></td>
    
                        <td><label>
                            <input name="txtTotalTax[]" id="txtTotalTax" class="chkTotal txtTotalTax" type="text" value="" title="" data="txtTotalTax">
                        </label></td>
    
                        <td><label>
                            <input name="txtNetVendor[]" id="txtNetVendor" class="textfield5 txtNetVendor chkNet" title="" data="txtNetVendor" type="text" value="">
                        </label></td>
    
                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del2" id="del_<?php echo $key1;?>" title="Delete"/></td>
                        <td> </td>
                    </tr>
                    <?php
                }//end else
                ?>
                <tr>
                    <td colspan="3"></td>
                    <td><?php echo GRANDTOTAL;?></td>
                    <td><input name="txtFareTotalAmount" class="textfield5" id="txtFareTotalAmount" type="text" value="<?php echo $this->txtFareTotalAmount;?>"></td>
                    <td><input name="txtTax1TotalAmount" class="textfield5" id="txtTax1TotalAmount" type="text" value="<?php echo $this->txtTax1TotalAmount;?>"></td>
                    <td><input name="txtTax2TotalAmount" class="textfield5" id="txtTax2TotalAmount" type="text" value="<?php echo $this->txtTax2TotalAmount;?>"></td>
                    <td><input name="txtTax3TotalAmount" class="textfield5" id="txtTax3TotalAmount" type="text" value="<?php echo $this->txtTax3TotalAmount;?>"></td>
                    <td><input name="txtTotalTaxAmount" class="textfield5" id="txtTotalTaxAmount" type="text" value="<?php echo $this->txtTotalTaxAmount;?>"></td>
                    <td><input name="txtTotalNetVendorAmount" class="textfield5" id="txtTotalNetVendorAmount" type="text" value="<?php echo $this->txtTotalNetVendorAmount;?>"></td>
                    <td></td>
                </tr>

                <tr>
                    <td><?php echo ACCCODENAME;?></td>
                    <td><label>
                        <?php echo form_dropdown('ddlAccount', $list_account_value, $this->ddlAccount, 'id="ddlAccount"');?>
                    </label></td>

                   <td><?php echo ACCHEAD;?></td>
                   <td><label>
                   <span id="accHead">
					<?php echo form_dropdown('ddlAccHead', $list_acchead_value, $this->ddlAccHead,'id="ddlAccHead"');?>
                    </span>
                   </label></td>
<!--
                    <td><?php echo NETFARE;?></td> -->
                    <td><input name="txtTotalNetVendor1" class="textfield5" id="txtTotalNetVendor1" type="hidden" value="<?php echo $this->txtTotalNetVendor1;?>"> </td>
                    <td></td>
                </tr>

            </table>
        </td>
    </tr>    
   
    <tr>
        <td colspan="8"><h6><?php echo ACDETAILS?></h6></td>
    </tr>

    <tr>
    <td colspan="8"> 
    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <td><?php echo CUSTACCNAME;?></td>
    <td><label>
    <span id="accCust">
    <?php echo form_dropdown('ddlCustomerAc', $list_custacc_value, $this->ddlCustomerAc,'id="ddlCustomerAc"');?>
    </span>
    </label></td>

            <td><?php echo SALESAMT;?></td>
            <td><label>
             <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSalesAmt);?>">
            </label></td>
 
            <td><?php echo SERVICEPERCENT;?></td>
            <td><label>
            <input name="txtServicePerc" id="txtServicePerc" class="textfield5" type="text" value="<?php echo stripslashes($this->txtServicePerc);?>">
            </label></td>
        
            <td><?php echo SERVICEAMT;?></td>
            <td><label>
            <input name="txtServiceAmt" id="txtServiceAmt" class="textfield5" type="text" value="<?php echo stripslashes($this->txtServiceAmt);?>">
            </label></td>
 
    </table>
    </td>
    </tr>


    
<!--    <tr>
        <td colspan="8"><a href="#" class="topopup" id="3"><span style="font-size: 12px;font-weight: bold;"><?php echo PASSENGERCONTACT;?></span></a></td>
    </tr>
-->
    <tr>
        <td colspan="8"><br><h6><?php echo XODETAILS?></h6></td>
    </tr>

    <tr>
        <td><?php echo SEGMENT;?></td>
        <td><label>
            <input name="txtSegment" id="txtSegment" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSegment);?>">
        </label></td>
    
        <td><?php echo DESTINATION;?>&nbsp;<span class="warning">*</span></td>
        <td colspan="5"><label>
            <input name="txtDestination" id="txtDestination" class="textfield3" type="text" value="<?php echo stripslashes($this->txtDestination);?>">
        </label></td>
    </tr>
   
    <tr>
        <td colspan="8">
            <table id="dataTable" title="dataTable">
                <tr>
                    <td colspan="11"></td>
                </tr>
                <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew"/>
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
                    <td colspan="8"><?php echo DAY;?></td>
                </tr>
                <?php
                if (!empty($txtCarrier)) 
				{
                    foreach ($txtCarrier as $key => $aitem):
                        ?>
                        <tr class="re">
                            <td>#</td>
                            <td><input name="txtCarrier[]" id="txtCarrier<?php echo $key+1;?>" class="chkSegment" type="text"
                                       value="<?php echo stripslashes($aitem);?>"></td>
                            <td><input name="txtFlightNo[]" id="txtFlightNo" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtFlightNo[$key]);?>"></td>
                            <td><input name="txtClass[]" id="txtClass" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtClass[$key]);?>"></td>
                            <td><input name="txtTravelDate[]" id="txtTravelDate" class="textfield4 txtTravelDate" type="text"
                                       value="<?php echo stripslashes($txtTravelDate[$key]);?>"></td>
                            <td><input name="txtSector[]" id="txtSector" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtSector[$key]);?>"></td>
                            <td><input name="txtSector2[]" id="txtSector2<?php echo $key+1;?>" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtSector2[$key]);?>"></td>
                            <td><input name="txtStatus[]" id="txtStatus" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtStatus[$key]);?>"></td>
                            <td><input name="txtTimeDep[]" id="txtTimeDep<?php echo $key+1;?>" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtTimeDep[$key]);?>"></td>
                            <td><input name="txtTimeArr[]" id="txtTimeArr<?php echo $key+1;?>" class="textfield4" type="text"
                                       value="<?php echo stripslashes($txtTimeArr[$key]);?>"></td>
                            <td><input name="txtDay[]" id="txtDay" class="chkDay" type="text"
                                       value="<?php echo stripslashes($txtDay[$key]);?>"></td>
                            <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkAccID[$key];?>" title="Delete"/></td>
                        </tr>
                        <?php
                    endforeach;
                }//end if
                else 
				{?>
                    <tr class="re">
                        <td>#</td>
                        <td><input name="txtCarrier[]" id="txtCarrier" class="chkSegment" type="text" value=""></td>
                        <td><input name="txtFlightNo[]" id="txtFlightNo" class="textfield4" type="text" value=""></td>
                        <td><input name="txtClass[]" id="txtClass" class="textfield4" type="text" value=""></td>
                        <td><input name="txtTravelDate[]" id="txtTravelDate" class="textfield24 txtTravelDate" type="text" value=""></td>
                        <td><input name="txtSector[]" id="txtSector" class="textfield4" type="text" value=""></td>
                        <td><input name="txtSector2[]" id="txtSector2" class="textfield4" type="text" value=""></td>
                        <td><input name="txtStatus[]" id="txtStatus" class="textfield4" type="text" value=""></td>
                        <td><input name="txtTimeDep[]" id="txtTimeDep" class="textfield4" type="text" value=""></td>
                        <td><input name="txtTimeArr[]" id="txtTimeArr" class="textfield4" type="text" value=""></td>
                        <td><input name="txtDay[]" id="txtDay" class="chkDay" type="text" value=""></td>
                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $key;?>" title="Delete"/></td>
                     </tr>
               <?php }//end else
                ?>
            </table>
        </td>
    </tr>
    
    <tr>
        <td><?php echo REMARKS;?>&nbsp;<span class="warning">*</span></td>
        <td  colspan="4"><label>
            <textarea name="txtRemarks" id="txtRemarks" rows="7" cols="12"><?php echo stripslashes($this->txtRemarks);?></textarea>
        </label></td>
        <td colspan="3"></td>
    </tr>
    
    <tr>
        <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
        <td colspan="7"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;</label> <label><input
            type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
    </tr>
<!--
    <tr>
        <td colspan="8"><a href="#" class="topopup" id="2"><span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span></a></td>
    </tr>
-->
    <?php if($this->uri->segment(4) == 'e'){?>
    <tr>
        <td>
            <label><?php echo SALESCLOSINGSTATUS?></label>
        </td>
        <td>
            <label>
                <input type="text" value="<?php if($close_status->nXId !=''){ echo OPEN; } else { echo CLOSED; } ?>" readonly="readonly">
            </label>
        </td>
        <td>
            <?php if($close_status->nXId ==''){?>
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
                <input type="text" value="<?php if($refund_status->nXId !=''){ echo OPEN; } else { echo CLOSED; } ?>"  readonly="readonly">
            </label>
        </td>
        <td>
            <?php if($refund_status->nXId ==''){?>
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
        <td colspan="7"><label>

            <?php if($this->uri->segment(4) == 'a'):?>
            <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
            <?php else:?>
            <?php if($close_status->nXId !='' || trim($this->session->userdata('backURL')) != '' || $this->uri->segment(6) == 'closed'){  ?>
                <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
                <?php }
            else{ ?>
                <input type="button" name="btnClosed" id="btnClosed" value="<?php echo CLOSED;?>" disabled="disabled"/>
                <?php } ?>
            <?php endif;?>

            <?php if ($this->uri->segment(4) == 'a') { ?>
            <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>"/>
            <?php }//end if?>
            <input type="reset" value="<?php echo RESET;?>"/>
            <input type="button" name="btnCancel" value="<?php echo BACKTOXOBOOKING;?>" id="btnCancel">
        </label>
            <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
    </tr>
</tbody>
</table>

<!-- Start of 2 Sticky Note
<div id="toPopup2" class="toPopup"> 
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
    <?php echo $this->load->view('xo_booking/show_acdetails');?>
    </div> 
</div>
 End of Sticky Note -->
  </div>

    <!-- Start of 2 Sticky Note -->
    <div id="toPopup3" class="toPopup"> 
        <div class="close"></div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup_content"> <!--your content start-->
        <?php echo $this->load->view('xo_booking/show_passdetails');?>
        </div> <!--your content end-->
    </div>
    <!-- End of Sticky Note -->

</form>
</div>
