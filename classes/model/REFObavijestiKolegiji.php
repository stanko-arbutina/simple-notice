<?php

	//veza između kolegija i profesora koji ga drže
	class REFObavijestiKolegiji extends DBReference {
		public static $_tablename = NULL;
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_DBObjects=array();
		
	} REFObavijestiKolegiji::_init('ref_obavijesti_kolegiji',array('Obavijest'=>'obavijest_id','Kolegij'=>'kolegij_id'));
	
?>