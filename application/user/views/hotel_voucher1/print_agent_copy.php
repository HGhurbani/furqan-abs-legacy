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
<!DOCTYPE html>
<head>
<title>Print Agent Copy</title>
                <link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">
                <link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">
                <link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">
                </head>
                <body>
<link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
<script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>

<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>
<script>
$(document).ready(function() {

    $('#toPopup4').hide();
    $('#email').click(function(e) {
        $('#toPopup4').show();
    });

    $('#btnPrint').click(function(e) {
        window.print() ;
    });
});
</script>
<?php if($this->uri->segment(4) == 'U') { ?>
<span class="warning"> <?php echo "Failed To Send Email"; ?></span>
<?php } ?>
<table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm"><tbody>        <?php            $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");            $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");            $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");            $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");            $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");            $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");            $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");            $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");            $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");            ?>            <tr>                <td colspan="6" style="padding-top:none !important;">                    
<img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" style="height:60px; width:17%;margin-top: -10px;" alt="<?php echo $name;?>" class="print_logo">                    <div class="print_desc" style="margin-top:-10px !important;">					<span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>					<?php echo $addr;?>.<?php echo PHONE;?>: <?php echo $phone;?>,<?php echo FAX;?>: <?php echo $mobile;?>					</div>					<div style="float:right; margin-top: -9%">					<span style="font-size: 10px;font-weight: bold;"><?php echo SERVICEVOUCHER;?></span>					</div>                </td> 

<style type="text/css">
.smallBox1{
		    
	}

@media print {input#email{display: none;}
@media print {
    @page {
        size: letter;
        margin: 0.1cm;
    }
	
}

</style>   


<td colspan="2">        <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTAGENTPAGE;?>" class="btnGeneral"><br><br><div align="center"><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>    </td></tr><tr>    <td><?php echo VOUCHERID;?></td>    <td> <div class="smallBox1"><?php echo $this->txtTktName;?></div></td>	<td><?php echo CUSTDATE;?></td>    <td><label><div class="smallBox1"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div></label></td>	<td><?php echo SERVICES;?></td>    <td><label>        <div class="smallBox1"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?></div>    </label></td></tr><tr>    <td><?php echo LEADNAMES;?></td>    <td colspan="2"><div class="smallBox1"> <?php echo stripslashes($this->txtPassenger);?></div></td>    <td><?php echo TOBEPRESENTED;?></td>     <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?></div></td></tr><tr>   <!-- <td><?php echo COUNTRYNAME;?></td>    <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>-->    <td><?php echo ADDRESS;?></td>    <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></div></td>	<td>
<?php echo PHONE;?></td>    <td colspan="2"><div class="smallBox1"><?php 
echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td></tr><tr><td><?php echo REMARKS;?></td>    <td colspan="5"><label><?php echo stripslashes($this->txtExtraServ);?>,        <?php echo stripslashes($this->txtRemarks);?>    </label></td>    </tr>

<tr>    <td colspan="6">        




<table width="100%" class="reportForm">            
<tr>               
 <td><?php echo CHECKIN;?></td>                
 <td><?php echo CHECKOUT;?></td>                
 <td><?php echo NIGHTS;?></td>               
 <td><?php echo ADULT;?></td>                
 <td><?php echo CHILD;?></td>                
 <td><?php echo ROOMTYPE;?></td>                
 <td><?php echo 'UNITS';?></td>
<td style="border:none; " align="right"><?php echo "SIGNATURE & STAMP";?></td> </tr>            
 <tr>                
 <td><label> <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckIn)));?></label></td>                
 <td><label><?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckOut)));?></label></td>
 <td><label><?php echo stripslashes($this->txtNights);?></label></td>
 <td><label><?php echo stripslashes($this->txtAdult);?></label></td>                
 <td><label>                    <?php echo stripslashes($this->txtChild);?>                </label></td>                
 <td><label>                    <?php echo stripslashes($this->txtRoomType);?>                </label></td>                
 <td><label>                    <?php echo stripslashes($this->txtNoOfUnit);?>                </label></td>
