<?php
	//ovu klasu će svi kontroleri naslijeđivati, ako ništa, zbog dummy funkcija
	class BaseController{
		public static $current_controller;
	
		private static function guessModel(){
			$cont=self::$current_controller;
			$models=scandir('classes/model/');
			for ($i=0;$i<count($models);$i++) {
				$m=ucfirst(stristr($models[$i],".",true));
				if ($m!='') if (stristr($cont,$m)) return $m;
				}
				
		}
	
		protected static function submit_changes($message='Promjene su spremljene.'){
			$model=self::guessModel();
			eval("\$u=$model::newFromRequest();");
			$u->save();
			Dispatcher::addMessage($message);
			return Dispatcher::showSimplePage(self::$current_controller,'Index');
		}
	
	
	
		public static function Add(){
			$cont=self::$current_controller;
			eval("\$f=$cont::form('SubmitAdd');");
			if (!Dispatcher::$can_continue)$f->getParsFromRequest();
			return $f->render();
		}
		
		public static function Edit(){
			$cont=self::$current_controller;
			eval("\$f=$cont::form('SubmitEdit');");
			$f->getIdFromRequest();
			if (!Dispatcher::$can_continue)$f->getParsFromRequest();
			else {
				$model=self::guessModel();
				$id=U::varFromRequest('ID');
				eval("\$o=$model::load($id);");
				$f->getParsFromObject($o);
			}
			return $f->render();
		}
		
		public static function Delete(){
			$model=self::guessModel();
			$id=U::varFromRequest('ID');
			eval("\$o=$model::load($id);");
			$o->delete();
			return Dispatcher::showSimplePage(self::$current_controller,'Index');
		}
				
		
		
		public static function BeforeSubmitAdd(){
			$cont=self::$current_controller;
			if (method_exists($cont,'before_submit_form'))
				eval("$cont::before_submit_form();");
		}
		
		public static function BeforeSubmitEdit(){
			$cont=self::$current_controller;
			if (method_exists($cont,'before_submit_form'))
				eval("$cont::before_submit_form();");
		}
		
		public static function CommonBeforeFilters(){
			if ((U::is_set('password'))&&(U::is_set('password_repeat'))) {
				$p=U::varFromRequest('password');
				$rp=U::varFromRequest('password_repeat');
				if ($rp!=$p) Dispatcher::addError('Lozinka i ponovljena lozinka se razlikuju.');
			}
		}
		
		final public static function Login(){
			$f=new FormRenderer('Home','SubmitLogin');
			$f->OpenSet('Prijava korisnika');
				$f->add('username','Korisničko ime:');
				$f->add('password','Lozinka:');
			$f->CloseSet();
			$f->getParsFromRequest();
			$f->class_attr='login_form';
			$out=$f->render();
			return $out;
		}
		
		
		final public static function BeforeSubmitLogin(){
			$username=U::varFromRequest('username');
			$password=U::varFromRequest('password');
			if ($username=='') Dispatcher::addWarning('Korisničko ime ne može biti prazno.');
			$id = User::authenticate($username,$password);
			if ($id==-1) Dispatcher::addError('Pogrešno korisničko ime ili lozinka.');
				else SessionManager::doLogin($id);
		}
		
		final public static function SubmitLogin(){
			return Dispatcher::getPage('Home','Index');
		}
		
		final public static function Logout(){
			SessionManager::doLogout();
			return Dispatcher::getPage('Home','Login');
		}
		
	}
?>