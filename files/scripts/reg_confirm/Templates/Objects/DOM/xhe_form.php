<?php
// 2762
//////////////////////////////////////////////////// Form /////////////////////////////////////////////////
class XHEForm  extends XHEFormCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEForm($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Form";
	}
   	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("Form.GetCount");
	}
   	// get number by name
	function get_number_by_name($name)
	{
		return $this->call("Form.GetNumberByName?name=".urlencode($name));
	}
   	// get number by id
	function get_number_by_id($id)
	{
		return $this->call("Form.GetNumberByID?id=".urlencode($id));
	}
   	// get name by number
	function get_name_by_number($number)
	{
		return $this->call("Form.GetNameByNumber?number=".urlencode($number));
	}
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("Form.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("Form.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("Form.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("Form.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
        // add (or set) atribute by number
	function add_atribute_by_number($number,$name_atr,$value_atr)
	{
               return $this->z_add_atribute_by_number($number,$name_atr,$value_atr);
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// submit form by name
	function submit_by_name($name)
	{
		if ($this->call("Form.SubmitFormByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
   	// submit form by id
	function submit_by_id($id)
	{
		if ($this->call("Form.SubmitFormByID?id=".urlencode($id))=="true")
			return true;
		else
			return false;
	}
   	// submit form by number
	function submit_by_number($number)
	{
		if ($this->call("Form.SubmitFormByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get number by name
	function reset_by_name($name)
	{
		if ($this->call("Form.ResetFormByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
   	// get name by number
	function reset_by_number($number)
	{
		if ($this->call("Form.ResetFormByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// submit form by name
	function get_action_by_name($name)
	{
		return $this->call("Form.GetActionByName?name=".urlencode($name));
	}
   	// submit form by id
	function get_action_by_id($id)
	{
		return $this->call("Form.GetActionByID?id=".urlencode($id));
	}
   	// submit form by number
	function get_action_by_number($number)
	{
		return $this->call("Form.GetActionByNumber?number=".urlencode($number));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// submit form by name
	function get_content_by_name($name,$as_html)
	{
		return $this->call("Form.GetContentByName?name=".urlencode($name)."&as_html=".urlencode($as_html));
	}
   	// submit form by id
	function get_content_by_id($id,$as_html)
	{
		return $this->call("Form.GetContentByID?id=".urlencode($id)."&as_html=".urlencode($as_html));
	}
   	// submit form by number
	function get_content_by_number($number,$as_html)
	{
		return $this->call("Form.GetContentByNumber?number=".urlencode($number)."&as_html=".urlencode($as_html));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get all elements by name
	function get_all_elements_by_name($name,$element_type="")
	{
		return $this->call("Form.GetAllElementsByName?name=".urlencode($name)."&element_type=".urlencode($element_type));
	}
   	// get all elements by id
	function get_all_elements_by_id($id,$element_type="")
	{
		return $this->call("Form.GetAllElementsByID?id=".urlencode($id)."&element_type=".urlencode($element_type));
	}
   	// get all elements by numbers
	function get_all_elements_by_number($number,$element_type="")
	{
		return $this->call("Form.GetAllElementsByNumber?number=".urlencode($number)."&element_type=".urlencode($element_type));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};		
?>