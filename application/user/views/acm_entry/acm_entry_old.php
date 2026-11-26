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
<script type="text/javascript">
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
		var nVal = $('#txtSearch').val();
		$.ajax({
            type:'POST',
            data:{txtSearch:nVal,btnSearch:'Go'},
            url:'<?php echo base_url().'index.php/acm_entry/filterList/'.$this->uri->segment(3);?>',
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

    <?php
    if(intval($this->uri->segment(5)) != 0 ):
    if($this->uri->segment(4) == 'edit_suc' || $this->uri->segment(4) == 'add_suc'):?>

        window.open('<?php echo base_url().'index.php/acm_entry/printPreview/'.$this->uri->segment(5);?>','PrintWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");

        /*window.open('<?php //echo base_url().'index.php/cashbook_receipt/printAgentPreview/'.$this->uri->segment(5);?>','PrintAgentWindow',"width=1000,height=700, scrollbars=yes,toolbar=no,menubar=yes");*/

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
<?php if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'E' || $action_access->vAccess == 'F'):?>
<h3 class="content-box-header"><a href="<?php echo base_url().'index.php/acm_entry/editAcmEntry/'.$this->uri->segment(3);?>/a"><?php echo ADDNEWACMENTRY;?></a></h3>
<?php endif;?>


<p align="right"><?php echo DIVISIONNAMEORDOCNUMBER;?>&nbsp;<input type="text" name="txtSearch" id="txtSearch" value="<?php echo $this->txtSearch;?>" class="txtSearch" />&nbsp;<input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></p>

<div class="formHead">
    <h4><?php echo $this->showTitle;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/acm_entry/delrecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
           <th align="left"><input type="checkbox" name="chkAll"  id="chkAll" onClick="checkAll();" class="check-all"/></th>
		   <th align="left"></th>
           <th align="left">#</th>
		   <th align="left">Print Acm Entry</th>
           <th align="left"><?php echo CUSTDATE;?></th>
           <th align="left"><?php echo DOCNUMBER;?></th>
		   <th align="left"><?php echo REFNAME;?></th>
           <th align="left"><?php echo ACCOUNT;?></th>
           <th align="left"><?php echo SUBACCOUNT;?></th>
           <th align="left"><?php echo DEBIT;?></th>
           <th align="left"><?php echo CREDIT;?></th>
           <th align="left"><?php echo CREDITVOUCHERDETAILS;?></th>
           <th align="left"><?php echo ACTIVE;?></th>
           <th align="left"><?php echo ACTION;?></th>
        </tr>
    </thead>
 
    <tfoot>
        <tr class="formButs">
            <td colspan="12">
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
        <tr>
            <td align="left"><input name="del[]" type="checkbox" id="del[]" value="<?php echo $item->nACId;?>" class="chcktbl"/></td>
			<!-- adding print button in this screen for printing each acm entry  the same as update_acm_entry button-->
		    <td align="left">
			    <form target = '_blank' action="<?php echo base_url();?>index.php/acm_entry/printPreview/<?php echo $item->nACId;?>" title="<?php echo EDIT;?>">			 
            </form>
		    </td>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
			<!-- adding print button in this screen for printing each acm entry  the same as update_acm_entry button-->
		    <td align="left">
			   <form target = '_blank' action="<?php echo base_url();?>index.php/acm_entry/printPreview/<?php echo $item->nACId;?>" title="<?php echo EDIT;?>">
               <input type="submit" value="Print">
		       </form>
	        </td>
			
            <td align="left"><?php echo  date('d-m-Y',strtotime($item->dDate)); ?></td>
            <td align="left">

            <?php //user access aa // if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
            <a href="<?php echo base_url();?>index.php/acm_entry/editAcmEntry/<?php echo $this->uri->segment(3).'/e/'.$item->nACId;?>" title="<?php echo EDIT;?>">         <?php echo stripslashes($item->vDocNumber);?></a>
            <?php // endif;?>
            </td>
            <td align="left"><?php echo stripslashes($item->vRefName);?></td>
            <td> &nbsp;</td> <td> &nbsp;</td> <td> &nbsp;</td> <td> &nbsp;</td>
			<td align="left"><a class="popup-link-<?php echo $item->nACId;?>"><?php echo VIEW;?></a></td>
            <td align="left"><?php echo ($item->vActive == '1')?'Yes':'No';?></td>
            <td align="left">
            <!-- Icons -->
            <!--<?php //User access //if($this->session->userdata('AccPeriod')==1 && $action_access->vAccess == 'U' || $action_access->vAccess == 'F'):?>
                <a href="<?php echo base_url();?>index.php/acm_entry/editAcmEntry/<?php echo $this->uri->segment(3).'/e/'.$item->nACId;?>" title="<?php echo EDIT;?>"><img src="<?php echo base_url();?>static/images/pencil.png" alt="<?php echo EDIT;?>" border="0" /></a>
                <?php// endif;?> -->
                <?php //user access 
					$aid=$this->common_model->get_field_name('Admin','nGroupId'," WHERE nId='".$this->session->userdata('AbsAdmin')."'");
			if($aid!=45){
                if($this->common_model->get_account_period_date($item->dDate) ==1 && $action_access->vAccess == 'F'): ?>
                    <a href="<?php echo base_url();?>index.php/acm_entry/delAcmEntry/<?php echo $this->uri->segment(3).'/'.$item->nACId;?>" onClick="return confirm('<?php echo AREYOUSUREDELETE;?>');" title="<?php echo DELETE;?>"><img src="<?php echo base_url();?>static/images/cross.png" alt="Delete" border="0" /></a>
			<?php endif; }?>

		    <!-- Start of Sticky Note -->
            <div class="popup-box" id="popup-box-<?php echo $item->nACId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>Details</h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->acm_entry_model->showDetails($item->nACId);?>
                        </div>
                    </div>
                </div>
		<!-- End of Sticky Note -->

            </td>
        </tr>
<!-- Main Account -->
        <?php
        $Debit_main =0;
        $Credit_main =0;
        if($item->vTransMode == 'Dr'){
            $Debit_main = $item->vAmountDC;
        }else{
            $Credit_main =  $item->vAmountDC;
        }
        $accountName_main= $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->nAcCode . "'");
        $subAccountName_main= $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->vSubCode. "'");
        ?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
			<td></td>
			<td></td>
            <td align="left"> <?php echo $accountName_main ?> </td>
            <td align="left"> <?php echo $subAccountName_main; ?> </td>
            <td align="left"> <?php echo $Debit_main; ?> </td>
            <td align="left"> <?php echo $Credit_main; ?> </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>
<!-- Other Accounts -->
<?php

$account_list_value =  $this->acm_entry_model->getAccounts($item->nACId);
        foreach($account_list_value as $account_key=>$account_item):
?>
        <tr>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
            <td></td>
			<td></td>
			<td></td>
            <td align="left"><?php echo $account_item->subAccName ; ?></td>
            <td align="left"><?php echo $account_item->drAccountName; ?></td>
            <td align="left"><?php echo  $account_item->Debit; ?>  </td>
            <td align="left"> <?php echo  $account_item->Credit; ?> </td>
            <td></td>
            <td></td>
            <td></td>
        </tr>

        <?php
endforeach;?>



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
<?php echo $this->common_model->p->show();?>
</div> <!-- End .pagination -->
<div class="clear"></div>

</div>
</form>
</div>
</div>
  <div id="blackout"></div>
