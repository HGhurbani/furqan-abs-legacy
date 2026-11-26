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
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>

<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<script language="javascript1.1" type="text/javascript">



    $(document).ready(function () {

        $("#txtFromDate").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtToDate").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtCheckIn").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtCheckOut").datepick({ dateFormat:'yyyy-mm-dd'});



	function getLocation(nVal) {

			$.ajax({

				type:'POST',

				data:{ddlDivision:nVal},

				url:'<?php echo base_url() . 'index.php/customer_report/filterDivision/' . $this->uri->segment(3);?>',

				dataType:'html',

				beforeSend:function () {

					$('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

				},

				success:function (response) {

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
         url:'<?php echo base_url().'index.php/customer_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var issued = $('#ddlIssued').val();
                 $('#ddlIssued').html(response);
                 $('#ddlIssued').val(issued);
         }
     });

}



//        getLocation($('#ddlDivision').val());






        $('#loc').change(function(){
            changeAllAccount();
            });

        $('#loc2').change(function(){
            changeAllAccount();
            });
changeAllAccount();


        $('#ddlDivision').change(function () {

            var nVal = $('#ddlDivision').val();

            getLocation(nVal);

        });



        $('#ddlSubAccount').change(function () {

            var nVal = $('#ddlSubAccount').val();

            $.ajax({

                type:'POST',

                data:{ddlSubAccount:nVal},

                url:'<?php echo base_url() . 'index.php/customer_report/filterAccount/' . $this->uri->segment(3);?>',

                dataType:'html',

                success:function (response) {

                    $('#acc').html(response);

                }

            });

        });



        $('#ddlCountry').change(function () {

            var nVal = $('#ddlCountry').val();

            $.ajax({

                type:'POST',

                data:{ddlCountry:nVal},

                url:'<?php echo base_url() . 'index.php/customer_report/filterCountry/' . $this->uri->segment(3);?>',

                dataType:'html',

                beforeSend:function () {

                    $('#city').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

                },

                success:function (response) {

                    $('#city').html(response);

                }

            });

        });



        $('#btnCustomer').click(function()

        {

			getVal('All');

        });



        $('#btnAirline').click(function()

        {

			getVal('Airline');

        });



        $('#btnHotel').click(function()

        {

			getVal('Hotel');

        });



		//function

		function getVal(nval)

		{

			switch(nval)

			{

				case "All":

					var path = '<?php echo base_url().'index.php/customer_report/getReport/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

				break;



				case "Hotel":

					var path = '<?php echo base_url().'index.php/customer_report/getHotelReport/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

				break;



				case "Airline":

					var path = '<?php echo base_url().'index.php/customer_report/getAirlineReport/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

				break;

			}//end switch



			var nDivision       = $('#ddlDivision').val();

            var nLocation       = $('#ddlLocation').val();

            var nLocation2      = $('#ddlLocation2').val();

            var nToDate         = $('#txtToDate').val();

            var nFromDate       = $('#txtFromDate').val();

            var nVendor         = $('#ddlVendor').val();

            var nAirline        = $('#ddlAirline').val();

            var nCountry        = $('#ddlCountry').val();

            var nPayableAccount = $('#ddlPayableAccount').val();

            var nCity           = $('#ddlCity').val();

            var nIssued         = $('#ddlIssued').val();

            var nHotel          = $('#ddlHotel').val();

            var nCheckIn        = $('#txtCheckIn').val();

            var nCheckOut       = $('#txtCheckOut').val();

            var nAccount        = $('#ddlAccount').val();

            var nSubAccount     = $('#ddlSubAccount').val();

            var txtSector1      = $('#txtSector1').val();

            var txtSector2      = $('#txtSector2').val();

            var txtSector3      = $('#txtSector3').val();

            var txtSector4      = $('#txtSector4').val();

            var txtSector5      = $('#txtSector5').val();

            var txtSector6      = $('#txtSector6').val();

            var txtSector7      = $('#txtSector7').val();

            var txtDestination  = $('#txtDestination').val();

            var nGds            = $('#ddlGDS').val();

            var nSalesType      = $('#ddlSalesType').val();

            var nRefund         = $('#ddlRefund').val();

            var nReport         = $('#ddlReport').val();

            $.ajax({

                type:'POST',

                data:{ddlDivision:nDivision,ddlLocation:nLocation, ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,ddlVendor:nVendor,

                    ddlCountry:nCountry,ddlAirline:nAirline,ddlPayableAccount:nPayableAccount,ddlCity:nCity, ddlIssued:nIssued,ddlHotel:nHotel,txtCheckIn:nCheckIn,

                    txtCheckOut:nCheckOut,ddlAccount:nAccount,ddlSubAccount:nSubAccount,txtSector1:txtSector1,txtSector2:txtSector2,txtSector3:txtSector3,

                    txtSector4:txtSector4,txtSector5:txtSector5,txtSector6:txtSector6,txtSector7:txtSector7,txtDestination:txtDestination,

                    ddlGDS:nGds,ddlSalesType:nSalesType,ddlRefund:nRefund,ddlReport:nReport,sess_clear:sess_clear},

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



<?php

echo $this->common_model->successMsg($this->uri->segment(5));

echo validation_errors('<p class="errorClass">', '</p>');

$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');



if (isset($error_value) && trim($error_value) != '') {

    ?>

<code><?php echo $error_value;?></code>

<?php

}//end if 

?>

<?php $this->load->view('general/admin_menu'); ?>

<div class="formHead">

    <h4><?php echo $this->showTitle;?></h4>

</div>

<div class="formBorder">

<?php echo form_open('customer_report/editReprint/' . $this->uri->segment(3) . '/' . $this->uri->segment(4), $attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Customer Report ...</h3><br>
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

                        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision" class="ddlDivision"');?>

                    </label></td>



                    <td><?php echo LOCATION . '&nbsp;' . FROM;?></td>

                    <td><label>

                        <span id="loc">

                            <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation" class="ddlLocation"');?>

                        </span>

                    </label></td>



                    <td><?php echo LOCATION . '&nbsp;' . TO;?></td>

                    <td><label>

                        <span id="loc2">

                            <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2, 'id="ddlLocation2" class="ddlLocation2"');?>

                        </span>

                    </label></td>

                </tr>



                <tr>

                    <td><?php echo FROMDATE;?></td>

                    <td>

                        <input name="txtFromDate" id="txtFromDate" class="textfield5" type="text" value="<?php echo $this->txtFromDate;?>">

                    </td>



                    <td><?php echo TODATE;?></td>

                    <td>

                        <input name="txtToDate" id="txtToDate" class="textfield5" type="text" value="<?php echo $this->txtToDate;?>">

                    </td>


<!--
                    <td><?php echo VENDOR;?></td>

                    <td><label>

                        <?php echo form_dropdown('ddlVendor', $list_vendor_value, $this->ddlVendor, 'id="ddlVendor" class="ddlVendor"');?>

                    </label></td>
-->
                </tr>



                <tr>



                    <td><?php echo AIRLINE;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlAirline', $list_airline_value, $this->ddlAirline, 'id="ddlAirline"  class="chosen-select"');?>

                    </label></td>
                    <td><?php echo ISSUEDBY;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued, 'id="ddlIssued"  class="chosen-select"');?>

                    </label></td>



               </tr>



                <tr>

  <!--                  <td><?php echo PAYABLEACCOUNT;?></td>

                    <td><label>

                        <?php echo form_dropdown('ddlPayableAccount', $list_payable, $this->ddlPayableAccount, 'id="ddlPayableAccount" class="ddlPayableAccount"');?>

                    </label></td>


--> 

                    <td><?php echo COUNTRY;?></td>

                    <td><label>

                        <?php echo form_dropdown('ddlCountry', $list_country_value, $this->ddlCountry, 'id="ddlCountry"  class="chosen-select"');?>

                    </label></td>

 

                    <td><?php echo CITYNAME;?>&nbsp;<span class="warning"></span></td>

                    <td><label><span id="city">

                <?php echo form_dropdown('ddlCity', $list_city_value, $this->ddlCity, 'id="ddlCity" class="chosen-select"');?>

                </span></label></td>





                    <td><?php echo HOTELNAME;?>&nbsp;<span class="warning"></span></td>

                    <td colspan="5"><label>

                        <?php echo form_dropdown('ddlHotel', $list_hotel_value, $this->ddlHotel, 'id="ddlHotel" class="chosen-select"');?>

                    </label></td>

                </tr>

<!--

                <tr>

                    <td><?php echo CHECKIN;?>&nbsp;<span class="warning"></span></td>

                    <td><label>

                        <input name="txtCheckIn" id="txtCheckIn" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCheckIn);?>">

                    </label></td>



                    <td><?php echo CHECKOUT;?>&nbsp;<span class="warning"></span></td>

                    <td><label>

                        <input name="txtCheckOut" id="txtCheckOut" class="textfield5" type="text" value="<?php echo stripslashes($this->txtCheckOut);?>">

                    </label></td>

                </tr>

-->

                <tr>

                    <td><?php echo ACHEAD;?></td>

                    <td>

                        <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"  class="chosen-select"');?>

                    </td>

                    <td><?php echo  SUBACCOUNT;?> <span class="warning"></span></td>

                    <td>

                    <span id="acc">
                        <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>
                    </span>

                    </td>




                </tr>


<!--
                <tr>

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

                        <input name="txtSector4" id="txtSector4" class="textfield5" type="text"  value="<?php echo stripslashes($this->txtSector4);?>">

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



                <tr>

                    <td><?php echo DESTINATION;?></td>

                    <td><label>

                        <input name="txtDestination" id="txtDestination" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDestination);?>">

                    </label></td>



                    <td><?php echo GDS;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS, 'id="ddlGDS"');?>

                    </label></td>

                </tr>

-->

                <tr>

                    <td><?php echo SALESTYPE;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlSalesType', $this->customer_report_model->mainSalesArray(), $this->ddlSalesType, ' id="ddlSalesType"');?>

                    </label></td>



                    <td><?php echo SALESREFUND;?> <span class="warning"></span></td>

                    <td><label>



                        <?php echo form_dropdown('ddlRefund', $this->customer_report_model->mainRefundArray(), $this->ddlRefund, ' id="ddlRefund"');?>

                    </label></td>



                </tr>



<!--                <tr>

                    <td><?php echo REPORTTYPE;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlReport', $this->customer_report_model->mainReportArray(), $this->ddlReport, ' id="ddlReport"');?>

                    </label></td>
                </tr>

                <tr>
                    <td colspan="5"><label>

                        <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>

                        &nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo FOREIGNCURRENCY;?>

                    </label></td>

                </tr>


-->

            </table>

        </td>

    </tr>

    <tr class="formButs">

        <td>&nbsp;</td>

        <td colspan="9">

            <input type="button" name="btnCustomer" value="<?php echo CUSTOMERREPORT;?>" id="btnCustomer">

            <input type="button" name="btnAirline" value="<?php echo CUSTOMERREPORTBYAIRLINE;?>" id="btnAirline">

            <input type="button" name="btnHotel" value="<?php echo CUSTOMERREPORTBYHOTEL;?>" id="btnHotel">



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
