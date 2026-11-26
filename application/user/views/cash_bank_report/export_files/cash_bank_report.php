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
            url:'<?php echo base_url().'index.php/cash_bank_report/filterDivision/'.$this->uri->segment(3);?>',
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
         url:'<?php echo base_url().'index.php/cash_bank_report/getIssuedByMultiLoc/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/cash_bank_report/filterAccount/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/cash_bank_report/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success:function (response) {
                $('#subAcc').html(response);
            }
        });
    }

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});
		$("#txtTillDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        getLocation($('#ddlDivision').val());




        $('#ddlLocation').change(function(){
            changeAllAccount();
            });

        $('#ddlLocation2').change(function(){
            changeAllAccount();
            });


        $('#ddlDivision').change(function(){
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
/*        $('#ddlAccount').change(function () {
            var nVal = $('#ddlAccount').val();
            getSubAccount(nVal);
        });
*/
        $('#btnRegister').click(function () {
            getVal('Register');
        });

        $('#btnTransaction').click(function () {
            getVal('Transaction');
        });

        function getVal(nval) {
            switch (nval) {
                case "Register":
                    var path = '<?php echo base_url().'index.php/cash_bank_report/getRegister/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Transaction":
                    var path = '<?php echo base_url().'index.php/cash_bank_report/getTransaction/'.$this->uri->segment(3);?>';
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
            var nTransaction    = $('#ddlTransaction').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,
                      txtFromYear:nFromYear,txtToYear:nToYear,txtTillDate:nTillDate,ddlAccGroup:nAccGroup,ddlCustomer:nCustomer,
                      ddlAccount:nAccount,ddlSubAccount:accId[0],
                      tableType:accId[1],ddlEmployee:nEmployee,ddlAgent:nAgent,ddlReport:nReport,
                      ddlTransaction:nTransaction,sess_clear:sess_clear},
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
<?php echo form_open('cash_bank_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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
                    <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision smallDropdown"');?>
                </label></td>

                <td><?php echo LOCATION.'&nbsp;'.FROM;?></td>
                <td><label>
                        <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>
                </label></td>

                <td><?php echo LOCATION.'&nbsp;'.TO;?></td>
                <td><label>
                        <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2 smallDropdown"');?>
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
            <!--<tr>
                <td><?php /*echo FROMYEAR;*/?> <span class="warning"></span></td>
                <td><label>
                <input name="txtFromYear" id="txtFromYear" class="textfield5" type="text" value="<?php /*echo stripslashes($this->txtFromYear);*/?>">
                </label></td>
				 <td><?php /*echo TOYEAR;*/?> <span class="warning"></span></td>
                <td><label>
                <input name="txtToYear" id="txtToYear" class="textfield5" type="text" value="<?php /*echo stripslashes($this->txtToYear);*/?>">
                </label></td>
				
				<td><?php /*echo TILLDATE;*/?> <span class="warning"></span></td>
                <td><label>
               <input name="txtTillDate" id="txtTillDate" class="textfield5" type="text" value="<?php /*echo $this->txtTillDate;*/?>">
                </label></td>
			</tr>-->
            <tr>
                <td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
                <td><label>
                    <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup, 'id="ddlAccGroup" class="chosen-select"');?>
                </label></td>

                <td><?php echo ACNAME;?> <span class="warning"></span></td>
                <td>
                <span id="Acc">
                    <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>
                </span>
                </td>

                <td><?php echo SUBACCOUNT;?></td>
                <td>
                <span id="subAcc">
                    <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>
                </span>
                </td>
            </tr>

            <tr>
                <!--<td><?php /*echo PARENTCUSAC;*/?> <span class="warning"></span></td>
                <td><label>
                    <?php /*echo form_dropdown('ddlCustomer', $list_customer_value, $this->ddlCustomer, 'id="ddlCustomer"');*/?>
                </label></td>-->

                <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>
                <td><label>
                <span id="Emp">
                    <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee" class="chosen-select"');?>
                </span>
                </label></td>

                <!--<td><?php /*echo AGENTAC;;*/?> <span class="warning"></span></td>
                <td><label>
                    <?php /*echo form_dropdown('ddlAgent', $list_agent, $this->ddlAgent, 'id="ddlAgent"');*/?>
                </label></td>-->
            </tr>
			
		    <tr>
				<td><?php echo REPORTTYPE;?> <span class="warning"></span></td>
				<td><label>
				<?php echo form_dropdown('ddlReport', $this->cash_bank_report_model->mainReportArray(), $this->ddlReport,' id="ddlReport"');?>
				</label></td>
				
				<td><?php echo TRANSACTIONTYPE;?> <span class="warning"></span></td>
				<td><label>
				<?php echo form_dropdown('ddlTransaction', $this->cash_bank_report_model->mainTransactionArray(), $this->ddlTransaction,' id="ddlTransaction"');?>
				</label></td>
            </tr>
        </table>
    </td>
</tr>

<tr class="formButs">
    <td>
        <input type="button" name="btnRegister" value="<?php echo REGISTERREPORT;?>" id="btnRegister"/>
        <input type="button" name="btnTransaction" value="<?php echo TRANSACTIONREPORT ;?>" id="btnTransaction">
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
