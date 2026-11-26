<?php
require_once(__DIR__.'/Logger.php');
$log = new KLogger ( "logs/log.txt" , KLogger::INFO );
//$log = KLogger::logger();
$log->LogInfo('======= Script Started =====');
// set_time_limit(128);
$endPoint['get_token'] = "http://financialapi.wbe.travel/api/GetToken";
$endPoint['get_invoice'] = 'http://financialapi.wbe.travel/api/GetInvoices';

   $token = getToken(); 
  echo $token;
$log->LogInfo('Token Generated : '.$token);
// $token = (getenv('ABS_TOKEN') ?: '');
function GetBetween($var1="",$var2="",$pool)
{
$temp1 = strpos($pool,$var1)+strlen($var1);
$result = substr($pool,$temp1,strlen($pool));
$dd=strpos($result,$var2);
if($dd == 0){
$dd = strlen($result);
}
return substr($result,0,$dd);
}
$invoice = getInvoice($token);
if($invoice)
	$log->LogInfo('Invoice data available');

$invoiceDate = '';
$jsonData = '[';
$invo = '';
foreach ($invoice as $key => $value) 
{
        $invoiceDate = str_replace('Z', '', str_replace('T', ' ',$value->InvoiceDate)); 
		$status = 'Invoiced';
		$voucherno=$value->Id;
		//$Remarks=$value->Remarks;
		$SellingFareTotal=$value->Amount;
		
		foreach ($value->PurchaseList as $purchase) 
		{
			$Currency=$purchase->Currency;
			$Rate=$purchase->ExchangeRate;
				foreach ($purchase->PurchaseDetailList as $pp) 
				{
				$RoomType=$pp->RoomType;
				$exp = explode(" ", $RoomType);


				$RoomType= $exp[0];
				//$RoomCount=$pp->UnitNo;
				$NetFareTotal=$pp->Amount;
				$Remarks=$pp->Description;
				}
		}
		$j=0; $un=0;
		foreach ($value->InvoiceDetailList as $inv) 
		{
			$id=$inv->Id;
			$Description=$inv->Description;
			/*$exp = explode(" ", $Description);

			foreach($exp as $k => $val)
			{
			  if($val == 'from')
				  $cid = $exp[$k+1];
			  if($val == 'to')
				  $cod = $exp[$k+1];
			}
			 $cid = str_replace('/', '-', substr($cid,0,10));
			$cod = str_replace('/', '-', substr($cod,0,10));
			 $cid=date('Y-m-d', strtotime($cid));
			 $cod=date('Y-m-d', strtotime($cod));
			$timeDiff = abs(strtotime($cid) - strtotime($cod));
			$numberDays = $timeDiff/86400;
			$Non=$numberDays;
			
			$NetFareTotal=$inv->Amount;
			$NetFarePerNight=$NetFareTotal/$Non;
			
			$RoomType= trim(GetBetween("Room Type:","Pax",$Description));
			*/
			$UnitNo=$inv->UnitNo;
			$un = $un + intval($UnitNo);
			/*$invo .='
				"id'.$j.'": "'.$id.'",
				"RoomType'.$j.'": "'.$RoomType.'",
				"UnitNo'.$j.'": "'.$UnitNo.'",
				"CIn'.$j.'": "'.$cid.'",
				"COut'.$j.'": "'.$cod.'",
				"Non'.$j.'": "'.$Non.'",
				"NetFareTotal'.$j.'": "'.$NetFareTotal.'",
				"NetFarePerNight'.$j.'": "'.$NetFarePerNight.'",';
				$j=$j+1; */
		}
		
		foreach ($value->BookingList as $book) 
		{
			
		
			$AgentConfirmationNumber=-$book->AgencyId;
			$HotelConfirmationNumber=-$book->RealSupplierId;
			$IssuedAgent=$book->AgencyName;
			$IssuedID=$book->AgentName;
			$PassengerName=$book->LeaderName;
			$CityName=$book->CityName;
			$HotelName=$book->ItemName;
			$CheckI=str_replace('Z', '', str_replace('T', ' ',$book->StartDate));
			$CheckO=str_replace('Z', '', str_replace('T', ' ',$book->EndDate));
			$CheckIn=date('Y-m-d', strtotime($CheckI));
			$CheckOut=date('Y-m-d', strtotime($CheckO));
			$timeDiff = abs(strtotime($CheckI) - strtotime($CheckO));

			$numberDays = $timeDiff/86400;
			$NumberOfNights=$numberDays;
			$Adult=$book->AdultNo;
			$Child=$book->ChildNo;
			$Services=$book->MealType;
			$vBookingId=$book->BookingRef;
			//$NetFarePerNight='';
			//$NetFareTotal='';
			//$SellingFarePerNight='';
			//$SellingFareTotal='';
			$HotelApiName=-$book->ProviderId;
			$AgencyId=-$book->AgencyId;
			$AgencyName=$book->AgencyName;
			$CountryName=$book->CountryName;
			$ProviderId=-$book->ProviderId;
			$ProviderCode=$book->ProviderCode;
			$ProviderName=$book->ProviderName;
			
		}
		$NetFarePerNight1= $NetFareTotal/$NumberOfNights;
		$NetFarePerNight= $NetFarePerNight1/$un;
		$SellingFarePerNight1= $SellingFareTotal/$NumberOfNights;
		$SellingFarePerNight= $SellingFarePerNight1/$un;
        // Append the next invoice's JSON data to all of the JSON data 
        $jsonData .='{"IssuedDate": "'.$invoiceDate.'",
				"Status": "'.$status.'",
				"VoucherNumber": "'.$voucherno.'",
				"vBookingId": "'.$vBookingId.'",
				"AgencyId": "'.$AgencyId.'",
				"AgencyName": "'.$AgencyName.'",
				"ProviderId": "'.$ProviderId.'",
				"ProviderCode": "'.$ProviderCode.'",
				"ProviderName": "'.$ProviderName.'",
				"Remarks": "'.$Remarks.'",
				"Currency": "'.$Currency.'",
				"Rate": "'.$Rate.'",
				"RoomType": "'.substr($RoomType,0,8).'",
				"RoomCount": "'.$un.'",
				"AgentConfirmationNumber": "'.$AgentConfirmationNumber.'",
				"HotelConfirmationNumber": "'.$HotelConfirmationNumber.'",
				"IssuedAgent": "'.$IssuedAgent.'",
				"IssuedID": "'.$IssuedID.'",
				"PassengerName": "'.$PassengerName.'",
				"CityName": "'.$CityName.'",
				"HotelName": "'.$HotelName.'",
				"CheckIn": "'.$CheckI.'",
				"CheckOut": "'.$CheckO.'",
				"NumberOfNights": "'.$NumberOfNights.'",
				"Adult": "'.$Adult.'",
				"Child": "'.$Child.'",
				"Services": "'.$Services.'",
				"HotelApiName": "'.$HotelApiName.'",
				"NetFareTotal": "'.$NetFareTotal.'",
				"SellingFarePerNight": "'.number_format($SellingFarePerNight,3).'",
				"SellingFareTotal": "'.$SellingFareTotal.'",
				"NetFarePerNight": "'.$NetFarePerNight.'",
				"CountryName": "'.$CountryName.'"
				},'; 

 }

 // Clean up and close off the JSON data:
 $jsonData = rtrim($jsonData, ','); // remove the trailing comma
 $jsonData .= ']'; // Add the closing bracket.
