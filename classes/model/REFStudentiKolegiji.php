<?php

	//veza između kolegija i profesora koji ga drže
	class REFStudentiKolegiji extends DBReference {
		public static $_tablename = NULL;
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_DBObjects=array();
		
	} REFStudentiKolegiji::_init('ref_studenti_kolegiji',array('User'=>'student_id','Kolegij'=>'kolegij_id'));
	
?>