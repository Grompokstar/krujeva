<?php

class XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE VARIABLES /////////////////////////////////////////////////////////////

	// server address and port
	var $server;
	// server password
	var $password;
	// command prefix
	var $prefix;

	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEBaseObject($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
	}
	// call a command on the server
	function call($command,$timeout=60)
	{
		// call server and return its answer
		$url = "http://".$this->server."/".$command;
		if(strlen($this->password))
		{
			if(strstr($url,"&"))
				$url .= "&password=".$this->password;
			else
				$url .= "?password=".$this->password;
		}
		$postvars="";
		if(strstr($url,"?"))
      		{
         		$indexPost=strpos($url,"?",0);
			$postvars=substr($url,$indexPost+1,strlen($url)-$indexPost);
			$url=substr($url,0,$indexPost);
	   	}
      		$postvars=$postvars."  ";
      		$cUrl = curl_init();
      		curl_setopt($cUrl, CURLOPT_URL, $url);
      		curl_setopt($cUrl, CURLOPT_POST, 1);      
      		curl_setopt($cUrl, CURLOPT_POSTFIELDS, $postvars);
      		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
      		curl_setopt($cUrl, CURLOPT_TIMEOUT, $timeout);
		$html=curl_exec($cUrl);

		// порт не обрабатывается
		global $bClosePHPIfNotConnected;
		if ($bClosePHPIfNotConnected===true && $html===false)
		{
  			echo("\nNot connected.Script will close\n");
			die("XWeb@exit");
		}
    		$html = trim($html);
      		curl_close($cUrl);
	
		return $html;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
}
?>