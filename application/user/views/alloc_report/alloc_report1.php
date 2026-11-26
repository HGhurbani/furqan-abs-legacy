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

        submitHandler:function () {

        }

    });



    function getLocation(nVal) {

        $.ajax({

            type:'POST',

            data:{ddlDivision:nVal},

            url:'<?php echo base_url().'index.php/alloc_report/filterDivision/'.$this->uri->segment(3);?>',

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

         url:'<?php echo base_url().'index.php/alloc_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',

         dataType:'html',

         success: function(response) {

                 var issued = $('#ddlEmployee').val();

                 $('#Emp').html(response);

                 $('#ddlEmployee').val(issued);

         }

     });



}



    function getAccount(nVal) {

        $.ajax({

            type:'POST',

            data:{ddlAccGroup:nVal},

            url:'<?php echo base_url().'index.php/alloc_report/filterAccount/'.$this->uri->segment(3);?>',

            dataType:'html',

            success: function(response) {

                $('#Acc').html(response);

            }

        });

    }



    function getSubAccount(nVal) {

        $.ajax({

            type:'POST',

            data:{ddlAccount:nVal},

            url:'<?php echo base_url().'index.php/alloc_report/filterSubAccount/'.$this->uri->segment(3);?>',

            dataType:'html',

            success:function (response) {

                $('#subAcc1').html(response);

            }

        });

    }



    $(document).ready(function () {

        $("#txtFromDate").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtToDate").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtCheckIn").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtCheckOut").datepick({ dateFormat:'yyyy-mm-dd'});

        $("#txtTillDate").datepick({ dateFormat:'yyyy-mm-dd'});



        getLocation($('#ddlDivision').val());









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



        //getAccount($('#ddlAccGroup').val());



        $('#ddlAccGroup').change(function(){

            var nVal = $('#ddlAccGroup').val();

            getAccount(nVal);

        });



        getSubAccount($('#ddlAccount').val());



        $('#Acc').click(function () {

            var nVal = $('#ddlAccount').val();

            getSubAccount(nVal);

        });

        $('#ddlAccount').change(function () {

            var nVal = $('#ddlAccount').val();

            getSubAccount(nVal);

        });

        $('#btnReceivable').click(function()

        {

            getVal('Receivable');

        });



        $('#btnPayable').click(function()

        {

            getVal('Payable');

        });



        $('#btnPayableReconciliation').click(function()

        {

            getVal('PayableReconciliation');

        });



        $('#btnBSPReconciliation').click(function()

        {

            getVal('BSPReconciliation');

        });



        $('#btnBankReconciliation').click(function()

        {

            getVal('BankReconciliation');

        });



        function getVal(nval)

        {

            switch(nval)

            {

                case "Receivable":

                    var path = '<?php echo base_url().'index.php/alloc_report/getReceivable/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;



                case "Payable":

                    var path = '<?php echo base_url().'index.php/alloc_report/getPayable/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;



                case "PayableReconciliation":

                    var path = '<?php echo base_url().'index.php/alloc_report/getPayableReconciliation/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;



                case "BSPReconciliation":

                    var path = '<?php echo base_url().'index.php/alloc_report/getBSPReconciliation/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;



                case "BankReconciliation":

                    var path = '<?php echo base_url().'index.php/alloc_report/getBankReconciliation/'.$this->uri->segment(3);?>';

                    var sess_clear = 'y';

                    break;

            }//end switch



            var nDivision       = $('#ddlDivision').val();

            var nLocation       = $('#ddlLocation').val();

            var nLocation2      = $('#ddlLocation2').val();

            var nToDate         = $('#txtToDate').val();

            var nFromDate       = $('#txtFromDate').val();

            var nFromYear       = $('#txtFromYear').val();

            var nToYear         = $('#txtToYear').val();

            var nTillDate       = $('#txtTillDate').val();

            var nAccGroup       = $('#ddlAccGroup').val();

            var nCustomer       = $('#ddlCustomer').val();

            var nAccount        = $('#ddlAccount').val();

            var nSubAccount     = $('#ddlSubAccount').val();

            var accId           = nSubAccount.split("-");

            var nEmployee       = $('#ddlEmployee').val();

            var nAgent          = $('#ddlAgent').val();

            var nReport         = $('#ddlReport').val();

            $.ajax({

                type:'POST',

                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,

                    txtFromYear:nFromYear,txtToYear:nToYear,txtTillDate:nTillDate,ddlAccGroup:nAccGroup,ddlCustomer:nCustomer,

                    ddlAccount:nAccount,ddlSubAccount:accId[0],tableType:accId[1],ddlEmployee:nEmployee,ddlAgent:nAgent,ddlReport:nReport,

                    sess_clear:sess_clear},

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

<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1"

        type="text/javascript"></script>

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

    <?php echo form_open('alloc_report/editReprint/'.$this->uri->segment(3).'/'. $this->uri->segment(4), $attributes); ?>

    <?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Allocation Report ...</h3><br>
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

                            <input name="txtFromDate" id="txtFromDate" class="textfield5" type="text" value="<?php echo $this->txtFromDate;?>">

                        </td>



                        <td><?php echo TODATE;?></td>

                        <td>

                            <input name="txtToDate" id="txtToDate" class="textfield5" type="text" value="<?php echo $this->txtToDate;?>">

                        </td>



                    </tr>



                   <!--  <tr>



                        <td><?php echo FROMYEAR;?> <span class="warning"></span></td>

                        <td><label>

                            <input name="txtFromYear" id="txtFromYear" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFromYear);?>">

                        </label></td>

                        <td><?php echo TOYEAR;?> <span class="warning"></span></td>

                        <td><label>

                            <input name="txtToYear" id="txtToYear" class="textfield5" type="text" value="<?php echo stripslashes($this->txtToYear);?>">

                        </label></td>



                        <td><?php echo TILLDATE;?> <span class="warning"></span></td>

                        <td><label>

                            <input name="txtTillDate" id="txtTillDate" class="textfield5" type="text" value="<?php echo $this->txtTillDate;?>">

                        </label></td>

                    </tr>-->

                    <tr>

                        <!--<td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>

                        <td><label>

                            <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup, 'id="ddlAccGroup" class="chosen-select"');?>

                        </label></td>
-->


                        <td><?php echo ACNAME;?> <span class="warning"></span></td>

                        <td>

                        <span id="Acc">

                            <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>

                        </span>

                        </td>



                        <td><?php echo SUBACCOUNT;?></td>

                        <td>

                        <span id="subAcc1">

                            <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, 'id="ddlSubAccount" ');?>

                        </span>

                        </td>

                    </tr>



                    <!--<tr>

                        <td><?php echo PARENTCUSAC;?> <span class="warning"></span></td>

                        <td><label>

                            <?php echo form_dropdown('ddlCustomer', $list_customer_value, $this->ddlCustomer, 'id="ddlCustomer" class="chosen-select"');?>

                        </label></td>



                        <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>

                        <td><label>

                        <span id="Emp">

                            <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee" class="chosen-select"');?>

                        </span>

                        </label></td>



                        <td><?php echo AGENTAC;;?> <span class="warning"></span></td>

                        <td><label>

                            <?php echo form_dropdown('ddlAgent', $list_agent, $this->ddlAgent, 'id="ddlAgent"');?>

                        </label></td>

                    </tr>

                    <tr>

                        <td><?php echo REPORTTYPE;?> <span class="warning"></span></td>

                        <td><label>

                            <?php echo form_dropdown('ddlReport', $this->alloc_report_model->mainReportArray(), $this->ddlReport, ' id="ddlReport"class="singleDrop"');?>

                        </label></td>

                    </tr>
-->


                    <!--<tr>

                        <td colspan="5"><label>

                            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>

                            &nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo FOREIGNCURRENCY;?>

                        </label></td>

                    </tr>-->

                </table>

            </td>

        </tr>



        <tr class="formButs">

            <td><!--<input type="button" name="btnReceivable" id="btnReceivable" value="<?php echo RECEIVABLEALLOCATIONREPORT;?>"/>

                --><input type="button" name="btnPayable" id="btnPayable" value="<?php echo ALLOCATIONREPORT;?>">

                <!--<input type="button" name="btnPayableReconciliation" id="btnPayableReconciliation" value="<?php /*echo PAYABLERECONCILIATIONREPORT;*/?>">

                <input type="button" name="btnBSPReconciliation" id="btnBSPReconciliation" value="<?php /*echo BSPRECONCILIATIONREPORT;*/?>">

                <input type="button" name="btnBankReconciliation" id="btnBankReconciliation" value="<?php /*echo BANKRECONCILIATIONREPORT;*/?>">-->

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

