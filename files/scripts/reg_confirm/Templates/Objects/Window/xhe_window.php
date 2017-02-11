<?php
// 2814
//////////////////////////////////////////////////// Window /////////////////////////////////////////////////
class XHEWindow extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEWindow($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Window";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	// get all visible main window text
	function get_all_texts($visibled,$mained)
	{
        return $this->call("Window.GetAllTexts?visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// get all visible main window text
	function get_count($visibled,$mained)
	{
        return $this->call("Window.GetCount?visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// get visible main window text by number
	function get_text_by_number($number,$visibled,$mained)
	{
        return $this->call("Window.GetTextByNumber?number=".urlencode($number)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// get visible child windows count in visible main window by number
	function get_child_count_by_number($number,$visibled,$mained)
	{
        return $this->call("Window.GetChildCountByNumber?number=".urlencode($number)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// get visible child windows texts in visible main window by number
	function get_child_texts_by_number($number,$visibled,$mained)
	{
        return $this->call("Window.GetAllChildTextsByNumber?number=".urlencode($number)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// get number of visible main window by text
	function get_number_by_text($text,$exactly,$visibled,$mained)
	{
        return $this->call("Window.GetNumberByText?text=".urlencode($text)."&exactly=".urlencode($exactly)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// send message to visible main window by number
	function send_message_by_number($number,$message,$wparam,$lparam,$visibled,$mained)
	{
        return $this->call("Window.SendMessageByNumber?number=".urlencode($number)."&message=".urlencode($message)."&wparam=".urlencode($wparam)."&lparam=".urlencode($lparam)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained));
	}
	// press button by text in window by number
	function press_button_by_text_in_window_by_number($number,$text,$exactly,$visibled,$mained)
	{
        if ($this->call("Window.PressButtonByTextInWindowByNumber?number=".urlencode($number)."&text=".urlencode($text)."&exactly=".urlencode($exactly)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained))=="true")
			return true;
		else
			return false;
	}
	// set text in child winow by number in window by number
	function set_window_text_by_child_number_in_window_by_number($number,$child_number,$text,$visibled,$mained)
	{
        if ($this->call("Window.SetWindowTextByNumberInWindowByNumber?number=".urlencode($number)."&child_number=".urlencode($child_number)."&text=".urlencode($text)."&visibled=".urlencode($visibled)."&mained=".urlencode($mained))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>