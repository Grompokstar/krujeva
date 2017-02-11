<?php
// 2792
//////////////////////////////////////////////////// Browser - several Functional for work with browser /////////////////////////////////////////
class XHEBrowser extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHEBrowser($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Browser";
	}
	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

	//////////////////////////////////////////////////////////// FUNCTIONAL /////////////////////////////////////////////////////////////////
	// navigate to the given URL
	function navigate($url,$use_cache=true)
	{
		if ($this->call("Browser.Navigate?url=".urlencode($url)."&use_cache=".urlencode($use_cache))=="true")
			return true;
		else
			return false;		
	}
	// is browser now is busy
	function is_busy($num=-1)
	{               		
                if ($this->call("Browser.IsBusy?num=".urlencode($num))=="true")
                	return true;
		else
			return false;		

	}
	// get browser ready state
	function get_ready_state()
	{               		
                return $this->call("Browser.GetReadyState");
	}

	// wait while explorer is busy (maybe it's loading web page now)
	function wait($num=1)
	{
		$is_busy = $this->call("Browser.IsBusy?num=".urlencode($num));
		while($is_busy=="true")
		{
			sleep($num);
			$is_busy = $this->call("Browser.IsBusy?num=".urlencode($num));      
		}
               return true;

	}
        // wait some sec and refresh count
        function wait_for($sec,$n,$num=-1)
        {
                global $i_p,$a_p;
                $i_p=0;
                $a_p=0;
                $is_busy = $this->call("Browser.IsBusy?num=".urlencode($num));
		while($is_busy=="true")
		{
                       if($sec<=$i_p)
                       { 
                         global $i_p,$a_p; 
                         $i_p=0;
                         if($a_p==$n)
                         {
                           //echo "<br> connection problem! <br>";
		           return false;
                           break;
                         }
                         $this->call("Browser.Navigate?url=".urlencode($this->call("WebPage.GetLocationURL")));
                         $a_p++;                                   
                        }

			sleep(1);
                        global $i_p;
                        $i_p++;
                        //echo " i_p = ".urlencode($i_p);
			$is_busy = $this->call("Browser.IsBusy?num=".urlencode($num));               
	        }
		return true;
        }
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// refresh browser
	function refresh()
	{
		if ($this->call("Browser.Refresh")=="true")
			return true;
		else
			return false;		
	}
	// stop navigate
	function stop()
	{
		if ($this->call("Browser.Stop")=="true")
			return true;
		else
			return false;		
	}
	// go back
	function go_back()
	{
		if ($this->call("Browser.GoBack")=="true")
			return true;
		else
			return false;		
	}
        // close current active browser if it not Main browser
	function close()
	{
		if ($this->call("Browser.Close")=="true")
			return true;
		else
			return false;		
	}
        // close all opened browser without Main browser
	function close_all_tabs()
	{
		if ($this->call("Browser.CloseAll")=="true")
			return true;
		else
			return false;		
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// recreate browser control
	function recreate()
	{
		return $this->call("Browser.Recreate");
   	}	
	// clear IE cash (in future will replace -> clear_cache)
	function clear_cash()
	{
		if ($this->call("Browser.ClearCache")=="true")
			return true;
		else
			return false;		
	}
	// clear IE cache (right syntaxis)
	function clear_cache()
	{
		if ($this->call("Browser.ClearCache")=="true")
			return true;
		else
			return false;		
	}
	// clear IE history
	function clear_history()
	{
		if ($this->call("Browser.ClearHistory")=="true")
			return true;
		else
			return false;		
	}
	// clear IE address bar history
	function clear_address_bar_history()
	{
		if ($this->call("Browser.ClearAddressBarHistory")=="true")
			return true;
		else
			return false;		
	}
	// clear IE cookies
	function clear_cookies($match_name,$clear_session=false)
	{
		if ($this->call("Browser.ClearCookies?match_name=".urlencode($match_name)."&clear_session=".urlencode($clear_session))=="true")
			return true;
		else
			return false;		
	}
	// get browser version
	function get_version($numerica)
	{
		return $this->call("Browser.GetBrowserVersion?numerica=".urlencode($numerica));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// enable proxy to browser
	function enable_proxy($connection,$proxy)
	{
     
		if ($this->call("Browser.EnableProxy?connection=".urlencode($connection)."&proxy=".urlencode($proxy))=="true")
			return true;
		else
			return false;		
	}
	// disable proxy to browser
	function disable_proxy($connection)
	{
		if ($this->call("Browser.DisableProxy?connection=".urlencode($connection))=="true")
			return true;
		else
			return false;
  	}	
	// get current proxy
	function get_current_proxy($connection)
	{
		return $this->call("Browser.GetCurrentProxy?connection=".urlencode($connection));
  	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	// get page width
	function get_page_width()
	{
		return $this->call("Browser.GetPageWidth");
   	}	
	// get page height
	function get_page_height()
	{
		return $this->call("Browser.GetPageHeight");
   	}	
	// set vertical scroll pos
	function set_vertical_scroll_pos($y)
	{
		return $this->call("Browser.SetVerticalScrollPos?y=".urlencode($y));
   	}	
	// set vertical scroll pos
	function set_horizontal_scroll_pos($x)
	{
		return $this->call("Browser.SetHorizontalScrollPos?x=".urlencode($x));
   	}	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
  	// set browser width
	function set_width($width)
	{
		return $this->call("Browser.SetBrowserWidth?width=".urlencode($width));
   	}	
	// set browser height
	function set_height($height)
	{
		return $this->call("Browser.SetBrowserHeight?height=".urlencode($height));
   	}	
  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	// set default http authorization
	function set_default_authorization($login,$password)
	{
		$res = ($this->call("Browser.SetDefaultAuthorization?login=".urlencode($login)."&password=".urlencode($password))=="true");
                return $res;
   	}	
	// reset default http authorization
	function reset_default_authorization()
	{
		$res = ($this->call("Browser.ResetDefaultAuthorization")=="true");
                return $res;
   	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	// set default download file
	function set_default_download($folder)
	{
		$res = ($this->call("Browser.SetDefaultDownload?folder=".urlencode($folder))=="true");
                return $res;
   	}
        // reset default download
	function reset_default_download()
	{
		$res = ($this->call("Browser.ResetDefaultDownload")=="true");
                return $res;
   	}		
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
	// set user agent string
	function set_user_agent($agent_string,$refresh=true)
	{
		return $this->call("Browser.SetUserAgent?agent_string=".urlencode($agent_string)."&refresh=".urlencode($refresh));
   	}
   	// set accept
	function set_accept($accept_string)
	{
		return $this->call("Browser.SetAccept?accept_string=".urlencode($accept_string));
   	}
   	// set user agent string
	function set_accept_encoding($accept_string)
	{
		return $this->call("Browser.SetAcceptEncoding?accept_string=".urlencode($accept_string));
   	}
   	// set user agent string
	function set_accept_language($accept_string)
	{
		return $this->call("Browser.SetAcceptLanguage?accept_string=".urlencode($accept_string));
   	}
   	// set user agent string
	function set_accept_charset($accept_string)
	{
		return $this->call("Browser.SetAcceptCharset?accept_string=".urlencode($accept_string));
   	}	
  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
   	// set user agent string
	function set_referer($referer)
	{
		return $this->call("Browser.SetReferer?referer=".urlencode($referer));
   	}	
  	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
   	// get cookie
	function get_cookie()
	{
		return $this->call("Browser.GetCookie");
   	}	
	// set cookie
	function set_cookie($cookie)
	{
		return $this->call("Browser.SetCookie?cookie=".urlencode($cookie));
   	}
   	// check internet connection
	function check_internet_connection()
	{
		return ($this->call("Browser.CheckInternetConnection")=="true");
   	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
   	// get cookie
	function get_cookie_for_url($url,$name)
	{
		return $this->call("Browser.GetCookieForUrl?url=".urlencode($url)."&name=".urlencode($name));
   	}	
	// set cookie
	function set_cookie_for_url($url,$name,$cookie)
	{
		return $this->call("Browser.SetCookieForUrl?url=".urlencode($url)."&name=".urlencode($name)."&cookie=".urlencode($cookie));
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
   	// get selected text
	function get_selected_text($as_html)
	{
		return $this->call("Browser.GetSelectedText?as_html=".urlencode($as_html));
	}
   	// save page as 
	function save_page_as($file)
	{
		return $this->call("Browser.SavePageAs?file=".urlencode($file));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
   	// enable popup
	function enable_popup($enable,$refresh=true)
	{
		return $this->call("Browser.EnablePopup?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
     	// enable images
	function enable_images($enable,$refresh=true)
	{
		return $this->call("Browser.EnableImage?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
     	// enable ActiveX
	function enable_activex($enable,$refresh=true)
	{
		return $this->call("Browser.EnableActiveX?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
     	// enable Java Script
	function enable_java_script($enable,$refresh=true)
	{
		return $this->call("Browser.EnableJavaScript?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
     	// enable Quiet Regime
	function enable_quiet_regime($enable,$refresh=true)
	{
		return $this->call("Browser.QuietRegime?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
     	// enable popup
	function disable_script_error($enable,$refresh=true)
	{
		return $this->call("Browser.DisableScriptError?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
        // disable download file 
	function disable_download_file_dialog($enable)
	{
		return $this->call("Browser.DisableDownload?enable=".urlencode($enable));
	}
     	// enable browser message boxes ($answer is one from "Ok","Cancel","Abort","Retry","Ignore","Yes","No")
	function enable_browser_message_boxes($enable,$default_answer="Ok")
	{
		return $this->call("Browser.EnableBrowserMessageBoxes?enable=".urlencode($enable)."&default_answer=".urlencode($default_answer));
	}
        // get last message box caption
	function get_last_messagebox_caption()
	{
		return $this->call("Browser.GetLastMessageBoxCaption");
	}
        // get last message box text
	function get_last_messagebox_text()
	{
		return $this->call("Browser.GetLastMessageBoxText");
	}
        // get last message box type
	function get_last_messagebox_type()
	{
		return $this->call("Browser.GetLastMessageBoxType");
	}
        // clear last messagebox info
	function clear_last_messagebox_info()
	{
		if ($this->call("Browser.ClearLastMessageBoxInfo")=="true")
			return true;
		else
			return false;
	}
   	// enable sounds
	function enable_sounds($enable,$refresh=true)
	{
		return $this->call("Browser.EnableSounds?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
   	// enable frames
	function enable_frames($enable,$refresh=true)
	{
		return $this->call("Browser.EnableFrames?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
   	// enable java
	function enable_java($enable,$refresh=true)
	{
		return $this->call("Browser.EnableJava?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
   	// enable video
	function enable_video($enable,$refresh=true)
	{
		return $this->call("Browser.EnableVideo?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
   	// enable cache
	function enable_cache($enable,$refresh=true)
	{
		return $this->call("Browser.EnableCache?enable=".urlencode($enable)."&refresh=".urlencode($refresh));
	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// check current connections
   	function check_connection($url,$timeout,$use_cache=false,$num=-1)
   	{
      		// делаем переход
      		if($this->call("Browser.Navigate?url=".urlencode($url)."&use_cache=".urlencode($use_cache))=="false")
        		return false;

      		global $time;
      		$time=0;
		// wait
      		$is_busy = $this->call("Browser.IsBusy?num=".urlencode($num));
     		while($is_busy=="true")
		{
         		global $time;
         		$time++;
			sleep(1);

			$is_busy = $this->call("Browser.IsBusy?num=".urlencode($num));      
         		if($time==$timeout)
            			return ($is_busy=="false");
		}
      
      		$text =  $this->call("WebPage.GetBody");
      		if($text=="")
        		return false;
     
      		$index= strpos($text,"Forbidden");
      		if($index!=null)
         		return false;
      
      		$ind= strpos($text,"The page cannot be found");
      		if($ind!=null)
         		return false;
            
                $ind= strpos($text,"Нет подключения к Интернету.");
                if($ind!=null)
                        return false;
                
                $ind= strpos($text,"Эта программа не может отобразить эту веб-страницу");
                if($ind!=null)
                       return false;
            
                $ind= strpos($text,"Переход на веб-страницу отменен");
                if($ind!=null)
                       return false;
             
                $ind= strpos($text,"The requested URL could not be retrieved");
                if($ind!=null)
                       return false;
      
                $ind= strpos($text,"Navigation to the webpage was canceled");
                if($ind!=null)
                       return false;

                $ind= strpos($text,"This program cannot display the webpage");
                if($ind!=null)
                       return false;

               $ind= strpos($text,"Bad Gateway");
                if($ind!=null)
                       return false;
          
               $ind= strpos($text,"Internal Server Error");
                if($ind!=null)
                       return false;

               $ind= strpos($text,"The website cannot display the page");
                if($ind!=null)
                       return false;

      		return ($is_busy=="false");
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
        // get popup body by url
	function get_popup_source($url,$exactly)
	{
		return $this->call("Browser.GetPopUpSource?url=".urlencode($url)."&exactly=".urlencode($exactly));
        }	
        // close popup 
	function close_popup($url,$exactly)
	{
		return $this->call("Browser.ClosePopup?url=".urlencode($url)."&exactly=".urlencode($exactly));
        }	
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
     	// paste text from clipboard to browser
	function paste()
	{
		if ( $this->call("Browser.Paste")=="true" )
			return true;
		else
			return false;
	}
     	// set optical zoom to browser
	function set_zoom($zoom)
	{
		if ( $this->call("Browser.SetZoom?zoom=".urlencode($zoom))=="true" )
			return true;
		else
			return false;
	}
     	// run browser command (ex: IDM_PASTE,IDM_COPY,IDM_PRINT etc.)
	function run_command($command)
	{
		if ($this->call("Browser.RunCommand?command=".urlencode($command))=="true")
			return true;
		else
			return false;
	}
        // send post query
	function send_post_query($url,$data,$type="application/x-www-form-urlencoded",$set_as_page=0)
	{
                $res=$this->call("Browser.SendPostQuery?url=".urlencode($url)."&data=".urlencode($data)."&type=".urlencode($type)."&set_as_page=".urlencode($set_as_page));
		if($res!="")
                   return $res;
                else
                   return false;   
        }
        // send get query
	function send_get_query($url,$data)
	{
            $res=$this->call("Browser.SendGetQuery?url=".urlencode($url)."&data=".urlencode($data));
		if($res!="")
                   return $res;
                else
                   return false;   
        }
        // send post query
	function call_java_script($func,$parametrs)
	{
                $res=$this->call("Browser.CallJavaScript?func=".urlencode($func)."&parametrs=".urlencode($parametrs));
		if($res!="")
                   return $res;
                else
                   return false;   
        }
     	// change cookies folder
	function change_cookies_folder($folder)
	{
		if ($this->call("Browser.ChangeCookiesFolder?folder=".urlencode($folder))=="true")
			return true;
		else
			return false;
	}
     	// change cache folder
	function change_cache_folder($folder)
	{
		if ($this->call("Browser.ChangeCacheFolder?folder=".urlencode($folder))=="true")
			return true;
		else
			return false;
	}
     	// set browser tabs count
	function set_count($count)
	{
		if ($this->call("Browser.SetCount?count=".urlencode($count))=="true")
			return true;
		else
			return false;
	}
     	// set active browser tab
	function set_active_browser($num,$activate=true)
	{
		if ($this->call("Browser.SetActiveBrowser?num=".urlencode($num)."&activate=".urlencode($activate))=="true")
			return true;
		else
			return false;
	}
     	// disable security problem dialogs
	function disable_security_problem_dialogs($disable)
	{
		if ($this->call("Browser.DisableSecurityProblemDlg?disable=".urlencode($disable))=="true")
			return true;
		else
			return false;
	}
     	// get window width
	function get_window_width()
	{
		return $this->call("Browser.GetWindowWidth");
	}
     	// get window height
	function get_window_height()
	{
		return $this->call("Browser.GetWindowHeight");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////	
};
?>