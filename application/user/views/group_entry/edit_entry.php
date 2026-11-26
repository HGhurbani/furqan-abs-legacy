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
<style>
.pricebox1 {
    min-width: 40px !important;
}
#ddlType{
	min-width: 99px !important;
}
</style>
<link rel="stylesheet" href="<?php echo base_url();?>static/plugins/dropdown/chosen.css">

<script language="javascript1.1" type="text/javascript">
$.validator.setDefaults({
	submitHandler: function() {}
});

function getLocation(nVal){
    var locVal = $('#ddlLocation').val();
    $.ajax({
        type:'POST',
        data:{ddlDivision:1},
        url:'<?php echo base_url().'index.php/cashbook_receipt/filterDivision/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
            $('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
            $('#loc').html(response);
            $('#ddlLocation').val(locVal);
			
			
			
			
           
        }


    });

   

}
function tot_t(){
	var t_adt =  $('#t_adt').val();
	var t_cld =  $('#t_cld').val();
	var t_inf =  0;//$('#t_adt').val();
	t_adt     = (t_adt == '')?0:t_adt;

        t_cld     = (t_cld == '')?0:t_cld;

        
        var tot_t = parseFloat(t_adt) + parseFloat(t_cld);
		var t_bal =  $('#t_bal1').val();
		 $('#tot_t').val(tot_t);
		 t_bal=parseFloat(t_bal) - parseFloat(tot_t);
		 $('#t_bal').val(t_bal);
}
function tot_h(){
	var h_sgl =  $('#h_sgl').val();
	var h_dbl =  $('#h_dbl').val();
	var h_tpl =  $('#h_tpl').val();
	
	var h_suite =  $('#h_suite').val();
	var h_oths =  $('#h_oths').val();
	
	
	h_sgl     = (h_sgl == '')?0:h_sgl;

        h_dbl     = (h_dbl == '')?0:h_dbl;

        h_tpl     = (h_tpl == '')?0:h_tpl;

        h_suite     = (h_suite == '')?0:h_suite;
		 h_oths     = (h_oths == '')?0:h_oths;
	
        
        var tot_h = parseFloat(h_sgl) + parseFloat(h_dbl)+ parseFloat(h_tpl)+ parseFloat(h_suite)+ parseFloat(h_oths);
		 $('#h_tot').val(tot_h);
		 var h_bal =  $('#h_bal1').val();
		 h_bal=parseFloat(h_bal) - parseFloat(tot_h);
		 $('#h_bal').val(h_bal);
		 
}



