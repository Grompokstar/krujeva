<?php
// 2766
//////////////////////////////////////////////////// SelectElement /////////////////////////////////////////////////
class XHESelectElement extends XHESelectElementCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHESelectElement($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "SelectElement";
	}
  	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// click on listbox by atribute
	function click_by_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("SelectElement.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
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
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// select value by name
	function select_value_by_name($name,$value)
	{
		if ($this->call("SelectElement.SelectValueByName?name=".urlencode($name)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by text
	function select_value_by_num($value,$num)
	{
		if ($this->call("SelectElement.SelectValueByNum?value=".urlencode($value)."&num=".urlencode($num))=="true")
			return true;
		else
			return false;
	}
        // select random value by name
	function select_random_value_by_name($name)
	{
		if ($this->call("SelectElement.SelectRandomValueByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
   	// select random value by num 
	function select_random_value_by_number($num)
	{
		if ($this->call("SelectElement.SelectRandomValueByNum?num=".urlencode($num))=="true")
			return true;
		else
			return false;
	}
   	// select value bu name
	function select_part_value_by_name($name,$value,$exactly)
	{
		if ($this->call("SelectElement.SelectPartValueByName?name=".urlencode($name)."&value=".urlencode($value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
	// select value with num by name
	function select_num_value_by_name($name,$num)
	{
		if ($this->call("SelectElement.SelectNumValueByName?name=".urlencode($name)."&num=".urlencode($num))=="true")
			return true;
		else
			return false;
	}
	// select value with num by number
	function select_num_value_by_number($number,$num)
	{
		if ($this->call("SelectElement.SelectNumValueByNumber?number=".urlencode($number)."&num=".urlencode($num))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by inner name
	function select_num_by_inner_name($num,$innername)
	{
		if ($this->call("SelectElement.SelectNumByInnerName?num=".urlencode($num)."&innername=".urlencode($innername))=="true")
			return true;
		else
			return false;
	}
   	// select value with name by inner name
	function select_name_by_inner_name($name,$innername)
	{
		if ($this->call("SelectElement.SelectNameByInnerName?name=".urlencode($name)."&innername=".urlencode($innername))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// select value bu name
	function select_option_text_by_name($name,$value)
	{
		if ($this->call("SelectElement.SelectValueByName?name=".urlencode($name)."&value=".urlencode($value))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by text
	function select_option_text_by_num($value,$num)
	{
		if ($this->call("SelectElement.SelectValueByNum?value=".urlencode($value)."&num=".urlencode($num))=="true")
			return true;
		else
			return false;
	}
    	// select value with num by inner name
	function select_option_value_by_num($num,$innername)
	{
		if ($this->call("SelectElement.SelectNumByInnerName?num=".urlencode($num)."&innername=".urlencode($innername))=="true")
			return true;
		else
			return false;
	}
   	// select value with name by inner name
	function select_option_value_by_name($name,$innername)
	{
		if ($this->call("SelectElement.SelectNameByInnerName?name=".urlencode($name)."&innername=".urlencode($innername))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get selected element index by name
	function get_selected_index_by_name($name)
	{
		return $this->call("SelectElement.GetSelectedIndexByName?name=".urlencode($name));
	}
	// get selected element index by number
	function get_selected_index_by_number($number)
	{
		return $this->call("SelectElement.GetSelectedIndexByNumber?number=".urlencode($number));
	}
        // get selected element value by name
	function get_value_by_name($name)
	{
		return $this->call("SelectElement.GetValueByName?name=".urlencode($name));
	}
	// get selected element value by number
	function get_value_by_number($number)
	{
		return $this->call("SelectElement.GetValueByNumber?number=".urlencode($number));
	}
        // get current selected option text by name
	function get_cur_option_text_by_name($name)
	{
		return $this->call("SelectElement.GetCurOptionTextByName?name=".urlencode($name));
	}
	// get current selected option text by number
	function get_cur_option_text_by_number($number)
	{
		return $this->call("SelectElement.GetCurOptionTextByNumber?number=".urlencode($number));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("SelectElement.GetCount");
	}
	// get select element size 
	function get_size_by_name($name)
	{
		return $this->call("SelectElement.GetSizeByName?name=".urlencode($name));
	}
	// get select element size 
	function get_size_by_number($number)
	{
		return $this->call("SelectElement.GetSizeByNumber?number=".urlencode($number));
	}
	// get select element length 
	function get_length_by_name($name)
	{
		return $this->call("SelectElement.GetLengthByName?name=".urlencode($name));
	}
	// get select element length 
	function get_length_by_number($number)
	{
		return $this->call("SelectElement.GetLengthByNumber?number=".urlencode($number));
	}
	// get select element type 
	function get_type_by_name($name)
	{
		return $this->call("SelectElement.GetTypeByName?name=".urlencode($name));
	}
	// get select element type 
	function get_type_by_number($number)
	{
		return $this->call("SelectElement.GetTypeByNumber?number=".urlencode($number));
	}
        // get all elements text by listbox name 
	function get_all_texts_by_name($name)
	{
		return $this->call("SelectElement.GetAllTextsByName?name=".urlencode($name));
	}
        // get all elements text by listbox number 
	function get_all_texts_by_number($number)
	{
		return $this->call("SelectElement.GetAllTextsByNumber?number=".urlencode($number));
	}
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("SelectElement.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("SelectElement.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute
        function get_atribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr)
        {
               $res = $this->call("SelectElement.GetAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute in frame by number
        function get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               $res = $this->call("SelectElement.GetAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&name_attr=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
        }
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get element name by number 
	function get_name_by_number($number)
	{
		return $this->call("SelectElement.GetNameByNumber?number=".urlencode($number));
	}
   	// click by name
	function get_number_by_name($name)
	{
		return $this->call("SelectElement.GetNumberByName?name=".urlencode($name));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // is exist by name
        function is_exist_with_name($name)
        {
                if ($this->call("SelectElement.IsExistByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
	// is exist with attribute
	function is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("SelectElement.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is exist with attribute in frame by number
	function is_exist_with_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_number)
	{
		if ($this->call("SelectElement.IsExistsWithAttrInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_number=".urlencode($frame_number))=="true")
			return true;
		else
			return false;
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("SelectElement.GetCountInFrameByNum?number=".urlencode($number));
	}
   	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
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
		if ($this->call("SelectElement.SetFocusByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// select value bu name
	function multi_select_name_by_num($name, $values)
	{
		if ($this->call("SelectElement.MultiSelectNamebyNum?name=".urlencode($name)."&values=".urlencode($values))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by text
	function multi_select_num_by_num($num, $values)
	{
		if ($this->call("SelectElement.MultiSelectNumbyNum?num=".urlencode($num)."&values=".urlencode($values))=="true")
			return true;
		else
			return false;
	}
   	// select value bu name
	function multi_select_name_by_text($name, $values)
	{
		if ($this->call("SelectElement.MultiSelectNamebyText?name=".urlencode($name)."&values=".urlencode($values))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by text
	function multi_select_num_by_text($num, $values)
	{
		if ($this->call("SelectElement.MultiSelectNumbyText?num=".urlencode($num)."&values=".urlencode($values))=="true")
			return true;
		else
			return false;
	}
   	// select value bu name
	function multi_select_name_by_inner_name($name, $values)
	{
		if ($this->call("SelectElement.MultiSelectNamebyInnerName?name=".urlencode($name)."&values=".urlencode($values))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by text
	function multi_select_num_by_inner_name($num, $values)
	{
		if ($this->call("SelectElement.MultiSelectNumbyInnerName?num=".urlencode($num)."&values=".urlencode($values))=="true")
			return true;
		else
			return false;
	}
   	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// select value by name
	function select_value_within_iframe_by_name($name,$value,$frame)
	{
		if ($this->call("SelectElement.SelectValueWithinIframeByName?name=".urlencode($name)."&value=".urlencode($value)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by text
	function select_value_within_iframe_by_num($value,$num,$frame)
	{
		if ($this->call("SelectElement.SelectValueWithinIframeByNum?value=".urlencode($value)."&num=".urlencode($num)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
   	// select value bu name
	function select_part_value_within_iframe_by_name($name,$value,$exactly,$frame)
	{
		if ($this->call("SelectElement.SelectPartValueWithinIframeByName?name=".urlencode($name)."&value=".urlencode($value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// select value with num by name
	function select_num_value_within_iframe_by_name($name,$num,$frame)
	{
		if ($this->call("SelectElement.SelectNumValueWithinIframeByName?name=".urlencode($name)."&num=".urlencode($num)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// select value with num by number
	function select_num_value_within_iframe_by_number($number,$num,$frame)
	{
		if ($this->call("SelectElement.SelectNumValueWithinIframeByNumber?number=".urlencode($number)."&num=".urlencode($num)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
   	// select value with num by inner name
	function select_num_within_iframe_by_inner_name($num,$innername,$frame)
	{
		if ($this->call("SelectElement.SelectNumWithinIframeByInnerName?num=".urlencode($num)."&innername=".urlencode($innername)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
   	// select value with name by inner name
	function select_name_within_iframe_by_inner_name($name,$innername,$frame)
	{
		if ($this->call("SelectElement.SelectNameWithinIframeByInnerName?name=".urlencode($name)."&innername=".urlencode($innername)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("SelectElement.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("SelectElement.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
	// add (or set) attribute by attribute
	function add_attribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr,$value_attr)
	{
               $res = $this->call("SelectElement.AddAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
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
               $res = $this->call("SelectElement.AddAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr))."&frame_number=".urlencode($frame_number);
               if ($res =="false")
			return false;
		else
			return $res;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// add option by name
	function add_option_by_name($name,$text,$value)
	{
		return $this->call("SelectElement.AddOptionByName?name=".urlencode($name)."&text=".urlencode($text)."&value=".urlencode($value));
	}
	// add option by number
	function add_option_by_number($number,$text,$value)
	{
		return $this->call("SelectElement.AddOptionByNumber?number=".urlencode($number)."&text=".urlencode($text)."&value=".urlencode($value));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// set focus by attribute in frame by number
	function set_focus_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_num)
	{
		if ($this->call("SelectElement.SetFocusByAttrInFrameByNum?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else
			return false;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>