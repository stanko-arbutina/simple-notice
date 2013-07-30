<?php
//jako jednostavni ORM
	class DBTable {
		public static $_tablename = NULL;
		public static $_columns=array();
		public static $_pkeys=array();
		
		function __construct(){
			foreach (static::$_columns as $key) $this->$key='';
		}
		
		public static function _init($tablename,$primary_keys=array('ID')){
			static::$_tablename=$tablename;
			static::$_pkeys=$primary_keys;
			static::$_columns=array_keys(static::getFields());
		}
		
		protected function _getIdentity(){
			$ident='(';
			for ($i=0;$i<count(static::$_pkeys);$i++) {
				$k=static::$_pkeys[$i];
				$ident.=$k.'='.$this->$k;
				if ($i<(count(static::$_pkeys)-1)) $ident.=' AND ';
			}
			$ident.=')';
			return $ident;
		}
		
		public function _create($excludes=array()){
			$obj=new static();
			$cols='(';
			$vals='(';
			foreach ($this as $key => $value) 
				if (($key!='_refs')&&(!in_array($key,$excludes))){
					$cols .= $key .", ";
					$vals .= "'".$value."'" .", ";
					$obj->$key=$value;
			}
			$cols = substr($cols,0,-2).') ';
			$vals = substr($vals,0,-2).') ';
			DB::query("INSERT INTO ".static::$_tablename." $cols VALUES $vals;");
		}
		
		public static function newFromRequest(){
			$o = new static();
			foreach (static::$_columns as $key) $o->$key=U::varFromRequest($key);
			return $o;
		}
		
		public static function getFields(){
			$params=array();
			$rez=DB::query("DESCRIBE ".static::$_tablename.";");
			while ($row=mysql_fetch_object($rez)) 
				$params[$row->Field]=$row->Type;
			return $params;
		}
		
		public static function select($wh="") {
			if ($wh=="") $rez = DB::query("SELECT * FROM ".static::$_tablename.";");
			else $rez = DB::query("SELECT * FROM ".static::$_tablename." WHERE $wh;");
			$arr=array();
			while ($row = mysql_fetch_object($rez)) {
				$o = new static();
				foreach ($row as $key => $value) $o->$key = $value;
				$arr[]=$o;
			}
			return $arr;
		}	
		
		public function delete(){ //brišemo objekt po objekt jer će trebati callbackovi za reference
			$ident=$this->_getIdentity();
			DB::query("DELETE FROM ".static::$_tablename." WHERE $ident;");
		}	
		
		public static function load($params){
			$ident='(';
			foreach ($params as $key => $value) $ident.=$key.'='.$value.' AND ';
			$ident=substr($ident,0,-5).')';
			$rez = DB::query("SELECT * FROM ".static::$_tablename." WHERE $ident;");
			if ($row=mysql_fetch_object($rez)){
			$o = new static();
			foreach ($row as $key => $value) $o->$key = $value;
			return $o;
			} else return NULL;
		}
		
		public function save($excludes=array()){
			$create=false;
			for ($i=0;$i<count(static::$_pkeys);$i++) {
				$k=static::$_pkeys[$i];
				if ($this->$k=='') $create=true;
			}
			if ($create) $this->_create($excludes);
			else {
				$tstr="";
				foreach ($this as $key => $value) if ($key!='_refs') 
					if ((!in_array($key,static::$_pkeys))&&(!in_array($key,$excludes))) $tstr.=$key." = '".$value."', ";
				$tstr=substr($tstr,0,-2);
				$ident=$this->_getIdentity();
				DB::query("UPDATE ".static::$_tablename." SET $tstr WHERE $ident;");
			}
		}
		
	}
?>