$(document).ready(function(){
	getLocation(1);
    
    $("ul.pgcontent").quickPagination({pageSize:"5"});
	
	
	$('#btnCancel').click(function(){
		window.location.replace('<?php echo base_url().'index.php/group_entry/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
	$("#txtDate1").datepick({ dateFormat: 'dd-mm-yyyy'});
	
	$("#txtRefDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	$("#txtRDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	$(".txtRDate").datepick({ dateFormat: 'dd-mm-yyyy'});
	
	

		
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
	
	// validate signup form on keyup and submit
	$("#frmValidate").validate({
		rules: {
			 ddlLedger: {
					selectNone : true
					},
			<?php if($this->uri->segment(4) == 'e'){?>
				
                   doc: {
					selectNone : true
					},
				<?php } ?>
			
			 ddlLocation: {
					selectNone : true
					}
			/* 'ddlAccount2[]': {
					selectNone : true
					}*/
		},
		messages: {
			ddlLedger: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					},
					
			<?php if($this->uri->segment(4) == 'e'){?>
				
                   doc: {
					selectNone : "<?php echo "Required";?>"
					},
				<?php } ?>
			
			ddlLocation: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					}/*,
			'ddlAccount2[]': {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>"
					}*/
		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
	
	$('#txtRefName').keyup(function()
    {
		<?php if($this->uri->segment(4) == 'a'){?>
				
                    
				
		var locVal = $('#txtDocNumber').val();
		//alert(locVal);
    $.ajax({
        type:'POST',
        data:{txtDocNumber:locVal},
        url:'<?php echo base_url().'index.php/group_entry/filterdoc/'.$this->uri->segment(3);?>',
        dataType:'html',
        beforeSend: function() {
           // $('#avl').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
        },
        success: function(response) {
			console.log(response);
            //$('#avl').html(response);
             
           
        }


    });
	
	<?php } ?>
	});

 $('#t_cld').keyup(function()
    {
		tot_t();
	});
	$('#t_adt').keyup(function()
    {
		tot_t();
	});
	$('#tot_t').keyup(function()
    {
		tot_t();
	});
	$('#h_sgl').keyup(function()
    {
		tot_h();
	});
	$('#h_dbl').keyup(function()
    {
		tot_h();
	});
	$('#h_tpl').keyup(function()
    {
		tot_h();
	});
	$('#h_suite').keyup(function()
    {
		tot_h();
	});
	$('#h_oths').keyup(function()
    {
		tot_h();
	});
	$('#h_tot').keyup(function()
    {
		tot_h();
	});
	
	$('.ddlAccount2').change(function()
    {
        var id    = this.id;
        var getId = id.split('ddlAccount2');
        var nVal  = $('#ddlAccount2').val();
        var accId = nVal.split("-");
        $.ajax({
            type:'POST',
            data:{ddlAccount2:accId[0],tableType:accId[1]},
            url:'<?php echo base_url().'index.php/group_entry/filterSubAccount/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
                
                $('#ddlSubAccount').html(response);
            }
        });
    });
	<?php if($this->uri->segment(4) == 'e'){?>
	$('#stk_t').change(function()
    {
		var getId = $('#stk_t').val();
		 $.ajax({
            type:'POST',
            data:{stk_t:getId},
            url:'<?php echo base_url().'index.php/group_entry/filtert_qan/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
               
                $('#t_bal').val(response);
				$('#t_bal1').val(response);
            }
        });
		
		
		
	});
	<?php } ?>
	<?php if($this->uri->segment(4) == 'a'){?>
	$('#stk_t').change(function()
    {
		var getId = $('#stk_t').val();
		 $.ajax({
            type:'POST',
            data:{stk_t:getId},
            url:'<?php echo base_url().'index.php/group_entry/filtert_qan/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
               
                $('#t_bal').val(response);
				$('#t_bal1').val(response);
            }
        });
		var getId = $('#stk_t').val();
		
		 $('#txtDocNumber').val(getId);
		
	});
	<?php }?>
	
	<?php if($this->uri->segment(4) == 'ee'){?>
	
	 var accId="<?php echo $this->ddlAccount;?>";
	 var acc= "C";
	 var loc= "<?php echo $this->ddlLocation;?>";
	  var doc= "<?php echo $this->doc;?>";
	 $.ajax({

                        type:'POST',

                        data:{ddlDrAccMain:accId,tableType:acc,ddlloc:loc,ddldoc:doc},

//                        
                        url:'<?php echo base_url().'index.php/group_entry/getCRV/'.$this->uri->segment(3);?>',

                        dataType:'html',

                        success: function(response) {
							
							
					  $('#doc').html(response);
						
                        }

                 });
	<?php }?>
				 

	
	$('#stk_h').change(function()
    {
		
		var getId = $('#stk_h').val();
		//alert(getId);
		 $.ajax({
            type:'POST',
            data:{stk_h:getId},
            url:'<?php echo base_url().'index.php/group_entry/filterh_qan/'.$this->uri->segment(3);?>',
            dataType:'html',
            success: function(response) {
               console.log(response);
                $('#h_bal').val(response);
				$('#h_bal1').val(response);
            }
        });
		
	});
	
	$('#slsamt').keyup(function()
    {
		var nVal2 = $('#slsamt').val();
		$('#chkBAmount').val(nVal2);
		
	});
	
	$('input[name="chkRAmount[]"]').keyup(function(e)
	{
		var id      = this.id;
		//alert(id);
		var nVal1 = $('#slsamt').val();
		var debitTotal =0;
		var creditTotal=0
        $('.dcRate').each(function () 
			{
				 var nVal2 = $(this).val();
                
					
					debitTotal = (parseFloat(debitTotal)+parseFloat(nVal2));
					creditTotal = (parseFloat(nVal1)-parseFloat(debitTotal));
					
				
            });
			 $('#rcamt').val(debitTotal);
			 
			 var getId   = id.split('chkRAmount');
			 
			  $('#chkBAmount'+getId[1]).val(creditTotal);
			
	});

        $("#addRow").click(function() {
			<?php if($this->uri->segment(4) == 'a'){?>
var i = 1;
<?php } else{ ?>

var i = $("#dataTable").attr('rows').length;
var i = i-1;
//alert(i);

<?php }?>
			//$(".ddlAccount2").select2("destroy");
			$(".chosen-select").chosen("destroy");
        var m	 = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
            var id = this.id;
			
            $(this).attr({
              'id': function(_, id2) { return id + i; },
              'value': ''
            });
			}).end().find("select").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': '0'
                });
        
            }).end().find("textarea").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
            }).end().insertAfter('#dataTable tbody>tr:last');
			
       i++;
	   $(".chosen-select").chosen();
	   
	    //$(".ddlAccount2").select2();
       var rowCount = document.getElementById("dataTable").rows.length; //$("#dataTable").attr('title').length;
       m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
	    m.find('img[class="del"]').attr({

                        'id': 'del'

                    });
       return false;
	
	});
	
	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if ($("#dataTable tr").length != 2) {
		//$("#dataTable tr:last")
		//alert(rowCount);
		}
		if((rowCount)>2)
		{
			if ($(this).is('#dataTable tr:last')) {
    
	//$("#dataTable tr:last").remove();
            $(this).parent().parent().remove();
            var dId = this.id;
            dId     = dId.split('_');
            $.ajax(
            {
                type:'POST',
                url:'<?php echo base_url().'index.php/group_entry/deleteRecord/'.$this->uri->segment(3);?>',
                data:{subCode:dId[1]},
                dataType:'html',
                success: function(response) {
                }
            });
			var debitTotal =0;
			$('.dcRate').each(function () 
			{
				 var nVal2 = $(this).val();
                
					
					debitTotal = (parseFloat(debitTotal)+parseFloat(nVal2));
					
					
				
            });
			 $('#rcamt').val(debitTotal);
			
			
  }
			
			
		}//end if
		else
		{
			$('#del_'+rowCount).attr('disabled', true);
		}//end if
	});

  

    

});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">

