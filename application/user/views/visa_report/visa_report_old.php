<?php

?>

<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">

<script language="javascript1.1" type="text/javascript">



    function getLocation(nVal) {

        $.ajax({

            type:'POST',

            data:{ddlDivision:nVal},

            url:'<?php echo base_url() . 'index.php/visa_report/filterDivision/' . $this->uri->segment(3);?>',

            dataType:'html',

            beforeSend:function () {

                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');

            },

            success:function (response) {

                $('#ddlLocation2').html(response);

                $('#ddlLocation').html(response);

            }

        });

    }

    $(document).ready(function () {

        $("#txtToDate").datepick({  maxDate: 0,dateFormat:'yyyy-mm-dd'});

        $("#txtFromDate").datepick({  maxDate: 0,dateFormat:'yyyy-mm-dd'});

        $("#txtCheckIn").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtCheckOut").datepick({ dateFormat:'yyyy-mm-dd'});



//        getLocation($('#ddlDivision').val());



        $('#ddlDivision').change(function () {

            var nVal = $('#ddlDivision').val();

            getLocation(nVal);

        });



    function getSubAccount(nVal) {

        $.ajax({

            type:'POST',

            data:{ddlAccount:nVal},

            url:'<?php echo base_url().'index.php/visa_report/filterSubAccount/'.$this->uri->segment(3);?>',

            dataType:'html',

            success:function (response) {

                $('#subAcc').html(response);

            }

        });

    }

        //getSubAccount($('#ddlAccount').val());



        $('#ddlAccount').change(function () {

            var nVal = $('#ddlAccount').val();

            getSubAccount(nVal);

        });


$('#btnReport').click(function(){

            var nDivision       = $('#ddlDivision').val();

            var nLocation       = $('#ddlLocation').val();

            var nLocation2      = $('#ddlLocation2').val();

            var nToDate         = $('#txtToDate').val();

            var nFromDate       = $('#txtFromDate').val();

            var nPayableAccount = $('#ddlPayableAccount').val();


            var nIssued         = $('#ddlIssued').val();



            var nAccount        = $('#ddlAccount').val();

            var nSubAccount     = $('#ddlSubAccount').val();

            var nRefund         = 'All'//$('#ddlRefund').val();

            var nReport         = $('#ddlReport').val();

            var sess_clear      = 'y';

//alert(nSubAccount);

              $.ajax({

                type:'POST',

                data:{ddlDivision:nDivision,ddlLocation:nLocation, ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,ddlPayableAccount:nPayableAccount,ddlIssued:nIssued,ddlAccount:nAccount,ddlSubAccount:nSubAccount,ddlRefund:nRefund,ddlReport:nReport,sess_clear:sess_clear},

                url:'<?php echo base_url().'index.php/visa_report/filterList/'.$this->uri->segment(3);?>',

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

        });


        $('#btnstkReport').click(function(){

            var nDivision       = $('#ddlDivision').val();

            var nLocation       = $('#ddlLocation').val();

            var nLocation2      = $('#ddlLocation2').val();

            var nToDate         = $('#txtToDate').val();

            var nFromDate       = $('#txtFromDate').val();

            var nPayableAccount = $('#ddlPayableAccount').val();


            var nIssued         = $('#ddlIssued').val();



            var nAccount        = $('#ddlAccount').val();

            var nSubAccount     = $('#ddlSubAccount').val();

            var nRefund         = 'All'//$('#ddlRefund').val();

            var nReport         = $('#ddlReport').val();

            var sess_clear      = 'y';

//alert(nSubAccount);

              $.ajax({

                type:'POST',

                data:{ddlDivision:nDivision,ddlLocation:nLocation, ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,ddlPayableAccount:nPayableAccount,ddlIssued:nIssued,ddlAccount:nAccount,ddlSubAccount:nSubAccount,ddlRefund:nRefund,ddlReport:nReport,sess_clear:sess_clear},

                url:'<?php echo base_url().'index.php/visa_report/filterList_stk/'.$this->uri->segment(3);?>',

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

        });





       






    });

</script>



<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css"media="all"/>

<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>

<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

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

<?php echo form_open('visa_report/editReprint/' . $this->uri->segment(3) . '/' . $this->uri->segment(4), $attributes); ?>

<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Group Report ...</h3><br>
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

                        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision" class="ddlDivision smallDropdown"');?>

                            </label></td>



                    <td><?php echo LOCATION . '&nbsp;' . FROM;?></td>

                    <td><label>

                        <span id="loc">

                            <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation" class="ddlLocation smallDropdown"');?>

                        </span>

                    </label></td>



                    <td><?php echo LOCATION . '&nbsp;' . TO;?></td>

                    <td><label>

                        <span id="loc2">

                            <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2, 'id="ddlLocation2" class="ddlLocation2 smallDropdown"');?>

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



             <!--  <tr>



                    <td><?php echo ACNAME;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class=" chosen-select " ');?>

                    </label></td>



                    <td><?php echo SUBACCOUNT;?>&nbsp;<span class="warning"></span></td>

                    <td colspan="5"><label>

                    <span id="subAcc">

                        <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>

                    </span>

                    </label></td>







                </tr>-->



                <tr>

                    <td><?php echo REPORTTYPE;?> <span class="warning"></span></td>

                    <td><label>

                        <?php echo form_dropdown('ddlReport', $this->visa_report_model->mainReportArray(), $this->ddlReport, ' id="ddlReport"class="singleDrop"');?>

                    </label></td>

                </tr>



            </table>

        </td>

    </tr>





    <tr class="formButs">

        <td>&nbsp;</td>

        <td colspan="2">

           
<input type="button" name="btnstkReport" value="<?php echo "Stock Report";?>" id="btnstkReport">
<input type="button" name="btnReport" value="<?php echo "Group Report";?>" id="btnReport">
           <!--<input type="button" name="btnSlsReportVendor" value="<?php echo SALESREPORTBYVENDOR;?>" id="btnSlsReportVendor">

           <input type="button" name="btnSlsReport" value="<?php echo SALESREPORT;?>" id="btnSlsReport">-->

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

