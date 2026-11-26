<?php 
//data as array form from Galileo
$All_tickets_division = $_REQUEST['All_tickets'];
$All_tickets = explode("division",$All_tickets_division);

//echo '<pre>';
//print_r($All_tickets);
//echo '</pre>';

//Retriving Valid or Generated Tickets
for($i=0,$j=0;$i<sizeof($All_tickets);$i++)
{
$check_valid = explode(" ",$All_tickets[$i]);
if($check_valid[0]!="UNABLE")
{
$Valid_Tkt_no[$j] = $All_tickets[$i];
$j++;
}
}

//Removing empty space from the result 
$Valid_Tkt_no = array_filter($Valid_Tkt_no);

// Extracting Conjuction Tickets to a New Array
$TktArraySize = sizeof($Valid_Tkt_no);
$i=0;
$y = 0;    // 01-Apr-2014  1.
for($k=0;$k<sizeof($Valid_Tkt_no);$k++)
{
$TKT_no = find_word('TKT',$Valid_Tkt_no[$k]);
if (strpos($TKT_no, '-') !== false)
{
$y = 1; //01-Apr-2014  2.
$secondTKT = explode("-",$TKT_no);

$New_TKT_no = substr($TKT_no, -7);
$AdditionalTKTs[$i] = str_replace($New_TKT_no,$secondTKT[1],$Valid_Tkt_no[$k]);
$i++;
}
}

// Removing Conjuction Segment From Second Ticket
for($s=0;$s<sizeof($AdditionalTKTs);$s++)
{
$TKT_no = find_word('TKT',$AdditionalTKTs[$s]);
$conj_num = substr($TKT_no, -3);
$file = $AdditionalTKTs[$s];
$startIndex=strrpos($file,'CPN');
$endIndex=strpos($file,'----'.$conj_num.'----');
$strLength=$endIndex-$startIndex;
$finalString=substr($file,$startIndex+6,$strLength-6);
$AdditionalTKTs[$s] = str_replace($finalString.'----'.$conj_num.'----','',$AdditionalTKTs[$s]);
$AdditionalTKTs[$s] = $AdditionalTKTs[$s]."CONJUCTION"; 
}

// New TicketDetails Array Wth Conjuction Details As Seperate Ticket    //01-Apr-2014  Start 3.
if($y==1)
{
$Valid_Tkt_no = array_merge($Valid_Tkt_no,$AdditionalTKTs);
}
else
{
$Valid_Tkt_no = $Valid_Tkt_no;
}

//01-Apr-2014  End 3.

// Removing Conjuction Segment From first Ticket
for($w=0;$w<sizeof($Valid_Tkt_no);$w++)
{
$TKT_no = find_word('TKT',$Valid_Tkt_no[$w]);
if (strpos($TKT_no, '-') !== false)
{ 
$conj_num = substr($TKT_no, -3);
$file = $Valid_Tkt_no[$w];
//echo '----'.$conj_num.'----';
$startIndex=strrpos($file,'----'.$conj_num.'----');
$endIndex=strpos($file,'FARE ');
$strLength=$endIndex-$startIndex;
$finalString=substr($file,$startIndex,$strLength);
$Valid_Tkt_no[$w] = str_replace($finalString,'',$Valid_Tkt_no[$w]);
}
}




//TiketNo,Name, etc details
function find_word($find,$single_tickets)
{
$find = $find.':';
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);
$substring_array = explode($find,$single_tickets);
$substring_array = explode(" ",$substring_array[1]);
if($find=='TKT:'){
$result = $substring_array[0].$substring_array[1].$substring_array[2].$substring_array[3];
}
else{
$result = $substring_array[0].$substring_array[1];
}

return $result;

}
//TiketNo,Name, etc details
 
 
// For Reissued Tickets 
function find_Reissued($find,$single_tickets) 
{
if (strpos($single_tickets,$find) !== false)
{
$find = $find.':';
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode($find,$single_tickets);
$substring_array = explode("ORIGINAL ISSUE:",$substring_array[1]); 
$result = $substring_array[0];
}
else
{
$result = 1;
}
return $result;
}


