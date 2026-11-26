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

            url:'<?php echo base_url().'index.php/gds_report/filterDivision/'.$this->uri->segment(3);?>',

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

         url:'<?php echo base_url().'index.php/gds_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',

         dataType:'html',

         success: function(response) {

                 var issued = $('#ddlIssued').val();

                 $('#ddlIssued').html(response);

                 $('#ddlIssued').val(issued);

         }

     });



}$(document).ready(function()



    {

        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});

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

        $('#ddlPayableAccount').change(function()

        {

            var nVal  = $('#ddlPayableAccount').val();

            $.ajax({

                type:'POST',

                data:{ddlPayableAccount:nVal},

                url:'<?php echo base_url().'index.php/gds_report/filterSubAccount/'.$this->uri->segment(3);?>',

                dataType:'html',

                success: function(response) {

                    $('#subAcc').html(response);

                }

            });

        });



        $('#btnGDSDetails').click(function () {

            getVal('GDSDetails');

        });

        $('#btnGDSSummary').click(function () {

            getVal('GDSSummary');

        });

        $('#btnAirlineDetails').click(function () {

            getVal('AirlineDetails');

        });

        $('#btnAirlineSummary').click(function () {

            getVal('AirlineSummary');

        });

        function getVal(nval) {

            switch (nval) {

                case "GDSDetails":

                    var path = '<?php echo base_url() . 'index.php/gds_report/showGdsDetails/' . $this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;

                case "GDSSummary":

                    var path = '<?php echo base_url() . 'index.php/gds_report/showGdsSummary/' . $this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;

                case "AirlineDetails":

                    var path = '<?php echo base_url() . 'index.php/gds_report/showAirlineDetails/' . $this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;

                case "AirlineSummary":

                    var path = '<?php echo base_url() . 'index.php/gds_report/showAirlineSummary/' . $this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;

            }//end switch

            var nDivision       = $('#ddlDivision').val();

            var nLocation       = $('#ddlLocation').val();

            var nLocation2      = $('#ddlLocation2').val();

            var nToDate         = $('#txtToDate').val();

            var nFromDate       = $('#txtFromDate').val();

            var nAirline        = $('#ddlAirline').val();

            var nPayableAccount = $('#ddlPayableAccount').val();

            var nIssued         = $('#ddlIssued').val();

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

            var nRefund         = $('#ddlRefund').val();

	        var nStatus          = $('#ddlStatus').val();			

            var nReport         = $('#ddlReport').val();

            var nOpenClosed     = $('#ddlOpenClosed').val(); 

			var nReportType     = $('#ddlReportType').val();

            $.ajax({

                type:'POST',

                data:{ddlDivision:nDivision,ddlLocation:nLocation, ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,

                    ddlAirline:nAirline,ddlPayableAccount:nPayableAccount,ddlIssued:nIssued,ddlAccount:nAccount,

                    ddlSubAccount:nSubAccount,txtSector1:txtSector1,txtSector2:txtSector2,txtSector3:txtSector3,

                    txtSector4:txtSector4,txtSector5:txtSector5,txtSector6:txtSector6,txtSector7:txtSector7,

                    txtDestination:txtDestination,ddlGDS:nGds,ddlRefund:nRefund,ddlOpenClosed:nOpenClosed,ddlReport:nReport,ddlReportType:nReportType,ddlStatus:nStatus,sess_clear:sess_clear},

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

<?php echo form_open('gds_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating GDS Report ...</h3><br>
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

				

            </tr>

            <tr>

                <td><?php echo AIRLINE;?> <span class="warning"></span></td>

                <td><label>

                <?php echo form_dropdown('ddlAirline', $list_airline_value, $this->ddlAirline,'id="ddlAirline" class="chosen-select"');?>

                </label></td>



				 <td><?php echo ISSUEDBY;?> <span class="warning"></span></td>

				<td><label>

				 <?php echo form_dropdown('ddlIssued', $list_emp_value, $this->ddlIssued,'id="ddlIssued" class="chosen-select"');?>

				</label></td>



            </tr>

            <tr>

                <td><?php echo PAYABLEACCOUNT;?></td>

                <td><label>

                    <?php echo form_dropdown('ddlPayableAccount', $list_payable,$this->ddlPayableAccount, 'id="ddlPayableAccount" class="ddlPayableAccount chosen-select"');?>

                </label></td>

			    <td><?php echo SUBACCOUNT;?>&nbsp;<span class="warning"></span></td>

                <td colspan="5"><label>

                <span id="subAcc">

                    <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>

                </span>

                </label></td>

			</tr>

 

<tr>	

				<td><?php echo GDS;?> <span class="warning"></span></td>

				<td><label>

				 <?php echo form_dropdown('ddlGDS', $list_gds_value, $this->ddlGDS,'id="ddlGDS" class="singleDrop"');?>

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

			<tr>

				<td><?php echo DESTINATION;?></td>

                <td><label>

                <input name="txtDestination" id="txtDestination" class="textfield5" type="text" value="<?php echo stripslashes($this->txtDestination);?>">

                </label></td>

				

            </tr>

-->

			<tr>

                

				<td><?php echo OPENCLOSED;?> <span class="warning"></span></td>

				<td><label>

				<?php echo form_dropdown('ddlOpenClosed', $this->gds_report_model->mainOpenClosedArray(), $this->ddlOpenClosed,' id="ddlOpenClosed"class="singleDrop"');?>

				</label></td>	

				

				<td><?php echo SALESREFUND;?> <span class="warning"></span></td>

                <td><label>

                 

				 <?php echo form_dropdown('ddlRefund', $this->gds_report_model->mainRefundArray(), $this->ddlRefund,' id="ddlRefund" class="singleDrop"');?>

                </label></td>

				

                <td><?php echo STATUS;?> <span class="warning"></span></td>

                <td><label>

                    <?php echo form_dropdown('ddlStatus', $this->gds_report_model->mainStatusArray(), $this->ddlStatus, ' id="ddlStatus" class="singleDrop"');?>

                </label></td>





<!--				

				<td><?php echo REPORTBY;?> <span class="warning"></span></td>

				<td><label>

				<?php echo form_dropdown('ddlReport', $this->gds_report_model->mainReportArray(), $this->ddlReport,' id="ddlReport"class="singleDrop"');?>

				</label></td>	

				

				<td><?php echo REPORTTYPE;?> <span class="warning"></span></td>

				<td><label>

				<?php echo form_dropdown('ddlReportType', $this->gds_report_model->mainReportTypeArray(), $this->ddlReportType,' id="ddlReportType"class="singleDrop"');?>

				</label></td>

            </tr>

-->

<!--            <tr>

                <td colspan="5"><label>

                    <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>&nbsp;&nbsp;

                    <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo FOREIGNCURRENCY;?></label></td>

            </tr>

-->			

			<!--<tr>

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

            </tr>-->



        </table>

    </td>

</tr>

<tr class="formButs">

    <td>

        <input type="button" name="btnGDSDetails" value="<?php echo GDSREPORTDETAILS;?>" id="btnGDSDetails">

        <input type="button" name="btnGDSSummary" value="<?php echo GDSREPORTSUMMARY;?>" id="btnGDSSummary">
<!--
        <input type="button" name="btnAirlineDetails" value="<?php echo AIRLINEREPORTDETAILS;?>" id="btnAirlineDetails">

        <input type="button" name="btnAirlineSummary" value="<?php echo AIRLINEREPORTSUMMARY;?>" id="btnAirlineSummary">-->

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