<td style="border:none;"><label>                              </label></td> 
 </tr>        </table>    
 </td></tr>
 <tr>	<td  colspan="3"><div class="Box">		
 <?php echo "BOOKED & PAID BY";?> : <?php
 echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$this->ddlAcCode."' ");?></div></td>

 <td align="right" colspan="3"><div class="Box"><?php echo "Hotel Conf #:";?> : <?php
 echo $this->vHotelConfId;?>  &nbsp&nbsp&nbsp  <?php echo ISSUEDBY;?> : <?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?><br>
 
 <?php ?>    </div></td></tr>
  <tr><td><td></tr></tbody></table>
 
 
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm"><tbody> <tr>                <td colspan="6" style="padding-top:none !important;">                    
 <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" style="height:60px; width:17%;margin-top: -10px;" alt="<?php echo $name;?>" class="print_logo">                    <div class="print_desc" style="margin-top:-8px !important;">					<span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>					<?php echo $addr;?>.<?php echo PHONE;?>: <?php echo $phone;?>,<?php echo FAX;?>: <?php echo $mobile;?>					</div>					<div style="float:right; margin-top: -8%">					<span style="font-size: 10px;font-weight: bold;"><?php echo SERVICEVOUCHER;?></span>					</div>                </td>    <td colspan="2">    </td></tr><tr>    <td><?php echo VOUCHERID;?></td>    <td> <div class="smallBox1"><?php echo $this->txtTktName;?></div></td>	<td><?php echo CUSTDATE;?></td>    <td><label><div class="smallBox1"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div></label></td>	<td><?php echo SERVICES;?></td>    <td><label>        <div class="smallBox1"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?></div>    </label></td></tr><tr>    <td><?php echo LEADNAMES;?></td>    <td colspan="2"><div class="smallBox1"> <?php echo stripslashes($this->txtPassenger);?></div></td>    <td><?php echo TOBEPRESENTED;?></td>     <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?></div></td></tr><tr>   <!-- <td><?php echo COUNTRYNAME;?></td>    <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>-->    <td><?php echo ADDRESS;?></td>    <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></div></td>	<td><?php echo PHONE;?></td>    <td colspan="2"><div class="smallBox1"><?php 
 echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></div></td></tr><tr><td><?php echo REMARKS;?></td>    <td colspan="5"><label><?php echo stripslashes($this->txtExtraServ);?>,        <?php echo stripslashes($this->txtRemarks);?>    </label></td>    </tr>
 <tr>    <td colspan="6">        
 <table width="100%" class="reportForm">            
<tr>               
 <td><?php echo CHECKIN;?></td>                
 <td><?php echo CHECKOUT;?></td>                
 <td><?php echo NIGHTS;?></td>               
 <td><?php echo ADULT;?></td>                
 <td><?php echo CHILD;?></td>                
 <td><?php echo ROOMTYPE;?></td>                
 <td><?php echo 'UNITS';?></td>
<td style="border:none; " align="right"><?php echo "SIGNATURE & STAMP";?></td> </tr>            
 <tr>                
 <td><label> <?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckIn)));?></label></td>                
 <td><label><?php echo stripslashes(date('d-m-Y',strtotime($this->txtCheckOut)));?></label></td>
 <td><label><?php echo stripslashes($this->txtNights);?></label></td>
 <td><label><?php echo stripslashes($this->txtAdult);?></label></td>                
 <td><label>                    <?php echo stripslashes($this->txtChild);?>                </label></td>                
 <td><label>                    <?php echo stripslashes($this->txtRoomType);?>                </label></td>                
 <td><label>                    <?php echo stripslashes($this->txtNoOfUnit);?>                </label></td>
<td style="border:none;"><label>                              </label></td> 
 </tr>        </table>    
 </td></tr>
 <tr>	<td  colspan="3"><div class="Box">		
 <?php echo "BOOKED & PAID BY";?> : <?php echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$this->ddlAcCode."' ");?></div></td>

 <td align="right" colspan="3"><div class="Box"><?php echo "Hotel Conf#:";?> : <?php echo $this->vHotelConfId;?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo ISSUEDBY;?> : <?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?><br>
 <tr><td><td></tr><tr><td><td></tr>
 <?php ?>    </div></td></tr>
 
 </tbody></table>
 
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">
<tbody>
            <tr>
                <td colspan="6" style="padding-top:none !important;">
                    <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" style=" height:60px; width:17%;margin-top: -10px;" alt="<?php echo $name;?>" class="print_logo">
                    <div class="print_desc" style="margin-top:-5px !important;">					
					<span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br>					
					<?php echo $addr;?>.<?php echo PHONE;?>: <?php echo $phone;?>,<?php echo FAX;?>: <?php echo $mobile;?>					
					</div>					
					<div style="float:right; margin-top: -8%">					
					<span style="font-size: 10px;font-weight: bold;"><?php echo "ACCOUNTS COPY";?></span>					
					</div>
                </td> 

   <td colspan="2">
      
    </td>
</tr>
<tr>
    <td><?php echo VOUCHERID;?></td>
    <td> <div class="smallBox1"><?php echo $this->txtTktName;?></div></td>	<td><?php echo CUSTDATE;?></td>
    <td><label><div class="smallBox1"><?php echo date('d-m-Y',strtotime($this->txtDate));?></div></label></td>	<td><?php echo SERVICES;?></td>
    <td><label>
        <div class="smallBox1"><?php echo $this->common_model->get_field_name('Services','vTitle'," WHERE nServId='".$this->ddlServices."'");?></div>
    </label></td></tr><tr>
    <td><?php echo LEADNAMES;?></td>
    <td colspan="2"><div class="smallBox1"> <?php echo stripslashes($this->txtPassenger);?></div></td>

    <td><?php echo TOBEPRESENTED;?></td>
     <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Hotel','vName'," WHERE nHId='".$this->ddlHotel."'");?></div></td>
