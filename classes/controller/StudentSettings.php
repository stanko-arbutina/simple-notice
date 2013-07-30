<?php
	class StudentSettings extends BaseController {
	
		public static function Edit(){
			$f=new FormRenderer('Settings','SubmitEdit');
			$f->OpenSet('Promjena lozinke');
				$f->add('password','Nova lozinka:');
				$f->add('password_repeat','Ponovljena nova lozinka:');
			$f->CloseSet();
			$f->openSet('Želim primati obavijesti o slijedećim kolegijima');
			$f->add('kolegij_id','','','studij_checkbox_list');
			
			$lst=array();
			$studiji=Studij::select();
			for($i=0;$i<count($studiji);$i++){
				$kolegiji=$studiji[$i]->Kolegij()->getReferencesForUserSettings();
				if (count($kolegiji)!=0) $lst[$studiji[$i]->ime_studija]=$kolegiji;
			}
			
			$f->named_components['kolegij_id']->params['list']=$lst;
			$f->named_components['kolegij_id']->params['name']='naziv_kolegija';
			$data=U::varFromRequest('kolegij_id');
			if ($data==NULL){
				$user=SessionManager::getCurrentUser();
				$data=$user->kolegij_id;
			}
			$f->named_components['kolegij_id']->value=$data;
			$f->closeSet();
			$out=$f->render();
			return $out;
		}
		
		public static function SubmitEdit(){
			$p=U::varFromRequest('password');
			$user=SessionManager::getCurrentUser();
			if ($p!='') $user->hashed_password=MD5($p);
			$user->kolegij_id=U::varFromRequest('kolegij_id');
			$user->save();
			Dispatcher::addMessage("Podaci su uspješno promijenjeni.");
			return Dispatcher::showSimplePage('StudentHome','Index');
		}
		
	}
?>