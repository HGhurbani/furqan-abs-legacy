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

<script  src="<?php echo base_url();?>static/js/jquery.printElement.js" type="text/javascript"></script>

<script type="text/javascript">

    $(document).ready(function(){

        $('#btnCancel').click(function()

        {

            window.location.replace('<?php echo base_url().'index.php/sales_receipt_report/showlist/'.$this->uri->segment(3);?>');

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



        var contentBoxExport = $('.content-box-export'), contentBoxExportPos = contentBoxExport.offset();

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



<div class="content-box-export">

    <div class="inner">

    <a href="<?php echo base_url().'index.php/export_xl/generate_xl/'.$this->uri->segment(3)."/detail_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-excel.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_word/generate_word/'.$this->uri->segment(3)."/detail_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-word.png" alt=" " /></a>

    <a href="<?php echo base_url().'index.php/export_pdf/generate_pdf/'.$this->uri->segment(3)."/detail_report";?>"><img src="<?php echo base_url(); ?>static/images/ic-export-pdf.png" alt=" " /></a>

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

            <th align="center" colspan="18">

            <div class="print_desc">

                <span style="font-size: 11px;"><?php echo $name;?></span><br>

                <?php echo $addr;?><br>

                <?php echo PHONE;?>: <?php echo $phone;?>

                <?php echo MOBILE;?>: <?php echo $mobile;?><br>

                <?php echo EMAIL;?>: <?php echo $email;?>,

                <?php echo WEB;?>: <?php echo $web;?>

            </th>

        </tr>


<style media="print">

  @media print {
    @page {
		margin-left: 0.5cm;
		
		margin-right: 0.5cm;
     
    }
	@page:left{
  @bottom-left {
    content: "Page " counter(page) " of " counter(pages);
  }
}
   
}

@media print
{
  table { page-break-inside:auto !important; }
    tr    { page-break-inside:avoid !important; page-break-after:auto !important; }
    thead { display:table-header-group }
    tfoot { display:table-footer-group }
}

  #bg{ background-color:#CCC !important; }


</style>

<style>
@media all {
	.page-break	{ display: none; }
}

@media print {
	.page-break	{ display: block; page-break-before: always; }
}
td{font-size: 12px;

    border: 1px solid black;

border-collapse: collapse;

}

</style>     <tr> 

        <td colspan="6" style="border:0" height="25px" align="left">&nbsp;<strong><?php echo "Sales Receipt Report";?></strong></td>

    

        <td colspan="6" style="border:0" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>

    </tr>

            <tr>

                <th align="left"><?php echo FROMDATE;?></th>

                <th align="left"><?php echo $fromDate;?></th>

                <th align="left"><?php echo TODATE;?></th>

              <th align="left"><?php echo $toDate;?></th>

                 <!-- <th align="left"><?php echo ACCOUNTCODE;?></th>

                <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vACode'," where nId='".$search_acc."'");?></th>

                <th align="left"><?php echo ACCOUNT;?></th>

                <th align="left"><?php echo $this->common_model->get_field_name('AccountHeadAccounts','vTitle'," where nId='".$search_acc."'");?></th>
-->
                <th colspan='9'>&nbsp;</th>

            </tr>

            <tr><td></td></tr>

        <tr id="bg">

            <th align="left"><?php echo SRNO;?></th>

            <th align="left"><?php echo CUSTDATE;?></th>

            
            <th align="left"><?php echo ISSUEDBY;?></th>
			
            <th align="left"><?php echo DOCUMENTNUMBER;?></th>

             <th align="left"><?php echo TICKETNO;?></th>

           
            
           

            <th align="left"><?php echo PASSNAME;?></th>

            <th align="left"><?php echo 'NET AMT';?></th>
            <th align="left"><?php echo 'SALES AMT';?></th>
			<th align="left"><?php echo 'Account';?></th>

            <!--<th align="left"><?php echo BALANCE;?></th>
-->
        </tr>



    <?php

    if(!empty($list_value)){?>



    <tbody>



        <?php

        foreach($list_value as $key=>$item):


$empId='';
            $sector = '';
			$tktAmt ='';
			//$Numbe=@split("-", $vNumber);
        $Number=$item->vTktNumber;
        $air=$Numbe[0];
            switch($item->vType){

                case 'Tkt': 

                    $sector = $this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$Number."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$Number."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector2'," WHERE nTicketId='".$Number."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$Number."'");
					
$tktAmt = $this->common_model->get_field_name('TicketFareDetails','nNetVendor'," WHERE nTicketId = ".$Number." ");
$empId=$this->common_model->get_field_name('TicketBooking','vIssuedBy'," WHERE nTId='".$Number."'");
                    break;

                case 'Xo':
$tktAmt = $this->common_model->get_field_name('XoBooking','vGrandNetFare'," WHERE nXId = ".$Number." ");
                   $sector = $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$Number."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$Number."'");
$empId=$this->common_model->get_field_name('XoBooking','vIssuedBy'," WHERE nXId='".$Number."'");
                    break;
 case 'Htl': $tktAmt = $this->common_model->get_field_name('HotelVoucher','vTotalDC'," WHERE nHVId = ".$Number." ");
 $empId=$this->common_model->get_field_name('HotelVoucher','vIssuedBy'," WHERE nHVId='".$Number."'");
break;
                default : $sector = '';
				
            } //end case

            $Number = $salesId = $Invpath = $InvDate =$InvNum = $credit = $debit = '';



            if($item->salesClosedId){

                $salesId  = $this->common_model->get_field_name('SalesClosingDetails','nSalesCloseId'," WHERE nId='".$item->salesClosedId."'");

                $Invpath = base_url().'index.php/sales_closing/editSalesClosing/4/e/'.$item->salesClosedId.'/'.$salesId.'/cl';

                $InvDate =  $this->common_model->get_field_name('SalesClosing','dDate', " WHERE nSCId = ".$salesId ." ");

                $InvDate = date("d-M-y", strtotime($InvDate));

                $InvNum =  $this->common_model->get_field_name('SalesClosing','vInvoiceNumber', " WHERE nSCId = ".$salesId ." ");

                $credit = $item->nAmountDr;

            }// end if



            if($search_tabletype == 'A') $credit = $item->nAmountDr;

            else $debit = $item->nAmountDr;



            if($search_tabletype == 'A' && $item->salesClosedId) $debit = $item->nAmountDr;

?>

    

                <tr>

                    <td><?php echo $key+1;?></td>

                    <td><?php echo date("d-M-y", strtotime($item->dDate));?></td>
                    
                    <td><?php  if($empId==''){ 
					echo $this->common_model->get_field_name('Admin','vUsername', " WHERE nId='".$item->vEnteredBy."' ");
					
					}else{
					echo stripslashes($this->common_model->get_field_name('EmployeeMaster', 'vCode', " WHERE nEId='".$empId."'"));
	
						}?></td>      
                    <td><a href="<?php echo base_url(). $item->path .$item->nSRId;?>"><?php echo $item->vDocNumber;?></a></td>

                    <td><?php echo $item->air."-".$item->Number;?></td>
   

                    <td><?php echo $item->passName;?><br><?php echo $sector. "<br>" . $item->vRemarks;?></td>

                   

                    <td><?php echo $tktAmt;?></td><td><?php echo number_format($debit,2);?></td>
					<td><?php echo $item->CardName;?></td>
                   <!-- <td><?php echo $debit - $credit;?></td>
-->
                </tr>

                <?php 

                $i = $i + 1;

                $totalDebit += $debit;

                $totalAmt += $tktAmt;

                //$totalopenDebit = $openDebit;

                //$totalopenCredit = $openCredit;



                endforeach;?>





        <tr id="bg">

            <td colspan="6" align="right"><strong><?php echo TOTALAMOUNT;?></strong></td>

            <td><?php echo number_format($totalAmt,2);?></td>
            <td><?php echo number_format($totalDebit,2);?></td>

            

            <!--<td><?php echo $totalDebit - $totalCredit;?></td>
-->
        </tr>
		
		
		<tr>
<td style="border:0px;" ></td>
            <td  style="border:0px;" colspan="13"  align="left"><strong><br><br><br><br><?php echo "PREPARED BY: ";  echo stripslashes($this->common_model->get_field_name('Admin','vUsername', " WHERE nId='".$this->session->userdata('AbsAdmin')."' "));
				?></strong> </td>

            
        </tr>
		<tr>
<td style="border:0px;"></td>
            <td style="border:0px;" colspan="13" align="left"><strong><br><br><br><br><?php echo RECEIVEDBY; echo":";;?></strong></td>

            
        </tr>

        


<?php }else { ?>

   <tr>

        <td colspan="13" align="center"><?php echo SORRYNORECORD;?></td>

    </tr>

        <?php

    }//end if

    ?>



    <tr class="formButs">

        <td>&nbsp;</td>

        <td colspan="12">

            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">

            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">

        </td>

    </tr>

    </tbody>

</table>

