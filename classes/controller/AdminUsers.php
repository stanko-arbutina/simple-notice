<?php
	//Administracija korisnika
	class AdminUsers extends BaseController {
		public static function Index(){
			$t=TableRenderer::createSimple(array('Username'=>'Korisničko ime','Type'=>'Tip korisnika','Email'=>'E-mail'),
																			Dispatcher::href_to("Users","Add"),'Dodaj korisnika',
																			Dispatcher::href_to("Users","Edit"),
																			Dispatcher::href_to("Users","Delete"));
			$users=User::select("NOT Username='Administrator' ORDER BY Username;");
			return $t->render($users);
		}
		
		protected static function form($submit_where){
			$f=FormRenderer::standardForm('User','Users',$submit_where,'Podaci o korisniku',array('Type'=>'Vrsta korisnika:'));
			//samo je jedan admin!	
			$c=$f->named_components['Type'];
			U::removeElFromArray($c->params,'Admin');
			return $f;
		}
		
		protected static function before_submit_form(){	
			$username=U::varFromRequest('Username');
			if ($username=='') Dispatcher::addError('Korisničko ime ne smije biti prazno');
				else {
					$sql_check="username='$username'";
					$id=U::varFromRequest('ID');
					if ($id!='') $sql_check.=" AND NOT ID=$id";
					$users=User::select($sql_check);
					if (count($users)>0) Dispatcher::addError('Korisničko ime je već zauzeto.');
			}
		}
		
		public static function BeforeSubmitEdit(){ //ako nije mijenjao šifru, nećemo je prazniti
			$p=U::varFromRequest('password');
			$rp=U::varFromRequest('password_repeat');
			if (($p=='')&&($rp=='')){
				U::varFromRequest('password','',true);
				U::varFromRequest('password_repeat','',true);
			}
			$cont=self::$current_controller;
			$cont::before_submit_form();
		}
		
		public static function Delete(){
			Dispatcher::addMessage("Korisnik je uspješno izbrisan.");
			return parent::Delete();
		}
		public static function SubmitAdd(){
			return self::submit_changes('Korisnik je uspješno dodan.');
		}
		
		public static function SubmitEdit(){
			return self::submit_changes('Podaci o korisniku su uspješno izmijenjeni.');
		}
		
	}
?>