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
            url:'<?php echo base_url().'index.php/note_reprint/filterDivision/'.$this->uri->segment(3);?>',
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
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/note_reprint/getAllAccount/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var acc = $('#ddlAccount').val();
                // $('#ddlAccount').html(response);
                // $('#ddlAccount').val(acc);
         }
     });

     $.ajax({
         type:'POST',
         data:{ddlLocation:loc,ddlDivision:div},
         url:'<?php echo base_url().'index.php/note_reprint/getIssuedBy/'.$this->uri->segment(3);?>',
         dataType:'html',
         success: function(response) {
                 var issued = $('#ddlIssued').val();
                 //$('#ddlIssued').html(response);
                // $('#ddlIssued').val(issued);
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

        $('#btnSales').click(function()
        {
            getVal('Sales');
        });

        $('#btnRefund').click(function()
        {
            getVal('Refund');
        });

        function getVal(nval)
        {
            switch(nval)
            {
                case "Sales":
                    var path = '<?php echo base_url().'index.php/note_reprint/getSales/'.$this->uri->segment(3);?>';
                    break;

                case "Refund":
                    var path = '<?php echo base_url().'index.php/note_reprint/getRefund/'.$this->uri->segment(3);?>';
                    break;
            }//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nToDate         = $('#txtToDate').val();
            var nFromDate       = $('#txtFromDate').val();
            var nAccount        = $('#ddlAccount').val();
            var nSubAccount     = $('#ddlSubAccount').val();
            var nEmployee       = $('#ddlIssued').val();
            var win             = '';			//alert(nAccount);
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,txtToDate:nToDate,txtFromDate:nFromDate,
                      ddlAccount:nAccount,ddlSubAccount:nSubAccount,ddlIssued:nEmployee},
                url:path,
                dataType:'html',
                success:function (response) {
                    window.open(path,'PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");
                	//win.document.write(response);
				}
            });
        }//end function

    $('#ddlAccount').change(function()
    {
        var nVal = $('#ddlAccount').val();
        var accId = nVal.split("-");
        $.ajax({
            type:'POST',
            data:{ddlAccount:accId[0],tableType:accId[1]},
            url:'<?php echo base_url().'index.php/note_reprint/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                var subacc = $('#ddlSubAccount').val();
                $('#ddlSubAccount').html(response);
                $('#ddlSubAccount').val(subacc);
            }
        });
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
<?php echo form_open('note_reprint/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
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
    <td><?php echo ACCOUNTNAME;?> <span class="warning"></span></td>
    <td><label><?php echo form_dropdown('ddlAccount', $list_acc_value, $this->ddlAccount, 'id="ddlAccount" class="chosen-select"');?>
      
    </label></td>

    <td><?php echo SUBACCOUNT;?> <span class="warning"></span></td>
    <td><label>
        <?php echo form_dropdown('ddlSubAccount', $list_sub_acc, $this->ddlSubAccount,'id="ddlSubAccount" ');?>
    </label></td>
</tr>

<tr>
    <td><?php echo ISSUEDBY;?> <span class="warning"></span></td>
    <td><label>
        <?php echo form_dropdown('ddlIssued', $list_emp, $this->ddlIssued,'id="ddlIssued" class="chosen-select"');?>
    </label></td>
</tr>

<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="3">
        <input type="button" id="btnSales" name="btnSales" value="<?php echo NETINVOICE;?>" />
        <input type="button" id="btnRefund" name="btnRefund" value="<?php echo CREDITNOTE ;?>">
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
