<?php/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4:             */// +----------------------------------------------------------------------+// | PHP version 4/5                                                      |// +----------------------------------------------------------------------+// | Copyright (c) 2004-2010 Qbicblack  Technologies                      |// +----------------------------------------------------------------------+// | This source file is a part of ABS Account Balancing Solution         |// +----------------------------------------------------------------------+// | Authors: programmers                                                 |// |                                                                      |// +----------------------------------------------------------------------+                                                     ?>
<!DOCTYPE html>    <head>    <link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">   
 <link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">    
 <link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">    
 </head>    
 <body><link href="<?php echo base_url();?>static/plugins/popup_div/style/style.css" rel="stylesheet" type="text/css" media="all" />
 <script type="text/javascript" src="<?php echo base_url();?>static/plugins/popup_div/js/script2.js"></script>   
 <script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>    
 <script>    $(document).ready(function() {    
 $('#toPopup4').hide();   
 $('#email').click(function(e) {        
 $('#toPopup4').show();    });       
 $('#btnPrint').click(function(e) {            
 window.print() ;        });    });			    
 </script>	
 <?php if($this->uri->segment(4) == 'U') { ?><span class="warning"> <?php echo "Failed To Send Email"; ?></span><?php } ?>    
 <table width="100%" border="0" cellspacing="0" cellpadding="0" class="printForm">        
 <tbody>        
 <?php            
 $logo = $this->common_model->get_field_name('Location','vImg'," WHERE nId='".$this->ddlLocation."'");            
 $name = $this->common_model->get_field_name('Location','vTitle'," WHERE nId='".$this->ddlLocation."'");            
 $addr = $this->common_model->get_field_name('Location','vAddress'," WHERE nId='".$this->ddlLocation."'");            
 $phone = $this->common_model->get_field_name('Location','vPhone'," WHERE nId='".$this->ddlLocation."'");            
 $mobile = $this->common_model->get_field_name('Location','vMobile'," WHERE nId='".$this->ddlLocation."'");            
 $email = $this->common_model->get_field_name('Location','vEmail'," WHERE nId='".$this->ddlLocation."'");            
 $web = $this->common_model->get_field_name('Location','vWeb'," WHERE nId='".$this->ddlLocation."'");            
 $curr = $this->common_model->get_field_name('Location','nCurrency'," WHERE nId='".$this->ddlLocation."'");            
 $cVal = $this->common_model->get_field_name('Currency','vCurrcode'," WHERE nId='".$curr."'");            ?>
 <style type="text/css">
 .print_desc {   	 
 padding-left: 10% !important;}
 @media print {input#email{display: none;}
 </style>		
 <tr>		
 <td colspan="8">                    
 <img src="<?php echo base_url();?>upload/location/<?php echo $logo;?>" alt="<?php echo $name;?>" class="print_logo"> 
 <div class="print_desc">                    
 <span style="font-size: 14px;font-weight: bold;"><?php echo $name;?></span><br> 
 <?php echo $addr;?><br>                        
 <?php echo PHONE;?>: <?php echo $phone;?>                        
 <?php echo FAX;?>: <?php echo $mobile;?><br>                        
 <?php echo EMAIL;?>: <?php echo $email;?>,                        
 <?php echo WEB;?>: <?php echo $web;?>                    
 </div>                </td>		
 
 <td colspan="2">			
 
 <input type="button" name="btnPrint" id="btnPrint" value="<?php echo PRINTINVOICEPAGE;?>" class="btnGeneral">
 <br>			
 <div><a href="#" class="topopup" id="1"><input type="image" name="email" src="<?php echo base_url(); ?>static/images/email_icon.jpg" id="email"  height="35" width="50"></a></div>
 </td>		
 </tr>		      
 <tr>               
 <td colspan="8" align="center"><h5><?php  echo SALESRECEIPT;?></h5></td>           
 </tr>       
 <tr>			
 <td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;"><?php echo CUSTDATE;?>&nbsp;            <strong><?php echo $this->txtDate;?></strong>			</td>
 <td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>	
 <td width="300" align="left" style="border: 1px solid black;border-left-style:none; border-bottom: 0px"><?php echo DOCNUMBER;?>&nbsp;			<strong><?php echo $this->txtDocNumber;?><strong>			</td>				                        
 </tr>		
 <tr>					
 <td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;"><?php echo TONAME;?>&nbsp;		    <strong><?php echo $this->txtRefName;?></strong>			</td>			
 <td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>	
 <td width="300" align="left" style="border: 1px solid black;border-left-style:none; border-bottom: 0px">			</td>		
 </tr>		<tr></tr>        
 <tr>            			
 <td width="300" style="border: 1px solid black; border-bottom: 0px;border-right-style:none;">			</td>		
 <td style="border: 1px solid black; border-bottom: 0px; border-right-style: none;border-left-style:none;" colspan="1">&nbsp;</td>	
 <td width="300" align="right" style="border: 1px solid black;border-left-style:none; border-bottom: 0px">						</td>
 </tr>		 
 <style type="text/css"> 
 table { font-size: 10px; }</style>
 <td colspan="8" height="520" valign="top" style="border: 1px solid black; border-top: 0px;">
 <table cellpadding="0" style="border: 0px" width="100%">    
 <tr id="nowas">  <style> 
 #nowas{	 
 border-bottom: 1px solid black !important; }
 td{    border: 1px solid black;border-collapse: collapse;}
 </style>                             
 <td><?php echo SRNO;?></td>                           
 <td><?php echo TTYPE;?></td>                            
 <td><?php echo AIRLINE;?></td>                           
 <td><?php echo TICKETNAME;?></td>                           
 <td><?php echo PASSENGER;?></td>                            
 <td><?php echo OTHERDETAILS;?></td>                           
 <td><?php echo CREDITCARD;?></td>                            
 <td><?php echo AMOUNT;?></td>                       
 </tr>                        <?php foreach($chkCard as $k=>$v): ?>                             
 <tr>                                   
 <td>                                        <?php echo $k+1;?>                                    </td>  
 <td><label>                                        <?php echo stripslashes($chkType[$k]);?>                                    </label></td>
 <td>                                        <?php echo stripslashes($chkAirline[$k]);?>                                    </td>  
 <td>                                        <?php echo stripslashes($chkTktNumber[$k]);?>                                    </td>  
 <td>                                        <?php if(!empty($chkTktNumber[$k])){ echo $chkPass[$k]; } else { echo $this->txtRefName; };?>                                    </td>
 <td>                                        <?php echo $chkDesc[$k];?>                                    </td>    
 <td>                                        <?php echo $this->common_model->get_field_name('Card','vName', " WHERE nCId = ".$chkCard[$k] ."");?>                                    </td>  
 <td>                                        <?php echo $chkAmount[$k];?>                                    </td>  
 </tr>                                <?php   
 $total += $chkAmount[$k]; 
 endforeach;                        ?>   

 </table>                </td>            </tr>  
