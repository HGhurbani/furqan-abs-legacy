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
            url:'<?php echo base_url().'index.php/ageing_report/filterDivision/'.$this->uri->segment(3);?>',
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

    function getAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccGroup:nVal},
            url:'<?php echo base_url().'index.php/ageing_report/filterAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#ddlAccount').html(response);
            }
        });
    }

    function getSubAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccount:nVal},
            url:'<?php echo base_url().'index.php/ageing_report/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success:function (response) {
                $('#subAcc').html(response);
            }
        });
    }

    function changeSlab(nVal) {
        $('#txtSlab1').val(nVal);
        $('#txtSlab2').val(nVal * 2);
        $('#txtSlab3').val(nVal * 3 );
        $('#txtSlab4').val(nVal * 4);
    }

    $(document).ready(function () {
        $("#txtFromDate").datepick({ dateFormat:'dd-mm-yyyy'});
        $("#txtToDate").datepick({ dateFormat:'dd-mm-yyyy'});
        $("#txtTillDate").datepick({ dateFormat:'dd-mm-yyyy'});

        getLocation($('#ddlDivision').val());

        $('#txtSlab1').keyup(function () {
            var nVal =  $('#txtSlab1').val();
            changeSlab(nVal);
        });


        $('#txtSlab2').keyup(function () {
            var nVal =  $('#txtSlab2').val();
            nVal = nVal/2;
            changeSlab(nVal);
        });


        $('#txtSlab3').keyup(function () {
            var nVal =  $('#txtSlab3').val();
            nVal = nVal/3;
            changeSlab(nVal);
        });


        $('#txtSlab4').keyup(function () {
            var nVal =  $('#txtSlab4').val();
            nVal = nVal/4;
            changeSlab(nVal);
        });


        $('#ddlDivision').change(function () {
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

		if($('#ddlAccGroup').val() != ''){
       		 //getAccount($('#ddlAccGroup').val());
		}//end if

        $('#ddlAccGroup').change(function(){
            var nVal = $('#ddlAccGroup').val();
            getAccount(nVal);
        });

        getSubAccount($('#ddlAccount').val());

        $('#ddlAccount').change(function () {
            var nVal = $('#ddlAccount').val();
            getSubAccount(nVal);
        });

        $('#btnReceivable').click(function () {
            getVal('Receivable');
        });

        $('#btnReceivableDetail').click(function () {
            getVal('ReceivableDetail');
        });

        //function
        function getVal(nval) {
            switch (nval) {
                case "Receivable":
                    var path = '<?php echo base_url().'index.php/ageing_report/getReceivable/'.$this->uri->segment(3);?>';
                    break;

                case "ReceivableDetail":
                    var path = '<?php echo base_url().'index.php/ageing_report/getReceivableDetail/'.$this->uri->segment(3);?>';
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
            var nSlab1           = $('#txtSlab1').val();
            var nSlab2           = $('#txtSlab2').val();
            var nSlab3           = $('#txtSlab3').val();
            var nSlab4           = $('#txtSlab4').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,
                      txtFromYear:nFromYear,txtToYear:nToYear,txtTillDate:nTillDate,ddlAccGroup:nAccGroup,ddlCustomer:nCustomer,
                      ddlAccount:nAccount,ddlSubAccount:accId[0],tableType:accId[1],ddlEmployee:nEmployee,ddlAgent:nAgent,ddlReport:nReport,
                      txtSlab1:nSlab1,txtSlab2:nSlab2,txtSlab3:nSlab3,txtSlab4:nSlab4},
                url:path,
                dataType:'html',
                success:function (response) {
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
    <?php echo form_open('ageing_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4), $attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>
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
                    </tr>

                    <tr>
<!--                        <td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
                        <td><label>
                            <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup, 'id="ddlAccGroup"');?>
                        </label></td>
-->
                        <td><?php echo ACNAME;?> <span class="warning"></span></td>
                        <td>
                            <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>
                        </td>

                        <td><?php echo SUBACCOUNT;?></td>
                        <td>
                        <span id="subAcc">
                            <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>
                        </span>
                        </td>
                    </tr>
<!--
                    <tr>
                        <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>
                        <td><label>
                            <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee"');?>
                        </label></td>
                    </tr>
-->
                    <tr>
                        <!--<td><?php /*echo PARENTCUSAC;*/?> <span class="warning"></span></td>
                        <td><label>
                            <?php /*echo form_dropdown('ddlCustomer', $list_customer_value, $this->ddlCustomer, 'id="ddlCustomer"');*/?>
                        </label></td>-->

                        <!--<td><?php /*echo AGENTAC;;*/?> <span class="warning"></span></td>
                        <td><label>
                            <?php /*echo form_dropdown('ddlAgent', $list_agent, $this->ddlAgent, 'id="ddlAgent"');*/?>
                        </label></td>-->
                    </tr>

                    <tr> <td colspan="6"> <table> <tr>
                        <td><?php echo AGEINGSLAB;?> <span class="warning"></span></td>
                        <td><?php echo SLAB1;?> <span class="warning"></span></td>
                        <td>
                            <input name="txtSlab1" id="txtSlab1" class="textfield5" type="text" value="<?php echo $this->txtSlab1; ?>">
                        </td>

                        <td><?php echo SLAB2;?> <span class="warning"></span></td>
                        <td>
                            <input name="txtSlab2" id="txtSlab2" class="textfield5" type="text" value="<?php echo $this->txtSlab2;?>">
                        </td>
                        <td><?php echo SLAB3;?> <span class="warning"></span></td>
                        <td>
                            <input name="txtSlab3" id="txtSlab3" class="textfield5" type="text" value="<?php echo $this->txtSlab3;?>">
                        </td>
                        <td><?php echo SLAB4;?> <span class="warning"></span></td>
                        <td>
                            <input name="txtSlab4" id="txtSlab4" class="textfield5" type="text" value="<?php echo $this->txtSlab4;?>">
                        </td> </tr> </table> </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="formButs">
            <td>
                <input type="button" name="btnReceivable" id="btnReceivable" value="<?php echo RECEIVABLEAGEING;?>"/>
                <input type="button" name="btnReceivableDetail" id="btnReceivableDetail" value="<?php echo RECEIVABLEWITHDETAILAGEING;?>">
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
