<?php
	//home page za administratora
	class StudentHome extends BaseController {
		public static function Index(){
			$user=SessionManager::getCurrentUser();
			$vrsta=U::varFromRequest('vrsta_obavijesti');
			$obavijesti=$user->getNoticesForStudent($vrsta);
			$out=FE::SearchBarForUserHome();			
			for ($i=0;$i<count($obavijesti);$i++) {
				$kolegiji=$obavijesti[$i]->Kolegij()->getReferences();
				$out.=BV::Obavijest($obavijesti[$i],$kolegiji);
			}
			return $out;
		}
		
	}
?>