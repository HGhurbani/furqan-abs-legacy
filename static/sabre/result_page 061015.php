<?php 
/*$All_tickets_division = "undefined?AA13MAY1306M0117               Y    000075242 21171 3  BMUMTF        611111100000010001 3VPF * NQ 63EE51            12MAY 16533VPF * NQ   00014MAYBOMMUMBAI           BOMMUMBAI           002002002002004001000000002000  01 210613MAY











22490109 EXT 103 NIYAZ -FAIRMONT TRAVEL SVCS KWI - A


NQ
M101QURESHI/FARZANA MRS                                                                                                     021 02000000  
0102
0103



M102QURESHI/SHWAANA MS                                                                                                      021 02000000  
0102
0204



M201ADT37602761606X 2   RST       INR   23410  36.300YQ   0.250YR  21.450XT KWD 169.000                             KWD 111.000       0     .000  111.000  F  58.000               BOM  N 3VPF A NQ 3VPF A NQ                      02019W92652435580
0102
01
CASH


NON-ENDO





M202ADT37602761702X 2   RST       INR   23410  36.300YQ   0.250YR  21.450XT KWD 169.000                             KWD 111.000       0     .000  111.000  F  58.000               BOM  N 3VPF A NQ 3VPF A NQ                      02019W92652435590
0102
01
CASH


NON-ENDO





M3011 0HK14MAYAIRNBOMMUMBAI           KWIKUWAIT           9W  574P 2355 0130  4.05       110                    73H001714      00TERMINAL 2 INTERNATIONAL                                         0                                     1002015MCEVFX  



M3021 0HK31MAYAIRNKWIKUWAIT           BOMMUMBAI           9W  573L 0025 0700  4.05       000                    73H001714      00                              TERMINAL 2 INTERNATIONAL           0                                     1002015MCEVFX  



M401ADT  14MAY14MAYOK40KP2SP3MIA       00196.121P2SP3MIA                              
M402ADTO 31MAY31MAYOK30KL2LRTIA        00178.671L2LRTIA                               
M50101  9W#9265243558/   .000/ 111.000/ 58.000/ONE/CA 1.1QURESHI FARZANA MRS/1/F/E
M50202  9W#9265243559/   .000/ 111.000/ 58.000/ONE/CA 2.1QURESHI SHWAANA MS/1/F/E
M50301  9W#9265243538/   .000/ 111.000/ 58.000/ONE/CA 1.1QURESHI FARZANA MRS/1/F/E
M50402  9W#9265243539/   .000/ 111.000/ 58.000/ONE/CA 2.1QURESHI SHWAANA MS/1/F/E
M6ADT1   BOM 9W KWI196.12 9W BOM178.67NUC374.79END ROE62.45902 XT2.000KW0.400GZ0.250YX1.150WO10.950JN3.500IN3.200YM
M901 9W     183545283            HK 9W           1.1  QURESHI/FARZANA MRS
M902 9W     183545331            HK 9W           2.1  QURESHI/SHWAANA MS

*** EOM ***
division";*/
$All_tickets_division=$_REQUEST['AllTkts'];


$All_tickets = explode("division",$All_tickets_division);

$Valid_Tkt_no = $All_tickets;

//RST COUNT
function find_rstcnt($single_tickets)
{

$file = $single_tickets;

if (strpos($single_tickets,' RSTU ') == false)
{

$result = preg_match_all('/\bRST\b/', $single_tickets, $out);

}
else
{
$result = preg_match_all('/\bRSTU\b/', $single_tickets, $out);
}

return $result;
}
//RST COUNT

//M50 COUNT
function find_M50cnt($single_tickets)
{
$file = $single_tickets;


$result = preg_match_all('/M50/', $single_tickets, $out);

return $result;
}
//M50 COUNT

//Find Status
function find_status($single_tickets)
{
$file = $single_tickets;
$identity = preg_replace( "/\s+/", " ", $file);
$nidentity = explode(" ",$identity);
$count = count($nidentity);

$substring_m50 = explode("M50",$single_tickets);
$m50_cnt = count($substring_m50) - 1;
$m50_space = explode(" ",$substring_m50[$m50_cnt]);
$m50_hash = explode("#",$m50_space[1]);
if($count <= "7")
{
$result = "Void";
}
elseif(strlen($m50_hash[0]) == 3 && substr($m50_hash[0], 0, 1) === 'A')
{
$result = "Re Issued";
}
else
{
$result = "Normal";
}

return $result;
}
//Find Status

//TiketNo,Name, etc details Multiple
function find_word_multi($find,$single_tickets,$j)
{
$file = $single_tickets;
$identity = preg_replace( "/\s+/", " ", $file);
$nidentity = explode(" ",$identity);
$count = count($nidentity);

$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode("M5",$single_tickets);

$substring_m50 = explode("M5",$single_tickets);
$m50_cnt = count($substring_m50) - 1;
//print_r($m50_cnt);
//print_r($substring_m50[$m50_cnt]);
$m50_space = explode(" ",$substring_m50[$m50_cnt]);

$m50_hash = explode("#",$m50_space[1]);
//echo('<pre>');print_r($m50_hash);echo('</pre>');
$m50_hash_chop = chop($m50_hash[1],"/");

if (strpos($single_tickets,' RSTU ') == false)
{
if(strlen($m50_hash[0]) == 3 && substr($m50_hash[0], 0, 1) === 'A')
{

//-------------array start count------------------//
if (strpos($single_tickets,' RSTU ') == false)
{
$rstct = preg_match_all('/\bRST\b/', $single_tickets, $out);
}
else
{
$rstct = preg_match_all('/\bRSTU\b/', $single_tickets, $out);
}

$arrstart = $m50_cnt - $rstct;
//$arrstart = $arrstart+1;
//-------------array start count------------------//

$m50_fisrt_tkt_space = explode(" ",$substring_m50[$j+$arrstart]);

$m50_fisrt_tkt_hash = explode("#",$m50_fisrt_tkt_space[1]);
//echo('<pre>');print_r($m50_hash);echo('</pre>');
$m50_fisrt_tkt = chop($m50_fisrt_tkt_hash[1],"/");

$m50_at = explode("@",$substring_m50[$j+$arrstart]);
$m50_at_hash = explode("/",$m50_at[1]);
$result = $m50_fisrt_tkt." (".$m50_at_hash[0].")";
}elseif(strlen($m50_hash[0]) == 3 && substr($m50_hash[0], 0, 1) === 'E')
{

$m50_fisrt_tkt_space = explode(" ",$substring_m50[$j+5]);

$m50_fisrt_tkt_hash = explode("-",$m50_fisrt_tkt_space[1]);
//echo('<pre>');print_r($m50_hash);echo('</pre>');
$m50_fisrt_tkt = chop($m50_fisrt_tkt_hash[1],"/");

$m50_at = explode("@",$substring_m50[$j+$arrstart]);
$m50_at_hash = explode("/",$m50_at[1]);
$result = $m50_fisrt_tkt." (".$m50_at_hash[0].")";
}
else
{


$substring_array_new = explode(" ",$substring_array[$j]);
$substring_array_newh = explode("#",$substring_array_new[1]);
$result = $substring_array_newh[1];

    }
}
else
{
$substring_array_new = explode(" ",$substring_array[$j]);
$substring_array_newh = explode("#",$substring_array_new[1]);
$result = $substring_array_newh[1];
}

$result = chop($result,"/");
return $result;

}
//TiketNo,Name, etc details Multiple