function FindTicketType($single_tickets)
{
$ticketType =" ";
if (strpos($single_tickets, 'CONJUCTION') !== false)
{
$ticketType = 'Conjunction';
}
else if (strpos($single_tickets, 'EXCHANGED FOR') !== false) 
{
$ticketType = 'Reissued';
}  //01-Apr-2014  Start 4.
else if (strpos($single_tickets, 'RFND') !== false) 
{
$ticketType = 'Refund';
}  //01-Apr-2014  End 4.                                      
else
{

	$itanary_details = Segment_details('CPN',$single_tickets); 
	$ticket_type = $itanary_details[0];
	if($ticket_type=='VOID')
	{
	$ticketType = 'Void';
	}
	else
	{
	$ticketType = 'Normal';
	}
	
	}

return $ticketType;
}





//Aircode
function find_threedigit_aircode($find,$single_tickets)
{
$find = $find.':';
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);
$substring_array = explode($find,$single_tickets);
$substring_array = explode(" ",$substring_array[1]);
if($find=='TKT:'){
$result = $substring_array[0].$substring_array[1].$substring_array[2].$substring_array[3];
}
else{
$result = $substring_array[0].$substring_array[1];
}

$air_code  = array_map('intval', str_split($result));
$result = $air_code[0].$air_code[1].$air_code[2];
return $result;

}
//Aircode


//RLOC - For Gds PNR details   
function find_code_gds($find,$single_tickets)   //01-Apr-2014  Start 5.
{
$find = $find;
$file = preg_replace( "/\s+/", " ", $single_tickets );
$startIndex=strrpos($file,'RLOC 1G');
$endIndex=strpos($file,'*****');

if($endIndex1=="")
{
$endIndex = strlen($file);
}else { $endIndex = $endIndex1; }

$strLength=$endIndex-$startIndex;
$finalString=substr($file,$startIndex+7,$strLength-7);
$substring_array = explode(' ',$finalString);
	
$substring_array = array_filter($substring_array, 'strlen');
$substring_array = array_slice($substring_array, 0);
//echo '<pre>',print_r($substring_array,1),'</pre>';
$gds_pnr = $substring_array[0];

return $gds_pnr;

}  //01-Apr-2014  End 5.

//RLOC - For Gds PNR details

                               //01-Apr-2014  Start 6.
//RLOC - For Air PNR details
function find_code_airpnr($find,$single_tickets)
{
$find = $find;
$file = preg_replace( "/\s+/", " ", $single_tickets );
$startIndex=strrpos($file,'RLOC 1G');
$endIndex=strpos($file,'*****');

if($endIndex1=="")
{
$endIndex = strlen($file);
}else { $endIndex = $endIndex1; }

$strLength=$endIndex-$startIndex;
$finalString=substr($file,$startIndex+7,$strLength-7);
$substring_array = explode(' ',$finalString);
	
$substring_array = array_filter($substring_array, 'strlen');
$substring_array = array_slice($substring_array, 0);
//echo '<pre>',print_r($substring_array,1),'</pre>';
$air_pnr = $substring_array[0];

return $air_pnr;

}
//RLOC - For Air PNR details
                                //01-Apr-2014  End 6.
      
// For SignonId/ticketed
function find_SignonId($single_tickets)
{
$find = "RCVD";
$file=preg_replace( "/\s+/", " ", $single_tickets );
$startIndex = strpos($file,'TICKETING DATA HISTORY');
$endIndex = strpos($file,'AIR HISTORY');
$string_length=$endIndex-$startIndex;
$datas=substr($file,$startIndex,$string_length);
$subStartIndex = strrpos($datas,'RCVD');
$subEndIndex = strrpos($datas,'CRDT');
$sub_string_length=$subEndIndex-$subStartIndex;
$sub_datas=substr($datas,$subStartIndex,$sub_string_length);
$signonId = substr($sub_datas,-7);
return $signonId;
}
// For SignonId/ticketed

