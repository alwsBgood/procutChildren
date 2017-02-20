<?php


$leads['request']['leads']['add']=array(
  array(
    'name'=>'МК Дети ' .$city_event ,
    //'date_create'=>1298904164, //optional
    'price'=>$price_lead,
    'custom_fields'=>array(
      array(
        #Город проведения
        'id'=>1316554, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$city_event
          )
        )
      ),
      array(
        #Город регистрации
        'id'=>1419758, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$city
          )
        )
      ),
      array(
        #page_url
        'id'=>1419760, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$page_url
          )
        )
      ),
      array(
        #Форма
        'id'=>1419762, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$data_form
          )
        )
      ),
      array(
        #Дополнительное поле
        'id'=>1424371, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$additional_field
          )
        )
      ),
      array(
        #date_submitted
        'id'=>1419766, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$date_submitted
          )
        )
      ),
      array(
        #time_submitted
        'id'=>1419768, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$time_submitted
          )
        )
      ),
      array(
        #ref
        'id'=>1419774, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$ref
          )
        )
      ),
      array(
        #utm_source
        'id'=>1419776, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$utm_source
          )
        )
      ),
      array(
        #utm_campaign
        'id'=>1419790, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$utm_campaign
          )
        )
      ),
      array(
        #utm_medium
        'id'=>1419792, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$utm_medium
          )
        )
      ),
      array(
        #utm_content
        'id'=>1419798, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$utm_content
          )
        )
      ),
      array(
        #utm_term
        'id'=>1419796, #Уникальный индентификатор заполняемого дополнительного поля
        'values'=>array(
          array(
            'value'=>$utm_term
          )
        )
      )
    )
  )
);


$subdomain='procut'; #Наш аккаунт - поддомен
#Формируем ссылку для запроса
$link='https://'.$subdomain.'.amocrm.ru/private/api/v2/json/leads/set';

$curl=curl_init(); #Сохраняем дескриптор сеанса cURL
#Устанавливаем необходимые опции для сеанса cURL
curl_setopt($curl,CURLOPT_RETURNTRANSFER,true);
curl_setopt($curl,CURLOPT_USERAGENT,'amoCRM-API-client/1.0');
curl_setopt($curl,CURLOPT_URL,$link);
curl_setopt($curl,CURLOPT_CUSTOMREQUEST,'POST');
curl_setopt($curl,CURLOPT_POSTFIELDS,json_encode($leads));
curl_setopt($curl,CURLOPT_HTTPHEADER,array('Content-Type: application/json'));
curl_setopt($curl,CURLOPT_HEADER,false);
curl_setopt($curl,CURLOPT_COOKIEFILE,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_COOKIEJAR,dirname(__FILE__).'/cookie.txt'); #PHP>5.3.6 dirname(__FILE__) -> __DIR__
curl_setopt($curl,CURLOPT_SSL_VERIFYPEER,0);
curl_setopt($curl,CURLOPT_SSL_VERIFYHOST,0);

$out=curl_exec($curl); #Инициируем запрос к API и сохраняем ответ в переменную
$code=curl_getinfo($curl,CURLINFO_HTTP_CODE);

$code=(int)$code;
$errors=array(
  301=>'Moved permanently',
  400=>'Bad request',
  401=>'Unauthorized',
  403=>'Forbidden',
  404=>'Not found',
  500=>'Internal server error',
  502=>'Bad gateway',
  503=>'Service unavailable'
);
try
{
  #Если код ответа не равен 200 или 204 - возвращаем сообщение об ошибке
  if($code!=200 && $code!=204)
    throw new Exception(isset($errors[$code]) ? $errors[$code] : 'Undescribed error',$code);
}
catch(Exception $E)
{
  die('Ошибка: '.$E->getMessage().PHP_EOL.'Код ошибки: '.$E->getCode());
}

/**
 * Данные получаем в формате JSON, поэтому, для получения читаемых данных,
 * нам придётся перевести ответ в формат, понятный PHP
 */
$Response=json_decode($out,true);
$Response=$Response['response']['leads']['add'];

$newlead_id="";
foreach($Response as $v)
  if(is_array($v))
    $newlead_id.=$v['id'];

?>
