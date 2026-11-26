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
            url:'<?php echo base_url().'index.php/sub_account_report/filterDivision/'.$this->uri->segment(3);?>',
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

        $('#btnCancel').click(function() {
            window.location.replace('<?php echo base_url().'index.php/sub_account_report/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

        $('#ddlDivision').change(function(){
            var nVal = $('#ddlDivision').val();
            getLocation(nVal);
        });
        getLocation($('#ddlDivision').val());

        $('#btnSubAccount').click(function()
        {
            getVal('SubAccount');
        });

        $('#btnAirline').click(function()
        {
            getVal('Airline');
        });

        $('#btnHotel').click(function()
        {
            getVal('Hotel');
        });

        $('#btnCountry').click(function()
        {
            getVal('Country');
        });

        //function
        function getVal(nval)
        {
            switch(nval)
            {
                case "SubAccount":
                    var path = '<?php echo base_url().'index.php/sub_account_report/getSubAccount/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                break;

                case "Airline":
                    var path = '<?php echo base_url().'index.php/sub_account_report/getAirline/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                break;

                case "Hotel":
                    var path = '<?php echo base_url().'index.php/sub_account_report/getHotel/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                break;

                case "Country":
                    var path = '<?php echo base_url().'index.php/sub_account_report/getCountry/'.$this->uri->segment(3);?>';
                    var sess_clear = 'y';
                break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nAccount        = $('#ddlAccount').val();
            var nType           = $('#ddlType').val();
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlAccount:nAccount,ddlType:nType,sess_clear:sess_clear},
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
<?php echo form_open('sub_account_report/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
    <div id="result">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
        <tbody>

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
        </tr>

        <tr>
            <td><?php echo ACNAME;?> <span class="warning"></span></td>
            <td>
                <?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>
            </td>

            <td><?php echo ACCTYPE;?> <span class="warning"></span></td>
            <td><label>
                <?php echo form_dropdown('ddlType', $this->sub_account_report_model->mainTypeArray(), $this->ddlType,' id="ddlType"class="singleDrop"');?>
            </label></td>
        </tr>

        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="9">
                <input type="button" name="btnSubAccount" id="btnSubAccount" value="<?php echo SUBACCOUNTLIST;?>" />
                <input type="button" name="btnAirline" id="btnAirline" value="<?php echo AIRLINELIST ;?>">
                <input type="button" name="btnHotel" id="btnHotel" value="<?php echo HOTELLIST ;?>">
                <input type="button" name="btnCountry" id="btnCountry" value="<?php echo COUNTRYCITYLIST ;?>">
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
