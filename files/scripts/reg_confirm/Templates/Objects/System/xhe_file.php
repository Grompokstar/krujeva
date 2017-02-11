<?php
// 3636
//////////////////////////////////////////////////// Sound /////////////////////////////////////////////////
class XHEFile_os extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEFile_os($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "File";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	function get_creation_date($path)
	{			  
		return $this->call("File.GetCreationDate?path=".urlencode($path));
	}
	function get_modification_date($path)
	{			  
		return $this->call("File.GetModificationDate?path=".urlencode($path));
	}
	function get_lastacess_date($path)
	{			  
		return $this->call("File.GetLastAcessDate?path=".urlencode($path));
	}
	// get file size
	function get_size($path)
	{			  
		return $this->call("File.GetSize?path=".urlencode($path));
	}
        // get file name
	function get_file_name($path)
	{			  
		return $this->call("File.GetFileName?path=".urlencode($path));
	}
        // get file title (without extention)
	function get_file_title($path)
	{			  
		return $this->call("File.GetFileTitle?path=".urlencode($path));
	}
        // get file extention
	function get_file_ext($path)
	{			  
		return $this->call("File.GetFileExt?path=".urlencode($path));
	}
        // get file folder
	function get_file_folder($path)
	{			  
		return $this->call("File.GetFileFolder?path=".urlencode($path));
	}
        // get file disk
	function get_file_disk($path)
	{			  
		return $this->call("File.GetFileDisk?path=".urlencode($path));
	}
	// copy file
	function copy($path,$new_file_place,$flag_fail_exist="false")
	{			  
		return $this->call("File.Copy?path=".urlencode($path)."&new_file_place=".urlencode($new_file_place)."&flag_fail_exist=".urlencode($flag_fail_exist));
	}
	// rename file
	function rename($path,$new_file_name)
	{			  
		return $this->call("File.Rename?path=".urlencode($path)."&new_file_name=".urlencode($new_file_name));
	}
	// move file
	function move($path,$new_file_place)
	{			  
		return $this->call("File.Move?path=".urlencode($path)."&new_file_place=".urlencode($new_file_place));
	}
	// delete file
	function delete($path)
	{			  
		return $this->call("File.Delete?path=".urlencode($path));
	}
	// is normal
	function is_normal($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("NORMAL"));
	}
	// is readonly
        function is_readonnly($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("READONLY"));
	}
	// is hidden
	function is_hidden($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("HIDDEN"));
	}
	// is system
	function is_system($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("SYSTEM"));
	}
	// is archive
	function is_archive($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("ARCHIVE"));
	}
	// is normal
	function set_normal($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("NORMAL"));
	}
	// is readonly
        function set_readonnly($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("READONLY"));
	}
	// is hidden
	function set_hidden($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("HIDDEN"));
	}
	// is system
	function set_system($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("SYSTEM"));
	}
	// is archive
	function set_archive($path)
	{			  
		return $this->call("File.GetAttr?path=".urlencode($path)."&attr=".urlencode("ARCHIVE"));
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>