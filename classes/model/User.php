<?php
	class User extends DBObject{
		public static $_tablename = NULL; 
		public static $_columns=array();
		public static $_pkeys=array();
		public static $_references=array();
	
		public static function newFromRequest(){ //moramo zakodirati password u MD5 hash
			$o = new self();
			foreach (self::$_columns as $key) $o->$key=U::varFromRequest($key);
			$password=U::varFromRequest('password');
			if ($password!='') $o->hashed_password=MD5($password);
			return $o;
		}
		
		public function getNoticesForStudent($type=''){ //obavijesti za studenta
			if ($type=='Sve obavijesti') $type='';
			if ($type!='') $type=" AND vrsta_obavijesti='$type'";
			$q='SELECT ID FROM obavijesti WHERE ID IN (SELECT obavijest_id FROM ref_obavijesti_kolegiji WHERE kolegij_id IN (SELECT kolegij_id FROM ref_studenti_kolegiji WHERE student_id='.$this->ID.'))'; 			$q.="$type ORDER BY Datum DESC;";
			$rez=DB::query($q);
			$rezarr=array();
			while ($row = mysql_fetch_object($rez)) $rezarr[]=Obavijest::load($row->ID);
			return $rezarr;
		}
		
		public function save(){ //problem je, ako editiramo korisnika i ne diramo mu šifru, ne želimo je isprazniti, ali ne želimo
			//da se može vidjeti hashed_password
			if ($this->hashed_password=='') {
				$tu=self::load($this->ID);
				$this->hashed_password=$tu->hashed_password;
			}
			parent::save();
		}
		
		public static function authenticate($username,$password){
			//provjerava postoji li user s $username i $password u bazi, ako da, vraća id, inače vraća 1
			$candidates = self::select("Username = '$username'");
			if (count($candidates)){
				$user=$candidates[0];
				if ($user->hashed_password == MD5($password))
					return $user->ID;
			}
			return -1;
		}
	} User::_init('users');
User::references('Kolegij','REFStudentiKolegiji');
?>