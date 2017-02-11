<?php
// 3527
//////////////////////////////////////////////////////////// SEO - get several site info ////////////////////////////////////////////////////////
class XHESubmitter extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHESubmitter($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Submitter";
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
  	
	/////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// get random name
        function generate_random_name($lang)
	{
		return $this->call("Submitter.GetRandomName?lang=".urlencode($lang));
	}
	// get random second name
        function generate_random_second_name($lang)
	{
		return $this->call("Submitter.GetRandomSecondName?lang=".urlencode($lang));
	}
	// get random street
        function generate_random_street($lang)
	{
		return $this->call("Submitter.GetRandomStreet?lang=".urlencode($lang));
	}
	// get random city
        function generate_random_city($lang)
	{
		return $this->call("Submitter.GetRandomCity?lang=".urlencode($lang));
	}
	// get random region
        function generate_random_region($lang)
	{
		return $this->call("Submitter.GetRandomRegion?lang=".urlencode($lang));
	}
	// get random country
        function generate_random_country($lang)
	{
		return $this->call("Submitter.GetRandomCountry?lang=".urlencode($lang));
	}
	// get random nick name
        function generate_random_nick_name($len)
	{
		return $this->call("Submitter.GenRandomNick?len=".urlencode($len));
	}
	// get random text
        function generate_random_text($len,$type)
	{
		return $this->call("Submitter.GenRandomText?len=".urlencode($len)."&type=".urlencode($type));
	}
	// get random number
        function generate_random_number($min,$max)
	{
		return $this->call("Submitter.GenRandomNum?min=".urlencode($min)."&max=".urlencode($max));
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};
?>