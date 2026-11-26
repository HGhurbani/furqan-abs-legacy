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
            url:'<?php echo base_url().'index.php/ac_report/filterDivision/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/ac_report/filterAccount/'.$this->uri->segment(3);?>',
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
            url:'<?php echo base_url().'index.php/ac_report/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success:function (response) {
                $('#subAcc').html(response);
            }
        });
    }

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'dd-mm-yyyy'});
        $("#txtToDate").datepick({ dateFormat: 'dd-mm-yyyy'});

        getLocation($('#ddlDivision').val());

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

        $('#ddlAccount').change(function () {
            var nVal = $('#ddlAccount').val();
            getSubAccount(nVal);
        });

        $('#btnDetail').click(function()
        {
            getVal('Detail');
        });

        $('#btnDaily').click(function()
        {
            getVal('Daily');
        });

        $('#btnWeekly').click(function()
        {
            getVal('Weekly');
        });

        $('#btnMonthly').click(function()
        {
            getVal('Monthly');
        });

        $('#btnYearly').click(function()
        {
            getVal('Yearly');
        });

        $('#btnSummary').click(function()
        {
            getVal('Summary');
        });

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "Detail":
                    var path = '<?php echo base_url().'index.php/ac_report/getDetailReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Daily":
                    var path = '<?php echo base_url().'index.php/ac_report/getDailyReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Weekly":
                    var path = '<?php echo base_url().'index.php/ac_report/getWeeklyReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Monthly":
                    var path = '<?php echo base_url().'index.php/ac_report/getMonthlyReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Yearly":
                    var path = '<?php echo base_url().'index.php/ac_report/getYearlyReport/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Summary":
                    var path = '<?php echo base_url().'index.php/ac_report/getSummaryReport/'.$this->uri->segment(3);?>';
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
            var nAccGroup       = $('#ddlAccGroup').val();
            var nAccount        = $('#ddlAccount').val();
            var nSubAccount     = $('#ddlSubAccount').val();
            var accId           = nSubAccount.split("-");
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,
                      txtFromYear:nFromYear,txtToYear:nToYear,ddlAccGroup:nAccGroup,ddlAccount:nAccount,ddlSubAccount:accId[0],
                      tableType:accId[1],sess_clear:sess_clear},
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
<?php echo form_open('ac_report/showlist/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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

            <!--<tr>
                <td><?php /*echo FROMYEAR;*/?> <span class="warning"></span></td>
                <td><label>
                    <input name="txtFromYear" id="txtFromYear" class="textfield5" type="text" value="<?php /*echo stripslashes($this->txtFromYear);*/?>">
                    </label>
                </td>
				<td><?php /*echo TOYEAR;*/?> <span class="warning"></span></td>
                <td><label>
                <input name="txtToYear" id="txtToYear" class="textfield5" type="text" value="<?php /*echo stripslashes($this->txtToYear);*/?>">
                </label></td>
			</tr>-->

			<tr>
				<td class="hidden"><?php echo ACCOUNTGROUP;?> <span class="warning"></span></td>
				<td class="hidden"><label>
				 <?php echo form_dropdown('ddlAccGroup', $list_acc_group, $this->ddlAccGroup,'id="ddlAccGroup"');?>
				</label></td>

                <td><?php echo ACNAME;?> <span class="warning"></span></td>
                <td>
                    <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount"  class="chosen-select"');?>
                </td>

                <td><?php echo SUBACCOUNT;?></td>
                <td>
                <span id="subAcc">
                    <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount" class="chosen-select"');?>
                </span>
                </td>
            </tr>

            <!--<tr>
                <td colspan="5">
                    <label>
                        <input type="radio" name="chkActive" value="1" <?php /*echo $this->chkValYes;*/?> />&nbsp;<?php /*echo DOMESTICCURRENCY;*/?>&nbsp;&nbsp;
                        <input type="radio" name="chkActive" value="0" <?php /*echo $this->chkValNo;*/?>/>&nbsp;<?php /*echo FOREIGNCURRENCY;*/?>
                    </label>
                </td>
            </tr>-->

        </table>
    </td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9">
        <input type="button" name="btnDetail" id="btnDetail"  value="<?php echo DETAILREPORT;?>" />
        <input type="button" name="btnDaily" id="btnDaily" value="<?php echo DAILYREPORT;?>">
        <input type="button" name="btnWeekly" id="btnWeekly" value="<?php echo WEEKLYREPORT;?>">
        <input type="button" name="btnMonthly" id="btnMonthly" value="<?php echo MONTHLYREPORT;?>">
        <input type="button" name="btnYearly" id="btnYearly" value="<?php echo YEARLYREPORT;?>" />
        <input type="button" name="btnSummary" id="btnSummary" value="<?php echo SUMMARYREPORT;?>">
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
