<?php
	//baratanje s prikazom listi (pomoću html tablice)
	class TableRenderer{
		public $components;
		public $named_components;
		
		public $addController;
		public $addText;
	
		public function add($header,$name,$type='text',$params=array()){
			$tc=new TableComponent();
			$tc->header=$header;
			$tc->name=$name;
			$tc->type=$type;
			$tc->params=$params;
			$this->components[]=$tc;
			if ($name!='') $this->named_components[$name]=$tc;
		}
		
		public static function createSimple($params,$addController,$addText,$editController,$deleteController){
			$o = new self();
			$o->addController=$addController;
			$o->addText=$addText;
			foreach ($params as $key => $value) {
				$o->add($value,$key);
			}
			$o->add('Akcije','standard_actions','standard_actions');
			$o->named_components['standard_actions']->params=array();
			if ($editController!='') $o->named_components['standard_actions']->params['editController']=$editController;
			if ($deleteController!='') $o->named_components['standard_actions']->params['deleteController']=$deleteController;
			return $o;
		}
		
		public function render($object_array){
			$out='';
			if ($this->addText!=''){
				$out.=BV::a($this->addController,$this->addText,'ui_button add_link');
			}
			$out.="<table class='listTable'>";
			$out.="<tr>\n";
			for ($i=0; $i<count($this->components);$i++)
				$out.=$this->components[$i]->renderTh();
			$out.="</tr>\n";
			for ($j=0;$j<count($object_array);$j++){
				$o=$object_array[$j];
				$out.="<tr>\n";
				for ($i=0; $i<count($this->components);$i++)
					$out.=$this->components[$i]->renderTd($o);
				$out.="</tr>\n";
			}
			$out.="</table>\n";
			return $out;			
		}
		
		
	}
?>