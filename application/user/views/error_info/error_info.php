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
<div class="formHead">
    <h4><?php echo "Error Reports"?></h4>
</div>
<div class="formBorder">
<div id="result">
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="entryForm">
    <thead>
        <tr>
            <th align="left">#</th>
            <th align="left"><?php echo DIVISIONNAME;?></th>
            <th align="left"><?php echo LOCATIONNAME;?></th>
            <th align="left"><?php echo "Document #";?></th>
			<th align="left"><?php echo "Account";?></th>
            <th align="left"><?php echo "SubAccount";?></th>
            <th align="left"><?php echo "Problem";?></th>
        </tr>
    </thead>
    <tfoot>
    	<tr>
        	<td colspan="18">
			
			<hr />
			<?php 
    // integer starts at 0 before counting
    $i = 0; 
    $dir = '/home/wwwalfur/public_html/setup/static/amadeous/tickets/';
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false){
            if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
                $i++;
        }
    }
	$i=$i-1;
    // prints out how many were in the directory
    echo "Amadeous $i files";
	
	$j = 0; 
    $dir = '/home/wwwalfur/public_html/setup/static/gds/tickets/';
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false){
            if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
                $j++;
        }
    }
	$j=$j-1;
    // prints out how many were in the directory
    echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Galileo $j files";
	
	$k = 0; 
    $dir = '/home/wwwalfur/public_html/setup/static/sabre/Tickets/';
    if ($handle = opendir($dir)) {
        while (($file = readdir($handle)) !== false){
            if (!in_array($file, array('.', '..')) && !is_dir($dir.$file)) 
                $k++;
        }
    }
	//$k=$k-1;
    // prints out how many were in the directory
    echo "        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Sabre $k files";
?></td>
        </tr>
    </tfoot>
    <tbody>
    <?php $i = 1;?>
	<?php foreach($sjv_value as $key=>$item):
		  	$getDet = $this->system_jv_model->getSystemJvDrCr($item->nId);
			if($getDet['debit']!=$getDet['credit']){
	?>
        <tr>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo stripslashes($item->dName);?></td>
            <td align="left"><?php echo stripslashes($item->lName);?></td>
            <td align="left"><?php echo stripslashes($item->nInvoice);?></td>
            <td align="left"><?php echo $getDet['debit']." -- ".$getDet['credit']; ?></td>
            <td align="left"><a class="popup-link-<?php echo $item->nId;?>">View</a></td>
			<?php if($getDet['debit']==$getDet['credit']){?>			
			<td align="left"><?php echo "Equal";?></td>			
			<?php }else{?>			
			<td style="color:red;"align="left"><?php echo "System JV Not Equal";?></td>			
			<?php }?>
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
			<?php } endforeach;?>
			<?php foreach($sjv_value as $key=>$item):
			$sqlRefundclosingQuery  = "SELECT sj.nInvoice FROM tbl_SystemJv sj LEFT JOIN tbl_SystemJvDetails sjd ON sjd.nSjId=sj.nId WHERE sj.nId = '".$item->nId."'";
		$adminGroupList 		= $this->db->query($sqlRefundclosingQuery);
		$no=$adminGroupList->num_rows();
			if($no==1){
				$no=4;
			}
			if($no==2){
				$no=4;
			}
			if($no<4 && $item->nInvoice!='CRT-01-16-00001' && $item->nInvoice!='CRT-02-16-00029' && $item->nInvoice!='CRT-02-16-00006'){
		  	$getDet = $this->system_jv_model->getSystemJvDrCr($item->nId);
	?>
        <tr>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
            <td align="left"><?php echo stripslashes($item->dName);?></td>
            <td align="left"><?php echo stripslashes($item->lName);?></td>
            <td align="left"><?php echo stripslashes($item->nInvoice);?></td>
            <td align="left"><?php echo $getDet['debit']." -- ".$getDet['credit']; ?></td>
            <td align="left"><a class="popup-link-<?php echo $item->nId;?>">View</a></td>
			<td align="left"><?php echo "SJ ".$no." Lines Only ";?></td>			
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
			<?php } endforeach;?>
    <?php
//var_dump($list_value);
if(!empty($list_value))	{
	foreach($list_value as $key=>$item):
			$tableId_1=$item->tableId_1;
			switch($item->tblName)
            {
                case "CR":
                    $path = base_url().'index.php/cashbook_receipt/editCashbookreceipt/5/e/'.$tableId_1;
                    break;
                case "CP":
                    $path = base_url().'index.php/cashbook_payment/editCashbookpayment/5/e/'.$tableId_1;
                    break;
                case "BR":
                    $path = base_url().'index.php/bankbook_entry/editBankbookentry/5/e/'.$tableId_1;
                    break;
                case "BP":
                    $path = base_url().'index.php/bank_payment/editBankbookpayment/5/e/'.$tableId_1;
                    break;
                case "JV":
                    $path = base_url().'index.php/jv/editJv/5/e/'.$tableId_1;
                    break;
                case "ACM":
                    $path = base_url().'index.php/acm_entry/editAcmEntry/5/e/'.$tableId_1;
                    break;
                case "ADM":
                    $path = base_url().'index.php/adm_entry/editAdmEntry/5/e/'.$tableId_1;
                    break;
					case "Htl":
                    $path = base_url().'index.php/hotel_voucher/editHotelvoucher/4/e/'.$tableId_1;
                    break;
					case "SC":
                   $path ='';// base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$salesDetId.'/'.$tableId_1.'/cl';
                    break;
			}
			switch($item->vTable){
					case "C": $drAccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); break;
					case "V": $drAccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'"); break;
					case "AG": $drAccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode  ."'"); break;
					case "O": $drAccountName = $this->common_model->get_field_name('Other','vName'," WHERE nOId = '". $item->vACode  ."'"); break;
					default: $item->vAcSubCode = $item->vACode ; $drAccountName = ""; break;
				}//end switch
				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");
	?>
        <tr>
            <td align="left"><?php echo $this->common_model->_offset+$i;?></td>
			<td align="left"><?php echo $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".stripslashes($item->nDivision)."'");?></td>
            <td align="left"><?php echo $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".stripslashes($item->vLocation)."'");?></td>
			 <td align="left"><a href="<?php echo $path?>" target="_blank"><?php echo stripslashes($item->vDocNumber);?></a></td>
            <td align="left"><?php echo $subAccName;?></td>
            <td align="left"><?php echo $drAccountName;?></td>
            <td align="left"><?php echo $item->des;?></td>
        </tr>
		<?php $i= $i+1;?>
<?php endforeach; }?>



<?php if(empty($list_value) && empty($sjv_value))
{
?>
<tr>
    <td colspan="18" align="center"><?php echo SORRYNORECORD;?></td>
</tr>
<?php }//end if
?>
</tbody>
</table>
<div class="clear"></div>
</div>
</form>
</div>
</div>
<div id="blackout"></div>