<?php

//baratanje s komponentama liste (pomoću html tablice)
	class TableComponent{
		public $header='Naslov';
		public $type='text';
		public $name='';
		public $params;
		
		public function renderTh(){
			return '<th>'.$this->header."</th>\n";
		}
		
		public function renderTd($object){
			$out='<td';
			switch ($this->type){
				case 'standard_actions':
					$out.=" class='action_cell'>";
					if (isset($this->params['editController']))
						$out.=BV::a($this->params['editController'].'&ID='.$object->ID,'Uredi',$class='edit_link ui_button');
					if (isset($this->params['deleteController']))
						$out.=BV::a($this->params['deleteController'].'&ID='.$object->ID,'Briši',$class='delete_link ui_button');
					break;
				case 'bool':
					$out.='>';
					$what=$this->name;
					if ($object->$what=='1') $out.="<img src='resources/icons/designcode/checkmark.png'/>"; 
						else $out.="<img src='resources/icons/designcode/remove.png'/>";
					break;
				default:
					$out.='>';
					$what=$this->name;
					$out.=$object->$what;
			}
			$out.="</td>\n";
			return $out;
			
		}
	}

?>
