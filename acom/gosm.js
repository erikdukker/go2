var dlp 		= ';' //tussen par
var dlw			= '=' //tussen par
function pro (somNo,vr,kn,ea) { //vr=vorm/kn=knop/ea=extra aktie\
	som		= {}
	somsc		= document.getElementById('s|'+somNo).value; // som parameters
	sompars		= somsc.split(";")
	for (i = 0, len = sompars.length; i < len; i++) { 
		sompar	= sompars[i].split("=");
		som[sompar[0]] = sompar[1]
	}
//	alert(som.ok)
	if (typeof( som.ok ) == 'undefined' || somNo == 0 ) {	// al beoordeeld ? de eerste vorige uit de sessie is niet beoordeeld
		if (somNo > 0) { // voorgaande weg
			uit	= somNo - 1
			// alert('uit '+uit+' no '+somNo)		
			var dd = document.getElementById('d|'+uit);
			if (dd != null) {
				document.getElementById('d|'+uit.toString()).style.display='none'	
			}
		}	
//		if (somNo < 5) { // volgende laten zien alleen als antwoord is gegeven 
		if (ea != 9) { // volgende laten zien alleen als antwoord is gegeven 
			if (ea != 1) { 
				aan = somNo + 1
				document.getElementById('d|'+aan.toString()).style.display='inline'	
				var tx = document.getElementById('t|'+aan);
				if (tx != null) {
					tx.focus()
				} else {
					document.getElementById('k|'+aan+'|1'.toString()).focus()	
				}	
			}	
		}	
		if (vr 	== 1) { //mk
			document.getElementById('k|'+somNo+'|'+som['ko'].toString()).style.background='#41fa10'; 
			if (kn == som.ko){ 			
				document.getElementById('ok|'+somNo.toString()).style.display='inline'		
				document.getElementById('s|'+somNo).value = somsc+'sc='+kn+';ok=a;';
			} else {
				document.getElementById('no|'+somNo.toString()).style.display='inline'			
				document.getElementById('k|'+somNo+'|'+kn.toString()).style.background='#fd0404'
				document.getElementById('s|'+somNo).value = somsc+'sc='+kn+';ok=u;';
			}
		}
		if (vr == 2) { //tx
			sc=document.getElementById('t|'+somNo).value;	
			gegant		= sc.replace(/\s+/g, '');
			corant		= som['rs'].replace(/\s+/g, '');
			document.getElementById('t|'+somNo.toString()).disabled=true	
			//alert(som);
			if (gegant != corant){ 
				document.getElementById('no|'+somNo.toString()).style.display='inline'
				document.getElementById('o|'+somNo.toString()).style.display='inline'			
				document.getElementById('s|'+somNo).value = somsc+'sc='+sc+';ok=u;';
			} else {
				document.getElementById('ok|'+somNo.toString()).style.display='inline'			
				document.getElementById('s|'+somNo).value = somsc+'sc='+sc+';ok=a;';
			}
		}
		document.getElementById('d|'+somNo.toString()).style.display=''	
		document.getElementById('d|'+somNo.toString()).style.opacity="0.4"
		document.getElementById('v|s'+somNo.toString()).style.display='none'
		document.getElementById('v|v'+somNo.toString()).style.display='inline'
	}
	if (ea == 9) {
		//alert('submit')
		document.getElementById('fsmtn').submit();
	}
}