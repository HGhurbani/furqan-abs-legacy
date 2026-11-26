<?php 
//data as array form from Amadeus 
$All_tickets_division=$_REQUEST['AllTkts'];
$All_tickets_division1 = "T51G773392020530035528APR160745 RJ512ROYAL JORDANIAN AIRLINE 06AUG16E708DCFE5DDB

32AK32AK42202134 AS2RRW         32AKKSNKSAG27APR1600128APR16007

KWD0000001040003KWD00000000  00000000  00000000  00000000  00000000  000000000000               

NNNYN5NNYAYA NNNX   KW                             

000000001000004000000001000001001000001000000000



A02092F TK:YJT:04.35ANL:ROYAL JORDANIAN AIRLINE DDL:15AUG16

A0404RJ512ROYAL JORDAN 642R HK15AUG2340 0145 3AMMAMMAN        KWIKUWAIT       IND   O0   320    00738F TK:YJT:02.05ANL:ROYAL JORDANIAN AIRLINE DDL:15AUG16



A0701KWD     104.000KWD     133.350               KWDT1:   0.400GZT2:   0.250YXT3:  28.700XT

IT:   4.500KJ   7.100FR   1.550IZ   9.500QX   6.050YQ



A080101RSSKWICH0000000006AUG1606AUG16      F:RSSKWICH       E:NNEND/NON REFUNDABLE                                        B:1PC

A080102RSSKWICH0000000006AUG1606AUG16      F:RSSKWICH       E:NNEND/NON REFUNDABLE                                        B:1PC

A080103RSSKWICH0000000015AUG1615AUG16      F:RSSKWICH       E:NNEND/NON REFUNDABLE                                        B:1PC

A080104RSSKWICH0000000015AUG1615AUG16      F:RSSKWICH       E:NNEND/NON REFUNDABLE                                        B:1PC



A09010KWI RJ X/AMM RJ PAR Q KWIPAR99.74 72.31 RJ X/AMM RJ KWI Q PAR

KWI99.74 72.31 NUC344.10END ROE0.300778 XT 4.500KJ7.100FR1.55

0IZ9.500QX6.050YQ



A11S      133.350N                                     P:01



A12TKWI *RAHAL TRAVEL REF KAMAL 97795263



A14VL-174627APRMUCRM1A5H25XJ



";
$All_tickets = explode("division",$All_tickets_division);



// ============================ here making ticket files to array  ======================= 

$mutiple_ticket_status=0;
$single_ticket_status=0;
$conjetion_status = 0;

// For Normal or Multiple tickets Re-arranging
for($x=0;$x<sizeof($All_tickets);$x++)  // for($x=0;$x<sizeof($All_tickets)-1;$x++) for run in scheduler
    {
	$substring_array = explode("A02",$All_tickets[$x]);
	$substring_array1 = explode("A02",$All_tickets[$x]);
	 //echo "<pre>";var_dump($substring_array1);echo "</pre>";
$substring_array2 = explode(":",$substring_array1[1]);
//$substring_array2 = explode(" ",$substring_array2[0]);
$tkt_no = preg_replace('/\s+/', '', $substring_array2[0]);
//echo "<pre>";var_dump($tkt_no);echo "</pre>";
 $con=substr($tkt_no,-10);
 $con=substr($con,0,2);
	if(sizeof($substring_array)<3)
	{
	$single_ticket_status=1;
	//echo "Normal".$x."<br/>";
if($con=='02'){
	$Single_tickets[$x]=$All_tickets[$x];
	//$substring_ar = explode(":",$substring_array[1]);
	//echo "<pre>";var_dump($substring_ar);echo "</pre>";
	//$substring_ar = preg_replace('/\s+/', '', $substring_ar[0]);
	//$Single_tickets[0]=$substring_array[0]."A02".$substring_array[1];
	//$Single_tickets[1]=$substring_array[0]."A02".$substring_array[1];
	
}else{
	$Single_tickets[$x]=$All_tickets[$x];
}
	
	}
	else
	{
	$mutiple_ticket_status=1;
	
	//var_dump($substring_array[2]);
	$k=$x;
		for($s=1;$s<sizeof($substring_array);$s++)
		{
			$si=sizeof($substring_array)-1;
		//echo "I-00".$substring_array[$s];
if($s==$si){
$Multi_tickets[$k][$s]= $substring_array[0]."A02".$substring_array[$s];
		
}	else{
$Multi_tickets[$k][$s]= $substring_array[0]."A02".$substring_array[$s].substr($substring_array[$si],103,strlen($substring_array[$si]));
		
}	
			$k++;
		}
	
	}
}
// For Normal or Multiple tickets Re-arranging


