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

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/airline/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
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
			txtAirlineNo: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/airline/checkAirlineNo/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/airline/checkAirlineNo'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtAirlineName: {
						required : true,
						<?php
						if($this->uri->segment(5) !='')
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/airline/checkTitle/'.$this->uri->segment(5);?>',type: "post"}
						<?php	
						}//end if
						else
						{
						?>
						remote   : {url:'<?php echo base_url().'index.php/airline/checkTitle'?>',type: "post"}
						<?php
						}//end else
						?>
					},
			txtAlpha2: {
					required : true
					},
			txtAlpha3: {
					required : true
					},
			ddlAirlineType: {
					selectNone : true
					}
		},
		messages: {
			txtAirlineNo: {
				required : "<?php echo AIRLINECODE;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo AIRLINENOALREADYTAKEN;?>" 
			},
			txtAirlineName: {
				required : "<?php echo AIRLINENAME;?><?php echo CANTBEBLANK;?>",
				remote   : "<?php echo AIRLINENAMEALREADYTAKEN;?>" 
			},
			txtAlpha2: {
				required : "<?php echo AIRLINEALPHA2;?><?php echo CANTBEBLANK;?>"
			},
			txtAlpha3: {
				required : "<?php echo AIRLINEALPHA3;?><?php echo CANTBEBLANK;?>"
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
	
/*------------------Custom Add new row for account------------------*/
	
	  $("#addRow").click(function() {
		  var m	       = $('#dataTable tbody>tr:last').clone(true).insertAfter('#dataTable tbody>tr:last');
		  var rowCount = $("#dataTable").attr('rows').length;
		   m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
		   return false;
	});
	
/*------------------Custom delete row ------------------*/	
	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if((rowCount)>1)
		{
			$(this).parent().parent().remove();
			//delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/airline/deleteRecord/'.$this->uri->segment(3);?>',
				 data:{subCode:dId[1]},
				 dataType:'html',
				 beforeSend: function() {
						//$('#txtAccname'+nId).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
					},
					success: function(response) {
					}
			});
		}//end if
		else
		{
			//var src = $(this).attr("src").replace("cross", "cross_grey_small");
			
			$('#del_'+rowCount).attr('disabled', true);
           // $(this).attr("src", src);

		}//end if
			
	});

    $('#ddlARAc').change(function()
    {
        var nVal  = $('#ddlARAc').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlARAc:nVal},
                url:'<?php echo base_url().'index.php/airline/filterSubArAc/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#subArAc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#subArAc').html(response);
                }
            });
    });

    $('#ddlAPAc').change(function()
    {
        var nVal  = $('#ddlAPAc').val();
        $.ajax(
            {
                type:'POST',
                data:{ddlAPAc:nVal},
                url:'<?php echo base_url().'index.php/airline/filterSubApAc/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend: function() {
                    $('#subApAc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success: function(response) {
                    $('#subApAc').html(response);
                }
            });
    });
	
});
</script>
<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCategory', 'id' => 'frmValidate');

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
<?php echo form_open('airline/editAirline/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idHAArray', $chkAccID);?>
<?php echo form_hidden('idHCArray', $chkAccID2);?>

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
       <tr>
              <td><?php echo AIRLINECODE;?> <span class="warning">*</span></td>
              <td><label>
                 <input name="txtAirlineNo"  class="textfield3" type="text" value="<?php echo $this->txtAirlineNo;?>" id="txtAirlineNo">
            </label></td>
            
              <td><?php echo AIRLINEALPHA;?> <span class="warning">*</span></td>
              <td><label>
                 <input name="txtAlpha"  class="textfield4" type="text" value="<?php echo $this->txtAlpha;?>" id="txtAlpha">
            </label></td>
            
              <td><?php echo AIRLINENAME;?> <span class="warning">*</span></td>
              <td><label>
                <input name="txtAirlineName" class="textfield" type="text" value="<?php echo $this->txtAirlineName;?>" id="txtAirlineName">
            </label></td>
        </tr>  
 
        <tr>
            <td><?php echo ARAC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlARAc', $list_ar_ac, $this->ddlARAc," id='ddlARAc'");?>
          </label></td>
          
            <td><?php echo SUBAC;?></td>
            <td><span id="subArAc">
            <?php echo form_dropdown('ddlSubARAc', $list_subar_ac, $this->ddlSubARAc," id='ddlSubARAc'");?></span>
		    </td>

            <td><?php echo AIRLINETYPE;?> <span class="warning">*</span></td>
            <td><label>
             <?php echo form_dropdown('ddlAirlineType', $list_airline_type, $this->ddlAirlineType," id='ddlAirlineType'");?>
          </label></td>
        </tr>
        
		<tr>
            <td><?php echo APAC;?></td>
            <td><label>
             <?php echo form_dropdown('ddlAPAc', $list_ap_ac, $this->ddlAPAc," id='ddlAPAc'");?>
          </label></td>
          
            <td><?php echo SUBAC;?></td>
            <td><span id="subApAc">
             <?php echo form_dropdown('ddlSubAPAc', $list_subap_ac, $this->ddlSubAPAc," id='ddlSubAPAc'");?></span>
		  </td>
        </tr> 

        <tr>
             <td><?php echo TKTCOMMISSION;?></td>
             <td><input name="txtTktComm" class="textfield inpsmallbox" type="text" value="<?php echo $this->txtTktComm;?>" id="txtTktComm"></td>
             
             <td><?php echo XOCOMMISSION;?></td>
             <td><input name="txtXoComm" class="textfield inpsmallbox" type="text" value="<?php echo $this->txtXoComm;?>" id="txtXoComm"></td>
             
             <td><?php echo GSACOMMISSION;?></td>
             <td><input name="txtGSAComm" class="textfield inpsmallbox" type="text" value="<?php echo $this->txtGSAComm;?>" id="txtGSAComm"></td>
         </tr>

		 <tr>
          <td colspan="6">
            <div class="release_listcontainer">
              <ul class="menu_rls">
                <li><strong><?php echo FARECODE;?></strong></li>
                </ul>
              <div id="result">
                <span class="clears"></span>
                <div class="listcontnts" style="width:1374px;">
                <table id="dataTable" title="dataTable">
                    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
                    <?php
                        if(!empty($chkAccCode))
                        {
                            foreach($chkAccCode as $key => $aitem):
                    ?>
                        <tr>
                           <td><?php echo CODE;?></td>
                           <td><label>
                           <input name="txtAccCode[]" id="txtAccCode" class="textfield22 inpsmallbox" type="text" value="<?php echo stripslashes($aitem);?>" size="4">
                           </label></td>
                           
                           <td><?php echo FAREVALUE;?></td>
                           <td><label>
                           <input name="txtAccTitle[]" id="txtAccTitle" class="textfield33 inpsmallbox" type="text" value="<?php echo stripslashes($chkAccTitle[$key]);?>" size="40">
                           </label></td>
                           <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkAccID[$key]; ?>" title="Delete"/></td>
                        </tr>
                        <?php 
                            endforeach;
                        }//end if
                        else
                        {?>
                        <tr>
                           <td><?php echo CODE;?></td>
                           <td><label>
                           <input name="txtAccCode[]" id="txtAccCode" class="textfield22 inpsmallbox" type="text" value="" size="4">
                           </label></td>
                           
                           <td><?php echo FAREVALUE;?></td>
                           <td><label>
                           <input name="txtAccTitle[]" id="txtAccTitle" class="textfield33 inpsmallbox" type="text" value="" size="40">
                           </label></td>
                           <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nArId; ?>" title="Delete"/></td>
                        </tr>
                        <?php
                            }//end else
                        ?>
				 </table>
                </div>
              </div>
             </div>
            </td>
         </tr> 

		 <tr>
         	<td colspan="6"><strong><?php echo OTHERDETAILS;?></strong></td>
         </tr>
          
         <tr>
            <td><?php echo NAME;?></td>
            <td><label>
             <input name="txtName" id="txtName" class="textfield44" type="text" value="<?php echo stripslashes($this->txtName);?>">
            </label></td>
            
            <td><?php echo DESIGNATION;?></td>
            <td colspan="3"><label>
             <input name="txtDesignation" id="txtDesignation" class="textfield55" type="text" value="<?php echo stripslashes($this->txtDesignation);?>" >
            </label></td>
         </tr>
         
         <tr>   
            <td><?php echo MOBILE;?></td>
            <td><label>
             <input name="txtMobile" id="txtMobile" class="textfield66" type="text" value="<?php echo stripslashes($this->txtMobile);?>" >
            </label></td>
            
            <td><?php echo LANDPHONE;?></td>
            <td colspan="3"><label>
             <input name="txtPhone" id="txtPhone" class="textfield77" type="text" value="<?php echo stripslashes($this->txtPhone);?>" >
            </label></td>
         </tr>
         
         <tr>   
            <td><?php echo EMAIL;?></td>
            <td><label>
             <input name="txtEmail" id="txtEmail" class="textfield88" type="text" value="<?php echo stripslashes($this->txtEmail);?>" >
            </label></td>
            
           <td><?php echo ADDRESS;?></td>
           <td colspan="3"><label>
             <textarea name="txtAddress" id="txtAddress" class="textfield99" type="text"><?php echo stripslashes($this->txtAddress);?></textarea>
           </label></td>
        </tr>
           
         <tr>
            <td><?php echo ACTIVE;?> </td>
            <td colspan="5" align="left"><label>
          <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
         </tr>
        
         <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="5" align="left"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
              <?php if($this->uri->segment(4) == 'a')
              {?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOAIRLINE;?>" id="btnCancel">
           </label></td>
         </tr>
    </tbody>
  </table>
  </form>
</div>
