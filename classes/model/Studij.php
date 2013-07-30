<?php
	class Studij extends DBObject{
		public static $_tablename = NULL; 
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_references=array();
		
		
	} Studij::_init('studiji');
	Studij::references('Kolegij','REFKolegijiStudiji');
?>