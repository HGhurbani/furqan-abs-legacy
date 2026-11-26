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
<meta http-equiv="Content-Type" content="text/html;charset=UTF-8">
<div id="framecontentTop">
        <!--Logo and top links-->
        <div id="logoStripMain">
            <div id="logoStripCont">
            	<div class="logoDet">
                	<div class="logoIcon">
                        <a class="logo" href="<?php echo base_url();?>index.php/welcome">
                            <img src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>" alt="<?php echo $this->common_model->getCompanyName();?>" height="38">
                        </a>
                    </div>
                	<h2 class="compName"><a href="<?php echo base_url();?>index.php/welcome"><?php echo $this->common_model->getCompanyName();?></a></h2>
                </div>

                <div class="poweredby">
                Powered by
                <img src="<?php echo base_url();?>static/images/ui-abs-logo.png" alt="ABS" width="66" height="19">
                </div>
                <div class="uname-time">
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
