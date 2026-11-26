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
            url:'<?php echo base_url().'index.php/ar_allocation/filterDivision/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#ddlLocation2').html(response);
                $('#ddlLocation').html(response);
            changeSubAccount();

            }
        });
    }

function changeSubAccount(){
        var loc  = $('#ddlLocation').val();
        var div = $('#ddlDivision').val();

            var nVal  = $('#ddlAccount').val();
           var accId = nVal.split("-");
            $.ajax({
                type:'POST',
                data:{ddlAccount:accId[0],tableType:accId[1],ddlLocation:loc,ddlDivision:div},
                url:'<?php echo base_url().'index.php/ar_allocation/filterSubAccount/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    var nSub = $('#ddlSubAccount').val();
                    $('#ddlSubAccount').html(response);
                    if(!(nSub == '' || nSub == null))
                        $('#ddlSubAccount').val(nSub);
                }
            });
}

    function getAccount(nVal) {
        $.ajax({
            type:'POST',
            data:{ddlType:nVal},
            url:'<?php echo base_url().'index.php/ar_allocation/getAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#ddlAccount').html(response);

            }
        });
    }

    function getSub() {
        $.ajax({
            type:'POST',
            data:{ddlType:0},
            url:'<?php echo base_url().'index.php/ar_allocation/getSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                $('#ddlSubAccount').html(response);
            }
        });
    }

    $(document).ready(function(){

        $("ul.pgcontent").quickPagination({pageSize:"5"});

        $('#btnCancel').click(function(){
            window.location.replace('<?php echo base_url().'index.php/ar_allocation/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtDate1").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtRefDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        jQuery.validator.addMethod(
            "selectNone",
            function(value, element) {
                if (element.value == "0")
                {
                    return false;
                }
                else return true;
            },
            "<?php echo PLEASESELECTANOPTION;?>"
        );

        $("#frmValidate").validate({
            rules: {
                ddlLedger: {
                    selectNone : true
                },
                ddlDivision: {
                    selectNone : true
                }
            },
            messages: {
                ddlLedger: {
                    selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                },
                ddlDivision: {
                    selectNone : "<?php echo PLEASESELECTANOPTION;?>"
                }
            },
            success: function(label2) {
                // set   as text for IE
                label2.html("").addClass("checked");
            } ,
            submitHandler: function(form) {
                $(form).ajaxSubmit();
            }

        });

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        getLocation($('#ddlDivision').val());

        $('#loc').change(function(){
                changeSubAccount();
        });

        $('#ddlAccount').change(function()
        {
            changeSubAccount();
        });

        $('#ddlAccount').change();

        $('#ddlType').change(function()
        {
            var nVal = $('#ddlType').val();
            getAccount(nVal);
            getSub();
        });

//        getAccount($('#ddlType').val());

        $('#btnSearch').click(function()
        {
            var ddlType       = $('#ddlType').val();
            var ddlDivision   = $('#ddlDivision').val();
            var ddlLocation   = $('#ddlLocation').val();
            var ddlAccount    = $('#ddlAccount').val();
            var ddlSubAccount = $('#ddlSubAccount').val();
			var accId 		  = ddlSubAccount.split("-");
			var subAccId      =  ddlAccount.split("-");
            var ddlAllocation = $('#ddlAllocation').val();

            $('#dataTableTd').addClass('hidden');
            $('#dataTableTd2').addClass('hidden');
//alert(accId[1]);
            $.ajax({
                type:'POST',
                data:{ddlType:ddlType,ddlDivision:ddlDivision,ddlLocation:ddlLocation,ddlAccount:accId[0],ddlAccType:accId[1],
					  ddlSubAccount:subAccId[0],ddlAllocation:ddlAllocation,btnSearch:'Go'},
                url:'<?php echo base_url().'index.php/ar_allocation/filterList/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {

                    $('#dataTableTd').removeClass('hidden');
                    $('#showDataTable').html(response);
                }
            });
        });

    });
</script>

<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>') ; 
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
<?php echo form_open('ar_allocation/editAllocation/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
<!-- <tr>
    <td><?php echo CASHRECEIPTTYPE;?></td>
    <td>
        <?php echo form_dropdown('ddlType', $list_trans_value, $this->ddlType, 'id="ddlType"');?>
    </td>
    <td colspan="2"></td>
</tr>
-->
<tr>
    <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
    <td>
        <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
    </td>

    <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
    <td>
        <span id="loc">
            <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
        </span>
    </td>
</tr>

<tr>
    <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
    <td>
        <?php echo form_dropdown('ddlAccount', $list_account, $this->ddlAccount, 'id="ddlAccount"');?>
    </td>

    <td><?php echo SUBCODE;?></td>
    <td>
        <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount, 'id="ddlSubAccount"');?>
    </td>
</tr>

<tr>
    <td><?php echo ALLOCATIONTYPE;?></td>
    <td>
        <?php echo form_dropdown('ddlAllocation', $list_alloc_type, $this->ddlAllocation, 'id="ddlAllocation"');?>
    </td>
    <td colspan="2"></td>
</tr>

<tr>
    <td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
</tr>

<tr>
    <td class="hidden" id='dataTableTd'  colspan="2">
        <span id="showDataTable">
        <table id="dataTable" title="dataTable" style="border:1px solid #C9C9C9;padding-bottom: 10px;">
        </table>
        </span>
    </td>

    <td class="hidden" id='dataTableTd2' colspan="2">
        <span id="showDataTable2"></span>
    </td>



</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="5"><label>
<!--        <input type="submit" name="btnGo" id="btnGo" value="<?php echo SAVEEXIT;?>" /> -->
        <input type="reset" value="<?php echo RESET;?>" />
        <input type="button" name="btnCancel" value="<?php echo EXITP;?>" id="btnCancel">
    </label></td>
</tr>
</tbody>
</table>
</form>
</div>
