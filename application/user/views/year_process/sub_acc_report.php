<?php
 error_reporting(0);
ini_set('max_execution_time', 15000);
		ini_set('memory_limit', '-1');
		$kl=1;
$ky=0;
?>

<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function(){

        $('#btnCancel').click(function()

        {

            window.location.replace('<?php echo base_url().'index.php/trial_report/showlist/'.$this->uri->segment(3);?>');

            return false;

        });



        $('.print_logo').hide();

        $('.print_txtDesc').hide();



        $('#btnPrint').click(function(e) {

            $('.print_logo').show();

            $('.print_txtDesc').show();

            $('#reportForm').printElement();

            $('.print_logo').hide();

            $('.print_txtDesc').hide();

        });



        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset()

        $('#maincontent').scroll(function() {

            console.log(contentBoxExportPos);

            if($(this).scrollTop() > (contentBoxExportPos.top - 80) && contentBoxExport.css('position') == 'static'){

                contentBoxExport.addClass('fixed')

            }

            else if($(this).scrollTop() <= contentBoxExportPos.top && contentBoxExport.hasClass('fixed')){

                contentBoxExport.removeClass('fixed')

            }

        })



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


<div class="content-box-export">

    <div class="inner">

        <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/sub_account_trial";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

        <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/sub_account_trial";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

        <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/sub_account_trial";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

    </div>

</div> <!--.content-box-export-->
<?php echo form_open('year_closing/editYearClose_new/1',$attributes); ?>


<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">



<?php
$this->load->database();

$servername = $this->db->hostname;

$username = $this->db->username;

$password = $this->db->password;

$dbname = $this->db->database;

$conn = new mysqli($servername, $username, $password, $dbname);
$conn_new=new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} 
if ($conn_new->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

 $main_id=$this->common_model->get_field_name('YearClosing','nYCId'," WHERE loc='".$this->ddlLocation."' AND vFYear='2017'");
//var_dump($main_id);
 if($main_id ==0){
$dt=date("Y-m-d");
$yr=substr($fromDate,0,4);
$ur=$this->session->userdata('AbsAdmin');
$sql_main = "INSERT INTO tbl_YearClosing(dDate,vFYear,vTYear,dFDate,dTDate,nDivision,vActive,nUserId,loc) 
VALUES ('$dt','$yr','$yr','$fromDate','$toDate','1','1','$ur','$this->ddlLocation');";

if ($conn_new->query($sql_main) === TRUE) {
    $main_id = $conn_new->insert_id;
   // echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
	$main_id =100;
   // echo "Error: " . $sql . "<br>" . $conn->error;
}
   
}else{
	  $sql_d = "DELETE FROM tbl_YearClosingDetails WHERE nYearCId ='$main_id'";

if ($conn_new->query($sql_d) === TRUE) {
   // echo "Record deleted successfully";
} else {
   // echo "Error deleting record: " . $conn->error;
}


}
   $op_id=$this->common_model->get_field_name('OpeningBalance','nOBId'," WHERE vLocation='".$this->ddlLocation."' AND vSubLedger='X'");
if($op_id ==0){
  $dt='2017-01-01';//date("Y-m-d");
$yr="2017-2017";//date("Y",$fromDate);
$ur=$this->session->userdata('AbsAdmin');
$totalBalanceCredit=0;
$totalBalanceDebit=0;
$tot=$totalBalanceCredit+$totalBalanceDebit;
$curnd=date('Y-m-d H:i:s');
 $sql_ope = "INSERT INTO tbl_OpeningBalance(vLedger,vSubLedger,nDivision,vLocation,vYear,dDate,vActive,nUserId,eid,edate) 
VALUES ('GL','X','$this->ddlDivision','$this->ddlLocation','$yr','$dt','1','$ur','$ur','$curnd');";


if ($conn->query($sql_ope) === TRUE) {
    $op_id = $conn->insert_id;
   // echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
	$op_id =100;
   // echo "Error: " . $sql . "<br>" . $conn->error;
} 
}else{
	$sql = "DELETE FROM tbl_OpeningBalanceDetails WHERE nBalId='$op_id'";

if ($conn->query($sql) === TRUE) {
   // echo "Record deleted successfully";
} else {
   // echo "Error deleting record: " . $conn->error;
}
}

//$list_value =  $this->year_process_model->getAllSubAccountDetails($search_accGrp,$search_Acc,$list_value);



    $j = 0;

    $flag = false;

    $chkGroupId = $chkHeadId = $chkAccountId = $chkSubAccountId = array();

    if(!empty($this->ddlLocation)):

        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");

        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");

        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");

        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");

        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");

        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");

        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");

        $link   = base_url().'upload/location/'.$logo;

    elseif(!empty($this->ddlLocation2)):

        $logo   = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation2."'");

        $name   = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation2."'");

        $addr   = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation2."'");

        $phone  = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation2."'");

        $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation2."'");

        $email  = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation2."'");

        $web    = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation2."'");

        $link   = base_url().'upload/location/'.$logo;

    else:

        $logo   = $this->common_model->get_field_name('Division','vImg'," WHERE nId='".$this->ddlDivision."'");

        $name   = $this->common_model->get_field_name('Division','vTitle'," WHERE nId='".$this->ddlDivision."'");

        $addr   = $this->common_model->get_field_name('Division','vAddress'," WHERE nId='".$this->ddlDivision."'");

        $phone  = $this->common_model->get_field_name('Division','vPhone'," WHERE nId='".$this->ddlDivision."'");

        $mobile = $this->common_model->get_field_name('Division','vMobile'," WHERE nId='".$this->ddlDivision."'");

        $email  = $this->common_model->get_field_name('Division','vEmail'," WHERE nId='".$this->ddlDivision."'");

        $web    = $this->common_model->get_field_name('Division','vWeb'," WHERE nId='".$this->ddlDivision."'");

        $link   = base_url().'upload/division/'.$logo;

    endif;

    ?>

