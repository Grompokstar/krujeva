<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XHEInputCompatible extends XHEBaseDOM
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get alt by name (in future will replace -> get_alt_by_name)
	function get_alt_text_by_name($name) 
	{
		return $this->call("Input.GetAltTextByName?name=".urlencode($name));
	}   
   	// get alt by number (in future will replace -> get_alt_by_number)
	function get_alt_text_by_number($number)
	{
		return $this->call("Input.GetAltTextByNumber?number=".urlencode($number));
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// click on element by atribute
	function click_by_attribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
	}	
	// send event to element by any attribute
	function send_event_by_attribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
};		
?>