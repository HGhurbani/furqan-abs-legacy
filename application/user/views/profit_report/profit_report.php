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

<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">

<script language="javascript1.1" type="text/javascript">







    $.validator.setDefaults({



        submitHandler: function() {}



    });







   



    function getLocation(nVal) {



        $.ajax({



            type:'POST',



            data:{ddlDivision:nVal},



            url:'<?php echo base_url().'index.php/profit_report/filterDivision/'.$this->uri->segment(3);?>',



            dataType:'html',



            beforeSend: function() {



                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');



            },



            success: function(response) {



                $('#ddlLocation2').html(response);



                $('#ddlLocation').html(response);

                changeAllAccount();



            }



        });



    }



function changeAllAccount(){

     var loc  = $('#ddlLocation').val();

     var loc2  = $('#ddlLocation2').val();

     var div = $('#ddlDivision').val();



     $.ajax({

         type:'POST',

         data:{ddlLocation:loc,ddlDivision:div,ddlLocation2:loc2},

         url:'<?php echo base_url().'index.php/profit_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',

         dataType:'html',

         success: function(response) {

                 var issued = $('#ddlIssued').val();

                 $('#ddlIssued').html(response);

                 $('#ddlIssued').val(issued);

         }

     });



}







    $(document).ready(function()



    {



        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});



        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});



		$("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});



		$("#txtCheckIn").datepick({ dateFormat: 'yyyy-mm-dd'});



		$("#txtCheckOut").datepick({ dateFormat: 'yyyy-mm-dd'});







//        getLocation($('#ddlDivision').val());













        $('#loc').change(function(){

            changeAllAccount();

            });



        $('#loc2').change(function(){

            changeAllAccount();

            });

        changeAllAccount();





        $('#ddlDivision').change(function(){



            var nVal = $('#ddlDivision').val();



            getLocation(nVal);



        });







        $('#ddlCountry').change(function()



        {



            var nVal  = $('#ddlCountry').val();



            $.ajax({



                 type:'POST',



                 data:{ddlCountry:nVal},



                 url:'<?php echo base_url().'index.php/profit_report/filterCountry/'.$this->uri->segment(3);?>',



                 dataType:'html',



                 beforeSend: function() {



                        $('#city').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');



                    },



                    success: function(response) {



                        $('#city').html(response);



                    }



            });



        });







        $('#ddlAccount').change(function()



        {



            var nVal  = $('#ddlAccount').val();



            $.ajax({



                type:'POST',



                data:{ddlAccount:nVal},



                url:'<?php echo base_url().'index.php/profit_report/filterSubAccount/'.$this->uri->segment(3);?>',



                dataType:'html',



                success: function(response) {



                    $('#subAcc').html(response);



                }



            });



        });







        $('#btnAirline').click(function () {



            getVal('Airline');



        });







        $('#btnGdsbooking').click(function () {



            getVal('GdsBooking');



        });







        $('#btnHotelagent').click(function () {



            getVal('HotelAgent');



        });







        $('#btnHotel').click(function () {



            getVal('Hotel');



        });







        $('#btnPayable').click(function () {



            getVal('Payable');



        });







        $('#btnGdsticketed').click(function () {



            getVal('GdsTicketed');



        });







        $('#btnHotelcheckin').click(function () {



            getVal('HotelCheckIn');



        });







        $('#btnHotelemployee').click(function () {



            getVal('HotelEmployee');



        });







        $('#btnEmployee').click(function () {



            getVal('Employee');



        });







        $('#btnHotelcheckout').click(function () {



            getVal('HotelCheckOut');



        });







        $('#btnSector').click(function () {



            getVal('Sector');



        });







        $('#btnHotelcountry').click(function () {



            getVal('HotelCountry');



        });







        $('#btnDestination').click(function () {



            getVal('Destination');



        });







        $('#btnHotelcity').click(function () {



            getVal('HotelCity');



        });







        $('#btnProfit').click(function () {



            getVal('Profit');



        });



		$('#btnStatus').click(function () {

			getVal('Status');

		});





        function getVal(nval) {



            switch (nval) {



                case "Airline":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showAirline/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "GdsBooking":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showGdsBooking/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "Hotel":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showHotel/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "HotelAgent":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showHotel/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "Payable":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showPayable/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "GdsTicketed":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showGdsTicketed/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "HotelCheckIn":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showHotelCheckIn/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "HotelEmployee":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showEmployee/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "Employee":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showEmployee/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "HotelCheckOut":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showHotelCheckOut/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "Sector":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showSector/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "HotelCountry":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showHotelCountry/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "Destination":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showDestination/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "HotelCity":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showHotelCity/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;







                case "Profit":



                    var path = '<?php echo base_url() . 'index.php/profit_report/showProfit/' . $this->uri->segment(3);?>';



                    var sess_clear = 'y';



                    break;



				case "Status":

					var path = '<?php echo base_url() . 'index.php/profit_report/showStatus/' . $this->uri->segment(3);?>';

					var sess_clear = 'y';

					break;





            }//end switch







            var nDivision        = $('#ddlDivision').val();



            var nLocation        = $('#ddlLocation').val();



            var nLocation2       = $('#ddlLocation2').val();



            var nToDate          = $('#txtToDate').val();



            var nFromDate        = $('#txtFromDate').val();



            var nVendor          = $('#ddlVendor').val();



            var nCountry         = $('#ddlCountry').val();



            var nAirline         = $('#ddlAirline').val();



            var nPayableAccount  = $('#ddlPayableAccount').val();



            var nCity            = $('#ddlCity').val();



            var nIssued          = $('#ddlIssued').val();



            var nHotel           = $('#ddlHotel').val();



            var nCheckIn         = $('#txtCheckIn').val();



            var nCheckOut        = $('#txtCheckOut').val();



            var nAccount         = $('#ddlAccount').val();



            var nSubAccount      = $('#ddlSubAccount').val();



            var nSector1         = $('#txtSector1').val();



            var nSector2         = $('#txtSector2').val();



            var nSector3         = $('#txtSector3').val();



            var nSector4         = $('#txtSector4').val();



            var nSector5         = $('#txtSector5').val();



            var nSector6         = $('#txtSector6').val();



            var nSector7         = $('#txtSector7').val();



            var nDestination     = $('#txtDestination').val();



            var nGDS             = $('#ddlGDS').val();



            var nRefund          = $('#ddlRefund').val();



            var nStatus          = $('#ddlStatus').val();



            var nSalesType       = $('#ddlSalesType').val();



            var nReport          = $('#ddlReport').val();



            $.ajax({



                    type:'POST',



                    data:{ddlDivision:nDivision, ddlLocation:nLocation, ddlLocation2:nLocation2, txtToDate:nToDate, txtFromDate:nFromDate,



                          ddlVendor:nVendor,ddlCountry:nCountry, ddlAirline:nAirline, ddlPayableAccount:nPayableAccount, ddlCity:nCity,



                          ddlIssued:nIssued, ddlHotel:nHotel, txtCheckIn:nCheckIn, txtCheckOut:nCheckOut,txtSector1:nSector1, txtSector2:nSector2,



                          txtSector3:nSector3, txtSector4:nSector4, txtSector5:nSector5, txtSector6:nSector6, txtSector7:nSector7,



                          ddlAccount:nAccount, ddlSubAccount:nSubAccount,txtDestination:nDestination, ddlGDS:nGDS,ddlRefund:nRefund,



                          ddlStatus:nStatus, ddlSalesType:nSalesType, ddlReport:nReport,sess_clear:sess_clear},



                    url:path,



                    dataType:'html',



                    beforeSend: function() {
                $('#loading').show();
				$('#result').hide();
				},
				error: function (jqXHR, exception) {
					var msg = '';
					if (jqXHR.status === 0) {
						msg = 'Not connect.\n Verify Network.';
					} else if (jqXHR.status == 404) {
						msg = 'Requested page not found. [404]';
					} else if (jqXHR.status == 500) {
						msg = 'Internal Server Error [500].';
					} else if (exception === 'parsererror') {
						msg = 'Requested JSON parse failed.';
					} else if (exception === 'timeout') {
						msg = 'Time out error.';
					} else if (exception === 'abort') {
						msg = 'Ajax request aborted.';
					} else {
						msg = 'Uncaught Error.\n' + jqXHR.responseText;
					}
					$('#img1').hide();
					$('#title').hide();
					$('#error1').html(msg);
				},
				
				success:function (response) {
					$('#loading').hide();
					 $('#result').show();
                    $('#result').html(response);
                }
            });



        }//end function



    });



	