<thead>

    

<tr>

    <th align="center" colspan="8">

        



        <div class="print_txtDesc">

            <span style="font-size: 11px;font-weight: bold;"><?php echo $name;?></span><br>

            <?php echo $addr;?><br>

            <?php echo PHONE;?>: <?php echo $phone;?>

            <?php echo MOBILE;?>: <?php echo $mobile;?><br>

            <?php echo EMAIL;?>: <?php echo $email;?>,

            <?php echo WEB;?>: <?php echo $web;?>

        </div>

    </th>

</tr>

<tr>

            <td colspan="6" style="border:0" height="25px" align="left"><strong><?php echo TBWITHSUBACCREPORT;?></strong></td>
<td colspan="6" style="border:0" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>
        </tr>

<tr>

    <th colspan="2" style="color:#3BB3C2;" align="right"><strong><?php echo FROMDATE;?></strong></th>

    <th colspan="2" align="left"><?php echo $fromDate;?></th>

    <th style="color:#3BB3C2;" align="right"><strong><?php echo TODATE;?></strong></th>

    <th align="left"><?php echo $toDate;?></th>

    <th align="left" colspan="7">&nbsp;</th>

</tr>

<style media="print">



  #bg{   -webkit-print-color-adjust:exact;

 background-color:#CCC !important; }

 

</style>

<style>

table { font-size: 9px; }

td{font-size: 12px;

    border: 1px solid black;

border-collapse: collapse;

}

</style>

</thead>

<thead>



<tr>

    <th>&nbsp;</th>

    <th>&nbsp;</th>

    <td colspan="2" align="center"><strong><?php echo OPENING;?></strong></td>

    <td colspan="2" align="center"><strong><?php echo TRANSACTION;?></strong></td>

    <td colspan="2" align="center"><strong><?php echo BALANCE;?></strong></td>

</tr>

