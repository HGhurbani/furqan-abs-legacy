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
		window.location.replace('<?php echo base_url().'index.php/account_head/showlist/'.$this->uri->segment(3);?>');
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
			 ddlAccgroup: {
					selectNone : true
					},
			 txtClscode: {
					required : true
			 		},
			 txtAcchead: {
					required : true
					}/*,
			 ddlDivision: {
					selectNone : true
					},
			 'ddlLocation[]': {
					selectNone : true
					}
*/		},
		messages: {
			ddlGroup: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			 ddlAccgroup: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
					},
			txtClscode: {
				required : "<?php echo SUBCLSCODE;?><?php echo CANTBEBLANK;?>"
			},
			txtAcchead: {
				required : "<?php echo ACCHEADNAME;?><?php echo CANTBEBLANK;?>"
			}/*,
			ddlDivision: {
					selectNone : "<?php echo PLEASESELECTANOPTION;?>" 
			},
		   'ddlLocation[]': {
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

	
/*------------------Custom Add new row for account------------------*/

    var i = 1;
    $("#addRow").click(function() {
        var m = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
            var id = this.id;
            $(this).attr({
                'id': function(_, id2) { return id + i; },
                'value': ''
            });
        }).end().find("select").each(function() {
                var id = this.id;
                $(this).attr({
                    'id': function(_, id2) { return id + i; },
                    'value': ''
                });
            }).end().insertAfter('#dataTable tbody>tr:last');
        i++;
        var rowCount = $("#dataTable").attr('rows').length;
        m.find('img[class="del"]').attr('src','<?php echo base_url();?>static/images/cross.png');
        return false;
    });
	
