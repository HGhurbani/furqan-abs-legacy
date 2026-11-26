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
error_reporting(E_ALL ^ E_NOTICE);

//site Name
$title = $this->common_model->get_site_title(); 
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"> <!--<![endif]-->
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
<title><?php echo $title;?></title>
<meta name="description" content="">
<meta name="viewport" content="width=device-width">
<script src="<?php echo base_url();?>static/js/modernizr-latest.js"></script>

<script src="<?php echo base_url();?>static/js/jquery-1.9.1.min.js"></script>

<!--<script src="//ajax.googleapis.com/ajax/libs/jquery/1.8.3/jquery.min.js"></script>-->
<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery-1.4.1.js"></script>

<link href="<?php echo base_url();?>static/css/reset.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/common.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/structure.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/menu.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/user_style.css" rel="stylesheet" type="text/css">
<script src="<?php echo base_url();?>static/js/jquery.validate.js" type="text/javascript"></script>
<!--<script src="<?php echo base_url();?>static/js/cmxforms.js" type="text/javascript"></script>
--><?php /*?><script src="<?php echo base_url();?>static/js/jquery.idTabs.min.js" type="text/javascript"></script>
<?php */?>
<script type="text/javascript">
//Initial load of page
$(document).ready(sizeContent);

//Every resize of window
$(window).resize(sizeContent);

//Dynamically assign height
function sizeContent() {
    var newHeight = $("#maincontent").height() - $(".pageHead").height() -10 + "px";
 	$(".leftMenu").css("height", newHeight);
	$('.hasSubMenu').css({'margin-left':$(".leftMenu").width()});
	
}
</script>
<meta name="google-translate-customization" content="d1e096974a431b7a-c71357db09da4576-g6a1d6b38a5516264-1e"></meta>

</head>
