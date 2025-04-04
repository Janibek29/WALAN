<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	abstract class jV {
		var $n;
		var $cn;
		var $cr;
		var $vw;
		var $ip;
		var $oM;
		
		var $oC;
		var $gotoshow;
		var $rurl;
		
		abstract protected function init();
		abstract protected function r();
		
		
		function __construct($oM, $ip=array()) {
			$this->oM = $oM;
			$this->ip = $ip;
			$this->n = 1;
			if(isset($this->ip['n']))
				$this->n = $this->ip['n'];
			$this->gotoshow = false;
			
			$this->ipcr();
			
			$this->setcr();
			$this->init();
			
			$this->rurl = "?".$this->oM->cmd."=";
			$this->rurl .= $this->vw.$this->oM->ssd.$this->oC->fp.$this->oM->ssd.'r';
		}
		
		function ipcr() {
		}
		
		function setcr() {
			$this->cn = get_class($this);
			$this->cn = str_replace('\\', $this->oM->ssc, $this->cn);
			$this->vw = $this->cn.$this->oM->ssd.$this->n;
			$this->cr = str_replace('vs'.$this->oM->ssc.'v', 'cs'.$this->oM->ssc.'c', $this->vw);
			$ar = explode($this->oM->ssd, $this->cr);
			$cn = str_replace($this->oM->ssc, '\\', $ar[0]);
			$this->oC = new $cn($this->oM, $this->ip);			
		}
		
		function show() {
			$r = '';
			$r .= '<div id="'.$this->vw.'">';
			$r .= $this->r();
			$r .= '</div>';
			return $r;
		}
		
		function b($attr, $bc, $cmd, $ac, $tt) {
			$r = "<button $attr";
			$r .= " onclick=\" var c=true; ".$bc;
			$r .= "if(c) {".$this->oM->jo;
			$r .= ".a('?".$this->oC->ccr."$cmd', ";
			$r .= $this->oM->jo.".p); ".$ac."} ";
			$r .= "\">".$tt."</button>";
			return $r;
		}
		
		
	}
?>