/*------------------Custom delete row ------------------*/	
	
	$('.del').live('click',function(){
		var rowCount = $("#dataTable").attr('rows').length;
		if((rowCount)>2)
		{
			$(this).parent().parent().remove();
			//delete from the db
			var dId = this.id;
			dId     = dId.split('_');
			//delete from db
			$.ajax(
			 {
				 type:'POST',
				 url:'<?php echo base_url().'index.php/account_head/deleteRecord/'.$this->uri->segment(3);?>',
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
			$('#del_'+rowCount).attr('disabled', true);
		}//end if
	});

    $('.textfield7').keyup(function()
    {
        var nId   = this.id;
        var getId = nId.split('txtMindigit');
        var nVal  = $('#txtPrefix'+getId[1]).val();
        var nVal1 = $('#txtMindigit'+getId[1]).val();
        var nVal2 = $('#ddlSubAuto'+getId[1]).val();
        $.ajax({
            type:'POST',
            data:{txtPrefix:nVal,txtMindigit:nVal1,ddlSubAuto:nVal2},
            url:'<?php echo base_url().'index.php/account_head/generateSubLedgerNo/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                //$('#result2').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#txtSublno'+getId[1]).val(response);
            }
        });
    });

    $('.Subsale').change(function()
    {
        var nId  = this.id;
        var nVal = $('#'+nId).val();
        var getId = nId.split('ddlSubsale');
		
		switch(nVal)
		{
			case "1":
				if( $('#ddlSubtype'+getId[1]).val() == 'N')
				{
					$("select#ddlSubtype"+getId[1]+" > option[value*='N']").remove();
				}//end if
				$('#ddlSubledger'+getId[1]).val('S');
			break;
				
			case "0":
				$('#ddlSubtype'+getId[1]).append(new Option("Normal", "N",true));
				$('#ddlSubledger'+getId[1]).val('O');
			break;	
		}//end switch
   });
	

    $('.chkDivision').change(function(e) {
        var id   = this.id;
        var nVal = $('#'+id).val();
        var getId = id.split('ddlDivision');
        $.ajax(
            {
                type:'POST',
                data:{ddlDivision:nVal},
                url:'<?php echo base_url().'index.php/account_head/filterDivision/'.$this->uri->segment(3);?>',
                dataType:'html',
                beforeSend:function () {
                    $('#ddlLocation'+getId[1]).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
                },
                success:function (response) {
                    $('#ddlLocation'+getId[1]).html(response);
                }
            });
    });
});
</script>
<?php
echo $this->common_model->successMsg($this->uri->segment(5));
echo validation_errors('<p class="errorClass">','</p>');
$attributes = array('name' => 'frmCountry', 'id' => 'frmValidate');

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
<?php echo form_open('account_head/editAcchead/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idHAArray', $chkAccID);?>
<?php echo form_hidden('idHCArray', $chkAccID2);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td width="14%"><?php echo ACCGROUPNAME;?> <span class="warning">*</span></td>
            <td width="86%"><label>
             <?php echo form_dropdown('ddlAccgroup', $list_acc_group, $this->ddlAccgroup, 'id="ddlAccgroup"');?>
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo SUBCLSCODE;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtClscode" id="txtClscode" class="textfield3" type="text" value="<?php echo stripslashes($this->txtClscode);?>" size="4">
            </label></td>
        </tr>
        
        <tr>
            <td><?php echo ACCHEADNAME;?> <span class="warning">*</span></td>
            <td><label>
             <input name="txtAcchead" id="txtAcchead" class="textfield" type="text" value="<?php echo stripslashes($this->txtAcchead);?>" size="40">
            </label></td>
        </tr>
		
        <tr>
            <td colspan="2">
                <h6><?php echo ACCNAME;?></h6>
                <table id="dataTable" title="dataTable">
                    <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
                    <tr>
                        <td><?php echo ACCCODENAME;?></td>
                        <td><?php echo ACCNAMETITLE;?></td>
                        <td><?php echo SUBSIDARYSALE;?></td>
                        <td><?php echo SUBLEDGERNOT;?></td>
                        <td><?php echo SUBLEDGERTYPE;?></td>
                        <td><?php echo SUBLEDGERAUTO;?></td>
                        <td><?php echo SALESTYPE;?></td>
                        <td><?php echo TRANSPREFIX;?></td>
                        <td><?php echo TRANSMINDIGIT;?></td>
                        <td><?php echo SUBLEDGERNUM;?></td>
                        <td><?php echo DIVISIONNAME;?></td>
                        <td><?php echo LOCATIONNAME;?></td>
                        <td><?php echo ALLOCATIONFLAG;?></td>
                        <td><?php echo REFUNDCONTROL;?></td>
                    </tr>
                    <?php
                    if(!empty($chkAccCode))
                    {
                    foreach($chkAccCode as $key => $aitem):
                    ?>
                    <tr>
                        <td><label>
                                <input name="txtAccCode[]" id="txtAccCode" class="textfield2" type="text" value="<?php echo stripslashes($aitem);?>" size="4">
                            </label>
                        </td>

                        <td><label>
                                <input name="txtAccTitle[]" id="txtAccTitle" class="textfield" type="text" value="<?php echo stripslashes($chkAccTitle[$key]);?>" size="40">
                            </label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubsale[]', $list_sub_sale, $chkAccSubsale[$key],"id='ddlSubsale".$key."'  class='Subsale'");?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubledger[]', $list_sub_ledger, $chkAccSubledger[$key],"id='ddlSubledger".$key."' class='Subledger'");?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubtype[]', $list_sub_type,$chkAccSubtype[$key],"id='ddlSubtype".$key."' class='Subtype'");?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubAuto[]', $list_sub_auto,$chkAccSubAuto[$key],"id='ddlSubAuto".$key."' class='SubAuto'");?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSalesType[]', $list_sale_type,$chkAccSaleType[$key],"id='ddlSalesType".$key."' class='singleDrop'");?>
                        </label></td>

                        <td><label>
                            <input name="txtPrefix[]" id="txtPrefix<?php echo $key;?>" class="textfield6" type="text" value="<?php echo stripslashes($chkAccPrefix[$key]);?>" size="4">
                        </label></td>

                        <td><label>
                            <input name="txtMindigit[]" id="txtMindigit<?php echo $key;?>" class="textfield7" type="text" value="<?php echo stripslashes($chkAccMindigit[$key]);?>" size="4">
                        </label></td>

                        <td><label>
                            <input name="txtSublno[]" id="txtSublno<?php echo $key;?>" class="textfield3" type="text" value="<?php echo stripslashes($chkAccSublno[$key]);?>" size="40">
                        </label></td>

                        <td>
                            <?php echo form_dropdown('ddlDivision[]', $list_div_value, $chkAccDivision[$key], 'id="ddlDivision'.$key.'" class="chkDivision"');?>
                        </td>

                        <td><label><?php echo form_dropdown('ddlLocation[]', $this->accounthead_model->filterLocation($chkAccDivision[$key]),  $chkAccLocation[$key], 'id="ddlLocation'.$key.'" class="singleDrop"');?></label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlAllflag[]', $list_all_flag, $chkAllflag[$key], 'id="ddlAllflag" class="singleDrop"');?>
                        </label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlRefund[]', $list_refund_flag, $chkRefund[$key], 'id="ddlRefund" class="singleDrop"');?>
                        </label>
                        </td>
                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $chkAccID[$key]; ?>" title="Delete"/></td>
                    </tr>
                    <?php
                    endforeach;
                    }//end if 
                    else
                    {
                    $i = 0;
                    while ($i < 4) 
					{?>
                    <tr>
                        <td><label>
                                <input name="txtAccCode[]" id="txtAccCode<?php echo $i;?>" class="textfield2" type="text" value="" size="4">
                            </label>
                        </td>

                        <td><label>
                                <input name="txtAccTitle[]" id="txtAccTitle<?php echo $i;?>" class="textfield" type="text" value="" size="40">
                            </label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubsale[]', $list_sub_sale, $this->ddlSubsale,'id="ddlSubsale'.$i.'" class="Subsale"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubledger[]', $list_sub_ledger, $this->ddlSubledger,'id="ddlSubledger'.$i.'" class="Subledger"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubtype[]', $list_sub_type,$this->ddlSubtype,'id="ddlSubtype'.$i.'" class="Subtype"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSubAuto[]', $list_sub_auto,$this->ddlSubAuto,'id="ddlSubAuto'.$i.'" class="SubAuto"');?>
                        </label></td>

                        <td><label>
                            <?php echo form_dropdown('ddlSalesType[]', $list_sale_type,$this->ddlSalesType,"id='ddlSalesType".$key."' class='singleDrop'");?>
                        </label></td>

                        <td><label>
                            <input name="txtPrefix[]" id="txtPrefix<?php echo $i;?>" class="textfield6" type="text" value="" size="4">
                        </label></td>

                        <td><label>
                            <input name="txtMindigit[]" id="txtMindigit<?php echo $i;?>" class="textfield7" type="text" value="" size="4">
                        </label></td>

                        <td><label>
                            <input name="txtSublno[]" id="txtSublno<?php echo $i;?>" class="textfield3" type="text" value="" size="40">
                        </label></td>

                        <td>
                            <?php echo form_dropdown('ddlDivision[]', $list_div_value, $this->ddlDivision, 'id="ddlDivision'.$i.'" class="chkDivision"');?>
                        </td>

                        <td><label><span id="loc"><?php echo form_dropdown('ddlLocation[]', $list_loc_value, $this->ddlLocation, 'id="ddlLocation" class="singleDrop"');?></span></label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlAllflag[]', $list_all_flag, $this->ddlAllflag, 'id="ddlAllflag" class="singleDrop"');?>
                            </label>
                        </td>

                        <td><label>
                            <?php echo form_dropdown('ddlRefund[]', $list_refund_flag, $this->ddlRefund, 'id="ddlRefund" class="singleDrop"');?>
                            </label>
                        </td>

                        <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nAId; ?>" title="Delete"/></td>
                    </tr>
                    <?php
					 $i++;
                 	}//end while

                    }//end else
                    ?>
                </table>
            </td>
        </tr>
		
        <tr>
            <td><?php echo ACTIVEFLAG;?> <span class="warning">*</span></td>
            <td><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>

        <tr class="formButs">
            <td>&nbsp;</td>
            <td><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
              <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOACCHEAD;?>" id="btnCancel">
            </label></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
