<?php
// 2780
//////////////////////////////////////////////////// Mouse /////////////////////////////////////////////////
class XHEKeyboard extends XHEBaseObject
{
   	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEKeyboard($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Keyboard";
	}
    	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// emulate enter string from keyboard (timeout in ms)
   	function input($string,$timeout=0)
   	{
      		return $this->call("Keyboard.Input?string=".urlencode($string)."&timeout=".urlencode($timeout));
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// emulate press key by key scan code
   	function press_key_by_code($code)
   	{
      		return $this->call("Keyboard.PressKeyByCode?code=".urlencode($code));
   	}   
	// emulate key up by scan key code
   	function key_up($key)
   	{
      		return $this->call("Keyboard.KeyUp?key=".urlencode($key));
   	}
	// emulate key down by scan key code
   	function key_down($key)
   	{
      		return $this->call("Keyboard.KeyDown?key=".urlencode($key));
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// send string to browser as messages (timeout in ms)
   	function send_input($string,$timeout=0)
   	{
      		return $this->call("Keyboard.SendInput?string=".urlencode($string)."&timeout=".urlencode($timeout));
   	}
	// send key message to browser
   	function send_key($key)
   	{
      		return $this->call("Keyboard.SendKey?key=".urlencode($key));
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set ctrl prefix (on/of)
   	function set_ctrl_prefix($is_on)
   	{
     		return $this->call("Keyboard.SetCtrlPrefix?is_on=".urlencode($is_on));
   	}
	// set alt prefix (on/of)
	function set_alt_prefix($is_on)
   	{
     		return $this->call("Keyboard.SetAltPrefix?is_on=".urlencode($is_on));
   	}	
	// set shift prefix (on/of)
   	function set_shift_prefix($is_on)
   	{
     		return $this->call("Keyboard.SetShiftPrefix?is_on=".urlencode($is_on));
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// emulate press NUM lock
	function press_num_lock()
   	{
      		return $this->call("Keyboard.PressNumLock");
   	}
   	// press CAPS lock
	function press_caps_lock() 
   	{
      		return $this->call("Keyboard.PressCapsLock");
   	}  
   	// press SCROLL lock
	function press_scroll_lock()
   	{ 
     		return $this->call("Keyboard.PressScrollLock");
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set current langauage
   	function set_current_language($language)
   	{
      		return $this->call("Keyboard.SetCurrentLanguage?language=".urlencode($language));
   	}
	// get current langauage
   	function get_current_language()
   	{
      		return $this->call("Keyboard.GetCurrentLanguage");
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};
?>