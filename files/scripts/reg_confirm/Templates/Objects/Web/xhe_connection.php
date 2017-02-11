<?php
// 2795
//////////////////////////////////////////////////////////// SEO - get several site info ////////////////////////////////////////////////////////
class XHEConnection extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHEConnection($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Connection";
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// Dial RAS
        function dial_ras($name,$login,$password) 
	{
		return $this->call("Connection.Dial_RAS?name=".urlencode($name)."&login=".urlencode($login)."&password=".urlencode($password));
	}
	// HangUp RAS
        function hang_up_ras()
	{
		return $this->call("Connection.HangUp_RAS");
	}
	// Get Name by Number for RAS (from 0 ...)
        function get_name_by_number_ras($number)
	{
		return $this->call("Connection.GetNameByNumber_RAS?number=".urlencode($number));
	}
	// Get All RAS Conenction (delimeter - "<br>" )
        function get_all_connection_ras()
        {
                return $this->call("Connection.GetAllConnection_RAS");
        }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// restart LAN Adatpter by name
        function restart_lan_by_name($name)
	{
		if ($this->call("Connection.RestartLANByName?name=".urlencode($name))=="true")
			return true;
		else
			return false;
	}
	// restart LAN Adatpter by number
        function restart_lan_by_number($number)
	{
		if ($this->call("Connection.RestartLANByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// is now exist internet connection
	function is_connect_to_internet()
	{
		if ($this->call("Connection.IsConnected")=="true")
			return true;
		else
			return false;		
	}
	// get connection parameters string
	function get_connection_parameters()
	{
		return $this->call("Connection.GetConnectionStatusName");
	}
	// get current ip address
	function get_local_ip()
	{
		return $this->call("Connection.GetLocalIP");
	}
	// get current ip address
	function get_real_ip()
	{
		return $this->call("Connection.GetRealIP");
	}
        // check ping site
        function check_ping_site($site)
        {
               if($this->call("Connection.CheckPingSite?site=".urlencode($site))=="true")
                       return true;
               else
                       return false;
        }

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// enable proxy
	function enable_proxy($connection,$proxy)
	{
     
		if ($this->call("Connection.EnableProxy?connection=".urlencode($connection)."&proxy=".urlencode($proxy))=="true")
			return true;
		else
			return false;		
	}
	// disable proxy
	function disable_proxy($connection)
	{
		if ($this->call("Connection.DisableProxy?connection=".urlencode($connection))=="true")
			return true;
		else
			return false;
  	}	
	// get current proxy
	function get_current_proxy($connection)
	{
		return $this->call("Connection.GetCurrentProxy?connection=".urlencode($connection));
  	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>