<?php
	//Administracija studija
	class AdminStudiji extends BaseController {
		public static function Index(){
			$t=TableRenderer::createSimple(array('ime_studija'=>'Ime studija','broj_godina'=>'Broj godina'),
																			Dispatcher::href_to("Studiji","Add"),'Novi studij',
																			Dispatcher::href_to("Studiji","Edit"),
																			Dispatcher::href_to("Studiji","Delete"));
			$sts=Studij::select();
			return $t->render($sts);
		}
		
		protected static function form($submit_where){
			$f=FormRenderer::standardForm('Studij','Studiji',$submit_where,'Podaci o studiju');
			//samo je jedan admin!	
			$c=$f->named_components['Type'];
			U::removeElFromArray($c->params,'Admin');
			return $f;
		}
		
		protected static function before_submit_form(){	
			$name=U::varFromRequest('ime_studija');
			if ($name=='') Dispatcher::addError('Ime studija ne smije biti prazno');
				else {
					$sql_check="ime_studija='$name'";
					$id=U::varFromRequest('ID');
					if ($id!='') $sql_check.=" AND NOT ID=$id";
					$sts=Studij::select($sql_check);
					if (count($sts)>0) Dispatcher::addError('Već postoji studij tog imena.');
			}
		}
		
		
		public static function Delete(){
			Dispatcher::addMessage("Studij je uspješno izbrisan.");
			return parent::Delete();
		}
		public static function SubmitAdd(){
			return self::submit_changes('Studij je uspješno dodan.');
		}
		
		public static function SubmitEdit(){
			return self::submit_changes('Podaci o studiju su uspješno izmijenjeni.');
		}
		
	}
?>