<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

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
<?php echo form_open('group_entry/editEntry/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $chkDetailID);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
	
	<?php $id=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");

	if(($this->uri->segment(4) == 'e' && $id==1 ) || ($this->uri->segment(4) == 'e' && $id==46 ) || ($this->uri->segment(4) == 'a')){
	?>
        <tr>
           

            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><label><span id="loc">
                 <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
                </span>
            </label></td>
			<td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtDate1" id="txtDate1" type="text" value="<?php echo $this->txtDate1;?>" class="pricebox">
			  <input name="txtaDoc" id="txtaDoc" type="hidden" value="<?php echo $this->txtadoc;?>" class="pricebox">
            </label></td>
        
		
		</tr>

        <tr>
            
          <td><?php echo CASHRECEIPTTYPE;?> </td>
            <td> 
                <?php echo form_dropdown('ddlType', $list_trans_value, $this->ddlTransaction, 'id="ddlType"');?>
            </td>

            <td><?php echo "GROUP NO";?></td>
            <td><label>
			<?php if($this->uri->segment(4) == 'a'){?>
                <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"
                       value="AJT/" size="20" >
			<?php }else{?>
			<input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"
                       value="<?php echo $this->txtDocNumber;?>" size="20" readonly="readonly">
			<?php }?>
			 
            </label></td>

        </tr>

       
		
        <tr>
            <td><?php echo "Passanger Name";?></td>
            <td><label>
                <input name="txtRefName" id="txtRefName" type="text" value="<?php echo $this->txtRefName;?>" class="textfield">
            </label></td>

          
            <td><?php echo "Date of travel";?></td>
            <td><label>
             <input name="txtRefDate" id="txtRefDate" type="text" value="<?php echo $this->txtRefDate;?>" size="40" class="pricebox">
            </label></td>
        </tr>
		<tr>
            <td><?php echo CONTACT;?></td>
            <td><label>
                <input name="ctc1" id="ctc1" type="text" value="<?php echo $this->ctc1;?>" class="pricebox">
                                                    
			</label></td>
			<td><label>
                <input name="ctc2" id="ctc2" type="text" value="<?php echo $this->ctc2;?>" size="40" class="pricebox">
                              
			</label></td>
			<td><label>
                <input name="ctc3" id="ctc3" type="text" value="<?php echo $this->ctc3;?>" class="pricebox">
                              
			</label></td>
			

          
            
           
        </tr>

		
		<tr>
            <td ><?php echo "Tickets";?></td>
            <td colspan="10"><table ><tr>
			<td>Type</td>
			<td>Adult</td>
			<td>Child</td>
			<td>Infant</td>
			<td>Total</td><td>BALANCE</td></tr>
			<tr>
			<td><?php echo form_dropdown('stk_t', $list_st_type, $this->stk_t, 'id="stk_t"');?></td>
			<td> <input name="t_adt" id="t_adt" type="text" value="<?php echo $this->t_adt;?>" class="pricebox1"></td>
			<td> <input name="t_cld" id="t_cld" type="text" value="<?php echo $this->t_cld;?>" class="pricebox1"></td>
			<td> <input name="t_inf" id="t_inf" type="text" value="<?php echo $this->t_inf;?>" class="pricebox1"></td>
			<td> <input name="tot_t" id="tot_t" type="text" value="<?php echo $this->tot_t;?>" class="pricebox1"></td>
			<td> <input name="t_bal1" id="t_bal1" type="hidden" value="" class="pricebox1">
			<input name="t_bal" id="t_bal" type="text" value="<?php echo $this->t_bal;?>" class="pricebox1"></td></tr>
			
			</table></td>
        </tr>
		<tr>
            <td ><?php echo "Hotels";?></td>
            <td colspan="10"><table ><tr>
			<td>Type</td>
			<td>SINGLE</td>
			<td>DOUBLE</td>
			<td>TRIPLE</td>
			<td>Suite</td>
			<td>OTHERS</td><td>Total</td> <td>BALANCE</td></tr>
			<tr>
			<td><?php echo form_dropdown('stk_h', $list_st_type_v, $this->stk_h, 'id="stk_h"');?></td>
			<td> <input name="h_sgl" id="h_sgl" type="text" value="<?php echo $this->h_sgl;?>" class="pricebox1"></td>
			<td> <input name="h_dbl" id="h_dbl" type="text" value="<?php echo $this->h_dbl;?>" class="pricebox1"></td>
			<td> <input name="h_tpl" id="h_tpl" type="text" value="<?php echo $this->h_tpl;?>" class="pricebox1"></td>
			<td> <input name="h_suite" id="h_suite" type="text" value="<?php echo $this->h_suite;?>" class="pricebox1"></td>
			<td> <input name="h_oths" id="h_oths" type="text" value="<?php echo $this->h_oths;?>" class="pricebox1"></td>
			<td> <input name="h_tot" id="h_tot" type="text" value="<?php echo $this->h_tot;?>" class="pricebox1"></td>
			
			<td>
			<input name="h_bal1" id="h_bal1" type="hidden" value="" class="pricebox1">
			<input name="h_bal" id="h_bal" type="text" value="<?php echo $this->h_bal;?>" class="pricebox1"></td></tr>
			</table></td>
        </tr>
		
         <tr>
            <td><?php echo REMARKS;?></td>
            <td><label>
                <textarea name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo $this->remarks;?></textarea>
				
            </label></td>
			
			
        </tr>
<tr>
<td ><?php echo SALESAMT;?></td>
            
             <td >   <input name="slsamt" id="slsamt" type="text" value="<?php echo $this->totalamt;?>" class="pricebox1">
            </td>
			<td ><?php echo "Received";?>
            
                <input readonly="readonly" name="rcamt" id="rcamt" type="text" value="<?php echo $this->received;?>" class="pricebox1">
            </td>
			
</tr>		
		<!--<tr>
		<td><?php echo ACCCODENAME; echo $this->ddlAc;?> <span class="warning">*</span></td>
            <td><label>
			 <?php echo form_dropdown('ddlAccount', $list_account_multiple, $this->ddlAccount, 'id="ddlAccount2" class="ddlAccount2 chosen-select "');?>
              <?php //echo form_dropdown('ddlSubAccount',$list_sub_acc, $this->ddlsubAccount, 'id="ddlSubAccount" class="ddlSubAccount2"');?>
               <?php echo form_dropdown('ddlSubAccount', $this->group_entry_model->filterSubAccount($this->ddlAc,$this->tbl), $this->ddlsubAccount, 'id="ddlSubAccount" class="ddlSubAccount2"');?>
                           
             <?php //echo form_dropdown('ddlAccount', $list_account, $this->ddlAccount, 'id="ddlAccount"');?>
            </label></td>
            
			
			
        </tr>  -->     
      
            <!--<td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td colspan="5"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        --></tr>
		
	<?php } else {?>
	<tr>
           

            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><label><span id="loc">
                 <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
                </span>
            </label></td>
			<td><?php echo CUSTDATE;?> <span class="warning">*</span></td>
            <td><label>
             <input readonly="readonly" name="txtDate1" id="txtDate1" type="text" value="<?php echo $this->txtDate1;?>" class="pricebox">
			  <input name="txtaDoc" id="txtaDoc" type="hidden" value="<?php echo $this->txtadoc;?>" class="pricebox">
            </label></td>
        
		
		</tr>

        <tr>
            
          <td><?php echo CASHRECEIPTTYPE;?> </td>
            <td> 
                <?php echo form_dropdown('ddlType', $list_trans_value, $this->ddlTransaction, 'id="ddlType"');?>
            </td>

            <td><?php echo "GROUP NO";?></td>
            <td><label>
			<?php if($this->uri->segment(4) == 'a'){?>
                <input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"
                       value="AJT/" size="20" >
			<?php }else{?>
			<input name="txtDocNumber" id="txtDocNumber" class="textfielddoc currentvalue" type="text"
                       value="<?php echo $this->txtDocNumber;?>" size="20" readonly="readonly">
			<?php }?>
			 
            </label></td>

        </tr>

       
		
        <tr>
            <td><?php echo "Passanger Name";?></td>
            <td><label>
                <input readonly="readonly" name="txtRefName" id="txtRefName" type="text" value="<?php echo $this->txtRefName;?>" class="textfield">
            </label></td>

          
            <td><?php echo "Date of travel";?></td>
            <td><label>
             <input readonly="readonly" name="txtRefDate" id="txtRefDate" type="text" value="<?php echo $this->txtRefDate;?>" size="40" class="pricebox">
            </label></td>
        </tr>
		<tr>
            <td><?php echo CONTACT;?></td>
            <td><label>
                <input readonly="readonly" name="ctc1" id="ctc1" type="text" value="<?php echo $this->ctc1;?>" class="pricebox">
                                                    
			</label></td>
			<td><label>
                <input readonly="readonly" name="ctc2" id="ctc2" type="text" value="<?php echo $this->ctc2;?>" size="40" class="pricebox">
                              
			</label></td>
			<td><label>
                <input readonly="readonly" name="ctc3" id="ctc3" type="text" value="<?php echo $this->ctc3;?>" class="pricebox">
                              
			</label></td>
			

          
            
           
        </tr>

		
		<tr>
            <td ><?php echo "Tickets";?></td>
            <td colspan="10"><table ><tr>
			<td>Type</td>
			<td>Adult</td>
			<td>Child</td>
			<td>Infant</td>
			<td>Total</td><td>BALANCE</td></tr>
			<tr>
			<td><?php echo form_dropdown('stk_t', $list_st_type, $this->stk_t, 'id="stk_t"');?></td>
			<td> <input readonly="readonly" name="t_adt" id="t_adt" type="text" value="<?php echo $this->t_adt;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="t_cld" id="t_cld" type="text" value="<?php echo $this->t_cld;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="t_inf" id="t_inf" type="text" value="<?php echo $this->t_inf;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="tot_t" id="tot_t" type="text" value="<?php echo $this->tot_t;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="t_bal1" id="t_bal1" type="hidden" value="" class="pricebox1">
			<input readonly="readonly" name="t_bal" id="t_bal" type="text" value="<?php echo $this->t_bal;?>" class="pricebox1"></td></tr>
			
			</table></td>
        </tr>
		<tr>
            <td ><?php echo "Hotels";?></td>
            <td colspan="10"><table ><tr>
			<td>Type</td>
			<td>SINGLE</td>
			<td>DOUBLE</td>
			<td>TRIPLE</td>
			<td>Suite</td>
			<td>OTHERS</td><td>Total</td> <td>BALANCE</td></tr>
			<tr>
			<td><?php echo form_dropdown('stk_h', $list_st_type_v, $this->stk_h, 'id="stk_h"');?></td>
			<td> <input readonly="readonly" name="h_sgl" id="h_sgl" type="text" value="<?php echo $this->h_sgl;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="h_dbl" id="h_dbl" type="text" value="<?php echo $this->h_dbl;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="h_tpl" id="h_tpl" type="text" value="<?php echo $this->h_tpl;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="h_suite" id="h_suite" type="text" value="<?php echo $this->h_suite;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="h_oths" id="h_oths" type="text" value="<?php echo $this->h_oths;?>" class="pricebox1"></td>
			<td> <input readonly="readonly" name="h_tot" id="h_tot" type="text" value="<?php echo $this->h_tot;?>" class="pricebox1"></td>
			
			<td>
			<input readonly="readonly" name="h_bal1" id="h_bal1" type="hidden" value="" class="pricebox1">
			<input readonly="readonly" name="h_bal" id="h_bal" type="text" value="<?php echo $this->h_bal;?>" class="pricebox1"></td></tr>
			</table></td>
        </tr>
		
         <tr>
            <td><?php echo REMARKS;?></td>
            <td><label>
                <textarea readonly="readonly" name="txtRemarks" id="txtRemarks" rows="5" cols="5"><?php echo $this->remarks;?></textarea>
				
            </label></td>
			
			
        </tr>
<tr>
<td ><?php echo SALESAMT;?></td>
            
             <td >   <input readonly="readonly" name="slsamt" id="slsamt" type="text" value="<?php echo $this->totalamt;?>" class="pricebox1">
            </td>
			<td ><?php echo "Received";?>
            
                <input readonly="readonly" readonly="readonly" name="rcamt" id="rcamt" type="text" value="<?php echo $this->received;?>" class="pricebox1">
            </td>
			
</tr>		
		<!--<tr>
		<td><?php echo ACCCODENAME; echo $this->ddlAc;?> <span class="warning">*</span></td>
            <td><label>
			 <?php echo form_dropdown('ddlAccount', $list_account_multiple, $this->ddlAccount, 'id="ddlAccount2" class="ddlAccount2 chosen-select "');?>
              <?php //echo form_dropdown('ddlSubAccount',$list_sub_acc, $this->ddlsubAccount, 'id="ddlSubAccount" class="ddlSubAccount2"');?>
               <?php echo form_dropdown('ddlSubAccount', $this->group_entry_model->filterSubAccount($this->ddlAc,$this->tbl), $this->ddlsubAccount, 'id="ddlSubAccount" class="ddlSubAccount2"');?>
                           
             <?php //echo form_dropdown('ddlAccount', $list_account, $this->ddlAccount, 'id="ddlAccount"');?>
            </label></td>
            
			
			
        </tr>  -->     
      
            <!--<td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td colspan="5"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        --></tr>
	
	<?php }?>
      <tr>
        	<td colspan="10">
                <table id="dataTable" title="dataTable">
                <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" tabindex="-1" />
                <tr>
                    <td>&nbsp;</td>
					<td><?php echo "Date";?></td>
                    <td><?php echo "Received";?></td>
                    <td><?php echo "Balance";?></td>
					<td><?php echo "Document";?></td>
                    <td><?php echo DESCRIPTION;?></td>
                </tr>
                <?php
                if(!empty($chkLoc))
                {
                    foreach($chkLoc as $key => $aitem):
                ?>
                <tr>
				
                    <td><input hidden="hidden" type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect<?php echo $key;?>" class="chkSelect" value="<?php echo $chkDetailID[$key];?>" tabindex="-1"></td>   
                     <td><input style="min-width: 70px !important;" class="txtRDate"  name="txtRDate[]" id="txtRDate<?php echo $key;?>" type="text" value="<?php echo $chkdate[$key];?>" class="pricebox1"></td>
                    <td><input name="chkRAmount[]" id="chkRAmount<?php echo $key;?>" class="textfield6 dcRate" data-row="<?php echo $key+1;?>" data-name="dcrate<?php echo $key+1;?>"  type="text" value="<?php echo stripslashes($chkRAmount[$key]);?>"></td>
                    <td><input name="chkBAmount[]" id="chkBAmount<?php echo $key;?>" class="textfield4 fcRate" type="text" value="<?php echo stripslashes($chkBAmount[$key]);?>" tabindex="-1"></td>
					<td>   <?php echo form_dropdown('doc[]', $list_doc, $chkdoc[$key],"id='doc".$key."'");?></td>
                    <td><textarea name="txtDes[]"  id="txtDes<?php echo $key;?>" rows="2" cols="8" style="min-width: 100px;" class="txtDes"><?php echo stripslashes($chkDes[$key]);?></textarea></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkDetailID[$key];?>" title="Delete"/></td>
                </tr>
                <?php
                    endforeach;
                }//end if
                else
                { ?>
                <tr>
                    <td><input hidden="hidden" type="checkbox" checked onclick="return false" name="chkbox[]" id="chkSelect" class="chkSelect" value=""  tabindex="-1"></td>
                     <td><input style="min-width: 70px !important;" name="txtRDate[]" id="txtRDate" type="text" value="<?php echo $this->txtDate1;?>" class="pricebox1"></td>
                      
                    <td><input name="chkRAmount[]" id="chkRAmount" class="textfield6 dcRate" type="text" data-row="1" data-name="dcrate1" value=""></td>
                    <td><input name="chkBAmount[]" id="chkBAmount" class="textfield4 fcRate" type="text" value="" tabindex="-1"></td>
					<td> <?php echo form_dropdown('doc[]', $list_doc, $this->doc, 'id="doc" class="doc"');?></td>
                    <td><textarea name="txtDes[]" id="txtDes" rows="2" cols="8" style="min-width: 180px;" class="txtDes"></textarea></td>

                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_>" title="Delete"/></td>
                </tr>
                <?php
                }//end else
                ?>
             </table>
            </td>
       </tr>
        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="5"><label>
			 
				<input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
				
              <?php if($this->uri->segment(4) == 'a'){?>
                <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo "BACK TO GROUP ENTRY";?>" id="btnCancel">
            </label>
               
        </tr>
    </tbody>
  </table>

    <!-- Start of Sticky Note -->
    <div id="toPopup1" class="toPopup">
        <div class="close"></div>
        <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
        <div id="popup_content"> <!--your content start-->
        <?php //echo $this->load->view('group_entry/show_allocation');?>
        </div> <!--your content end-->
    </div>
    <!-- End of Sticky Note -->
  
  </form>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.2/js/select2.min.js"></script>
 
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
	   //$(selector).chosen(config[selector]);
	   //$(selector).trigger("chosen:updated");
	  // $(selector).chosen("destroy");
    }
  </script>

  </div>
