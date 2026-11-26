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
            url:'<?php echo base_url().'index.php/year_process/filterDivision/'.$this->uri->segment(3);?>',
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

	function getYear(nVal) {
        $.ajax({
            type:'POST',
            data:{nId:nVal},
            url:'<?php echo base_url().'index.php/year_process/filterYear/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
				 var obj = $.parseJSON(response);
				 $('#txtFromDate').val(obj.dFDate);
				 $('#txtToDate').val(obj.dTDate);
				
            }
        });
    }
	
	
	

    $(document).ready(function()
    {
		
		 $('#ddlyear').change(function(){
			var id      = this.value;
			getYear(id);

           });

		
        $("#txtFromDate").datepick({ dateFormat: 'yyyy-mm-dd'});
        $("#txtToDate").datepick({ dateFormat: 'yyyy-mm-dd'});
		$("#txtTillDate").datepick({ dateFormat: 'yyyy-mm-dd'});


        $('#btnCancel').click(function() {
            window.location.replace('<?php echo base_url().'index.php/year_process/showlist/'.$this->uri->segment(3);?>');
            return false;
        });

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

        $('#btnAccGroup').click(function()
        {
            getVal('AccGroup');
        });

        $('#btnAccount').click(function()
        {
            getVal('Account');
        });

        $('#btnSubGroup').click(function()
        {
            getVal('SubGroup');
        });

        $('#btnSubAccount').click(function()
        {
            getVal('SubAccount');
        });		 
		$('#btnSubAccount1').click(function()        
		{            
			getVal('SubAccount1');        
		});

        function getVal(nval)
        {
            switch(nval)
            {
                
                case "SubAccount":
                    var path = '<?php echo base_url().'index.php/year_process/getSubAccount/'.$this->uri->segment(3);?>';
                    break;					
			}//end switch

            var nDivision       = $('#ddlDivision').val();
            var nLocation       = $('#ddlLocation').val();
            var nLocation2      = $('#ddlLocation2').val();
            var nToDate         = $('#txtToDate').val();
            var nFromDate       = $('#txtFromDate').val();
            
            $.ajax({
                type:'POST',
                data:{ddlDivision:nDivision,ddlLocation:nLocation,ddlLocation2:nLocation2,txtToDate:nToDate,txtFromDate:nFromDate},
                url:path,
                dataType:'html',
				beforeSend: function() {
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
					$('#loading').hide();
					 $('#result').show();
					// console.log(response.status);
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
<?php echo form_open('year_process/editReprint/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<div id="loading" style="display:none;">
<br>
<br>
<center>
<h3 id="title"> Generating Year Closing ...</h3><br>
<h3 style="color:red;" id="error"></h3><br>

<h3 style="color:red;" id="error1"></h3><br>


<img id="img1" src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." /><br><br>
</center>
</div>
     <div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
<tbody>
<style>
table.entryForm input[type="text"], table.entryForm input[type="password"], table.entryForm select{
	
	width:30% ! important;
}
</style>
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
        <?php 
		
		
		echo form_dropdown('ddlYear', $list_Year, $this->year,'id="ddlyear"');?>
    </label></td>

    <td><?php echo FROMDATE;?></td>
    <td>
        <input name="txtFromDate" id="txtFromDate" class="textfield5" type="text" value="<?php echo $this->txtFromDate;?>">
    </td>

    <td><?php echo TODATE;?></td>
    <td>
        <input name="txtToDate" id="txtToDate" class="textfield5" type="text" value="<?php echo $this->txtToDate;?>">
    </td>
</tr>


<tr class="formButs">
    <td>&nbsp;</td>
    <td colspan="5">
	
        <input type="button" id="btnSubAccount" name="btnSubAccount" value="<?php echo "Generate" ;?>">		 
		
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