//var_dump($jsonData);
 echo '<pre>';
 print_r($invoice);
 echo '</pre>';

$functionStart = '<?php function getHotelTickets(){$Valid_Tkt_no =\''; 
$functionEnd = '\'; return ($Valid_Tkt_no);} ?>'; 

$myfile = fopen("newfile.php", "w") or die("Unable to open file!");
$txt = $functionStart.$jsonData.$functionEnd;
fwrite($myfile, $txt);
fclose($myfile);
$admin_email = "balu@abskw.com";
			$email = "sys@abskw.com";
			$subject = "File Generated : ".date('Y-m-d H:i:s');
			$comment = "Current Date:".date('Y-m-d');
//mail($admin_email, "$subject", $comment, "From:" . $email);
$log->LogInfo('======= Script End =====');
function getToken()
{
	global $endPoint;
	$headers = array();
	$headers[] = 'Username: etsapi';
	$headers[] = 'Password: UhzjzPAj';
	$headers[] = 'AccountCode: ETS';
	$result = get($endPoint['get_token'], $headers);
	$json = (json_decode($result));
	return $json->Token;
}

function getInvoice($token)
{
	global $endPoint;
	$headers = array();
	$headers[] = "Token: $token";
	$headers[] = 'AccountCode: ETS';
	$headers[] = 'Content-Type: application/x-www-form-urlencoded';

	// $headers[] = 'Accept: */*';
	// $headers[] = 'Accept-Encoding: gzip, deflate';
	// $headers[] = 'Accept-Language: en-US,en;q=0.8';
	date_default_timezone_set('Asia/Kuwait');

	$EndDate = strtotime(date('Y-m-d'));
	$StartDate = strtotime(date('Y-m-d'));
	// dyanamic 
	$params["StartDate"] = date('Y-m-d H:i:s', $StartDate); // Dyanamic start date
	$params["EndDate"] = date('Y-m-d H:i:s', $EndDate);// dyanamic  end date
	
	//static
	
	//$params["StartDate"] = "2015-12-21 00:00:00";
	//$params["EndDate"] = "2015-12-17 00:00:00";
	$params["RetrieveBookingInfo"] = "true";

	$result = post($endPoint['get_invoice'], $headers, $params);
	$json = (json_decode($result));
	return $json;
}
 

