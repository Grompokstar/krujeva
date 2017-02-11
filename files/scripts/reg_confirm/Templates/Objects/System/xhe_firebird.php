<?php
// 2777
//////////////////////////////////////////////////// Clipboard /////////////////////////////////////////////////
class XHEFirebird extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEFirebird($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Firebird";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////

	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// execute SQL
	function set_db($path)
	{
		if ($this->call("Firebird.SetDB?path=".urlencode($path)))      
			return true;
		else
			return false;
	}
	// execute SQL
	function exe_sql($sql)
	{
		return ($this->call("Firebird.ExeSQL?sql=".urlencode($sql)));

	}
	// create table
	function create_table($name_table,$arr_names_cells,$arr_types_cells)
	{			  
		return($this->call("Firebird.CreateTable?name_table=".urlencode($name_table)."&arr_names_cells=".urlencode($arr_names_cells)."&arr_types_cells=".urlencode($arr_types_cells)));

	}
	// insert record
	function insert_record($name_table,$fields,$values)
	{			  
		return($this->call("Firebird.InsertRecord?name_table=".urlencode($name_table)."&fields=".urlencode($fields)."&values=".urlencode($values)));


	}               
	function get_record($name_table,$name_colums,$where)
	{			  
		return ($this->call("Firebird.GetRecord?name_table=".urlencode($name_table)."&name_colums=".urlencode($name_colums)."&where=".urlencode($where)));

	}               
	// get count rows
	function get_count_rows($name_table)
	{			  
		return $this->call("Firebird.GetCountRows?name_table=".urlencode($name_table));


	}
	// get Colums from Firebird DB
	function get_colums($name_table)
	{			  
		return $this->call("Firebird.GetColums?name_table=".urlencode($name_table));


	}
	// get list tabels from Firebird DB
	function get_list_tabels()
	{			  
		return $this->call("Firebird.GetListTabls");

	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>