// For booked id
function find_BookedId($single_tickets)
{
$find = "RCVD";
$file=preg_replace( "/\s+/", " ", $single_tickets );
$startIndex = strpos($file,'AIR HISTORY');
$endIndex=strlen($file);
$stringLength=$endIndex-$startIndex;
$stringData=substr($file,$startIndex,$stringLength);
$subStartIndex = strrpos($stringData,'RCVD');
$subEndIndex = strrpos($stringData,'CRDT');
$sub_string_length=$subEndIndex-$subStartIndex;
$sub_datas=substr($stringData,$subStartIndex,$sub_string_length);
$signonId = substr($sub_datas,-7);
return $signonId;
}
// For booked id



 
//For Segment Table
function Segment_details_with_time($single_tickets)
{
$file=preg_replace( "/\s+/", " ", $single_tickets );
$substring_array = explode('CPN',$file);
$substring_array = explode("FARE",$substring_array[1]);
$substring_array = explode(" ",$substring_array[0]);
$substring_array = array_filter($substring_array);
$substring_array = array_slice($substring_array, 0);
// ok
$additional_details_string_start=strpos($file,'RLOC');
$additional_details_string_end=strpos($file,'** FILED FARE DATA EXISTS **');
$additional_details_string_length=$additional_details_string_end-$additional_details_string_start;
$additional_details_string=substr($file,$additional_details_string_start,$additional_details_string_length);
$additional_details_string_start1=strrpos($additional_details_string,'1.');
$additional_details_string_end1=strlen($additional_details_string);
$additional_details_string_length1=$additional_details_string_end1-$additional_details_string_start1;
$additional_details_string1=substr($additional_details_string,$additional_details_string_start1,$additional_details_string_length1);

$exp_vals= explode(' ',$additional_details_string1);
$exp_vals1= explode('.',$additional_details_string1);


for($m=1;$m<sizeof($exp_vals1);$m++){
$seg_temp_details= explode(' ',$exp_vals1[$m]);

for($n=1;$n<sizeof($seg_temp_details);$n++){
if($n==8){
$arraival_time[]=$seg_temp_details[8];
}
}
}

//Retriving Valid or Generated Tickets
$substring_array1 = array();
for($i=0,$j=0;$i<sizeof($substring_array);$i++)
{
$avl=strpos($substring_array[$i],'NVA');
$avl1=strpos($substring_array[$i],'NVB');
$avl2=strpos($substring_array[$i],'----');
if(($avl=== false)&&($avl1=== false)&&($avl2=== false))
{
$substring_array1[$j] = $substring_array[$i];
$j++;
}
}


if($substring_array1){
$substring_array=$substring_array1;
}
$m=0;
$j=0;
$segment_array = array();


for($i=0;$i<sizeof($substring_array);$i++)
{
if($j==10){
$j=0;
$m++;
}
if($j==8){
//echo $arraival_time[$m];
$segment_array[$m][$j]=($arraival_time[$m])?$arraival_time[$m]:'-';
}else{
$segment_array[$m][$j]=$substring_array[$i];
}
$j++;
}


return $segment_array; 
}
//For Segment Table

//For Segment Table
function Segment_details($find,$single_tickets)
{
//$file = $single_tickets;
$file=preg_replace( "/\s+/", " ", $single_tickets );
$substring_array = explode($find,$file);
$substring_array = explode("FARE",$substring_array[1]);
$substring_array = explode(" ",$substring_array[0]);
$substring_array = array_filter($substring_array);
$substring_array = array_slice($substring_array, 0);
//Retriving Valid or Generated Tickets
$substring_array1 = array();
for($i=0,$j=0;$i<sizeof($substring_array);$i++)
{
$avl=strpos($substring_array[$i],'NVA');
$avl1=strpos($substring_array[$i],'NVB');
if(($avl=== false)&&($avl1=== false))
{
$substring_array1[$j] = $substring_array[$i];
$j++;
}
}
if($substring_array1){
$substring_array=$substring_array1;
}



return $substring_array; 
}
?>




<?php 



//For Fare Details
function base_fair_detail($single_tickets,$ticket_no)
{
$file = preg_replace( "/\s+/", " ", $single_tickets );

if(strpos($file,'FARE ') !== false) 
{
   $substring_array = explode("FARE ",$file);
   $substring_array = explode(" ",$substring_array[2]);
   //echo '<pre>',print_r($substring_array,1),'</pre>';
   $base_fare = $substring_array[1].'  '.$substring_array[0];
   
   
   
   if(strpos($file,'EQUIV ') !== false) 
   {
   $substring_array = explode("EQUIV ",$file);
 
	 if($substring_array[2])
	 {
	   $substring_array = explode(" ",$substring_array[2]);
	 }
	 else
	 { 
	   $substring_array = explode(" ",$substring_array[1]);
	 }
  
	  //echo '<pre>',print_r($substring_array,1),'</pre>';
	  
	  $base_fare = $base_fare.'  '.$substring_array[1].' '.$substring_array[0];
	   //echo '<pre>',print_r($substring_array,1),'</pre>';
   } 
   
} 
else 
{

//if(strpos($substring_array,'FARE IT') !== false) 
//{
$total_fare = total_fair_detail_function($single_tickets,$ticket_no);
$tkt_tax1 = $tax_details = tax_details($single_tickets,1);
$tkt_tax2 = $tax_details = tax_details($single_tickets,2);
$tkt_tax3 = $tax_details = tax_details($single_tickets,3);
$total_tax = $tkt_tax1 + $tkt_tax2 + $tkt_tax3; 
$base_fare =$total_fare - $total_tax;
//}
}





return $base_fare;
}
//For Fare Details
?>

