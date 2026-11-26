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
// +----------------------------------------------------------------------|
?>
<script language="javascript1.1" type="text/javascript">
var status = false;
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
        $('#'+this.id).autocomplete('<?php echo base_url();?>index.php/refund_register/suggestionlist', {
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
        }).result(function(e, item)
            {
                var nId  = this.id;
                $.ajax(
                    {
                        type:'POST',
                        url:'<?php echo base_url().'index.php/refund_register/getCashreceiptDetails/'.$this->uri->segment(3);?>',
                        data:{txtAcccode:format(item)},
                        dataType:'html',
                        beforeSend: function() {
                            $('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                        },
                        success: function(response) {
                            $('#txtAccname'+nId).val(response);
                        }
                    });
            });
    });
});

function getLocation(nVal) {
    $.ajax({
        type:'POST',
        data:{ddlDivision:nVal},
        url:'<?php echo base_url().'index.php/refund_register/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
		async:false,
        beforeSend: function() {
            $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            //alert(response)
            $('#loc').html(response);
			status = true;

            var nVal1 = $('#ddlType').val();
            var nVal2 = $('#ddlLocation').val();
            $.ajax({
                type:'POST',
                data:{ddlType:nVal1,ddlLocation:nVal2},
                url:'<?php echo base_url().'index.php/refund_register/generateDocNo/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#txtDocNumber').val(response);
                }
            });

        }
    });

}

function changeTktType(id){

        $.ajax({
            type:'POST',
            url:'<?php echo base_url().'index.php/refund_register/getTktType/'.$this->uri->segment(3);?>',
            data:{},
            dataType:'html',
            beforeSend: function() {
                $('#ddlTktType'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                //alert(response);
                $('#ddlTktType'+id).html(response);
            }
        });
} //end function


function changeNewOld(id){
        $.ajax({
            type:'POST',
            url:'<?php echo base_url().'index.php/refund_register/getNewOld/'.$this->uri->segment(3);?>',
            data:{},
            dataType:'html',
            beforeSend: function() {
                $('#ddlYesNo'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                //alert(response);
                $('#ddlYesNo'+id).html(response);
            }
        });
} //end function

function changeTicket(id){
		var curVal = $('#ddlTktType'+id).val();
        var loc  = $('#ddlLocation').val();
        var div = $('#ddlDivision').val();

        $.ajax({
            type:'POST',
            url:'<?php echo base_url().'index.php/refund_register/getTktNumber/'.$this->uri->segment(3);?>',
            data:{ddlTktType:curVal,ddlLocation:loc,ddlDivision:div},
            dataType:'html',
            beforeSend: function() {
                $('#ddlTkt'+id).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                //alert(response);
                $('#ddlTkt'+id).html(response);
            }
        });
}


function changeAllAccount(){
     var loc  = $('#ddlLocation').val();
     var div = $('#ddlDivision').val();
     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_register/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAccApId').val();
                 $('#ddlAccApId').html(response);
                 $('#ddlAccApId').val(acc);
         }
     });

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_register/getAllAccount_Subcode/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAccountClose').val();
                 $('#ddlAccountClose').html(response);
                 $('#ddlAccountClose').val(acc);
         }
     });


     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_register/getVendorAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAccount').val();
                 $('#ddlAccount').html(response);
                 $('#ddlAccount').val(acc);
         }
     });

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/refund_register/getAgentAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAgentAc').val();
                 $('#ddlAgentAc').html(response);
                 $('#ddlAgentAc').val(acc);
         }
    });

    $('.ddlTktType').each(function(){
        var id     = this.id;
        var nVal   = $('#'+id).val();
        var getId  = id.split('ddlTktType');
        var editable = $("#txtEditable" + getId[1]).val();
        if(editable == 'Y')
            changeTicket(getId[1]);
    });


}


function changePaidTo(id){
        var salesAmt       = ($('#txtSaleAmt'+id).val() != '' ? $('#txtSaleAmt'+id).val() : 0);
        var nVendorAmt     = ($('#txtVendorAmt'+id).val() != '' ? $('#txtVendorAmt'+id).val() : 0);
        var nOCharge       = ($('#txtOCharge'+id).val() != '' ? $('#txtOCharge'+id).val() : 0);
        var nOthAdj3       = ($('#txtOthAdj3'+id).val() != '' ? $('#txtOthAdj3'+id).val() : 0);
        var nOthAdj        = ($('#txtOthAdj'+id).val() != '' ? $('#txtOthAdj'+id).val() : 0);
        var nUsedFare      = ($('#txtUsedFare'+id).val() != '' ? $('#txtUsedFare'+id).val() : 0);
        var nUsedTax       = ($('#txtUsedTax'+id).val() != '' ? $('#txtUsedTax'+id).val() : 0);


        var netTotal       = (parseFloat(salesAmt) - parseFloat(nVendorAmt) - parseFloat(nOCharge) + parseFloat(nOthAdj3) - parseFloat(nOthAdj) - parseFloat(nUsedFare) - parseFloat(nUsedTax));
        $('#txtPaidTo'+id).val(netTotal);
        $('#txtMainPaidTo'+id).val(netTotal);

        var refund         = $('#txtNetAmt2'+id).val();
        var service        = parseFloat(refund)-parseFloat(netTotal);
        var servPercent    = ((parseFloat(service)/parseFloat(netTotal))*100);
        $('#txtServicePer'+id).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+id).val(service);

}

function onChangetxtComm2(id){

		var netAmt1  	   = ($('#txtNetAmt1'+id).val() != '' ? $('#txtNetAmt1'+id).val() : 0);
//        var nFare     	   = ($('#txtUsedFare'+id).val() != '' ? $('#txtUsedFare'+id).val() : 0);
//        var nTax    	   = ($('#txtUsedTax'+id).val() != '' ? $('#txtUsedTax'+id).val() : 0);
        var nComm    	   = ($('#txtComm2'+id).val() != '' ? $('#txtComm2'+id).val() : 0);
        var nCreditAmt     = ($('#txtCreditAmt'+id).val() != '' ? $('#txtCreditAmt'+id).val() : 0);
        var nUsedFare      = ($('#txtUsedFare'+id).val() != '' ? $('#txtUsedFare'+id).val() : 0);
        var nUsedTax       = ($('#txtUsedTax'+id).val() != '' ? $('#txtUsedTax'+id).val() : 0);
		var nVendor    	   = ($('#txtVendorAmt'+id).val() != '' ? $('#txtVendorAmt'+id).val() : 0);

        var netTotal 	   = (parseFloat(netAmt1) - parseFloat(nComm) - parseFloat(nCreditAmt) - parseFloat(nUsedFare) - parseFloat(nUsedTax) - parseFloat(nVendor));

        $('#txtNetAmt2'+id).val(netTotal);
        $('#txtMainNetAmt'+id).val(netTotal);

        var paidTo         = $('#txtPaidTo'+id).val();
        var service        = parseFloat(netTotal)-parseFloat(paidTo);
        var servPercent    = ((parseFloat(service)/parseFloat(paidTo))*100);
        $('#txtServicePer'+id).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+id).val(service);
}

