<?php
// 2735
//////////////////////////////////////////////////// Element - several Functional for work with elements on web pages ////////////////////////////////////////
class XHEElement  extends XHEElementCompatible
{
        ///////////////////////////////////////////////////////// SERVICE VARIABLES //////////////////////////////////////////////////////////////
        // server address and port
        var $server;
        // server password
        var $password;
        ///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
        // server initialization
        function XHEElement($server,$password="")
        {    
                $this->server = $server;
                $this->password = $password;
		$this->prefix = "Element";

        }

        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("Element.GetCount");
	}
        // get value of element by name
        function get_element_value_by_name($name)
        {
                return $this->call("Element.GetElementValueByName?name=".urlencode($name));
        }
        // get inner html of element by name
        function get_element_innerHtml_by_name($name)
        {
                return $this->call("Element.GetElementInnerHtmlByName?name=".urlencode($name));
        }
        // get element inner text by id
        function get_element_innerText_by_id($id)
        {
                return $this->call("Element.GetElementInnerTextByID?id=".urlencode($id));
        }
        // get inner html of element by id
        function get_element_innerHtml_by_id($id)
        {
                return $this->call("Element.GetElementInnerHtmlByID?id=".urlencode($id));
        }
        // get element inner text by mane
        function get_element_innerText_by_name($name)
        {
                return $this->call("Element.GetElementInnerTextByName?name=".urlencode($name));
        }
        // set value of element by mane
        function set_element_value_by_name($name,$text)
        {
                if ($this->call("Element.SetElementValueByName?name=".urlencode($name)."&text=".urlencode($text))=="true")
                        return true;
                else
                        return false;
        }
        // get attribute of element by name
        function get_element_attribute_by_name($name,$attribute)
        {
                return $this->call("Element.GetElementAtributeByName?name=".urlencode($name)."&attribute=".urlencode($attribute));
        }
        // get attribute of element by number
        function get_element_attribute_by_number($number,$attribute)
        {
                return $this->call("Element.GetElementAtributeByNumber?number=".urlencode($number)."&attribute=".urlencode($attribute));
        }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // get all attributes of element by name
        function get_all_attributes_by_name($name)
        {
                return $this->call("Element.GetAllAtributesByName?name=".urlencode($name));
        }
        // get all attributes of element by number
        function get_all_attributes_by_number($number)
        {
                return $this->call("Element.GetAllAtributesByNumber?number=".urlencode($number));
        }
        // get all attributes values of element by name
        function get_all_attributes_values_by_name($name)
        {
                return $this->call("Element.GetAllAtributesValuesByName?name=".urlencode($name));
        }
        // get all attributes values of element by number
        function get_all_attributes_values_by_number($number)
        {
                return $this->call("Element.GetAllAtributesValuesByNumber?number=".urlencode($number));
        }
        // get all events of element by name
        function get_all_events_by_name($name)
        {
                return $this->call("Element.GetAllEventsByName?name=".urlencode($name));
        }
        // get all events of element by number
        function get_all_events_by_number($number)
        {
                return $this->call("Element.GetAllEventsByNumber?number=".urlencode($number));
        }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // is exist by name
        function is_exist_with_name($name)
        {
                if ($this->call("Element.IsExistByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
	// is exist with attribute
	function is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("Element.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("Element.GetCountInFrameByNum?number=".urlencode($number));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // set value of element by mane
        function set_element_attribute_by_name($name,$attribute,$value)
        {
                if ($this->call("Element.SetElementAtributeByName?name=".urlencode($name)."&attribute=".urlencode($attribute)."&value=".urlencode($value))=="true")
                        return true;
                else
                        return false;
        }
        /////////////////////////////////////////////////////
        // click on element by name
        function click_on_element_by_name($name)
        {
                if ($this->call("Element.ClickOnElementByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        
        }
        // click on element by number
        function click_on_element_by_number($number)
        {
                if ($this->call("Element.ClickOnElementByNumber?number=".urlencode($number))=="true")
                        return true;
                else
                        return false;
        }
        // click on element by inner text
        function click_on_element_by_inner_text($inner_text,$exactly="true")
        {
                if ($this->call("Element.ClickOnElementByInnerText?inner_text=".urlencode($inner_text)."&exactly=".urlencode($exactly))=="true")
                        return true;
                else
                        return false;
        }
	// click on elemnt by atribute
	function click_by_atribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->z_click_by_atribute($atr_name,$atr_value,$exactly);
	}	
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // click on element by number in frame
        function click_on_element_by_name_withiniframe($name,$frame)
        {
                if  ($this->call("Element.ClickOnElementByNameWithinIframe?name=".urlencode($name)."&frame=".urlencode($frame))=="true")
                        return true;
                else
                        return false;
        }
        // click on element by inner text in frame
        function click_on_element_by_inner_text_withiniframe($inner_text,$frame,$exactly)
        {
                if ($this->call("Element.ClickOnElementByInnerTextWithinIframe?inner_text=".urlencode($inner_text)."&frame=".urlencode($frame)."&exactly=".urlencode($exactly))=="true")
                        return true;
                else
                        return false;
        }
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("Element.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}	
        /////////////////////////////////////////////////////// SEND EVENT //////////////////////////////////////////////////////////
	// send event to anchor by name
	function send_event_by_name($name,$event)
	{
		return $this->z_send_event_by_name($name,$event);
	}
	// send event to anchor by number 
	function send_event_by_number($number,$event)
	{
		return $this->z_send_event_by_number($number,$event);
	}
	// send event to anchor by inner text
	function send_event_by_inner_text($text,$exactly,$event)
	{
		return $this->z_send_event_by_inner_text($text,$exactly,$event);
	}
	// send event to anchor by href
	function send_event_by_href($url,$exactly,$event)
	{
		return $this->z_send_event_by_href($url,$exactly,$event);
	}
	// send event to element by any attribute
	function send_event_by_atribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
	}
        /////////////////////////////////////////////////////// SEND EVENT IN FRAME //////////////////////////////////////////////////////////
	// send event to anchor by name in frame
	function send_event_by_name_in_frame($name,$event,$frame)
	{
		return $this->z_send_event_by_name_in_frame($name,$event,$frame);
	}
	// send event to anchor by number in frame
	function send_event_by_number_in_frame($number,$event,$frame)
	{
		return $this->z_send_event_by_number_in_frame($number,$event,$frame);
	}
	// send event to anchor by inner text in frame
	function send_event_by_inner_text_in_frame($text,$exactly,$event,$frame)
	{
		return $this->z_send_event_by_inner_text_in_frame($text,$exactly,$event,$frame);
	}
	// send event to anchor by href in frame
	function send_event_by_href_in_frame($url,$exactly,$event,$frame)
	{
		return $this->z_send_event_by_href_in_frame($url,$exactly,$event,$frame);
	}
	// send event to element by any attribute in frame
	function send_event_by_atribute_in_frame($atr_name,$atr_value,$exactly,$event,$frame)
	{
		return $this->z_send_event_by_atribute_in_frame($atr_name,$atr_value,$exactly,$event,$frame);
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // get Left Offset of element by name (relative all webpage)
        function get_left_offset_on_page_by_name($name)
        {
                return $this->call("Element.GetLeftOffsetOnPageByName?name=".urlencode($name));
        }
        // get Top Offset of element by name (relative all webpage)
        function get_top_offset_on_page_by_name($name)
        {
                return $this->call("Element.GetTopOffsetOnPageByName?name=".urlencode($name));
        }
        // get Left Offset of element by href (relative all webpage)
        function get_left_offset_on_page_by_href($href,$exactly)
        {
                return $this->call("Element.GetLeftOffsetOnPageByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
        }
        // get Top Offset of element by href (relative all webpage)
        function get_top_offset_on_page_by_href($href,$exactly)
        {
                return $this->call("Element.GetTopOffsetOnPageByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
        }
        // get Left Offset of element by tag by number (relative all webpage)
        function get_left_offset_on_page_by_tag_by_number($tag,$number)
        {
                return $this->call("Element.GetLeftOffsetOnPageByTagByNumber?tag=".urlencode($tag)."&number=".urlencode($number));
        }
        // get Top Offset of element by tag by number (relative all webpage)
        function get_top_offset_on_page_by_tag_by_number($tag,$number)
        {
                return $this->call("Element.GetTopOffsetOnPageByTagByNumber?tag=".urlencode($tag)."&number=".urlencode($number));
        }
        // get Width of element by name
        function get_width_by_name($name)
        {
                return $this->call("Element.GetWidthByName?name=".urlencode($name));
        }
        // get Height of element by name
        function get_height_by_name($name)
        {
                return $this->call("Element.GetHeightByName?name=".urlencode($name));
        }
        // get Width of element by href
        function get_width_by_href($href,$exactly)
        {
                return $this->call("Element.GetWidthByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
        }
        // get Height of element by href
        function get_height_by_href($href,$exactly)
        {
                return $this->call("Element.GetHeightByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("Element.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("Element.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
	// add attribute by name
	function add_attribute_by_name($name,$name_attr,$value_attr)
	{
		return $this->call("Element.AddAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
	}
	// add attribute by number
	function add_attribute_by_number($number,$name_attr,$value_attr)
	{
		return $this->call("Element.AddAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
	}        
	//////////////////////////////////////////////////////////////// SET INNER TEXT //////////////////////////////////////////////////////////////////////
        // set inner text by name
	function set_inner_text_by_name($name,$text,$frame=-1)
	{
	         return $this->z_set_inner_text_by_name($name,$text,$frame);
	}
	// set inner text by number 
	function set_inner_text_by_number($number,$text,$frame=-1)
	{
		 return $this->z_set_inner_text_by_number($number,$text,$frame);
	}	
	// set inner text by any atribute
	function set_inner_text_by_atribute($attr_name,$attr_value,$text,$exactly=true,$frame=-1)
	{
        	return $this->z_set_inner_text_by_atribute($attr_name,$attr_value,$text,$exactly,$frame);
	}
        //////////////////////////////////////////////////////////////// SET INNER HTML //////////////////////////////////////////////////////////////////////
        // set inner html by name
	function set_inner_html_by_name($name,$html,$frame=-1)
	{
		return $this->z_set_inner_html_by_name($name,$html,$frame);
	}
	// set inner html by number 
	function set_inner_html_by_number($number,$html,$frame=-1)
	{
		return $this->z_set_inner_html_by_number($number,$html,$frame);
	}	
	// set inner html by any atribute
	function set_inner_html_by_atribute($attr_name,$attr_value,$html,$exactly=true,$frame=-1)
	{
		return $this->z_set_inner_html_by_atribute($attr_name,$attr_value,$html,$exactly,$frame);
	}
};      
?>