<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XHEImageCompatible extends XHEBaseDOM
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// is image loaded to browser by number (in future will replace -> is_complete_by_number)
	function is_complete($number)
	{
	        if ($this->call("Image.IsCompleteByNumber?number=".urlencode($number))=="true")
                   return true;
                else
                   return false;
	}
   	// get src by name (image url) (in future will replace -> get_src_by_name)
	function get_href_by_name($name)
	{
		return $this->call("Image.GetHrefByName?name=".urlencode($name));
	}
	// get src by number (image url) (in future will replace -> get_src_by_name)
	function get_href_by_number($number)
	{
		return $this->call("Image.GetHrefByNumber?number=".urlencode($number));
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