$(document).ready(function(){

    $("ul.pgcontent").quickPagination({pageSize:"5"});

    $('#btnCancel').click(function(){
        window.location.replace('<?php echo base_url().'index.php/refund_register/showlist/'.$this->uri->segment(3);?>');
        return false;
    });

    $("#txtDate").datepick({ dateFormat:'dd-mm-yyyy'});
    $("#txtTravelDate").datepick({ dateFormat:'dd-mm-yyyy'});
    $(".txtTravelDate").datepick({ dateFormat:'dd-mm-yyyy'});
    $("#txtSdt").datepick({ dateFormat:'dd-mm-yyyy'});
    $("#txtRdt").datepick({ dateFormat:'dd-mm-yyyy'});

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

    // validate signup form on keyup and submit
    $("#frmValidate").validate({
        rules: {
            ddlType: {
                selectNone : true
            },
            ddlLedger: {
                selectNone : true
            },
            ddlDivision: {
                selectNone : true
            },
            ddlLocation: {
                selectNone : true
            },
            txtDocNumber: {
                required : true
            },
            txtPassenger: {
                required : true
            },
            'txtMainPaidTo[]': {
                required : true
            }
        },
        messages: {
            ddlType: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlLedger: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlDivision: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlLocation: {
                selectNone : "<?php echo PLEASESELECTANOPTION;?>"
            },
            txtDocNumber: {
                required : "<?php echo CANTBEBLANK;?>"
            },
            txtPassenger: {
                required : "<?php echo TO;?><?php echo CANTBEBLANK;?>"
            },
            'txtMainPaidTo[]': {
                required : "<?php echo CANTBEBLANK;?>"
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

    /*------------------Custom Add new row for account------------------*/
    var i = 1;
    $("#addRow").click(function() {
        var m	 = $('#dataTable > tbody>tr:last').clone(true).find("input").each(function() {
            var id = this.id;
            $(this).attr({
                'id': function(_, id2) { return id + i; },
                'value': ''
            });
        }).end().find("select").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
                
                var nVal1 = -1;
                var nVal2 = -1;
                var nVal3 = -1;
                nVal1 = id.indexOf('ddlTktType');
                nVal2 = id.indexOf('ddlYesNo');
                nVal3 = id.indexOf('ddlTkt');

                if(nVal1 != -1 && nVal3 != -1){
                    var getId  = id.split('ddlTktType');
                    changeTktType(getId[1]+i);
                } //endif 
                if(nVal2 != -1){
//                    var nVal4 = $(this).attr.id;
                    var getId  = id.split('ddlYesNo');
                    changeNewOld(getId[1]+i);
                } //endif
                if(nVal3 != -1 && nVal1 == -1){
                    //var nVal4 = $(this).attr.id;
                    var getId  = id.split('ddlTkt');
                    changeTicket(getId[1]+i);
                } //endif

            }).end().find("a").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
            }).end().find("div.toPopup").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
//            }).end().insertAfter('#dataTable > tbody>tr:last');
    }).end().find("img").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': 'del'
                });

            }).end().insertAfter('#dataTable > tbody>tr:last');
        i++;

        var rowCount = $("#dataTable").attr('rows').length;
        m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
        return false;
    });

    /*------------------Custom delete row ------------------*/

    $('.del').live('click',function(){
        var rowCount = $("#dataTable").attr('rows').length;
        if((rowCount)>2)
        {
            $(this).parent().parent().remove();
            //delete from the db
            var dId = this.id;
            dId     = dId.split('_');
            //delete from db
            $.ajax(
                {
                    type:'POST',
                    url:'<?php echo base_url().'index.php/refund_register/deleteRecord/'.$this->uri->segment(3);?>',
                    data:{subCode:dId[1]},
                    dataType:'html',
                    beforeSend: function() {
                        //$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                    },
                    success: function(response) {
                    }
                });

        }//end if
        else
        {
            //var src = $(this).attr("src").replace("cross", "cross_grey_small");

            $('#del_'+rowCount).attr('disabled', true);
            // $(this).attr("src", src);

        }//end if
    });

    $('#ddlDivision').change(function () {
        var nVal = $('#ddlDivision').val();
        getLocation(nVal);
        changeAllAccount();
    });

    $('#loc').change(function () {
            changeAllAccount();
    });

	<?php if($this->uri->segment(4) == 'a'):?>
		getLocation($('#ddlDivision').val());
		if(status == true)
		{
			var nVal1 = $('#ddlType').val();
			var nVal2 = $('#ddlLocation').val();
			$.ajax({
				type:'POST',
				data:{ddlType:nVal1,ddlLocation:nVal2},
				url:'<?php echo base_url().'index.php/refund_register/generateDocNo/'.$this->uri->segment(3);?>',
				dataType:'html',
				beforeSend:function () {
					$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success:function (response) {
					$('#txtDocNumber').val(response);
				}
			});
		}//end if
    <?php endif;?>
	
    changeAllAccount();

    $('#ddlCountry').change(function () {
        var nVal = $('#ddlCountry').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlCountry:nVal},
                url:'<?php echo base_url().'index.php/refund_register/filterCity/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    $('#ddlCity').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#ddlCity').html(response);
                }
            });
    });

	<?php if($this->uri->segment(4) == 'e'):?>
		var nVal = $('#ddlCurrency').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlCurrency:nVal},
                url:'<?php echo base_url().'index.php/refund_register/generateCurrRate/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#txtRate').val(response);
                }
            });

		$('.ddlYesNo').each(function(index) {
		 	var id     = this.id;
			var getId  = id.split('ddlYesNo');
			var nVal   = $('#'+id).val();
			if(nVal == 'O')
			{
				$('#txtTkt'+getId[1]).show();
				$('#ddlTkt'+getId[1]).hide();
			}//end if
			else
			{
				$('#txtTkt'+getId[1]).hide();
				$('#ddlTkt'+getId[1]).show();
			}//end else
		});
	<?php endif;?>

    $('#ddlCurrency').change(function () {
        var nVal = $('#ddlCurrency').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlCurrency:nVal},
                url:'<?php echo base_url().'index.php/refund_register/generateCurrRate/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#txtRate').val(response);
                }
            });
    });

	$('.ddlTktType').change(function(){
		var id     = this.id;
		var nVal   = $('#'+id).val();
		var getId  = id.split('ddlTktType');
        changeTicket(getId[1]);
	});

    $('#txtSegment').click(function (e) {
        var rowCount = $("#dataControl").attr('rows').length;
        $('#txtSegment').val(rowCount);
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

	$('.txtFare').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtFare');
        var nVal     	   = ($('#txtFare'+getId[1]).val() != '' ? $('#txtFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtTax'+getId[1]).val() != '' ? $('#txtTax'+getId[1]).val() : 0);
//        var nVal3    	   = ($('#txtComm1'+getId[1]).val() != '' ? $('#txtComm1'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtOtherAmt'+getId[1]).val() != '' ? $('#txtOtherAmt'+getId[1]).val() : 0);
        var netTotal 	   = parseFloat(nVal) + parseFloat(nVal2) + parseFloat(nVal4);
        $('#txtNetAmt1'+getId[1]).val(netTotal);

        onChangetxtComm2(getId[1]);
/*
        var paidTo         = $('#txtPaidTo'+getId[1]).val();
        var service        = parseFloat(netTotal)-parseFloat(paidTo);
        var servPercent    = ((parseFloat(service)/parseFloat(paidTo))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
*/
	});

	$('.txtTax').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtTax');
        var nVal     	   = ($('#txtFare'+getId[1]).val() != '' ? $('#txtFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtTax'+getId[1]).val() != '' ? $('#txtTax'+getId[1]).val() : 0);
