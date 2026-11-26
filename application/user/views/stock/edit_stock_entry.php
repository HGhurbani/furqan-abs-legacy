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

function checkAll() 
{
	if(document.getElementById('chkAll').checked==false)
	{
		uncheckAll();
	}//end if
	else
	{
		for (var i = 0; i < document.frmGroup.elements.length; i++) 
		{
			var e = document.frmGroup.elements[i];
			if (e.name == "ddlAcc[]") 
			{
				e.checked = true;
			}//end if
		}//end for loop
	}//end else
}//end function	

function uncheckAll() 
{
	for (var i = 0; i < document.frmGroup.elements.length; i++) 
	{
		var e = document.frmGroup.elements[i];
		if (e.name == "ddlAcc[]") 
		{
			e.checked = false;
		}//end if
	}//end for loop
}//end function	

function getMainValidation() {
    $("#frmValidate").validate({
        rules:{
            ddlDivision:{
                selectNone:true
            },
            ddlLocation:{
                selectNone:true
            },
            txtDate:{
                required:true
            },
            txtDocNo:{
                required:true
            },
            txtYearRef:{
                required:true
            }
        },
        messages:{
            ddlDivision:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            ddlLocation:{
                selectNone:"<?php echo PLEASESELECTANOPTION;?>"
            },
            txtDate:{
                required:"<?php echo CUSTDATE;?><?php echo CANTBEBLANK;?>"
            },
            txtDocNo:{
                required:"<?php echo DOCNUMBER;?><?php echo CANTBEBLANK;?>"
            },
            txtYearRef:{
                required:"<?php echo YEARREF;?><?php echo CANTBEBLANK;?>"
            }
        },
        success:function (label2) {
            // set   as text for IE
            label2.html("").addClass("checked");
        },
        submitHandler:function (form) {
            $(form).ajaxSubmit();
        }

    });
}//end validating function

