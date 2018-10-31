<?
	function varDump(){
		
		global $USER;
		
		if($USER->IsAdmin()){
			$numargs = func_num_args();
			
			$arg_list = func_get_args();
			
			echo "<pre>";
			for ($i = 0; $i < $numargs; $i++) {
				 var_dump($arg_list[$i]);
			}
			echo "</pre>";
		}
	}