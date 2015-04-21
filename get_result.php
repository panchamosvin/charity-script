<?php

/******** orghunter api ********/

$fields_org = array(
	'user_key' => urlencode("24e4df8f59b250ce18489502f63221b8"),
	'searchTerm' => urlencode($_POST['str'])
	
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
/*
echo "<pre>";
print_r($data_arr_org);	*/

for($k=0;$k<sizeof($data_arr_org['data']);$k++){
	/*$charityname = $data_arr_org['data'][$k]->charityName;
	if( $charityname == $_REQUEST['str'] ){
		
	}*/
	$charityname=$data_arr_org['data'][$k]->charityName."<br>";		
	$city=$data_arr_org['data'][$k]->city."<br>";		
	$state=$data_arr_org['data'][$k]->state."<br>";		
	$zip=$data_arr_org['data'][$k]->zipCode."<br>";		
}

/********************  Wikipedia Search Api ***************/

$keywrd = ucwords(strtolower($_POST['str']));
$findme = "Inc";
$pos = strpos($keywrd, $findme);
if ($pos !== false) {
	$keywrd = str_replace("Inc","",$keywrd);
}

$fields = array(
	'action' => urlencode("query"),
	'list' => urlencode("search"),
	'srsearch' => urlencode($keywrd),
	'srprop' => urlencode("timestamp"),
	'format' => urlencode("json")	
);
foreach($fields as $key=>$value) { 
	$fields_string .= $key.'='.$value.'&'; 
}
rtrim($fields_string, '&');

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, "http://en.wikipedia.org/w/api.php?");
curl_setopt($ch,CURLOPT_POST, count($fields));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

$result = curl_exec($ch);
curl_close($ch);

$data_arr_org=array();
$data_arr_org=(array)json_decode($result);
$search_results = (array)$data_arr_org['query']->search;
for($i=0; $i<sizeof($search_results);$i++){
	$title = $search_results[$i]->title;
	
	$query = ucwords(strtolower($_POST['str']));
	$findme = "Inc";
	$pos = strpos($query,$findme);
	if ($pos !== false) {
		$query = str_replace("Inc", "", $query);
	}
	similar_text($title,$query,$percent);
	if($percent >= 70){
		$fields = array(
			'action' => urlencode("query"),
			'prop' => urlencode("extracts"),
			'titles' => urlencode($title),
			'format' => urlencode("json"),
			'exintro' => urlencode("1"),
			'exsentences' => urlencode("10")
		);
		foreach($fields as $key=>$value) { 
			$fields_string .= $key.'='.$value.'&'; 
		}
		rtrim($fields_string, '&');
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://en.wikipedia.org/w/api.php?");
		curl_setopt($ch,CURLOPT_POST, count($fields));
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // return the output in string format
		curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);

		$result = curl_exec($ch);
		curl_close($ch);

		$convert= json_decode($result);
		/* fetch key from array */
		$x= (array)$convert->query->pages;
		reset($x);
		$first_key = key($x);
		//echo $first_key; die;
		if($first_key != -1){		
			$res = $convert->query->pages->$first_key->extract;
			$data=array($charityname, $city, $state, $zip, $res);
			echo $convertintostr = implode(",",$data);	
		}
		else{
			$res = "nteeClass: International Development, Relief Services, nteeclass";
			$data=array($charityname, $city, $state, $zip, $res);	
			echo $convertintostr = implode(",",$data);	
		}
	}
	/*else{
			$res = "nteeClass: International Development, Relief Services, nteeclass";
			$data=array($charityname, $city, $state, $zip, $res);	
			echo $convertintostr = implode(",",$data);	
	}*/
}
?>



	


