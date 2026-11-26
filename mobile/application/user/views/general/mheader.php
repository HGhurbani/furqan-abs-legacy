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
if($this->common_model->get_set_sessions()!='')
{
	$this->common_model->pageAccess(); 
}//end if

?>
<style>
table.entryForm td{
	
	padding: 4px 5px !important;
}
</style>
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<div id="framecontentTop">
        <!--Logo and top links-->
        <div id="logoStripMain">
            <div id="logoStripCont">
            	<div class="logoDet">
                	<div class="logoIcon">
                        <a class="logo" href="<?php echo base_url();?>index.php/manage_info/showlist/8">
                            <img src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>" alt="<?php echo $this->common_model->getCompanyName();?>" height="38">
                        </a>
                    </div>
                	<h2 class="compName"><a style="font-size:48% !important;" href="<?php echo base_url();?>index.php/manage_info/showlist/8"><?php echo $this->common_model->getCompanyName();?></a></h2>
               </div>

                <div  class="uname-time">
                	 <div class="username" style="text-transform:Capitalize;"><br>
					 <?php echo $this->session->userdata(showName) . " " . $this->session->userdata(showLName);?>
                   <?php echo " - ".$this->session->userdata(groupName); echo"&nbsp;&nbsp;&nbsp;"; ?>
                   
				   <a align="right" href="<?php echo base_url();?>/index.php/logout" class="logout"><img src="http://www.icon100.com/up/4080/128/205-LogOff.png" alt="" width="15" height="16"></a>
				
                    </div>
                </div>
                <div style="display:none;" class="uname-time">
                	 <a align="right" href="<?php echo base_url();?>/index.php/logout" class="logout"><img src="<?php echo base_url();?>/static/images/logout.gif" alt="" width="15" height="16"></a>
				<div class="username" style="text-transform:Capitalize;">
                   <?php echo $this->session->userdata(showName) . " " . $this->session->userdata(showLName);?>
                   <?php echo " - ".$this->session->userdata(groupName); ?>
                    </div>
                    <div class="time">
                    <?php echo $this->common_model->get_admin_lastlogin();?>
                    </div>
                </div>
          </div>
        </div>
