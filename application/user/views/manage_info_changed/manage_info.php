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
            url:'<?php echo base_url().'index.php/manage_info/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#ddlLocation2').html(response);
                $('#ddlLocation').html(response);
            }
        });
    }

    function getAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlAccGroup:nVal},
            url:'<?php echo base_url().'index.php/manage_info/filterAccount/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/manage_info/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success:function (response) {
                $('#ddlSubAccount').html(response);
            }
        });
    }

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtTillDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        getLocation($('#ddlDivision').val());

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        $('#ddlAccGroup').change(function(){
            var nVal = $('#ddlAccGroup').val();
            getAccount(nVal);
        });

        getSubAccount($('#ddlAccount').val());

        $('#ddlAccount').change(function () {
            var nVal = $('#ddlAccount').val();
            getSubAccount(nVal);
        });

        $('#btnProfitEmployee').click(function()
        {
            getVal('ProfitEmployee');
        });

        $('#btnSaleSummary').click(function()
        {
            getVal('SaleSummary');
        });

        $('#btnProfitCustomer').click(function()
        {
            getVal('ProfitCustomer');
        });

        $('#btnAirlineSummary').click(function()
        {
            getVal('AirlineSummary');
        });

        $('#btnProfitByVendor').click(function()
        {
            getVal('ProfitByVendor');
        });

        $('#btnHotelSummary').click(function()
        {
            getVal('HotelSummary');
        });

        $('#btnProfitGds').click(function()
        {
            getVal('ProfitGds');
        });

        $('#btnLocationSummary').click(function()
        {
            getVal('LocationSummary');
        });

        $('#btnAccountSummary').click(function()
        {
            getVal('AccountSummary');
        });

        $('#btnCustomerSummary').click(function()
        {
            getVal('CustomerSummary');
        });

        $('#btnVendorSummary').click(function()
        {
            getVal('VendorSummary');
        });

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "SaleSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getSaleSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "AccountSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getAccountSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "CustomerSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getCustomerSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "VendorSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getVendorSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "ProfitEmployee":
                    var path = '<?php echo base_url().'index.php/manage_info/getProfitEmployeeReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "ProfitCustomer":
                    var path = '<?php echo base_url().'index.php/manage_info/getProfitCustomerReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "AirlineSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getAirlineSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "ProfitByVendor":
                    var path = '<?php echo base_url().'index.php/manage_info/getProfitByVendorReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "HotelSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getHotelSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "ProfitGds":
                    var path = '<?php echo base_url().'index.php/manage_info/getProfitGdsReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "LocationSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getLocationSummaryReport/'.$this->uri->segment(3);?>';
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
            var nEmployee       = $('#ddlEmployee').val();
            var nAccount        = $('#ddlAccount').val();
            var nSubAccount     = $('#ddlSubAccount').val();
            var nAirline        = $('#ddlAirline').val();
            var nVendor         = $('#ddlVendor').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,
                      txtFromYear:nFromYear,txtToYear:nToYear,txtTillDate:nTillDate,ddlAccGroup:nAccGroup,ddlEmployee:nEmployee,
                      ddlAccount:nAccount,ddlSubAccount:nSubAccount,ddlAirline:nAirline,ddlVendor:nVendor,sess_clear:sess_clear},
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
    <?php echo form_open('manage_info/showlist/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
            <tbody>

            <tr>
                <td colspan="10">
                    <table>
                        <tr>
                            <td><?php echo DIVISIONNAME;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision"');?>
                            </label></td>

                            <td><?php echo LOCATION.'&nbsp;'.FROM;?></td>
                            <td><label>
                            <span id="loc">
                                <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation"');?>
                            </span>
                            </label></td>

                            <td><?php echo LOCATION.'&nbsp;'.TO;?></td>
                            <td><label>
                    <span id="loc2">
                        <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2"');?>
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
                            </label>
                            </td>

                            <td><?php echo TOYEAR;?> <span class="warning"></span></td>
                            <td><label>
                                <input name="txtToYear" id="txtToYear" class="textfield5" type="text" value="<?php echo stripslashes($this->txtToYear);?>">
                            </label></td>

                            <td><?php echo TILLDATE;?> <span class="warning"></span></td>
                            <td><label>
                                <input name="txtTillDate" id="txtTillDate" class="textfield5" type="text" value="<?php echo stripslashes($this->txtTillDate);?>">
                            </label></td>
                        </tr>

                        <tr>
                            <td><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup,'id="ddlAccGroup"');?>
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
                            <td><?php echo EMPLOYEEAC;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlEmployee', $list_emp_value, $this->ddlEmployee, 'id="ddlEmployee"');?>
                            </label></td>

                            <td><?php echo AIRLINE;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlAirline', $list_airline, $this->ddlAirline, 'id="ddlAirline"');?>
                            </label></td>

                            <td><?php echo VENDORNAME;;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo form_dropdown('ddlVendor', $list_vendor, $this->ddlVendor, 'id="ddlVendor"');?>
                            </label></td>
                        </tr>

                        <tr>
                            <td colspan="5">
                                <label>
                                    <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo DOMESTICCURRENCY;?>&nbsp;&nbsp;
                                    <input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo FOREIGNCURRENCY;?>
                                </label>
                            </td>
                        </tr>

                    </table>
                </td>
            </tr>

            <tr class="formButs">
                <td>
                    <input type="button" name="btnSaleSummary" id="btnSaleSummary" value="<?php echo SALESUMMARY;?>">
                    <input type="button" name="btnAccountSummary" id="btnAccountSummary"  value="<?php echo ACCOUNTSUMMARY;?>" />
                    <input type="button" name="btnCustomerSummary" id="btnCustomerSummary" value="<?php echo RECEIVABLESUMMARY;?>">
                    <input type="button" name="btnVendorSummary" id="btnVendorSummary" value="<?php echo PAYABLESUMMARY;?>">
                    <input type="button" name="btnLocationSummary" id="btnLocationSummary" value="<?php echo LOCATIONSUMMARY;?>">
                    <br>
                    <br>
                    <input type="button" name="btnProfitEmployee" id="btnProfitEmployee"  value="<?php echo PROFITBYEMPLOYEE;?>" />
                    <input type="button" name="btnProfitCustomer" id="btnProfitCustomer" value="<?php echo PROFITBYCUSTOMER;?>">
                    <input type="button" name="btnProfitByVendor" id="btnProfitByVendor" value="<?php echo PROFITBYVENDOR;?>" />
                    <input type="button" name="btnProfitGds" id="btnProfitGds" value="<?php echo PROFITBYGDS;?>">
                    <input type="button" name="btnAirlineSummary" id="btnAirlineSummary" value="<?php echo AIRLINESUMMARY;?>">
                    <input type="button" name="btnHotelSummary" id="btnHotelSummary" value="<?php echo HOTELSUMMARY;?>">
                </td>
            </tr>

            </tbody>
        </table>

    </form>

    <div id="result">
    </div>
</div>