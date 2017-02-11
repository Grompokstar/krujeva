<?php
// 8424
//////////////////////////////////////////////////// Anticapcha /////////////////////////////////////////////////
class XHEAnticapcha
{
        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// server address
	var $server;

	// capcha id
	var $last_capcha_id;
	// capcha file
	var $last_capcha_filename;
	// capcha reuslt
	var $last_capcha_result;

        // constructor
        function XHEAnticapcha ($server)
        {
		$this->server = $server;
        }

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// call a command on the server
	function call($command)
	{
		// call server and return its answer
		$url = "http://".$this->server."/".$command;
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
      		curl_setopt($cUrl, CURLOPT_T, 1);      
      		curl_setopt($cUrl, CURLOPT_POSTFIELDS, $postvars);
      		curl_setopt($cUrl, CURLOPT_RETURNTRANSFER, 1);
      		curl_setopt($cUrl, CURLOPT_TIMEOUT, 60);
      		$html = trim(curl_exec($cUrl));
      		curl_close($cUrl);
	
		return $html;
	}

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	// get last capcha id
	function get_last_capcha_id()
	{
            return $this->last_capcha_id;
	}
	// get last capcha file
	function get_last_capcha_filename()
	{
            return $this->last_capcha_filename;
	}
	// get last capcha result
	function get_last_capcha_result()
	{
            return $this->last_capcha_result;
	}
	// report bug capcha
	function report_bug_capcha($key,$id)
	{
            return $this->call("res.php?key=".urlencode($key)."&action=reportbad&id=".urlencode($id));
	}

        ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////
/*
$filename - полный путь к файлу
$apikey   - ключ для работы
$rtimeout - задержка между опросами статуса капчи
$mtimeout - время ожидания ввода капчи

включить/выключить verbose mode (комментирование происходящего):
$is_verbose - false(выключить),  true(включить)

дополнительно (дефолтные параметры править не нужно без необходимости):
$is_phrase - 0 либо 1 - флаг "в капче 2 и более слов"
$is_regsense - 0 либо 1 - флаг "регистр букв в капче имеет значение"
$is_numeric -  0 либо 1 - флаг "капча состоит только из цифр"
$min_len    -  0 (без ограничений), любая другая цифра указывает минимальную длину текста капчи
$max_len    -  0 (без ограничений), любая другая цифра указывает максимальную длину текста капчи

пример:
$text=recognize("/path/to/file/captcha.jpg","ваш_ключ_из_админки",true);

$text=recognize("/path/to/file/captcha.jpg","ваш_ключ_из_админки",false);  //отключено комментирование

$text=recognize("/path/to/file/captcha.jpg","ваш_ключ_из_админки",false,1,0,0,5);  //отключено комментирование, капча состоит из двух слов, общая минимальная длина равна 5 символам

*/

function recognize($filename, $apikey, $path ='http://www.anti-captcha.com',  $is_verbose = true, $rtimeout = 5, $mtimeout = 120, $is_phrase = 0, $is_regsense = 0, $is_numeric = 0, $min_len = 0, $max_len = 0)
{
        $this->last_capcha_id=-1;
        $this->last_capcha_filename=$filename;
	if (!file_exists($filename))
	{
		if ($is_verbose) 
                  echo "file $filename not found\n";
                $this->last_capcha_result=false;
		return false;
	}
    $postdata = array(
        'method'    => 'post', 
        'key'       => $apikey, 
        'file'      => '@'.$filename, //полный путь к файлу
        'phrase'	=> $is_phrase,
        'regsense'	=> $is_regsense,
        'numeric'	=> $is_numeric,
        'min_len'	=> $min_len,
        'max_len'	=> $max_len,
        'soft_id'	=> '15',
    );
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL,             $path.'/in.php');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER,     1);
    curl_setopt($ch, CURLOPT_TIMEOUT,             60);
    curl_setopt($ch, CURLOPT_POST,                 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS,         $postdata);
    $result = curl_exec($ch);
    if (curl_errno($ch)) 
    {
    	if ($is_verbose) 
          echo "CURL returned error: ".curl_error($ch)."\n";
        $this->last_capcha_result=false;
        return false;
    }
    curl_close($ch);
    if (strpos($result, "ERROR")!==false)
    {
    	if ($is_verbose) 
          echo "server returned error: $result\n";
        $this->last_capcha_result=false;
        return false;
    }
    else
    {
        $ex = explode("|", $result);
        $captcha_id = $ex[1];
        $this->last_capcha_id=$captcha_id;
    	if ($is_verbose) echo "captcha sent, got captcha ID $captcha_id\n";
        $waittime = 0;
        if ($is_verbose) echo "waiting for $rtimeout seconds\n";
        sleep($rtimeout);
        while(true)
        {
            $result = file_get_contents($path.'/res.php?key='.$apikey.'&soft_id=15&action=get&id='.$captcha_id);
            if (strpos($result, 'ERROR')!==false)
            {
            	if ($is_verbose) echo "server returned error: $result\n";
                $this->last_capcha_result=false;
                return false;
            }
            if ($result=="CAPCHA_NOT_READY")
            {
            	if ($is_verbose) echo "captcha is not ready yet\n";
            	$waittime += $rtimeout;
            	if ($waittime>$mtimeout) 
            	{
            		if ($is_verbose) echo "timelimit ($mtimeout) hit\n";
            		break;
            	}
        	if ($is_verbose) echo "waiting for $rtimeout seconds\n";
            	sleep($rtimeout);
            }
            else
            {
            	$ex = explode('|', $result);
            	if (trim($ex[0])=='OK') 
                {
		   $this->last_capcha_result=trim($ex[1]);
                   return trim($ex[1]);
                }
            }

        }
        
        $this->last_capcha_result=false;
        return false;
    }
}
};
?> 
