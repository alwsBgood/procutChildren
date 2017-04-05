<?php

$recepient = "anastasiya.procut@gmail.com";
$sitename = "PROCUT KIDS";

$mail = trim($_GET["email"]);
$phone = trim($_GET["phone"]);
$country = trim($_GET["country"]);
$data_form= trim($_GET["data_form"]);
$name = trim($_GET["name"]);
$city = trim($_GET["city"]);
$additional_field = trim($_GET["entry_1778222684"]);
$utm_source= trim($_GET["utm_source"]);
$utm_campaign= trim($_GET["utm_campaign"]);
$utm_medium= trim($_GET["utm_medium"]);
$date_submitted= trim($_GET["date_submitted"]);
$time_submitted= trim($_GET["time_submitted"]);
$ip_address= $_SERVER["REMOTE_ADDR"];
$page_variant_name= trim($_GET["page_variant_name"]);
$page_uuid= trim($_GET["page_uuid"]);
$page_name= trim($_GET["page_name"]);
$page_url= trim($_GET["page_url"]);
$ref= trim($_GET["ref"]);
$src= trim($_GET["src"]);
$utm_term= trim($_GET["utm_term"]);
$utm_content= trim($_GET["utm_content"]);
$lead_name= trim($_GET["lead_name"]);
$lead_price= trim($_GET["lead_price"]);
$event_id= trim($_GET["event_id"]);
$landing_version= trim($_GET["landing_version"]);
$event_type= trim($_GET["event_type"]);
$event_subject= trim($_GET["event_subject"]);
$event_source= trim($_GET["event_source"]);
$event_motivation= trim($_GET["event_motivation"]);
$message_template_id= trim($_GET["message_template_id"]);
$product_id= trim($_GET["product_id"]);
$GA_client_id = $_COOKIE['_ga'];
$hmid = trim($_GET["hmid"]);
$invite_id = trim($_GET["invite_id"]);

$dblocation = "procutsc.mysql.ukraine.com.ua"; // Имя сервера
$dbuser = "procutsc_db";          // Имя пользователя
$dbpasswd = "FAXNGtDt";            // Пароль
$dbname = "procutsc_db";
$dbcnx = @mysql_connect($dblocation,$dbuser,$dbpasswd);

if (!$dbcnx) // Если дескриптор равен 0 соединение не установлено
{
  echo("<P>error</P>");

}
if (!@mysql_select_db($dbname, $dbcnx))
{
  echo( "<P>В настоящий момент база данных не доступна, поэтому
            корректное отображение страницы невозможно.</P>" );

}

$query = "INSERT INTO procut_leads set name='$name', phone='$phone', mail='$mail', utm_source='$utm_source', utm_campaign='$utm_campaign', utm_medium='$utm_medium', date_submitted=NOW(), time_submitted=CURTIME(), page_url='$page_url', ref='$ref', utm_term='$utm_term', utm_content='$utm_content', form_type='$data_form', ip_address='$ip_address', is_test=case when (lower(name) like '%test%' OR lower(name) like '%тест%' OR ip_address ='62.80.163.149') then 1 else 0 end";

/*$query = "UPDATE email_course SET name='$name', phone='$phone', mail='$mail', utm_source='$utm_source', utm_campaign='$utm_campaign', utm_medium='$utm_medium', date_submitted=NOW(), time_submitted='$time_submitted', page_url='$page_url', ref='$ref', utm_term='$utm_term', utm_content='$utm_content', lead_name='$lead_name', lead_price='$lead_price', city='$city', invite_id='$invite_id' WHERE hmid=$hmid";*/
mysql_query ("SET NAMES utf8");
mysql_query ( $query );

$pagetitle = $sitename;
$message = "
Имя: $name <br> 
Телефон: $phone <br>
E-mail: $mail <br>
Город: $city <br>
Форма заявки: $data_form <br>
Дополнительное поле: $additional_field <br>
utm_source: $utm_source <br>
utm_campaign: $utm_campaign<br>
utm_medium: $utm_medium<br> 
date_submitted: $date_submitted<br>
time_submitted: $time_submitted<br>
page_url: $page_url<br>
ref: $ref<br>
utm_term: $utm_term<br>
utm_content: $utm_content<br>
lead_name: $lead_name<br>
lead_price: $lead_price<br>
hmid: $hmid
";

// Для отправки HTML-письма должен быть установлен заголовок Content-type
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=urf-8' . "\r\n";

// Дополнительные заголовки
$headers .= 'From: procut.com.ua';


mail($recepient, $pagetitle, $message, $headers);



// SPUTNIK API
$first_name = $name;
$email = $mail;

$user = 'es@willcatchfire.com';
$password = 'wiLL789';
$subscribe_contact_url = 'https://esputnik.com.ua/api/v1/contact/subscribe';
$formType = 'Kids_article';

$json_value = array("name" => $name, "mail" => $mail, "phone" => $phone);

$json_contact_value = new stdClass();
$contact = new stdClass();
$contact->firstName = $first_name;
$contact->channels = array(array('type' => 'email', 'value' => $email), array('type'=>'sms', 'value' => $phone));
$groups = $formType;
$json_contact_value->contact = $contact;
$json_contact_value->groups = $groups;
$json_contact_value->formType = $formType;
send_request($subscribe_contact_url, $json_contact_value, $user, $password);
var_dump($contact);

function send_request($url, $json_value, $user, $password) {
$ch = curl_init('https://esputnik.com.ua/api/v1/contact/subscribe');
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($json_value));
curl_setopt($ch, CURLOPT_HEADER, 1);
curl_setopt($ch, CURLOPT_HTTPHEADER, array('Accept: application/json', 'Content-Type: application/json'));
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch,CURLOPT_USERPWD, $user.':'.$password);
curl_setopt($ch,CURLOPT_RETURNTRANSFER, 1);
$output = curl_exec($ch);
echo($output);
curl_close($ch);
}

// Отправка хука в Slack

$message_to_slack = "
*$pagetitle*
Имя: $name
Телефон: $phone
E-mail: $mail
Город: $city
page_url: $page_url
Форма заявки: $data_form
Дополнительное поле: $additional_field
date_submitted: $date_submitted
time_submitted: $time_submitted
lead_name: $lead_name
lead_price: $lead_price
ref: $ref
utm_source: $utm_source
utm_campaign: $utm_campaign
utm_medium: $utm_medium
utm_term: $utm_term
utm_content: $utm_content
-------------------------
";

$room = "leads";
$icon = ":glitch_crab:";
$data = "payload=" . json_encode(array(
        "channel"       =>  "#{$room}",
        "text"          =>  $message_to_slack,
        "icon_emoji"    =>  $icon
    ));
$url = "https://hooks.slack.com/services/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";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
$result = curl_exec($ch);
echo var_dump($result);
if($result === false)
{
    echo 'Curl error: ' . curl_error($ch);
}
curl_close($ch);
?>