//        var nVal3    	   = ($('#txtComm1'+getId[1]).val() != '' ? $('#txtComm1'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtOtherAmt'+getId[1]).val() != '' ? $('#txtOtherAmt'+getId[1]).val() : 0);
        var netTotal 	   = parseFloat(nVal) + parseFloat(nVal2) + parseFloat(nVal4);
        $('#txtNetAmt1'+getId[1]).val(netTotal);

        onChangetxtComm2(getId[1]);

	});
/*
	$('.txtComm1').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtComm1');
        var nVal     	   = ($('#txtFare'+getId[1]).val() != '' ? $('#txtFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtTax'+getId[1]).val() != '' ? $('#txtTax'+getId[1]).val() : 0);
        var nVal3    	   = ($('#txtComm1'+getId[1]).val() != '' ? $('#txtComm1'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtOtherAmt'+getId[1]).val() != '' ? $('#txtOtherAmt'+getId[1]).val() : 0);
        var netTotal 	   = parseFloat(nVal) + parseFloat(nVal2) + parseFloat(nVal3) + parseFloat(nVal4);
        $('#txtNetAmt1'+getId[1]).val(netTotal);
		$('#txtNetAmt2'+getId[1]).val(netTotal);
		$('#txtMainNetAmt'+getId[1]).val(netTotal);

        var paidTo         = $('#txtPaidTo'+getId[1]).val();
        var service        = parseFloat(netTotal)-parseFloat(paidTo);
        var servPercent    = ((parseFloat(service)/parseFloat(paidTo))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
	});
*/
    $('.txtOtherAmt').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtOtherAmt');
        var nVal     	   = ($('#txtFare'+getId[1]).val() != '' ? $('#txtFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtTax'+getId[1]).val() != '' ? $('#txtTax'+getId[1]).val() : 0);
//        var nVal3    	   = ($('#txtComm1'+getId[1]).val() != '' ? $('#txtComm1'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtOtherAmt'+getId[1]).val() != '' ? $('#txtOtherAmt'+getId[1]).val() : 0);
        var netTotal 	   = parseFloat(nVal) + parseFloat(nVal2) + parseFloat(nVal4);
        $('#txtNetAmt1'+getId[1]).val(netTotal);
        onChangetxtComm2(getId[1]);

    });

    $('.txtUsedFare').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtUsedFare');
        onChangetxtComm2(getId[1]);
        changePaidTo(getId[1]);

   });

    $('.txtUsedTax').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtUsedTax');
        onChangetxtComm2(getId[1]);
        changePaidTo(getId[1]);
   });

    $('.txtComm2').keyup(function () {

        var id             = this.id;
        var getId          = id.split('txtComm2');
        onChangetxtComm2(getId[1]);

   });

    $('.txtVendorAmt').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtVendorAmt');
        onChangetxtComm2(getId[1]);
        changePaidTo(getId[1]);


/*		var netAmt1  	   = ($('#txtNetAmt1'+getId[1]).val() != '' ? $('#txtNetAmt1'+getId[1]).val() : 0);
        var nFare     	   = ($('#txtUsedFare'+getId[1]).val() != '' ? $('#txtUsedFare'+getId[1]).val() : 0);
        var nTax    	   = ($('#txtUsedTax'+getId[1]).val() != '' ? $('#txtUsedTax'+getId[1]).val() : 0);
        var nComm    	   = ($('#txtComm2'+getId[1]).val() != '' ? $('#txtComm2'+getId[1]).val() : 0);
		var nVendor    	   = ($('#txtVendorAmt'+getId[1]).val() != '' ? $('#txtVendorAmt'+getId[1]).val() : 0);
        var netTotal 	   = (parseFloat(netAmt1) - parseFloat(nFare) - parseFloat(nTax) - parseFloat(nComm) - parseFloat(nVendor));
        $('#txtNetAmt2'+getId[1]).val(netTotal);
        $('#txtMainNetAmt'+getId[1]).val(netTotal);

        var paidTo         = $('#txtPaidTo'+getId[1]).val();
        var service        = parseFloat(netTotal)-parseFloat(paidTo);
        var servPercent    = ((parseFloat(service)/parseFloat(paidTo))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
*/
   });

    $('.txtSaleAmt').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtSaleAmt');
        changePaidTo(getId[1]);
	});

    $('.txtOCharge').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtOCharge');

        changePaidTo(getId[1]);


/*        var nVal     	   = ($('#txtUsedFare'+getId[1]).val() != '' ? $('#txtUsedFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtUsedTax'+getId[1]).val() != '' ? $('#txtUsedTax'+getId[1]).val() : 0);
        var nVal3    	   = ($('#txtComm2'+getId[1]).val() != '' ? $('#txtComm2'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtVendorAmt'+getId[1]).val() != '' ? $('#txtVendorAmt'+getId[1]).val() : 0);

	    var salesAmt       = ($('#txtSaleAmt'+getId[1]).val() != '' ? $('#txtSaleAmt'+getId[1]).val() : 0);
        var nVal5     	   = ($('#txtOCharge'+getId[1]).val() != '' ? $('#txtOCharge'+getId[1]).val() : 0);
        var nVal6    	   = ($('#txtOthAdj3'+getId[1]).val() != '' ? $('#txtOthAdj3'+getId[1]).val() : 0);
        var nVal7    	   = ($('#txtOthAdj'+getId[1]).val() != '' ? $('#txtOthAdj'+getId[1]).val() : 0);
        var netTotal 	   = (parseFloat(salesAmt) - parseFloat(nVal) - parseFloat(nVal2) - parseFloat(nVal3) - parseFloat(nVal4)) - (parseFloat(nVal5) - parseFloat(nVal6) + parseFloat(nVal7));
        $('#txtPaidTo'+getId[1]).val(netTotal);
        $('#txtMainPaidTo'+getId[1]).val(netTotal);
        var refund         = $('#txtNetAmt2'+getId[1]).val();
        var service        = parseFloat(refund)-parseFloat(netTotal);
        var servPercent    = ((parseFloat(service)/parseFloat(netTotal))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
*/
	});

    $('.txtOthAdj3').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtOthAdj3');
        changePaidTo(getId[1]);
