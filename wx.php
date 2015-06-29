<?php
/**
  * wechat php test
  */

//define your token
define("TOKEN", "asuliuapi123");
$wechatObj = new wechatCallbackapiTest();
$wechatObj->valid();

$wechatObj->responseMsg();
/*
$postStr = file_get_contents("php://input");

$tmpfile = fopen(time().".txt", "w") or die("Unable to open file!");
fwrite($tmpfile, $postStr);
fclose($tmpfile);
*/

class wechatCallbackapiTest
{
	public function valid()
    {
        $echoStr = $_GET["echostr"];

        //valid signature , option
        if($this->checkSignature()){
        	echo $echoStr;
        }
    }

    public function responseMsg()
    {
		//get post data, May be due to the different environments
		$postStr = $GLOBALS["HTTP_RAW_POST_DATA"];

      	//extract post data
		if (!empty($postStr)){
                /* libxml_disable_entity_loader is to prevent XML eXternal Entity Injection,
                   the best way is to check the validity of xml by yourself */
                libxml_disable_entity_loader(true);
              	$postObj = simplexml_load_string($postStr, 'SimpleXMLElement', LIBXML_NOCDATA);
                $fromUsername = $postObj->FromUserName;
                $toUsername = $postObj->ToUserName;
                $keyword = trim($postObj->Content);
                $time = time();
                $textTpl = "<xml>
							<ToUserName><![CDATA[%s]]></ToUserName>
							<FromUserName><![CDATA[%s]]></FromUserName>
							<CreateTime>%s</CreateTime>
							<MsgType><![CDATA[%s]]></MsgType>
							<Content><![CDATA[%s]]></Content>
							<FuncFlag>0</FuncFlag>
							</xml>";             
				if(!empty( $keyword ))
                {
                    if(substr( $keyword, 0, 4) == "reg:")
                    {
                        // create a msg queue here with the devid
                        $devid = substr($keyword, 4);
                        $this->regHandler( trim($fromUsername), $devid );
                        $contentStr = "Reg OK:".$devid;
                    }
                    elseif(substr( $keyword, 0, 1 ) == "#")
                    {
                        $pos = strpos( $keyword, ":" );
                        if( !$pos )
                        {
                            $contentStr = "Not supported command";
                        }
                        else
                        {
                            $devid = substr( $keyword, 1, $pos-1 );
                            $msg = substr( $keyword, $pos+1 );
                            $this->recvHandler( $devid, $msg );
                            $contentStr = "Recv OK:".$devid.", ".$msg;
                        }
                    }
		    elseif(substr( $keyword, 0, 6) == "unreg:")
		    {
                        $devid = substr($keyword, 6);
                        $this->unregHandler( trim($fromUsername), $devid );
                        $contentStr = "Unreg OK:".$devid;
		    }
                    else
                    {
                        $contentStr = "Welcome to Asura's wechat world!";
                    }
                    $msgType = "text";
                	$resultStr = sprintf($textTpl, $fromUsername, $toUsername, $time, $msgType, $contentStr);
                	echo $resultStr;
                }else{
                	echo "Input something...";
                }

        }else {
        	echo "";
        	exit;
        }
    }
		
	private function checkSignature()
	{
        // you must define TOKEN by yourself
        if (!defined("TOKEN")) {
            throw new Exception('TOKEN is not defined!');
        }
        
        $signature = $_GET["signature"];
        $timestamp = $_GET["timestamp"];
        $nonce = $_GET["nonce"];
        		
		$token = TOKEN;
		$tmpArr = array($token, $timestamp, $nonce);
        // use SORT_STRING rule
		sort($tmpArr, SORT_STRING);
		$tmpStr = implode( $tmpArr );
		$tmpStr = sha1( $tmpStr );
		
		if( $tmpStr == $signature ){
			return true;
		}else{
			return false;
		}
	}

    private function regHandler( $user, $devid )
    {
        # code...
        include( './qmc.php' );
        QMC::store( $devid, array( $user ));
    }

    private function recvHandler( $devid, $msg )
    {
        # code...
        include( './qmc.php' );
        QMC::input( $devid, $msg );
    }

    private function unregHandler( $user, $devid )
    {
        # code...
        include( './qmc.php' );
        QMC::remove( $devid, $user );
    }
}


?>
