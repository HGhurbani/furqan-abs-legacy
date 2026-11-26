<?php 
//data as array form from Amadeus
$All_tickets_division = $_REQUEST['AllTkts'];
$All_tickets = explode("division",$All_tickets_division);



// ============================ here making ticket files to array  ======================= 

$mutiple_ticket_status=0;
$single_ticket_status=0;
$conjetion_status = 0;

// For Normal or Multiple tickets Re-arranging
for($x=0;$x<sizeof($All_tickets);$x++)  // for($x=0;$x<sizeof($All_tickets)-1;$x++) for run in scheduler
    {
	$substring_array = explode("I-00",$All_tickets[$x]);
	if(sizeof($substring_array)<3)
	{
	$single_ticket_status=1;
	//echo "Normal".$x."<br/>";
	$Single_tickets[$x]=$All_tickets[$x];
	}
	else
	{
	$mutiple_ticket_status=1;
	//echo "Multi".$x."<br/>";
	$k=$x;
		for($s=1;$s<sizeof($substring_array);$s++)
		{
		//echo "I-00".$substring_array[$s]; 
		$Multi_tickets[$k][$s]= $substring_array[0]."I-00".$substring_array[$s];
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

// Please change the file name as based on your requirement
$file='ticket_result.php';
file_put_contents($file, $f_data);
?>

<?php // Result Printing Ends Here ?>