<?php 
//For Total Fare Details
function total_fair_detail_function($single_tickets,$ticket_no)
{
//$file = $single_tickets;
$file = preg_replace( "/\s+/", " ", $single_tickets );
//$substring_array = explode(" ",$file);
$substring_array1 = explode("TOTAL ",$file);
$substring_array = explode(" ",$substring_array1[1]);
//echo '<pre>',print_r($substring_array,1),'</pre>';
$base_fare = $substring_array[1];
if($base_fare == 'IT')
{
$ticket_no = "/".$ticket_no."/";
$faredetailsArray =  explode($ticket_no,$single_tickets);
$faredetailsArray =  explode('/',$faredetailsArray[1]);
$base_fare = $faredetailsArray[1];
//$base_fare = $faredetailsArray3[4];
}
return $base_fare;
}
//For Total Fare Details
?>


<?php 
//For Total Fare Details
function total_fair_detail($single_tickets,$ticket_no)
{
//$file = $single_tickets;
$file = preg_replace( "/\s+/", " ", $single_tickets );
//$substring_array = explode(" ",$file);
$substring_array1 = explode("TOTAL ",$file);
$substring_array = explode(" ",$substring_array1[1]);
//echo '<pre>',print_r($substring_array,1),'</pre>';
$base_fare = $substring_array[1].' '.$substring_array[0];
if($base_fare == 'IT')
{
$ticket_no = "/".$ticket_no."/";
$faredetailsArray =  explode($ticket_no,$single_tickets);
$faredetailsArray =  explode('/',$faredetailsArray[1]);
$base_fare = $faredetailsArray[1];
//$base_fare = $faredetailsArray3[4];
}
return $base_fare;
}
//For Total Fare Details
?>


<?php
//Tax Details
function tax_details($single_tickets,$no)
{
//$file = $single_tickets;
$file = preg_replace( "/\s+/", " ", $single_tickets );
$end_pos=strpos($file,'TOTAL ');
$start_pos=strpos($file,'TAX');
$length_needed=$end_pos-$start_pos;
$actual_string=substr($file,$start_pos,$length_needed);
$substring_array = explode("TAX",$actual_string);
$new_tax=$substring_array[$no];
$new_tax1= explode(" ",$new_tax);
return $new_tax1[1];
}
//Tax Details

//RemoveLine
function RemoveLine($string)
{ 
	if (strpos($string, '-') !== false)
	{
	$tkt_number = explode('-',$string);
	$result = $tkt_number[0];
	}
	else
	{
	$result = $string;
	}
	return $result;
}
//RemoveLine

?>



<?php // Ticketing Result Start Here ?>


<?php 


$date_time = $_REQUEST['date_time'];
$file='tickets/' . $date_time . ".php";
$fh = fopen($file, 'w');
$f_data = " <?php function getGdsTickets" . $date_time .  "() { \$Valid_Tkt_no   = '[";
fwrite($fh,$f_data);


