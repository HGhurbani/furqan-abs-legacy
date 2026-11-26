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



<!-- <link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css"> -->





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

nVal2 = nVal2.replace(",", "");

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

nVal2 = nVal2.replace(",", "");

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

nVal2 = nVal2.replace(",", "");

        if (nVal2 == '') {

            nVal2 = 0;

        }

        txtTotal += parseFloat(nVal2);

    });

    $('#txtTotalDc').val(txtTotal);

    changeServiceDetails();  

}//end function



function changeSalesAmtPerNight(){

    var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);

   var nTotalNights   = 0;

    var nTotalRooms   = 0;

    var temp = 0;

    $('.txtNight').each(function () {

        temp = ($(this).val() != '' ? $(this).val() : 0);

       nTotalNights = parseFloat(nTotalNights) + parseFloat(temp); 

    });

    $('.NoofRooms').each(function () { 

            temp = ($(this).val() != '' ? $(this).val() : 0);

            nTotalRooms = parseFloat(nTotalRooms) + parseFloat(temp);

    });

    nTotalNights = (nTotalNights != 0 ? nTotalNights : 1);

    nTotalRooms  = (nTotalRooms != 0 ? nTotalRooms : 1);
	nVal = nVal.replace(",", "");
    var PerNightAmt = parseFloat(nVal)/(parseFloat(nTotalNights)*parseFloat(nTotalRooms));
	var PerNightAmt = parseFloat(PerNightAmt).toFixed(3)
    $('#txtSalesAmtPerNight').val(PerNightAmt);

}



function changeServiceDetails(){

    var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);

    var nVal2   = ($('#txtTotalDc').val() != '' ? $('#txtTotalDc').val() : 0);
	nVal = nVal.replace(",", "");

    var SerAmt = parseFloat(nVal) - parseFloat(nVal2);

    $('#txtServiceAmt').val(SerAmt);

    var SerPercent = parseFloat((SerAmt/nVal2)*100);

    $('#txtServicePerc').val(SerPercent);

}



function changeSalesAmt(){

        var nVal    = ($('#txtSalesAmtPerNight').val() != '' ? $('#txtSalesAmtPerNight').val() : 0);

        var nTotalNights   = 0;

        var nTotalRooms   = 0;

        var temp = 0;

        $('.txtNight').each(function () {

                temp = ($(this).val() != '' ? $(this).val() : 0);

                nTotalNights = parseFloat(nTotalNights) + parseFloat(temp); 

                });

        $('.NoofRooms').each(function () { 

                temp = ($(this).val() != '' ? $(this).val() : 0);

                nTotalRooms = parseFloat(nTotalRooms) + parseFloat(temp);

                });

//alert(nTotalNights + " - " + nTotalRooms + " - " + nVal);
		nVal = nVal.replace(",", "");

        var SlsAmt = parseFloat(nVal)*(parseFloat(nTotalNights)*parseFloat(nTotalRooms));

        //$('#txtSalesAmt').val(SlsAmt);

    }





function setExchangeRate(){

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

}





function changeAllAccount(){

     var loc  = $('#ddlLocation').val();

     var div = $('#ddlDivision').val();



     $.ajax({

         type:'POST',

         data:{ddlLocation:loc,ddlDivision:div},

         url:'<?php echo base_url().'index.php/hotel_voucher/getIssuedBy/'.$this->uri->segment(3);?>',

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

         url:'<?php echo base_url().'index.php/hotel_voucher/getBookedBy/'.$this->uri->segment(3);?>',

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

         url:'<?php echo base_url().'index.php/hotel_voucher/getVendorAccount/'.$this->uri->segment(3);?>',

         dataType:'html',

         success: function(response) {

                 var acc = $('#ddlAcCode').val();

                 $('#AcCode').html(response);

                 $('#ddlAcCode').val(acc);

         }

     });

     $.ajax({

         type:'POST',

         data:{ddlLocation:loc,ddlDivision:div},

         url:'<?php echo base_url().'index.php/hotel_voucher/getCustAccount/'.$this->uri->segment(3);?>',

         dataType:'html',

         success: function(response) {

                 var acc = $('#ddlCustomerAc').val();

                 $('#CustAc').html(response);

                 $('#ddlCustomerAc').val(acc);

         }

     });







}



    function calcTotalNights(){

        var sVal  = $('#txtCheckIn').val();

        var sVal2 = $('#txtCheckOut').val();

        if(sVal != '' && sVal2 != '') {

            var sVal_split = sVal.split("-");

            var sVal2_split = sVal2.split("-");

            var start = new Date(sVal_split[2],sVal_split[1] - 1,sVal_split[0]);

            var end = new Date(sVal2_split[2],sVal2_split[1] - 1,sVal2_split[0]);

            var diff = new Date(end - start);

            var days = diff/1000/60/60/24;
  $('#txtNight').val(days);
            $('#txtNights').val(days);
//calcTotalNight();
			

        }

    }
