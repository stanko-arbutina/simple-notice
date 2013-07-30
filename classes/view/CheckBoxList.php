<?php

 //baratanje s komponentama forme (uglavnom input tagovi)
	class CheckBoxList extends FormComponent{
		public $type='text';
		public $name='';
		public $value='';
		public $class_attr='';
		public $label='';
		public $id='';
		public $params;
		
		
		public function render(){
			$out="<ul class='checkboxlist'>\n";
			switch ($this->type){
				case 'studij_list':
					$studiji=$this->params;
					
					for ($i=0;$i<count($studiji);$i++){
						$out.="<li><ul class='checkboxlist'/>".$studiji[$i]->ime_studija."\n";
						for ($j=1;$j<=$studiji[$i]->broj_godina;$j++){
							$out.="<li><input type='checkbox' name='".$this->name."[".$studiji[$i]->ID."][".$j."]' value='1'";
							if (isset($this->value[$studiji[$i]->ID]))
								if (isset($this->value[$studiji[$i]->ID][$j]))
									$out.=' checked ';
							$out.="/>";
							$out.=BV::span($j.' godina');
							$out.="</li>\n";					
							}
							$out.="</ul></li>\n";
					}
					break;
				case 'checkbox_list':
					$elementi=$this->params['list'];
					$display_name=$this->params['name'];
					for ($i=0;$i<count($elementi);$i++){						
						$out.="<li><input type='checkbox' name='".$this->name."[".$elementi[$i]->ID."]' value='1'";
						if (isset($this->value[$elementi[$i]->ID]))
								$out.=' checked ';
						$out.="/>";
						$out.=BV::span($elementi[$i]->$display_name);
						$out.="</li>\n";					
						}
					break;
					case 'studij_checkbox_list':
						$elementi=$this->params['list'];
						$display_name=$this->params['name'];
						
						foreach ($elementi as $key => $value){
								$out.="<li><ul class='checkboxlist'/>".$key."\n";
								if ($value) for ($i=0;$i<count($value);$i++){						
									$out.="<li><input type='checkbox' name='".$this->name."[".$value[$i]->ID."]' value='1'";
									if (isset($this->value[$value[$i]->ID]))
										$out.=' checked ';
									$out.="/>";
									$out.=BV::span($value[$i]->$display_name);
									$out.="</li>\n";					
								}
								$out.="</ul></li>\n";
						}
					break;
			}
			$out.="</ul>\n";
			
			return $out;
		}
		
	}	
?>