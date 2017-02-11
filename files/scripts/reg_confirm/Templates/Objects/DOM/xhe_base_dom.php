<?php

class XHEBaseDOM extends XHEBaseObject
{
	//////////////////////////////////////////////////////////////// CLICK //////////////////////////////////////////////////////////////////////
        // click on element by name
	function z_click_by_name($name)
	{
		if ($this->call("$this->prefix.ClickByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
	// click on element by number 
	function z_click_by_number($number)
	{
		if ($this->call("$this->prefix.ClickByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
	// click on element by inner text
	function z_click_by_inner_text($text,$exactly)
	{
		if ($this->call("$this->prefix.ClickByInnerText?text=".urlencode($text)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
	// click on element by href
	function z_click_by_href($url,$exactly)
	{
		if ($this->call("$this->prefix.ClickByHRef?url=".urlencode($url)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// click on element by any atribute
	function z_click_by_atribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("$this->prefix.ClickByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// click on random element
	function z_click_random()
	{
		if ($this->call("$this->prefix.ClickRandom")=="true")
			return true;
		else
			return false;
	}
        // click on all elements
	function z_click_all()
	{
		if ($this->call("$this->prefix.ClickAll")=="true")
			return true;
		else
			return false;
	}
        // click on random element
	function z_click_random_in_frame($frame)
	{
		if ($this->call("$this->prefix.ClickRandomInFrame?frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////// SEND EVENT ///////////////////////////////////////////////////
	// send event to element by name
	function z_send_event_by_name($name,$event)
	{
		if ($this->call("$this->prefix.EventByName?name=".urlencode($name)."&event=".urlencode($event))=="true")
			return true;
		else
			return false;
	}
	// send event to element by number 
	function z_send_event_by_number($number,$event)
	{
		if ($this->call("$this->prefix.EventByNumber?number=".urlencode($number)."&event=".urlencode($event))=="true")
			return true;
		else
			return false;
	}
	// send event to element by inner text
	function z_send_event_by_inner_text($text,$exactly,$event)
	{
		if ($this->call("$this->prefix.EventByInnerText?text=".urlencode($text)."&exactly=".urlencode($exactly)."&event=".urlencode($event))=="true")
			return true;
		else
			return false;
	}
	// send event to element by href
	function z_send_event_by_href($url,$exactly,$event)
	{
		if ($this->call("$this->prefix.EventByHRef?url=".urlencode($url)."&exactly=".urlencode($exactly)."&event=".urlencode($event))=="true")
			return true;
		else
			return false;
	}
	// send event to element by any atribute
	function z_send_event_by_atribute($attr_name,$attr_value,$exactly,$event)
	{
		if ($this->call("$this->prefix.EventByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&event=".urlencode($event))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////// GET X Y ///////////////////////////////////////////////////
        // get x of element by name
	function z_get_x_by_name($name)
	{
		return $this->call("$this->prefix.GetXByName?name=".urlencode($name));

	}
	// get x of element by number 
	function z_get_x_by_number($number)
	{
        	return $this->call("$this->prefix.GetXByNumber?number=".urlencode($number));

	}
	// get x of element by inner text
	function z_get_x_by_inner_text($text,$exactly)
	{
		return $this->call("$this->prefix.GetXByInnerText?text=".urlencode($text)."&exactly=".urlencode($exactly));
	}
	// get x of element by href
	function z_get_x_by_href($href,$exactly)
	{
		return $this->call("$this->prefix.GetXByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
	}	
	// get x of element by any atribute
	function z_get_x_by_atribute($attr_name,$attr_value,$exactly)
	{
		return $this->call("$this->prefix.GetXByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly));
	}
        // get y of element by name
	function z_get_y_by_name($name)
	{
		return $this->call("$this->prefix.GetYByName?name=".urlencode($name));

	}
	// get y of element by number 
	function z_get_y_by_number($number)
	{
        	return $this->call("$this->prefix.GetYByNumber?number=".urlencode($number));

	}
	// get y of element by inner text
	function z_get_y_by_inner_text($text,$exactly)
	{
		return $this->call("$this->prefix.GetYByInnerText?text=".urlencode($text)."&exactly=".urlencode($exactly));
	}
	// get y of element by href
	function z_get_y_by_href($href,$exactly)
	{
		return $this->call("$this->prefix.GetYByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
	}	
	// get y of element by any atribute
	function z_get_y_by_atribute($attr_name,$attr_value,$exactly)
	{
		return $this->call("$this->prefix.GetYByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly));
	} 
        //////////////////////////////////////////////////////////// SEND EVENT IN FRAME ///////////////////////////////////////////////////
	// send event to element by name
	function z_send_event_by_name_in_frame($name,$event,$frame)
	{
		if ($this->call("$this->prefix.EventByNameInFrame?name=".urlencode($name)."&event=".urlencode($event)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// send event to element by number 
	function z_send_event_by_number_in_frame($number,$event,$frame)
	{
		if ($this->call("$this->prefix.EventByNumberInFrame?number=".urlencode($number)."&event=".urlencode($event)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// send event to element by inner text
	function z_send_event_by_inner_text_in_frame($text,$exactly,$event,$frame)
	{
		if ($this->call("$this->prefix.EventByInnerTextInFrame?text=".urlencode($text)."&exactly=".urlencode($exactly)."&event=".urlencode($event)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// send event to element by href
	function z_send_event_by_href_in_frame($url,$exactly,$event,$frame)
	{
		if ($this->call("$this->prefix.EventByHRefInFrame?url=".urlencode($url)."&exactly=".urlencode($exactly)."&event=".urlencode($event)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// send event to element by any atribute
	function z_send_event_by_atribute_in_frame($attr_name,$attr_value,$exactly,$event,$frame)
	{
		if ($this->call("$this->prefix.EventByAttrInFrame?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&event=".urlencode($event)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        /////////////////////////////////////////////////////////// IS EXIST /////////////////////////////////////////////////////////////////////////////// 
        // is element exist by name
        function z_is_exist_with_name($name)
        {
                if ($this->call("$this->prefix.IsExistByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
	// is element exist by inner text
	function z_is_exist_with_inner_text($text,$exactly)
	{
		if ($this->call("$this->prefix.IsExistsWithInnerText?text=".urlencode($text)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is element exist by href
	function z_is_exist_with_href($href,$exactly)
	{
		if ($this->call("$this->prefix.IsExistsWithHref?href=".urlencode($href)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is element exist by attribute
	function z_is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("$this->prefix.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        /////////////////////////////////////////////////////////// IS EXIST IN FRAME/////////////////////////////////////////////////////////////////////////////// 
        // is element exist by name in frame
        function z_is_exist_with_name_in_frame($name,$frame)
        {
                if ($this->call("$this->prefix.IsExistByNameInFrame?name=".urlencode($name)."&frame=".urlencode($frame))=="true")
                        return true;
                else
                        return false;
        }
	// is element exist by inner text in frame
	function z_is_exist_with_inner_text_in_frame($text,$frame,$exactly)
	{
		if ($this->call("$this->prefix.IsExistsWithInnerTextInFrame?text=".urlencode($text)."&frame=".urlencode($frame)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is element exist by href in frame
	function z_is_exist_with_href_in_frame($href,$frame,$exactly)
	{
		if ($this->call("$this->prefix.IsExistsWithHrefInFrame?href=".urlencode($href)."&frame=".urlencode($frame)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is element exist by attribute in frame
	function z_is_exist_with_attribute_in_frame($attr_name,$attr_value,$frame,$exactly)
	{
		if ($this->call("$this->prefix.IsExistsWithAttrInFrame?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&frame=".urlencode($frame)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        /////////////////////////////////////////////////////////// IS DISABLE /////////////////////////////////////////////////////////////////////////////// 	
        // is disabled by number
        function z_is_disabled_by_number($number)
        {
                if ($this->call("$this->prefix.IsDisabledByNumber?number=".urlencode($number))=="true")
                        return true;
                else
                        return false;
        }
        // is disabled by name
        function z_is_disabled_by_name($name)
        {
                if ($this->call("$this->prefix.IsDisabledByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove atribute by name
	function z_remove_atribute_by_name($name,$name_attr)
	{
		$res = $this->call("$this->prefix.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
	}
	// remove atribute by number
	function z_remove_atribute_by_number($number,$name_attr)
	{
	       $res = $this->call("$this->prefix.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
	       else
			return $res;
	}
	// remove atribute by name
	function z_remove_atribute_by_name_in_frame($name,$name_attr,$frame_number)
	{
		$res = $this->call("$this->prefix.RemoveAtributeByNameInFrame?name=".urlencode($name)."&name_attr=".urlencode($name_attr)."&frame_number=".urlencode($frame_number));
               if ($res =="false")
			return false;
	       else
			return $res;
	}
	// remove atribute by number
	function z_remove_atribute_by_number_in_frame($number,$name_attr,$frame_number)
	{
		$res= $this->call("$this->prefix.RemoveAtributeByNumberInFrame?number=".urlencode($number)."&name_attr=".urlencode($name_attr)."&frame_number=".urlencode($frame_number));
               if ($res =="false")
			return false;
	       else
			return $res;
	}
	// remove atribute by atribute in frame by number
	function z_remove_atribute_by_atribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
	{
               $res = $this->call("$this->prefix.RemoveAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&frame_number=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
	}
	// add (or set) atribute by number
	function z_add_atribute_by_number($number,$name_attr,$value_attr)
	{
               $res = $this->call("$this->prefix.AddAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
               if ($res =="false")
			return false;
		else
			return $res;
	}
        // add (or set) atribute by atribute
	function z_add_atribute_by_atribute($attr_name,$attr_value,$exactly,$name_attr,$value_attr)
	{
               $res = $this->call("$this->prefix.AddAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
               if ($res =="false")
			return false;
		else
			return $res;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get count of elements on page
	function z_get_count()
	{
		return $this->call("$this->prefix.GetCount");
	}
	// get inner texts of all elements on page
	function z_get_all_inner_texts($separator="<br>")
	{
		return $this->call("$this->prefix.GetAllInnerTexts?separator=".urlencode($separator));
	}
        // get inner texts of all elements on page in frame
	function z_get_all_inner_texts_in_frame($frame, $separator="<br>")
	{
		return $this->call("$this->prefix.GetAllInnerTextsInFrame?separator=".urlencode($separator)."&frame=".urlencode($frame));
	}	
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get name by number
	function z_get_name_by_number($number)
	{
		return $this->call("$this->prefix.GetNameByNumber?number=".urlencode($number));
	}
   	// get number by name
	function z_get_number_by_name($name)
	{
		return $this->call("$this->prefix.GetNumberByName?name=".urlencode($name));
	}
   	// get number by inner text
	function z_get_number_by_inner_text($innertext,$exactly=true)
	{
		return $this->call("$this->prefix.GetNumberByInnerText?innertext=".urlencode($innertext)."&exactly=".urlencode($exactly));
	}
        // get number by atribute
        function z_get_number_by_atribute($attr_name,$attr_value,$exactly=true)
        {
               return $this->call("$this->prefix.GetNumberByAtribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly));
        }
	// get inner text by name
	function z_get_inner_text_by_name($name)
	{
		return $this->call("$this->prefix.GetInnerTextByName?name=".urlencode($name));
	}
	// get inner text by number
	function z_get_inner_text_by_number($number)
	{
		return $this->call("$this->prefix.GetInnerTextByNumber?number=".urlencode($number));
	}
	// get inner text by name
	function z_get_inner_text_by_href($href,$exactly=true)
	{
		return $this->call("$this->prefix.GetInnerTextByHref?href=".urlencode($href)."&exactly=".urlencode($exactly));
	}
        // get href by name
	function z_get_href_by_name($name)
	{
		return $this->call("$this->prefix.GetHRefByName?name=".urlencode($name));
	}
	// get href by number
	function z_get_href_by_number($number)
	{
		return $this->call("$this->prefix.GetHRefByNumber?number=".urlencode($number));
	}
        // get href by inner text
	function z_get_href_by_inner_text($inner_text,$exactly=false)
	{
		return $this->call("$this->prefix.GetHRefByInnerText?inner_text=".urlencode($inner_text)."&exactly=".urlencode($exactly));
	}
        // get atribute by name
        function z_get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("$this->prefix.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function z_get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("$this->prefix.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by atribute
        function z_get_atribute_by_atribute($attr_name,$attr_value,$exactly,$name_attr)
        {
               $res = $this->call("$this->prefix.GetAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// set focus to element by name
	function z_set_focus_by_name($name)
	{
		if ($this->call("$this->prefix.SetFocusByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
	// set focus to element by name
	function z_set_focus_by_number($number)
	{
		if ($this->call("$this->prefix.SetFocusByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
   	// set focus to element by inner text
	function z_set_focus_by_inner_text($innertext,$exactly=true)
	{
		if ($this->call("$this->prefix.SetFocusByInnerText?innertext=".urlencode($innertext)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
   	// set focus to element by href
	function z_set_focus_by_href($href,$exactly=true)
	{
		if ($this->call("$this->prefix.SetFocusByHref?href=".urlencode($href)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
   	// set focus to element by atribute
	function z_set_focus_by_atribute($attr_name,$attr_value,$exactly=true)
	{
		if ($this->call("$this->prefix.SetFocusByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////// SET INNER TEXT //////////////////////////////////////////////////////////////////////
        // set inner text by name
	function z_set_inner_text_by_name($name,$text,$frame)
	{
		if ($this->call("$this->prefix.SetInnerTextByName?name=".urlencode($name)."&text=".urlencode($text)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// set inner text by number 
	function z_set_inner_text_by_number($number,$text,$frame)
	{
		if ($this->call("$this->prefix.SetInnerTextByNumber?number=".urlencode($number)."&text=".urlencode($text)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}	
	// set inner text by any atribute
	function z_set_inner_text_by_atribute($attr_name,$attr_value,$text,$exactly,$frame)
	{
		if ($this->call("$this->prefix.SetInnerTextByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&text=".urlencode($text)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////// SET INNER HTML //////////////////////////////////////////////////////////////////////
        // set inner html by name
	function z_set_inner_html_by_name($name,$html,$frame)
	{
		if ($this->call("$this->prefix.SetInnerHtmlByName?name=".urlencode($name)."&html=".urlencode($html)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// set inner html by number 
	function z_set_inner_html_by_number($number,$html,$frame)
	{
		if ($this->call("$this->prefix.SetInnerHtmlByNumber?number=".urlencode($number)."&html=".urlencode($html)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}	
	// set inner html by any atribute
	function z_set_inner_html_by_atribute($attr_name,$attr_value,$html,$exactly,$frame)
	{
		if ($this->call("$this->prefix.SetInnerHtmlByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&html=".urlencode($html)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
}
?>