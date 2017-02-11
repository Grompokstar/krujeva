<?php
// 2748
//////////////////////////////////////////////////// Table /////////////////////////////////////////////////
class XHETable extends XHETableCompatible
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// server address and port
	var $server;
	// server password
	var $password;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHETable($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Table";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL ///////////////////////////////////////////////////////////////////	
	// get count of elements
	function get_count()
	{
		return $this->call("Table.GetCount");
	}
	// get cols by number
	function get_cols_by_number($number)
	{
		return $this->call("Table.GetColsByNumber?number=".urlencode($number));
	}
	// get rows by number
	function get_rows_by_number($number)
	{
		return $this->call("Table.GetRowsByNumber?number=".urlencode($number));
	}
	// get cols by number
	function get_height_by_number($number)
	{
		return $this->call("Table.GetHeightByNumber?number=".urlencode($number));
	}
	// get rows by number
	function get_width_by_number($number)
	{
		return $this->call("Table.GetWidthByNumber?number=".urlencode($number));
	}
	////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////// 
	// get count of elements
	function get_count_within_iframe_by_number($number)
	{
		return $this->call("Table.GetCountInFrameByNum?number=".urlencode($number));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get cell by number
	function get_cell_by_number($number,$row,$col,$as_html)
	{
		return $this->call("Table.GetCellByNumber?number=".urlencode($number)."&row=".urlencode($row)."&col=".urlencode($col)."&as_html=".urlencode($as_html));
	}
	// get cell by pos by number
	function get_cell_by_pos_by_number($number,$pos,$as_html)
	{
		return $this->call("Table.GetCellByPosByNumber?number=".urlencode($number)."&pos=".urlencode($pos)."&as_html=".urlencode($as_html));
	}
	// get cell count by number
	function get_cell_count_by_number($number)
	{
		return $this->call("Table.GetCellCountByNumber?number=".urlencode($number));
	}
	// get row by number
	function get_row_by_number($number,$row,$as_html)
	{
		return $this->call("Table.GetRowByNumber?number=".urlencode($number)."&row=".urlencode($row)."&as_html=".urlencode($as_html));
	}
	// get col by number
	function get_col_by_number($number,$col,$as_html)
	{
		return $this->call("Table.GetColByNumber?number=".urlencode($number)."&col=".urlencode($col)."&as_html=".urlencode($as_html));
	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};	
?>