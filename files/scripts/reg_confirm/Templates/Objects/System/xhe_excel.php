<?php
// 2718
//////////////////////////////////////////////////// Sound /////////////////////////////////////////////////
class XHEExcel extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEExcel($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Excel";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function get_creation_date($path)
	{			  
		return $this->call("File.GetCreationDate?path=".urlencode($path));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>