function get($url, $headers)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

 //    curl_setopt($ch, CURLOPT_HEADER, 1);
	// curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
	curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 200 );
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);

    $data = curl_exec($ch);
    
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if($errno = curl_errno($ch)) {
        $error_message = curl_strerror($errno);
        echo "cURL error ({$errno}):\n {$error_message}";
    }

    curl_close($ch);
    if ($status == 200) {
        return $data;
    } else {
        return $status;
    }
}

function post($url, $headers, $params)
{
	foreach ( $params as $key => $value) 
	{
	    $post_items[] = $key . '=' . $value;
	}
	$post_string = implode ('&', $post_items);

	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.80 Safari/537.36');
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

    // curl_setopt($ch, CURLOPT_HEADER, 1);
	curl_setopt($ch, CURLINFO_HEADER_OUT, true);

    // curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_IPRESOLVE, CURL_IPRESOLVE_V4 );
    curl_setopt($ch, CURLOPT_DNS_USE_GLOBAL_CACHE, false );
	curl_setopt($ch, CURLOPT_DNS_CACHE_TIMEOUT, 200 );
	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);


	curl_setopt($ch, CURLOPT_NOPROGRESS, false);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);

	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post_string);

	$data = curl_exec($ch);
	// echo '<pre> Data';
 //    print_r($data);
 //    echo '</pre>';
	// echo '<pre>';
 //    print_r(curl_getinfo($ch));
 //    echo '</pre>';
    $status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    $errno = curl_errno($ch);
    if($errno) {
        $error_message = curl_strerror($errno);
        echo "cURL error ({$errno}):\n {$error_message}";
    }

	curl_close($ch);
	// echo '<pre> Data';
 //    print_r($data);
 //    echo '</pre>';
    if ($status == 200) {
        return $data;
    } else {
        return $status;
    }
}
?>