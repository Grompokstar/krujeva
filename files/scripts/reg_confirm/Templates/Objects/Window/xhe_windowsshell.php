<?php
// 2817
//////////////////////////////////////////////////// Debug /////////////////////////////////////////////////
class XHEWindowsShell extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEWindowsShell($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "WindowsShell";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get screen width
	function get_screen_width()
	{
		return $this->call("WindowsShell.GetScreenWidth");
	}
   	// get screen height
	function get_screen_height()
	{
		return $this->call("WindowsShell.GetScreenHeight");
	}
   	// set screen resolution
	function set_screen_resolution($width,$height)
	{
		$this->call("WindowsShell.SetScreenResolution?width=".urlencode($width)."&height=".urlencode($height));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get windows title
	function get_windows_title()
	{
		return $this->call("WindowsShell.GetWindowsTitle");
	}
	// get windows version
	function get_windows_version()
	{
		return $this->call("WindowsShell.GetWindowsVersion");
	}
	// get windows build
	function get_windows_build()
	{
		return $this->call("WindowsShell.GetWindowsBuild");
	}
	// get windows platform id
	function get_windows_platform_id()
	{
		return $this->call("WindowsShell.GetWindowsPlatformID");
	}
	// get windows SP info
	function get_windows_sp_info()
	{
		return $this->call("WindowsShell.GetWindowsSPInfo");
	}
	// get computer name
	function get_computer_name()
	{
		return $this->call("WindowsShell.GetComputerName");
	}
	// get user name
	function get_user_name()
	{
		return $this->call("WindowsShell.GetUserName");
	}
	// get cpu name
	function get_cpu_name()
	{
		return $this->call("WindowsShell.GetCPUName");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>