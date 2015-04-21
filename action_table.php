<?php

$fields_org = array(
	'user_key' => urlencode("24e4df8f59b250ce18489502f63221b8"),
	'searchTerm' => urlencode($_POST['searchTerm'])
	
);
foreach($fields_org as $key=>$value) { 
	$fields_string_org .= $key.'='.$value.'&'; 
}
rtrim($fields_string_org, '&');
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://data.orghunter.com/v1/charitysearch?");
curl_setopt($ch,CURLOPT_POST, count($fields_org));
 curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string_org);

$org = curl_exec($ch);
curl_close($ch);


$data_arr_org=array();
$data_arr_org=(array)json_decode($org);

print_r(json_encode($data_arr_org));

?>