/*
        var nVal     	   = ($('#txtUsedFare'+getId[1]).val() != '' ? $('#txtUsedFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtUsedTax'+getId[1]).val() != '' ? $('#txtUsedTax'+getId[1]).val() : 0);
        var nVal3    	   = ($('#txtComm2'+getId[1]).val() != '' ? $('#txtComm2'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtVendorAmt'+getId[1]).val() != '' ? $('#txtVendorAmt'+getId[1]).val() : 0);

	    var salesAmt       = ($('#txtSaleAmt'+getId[1]).val() != '' ? $('#txtSaleAmt'+getId[1]).val() : 0);
        var nVal5     	   = ($('#txtOCharge'+getId[1]).val() != '' ? $('#txtOCharge'+getId[1]).val() : 0);
        var nVal6    	   = ($('#txtOthAdj3'+getId[1]).val() != '' ? $('#txtOthAdj3'+getId[1]).val() : 0);
        var nVal7    	   = ($('#txtOthAdj'+getId[1]).val() != '' ? $('#txtOthAdj'+getId[1]).val() : 0);
        var netTotal 	   = (parseFloat(salesAmt) - parseFloat(nVal) - parseFloat(nVal2) - parseFloat(nVal3) - parseFloat(nVal4)) - (parseFloat(nVal5) - parseFloat(nVal6) + parseFloat(nVal7));
        $('#txtPaidTo'+getId[1]).val(netTotal);
        $('#txtMainPaidTo'+getId[1]).val(netTotal);
        var refund         = $('#txtNetAmt2'+getId[1]).val();
        var service        = parseFloat(refund)-parseFloat(netTotal);
        var servPercent    = ((parseFloat(service)/parseFloat(netTotal))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
*/
	});

    $('.txtOthAdj').keyup(function () {
        var id             = this.id;
        var getId          = id.split('txtOthAdj');
        changePaidTo(getId[1]);
/*
        var nVal     	   = ($('#txtUsedFare'+getId[1]).val() != '' ? $('#txtUsedFare'+getId[1]).val() : 0);
        var nVal2    	   = ($('#txtUsedTax'+getId[1]).val() != '' ? $('#txtUsedTax'+getId[1]).val() : 0);
        var nVal3    	   = ($('#txtComm2'+getId[1]).val() != '' ? $('#txtComm2'+getId[1]).val() : 0);
		var nVal4    	   = ($('#txtVendorAmt'+getId[1]).val() != '' ? $('#txtVendorAmt'+getId[1]).val() : 0);

	    var salesAmt       = ($('#txtSaleAmt'+getId[1]).val() != '' ? $('#txtSaleAmt'+getId[1]).val() : 0);
        var nVal5     	   = ($('#txtOCharge'+getId[1]).val() != '' ? $('#txtOCharge'+getId[1]).val() : 0);
        var nVal6    	   = ($('#txtOthAdj3'+getId[1]).val() != '' ? $('#txtOthAdj3'+getId[1]).val() : 0);
        var nVal7    	   = ($('#txtOthAdj'+getId[1]).val() != '' ? $('#txtOthAdj'+getId[1]).val() : 0);
        var netTotal 	   = (parseFloat(salesAmt) - parseFloat(nVal) - parseFloat(nVal2) - parseFloat(nVal3) - parseFloat(nVal4)) - (parseFloat(nVal5) - parseFloat(nVal6) + parseFloat(nVal7));
        $('#txtPaidTo'+getId[1]).val(netTotal);
        $('#txtMainPaidTo'+getId[1]).val(netTotal);
        var refund         = $('#txtNetAmt2'+getId[1]).val();
        var service        = parseFloat(refund)-parseFloat(netTotal);
        var servPercent    = ((parseFloat(service)/parseFloat(netTotal))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
*/
    });

    $('.ddlYesNo').change(function(){
        var id     = this.id;
		var getId  = id.split('ddlYesNo');
		var nVal   = $('#'+id).val();
        if(nVal == 'O')
		{
            $('#txtTkt'+getId[1]).show();
            $('#ddlTkt'+getId[1]).hide();
        }//end if
        else
		{
            $('#txtTkt'+getId[1]).hide();
            $('#ddlTkt'+getId[1]).show();
        }//end else
    });

    $('#txtTkt').hide();
    $('#ddlTkt').show();

    $('#ddlAccountClose').change(function(e) {
        var nVal  = $('#ddlAccountClose').val();
        $.ajax({
            type:'POST',
            data:{ddlAccountClose:nVal},
            url:'<?php echo base_url().'index.php/refund_register/getAccountHead/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#ddlAccountHead').html(response);
            }
        });
    });

    $('#ddlType').change(function () {
        var nVal = $('#ddlType').val();
        var nVal2 = $('#ddlLocation').val();
        $.ajax({
            type:'POST',
            data:{ddlType:nVal,ddlLocation:nVal2},
            url:'<?php echo base_url().'index.php/refund_register/generateDocNo/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend:function () {
                $('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success:function (response) {
                $('#txtDocNumber').val(response);
            }
        });
    });
         
    $('.ddlTkt').change(function(){
        var id      = this.id;
        var nVal    = $('#'+id).val();
        var getId   = id.split('ddlTkt');
        var tktVal  = $('#ddlTkt'+getId[1]).val();
        var typeVal = $('#ddlTktType'+getId[1]).val();
        $.ajax({
            type:'POST',
            url:'<?php echo base_url().'index.php/refund_register/getTktValues/'.$this->uri->segment(3);?>',
            data:{ddlTkt:tktVal,ddlTktType:typeVal},
            dataType:'html',
            success: function(response) {
                var obj = $.parseJSON(response);
                $('#txtORGTkt'+getId[1]).val(obj.OrgTkt);
                $('#txtPass'+getId[1]).val(obj.PassName);
                $('#txtPassenger').val(obj.PassName);
                $('#txtAirline'+getId[1]).val(obj.Airline);
                $('#txtRemarks'+getId[1]).val(obj.Remarks);
                $('#txtFare'+getId[1]).val(obj.Fare);
                $('#txtTax'+getId[1]).val(obj.Tax);
                $('#txtNetAmt'+getId[1]).val(obj.NetAmt);
                $('#txtServiceAmt2'+getId[1]).val(obj.ServAmt);
                $('#txtDiscAmt'+getId[1]).val(obj.DiscAmt);
                $('#txtComm2'+getId[1]).val(obj.CommAmt);
// aust
                $('#txtCreditAmt'+getId[1]).val(obj.CreditAmt);
                $('#txtSaleAmt'+getId[1]).val(obj.collected);
                $('#txtAgentAmt2'+getId[1]).val(obj.agentAmt);
                $('#txtSector1').val(obj.Sector1);
                $('#txtSector2').val(obj.Sector2);
                $('#txtSector3').val(obj.Sector3);
                $('#txtSector4').val(obj.Sector4);
                $('#txtSector5').val(obj.Sector5);
                $('#txtSegment').val(obj.Segment);
                $('#txtDestination').val(obj.Destination);
                $('#ddlGDS').val(obj.Gds);
                $('#ddlAccount').val(obj.Account);
                $('#ddlAccApId').val(obj.AccountApId);
                $('#ddlAccountClose').val(obj.AccountClose);
                $('#ddlAccountHead').val(obj.AccountHead);
                $('#ddlAgentAc').val(obj.AgentAcc);
                $('#txtSdt').val(obj.SalesDate);
                $('#ddlHotel').val(obj.hotel);
                $('#ddlCountry').val(obj.country);
                $('#ddlCity').val(obj.city);
                $('#ddlIssued').val(obj.Employee);

                $.ajax({
                    type:'POST',
                    data:{ddlAccountClose:obj.AccountClose},
                    url:'<?php echo base_url().'index.php/refund_register/getAccountHead/'.$this->uri->segment(3);?>',
                    dataType:'html',
                    success: function(response) {
                        $('#ddlAccountHead').html(response);
                    }
                });
                var nVal     	   = ($('#txtFare'+getId[1]).val() != '' ? $('#txtFare'+getId[1]).val() : 0);
                var nVal2    	   = ($('#txtTax'+getId[1]).val() != '' ? $('#txtTax'+getId[1]).val() : 0);
//                var nVal3    	   = ($('#txtComm1'+getId[1]).val() != '' ? $('#txtComm1'+getId[1]).val() : 0);
                var nVal4    	   = ($('#txtOtherAmt'+getId[1]).val() != '' ? $('#txtOtherAmt'+getId[1]).val() : 0);
                var netTotal 	   = parseFloat(nVal) + parseFloat(nVal2) + parseFloat(nVal4);
                $('#txtNetAmt1'+getId[1]).val(netTotal);

                onChangetxtComm2(getId[1]);
                changePaidTo(getId[1]);

/*                $('#txtNetAmt2'+getId[1]).val(netTotal);
                $('#txtMainNetAmt'+getId[1]).val(netTotal);

                var nVal1     	   = ($('#txtUsedFare'+getId[1]).val() != '' ? $('#txtUsedFare'+getId[1]).val() : 0);
                var nVal21    	   = ($('#txtUsedTax'+getId[1]).val() != '' ? $('#txtUsedTax'+getId[1]).val() : 0);
                var nVal31    	   = ($('#txtComm2'+getId[1]).val() != '' ? $('#txtComm2'+getId[1]).val() : 0);
                var nVal41    	   = ($('#txtVendorAmt'+getId[1]).val() != '' ? $('#txtVendorAmt'+getId[1]).val() : 0);

                var salesAmt       = ($('#txtSaleAmt'+getId[1]).val() != '' ? $('#txtSaleAmt'+getId[1]).val() : 0);
                var nVal51     	   = ($('#txtOCharge'+getId[1]).val() != '' ? $('#txtOCharge'+getId[1]).val() : 0);
                var nVal61    	   = ($('#txtOthAdj3'+getId[1]).val() != '' ? $('#txtOthAdj3'+getId[1]).val() : 0);
                var nVal71    	   = ($('#txtOthAdj'+getId[1]).val() != '' ? $('#txtOthAdj'+getId[1]).val() : 0);
                var netTotal2 	   = (parseFloat(salesAmt) - parseFloat(nVal1) - parseFloat(nVal21) - parseFloat(nVal31) - parseFloat(nVal41)) - (parseFloat(nVal51) - parseFloat(nVal61) + parseFloat(nVal71));

                if(netTotal2==0)
                    netTotal2 = '';
                $('#txtPaidTo'+getId[1]).val(netTotal2);
                $('#txtMainPaidTo'+getId[1]).val(netTotal2);
                var refund         = $('#txtNetAmt2'+getId[1]).val();
                var service        = parseFloat(refund)-parseFloat(netTotal2);
                var servPercent    = ((parseFloat(service)/parseFloat(netTotal2))*100);
                $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
                $('#txtServiceAmt'+getId[1]).val(service);
*/
            }
        });
    });
/*    $('.txtPaidTo').keyup(function(){
        var id      = this.id;
        var nVal    = $('#'+id).val();
        var getId   = id.split('txtPaidTo');
        $('#txtMainPaidTo'+getId[1]).val(nVal);
        var refund         = $('#txtNetAmt2'+getId[1]).val();
        var service        = parseFloat(refund)-parseFloat(netTotal);
        var servPercent    = ((parseFloat(service)/parseFloat(netTotal))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
    });

    $('.txtMainPaidTo').keyup(function(){
        var id      = this.id;
        var nVal    = $('#'+id).val();
        var getId   = id.split('txtMainPaidTo');
        $('#txtPaidTo'+getId[1]).val(nVal);
        var refund         = $('#txtNetAmt2'+getId[1]).val();
        var service        = parseFloat(refund)-parseFloat(netTotal);
        var servPercent    = ((parseFloat(service)/parseFloat(netTotal))*100);
        $('#txtServicePer'+getId[1]).val(servPercent.toFixed(3));
        $('#txtServiceAmt'+getId[1]).val(service);
    });
*/
    $('.txtVendorAmt').keyup();
});
</script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

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
<?php echo form_open('refund_register/editRefund/'.$this->uri->segment(3).'/'.$this->uri->segment(4), $attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('nCId', $this->catId);?>
<?php echo form_hidden('nCheck', 'frmPost');?>
<?php echo form_hidden('idArray', $chkAccID);?>
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
            </span></label></td>

    <td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtDate" id="txtDate" class="textfield4" type="text" value="<?php echo $this->txtDate;?>">
    </label></td>
</tr>

<tr>
    <td><?php echo TREFSTATUS;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType, 'id="ddlType"');?>
    </label></td>

    <td><?php echo DOCNUMBER;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtDocNumber" id="txtDocNumber" class="textfield" type="text" value="<?php echo $this->txtDocNumber;?>" size="20">
    </label></td>

