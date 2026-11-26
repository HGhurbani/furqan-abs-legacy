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
$this->load->view('general/title');?>	
    <body style="background: #242727">
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->

        <div class="login-container">

            <!--<div class="welcome-txt"></div>-->

            <h1 class="clogo"><a href="<?php echo base_url();?>index.php"><img style="padding-top: 25px;" src="<?php echo base_url();?>upload/company/<?php echo $this->common_model->getCompanylogo();?>"></a></h1>

            <div class="login-box">
                <span class="title"><?php echo ADMINLOGIN;?></span>
  