</script>



<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>



<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">



<?php



echo $this->common_model->successMsg($this->uri->segment(5));



echo validation_errors('<p class="errorClass">','</p>');



$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');







if(isset($error_value) && trim($error_value)!='')



{



    ?>



<code><?php echo $error_value;?></code>



<?php



}//end if



?>



<?php $this->load->view('general/admin_menu');?>



<div class="formHead">



    <h4><?php echo $this->showTitle;?></h4>



</div>



<div class="formBorder">



<?php echo form_open('profit_report/showlist/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>



<?php echo form_hidden('nId', $this->catId);?>



<?php echo form_hidden('idArray', $chkDetailID);?>

<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Profit Report ...</h3><br>
<h3 style="color:red;" id="error"></h3><br>

<h3 style="color:red;" id="error1"></h3><br>


<img id="img1" src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>

<div id="result">



    <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">



<tbody>







<tr>



    <td colspan="10">



        <table>



            <tr>



				<td><?php echo DIVISIONNAME;?> <span class="warning"></span></td>



                    <td><label>



                        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision smallDropdown"');?>



                    </label></td>







                    <td><?php echo LOCATION.'&nbsp;'.FROM;?></td>



                    <td><label>



                        <span id="loc">



                            <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>



                        </span>



                    </label></td>







                    <td><?php echo LOCATION.'&nbsp;'.TO;?></td>



                    <td><label>



                        <span id="loc2">



                            <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2 smallDropdown"');?>



                        </span>



                    </label></td>



			</tr>



            <tr>



                <td><?php echo FROMDATE;?></td>



                <td>



                <input name="txtToDate" id="txtToDate" class="textfield5" type="text" value="<?php echo $this->txtToDate;?>">



                </td>







                 <td><?php echo TODATE;?></td>



                <td>



                <input name="txtFromDate" id="txtFromDate" class="textfield5" type="text" value="<?php echo $this->txtFromDate;?>">



                </td>



				

<!--

				<td><?php echo VENDOR;?></td>



                <td><label>



                <?php echo form_dropdown('ddlVendor', $list_vendor_value,$this->ddlVendor, 'id="ddlDrAccMain" class="ddlVendor"');?>



                </label></td>

-->

            </tr>







            <tr>







                <td><?php echo AIRLINE;?> <span class="warning"></span></td>



                <td><label>



                <?php echo form_dropdown('ddlAirline', $list_airline_value, $this->ddlAirline,'id="ddlAirline"  class="chosen-select"');?>



                </label></td>





				 <td><?php echo ISSUEDBY;?> <span class="warning"></span></td>



				<td><label>



				 <?php echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued,'id="ddlIssued" ');?>



				</label></td>