</tr>

<tr>
    <td><?php echo TO;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtPassenger" id="txtPassenger" class="textfield" type="text" value="<?php echo stripslashes($this->txtPassenger);?>">
    </label></td>

    <td><?php echo CURRNAME;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency, 'id="ddlCurrency"');?>
    </label></td>

    <td><?php echo CURRRATE;?> <span class="warning">*</span></td>
    <td colspan="3"><label>
        <input name="txtRate" id="txtRate" class="textfield5" type="text" value="<?php echo $this->txtRate;?>" size="20">
    </label></td>
</tr>

<tr>
    <td colspan="10">
        <table id="dataTable" title="dataTable">
            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
            <tbody>
            <tr>
            	<td>&nbsp;</td>
                <td><?php echo REFTYPE;?></td>
                <td><?php echo NEWOLD;?></td>
                <td><?php echo AIRLINE;?></td>
                <td><?php echo TICKETNO;?></td>
                <td><?php echo ORGTKT;?></td>
                <td><?php echo PASSENGER;?></td>
                <td><?php echo REMARKS;?></td>
                <td><?php echo REFUNDAMT;?></td>
                <td><?php echo PAIDTO;?></td>
                <td>&nbsp;</td>
            </tr>
            <?php
            if (!empty($chkType)) {
                foreach($chkType as $key => $aitem):
                    ?>
                    <tr>
                        <td><input type="checkbox" checked="checked" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>"></td>

                        <td><label>
                            <?php if($chkType[$key] == 'Tkt') $chkTypeText = 'TICKET'; 
                            elseif($chkType[$key] == 'Htl') $chkTypeText = 'HOTEL';
                            else  $chkTypeText = 'XO';?>
                            <?php echo form_dropdown('ddlTktType[]', array($chkType[$key]=> $chkTypeText), $chkType[$key], "id='ddlTktType".$key."' class='singleDrop ddlTktType'");?>
                        </label></td>

                        <td><label>
                            <?php if($ddlYesNo[$key] == 'N') {
                                    $ddlYesNoText = 'NEW';
                                    switch ($chkType[$key])
                                    {
                                        case "Tkt":
                                            $ticket = $this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$chkTktNumber[$key]."'");
                                        break;

                                        case "Xo":
                                            $ticket = $this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$chkTktNumber[$key]."'");
                                        break;

                                        case "Htl":
                                            $ticket = $this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$chkTktNumber[$key]."'");
                                        break;
                                    }//end switch
                                }
                                else {  $ddlYesNoText = 'OLD'; $ticket = $chkTktNumber[$key];} ?>
                            <?php echo form_dropdown('ddlYesNo[]', array($ddlYesNo[$key] => $ddlYesNoText), $ddlYesNo[$key], "id='ddlYesNo".$key."' class='singleDrop ddlYesNo'");?>
                        </label></td>

                        <td><label>
                            <input name="txtAirline[]" id="txtAirline<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($txtAirline[$key]);?>">
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlTkt[]', array($chkTktNumber[$key] => $ticket), $chkTktNumber[$key],"id='ddlTkt".$key."' class='ddlTkt'");?>
                            <input type="text" name="txtTkt[]" id="txtTkt<?php echo $key;?>" value="<?php echo stripslashes($chkTktNumber[$key]);?>" />
                            <input type="hidden" name="txtEditable[]" id="txtEditable<?php echo $key;?>" value="N" />
                        </label></td>

                        <td><label>
                            <input name="txtORGTkt[]" id="txtORGTkt<?php echo $key;?>" class="textfield5" type="text" value="<?php echo stripslashes($txtORGTkt[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtPass[]" id="txtPass<?php echo $key;?>" class="textfield" type="text" value="<?php echo stripslashes($txtPass[$key]);?>">
                        </label></td>

                        <td><label>
                            <input name="txtRemarks[]" id="txtRemarks<?php echo $key;?>" class="textfield" type="text" value="<?php echo stripslashes($txtRemarks[$key]);?>" title="">
                        </label></td>

                        <td><label>
                            <input name="txtMainNetAmt[]" id="txtMainNetAmt<?php echo $key;?>" class="textfield5 txtMainNetAmt" type="text" value="<?php echo $chkNetAmt2[$key];?>">
                        </label></td>

                        <td colspan="5"><label>
                            <input name="txtMainPaidTo[]" id="txtMainPaidTo<?php echo $key;?>" class="textfield5 txtMainPaidTo" type="text" value="<?php echo $chkPaidTo[$key];?>">
                        </label></td>

                        <td><a href="#" class="topopup" id="<?php echo $key;?>"><span style="font-size: 12px;font-weight: bold;"><?php echo DETAILS;?></span></a></td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/>
                            <div id="toPopup<?php echo $key;?>" class="toPopup" style="top: 20%;">
                                <div class="close"></div>
                                <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
                                <div id="popup_content"> <!--your content start-->
                                <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
                                <tr>
                                    <td colspan="6"><h6><?php echo NETPAIDAMOUNT;?></h6></td>
                                </tr>

                                    <tr>
                                        <td colspan="6">
                                            <table>
                                                <tr>
                                                    <td><?php echo FARE;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtFare[]" id="txtFare<?php echo $key;?>" class="textfield5 txtFare" type="text" value="<?php echo $chkFare[$key];?>">
                                                    </label></td>

                                                    <td><?php echo USEDFARE;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtUsedFare[]" id="txtUsedFare<?php echo $key;?>" class="textfield5 txtUsedFare" type="text" value="<?php echo $chkUsedFare[$key];?>"></label></td>

                                                    <td><?php echo SALESAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtSaleAmt[]" id="txtSaleAmt<?php echo $key;?>" class="textfield5 txtSaleAmt" type="text" value="<?php echo $chkSaleAmt[$key];?>">
                                                    </label></td>

                                                    <td><?php echo SERAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtServiceAmt2[]" id="txtServiceAmt2<?php echo $key;?>" class="textfield5 txtServiceAmt2" type="text" value="<?php echo $chkServiceAmt2[$key];?>">
                                                    </label>
                                                    </td>

                                                    <td><?php echo COUNTRYNAME;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <?php echo form_dropdown('ddlCountry[]', $list_country, $chkCountry[$key],"id='ddlCountry'.$key");?>
                                                    </label></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo TAX;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtTax[]" id="txtTax<?php echo $key;?>" class="textfield5 txtTax" type="text" value="<?php echo $chkTax[$key];?>">
                                                    </label></td>

                                                    <td><?php echo USEDTAX;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtUsedTax[]" id="txtUsedTax<?php echo $key;?>" class="textfield5 txtUsedTax" type="text" value="<?php echo $chkUsedTax[$key];?>">
                                                    </label></td>

                                                    <td><?php echo OTHADJ2;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtOthAdj[]" id="txtOthAdj<?php echo $key;?>" class="textfield5 txtOthAdj" type="text" value="<?php echo $chkOthAdj[$key];?>"></label></td>

                                                    <td><?php echo DISCAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtDiscAmt[]" id="txtDiscAmt<?php echo $key;?>" class="textfield5 txtDiscAmt" type="text" value="<?php echo $chkDiscAmt[$key];?>">
                                                    </label></td>

                                                    <td><?php echo CITYNAME;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <?php echo form_dropdown('ddlCity[]', $list_city, $chkCity[$key],"id='ddlCity'.$key");?>
                                                    </label></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo CREDITAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtCreditAmt[]" id="txtCreditAmt<?php echo $key;?>" class="textfield5 txtCreditAmt" type="text" value="<?php echo $chkCreditAmt[$key];?>">
                                                        <input name="txtComm1[]" id="txtComm1<?php echo $key;?>" class="textfield5 txtComm1" type="hidden" value="<?php echo $chkComm1[$key];?>">
                                                    </label></td>

                                                    <td><?php echo COMMREFAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtComm2[]" id="txtComm2<?php echo $key;?>" class="textfield5 txtComm2" type="text" value="<?php echo $chkComm2[$key];?>">
                                                    </label></td>

                                                    <td><?php echo OTHADJ1;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtOthAdj3[]" id="txtOthAdj3<?php echo $key;?>" class="textfield5 txtOthAdj3" type="text" value="<?php echo $chkOthAdj3[$key];?>">
                                                    </label></td>

                                                    <td><?php echo OTHADJ;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtOthAdj2[]" id="txtOthAdj2<?php echo $key;?>" class="textfield5 txtOthAdj2" type="text" value="<?php echo $chkOthAdj2[$key];?>">
                                                    </label></td>

                                                    <td><?php echo HOTELNAME;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <?php echo form_dropdown('ddlHotel[]', $list_hotel, $chkHotel[$key],"id='ddlHotel'.$key");?>
                                                    </label></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo OTHER;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtOtherAmt[]" id="txtOtherAmt<?php echo $key;?>" class="textfield5 txtOtherAmt" type="text" value="<?php echo $chkOtherAmt[$key];?>"></label>
                                                        </label></td>

                                                    <td><?php echo VENDORNAME;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtVendorAmt[]" id="txtVendorAmt<?php echo $key;?>" class="textfield5 txtVendorAmt" type="text" value="<?php echo $chkVendorAmt[$key];?>"></label>
                                                        </label></td>

                                                    <td><?php echo OURCHARGE;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtOCharge[]" id="txtOCharge<?php echo $key;?>" class="textfield5 txtOCharge" type="text" value="<?php echo $chkOCharge[$key];?>">
                                                    </label></td>

                                                    <td><?php echo AGENTAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtAgentAmt2[]" id="txtAgentAmt2<?php echo $key;?>" class="textfield5 txtAgentAmt2" type="text" value="<?php echo $chkAgentAmt2[$key];?>">
                                                    </label></td>

                                                    <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <?php echo form_dropdown('ddlIssued[]', $list_emp_value, $chkIssued[$key], "id='ddlIssued'.$key");?>
                                                    </label></td>
                                                </tr>

                                                <tr>
                                                    <td><?php echo NETAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtNetAmt1[]" id="txtNetAmt1<?php echo $key;?>" class="textfield5 txtNetAmt1" type="text" value="<?php echo $chkNetAmt1[$key];?>" readonly="readonly">
                                                    </label></td>

                                                    <td><?php echo REFUNDAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtNetAmt2[]" id="txtNetAmt2<?php echo $key;?>" class="textfield5 txtNetAmt2" type="text" value="<?php echo $chkNetAmt2[$key];?>" readonly="readonly">
                                                    </label></td>

                                                    <td><?php echo PAIDTO;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtPaidTo[]" id="txtPaidTo<?php echo $key;?>" class="textfield5 txtPaidTo" type="text" value="<?php echo $chkPaidTo[$key];?>" readonly="readonly">
                                                    </label></td>

                                                    <td><?php echo SERAMT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtServiceAmt[]" id="txtServiceAmt<?php echo $key;?>" class="textfield5 txtServiceAmt" type="text" value="<?php echo $chkServiceAmt[$key];?>" readonly="readonly">
                                                    </label>
                                                    </td>

                                                    <td><?php echo SERVICEPERCENT;?> <span class="warning">*</span></td>
                                                    <td><label>
                                                        <input name="txtServicePer[]" id="txtServicePer<?php echo $key;?>" class="textfield5 txtServicePer" type="text" value="<?php echo $chkServicePer2[$key];?>" readonly="readonly">
                                                    </label>
                                                    </td>
                                                </tr>
                                            </table>
                                        </td>
                                    </tr>

                                </table>
                                </div> <!--your content end-->
                            </div>
                        </td>
                    </tr>
                    <?php
                endforeach;
            }//end if
            else
            {?>
                <tr>
                    <td><input type="checkbox" checked="checked" name="chkbox[]" id="chkSelect" class="chkSelect" value=""></td>
                    <td><label>
                        <?php echo form_dropdown('ddlTktType[]', $list_type, $this->ddlTktType, 'id="ddlTktType" class="singleDrop ddlTktType"');?>
                    </label></td>

                    <td><label>
                        <?php echo form_dropdown('ddlYesNo[]', $list_NewOld, $this->ddlYesNo, 'id="ddlYesNo" class="singleDrop ddlYesNo"');?>
                    </label></td>

                    <td><label>
                        <input name="txtAirline[]" id="txtAirline" class="textfield5" type="text">
                    </label></td>


                    <td><label>
                            <?php echo form_dropdown('ddlTkt[]', $list_tkt_value, $this->ddlTkt,'id="ddlTkt" class="ddlTkt"');?>
                            <input type="text" name="txtTkt[]" id="txtTkt" value="" />
                            <input type="hidden" name="txtEditable[]" id="txtEditable" value="Y" />
                    </label></td>

                    <td><label>
                        <input name="txtORGTkt[]" id="txtORGTkt" class="textfield5" type="text">
                    </label></td>

                    <td><label>
                        <input name="txtPass[]" id="txtPass" class="textfield" type="text">
                    </label></td>

                    <td><label>
                        <input name="txtRemarks[]" id="txtRemarks" class="textfield" type="text" title="">
                    </label></td>

                    <td><label>
                        <input name="txtMainNetAmt[]" id="txtMainNetAmt" class="textfield5 txtMainNetAmt" type="text">
                    </label></td>

                    <td colspan="5"><label>
                        <input name="txtMainPaidTo[]" id="txtMainPaidTo" class="textfield5 txtMainPaidTo" type="text">
                    </label></td>

                    <td><a href="#" class="topopup" id=""><span style="font-size: 12px;font-weight: bold;"><?php echo DETAILS;?></span></a></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_" title="Delete"/>
                        <div id="toPopup" class="toPopup" style="top: 20%;">
                            <div class="close"></div>
                            <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
                            <div id="popup_content"> <!--your content start-->
                            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
                            <tr>
                                <td colspan="6"><h6><?php echo NETPAIDAMOUNT;?></h6></td>
                            </tr>
                                <tr>
                                    <td colspan="6">
                                        <table>
                                            <tr>
                                                <td><?php echo FARE;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtFare[]" id="txtFare" class="textfield5 txtFare" type="text">
                                                </label></td>

                                                <td><?php echo USEDFARE;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtUsedFare[]" id="txtUsedFare" class="textfield5 txtUsedFare" type="text"></label></td>

                                                <td><?php echo SALESAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtSaleAmt[]" id="txtSaleAmt" class="textfield5 txtSaleAmt" type="text">
                                                </label></td>

                                                <td><?php echo SERAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtServiceAmt2[]" id="txtServiceAmt2" class="textfield5 txtServiceAmt2" type="text"></label>
                                                </td>

                                                <td><?php echo COUNTRYNAME;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <?php echo form_dropdown('ddlCountry[]', $list_country, $this->ddlCountry, 'id="ddlCountry"');?>
                                                </label></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo TAX;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtTax[]" id="txtTax" class="textfield5 txtTax" type="text">
                                                </label></td>

                                                <td><?php echo USEDTAX;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtUsedTax[]" id="txtUsedTax" class="textfield5 txtUsedTax" type="text">
                                                </label></td>

                                                <td><?php echo OTHADJ2;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtOthAdj[]" id="txtOthAdj" class="textfield5 txtOthAdj" type="text"></label>
                                                </td>

                                                <td><?php echo DISCAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtDiscAmt[]" id="txtDiscAmt" class="textfield5 txtDiscAmt" type="text">
                                                </label></td>

                                                <td><?php echo CITYNAME;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <?php echo form_dropdown('ddlCity[]', $list_city, $this->ddlCity, 'id="ddlCity"');?>
                                                </label></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo CREDITAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtComm1[]" id="txtComm1" class="textfield5 txtComm1" type="hidden">
                                                    <input name="txtCreditAmt[]" id="txtCreditAmt" class="textfield5 txtCreditAmt" type="text">
                                                </label></td>

                                                <td><?php echo COMMREFAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtComm2[]" id="txtComm2" class="textfield5 txtComm2" type="text">
                                                </label></td>

                                                <td><?php echo OTHADJ1;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtOthAdj3[]" id="txtOthAdj3" class="textfield5 txtOthAdj3" type="text">
                                                </label></td>

                                                <td><?php echo OTHADJ;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtOthAdj2[]" id="txtOthAdj2" class="textfield5 txtOthAdj2" type="text">
                                                </label></td>

                                                <td><?php echo HOTELNAME;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <?php echo form_dropdown('ddlHotel[]', $list_hotel, $this->ddlHotel, 'id="ddlHotel"');?>
                                                </label></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo OTHER;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtOtherAmt[]" id="txtOtherAmt" class="textfield5 txtOtherAmt" type="text"></label>
                                                    </label></td>

                                                <td><?php echo VENDORNAME;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtVendorAmt[]" id="txtVendorAmt" class="textfield5 txtVendorAmt" type="text"></label>
                                                    </label></td>

                                                <td><?php echo OURCHARGE;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtOCharge[]" id="txtOCharge" class="textfield5 txtOCharge" type="text">
                                                </label></td>

                                                <td><?php echo AGENTAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtAgentAmt2[]" id="txtAgentAmt2" class="textfield5 txtAgentAmt2" type="text">
                                                </label></td>

                                                <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <?php echo form_dropdown('ddlIssued[]', $list_emp_value, $this->ddlIssued, 'id="ddlIssued"');?>
                                                </label></td>
                                            </tr>

                                            <tr>
                                                <td><?php echo NETAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtNetAmt1[]" id="txtNetAmt1" class="textfield5 txtNetAmt1" type="text" readonly="readonly">
                                                </label></td>

                                                <td><?php echo REFUNDAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtNetAmt2[]" id="txtNetAmt2" class="textfield5 txtNetAmt2" type="text" readonly="readonly">
                                                </label></td>

                                                <td><?php echo PAIDTO;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtPaidTo[]" id="txtPaidTo" class="textfield5 txtPaidTo" type="text" readonly="readonly">
                                                </label></td>

                                                <td><?php echo SERAMT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtServiceAmt[]" id="txtServiceAmt" class="textfield5 txtServiceAmt" type="text" readonly="readonly">
                                                </label>
                                                </td>

                                                <td><?php echo SERVICEPERCENT;?> <span class="warning">*</span></td>
                                                <td><label>
                                                    <input name="txtServicePer[]" id="txtServicePer" class="textfield5 txtServicePer" type="text" readonly="readonly"> 
                                                </label>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            </div> <!--your content end-->
                        </div>
                    </td>

                </tr>
                <?php
            }//end else
            ?>
            </tbody>
        </table>
    </td>