<tr id="bg">

    <td align="center"><strong><?php echo ACCODE;?></strong></td>

    <td align="center"><strong><?php echo ACCCODENAME;?></strong></td>

    <td align="center"><strong><?php echo DEBIT;?></strong></td>

    <td align="center"><strong><?php echo CREDIT;?></strong></td>

    <td align="center"><strong><?php echo DEBIT;?></strong></td>

    <td align="center"><strong><?php echo CREDIT;?></strong></td>

    <td align="center"><strong><?php echo DEBIT;?></strong></td>

    <td align="center"><strong><?php echo CREDIT;?></strong></td>

</tr>

</thead>

    <?php

	

    foreach($list_value as $key => $item):



        if(!in_array($item->HeadId,$chkHeadId)):

            $chkHeadId[]             = $item->HeadId; ?>

            <?php if($j != 0 && $item->HeadId!=1):
			
			?>
           <tr id="bg">

            <td colspan="2" align="right"><strong><?php  echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo number_format($totalOpenDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalOpenCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceCreditLoop,3);?></strong></td>

        </tr>

			<?php ?>

            <tr>

                <td colspan="8" align="right">&nbsp;</td>

            </tr>

                <?php $flag = true; endif;?>



        <tr id="bg">

            <td align="left"><strong><?php echo $this->common_model->get_field_name('AccountHead','vHCode'," WHERE nAId = '" . $item->HeadId . "' ");?></strong></td>

            <td align="left"><strong><?php echo $this->common_model->get_field_name('AccountHead','vAcname'," WHERE nAId = '" . $item->HeadId . "' ");?></strong></td>

            <td colspan="6" align="center">&nbsp;</td>

        </tr>

            <?php

            $j = 1;

        endif;

        ?>



    <tbody>

        <?php

//        if(($item->subacc == 0 && !in_array($item->AccountId,$chkAccountId))|| ($item->subacc != 0 && !in_array($item->subacc,$chkSubAccountId))):



?>

        <tr>

            <?php



           switch($item->vTable)

            {

                case "C":

                    $accSubName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId='".$item->subacc."'");

                    $accSubCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId='".$item->subacc."'");

                    break;



                case "V":

                    $accSubName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId='".$item->subacc."'");

                    $accSubCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId='".$item->subacc."'");

                    break;



                case "AG":

                    $accSubName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId='".$item->subacc."'");

                    $accSubCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId='".$item->subacc."'");

                    break;



                case "O":

                    $accSubName = $this->common_model->get_field_name('Other','vName'," WHERE nOId='".$item->subacc."'");

                    $accSubCode = $this->common_model->get_field_name('Other','vCode'," WHERE nOId='".$item->subacc."'");

                    break;



                default: $accSubName = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='". $item->AccountId . "'");

                         $accSubCode =  $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='". $item->AccountId . "'");

            }



        $getOpenValues=  $this->year_process_model->getOpeningBalForAcc($item->AccountId, $item->subacc,$item->vTable);



        $getBalance     = ($getOpenValues['debit']-$getOpenValues['credit'])+($item->debit-$item->credit);



            if($getBalance > 0):

                $balanceDebit = $getBalance;
				$am=$getBalance;
                $balanceCredit = '';
				$ddlMode='Dr';

            else:

                $balanceDebit = '';
				$ddlMode='Cr';
				$am=substr($getBalance,1,strlen($getBalance));
                $balanceCredit = substr($getBalance,1,strlen($getBalance));

            endif;


