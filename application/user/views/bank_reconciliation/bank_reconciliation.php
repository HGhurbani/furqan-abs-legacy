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
            url:'<?php echo base_url().'index.php/bank_reconciliation/filterDivision/'.$this->uri->segment(3);?>',
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
         data:{ddlLocation:loc,ddlLocation2:loc2,ddlDivision:div},
         url:'<?php echo base_url().'index.php/bank_reconciliation/getBankAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
             var acc = $('#ddlAccount').val();
             $('#ddlAccount').html(response);
             $('#ddlAccount').val(acc);
         }
     });

}

    $(document).ready(function()
    {
        $("#txtFromDate").datepick({ dateFormat: 'dd-mm-yyyy'});
        $("#txtToDate").datepick({ dateFormat: 'dd-mm-yyyy'});

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

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "Full":
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "Matched":
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;

                case "UnMatched":
                    var path = '<?php echo base_url().'index.php/bank_reconciliation/displayUnMatchedList/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nLocation2       = $('#ddlLocation2').val();
            var nFromDate       = $('#txtFromDate').val();
            var nToDate         = $('#txtToDate').val();
            var nAccount        = $('#ddlAccount').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate,ddlAccount:nAccount,sess_clear:sess_clear},
                url:path,
                dataType:'html',
                success:function (response) {
                    $('#result').html(response);
                }
            });
        }//end function

        $('#btnProcess,#btnView,#btnViewMatched,#btnViewUnMatched').click(function(e) {
            var idVal    = this.id;
            var fromDate = $('#txtFromDate').val();
            var toDate   = $('#txtToDate').val();
            if(fromDate != '' && toDate != '')
            {
                switch(idVal)
                {
                    case "btnView":
                        getVal('Full');
                        break;

                    case "btnViewMatched":
                        getVal('Matched');
                        break;

                    case "btnViewUnMatched":
                        getVal('UnMatched');
                        break;
                }//end switch
            }
            else
            {
                alert('Give From date And To Date');
                return false;
            }
        });

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
<?php echo form_open_multipart('bank_reconciliation/process/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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

                        <td><?php echo LOCATIONNAME;?></td>
                        <td><label>
                            <span id="loc">
                                <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation,'id="ddlLocation" class="ddlLocation smallDropdown"');?>
                            </span>
                        </label></td>
                        <td><?php echo LOCATION.'&nbsp;'.TO;?></td>
                            <td><label>
                                <span id="loc2">
                                <?php echo form_dropdown('ddlLocation2', $list_loc_value, $this->ddlLocation2,'id="ddlLocation2" class="ddlLocation2"');?>
                                </span>
                            </label></td>
                        </tr>

<!--                        <td><?php //echo IATA;?></td>
                        <td>
                        <input name="txtIATA" id="txtIATA" class="textfield" type="text" value="<?php //echo $this->txtIATA;?>">
                        </td> -->
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

                        <td><?php echo ACCCODENAME;?> <span class="warning">*</span></td>
                        <td><label>
                        <?php echo form_dropdown('ddlAccount', $list_account, $this->ddlAccount, 'id="ddlAccount"');?>
                        </label></td>

                        <!--<td>
                            <input type="radio" value="f" name="txtReport" id="txtReport" checked="checked" />&nbsp;<?php /*echo FREPORT;*/?>&nbsp;&nbsp;
                            <input type="radio" value="u" name="txtReport" id="txtReport2" />&nbsp;<?php /*echo UREPORT;*/?>
                        </td>-->
<!--                        <td><label>
                            <input type="file" name="txtFile" id="txtFile" />
                        </label> -->
                        </td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr class="formButs">
            <td></td>
            <td colspan="5">
<!--                <input type="submit" name="btnProcess" value="<?php //echo PROCESS;?>" id="btnProcess"/> -->
                <input type="button" name="btnView" id="btnView" value="<?php echo FREPORT;?>" />
                <input type="button" name="btnViewMatched" id="btnViewMatched" value="<?php echo MREPORT;?>" />
                <input type="button" name="btnViewUnMatched" id="btnViewUnMatched" value="<?php echo UREPORT;?>" />
            </td>
        </tr>
        </tbody>
        </table>
</div>
    </form>
</div>
