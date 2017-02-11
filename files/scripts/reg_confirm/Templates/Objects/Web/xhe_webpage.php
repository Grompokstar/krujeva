<?php
// 2805
//////////////////////////////////////////////////// WebPage - several Functional for work with web pages ////////////////////////////////////////
class XHEWebPage extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEWebPage($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "WebPage";
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// get current url
	function get_location_url()
	{
		return $this->call("WebPage.GetLocationURL");
	}
	// get body (html from browser)
	function get_body()
	{
		return $this->call("WebPage.GetBody");
	}
	// set body (html for browser)
	function set_body($body)
	{
		if ($this->call("WebPage.SetBody?body=".urlencode($body))=="true")
			return true;
		else
			return false;
	}
   	// get title (title from browser)
	function get_title()
	{
		return $this->call("WebPage.GetTitle");
	}
	// get source (html from site)
	function get_source()
	{
		return $this->call("WebPage.GetSource");
	}
	// get source length (html from site)
	function get_source_length()
	{
		return $this->call("WebPage.GetSourceLength");
	}
	// get encoding of current page
	function get_encoding()
	{
		return $this->call("WebPage.GetEncoding");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// save source to file
	function save_source_to_file($filepath)
	{
		if ($this->call("WebPage.SaveSourceToFile?filepath=".urlencode($filepath))=="true")
			return true;
		else
			return false;
	}
	// save any url to file
	function save_url_to_file($url,$filepath,$timeout=60)
        {
                if ($this->call("WebPage.SaveUrlToFile?url=".urlencode($url)."&filepath=".urlencode($filepath),$timeout)=="true")
                        return true;
                else
                        return false;
        }
        // get url size
	function get_url_size($url)
        {
                $res = $this->call("WebPage.GetUrlSize?url=".urlencode($url));
                if($res!="false")
                        return $res;
                else
                        return false;
        }
        // load web page
	function load_web_page($url)
	{
		return $this->call("WebPage.LoadWebPage?url=".urlencode($url));
	}
   	// get body of documants (as html or as text)
	function get_document_body($as_html)
	{
		return $this->call("WebPage.GetDocumentBody?as_html=".urlencode($as_html));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// print screen to file
	function print_screen($filepath,$xl=-1,$yt=-1,$xr=-1,$yb=-1)
	{
		if ($this->call("WebPage.PrintScreen?filepath=".urlencode($filepath)."&xl=".urlencode($xl)."&yt=".urlencode($yt)."&xr=".urlencode($xr)."&yb=".urlencode($yb))=="true")
			return true;
		else
			return false;
	}
     	// get X some picture in webpage picture
	function get_x_in_webpage_picture($picture_filepath)
	{
		return $this->call("WebPage.GetBitmapX?picture_filepath=".urlencode($picture_filepath));
	}
     	// get Y some picture in webpage picture
	function get_y_in_webpage_picture($picture_filepath)
	{
		return $this->call("WebPage.GetBitmapY?picture_filepath=".urlencode($picture_filepath));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get value of element by name
	function get_element_value_by_name($name)
	{
		return $this->call("WebPage.GetElementValueByName?name=".urlencode($name));
	}
   	// get inner html of element by mane
	function get_element_innerHtml_by_name($name)
	{
		return $this->call("WebPage.GetElementInnerHtmlByName?name=".urlencode($name));
	}
   	// get element inner text by mane
	function get_element_innerText_by_name($name)
	{
		return $this->call("WebPage.GetElementInnerTextByName?name=".urlencode($name));
	}
    	// set value of element by mane
	function set_element_value_by_name($name,$text)
	{
		return $this->call("WebPage.SetElementValueByName?name=".urlencode($name)."&text=".urlencode($text));
	}
   	// click on element by name
	function click_on_element_by_name($name)
	{
		return $this->call("WebPage.ClickOnElementByName?name=".urlencode($name));
	}
   	// click on element by number
	function click_on_element_by_number($number)
	{
		return $this->call("WebPage.ClickOnElementByNumber?number=".urlencode($number));
	}
   	// click on element by inner text
   	function click_on_element_by_inner_text($inner_text)
	{
		return $this->call("WebPage.ClickOnElementByInnerText?inner_text=".urlencode($inner_text));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // get body before prefix
	function get_body_before_prefix($prefix,$as_html=true)
	{
            $res=$this->call("WebPage.GetBodyBeforePrefix?prefix=".urlencode($prefix)."&as_html=".urlencode($as_html));
	    if($res!="false")
            	return $res;
            else
                return false;   
        }
        // get body after prefix
	function get_body_after_prefix($prefix,$as_html=true)
	{
            $res=$this->call("WebPage.GetBodyAfterPrefix?prefix=".urlencode($prefix)."&as_html=".urlencode($as_html));
	    if($res!="false")
            	return $res;
            else
                return false;   
        }
        // get body iner prefix
	function get_body_inter_prefix($prefix1,$prefix2,$as_html=true)
	{
            $res=$this->call("WebPage.GetBodyInterPrefix?prefix1=".urlencode($prefix1)."&prefix2=".urlencode($prefix2)."&as_html=".urlencode($as_html));
	    if($res!="false")
            	return $res;
            else
                return false;   
        }
        // get body iner prefix all
	function get_body_inter_prefix_all($prefix1,$prefix2,$as_html=true,$shift1=0,$shift2=0,$separator="<br>")
	{                                                                 
            $res=$this->call("WebPage.GetBodyInterPrefixAll?prefix1=".urlencode($prefix1)."&prefix2=".urlencode($prefix2)."&as_html=".urlencode($as_html)."&shift1=".urlencode($shift1)."&shift2=".urlencode($shift2)."&separator=".urlencode($separator));
	    if($res!="false")
            	return $res;
            else
                return false;   
        }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
};	
?>