</td>

<!--

            <tr>



                <td><?php echo PAYABLEACCOUNT;?></td>



                <td><label>



                    <?php echo form_dropdown('ddlPayableAccount', $list_payable, $this->ddlPayableAccount, 'id="ddlPayableAccount" class="ddlPayableAccount"');?>



                </label></td>

</tr>

-->

<tr>

                <td><?php echo COUNTRY;?></td>



                <td><label>



                 <?php echo form_dropdown('ddlCountry', $list_country_value, $this->ddlCountry,'id="ddlCountry" class="chosen-select"');?>



                </label></td>





                <td><?php echo CITYNAME;?>&nbsp;<span class="warning"></span></td>



                <td><label><span id="city">



                <?php echo form_dropdown('ddlCity', $list_city_value, $this->ddlCity,'id="ddlCity" class="chosen-select"');?>



                </span></label></td>









				<td><?php echo HOTELNAME;?>&nbsp;<span class="warning"></span></td>



                <td colspan="5"><label>



                <?php echo form_dropdown('ddlHotel', $list_hotel_value, $this->ddlHotel,'id="ddlHotel" class="chosen-select"');?>



                </label></td>



			</tr>



			



			<tr>



				<td><?php echo CHECKINOUTFROM ;?>&nbsp;<span class="warning"></span></td>



                <td><label>



                <input name="txtCheckIn" id="txtCheckIn" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCheckIn);?>">



                </label></td>



				



				<td><?php echo CHECKINOUTTO;?>&nbsp;<span class="warning"></span></td>



                <td><label>



                <input name="txtCheckOut" id="txtCheckOut" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCheckOut);?>">



                </label></td>



			</tr>

			<tr>

                <td><?php echo ACNAME;?> <span class="warning"></span></td>

                <td><label>

                    <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>

                </label></td>



                <td><?php echo SUBACCOUNT;?>&nbsp;<span class="warning"></span></td>

                <td colspan="5"><label>

                <span id="subAcc">

                    <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>

                </span>

                </label></td>

			</tr>

	