if($accSubCode!=''){
            ?>



            <td> 
			<input type="hidden" name="sub[]" id="sub<?php echo $key;?>" value="<?php echo $item->subacc;?>">
			<input type="hidden" name="acc[]" id="acc<?php echo $key;?>" value="<?php echo $item->AccountId;?>">
			<input type="hidden" name="tbl[]" id="tbl<?php echo $key;?>" value="<?php echo $item->vTable;?>">
			<input type="hidden" name="txtAcccode[]" id="txtAcccode<?php echo $key;?>" value="<?php echo $accSubCode;?>"><?php echo $accSubCode;?></td>

            <td>
			<input type="hidden" name="txtDes[]" id="txtDes<?php echo $key;?>" value="<?php echo $accSubName?>">
			<?php echo $accSubName; ?></td>

            <td><?php echo number_format((float)$getOpenValues['debit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$getOpenValues['credit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$item->debit, 3, ".", "");?></td>

            <td><?php echo number_format((float)$item->credit, 3, ".", "");?></td>
<input type="hidden" name="ddlMode[]" id="txtAmount<?php echo $key;?>" value="<?php echo $ddlMode;?>">

            <td>
			<input type="hidden" name="txtAmountFC[]" id="txtAmountFC<?php echo $key;?>" value="<?php echo number_format((float)$balanceDebit, 3, ".", "");?>"><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td>

            <td><input type="hidden" name="txtAmount[]" id="txtAmount<?php echo $key;?>" value="<?php echo number_format((float)$balanceCredit, 3, ".", "");?>"><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td>

        </tr>

<?php 
$am1=$am;
if($kl==1){
	if($item->vTable=='V'){
		$cur=$this->common_model->get_field_name('VendorMaster','nCurrency'," WHERE nVId = '" . $item->subacc . "' ");
		$rate=$this->common_model->get_field_name('Currency','vRate'," WHERE nId = '" . $cur . "' ");
		$am1=$am/$rate;
	}else{
		$cur=2;
		$am1=$am;
	}
	
	$sql_op = "INSERT INTO tbl_OpeningBalanceDetails (nBalId,nLoc,vACode,vAcSubCode,vMode,nCurrency,vRate,vBalDC,vBalFC,vBalCredit,vBalDebit) 
	VALUES ('$op_id','$this->ddlLocation','$item->AccountId','$item->subacc','$ddlMode','$cur','1','$am','$am1','$balanceCredit','$balanceDebit');";
   
	$sql = "INSERT INTO tbl_YearClosingDetails (nYearCId,nAHAId,vDes,vMode,vDCBal,vFCBal,sub,vTable) VALUES ('$main_id','$item->AccountId','$accSubName','$ddlMode','$balanceDebit','$balanceCredit','$item->subacc','$item->vTable');";
   	$kl=10;
	}else{
		if($item->vTable=='V'){
		$cur=$this->common_model->get_field_name('VendorMaster','nCurrency'," WHERE nVId = '" . $item->subacc . "' ");
		$rate=$this->common_model->get_field_name('Currency','vRate'," WHERE nId = '" . $cur . "' ");
		$am1=$am/$rate;
	}else{
		$cur=2;
		$am1=$am;
	}
	
		$sql_op .= "INSERT INTO tbl_OpeningBalanceDetails (nBalId,nLoc,vACode,vAcSubCode,vMode,nCurrency,vRate,vBalDC,vBalFC,vBalCredit,vBalDebit) 
	VALUES ('$op_id','$this->ddlLocation','$item->AccountId','$item->subacc','$ddlMode','$cur','1','$am','$am1','$balanceCredit','$balanceDebit');";
   
		$sql .= "INSERT INTO tbl_YearClosingDetails (nYearCId,nAHAId,vDes,vMode,vDCBal,vFCBal,sub,vTable) VALUES ('$main_id','$item->AccountId','$accSubName','$ddlMode','$balanceDebit','$balanceCredit','$item->subacc','$item->vTable');";
   
	}

            $totalOpenDebit     += $getOpenValues['debit'];

            $totalOpenCredit    += $getOpenValues['credit'];

            $totalDebit         += $item->debit;

            $totalCredit        += $item->credit;

            $totalBalanceDebit  += $balanceDebit;

            $totalBalanceCredit += $balanceCredit;



            if($flag == true):

                $totalOpenDebitLoop  = '';

                $totalOpenCreditLoop = '';

                $totalDebitLoop      = '';

                $totalCreditLoop     = '';

                $totalBalanceDebitLoop = '';

                $totalBalanceCreditLoop = '';

                $flag                = false;

            endif;



            $chkSubAccountId[]       = $item->subacc . "-" . $item->vTable;

            $chkAccountId[] = $item->AccountId;



            $totalOpenDebitLoop     += $getOpenValues['debit'];

            $totalOpenCreditLoop    += $getOpenValues['credit'];

            $totalDebitLoop         += $item->debit;

            $totalCreditLoop        += $item->credit;

            $totalBalanceDebitLoop  += $balanceDebit;

            $totalBalanceCreditLoop += $balanceCredit;

//        endif;

}

        if(($key+1) == count($list_value)): ?>

       <tr id="bg">

            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo number_format($totalOpenDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalOpenCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalCreditLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceDebitLoop,3);?></strong></td>

            <td><strong><?php echo number_format($totalBalanceCreditLoop,3);?></strong></td>

        </tr>



            <?php endif;

    endforeach; $key1=$key+1;
	
	
	
	//$this->$db->query($sql);

	
	?>



<!--<tr>

    <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

    <td><strong><?php echo $totalOpenDebit;?></strong></td>

    <td><strong><?php echo $totalOpenCredit;?></strong></td>

    <td><strong><?php echo $totalDebit;?></strong></td>

    <td><strong><?php echo $totalCredit;?></strong></td>

    <td><strong><?php echo $totalBalanceDebit;?></strong></td>

    <td><strong><?php echo $totalBalanceCredit;?></strong></td>

</tr>-->


<tr> <td>&nbsp;  </td> </tr>

    <?php

            $account_list = $this->year_process_model->subAccountList($search_acc,$search_subAcc,$chkAccountId,$chkSubAccountId);

$j = 0;
//$account_list = array_merge($list_value, $account_list);


    $chkHeadId = array();

    $chkAccountId = array();

            foreach($account_list as $key => $item): 



        if(!in_array($item['HeadId'],$chkHeadId)):

            $chkHeadId[]             = $item['HeadId']; ?>

            <?php if($j != 0):?>

<!--            <tr>

                <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

                <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>

                <td><strong><?php echo $totalOpenCreditLoop;?></strong></td>

                <td><strong><?php echo $totalDebitLoop;?></strong></td>

                <td><strong><?php echo $totalCreditLoop;?></strong></td>

                <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>

                <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>

            </tr>

-->

            <tr>

                <td colspan="8" align="right">&nbsp;</td>

            </tr>

                <?php $flag = true; endif;?>



        <tr>

            <td align="left"><h6><?php echo $item['HeadCode'];?></h6></td> 

            <td align="left"><h6><?php echo $item['HeadName'];?></h6></td>

            <td colspan="6" align="center">&nbsp;</td>

        </tr>

            <?php

            $j = 1;

        endif;

        ?>



    <tbody>

        <?php

//        if(!in_array($item['AccountId'],$chkAccountId)):



?>

        <tr>

<?php

        $getBalance     = ($item['debit']-$item['credit']);



            if($getBalance > 0):

                $balanceDebit = $getBalance;
				$ddlMode='Dr';
				$am=$getBalance;
                $balanceCredit = '';

            else:

                $balanceDebit = '';
				$ddlMode='Cr';
				$am=substr($getBalance,1,strlen($getBalance));
                $balanceCredit = substr($getBalance,1,strlen($getBalance));

            endif;



            if($item['subAccountId'] == 0){

                $item['subAccountCode'] =  $item['accountCode'];

                $item['subAccountName'] =  $item['accountName'];

            }

?>

          
			 <td>
			 <input type="hidden" name="sub[]" id="sub<?php echo $k=$key+$key1;?>" value="<?php echo $item['subAccountId']?>">
			<input type="hidden" name="acc[]" id="acc<?php echo $k=$key+$key1;?>" value="<?php echo $item['AccountId'];?>">
		
			 <input type="hidden" name="tbl[]" id="tbl<?php echo $k=$key+$key1;?>" value="<?php echo $item['vTable'];?>">
			 <input type="hidden" name="txtAcccode[]" id="txtAcccode<?php echo $k=$key+$key1;?>" value="<?php echo $item['subAccountCode'];?>"><?php echo $item['subAccountCode'];?></td>


            <td>
			<input type="hidden" name="txtDes[]" id="txtDes<?php echo $k=$key+$key1;?>" value="<?php echo $item['subAccountName'];?>"><?php echo $item['subAccountName'];?></td>

            <td><?php echo number_format((float)$item['debit'], 3, ".", "");?></td>

            <td><?php echo number_format((float)$item['credit'], 3, ".", "");?></td>

            <td>&nbsp;</td>

            <td>&nbsp;<input type="hidden" name="ddlMode[]" id="ddlMode<?php echo $k=$key+$key1;?>" value="<?php echo $ddlMode;?>"></td>

           <td><input type="hidden" name="txtAmountFC[]" id="txtAmountFC<?php echo $k=$key+$key1;?>" value="<?php echo number_format((float)$balanceDebit, 3, ".", "");?>"><?php echo number_format((float)$balanceDebit, 3, ".", "");?></td>

            <td><input type="hidden" name="txtAmount[]" id="txtAmount<?php echo $k=$key+$key1;?>" value="<?php echo number_format((float)$balanceCredit, 3, ".", "");?>"><?php echo number_format((float)$balanceCredit, 3, ".", "");?></td>
			

        </tr>

            <?php
			$iAccountId=$item['AccountId'];
			$isubAccountId=$item['subAccountId'];
			$subAccountName=$item['subAccountName'];
			$vTable=$item['vTable'];
			
		if($kl==1){
			if($vTable=='V'){
		$cur=$this->common_model->get_field_name('VendorMaster','nCurrency'," WHERE nVId = '" . $isubAccountId . "' ");
		$rate=$this->common_model->get_field_name('Currency','vRate'," WHERE nId = '" . $cur . "' ");
		$am1=$am/$rate;
	}else{
		$cur=2;
		$am1=$am;
	}
	
			$sql_op = "INSERT INTO tbl_OpeningBalanceDetails (nBalId,nLoc,vACode,vAcSubCode,vMode,nCurrency,vRate,vBalDC,vBalFC,vBalCredit,vBalDebit) 
	VALUES ('$op_id','$this->ddlLocation','$iAccountId','$isubAccountId','$ddlMode','$cur','1','$am','$am','$balanceCredit','$balanceDebit');";
   
	$sql = "INSERT INTO tbl_YearClosingDetails (nYearCId,nAHAId,vDes,vMode,vDCBal,vFCBal,sub,vTable) VALUES ('$main_id','$iAccountId','$subAccountName','$ddlMode','$balanceDebit','$balanceCredit','$isubAccountId','$vTable');";
   	$kl=10;
	}else{
			if($vTable=='V'){
		$cur=$this->common_model->get_field_name('VendorMaster','nCurrency'," WHERE nVId = '" . $isubAccountId . "' ");
		$rate=$this->common_model->get_field_name('Currency','vRate'," WHERE nId = '" . $cur . "' ");
		$am1=$am/$rate;
	}else{
		$cur=2;
		$am1=$am;
	}
	
		$sql_op .= "INSERT INTO tbl_OpeningBalanceDetails (nBalId,nLoc,vACode,vAcSubCode,vMode,nCurrency,vRate,vBalDC,vBalFC,vBalCredit,vBalDebit) 
	VALUES ('$op_id','$this->ddlLocation','$iAccountId','$isubAccountId','$ddlMode','$cur','1','$am','$am','$balanceCredit','$balanceDebit');";
   
		$sql .= "INSERT INTO tbl_YearClosingDetails (nYearCId,nAHAId,vDes,vMode,vDCBal,vFCBal,sub,vTable) VALUES ('$main_id','$iAccountId','$subAccountName','$ddlMode','$balanceDebit','$balanceCredit','$isubAccountId','$vTable');";
   
	}
			
            $totalOpenDebit     += $item['debit'];

            $totalOpenCredit    += $item['credit'];

//            $totalDebit         += $item['debit'];

//            $totalCredit        += $item['credit'];

            $totalBalanceDebit  += $balanceDebit;

            $totalBalanceCredit += $balanceCredit;



            if($flag == true):

                $totalOpenDebitLoop  = '';

                $totalOpenCreditLoop = '';

                $totalDebitLoop      = '';

                $totalCreditLoop     = '';

                $totalBalanceDebitLoop = '';

                $totalBalanceCreditLoop = '';

                $flag                = false;

            endif;



            $chkSubAccountId[]       = $item['subacc'];

            $chkAccountId[] = $item['accountId'];



            $totalOpenDebitLoop     += $item['debit'];

            $totalOpenCreditLoop    += $item['credit'];

//            $totalDebitLoop         += $item['debit'];

//            $totalCreditLoop        += $item['credit'];

            $totalBalanceDebitLoop  += $balanceDebit;

            $totalBalanceCreditLoop += $balanceCredit;

//        endif;



        if(($key+1) == count($list_value)): ?>

<!--        <tr>

            <td colspan="2" align="right"><strong><?php echo GROUPTOTAL;?></strong></td>

            <td><strong><?php echo $totalOpenDebitLoop;?></strong></td>

            <td><strong><?php echo $totalOpenCreditLoop;?></strong></td>

            <td><strong><?php echo $totalDebitLoop;?></strong></td>

            <td><strong><?php echo $totalCreditLoop;?></strong></td>

            <td><strong><?php echo $totalBalanceDebitLoop;?></strong></td>

            <td><strong><?php echo $totalBalanceCreditLoop;?></strong></td>

        </tr>

-->

            <?php endif;

    endforeach;?>

 <input type="hidden" id="count" name="coun" value="<?php echo $k=$key+$key1;?>">

<tr id="bg">

    <td colspan="2" align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

    <td><strong><?php echo number_format((float)$totalOpenDebit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalOpenCredit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalDebit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalCredit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalBalanceDebit, 3, ".", "");?></strong></td>

    <td><strong><?php echo number_format((float)$totalBalanceCredit, 3, ".", "");?></strong></td>

</tr>





<?php



    if(empty($list_value) && empty($account_list)){



    ?>

<tr>

    <td style="border:0" colspan="8" align="center"><?php echo SORRYNORECORD;?></td>

</tr>

    <?php

}//end if

?>

<style type="text/css">

@media print {

input#btnPrint,

input#btnCancel {

display: none;

}

}

