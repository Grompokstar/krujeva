<?php
// 6979
//////////////////////////////////////////////////////////// SEO - get several site info ////////////////////////////////////////////////////////
class XHEProxyCheker extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHEProxyCheker($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "ProxyChecker";
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	/////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// add proxy to list
        function add_proxy($str_proxy)
	{
		return $this->call("ProxyCheker.AddProxy?str_proxy=".urlencode($str_proxy));
	}
	// add proxy to file
        function add_proxy_from_file($path)
	{
		return $this->call("ProxyCheker.AddProxyFile?path=".urlencode($path));
	}
	// add proxy to list from url
        function add_proxy_from_url($url)
	{
		return $this->call("ProxyCheker.AddProxyUrl?url=".urlencode($url));
	}
	// set speed testing
        function set_speed_testing($speed)
	{
		return $this->call("ProxyCheker.SetSpeed?speed=".urlencode($speed));
	}
	// set quality testing
        function set_quality_testing($quality)
	{
		return $this->call("ProxyCheker.SetQuality?quality=".urlencode($quality));
	}

	// get some proxy
        function get_proxy($n,$param_proxy="all")
	{
		return $this->call("ProxyCheker.GetProxy?param_proxy=".urlencode($param_proxy));
	}
	// get fastest proxy
        function get_fastest_proxy($param_proxy="all")
	{
		return $this->call("ProxyCheker.FastestProxy?param_proxy=".urlencode($param_proxy));
	}
	// get count some proxy
        function get_count_proxy($param_proxy="all")
	{
		return $this->call("ProxyCheker.CetCountProxy?param_proxy=".urlencode($param_proxy));
	}
	// clear some proxy from list
        function delete_proxy($param_proxy="all")
	{
		return $this->call("ProxyCheker.DeleteProxy?param_proxy=".urlencode($param_proxy));
	}
	// save list proxy to file
        function save_proxy($path,$param_proxy="all")
	{
		return $this->call("ProxyCheker.SaveProxy?path=".urlencode($path)."&param_proxy=".urlencode($param_proxy));
	}
	// dedupe list proxy
        function dedupe_proxy()
	{
		return $this->call("ProxyCheker.DedupeProxy");
	}
	function is_running()
	{
		return $this->call("ProxyCheker.IsRun");
	}

        function run($is_wait="false")
	{
		$flag = $this->call("ProxyCheker.Run");
		while($is_wait && $this->is_running()){ sleep(1);  };
		return flag;

	}
        function stop()
	{
		return $this->call("ProxyCheker.Stop");
	}
        


	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};
?>