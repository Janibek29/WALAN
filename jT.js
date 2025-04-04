  
var jT = {
  cn: ''
  , n: 1
  , c: ''
  , f: ''
  , acr: true
  , res: ''
  , uc: ""
  , p: ""
  , tf_id: 0
  , dlg_n: 0
  , GDSMB: ":"
  , r_dvid: ''
  , rr_dvid: ''
  , aasync: true
  , atimeout: 10000
  , xhrs: []
  //a = undefined;
  , xhrn: 0
  , rckf: ''
  , rurl: ''
  , maindiv: 'maindiv'
  , loadimgid: "loadimg"
  , ap: {}
  , isreload: false
  
  , setwh: function() {
	  this.a('?wh','w='+window.screen.width+'&h='+window.screen.height);
  }
  
  
  
  , LockOn: function() { 
    var lock = document.getElementById(this.maindiv);
    var img = document.getElementById(this.loadimgid);
	if(typeof lock != undefined && lock != null) {
		lock.style.pointerEvents = "none";
		//lock.style.opacity = 0.4;
		img.style.display = "block";
    }
  }
  
  , LockOff: function() { 
    var lock = document.getElementById(this.maindiv);
    var img = document.getElementById(this.loadimgid);
    lock.style.pointerEvents = "unset";
    //lock.style.opacity = 1;
    img.style.display = "none";
    
    //lock.removeChild(this.loadimg);
    //if (lock) 
       //lock.className = 'LockOff'; 

    //lock.innerHTML = str;
	if(this.isreload) {
		window.location.reload();
		this.isreload = false;
	}
  }
      
  , ovlon: function() {
    //alert('ovlon');
    document.getElementById("overlay").style.display = "block";
  }
  
  , ovloff: function() {
    //alert('ovloff');
    document.getElementById("overlay").style.display = "none";
  }
  
  , a2: function (url, pst) {
    return $.ajax({
      type: "POST",
      url: url,
      //async: false,
      success: function(){
        zT.ovloff();
      }
    }).done(function() {
      //$( this ).addClass( "done" );
      
    }).responseText;
    //return '';
    //return xmlhttp.responseText;
  }
  

  , a: function (url, pst) {
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  var xhr = new XMLHttpRequest();
		}
		else {// code for IE6, IE5
		  var xhr = new ActiveXObject("Microsoft.XMLHTTP");      
		}
		
		
		
		xhr.ontimeout = (e) => {
		  
		};
			  
		  xhr.onload = () => {			
			if (xhr.readyState === 4) {
			  if (xhr.status === 200) {
				var c = '';
				var k = '';
				var f = '';
				var fp = 1;
				var ar = xhr.responseURL.split('?');
				var urlps = ar[1].split('&');
				//urlps.forEach(function(elem, ind) {
					elem = urlps[0];
					ar = elem.split('=');
					if(ar[0]==CMD) {
						ar = ar[1].split(SSD);
						c = ar[0];
						k = ar[1];
						f = ar[2];
						if(!isNaN(f)) {
							fp = f;
							f = ar[3];
						}
					}
				//});
				
				if(xhr.responseText!='') {
					this.ares(c, k, f, xhr.responseText, fp);
				} /*else if(this.rurl!=''){
					this.a(this.rurl, '');
				}*/
				
				if(f=='rl') {
					window.location.reload();
				}
				
				if(this.lockShow)
					this.LockOff();
			  }
			}			
		  };
		  
		  xhr.onerror = (e) => {
			alert(xhr.statusText);
		  };
		  
		  if(this.lockShow)
			this.LockOn();
		
    
		xhr.open("POST", url, true);
		xhr.setRequestHeader('Pragma', 'no-cache');
		xhr.setRequestHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
		xhr.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
		xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
		xhr.timeout = this.atimeout;
		xhr.send(pst);
	}
	
	
	, ufs: function(url, files, max_size, quality, pst) {
		//alert(url);
		var formData = new FormData();
		var params = new URLSearchParams(pst);
		params.forEach(function(value, key) {
			formData.append(key, value);
		});
		
		for (var i = 0; i < files.length; i++) {
			var file = window.file = files[i]; // global scope so visible in console
			
			if(file.type.match('image.*')) {
				if(max_size>0) { //егер шектеу болса
					// Load the image
					var reader = new FileReader();
					reader.onload = function (readerEvent) {
						
						var image = new Image();
						
						image.onload = function (imageEvent) {
							// Resize the image
							var canvas = document.createElement('canvas'),
								width = image.width,
								height = image.height;
							if (width > height) {
								if (width > max_size) {
									height *= max_size / width;
									width = max_size;
								}
							} else {
								if (height > max_size) {
									width *= max_size / height;
									height = max_size;
								}
							}
							canvas.width = width;
							canvas.height = height;
							var ctx = canvas.getContext('2d');
							ctx.drawImage(image, 0, 0, width, height);
							/*
							ctx.rotate(20 * Math.PI / 180);
							ctx.fillRect(50, 20, 100, 50);*/
							canvas.toBlob(
								(blob) => {
								  if (blob) {
									var resizedFile = new File([blob], file.name, file);
									formData.append("file"+i, resizedFile);
									jT.sendfs(url, formData);									
								  }
								},
								"image/jpeg",
								quality
							);
						}
						image.src = readerEvent.target.result;
					}
					reader.readAsDataURL(file);
				} else { ////егер шектеу болмаса
					formData.append("file"+i, file);
					//jT.sendfs(url, formData);
				}
			} else {
				formData.append("file"+i, file);
				
			}
		}
		
		if(max_size==0) {
			jT.sendfs(url, formData);
		}
	}
	
	, sendfs: function(url, formData) {
		//alert(url);
		if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
		  var xhr = new XMLHttpRequest();
		}
		else {// code for IE6, IE5
		  var xhr = new ActiveXObject("Microsoft.XMLHTTP");      
		}
		xhr.onload = () => {			
			if (xhr.readyState === 4) {
			  if (xhr.status === 200) {
				//alert(xhr.responseText); 
				var c = '';
				var k = '';
				var f = '';
				var ar = xhr.responseURL.split('?');
				var urlps = ar[1].split('&');//alert(xhr.responseURL);
				//urlps.forEach(function(elem, ind) {
					elem = urlps[0];//alert(urlps[0]);
					ar = elem.split('=');//alert(ar[0]+" "+);
					if(ar[0]==CMD) {
						ar = ar[1].split(SSD);
						c = ar[0];
						k = ar[1];
						f = ar[2];
						//var rurl = "?"+CMD+"="+c+SSD+"r";
						//alert(rurl);
						//jT.a(rurl, '');
					}
				//});
				
				if(xhr.responseText!='') {
					this.ares(c, k, f, xhr.responseText);
				}
				
				if(this.lockShow)
					this.LockOff();
			  }
			}			
		};
		xhr.open("POST", url, true);
		xhr.send(formData);
		//console.log('sendfs', url);
	}
	
	, ares: function(c, k, f, rtxt, fp) {
		//alert("c="+c+" k="+k+" f="+f);
		switch (c) {
			case 'cls:mvc:cs:cBC':
				switch (f) {
				  case 'bc':
					location.href = "?pg="+k+"&bc="+encodeURIComponent(rtxt);
				  break;
				  case 'nc':
					location.href = "?pg="+k+"&nc="+encodeURIComponent(rtxt);
				  break;
				}
			break;
			default:
				
				switch (f) {
					/*case 'idMSG':
						document.getElementById('idMSG').innerHTML = rtxt;
					break;*/
				  case 'r':
					var dvnm = c+SSD+k;
					//alert(dvnm);
					//alert(rtxt);
					document.getElementById(dvnm).innerHTML = rtxt;
					this.jq();
				  break;
				  case 'getshowed':
					if(rtxt==1) {
						this.olshowed = true;
					} else {
						this.olshowed = false;
					}
					this.OL();
				  break;
				  case 'getDial':
					document.getElementById("msgDial").innerHTML = rtxt;
					this.rckf = '';
					
				  break;
				  case 'getDialMsgCount':
					if(this.DialMsgCount!=rtxt) {
						this.UpdateMsg = true;
						this.DialMsgCount = rtxt;
					}
					this.rckf = '';
					
				  break;
				  
				  case 'ufs':
					alert(rtxt);
					location.reload();
				  break;
				  
				  default:
					
					//$r .= $this->jo.".a('?".$this->cmd."=".$this->vw.$this->ssd."r".$this->rgp."', '".$this->rpp."');";
					/*
					alert(rtxt);
					if(rtxt.substring(1, 2)=='r'.SSD) {
						alert("c="+c+" k="+k+" f="+f);
						//document.getElementById(c+SSD+k).innerHTML = rtxt;				
					} else
						alert("c="+c+" k="+k+" f="+f+" rtxt="+rtxt);*/
						alert(rtxt);
				}
				
		}
	}
	
	, showimg: function (fid) {
		
		var sufimg = document.querySelector("#sufimg");
		
		if (typeof sufimg !== "undefined") {
			sufimg.src = "index.php?CKF=cls:mvc:vs:vFILES;1;img&img="+fid;
			sufimg.style.display = "";
		}
		/*
		var dvimg = document.createElement('div');
		dvimg.style.position = "fixed";
		dvimg.style.top = "0";
		dvimg.style.left = "0";
		dvimg.style.width = "100%";
		dvimg.style.height = "100%";
		
			var img = document.createElement('img');
			img.src = "img/msg2.png";
			img.style.top = "0";
			img.style.left = "0";
			img.style.width = "100%";
			img.style.height = "100%";
			img.addEventListener('dblclick', function handleClick(event) {
			  jT.hideimg();
			});
			dvimg.appendChild(img);
		document.body.appendChild(dvimg);*/
	}
	
	, hideimg: function () {
		var sufimg = document.querySelector("#sufimg");
		
		if (typeof sufimg !== "undefined") {
			sufimg.style.display = "none";
		}
	}
	
  , aa: function (url, pst) {
    if (window.XMLHttpRequest) {// code for IE7+, Firefox, Chrome, Opera, Safari
      xmlhttp=new XMLHttpRequest();
    }
    else {// code for IE6, IE5
      xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
    }
    //xmlhttp.open("POST", url, false);
    xmlhttp.open("POST", url, true);
    xmlhttp.setRequestHeader('Pragma', 'no-cache');
    xmlhttp.setRequestHeader('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
    xmlhttp.setRequestHeader("If-Modified-Since", "Sat, 1 Jan 2000 00:00:00 GMT");
    xmlhttp.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    
    xmlhttp.onload = (e) => {
      if (xmlhttp.readyState === 4) {
        if (xmlhttp.status === 200) {
          document.getElementById('wrk').innerHTML = xmlhttp.responseText;
        } else {
          console.error(xmlhttp.statusText);
        }
      }
    };
    xmlhttp.onerror = (e) => {
      console.error(xmlhttp.statusText);
    };
    
    xmlhttp.send(pst);
    
    return xmlhttp.responseText;
  }
  
  , ac: function() {
    this.res = '';
    this.uc = this.f+'?'+CMD+'='+this.cn+SSD+this.n+SSD+this.c;
    //alert(this.uc);
    if(this.acr)
      this.res = this.a(this.uc, this.p);
    return this.res;
  }
  
  , r: function() {
    
    if(!this.aasync) {
      this.c = 'r';
      this.ac();
      var dvid = this.cn+SSD+this.n;
      document.getElementById(dvid).innerHTML = this.res;
      this.jq();
    } else {
      this.c = 'r';
      this.rckf=this.cn+SSD+this.n;
	  zT.a('?CKF='+this.rckf+SSD+this.c, '');
    }
    //console.log(this.rckf);
    return this.res;
  }
  
	, utf8b64: function(str) {
		return window.btoa(unescape(encodeURIComponent(str)));
	}
	
	, b64EncodeUnicode: function (str) {
		// first we use encodeURIComponent to get percent-encoded UTF-8,
		// then we convert the percent encodings into raw bytes which
		// can be fed into btoa.
		return btoa(encodeURIComponent(str).replace(/%([0-9A-F]{2})/g,
			function toSolidBytes(match, p1) {
				return String.fromCharCode('0x' + p1);
		}));
	}
	
  , rd: function(cn, n) {
    
    if(!this.aasync) {
      var cn1 = this.cn;
      var n1 = this.n;
      this.cn = cn;
      this.n = n;
      this.c = 'r';
      this.ac(); //alert(cn+SSD+n);
      var dvid = cn+SSD+n;
      document.getElementById(dvid).innerHTML = this.res;
      this.jq();
      this.cn = cn1;
      this.n = n1;
    } else {
      //this.c = 'r';
      this.rckf=cn+SSD+n;
    }
    //console.log(this.rckf);
    return this.res;
  }
  
  , test: function() {
    var r = 'test';
    alert(r);    
    return r;
  }
  
  
  
  , sv_cn: function(tf_id, rk) {
    var r=false;
    this.c = 'sv_cn';//alert(this.cn+"_"+this.n);
    this.ac();
    if(this.res!='') {
      r = confirm(this.res);
    } else {
      r = true;
    }      
    return r;
  }
  
  
  
  , rtg: function(tgid, c, p) {
    this.p = p;
    this.c = c;
    this.ac();
    //alert(this.res);
    document.getElementById(tgid).innerHTML = this.res;
    this.jq();
    return this.res;
  }
  
  , rmfm: function(p_cn, p_n, p_cls_nm) {
    this.c = 'rmfm';//alert(this.cn+"_"+this.n);
    this.cn = 'zTB';
    this.n = p_n;
    this.p = 'cls_nm='+p_cls_nm;
    this.ac();
    document.getElementById(p_cn).innerHTML = this.res;
    this.jq();
    
    return this.res;
  }
  
  , sr: function(t,cl,v) {
    this.cn = 'zS';
    this.c = 'sr';
    this.p = 't='+t+'&cl='+cl+'&v='+v;
    this.ac();
    return this.res;
  }
  
  , php: function(sc) {
    this.cn = 'zTB';
    this.c = 'scs';
    this.p = 'tf_id='+this.tf_id+'&sc='+sc+'&tp=php';
    this.ac();
    return this.res;
  }
  
  , cls: function(n, p) {
    //alert("Hello");
    this.n = n;
    this.cn = 'zTB';
    this.c = 'cls';
    this.p = p;
    this.ac();
    //alert(this.res);
    if(this.res!="")
      alert(this.res);
    else
      zT.r();
  }
  
  , rsc: function(n, p) {
    //alert("Hello");
    this.n = n;
    this.cn = 'cls:zGD';
    this.c = 'rsc';
    this.p = p;
    this.ac();
    return this.res;
  }
  
  , getCurrentTimeString: function() {
    var r = '';
    let days = ['JS', 'DS', 'SS', 'SR', 'BS', 'JM', 'SB'];
    var sdt = new Date();
    var dt = sdt.toLocaleDateString();
    var tm = sdt.toTimeString().replace(/ .*/, '');
    var ar = tm.split(':');
    r = ar[0]+':'+ar[1]+' '+days[sdt.getDay()]+' '+dt;
    return r;
  }
  
  //RefreshOnMinute
  , rom: function() { 
    var timeNode = document.getElementById('time-node');
    if (timeNode !== null)
      timeNode.innerHTML = zT.getCurrentTimeString();    
    
    var wrk = document.getElementById('wrk');
    if(typeof(wrk) != "undefined" && wrk !== null) {
      //wrk.innerHTML = 
      zT.aa(zT.f+'?'+CMD+'=cls'+SSC+'zA'+SSD+'1'+SSD+'wrk', '');
    }
        
  }
  
  , jq: function() {
    
	$( ".ac" ).autocomplete({
      source: function (request, response) {
        var oc = this.element.attr('data-oc');
		var foc = this.element.attr('data-foc');
		var ftrv = this.element.attr('data-ftrv');
		var tp = this.element.attr('data-tp');
		var rurl = this.element.attr('data-rurl');
        $.ajax({
          type: "POST",
          url: '?o=mvc:cs:cCL;1;1;ac',
          dataType: "json",
          data: "oc="+oc+"&foc="+foc+"&ftrv="+ftrv+'&tp='+tp+"&term="+request.term,
          async: true,
          cache:false,
          beforeSend: function(){            
          },
          success: function(data){
            //alert(data);
            response(data);            
          },
          error: function (request, status, error) {
            alert(request.responseText);
          }
        });        
      },
      delay: 1000,
      selectFirst: false,
      minLength: 1,
      select: function(event, ui) {
        var $el = $(this);
				var oc = $el.attr('data-oc');
				var foc = $el.attr('data-foc');
				var ftrv = $el.attr('data-ftrv');
				var tp = $el.attr('data-tp');
				var rurl = $el.attr('data-rurl');
				var v = ui.item.id;
    		var m = ui.item.label;
        jT.a('?o=mvc:cs:cCL;1;1;oc', 'oc='+oc+'&foc='+foc+'&ftrv='+ftrv+'&tp='+tp+'&v='+v+'&m='+m);
		
		if(rurl!='')
			jT.a(rurl,'');
      },
      response: function( event, ui ) {
      },
      messages: {
          noResults: '',
          results: function() {            
          }
      },
      close: function(el) {
      }
    });
	
    $( ".datepicker" ).datepicker({
      dateFormat: 'yy-mm-dd',
      monthNames : ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
      dayNamesMin : ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
      firstDay: 1
    });
    
    jQuery.datetimepicker.setLocale('ru');
    $( ".datetimepicker" ).datetimepicker({
      format:'Y-m-d H:i',
      lang:'ru',
      dayOfWeekStart: 1
    });
    
    //$(":input").inputmask();
    //$("#phone").inputmask({"mask": "+7(799)999-99-99"});
    if ($(".phone").hasOwnProperty('mask')) {
      $(".phone").mask("+7(799)999-99-99");
    }
    
  }
  
  , tryEval: function(code) {
    try{
      eval(code)
    } catch(e) {
      alert(e.name+code)
    } finally {
      //alert("finished")
    }
  }

  , ac1: function(cn, n, c, p) {
    this.cn=cn;
    //alert("cn="+cn+"n="+n+"c="+c+"p="+p);
    if(c=='r') {
      //alert(cn+n);
      this.res = this.a(this.get_uc(n)+c, p);
      
      //$( "#"+cn+n).html(this.res);
      var p_b = this.res.indexOf("<script>");
      var p_e = this.res.indexOf("</script>");
      if(p_b>=0) {
        
        var l = p_e-(p_b+8);
        //alert(p_b+8);
        var js_sc = this.res.substr((p_b+8),l);
        //alert(this.res);
        //alert(typeof(js_sc));
        //alert(js_sc);
        //eval(js_sc);
        this.tryEval(js_sc);
      }
      document.getElementById(cn+n).innerHTML = this.res;
      this.jq();
      return this.res;
    } else if(c=='uf') {
      //alert('uf');
      this.res = this.uf(this.get_uc(n)+c,p);
      return this.res;
    } else if(c=='gf') {
      window.open(this.get_uc(n)+c+'&'+p,'_blank');
    } else if(c=='prn') {
      this.res = this.a(this.get_uc(n)+c, p);
      //alert(this.get_uc(n)+c+p+'res='+this.res);
      //alert(this.res);
      window.open(this.get_uc(n)+'gf&file_id='+this.res,'_blank');
    }  else {
      return this.a(this.get_uc(n)+c, p);
    }
  }
  
  , uff: function(filen, files, tp_id) {
    //alert('Hello');
    var url = zT.f+'?ZOBJ='+zT.cn+'&N='+zT.n+'&C=uf'+'&filen='+filen+'&tp_id='+tp_id;
    this.res = this.uf(url, files);
    
    if(!isNaN(this.res))
      alert(url+this.res);
    return this.res;
  }
  
  , uff1: function(filen, files, tp_id) {
    alert('Hello');
    //var url = zT.f+'?ZOBJ='+zT.cn+'&N='+zT.n+'&C=uf'+'&filen='+filen+'&tp_id='+tp_id;
    //this.res = this.uf(url, files);
    //alert(url+this.res);
    //return this.res;
  }
  
  , uf: function(url, files) {
    //var url = "index.php?sc=ff&uf=uf";
    //alert(files[0].size);
    var r = 0;
    var formData = new FormData();
    var file = files[0];
    if (file.size<4*1024*1024) {
      formData.append("file", file);
      
      var xmlhttp;
      if (window.XMLHttpRequest)
        xmlhttp=new XMLHttpRequest();
      else
        xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
      xmlhttp.open("POST", url, false);
      xmlhttp.send(formData);
      r = xmlhttp.responseText;
      alert(r);
    }
    return r;
  }
  
	
  
  , set_ada: function(n, p) {
    this.cn = 'zTB';
    this.n = n;
    this.c = 'set_ada';
    this.p = 'p='+p;
    this.ac();
    return this.res;
  }
  
  , dlg: function(tt, W, H, data) {
    zT.dlg_n++;
    id = 'zGdlg'+zT.dlg_n;
    if(document.getElementById(id)) {
      zG.dcl(id);
    }
    
    var d = document.createElement("div");
    d.id = id;
    d.setAttribute("title",tt);
    document.body.appendChild(d);
    
    $( "#"+id ).dialog({
      autoOpen: false,
      width: W,
      height: H,
      modal: true
    });
    
    $( "#"+id ).dialog( "open" );
    
    $( "#"+id ).on('dialogclose', function(event) {
      zT.dcl(id);
    });
     
    this.bd = document.createElement("div");
    this.bd.id = id+"_bd";
    this.bd.innerHTML = data;
    d.appendChild(this.bd);
    this.jq();
    return this.bd;
  }
  
  , dcl: function(id) {
    $( "#"+id ).dialog("destroy");
    $( "#"+id ).remove();//alert(zZ.clscr);
    if(zT.ads!="") {
      
      eval(zT.ads);
    }
  }
  
  , txt: function(v, cn, n, c) {
    zG.dlg("TEXT", 680, 560, ''); 
    var d = document.getElementById('zGdlg'+zG.dlg_n+"_bd");
    var t = document.createElement("textarea");
    t.setAttribute("rows",25);
    t.setAttribute("cols",80);
    t.setAttribute("onchange","zG.r = zG.ac('"+cn+"', '"+n+"', '"+c+"', zG.p+'&v='+encodeURIComponent(this.value)); if(zG.r!='') alert(zG.r);");
    t.value = v;
    //t.id = id+"_bd";
    //t.innerHTML = data;
    d.appendChild(t);
  }
  
  , rr: function() {
    
    zG.ac(zG.p_cn,zG.p_n,'r','');
    
  }
  
}


/*
const fileSelector = document.getElementById('file-selector');
  fileSelector.addEventListener('change', (event) => {
    const fileList = event.target.files;
    console.log(fileList);
    alert('Hello');
  });*/
