<?php
 //baratanje s komponentama forme (uglavnom input tagovi)
	class FormComponent{
		public $type='text';
		public $name='';
		public $value='';
		public $class_attr='';
		public $label='';
		public $id='';
		public $params;
		
		
		public function render(){
			switch ($this->type) {
				case 'OpenSet':
					$out="<fieldset>\n";
					if ($this->label!='') $out.="<legend>".$this->label."</legend>\n";
					$out.="<table class='form_organizer'>\n";
					return $out;
				case 'CloseSet':
					return "</table></fieldset>\n";
				case 'hidden': //vjerojatno će trebati neko bolje rješenje za ovo
					$out='</table>';
					$out.= FE::rowWrapper($this->label,FE::input_tag($this->type,$this->name,$this->value,$this->class_attr,$this->id));
					$out.="<table class='form_organizer'>\n";
					return $out;
				case 'bool':
					$out="<input type='checkbox' name='".$this->name."' value='1'";
					if ($this->value==1) $out.=' checked';
					$out.="/>\n";
					return FE::rowWrapper($this->label,$out);
				case 'textarea':
					$out="<textarea name='".$this->name."' cols='60' rows='10'>\n";
					$out.=$this->value;
					$out.="</textarea>\n";
					return FE::rowWrapper($this->label,$out);
				case 'enum':
					return FE::rowWrapper($this->label,FE::simpleSelect($this->name,$this->params,$this->value,$this->class_attr,$this->id));
				case 'studij_list':
					return FE::studijiList($this->params['boxes_list']);
				default:
					return FE::rowWrapper($this->label,FE::input_tag($this->type,$this->name,$this->value,$this->class_attr,$this->id));
			}
		}
		
	}	
?>