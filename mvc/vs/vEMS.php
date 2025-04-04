<?php
	/*
	* Copyright (C) 2025 Жанибек Какимбаев
	*
	* Бұл бағдарлама GNU General Public License v3.0 (GPLv3) лицензиясы бойынша таратылады.
	* Лицензия мәтіні: https://www.gnu.org/licenses/gpl-3.0.html
	*/
	namespace mvc\vs;
	
	

	class vEMS extends jV {
		function init() {
			$r = '';
			
			return $r;
		}
		
		function r1() {
			$r = '';
			$r .= 'test';
			
			
			return $r;
		}
		
		function r() {
			$r = '';
			//$r .= $this->oC->fp;
			switch($this->oC->fp) {
				case 1:
					$r .= '';
				break;
				case 2:
					$r .= $this->showMSGLIST();
				break;
				case 3:
					$r .= $this->showMSG();
				break;
				case 4:
					$r .= $this->showSND();
				break;
			}			
			return $r;
		}
		
		function showMSGLIST() {
			$r = '';
			//$r .= print_r($this->oC->fd['its'][$this->oC->trv]['rs'], true);
			$r .= $this->b('', '', 'rrs', "jT.a('".$this->rurl."', '');", 'Жаңарту');
			//$r .= $this->b('', '', 'rrs', "", 'Жаңарту');//window.location.reload();
			$f = '';
			switch($this->oC->f) {
				case 'INBOX':
					$f = 'Кіріс';
				break;
				case 'Sent':
					$f = 'Жіберілген';
				break;
			}
			$r .= '<br/>'.$f;
			$r .= '<table>';
			$r .= '<thead>';
			$r .= '<tr>';
			$r .= '<th>Кім</th>';
			$r .= '<th>Тақырып</th>';
			$r .= '<th>Уақыты</th>';
			$r .= '</tr>';
			$r .= '</thead>';
			foreach($this->oC->fd['its'][$this->oC->trv]['rs'] as $rk=>$rw) {
				if($this->oC->f==$rw['f']) {
					$r .= '<tr>';
					if($rw['f']=='INBOX')
						$who = $rw['d']['SNDR'];
					else
						$who = $rw['d']['RCPT'];
					$r .= '<td>'.htmlspecialchars($who).'</td>';
					$sbj = $rw['d']['SBJ'];
					if($sbj=='')
						$sbj = '*';
					$url = '?pg=ems&'.$this->oC->cr.';1;fp=3&id='.$rw['d']['ID'];
					//$r .= '<td><a href="'.$url.'" >'.'usn='.$rw['d']['USN'].$sbj.$rw['d']['DC'].'</a></td>';
					$r .= '<td><a href="'.$url.'" >'.$sbj.'</a></td>';
					$r .= '<td>'.$rw['d']['DT'].'</td>';
					$r .= '</tr>';
				}
			}
			$r .= '</table>';
			return $r;
		}
		
		function showMSG() {
			$r = '';
			//$r .= 'showMSG';
			if(isset($this->oC->fd['msg']))
				$r .= $this->oC->fd['msg'];
			//$r .= print_r($this->oC->fd['rw'], true);
			return $r;
		}
		
		function showBD422() {
			$r = '';
			ob_start();
			?>
			<div id="editor">
					<h1>Сәлем, Әлем!</h1>
					<p>WALAN</p>
				</div>
			<script src="/m/ckeditor_4.22.1_full/ckeditor/ckeditor.js"></script>
			<script src="/m/ckeditor_4.22.1_full/ckeditor/samples/js/sample.js"></script>
			<script>
				initSample();
				CKEDITOR.config.versionCheck = false;
			</script>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function showBD() {
			$r = '';
			ob_start();
			?>
			<style>
				.main-container {
					width: 795px;
					margin-left: auto;
					margin-right: auto;
					
				}
				
				.ck-toolbar__items {
					zoom: 0.8;
				}
				
				.cke_top
{
    zoom:0.8;
}
.cke_toolbar {
    zoom: 0.8;
}
			</style>
			<div class="main-container">
				<button onclick="alert(editor.getData()); editor.setData( '<p>Some text.</p>' ); alert(editor.getData());">Save</button>
				<div id="editor">
					<p>Hello from CKEditor 5!</p>
				</div>
			</div>
			<script type="importmap">
				{
					"imports": {
						"ckeditor5": "/m/ckeditor5-44.1.0/ckeditor5/ckeditor5.js",
						"ckeditor5/": "/m/ckeditor5-44.1.0/ckeditor5/"
					}
				}
			</script>
			<script type="module">
				import {
					ClassicEditor,
					Essentials,
					Paragraph,
					Bold,
					Italic,
					Font
				} from 'ckeditor5';
				
				ClassicEditor
					.create( document.querySelector( '#editor' ), {
						licenseKey: 'GPL', // Or <YOUR_LICENSE_KEY>
						plugins: [ Essentials, Paragraph, Bold, Italic, Font ],
						toolbar: [
							'undo', 'redo', '|', 'bold', 'italic', '|',
							'fontSize', 'fontFamily', 'fontColor', 'fontBackgroundColor'
						],
						licenseKey: 'GPL'
					} )
					.then( editor => {
						window.editor = editor;
					} )
					.catch( error => {
						console.error( error );
					} );
					
				
				//editor.config.width = '75%';
			</script>
			<?php
			$r .= ob_get_contents();
			ob_end_clean();
			return $r;
		}
		
		function showSND() {
			$r = '';
			//$r .= '<input type="text"/>';
			//$r .= '<br/><textarea></textarea>';
			
			
			//$ar = $this->oM->getHTML('{LGS.lsd:SCF:TYPES/LGS.focM:rl}'
			//$ar = $this->oM->getHTML('{TO.ac:EMS:ud=1}'
			$ar = $this->oM->getHTML('{TO.e}'
				, $this->cr
				, $this->oC->fp
				, 'fd'
				, 1 //trv
				, 'rw'
				, $this->oC->fd['rw']
				, $this->oC
			);
			$r .= 'Кімге'.$ar['hd'];
			
			$ar = $this->oM->getHTML('{SBJ.e}'
				, $this->cr
				, $this->oC->fp
				, 'fd'
				, 1 //trv
				, 'rw'
				, $this->oC->fd['rw']
				, $this->oC
			);
			$r .= '<br/>Тақырып'.$ar['hd'];
			$r .= $this->showBD422();
			/*
			$ar = $this->oM->getHTML('{MSG.txt}'
				, $this->cr
				, $this->oC->fp
				, 'fd'
				, 1 //trv
				, 'rw'
				, $this->oC->fd['rw']
				, $this->oC
			);
			$r .= '<br/>'.$ar['hd'];*/
									
			$r .= '<br/>'.$this->b('', "jT.p='d='+encodeURIComponent(CKEDITOR.instances['editor'].getData());", 'sendMSG', '', 'Жіберу');
			return $r;
		}
		
		function showTP() {
			$r = '';
			$url = '?pg=ems&'.$this->oC->pcr.'fp=2&tp=1';
			if($this->oM->sd['uid']>0) {
				$url = '?pg=ems&'.$this->oC->pcr.'fp=4';
				$r .= '<a href="'.$url.'" >Жазу</a>';
				$r .= '<table>';
				$r .= '<tbody>';
				$url = '?pg=ems&'.$this->oC->pcr.'fp=2&f=INBOX';
				$r .= '<tr><td><a href="'.$url.'" >Кіріс</a></td></tr>';
				$url = '?pg=ems&'.$this->oC->pcr.'fp=2&f=Sent';
				$r .= '<tr><td><a href="'.$url.'" >Жіберілген</a></td></tr>';
				$url = '?pg=ems&'.$this->oC->pcr.'fp=2&f=Sent';
				$r .= '<tr><td><a href="'.$url.'" >Нобай</a></td></tr>';
				$url = '?pg=ems&'.$this->oC->pcr.'fp=2&f=INBOX';
				$r .= '<tr><td><a href="'.$url.'" >Қоқыс</a></td></tr>';
				$r .= '</tbody>';
				$r .= '</table>';
			} else {
				$r .= 'Авторлау қажет';
			}
			return $r;
		}
		
		
		
	}
?>
