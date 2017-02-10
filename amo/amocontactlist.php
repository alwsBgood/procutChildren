<?PHP header("Content-Type: text/html; charset=utf-8");?>
<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
<?php

$mail = trim($_POST["entry_114323142"]);
$phone = trim($_POST["entry_865589441"]);
$name = trim($_POST["entry_1734074772"]);
$city = trim($_POST["city"]);
$country = trim($_POST["country"]);
$data_form = trim($_POST["data_form"]);
$additional_field = trim($_POST["entry_1778222684"]);
$utm_source= trim($_POST["utm_source"]);
$utm_campaign= trim($_POST["utm_campaign"]);
$utm_medium= trim($_POST["utm_medium"]);
$ip_address= $_SERVER["REMOTE_ADDR"];
$page_variant_name= trim($_POST["page_variant_name"]);
$page_uuid= trim($_POST["page_uuid"]);
$page_name= trim($_POST["page_name"]);
$page_url= trim($_POST["page_url"]);
$ref= trim($_POST["ref"]);
$src= trim($_POST["src"]);
$utm_term= trim($_POST["utm_term"]);
$utm_content= trim($_POST["utm_content"]);
$lead_name= trim($_POST["lead_name"]);
$lead_price= trim($_POST["lead_price"]);
$product_id= trim($_POST["product_id"]);
$GA_client_id = $_COOKIE['_ga'];
$parts = parse_url($page_url);
parse_str($parts['query'], $query);
$GA_invite_id = $query['invite_id'];

#Special data for current page
$city_event = 'Kiev'; #City of current event
$time_submitted = strftime('%T'); #Current time for lead discription
$date_submitted = strftime('%m.%d.%Y'); #Current date for lead discription
$month_lead = strftime('%B'); #Current month for lead name
$year_lead = strftime('%Y'); #Current year for lead name
$price_lead = '625'; #Current year for lead name
if (empty($name)) {
	$name = 'Имя не указано ' . $time_submitted;
}



require 'amoinit.php';

$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list';
$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
 
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);

$Response=json_decode($out, true);
$Response=$Response['response']['contacts'];
$response_arr =$Response;

$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/contacts/list?limit_rows=500&limit_offset=500';

$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);
 
$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);
curl_close($curl);

$Response=json_decode($out, true);
$Response=$Response['response']['contacts'];

$final_arr = array_merge($Response, $response_arr );

$account_id = "1";
 
$mail_query=false;
$phone_query=false;
foreach($final_arr as $v) {
	if ($v['custom_fields'][1]['values'][0]['value'] == $mail) {
		$mail_query = true;
		$account_id = $v['id'];
	}
}
foreach($final_arr as $v) {
	if ($v['custom_fields'][0]['values'][0]['value'] == $phone) {
		$phone_query = true;
		$account_id = $v['id'];
	}
}


if(!$phone_query && !$mail_query){
	// совпадений нет, добавляем новый лид+контакт
	include  'amolead.php'; 
	include  'amoadd.php'; 
}
else {
	// mail или phone совпадаeт
	if ($phone_query && empty($mail)) {
		// Телефон совпадает, Mail не заполнен
		include  'amolead.php'; 
	}
	if (!$phone_query && empty($mail)) {
		// Телефон не совпадает, Mail не заполнен
		include  'amolead.php';
		include  'amoadd.php'; 
	}
		if ($phone_query && !$mail_query) {
			// Телефон совпадает, Mail не совпадает
			include  'amolead.php'; 
			include  'amocontact_mail.php';  
		}
		if ($mail_query && !$phone_query) {
			// Mail совпадает, Телефон не совпадает
			include  'amolead.php'; 
			include  'amocontact_phone.php';
		}
		if ($phone_query && $mail_query) {
			// Mail и phone совпадают
			include  'amolead.php'; 
			include  'amolead_exist.php'; 
	}
}

?>