<?php
//BasicView - skraćeno jer će se često pozivati
//za generiranje html elemenata

	class BV {
		public static function DBerror($e){
			$out="<div class='dberror user_info'>$e<hr/>".mysql_error()."</div>";
			return $out;
		}
		public static function DSerror($e){
			$out="<div class='dserror user_info'>$e<hr/></div>";
			return $out;
		}
		
		public static function DSerrorList(){
			$out="<div class='dserrorlist user_info'>Nemoguće je nastaviti zbog slijedećih grešaka: <ul>\n";
			for ($i=0;$i<count(Dispatcher::$errors);$i++) $out.='<li>'.Dispatcher::$errors[$i]."</li>\n";
			$out.="</ul></div>\n";
			return $out;
		}
		public static function Obavijest($o,$k){
			$kl='';
			for ($i=0;$i<count($k);$i++){
				$kl.=$k[$i]->naziv_kolegija;
				if ($i<(count($k)-1)) $kl.=', ';
			}
			$out="<div class='obavijest'>\n";
			$out.="<div class='obavijest_kolegiji'>$kl</div>\n";
			$out.="<div class='obavijest_naslov'>".$o->Naslov."</div>\n";
			$out.="<div class='obavijest_datum'>".$o->Datum."</div>\n";
			$out.="<div class='obavijest_vrsta'>".$o->vrsta_obavijesti."</div>\n";
			$out.="<div class='obavijest_tekst'>".$o->tekst_obavijesti."</div>\n";
			$out.="</div>\n";
			return $out;
		}
		public static function DSwarningList(){
			$out="<div class='dswarninglist user_info'>Upozorenje: <ul>\n";
			for ($i=0;$i<count(Dispatcher::$warnings);$i++) $out.='<li>'.Dispatcher::$warnings[$i]."</li>\n";
			$out.="</ul></div>\n";
			return $out;
		}
		
		public static function DSmessage(){
			$out="<div class='dsmessage user_info'>".Dispatcher::$message."</div>\n";
			return $out;
		}

		
		public static function a($where,$innerHTML="",$class="",$id=""){
			$out="<a href='$where'".self::ic($id,$class);
			$out.=">$innerHTML</a>";			
			return $out;
		}
	
	
		public static function span($innerHTML,$class='',$id=''){
			$out="<span".self::ic($id,$class);
			$out.=">$innerHTML</span>";			
			return $out;
		}
		
		public static function ic($id='',$class=''){	//id_class
			$out="";
			if ($class!='') $out.=" class='$class'";
			if ($id!='') $out.=" id='$id'";
			return $out;
		}
	
	}
?>