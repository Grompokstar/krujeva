<?php
// 2929
//////////////////////////////////////////////////// Button /////////////////////////////////////////////////
class XHEButton extends XHEButtonCompatible
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS //////////////////////////////////////////////
	// server initialization
	function XHEButton($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Button";
	}
        //////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	
	////////////////////////////////////////////////////////////// CLICK //////////////////////////////////////////////////////
        // click by name
	function click_by_name($name)
	{
		return $this->z_click_by_name($name);
	}
	// click by number
	function click_by_number($number)
	{
		return $this->z_click_by_number($number);
	}
	// click by inner text
	function click_by_inner_text($text,$exactly="true")
	{
		return $this->z_click_by_inner_text($text,$exactly);
	}
	// click on button by atribute
	function click_by_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
	}
        // click on random element
	function click_random()
	{
		return $this->z_click_random();
	}
	/////////////////////////////////////////////////////// SEND EVENT //////////////////////////////////////////////////////////
	// send event to button by name
	function send_event_by_name($name,$event)
	{
		return $this->z_send_event_by_name($name,$event);
	}
	// send event to button by number 
	function send_event_by_number($number,$event)
	{
		return $this->z_send_event_by_number($number,$event);
	}
	// send event to button by inner text
	function send_event_by_inner_text($text,$exactly,$event)
	{
		return $this->z_send_event_by_inner_text($text,$exactly,$event);
	}
        // send event to element by any attribute
	function send_event_by_atribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
	}
	// get count of elements
	function get_count()
	{
		return $this->call("Button.GetCount");
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // is exist by name
        function is_exist_with_name($name)
        {
                if ($this->call("Button.IsExistByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
    	// get number by name
	function is_exist_with_inner_text($innertext)
	{
		if ($this->call("Button.IsExistsWithInnerText?innertext=".urlencode($innertext))=="true")
			return true;
		else	
			return false;
	}
	// is exist with attribute
	function is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("Button.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
   	// get number by name
	function get_number_by_name($name)
	{
		return $this->call("Button.GetNumberByName?name=".urlencode($name));
	}
   	// get name by number
	function get_name_by_number($number)
	{
		return $this->call("Button.GetNameByNumber?number=".urlencode($number));
	}
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("Button.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("Button.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute
        function get_atribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr)
        {
               $res = $this->call("Button.GetAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute in frame by number
        function get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               $res = $this->call("Button.GetAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&name_attr=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        //////////////////////////////////////////////////////////// GET X Y ///////////////////////////////////////////////////
        // get x of element by name
	function get_x_by_name($name)
	{
		return $this->z_get_x_by_name($name);

	}
	// get x of element by number 
	function get_x_by_number($number)
	{
        	return $this->z_get_x_by_number($number);

	}
	// get x of element by inner text
	function get_x_by_inner_text($text,$exactly=true)
	{
		return $this->z_get_x_by_inner_text($text,$exactly);
	}	
	// get x of element by any atribute
	function get_x_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->z_get_x_by_atribute($attr_name,$attr_value,$exactly);
	}
        // get y of element by name
	function get_y_by_name($name)
	{
		return $this->z_get_y_by_name($name);

	}
	// get y of element by number 
	function get_y_by_number($number)
	{
        	return $this->z_get_y_by_number($number);

	}
	// get y of element by inner text
	function get_y_by_inner_text($text,$exactly=true)
	{
		return $this->z_get_y_by_inner_text($text,$exactly);
	}	
	// get y of element by any atribute
	function get_y_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->z_get_y_by_atribute($attr_name,$attr_value,$exactly);
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("Button.GetCountInFrameByNum?number=".urlencode($number));
	}
	// is exist with attribute in frame by number
	function is_exist_with_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_number)
	{
		if ($this->call("Button.IsExistsWithAttrInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_number=".urlencode($frame_number))=="true")
			return true;
		else
			return false;
	}	
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// is exist with inner text in frame by number
	function is_exist_with_inner_text_in_frame_by_number($text,$exactly,$frame_num)
	{
		if ($this->call("Button.IsExistsWithInnerTextInFrameByNumber?text=".urlencode($text)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get name by number
	function get_all_inner_texts($separator="<br>")
	{
		return $this->call("Button.GetAllInnerText?separator=".urlencode($separator));
	}
   	// get number by name
	function get_inner_text_by_name($name)
	{
		return $this->call("Button.GetInnerTextByName?name=".urlencode($name));
	}
   	// get name by number
	function get_inner_text_by_number($number)
	{
		return $this->call("Button.GetInnerTextByNumber?number=".urlencode($number));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("Button.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("Button.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
	// add (or set) attribute by attribute
	function add_attribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr,$value_attr)
	{
               $res = $this->call("Button.AddAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
               if ($res =="false")
			return false;
		else
			return $res;
	}
	// add (or set) attribute by attribute in frame by number
	function add_attribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$value_attr,$frame_number)
	{
               $res = $this->call("Button.AddAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr))."&frame_number=".urlencode($frame_number);
               if ($res =="false")
			return false;
		else
			return $res;
	}
        // add (or set) atribute by number
	function add_atribute_by_number($number,$name_atr,$value_atr)
	{
               return $this->z_add_atribute_by_number($number,$name_atr,$value_atr);
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// set value by name
	function set_focus_by_name($name)
	{
		return $this->z_set_focus_by_name($name);
	}
	// set value by number
	function set_focus_by_number($number)
	{
		return $this->z_set_focus_by_number($number);
	}
   	// set focus by attribute
	function set_focus_by_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("Button.SetFocusByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // send event to element by name in frame
	function send_event_by_name_in_frame($name,$event,$frame)
	{
		return $this->z_send_event_by_name_in_frame($name,$event,$frame);
	}
	// send event to element by number in frame
	function send_event_by_number_in_frame($number,$event,$frame)
	{
		return $this->z_send_event_by_number_in_frame($number,$event,$frame);
	}
	// send event to element by inner text in frame
	function send_event_by_inner_text_in_frame($text,$exactly,$event,$frame)
	{
		return $this->z_send_event_by_inner_text_in_frame($text,$exactly,$event,$frame);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// click within frame by name
	function click_within_iframe_by_name($name,$frame)
	{
		if ($this->call("Button.ClickWithinIframeByName?name=".urlencode($name)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
   	// click within frame by name
	function click_within_iframe_by_number($number,$frame)
	{
		if ($this->call("Button.ClickWithinIframeByNumber?number=".urlencode($number)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("Button.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        // click on random element in frame
	function click_random_in_frame($frame)
	{
		return $this->z_click_random_in_frame($frame);
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// set focus by attribute in frame by number
	function set_focus_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_num)
	{
		if ($this->call("Button.SetFocusByAttrInFrameByNum?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else
			return false;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // is disabled by number
        function is_disabled_by_number($number)
        {
               return $this->z_is_disabled_by_number($number);
        }
        // is disabled by name
        function is_disabled_by_name($name)
        {
               return $this->z_is_disabled_by_name($name); 
        }
        /////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>