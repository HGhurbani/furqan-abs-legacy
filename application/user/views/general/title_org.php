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

switch($this->session->userdata('lang'))
{ 
	case "eng":
		$htmlDocType = '<html xmlns="http://www.w3.org/1999/xhtml">';
	break;

	case "ar":
		$htmlDocType = '<html dir="rtl" lang="ar" xml:lang="ar" xmlns="http://www.w3.org/1999/xhtml">';
	break;
	
	default:
		$htmlDocType = '<html xmlns="http://www.w3.org/1999/xhtml">';
	break;
}

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<?php echo $htmlDocType;?>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $title;?> Admin Panel</title>
<meta name="rating" content="general" />
<meta name="copyright" content="Copyright <?php echo date('Y');?> - Qbicblack" />
<meta name="expires" content="never">
<meta name="distribution" content="global" />
<meta name="robots" content="index,follow" />
<link href="<?php echo base_url();?>static/css/admin_style.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/paging.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/css/round-corner.css" rel="stylesheet" type="text/css">

<!-- jQuery -->
<script type="text/javascript" src="<?php echo base_url();?>static/js/jquery-1.3.2.min.js"></script>

<!-- jQuery Configuration -->
<script type="text/javascript" src="<?php echo base_url();?>static/js/simpla.jquery.configuration.js"></script>

<script src="<?php echo base_url();?>static/plugins/chat/chat.js" type="text/javascript"></script>
<link href="<?php echo base_url();?>static/plugins/chat/chat.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/plugins/chat/screen.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/plugins/chat/screen_ie.css" rel="stylesheet" type="text/css">
<link href="<?php echo base_url();?>static/images/favicon.ico" type="image/x-icon" rel="icon"> 
<link href="<?php echo base_url();?>static/images/favicon.ico" type="image/x-icon" rel="shortcut icon">
		