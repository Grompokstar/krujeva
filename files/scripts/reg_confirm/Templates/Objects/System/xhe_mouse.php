<?php
// 2783
//////////////////////////////////////////////////// Mouse /////////////////////////////////////////////////
class XHEMouse extends XHEBaseObject
{
	///////////////////////////////////////////////////////// SERVICE VARIABLES /////////////////////////////////////////////////////////////
	// temporary
   	var $x;
   	var $y;
	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHEMouse($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "Mouse";
	}
	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// emullate click to selected point (browser related)
	function click($x,$y)
	{
		return $this->call("Mouse.Click?x=".urlencode($x)."&y=".urlencode($y));
	}
   	// emullate left button down (browser related)
   	function left_button_down($x,$y)
   	{
     		return $this->call("Mouse.LeftDown?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// emullate left button up (browser related)
   	function left_button_up($x,$y)
   	{
     		return $this->call("Mouse.LeftUp?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// emullate double click (browser related)
   	function double_click($x,$y)
   	{
     		return $this->call("Mouse.DoubleClick?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// send click to selected point (browser related)
	function send_click($x,$y)
	{
		return $this->call("Mouse.SendClick?x=".urlencode($x)."&y=".urlencode($y));
	}
   	// send left button down (browser related)
   	function send_left_button_down($x,$y)
   	{
     		return $this->call("Mouse.SendLeftDown?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// send left button up (browser related)
   	function send_left_button_up($x,$y)
   	{
     		return $this->call("Mouse.SendLeftUp?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// emullate right button click (browser related)
   	function right_button_click($x,$y)
   	{
     		return $this->call("Mouse.ClickRigthButton?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// emullate right button down (browser related)
   	function right_button_down($x,$y)
   	{
     		return $this->call("Mouse.RightDown?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// emullate right button up (browser related)
   	function right_button_up($x,$y)
   	{
     		return $this->call("Mouse.RightUp?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// emullate move to selected point (browser related)
	function move($x,$y)
	{
		return $this->call("Mouse.Move?x=".urlencode($x)."&y=".urlencode($y));
	}
   	// emullate move wheel (browser related)
   	function wheel($time,$x,$y)
   	{
     		return $this->call("Mouse.Wheel?time=".urlencode($time)."&x=".urlencode($x)."&y=".urlencode($y));
   	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// send right button click (browser related)
   	function send_right_button_click($x,$y)
   	{
     		return $this->call("Mouse.SendClickRigthButton?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// send right button down (browser related)
   	function send_right_button_down($x,$y)
   	{
     		return $this->call("Mouse.SendRightDown?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	// send right button up (browser related)
   	function send_right_button_up($x,$y)
   	{
     		return $this->call("Mouse.SendRightUp?x=".urlencode($x)."&y=".urlencode($y));
   	}
   	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
   	// get x mouse position (browser related)
   	function get_x()
   	{
      		return $this->call("Mouse.GetX");
   	}
   	// get y mouse position (browser related)
    	function get_y()
   	{
     		return $this->call("Mouse.GetY");
   	}
   	// get x and y mouse position (browser related)
   	function get_position ()
   	{
     		$res = $this->call("Mouse.GetXY");
     		$pos =strpos($res," ");
     		$this->x = substr($res ,0,$pos);
     		$this->y = substr($res ,$pos+1,strlen($res)-$pos-1);
   	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// emullate click to selected point on desktop screen
	function click_to_screen($x,$y)
	{
		return $this->call("Mouse.ClickAbsolute?x=".urlencode($x)."&y=".urlencode($y));
	}
	// emullate move to selected point 	
	function move_on_screen($x,$y)
	{
		return $this->call("Mouse.MoveAbsolute?x=".urlencode($x)."&y=".urlencode($y));
	}
        //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        // send click to selected point (flash player related)
	function send_click_to_flash_player($x,$y,$flash_num,$bUseFlashXY=false)
	{
		return $this->call("Mouse.SendClickToFlashPlayer?x=".urlencode($x)."&y=".urlencode($y)."&flash_num=".urlencode($flash_num)."&bUseFlashXY=".urlencode($bUseFlashXY));
	}
        // send right click to selected point (flash player related)
	function send_right_click_to_flash_player($x,$y,$flash_num,$bUseFlashXY=false)
	{
		return $this->call("Mouse.SendRigthClickToFlashPlayer?x=".urlencode($x)."&y=".urlencode($y)."&flash_num=".urlencode($flash_num)."&bUseFlashXY=".urlencode($bUseFlashXY));
	}
        // send mouse move to selected point (flash player related)
	function send_mouse_move_to_flash_player($x,$y,$flash_num,$bUseFlashXY=false)
	{
		return $this->call("Mouse.SendMouseMoveToFlashPlayer?x=".urlencode($x)."&y=".urlencode($y)."&flash_num=".urlencode($flash_num)."&bUseFlashXY=".urlencode($bUseFlashXY));
	}
        // get mouse position to selected point (flash player related)
	function get_mouse_pos_to_flash_player($flash_num,$x="",$y="")
	{
		return $this->call("Mouse.GetMousePosToFlashPlayer?x=".urlencode($x)."&y=".urlencode($y)."&flash_num=".urlencode($flash_num));
	}
};
?>