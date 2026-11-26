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
/*$.validator.setDefaults({
	submitHandler: function() {}
});
*/
$(document).ready(function()
{
//    ('#ddlCountry2').val("5");// $('#ddlCountry').val());
/*
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
	$("#frmValidate2").validate({
		rules: {
			txtTitle2: {
		 			required : true,
/*						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/hotel_voucher/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/hotel_voucher/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			ddlCountry2: {
					selectNone : true
					},
			ddlService2: {
					selectNone : true
					},		
			ddlCity2: {
					selectNone : true
					}
		},
		messages: {
			txtTitle2: {
				required : "<?php echo HOTELNAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo HOTELNAMEALREADYTAKEN;?>" 
			},

		},
	 success: function(label2) { 
            // set   as text for IE 
            label2.html("").addClass("checked"); 
        } ,
		submitHandler: function(form) {
                $(form).ajaxSubmit();
             } 
		
	});
		*/	
		$('#btnFinish').click(function () {
                    var txtTitle2 = $('#txtTitle2').val();
                    var chkActive2 = $('#chkActive2').val(); 
                    var ddlCity2 = $('#ddlCity2').val(); 
                    var ddlCountry2 = $('#ddlCountry2').val(); 
                    var ddlService2 = $('#ddlService2 ').val(); 
                    var txtPhone2 = $('#txtPhone2').val(); 
                    var txtFax2 = $('#txtFax2').val(); 
                    var txtAddress2 = $('#txtAddress2').val();					//alert(ddlCity2);
                    if((ddlCity2 != 0)&& (ddlCountry2 != 0 ) && ( ddlService2 != 0)&& (txtTitle2 != '')){                    
                         $.ajax({
                                type:'POST',
                                data:{txtTitle2:txtTitle2,chkActive2:chkActive2,ddlCity2:ddlCity2,ddlCountry2:ddlCountry2,ddlService2:ddlService2,txtPhone2:txtPhone2,txtFax2:txtFax2,txtAddress2:txtAddress2},
                                url:'<?php echo base_url().'index.php/hotel_voucher/editHotel/'.$this->uri->segment(3);?>',
                                dataType:'html',
                                success: function(response) {
                                    $('#toPopup4').hide();
                                    var nCountry = $('#ddlCountry').val();
                                    var nCity    = $('#ddlCity').val();
                                    $('#ddlCity2').val(nCity);
                                    $('#ddlCountry2').val(nCountry);
                                    $('#txtTitle2').val("");
                                    $('#txtPhone2').val("");
                                    $('#txtFax2').val("");  
                                    $('#txtAddress2').val(""); 
                                    $.ajax(
                                        {
                                        type:'POST',
                                        data:{ddlCountry:nCountry,ddlCity:nCity},
                                        url:'<?php echo base_url().'index.php/hotel_voucher/filterHotel/'.$this->uri->segment(3);?>',
                                        dataType:'html',
                                        success: function(response) {
                                        $('#hotel').html(response);
                                        }
                                        });
                                 }

                          });

                                                                 /* 
                                    if(ddlCity2 == $('#ddlCity').val()){
                                        //                                        var optVal = $("#hotel option:contains('"+txtTitle2+"')").attr('value');
                                        //                                       $("#hotel").val( optVal )
//                                        $("#hotel option:contains("+txtTitle2+")").attr('selected', true);
$("#hotel").find("option:contains('text')").each(function(){
  if( $(this).text() == txtTitle2){
    $(this).attr("selected","selected");
  }
});
alert($("#hotel").text());
alert(txtTitle2);

                                    }*/
 }
		});

	
	$('#ddlCountry2').change(function()
	{
		var nVal  = $('#ddlCountry2').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlCountry2:nVal},
			 url:'<?php echo base_url().'index.php/hotel_voucher/filterCountry2/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#city2').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#city2').html(response);
				}
		  });
	});
	


	
});
</script>
<?php 
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes2 = array('name' => 'frmCategory2', 'id' => 'frmValidate2');

if(isset($error_value) && trim($error_value)!='')
{
?>
<code><?php echo $error_value;?></code>
<?php
}//end if
?>
<?php //$this->load->view('general/admin_menu');?>
<?php $this->load->helper(array('form', 'url')); 
$this->load->library('form_validation'); 
?>
<?php echo form_open('hotel_voucher/add_Hotel/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes2); ?>

<tr>
    <td colspan="8"><label><strong><?php echo ADDHOTEL;?></strong></label></td>
</tr>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%" align="left"><?php echo COUNTRYNAME;?> <span class="warning">*</span></td>
            <td width="34%" align="left"><label>
             <?php echo form_dropdown('ddlCountry2', $list_country_value,$this->ddlCountry2 ," id='ddlCountry2'");?>
            </label></td>
            <td><?php echo CITYNAME;?> <span class="warning">*</span></td>
            <td>
             <span id="city2">
            <?php echo form_dropdown('ddlCity2', $list_city_value, $this->ddlCity2,'id="ddlCity2"');?>
            </span>
            </td>
        </tr>
        
        <tr>
        <td><?php echo SERVICETYPE;?> <span class="warning">*</span></td>
          <td><label>
             <?php echo form_dropdown('ddlService2', $list_services_value, $this->ddlService2," id='ddlService2'");?>
            </label></td>
          <td><?php echo HOTELNAME;?> <span class="warning">*</span></td>
          <td><label>
             <input id="txtTitle2" name="txtTitle2"  class="textfield" type="text" value="<?php echo $this->txtTitle2;?>" size="40">
            </label></td>
        </tr>
      
        <tr>
            <td><?php echo PHONE;?></td>
              <td><label>
                 <input name="txtPhone2"  class="textfield" type="text" value="<?php echo $this->txtPhone2;?>" size="40" id="txtPhone2">
                </label></td>
              <td><?php echo FAX;?></td>
              <td><label>
                 <input name="txtFax2"  class="textfield" type="text" value="<?php echo $this->txtFax2;?>" size="40" id="txtFax2">
                </label></td>
        </tr>  
 
 		<tr>
           <td><?php echo ADDRESS;?></td>
           <td colspan="4"><label>
             <textarea name="txtAddress2" id="txtAddress2"><?php echo $this->txtAddress2;?></textarea>
            </label></td>
        </tr>
                   
        <tr>
            <td><?php echo ACTIVE;?> </td>
            <td colspan="3"><label>
            <input id="chkActive2" type="radio" name="chkActive2" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive2" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>
        
<!--        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="3"><label>
              <input type="submit" name="btnGo2" id="btnGo2" value="<?php echo $this->btnVal;?>" />
			  <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit2" id="btnSaveExit2" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
            </label></td>
        </tr>

-->
           <tr class="formButs">
                <td>&nbsp;</td>
                <td colspan="6"><label>
                    <input type="button" name="btnFinish" id="btnFinish" value="Save & Exit" />
                </label></td>
            </tr>
  

    </tbody>
  </table>
  </form>
</div>
