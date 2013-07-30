<?php
	//modelira many to many vezu između dva DBObjecta
	
	class DBReference extends DBTable {
		public static $_tablename = NULL;
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_DBObjects=array();
		
		public static function _init($tablename,$dbobjects){
			$pkeys=array();
			static::$_DBObjects=$dbobjects;
			foreach ($dbobjects as $key => $value) $pkeys[]=$value;
			static::$_tablename=$tablename;
			static::$_pkeys=$pkeys;
			static::$_columns=array_keys(static::getFields());
		}
		
		public $parent_object;
		public $parent_model;
		
		public $parent_key;
		public $other_key;
		public $other_model;
		
		private function getParentID(){
			return $this->parent_object->ID;
		}
		
		private function getIDfromObject($obj){
			if (is_numeric($obj)) return $obj;
			else return $obj->ID;
		}
		
		protected function getQueryIdent($pars=array()){
			$q='WHERE '.$this->parent_key.'='.$this->getParentID();
			foreach ($pars as $key => $value) $q.= ' AND '.$key.'='."'$value'";
			return $q;
		}
		
		function __construct($object,$model){
			$this->parent_object = $object;
			$this->parent_model = $model;
			$this->parent_key=static::$_DBObjects[$model];
			foreach (static::$_DBObjects as $key => $value)
				if ($key!=$model) {
					$this->other_key=$value;
					$this->other_model=$key;
				}
		}
		
		public function createReference($obj,$pars=array()){
			$id=$this->getIDfromObject($obj);
			$cols='('.$this->parent_key.', '.$this->other_key;
			$vals='('.$this->getParentID().', '.$id;
			foreach ($pars as $key => $value) {
				$cols .= ", ".$key;
				$vals .= ", "."'".$value."'";
			}
			$cols .=')';
			$vals .=')';
			DB::query("INSERT INTO ".static::$_tablename." $cols VALUES $vals;");
		}
	
		
		public function deleteReference($obj){
			$id=getIDfromObject($obj);
			DB::query("DELETE FROM ".static::$_tablename." ".$this->getQueryIdent()." AND ".$this->other_key."='$id';");
		}
		
		public function deleteAllReferences($pars=array()){
			DB::query("DELETE FROM ".static::$_tablename." ".$this->getQueryIdent($pars).";");
		}
		public function getReferences($pars=array()){
			$other=$this->other_key;
			$m=$this->other_model;
			$rez=DB::query("SELECT $other FROM ".static::$_tablename." ".$this->getQueryIdent($pars).";");
			$arr=array();
			while ($row = mysql_fetch_object($rez))
				$arr[]=$m::load($row->$other);
				
			return $arr;
		}
		
		public function getArrayForRequest(){
			$other=$this->other_key;
			$m=$this->parent_model;
			$rez=DB::query("SELECT * FROM ".static::$_tablename." ".$this->getQueryIdent().";");
			$rezarr=array();
			while ($row = mysql_fetch_object($rez)){
				$k=$this->other_key;
				$rezarr[$row->$k]=1;
			}
			return $rezarr;
		}
		
		
	}

?>