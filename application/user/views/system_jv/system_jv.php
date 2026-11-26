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
<script src="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/jcalendar/jquery.datepick.css" type="text/css" rel="stylesheet">
<script src="<?php echo base_url();?>static/plugins/popup/popup.js" language="javascript1.1" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/popup/popup.css" type="text/css" rel="stylesheet">
<script language="javascript1.1" type="text/javascript">
$(document).ready(function()
{
    $("#txtDate").datepick({ dateFormat: 'yyyy-mm-dd'});

	$('#btnSearch').click(function()
	{
        var nVal  = $('#txtDivision').val();
        var nVal2 = $('#txtLocation').val();
        var nVal3 = $('#txtDate').val();
        var nVal4 = $('#txtDoc').val();
        var nVal5 = $('#txtInvo').val();
        
        $.ajax({
            type:'POST',
			data:{txtDivision:nVal,txtLocation:nVal2,txtDate:nVal3,txtDoc:nVal4,txtInvo:nVal5,btnSearch:'Go'},
			url:'<?php echo base_url().'index.php/system_jv/filterList/'.$this->uri->segment(3);?>',
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
<div id="boxes">
<?php 
$attributes = array('name' => 'frmGroup', 'id' => 'frmGroup');
$this->load->model('common_model');
echo $this->common_model->successMsg($this->uri->segment(4));
?>
<?php $this->load->view('general/admin_menu');?>
<div class="content-box-export">
    <div class="inner">
<a href="<?php echo base_url().'index.php/export_xl/system_jv/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_word/system_jv/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>
<a href="<?php echo base_url().'index.php/export_pdf/system_jv/'.$this->uri->segment(3);?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>
</div>
</div> <!--.content-box-export-->
    <table>
    	<tr>
            <th><?php echo DIVISIONNAME;?></th>
            <th><?php echo LOCATIONNAME;?></th>
            <th><?php echo DATE;?></th>
            <th><?php echo DOCUMENTNUMBER;?></th>
            <th><?php echo REFNUMBER;?></th>
        </tr>
        <tr>
            <td><input type="text" name="txtDivision" id="txtDivision" value="<?php echo $this->txtDivision;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtLocation" id="txtLocation" value="<?php echo $this->txtLocation;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtDate" id="txtDate" value="<?php echo $this->txtDate;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtDoc" id="txtDoc" value="<?php echo $this->txtDoc;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="text" name="txtInvo" id="txtInvo" value="<?php echo $this->txtInvo;?>" class="txtSearch2" />&nbsp;&nbsp;</td>
            <td><input type="button" name="btnSearch" value="<?php echo GO;?>" id="btnSearch" class="buttonGo" /></td>
        </tr>
    </table><br />
<div class="formHead">
    <h4><?php echo $this->showTitle1;?></h4>
</div>
<div class="formBorder">
<?php echo form_open(base_url().'index.php/system_jv/delRecords',$attributes); ?>
<?php echo form_hidden('nCId', $this->uri->segment(3));?>
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
            <th align="left">#</th>
            <th align="left"><?php echo DIVISIONNAME;?></th>
            <th align="left"><?php echo LOCATIONNAME;?></th>
            <th align="left"><?php echo JVNUMBER;?></th>
			<th align="left"><?php echo INVOICE;?></th>
            <th align="left"><?php echo CUSTDATE;?></th>
            <th align="left"><?php echo VIEW;?></th>
            <th align="left"><?php echo DEBIT;?></th>
            <th align="left"><?php echo CREDIT;?></th>			<th align="left"></th>
        </tr>
    </thead>
 
    <tfoot>
    	<tr>
        	<td colspan="18"><hr /></td>
        </tr>
        <tr>
            <td colspan="18">
                <?php if(!empty($list_value))
                      {
                ?>
                <div class="bulk-actions align-left">
                <strong><?php echo ACCCODENAME;?></strong>&nbsp;&nbsp;
                    <input type="textbox" value="" class="txtSearch2" name="txtACName" id="txtACName"/>&nbsp;&nbsp;
                    <strong><?php echo SUBNAME;?></strong>&nbsp;&nbsp;
                     <input type="textbox" value="" class="txtSearch2" name="txtSBName" id="txtSBName"/>
                </div>
                <?php
                      }//end if
                ?>						
            </td>
        </tr>
    </tfoot>
 
    <tbody>
    <?php $i = 1;?>
    <?php foreach($list_value as $key=>$item):
		  	$getDet = $this->system_jv_model->getSystemJvDrCr($item->nId);
	?>
        <tr>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo stripslashes($item->dName);?></td>
            <td align="left"><?php echo stripslashes($item->lName);?></td>
            <td align="left"><?php echo stripslashes($item->vJVNo);?></td>
            <td align="left"><?php echo stripslashes($item->nInvoice);?></td>
            <td align="left"><?php echo stripslashes($item->dDate);?></td>
            <td align="left"><a class="popup-link-<?php echo $item->nId;?>">View</a></td>
            <td align="left"><?php echo $getDet['debit'];?></td>
            <td align="left"><?php echo $getDet['credit'];?></td>			<td align="left"><?php echo $getDet['credit'];?></td>		<?php if($getDet['debit']==$getDet['credit']){?>			<td align="left"><?php echo "Equal";?></td>			<?php }else{?>			<td style="color:red;"align="left"><?php echo "Not Equal";?></td>			<?php }?>
        </tr>
        <tr>
        	<td colspan="9">
                <div class="popup-box" id="popup-box-<?php echo $item->nId;?>">
                    <div class="close">X</div>
                    <div class="top">
                        <h2>System JV Detail - <?php echo stripslashes($item->vJVNo);?></h2>
                    </div>
                    <div class="bottom">
                    	<div class="wa">
                        	<?php echo $this->system_jv_model->getSystemJvDetails($item->nId);?>
                        </div>
                    </div>
                </div>
             </td>
        </tr>
		<?php $i= $i+1;?>
        <?php endforeach;?>
<?php if(empty($list_value))
{
?>
<tr>
    <td colspan="18" align="center"><?php echo SORRYNORECORD;?></td>
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
