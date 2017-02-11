<?php
// 2718
//////////////////////////////////////////////////// Sound /////////////////////////////////////////////////
class XHEMsWord extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEMsWord($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "MsWord";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // create a new document
	function create_doc($name,$is_visible=true)
	{
		return $this->call("MsWord.CreateDoc?name=".urlencode($name)."&is_visible=".urlencode($is_visible));
	}
	// open document
	function open_doc($name)
	{
		return $this->call("MsWord.OpenDoc?name=".urlencode($name));
		
	}
	// save document
	function save_doc($name)
	{
		return $this->call("MsWord.SaveDoc?name=".urlencode($name));
		
	}
	// close document
	function close_docs($flag_save)
	{
		return $this->call("MsWord.CloseDocs?flag_save=".urlencode($flag_save));
		
	}
	// set text on document
	function set_text($name_doc, $text, $pos)
	{
  		return $this->call("MsWord.SetText?name_doc=".urlencode($name_doc)."&text=".urlencode($text)."&pos=".urlencode($pos));
	}
	// set text on document
	function set_table($name_doc, $row, $col, $pos)
	{
  		return $this->call("MsWord.SetTable?name_doc=".urlencode($name_doc)."&row=".urlencode($row)."&col=".urlencode($col)."&pos=".urlencode($pos));
	}
	// set image on document
	function set_image($name_doc, $path, $pos)
	{
  		return $this->call("MsWord.SetImage?name_doc=".urlencode($name_doc)."&path=".urlencode($path)."&pos=".urlencode($pos));
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>