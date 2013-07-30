<?php
//(meni) često korišteni konstrukti u PHPu, U zbog kratkoće
	class U{
		public static function varFromRequest($name, $default='',$unset=false){
			if (isset($_REQUEST[$name])) $var=$_REQUEST[$name];
				else $var=$default;
			if ($unset) unset($_REQUEST[$name]);
			return $var;
		}
		public static function removeElFromArray(&$ar,$el){
			$tar=array();
			for ($i=0;$i<count($ar);$i++) if ($ar[$i]!=$el) $tar[]=$ar[$i];
			$ar=$tar;
		}
		public static function is_set($name){
			if (isset($_REQUEST[$name])) return true;
			return false;
		}
	}
?>