for($k=0;$k<sizeof($Valid_Tkt_no);$k++)
{
//echo $Valid_Tkt_no[$k];

  if($i!=0) { $f_data = ","; fwrite($fh,$f_data); }
  $f_data = "{" ;
  fwrite($fh,$f_data)

?>

 <?php $ticket_no = find_word('TKT',$Valid_Tkt_no[$k]); 
 $ticket_no = RemoveLine($ticket_no);
 $f_data ='"tkt_no":"' . $ticket_no . '"';
  fwrite($fh,$f_data);
?>
<!--
<tr><td width="342">Location : </td>
<td width="605"><?php echo $location = find_word('PSEUDO',$Valid_Tkt_no[$k]); ?></td>
</tr>
-->
<?php
 $issued_date = find_word('ISSUED',$Valid_Tkt_no[$k]);
  $f_data =',"dateofissue":"' . $issued_date . '"';
  fwrite($fh,$f_data);

  ?>









    <?php // Start  19-March-2014 ?>
    <?php $ticket_type = FindTicketType($Valid_Tkt_no[$k]); 
  if($ticket_type=='LFTD') { 
    "Normal";
    $f_data =',"tkt_type":"Normal"';
    fwrite($fh,$f_data);
  }else {
    $ticket_type;
    $f_data =',"tkt_type":"' .$ticket_type . '"';
    fwrite($fh,$f_data);
  } 
  ?>

    <?php // End  19-March-2014 ?>

    <!--GDS (Galileo, Amadeus, Saber) : -->

      <?php $air_code = find_threedigit_aircode('TKT',$Valid_Tkt_no[$k]);
  $f_data =',"air_code":"' .$air_code. '"';
  fwrite($fh,$f_data);
  ?>

    <?php 
    // 3. Start - 20-Mar-2014 
    $ticket_no = find_word('TKT',$Valid_Tkt_no[$k]); 
  $reissued = find_Reissued('EXCHANGED FOR',$Valid_Tkt_no[$k]);
  $ticket_no = RemoveLine($ticket_no);
  if($reissued==1)
  {
    $ticket_no;
//    $reissued = '';
  }
  else
  {
    $ticket_no = $ticket_no.'  ('.$reissued.' ) ';
  }
  $ticket_no;
// change on 111714 angela
  $f_data =',"tkt_no":"' .$ticket_no. '"';
  fwrite($fh,$f_data);
  // 3. End - 20-Mar-2014 
  ?>


    <?php $pass_name = find_word('NAME',$Valid_Tkt_no[$k]);
  $f_data =',"pass_name":"' .$pass_name. '"';
  fwrite($fh,$f_data);

  ?>
    <!--Issued staff code and booked staff code : -->
    <?php $gds_airPNR = find_code_gds('RLOC',$Valid_Tkt_no[$k]);
  $f_data =',"gds_pnr":"' .$gds_airPNR . '"';
  fwrite($fh,$f_data);

  ?>
    <?php $gds_airPNR = find_code_airpnr('RLOC',$Valid_Tkt_no[$k]); 
  $f_data =',"air_pnr":"' .$gds_airPNR. '"';
  fwrite($fh,$f_data);
  ?>  
<?php $SignId = find_SignonId($Valid_Tkt_no[$k]); 
  $SignId = trim($SignId);
  $f_data =',"signon_id":"' .$SignId . '"';
  fwrite($fh,$f_data);

  ?>
    <?php $BookedId = find_BookedId($Valid_Tkt_no[$k]);
  $BookedId = trim($BookedId);
  $f_data =',"booked_id":"' .$BookedId. '"';
  fwrite($fh,$f_data);

  ?>
    <?php $base_fare = base_fair_detail($Valid_Tkt_no[$k],$ticket_no); 
  $f_data =',"fare":"' .$base_fare. '"';
  fwrite($fh,$f_data);

  ?>

    <?php $total_fare = total_fair_detail($Valid_Tkt_no[$k],$ticket_no);
  $f_data =',"total_fare":"' .$total_fare. '"';
  fwrite($fh,$f_data);


  ?>




    <?php  $tax_details = tax_details($Valid_Tkt_no[$k],1); 

  $f_data =',"Taxes1":"' .$tax_details . '"';
  fwrite($fh,$f_data);


  ?>


    <?php  $tax_details = tax_details($Valid_Tkt_no[$k],2); 
  $f_data =',"Taxes2":"' .$tax_details . '"';
  fwrite($fh,$f_data);



  $tax_details = tax_details($Valid_Tkt_no[$k],3);
  $f_data =',"Taxes345":"' .$tax_details . '"';
  fwrite($fh,$f_data);


$seg_data=Segment_details_with_time($Valid_Tkt_no[$k]);
if($seg_data){
for($i=0;$i<sizeof($seg_data);$i++){
//	echo('<tr>');
/*	for($j=0;$j<10;$j++){
	if($seg_data[$i][$j]=="LFTD")
		{
		echo('<td>Normal</td>');
		}
	else
		{
		
		echo('<td>'.$seg_data[$i][$j].' --- ' . $j . '</td>');
		
		}
	
	}
*/
/*
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][0] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][1] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][2] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][3] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][4] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][5] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][6] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][7] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][8] .'"'. '</td>';
  echo $f_data;
  $f_data = '<td>'.$i . '":"' .$seg_data[$i][9] .'"'. '</td>';
  echo $f_data;
*/

      $f_data =',"Airline'.$i. '":"' . $seg_data[$i][1] .'"';
      fwrite($fh,$f_data);
      $f_data =',"FlightNumber'.$i. '":"' . $seg_data[$i][2] .'"';
      fwrite($fh,$f_data);
      $f_data =',"Class'.$i. '":"' . $seg_data[$i][3] .'"';
      fwrite($fh,$f_data);
      $f_data =',"DepDate'.$i. '":"' . $seg_data[$i][4] .'"';
      fwrite($fh,$f_data);
      $f_data =',"Cityorigin'.$i. '":"' . substr($seg_data[$i][5],0,3) .'"';
      fwrite($fh,$f_data);
      $f_data =',"CityDest'.$i. '":"' . substr($seg_data[$i][5],3) .'"';
      fwrite($fh,$f_data);
      $f_data =',"DepTime'.$i. '":"' . $seg_data[$i][6] .'"';
      fwrite($fh,$f_data);
      $f_data =',"status'.$i. '":"' . $seg_data[$i][7] .'"';
      fwrite($fh,$f_data);
      $f_data =',"ArrTime'.$i. '":"' . $seg_data[$i][8] .'"';
      fwrite($fh,$f_data);

/*
$k= $i + 1;
      echo ',"Airline'.$k. '":"' . $seg_data[$i][1] .'"';
      echo ',"FlightNumber'.$k. '":"' . $seg_data[$i][2] .'"';
      echo ',"Class'.$k. '":"' . $seg_data[$i][3] .'"';
      echo ',"DepDate'.$k. '":"' . $seg_data[$i][4] .'"';
      echo ',"Cityorigin'.$k. '":"' . substr($seg_data[$i][5],0,3) .'"';
      echo ',"CityDest'.$k. '":"' . substr($seg_data[$i][5],3) .'"';
      echo ',"DepTime'.$k. '":"' . $seg_data[$i][6] .'"';
      echo ',"status'.$k. '":"' . $seg_data[$i][7] .'"';
      echo ',"ArrTime'.$k. '":"' . $seg_data[$i][8] .'"';
 */



//	echo('</tr>');
	}
}
?>


<?php // End 13-March-2014 ?>
</table>
</td></tr>
</table>

<?php
  $f_data = "}"	;
  fwrite($fh,$f_data)
?>
<?php }  ?>

