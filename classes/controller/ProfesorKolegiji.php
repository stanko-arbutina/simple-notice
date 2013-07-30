<?php
	//Administracija studija
	class ProfesorKolegiji extends BaseController {
		public static function Index(){
			$t=TableRenderer::createSimple(array('naziv_kolegija'=>'Naziv kolegija','web_stranica'=>'Web stranica'),
																			Dispatcher::href_to("Kolegiji","Add"),'Novi kolegij',
																			Dispatcher::href_to("Kolegiji","Edit"),
																			Dispatcher::href_to("Kolegiji","Delete"));
			$kgs=Kolegij::select("owner_id='".SessionManager::getCurrentUserId()."'");
			return $t->render($kgs);
		}
		
		protected static function form($submit_where){
			$f=FormRenderer::standardForm('Kolegij','Kolegiji',$submit_where,'Opći podaci o kolegiju');
			
			//neke stvari su interne
			$f->remove('owner_id');
			$f->remove('last_check');
			$f->remove('check_hash');
			
			$f->openSet('Studiji/godine na kojima se sluša');
			$f->add('studij_id','','','studij_list');
			$f->named_components['studij_id']->params=Studij::select();
			
			$f->closeSet();
			return $f;
		}
		
		protected static function before_submit_form(){	
			$name=U::varFromRequest('naziv_kolegija');
			if ($name=='') Dispatcher::addError('Ime kolegija ne smije biti prazno');
				else {
					$sql_check="naziv_kolegija='$name'";
					$id=U::varFromRequest('ID');
					if ($id!='') $sql_check.=" AND NOT ID=$id";
					$sts=Kolegij::select($sql_check);
					if (count($sts)>0) Dispatcher::addError('Već postoji kolegij tog imena.');
			}
		}
		
		
		public static function Delete(){
			Dispatcher::addMessage("Kolegij je uspješno izbrisan.");
			return parent::Delete();
		}
		public static function SubmitAdd(){
			return self::submit_changes('Kolegij je uspješno dodan.');
		}
		
		public static function SubmitEdit(){
			return self::submit_changes('Podaci o kolegiju su uspješno izmijenjeni.');
		}
		
	}
?>