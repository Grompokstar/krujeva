<?php
// 3684
//////////////////////////////////////////////////// Sound /////////////////////////////////////////////////
class XHEFolder extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEFolder($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Folder";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// create_folder
	function create_folder($path)
	{			  
		return $this->call("Folder.CreateFolder?path=".urlencode($path));
	}
	// get file size
	function get_size($path)
	{			  
		return $this->call("Folder.GetSize?path=".urlencode($path));
	}
        // get folder name
	function get_folder_name($path)
	{			  
		return $this->call("Folder.GetFolderName?path=".urlencode($path));
	}
        // get file disk
	function get_folder_disk($path)
	{			  
		return $this->call("Folder.GetFolderDisk?path=".urlencode($path));
	}
	// copy file
	function copy($path,$new_folder_place,$flag_fail_exist="true")
	{			  
		return $this->call("Folder.Copy?path=".urlencode($path)."&new_folder_place=".urlencode($new_folder_place)."&flag_fail_exist=".urlencode($flag_fail_exist));
	}
	// rename file
	function rename($path,$new_folder_name)
	{			  
		return $this->call("Folder.Rename?path=".urlencode($path)."&new_folder_name=".urlencode($new_folder_name));
	}
	// move file
	function move($path,$new_folder_place)
	{			  
		return $this->call("Folder.Move?path=".urlencode($path)."&new_folder_place=".urlencode($new_folder_place));
	}
	// delete file
	function delete($path)
	{			  
		return $this->call("Folder.Delete?path=".urlencode($path));
	}
	function get_all_items($path)
	{			  
		return $this->call("Folder.GetAllItemsInFolder?path=".urlencode($path));
	}

	// zip file
/*	function zip($path)
	{			  
		return $this->call("File.Zip?path=".urlencode($path));
	}
	// rar file
	function rar($path)
	{			  
		return $this->call("File.Rar?path=".urlencode($path));
	}
	// unzip file
	function unzip($path)
	{			  
		return $this->call("File.UnZip?path=".urlencode($path));
	}
	// unrar file
	function unrar($path)
	{			  
		return $this->call("File.UnRar?path=".urlencode($path));
	}   */

	////////////////////ATRIBUTES//////////////
	//get creation date
	function get_creation_date($path)
	{			  
		return $this->call("Folder.GetCreationDate?path=".urlencode($path));
	}
	//get modification date
	function get_modification_date($path)
	{			  
		return $this->call("Folder.GetModificationDate?path=".urlencode($path));
	}
	//get last acess date
	function get_lastacess_date($path)
	{			  
		return $this->call("Folder.GetLastAcessDate?path=".urlencode($path));
	}

	// is normal
	function is_normal($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("NORMAL"));
	}
	// is readonly
        function is_readonly($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("READONLY"));
	}
	// is hidden
	function is_hidden($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("HIDDEN"));
	}
	// is system
	function is_system($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("SYSTEM"));
	}
	// is archive
	function is_archive($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("ARCHIVE"));
	}
	// is normal
	function set_normal($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("NORMAL"));
	}
	// is readonly
        function set_readonly($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("READONLY"));
	}
	// is hidden
	function set_hidden($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("HIDDEN"));
	}
	// is system
	function set_system($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("SYSTEM"));
	}
	// is archive
	function set_archive($path)
	{			  
		return $this->call("Folder.GetAttr?path=".urlencode($path)."&attr=".urlencode("ARCHIVE"));
	}
	          
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>