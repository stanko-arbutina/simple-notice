<?php
//osnovno spajanje s bazom 
	class DB {
		private static $connected=false;
		
		private static function connect(){
			$con = @mysql_connect("localhost","root","pero"); //ovdje mijenjati parametre za spajanje s bazom
			if (!$con) die(BV::DBerror("Greška u spajanju s bazom!"));
			else {
				mysql_select_db("simplenotice",$con);
				self::$connected=$con;
				return $con;
			}
		}
		
		public static function connect_if_not(){
			if (!self::$connected) self::connect();
		}
		
		public static function disconnect_if_connected(){
			if (self::$connected) {
				mysql_close(self::$connected);
			}
		}
		
		
		
		public static function query($sql) {
			self::connect_if_not();
			$rez=mysql_query($sql);
			if ($rez) return $rez;
			else die (BV::DBerror("Upit <i>$sql</i> javlja slijedeću grešku:"));
		}
		
		
		
	}
?>