</tr>
<tr>
   <!-- <td><?php echo COUNTRYNAME;?></td>
    <td colspan="2"><div class="smallBox1"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCountry."'");?></div></td>-->

    <td><?php echo ADDRESS;?></td>
    <td colspan="2"><?php echo $this->common_model->get_field_name('Country','vName'," WHERE nId='".$this->ddlCity."'");?></td>	
	<td><?php echo PHONE;?></td>    <td colspan="2"><?php echo $this->common_model->get_field_name('Hotel','vPhone'," WHERE nHId='".$this->ddlHotel."'");?></td>
</tr>


<tr><td><?php echo REMARKS;?></td>    <td colspan="5"><label><?php echo stripslashes($this->txtExtraServ);?>,        <?php echo stripslashes($this->txtRemarks);?>    </label></td>
    
</tr>

<tr>
    <td colspan="12">
       <table class="reportForm" width="100%">



            <?php

            if(!empty($txtPayRoomType))

            {

                ?>

                <tr>

                    <td><?php echo ROOMTYPE;?></td>

                    <td ><?php echo INDATE;?></td>

                    <td><?php echo OUTDATE;?></td>

                    <td><?php echo DAYS;?></td>

                    <td><?php echo NOOFROOMS;?></td>

                   <!-- <td><?php echo RATENETFC;?></td>-->

                    <td><?php echo TOTALINFC;?></td>

                    <!--<td><?php echo RATENETDC;?></td>-->

                    <td><?php echo TOTALINDC;?></td>

                    <td><?php echo EXCHRATE;?></td>

                    <td><?php echo CURRENCY;?></td>

                </tr>



                <?php

                $currency_name =  $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId=" . stripslashes($this->ddlCurrency));

                foreach($txtPayRoomType as $key => $aitem):

                    ?>

                    <tr>

                        <td><?php echo stripslashes($txtPayRoomType[$key]);?></td>

                        <td ><?php echo stripslashes(date('d-m-Y',strtotime($txtChkinDate[$key])));?></td>

                        <td ><?php echo stripslashes(date('d-m-Y',strtotime($txtChkoutDate[$key])));?></td>

                        <td><?php echo stripslashes($txtNight[$key]);?></td>

                        <td><?php echo stripslashes($txtNoofRooms[$key]);?></td>

                        <!--<td><?php echo number_format($txtRateNetFc[$key],3);?></td>-->

                        <td><?php echo number_format($txtTotalinFc[$key],3);?></td>

                        <!--<td><?php echo stripslashes($txtRateNetDc[$key]);?></td>-->

                        <td><?php echo stripslashes($txtTotalinDc[$key]);?></td>

                        <td style="font-weight:bold;"><?php echo stripslashes($txtExchangeRate[$key]);?></td>

                        <td style="font-weight:bold;"><?php echo $currency_name; ?></td>

                    </tr>

                    <?php

                endforeach; ?>



                <tr>

                    <td colspan="2">&nbsp;</td>

                    <td colspan="3" align="right"><?php echo TOTALAMT;?></td>

                    <!--<td><?php echo number_format($this->txtRateNetFcTotal,3);?></td>-->

                    <td style="font-weight:bold;"><?php echo number_format($this->txtTotalFc,3);?></td>

                    <!--<td><?php echo $this->txtRateNetDcTotal;?></td>-->

                    <td style="font-weight:bold;"><?php echo $this->txtTotalDc;?></td>

                    

                </tr>



                <?php

            }//end if

            ?>

        </table>    
 </td></tr>

 <tr colspan="12" align="center">
 <td>SALES AMT : <strong><?php echo $this->txtCollected;?></strong></td>
 <td>SRV AMT : <strong><?php echo $this->txtServiceAmt;?></strong></td>
 <td>SRV PER : <strong><?php echo $this->txtServicePerc; echo"%";?></strong></td>
 </tr>
 <tr>	
 <td  colspan="3"><div class="Box">		
 <?php echo "BOOKED & PAID BY";?> : <?php 
 echo $this->common_model->get_field_name('VendorMaster','vName'," WHERE nVId = '".$this->ddlAcCode."' ");?></div></td>
 

 <td align="right" colspan="3">
 <div class="Box"><?php echo "Hotel Conf#:";?> : <?php  echo $this->vHotelConfId;?> &nbsp;&nbsp;&nbsp;&nbsp;<?php echo ISSUEDBY;?> : <?php echo $this->common_model->get_field_name('EmployeeMaster','vName'," WHERE nEId='".$this->ddlIssued."'");?>
 </div></td></tr></tbody></table>
<!-- Start of 4 Sticky Note -->

<div id="toPopup4" class="toPopup">
    <div class="close"></div>
    <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>
    <div id="popup_content">
        <?php $data['location'] = $this->ddlLocation; echo $this->load->view('hotel_voucher/show_emailDetails',$data);?>
    </div>
<!-- End of Sticky Note -->
</div>

</body>
</html>
