<?php
// 2799
//////////////////////////////////////////////////////////// MAIL - work with e-mail ////////////////////////////////////////////////////////
class XHEMail extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS ////////////////////////////////////////////////////////////
	// server initialization
	function XHEMail($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Mail";
	}
	  	
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////////////////////
	// connect to pop3 mail server
        function pop3_connect($host,$user,$login,$port=110)
	{
		if ($this->call("Mail.ConnectPOP3?host=".urlencode($host)."&user=".urlencode($user)."&login=".urlencode($login)."&port=".urlencode($port))=="true")
			return true;
		else
			return false;
	}
	// disconnect from current connected pop3 server
        function pop3_disconnect()
	{
		if ($this->call("Mail.DisconnectPOP3")=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get total count of mails 
        function get_total_count_of_mails()
	{
		return $this->call("Mail.GetTotalCountOfMails");
	}
	// get total size of mails 
        function get_total_size_of_mails()
	{
		return $this->call("Mail.GetTotalSizeOfMails");
	}
	// set pop3 timeout
        function set_pop3_timeout($time_out)
	{
		return $this->call("Mail.SetPop3Timeout?timeout=".urlencode($time_out));
	}
	// get pop3 timeout
        function get_pop3_timeout()
	{
		return $this->call("Mail.GetPop3Timeout");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get message header by number (without retrive)
        function get_message_header_by_number($number)
	{
		return $this->call("Mail.GetMessageHeaderByNumber?number=".urlencode($number));
	}
	// get message size by number (without retrive)
        function get_message_size_by_number($number)
	{
		return $this->call("Mail.GetMessageSizeByNumber?number=".urlencode($number));
	}
	// get message ID by number (without retrive)
        function get_message_id_by_number($number)
	{
		return $this->call("Mail.GetMessageIDByNumber?number=".urlencode($number));
	}
	// get message subject by number (without retrive)
        function get_message_subject_by_number($number)
	{
		return $this->call("Mail.GetMessageSubjectByNumber?number=".urlencode($number));
	}
	// get message from by number (without retrive)
        function get_message_from_by_number($number)
	{
		return $this->call("Mail.GetMessageFromByNumber?number=".urlencode($number));
	}
	// get message date by number (without retrive)
        function get_message_date_by_number($number)
	{
		return $this->call("Mail.GetMessageDateByNumber?number=".urlencode($number));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// retriev message by number
        function retrieve_message_by_number($number)
	{
		return $this->call("Mail.RetrieveMessageByNumber?number=".urlencode($number));
	}
	// retriev message by number
        function delete_message_by_number($number)
	{
		if ($this->call("Mail.DeleteMessageByNumber?number=".urlencode($number))=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// connect to smtp mail server (типы коннекта -   NoLogin=0, CramMD5=1, Auth Login=2, Login Plain=3)
        function smtp_connect($host,$user,$login,$port=25,$type=0)
	{
		if ($this->call("Mail.ConnectSMTP?host=".urlencode($host)."&user=".urlencode($user)."&login=".urlencode($login)."&port=".urlencode($port))=="true")
			return true;
		else
			return false;
	}
	// disconnect from current connected smtp server
        function smtp_disconnect()
	{
		if ($this->call("Mail.DisconnectSMTP")=="true")
			return true;
		else
			return false;
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// set pop3 timeout
        function set_smtp_timeout($time_out)
	{
		return $this->call("Mail.SetSMTPTimeout?timeout=".urlencode($time_out));
	}
	// get smtp timeout
        function get_smtp_timeout()
	{
		return $this->call("Mail.GetSMTPTimeout");
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// send text message
        function send_text_message($text,$to,$from="",$subject="")
	{
		if ($this->call("Mail.SendTextMessage?text=".urlencode($text)."&to=".urlencode($to)."&from=".urlencode($from)."&subject=".urlencode($subject))=="true")
			return true;
		else
			return false;
	}
	// send html message
        function send_html_message($html,$to,$from="",$subject="")
	{
		if ($this->call("Mail.SendHtmlMessage?html=".urlencode($html)."&to=".urlencode($to)."&from=".urlencode($from)."&subject=".urlencode($subject))=="true")
			return true;
		else
			return false;
	}
	// find 
        function find_and_navigate_on_link_by_number($mail_num, $link_num)
	{
		if ($this->call("Mail.FindAndNavLinkNum?mail_num=".urlencode($mail_num)."&link_num=".urlencode($link_num)))
			return true;
		else
			return false;
	}

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>