<!--

			<tr>



                <td><?php echo ACNAME;?> <span class="warning"></span></td>



                <td><label>



                    <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount"');?>



                </label></td>







                <td><?php echo SUBACCOUNT;?>&nbsp;<span class="warning"></span></td>



                <td colspan="5"><label>



                    <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"');?>



                </label></td>



			</tr>

-->

<!--            <tr>



                <td><?php echo SECTOR;?> <span class="warning"></span></td>



                <td><label>



                <input name="txtSector1" id="txtSector1" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector1);?>">



                </label></td>



				<td><label>



                <input name="txtSector2" id="txtSector2" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector2);?>">



                </label></td>



				<td><label>



                <input name="txtSector3" id="txtSector3" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector3);?>">



                </label></td>



				<td><label>



                <input name="txtSector4" id="txtSector4" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector4);?>">



                </label></td>



				<td><label>



                <input name="txtSector5" id="txtSector5" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector5);?>">



                </label></td>



				<td><label>



                <input name="txtSector6" id="txtSector6" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector6);?>">



                </label></td>



				<td><label>



                <input name="txtSector7" id="txtSector7" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector7);?>">



                </label></td>



			</tr>

-->



		<tr>



                <td><?php echo SALESTYPE;?> <span class="warning"></span></td>



                <td><label>



                 <?php echo form_dropdown('ddlSalesType', $this->profit_report_model->mainSalesArray(), $this->ddlSalesType,' id="ddlSalesType" class="singleDrop"');?>



                </label></td>



				



				<td><?php echo SALESREFUND;?> <span class="warning"></span></td>



                <td><label>



                 



				 <?php echo form_dropdown('ddlRefund', $this->profit_report_model->mainRefundArray(), $this->ddlRefund,' id="ddlRefund" class="singleDrop"');?>



                </label></td>



				



				<td><?php echo STATUS;?> <span class="warning"></span></td>



                <td><label>



               <?php echo form_dropdown('ddlStatus', $this->profit_report_model->mainStatusArray(), $this->ddlStatus,' id="ddlStatus" class="singleDrop"');?>



                </label></td>



				



            </tr>



			<tr>



				<td><?php echo DESTINATION;?></td>



                <td><label>



                <input name="txtDestination" id="txtDestination" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDestination);?>">



                </label></td>



				



				<td><?php echo GDS;?> <span class="warning"></span></td>



				<td><label>



				 <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS,'id="ddlGDS" class="singleDrop"');?>



				</label></td>



            </tr>



<!--	

			<tr>



				<td><?php echo REPORTTYPE;?> <span class="warning"></span></td>



				<td><label>



				<?php echo form_dropdown('ddlReport', $this->profit_report_model->mainReportArray(), $this->ddlReport,' id="ddlReport"class="singleDrop"');?>



				</label></td>



            </tr>

