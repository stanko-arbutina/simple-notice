<?php
//objekti koji predstavljaju entitete, imaju autinkrement ID kao primary key
	class DBObject extends DBTable {
		public static $_tablename = NULL; //nekakvi meni neshvatljivi problemi su s nasljeđivanjem static class variabli
		public static $_columns=array(); //redeklaracija pomaže
		public static $_pkeys=array();
		public static $_references=array();
		
		public $_refs=array();
		public function _create($excludes=array()){
			parent::_create($excludes);
			$r=DB::query("SELECT MAX(ID) FROM ".static::$_tablename.";");
			$row=mysql_fetch_array($r);
			$this->ID = (int) $row["MAX(ID)"];
		}
		public function __construct(){
			parent::__construct();
			foreach (static::$_references as $key => $value)
				$this->_refs[$key] = new $value($this,get_class($this));			
		}
		public static function references($model,$through){
			static::$_references[$model]=$through;
		}
		
		public static function newFromRequest(){
			$o = new static();
			foreach (static::$_columns as $key) $o->$key=U::varFromRequest($key);
			foreach ($o->_refs as $key=>$value){
				$v = $value->other_key;
				$o->$v=U::varFromRequest($v);
			}
			return $o;
		}
		public function delete(){
			foreach ($this->_refs as $key=>$value) $this->$key()->deleteAllReferences();
			parent::delete();
		}
		
		public function save(){
			$excludes=array();
			foreach ($this->_refs as $key=>$value)$excludes[]= $value->other_key;
			
			parent::save($excludes);
			$this->saveReferences();
		}
		
		public function saveReferences(){
			foreach ($this->_refs as $key=>$value) {
				$this->$key()->deleteAllReferences();
				$k=$value->other_key;
				$lst=$this->$k;
				if ($lst) foreach ($lst as $k => $v)
					$this->$key()->createReference($k);
			}
		}
		
		public static function load($id){
			$o=parent::load(array('ID'=>$id));
			if ($o) 
				foreach ($o->_refs as $key=>$value){
					$k=$value->other_key;
					$o->$k=$o->$key()->getArrayForRequest();
				}
			return $o;
		}
		
		public function __call($method, $args){ //da lako možemo dolaziti do referenci.
        if(method_exists($this, $method)) 
          return call_user_func_array(array($this, $method), $args);
        else if (array_key_exists($method,$this->_refs)) 
					return $this->_refs[$method];
				else
          echo "Greška u DBObject call!,$method : $args";
        
    } 
		
	}
?>