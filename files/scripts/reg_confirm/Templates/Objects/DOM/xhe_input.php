<?php
// 2753
//////////////////////////////////////////////////// Input /////////////////////////////////////////////////
class XHEInput  extends XHEInputCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEInput($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Input";
	}
   	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// click on input by atribute
	function click_by_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
	}
        // click on random element
	function click_random()
	{
		return $this->z_click_random();
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("Input.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        // click on random element in frame
	function click_random_in_frame($frame)
	{
		return $this->z_click_random_in_frame($frame);
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
	// send event to element by name
	function send_event_by_name($name,$event)
	{
		return $this->z_send_event_by_name($name,$event);
	}
	// send event to element by number 
	function send_event_by_number($number,$event)
	{
		return $this->z_send_event_by_number($number,$event);
	}
	// send event to element by inner text
	function send_event_by_inner_text($text,$exactly,$event)
	{
		return $this->z_send_event_by_inner_text($text,$exactly,$event);
	}
        // send event to element by any attribute
	function send_event_by_atribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
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
	// set value by name
	function set_value_by_name($name,$value)
	{
		if ($this->call("Input.SetValueByName?name=".urlencode($name)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	// set value by number
	function set_value_by_number($number,$value)
	{
		if ($this->call("Input.SetValueByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set value by number in frame
	function set_value_within_iframe_by_name($name,$value,$frame)
	{
		if ($this->call("Input.SetValueWithinIframeByName?name=".urlencode($name)."&value=".urlencode($value)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
   	// set value by number in frame
	function set_value_within_iframe_by_number($number,$value,$frame)
	{
		if ($this->call("Input.SetValueWithinIframeByNumber?number=".urlencode($number)."&value=".urlencode($value)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // set value by name in form by form name
	function set_value_by_name_by_form_name($name,$value,$formname)
	{
		if ($this->call("Input.SetValueByNameByFormName?name=".urlencode($name)."&value=".urlencode($value)."&formname=".urlencode($formname))=="true")
			return true;
		else
			return false;
	}
	// set value byname in form by form number
	function set_value_by_name_by_form_number($name,$value,$formnumber)
	{
		if ($this->call("Input.SetValueByNameByFormNumber?name=".urlencode($name)."&value=".urlencode($value)."&formnumber=".urlencode($formnumber))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////        
        // get value by name
	function get_value_by_name($name)
	{
		return $this->call("Input.GetValueByName?name=".urlencode($name));
	}   
        // get value by number
	function get_value_by_number($number)
	{
		return $this->call("Input.GetValueByNumber?number=".urlencode($number));
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("Input.GetCount");
	}
	// get alt by name
	function get_alt_by_name($name) 
	{
		return $this->call("Input.GetAltTextByName?name=".urlencode($name));
	}   
   	// get alt by number
	function get_alt_by_number($number)
	{
		return $this->call("Input.GetAltTextByNumber?number=".urlencode($number));
	}	
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("Input.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("Input.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute
        function get_atribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr)
        {
               $res = $this->call("Input.GetAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute in frame by number
        function get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               $res = $this->call("Input.GetAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&name_attr=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
        }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("Input.GetCountInFrameByNum?number=".urlencode($number));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// is exist by name
	function is_exist_with_name($name)
	{
		if ($this->call("Input.IsExistsByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
	// is exist with attribute
	function is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("Input.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// is exist by name in frame
	function is_exist_within_iframe_with_name($name,$frame)
	{
		if ($this->call("Input.IsExistsWithinIframeWithName?name=".urlencode($name)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// is exist with attribute in frame by number
	function is_exist_with_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_number)
	{
		if ($this->call("Input.IsExistsWithAttrInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_number=".urlencode($frame_number))=="true")
			return true;
		else
			return false;
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get number by name
	function get_number_by_name($name)
	{
                $res = $this->call("Input.GetNumberByName?name=".urlencode($name));
		if($res==-1 || $res=="false")
                        return false;
                else
                        return $res;
	}
   
        // get name by number
	function get_name_by_number($number)
	{
		return $this->call("Input.GetNameByNumber?number=".urlencode($number));
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
   	// set focus by attribute
	function set_focus_by_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("Input.SetFocusByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("Input.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("Input.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
	// add (or set) attribute by attribute
	function add_attribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr,$value_attr)
	{
               $res = $this->call("Input.AddAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
               if ($res =="false")
			return false;
		else
			return $res;
	}
	// add (or set) attribute by attribute in frame by number
	function add_attribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$value_attr,$frame_number)
	{
               $res = $this->call("Input.AddAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr))."&frame_number=".urlencode($frame_number);
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
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// set focus by attribute in frame by number
	function set_focus_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_num)
	{
		if ($this->call("Input.SetFocusByAttrInFrameByNum?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else
			return false;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};		
?>