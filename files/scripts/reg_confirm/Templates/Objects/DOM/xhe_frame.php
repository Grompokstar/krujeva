<?php
// 2744
//////////////////////////////////////////////////// Frame /////////////////////////////////////////////////
class XHEFrame  extends XHEFrameCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEFrame($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Frame";
	}
   	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("Frame.GetCount");
	}
   	// get number by name
	function get_number_by_name($name)
	{
		return $this->call("Frame.GetNumberByName?name=".urlencode($name));
	}
   	// get name by number
	function get_name_by_number($number)
	{
		return $this->call("Frame.GetNameByNumber?number=".urlencode($number));
	}
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("Frame.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("Frame.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get number by name
	function get_width_by_name($name)
	{
		return $this->call("Frame.GetWidthByName?name=".urlencode($name));
	}
   	// get name by number
	function get_width_by_number($number)
	{
		return $this->call("Frame.GetWidthByNumber?number=".urlencode($number));
	}
   	// get number by name
	function get_height_by_name($name)
	{
		return $this->call("Frame.GetHeightByName?name=".urlencode($name));
	}
   	// get name by number
	function get_height_by_number($number)
	{
		return $this->call("Frame.GetHeightByNumber?number=".urlencode($number));
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
	// get y of element by any atribute
	function get_y_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->z_get_y_by_atribute($attr_name,$attr_value,$exactly);
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	// get number by name
	function get_src_by_name($name)
	{
		return $this->call("Frame.GetSrcByName?name=".urlencode($name));
	}
   	// get name by number
	function get_src_by_number($number)
	{
		return $this->call("Frame.GetSrcByNumber?number=".urlencode($number));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get name by number
	function get_all_elements_by_number($number)
	{
		return $this->call("Frame.GetAllElementsByNumber?number=".urlencode($number));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get body by number
	function get_body_by_number($number,$as_html)
	{
		return $this->call("Frame.GetFrameBodyByNumber?number=".urlencode($number)."&as_html=".urlencode($as_html));
	}
   	// set body by number
	function set_body_by_number($number,$html_body)
	{
		return $this->call("Frame.SetFrameBodyByNumber?number=".urlencode($number)."&html_body=".urlencode($html_body));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("Frame.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("Frame.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
        // add (or set) atribute by number
	function add_atribute_by_number($number,$name_atr,$value_atr)
	{
               return $this->z_add_atribute_by_number($number,$name_atr,$value_atr);
	}
        // send event to element by any attribute
	function send_event_by_attribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};		
?>