<?php

$f_data = "]';  return ( \$Valid_Tkt_no); } ?>";
fwrite($fh,$f_data);
//   $file='tickets/' . date(mdy) . ".php";
//    file_put_contents($file, $f_data);
fclose($fh);

?>


<?php // Result Printing Ends Here ?>


<?php /*?><?php
$f_data='<?php
function getGDSTickets()
{
     $Valid_Tkt_no   = array();
	 ';
	 for($i=0;$i<sizeof($Valid_Tkt_no);$i++){
	 $f_data.='$Valid_Tkt_no[] ="'.$Valid_Tkt_no[$i].'";';
	 }
$f_data.= 'return ($Valid_Tkt_no);
}
?>';


// Please change the file name as based on your requirement
$file='writeto.php';
file_put_contents($file, $f_data?><?php */?>


	
<?php

// delete older files only in that day
$D_new_files_list = array();
foreach (glob("tickets/*.php") as $filename)
{
  $D_file_name_1 = explode("/", $filename);
  $D_file_name = explode(".", $D_file_name_1[1]);
  $D_file_expansion = explode("_",$D_file_name[0]);
  //echo $D_file_expansion[0] . "    " . $D_file_expansion[1] . "<br>";

  $f_l_date = $D_new_files_list[$D_file_expansion[0]];
  if($f_l_date == '') {  $D_new_files_list[$D_file_expansion[0]] = $D_file_expansion[1];  }
  else {
    if( $D_new_files_list[$D_file_expansion[0]] < $D_file_expansion[1] ){
      $D_new_files_list[$D_file_expansion[0]] =  $D_file_expansion[1];

    }
  }

}
// echo print_r($D_new_files_list);

foreach ($D_new_files_list as $key => $values) {

  foreach (glob("tickets/*.php") as $filename){
  $D_file_name_1 = explode("/", $filename);
  $D_file_name = explode(".", $D_file_name_1[1]);
  $D_file_expansion = explode("_",$D_file_name[0]);
   if($key == $D_file_expansion[0] && $values !=  $D_file_expansion[1]){ unlink($filename); }

  }
}



?>