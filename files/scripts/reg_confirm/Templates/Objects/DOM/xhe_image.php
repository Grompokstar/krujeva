<?php
// 2742
//////////////////////////////////////////////////// Image /////////////////////////////////////////////////
class XHEImage extends XHEImageCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
   
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEImage($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Image";
	}
   	//////////////////////////////////////////////////////////// FUNCTIONAL ///////////////////////////////////////////////////
	// get count of elements
	function get_count()
	{
		return $this->call("Image.GetCount");
	}
   	// get src by name
	function get_src_by_name($name)
	{
		return $this->call("Image.GetHrefByName?name=".urlencode($name));
	}
	// get src by number
	function get_src_by_number($number)
	{
		return $this->call("Image.GetHrefByNumber?number=".urlencode($number));
	}
	// get alt by number (image alt)
	function get_alt_by_number($number)
	{
		return $this->call("Image.GetAltByNumber?number=".urlencode($number));
	}
	// get alt by number (image alt)
	function get_alt_by_name($name)
	{
		return $this->call("Image.GetAltByName?name=".urlencode($name));
	}
	// get number by src (image number)
	function get_number_by_src($src,$exactly)
	{
		return $this->call("Image.GetNumberBySrc?src=".urlencode($src)."&exactly=".urlencode($exactly));
	}
        // get atribute by name
        function get_atribute_by_name($name,$name_attr)
        {
               $res = $this->call("Image.GetAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by number
        function get_atribute_by_number($number,$name_attr)
        {
               $res = $this->call("Image.GetAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute
        function get_atribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr)
        {
               $res = $this->call("Image.GetAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr));
               if ($res =="false")
			return false;
		else
			return $res;
        }
        // get atribute by attribute in frame by number
        function get_atribute_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$name_attr,$frame_number)
        {
               $res = $this->call("Image.GetAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&name_attr=".urlencode($frame_number));
               if ($res =="false")
			return false;
		else
			return $res;
        }
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
        // is exist by name
        function is_exist_with_name($name)
        {
                if ($this->call("Image.IsExistByName?name=".urlencode($name))=="true")
                        return true;
                else
                        return false;
        }
	// is exist with attribute
	function is_exist_with_attribute($attr_name,$attr_value,$exactly)
	{
		if ($this->call("Image.IsExistsWithAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}	
	// is exist with attribute in frame by number
	function is_exist_with_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_number)
	{
		if ($this->call("Image.IsExistsWithAttrInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_number=".urlencode($frame_number))=="true")
			return true;
		else
			return false;
	}	
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("Image.GetCountInFrameByNum?number=".urlencode($number));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get width by number
	function get_width_by_number($number)
	{
		return $this->call("Image.GetWidthByNumber?number=".urlencode($number));
	}
	// get height by number
	function get_height_by_number($number)
	{
		return $this->call("Image.GetHeightByNumber?number=".urlencode($number));
	}
   	// get width by number
	function get_width_by_name($name)
	{
		return $this->call("Image.GetWidthByName?name=".urlencode($name));
	}
	// get height by number
	function get_height_by_name($name)
	{
		return $this->call("Image.GetHeightByName?name=".urlencode($name));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get file create date by number
	function get_file_create_date_by_number($number)
	{
		return $this->call("Image.GetFileCreatedDateByNumber?number=".urlencode($number));
	}
	// get file modifycation date by number
	function get_file_modification_date_by_number($number)
	{
		return $this->call("Image.GetFileModifiedDateByNumber?number=".urlencode($number));
	}
	// get file size by number
	function get_file_size_by_number($number)
	{
		return $this->call("Image.GetFileSizeByNumber?number=".urlencode($number));
	}
   	// get file create date by name
	function get_file_create_date_by_name($name)
	{
		return $this->call("Image.GetFileCreatedDateByName?name=".urlencode($name));
	}
	// get file modifycation date by number
	function get_file_modification_date_by_name($name)
	{
		return $this->call("Image.GetFileModifiedDateByName?name=".urlencode($name));
	}
	// get file size by number
	function get_file_size_by_name($name)
	{
		return $this->call("Image.GetFileSizeByName?name=".urlencode($name));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// is image loaded to browser by number
	function is_complete_by_number($number)
	{
	        if ($this->call("Image.IsCompleteByNumber?number=".urlencode($number))=="true")
                   return true;
                else
                   return false;
	}
	// is image loaded to browser by name
   	function is_complete_by_name($name)
	{
		if ($this->call("Image.IsCompleteByName?name=".urlencode($name))=="true")
                   return true;
                else
                   return false;
 
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// save image to file by number
	function save_to_file_by_number($number,$filepath)
	{
		if ($this->call("Image.SaveToFileByNumber?number=".urlencode($number)."&filepath=".urlencode($filepath))=="true")
			return true;
		else
			return false;
	}
  	// save image to file by name
	function save_to_file_by_name($name,$filepath)
	{
		if ($this->call("Image.SaveToFileByName?name=".urlencode($name)."&filepath=".urlencode($filepath))=="true")
			return true;
		else
			return false;
	}
    	// save image to file by url
	function save_to_file_by_url($url,$filepath,$exactly="true")
	{
		if ($this->call("Image.SaveToFileByURL?url=".urlencode($url)."&filepath=".urlencode($filepath)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // click on random element
	function click_random()
	{
		return $this->z_click_random();
	}
        // click by name
	function click_by_name($name)
	{
		if ($this->call("Image.ClickByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
	// click by number
	function click_by_number($number)
	{
		if ($this->call("Image.ClickByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
	// click by alt
	function click_by_alt($alt,$exactly=true)
	{
		if ($this->call("Image.ClickByAlt?alt=".urlencode($alt)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
	// click by src
	function click_by_src($src,$exactly=true)
	{
		if ($this->call("Image.ClickBySrc?src=".urlencode($src)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
	// click on image by atribute
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
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// click by attribute in iframe
	function click_within_iframe_by_attribute($attr_name,$attr_value,$exactly,$frame)
	{
		if ($this->call("Image.ClickWithinIframeByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame))=="true")
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
	// send event to image by name
	function send_event_by_name($name,$event)
	{
		return $this->z_send_event_by_name($name,$event);
	}
	// send event to image by number 
	function send_event_by_number($number,$event)
	{
		return $this->z_send_event_by_number($number,$event);
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
		if ($this->call("Image.SetFocusByAttr?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    	// recognize image as capcha from disk
	function recognize_captcha($filepath,$type)
	{
		return $this->call("Image.RecognizeCapcha?filepath=".urlencode($filepath)."&type=".urlencode($type));
	}
        // recognize captcha using anticaptcha service
        function recognize_by_anticaptcha($url,$file,$key,$path='http://antigate.com',$is_verbose = true, $rtimeout = 5, $mtimeout = 120, $is_phrase = 0, $is_regsense = 0, $is_numeric = 0, $min_len = 0, $max_len = 0)
        {
               if($url!="")
                  $this->call("Image.SaveToFileByURL?url=".urlencode($url)."&filepath=".urlencode($file)."&exactly=".urlencode("false"));   
					
               global $anticapcha;
               $captcha=$anticapcha->recognize($file,$key,$path,$is_verbose,$rtimeout,$mtimeout,$is_phrase,$is_regsense,$is_numeric,$min_len,$max_len);

               return $captcha;
        }
        // recognize by captchabot
        function recognize_by_captchabot($systemkey,$file,$url="",$code=0)
        {
          if($url!="")
                  $this->call("Image.SaveToFileByURL?url=".urlencode($url)."&filepath=".urlencode($file)."&exactly=".urlencode("false"));   

          
          global $captchabot;

          $captchabot->SystemKey = $systemkey;
          $res=$captchabot->recognize($file,$code);

          return $res;
        }
        
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	
        // save image to file by number within Iframe by number
	function save_to_file_by_number_withinIframe_number($number,$filepath,$framenum)
	{
		if ($this->call("Image.SaveToFileByNumberWithinIframe?number=".urlencode($number)."&filepath=".urlencode($filepath)."&framenum=".urlencode($framenum))=="true")
			return true;
		else
			return false;
	}
  	// save image to file by name within Iframe by number
	function save_to_file_by_name_withinIframe_number($name,$filepath,$framenum)
	{
		if ($this->call("Image.SaveToFileByNameWithinIframe?name=".urlencode($name)."&filepath=".urlencode($filepath)."&framenum=".urlencode($framenum))=="true")
			return true;
		else
			return false;
	}
    	// save image to file by url within Iframe by number
	function save_to_file_by_url_withinIframe_number($url,$filepath,$framenum,$exactly="true")
	{
		if ($this->call("Image.SaveToFileByURLWithinIframe?url=".urlencode($url)."&filepath=".urlencode($filepath)."&framenum=".urlencode($framenum)."&exactly=".urlencode($exactly))=="true")
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // show picture by src
        function show_by_src($src,$exactly,$frame=-1)
        {
          return $this->call("Image.ShowImageBySrc?src=".urlencode($src)."&exactly=".urlencode($exactly)."&frame=".urlencode($frame));
        }
        // show picture by name
        function show_by_name($name,$frame=-1)
        {
          return $this->call("Image.ShowImageByName?name=".urlencode($name)."&frame=".urlencode($frame));
        }
        // show picture by number
        function show_by_number($number,$frame=-1)
        {
          return $this->call("Image.ShowImageByNumber?number=".urlencode($number)."&frame=".urlencode($frame));
        }
        // show picture by alt
        function show_by_alt($alt,$frame=-1)
        {
          return $this->call("Image.ShowImageByAlt?alt=".urlencode($alt)."&frame=".urlencode($frame));
        }
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// remove attribute by name
	function remove_attribute_by_name($name,$name_attr)
	{
		return $this->call("Image.RemoveAtributeByName?name=".urlencode($name)."&name_attr=".urlencode($name_attr));
	}
	// remove attribute by number
	function remove_attribute_by_number($number,$name_attr)
	{
		return $this->call("Image.RemoveAtributeByNumber?number=".urlencode($number)."&name_attr=".urlencode($name_attr));
	}
	// add (or set) attribute by attribute
	function add_attribute_by_attribute($attr_name,$attr_value,$exactly,$name_attr,$value_attr)
	{
               $res = $this->call("Image.AddAtributeByAttribute?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr));
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
               $res = $this->call("Image.AddAtributeByAttributeInFrameByNumber?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&name_attr=".urlencode($name_attr)."&value_attr=".urlencode($value_attr))."&frame_number=".urlencode($frame_number);
               if ($res =="false")
			return false;
		else
			return $res;
	}
   	// set focus by attribute in frame by number
	function set_focus_by_attribute_in_frame_by_number($attr_name,$attr_value,$exactly,$frame_num)
	{
		if ($this->call("Image.SetFocusByAttrInFrameByNum?attr_name=".urlencode($attr_name)."&attr_value=".urlencode($attr_value)."&exactly=".urlencode($exactly)."&frame_num=".urlencode($frame_num))=="true")
			return true;
		else
			return false;
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>