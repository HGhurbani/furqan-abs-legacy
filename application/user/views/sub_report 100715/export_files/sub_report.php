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
        submitHandler: function() {}
    });
   
    function getLocation(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlDivision:nVal},
            url:'<?php echo base_url().'index.php/sub_report/filterDivision/'.$this->uri->segment(3);?>',
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
         url:'<?php echo base_url().'index.php/sub_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var issued = $('#ddlEmployee').val();
                 $('#ddlEmployee').html(response);
                 $('#ddlEmployee').val(issued);
         }
     });

}

/*
    function getAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccGroup:nVal},
            url:'<?php echo base_url().'index.php/sub_report/filterAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#ddlAccount').html(response);
            }
        });
    }
*/
    function getSubAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccount:nVal},
            url:'<?php echo base_url() . 'index.php/sub_report/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success:function (response) {
                $('#ddlSubAccount').html(response);
            }
        });
    }

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'dd-mm-yyyy'});
        $("#txtToDate").datepick({ dateFormat: 'dd-mm-yyyy'});
		$("#txtTillDate").datepick({ dateFormat: 'dd-mm-yyyy'});

        $('#btnCancel').click(function() {
            window.location.replace('<?php echo base_url().'index.php/sub_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });
		getLocation($('#ddlDivision').val());



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
/*
        $('#ddlCustomer').change(function () {
            var nVal = $('#ddlCustomer').val();
            $.ajax({
                type:'POST',
                data:{ddlAccount:nVal},
                url:'<?php echo base_url() . 'index.php/sub_report/filterSubAccount/' . $this->uri->segment(3);?>',
                dataType:'html',
                success:function (response) {
                    $('#ddlSubAccount').html(response);
                }
            });
        });
*/


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

        $('#btnPayable').click(function () {
            getVal('Payable');
        });

        $('#btnReceivableDetail').click(function () {
            getVal('ReceivableDetail');
        });

        $('#btnPayableDetail').click(function () {
            getVal('PayableDetail');
        });

        $('#btnParentCustomer').click(function () {
            getVal('ParentCustomer');
        });

        $('#btnFCDCReport').click(function () {
            getVal('FCDCReport');
        });

        $('#btnSubAgent').click(function () {
            getVal('SubAgent');
        });

        $('#btnEmployeeCustomer').click(function () {
            getVal('EmployeeCustomer');
        });

        $('#btnSubAgentDetail').click(function () {
            getVal('SubAgentDetail');
        });

        //function
        function getVal(nval) {
            switch (nval) {
                case "Receivable":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getReceivableReports/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Payable":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getPayable/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "ReceivableDetail":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getReceivableDetail/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "PayableDetail":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getPayableDetail/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "ParentCustomer":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getParentCustomer/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "FCDCReport":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getFCDCReport/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "SubAgent":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getSubAgent/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "EmployeeCustomer":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getEmployeeCustomer/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "SubAgentDetail":
                    var path = '<?php echo base_url() . 'index.php/sub_report/getSubAgentDetail/' . $this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;
            }//end switch

            var nDivision   = $('#ddlDivision').val();
            var nLocation   = $('#ddlLocation').val();
            var nLocation2  = $('#ddlLocation2').val();
            var nToDate     = $('#txtToDate').val();
            var nFromDate   = $('#txtFromDate').val();
            var nFromYear   = $('#txtFromYear').val();
            var nToYear     = $('#txtToYear').val();
            var nTillDate   = $('#txtTillDate').val();
            var nAccGroup   = $('#ddlAccGroup').val();
            var nCustomer   = $('#ddlCustomer').val();
            var nAccount    = $('#ddlAccount').val();
            var nSubAccount = $('#ddlSubAccount').val();
            var nEmployee   = $('#ddlEmployee').val();
            var nAgent      = $('#ddlAgent').val();
            var nReportType = $('#ddlReport').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision, ddlLocation:nLocation, ddlLocation2:nLocation2, txtToDate:nToDate, txtFromDate:nFromDate,
                    txtFromYear:nFromYear, txtToYear:nToYear, txtTillDate:nTillDate, ddlAccGroup:nAccGroup,ddlCustomer:nCustomer,
                    ddlAccount:nAccount, ddlSubAccount:nSubAccount,ddlEmployee:nEmployee, ddlAgent:nAgent,ddlReport:nReportType,
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
<?php echo form_open('sub_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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
<!--
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
-->
            <tr>
<!--                <td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup, 'id="ddlAccGroup"');?>
                </label></td>

                <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee"');?>
                </label></td>
-->
<!--
                <td><?php echo PARENTCUSAC;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlCustomer', $list_customer_value, $this->ddlCustomer, 'id="ddlCustomer"');?>
                </label></td>
-->				
            </tr>

            <tr>

                <td><?php echo ACNAME;?> <span class="warning"></span></td>
                <td>
                    <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount"');?>
                </td>

                <td><?php echo SUBACCOUNT;?></td>
                <td>
                    <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"');?>
                </td>
            </tr>
<!--
            <tr>

                <td><?php echo AGENTAC;;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlAgent', $list_agent, $this->ddlAgent, 'id="ddlAgent"');?>
                </label></td>
            </tr>
-->			<tr>
                <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee"');?>
                </label></td>

				 <td><?php echo REPORTTYPE;?> <span class="warning"></span></td>
				<td><label>
				<?php echo form_dropdown('ddlReport', $this->sub_report_model->mainReportArray(), $this->ddlReport,' id="ddlReport"class="singleDrop"');?>
				</label></td>
            </tr>
<!--				
		    <tr>
                <td colspan="5"><label>
                    <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>&nbsp;&nbsp;
                    <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo FOREIGNCURRENCY;?></label>
                </td>
		 </tr>
-->        </table>
    </td>
</tr>

<tr class="formButs">
    <td>
        <input type="button" name="btnReceivable" id="btnReceivable" value="<?php echo PAYABLERECEIVABLEREPORT;?>" />
<!--        <input type="button" name="btnPayable" id="btnPayable" value="<?php echo PAYABLEREPORT ;?>"> -->
        <input type="button" name="btnFCDCReport" id="btnFCDCReport" value="<?php echo FCDCREPORT;?>">
<!--        <input type="button" name="btnReceivableDetail" id="btnReceivableDetail" value="<?php echo RECEIVABLEWITHNETDETAIL;?>">
        <input type="button" name="btnPayableDetail" id="btnPayableDetail" value="<?php echo PAYABLEWITHSALESDETAILS;?>"> -->
    </td>
</tr>
<!--
<tr class="formButs">
    <td>
        <input type="button" name="btnParentCustomer" id="btnParentCustomer"  value="<?php echo PARENTCUSTOMERREPORT;?>" />

        <input type="reset" name="btnEmployeeCustomer" id="btnEmployeeCustomer"  value="<?php echo EMPLOYEECUSTOMERREPORT;?>" />
        <input type="reset" name="btnSubAgent" id="btnSubAgent"  value="<?php echo SUBAGENTREPORT;?>" />
        <input type="reset" name="btnSubAgentDetail" id="btnSubAgentDetail"  value="<?php echo SUBAGENTWITHDETAILS;?>" />
    </td>
</tr>
-->
</tbody>
</table>
        </div>
    </form>
</div>
