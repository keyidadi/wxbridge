<?php


include("qmc.php");

$devid	= $_GET["devid"];
$msg	= $_GET["msg"];

if(empty($devid))
{
	$postStr	= $GLOBALS["HTTP_RAW_POST_DATA"];
	$obj		= json_decode($postStr);
	$devid		= $obj->devid;
	$msg		= $obj->msg; 
}

if(empty($devid)){
	die(json_encode(array("ret"=>-1,"msg"=>"no devid")));
}

$sendurl = "https://api.weixin.qq.com/cgi-bin/message/custom/send?access_token=".get_access_token();

$jsondata = array(
	"msgtype" => "text",
	"text" =>  array( "content" => $msg )
	);

$users = QMC::read($devid);
if(!$users||empty($users)){
	die(json_encode(array("ret"=>-2,"msg"=>"no user registered")));
}
foreach ($users as $user) {
	# code...
	$jsondata["touser"] = $user;
	$jsonStr = json_encode($jsondata);
	$http = http_post_json($sendurl, $jsonStr);
}
echo json_encode(array("ret"=>"0"));

function http_post_json($url, $jsonStr)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $jsonStr);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json; charset=utf-8',
            'Content-Length: ' . strlen($jsonStr)
        )
    );
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
 
    return array($httpCode, $response);
}

function get_access_token()
{
	$token = QMC::read("token");
	if(!$token){
		$authurl = "https://api.weixin.qq.com/cgi-bin/token?grant_type=client_credential&appid=wxee6c914fca02f4dd&secret=1d0fe2e4f06a42caf289ab817a56d331";
		$html	= file_get_contents($authurl);
		$obj	= json_decode($html);
		$token	= $obj->access_token;
		$time	= $obj->expires_in;

		QMC::store( "token", $token, $time-60 );
	}
	return $token;
}

?>
