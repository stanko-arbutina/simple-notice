<?php
//FormElements - za generiranje html tagova za forme
	class FE{
	
		public static function input_tag($type='text',$name='',$value='',$class='',$id=''){
			$el="<input type='$type'";
			if ($name!='') $el.=" name='$name'";
			if ($value!='') $el.=" value='$value'";
			$el.=BV::ic($class,$id);
			$el.='/>';
			return $el;
		}
		
		public static function simpleSelect($name,$ar,$value='',$class='',$id=''){
			if ($value=='') $value=$ar[0];
			$out="<select name='$name'".BV::ic($id,$class).">\n";
			for ($i=0;$i<count($ar);$i++) {
				$out.="<option value='".$ar[$i]."'";
				if ($ar[$i]==$value) $out.=' SELECTED';
				$out.='>'.$ar[$i]."</option>\n";
			}
			$out.="</select>\n";
			return $out;
		}
	
		public static function rowWrapper($label,$element){
			$out='<tr><td>';
			if ($label!='') $out.=BV::span($label,'inputlabel');
			$out.="</td><td>".$element."</td></tr>\n";
			return $out;
		}
		
		public static function SearchBarForUserHome(){
			$f=new FormRenderer('Home','Index');
			$f->OpenSet('Pretraga obavijesti');
				$f->add('vrsta_obavijesti','Vrsta obavijesti: ');
				$f->named_components['vrsta_obavijesti']->type='enum';
				$ar=Obavijest::getFields();
				$s=stristr($ar['vrsta_obavijesti'],"(");
				$s=trim($s,'()');
				$pars=explode(',',$s);
				for ($i=0;$i<count($pars);$i++) $pars[$i]=trim($pars[$i],"'");
				array_unshift($pars,'Sve obavijesti');
				$f->named_components['vrsta_obavijesti']->params=$pars;
				$f->cancel='';
			$f->CloseSet();
			$f->getParsFromRequest();
			$out=$f->render();
			return $out;
		}
		
	}
?>