//TiketNo,Name, etc details
function find_word($find,$single_tickets)
{

$file = $single_tickets;

$identity = preg_replace( "/\s+/", " ", $file);
$nidentity = explode(" ",$identity);
$count = count($nidentity);

//$rstcnt = substr_count($single_tickets, 'RST');
if (strpos($single_tickets,' RSTU ') == false)
{
$rstcnt = preg_match_all('/\bRST\b/', $single_tickets, $out);
}
else
{
$rstcnt = preg_match_all('/\bRSTU\b/', $single_tickets, $out);
}

if($rstcnt >1)
{
$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);
$substring_array = explode($find,$single_tickets);



for($i=1;$i<=$rstcnt;$i++)
	{
	
if (strpos($single_tickets,' RSTU ') == false)
{

$substring_array_new = explode(" ",$substring_array[$i]);
$result[$i] = $substring_array_new[0];
}
else
{
$substring_array_new = explode(" ",$substring_array[$i]);
$result[$i] = $substring_array_new[0];
}
$result[$i] = chop($result[$i],"/");

	}


}

elseif($count <= "10")
{

$result[1] = substr($nidentity[1],3,10);

}
else
{

$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);
$substring_array = explode($find,$single_tickets);

$substring_m50 = explode("M50",$single_tickets);
$m50_cnt = count($substring_m50) - 1;
//print_r($substring_m50[$m50_cnt]);
$m50_space = explode(" ",$substring_m50[$m50_cnt]);

$m50_hash = explode("#",$m50_space[1]);
//echo('<pre>');print_r($m50_hash);echo('</pre>');
$m50_hash_chop = chop($m50_hash[1],"/");
if(strlen($m50_hash[0]) == 3 && substr($m50_hash[0], 0, 1) === 'A')
{
$m50_at = explode("@",$substring_m50[$m50_cnt]);
$m50_at_hash = explode("/",$m50_at[1]);
$result[1] = $m50_hash_chop." (".$m50_at_hash[0].")";
}elseif(strlen($m50_hash[0]) == 3 && substr($m50_hash[0], 0, 1) === 'E')
{
$m50_at = explode("-",$substring_m50[$m50_cnt]);
$m50_at_hash = explode("/",$m50_at[1]);
$result[1] = $m50_hash_chop." (".$m50_at_hash[0].")";
}
else
{

if (strpos($single_tickets,' RSTU ') == false)
{
$item=1;
/*code by ifthik*/
if(count($substring_array)>2){
//echo('<br />Count:'.count($substring_array));
$i=0;
foreach($substring_array as $row){
$newArr=explode('/',$row);
if(count($newArr)>2){
$item=$i;
}
$i++;
}
}
/*code by ifthik*/
$substring_array = explode(" ",$substring_array[$item]);

$result[1] = $substring_array[0];

}
else
{

$item=1;
/*code by ifthik*/
if(count($substring_array)>2){
//echo('<br />Count:'.count($substring_array));
$i=0;
foreach($substring_array as $row){
$newArr=explode('/',$row);
if(count($newArr)>2){
$item=$i;
}
$i++;
}
}
/*code by ifthik*/
$substring_array = explode(" ",$substring_array[$item]);
$result[1] = $substring_array[0];
}


	$result[1] = chop($result[1],"/");
	
}



$result[1] = str_replace("@","",$result[1]); 
}

//echo('Result:<pre>');print_r($result);echo('</pre>');
return $result;

}
//TiketNo,Name, etc details



// Issue Date
function find_dateisuued($single_tickets)
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode(" ",$single_tickets);
 $str=str_replace("undefinedAA","",$substring_array[0]);
 $str1=str_replace(",","",$str);
 $str2=substr($str1, 0, 5);
 if($str2!='#'){
 $result=$str2;
 }else{
 $result='';
 }
/*if($substring_array[1] =="Y" && !is_numeric($substring_array[1]))
{
$result = $substring_array[11];
}
else
{
$result = $substring_array[10];
}*/
//echo('</br>Date:'.$result);

return $result;
}
// Issue Date




// Issued By
function find_isuuedby($single_tickets)
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode(" ",$single_tickets);
if($substring_array[1] =="Y" && !is_numeric($substring_array[1]))
{
$result = $substring_array[9];
}
else
{
$result = $substring_array[8];
}
return $result;

}
// Issued By

// Booked By
function find_bookedby($single_tickets)
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode(" ",$single_tickets);
if($substring_array[1] =="Y" && !is_numeric($substring_array[1]))
{
$result = $substring_array[14];
}
else
{
$result = $substring_array[13];
}
return $result;

}
// Booked By

// PCC
function find_pcc($single_tickets)
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode(" ",$single_tickets);
if($substring_array[1] =="Y" && !is_numeric($substring_array[1]))
{
$result = $substring_array[7];
}
else
{
$result = $substring_array[6];
}
return $result;

}
// PCC


