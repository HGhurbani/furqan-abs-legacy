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
			if (e.name == "del[]") 
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
		if (e.name == "del[]") 
		{
			e.checked = false;
		}//end if
	}//end for loop
}//end function	

$(document).ready(function()
{
	$('#btnGo').click(function()
	{
		if($('#ddlAction').val() == 0)
		{
			alert("<?php echo PLEASESELECTACTION;?>");
			return false;
		}//end if
		
		else
		{
			var length = $('.chcktbl:checked').length; 
			if (length > 0) 
			{
				if($('#ddlAction').val()=='yes')
				{
					var msg="<?php echo AREYOUSUREWANTTOACTIVE;?> "+$('#ddlAction').val()+"?";
				}
				if($('#ddlAction').val()=='no')
				{
					var msg="<?php echo AREYOUSUREWANTTOACTIVE;?> "+$('#ddlAction').val()+"?";
				}
				if($('#ddlAction').val()=='delete')
				{
					var msg="<?php echo AREYOUSURE;?> "+$('#ddlAction').val()+"?";
				}
				if (confirm(msg)) 
				{
					return true;
				}
				else
				{
					return false;
				}
			}
			else
			{
				alert("<?php echo PLEASECHECKITEM;?>");
				return false;
			}
		}//end else

	});

	$('.chkSubGroup').change(function()
	{
		var selID = this.id;
		var nVal = $('#'+selID).val();
		$('#txtSubId').val(nVal);
	});
	
	$('.editMain').click(function()
	{
		var chkVal = $('#txtSubId').val();
		if(chkVal == 0)
		{
			alert("Should select subgroup");
			return false;
		}//end if
		var nVal  = $(this).attr('href');
		var dllId = $('#txtSubId').val();
		
		//split or remove #
		nVal = nVal.split('#');
		
		$(location).attr('href','<?php echo base_url();?>index.php/main_group/editMaingroups/<?php echo $this->uri->segment(3).'/e/';?>'+nVal[1]+'/'+dllId);
		
	});


	$('#btnSearch').click(function()
	{
		var nVal = $('#ddlMainGroup').val();
		var txt  = $('#txtSubGroup').val();
		$.ajax(
		  {
			 type:'POST',
			 data:{ddlMainGroup:nVal,txtSubGroup:txt,btnSearch:'Go'},
			 url:'<?php echo base_url().'index.php/main_group/filterByMaingroup/'.$this->uri->segment(3);?>',
			 dataType:'html',
			 beforeSend: function() {
					$('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
				},
				success: function(response) {
				
					
					$('#result').html(response);
				}
		  });
	});

    var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()
    $('#maincontent').scroll(function() {
        console.log(contentBoxExportPos)
        if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
            contentBoxExport.addClass('fixed')
        }
        else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
            contentBoxExport.removeClass('fixed')
        }
    })

});
</script>
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">


<div id="boxes">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<?php $this->load->view('general/admin_menu');?>
<h3 class="content-box-header"><a href="<?php echo base_url().'index.php/main_group/editMaingroups/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWMAINGROUP;?></a></h3>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/main_group/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/main_group/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/main_group/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
    </div>
</div> <!--.content-box-export-->

<p align="right"><?php echo MAINGROUPNAME;?>&nbsp;
    <?php echo form_dropdown('ddlMainGroup', $list_main_group, $this->ddlMainGroup, 'id="ddlMainGroup" class="ddlSearch"');?>&nbsp;
    <?php /*echo SUBGROUP;*/?><!--&nbsp;
    <input type="text" name="txtSubGroup" id="txtSubGroup" class="txtSearch"/>&nbsp;-->
    <input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open(base_url().'index.php/main_group/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<input type="hidden" name="txtSubId" id="txtSubId" value="<?php $this->txtSubId;?>" />
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
           <th align="left">#</th>
           <th align="left"><?php echo MAINGROUPNAME;?></th>
           <th align="left"><?php echo SUBGROUP;?></th>
           <th align="left"><?php echo SUBGROUPDETAILS;?></th>
           <th align="left"><?php echo ACTIVE;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="7">
                <?php if(!empty($list_value))
                      {
                ?>
                <div class="bulk-actions align-left">
                    <select name="ddlAction" id="ddlAction">
                        <option value=""><?php echo BULKACTIONS;?></option>
                        <option value="yes"><?php echo ACTIVEYES;?></option>
                        <option value="no"><?php echo ACTIVENO;?></option>
                        <?php if($this->session->userdata('AccPeriod')==1 && $this->session->userdata('AbsAdmin')==1): ?>
                        <option value="delete"><?php echo DELETE;?></option>
                        <?php endif;?>
                    </select>
                    <input type="submit" value="<?php echo APPLYACTION;?>" class="button" name="btnGo" id="btnGo"/>
                </div>
                <?php
                        }//end if
                ?>						
            </td>
        </tr>
    </tfoot>
 
    <tbody>
    <?php $i= 1;?>
    <?php foreach($list_value as $key=>$item):?>
    <?php $ddlID = 'ddlSubGroup'.($this->common_model->_offset+$i);?>
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nId;?>" class="chcktbl"/></td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <?php switch($item->vTitle)
				  {
					  case "1" :
					  	$mainGroup = '1 - Assets';
					  break;

					  case "2" :
					  	$mainGroup = '2 - Liability';
					  break;

					  case "3" :
					  	$mainGroup = '3 - Income';
					  break;

					  case "4" :
					  	$mainGroup = '4 - Expense';
					  break;
				  }//end switch
		    ?>
            <td align="left"><?php echo stripslashes($mainGroup);?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('FinanceModule','vTitle'," WHERE nParent='".$item->nId."'");?></td>
            <td align="left"><a class="popup-link-<?php echo $item->nId;?>"><?php echo VIEW;?></a></td>
            <td align="left"><?php echo ($item->vActive=='1')?'Yes':'No';?></td>
            <td align="left">
            <!-- Icons -->
                <?php // User Access aa
                if($action_access->vAccess == 'F' || $action_access->vAccess == 'U'): ?>
                <a href="<?php echo base_url();?>index.php/main_group/editMaingroups/<?php echo $this->uri->segment(3).'/e/'.$item->nId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php endif;?>
                <?php //user access 
                if($action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/main_group/delmaingroup/<?php echo $this->uri->segment(3).'/'.$item->nId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
                <?php endif;?>
                <!-- Start of Sticky Note -->
               <div class="popup-box" id="popup-box-<?php echo $item->nId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->finance_model->showSubDetails($item->nId,5);?>
                        </div>
                    </div>
                </div>
                <!-- End of Sticky Note -->
            </td>
        </tr>
<?php $i= $i+1;?>
<?php endforeach;?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="7" align="center"><?php echo SORRYNORECORD;?></td>
</tr>
<?php }//end if
?>
</tbody>
</table>
<div class="pagination">
<?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>

</div><!-- Mask to cover the whole screen -->
</form>
</div>
</div>
  <div id="blackout"></div>
