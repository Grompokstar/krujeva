<?php
// 3503
//////////////////////////////////////////////////////////// SEO - get several site info ////////////////////////////////////////////////////////
class XHEFTP extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHEFTP($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "FTP";
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// connect to FTP server 
        function connect($server,$user="",$password="",$port="0",$flag_passive="false")
	{
		return $this->call("FTP.Connect?server=".urlencode($server)."&user=".urlencode($user)."&password=".urlencode($password)."&port=".urlencode($port)."&flag_passive=".urlencode($flag_passive));
	}
	// get file from server
        function get_file($server,$remote_file,$local_file,$flag_fail_exist="true",$file_atributes="128",$transfer_atributes="2",$context="1")
	{
		return $this->call("FTP.GetFile?server=".urlencode($server)."&remote_file=".urlencode($remote_file)."&local_file=".urlencode($local_file)."&flag_fail_exist=".urlencode($flag_fail_exist)."&file_atributes=".urlencode($file_atributes)."&transfer_atributes=".urlencode($transfer_atributes)."&context=".urlencode($context));
	}
	// put file to server                
        function put_file($server,$local_file,$remote_file,$transfer_atributes="2",$context="1")
	{
		return $this->call("FTP.PutFile?server=".urlencode($server)."&remote_file=".urlencode($remote_file)."&local_file=".urlencode($local_file)."&flag_fail_exist=".urlencode($flag_fail_exist)."&file_atributes=".urlencode($file_atributes)."&transfer_atributes=".urlencode($transfer_atributes)."&context=".urlencode($context));
	}
	// create dirrectory on server
	function create_directoy($server,$dir_name)
	{
		return $this->call("FTP.CreateDirectory?server=".urlencode($server)."&dir_name=".urlencode($dir_name));
	}
	// remove directory from server
	function remove_directory($server,$dir_name)
	{
		return $this->call("FTP.RemoveDirectory?server=".urlencode($server)."&dir_name=".urlencode($dir_name));
	}
	// remove file from server
	function remove_file($server,$file_name)
	{
		return $this->call("FTP.RemoveFile?server=".urlencode($server)."&file_name=".urlencode($file_name));
	}
	// rename file
	function rename($server,$exist_file,$new_file_name)
	{
		return $this->call("FTP.RemoveFile?server=".urlencode($server)."&exist_file=".urlencode($exist_file)."&new_file_name=".urlencode($new_file_name));
	}  
	// command to server
	function command($server,$command,$response="0",$transfer_atributes="2",$context="1")
	{
		return $this->call("FTP.Command?server=".urlencode($server)."&command=".urlencode($command)."&response=".urlencode($response)."&transfer_atributes=".urlencode($transfer_atributes)."&context=".urlencode($context));
	}
	// disconnect from server
	function disconect($server)
	{
		return $this->call("FTP.Disconnect?server=".urlencode($server));
	}        
	function disconect_all()
	{
		return $this->call("FTP.AllDisconnect");
	}
	 
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>