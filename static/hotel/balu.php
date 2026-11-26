<?php 
header("Location: https://abskw.com/demo/index.php/hotel_insert/index/");
date_default_timezone_set('Asia/Kuwait');
$admin_email = "balu@abskw.com";
  $email = "sys@abskw.com";
  $subject = "Cron Test Inserted";
  $comment = date('Y-m-d H:i:s');
 // $comment. = "<br>".$hotel_item['CountryName'];
  
  //send email
  mail($admin_email, "$subject", $comment, "From:" . $email);
echo  date(DATE_RFC2822);
//trigger_error('My PHP Version: ' . phpversion(), E_USER_NOTICE);
//phpinfo();
?>