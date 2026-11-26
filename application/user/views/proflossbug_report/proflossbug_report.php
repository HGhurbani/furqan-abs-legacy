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
            url:'<?php echo base_url().'index.php/proflossbug_report/filterDivision/'.$this->uri->segment(3);?>',
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
        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});
		$("#txtTillDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        getLocation($('#ddlDivision').val());

        $('#btnCancel').click(function() {
            window.location.replace('<?php echo base_url().'index.php/proflossbug_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        $('#btnBudgetDetail').click(function()
        {
            getVal('BudgetDetail');
        });

        $('#btnBudgetSummary').click(function()
        {
            getVal('BudgetSummary');
        });

        function getVal(nval)
        {
            switch(nval)
            {
                case "BudgetDetail":
                    var path = '<?php echo base_url().'index.php/proflossbug_report/getBudgetDetail/'.$this->uri->segment(3);?>';
                break;

                case "BudgetSummary":
                    var path = '<?php echo base_url().'index.php/proflossbug_report/getBudgetSummary/'.$this->uri->segment(3);?>';
                break;
            }//end switch

            var nDivision   = $('#ddlDivision').val();
            var nLocation   = $('#ddlLocation').val();
            var nLocation2  = $('#ddlLocation2').val();
            var nMonth      = $('#txtMonth').val();
            var nYear       = $('#txtYear').val();
            var nAccGroup   = $('#ddlAccGroup').val();
            var nAccount    = $('#ddlAccount').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtMonth:nMonth,txtYear:nYear,
                      ddlAccGroup:nAccGroup,ddlAccount:nAccount},
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
<?php echo form_open('proflossbug_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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
                            <span id="loc">
                                <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>
                            </span>
                        </label></td>

                        <td><?php echo LOCATION.'&nbsp;'.TO;?></td>
                        <td><label>
                            <span id="loc2">
                                <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2 smallDropdown"');?>
                            </span>
                        </label></td>
                    </tr>
                    <tr>
                        <td><?php echo YEAR;?> <span class="warning"></span></td>
                        <td><label>
                        <input name="txtYear" id="txtYear" class="textfield5" type="text" value="<?php echo stripslashes($this->txtYear);?>">
                        </label></td>
                         <td><?php echo MONTH;?> <span class="warning"></span></td>
                        <td><label>
                         <?php echo form_dropdown('txtMonth', $list_month, $this->txtMonth, 'id="txtMonth"');?>
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
                    </tr>

                </table>
            </td>
        </tr>

        <tr class="formButs">
            <td>
                <!--<input type="button" name="btnBudgetDetail" id="btnBudgetDetail" value="<?php echo WITHBUDGETDETAIL;?>" />-->
                <input type="button" name="btnBudgetSummary" id="btnBudgetSummary" value="<?php echo WITHBUDGETSUMMARY ;?>">
            </td>
        </tr>

        </tbody>
        </table>
     </div>
    </form>
</div>