$(document).ready(function()
{
	$('#btnCancel').click(function()
	{
		window.location.replace('<?php echo base_url().'index.php/stock_entry/showlist/'.$this->uri->segment(3);?>');
		return false;
	});
	
	$("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});
	
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
    $('#btnSaveExit').click(function(){
        if($('#txtStockTraQty').val() == '' || $('#txtStockTraQty').val() == 0)
        {
            getMainValidation();
        }
    });

    $('#btnGo').click(function(){
        if($('#txtStockTraQty').val() == '' || $('#txtStockTraQty').val() == 0)
        {
            getMainValidation();
        }
    });

	/*------------------Custom Add new row for account------------------*/
	var i = 1;
	  $("#addRow").click(function() {
		  var m	       = $('#dataTable tbody>tr:last').clone(true).find("input").each(function() {
			  			var id = this.id;
						var tid = id.split('txtStartNo');
						var tid2 =  id.split('txtEndNo');
						
						var tid3 = (tid[1] != 'undefined')?tid2[1]:tid[1];
						
						$(this).attr({
						  'id': function(_, id2) { return id + i; },
						  'value': '',
						  'title': tid3+i   
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
				 url:'<?php echo base_url().'index.php/stock_entry/deleteRecord/'.$this->uri->segment(3);?>',
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
		
   $('.ddlStockType').change(function()
	{
		var nId   = this.id;
		var nVal  = $('#'+nId).val();
       
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlStockType:nVal},
			 url:'<?php echo base_url().'index.php/stock_entry/getStockCode/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					//$('#result2').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) { 
					getVal(nId,response,nVal);
				}
		  });
	});
	
	function getVal(sid,response,catid)
	{
		$('.ddlStockType').each(function() {
			var id     = this.id;
            var val    = $('#txtStockCode'+sid).val();
			var valChk = $('#'+sid).val();
			
			var newArr  = chkValExit(catid);
			
			if(jQuery.inArray(response,newArr) == 0)
			{
				$('#txtStockCode'+sid).val(getNewVal(newArr.pop()));
				return false;
			}//end if
			else
			{
				$('#txtStockCode'+sid).val(response);
				return false;
			}//end else	
        });
	}//end funciton
	
	//function chkValExit(chkVal)
	function chkValExit(catid)
	{
		var valArray = [];
		
		$('.ddlStockType').each(function() 
		{
			var id    = this.id;
            var val   = $('#txtStockCode'+id).val();
			
			var newval  = val.split("-");
			
			if(val != '' && newval[0] == catid)
				valArray.push(val)
		});
		
		return (valArray);
	}//end funciton
	
	//generate new valu
	function getNewVal(val)
	{
		var newval  = val.split("-");
		var newval2 = parseFloat(newval[1])+1;
		newval2     = newval[0] +'-'+newval2;
		
		return (newval2);
	}//end funciton
					 
	<?php if($this->uri->segment(4) == 'a')
	{?>
	  $.ajax(
	  {
		 type:'POST',
		 data:{ddlDivision:$('#ddlDivision').val()},
		 url:'<?php echo base_url().'index.php/stock_entry/filterDivision/'.$this->uri->segment(3);?>',
		 dataType:'html',
		 beforeSend: function() {
				$('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
			},
			success: function(response) {
				$('#loc').html(response);
			}
	  });

	<?php }//end if
	else {?>
		$.ajax(
	  {
		 type:'POST',
		 data:{ddlDivision:'<?php echo $this->ddlDivision;?>',ddlLocation:'<?php echo $this->ddlLocation;?>'},
		 url:'<?php echo base_url().'index.php/stock_entry/filterDivision/'.$this->uri->segment(3);?>',
		 dataType:'html',
		 beforeSend: function() {
				$('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
			},
			success: function(response) {
				$('#loc').html(response);
			}
	  });
	<?php }//end else?>
	
    $('#ddlDivision').change(function()
	{
		var nVal  = $('#ddlDivision').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlDivision:nVal},
			 url:'<?php echo base_url().'index.php/stock_entry/filterDivision/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#loc').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
					$('#loc').html(response);
				}
		  });
	});
	
	//to get quantity from start and end number
	$('.txtEndNo').keyup(function(e) {
		var nId    = this.title;
		calStkQty(nId);
	});
	
	//to get quantity from start and end number
	$('.txtStartNo').keyup(function(e) {
		var nId    = this.title;
		calStkQty(nId);
	});

	function calStkQty(nId)
	{
		var node2  = $('#txtEndNo'+nId).val();
		var node1  = $('#txtStartNo'+nId).val();
		var totVal = (parseInt(node2) - parseInt(node1)) + 1;
		$('#txtStockQty'+nId).val(totVal);
	}//end function

	//to get quantity from start and end number
	$('#txtEnd').keyup(function(e) {
		var node2  = $('#txtEnd').val();
		var node1  = $('#txtStart').val();
		var totVal = (parseInt(node2) - parseInt(node1)) + 1;
		$('#txtStockTraQty').val(totVal);
	});
	
	//to get quantity from start and end number
	$('#txtStart').keyup(function(e) {
		var node2  = $('#txtEnd').val();
		var node1  = $('#txtStart').val();
		var totVal = (parseInt(node2) - parseInt(node1)) + 1;
		$('#txtStockTraQty').val(totVal);
	});

    $('#ddlCodeTra').change(function () {
        var nVal = $('#ddlCodeTra').val();
		$.ajax({
			type:'POST',
			url:'<?php echo base_url().'index.php/stock_entry/getStockValues/'.$this->uri->segment(3);?>',
			data:{ddlCodeTra:nVal},
			dataType:'html',
			beforeSend: function() {
				//$('#ddlTkt'+getId[1]).html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
			},
			success: function(response) {
				var obj = $.parseJSON(response);
               
				$('#txtStart').val(obj.Start);
				$('#txtEnd').val(obj.End);
				$('#txtStockTraQty').val(obj.StockTraQty);
			}
		});
    });

    $('.txtStartNo').keyup(function () {
        var getId = this.id;
		var nVal  = $('#'+getId).val();

        $('.txtStartNo').each(function () {
            var nVal2 = $(this).val();
			var nId2  = this.id;
			
            if(nVal == nVal2 && getId != nId2 && nVal2 != '')
            {
                alert('Already Exists');
				$('#'+getId).val('');
            }
            else
            {
				if(nId2 == getId)
				{
					$.ajax({
						type:'POST',
						data:{txtStartNo:nVal},
						url:'<?php echo base_url().'index.php/stock_entry/getStartNo/'.$this->uri->segment(3);?>',
						dataType:'html',
						success: function(response) {
							if(response != '')
							{
								//alert(response);
								//$('#'+nId2).val('');
							}//end if
						}
					});
				}//end if
            }
        });
    });
	
	$('.txtEndNo').keyup(function () {
        var getId = this.id;
		var nVal  = $('#'+getId).val();

        $('.txtEndNo').each(function () {
            var nVal2 = $(this).val();
			var nId2  = this.id;
			
            if(nVal == nVal2 && getId != nId2 && nVal2 != '')
            {
                alert('Already Exists');
				$('#'+getId).val('');
            }
            else
            {
				if(nId2 == getId)
				{
					$.ajax({
						type:'POST',
						data:{txtEndNo:nVal},
						url:'<?php echo base_url().'index.php/stock_entry/getEndNo/'.$this->uri->segment(3);?>',
						dataType:'html',
						success: function(response) {
							if(response != '')
							{
								//alert(response);
								//$('#'+nId2).val('');
							}//end if
						}
					});
				}//end if
            }
        });
    });

});
</script>
<link type="text/css" rel="stylesheet" href="<?php echo base_url();?>static/plugins/jpagination/styles.css" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/jpagination/jquery.quick.pagination.min.js"></script>
<script type="text/javascript">
    $(document).ready(function(){
        $("ul.pgcontent").quickPagination({pageSize:"5"});
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
<?php echo form_open('stock_entry/editStockentry/'.$this->uri->segment(3).'/'.$this->uri->segment(4),$attributes); ?>
<?php echo form_hidden('nId', $this->catId);?>
<?php echo form_hidden('idArray', $stockdetID);?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <tbody>
        <tr>
            <td><?php echo DIVISIONNAME;?> <span class="warning">*</span></td>
            <td><label>
                <?php echo form_dropdown('ddlDivision', $list_div_value, $this->ddlDivision, 'id="ddlDivision"');?>
            </label></td>
            
            <td><?php echo LOCATIONNAME;?> <span class="warning">*</span></td>
            <td><span id="loc">
             <?php echo form_dropdown('ddlLocation', $list_loc_value, $this->ddlLocation, 'id="ddlLocation"');?>
            </span></td>
        </tr>
        
        <tr>  
            <td align="left"><?php echo CUSTDATE;?> <span class="warning">*</span></td>
            <td align="left"><label>
            <input name="txtDate" id="txtDate" type="text" class="textfield3" value="<?php echo stripslashes($this->txtDate);?>">
            </label></td>
            
            <td align="left"><?php echo DOCNUMBER;?> <span class="warning">*</span></td>
            <td align="left"><label>
            <input name="txtDocNo" id="txtDocNo" type="text" value="<?php echo stripslashes($this->txtDocNo);?>">
            </label></td>
        </tr>
        
        <tr>
            <td align="left"><?php echo YEARREF;?> <span class="warning">*</span></td>
            <td align="left" colspan="3"><label>
            <input name="txtYearRef" id="txtYearRef" class="textfield3" type="text" value="<?php echo stripslashes($this->txtYearRef);?>" style="width:auto;">
            </label></td>
        </tr>
       
        <tr>
        	<td colspan="4">
            <table id="dataTable" title="dataTable">
            <input type="image" src="<?php echo base_url().'static/images/add.jpg';?>" value="Add New Item" id="addRow" class="addNew" />
			<?php
            if(!empty($chkStockType))
            {
                foreach($chkStockType as $key => $stockitem):
				
				switch($key)
				{
					case 0:
						$skey = '';
					break;
					
					default:
						$skey = $key;
					break;
				}//end swithc
            ?>
            <tr>
                <td><?php echo STOCKTYPE;?></td> 
                <td><label>
                 <?php echo form_dropdown('ddlStockType[]', $list_stock_type, $chkStockType[$key],"id='ddlStockType".$skey."' class='ddlStockType'");?>
                </label></td>
                
                <td><?php echo STOCKCODE;?></td> 
                <td><label>
                  <input name="txtStockCode[]" readonly="readonly" id="txtStockCodeddlStockType<?php echo $skey;?>" type="text" class="textfield4" value="<?php echo stripslashes($chkStockCode[$key]);?>" >
                </label></td>
                
                <td><?php echo START;?></td> 
                <td><label>
                  <input name="txtStartNo[]" id="txtStartNo<?php echo $skey;?>" type="text" class="txtStartNo textfield4" value="<?php echo stripslashes($chkStartNo[$key]);?>" title="<?php echo $skey;?>">
                </label></td>
                
                <td><?php echo END;?></td> 
                <td><label>
                  <input name="txtEndNo[]" id="txtEndNo<?php echo $skey;?>" type="text" class="textfield4 txtEndNo" value="<?php echo stripslashes($chkEndNo[$key]);?>" title="<?php echo $skey;?>">
                </label></td>
                
                <td><?php echo STOCKQTY;?></td> 
                <td><label>
                 <input name="txtStockQty[]" id="txtStockQty<?php echo $skey;?>" type="text" class="textfield4 txtStockQty" value="<?php echo stripslashes($chkQuantity[$key]);?>">
                </label></td>
                <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $stockdetID[$key];?>" title="Delete"/></td>
            </tr>
			<?php 
                endforeach;
            }//end if
            else
            {?>
                <tr>
                    <td><?php echo STOCKTYPE;?></td> 
                    <td><label>
                     <?php echo form_dropdown('ddlStockType[]', $list_stock_type, $this->ddlStockType,'id="ddlStockType" class="ddlStockType"');?>
                    </label></td>
                    <td><?php echo STOCKCODE;?></td> 
                    <td><label>
                      <input name="txtStockCode[]" readonly="readonly" id="txtStockCodeddlStockType" class="textfield4" type="text" value="" >
                    </label></td>
                    <td><?php echo START;?></td> 
                    <td><label>
                      <input name="txtStartNo[]" id="txtStartNo" type="text" class="txtStartNo textfield4" value="" >
                    </label></td>
                    <td><?php echo END;?></td> 
                    <td><label>
                      <input name="txtEndNo[]" id="txtEndNo" type="text" class="textfield4 txtEndNo" value="" >
                    </label></td>
                    <td><?php echo STOCKQTY;?></td> 
                    <td><label>
                     <input name="txtStockQty[]" id="txtStockQty" type="text" class="textfield4 txtStockQty" value="" >
                    </label></td>
                
                    <td align="center"><img src="<?php echo base_url();?>static/images/cross.png" alt="delete" class="del" id="del_<?php echo $item->nSEId; ?>" title="Delete"/></td>
                </tr>
            <?php
                }//end else
            ?>
         </table>
       </td>
       </tr>
       
       <tr>
            <td><?php echo ACTIVE;?> <span class="warning">*</span></td>
            <td colspan="3"><label>
            <input type="radio" name="chkActive" value="1" <?php echo $this->chkValYes;?> />&nbsp;<?php echo YES;?>&nbsp;&nbsp;<input type="radio" name="chkActive" value="0" <?php echo $this->chkValNo;?>/>&nbsp;<?php echo NO;?></label></td>
        </tr>

		<?php if($this->uri->segment(4) == 'a')
			  {?>
        <tr>
            <td colspan="4"><strong><label><?php echo TRANSFERDETAILS;?></label></strong></td>
        </tr>
              
        <tr>
        	<td colspan="4">
        		<table>
			        <tr>
			            <td><?php echo TOLOCATION;?></td> 
			            <td><label>
			              <?php echo form_dropdown('ddlLocationTra', $list_loctra_value, $this->ddlLocationTra, 'id="ddlLocationTra"');?>
			            </label></td>
			            
			            <td><?php echo CODE;?></td> 
			            <td><label>
			              <?php echo form_dropdown('ddlCodeTra', $list_code_value, $this->ddlCodeTra, 'id="ddlCodeTra" class="singleDrop"');?>
			            </label></td>

			            <td><?php echo START;?></td> 
			            <td><label>
			             <input name="txtStart" id="txtStart" type="text" class="textfield4" value="<?php echo stripslashes($this->txtStart);?>" >
			            </label></td>
			            
			            <td><?php echo END;?></td> 
			            <td><label>
			             <input name="txtEnd" id="txtEnd" type="text" class="textfield4" value="<?php echo stripslashes($this->txtEnd);?>" >
			            </label></td>
			            
			            <td><?php echo STOCKQTY;?></td> 
			            <td><label>
			             <input name="txtStockTraQty" id="txtStockTraQty" type="text" class="textfield4" value="<?php echo stripslashes($this->txtStockTraQty);?>" >
			            </label></td>
					</tr>
        		</table>
        	</td>
        </tr>
        <?php }//end if?>     
         
        <tr class="formButs">
            <td>&nbsp;</td>
            <td colspan="3"><label>
              <input type="submit" name="btnGo" id="btnGo" value="<?php echo $this->btnVal;?>" />
			  <?php if($this->uri->segment(4) == 'a'){?>
              <input type="submit" name="btnSaveExit" id="btnSaveExit" value="<?php echo $this->btnVal2;?>" />
              <?php }//end if?>
              <input type="reset" value="<?php echo RESET;?>" />
              <input type="button" name="btnCancel" value="<?php echo BACKTOSTOCKENTRY;?>" id="btnCancel">
            </label>
                <input type="checkbox" name="chkPrint" id="chkPrint" value="1" checked="checked"><?php echo PRINTPAGE;?></td>
        </tr>
    </tbody>
  </table>
  </form>
  </div>
