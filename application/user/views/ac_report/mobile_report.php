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
            window.location.replace('<?php echo base_url().'index.php/ac_report/showlist/'.$this->uri->segment(3);?>');
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

<table width="100%" border="0" cellspacing="0" cellpadding="0" class="reportForm" id="reportForm" >
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
            <th align="center" colspan="9">
                <!-- <img src="<?php echo $link;?>" alt="<?php echo $name;?>" class="print_logo" width="100" height="100"> -->

                <div class="print_desc">
                    <span style="font-size: 11px;font-weight: bold;"><?php echo $name;?></span><br>
                    <?php echo $addr;?><br>
                    <?php echo PHONE;?>: <?php echo $phone;?>
                    <?php echo MOBILE;?>: <?php echo $mobile;?><br>
                    <?php echo EMAIL;?>: <?php echo $email;?>,
                    <?php echo WEB;?>: <?php echo $web;?>
                </div>
            </th>
        </tr>
		
<!-- <tr><td style="border:0"></td></tr> -->
		<tr>
            <td colspan="6" style="border:0" height="25px" align="left"><strong><?php echo "CTC Search Report";?></strong></td>
        <td colspan="6" style="border:0" height="25px" align="right">&nbsp;<strong><?php
date_default_timezone_set('Asia/Kuwait');
		echo $date = date('d-m-Y H:i:s');?></strong></td>
		</tr>
            <!-- <tr><td style="border:0"></td></tr> -->
            <tr>
                <th align="left"><?php echo FROMDATE;?></th>
                <th align="left"><?php echo $fromDate;?></th>
                <th align="center"><?php echo TODATE;?></th>
                <th align="left"><?php echo $toDate;?></th>
                <!-- <th align="left" colspan="9">&nbsp;</th> -->
            </tr>
			<style type="text/css">
body{
  -webkit-print-color-adjust:exact;
}
  table { font-size: 9px; }
</style>

<style media="print">
 
 #dt{
width:8%!important;	
}
#doc{
width:15%!important;	
}
	#des{
		width:15%;
	}
	

  #bg{ background-color:#CCC !important; }
 
