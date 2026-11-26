<?php



?>





<?php

//echo $a = $this->common_model->get_field_name('CashAllocation','vDebitAmt'," WHERE vAllType ='RC' AND nDrAccount = '" . $item->vACode . "'");



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

<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function(){

        $('#btnCancel').click(function()

        {

            window.location.replace('<?php echo base_url().'index.php/ageing_report/showlist/'.$this->uri->segment(3);?>');

            return false;

        });



        $('.print_logo').hide();

        $('.print_desc').hide();



        $('#btnPrint').click(function(e) {

            $('.print_logo').show();

            $('.print_desc').show();

            $('#reportForm').printElement();

            $('.print_logo').hide();

            $('.print_desc').hide();

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



<div class="content-box-export">

    <div class="inner">

    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/receivable_ageing_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/receivable_ageing_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/receivable_ageing_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

    </div>

</div> <!--.content-box-export-->

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm">

    <thead>

    <?php

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

    <tr>

        <th colspan="15"><h3><?php echo AGEINGSUMMARYREPORT;?></h3></th>

    </tr>



    <tr>

                <div class="print_desc">

                <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>

                <?php echo $addr;?><br>

                <?php echo PHONE;?>: <?php echo $phone;?>

                <?php echo MOBILE;?>: <?php echo $mobile;?><br>

                <?php echo EMAIL;?>: <?php echo $email;?>,

                <?php echo WEB;?>: <?php echo $web;?>

            </div>

        </th>

    </tr>

	<style type="text/css">

body{

  -webkit-print-color-adjust:exact;

}

  table { font-size: 9px; }

</style>

<style media="print">

  #bg{ background-color:#CCC !important; }

 

</style>

<style>

td{

    border: 1px solid black;

border-collapse: collapse;

}

</style>

    <tr><td style="border:0"></td></tr>

    <tr>

        <th style="color:#3BB3C2;" align="left"><?php echo FROMDATE;?></th>

        <th align="left"><?php echo $fromDate;?></th>

        <th style="color:#3BB3C2;" align="center"><?php echo TODATE;?></th>

        <th align="left"><?php echo $toDate;?></th>

        <th align="left" colspan="12">&nbsp;</th>

    </tr>

    <tr><td style="border:0"></td></tr>



    <tr id="bg">

        <td height="25px" align="center"><?php echo SRNO;?></td>

        <td align="center"><?php echo ACCODE;?></td>

        <td align="center"><?php echo ACCCODENAME;?></td>

        <td align="center"><?php echo SUBCODE;?></td>

        <td align="center"><?php echo SUBNAME;?></td>

        <td align="center"><?php echo OPENINGBALANCE;?></td>

        <td align="center"><?php echo $slab1 . " " .DAYS;?></td>

        <td align="center"><?php echo ($slab1+1) . "-" . $slab2 ." " . DAYS;?></td>

        <td align="center"><?php echo ($slab2+1) . "-" . $slab3 . " " . DAYS;?></td>

        <td align="center"><?php echo ($slab3+1) . "-" . $slab4 . " " . DAYS;?></td>

        <td align="center"><?php echo ABOVE . " " . $slab4 . " " .DAYS;?></td>

        <td align="center"><?php echo UNALLOCATED;?></td>

        <td align="center"><?php echo BALANCE;?></td>

    </tr>

    </thead>



    <tbody>



    <?php

    if(!empty($list_value_new)){

        $recoed_present = 1;

        $i = 1;

		$balance = 0;

		$chkAccount = array();

        foreach($list_value_new as $key=>$item):

				switch($item->vTable){

					case "C": $AccountName = $this->common_model->get_field_name('CustomerMaster','vName'," WHERE nCId = '". $item->vACode  ."'"); 

		  						$AccountCode = $this->common_model->get_field_name('CustomerMaster','vCode'," WHERE nCId = '". $item->vACode  ."'"); 

								break;

					case "V": $AccountName = $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '". $item->vACode  ."'");

								$AccountCode = $this->common_model->get_field_name('VendorMaster','vCode'," WHERE nVId = '". $item->vACode  ."'"); 

								break;

                    case "AG": $AccountName = $this->common_model->get_field_name('Agent','vName'," WHERE nAId = '". $item->vACode  ."'");

                                $AccountCode = $this->common_model->get_field_name('Agent','vCode'," WHERE nAId = '". $item->vACode  ."'");

                                break;

					default: $item->vAcSubCode = $item->vACode ; $AccountName = ""; break;

				}//end switch

				$subAccName	 = $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," WHERE nId='".$item->vAcSubCode."'");

				$subAccCode	 = $this->common_model->get_field_name('AccountHeadAccounts','vACode'," WHERE nId='".$item->vAcSubCode."'");

$this->load->database();





$servername = $this->db->hostname;

$username = $this->db->username;

$password = $this->db->password;

$dbname = $this->db->database;

// Create connection

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection

if ($conn->connect_error) {

    die("Connection failed: " . $conn->connect_error);

} 


 $a='';
$sql = "SELECT 'O' as OutReport, rcd.vPaidAmt - IFNULL( (Select SUM(vAllAmt) FROM tbl_CashAllocation WHERE vAllType='RC' AND nReceiptDId = rc.nRCId GROUP BY rc.nRCId),0) as debit, rc.dDate as date,rc.vLocation as location,rcd.nTicketNumber as tic,rc.vPassenger as pass,rc.vDocNumber as document,rc.vRemarks as description,rcd.nDrAcc as account,'' as subacc,'' as balance, '' as credit,'' as issued,rc.nRCId as tableId,'RC' as tblName, '' as vRefNumber,'' as vRefName, rcd.nId as detId,'' as vType,'' as vNumber, '' as mainMode,rcd.vPaidAmt as mainAmount,'' as subCode, rc.nCurrency, rcd.vPaidAmt as vFCAmount, '' as vExchange, '' as mode, rcd.nDrAcc as vACode, '' as vAcSubCode, rcd.vTable as vTable FROM tbl_RefundClosing rc LEFT JOIN tbl_RefundClosingDetails rcd ON rcd.nRefundCloseId = rc.nRCId LEFT JOIN tbl_Location loc ON loc.nId = rc.vLocation WHERE rc.vActive='1' AND rc.nDivision='".$this->ddlDivision."' AND loc.vCode between (select vCode from tbl_Location where nId=".$this->ddlLocation.") and (select vCode from tbl_Location where nId=".$this->ddlLocation.") AND rc.dDate >= '".date('Y-m-d',strtotime($this->txtFromDate))."' AND rc.dDate <= '".date('Y-m-d',strtotime($this->txtToDate))."'  AND rcd.nDrAcc='".$item->vACode."' AND rcd.vTable IN ( 'V' , 'C' )";

$result = $conn->query($sql);



if ($result->num_rows > 0) {

    // output data of each row

    while($row = $result->fetch_assoc()) {

        $a+= $row["debit"];
		$b+= $row['mainAmount'];

    }

} 
//print $a ;// $x=$this->common_model->get_field_name('CashAllocation','vAllAmt'," WHERE vAllType ='RC' AND nDrAccount =".$item->vACode);
$sql1 = "SELECT * FROM tbl_CashAllocation WHERE vAllType ='CR' AND nDrAccount = ".$item->vACode;

$result1 = $conn->query($sql1);



if ($result1->num_rows > 0) {

    // output data of each row

    while($row1 = $result1->fetch_assoc()) {

        $a1+= $row1["vAllAmt"];

    }

} 



//print $a;

$conn->close();



            $open_list_value =  $this->ageing_report_model->getOpeningBalForAcc($item->vAcSubCode,$item->vACode,$item->vTable);

            $openbal = $open_list_value['alloc_debit'] - $open_list_value['alloc_credit'];

//echo $open_list_value['alloc_debit'] . " -------   " . $open_list_value['alloc_credit'];



                    if( $open_list_value['alloc_debit'] ==0 && $open_list_value['alloc_credit'] ==0 && $item->alloc_debit ==0 && $item->alloc_credit == 0) continue;

                    $recoed_present +=1;

		?>

			

        <tr>

            <td height='25px' align="center"><?php echo $key+1;?></td>

			<td align="center"><?php echo $subAccCode ; ?> </td>

			<td><strong><?php echo $subAccName ; ?> </strong></td>

			<td align="center"><?php echo $AccountCode ; ?> </td>			

			<td><strong><?php echo $AccountName ; ?> </strong></td>

            <td align="center"><?php echo number_format((float)$openbal, 3, ".", "");?></td>

            <td align="center"><?php echo $item->s1;?></td>

            <td align="center"><?php echo $item->s2;?></td>

            <td align="center"><?php echo $item->s3;?></td>

            <td align="center"><?php echo $item->s4;?></td>

            <td align="center"><?php echo $item->s5; ?></td>

            <?php $abc=0; 
			$abc=$item->alloc_credit-$b+$a;?>

            <td align="center"><?php echo number_format((float)$abc, 3, ".", "");?></td>

			

            <td align="center"><?php $balance =0;
//print $abc;
						$balance =  $item->s1 + $item->s2 + $item->s3 + $item->s4 + $item->s5 - $abc +$openbal  ;			

						echo number_format((float)$balance, 3, ".", "");?></td>

        </tr>

            <?php $i = $i + 1;

            $totalopenbal  += $openbal;

            $totalCredit  = $totalCredit+$abc;

		    $totalS1   += $item->s1;

		    $totalS2   += $item->s2;

		    $totalS3   += $item->s3;

		    $totalS4   += $item->s4;

		    $totalS5   += $item->s5;

            $totalBalance += $balance;
        endforeach;

        if($recoed_present !=1): ?>

    <tr id="bg">

        <td colspan="5" height='25px' align="right"><strong><?php echo GRANDTOTAL;?></strong></td>

        <td align="center"><?php echo number_format((float)$totalopenbal, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalS1, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalS2, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalS3, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalS4, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalS5, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalCredit, 3, ".", "");?></td>

        <td align="center"><?php echo number_format((float)$totalBalance, 3, ".", "");?></td>

    </tr>



    <?php else:?>

    <tr>

        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

    <?php endif;

    } 

    else{ ?>

    <tr>

        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>

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

        <td style="border:0">&nbsp;</td>

        <td style="border:0" colspan="13">

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>

