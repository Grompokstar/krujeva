<?php
// 2811
//////////////////////////////////////////////////// Debug /////////////////////////////////////////////////
class XHEDebug extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEDebug($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Debug";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// debug message box
	function message_box($text)
	{
		return $this->call("Debug.MessageBox?text=".urlencode($text));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// open debug tab
	function open_tab($page)
	{
		return $this->call("Debug.OpenTab?page=".urlencode($page));
	}
	// close debug tab
	function close_tab($page)
	{
		return $this->call("Debug.CloseTab?page=".urlencode($page));
	}
	// add text to debug panel
	function set_tab_content($page,$text)
	{
		return $this->call("Debug.SetTabContent?page=".urlencode($page)."&text=".urlencode($text));
	}
	// view tab contetnt as text
	function view_tab_as_text($page,$as_text)
	{
		return $this->call("Debug.ViewTabAsText?page=".urlencode($page)."&as_text=".urlencode($as_text));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// save tab content to file
	function save_tab_content_to_file($page,$filepath,$add="false")
	{
		return $this->call("Debug.SaveTabContentToFile?page=".urlencode($page)."&filepath=".urlencode($filepath)."&add=".urlencode($add));
	}	
	// clear all text on debug panel
	function clear_tab_content($page)
	{
		return $this->call("Debug.ClearTabContent?page=".urlencode($page));
	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get minimum memory size for XHE
	function get_min_mem_size()
	{
		return $this->call("Debug.GetMemMinSize");
	}	
	// get maximum memory size for XHE
	function get_max_mem_size()
	{
		return $this->call("Debug.GetMemMaxSize");
	}	
	// get current memory size for XHE
	function get_cur_mem_size()
	{
		return $this->call("Debug.GetMemCurSize");
	}	
	// get free physical memory size for XHE
	function get_free_physical_mem_size()
	{
		return $this->call("Debug.GetFreePhysMemSize");
	}	
	// optimize current memory
	function optimize_memory()
	{
		return $this->call("Debug.OptimizeMemory");
	}	
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set hooks on debug actions
	function set_hook($action,$php_script)
	{
		if ($this->call("Debug.SetHook?action=".$action."&php_script=".$php_script)==true)
			return true;
		else
			return false;
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // get current script path
	function get_cur_script_path()
	{
		return $this->call("Debug.GetCurrentScriptPath");
	}	
	// check is script run
	function is_script_run()
	{
		if ($this->call("Debug.IsScriptRun")==true)
                    return true;
                else 
                    return false;
	}
        // run current script	
        function run_current_script($params)
	{
		if ($this->call("Debug.RunScript?params=".$params)==true)
                    return true;
                else 
                    return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>