</style>
<style>
td{font-size: 12px;
    border: 1px solid black;
border-collapse: collapse;
}
</style>
			<?php
   
    if(!empty($list_value)){?>

    <tbody>
<tr id="bg">
            <td height="25px" align="center"><?php echo SRNO;?></td>
            <td align="center"><?php echo CUSTDATE;?></td>
            
            <td align="center"><?php echo DOCUMENTNUMBER;?></td>
            <td align="center"><?php echo TICKETNO;?></td>
            <td align="center"><?php echo DESCRIPTION;?></td>
            <td align="center"><?php echo NETAMT;?></td>
            <td align="center"><?php echo SLSAMT;?></td>
            
        </tr>
        <?php
    $i = 1;
        foreach($list_value as $key=>$item):
            ?>


		

    
            


               
                <tr style="page-break-inside: avoid !important;">
                    <td style="page-break-inside: avoid !important;" height="25" align="center"><?php echo $key+1;?></td>
                    <td id="dt" style="page-break-inside: avoid !important;" align="center"><?php echo date("d-M-y", strtotime($item->date));?></td>
					<td><?php echo $item->vInvoiceNumber;?></td>
					<?php 
					if($item->ntype=='sc'){
					switch($item->vType){
						case "Tkt":
						$Passenger=$this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$item->vTktNumber."'");
						
						$ticket=$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$item->vTktNumber."'");
						$vNumber=$item->vTktNumber;
						$desc  = "Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");
					
						Break;
						case "Htl":
						$Passenger=$this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$item->vTktNumber."'");
						
						$ticket=$this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$item->vTktNumber."'");
						
						$vNumber=$item->vTktNumber;
						$hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");

						$desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");

						
						Break;
						case "Xo":
						
						$Passenger=$this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='".$item->vTktNumber."'");
						
						$ticket=$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$item->vTktNumber."'");
						
						$vNumber=$item->vTktNumber;
						$desc  = "XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");

						Break;
					}}elseif($item->ntype=='rc'){
						$newold=$this->common_model->get_field_name('RefundRegisterDetails','vNewOld'," WHERE vTktType='".$item->vType."' AND nTktNum='".$item->vTktNumber."'");
						if($newold=="N"){
						switch($item->vType){
						case "Tkt":
						$Passenger=$this->common_model->get_field_name('TicketBooking','vPassenger'," WHERE nTId='".$item->vTktNumber."'");
						
						$ticket=$this->common_model->get_field_name('TicketBooking','vTicktManual'," WHERE nTId='".$item->vTktNumber."'");
						$vNumber=$item->vTktNumber;
						$desc  = "Tkt: ".$this->common_model->get_field_name('TicketDetails','vSector1'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector12'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector22'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector33'," WHERE nTicketId='".$vNumber."'").'-'.$this->common_model->get_field_name('TicketDetails','vSector44'," WHERE nTicketId='".$vNumber."'");
					
						Break;
						case "Htl":
						$Passenger=$this->common_model->get_field_name('HotelVoucher','vPassenger'," WHERE nHVId='".$item->vTktNumber."'");
						
						$ticket=$this->common_model->get_field_name('HotelVoucher','vTicktManual'," WHERE nHVId='".$item->vTktNumber."'");
						
						$vNumber=$item->vTktNumber;
						$hotel          = $this->common_model->get_field_name('HotelVoucher','vHotelName'," WHERE nHVId='".$vNumber."'");

						$desc  = "Htl: " . $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$hotel."'").'-'.$this->common_model->get_field_name('Country','vName'," WHERE nId='".$cityName."'").'- ChkIn:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckIn'," WHERE nHVId='".$vNumber."'"))).'- ChkOut:'.date('d-m-Y',strtotime($this->common_model->get_field_name('HotelVoucher','dCheckOut'," WHERE nHVId='".$vNumber."'"))).'-'.NHT.' '.$this->common_model->get_field_name('HotelVoucher','vNight'," WHERE nHVId='".$vNumber."'");

						
						Break;
						case "Xo":
						
						$Passenger=$this->common_model->get_field_name('XoBooking','vPassenger'," WHERE nXId='".$item->vTktNumber."'");
						
						$ticket=$this->common_model->get_field_name('XoBooking','vTicktManual'," WHERE nXId='".$item->vTktNumber."'");
						
						$vNumber=$item->vTktNumber;
						$desc  = "XO: " . $this->common_model->get_field_name('XoDetails','vSector'," WHERE nXoId='".$vNumber."'").'-'.$this->common_model->get_field_name('XoDetails','vSector2'," WHERE nXoId='".$vNumber."'");

						Break;
						}}else{
						$ticket=$item->vTktNumber;
						$Passenger=	$this->common_model->get_field_name('RefundRegisterDetails','vPassenger'," WHERE vTktType='".$item->vType."' AND nTktNum='".$item->vTktNumber."'");
						$desc  = '';					
						}
						
					}else{
						$ticket='';
						$Passenger=$item->vRefName;
						$desc  = $item->vRemarks;	
						
					}
					
					?>
					<td><?php echo $item->nAirline." - ".$ticket;?></td>
					<td><?php echo $Passenger."<br>".$desc;?></td>
					<td align="right" ><?php echo $item->vNetAmt;?></td>
					<td align="right" ><?php echo $item->vSlsAmt;?></td>
					
               
                </tr>
               

<?php        
$totalvNetAmt += $item->vNetAmt;
            $TotalvSlsAmt  += $item->vSlsAmt;
endforeach;?>
<tr id="bg">
            <td colspan="5" align="right" height="25"><strong><?php echo GRANDTOTAL;?></strong></td>
            
            <td align="right"><?php echo number_format((float)$totalvNetAmt, 3, ".", "");?></td>
            <td align="right"><?php echo number_format((float)$TotalvSlsAmt, 3, ".", "");?></td>
        </tr>

          
       
<?php }else { ?>
   <tr>
        <td style="border:0" colspan="11" align="center"><?php echo SORRYNORECORD;?></td>
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
        <td style="border:0" colspan="9">
            <input type="button" name="btnCancel" value="<?php echo BACKTOREPORT;?>" id="btnCancel">
            <input type="button" name="btnPrint" value="<?php echo PRINTPAGE;?>" id="btnPrint">
        </td>
    </tr>
    </tbody>
</table>
