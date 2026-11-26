<?php
include('hotel_result.php');
?>


<?php
$hotelData=getHotelTickets();
if($hotelData){
$phpArray = json_decode($hotelData, true);
foreach ($phpArray as $key => $value) {
	 echo('<pre>');  print_r($value); echo('</pre>');
}
}else{
echo('There is no hotel details to fetch');
}
echo "calling Model";
?>
<html>
<head>

<!-- <meta http-equiv="refresh" content="3"; URL="http://booking-accounts.com/abs/index.php"> -->
 <script language="javascript" type="text/javascript">
     <!--
//     window.location="http://booking-accounts.com/abs/index.php";

//redirect("http://booking-accounts.com/abs_demo/index.php/hotel_insert/showlist/");
//     window.location="http://booking-accounts.com/abs_demo/index.php/hotel_insert/showlist/";
     // -->
 </script>

        <title>Redirecting</title>
</head>
</html>