// Passenger Name Multi
function find_name_multi($single_tickets,$j)
{
$file = $single_tickets;

$single_tickets=preg_replace( "/\s+/", " ", $file);

$hash_cnt = preg_match_all('/#/', $single_tickets, $out);
$M50cnt = find_M50cnt($single_tickets);
if($hash_cnt > $M50cnt)
{
$substring_array = explode("M5",$single_tickets);
$substring_array_new = explode("/",$substring_array[$j]);
$substring_array = preg_split("~\d(?=\D*$)~", $substring_array_new[5]);
$result = $substring_array[1];
}

else
{
$substring_array = explode("#",$single_tickets);

/*echo("<pre>");
print_r($substring_array);
echo("</pre>");
*/
if (strpos($single_tickets,' RSTU ') == false)
{
$substring_array = explode("/",$substring_array[$j]);
$substring_array = preg_split("~\d(?=\D*$)~", $substring_array[5]);
$result = $substring_array[1];
}
else
{
$substring_array = explode("/",$substring_array[$j+1]);
$substring_array = preg_split("~\d(?=\D*$)~", $substring_array[5]);
$result = $substring_array[1];
}

}
return $result;
}
// Passenger Name Multi


// Passenger Name
function find_name($single_tickets)
{
$file = $single_tickets;

if (strpos($single_tickets,' RSTU ') == false)
{
$rstcnt = preg_match_all('/\bRST\b/', $single_tickets, $out);
}
else
{
$rstcnt = preg_match_all('/\bRSTU\b/', $single_tickets, $out);
}
if($rstcnt >1)
{
$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);
$substring_array = explode("#",$single_tickets);



for($i=1;$i<=$rstcnt;$i++)
	{
	
if (strpos($single_tickets,' RSTU ') == false)
{
$substring_array_new = explode("/",$substring_array[$i]);
$substring_array_new = preg_split("~\d(?=\D*$)~", $substring_array_new[5]);
$result[$i] = $substring_array_new[1];
}
else
{
$substring_array_new = explode("/",$substring_array[$i]);
$substring_array_new = preg_split("~\d(?=\D*$)~", $substring_array_new[5]);
$result[$i] = $substring_array_new[1];
}
$result[$i] = chop($result[$i],"/");

	}


}



else
		{
$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);
$substring_array = explode("#",$single_tickets);

if (strpos($single_tickets,' RSTU ') == false)
{
$substring_array = explode("/",$substring_array[1]);
$substring_array = preg_split("~\d(?=\D*$)~", $substring_array[5]);
$result[1] = $substring_array[1];
}
else
{
$substring_array = explode("/",$substring_array[2]);
$substring_array = preg_split("~\d(?=\D*$)~", $substring_array[5]);
$result[1] = $substring_array[1];
}

		}



return $result;
}
// Passenger Name


//Aircode Multi
function find_aircode_multi($single_tickets,$j)
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);

/*$M50cnt = find_M50cnt($single_tickets);

if($M50cnt > 1)
{
$m50split = explode("M50",$single_tickets);
$single_tickets = $m50split[$M50cnt];
}
*/
$hash_cnt = preg_match_all('/#/', $single_tickets, $out);
$M50cnt = find_M50cnt($single_tickets);
if($hash_cnt > $M50cnt)
{
$substring_array = explode("M5",$single_tickets);
$substring_array_new = explode(" ",$substring_array[$j]);
$substring_array_newh = explode("#",$substring_array_new[1]);
$result = $substring_array_newh[0];
}

else
{
$substring_array = explode("#",$single_tickets);



if($j == 1)
{
	if (strpos($single_tickets,' RSTU ') == false)
	{
	$substring_array = explode(" ",$substring_array[0]);
	
	$string = implode(" ",$substring_array);
	/*
	echo("<pre>");
print_r($string);
echo("</pre>");*/
	$result = substr($string, -2);
	}
	else
	{
	$substring_array = explode(" ",$substring_array[1]);
	$cnt_total = count($substring_array);
	$result = $substring_array[$cnt_total-1];
	}
}
else
{
	if (strpos($single_tickets,' RSTU ') == false)
	{
	
	$substring_array = explode(" ",$substring_array[$j-1]);
	$string = implode(" ",$substring_array);
	$result = substr($string, -2);
	}
	else
	{
	
	$substring_array = explode(" ",$substring_array[$j]);
	$cnt_total = count($substring_array);
	$result = $substring_array[$cnt_total-1];
	}
}
 }
return $result;

}
//Aircode Multi


//Aircode
function find_aircode($single_tickets)
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);

$M50cnt = find_M50cnt($single_tickets);

if($M50cnt > 1)
{
$m50split = explode("M50",$single_tickets);
$single_tickets = $m50split[$M50cnt];
}


$substring_array = explode("#",$single_tickets);
if (strpos($single_tickets,' RSTU ') == false)
{
$substring_array = explode(" ",$substring_array[0]);
$string = implode(" ",$substring_array);
$result = substr($string, -2);
}
else
{
$substring_array = explode(" ",$substring_array[1]);
$cnt_total = count($substring_array);
$result = $substring_array[$cnt_total-1];
}

return $result;

}
//Aircode


 
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

//For  PNR details   
function find_pnr($single_tickets)   //01-Apr-2014  Start 5.
{
$file = $single_tickets;
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode(" ",$single_tickets);

/*echo("<pre>");
print_r($substring_array);
echo("</pre>");*/

if($substring_array[1] =="Y" && !is_numeric($substring_array[1]))
{
$result = $substring_array[5];
}
else
{
$result = $substring_array[4];
}

return $result;

}  //01-Apr-2014  End 5.

//For PNR details

                               //01-Apr-2014  Start 6.
