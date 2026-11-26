<?php
$f_data='<?php
function getHotelTickets()
{     
     $Valid_Tkt_no   = ';
	 $f_data.="'".$_REQUEST['booking_data']."'";
$f_data.= ';return ($Valid_Tkt_no);
}
?>';

 ?>
<?php
// Please change the file name as based on your requirement
if($_REQUEST['booking_data']){
$file='hotel_result.php';
file_put_contents($file, $f_data);
}
if($_REQUEST['booking_data']){
echo(strlen ( $_REQUEST['booking_data'] ));
}else{
echo('0');
}
?>