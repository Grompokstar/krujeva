<?php
// 8425
//////////////////////////////////////////////////// Captchabot /////////////////////////////////////////////////
class XHECaptchabot
{
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	var $url;
	var $host;
	var $id;
        // server address
	var $server;


        // конструктор
        function XHECaptchabot()
	{
		$this->url="http://captchabot.com/xmlrpc/axmlrpc.php";
		$this->host="captchabot.com";

		$this->SystemKey="";
	}

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
        // get last capcha id
	function get_last_capcha_id()
	{
            return $this->id;
	}

        function recognize($file, $language=0)
	{
		$s2='';
		$contents=file_get_contents($file);

		if (!$contents) return 200;

		$converted=base64_encode($contents);

		$request="<methodCall><methodName>ocr_server::analyze</methodName><params>";
		$request.="<param><base64>$converted</base64></param>";
		$request.="<param><string>system_key</string></param>";
		$request.="<param><string>".$this->SystemKey."</string></param>";
		$request.="<param><int>".$language."</int></param>";
		$request.="</params></methodCall>";

		//return $request;

		$header[] = "Host: ".$this->host;
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: ".strlen($request);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $request;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 140);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			return "300";
		}

		$npos=strpos($data,"<string>");
		if ($npos)
		{
			$start=$npos+strlen("<string>");
			$s1=substr($data,$start);
			$npos=strpos($s1,"</string>");
			if ($npos)
			{
				$s2=substr($s1,0,$npos);
			}
		}
		$text=$s2;
		$npos=strpos($data,"<int>");
		if ($npos)
		{
			$start=$npos+strlen("<int>");
			$s1=substr($data,$start);
			$npos=strpos($s1,"</int>");
			if ($npos)
			{
				$s2=substr($s1,0,$npos);
			}
		}
		$this->id=$s2;
		return $text;

	}

	function report($result)
	{
		$request="<methodCall><methodName>ocr_server::ver</methodName><params><param><string>";
		$request.=($result)?"yes":"no";
		$request.="</string></param><param><int>".$this->id."</int></param></params></methodCall>";

		$header[] = "Host: ".$this->host;
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: ".strlen($request);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $request;



		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);

		$data = curl_exec($ch);


		if (curl_errno($ch)) {
			return false;
		}
		else
		{
			return true;
		}


	}
	function get_balance() 
        {
		$request="<methodCall><methodName>ocr_server::balance</methodName><params>";
		$request.="<param><string>system_key</string></param>";
		$request.="<param><string>".$this->SystemKey."</string></param>";		
		$request.="</params></methodCall>";

		$header[] = "Host: ".$this->host;
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: ".strlen($request);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $request;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);	
		
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			return "300 - ".curl_error($ch);
		}
		$npos=strpos($data,"<double>");
		if ($npos) {
			$start=$npos+strlen("<double>");
			$s1=substr($data,$start);
			$npos=strpos($s1,"</double>");
			if ($npos) {
				$s2=substr($s1,0,$npos);
			}
		}
		$text=$s2;
		return $text;		
	}
	function get_limit() 
        {
		$request="<methodCall><methodName>ocr_server::limit</methodName><params>";
		$request.="<param><string>system_key</string></param>";
		$request.="<param><string>".$this->SystemKey."</string></param>";		
		$request.="</params></methodCall>";

		$header[] = "Host: ".$this->host;
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: ".strlen($request);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $request;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);	
		
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			return "300 - ".curl_error($ch);
		}
		$npos=strpos($data,"<string>");
		if ($npos) {
			$start=$npos+strlen("<string>");
			$s1=substr($data,$start);
			$npos=strpos($s1,"</string>");
			if ($npos) {
				$s2=substr($s1,0,$npos);
			}
		}
		$text=$s2;
		
		$npos=strripos($data,"<string>");
		if ($npos) {
			$start=$npos+strlen("<string>");
			$s1=substr($data,$start);
			$npos=strripos($s1,"</string>");
			if ($npos) {
				$s2=substr($s1,0,$npos);
			}
		}
		$text2=$s2;		
		return $text;			
	}	
	function get_limit_used()
        {
		$request="<methodCall><methodName>ocr_server::limit</methodName><params>";
		$request.="<param><string>system_key</string></param>";
		$request.="<param><string>".$this->SystemKey."</string></param>";		
		$request.="</params></methodCall>";

		$header[] = "Host: ".$this->host;
		$header[] = "MIME-Version: 1.0";
		$header[] = "Content-type: multipart/mixed; boundary=----doc";
		$header[] = "Accept: text/xml";
		$header[] = "Content-length: ".strlen($request);
		$header[] = "Cache-Control: no-cache";
		$header[] = "Connection: close \r\n";
		$header[] = $request;

		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$this->url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_TIMEOUT, 300);
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST,'POST');
		curl_setopt($ch, CURLOPT_HTTPHEADER, $header);	
		
		$data = curl_exec($ch);
		if (curl_errno($ch)) {
			return "300 - ".curl_error($ch);
		}
		$npos=strpos($data,"<string>");
		if ($npos) {
			$start=$npos+strlen("<string>");
			$s1=substr($data,$start);
			$npos=strpos($s1,"</string>");
			if ($npos) {
				$s2=substr($s1,0,$npos);
			}
		}
		$text=$s2;
		
		$npos=strripos($data,"<string>");
		if ($npos) {
			$start=$npos+strlen("<string>");
			$s1=substr($data,$start);
			$npos=strripos($s1,"</string>");
			if ($npos) {
				$s2=substr($s1,0,$npos);
			}
		}
		$text2=$s2;		
		return $text2;			
	}		
};

?>