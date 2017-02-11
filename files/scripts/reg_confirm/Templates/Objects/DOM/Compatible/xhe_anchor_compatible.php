<?php
///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
class XHEAnchorCompatible extends XHEBaseDOM
{
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// click on element by any attribute
	function click_by_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
	}	
	// send event to element by any attribute
	function send_event_by_atribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// is anchor exist by attribute
	function is_exist_with_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_is_exist_with_attribute($atr_name,$atr_value,$exactly);
	}	
	// is anchor exist by attribute
	function is_exist_with_attribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_is_exist_with_attribute($atr_name,$atr_value,$exactly);
	}	
        // is anchor exist by name
        function is_exist_with_name($name)
        {
		return $this->z_is_exist_with_name($name);
        }
	// is anchor exist by inner text
	function is_exist_with_inner_text($text,$exactly=true)
	{
		return $this->z_is_exist_with_inner_text($text,$exactly);
	}	
	// is anchor exist by href
	function is_exist_with_href($href,$exactly=true)
	{
		return $this->z_is_exist_with_href($href,$exactly=true);
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// remove attribute by name
	function remove_atribute_by_name($name,$name_atr)
	{
		return $this->z_remove_atribute_by_name($name,$name_atr);
	}
	// remove attribute by number
	function remove_atribute_by_number($number,$name_atr)
	{
		return $this->z_remove_atribute_by_number($number,$name_atr);
	}
        // add (or set) attribute by number
	function add_atribute_by_number($number,$name_atr,$value_atr)
	{
               return $this->z_add_atribute_by_number($number,$name_atr,$value_atr);
	}
	// add (or set) attribute by attribute
	function add_atribute_by_attribute($atr_name,$atr_value,$exactly,$name_atr,$value_atr)
	{
               return $this->z_add_atribute_by_atribute($atr_name,$atr_value,$exactly,$name_atr,$value_atr);
	}
	// add (or set) attribute by attribute
	function add_atribute_by_atribute($atr_name,$atr_value,$exactly,$name_atr,$value_atr)
	{
               return $this->z_add_atribute_by_atribute($atr_name,$atr_value,$exactly,$name_atr,$value_atr);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // get number by attribute
        function get_number_by_atribute($atr_name,$atr_value,$exactly=true)
        {
               return $this->z_get_number_by_atribute($atr_name,$atr_value,$exactly);
        }
        // get attribute by name
        function get_atribute_by_name($name,$name_atr)
        {
               return $this->z_get_atribute_by_name($name,$name_atr);
        }
        // get attribute by number
        function get_atribute_by_number($number,$name_atr)
        {
               return $this->z_get_atribute_by_number($number,$name_atr);
        }
        // get attribute by attribute
        function get_atribute_by_attribute($atr_name,$atr_value,$exactly,$name_atr)
        {
               return $this->z_get_atribute_by_atribute($atr_name,$atr_value,$exactly,$name_atr);
        }
        // get attribute by attribute in frame by number
        function get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               $res = $this->call("Anchor.GetAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&name_attr=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
        }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   	// set focus to anchor by attribute
	function set_focus_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->z_set_focus_by_atribute($attr_name,$attr_value,$exactly);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// is exist with attribute in frame by number
	function is_exist_with_atribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_number)
	{
	       return $this->z_is_exist_with_attribute_in_frame($atr_name,$atr_value,$frame_number,$exactly);	
	}
	// remove attribute by name
	function remove_atribute_by_name_in_frame($name,$name_atr,$frame_number)
	{
		return $this->z_remove_atribute_by_name_in_frame($name,$name_atr,$frame_number);
	}
	// remove attribute by number
	function remove_atribute_by_number_in_frame($number,$name_atr,$frame_number)
	{
		return $this->z_remove_atribute_by_number_in_frame($name,$name_atr,$frame_number);
	}
	// remove atribute by atribute in frame by number
	function remove_atribute_by_attribute_in_frame_by_number($atr_name,$atr_value,$exactly,$name_atr,$frame_number)
	{
		return $this->z_remove_atribute_by_atribute_in_frame_by_number($atr_name,$atr_value,$exactly,$name_atr,$frame_number);
	}
	// get x of element by any attribute
	function get_x_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->z_get_x_by_atribute($attr_name,$attr_value,$exactly);
	}
	// get y of element by any attribute
	function get_y_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->z_get_y_by_atribute($attr_name,$attr_value,$exactly);
	}
	// is anchor exist by attribute in frame
	function is_exist_by_atribute_in_frame($atr_name,$atr_value,$frame,$exactly=true)
	{
		return $this->z_is_exist_with_attribute_in_frame($atr_name,$atr_value,$frame,$exactly);
	}
};		
?>