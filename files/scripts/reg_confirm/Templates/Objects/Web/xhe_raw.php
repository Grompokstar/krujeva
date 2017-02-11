<?php
// 6578
//////////////////////////////////////////////////// RAW stream - all exchange browser with www /////////////////////////////////////////
class XHERaw extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHERaw($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Raw";
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////// FUNCTIONAL /////////////////////////////////////////////////////////////////
     	// enable All Streams
	function enable_all_streams($enable)
	{
		if ($this->call("Raw.EnableAllStreams?enable=".urlencode($enable))=="true")
			return true;
		else
			return false;
	}
     	// enable HTTP Stream
	function enable_http_stream($enable)
	{
		if ($this->call("Raw.EnableHTTPStream?enable=".urlencode($enable))=="true")
			return true;
		else
			return false;
	}
     	// enable HTTPS Stream
	function enable_https_stream($enable)
	{
		if ($this->call("Raw.EnableHTTPSStream?enable=".urlencode($enable))=="true")
			return true;
		else
			return false;
	}
     	// get last request url
	function get_last_request_url($num)
	{
		return $this->call("Raw.GetLastRequestUrl?num=".urlencode($num));
	}
     	// get last request header
	function get_last_request_header($num)
	{
		return $this->call("Raw.GetLastRequestHeader?num=".urlencode($num));
	}

     	// get last response url
	function get_last_response_url($num)
	{
		return $this->call("Raw.GetLastResponseUrl?num=".urlencode($num));
	}
     	// get last response buffer
	function get_last_response_buffer($num)
	{
		return $this->call("Raw.GetLastResponseBuffer?num=".urlencode($num));
	}
     	// get last redirect url
	function get_last_redirect_url($num)
	{
		return $this->call("Raw.GetLastRedirectUrl?num=".urlencode($num));
	}
     	// get last redirect header
	function get_last_redirect_header($num)
	{
		return $this->call("Raw.GetLastRedirectHeader?num=".urlencode($num));
	}
     	// clear last reguests array
	function clear_last_requests_array()
	{
		if ($this->call("Raw.ClearLastRequestsArray")=="true")
			return true;
		else
			return false;
	}
     	// clear last responses array
	function clear_last_responses_array()
	{
		if ($this->call("Raw.ClearLastResponsesArray")=="true")
			return true;
		else
			return false;
	}
     	// set hook on begin browser download transaction
	function set_hook_on_begin_transaction($php_script_path)
	{
		if ($this->call("Raw.SetHookOnBeginTransAction?php_script_path=".urlencode($php_script_path))=="true")
			return true;
		else
			return false;
	}
     	// set hook on response from server to browser
	function set_hook_on_response($php_script_path)
	{
		if ($this->call("Raw.SetHookOnResponse?php_script_path=".urlencode($php_script_path))=="true")
			return true;
		else
			return false;
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
};
?>