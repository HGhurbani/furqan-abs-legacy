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

	$('#btnSearch').click(function()
	{
		var pass   = $('#txtPassName').val();
		var doc  = $('#txtdoc').val();
		var file  = $('#txtfile').val();
		var stk = $('#txtstk').val();
		
		
		$.ajax({
            type:'POST',
            data:{txtPassName:pass,txtdoc:doc,txtfile:file,txtstk:stk,btnSearch:'Go'},
            url:'<?php echo base_url().'index.php/group_entry/filterList/'.$this->uri->segment(3);?>',
            dataType:'html',
            beforeSend: function() {
                $('#result').html('<img src="<?php echo base_url();?>static/images/indicator.gif" alt="loading..." />');
            },
            success: function(response) {
                $('#result').html(response);
            }
        });
	});

    var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset();
    $('#maincontent').scroll(function() {
        console.log(contentBoxExportPos);
        if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){
            contentBoxExport.addClass('fixed')
        }
        else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){
            contentBoxExport.removeClass('fixed')
        }
    });

    <?php
    if(intval($this->uri->segment(5)) != 0 ):
        if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>

        window.open('<?php echo base_url().'index.php/group_entry/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=1100, scrollbars=yes,toolbar=no,menubar=yes");

       
    <?php endif;endif;?>

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
    <?php if($action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
    <h3 class="content-box-header"><a href="<?php echo base_url().'index.php/group_entry/editEntry/'.$this->uri->segment(3);?>/a"><?php echo "Add New Entry";?></a></h3>
    <?php endif;?>


<!--<p align="right"><?php echo DIVISIONNAMEORDOCNUMBER;?>&nbsp;<input type="text" name="txtSearch" id="txtSearch" value="<?php echo $this->txtSearch;?>" class="txtSearch" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>
-->

<table>
<tr>
<th>PASSANGER</th> 
<th>DOCUMENT</th>
<th>FILE NUMBER</th>
<th>STOCK</th>
<th></th>
</tr>
<tr>
<td><input type="text" name="txtPassName" id="txtPassName" value="" class="txtSearch2" />&nbsp;&nbsp;</td>
<td><input type="text" name="txtdoc" id="txtdoc" value="" class="txtSearch2" />&nbsp;&nbsp;</td> 
<td><input type="text" name="txtfile" id="txtfile" value="" class="txtSearch2" />&nbsp;&nbsp;</td>
<td>
 <?php echo form_dropdown('txtstk', $this->group_entry_model->getActive_stocks(), $this->ddlReport, ' id="txtstk" class="txtSearch2"');?>
&nbsp;&nbsp;</td>
<td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
</tr>
<tr>

</table><br>
<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">

<?php echo form_open(base_url().'index.php/group_entry/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">




    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
		   <th align="left"></th>
           <th align="left">#</th>
		  <th align="left">Print</th>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo DOCNUMBER;?></th>
		    <th align="left"><?php echo "File No";?></th>
			<th align="left"><?php echo "Travel Date";?></th>
		   <th align="left"><?php echo REFNAME;?></th>
            <th align="left"><?php echo "Tickets";?></th>
           <th align="left"><?php echo "Hotels";?></th>
           <th align="left"><?php echo "SALES";?></th>
           <th align="left"><?php echo "RECEIVED";?></th>
           
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
   <!-- <tfoot>
        <tr class="formButs">
            <td colspan="12">
                <?php
			if(!empty($list_value))
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
    </tfoot>-->
 
    <tbody>
    <?php $i= 1;?>
    <?php foreach($list_value as $key=>$item):?>
        <tr>
        
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->id;?>" class="chcktbl"/></td>
			
			<!-- adding print button in this screen for printing each cash receipt  the same as update_sales_closing button-->
			<td align="left">
			    <form target = '_blank' action="<?php echo base_url();?>index.php/group_entry/printPreview/<?php echo $item->id;?>" title="<?php echo EDIT;?>">			 
                </form>
			</td>

            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
			<td align="left">
			    <form target = '_blank' action="<?php echo base_url();?>index.php/group_entry/printPreview/<?php echo $item->id;?>" title="<?php echo EDIT;?>">
                <input type="submit" value="Print">
		        </form>
			</td>
			
            <td align="left"><?php echo  date('d-m-Y',strtotime($item->dDate)); ?></td>
			 <td align="left"><?php echo  $item->adoc; ?></td>
            <td align="left">
              <?php //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                <a href="<?php echo base_url();?>index.php/group_entry/editEntry/<?php echo $this->uri->segment(3).'/e/'.$item->id;?>" title="<?php echo EDIT;?>"> <?php echo stripslashes($item->groupno);?></a>
            <?php //endif;?>
            </td>
			<td align="left"><?php echo  date('d-m-Y',strtotime($item->dot)); ?></td>
            <td align="left"><?php echo stripslashes($item->paxname);?></td>
			<td align="left"><?php echo stripslashes($item->tot_t);?></td>
			<td align="left"><?php echo stripslashes($item->h_tot);?></td>
			<td align="left"><?php echo stripslashes($item->totalamt);?></td>
			<td align="left"><?php
			echo $this->common_model->get_field_name('GroupEntryDetails','sum(received)'," WHERE nGroupId = '".$item->id."'");
			 ?></td>
            
            
                <td align="left">
            
                <?php //user access 
				$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
			if($aid!=45){
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                <a href="<?php echo base_url();?>index.php/group_entry/delgroupentry/<?php echo $this->uri->segment(3).'/'.$item->id;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
			<?php endif; }?>
		    

            </td>
           

            </td>
            
            
            
            
        </tr>
        

<?php $i= $i+1;?>
        <?php endforeach;?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="12" align="center"><?php echo SORRYNORECORD;?></td>
</tr>
<?php }//end if
?>
</tbody>
</table>
<div class="pagination">
<?php //echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>
</div>
</form>
</div>
</div>
<div id="blackout"></div>
