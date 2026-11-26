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
            url:'<?php echo base_url().'index.php/balasheet_report/filterDivision/'.$this->uri->segment(3);?>',
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
    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtToDate").datepick({ onSelect: 
                function(dateText,inst){ 
                    var date1 = new Date(dateText);
                    var year = date1.getFullYear(); 
                    $("#txtFromYear").val(year); 
                }, 
                dateFormat: 'yyyy-mm-dd'
        });

        getLocation($('#ddlDivision').val());

        $('#btnCancel').click(function() {
            window.location.replace('<?php echo base_url().'index.php/balasheet_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        $('#btnBalanceGeneral').click(function () {
            getVal('BalanceGeneral');
        });

        $('#btnBalanceT').click(function () {
            getVal('BalanceT');
        });

        function getVal(nval) {
            switch (nval) {
                case "BalanceGeneral":
                    var path = '<?php echo base_url().'index.php/balasheet_report/getBalanceGeneral/'.$this->uri->segment(3);?>';
                    break;

                case "BalanceT":
                    var path = '<?php echo base_url().'index.php/balasheet_report/getBalanceT/'.$this->uri->segment(3);?>';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nLocation2      = $('#ddlLocation2').val();
            var nToDate         = $('#txtToDate').val();
            var nFromYear       = $('#txtFromYear').val();
            var nReport         = $('#ddlReport').val();//alert(nLocation);
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromYear:nFromYear,ddlReport:nReport},
                url:path,
                dataType:'html',
				 beforeSend: function() {
                $('#loading').show();
				$('#result').hide();
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
<?php echo form_open('balasheet_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3>Generating Balance Sheet ...</h3><br>
<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>
    <div id="result">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
        <tbody>

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
            <td><?php echo TODATE;?></td>
            <td>
                <input name="txtToDate" id="txtToDate" class="textfield5" type="text" value="<?php echo $this->txtToDate;?>">
            </td>

            <td><?php echo YEAR;?> <span class="warning"></span></td>
            <td><label>
                <input name="txtFromYear" id="txtFromYear" class="textfield5" type="text" value="<?php echo stripslashes($this->txtFromYear);?>">
            </label></td>
        </tr>
        <tr>
            <td><?php echo REPORTTYPE;?> <span class="warning"></span></td>
            <td><label>
                <?php echo form_dropdown('ddlReport', $this->balasheet_report_model->mainReportArray(), $this->ddlReport,' id="ddlReport"class="singleDrop"');?>
            </label></td>
        </tr>

        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="9">
                <input type="button" id="btnBalanceGeneral" name="btnBalanceGeneral" value="<?php echo BALANCESHEETGENERAL;?>"/>
                <input type="button" id="btnBalanceT" name="btnBalanceT" value="<?php echo BALANCESHEETTFORMAT ;?>"/>
            </td>
        </tr>

        </tbody>
        </table>
    </div>
    </form>
</div>
