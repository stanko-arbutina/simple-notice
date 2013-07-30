<?php
	//u osnovi, mapira HTTP requestove na prave kontrolere
	//i još par sitnica
	class Dispatcher{
	
		public static $errors=array();
		public static $warnings=array();
		public static $can_continue=true;
		public static $message;
		private static $_menu_drawn=false;
		private static function cmp($a,$b){
				return $a->order_num>$b->order_num;
		}
	
		public static function getMenu(){
			
			if ((SessionManager::isLoggedIn())&&(!self::$_menu_drawn)) { 
				self::$_menu_drawn=true;
				$out= "<ul class='menu'>";
				$items=MenuItem::select("type='All' OR type='".SessionManager::getCurrentUserType()."'");
				usort($items,'self::cmp');
				for ($i=0;$i<count($items);$i++)
					$out.= "<li>".BV::a(self::href_to($items[$i]->page,$items[$i]->action),$items[$i]->text)."</li>\n";
				$out.= "</ul>\n";
				return $out;
			} else return '';
		}
	
		public static function href_to($page="Home",$action="Index",$params=array()) {
			$url="index.php?page=$page&action=$action";
			foreach ($params as $k => $v) $url.="&$k=$v";
			return $url;
		}
		
		public static function parseRequest(){
			//iz $_REQUESTA izdvaja page i action
			$page=U::varFromRequest('page','Home',true);
			$action=U::varFromRequest('action','Index',true);
			if (!SessionManager::isLoggedIn()) 
				if (($action!='SubmitLogin')||($page!='Home')){
				$page='Home';
				$action='Login';
			}
			return array($page,$action);
		}
		
		private static function check_errors(){
			$out='';
			if (count(self::$errors)) $out .=BV::DSerrorList();
			if (count(self::$warnings)) $out .=BV::DSwarningList();
			return $out;
		}
		
		private static function getControllerName($page,$action=''){
			if ((!SessionManager::isLoggedIn())) return 'BaseController';
			$user_type=SessionManager::getCurrentUserType();
			return $user_type.$page;//trebat će promijeniti u ovisnosti koji user je tu
		}
		
		public static function showSimplePage($controller,$action){
			//kada nam ne treba sve kao u getPageu,samo najjednostavnija varijanta (npr. index nakon dodavanja objekta u bazu)
				$out='';
				if (self::$message!='') $out.=BV::DSmessage();
				eval("\$out.=$controller::$action();");																				//u PHPu prije 5.3 ovo ne radi $out.=$controller::$action();
				return $out;
		}
		
		public static function getPage($page,$action){
			$controller = self::getControllerName($page,$action);
			if (file_exists('classes/controller/'.$controller.'.php')) {
				BaseController::$current_controller=$controller;
				$before_action='Before'.$action;
				if (method_exists($controller,$before_action)) eval("$controller::$before_action();");
				BaseController::CommonBeforeFilters();
				$out = self::check_errors();
				if (!self::$can_continue) self::getLastActions($page,$action);
				$controller = self::getControllerName($page,$action);
				eval("\$out.=$controller::$action();");
				self::setLastActions($page,$action);
				return $out;
			}
			//echo $controller." ".$action;
			return BV::DSerror("Stranica ne postoji!"); 				
		}
		
		public static function addError($e='Nepoznata greška'){
			self::$can_continue=false;
			self::$errors[]=$e;
		}

		public static function addWarning($w='Nepoznato upozorenje'){
			self::$warnings[]=$w;
		}
		
		public static function addMessage($m='OK!'){
			self::$message=$m;
		}
		
		private static function setLastActions($page='Home',$action='Login'){
			SessionManager::setVar('lastPage',$page);
			SessionManager::setVar('lastAction',$action);
		}
		
		private static function getLastActions(&$page,&$action){
			if (!($page=SessionManager::getVar('lastPage')))
				$page='Home';
			if (!($action=SessionManager::getVar('lastAction')))
				$action='Login';
		}
			
	}
	

?>