//RLOC - For Air PNR details
function find_code_airpnr($find,$single_tickets)
{
$find = $find;
$file = preg_replace( "/\s+/", " ", $single_tickets );
$startIndex=strrpos($file,'RLOC 1G');
$endIndex=strpos($file,'*****');
$strLength=$endIndex-$startIndex;
//$finalString=substr($file,$startIndex+7,$strLength-7);
$finalString=substr($file,$startIndex+7+7,10);
$substring_array = explode(' ',$finalString);
//return $substring_array[1];
return $substring_array[2];
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

//For Fare Details Multi
function base_fair_detail_multi($single_tickets,$num,$j)
{

$file = $single_tickets;

if (strpos($single_tickets,'/EMD ') == false)
{
$file = preg_replace('/KWD/', '$0 ', $file);

$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array_fst = explode(" RST",$single_tickets);




$substring_array = explode(" ",$substring_array_fst[$j]);

/*echo("<pre>");
print_r($substring_array_fst);
echo("</pre>");
*/
$cntcur = count($substring_array[1]);

if(strlen($substring_array[1]) =="3")
	{
	
	if($substring_array[1] == "KWD")
{
switch ($num) {
    case 1:
        $result = $substring_array[2];
        break;
    case 2:
        $result = $substring_array[3];
        break;
    case 3:
        $result = $substring_array[4];
        break;
	case 4:
        $result = $substring_array[5];
		
        break;
	case 5:
        $result = $substring_array[7];
        break;		
		}
		
}
else
{
if($substring_array[5] == "KWD")
{

switch ($num) {
    case 1:
        $result = $substring_array[8];
        break;
    case 2:
        $result = $substring_array[3];
        break;
    case 3:
        $result = $substring_array[4];
        break;
	case 4:
        $result = "0";
		break;
	case 5:
        $result = $substring_array[6];
        break;		
		}
}
else
{
switch ($num) {
    case 1:
        $result = $substring_array[9];
        break;
    case 2:
        $result = $substring_array[3];
        break;
    case 3:
        $result = $substring_array[4];
        break;
	case 4:
        $result = $substring_array[5];
		break;
	case 5:
        $result = $substring_array[7];
        break;		
		}
}

		
		
}
	}
	else
	{
	if($substring_array[2] == "KWD")
{
switch ($num) {
    case 1:
        $result = $substring_array[3];
        break;
    case 2:
        $result = $substring_array[4];
        break;
    case 3:
        $result = $substring_array[5];
        break;
	case 4:
        $result = $substring_array[6];
		
        break;
	case 5:
        $result = $substring_array[8];
        break;		
		}
		
}
else
{
if($substring_array[6] == "KWD")
{

switch ($num) {
    case 1:
        $result = $substring_array[9];
        break;
    case 2:
        $result = $substring_array[4];
        break;
    case 3:
        $result = $substring_array[5];
        break;
	case 4:
        $result = "0";
		break;
	case 5:
        $result = $substring_array[7];
        break;		
		}
}
else
{
switch ($num) {
    case 1:
        $result = $substring_array[10];
        break;
    case 2:
        $result = $substring_array[4];
        break;
    case 3:
        $result = $substring_array[5];
        break;
	case 4:
        $result = $substring_array[6];
		break;
	case 5:
        $result = $substring_array[8];
        break;		
		}
}

		
		
}
	}



}
else
{
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode("MG0",$single_tickets);
$substring_array_space = explode(" ",$substring_array[1]);

switch ($num) {
    case 1:
        $result[1] = $substring_array_space[5];
        break;
    case 2:
        $result[1] = "0";
        break;
    case 3:
        $result[1] = "0";
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = $substring_array_space[5];
        break;		
		}
}
	return $result;



}
//For Fare Details Multi

//For Fare Details
function base_fair_detail($single_tickets,$num)
{

$file = $single_tickets;
if (strpos($single_tickets,'/EMD ') == false)
{
$file = preg_replace('/KWD/', '$0 ', $file);

$single_tickets=preg_replace( "/\s+/", " ", $file);

//$substring_array = str_replace('  ','',$file);
$substring_array_fst = explode(" RST",$single_tickets);



if(strpos($single_tickets,'NO ADC') == true)
{
switch ($num) {
    case 1:
        $result[1] = "0";
        break;
    case 2:
        $result[1] = "0";
        break;
    case 3:
        $result[1] = "0";
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = "0";
        break;		
		}
}

else
{

//$rstcnt = substr_count($single_tickets, 'RST');
if (strpos($single_tickets,' RSTU ') == false)
{
$rstcnt = preg_match_all('/\bRST\b/', $single_tickets, $out);
}
else
{
$rstcnt = preg_match_all('/\bRSTU\b/', $single_tickets, $out);
}

if($rstcnt >1)
{

for($i=1;$i<=$rstcnt;$i++)
	{
	
	$substring_array = explode(" ",$substring_array_fst[$i]);
	//$result = array();
	//--------------------------------------------------//
	
	if(strlen($substring_array[1]) =="3")
	{
	if($substring_array[1] == "KWD")
{
switch ($num) {
    case 1:
        $result[$i] = $substring_array[2];
        break;
    case 2:
        $result[$i] = $substring_array[3];
        break;
    case 3:
        $result[$i] = $substring_array[4];
        break;
	case 4:
        $result[$i] = $substring_array[5];
		
        break;
	case 5:
        $result[$i] = $substring_array[7];
        break;		
		}
}
else
{
if($substring_array[5] == "KWD")
{
switch ($num) {
    case 1:
        $result[$i] = $substring_array[8];
        break;
    case 2:
        $result[$i] = $substring_array[3];
        break;
    case 3:
        $result[$i] = $substring_array[4];
        break;
	case 4:
        $result[$i] = "0";
		break;
	case 5:
        $result[$i] = $substring_array[6];
        break;		
		}
}
else
{
switch ($num) {
    case 1:
        $result[$i] = $substring_array[9];
        break;
    case 2:
        $result[$i] = $substring_array[3];
        break;
    case 3:
        $result[$i] = $substring_array[4];
        break;
	case 4:
        $result[$i] = $substring_array[5];
		break;
	case 5:
        $result[$i] = $substring_array[7];
        break;		
		}
}

		
		
}
	}
	else
	{
	if($substring_array[2] == "KWD")
{
switch ($num) {
    case 1:
        $result[$i] = $substring_array[3];
        break;
    case 2:
        $result[$i] = $substring_array[4];
        break;
    case 3:
        $result[$i] = $substring_array[5];
        break;
	case 4:
        $result[$i] = $substring_array[6];
		
        break;
	case 5:
        $result[$i] = $substring_array[8];
        break;		
		}
}
else
{
if($substring_array[6] == "KWD")
{
switch ($num) {
    case 1:
        $result[$i] = $substring_array[9];
        break;
    case 2:
        $result[$i] = $substring_array[4];
        break;
    case 3:
        $result[$i] = $substring_array[5];
        break;
	case 4:
        $result[$i] = "0";
		break;
	case 5:
        $result[$i] = $substring_array[7];
        break;		
		}
}
else
{
switch ($num) {
    case 1:
        $result[$i] = $substring_array[10];
        break;
    case 2:
        $result[$i] = $substring_array[4];
        break;
    case 3:
        $result[$i] = $substring_array[5];
        break;
	case 4:
        $result[$i] = $substring_array[6];
		break;
	case 5:
        $result[$i] = $substring_array[8];
        break;		
		}
}

		
		
}
	}
	
	

	//--------------------------------------------------//
	}
	
}

elseif($rstcnt ==0)
{

$M50cnt = find_M50cnt($single_tickets);

	if($M50cnt > 1)
	{
	$m50split = explode("M50",$single_tickets);
	$single_tickets = $m50split[$M50cnt];
	}
	$substring_array = explode("/",$single_tickets);
	
	
	switch ($num) {
    case 1:
        $result[1] = $substring_array[2];
        break;
    case 2:
        $result[1] = "0";
        break;
    case 3:
        $result[1] = "0";
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = $substring_array[2];
        break;		
		}

}


else
{

$substring_array = explode(" ",$substring_array_fst[1]);

/*echo("<pre>");
print_r($substring_array);
echo("</pre>");*/

$cntcur = count($substring_array[1]);

if(strlen($substring_array[1]) =="3")
	{
	
	if($substring_array[1] == "KWD")
{
switch ($num) {
    case 1:
        $result[1] = $substring_array[2];
        break;
    case 2:
        $result[1] = $substring_array[3];
        break;
    case 3:
        $result[1] = $substring_array[4];
        break;
	case 4:
        $result[1] = $substring_array[5];
		
        break;
	case 5:
        $result[1] = $substring_array[7];
        break;		
		}
		
}
else
{
if($substring_array[5] == "KWD")
{

switch ($num) {
    case 1:
        $result[1] = $substring_array[8];
        break;
    case 2:
        $result[1] = $substring_array[3];
        break;
    case 3:
        $result[1] = $substring_array[4];
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = $substring_array[6];
        break;		
		}
}
elseif($substring_array[4] == "KWD")
{
switch ($num) {
    case 1:
        $result[1] = $substring_array[7];
        break;
    case 2:
        $result[1] = $substring_array[3];
        break;
    case 3:
        $result[1] = "0";
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = $substring_array[5];
        break;		
		}
}
else
{

switch ($num) {
    case 1:
        $result[1] = $substring_array[9];
        break;
    case 2:
        $result[1] = $substring_array[3];
        break;
    case 3:
        $result[1] = $substring_array[4];
        break;
	case 4:
        $result[1] = $substring_array[5];
		break;
	case 5:
        $result[1] = $substring_array[7];
        break;		
		}
}

		
		
}
	}
	else
	{
	if($substring_array[2] == "KWD")
{
switch ($num) {
    case 1:
        $result[1] = $substring_array[3];
        break;
    case 2:
        $result[1] = $substring_array[4];
        break;
    case 3:
        $result[1] = $substring_array[5];
        break;
	case 4:
        $result[1] = $substring_array[6];
		
        break;
	case 5:
        $result[1] = $substring_array[8];
        break;		
		}
		
}
else
{
if($substring_array[6] == "KWD")
{

switch ($num) {
    case 1:
        $result[1] = $substring_array[9];
        break;
    case 2:
        $result[1] = $substring_array[4];
        break;
    case 3:
        $result[1] = $substring_array[5];
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = $substring_array[7];
        break;		
		}
}
else
{
switch ($num) {
    case 1:
        $result[1] = $substring_array[10];
        break;
    case 2:
        $result[1] = $substring_array[4];
        break;
    case 3:
        $result[1] = $substring_array[5];
        break;
	case 4:
        $result[1] = $substring_array[6];
		break;
	case 5:
        $result[1] = $substring_array[8];
        break;		
		}
}

		
		
}
	}




	}

}
}
else
{
$single_tickets=preg_replace( "/\s+/", " ", $file);
$substring_array = explode("MG0",$single_tickets);
$substring_array_space = explode(" ",$substring_array[1]);

switch ($num) {
    case 1:
        $result[1] = $substring_array_space[5];
        break;
    case 2:
        $result[1] = "0";
        break;
    case 3:
        $result[1] = "0";
        break;
	case 4:
        $result[1] = "0";
		break;
	case 5:
        $result[1] = $substring_array_space[5];
        break;		
		}

}
	return $result;



}
//For Fare Details
?>

<?php 

//For Fare Details 
function find_segment($single_tickets)
{


$file = $single_tickets;
//$single_tickets= $file;
$single_tickets=preg_replace( "/\s+/", " ", $file);
//$substring_array = str_replace('  ','',$file);


for($i=1;$i<=6;$i++)
{
$dyn = 'M30'.$i.'1';
$fnd = strpos($single_tickets,$dyn);
if($fnd != false)
		{
		$substring_array = explode('M30'.$i.'1',$single_tickets);
		/*echo("<pre>");
		print_r($substring_array[1]);
		echo("</pre>");*/
		$substring_array = explode(" ",$substring_array[1]);
		$segment_array = array();
		
		$start = 'M30'.$i.'1';
		$nxti = $i + 1;
		$pos = array_search('M30'.$nxti.'1',$substring_array);
		
		if($pos == "")
		{
		
		
		$source=substr($substring_array[1],12,3);
		
		//$destination=substr($substring_array[2],0,3);
		
		$m6cnt = substr_count($single_tickets, 'M6'); // 2
		$start_sp = "M6";
		$end_sp = "NUC";
		 $r = explode($start_sp, $single_tickets);
		if (isset($r[1])){
			$r = explode($end_sp, $r[$m6cnt]);
			$dst_space = explode(" ",$r[0]);
			$cnt_dst = count($dst_space);
			}
			$destination_full=$dst_space[$cnt_dst-1];
			$destination = substr($destination_full,0,3);
		
		$travel_date =substr($substring_array[1],3,5);
		$status =substr($substring_array[1],1,2);
		$carrier =$substring_array[3];
		
		/*if(strlen($substring_array[4]) == 4)
		{
		$flight_no =substr($substring_array[4],0,3);
		$class =substr($substring_array[4],3,1);
		}
		else
		{
		$flight_no =substr($substring_array[4],0,4);
		$class =substr($substring_array[4],4,1);
		}*/
		
		$len = strlen($substring_array[4]);
		$lenn = $len - 1;
		$flight_no =substr($substring_array[4],0,$lenn);
		$class =substr($substring_array[4],-1);
		
		$time1 =$substring_array[5];
		$time2 =$substring_array[6];
		
		if(strlen($carrier) > 2)
		{
		
		$carrier =$substring_array[4];
		
		/*if(strlen($substring_array[5]) == 4)
		{
		$flight_no =substr($substring_array[5],0,3);
		$class =substr($substring_array[5],3,1);
		}
		else
		{
		$flight_no =substr($substring_array[5],0,4);
		$class =substr($substring_array[5],4,1);
		}*/
		
		$len = strlen($substring_array[5]);
		$lenn = $len - 1;
		$flight_no =substr($substring_array[5],0,$lenn);
		$class =substr($substring_array[5],-1);
		
		
		$time1 =$substring_array[6];
		$time2 =$substring_array[7];
		}
		
		if($source)
		{
		$segment_array[1] = $source."-".$destination."-".$travel_date."-".$carrier."-".$flight_no."-".$class."-".$time1."-".$status."-".$time2;
		}
		}
		else
		{
		
		$org_pos = $pos-1;
		$end = $substring_array[$org_pos];
		$end = substr($end, -10);
		$additional_details_string_start=strpos($file,'M30'.$i.'1');
		$additional_details_string_end=strripos($file,'M40');
		$additional_details_string_length=$additional_details_string_end-$additional_details_string_start;
		$additional_details_string=substr($file,$additional_details_string_start,$additional_details_string_length);
		//print_r($additional_details_string);
		
		for($k=1;$k<=6;$k++)
		{
		
		/*echo("<pre>");
		print_r($additional_details_string);
		echo("</pre>");*/
		$split = explode('M30'.$k.'1',$additional_details_string);
		
		$split=preg_replace( "/\s+/", " ", $split);
		$split = explode(" ",$split[1]);
		/*echo("<pre>");
		print_r($split);
		echo("</pre>");*/
		$source=substr($split[1],12,3);
		
		$j=$k+1;
		$splitdst = explode('M30'.$j.'1',$additional_details_string);
		/*echo("<pre>");
		print_r($splitdst);
		echo("</pre>");*/
		if($splitdst[1])
		{
		$splitdst=preg_replace( "/\s+/", " ", $splitdst);
		$splitdst = explode(" ",$splitdst[1]);
		$destination=substr($splitdst[1],12,3);
		}
		else
		{
		//$destination=substr($split[2],0,3);
		$m6cnt = substr_count($single_tickets, 'M6'); // 2
		$start_sp = "M6";
		$end_sp = "NUC";
		 $r = explode($start_sp, $single_tickets);
		if (isset($r[1])){
			$r = explode($end_sp, $r[$m6cnt]);
			$dst_space = explode(" ",$r[0]);
			$cnt_dst = count($dst_space);
			}
			$destination_full=$dst_space[$cnt_dst-1];
			$destination = substr($destination_full,0,3);
			
		}
		
		$travel_date =substr($split[1],3,5);
		$status =substr($split[1],1,2);
		$carrier =$split[3];
		/*if(strlen($split[4]) == 4)
		{
		$flight_no =substr($split[4],0,3);
		$class =substr($split[4],3,1);
		}
		else
		{
		$flight_no =substr($split[4],0,4);
		$class =substr($split[4],4,1);
		}*/
		
		
		$len = strlen($split[4]);
		$lenn = $len - 1;
		$flight_no =substr($split[4],0,$lenn);
		$class =substr($split[4],-1);
		
		
		$time1 =$split[5];
		$time2 =$split[6];
		//echo 'Carrier:'.$carrier;
		
		if(strlen($carrier) > 2)
		{
		$carrier =$split[4];
		
		if(strlen($carrier) > 2)
		{
		if(preg_match('/[0-9]/', $carrier))
		  	{
			$carrier =substr($split[3], -2);
		  	}
		else
			{
			$carrier =substr($split[4], -2);
			}   
		}
		
		
		
		/*if(strlen($split[5]) == 4)
		{
		$flight_no =substr($split[5],0,3);
		$class =substr($split[5],3,1);
		}
		else
		{
		$flight_no =substr($split[5],0,4);
		$class =substr($split[5],4,1);
		}*/
		
		$len = strlen($split[5]);
		$lenn = $len - 1;
		$flight_no =substr($split[5],0,$lenn);
		$class =substr($split[5],-1);
		
		$time1 =$split[6];
		$time2 =$split[7];
		}
		
		if($source)
		{
		$segment_array[$k] = $source."-".$destination."-".$travel_date."-".$carrier."-".$flight_no."-".$class."-".$time1."-".$status."-".$time2;
		}
		
		/*echo("<pre>");
		print_r($split[1]."-".$split[2]);
		echo("</pre>");*/
		}
		}
		
		}
if($fnd != false && $pos !="")
	{
	break;
	}
}



return $segment_array;

//print_r($segment_array);



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


$base_fare = trim($base_fare);


 //if (is_string($base_fare) == 'true' && !empty($base_fare))
 if (!preg_match('#[0-9]#',$base_fare) && !empty($base_fare))
{
//echo $file;
 $name = find_word('NAME',$single_tickets);


   $substring_array = explode("TKT: ",$file);
   $substring_array = explode(" ",$substring_array[1]);
   //echo '<pre>',print_r($substring_array),'</pre>';
   $ticket = $substring_array[1].$substring_array[2];
   
   
  $substring_array2 = explode($ticket,$file);
   $count = count($substring_array2);
   $arrcnt = $count-1;
   $substring_array2 = array_filter(array_map('trim', $substring_array2));
   $substring_array2 = explode(" ",$substring_array2[$arrcnt]);
   //echo '<pre>',print_r($substring_array2),'</pre>';
   
   if($substring_array2[1] == 'VOID')
   {
   $base_fare = 'VOID';
   }
   else
   {
   $base_fare = $substring_array2[2];
   $base_fare = $base_fare."KWD";
   }
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
$date_time = date(Y_d);
$file='Tickets/' . $date_time . ".php";
$fh = fopen($file, 'w');
$f_data = " <?php function getGdsTickets" . $date_time .  "() { \$Valid_Tkt_no   = '[";
fwrite($fh,$f_data);




for($k=0;$k<sizeof($Valid_Tkt_no)-1;$k++)
{
$tkt_type_chk = find_status($Valid_Tkt_no[$k]); 
if($tkt_type_chk!='Void'){
$rstcnt = find_rstcnt($Valid_Tkt_no[$k]);
$rstorg = find_rstcnt($Valid_Tkt_no[$k]);
if($rstcnt > 1)
{
$M50cnt = find_M50cnt($Valid_Tkt_no[$k]);

if($M50cnt > $rstcnt)
{
$rstcnt = $M50cnt;
}

for($j=1,$m=($M50cnt - $rstorg)+1;$j<=$rstcnt,$m<=$rstcnt;$j++,$m++)
{
if($k!=0 || $j>=2) { 
$f_data = ","; 
fwrite($fh,$f_data); }
$f_data = "{" ;
  fwrite($fh,$f_data);
?>

<?php 
$ticket_no = find_word_multi('#',$Valid_Tkt_no[$k],$m);
$ticket_no = explode("(", $ticket_no);
$ticket_no = $ticket_no['0'];
$f_data ='"tkt_no":"000'. $ticket_no . '"';
fwrite($fh,$f_data); 
?>

<?php 
$issued_date = find_dateisuued($Valid_Tkt_no[$k]);
$f_data =',"dateofissue":"' . $issued_date.date("y"). '"';
fwrite($fh,$f_data); 
?>
<?php $tkt_type = find_status($Valid_Tkt_no[$k]); 
$f_data =',"tkt_type":"' .$tkt_type. '"';
fwrite($fh,$f_data);
?> 
<?php $air_code = find_aircode_multi($Valid_Tkt_no[$k],$j);
$f_data =',"air_code":"' .$air_code. '"';
fwrite($fh,$f_data);
?>
<?php 
$ticket_no = find_word_multi('#',$Valid_Tkt_no[$k],$m);
$f_data =',"tkt_no":"000'. $ticket_no . '"';
fwrite($fh,$f_data); 
?>
<?php $pass_name = find_name_multi($Valid_Tkt_no[$k],$j); 
$f_data =',"pass_name":"' .$pass_name. '"';
fwrite($fh,$f_data);
?>
<?php $gds_airPNR = find_pnr($Valid_Tkt_no[$k]); 
$f_data =',"gds_pnr":"' .$gds_airPNR. '"';
fwrite($fh,$f_data);
?>  
<?php $air_airPNR = find_pnr($Valid_Tkt_no[$k]); 
$f_data =',"air_pnr":"' .$air_airPNR. '"';
fwrite($fh,$f_data);
?>
<?php $issued_by = find_isuuedby($Valid_Tkt_no[$k]); 
 $pcc = find_pcc($Valid_Tkt_no[$k]);
 $signon_id=$pcc.$issued_by;
$f_data =',"signon_id":"' .$signon_id. '"';
fwrite($fh,$f_data);
?> 
<?php $issued_by = find_isuuedby($Valid_Tkt_no[$k]); 
 $pcc = find_pcc($Valid_Tkt_no[$k]);
 $booked_id=$pcc.$issued_by;
$f_data =',"booked_id":"' .$signon_id. '"';
fwrite($fh,$f_data);
?> 
<?php $base_fare = base_fair_detail_multi($Valid_Tkt_no[$k],1,$j); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$base_fare = base_fair_detail_multi($Valid_Tkt_no[$k],5,$j);
}
$f_data =',"fare":"' .$base_fare. ' KWD"';
fwrite($fh,$f_data);
?>
<?php $total_fare = base_fair_detail_multi($Valid_Tkt_no[$k],5,$j); 
$f_data =',"total_fare":"' .$total_fare. ' KWD"';
fwrite($fh,$f_data);
?>   

<?php $tax_details = base_fair_detail_multi($Valid_Tkt_no[$k],2,$j); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$tax_details = '0';
}
$f_data =',"Taxes1":"' .$tax_details. '"';
fwrite($fh,$f_data);
?>

<?php $tax_details = base_fair_detail_multi($Valid_Tkt_no[$k],3,$j); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$tax_details = '0';
}
$f_data =',"Taxes2":"' .$tax_details. '"';
fwrite($fh,$f_data);
?>

<?php $tax_details = base_fair_detail_multi($Valid_Tkt_no[$k],4,$j); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$tax_details = '0';
}
$f_data =',"Taxes345":"' .$tax_details. '"';
fwrite($fh,$f_data);
?> 

 

<?php $segment = find_segment($Valid_Tkt_no[$k]);
reset($segment);
$first_key = key($segment);
$segcnt =$first_key + count($segment);
$l=0;
//fwrite($fh,$f_data);
for($i=$first_key;$i<$segcnt;$i++)
  {
  $substring_array_seg = explode("-",$segment[$i]);
 
  $f_data =',"Airline'.$l. '":"' . $substring_array_seg[3] .'"';
 fwrite($fh,$f_data);
 
  
  $f_data =',"FlightNumber'.$l. '":"' . $substring_array_seg[4] .'"';
 fwrite($fh,$f_data);
  $f_data =',"Class'.$l. '":"' . $substring_array_seg[5] .'"';
 fwrite($fh,$f_data);
  $f_data =',"DepDate'.$l. '":"' . $substring_array_seg[2] .'"';
 fwrite($fh,$f_data);
  $f_data =',"Cityorigin'.$l. '":"' . $substring_array_seg[0] .'"';
 fwrite($fh,$f_data);
  $f_data =',"CityDest'.$l. '":"' . $substring_array_seg[1] .'"';
 fwrite($fh,$f_data);
  $f_data =',"DepTime'.$l. '":"' . $substring_array_seg[6] .'"';
 fwrite($fh,$f_data);
 
 $f_data =',"status'.$l. '":"' . $substring_array_seg[7] .'"';
 fwrite($fh,$f_data);
 
 $f_data =',"ArrTime'.$l. '":"' . $substring_array_seg[8] .'"';
 fwrite($fh,$f_data);
 $l++;
  }
?>  
<?php

$f_data = "}"	;
  fwrite($fh,$f_data)
?>
<?php
}
}
else
{
if($k!=0) { 
$f_data = ","; 
fwrite($fh,$f_data); }
$f_data = "{" ;
  fwrite($fh,$f_data);
  
?>

<?php 
$ticket_no = find_word('#',$Valid_Tkt_no[$k]);
$ticket_no = explode("(", $ticket_no[1]);
$ticket_no = $ticket_no['0'];
$f_data ='"tkt_no":"000'. $ticket_no . '"';
fwrite($fh,$f_data); 
?>

<?php 
$issued_date = find_dateisuued($Valid_Tkt_no[$k]);
$f_data =',"dateofissue":"' . $issued_date.date("y") . '"';
fwrite($fh,$f_data); 
?>
<?php $tkt_type = find_status($Valid_Tkt_no[$k]); 
$f_data =',"tkt_type":"' .$tkt_type. '"';
fwrite($fh,$f_data);
?> 
<?php $air_code = find_aircode($Valid_Tkt_no[$k]); 
$f_data =',"air_code":"' .$air_code. '"';
fwrite($fh,$f_data);
?>
<?php

$ticket_no = find_word('#',$Valid_Tkt_no[$k]);
$f_data =',"tkt_no":"000'. $ticket_no[1] . '"';
fwrite($fh,$f_data); 
?>
<?php $pass_name = find_name($Valid_Tkt_no[$k]); 
$f_data =',"pass_name":"' .$pass_name[1]. '"';
fwrite($fh,$f_data);
?>
<?php $gds_airPNR = find_pnr($Valid_Tkt_no[$k]); 
$f_data =',"gds_pnr":"' .$gds_airPNR. '"';
fwrite($fh,$f_data);
?>
<?php $sir_airPNR = find_pnr($Valid_Tkt_no[$k]); 
$f_data =',"air_pnr":"' .$sir_airPNR. '"';
fwrite($fh,$f_data);
?>  

<?php $issued_by = find_isuuedby($Valid_Tkt_no[$k]); 
$pcc = find_pcc($Valid_Tkt_no[$k]);
$signon_id=$pcc.$issued_by;
$f_data =',"signon_id":"' .$signon_id. '"';
fwrite($fh,$f_data);
?> 
<?php $issued_by = find_isuuedby($Valid_Tkt_no[$k]); 
$pcc = find_pcc($Valid_Tkt_no[$k]);
$booked_id=$pcc.$issued_by;
$f_data =',"booked_id":"' .$booked_id. '"';
fwrite($fh,$f_data);
?>

<?php $base_fare = base_fair_detail($Valid_Tkt_no[$k],1); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$base_fare = base_fair_detail($Valid_Tkt_no[$k],5);
}
$f_data =',"fare":"' .$base_fare[1]. ' KWD"';
fwrite($fh,$f_data);
?>  
<?php $total_fare = base_fair_detail($Valid_Tkt_no[$k],5); 
$f_data =',"total_fare":"' .$total_fare[1]. ' KWD"';
fwrite($fh,$f_data);
?>


<?php $tax_details = base_fair_detail($Valid_Tkt_no[$k],2); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$tax_details[1] = '0';
}
$f_data =',"Taxes1":"' .$tax_details[1]. '"';
fwrite($fh,$f_data);
?>

