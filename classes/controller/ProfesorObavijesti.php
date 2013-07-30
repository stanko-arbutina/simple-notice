<?php
	//Administracija obavijesti
	class ProfesorObavijesti extends BaseController {
		public static function Index(){
			$t=TableRenderer::createSimple(array('Naslov'=>'Naslov obavijesti','vrsta_obavijesti'=>'Vrsta obavijesti',"istek"=>"Rok obavijesti","Datum" => "Datum i vrijeme"),
																			Dispatcher::href_to("Obavijesti","Add"),'Nova obavijest',
																			"",
																			Dispatcher::href_to("Obavijesti","Delete"));
			$t->named_components['istek']->type='bool';
			$obs=Obavijest::select("owner_id='".SessionManager::getCurrentUserId()."' ORDER BY DATUM DESC;");
			return $t->render($obs);
		}
		
		protected static function form($submit_where){
			$f=FormRenderer::standardForm('Obavijest','Obavijesti',$submit_where,'Detalji');			
			$f->named_components["Datum"]->type='hidden';
			$f->named_components["Datum"]->label='';
			$f->named_components["Datum"]->value=date('Y-m-d H:i:s');
			$f->named_components["istek"]->label='Obavijest ima rok (automatski se briše nakon 6 mjeseci od postavljanja):';
			$f->named_components["istek"]->type='bool';
			$f->named_components["tekst_obavijesti"]->type='textarea';
			$f->openSet('Obavijest se tiče slijedećih kolegija');
			$f->add('kolegij_id','','','checkbox_list');
			$f->named_components['kolegij_id']->params['list']=Kolegij::select('owner_id='.SessionManager::getCurrentUserId());
			$f->named_components['kolegij_id']->params['name']='naziv_kolegija';
			$f->remove('owner_id');
			$f->closeSet();
			return $f;
		}
		
		public static function Delete(){
			Dispatcher::addMessage("Obavijest je uspješno izbrisana.");
			return parent::Delete();
		}
		public static function SubmitAdd(){
			return self::submit_changes('Obavijest je uspješno dodana.');
		}
		
	
		
	}
?>