function calcTotalNight(){

        var sVal  = $('#txtCheckIn').val();

        var sVal2 = $('#txtCheckOut').val();

        if(sVal != '' && sVal2 != '') {

            var sVal_split = sVal.split("-");

            var sVal2_split = sVal2.split("-");

            var start = new Date(sVal_split[2],sVal_split[1] - 1,sVal_split[0]);

            var end = new Date(sVal2_split[2],sVal2_split[1] - 1,sVal2_split[0]);

            var diff = new Date(end - start);

            var days = diff/1000/60/60/24;

            $('#txtNight').val(days);

			

        }

    }



$(document).ready(function()

{



    $('#btnPrintPopup').click(function(e) {

            window.open('<?php echo base_url().'index.php/hotel_voucher/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");

				

			window.open('<?php echo base_url().'index.php/hotel_voucher/printAgentPreview/'.$this->uri->segment(5);?>','PrintAgentWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");



    });
	
	 $('#bac').click(function(e) {

          //  window.open('<?php echo base_url().'index.php/hotel_voucher/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");

				

			window.location.replace('<?php echo base_url().'index.php/'.$this->session->userdata('backURL2');?>');

		return false;


    });

 


    $('#btnPrint').click(function(e) {

        var html = '<table><tr><td></td></tr><tr><td>';

        html += $('.reportForm').html();

        html += '</td></tr></table>';



        window.print();

        return false;

    });



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
					ddlAcCode: {

					selectNone : true

					},
					ddlAccHead:{

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
					ddlAccHead: {
						selectNone : true
						
					},

			 ddlCountry: {

					selectNone : true

					},
					txtRoomType:{
						required:true
						
					},
			txtAdult: {
						required:true,

					number : true

					},
					txtNoOfUnit: {
						required:true,

					number : true

					},
					ddlCurrency:{

					selectNone : true

					},
					ddlHotel:{

					selectNone : true

					},
					txtPassenger:{
						required:true
						
					},
					'txtRateNetFc[]':{
						required:true,
						number : true
						
					},
			
			 ddlCity: {

					selectNone : true

					}
					
		},

		messages: {

			ddlDivision: {

					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 

					},
					ddlCurrency: {

					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 

					},
					ddlAcCode: {

					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 

					}, 
					'txtRateNetFc[]':{
						selectNone : "Required"
						
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
					
					txtRoomType: {

					selectNone : "Required"

					},
					txtRoomType: {

					selectNone : "Required"

					},
					txtPassenger: {

					selectNone : "Numbers Only"

					},
					ddlHotel: {

					selectNone : "<?php echo PLEASESELECTANOPTION;?>"

					},
					txtNoOfUnit: {

					selectNone : "Numbers Only"

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

       //  alert('1');

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

      setExchangeRate();

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

//	$('#toPopup3').hide();

	

	$('.topopup').click(function(){

		var id = this.id;

		$('#toPopup'+id).show();

	});



    $('#ddlCountry').change(function()

	{

		var nVal=$('#ddlCountry').val();

        $('#ddlCountry2').val(nVal);

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



		$.ajax(

		  {

			 type:'POST',

			 data:{ddlCountry2:nVal},

			 url:'<?php echo base_url().'index.php/hotel_voucher/filterCountry2/'.$this->uri->segment(3);?>',

			 dataType:'html',

			 beforeSend: function() {

					$('#city2').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

				},

				success: function(response) {

					$('#city2').html(response);

				}

		  });

	});

   $('#city').click(function(){

        var nCountry = $('#ddlCountry').val();

        var nCity    = $('#ddlCity').val();

        $('#ddlCity2').val(nCity);

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





   // $(document).on('change','#ddlCity', function()

   $('#city').live('change',function(){

        var nCountry = $('#ddlCountry').val();

        var nCity    = $('#ddlCity').val();

        $('#ddlCity2').val(nCity);

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

        setExchangeRate();

/*		var nVal  = $('#ddlCurrency').val();

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

		  });*/

	});



/*    $('#AcCode').click(function()

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

*/

    $('#AcCode').click(function(e) {

        var nVal = $('#ddlAcCode').val();
 var nCal  = $('#ddlAcCode').val();


        $.ajax(

            {

                type:'POST',

                data:{ddlAcCode:nVal},

                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountHead/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend: function() {

                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success: function(response) {

                    $('#accHead').html(response);

                }

            });
			
			$.ajax(

            {

                type:'POST',

                data:{ddlAcCode:nCal},

                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountCur/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend: function() {

                   $('#accCur').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success: function(response) {

                    $('#accCur').html(response);
					//console.log(response);
				setExchangeRate(); 
                }

            });

    });


 $('#AcCode').keyup(function(e) {

        var nVal = $('#ddlAcCode').val();
 var nCal  = $('#ddlAcCode').val();


        $.ajax(

            {

                type:'POST',

                data:{ddlAcCode:nVal},

                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountHead/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend: function() {

                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success: function(response) {

                    $('#accHead').html(response);

                }

            });
			
			$.ajax(

            {

                type:'POST',

                data:{ddlAcCode:nCal},

                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountCur/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend: function() {

                   $('#accCur').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success: function(response) {

                    $('#accCur').html(response);
					//console.log(response);
				setExchangeRate(); 
                }

            });

    });
 

    $('#ddlAcCode').change(function(e) {

        var nVal  = $('#ddlAcCode').val();
 var nCal  = $('#ddlAcCode').val();


$.ajax(

            {

                type:'POST',

                data:{ddlAcCode:nVal},

                url:'<?php echo base_url().'index.php/hotel_voucher/getAccountHead/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend: function() {

                    $('#accHead').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success: function(response) {

                    $('#accHead').html(response);
					setExchangeRate();

                }

            });

    });



   

    $('#txtSalesAmtPerNight').keyup(function(){

        changeSalesAmt(); 

    });



    changeSalesAmtPerNight();

    $('#txtSalesAmt').keyup(function(){

        changeSalesAmtPerNight();

        changeServiceDetails();

    });



    $('#txtServiceAmt').keyup(function(){

        changeServiceDetails();

    });



    $('#txtServicePerc').keyup(function(){

        changeServiceDetails();

    });



    $('#txtCollected').click(function(){

        var nVal    = ($('#txtSalesAmt').val() != '' ? $('#txtSalesAmt').val() : 0);

        var nVal2   = ($('#txtDiscountAmt').val() != '' ? $('#txtDiscountAmt').val() : 0);

		var nVal3   = ($('#txtServiceAmt').val() != '' ? $('#txtServiceAmt').val() : 0);

		var nVal4   = ($('#txtAgentAmt').val() != '' ? $('#txtAgentAmt').val() : 0);

        var collAmt = (parseFloat(nVal)-parseFloat(nVal2)-parseFloat(nVal4))+parseFloat(nVal3);

        $('#txtCollected').val(collAmt);

    });





    $('.totalFc').keyup(function () {

        var id       = this.id;

        var getId    = id.split('txtTotalinFc');

        var fcAmt    = ($('#txtTotalinFc' + getId[1]).val() != '' ? $('#txtTotalinFc'+getId[1]).val() : 0);

        var nytCount = ($('#txtNight'+getId[1]).val() != '' ? $('#txtNight'+getId[1]).val() : 0);

        var rooms    = ($('#txtNoofRooms'+getId[1]).val() != '' ? $('#txtNoofRooms'+getId[1]).val() : 0);

        var exrate   = ($('#txtExchangeRate'+getId[1]).val() != '' ? $('#txtExchangeRate'+getId[1]).val() : 0);



        if(fcAmt !=0 ){

            var totalFc  = (parseFloat(fcAmt)/(parseFloat(nytCount)*parseFloat(rooms)));

            var rateDc   = (parseFloat(totalFc)*parseFloat(exrate));

            var totalDc  = (parseFloat(rateDc)*parseFloat(nytCount)*parseFloat(rooms));



            $('#txtRateNetFc'+getId[1]).val(totalFc);

            $('#txtRateNetDc'+getId[1]).val(rateDc);

            $('#txtTotalinDc'+getId[1]).val(totalDc);



            getRateFcTotal();

            getFcTotal();

            getRateDcTotal();

            getDcTotal();

        }

    });





    $('.rateNetFc').keyup(function () {



        var id       = this.id;

        var getId    = id.split('txtRateNetFc');

        var fcAmt    = ($('#txtRateNetFc' + getId[1]).val() != '' ? $('#txtRateNetFc'+getId[1]).val() : 0);

        var nytCount = ($('#txtNight'+getId[1]).val() != '' ? $('#txtNight'+getId[1]).val() : 0);

        var rooms    = ($('#txtNoofRooms'+getId[1]).val() != '' ? $('#txtNoofRooms'+getId[1]).val() : 0);

        var exrate   = ($('#txtExchangeRate'+getId[1]).val() != '' ? $('#txtExchangeRate'+getId[1]).val() : 0);

		fcAmt = fcAmt.replace(",", "");
		
        var totalFc  = (parseFloat(fcAmt)*parseFloat(nytCount)*parseFloat(rooms));

        var rateDc   = (parseFloat(fcAmt)*parseFloat(exrate));
		rateDc = rateDc.replace(",", "");
		

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

            changeAllAccount();

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

                    

                    var sVal_tktnm  = $('#ddlTktname').val();

                    sVal_tktnm     = sVal_tktnm.split("-");

                    

                    $('#txtTktName').val(sVal_tktnm[1]);

                }

            });

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

            changeAllAccount();



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

                url:'<?php echo base_url().'index.php/hotel_voucher/getStock/'.$this->uri->segment(3);?>',

                dataType:'html',

                beforeSend: function() {

                    $('#locStock').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success: function(response) {

                    $('#locStock').html(response);

                }

            });

        changeAllAccount();



    });



    $('#loc').change(function(){
<?php if($this->uri->segment(4) == 'a')

    {?>  var nVal  = $('#ddlLocation').val();
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

                    

                    var sVal_tktnm  = $('#ddlTktname').val();

                    sVal_tktnm     = sVal_tktnm.split("-");

                    

                    $('#txtTktName').val(sVal_tktnm[1]);

                }

            });
	<?php } ?>
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



	$('#ddlTktname').change(function(e) {

		var sVal  = $('#ddlTktname').val();

		//var sVal2 = $("#ddlTktname option[value='"+sVal+"']").text();

		sVal     = sVal.split("-");



		$('#txtTktName').val(sVal[1]);

    });



    $('#txtNights').click(function() {

        calcTotalNights();

    });
	$('#txtNight').click(function() {

        calcTotalNights();

    });



    $('.txtChkinDate').each(function () { 

            changeSalesAmt();

            calcTotalNights();

    }); 

    $('.txtChkoutDate').each(function () { 

        changeSalesAmt();

        calcTotalNights();

    }); 



    $('.NoofRooms').each(function () { 

            changeSalesAmt();

    });



    $('.txtNight').click(function() {

        var dId = this.id;

        var idVal = dId.split('txtNight');



        var sVal  = $('#txtChkinDate'+idVal[1]).val();

        var sVal_split = sVal.split("-");

        var sVal2 = $('#txtChkoutDate'+idVal[1]).val();

        var sVal2_split = sVal2.split("-");

        var start = new Date(sVal_split[2],sVal_split[1] - 1,sVal_split[0]);

        var end = new Date(sVal2_split[2],sVal2_split[1] - 1,sVal2_split[0]);

        var diff = new Date(end - start);

        var days = diff/1000/60/60/24;

        days = (isNaN(days) ? 0 : days);

        $('#txtNight'+idVal[1]).val(days);

        changeSalesAmt();

    });



    $("#txtDate").datepick({ dateFormat: 'dd-mm-yyyy'});

    $("#txtCheckIn").datepick({ dateFormat:'dd-mm-yyyy',

        onSelect:function(){

            var sVal  = $('#txtCheckIn').val();

            $('#txtChkinDate').val(sVal);

            calcTotalNights();

        }});

    $("#txtCheckOut").datepick({ dateFormat:'dd-mm-yyyy',

        onSelect:function(){

            var sVal  = $('#txtCheckOut').val();

            $('#txtChkoutDate').val(sVal);

            calcTotalNights();

        }});

    $(".txtChkinDate").datepick({ dateFormat: 'dd-mm-yyyy'});

    $(".txtChkoutDate").datepick({ dateFormat: 'dd-mm-yyyy'});



    $('#txtRoomType').keyup(function() {

        var sVal  = $('#txtRoomType').val();

        $('#txtPayRoomType').val(sVal);

    });



    $('#txtNoOfUnit').keyup(function() {

        var sVal  = $('#txtNoOfUnit').val();

        $('#txtNoofRooms').val(sVal);

    });



   



	$('#ddlTktname').change(function(e) {

		var sVal  = $('#ddlTktname').val();

		sVal     = sVal.split("-");

		

		$('#txtTktName').val(sVal[1]);

	});	

	



    $('#ddlType').change(function(e) {

        if($('#ddlType').val() == 'V'){

           $('.PayRoomType').each(function () {  

                $(this).val("");

            }); 

            $('.txtChkinDate').each(function () { 

                $(this).val("");

            }); 

            $('.txtChkoutDate').each(function () { 

                $(this).val("");

            }); 

            $('.txtNight').each(function () { 

                $(this).val("");

            }); 

            $('.NoofRooms').each(function () { 

                $(this).val("");

            }); 

            $('.rateNetFc').each(function () { //Changed to class selector

                $(this).val("");

            }); 

            $('.totalFc').each(function () { 

                $(this).val("");

            }); 

            $('.rateNetDc').each(function () { 

                $(this).val("");

            }); 

            $('.totalDc').each(function () { 

                $(this).val("");

            }); 

            $('#txtSalesAmtPerNight').val(0);

            $('#txtServicePerc').val("");

            $('#txtSalesAmt').val(0);



            getRateFcTotal();

            getFcTotal();

            getRateDcTotal();

            getDcTotal(); 

        }

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

<?php echo form_open('hotel_voucher/editHotelvoucher/'.$this->uri->segment(3).'/'.$this->uri->segment(4).'/'.$this->uri->segment(5).'/'.$this->uri->segment(6),$attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>

<?php echo form_hidden('idHAArray', $chkHotelID);?>

<?php echo form_hidden('idAGTArray', $this->ddlAgentAc);?>

<?php echo form_hidden('idArray', $chkPaymtID);?>

<div id="copyref">



<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">

<tbody>

    <tr>

        <td><?php echo DIVISIONNAME;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision"');?>

        </label></td>



        <td><?php echo LOCATIONNAME;?>&nbsp;<span class="warning">*</span></td>

        <td><label><span id="loc">

                         <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation"');?>

                        </span></label></td>



        <td><?php echo CUSTDATE;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <input name="txtDate" id="txtDate" class="textfield4" type="text" value="<?php echo $this->txtDate;?>">

        </label></td>

    </tr>



    <tr>

        <td><?php echo DOCNUMBER;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <input name="txtDocNumber" id="txtDocNumber" class="textfield5" type="text" value="<?php echo $this->txtDocNumber;?>" size="20" style="width: 120px;">

        </label></td>



        <td><?php echo STATUS;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <?php echo form_dropdown('ddlType', $list_type_value, $this->ddlType,'id="ddlType" class="multipleDrop"');?>

        </label></td>

<!--        <td><?php echo ISSUEDID;?>

        </label></td>

        <td> --> <input type="hidden" name="txtIssuedEmail" id="txtIssuedEmail" value="<?php if($this->catId !='') {  echo $this->common_model->get_field_name('HotelVoucher','vIssuedEmailId'," WHERE nHVId = ".$this->catId." "); } ?>" /> <!-- </td> -->

 

    </tr>



    <tr>

        <td><?php echo VOUCHERID;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <span id="locStock">

                <?php echo form_dropdown('ddlTktname', $list_tkt_value, $this->ddlTktname,'id="ddlTktname" class="multipleDrop"');?>

            </span>

            

        </label></td>

        <td><input type="text" name="txtTktName" id="txtTktName" readonly="readonly" value="<?php echo $this->txtTktName;?>" /></td>

    </tr>



    <tr>

        <td><?php echo BOOKINGID;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <input name="txtBookingId" id="txtBookingId" class="textfield" type="text" value="<?php echo stripslashes($this->txtBookingId);?>">

        </label></td>



        <td><?php echo ISSUEDBY;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

        <span id="Issued">

            <?php  if(!empty($this->ddlIssued)){

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

                        echo form_dropdown('ddlBooked', $list_emp_value, $this->ddlBooked,'id="ddlBooked" class="multipleDrop "');

                       } else {

                              $login_id = $this->session->userdata('AbsAdmin');

                              $emp_id= $this->common_model->get_field_name('Admin','nEmpId'," WHERE nId = '".$login_id."' ");

                              echo form_dropdown('ddlBooked', $list_emp_value,$emp_id ,'id="ddlBooked" class="multipleDrop "');

                       }

             ?>

            </span>

        </label></td>

    </tr>



    <tr>

        <td><?php echo AGENTCONFID;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <input name="txtAgentConfId" id="txtAgentConfId" class="textfield" type="text" value="<?php echo stripslashes($this->txtAgentConfId);?>">

        </label></td>



        <td><?php echo HOTELCONFID;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <input name="txtHotelConfId" id="txtHotelConfId" class="textfield" type="text" value="<?php echo stripslashes($this->txtHotelConfId);?>">

        </label></td>



        <td><?php echo COPYHOTEL;?></td>

        <td><label>

            <input name="txtCopyTkt" id="txtCopyTkt" class="textfield4 chkSuggestion" type="text" value="<?php echo stripslashes($this->txtCopyTkt);?>">

        </label></td>

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

        </label>

        &nbsp;  &nbsp; 

        <a href="#" class="topopup" id="4"><span style="font-size: 12px;font-weight: bold;"><?php echo ADDHOTEL;?></span></a>

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

    </tr>



    <tr>

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

         <input name="txtRoomType" id="txtRoomType" class="textfield" type="text" value="<?php echo stripslashes($this->txtRoomType);?>">

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
<?php echo form_dropdown('txtExtraServ', $list_meal_value, $this->txtExtraServ,'id="txtExtraServ"');?>

       <!--  <input name="txtExtraServ" id="txtExtraServ" class="textfield" type="text" value="<?php echo stripslashes($this->txtExtraServ);?>">
-->
        </label></td>



        <td><?php echo REMARKS;?>&nbsp;<span class="warning">*</span></td>

        <td><label>

            <textarea name="txtRemarks" id="txtRemarks" rows="1"><?php echo stripslashes($this->txtRemarks);?></textarea>

        </label></td>

    </tr>



    <tr>

        <td colspan="7"><strong><?php echo PAYMENTDETAILS;?></strong></td>



    </tr>



    <tr>

        <td colspan="10">

            <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">

                <tr>

                    <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>

                    <td><label>

                    <span id="AcCode">

                        <?php echo form_dropdown('ddlAcCode', $list_accode_value, $this->ddlAcCode,'id="ddlAcCode" ');?>

                    </span>

                    </label></td>



                    <td><?php echo ACCHEAD;?></td>

                    <td><label>

                    <span id="accHead">

                     <?php echo form_dropdown('ddlAccHead', $list_acchead_value, $this->ddlAccHead,'id="ddlAccHead"');?>

                    </span>

                    </label></td>



                    <td><?php echo CURRNAME;?> <span class="warning">*</span></td>

                    <td><label>
				<span id="accCur">
                        <?php echo form_dropdown('ddlCurrency', $list_curr_value, $this->ddlCurrency,'id="ddlCurrency"');?>
					</span>

                    </label></td>



                    <td><?php echo CURRRATE;?> <span class="warning">*</span></td>

                    <td colspan="5"><label>

                        <input name="txtRate" id="txtRate" class="textfield3" type="text" value="<?php echo $this->txtRate;?>" size="20">

                    </label></td>

                </tr>

 

                </tr>

                <tr>

                    <td colspan="7">

<!--                        <span class="clears"></span>

-->                        <table id="dataTable" title="dataTable">

                            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />

                            <tr>

                                <td><?php echo SRNO;?></td>

                                <td><?php echo ROOMTYPE;?></td>

                                <td><?php echo INDATE;?></td>

                                <td><?php echo OUTDATE;?></td>

                                <td><?php echo NIGHTS;?></td>

                                <td><?php echo NOOFROOMS;?></td>

                                <td><?php echo RATENETFC;?></td>

                                <td><?php echo TOTALINFC;?></td>

                                <td><?php echo RATENETDC;?></td>

                                <td><?php echo TOTALINDC;?></td>

                                <td colspan="8"><?php echo EXCHRATE;?></td>

                            </tr>



                            <?php

                            if(!empty($txtPayRoomType))

                            {

                                foreach($txtPayRoomType as $key => $aitem):

                                    ?>

                                    <tr  class="re">

                                        <td><?php echo ($key+1);?></td>

                                        <td><input name="txtPayRoomType[]" id="txtPayRoomType<?php echo $key;?>" class="textfield5 PayRoomType " type="text" value="<?php echo stripslashes($txtPayRoomType[$key]);?>"></td>

                                        <td><input name="txtChkinDate[]" id="txtChkinDate<?php echo $key;?>" class="textfield5 txtChkinDate" type="text" value="<?php echo stripslashes($txtChkinDate[$key]);?>"></td>

                                        <td><input name="txtChkoutDate[]" id="txtChkoutDate<?php echo $key;?>" class="textfield5 txtChkoutDate" type="text" value="<?php echo stripslashes($txtChkoutDate[$key]);?>"></td>

                                        <td><input name="txtNight[]" id="txtNight0<?php  $key;?>" class="textfield3 txtNight" type="text" value="<?php echo stripslashes($txtNight[$key]);?>"></td>

                                        <td><input name="txtNoofRooms[]" id="txtNoofRooms<?php echo $key;?>" class="textfield3 NoofRooms" type="text" value="<?php echo stripslashes($txtNoofRooms[$key]);?>"></td>

                                        <td><input name="txtRateNetFc[]" id="txtRateNetFc<?php echo $key;?>" class="textfield3 rateNetFc" type="text" value="<?php echo number_format($txtRateNetFc[$key],3);?>"></td>

                                        <td><input name="txtTotalinFc[]" id="txtTotalinFc<?php echo $key;?>" class="textfield3 totalFc" type="text" value="<?php echo stripslashes($txtTotalinFc[$key]);?>"></td>

                                        <td><input name="txtRateNetDc[]" id="txtRateNetDc<?php echo $key;?>" class="textfield3 rateNetDc" type="text" value="<?php echo stripslashes($txtRateNetDc[$key]);?>"></td>

                                        <td><input name="txtTotalinDc[]" id="txtTotalinDc<?php echo $key;?>" class="textfield3 totalDc" type="text" value="<?php echo stripslashes($txtTotalinDc[$key]);?>"></td>

                                        <td><input name="txtExchangeRate[]" id="txtExchangeRate<?php echo $key;?>" class="txtExchangeRate textfield3" type="text" value="<?php echo stripslashes($txtExchangeRate[$key]);?>"></td>

                                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkHotelID[$key];?>" title="Delete"/></td>

                                    </tr>

                                    <?php

                                endforeach;

                            }//end if

                            else

                            {?>

                                <tr class="re">

                                    <td>#</td>

                                    <td><input name="txtPayRoomType[]" id="txtPayRoomType" class="textfield5 PayRoomType" type="text" value=""></td>

                                    <td><input name="txtChkinDate[]" id="txtChkinDate" class="textfield5 txtChkinDate" type="text" value=""></td>

                                    <td><input name="txtChkoutDate[]" id="txtChkoutDate" class="textfield5 txtChkoutDate" type="text" value=""></td>

                                    <td><input name="txtNight[]" id="txtNight" class="textfield3 txtNight" type="text" value=""></td>

                                    <td><input name="txtNoofRooms[]" id="txtNoofRooms" class="textfield3 NoofRooms" type="text" value=""></td>

                                    <td><input name="txtRateNetFc[]" id="txtRateNetFc" class="textfield3 rateNetFc" type="text" value=""></td>

                                    <td><input name="txtTotalinFc[]" id="txtTotalinFc" class="textfield3 totalFc" type="text" value=""></td>

                                    <td><input name="txtRateNetDc[]" id="txtRateNetDc" class="textfield3 rateNetDc" type="text" value=""></td>

                                    <td><input name="txtTotalinDc[]" id="txtTotalinDc" class="textfield3 totalDc" type="text" value=""></td>

                                    <td><input name="txtExchangeRate[]" id="txtExchangeRate" class="txtExchangeRate textfield3" type="text" value=""></td>

                                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nId;?>" title="Delete"/></td>

                                </tr>

                                <?php

                            }//end else

                            ?>



                            <tr>

                                <td colspan="4">&nbsp;</td>

                                <td colspan="2"><?php echo TOTALAMT;?></td>

                                <td><input name="txtRateNetFcTotal" class="textfield3" id="txtRateNetFcTotal" type="text" value="<?php echo number_format($this->txtRateNetFcTotal,3);?>"></td>

                                <td><input name="txtTotalFc" class="textfield3" id="txtTotalFc" type="text" value="<?php echo $this->txtTotalFc;?>"></td>

                                <td><input name="txtRateNetDcTotal" class="textfield3" id="txtRateNetDcTotal" type="text" value="<?php echo $this->txtRateNetDcTotal;?>"></td>

                                <td><input name="txtTotalDc" class="textfield3" id="txtTotalDc" type="text" value="<?php echo $this->txtTotalDc;?>"></td>

                                <td>&nbsp;</td>

                                <td>&nbsp;</td>

                            </tr>



                        </table>

                    </td>

                </tr>







            </table>

        </td>

    </tr>



<tr> 

        <td colspan="7"><span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span></td>

</tr>

<tr>

<td colspan="8">

     <table  width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm" id="dataControl">

        <tr>

          <td><?php echo CUSTACCNAME;?></td>

           <td><label>

           <span id="CustAc">

             <?php echo form_dropdown('ddlCustomerAc', $list_custacc_value, $this->ddlCustomerAc,'id="ddlCustomerAc"');?>

           </span>

           </label></td>

        </tr>




        <tr>

          <td><?php echo SALESAMTPERNIGHT;?></td>

            <td><label>

            <input name="txtSalesAmtPerNight" id="txtSalesAmtPerNight" class="textfield5" type="text" value="0">

            </label></td>



          <td><?php echo SALESAMT;?></td>

            <td><label>

             <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="text" value="<?php if( stripslashes($this->txtCollected) != ''){ 
			 $sls=str_replace(',','',$this->txtCollected);

			 
			 echo $sls;} else {echo "0"; }?>">

<!--             <input name="txtSalesAmt" id="txtSalesAmt" class="textfield5" type="text" value="<?php if( stripslashes($this->txtSalesAmt) != ''){ 
$sls=str_replace(',','',$this->txtSalesAmt);
echo $sls;} else {echo "0"; }?>"> -->

            </label></td>



          <td>&nbsp;</td>

           <td><label>

            <input name="txtServicePerc" id="txtServicePerc" class="textfield5 txtServicePerc hidden" type="text" value="<?php echo stripslashes($this->txtServicePerc);?>">

           </label></td>



          <td>&nbsp;</td>

           <td><label>

            <input name="txtServiceAmt" id="txtServiceAmt" class="textfield5 txtServiceAmt hidden" type="text" value="<?php echo stripslashes($this->txtServiceAmt);?>">

           </label></td>

        </tr>

                </table>

</td>

</tr>









    <tr>

        <td colspan="5"><a href="#" class="topopup" id="2"><span style="font-size: 12px;font-weight: bold;"><?php echo TRANSFERDETAILS;?></span></a></td>

    </tr>

<!--

    <tr>

        <td colspan="7"><a href="#" class="topopup" id="3"><span style="font-size: 12px;font-weight: bold;"><?php echo ACDETAILS;?></span></a></td>

    </tr>

-->

    <tr>

        <td><?php echo ACTIVE;?>&nbsp;<span class="warning">*</span></td>

        <td colspan="9"><label>

        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>

    </tr>



    <?php if($this->uri->segment(4) == 'e'){?>

    <tr>

        <td>

            <label><?php echo SALESCLOSINGSTATUS?></label>

        </td>

        <td>

            <label>
				<input type="hidden" name="clstatus" value="<?php if($close_status->nHVId !=''){ echo "OPEN"; } else { echo "CLOSED"; } ?>" readonly="readonly">
           
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

<?php   

 echo $val_alloc =$this->common_model->get_field_name('CashAllocation','nSalesCloseId'," WHERE vSalesCloseType ='SC' AND nSalesCloseId = '".$invoice_id->nSCId."'");
				if($val_alloc==0){?>

            <?php if($this->uri->segment(4) == 'a'):?>

            <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />

            <?php else:?>

            <?php  if($close_status->nHVId !='' || trim($this->session->userdata('backURL')) != '' || $this->uri->segment(6) == 'closed'){  ?>

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

          <input type="button" name="btnCancel" value="<?php echo BACKTOHOTELVOUCHER;?>" id="btnCancel">

        </label>





            <?php if($this->uri->segment(4) == 'a'):?>

            <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>

            <?php else:?>

            <?php if($close_status->nHVId !='' || trim($this->session->userdata('backURL')) != '' || $this->uri->segment(6) == 'closed'){  ?>

            <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>

                <?php }

            else{ ?>

                            <input type="button" name="btnPrintPopup" id="btnPrintPopup" value="<?php echo PRINTPAGE;?>"/>

                <?php } ?>

            <?php endif;?>



    </tr>

</tbody>

</table>



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

<!--

<div id="toPopup3" class="toPopup">

    <div class="close"></div>

    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

    <div id="popup_content">

        <?php // echo $this->load->view('hotel_voucher/show_acdetails');?>

    </div> 

</div>

-->

<!-- End of Sticky Note -->



<!-- Start of 4 Sticky Note -->



<div id="toPopup4" class="toPopup">

    <div class="close"></div>

    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>

    <div id="popup_content">

        <?php echo $this->load->view('hotel_voucher/add_hotel');?>

    </div> 

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

