<?php
// 2751
/////////////////////////////////////////////////// CheckButtton /////////////////////////////////////////////////
class XHECheckButton  extends XHECheckButtonCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHECheckButton($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "CheckButton";
	}
       	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

   	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// click on all elements
	function click_all()
	{
		return $this->z_click_all();
	}
        // click on random element
	function click_random()
	{
		return $this->z_click_random();
	}
        // click by name
	function click_by_name($name)
	{
		if ($this->call("CheckButton.ClickByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
	// click by number
	function click_by_number($number)
	{
		if ($this->call("CheckButton.ClickByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
	// click on checkbox by atribute
	function click_by_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
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
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("CheckButton.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        // click on random element in frame
	function click_random_in_frame($frame)
	{
		return $this->z_click_random_in_frame($frame);
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
	// set all check 
	function set_all_checked($value) 
	{
		if ($this->call("CheckButton.SetAllChecked?value=".urlencode($value))=="true" )
			return true;
		else
			return false;
	}
        // set check state
	function set_checked_by_name($name,$value) 
	{
		if ($this->call("CheckButton.SetCheckedByName?name=".urlencode($name)."&value=".urlencode($value))=="true" )
			return true;
		else
			return false;
	}
        // set check state
	function set_checked_by_value($value,$set_value) 
	{
		if ($this->call("CheckButton.SetCheckedByValue?value=".urlencode($value)."&set_value=".urlencode($set_value))=="true" )
			return true;
		else
			return false;
	}
	// set check state
	function set_checked_by_number($number,$value) 
	{
	        if ($this->call("CheckButton.SetCheckedByNumber?number=".urlencode($number)."&value=".urlencode($value))=="true")
			return true; 
		else
			return false; 
	}
         
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// is checked by name
	function is_checked_by_name($name) 
	{
		if ($this->call("CheckButton.IsCheckedByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
        // is checked by value
	function is_checked_by_value($value) 
	{
		if ($this->call("CheckButton.IsCheckedByValue?value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
	// is checked by number
	function is_checked_by_number($number) 
	{
		if ($this->call("CheckButton.IsCheckedByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
			
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("CheckButton.GetCount");
	}
	// get name by number
	function get_name_by_number($number)
	{
		return $this->call("CheckButton.GetNameByNumber?number=".urlencode($number));
	}
   	// click by name
	function get_number_by_name($name)
	{
		return $this->call("CheckButton.GetNumberByName?name=".urlencode($name));
	}
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("CheckButton.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("CheckButton.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute
        function get_atribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr)
        {
               $res = $this->call("CheckButton.GetAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute in frame by number
        function get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               $res = $this->call("CheckButton.GetAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&name_attr=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
        }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // is exist by name
        function is_exist_with_name($name)
        {
                if ($this->call("CheckButton.IsExistByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
	// is exist with attribute
	function is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("CheckButton.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is exist with attribute in frame by number
	function is_exist_with_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_number)
	{
		if ($this->call("CheckButton.IsExistsWithAttrInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_number=".urlencode($frame_number))=="true")
			return true;
		else
			return false;
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("CheckButton.GetCountInFrameByNum?number=".urlencode($number));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("CheckButton.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("CheckButton.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
	// add (or set) attribute by attribute
	function add_attribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr,$value_attr)
	{
               $res = $this->call("CheckButton.AddAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
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
	// add (or set) attribute by attribute in frame by number
	function add_attribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$value_attr,$frame_number)
	{
               $res = $this->call("CheckButton.AddAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr))."&frame_number=".urlencode($frame_number);
               if ($res =="false")
			return false;
		else
			return $res;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		if ($this->call("CheckButton.SetFocusByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// set focus by attribute in frame by number
	function set_focus_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_num)
	{
		if ($this->call("CheckButton.SetFocusByAttrInFrameByNum?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else
			return false;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>