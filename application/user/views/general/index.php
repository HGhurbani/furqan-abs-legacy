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
<html lang="en">
<head>
<?php $this->load->view('general/title');?>	
</head>
<body>

<div id="wrapper">

<!-- Header Styles Start -->
<?php $this->load->view('general/header');?>
<!-- Header Styles End -->



<!-- Top Nav Section Start -->
<?php $this->load->view('menu');?>
<!-- Top Nav Section End -->




<!-- Content Area Start -->
<div class="bannertop"></div>
<div class="contentarea">

<!-- content Area Left start --><!-- content Area Left End -->


<!-- content Area Right start -->
<div class="contentarea_right">
<?php $this->load->view('attendance/attendance');?>
<?php $this->load->view('center');?>
<?php $this->load->view('news/news_events');?>

<div class="clear"></div>
</div>



<!-- content Area Right End -->


<div class="clear"></div>
</div>

<!-- Content Area End -->


<!-- Footer Section Start -->
<?php $this->load->view('general/footer');?>
<!-- Footer Section End -->



<div class="clear"></div>
</div>
</body>
</html>