<tr >            
 <td colspan="8" style="padding-top:0px;margin:-4px;  border: 1px solid black; border-top: 0px">               
 <table  class="reportForm">								       			
                        
 <tr>							
 <td valign="bottom" style="border:none !important;"><strong><?php echo "Remarks: ";?></strong><?php echo $this->txtRemarks;?></td>		
                       </tr> 
 </table>        </td>            </tr> 
 <tr >            
 <td colspan="8" style="padding-top:0px;margin:-4px;  border: 1px solid black; border-top: 0px">               
 <table  class="reportForm">								       			
                         <tr>							
 <?php
                                $valCur = $this->common_model->getCompanyCurr();
								$strCurCode = $this->common_model->currencyCode($valCur);
								$strCurCoin = $this->common_model->currencyCoin($valCur);
								$strCurDecimal = $this->common_model->currencyDecimal($valCur);
								$multiple = '1';
								for($i=0;$i<$strCurDecimal;$i++){ $multiple =  $multiple . '0';}
								$decimal_part = (($total) - floor($total)) * $multiple;
                                $lengdecpart=strlen($decimal_part);
								
                               if($lengdecpart = 3)
                               {
                               ?>
	
				
				                <td style="border:0"  colspan="2" align="left"><strong><font size="1"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode . " AND " . $decimal_part. " " . $strCurCoin . "  " . ONLY;?></td> </strong></font>
								<td width="200" style="border:0" valign="middle" align="right"><strong><font size="1"><?php echo TOTAL;?></strong></td></font>
                                <td valign="middle" align="right" style="border:0">
								<strong><font size="1"><?php echo $total=number_format((float)$total,3, '.', '');?></strong></font>

								
								</td>
								
								
				
				   
								<?php
								} else 
									if($lengdecpart = 2){
									
								?>
								
								<td style="border:0" colspan="2" align="left"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode .  " " . $strCurCoin . "  " .  ONLY;?></td>
								<td align='center'><strong><?php echo TOTAL;?></strong></td>
                                <td>
                                <strong><?php echo $total . '0';?></strong>
                                </td>
								
								<?php }else 
								if($lengdecpart = 1){
								
								?>
								
								<td style="border:0" colspan="2" align="left"><?php echo AMOUNTINWORDS;?>&nbsp;&nbsp;<?php echo $this->common_model->convert_number_to_words(floor($total)) . " " . $strCurCode .  " " . $strCurCoin . "  " .  ONLY;?></td>
								<td align='center'><strong><?php echo TOTAL;?></strong></td>
                                <td>
                                <strong><?php echo $total . '0';?></strong>
                                </td>
				
				
				
								<?php } ?>	 </tr> 
 
 </table>          </td>           </tr><tr >           
 <td colspan="8" style="padding-top:0px;margin:-4px;  border: 1px solid black; border-top: 0px">                		
 <table height="70" class="reportForm">								       			</td> 	
 <tr>							
 <td valign="bottom" width="200" colspan="2" style="border:none !important;"><strong><font size="1">
 
 
<?php echo "PREPARED BY";?></td></strong></font>							
<td valign="bottom" width="200" colspan="2" style="border:none !important;" align="bottom"><strong><font size="1">
<?php echo "APPROVED BY";?></td></strong></font>							
<td valign="bottom" colspan="2" style="border:none !important;"><strong><font size="1">
<?php echo "RECEIVED BY";?></td></strong></font>                        </tr>                         
<tr>     

                       
<td style="border:none !important;"><?php echo $this->common_model->get_field_name('Admin','vUsername'," WHERE nId = '".$this->vEnteredBy."'");?></td>                            
<td style="border:none !important;">&nbsp;</td>                            
<td style="border:none !important;">&nbsp;</td>                       
 </tr>						                </table>                    
 </tr>    </tbody>    </table>	
 <!--<strong style="font-size:9px;"><?php //echo "This is a Computer generated document,bears no signature";?><strong>	        
 --></tbody>    </table>
 <!-- Start of 4 Sticky Note -->
 <div id="toPopup4" class="toPopup">    
 <div class="close"></div>    
 <span class="ecs_tooltip">Press Esc to close <span class="arrow"></span></span>    
 <div id="popup_content">        
 <?php $data['location'] = $this->ddlLocation; echo $this->load->view('sales_receipt/show_emailDetails',$data);?>    
 </div><!-- End of Sticky Note --></div></body></html>