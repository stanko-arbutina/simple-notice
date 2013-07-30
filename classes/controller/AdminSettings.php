<?php
	class AdminSettings extends BaseController {
	
		public static function Edit(){
			$f=new FormRenderer('Settings','SubmitEdit');
			$f->OpenSet('Promjena lozinke');
				$f->add('password','Nova lozinka:');
				$f->add('password_repeat','Ponovljena nova lozinka:');
			$f->CloseSet();
			$out=$f->render();
			return $out;
		}
		
		public static function SubmitEdit(){
			$p=U::varFromRequest('password');
			$user=SessionManager::getCurrentUser();
			$user->hashed_password=MD5($p);
			$user->save();
			Dispatcher::addMessage("Lozinka je uspješno promijenjena.");
			return Dispatcher::showSimplePage('AdminHome','Index');
		}
		
	}
?>