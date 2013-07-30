<?php
	class Obavijest extends DBObject{
		public static $_tablename = NULL; 
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_references=array();
	
		public function save(){
			$this->owner_id=SessionManager::getCurrentUserID();
			parent::save();
		}
		
		
	} Obavijest::_init('obavijesti');
	Obavijest::references('Kolegij','REFObavijestiKolegiji');
?>