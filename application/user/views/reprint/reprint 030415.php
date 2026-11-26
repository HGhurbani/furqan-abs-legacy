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
             url:'<?php echo base_url().'index.php/note_reprint/filterDivision/'.$this->uri->segment(3);?>',
             dataType:'html',
             beforeSend: function() {
                 $('#location').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
             },
             success: function(response) {
                 //alert(response)
                 $('#loc').html(response);
             }
        });
    }

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});

        getLocation($('#ddlDivision').val());

        $('#btnCancel').click(function() {
            window.location.replace('<?php echo base_url().'index.php/note_reprint/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });

        $('#toPopup1').hide();

        $('.topopup').click(function(){
            var id = this.id;
            $('#toPopup'+id).show();

            var nVal   = $('#ddlDivision').val();
            var nVal2  = $('#ddlLocation').val();
            var nVal3  = $('#ddlLocation2').val();
            var nVal4  = $('#txtFromDate').val();
            var nVal5  = $('#txtToDate').val();
            var nVal6  = $('#ddlIssued').val();
            var nVal7  = $('#ddlType').val();
            var nVal8  = $('#ddlDocType').val();

            $.ajax({
                type:'POST',
                data:{ddlDivision:nVal,ddlLocation:nVal2,ddlLocation2:nVal3,txtFromDate:nVal4,txtToDate:nVal5,ddlIssued:nVal6,
                    ddlType:nVal7,ddlDocType:nVal8,btnSearch:'Go'},
                url:'<?php echo base_url().'index.php/note_reprint/filterList/'.$this->uri->segment(3);?>',
                dataType:'html',
                success: function(response) {
                    $('#popup_content').html(response);
                }
            });
            
        });

    });
</script>

<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>


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
<?php echo form_open('note_reprint/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>

<tr>
    <td colspan="10">
        <table>
            <tr>
                <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
                </label></td>

                <td><?php echo LOCATIONNAME;?></td>
                <td><label>
                <span id="loc">
                    <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
                </span>
                </label></td>
            </tr>

            <tr>
                <td><?php echo SUBAGENTAC;?></td>
                <td>
                    <label><?php echo form_dropdown('ddlAgentAc', $list_agent, $this->ddlAgentAc, 'id="ddlAgentAc"');?></label>
                </td>

                <td><?php echo DRACCNAME;?></td>
                <td><label>
                    <?php echo form_dropdown('ddlDrAccMain', $list_dracc,$this->ddlDrAccMain, 'id="ddlDrAccMain" class="ddlDrAccMain"');?>
                </label></td>
            </tr>

            <tr>

                <td><?php echo ISSUEDBY;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlIssued', $list_employee, $this->ddlIssued,'id="ddlIssued"');?>
                </label></td>

                <td><?php echo CUSTDATE;?></td>
                <td><label>
                    <input name="txtFromDate" id="txtFromDate" class="textfield" type="text" value="<?php echo $this->txtFromDate;?>">
                </label></td>
            </tr>

            <tr>
                <td><?php echo CUSTDATE;?></td>
                <td><label>
                    <input name="txtToDate" id="txtToDate" class="textfield" type="text" value="<?php echo $this->txtToDate;?>">
                </label></td>

                <td><?php echo SALESTYPE;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlType', $sales_type, $this->ddlType,'id="ddlType" class="ddlType smallDropdown"');?>
                </label></td>

            </tr>
            <tr>
                <td><?php echo DOCUMENT;?> <span class="warning">*</span></td>
                <td><label>
                    <?php echo form_dropdown('ddlDocType', $doc_type, $this->ddlDocType,'id="ddlDocType" class="ddlDocType smallDropdown"');?>
                </label></td>
            </tr>
        </table>
    </td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="9"><label>
        <a href="#" class="topopup" id="1">
            <span style="font-size: 12px;font-weight: bold;">
                <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
            </span>
        </a>
        <?php if($this->uri->segment(4) == 'a'){?>
        <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
        <?php }//end if?>
        <input type="reset" value="<?php echo RESET;?>" />
        <input type="button" name="btnCancel" value="<?php echo BACKTOPRINT;?>" id="btnCancel">
    </label></td>
</tr>
</tbody>
</table>

    <!-- Start of Sticky Note -->
    <div id="toPopup1" class="toPopup">
        <div class="close"></div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup_content"> <!--your content start-->
            <?php //echo $this->load->view('reprint/preview');?>
        </div> <!--your content end-->
    </div>
    <!-- End of Sticky Note -->

    </form>
</div>