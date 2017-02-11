<?php
// 3830
//////////////////////////////////////////////////// Body /////////////////////////////////////////////////
class XHEBody extends XHEBodyCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEBody($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Body";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// set body value by name
	function set_text_by_name($name,$text)
	{
		if ($this->call("Body.SetBodyTextByName?name=".urlencode($name)."&text=".urlencode($text))=="true")
			return true;
		else
			return false;
	}
	// set body value by number
	function set_text_by_number($number,$text)
	{
		if ($this->call("Body.SetBodyTextByNumber?number=".urlencode($number)."&text=".urlencode($text))=="true")
			return true;
		else
			return false;
	}
   	// get body value by name
	function get_text_by_name($name)
	{
		return $this->call("Body.GetBodyTextByName?name=".urlencode($name));
	}   
        // get body value by number
	function get_text_by_number($number)
	{
		return $this->call("Body.GetBodyTextByNumber?number=".urlencode($number));
	}	
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set body value by number in frame
	function set_text_within_iframe_by_name($name,$text,$framenum)
	{
		if ($this->call("Body.SetBodyTextWithinIframeByName?name=".urlencode($name)."&text=".urlencode($text)."&framenum=".urlencode($framenum))=="true")
			return true;
		else
			return false;
	}
  	// set body value by number in frame
	function set_text_within_iframe_by_number($number,$text,$framenum)
	{
		if ($this->call("Body.SetBodyTextWithinIframeByNumber?number=".urlencode($number)."&text=".urlencode($text)."&framenum=".urlencode($framenum))=="true")
			return true;
		else
			return false;
	}
	// get body value by name
	function get_text_within_iframe_by_name($name,$framenum)
	{
		return $this->call("Body.GetBodyTextWithinIframeByName?name=".urlencode($name)."&framenum=".urlencode($framenum));
	}   
        // get body value by number
	function get_text_within_iframe_by_number($number,$framenum)
	{
		return $this->call("Body.GetBodyTextWithinIframeByNumber?number=".urlencode($number)."&framenum=".urlencode($framenum));
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set focus by name
	function set_focus_by_name($name)
	{
		return $this->z_set_focus_by_name($name);
	}
	// set focus by number
	function set_focus_by_number($number)
	{
		return $this->z_set_focus_by_number($number);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};		
?>