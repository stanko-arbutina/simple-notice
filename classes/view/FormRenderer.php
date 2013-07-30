<?php
//baratanje s formama
	class FormRenderer{
	
		public $page;
		public $action;
		public $class_attr='';
		public $form_action='index.php';
		public $method='POST';
		public $submit='U redu';
		public $cancel='Poništi';
		public $before_table='';
		
		public $components=array();
		public $named_components=array();
		
		function __construct($page,$action){
			$this->page=$page;
			$this->action=$action;
		}
		
		private function fieldProcessor($field,$type){
			$fc=$this->add($field);
			$fc->name=$field;
			switch($field){
				case 'Username':
					$fc->label='Korisničko ime:';
					break;
				case 'Email':
					$fc->label='E-mail:';
					break;
				case 'hashed_password':
					$fc->name='password';
					$fc->type='password';
					$fc->label='Lozinka:';
					$fc_r=$this->add('password_repeat','Ponovljena lozinka:');
					$fc_r->type='password';
					$this->named_components['password']=$fc;
					unset($this->named_components['hashed_password']);
					break;
				default:
					if ($fc->type!='hidden'){
						$fc->label=str_replace("_"," ",ucfirst($field)).":";
					}
			}
			$ttype=stristr($type,"(",true);
			if ($ttype){
				switch ($ttype){
					case 'enum':
						$fc->type='enum';
						$s=stristr($type,"(");
						$s=trim($s,'()');
						$fc->params=explode(',',$s);
						for ($i=0;$i<count($fc->params);$i++) $fc->params[$i]=trim($fc->params[$i],"'");
					break;
				}
			}
		}
		
		public static function standardForm($model,$page,$action,$title='Promjena podataka',$names_list=array()){
			$f=new self($page,$action);
			$f->openSet($title);
			eval("\$pars=$model::getFields();");
			foreach ($pars as $key => $value) $f->fieldProcessor($key,$value);
			$f->CloseSet();
			foreach ($names_list as $key => $value) $f->named_components[$key]->label=$value;
			return $f;
		}
		
		public function add($name='',$label='',$value='',$type=''){
				switch ($type){
					case 'studij_checkbox_list':
					case 'checkbox_list':
					case 'studij_list':
						$fc=new CheckBoxList();
						$fc->type=$type;
						break;
					default:
						$fc=new FormComponent();
				}
				$fc->name=$name;
				$fc->value='';
				//"pametne" pretpostavke s obzirom na name elementa
					if ($name=='ID') $fc->type='hidden';
					if (stristr($name,'password')) $fc->type='password';
				//
				$fc->label=$label;
				$this->components[]=$fc;
				if ($name!='') $this->named_components[$name]=$fc;
			return $fc;
		}
		
		public function remove($name){	
			$c=$this->named_components[$name];
			U::removeElFromArray($this->components,$c);
			unset($this->named_components[$name]);
		}
		public function OpenSet($name=''){
			$fc=new FormComponent();
			$fc->type='OpenSet';
			$fc->label=$name;
			$this->components[]=$fc;
		}
		public function CloseSet(){
			$fc=new FormComponent();
			$fc->type='CloseSet';
			$this->components[]=$fc;
		}
		
		public function getIdFromRequest(){
			if (isset($this->named_components['ID']))
				$this->named_components['ID']->value=U::varFromRequest('ID');
		}
		
		public function getParsFromRequest(){
			foreach ($this->named_components as $key => $component) 
				if ($component->value=='')
					if (!stristr($key,'password')) $component->value=U::varFromRequest($key);
		}
		
		public function getParsFromObject($o){
			foreach ($this->named_components as $key => $component) 
					if (!stristr($key,'password')) $component->value=$o->$key;
		}
		
		public function render(){
			$out="<form action='".$this->form_action."' method='".$this->method."'";
			if ($this->class_attr!='') $out.=" class='".$this->class_attr."'";
			$out.=">\n";
			$out.="<input type='hidden' name='page' value='".$this->page."'/>\n";
			$out.="<input type='hidden' name='action' value='".$this->action."'/>\n";
			for ($i=0;$i<count($this->components);$i++) $out.=$this->components[$i]->render();
			if ($this->submit!='') $out.="<input type='submit' class='ui_button submit_button' value='".$this->submit."'/>";
			if ($this->cancel!='') $out.="<input type='reset' class='ui_button reset_button' value='".$this->cancel."'/>";
			$out.='</form>';
			return $out;
		}
		//standard_form_for
	}

?>