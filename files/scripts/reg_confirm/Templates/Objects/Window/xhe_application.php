<?php
// 2808
//////////////////////////////////////////////////// Application /////////////////////////////////////////////////
class XHEApplication extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES ///////////////////////////////////////////////////////////////
	// enable exit
	var $enable_exit=true;
	///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS //////////////////////////////////////////////////////////////
	// server initialization
	function XHEApplication($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->enable_exit=true;
		$this->prefix = "Application";
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	/////////////////////////////////////////////////////////// GUI ////////////////////////////////////////////////////////////////////////////
	// set title
	function set_title($title)
	{
		if ($this->call("Application.SetTitle?title=".urlencode($title))=="true")
			return true;
		else
			return false;
	}
	// set blink
	function set_blink($blink)
	{
		if ($this->call("Application.SetBlink?blink=".urlencode($blink))=="true")
			return true;
		else
			return false;				
	}
	// show left pane
	function show_left_pane($show)
	{
		if ($this->call("Application.ShowLeftPane?show=".urlencode($show))=="true")
			return true;
		else
			return false;				
	}
	// show bottom pane
	function show_bottom_pane($show)
	{
		if ($this->call("Application.ShowBottomPane?show=".urlencode($show))=="true")
			return true;
		else
			return false;				
	}
	// enable full screen
	function enable_full_screen($enable)
	{
		if ($this->call("Application.EnableFullScreenMode?enable=".urlencode($enable))=="true")
			return true;
		else
			return false;				
	}
	// minimize to tray
	function minimize_to_tray()
	{
		if ($this->call("Application.MinimizeToTray")=="true")
			return true;
		else
			return false;				
	}
	// show from tray
	function show_from_tray()
	{
		if ($this->call("Application.ShowFromTray")=="true")
			return true;
		else
			return false;				
	}
	function show_tray_icon($flag_show)
	{
		if ($this->call("Application.ShowTrayIcon?flag_show=".urlencode($flag_show))=="true")
			return true;
		else
			return false;				
	}
	// set always on top
	function set_always_on_top($ontop)
	{
		if ($this->call("Application.SetAlwaysOnTop?ontop=".urlencode($ontop))=="true")
			return true;
		else
			return false;				
	}
        // set foreground xhe window
        function set_foreground_window()
        {       
                if ($this->call("Application.SetForegroundWindow")=="true")
			return true;
		else
			return false;				

        }
        ////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // get port
        function get_port()
        {       
                return $this->call("Application.GetPort");

        }
        // get install id
        function get_install_id()
        {       
                return $this->call("Application.GetInstallID");

        }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// properly stops the browser
	function enable_quit($enable_exit)
	{
		$this->enable_exit=$enable_exit;
	}
	// properly stops the browser
	function quit()
	{
		sleep(1);
		if ($this->enable_exit)
		  die("XWeb@exit");
	}
	// exit program
   	function exitapp()
   	{
		$this->call("Application.Exit",1);
   	}
	// restrat program
   	function restart($scriptpath="",$params="",$port="",$cache_folder="",$cookies_folder="")
   	{
		$this->call("Application.Restart?scriptpath=".urlencode($scriptpath)."&port=".urlencode($port)."&params=".urlencode($params)."&cache_folder=".urlencode($cache_folder)."&cookies_folder=".urlencode($cookies_folder),2);
   	}
	// pause script running by time (in minutes, if 0 endless)
   	function pause($timeout=0)
   	{
		if ($timeout==0)
		{
			$this->call("Application.Pause?timeout=".urlencode($timeout));
			fgets(STDIN);		
		}
		else
			sleep($timeout/1000);
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// run content of bat file by given path
   	function run_as_bat($content,$path,$show)
   	{
		$this->call("Application.RunAsBat?content=".urlencode($content)."&path=".urlencode($path)."&show=".urlencode($show));
   	}   
	// run content php file by given path
   	function run_as_php($content,$path,$show,$params="")
   	{
     		$this->call("Application.RunAsPHP?content=".urlencode($content)."&path=".urlencode($path)."&show=".urlencode($show)."&params=".urlencode($params));
   	}
	// execute external program by given path
   	function shell_execute($operat,$file,$param,$dir,$show)
   	{
      		$this->call("Application.ShellExecute?operat=".urlencode($operat)."&file=".urlencode($file)."&param=".urlencode($param)."&show=".urlencode($show));
   	}
	// get file from disk by given path
   	function get_file_from_disk($path)
   	{
      		return $this->call("Application.GetFileFromDisk?path=".urlencode($path));
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // show progress bar
        function show_progress_bar($show)
        {
                if ($this->call("Application.ShowProgressBar?show=".urlencode($show))=="true")
			return true;
		else
			return false;				
        }
        // set progress bar range
        function set_progress_range($lower,$upper,$step=1)
   	{
	     	$ret=$this->call("Application.SetRange?lower=".urlencode($lower)."&upper=".urlencode($upper)."&step=".urlencode($step));
		if($ret=="true")
                   return true;
                else
         	   return false;
   	}
        
        // set progress bar position
        function set_progress_pos($pos)
        {
                if ($this->call("Application.SetPos?pos=".urlencode($pos))=="true")
			return true;
		else
			return false;				
        }
        // advance the position to the next step
        function step_progress()
        {
                if ($this->call("Application.StepIt")=="true")
			return true;
		else
			return false;				
        }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get captcha by image number
   	function get_dlg_input_string($dlg_name,$dlg_text)
   	{
	     	$ret=$this->call("Application.InputString?dlg_name=".urlencode($dlg_name)."&dlg_text=".urlencode($dlg_text));
		fgets(STDIN);
         	return $ret;
   	}
   	// show question dilaog
   	function dlg_question($message)
   	{
	     	$ret=$this->call("Application.ShowQuestionDialog?message=".urlencode($message));
		fgets(STDIN);
         	return $ret;
   	}

   	// get file path trought dialog
   	function get_dlg_select_file($path,$action)
   	{
	     	$ret=$this->call("Application.SelectFileDialog?path=".urlencode($path)."&action=".urlencode($action));
		fgets(STDIN);
         	return $ret;
   	}
   	// get folder path trought dialog
   	function get_dlg_select_folder($path,$caption,$action)
   	{
	     	$ret=$this->call("Application.SelectFolderDialog?path=".urlencode($path)."&action=".urlencode($action)."&caption=".urlencode($caption));
		fgets(STDIN);
         	return $ret;
   	}
   	// show captcha dialog and get capcthca by url
   	function dlg_captcha_from_url($url)
   	{
	     	$ret=$this->call("Application.GetCaptchaFromUrl?url=".urlencode($url));
		fgets(STDIN);
        	return $ret;
   	}
	// show captcha dialog and get captcha by image number
   	function dlg_captcha_from_image_number($number)
   	{
	     	$ret=$this->call("Application.GetCaptchaByImageNumber?number=".urlencode($number));
		fgets(STDIN);
        	return $ret;
   	}
 	// get capcthca by url
   	function dlg_captcha_from_url_exactly($url,$exactly)
   	{
	     	$ret=$this->call("Application.GetCaptchaFromUrlExactly?url=".urlencode($url)."&exactly=".urlencode($exactly));
		fgets(STDIN);
        	return $ret;
   	}
	// xml dialog 
	function show_free_dlg($xml,$is_ret_xml="true",$separator="\r\n")
	{
		return $this->call("Application.ShowDlg?xml=".urlencode($xml)."&is_ret_xml=".urlencode($is_ret_xml)."&separator=".urlencode($separator));
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
};
?>