-->			<tr>

					<td><?php echo SECTOR;?> <span class="warning"></span></td>



                <td colspan="3" width="1"   nowrap="nowrap">

			<label>

                    <input name="txtSector1" id="txtSector1" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector1);?>">

                </label> &nbsp;&nbsp;

				<label>

                    <input name="txtSector2" id="txtSector2" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector2);?>">

                </label> &nbsp;&nbsp;

                <label>

                    <input name="txtSector3" id="txtSector3" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector3);?>">

                </label> &nbsp;&nbsp;

				<label>

                    <input name="txtSector4" id="txtSector4" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector4);?>">

                </label> &nbsp;&nbsp;

				<label>

                    <input name="txtSector5" id="txtSector5" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector5);?>">

                </label> &nbsp;&nbsp;

				<label>

                    <input name="txtSector6" id="txtSector6" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector6);?>">

                </label> &nbsp;&nbsp;

				<label>

                    <input name="txtSector7" id="txtSector7" class="textfield5" type="text" value="<?php echo stripslashes($this->txtSector7);?>">

            </label></td> 	

            </tr>



<!--            <tr>



                <td colspan="5"><label>



                <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>&nbsp;&nbsp;



                <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo FOREIGNCURRENCY;?></label></td>



            </tr>

-->

        </table>



    </td>



</tr>







<tr class="formButs">



    <td>

        <input type="button" name="btnAirline" value="<?php echo AIRLINEREPORT;?>" id="btnAirline"/>

        <input type="button" name="btnPayable" value="<?php echo PAYABLEACCOUNTSREPORT;?>" id="btnPayable">

        <input type="button" name="btnEmployee" value="<?php echo EMPLOYEREPORT;?>" id="btnEmployee">

		<!--<input type="button" name="btnSector" value="<?php echo SECTORREPORT;?>" id="btnSector">

        <input type="button" name="btnDestination" value="<?php echo DESTINATIONREPORT;?>" id="btnDestination">
-->
        <input type="button" name="btnStatus" value="<?php echo BYSTATUSREPORT;?>" id="btnStatus">

<!--

        <input type="button" name="btnGdsbooking" value="<?php echo GDSBOOKINGREPORT;?>" id="btnGdsbooking">

		<input type="button" name="btnGdsticketed" value="<?php echo GDSTICKETEDREPORT;?>" id="btnGdsticketed">

	    <input type="button" name="btnGdsstatus" value="<?php echo GDSSTATUSREPORT;?>" id="btnGdsstatus">

        <input type="button" name="btnGdshx" value="<?php echo GDSHXREPORT;?>" id="btnGdshx">

-->

      </td>

</tr>

<tr class="formButs">

    <td>

<!--   		<input type="button" name="btnHotelagent" value="<?php echo REPORTBYAGENT;?>" id="btnHotelagent"> -->

        <input type="button" name="btnHotelcheckin" value="<?php echo REPORTBYCHECKIN;?>" id="btnHotelcheckin">

        <input type="button" name="btnHotelcheckout" value="<?php echo REPORTBYCHECKOUT;?>" id="btnHotelcheckout">

        <input type="button" name="btnHotelcountry" value="<?php echo REPORTBYCOUNTRY;?>" id="btnHotelcountry">

        <input type="button" name="btnHotelcity" value="<?php echo REPORTBYCITY;?>" id="btnHotelcity">

		<input type="button" name="btnHotel" value="<?php echo REPORTBYHOTEL;?>" id="btnHotel">

<!--        <input type="button" name="btnProfit" value="<?php echo SALESREPORTWITHPROFIT;?>" id="btnProfit">

        <input type="button" name="btnHotelemployee" value="<?php echo REPORTBYEMPLOYEE;?>" id="btnHotelemployee"> -->

    </td>



</tr>



</tbody>



</table>



</div>



    </form>

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

</div>