</style>

<tr class="formButs">

    

    <td style="border:0" colspan="7">

        <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

        <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
		
                <input type="submit" name="btnSaveExit" id="btnSaveExit" value="PROCESS" />
                
    </td>

</tr>
<?php 
$dt='2017-01-01';//date("Y-m-d");
$yr="2017-2017";//date("Y",$fromDate);
$ur=$this->session->userdata('AbsAdmin');
$tot=$totalBalanceCredit+$totalBalanceDebit;
$curnd=date('Y-m-d H:i:s');
$sql_opu = "UPDATE  tbl_OpeningBalance SET vTotalDC='$tot',vTotalFC='$tot',nTotalC='$totalBalanceCredit',nTotalD='$totalBalanceDebit' WHERE nOBId='$op_id'";



if ($conn->query($sql_opu) === TRUE) {
   // $op_id = $conn->insert_id;
   // echo "New record created successfully. Last inserted ID is: " . $last_id;
} else {
	//$op_id =100;
   // echo "Error: " . $sql . "<br>" . $conn->error;
}


if ($conn->multi_query($sql_op) ===TRUE) {
	
	
	//$conn->multi_query($sql_aop) === 
   // echo "New records created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}


if ($conn_new->multi_query($sql) ===TRUE) {
	
	
	//$conn->multi_query($sql_aop) === 
   // echo "New records created successfully";
} else {
    //echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
$conn_new->close();


?>
</tbody>

</table>
</form>

