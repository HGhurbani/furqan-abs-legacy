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
//print_r($this->session->userdata('accessLoc')); 
$lo=$this->session->userdata('accessLoc');
$lo=str_replace('(','',$lo);
$lo=str_replace(')','',$lo);                                                  
?>
<style>

table.entryForm td {
    padding: 4px 0px !important;
}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">
<script language="javascript1.1" type="text/javascript">
    $.validator.setDefaults({
        submitHandler: function() {}
    });

    function getLocation(nVal) {
		//alert("test");
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
        $("#txtFromDate").datepick({ dateFormat: 'dd-mm-yyyy'});
		$("#txtFromDate").datepick('setDate', new Date());
        $("#txtToDate").datepick({ dateFormat: 'dd-mm-yyyy'});
		 $("#txtToDate").datepick('setDate', new Date());
        $("#txtTillDate").datepick({ dateFormat: 'dd-mm-yyyy'});

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
		$('#btnUnpost').click(function()
        {
            getVal('Unpost');
        });
		$('#btnPL').click(function()
        {
            getVal('pl');
        });

        //function
        function getVal(nval)
        {
			//alert(nval);
            switch(nval)
            {
                case "SaleSummary":
                    var path = '<?php echo base_url().'index.php/manage_info/getSaleSummaryReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;
				case "pl":
                    var path = '<?php echo base_url().'index.php/manage_info/getAccGroup/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;
				case "Unpost":
                    var path = '<?php echo base_url().'index.php/manage_info/getUnpostReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
					//alert(path);
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
			if(nLocation == null){
			nLocation='<?php echo $lo;?>';
	
			}
			if(nLocation == null){
			nLocation2='<?php echo $lo;?>';	
			}
			
			
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,
                      txtFromYear:nFromYear,txtToYear:nToYear,txtTillDate:nTillDate,ddlAccGroup:nAccGroup,ddlEmployee:nEmployee,
                      ddlAccount:nAccount,ddlSubAccount:nSubAccount,ddlAirline:nAirline,ddlVendor:nVendor,sess_clear:sess_clear},
                url:path,
                dataType:'html',
				

                beforeSend: function() {
					
					$('#error1').empty();
					$('#title').show();
					$('#img1').show();
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
					//alert(response);
					$('#loading').hide();
					 $('#result').show();
                    $('#result').html(response);
                }
            });
        }//end function
    });
	
	function validateForm() {
    var x = document.forms["frmGroup"]["txtFromDate"].value;
    if (x == null || x == "") {
        alert("Date is Required");
        return false;
    }
}

</script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmGroup', 'id' => 'frmValidate');
$js = 'onsubmit="return validateForm()"';


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
    <?php echo form_open('manage_info/showlist/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$js,$attributes); ?>
    <?php echo form_hidden('nId', $this->catId);?>

        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
            <tbody>

            <tr>
                <td >
                    <table>
                        <tr>
                            <td><?php echo '&nbsp;&nbsp;&nbsp;'.DIVISIONNAME;?> <span class="warning"></span></td>
                            <td><label>
                                <?php echo '&nbsp;&nbsp;&nbsp;'.form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision,'id="ddlDivision" class="ddlDivision"');?>
                            </label></td>
</tr><tr>
                            <td><?php echo '&nbsp;&nbsp;&nbsp;'.LOCATION.'&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';?></td>
                            <td><label>
                            <span id="loc">
                                <?php echo '&nbsp;&nbsp;&nbsp;'.form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation"');?>
                            </span>
                            </label></td>

                        </tr>

                        <tr>
                            <td><?php echo '&nbsp;&nbsp;&nbsp;'. FROMDATE;?></td>
                            <td>
                                <?php echo '&nbsp;&nbsp;&nbsp;';?><input required="required" name="txtFromDate" id="txtFromDate" class="textfield5" type="text"  value="<?php echo $this->txtFromDate;?>" readonly="true" >
                            </td>