</tr>

<tr>
    <td><?php echo ACCCODENAME;?></td>
    <td><label>
        <?php echo form_dropdown('ddlAccount', $list_account_value, $this->ddlAccount, 'id="ddlAccount"');?>
    </label></td>

    <td><?php echo MODEOFPAYMENT;?> <span class="warning">*</span></td>
    <td><label>
        <label><?php echo form_dropdown('ddlMode', $list_mode_value, $this->ddlMode, 'id="ddlMode" class="singleDrop"');?></label>
    </label></td>

	<td><?php echo ACAPID;?></td>
    <td colspan="3"><label>
		<?php echo form_dropdown('ddlAccApId', $list_accountapid_value, $this->ddlAccApId, 'id="ddlAccApId"');?>
        <?php /*?><input name="txtAcAPID" id="txtAcAPID" class="textfield3" type="text" value="<?php echo stripslashes($this->txtAcAPID);?>"><?php */?>
    </label></td>
</tr>

<tr>
	<td><?php echo SECTOR;?></td>
    <td><label>
        <input name="txtSector1" id="txtSector1" class="textfield4 txtSector1" type="text" value="<?php echo $this->txtSector1;?>">
    </label></td>
    <td><label>
        <input name="txtSector2" id="txtSector2" class="textfield4 txtSector2" type="text" value="<?php echo $this->txtSector2;?>">
    </label></td>
    <td><label>
        <input name="txtSector3" id="txtSector3" class="textfield4 txtSector3" type="text" value="<?php echo $this->txtSector3;?>">
    </label></td>
    <td><label>
        <input name="txtSector4" id="txtSector4" class="textfield4 txtSector4" type="text" value="<?php echo $this->txtSector4;?>">
    </label></td>
    <td><label>
        <input name="txtSector5" id="txtSector5" class="textfield4 txtSector5" type="text" value="<?php echo $this->txtSector5;?>">
    </label></td>

    <td><?php echo GDS;?> <span class="warning">*</span></td>
    <td><label>
        <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS, 'id="ddlGDS" class="singleDrop"');?>
    </label></td>
