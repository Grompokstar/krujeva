<?php
include "xhe_get_pr.php";
// 2801
//////////////////////////////////////////////////////////// SEO - get several site info ////////////////////////////////////////////////////////
class XHESEO extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHESEO($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Seo";
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// get google page page rank to string
        function get_page_rank($url)
	{
		return getPageRank($url);
	}
	// get yandex TIZ to string
	function get_yandex_tiz($site)
	{
		return $this->call("Seo.GetYandexPageTiz?site=".urlencode($site));
	}
	// get alexa rank to string
	function get_alexa_rank($site)
	{
		return $this->call("Seo.GetAlexaRank?site=".urlencode($site));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get xml site map
	function get_sitemap($site,$file,$timeout)
	{
		return $this->call("Seo.GetSiteMap?site=".urlencode($site)."&file=".urlencode($file),$timeout);
	}
	// get all inner site links to file
	function get_all_sitemap_links($site,$file,$timeout)
	{
		return $this->call("Seo.GetSiteMapLinks?site=".urlencode($site)."&file=".urlencode($file),$timeout);
	}
   	// get all outside site links to file
   	function get_all_outside_links($site,$file,$timeout,$separator="<br>")
   	{
		return $this->call("Seo.GetOutsideSiteLinks?site=".urlencode($site)."&file=".urlencode($file)."&separator=".urlencode($separator),$timeout);
	}   
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>