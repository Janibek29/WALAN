var jEMS = {
	CMD: "o"
	, SSC: ":"
	, SSD: ";"
	
	, mCLS: "oM"
	, vCLS: "mvc:vs:vEMS"
	, cCLS: "mvc:cs:cEMS"
	, fp: 1
	, CH: 0
	, MSGColor: "FFFFFF"
	, MURL: ""
	, COURL: ""
	, VOURL: ""
	, OLShowed: false
	, CID: 0
	, SID: 0
	, newmsgcount: 0
	
	, init: function(n, c, url) {
		
		this.CH = n;
		this.MSGColor = c;
		this.MURL = url;
		this.MOURL = url+"?"+this.CMD+"="+this.mCLS+this.SSD+this.fp+this.SSD+this.CH+this.SSD;
		this.COURL = url+"?"+this.CMD+"="+this.cCLS+this.SSD+this.fp+this.SSD+this.CH+this.SSD;
		this.VOURL = url+"?"+this.CMD+"="+this.vCLS+this.SSD+this.fp+this.SSD+this.CH+this.SSD;
		
		//this.setSID();
		//alert(this.MOURL);
		//Батырма
		document.body.appendChild(this.getBTN());
		
		//Хабарлама мәтін div
		//document.body.appendChild(this.setMBD());
		
		//alert(document.cookie);
		//CID бойынша санын алу
		//this.a(this.COURL+"cnt&cid=", '');
		//Таймер хабарламаны жаңалау
		//this.TM();
	}
	
	, getBTN: function() {
		var dvbt = document.createElement('div');
		dvbt.style.position = "fixed";
		dvbt.style.bottom = "30px";
		dvbt.style.left = "30px";
		dvbt.style.borderRadius = "30px";
		dvbt.style.background = "#"+this.MSGColor;
		dvbt.style.width = "50px";
		dvbt.style.height = "50px";
		dvbt.style.boxShadow = "0 0 20px rgba(0,0,0,0.5)";
		
		var img = document.createElement('img');
		img.src = this.MURL+"img/ems.png";
		img.width = "30";
		img.style.margin="10px";
		//img.onclick = "";
		img.addEventListener('click', function handleClick(event) {
		  jEMS.clkOL();
		});
		
		dvbt.appendChild(img);
		return dvbt;
	}
	
	, setMBD: function() {
		this.dvmsg = document.createElement('div');
		this.dvmsg.style.position = "fixed";
		this.dvmsg.style.display = "none";
		this.dvmsg.style.bottom = "100px";
		this.dvmsg.style.left = "20px";
		this.dvmsg.style.border = "1px solid "+"#"+this.msgcolor;
		//this.dvmsg.style.opacity = "0.9";
		this.dvmsg.style.borderRadius = "10px";
		this.dvmsg.style.background = "#FFFFFF";
		this.dvmsg.style.width = "150px";
		this.dvmsg.style.height = "200px";
		this.dvmsg.style.padding = "10px";
		this.dvmsg.style.boxShadow = "0 0 20px rgba(0,0,0,0.5)";
		this.dvmsg.style.zIndex = "999999";
		//Мәтін
		var url = this.VOURL+"showTP";
		//alert(url);
		this.a(url, '');
		return this.dvmsg;
	}
	
	, setSID: function() {
		if(this.getCookie('WALANSID')=='' || this.getCookie('WALANSID')=='0') {
			//this.a(this.MOURL+"startWALANSID", '');
		}
	}
	
	, TM: function() {
		/*setTimeout(() => {
		  jMSG.r();
		}, 1000);*/
		
		setInterval(() => {
			if(this.OLShowed) {
				const idMSG = document.getElementById('idMSG');
				if (!(typeof idMSG === 'undefined' || idMSG===null)) {
					//alert(idMSG);
					//this.a('?CKF=cls:mvc:vs:vMSG;1;idMSG', '');
				}
				
				const NEWMSG = document.getElementById('NEWMSG');
				if (!(typeof NEWMSG === 'undefined' || NEWMSG===null)) {
					this.a('?o=mvc:cs:cMSG;1;1;0;0;NEWMSG', '');
				}
				
				
				//Жаңа хабарламаны тексеру
				
				var idCHS = document.getElementById('idCHS');
				if (!(typeof idCHS === 'undefined' || idCHS===null)) {
					//alert(idCHS);
					this.a('?o=mvc:cs:cMSG;1;1;0;0;NEWMSGCHS', '');
				}
			}
			
			
		}, 2000);
		
		/*
		setInterval(() => {
			if(this.OLShowed) {
				//this.setCookie("WALANSID", 0, 360);
				//alert(document.cookie);
				//alert(this.getCookie('WALANSID'));
				//this.a(this.MOURL+"getCookieValue", '');
				//this.a(this.COURL+"MSGCount", '');
				//this.a(this.VOURL+"show", '');
				/*
				this.lockShow = false;
				this.rckf = this.jo+this.SSD+this.n;
				this.a(this.up+'getDialMsgCount', '');
				this.lockShow = true;
				
				if(this.UpdateMsg) {
					this.lockShow = false;
					this.rckf = this.jo+this.SSD+this.n;
					this.a(this.up+'getDial', '');
					this.lockShow = true;
					this.UpdateMsg = false;
				}
				/
				
			}
		}, 2000);*/
		
	}
	
	, goBottom: function () {
		this.a('?o=cls:mvc:cs:cMSG;1;1;0;0;newmsg0', '');
		//var idMSG = document.getElementById('idMSG');
		//idMSG.scrollTop = idMSG.scrollHeight;
		this.a('?o=cls:mvc:vs:vMSG;1;1;0;0;idMSG', '');
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
					if(ar[0]==this.CMD) {
						ar = ar[1].split(this.SSD);
						c = ar[0];
						k = ar[1];
						fp = ar[2];
						f = ar[3];
					}
				//});
				
				if(xhr.responseText!='') {
					this.ares(c, k, f, xhr.responseText, fp);
				}
				
				if(this.lockShow)
					this.LockOff();
			  }
			}			
		  };
		  
		  xhr.onerror = (e) => {
				//alert('xhr.onerror'+xhr.statusText);
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
	
	, ares: function(c, k, f, rtxt, fp) {
		//alert("c="+c+" k="+k+" fp="+fp+" f="+f+this.vCLS);
		switch (c) {
			case this.mCLS:
				switch (f) {					
					case 'startWALANSID':
						alert(rtxt);
						this.setCookie("WALANSID", rtxt, 360);
						//document.cookie = "WALANSID="+rtxt+"; SameSite=None;";
					break;
					case 'getCookieValue':
						alert(rtxt);
					break;
					default:
						alert(rtxt);
				}
			break;
			case this.vCLS:
				switch (f) {
					case 'r':
						var dvnm = c+SSD+k;
						document.getElementById(dvnm).innerHTML = rtxt;
					break;
					case 'idCHS':
						var idCHS = document.getElementById('idCHS');
						idCHS.innerHTML = rtxt;
					break;
					case 'newmsg0':
						
					break;
					case 'show':
						this.dvmsg.innerHTML = rtxt;
					break;
					case 'showTP':
						this.dvmsg.innerHTML = rtxt;
					break;
					case 'idMSG':
						//alert('idMSG');
						var idMSG = document.getElementById('idMSG');
						idMSG.innerHTML = rtxt;
						idMSG.scrollTop = idMSG.scrollHeight;
						var NEWMSG = document.getElementById('NEWMSG');
						NEWMSG.style.display = "none";
					break;
					default:
						alert(rtxt);
				}
			break;
			case this.cCLS:
				switch (f) {
					case 'NEWMSGCHS':
						if(this.newmsgcount!=rtxt) {
							//alert(rtxt);
							this.a('?o=mvc:vs:vMSG;1;1;idCHS', '');
							this.newmsgcount=rtxt;
						}
					break;
					case 'NEWMSG':
						var NEWMSG = document.getElementById('NEWMSG');
						if (!(typeof NEWMSG === 'undefined' || NEWMSG===null)) {
							NEWMSG.innerHTML = rtxt;
							if(rtxt>0) {
								
								var idMSG = document.getElementById('idMSG');
								if(idMSG.scrollHeight - Math.abs(idMSG.scrollTop) === idMSG.clientHeight) {
									this.goBottom();
								} else {
									NEWMSG.style.display = "block";
								}
								//alert((idMSG.scrollHeight-idMSG.scrollTop)+" "+idMSG.clientHeight);
								
							}
							
						}
						
					break;
					case 'cid':
						//alert(this.getCookie('WALANUSER'));
						//document.cookie = "WALANUSER=John";
						//alert(document.cookie);
						//this.CID = rtxt;
						//alert(rtxt);
					break;
					default:
						alert(rtxt);
				}
			break;
			
			default:
				alert(rtxt);/*
				switch (f) {
					
					default:
						alert(rtxt);
				}	*/			
		}
	}
	
	, clkOL: function() {
		if(!this.OLShowed)
			this.showOL();
		else
			this.hideOL();
	}
	
	, showOL: function() {
		if (typeof this.dvmsg === 'undefined') {
			//alert(this.dvmsg);
			document.body.appendChild(this.setMBD());
		}
		
		this.dvmsg.style.display = "block";
		this.OLShowed = true;
		//this.a(this.up+'setshowed', 'v='+this.OLShowed);
	}

	, hideOL: function() {
		this.dvmsg.style.display = "none";
		this.OLShowed = false;
		//this.a(this.up+'setshowed', 'v='+this.OLShowed);
	}
	
	, setCookie1: function(name, value, options = {}) {

	  options = {
		path: '/',
		// при необходимости добавьте другие значения по умолчанию
		...options
	  };

	  if (options.expires instanceof Date) {
		options.expires = options.expires.toUTCString();
	  }

	  let updatedCookie = encodeURIComponent(name) + "=" + encodeURIComponent(value);

	  for (let optionKey in options) {
		updatedCookie += "; " + optionKey;
		let optionValue = options[optionKey];
		if (optionValue !== true) {
		  updatedCookie += "=" + optionValue;
		}
	  }

	  document.cookie = updatedCookie;
	}

	, setCookie: function(cname, cvalue, exdays) {
	  const d = new Date();
	  d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
	  let expires = "expires="+d.toUTCString();
	  document.cookie = cname + "=" + cvalue + ";" + expires + ";path=/";
	}

	, getCookie: function(cname) {
	  let name = cname + "=";
	  let ca = document.cookie.split(';');
	  for(let i = 0; i < ca.length; i++) {
		let c = ca[i];
		while (c.charAt(0) == ' ') {
		  c = c.substring(1);
		}
		if (c.indexOf(name) == 0) {
		  return c.substring(name.length, c.length);
		}
	  }
	  return "";
	}

	, checkCookie: function() {
	  let user = getCookie("username");
	  if (user != "") {
		alert("Welcome again " + user);
	  } else {
		user = prompt("Please enter your name:", "");
		if (user != "" && user != null) {
		  setCookie("username", user, 365);
		}
	  }
	} 
}
