<?php
// 2707
//////////////////////////////////////////////////////////////// Anchor ///////////////////////////////////////////////////////////
class XHEAnchor extends XHEAnchorCompatible
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS //////////////////////////////////////////////
	// server initialization
	function XHEAnchor($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Anchor";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL ///////////////////////////////////////////////////

	////////////////////////////////////////////////////////////// CLICK //////////////////////////////////////////////////////
        // click on anchor by name
	function click_by_name($name)
	{
		return $this->z_click_by_name($name);
	}
	// click on anchor by number 
	function click_by_number($number)
	{
		return $this->z_click_by_number($number);
	}
	// click on anchor by inner text
	function click_by_inner_text($text,$exactly=true)
	{
		return $this->z_click_by_inner_text($text,$exactly);
	}
	// click on anchor by href
	function click_by_href($url,$exactly=true)
	{
		return $this->z_click_by_href($url,$exactly);
	}	
	// click on anchor by any attribute
	function click_by_attribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->click_by_atribute($atr_name,$atr_value,$exactly);
	}	
        // click on random element
	function click_random()
	{
		return $this->z_click_random();
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
	function send_event_by_attribute($atr_name,$atr_value,$exactly,$event)
	{
		return $this->z_send_event_by_atribute($atr_name,$atr_value,$exactly,$event);
	}
        //////////////////////////////////////////////////////////// GET INFO BY ALL ANCHORS ////////////////////////////////////////////
	// get count of anchors on page
	function get_count()
	{
		return $this->z_get_count();
	}
	// get inner texts of all anchors on page
	function get_all_inner_texts($separator="<br>")
	{
		return $this->z_get_all_inner_texts($separator);
	}	
	//////////////////////////////////////////////////////////// IS EXIST ///////////////////////////////////////////////////////////
        // is anchor exist by name
        function is_exist_by_name($name)
        {
		return $this->z_is_exist_with_name($name);
        }
	// is anchor exist by inner text
	function is_exist_by_inner_text($text,$exactly=true)
	{
		return $this->z_is_exist_with_inner_text($text,$exactly);
	}	
	// is anchor exist by href
	function is_exist_by_href($href,$exactly=true)
	{
		return $this->z_is_exist_with_href($href,$exactly);
	}	
	// is anchor exist by attribute
	function is_exist_by_attribute($atr_name,$atr_value,$exactly=true)
	{
		return $this->is_exist_with_atribute($atr_name,$atr_value,$exactly);
	}	
	/////////////////////////////////////////////////////////// SET INFO ////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_atr)
	{
		return $this->remove_atribute_by_name($name,$name_atr);
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_atr)
	{
		return $this->remove_atribute_by_number($number,$name_atr);
	}
        // add (or set) attribute by number
	function add_attribute_by_number($number,$name_atr,$value_atr)
	{
               return $this->add_atribute_by_number($number,$name_atr,$value_atr);
	}
	// add (or set) attribute by attribute
	function add_attribute_by_attribute($atr_name,$atr_value,$exactly,$name_atr,$value_atr)
	{
               return $this->add_atribute_by_atribute($atr_name,$atr_value,$exactly,$name_atr,$value_atr);
	}    
	//////////////////////////////////////////////////////// GET INFO ////////////////////////////////////////////////////////////////
	// get anchor name by number
	function get_name_by_number($number)
	{
		return $this->z_get_name_by_number($number);
	}
   	// get anchor number by name
	function get_number_by_name($name)
	{
		return $this->z_get_number_by_name($name);
	}
   	// get anchor number by inner text
	function get_number_by_inner_text($innertext,$exactly=true)
	{
		return $this->z_get_number_by_inner_text($innertext,$exactly);
	}
        // get number by attribute
        function get_number_by_attribute($atr_name,$atr_value,$exactly=true)
        {
               return $this->get_number_by_atribute($atr_name,$atr_value,$exactly);
        }
	// get inner text by name
	function get_inner_text_by_name($name)
	{
		return $this->z_get_inner_text_by_name($name);
	}
	// get inner text by number
	function get_inner_text_by_number($number)
	{
		return $this->z_get_inner_text_by_number($number);
	}
	// get inner text by name
	function get_inner_text_by_href($href,$exactly=true)
	{
		return $this->z_get_inner_text_by_href($href,$exactly);
	}
        // get href by name
	function get_href_by_name($name)
	{
		return $this->z_get_href_by_name($name);
	}
	// get href by number
	function get_href_by_number($number)
	{
		return $this->z_get_href_by_number($number);
	}
        // get href by inner text
	function get_href_by_inner_text($inner_text,$exactly=false)
	{
		return $this->z_get_href_by_inner_text($inner_text,$exactly);
	}
        // get attribute by name
        function get_attribute_by_name($name,$name_atr)
        {
               return $this->get_atribute_by_name($name,$name_atr);
        }
        // get attribute by number
        function get_attribute_by_number($number,$name_atr)
        {
               return $this->get_atribute_by_number($number,$name_atr);
        }
        // get attribute by attribute
        function get_attribute_by_attribute($atr_name,$atr_value,$exactly,$name_atr)
        {
               return $this->get_atribute_by_attribute($atr_name,$atr_value,$exactly,$name_atr);
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
	// get x of element by href
	function get_x_by_href($href,$exactly=true)
	{
		return $this->z_get_x_by_href($href,$exactly);
	}	
	// get x of element by any attribute
	function get_x_by_attribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->get_x_by_atribute($attr_name,$attr_value,$exactly);
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
	// get y of element by href
	function get_y_by_href($href,$exactly=true)
	{
		return $this->z_get_y_by_href($href,$exactly);
	}	
	// get y of element by any attribute
	function get_y_by_attribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->get_y_by_atribute($attr_name,$attr_value,$exactly);
	}
   	//////////////////////////////////////////////////////// SET FOCUS /////////////////////////////////////////////////////////////////////////////////
   	// set focus to anchor by name
	function set_focus_by_name($name)
	{
		return $this->z_set_focus_by_name($name);
	}
	// set focus to anchor by number
	function set_focus_by_number($number)
	{
		return $this->z_set_focus_by_number($number);
	}
   	// set focus to anchor by inner text
	function set_focus_by_inner_text($innertext,$exactly=true)
	{
		return $this->z_set_focus_by_inner_text($innertext,$exactly);
	}
   	// set focus to anchor by href
	function set_focus_by_href($href,$exactly=true)
	{

		return $this->z_set_focus_by_href($href,$exactly);
	}
   	// set focus to anchor by attribute
	function set_focus_by_attribute($attr_name,$attr_value,$exactly=true)
	{
		return $this->set_focus_by_atribute($attr_name,$attr_value,$exactly);
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	

	////////////////////////////////////////////////////// CLICK ON ANCHOR IN FRAME ////////////////////////////////////////////////////////// 
   	// click on anchor by name in iframe 
	function click_within_iframe_by_name($name,$frame)
	{
		if ($this->call("Anchor.ClickWithinIframeByName?name=".urlencode($name)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// click on anchor by number in iframe
	function click_within_iframe_by_number($number,$frame)
	{
		if ($this->call("Anchor.ClickWithinIframeByNumber?number=".urlencode($number)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// click on anchor by inner text in iframe
	function click_within_iframe_by_inner_text($text,$exactly,$frame)
	{
		if ($this->call("Anchor.ClickWithinIframeByInnerText?text=".urlencode($text)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
	// click on anchor by href in iframe
	function click_within_iframe_by_href($url,$exactly,$frame)
	{
		if ($this->call("Anchor.ClickWithinIframeByHRef?url=".urlencode($url)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}	
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("Anchor.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
			return true;
		else
			return false;
	}
        // click on random element in frame
	function click_random_in_frame($frame)
	{
		return $this->z_click_random_in_frame($frame);
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
	function send_event_by_attribute_in_frame($atr_name,$atr_value,$exactly,$event,$frame)
	{
		return $this->send_event_by_atribute_in_frame($atr_name,$atr_value,$exactly,$event,$frame);
	}
	//////////////////////////////////////////////////////////////// GET ALL INFO IN FRAME ///////////////////////////////////////////////////////
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("Anchor.GetCountInFrameByNum?number=".urlencode($number));
	}	

	// get inner texts of all anchors on page in frame
	function get_all_inner_texts_in_frame($frame, $separator="<br>")
	{
		return $this->z_get_all_inner_texts_in_frame($frame,$separator);
	}	
	
	/////////////////////////////////////////////////////////////////// SET INFO IN FRAME /////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name_in_frame($name,$name_atr,$frame_number)
	{

		return $this->remove_atribute_by_name_in_frame($name,$name_atr,$frame_number);
	}
	// remove attribute by number
	function remove_attribute_by_number_in_frame($number,$name_atr,$frame_number)
	{
		return $this->remove_atribute_by_number_in_frame($name,$name_atr,$frame_number);
	}
	// remove attribute by attribute in frame by number
	function remove_attribute_by_attribute_in_frame_by_number($atr_name,$atr_value,$exactly,$name_atr,$frame_number)
	{
		return $this->remove_atribute_by_attribute_in_frame_by_number($atr_name,$atr_value,$exactly,$name_atr,$frame_number);
	}
	// add (or set) attribute by attribute in frame by number
	function add_attribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$value_attr,$frame_number)
	{
               $res = $this->call("Anchor.AddAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr)."&frame_number=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
	}
        //////////////////////////////////////////////////////////// IS EXIST IN FRAME ///////////////////////////////////////////////////////////
        // is anchor exist by name in frame
        function is_exist_by_name_in_frame($name,$frame)
        {
		return $this->z_is_exist_with_name_in_frame($name,$frame);
        }
	// is anchor exist by inner text in frame
	function is_exist_by_inner_text_in_frame($text,$frame,$exactly=true)
	{
		return $this->z_is_exist_with_inner_text_in_frame($text,$frame,$exactly);
	}	
	// is anchor exist by href in frame
	function is_exist_by_href_in_frame($href,$frame,$exactly=true)
	{
		return $this->z_is_exist_with_href_in_frame($href,$frame,$exactly);
	}	
	// is anchor exist by attribute in frame
	function is_exist_by_attribute_in_frame($atr_name,$atr_value,$frame,$exactly=true)
	{
		return $this->is_exist_by_atribute_in_frame($atr_name,$atr_value,$frame,$exactly);
	}
	/////////////////////////////////////////////////////////////////// GET INFO IN FRAME /////////////////////////////////////////////////////////
        // get attribute by attribute in frame by number
        function get_attribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               return $this->get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number);
        }
	/////////////////////////////////////////////////////////////////// SET FOCUS IN FRAME ////////////////////////////////////////////////////////
   	// set focus by attribute in frame by number
	function set_focus_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_num)
	{
		if ($this->call("Anchor.SetFocusByAttrInFrameByNum?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else

			return false;
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 

	/////////////////////////////////////////////////////////////// SPECIFY FOR ANCHOR ONLY /////////////////////////////////////////////////////////////////////////// 
	// get all anchors urls on page
	function get_all_urls($separator="<br>")
	{
		return $this->call("Anchor.GetAllUrls?separator=".urlencode($separator));
	}
	// get all anchors urls on page by inner text
	function get_all_urls_by_inner_text($inner_text,$separator="<br>")
	{
		return $this->call("Anchor.GetAllUrlsByInnerText?inner_text=".$inner_text."&separator=".urlencode($separator));
	}
   	// get all external anchor inner texts and anchors urls on page
	function get_all_external_texts_and_url($url,$navigate="false",$separator="<br>")
	{
		return $this->call("Anchor.GetAllExternalUrls?url=".urlencode($url)."&navigate=".urlencode($navigate)."&separator=".urlencode($separator));
	}
        // get all anchors urls on page in frame
	function get_all_urls_in_frame($frame, $separator="<br>")
	{
		return $this->call("Anchor.GetAllUrlsInFrame?separator=".urlencode($separator)."&frame=".urlencode($frame));
	}
	// get all anchors urls on page by inner text in frame
	function get_all_urls_by_inner_text_in_frame($inner_text,$frame,$separator="<br>")
	{
		return $this->call("Anchor.GetAllUrlsByInnerTextInFrame?inner_text=".$inner_text."&frame=".urlencode($frame)."&separator=".urlencode($separator));
	}
   	// get all external anchor inner texts and anchors urls on page in frame
	function get_all_external_texts_and_url_in_frame($url,$frame,$separator="<br>")
	{
		return $this->call("Anchor.1GetAllExternalUrlsInFrame?url=".urlencode($url)."&frame=".urlencode($frame)."&separator=".urlencode($separator));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
};		
?>