</tr>

<tr>
	<td><?php echo SEGMENT;?></td>
    <td><label>
        <input name="txtSegment" id="txtSegment" class="textfield3" type="text" value="<?php echo stripslashes($this->txtSegment);?>">
    </label></td>

    <td><?php echo DESTINATION;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtDestination" id="txtDestination" class="textfield3" type="text" value="<?php echo stripslashes($this->txtDestination);?>">
    </label></td>
</tr>

<tr>
    <td><?php echo SALESDT;?></td>
    <td><label>
        <input name="txtSdt" id="txtSdt" class="textfield4" type="text" value="<?php echo stripslashes($this->txtSdt);?>">
    </label></td>

    <td><?php echo REFUNDDT;?> <span class="warning">*</span></td>
    <td><label>
        <input name="txtRdt" id="txtRdt" class="textfield4" type="text" value="<?php echo stripslashes($this->txtRdt);?>">
    </label></td>
</tr>


<tr>
    <td colspan="6"><h6><?php echo BILLINGDETAILS ?></h6></td>
</tr>

<tr>
    <td colspan="6">
        <table>
            <tr>
                <td><?php echo MODEOFPAYMENT;?> <span class="warning">*</span></td>
                <td><label>
                    <label><?php echo form_dropdown('ddlMode2', $list_mode_value, $this->ddlMode2, 'id="ddlMode2" class="singleDrop"');?></label>
                </label></td>

                <td><?php echo SUBCODE;?> <span class="warning">*</span></td>
                <td><label>
					<?php echo form_dropdown('ddlAccountClose', $list_custaccount_value, $this->ddlAccountClose, 'id="ddlAccountClose"');?>
                </label></td>

                <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
                <td><label>
					<?php echo form_dropdown('ddlAccountHead', $list_accounthead, $this->ddlAccountHead, 'id="ddlAccountHead"');?>
                </label></td>
            </tr>

            <tr>
                <td><?php echo AGENTAC;?></td>
                <td>
                    <label><?php echo form_dropdown('ddlAgentAc', $list_agentacc_value, $this->ddlAgentAc, 'id="ddlAgentAc"');?></label>
                </td>

                <td><?php echo LPO;?></td>
                <td colspan="5"><label><input name="txtLPO" id="txtLPO" class="textfield4" type="text" value="<?php echo stripslashes($this->txtLPO);?>"></label></td>
            </tr>

        </table>
    </td>
</tr>

<tr>
    <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
    <td colspan="5"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;
        <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9"><label>
        <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>"/>
        <?php if ($this->uri->segment(4) == 'a') { ?>
        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>"/>
        <?php }//end if?>
        <input type="reset" value="<?php echo RESET;?>"/>
        <input type="button" name="btnCancel" value="<?php echo BACKTOREFUND;?>" id="btnCancel">
    </label>
        <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
</tr>
</tbody>
</table>

</form>
</div>
