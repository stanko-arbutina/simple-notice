<?php

	//veza između kolegija i profesora koji ga drže
	class REFKolegijiStudiji extends DBReference {
		public static $_tablename = NULL;
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_DBObjects=array();
		
		public function getArrayForRequest(){
			$other=$this->other_key;
			$m=$this->parent_model;
			$rez=DB::query("SELECT * FROM ".static::$_tablename." ".$this->getQueryIdent().";");
			$rezarr=array();
			while ($row = mysql_fetch_object($rez)) 
				$rezarr[$row->studij_id][$row->godina]=1;
			return $rezarr;
		}
		public function getReferencesForUserSettings($pars=array()){
			$other=$this->other_key;
			$m=$this->other_model;
			$rez=DB::query("SELECT DISTINCT kolegij_id FROM ".static::$_tablename." ".$this->getQueryIdent($pars).";");
			$arr=array();
			while ($row = mysql_fetch_object($rez))
				$arr[]=$m::load($row->$other);
				
			return $arr;
		}		
	} REFKolegijiStudiji::_init('ref_kolegiji_studiji',array('Kolegij'=>'kolegij_id','Studij'=>'studij_id'));
	
?>