</tr><tr>
                            <td><?php echo '&nbsp;&nbsp;&nbsp;'. TODATE;?></td>
                            <td>
                                <?php echo '&nbsp;&nbsp;&nbsp;';?><input required="required" name="txtToDate" id="txtToDate" class="textfield5" type="text"  value="<?php echo $this->txtToDate;?>" readonly="true">
                            </td>
                        </tr>

    

                    </table>
                </td>
            </tr>

            <tr class="formButs">
                <td><?php echo '&nbsp;';?>
                    <input type="button" class="vald" name="btnSaleSummary" id="btnSaleSummary" value="<?php echo SALESUMMARY;?>">
                   <input type="button" class="vald" name="btnProfitEmployee" id="btnProfitEmployee"  value="<?php echo PROFITBYEMPLOYEE;?>" />
				      <!--<input type="button" class="vald" name="btnAccountSummary" id="btnAccountSummary"  value="<?php echo ACCOUNTSUMMARY;?>" />
                    <input type="button" class="vald" name="btnCustomerSummary" id="btnCustomerSummary" value="<?php echo RECEIVABLESUMMARY;?>">
                    <input type="button" class="vald" name="btnVendorSummary" id="btnVendorSummary" value="<?php echo PAYABLESUMMARY;?>">
                    <input type="button" class="vald" name="btnLocationSummary" id="btnLocationSummary" value="<?php echo LOCATIONSUMMARY;?>">
                    <br>
                    <br>
                    <input type="button" class="vald" name="btnProfitEmployee" id="btnProfitEmployee"  value="<?php echo PROFITBYEMPLOYEE;?>" />
                    <input type="button" class="vald" name="btnProfitCustomer" id="btnProfitCustomer" value="<?php echo PROFITBYCUSTOMER;?>">
                    <input type="button" class="vald" name="btnProfitByVendor" id="btnProfitByVendor" value="<?php echo PROFITBYVENDOR;?>" />
                    <input type="button" class="vald" name="btnProfitGds" id="btnProfitGds" value="<?php echo PROFITBYGDS;?>">
                    <input type="button" class="vald" name="btnAirlineSummary" id="btnAirlineSummary" value="<?php echo AIRLINESUMMARY;?>">
                    <input type="button" class="vald" name="btnHotelSummary" id="btnHotelSummary" value="<?php echo HOTELSUMMARY;?>">
               --> </td>
            </tr>
			<tr class="formButs">
                <td><?php echo '&nbsp;';?>
                    <input type="button" class="vald" name="btnUnpost" id="btnUnpost" value="<?php echo UNPOST;?>">
                    <input type="button" class="vald" name="btnPL" id="btnPL"  value="<?php echo "Profit/Loss";?>" />
                   <!-- <input type="button" class="vald" name="btnCustomerSummary" id="btnCustomerSummary" value="<?php echo RECEIVABLESUMMARY;?>">
                    <input type="button" class="vald" name="btnVendorSummary" id="btnVendorSummary" value="<?php echo PAYABLESUMMARY;?>">
                    <input type="button" class="vald" name="btnLocationSummary" id="btnLocationSummary" value="<?php echo LOCATIONSUMMARY;?>">
                    <br>
                    <br>
                    <input type="button" class="vald" name="btnProfitEmployee" id="btnProfitEmployee"  value="<?php echo PROFITBYEMPLOYEE;?>" />
                    <input type="button" class="vald" name="btnProfitCustomer" id="btnProfitCustomer" value="<?php echo PROFITBYCUSTOMER;?>">
                    <input type="button" class="vald" name="btnProfitByVendor" id="btnProfitByVendor" value="<?php echo PROFITBYVENDOR;?>" />
                    <input type="button" class="vald" name="btnProfitGds" id="btnProfitGds" value="<?php echo PROFITBYGDS;?>">
                    <input type="button" class="vald" name="btnAirlineSummary" id="btnAirlineSummary" value="<?php echo AIRLINESUMMARY;?>">
                    <input type="button" class="vald" name="btnHotelSummary" id="btnHotelSummary" value="<?php echo HOTELSUMMARY;?>">
               --> </td>
            </tr>

            </tbody>
        </table>

    </form>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Report ...</h3><br>
<h3 style="color:red;" id="error"></h3><br>

<h3 style="color:red;" id="error1"></h3><br>


<img id="img1" src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>
    <div id="result">
    </div>
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
