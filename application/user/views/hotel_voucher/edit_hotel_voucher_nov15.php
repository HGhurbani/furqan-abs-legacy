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

//before removing payment popup
?>
<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});

function getRateFcTotal()
{
    var txtTotal = 0;
    $('.rateNetFc').each(function () { //Changed to class selector
        var nVal2 = $(this).val();

        if (nVal2 == '') {
            nVal2 = 0;
        }
        txtTotal += parseFloat(nVal2);
    });
    $('#txtRateNetFcTotal').val(txtTotal);
}//end function

function getRateDcTotal()
{
    var txtTotal = 0;
    $('.rateNetDc').each(function () { //Changed to class selector
        var nVal2 = $(this).val();

        if (nVal2 == '') {
            nVal2 = 0;
        }
        txtTotal += parseFloat(nVal2);
    });
    $('#txtRateNetDcTotal').val(txtTotal);
}//end function

function getFcTotal()
{
    var txtTotal = 0;
    $('.totalFc').each(function () { //Changed to class selector
        var nVal2 = $(this).val();

        if (nVal2 == '') {
            nVal2 = 0;
        }
        txtTotal += parseFloat(nVal2);
    });
    $('#txtTotalFc').val(txtTotal);
}//end function

function getDcTotal()
{
    var txtTotal = 0;
    $('.totalDc').each(function () { //Changed to class selector
        var nVal2 = $(this).val();

        if (nVal2 == '') {
            nVal2 = 0;
        }
        txtTotal += parseFloat(nVal2);
    });
    $('#txtTotalDc').val(txtTotal);
}//end function

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/hotel_voucher/showlist/'.$this->uri->segment(3);?>');
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
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			 ddlDivision: {
					selectNone : true
					},
			 ddlLocation: {
					selectNone : true
					},
			 ddlTrans: {
				 	selectNone : true
			 		},
			 ddlAirline: {
					selectNone : true
					},
			 ddlGDS: {
					selectNone : true
					},
			 ddlBooked: {
					selectNone : true
					},
			 ddlIssued: {
					selectNone : true
					},
			 ddlPassenger: {
					selectNone : true
					},
			 ddlCountry: {
					selectNone : true
					},
			 ddlCity: {
					selectNone : true
					}
		},
		messages: {
			ddlDivision: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlLocation: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlTrans: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlAirline: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
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
			ddlPassenger: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlCountry: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			ddlCity: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
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
	  m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
	  return false;
	});
	