// Joining arrays with single,multiple tickets  
if(($single_ticket_status==0) and ($mutiple_ticket_status==1) )
   {
	//if only multiple ticket 
	$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($Multi_tickets));
	$All_tickets = iterator_to_array($it, false);
}
else if(($single_ticket_status==1) and ($mutiple_ticket_status==1))
{
	//for joining multiple tickets and single ticket arrays
	$it =  new RecursiveIteratorIterator(new RecursiveArrayIterator($Multi_tickets));
	$Multi_tickets = iterator_to_array($it, false);
	$All_tickets = array_merge($Multi_tickets,$Single_tickets);
	$All_tickets = array_filter($All_tickets, 'strlen');
	$All_tickets = array_slice($All_tickets, 0);
	//echo '<pre>',print_r($All_tickets,1),'</pre>';
}
else if (($single_ticket_status==1) and ($mutiple_ticket_status==0) )
{
	//for only single tickets 
	$All_tickets = $Single_tickets;
}
// Joining arrays with single,multiple tickets

//Find find_aireline_code
function find_aireline_code2($find,$single_tickets)
{
	$file = $single_tickets;
	$substring_array = explode($find,$single_tickets);
	//$aireline_code = explode(';', $substring_array[1]); 
	$aireline_code = explode('-', $substring_array[1]); 
	//echo '<pre>',print_r($aireline_code,1),'</pre>'; 
	return $aireline_code[0];
}
//Find find_aireline_code

//Conjetion ticket with segments first 4 to first ticket no. and the remaining for the second 
for($z=0;$z<sizeof($All_tickets);$z++)
{
	$find = 'T-';
	$single_tickets = $All_tickets[$z];
	$substring_array = explode($find,$single_tickets);
	$tkt_no = explode(';', $substring_array[1]);  
	$checking_multiple_status = substr($tkt_no[0], 15, 1);
	if($checking_multiple_status == '-')
	{
	$conjetion_status = 1;
	$find = 'T-';
	$single_tickets = $All_tickets[$z];
	$substring_array = explode($find,$single_tickets);
	$tkt_no = explode(';', $substring_array[1]);  
	$tkt_no = explode('-', $substring_array[1]); 
	$a = substr($tkt_no[1], 0, 8);
	$b = substr($tkt_no[2], 0, 2);
	$generated_new_tkt = $a.$b;
	$air_line_code = find_aireline_code2('T-',$single_tickets); // for making new string with airline code
	$test = $substring_array[0]."T-".$air_line_code."-".$generated_new_tkt."-"."CONJUCTION"; 
	$total_segment = explode('H-',$test); 
	$make_ticket_string = $total_segment[0];
	for($b=0;$b<sizeof($total_segment);$b++)  // for adding remaining segment to the second ticket
	{
	if($b>=5)
	{
	$make_ticket_string .= 'H-'.$total_segment[$b];
	}
	}
	$Conjetion_array[$z] = $make_ticket_string;
	}
}
if($conjetion_status==1) // only, if conjetion ticket exists 
	{
		$All_tickets = array_merge($All_tickets,$Conjetion_array);
		$All_tickets = array_filter($All_tickets, 'strlen');
		$All_tickets = array_slice($All_tickets, 0);
}


// =  =========================== here end of making ticket files to array  ======================= 






$All_tickets_len = sizeof($All_tickets);
if($All_tickets[$All_tickets_len-1] == "")
	{
	$decduct = 1;
}
else
	{
	$decduct = 0;
}
?>

<?php
$f_data='<?php
function getAmadeusTickets()
{
     $Valid_Tkt_no   = array();
	 ';
	 for($i=0;$i<sizeof($All_tickets);$i++){
	 $f_data.='$Valid_Tkt_no[] ="'.$All_tickets[$i].'";';
	 }
$f_data.= 'return ($Valid_Tkt_no);
}
?>';

 ?>
<?php 

// Please change the file name as based on your requirement$date_time = $_REQUEST['date_time'];$file='tickets/ticket_result_' . $date_time . ".php";
$file='ticket_result.php';
file_put_contents($file, $f_data);
?>

<?php // Result Printing Ends Here ?