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
                $('#ddlAccount').html(response);
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
                $('#ddlSubAccount').html(response);
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
    <?php echo form_open('alloc_report/editReprint/'.$this->uri->segment(3).'/'. $this->uri->segment(4), $attributes); ?>
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

                    <tr>

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
                    </tr>
                    <tr>
                        <td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
                        <td><label>
                            <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup, 'id="ddlAccGroup"');?>
                        </label></td>

                        <td><?php echo ACNAME;?> <span class="warning"></span></td>
                        <td>
                            <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount"');?>
                        </td>

                        <td><?php echo SUBACCOUNT;?></td>
                        <td>
                            <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"');?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo PARENTCUSAC;?> <span class="warning"></span></td>
                        <td><label>
                            <?php echo form_dropdown('ddlCustomer', $list_customer_value, $this->ddlCustomer, 'id="ddlCustomer"');?>
                        </label></td>

                        <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>
                        <td><label>
                            <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee"');?>
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
            <td><input type="button" name="btnReceivable" id="btnReceivable" value="<?php echo RECEIVABLEALLOCATIONREPORT;?>"/>
                <input type="button" name="btnPayable" id="btnPayable" value="<?php echo PAYABLEALLOCATIONREPORT;?>">
                <!--<input type="button" name="btnPayableReconciliation" id="btnPayableReconciliation" value="<?php /*echo PAYABLERECONCILIATIONREPORT;*/?>">
                <input type="button" name="btnBSPReconciliation" id="btnBSPReconciliation" value="<?php /*echo BSPRECONCILIATIONREPORT;*/?>">
                <input type="button" name="btnBankReconciliation" id="btnBankReconciliation" value="<?php /*echo BANKRECONCILIATIONREPORT;*/?>">-->
            </td>
        </tr>
        </tbody>
    </table>
    </div>
    </form>
</div>