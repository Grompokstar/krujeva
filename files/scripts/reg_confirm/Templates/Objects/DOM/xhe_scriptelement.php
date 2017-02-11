<?php
// 2746
//////////////////////////////////////////////////// ScriptElement /////////////////////////////////////////////////
class XHEScriptElement  extends XHEScriptElementCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEScriptElement($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "ScriptElement";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL ///////////////////////////////////////////////////////////////////	
	
	// get count of elements
	function get_count()
	{
		return $this->call("ScriptElement.GetCount");
	}
	// get defer by number
	function get_defer_by_number($number)
	{
		return $this->call("ScriptElement.GetDeferByNumber?number=".urlencode($number));
	}
	// get defer by src
	function get_defer_by_src($src)
	{
		return $this->call("ScriptElement.GetDeferBySRC?src=".urlencode($src));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("ScriptElement.GetCountInFrameByNum?number=".urlencode($number));
	}
   	// set defer by src
	function set_defer_by_src($src,$value)
	{
		if ($this->call("ScriptElement.SetDeferBySRC?src=".urlencode($src)."&value=".urlencode($src))=="true")
			return true;
		else
			return false;
	}
	// set defer by number
	function set_defer_by_number($number,$value)
	{
		if ($this->call("ScriptElement.SetDeferByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get event by number
	function get_event_by_number($number)
	{
		return $this->call("ScriptElement.GetEventByNumber?number=".urlencode($number));
	}
	// get event by src
	function get_event_by_src($src)
	{
		return $this->call("ScriptElement.GetEventBySRC?src=".urlencode($src));
	}
   	// set event by src
	function set_event_by_src($src,$value)
	{
		if ($this->call("ScriptElement.SetEventBySRC?src=".urlencode($src)."&value=".urlencode($src))=="true")
			return true;
		else
			return false;
	}
	// set event by number
	function set_event_by_number($number,$value)
	{
		if ($this->call("ScriptElement.SetEventByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get htmlFor by number
	function get_htmlFor_by_number($number)
	{
		return $this->call("ScriptElement.GetHTMLForByNumber?number=".urlencode($number));
	}
	// get htmlFor by src
	function get_htmlFor_by_src($src)
	{
		return $this->call("ScriptElement.GetHTMLForBySRC?src=".urlencode($src));
	}
   	// set htmlFor by src
	function set_htmlFor_by_src($src,$value)
	{
		if ($this->call("ScriptElement.SetHTMLForBySRC?src=".urlencode($src)."&value=".urlencode($src))=="true")
			return true;
		else
			return false;
	}
	// set htmlFor by number
	function set_htmlFor_by_number($number,$value)
	{
		if ($this->call("ScriptElement.SetHTMLForByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get readyState by number
	function get_readyState_by_number($number)
	{
		return $this->call("ScriptElement.GetReadyStateByNumber?number=".urlencode($number));
	}
	// get readyState by src
	function get_readyState_by_src($src)
	{
		return $this->call("ScriptElement.GetReadyStateBySRC?src=".urlencode($src));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get src by number
	function get_src_by_number($number)
	{
		return $this->call("ScriptElement.GetSRCByNumber?number=".urlencode($number));
	}
   	// set src by number
	function set_src_by_number($number,$value)
	{
		if ($this->call("ScriptElement.SetSRCByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get number by src
	function get_number_by_src($src)
	{
		return $this->call("ScriptElement.GetNumberBySRC?src=".urlencode($src));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get text by number
	function get_text_by_number($number)
	{
		return $this->call("ScriptElement.GetTextByNumber?number=".urlencode($number));
	}
	// get text by src
	function get_text_by_src($src)
	{
		return $this->call("ScriptElement.GetTextBySRC?src=".urlencode($src));
	}
   	// set text by src
	function set_text_by_src($src,$value)
	{
		if ($this->call("ScriptElement.SetTextBySRC?src=".urlencode($src)."&value=".urlencode($src))=="true")
			return true;
		else
			return false;
	}
	// set text by number
	function set_text_by_number($number,$value)
	{
		if ($this->call("ScriptElement.SetTextByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get type by number
	function get_type_by_number($number)
	{
		return $this->call("ScriptElement.GetTypeByNumber?number=".urlencode($number));
	}
	// get type by src
	function get_type_by_src($src)
	{
		return $this->call("ScriptElement.GetTypeBySRC?src=".urlencode($src));
	}
   	// set type by src
	function set_type_by_src($src,$value)
	{
		if ($this->call("ScriptElement.SetTypeBySRC?src=".urlencode($src)."&value=".urlencode($src))=="true")
			return true;
		else
			return false;
	}
	// set type by number
	function set_type_by_number($number,$value)
	{
		if ($this->call("ScriptElement.SetTypeByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>