/*------------------Custom delete row ------------------*/	
	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if((rowCount)>3)
		{
			$(this).parent().parent().remove();
		    //delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/hotel_voucher/deleteRecord/'.$this->uri->segment(3);?>',
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
			$('#del_'+rowCount).attr('disabled', true);
		}//end if
	});
	
    $('#ddlTrans').change(function()
	{
        var nVal  = $('#ddlTrans').val();
        $.ajax({
            type:'POST',
            data:{ddlTrans:nVal},
            url:'<?php echo base_url().'index.php/hotel_voucher/generateDocNo/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                //alert(response)
                $('#txtDocNumber').val(response);
            }
        });
	});

    $('#txtCopyTkt').click(function()
	{
        var nVal  = $('#txtCopyTkt').val();
        $.ajax({
            type:'POST',
            data:{txtCopyTkt:nVal},
            url:'<?php echo base_url().'index.php/hotel_voucher/checkHotelDetails/'.$this->uri->segment(3);?>',
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

    $('#ddlCountry').change(function()
	{
		var nVal  = $('#ddlCountry').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCountry:nVal},
			 url:'<?php echo base_url().'index.php/hotel_voucher/filterCountry/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#city').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#city').html(response);
				}
		  });
	});

   // $(document).on('change','#ddlCity', function()
   $('#ddlCity').live('change',function(){
        var nCountry = $('#ddlCountry').val();
        var nCity    = $('#ddlCity').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlCountry:nCountry,ddlCity:nCity},
                url:'<?php echo base_url().'index.php/hotel_voucher/filterHotel/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#hotel').html(response);
                }
            });
    });

    $('#ddlCurrency').change(function()
	{
		var nVal  = $('#ddlCurrency').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCurrency:nVal},
			 url:'<?php echo base_url().'index.php/hotel_voucher/generateCurrRate/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					//$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					//alert(response)
					$('#txtRate').val(response);
					$('.txtExchangeRate').val(response);
				}
		  });
	});

    $('#ddlAcCode').change(function()
    {
        var nVal  = $('#ddlAcCode').val();
        $.ajax({
            type:'POST',
            data:{ddlAcCode:nVal},
            url:'<?php echo base_url().'index.php/hotel_voucher/getVendorCurrRate/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                //$('#txtDocNumber').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                var obj = jQuery.parseJSON(response);
                $('#ddlCurrency').val(obj.currency);
                $('#txtRate').val(obj.rateVal);
                $('.txtExchangeRate').val(obj.rateVal);
            }
        });
    });

    $('#txtDiscount').keyup(function(){
        var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2   = ($('#txtDiscount').val() != '' ? $('#txtDiscount').val() : 0);
        var DiscAmt = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtDiscountAmt').val(DiscAmt);
    });

    $('#txtServicePerc').keyup(function(){
        var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2   = ($('#txtServicePerc').val() != '' ? $('#txtServicePerc').val() : 0);
        var servAmt = parseFloat(nVal2/100)*parseFloat(nVal);
        $('#txtServiceAmt').val(servAmt);
    });

    $('#txtCollected').click(function(){
        var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);
        var nVal2   = ($('#txtDiscountAmt').val() != '' ? $('#txtDiscountAmt').val() : 0);
		var nVal3   = ($('#txtServiceAmt').val() != '' ? $('#txtServiceAmt').val() : 0);
		var nVal4   = ($('#txtAgentAmt').val() != '' ? $('#txtAgentAmt').val() : 0);
        var collAmt = (parseFloat(nVal)-parseFloat(nVal2)-parseFloat(nVal4))+parseFloat(nVal3);
        $('#txtCollected').val(collAmt);
    });

    $('.rateNetFc').keyup(function () {

        var id       = this.id;
        var getId    = id.split('txtRateNetFc');
        var fcAmt    = ($('#txtRateNetFc' + getId[1]).val() != '' ? $('#txtRateNetFc'+getId[1]).val() : 0);
        var nytCount = ($('#txtNight'+getId[1]).val() != '' ? $('#txtNight'+getId[1]).val() : 0);
        var rooms    = ($('#txtNoofRooms'+getId[1]).val() != '' ? $('#txtNoofRooms'+getId[1]).val() : 0);
        var exrate   = ($('#txtExchangeRate'+getId[1]).val() != '' ? $('#txtExchangeRate'+getId[1]).val() : 0);

        var totalFc  = (parseFloat(fcAmt)*parseFloat(nytCount)*parseFloat(rooms));
        var rateDc   = (parseFloat(fcAmt)*parseFloat(exrate));
        var totalDc  = (parseFloat(rateDc)*parseFloat(nytCount)*parseFloat(rooms));

        $('#txtTotalinFc'+getId[1]).val(totalFc);
        $('#txtRateNetDc'+getId[1]).val(rateDc);
        $('#txtTotalinDc'+getId[1]).val(totalDc);

        getRateFcTotal();
        getFcTotal();
        getRateDcTotal();
        getDcTotal();
    });
	
    $('.totalFc').keyup(function () {
        getFcTotal();
    });
	
    $('.rateNetDc').keyup(function () {
        getRateDcTotal();
    });
	
    $('.totalDc').keyup(function () {
        getDcTotal();
    });

    <?php if($this->uri->segment(4) == 'a')
    {?>
    $.ajax({
        type:'POST',
        data:{ddlDivision:$('#ddlDivision').val(),ddlTktname:0},
        url:'<?php echo base_url().'index.php/hotel_voucher/filterDivision/'.$this->uri->segment(3);?>',
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
        url:'<?php echo base_url().'index.php/hotel_voucher/filterDivision/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/hotel_voucher/filterDivision/'.$this->uri->segment(3);?>',
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
                url:'<?php echo base_url().'index.php/hotel_voucher/getStock/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#locStock').html(response);
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

    $('#txtNights').click(function() {
        var sVal  = $('#txtCheckIn').val();
        var sVal2 = $('#txtCheckOut').val();
        var start = new Date(sVal);
        var end = new Date(sVal2);
        var diff = new Date(end - start);
        var days = diff/1000/60/60/24;
        $('#txtNights').val(days);
    });

    $('.txtNight').click(function() {
        var dId = this.id;
        var idVal = dId.split('txtNight');

        var sVal  = $('#txtChkinDate'+idVal[1]).val();
        var sVal2 = $('#txtChkoutDate'+idVal[1]).val();
        var start = new Date(sVal);
        var end = new Date(sVal2);
        var diff = new Date(end - start);
        var days = diff/1000/60/60/24;
        $('#txtNight'+idVal[1]).val(days);
    });

    $("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});
    $("#txtCheckIn").datepick({ dateFormat:'yyyy-mm-dd',
        onSelect:function(){
            var sVal  = $('#txtCheckIn').val();
            $('#txtChkinDate').val(sVal);
        }});
    $("#txtCheckOut").datepick({ dateFormat:'yyyy-mm-dd',
        onSelect:function(){
            var sVal  = $('#txtCheckOut').val();
            $('#txtChkoutDate').val(sVal);
        }});
    $(".txtChkinDate").datepick({ dateFormat: 'yyyy-mm-dd'});
    $(".txtChkoutDate").datepick({ dateFormat: 'yyyy-mm-dd'});

    $('#txtRoomType').keyup(function() {
        var sVal  = $('#txtRoomType').val();
        $('#txtPayRoomType').val(sVal);
    });

    $('#txtNoOfUnit').keyup(function() {
        var sVal  = $('#txtNoOfUnit').val();
        $('#txtNoofRooms').val(sVal);
    });

    $('#txtNights').click(function() {
        var sVal  = $('#txtNights').val();
        $('#txtNight').val(sVal);
    });

	$('#ddlTktname').change(function(e) {
		var sVal  = $('#ddlTktname').val();
		//alert(sVal);
		//var sVal2 = $("#ddlTktname option[value='"+sVal+"']").text();
		sVal     = sVal.split("-");
		
		$('#txtTktName').val(sVal[1]);
	});	
});
</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

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
<?php echo form_open('hotel_voucher/editHotelvoucher/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idHAArray', $chkHotelID);?>
<?php echo form_hidden('idAGTArray', $this->ddlAgentAc);?>
<?php echo form_hidden('idArray', $chkPaymtID);?>
<div id="copyref">

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
    <tr>
        <td colspan="10">
            <table>
                <tr>
                    <td><?php echo DIVISIONNAME;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>
                    </label></td>

                    <td><?php echo LOCATIONNAME;?>&nbsp;<span class="warning">*</span></td>
                    <td><label><span id="loc">
                     <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>
                    </span></label></td>
                </tr>
            </table>
        </td>
    </tr>

    <tr>
        <td colspan="10">
            <table>
                <tr>
                    <td><?php echo CUSTDATE;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <input name="txtDate" id="txtDate" class="textfield4" type="text" value="<?php echo date('Y-m-d');?>">
                    </label></td>
                
                    <td><?php echo DOCNUMBER;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <input name="txtDocNumber" id="txtDocNumber" class="textfield5" type="text" value="<?php echo $this->txtDocNumber;?>" size="20" style="width: 120px;">
                    </label></td>
                    
                    <td><?php echo TYPE;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType,'id="ddlType" class="singleDrop"');?>
                    </label></td>
                    
                    <td><?php echo VOUCHERID;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                    <span id="locStock">
                        <?php echo form_dropdown('ddlTktname', $list_tkt_value, $this->ddlTktname,'id="ddlTktname" class="multipleDrop"');?>
                    </span><input type="text" name="txtTktName" id="txtTktName" value="<?php echo $this->txtTktName;?>" />
                    </label></td>

                    <td><?php echo BOOKINGID;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <input name="txtBookingId" id="txtBookingId" class="textfield5" type="text" value="<?php echo stripslashes($this->txtBookingId);?>">
                    </label></td>
                </tr>

                <tr>
                    <td><?php echo ISSUEDBY;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <?php echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued,'id="ddlIssued" class="singleDrop"');?>
                    </label></td>
                    
                    <td><?php echo BOOKEDBY;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <?php echo form_dropdown('ddlBooked', $list_emp_value, $this->ddlBooked,'id="ddlBooked" class="singleDrop"');?>
                    </label></td>

                    <td><?php echo AGENTCONFID;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <input name="txtAgentConfId" id="txtAgentConfId" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAgentConfId);?>">
                    </label></td>
                    
                    <td><?php echo HOTELCONFID;?>&nbsp;<span class="warning">*</span></td>
                    <td><label>
                     <input name="txtHotelConfId" id="txtHotelConfId" class="textfield5" type="text" value="<?php echo stripslashes($this->txtHotelConfId);?>">
                    </label></td>
                    
                    <td><?php echo COPYHOTEL;?></td>
                    <td><label>
                    <input name="txtCopyTkt" id="txtCopyTkt" class="textfield4 chkSuggestion" type="text" value="<?php echo stripslashes($this->txtCopyTkt);?>">
                    </label></td>
                </tr>

            </table>
        </td>
    </tr>
       
    <tr>
        <td><?php echo PASSENGER;?>&nbsp;<span class="warning">*</span></td>
        <td colspan="9"><label>
         <input name="txtPassenger" id="txtPassenger" class="textfield" type="text" value="<?php echo stripslashes($this->txtPassenger);?>">
        </label></td>
    </tr>

    <tr>
        <td colspan="2"><strong><?php echo HOTELDETAILS;?></strong></td>
    </tr>
                    
    <tr>
        <td><?php echo COUNTRYNAME;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <?php echo form_dropdown('ddlCountry', $list_country_value, $this->ddlCountry,'id="ddlCountry"');?>
        </label></td>
        
        <td><?php echo CITYNAME;?>&nbsp;<span class="warning">*</span></td>
        <td><label><span id="city">
         <?php echo form_dropdown('ddlCity', $list_city_value, $this->ddlCity,'id="ddlCity"');?>
        </span></label></td>
    
        <td><?php echo HOTELNAME;?>&nbsp;<span class="warning">*</span></td>
        <td colspan="5"><label>
            <span id="hotel">
                <?php echo form_dropdown('ddlHotel', $list_hotel_value, $this->ddlHotel,'id="ddlHotel"');?>
        </span>
        </label></td>
    </tr>

    <tr>
        <td><?php echo CHECKIN;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <input name="txtCheckIn" id="txtCheckIn" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCheckIn);?>">
        </label></td>
        
        <td><?php echo CHECKOUT;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <input name="txtCheckOut" id="txtCheckOut" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCheckOut);?>">
        </label></td>

        <td><?php echo NIGHTS;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <input name="txtNights" id="txtNights" class="textfield5" type="text" value="<?php echo stripslashes($this->txtNights);?>">
        </label></td>

        <td><?php echo ADULT;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <input name="txtAdult" id="txtAdult" class="textfield5" type="text" value="<?php echo stripslashes($this->txtAdult);?>">
        </label></td>
        
        <td><?php echo CHILD;?>&nbsp;<span class="warning">*</span></td>
        <td colspan="3"><label>
         <input name="txtChild" id="txtChild" class="textfield5" type="text" value="<?php echo stripslashes($this->txtChild);?>">
        </label></td>
    </tr>

    <tr>
        <td><?php echo ROOMTYPE;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <input name="txtRoomType" id="txtRoomType" class="textfield5" type="text" value="<?php echo stripslashes($this->txtRoomType);?>">
        </label></td>
        
        <td><?php echo NOOFROOMS;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <input name="txtNoOfUnit" id="txtNoOfUnit" class="textfield5" type="text" value="<?php echo stripslashes($this->txtNoOfUnit);?>">
        </label></td>
    </tr>
    
    <tr>
        <td><?php echo SERVICES;?>&nbsp;<span class="warning">*</span></td>
        <td><label>
         <?php echo form_dropdown('ddlServices', $list_services_value, $this->ddlServices,'id="ddlServices"');?>
        </label></td>

        <td><label>
         <?php echo form_dropdown('ddlTransfer', $list_transfer_value, $this->ddlTransfer,'id="ddlTransfer"');?>
        </label></td>

        <td><label>
         <input name="txtExtraServ" id="txtExtraServ" class="textfield" type="text" value="<?php echo stripslashes($this->txtExtraServ);?>">
        </label></td>
    </tr>      
    
    <tr>
        <td colspan="5"><a href="#" class="topopup" id="2"><span style="font-size: 12px;font-weight: bold;"><?php echo TRANSFERDETAILS;?></span></a></td>

    </tr>  

    <tr>
        <td><?php echo REMARKS;?>&nbsp;<span class="warning">*</span></td>
        <td colspan="3"><label>
        <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo stripslashes($this->txtRemarks);?></textarea>
        </label></td>
        <td colspan="7"></td>
    </tr>

    <tr>
        <td><?php echo ACTIVE;?>&nbsp;<span class="warning">*</span></td>
        <td colspan="9"><label>
        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
    </tr>

    <tr>
       <td colspan="7"><a href="#" class="topopup" id="1"><span style="font-size: 12px;font-weight: bold;"><?php echo PAYMENTDETAILS;?></span></a></td>
       <td colspan="7"><a href="#" class="topopup" id="3"><span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span></a></td>
    </tr>

    <?php if($this->uri->segment(4) == 'e'){?>
    <tr>
        <td>
            <label><?php echo SALESCLOSINGSTATUS?></label>
        </td>
        <td>
            <label>
                <input type="text" value="<?php if($close_status->nHVId !=''){ echo OPEN; }else{ echo CLOSED;}?>" readonly="readonly">
            </label>
        </td>
        <td>
            <?php if($close_status->nHVId ==''){?>
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
                <input type="text" value="<?php if($refund_status->nHVId !=''){ echo OPEN; } else { echo CLOSED; } ?>"  readonly="readonly">
            </label>
        </td>
        <td>
            <?php if($refund_status->nHVId ==''){?>
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
        <td colspan="11"><label>
          <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
          <?php if($this->uri->segment(4) == 'a'){?>
          <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
          <?php }//end if?>
          <input type="reset" value="<?php echo RESET;?>" />
          <input type="button" name="btnCancel" value="<?php echo BACKTOHOTELVOUCHER;?>" id="btnCancel">
        </label></td>
    </tr>
</tbody>
</table>

<!-- Start of Sticky Note -->
<div id="toPopup1" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content"> <!--your content start-->
        <?php echo $this->load->view('hotel_voucher/show_paymentdetails');?>
    </div> <!--your content end-->
</div>
<!-- End of Sticky Note -->

<!-- Start of 2 Sticky Note -->
<div id="toPopup2" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content"> <!--your content start-->
        <?php echo $this->load->view('hotel_voucher/show_transferdetails');?>
    </div> <!--your content end-->
</div>
<!-- End of Sticky Note -->

<!-- Start of 3 Sticky Note -->
<div id="toPopup3" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content"> <!--your content start-->
        <?php echo $this->load->view('hotel_voucher/show_acdetails');?>
    </div> <!--your content end-->
</div>
<!-- End of Sticky Note -->
 </div>

  </form>
  </div>