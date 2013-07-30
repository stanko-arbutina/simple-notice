<?php
	class Kolegij extends DBObject{
		public static $_tablename = NULL; 
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_references=array();
	
		public function save(){
			$this->owner_id=SessionManager::getCurrentUserID();
			parent::save();
		}
		
		public function saveReferences(){
			$this->Studij()->deleteAllReferences();
			$lst=$this->studij_id;
			if ($lst){
			foreach ($lst as $studij_id => $godina_array)
				foreach ($godina_array as $key => $value)
					$this->Studij()->createReference($studij_id,array('godina'=>$key));
			}
		}
		
	} Kolegij::_init('kolegiji');
	Kolegij::references('Studij','REFKolegijiStudiji');
?>