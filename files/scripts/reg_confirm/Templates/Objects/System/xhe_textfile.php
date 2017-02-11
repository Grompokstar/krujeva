<?php
// 2789
//////////////////////////////////////////////////// Text File /////////////////////////////////////////////////
class XHETextFile extends XHEBaseObject
{
   	///////////////////////////////////////////////////////// SERVICVE FUNCTIONS /////////////////////////////////////////////////////////////
	// server initialization
	function XHETextFile($server,$password="")
	{    
		$this->server = $server;
		$this->password = $password;
		$this->prefix = "TextFile";
	}


	//////////////////////////////////////////////////////////// FUNCTIONAL //////////////////////////////////////////////////
	// sort strings in file
   	function sort($infilepath,$outfilepath,$timeout)
   	{
      		return $this->call("TextFile.Sort?infilepath=".urlencode($infilepath)."&outfilepath=".urlencode($outfilepath),$timeout);
   	}
  	// dedupe strings in file
   	function dedupe($infilepath,$outfilepath,$timeout)
   	{
      		return $this->call("TextFile.Dedupe?infilepath=".urlencode($infilepath)."&outfilepath=".urlencode($outfilepath),$timeout);
   	}
   	// get file lines count
   	function get_lines_count($filepath,$timeout) 
   	{
      		return $this->call("TextFile.GetLineCount?filepath=".urlencode($filepath),$timeout);
   	}
        // get line from file
   	function get_line_from_file($file,$rand,$line,$timeout) 
   	{
      		return $this->call("TextFile.GetLineFromFile?file=".urlencode($file)."&rand=".urlencode($rand)."&line=".urlencode($line),$timeout);
   	}
	// randomize to
   	function randomize_to($infilepath,$outfilepath,$timeout)
   	{
     		return $this->call("TextFile.RandomizeTo?infilepath=".urlencode($infilepath)."&outfilepath=".urlencode($outfilepath),$timeout);
   	}
	// randomize to
   	function file_links($path,$num_lines,$type_make)
   	{
     		return $this->call("TextFile.FileLinks?path=".urlencode($path)."&num_lines=".urlencode($num_lines)."&type_make=".urlencode($type_make));
   	}
  	// split files
        function split_to_part($infilepath,$outfilepath,$numparts,$timeout) 
   	{
                return $this->call("TextFile.SplitToPart?infilepath=".urlencode($infilepath)."&outfilepath=".urlencode($outfilepath)."&numparts=".urlencode($numparts),$timeout);
   	}	
   	// collect from folders to folder
   	function collect_from_folders_to_folder($infolderpath,$outfolderpath,$timeout)
   	{
     		return $this->call("TextFile.CollectFromFoldersToFolder?infolderpath=".urlencode($infolderpath)."&outfolderpath=".urlencode($outfolderpath),$timeout);
   	}
   	// collect from folders to file
	function collect_from_folders_to_file($infolderpath,$outfilepath,$timeout)
   	{
      		return $this->call("TextFile.CollectFromFoldersToFile?infolderpath=".urlencode($infolderpath)."&outfilepath=".urlencode($outfilepath),$timeout);
   	}
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// get all files in selected folder
   	function get_all_files_in_folder($folder,$file,$include_subfolders,$only_folders,$timeout)
   	{
      		return $this->call("TextFile.GetAllFilesInThisFolder?folder=".urlencode($folder)."&file=".urlencode($file)."&include_subfolders=".urlencode($include_subfolders)."&only_folders=".urlencode($only_folders),$timeout);
   	}
   	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
     	// write in file
   	function write_file($file,$str,$timeout) 
   	{
      		return $this->call("TextFile.WriteFile?file=".urlencode($file)."&str=".urlencode($str),$timeout);
   	}
        // append string to file
   	function add_string_to_file($file,$str,$timeout) 
   	{
      		return $this->call("TextFile.AddStringToFile?file=".urlencode($file)."&str=".urlencode($str),$timeout);
   	}
  	// read file content
   	function read_file($file,$timeout) 
   	{
      		return $this->call("TextFile.ReadFile?file=".urlencode($file),$timeout);
   	}
	// create folder
   	function create_folder($folder,$timeout)
   	{
      		return $this->call("TextFile.CreateFolder?folder=".urlencode($folder),$timeout);
   	}
	// get file folder
   	function get_file_folder($file,$timeout) 
   	{
      		return $this->call("TextFile.GetFileFolder?file=".urlencode($file),$timeout);
   	}    	
	// revert strings file
   	function revert_strings_file($infile,$outfile,$timeout) 
   	{
      		return $this->call("TextFile.RevertFile?infile=".urlencode($infile)."&outfile=".urlencode($outfile),$timeout);
   	}    	
	// generate folders by strings file
   	function generate_folders_by_strings_file($file,$folder,$timeout) 
   	{
      		return $this->call("TextFile.GenFolders?file=".urlencode($file)."&folder=".urlencode($folder),$timeout);
   	}    	
	// exclude strings file from file
   	function exclude_strings_file_from_file($infile,$excluding_file,$outfile,$timeout) 
   	{
      		return $this->call("TextFile.ExludeStrings?infile=".urlencode($infile)."&excluding_file=".urlencode($excluding_file)."&outfile=".urlencode($outfile),$timeout);
   	}    	
	// replace string
   	function replace_string($infile,$outfile,$old_str,$new_str,$timeout) 
   	{
      		return $this->call("TextFile.ReplaceString?infile=".urlencode($infile)."&outfile=".urlencode($outfile)."&old_str=".urlencode($old_str)."&new_str=".urlencode($new_str),$timeout);
   	}    	

	/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
};
?>