<?php $tax_details = base_fair_detail($Valid_Tkt_no[$k],3); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$tax_details[1] = '0';
}
$f_data =',"Taxes2":"' .$tax_details[1]. '"';
fwrite($fh,$f_data);
?>

<?php $tax_details = base_fair_detail($Valid_Tkt_no[$k],4); 
$tkt_type = find_status($Valid_Tkt_no[$k]); 
if($tkt_type=='Re Issued'){
$tax_details[1] = '0';
}
$f_data =',"Taxes345":"' .$tax_details[1]. '"';
fwrite($fh,$f_data);
?> 

  

<?php $segment = find_segment($Valid_Tkt_no[$k]);
if($segment !="")
{
reset($segment);
$first_key = key($segment);
$segcnt =$first_key + count($segment);
$j=0;
//fwrite($fh,$f_data);
for($i=$first_key;$i<$segcnt;$i++)
  {
  
  $substring_array_seg = explode("-",$segment[$i]);
 
  $f_data =',"Airline'.$j. '":"' . $substring_array_seg[3] .'"';
 fwrite($fh,$f_data);
 
  
  $f_data =',"FlightNumber'.$j. '":"' . $substring_array_seg[4] .'"';
 fwrite($fh,$f_data);
  $f_data =',"Class'.$j. '":"' . $substring_array_seg[5] .'"';
 fwrite($fh,$f_data);
  $f_data =',"DepDate'.$j. '":"' . $substring_array_seg[2] .'"';
 fwrite($fh,$f_data);
  $f_data =',"Cityorigin'.$j. '":"' . $substring_array_seg[0] .'"';
 fwrite($fh,$f_data);
  $f_data =',"CityDest'.$j. '":"' . $substring_array_seg[1] .'"';
 fwrite($fh,$f_data);
  $f_data =',"DepTime'.$j. '":"' . $substring_array_seg[6] .'"';
 fwrite($fh,$f_data);
 $f_data =',"status'.$j. '":"' . $substring_array_seg[7] .'"';
 fwrite($fh,$f_data);
 $f_data =',"ArrTime'.$j. '":"' . $substring_array_seg[8] .'"';
 fwrite($fh,$f_data);
  $j++;}
  }
?> 

<?php
  $f_data = "}"	;
  fwrite($fh,$f_data)
?>

<?php } ?>



<?php } }  ?>

<?php

$f_data = "]';  return ( \$Valid_Tkt_no); } ?>";
fwrite($fh,$f_data);
$funname = basename($file);         // $file is set to "index.php"
$functn = basename($funname, ".php"); // $file is set to "index"
//   $file='tickets/' . date(mdy) . ".php";
//    file_put_contents($file, $f_data);
fclose($fh);

//$date_time = date(Y_d);
//$file='tickets/' . $date_time . ".php";

if( ! ini_get('date.timezone') )
{
    date_default_timezone_set('GMT');
}

?>

