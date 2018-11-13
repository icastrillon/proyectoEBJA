function calcular_nota_final(codigo_inscripcion){
	var m1p1 = document.getElementById('m1p1-'+codigo_inscripcion);
	var m1p2 = document.getElementById('m1p2-'+codigo_inscripcion);
	var m1p3 = document.getElementById('m1p3-'+codigo_inscripcion);
	var m1p4 = document.getElementById('m1p4-'+codigo_inscripcion);
	var m2p1 = document.getElementById('m2p1-'+codigo_inscripcion);
	var m2p2 = document.getElementById('m2p2-'+codigo_inscripcion);
	var m2p3 = document.getElementById('m2p3-'+codigo_inscripcion);
	var m2p4 = document.getElementById('m2p4-'+codigo_inscripcion);
	var pm1 = document.getElementById('pm1-'+codigo_inscripcion);
	var pm2 = document.getElementById('pm2-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var dm1 = document.getElementById('dm1-'+codigo_inscripcion);
	var dm2 = document.getElementById('dm2-'+codigo_inscripcion);

	if(isNaN(m1p1.value)){
		m1p1.value = 0.00;
		return;
	}else if(m1p1.value > 10 || m1p1.value < 0){
		m1p1.value = 0.00;
		return;
	}

	if(isNaN(m1p2.value)){
		m1p2.value = 0.00;
		return;
	}else if(m1p2.value > 10 || m1p2.value < 0){
		m1p2.value = 0.00;
		return;
	}

	if(isNaN(m1p3.value)){
		m1p3.value = 0.00;
		return;
	}else if(m1p3.value > 10 || m1p3.value < 0){
		m1p3.value = 0.00;
		return;
	}

	if(isNaN(m1p4.value)){
		m1p4.value = 0.00;
		return;
	}else if(m1p4.value > 10 || m1p4.value < 0){
		m1p4.value = 0.00;
		return;
	}

	if(isNaN(m2p1.value)){
		m2p1.value = 0.00;
		return;
	}else if(m2p1.value > 10 || m2p1.value < 0){
		m2p1.value = 0.00;
		return;
	}

	if(isNaN(m2p2.value)){
		m2p2.value = 0.00;
		return;
	}else if(m2p2.value > 10 || m2p2.value < 0){
		m2p2.value = 0.00;
		return;
	}

	if(isNaN(m2p3.value)){
		m2p3.value = 0.00;
		return;
	}else if(m2p3.value > 10 || m2p3.value < 0){
		m2p3.value = 0.00;
		return;
	}

	if(isNaN(m2p4.value)){
		m2p4.value = 0.00;
		return;
	}else if(m2p4.value > 10 || m2p4.value < 0){
		m2p4.value = 0.00;
		return;
	}

	var hm1p1 = document.getElementById('hm1p1-'+codigo_inscripcion);
	var hm1p2 = document.getElementById('hm1p2-'+codigo_inscripcion);
	var hm1p3 = document.getElementById('hm1p3-'+codigo_inscripcion);
	var hm1p4 = document.getElementById('hm1p4-'+codigo_inscripcion);
	var hm2p1 = document.getElementById('hm2p1-'+codigo_inscripcion);
	var hm2p2 = document.getElementById('hm2p2-'+codigo_inscripcion);
	var hm2p3 = document.getElementById('hm2p3-'+codigo_inscripcion);
	var hm2p4 = document.getElementById('hm2p4-'+codigo_inscripcion);
	var hpm1 = document.getElementById('hpm1-'+codigo_inscripcion);
	var hpm2 = document.getElementById('hpm2-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);
	var hnf = document.getElementById('hnf-'+codigo_inscripcion);
	var hm1evcomp = document.getElementById('hm1evcomp-'+codigo_inscripcion);
	var hdm1 = document.getElementById('hdm1-'+codigo_inscripcion);
	var hdm2 = document.getElementById('hdm2-'+codigo_inscripcion);

	hm1p1.value = m1p1.value;
	hm1p2.value = m1p2.value;
	hm1p3.value = m1p3.value;
	hm1p4.value = m1p4.value;
	hm2p1.value = m2p1.value;
	hm2p2.value = m2p2.value;
	hm2p3.value = m2p3.value;
	hm2p4.value = m2p4.value;

	pm1.value = ((m1p1.value * 1) + (m1p2.value * 1) + (m1p3.value * 1) + (m1p4.value * 1))/4;
	pm1.value = trunc(pm1.value, 2);
	hpm1.value = pm1.value;

	pm2.value = ((m2p1.value * 1) + (m2p2.value * 1) + (m2p3.value * 1) + (m2p4.value * 1))/4;
	pm2.value = trunc(pm2.value, 2);
	hpm2.value = pm2.value;

	nf.value = ((pm1.value * 1) + (pm2.value * 1))/2;
	nf.value = trunc(nf.value, 2);
	hnf.value = nf.value;

	if(nf.value >= 7){
		nf.style.color = 'blue';
	}else{
		nf.style.color = 'red';
	}

	if(nf.value < 7){
		em.value = 'NP';
		hem.value = em.value;
		em.style.backgroundColor = '#ffb3b3';
	}else if(nf.value >= 7){
		em.value = 'P';
		hem.value = em.value;
		em.style.backgroundColor = '#b3ffcc';
	}else{
		em.value = 'D';
		hem.value = em.value;
		em.style.backgroundColor = 'orange';
	}
}

function trunc (x, posiciones = 0) {
  var s = x.toString()
  var l = s.length
  var decimalLength = s.indexOf('.') + 1
  var numStr = s.substr(0, decimalLength + posiciones)
  return Number(numStr)
}

function marcar_desercion(modulo, codigo_inscripcion, elemento){
	var m1p1 = document.getElementById('m1p1-'+codigo_inscripcion);
	var m1p2 = document.getElementById('m1p2-'+codigo_inscripcion);
	var m1p3 = document.getElementById('m1p3-'+codigo_inscripcion);
	var m1p4 = document.getElementById('m1p4-'+codigo_inscripcion);
	var m2p1 = document.getElementById('m2p1-'+codigo_inscripcion);
	var m2p2 = document.getElementById('m2p2-'+codigo_inscripcion);
	var m2p3 = document.getElementById('m2p3-'+codigo_inscripcion);
	var m2p4 = document.getElementById('m2p4-'+codigo_inscripcion);
	var m1evcomp = document.getElementById('m1evcomp-'+codigo_inscripcion);
	var m2evcomp = document.getElementById('m2evcomp-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);
	var dm2 = document.getElementById('dm2-'+codigo_inscripcion);
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var hnf = document.getElementById('hnf-'+codigo_inscripcion);

	if(modulo==1 && elemento.checked==true){
		nf.value = trunc(0,2);
		m1p1.disabled = true;
		m1p2.disabled = true;
		m1p3.disabled = true;
		m1p4.disabled = true;
		m1evcomp.disabled = true;
		em.value = 'D';
		em.style.backgroundColor = 'orange';
		hem.value = em.value;
		m2p1.disabled = true;
		m2p2.disabled = true;
		m2p3.disabled = true;
		m2p4.disabled = true;		
		m2evcomp.disabled = true;
		dm2.disabled = true;
		nf.value = 0;
		hnf.value = 0;
	}else if(modulo==2 && elemento.checked==true){
		m2p1.disabled = true;
		m2p2.disabled = true;
		m2p3.disabled = true;
		m2p4.disabled = true;		
		m2evcomp.disabled = true;
		em.value = 'D';
		em.style.backgroundColor = 'orange';
		hem.value = em.value;
		nf.value = 0;
		hnf.value = 0;
		calcular_nota_final(codigo_inscripcion);
	}else if(modulo==1 && elemento.checked==false){
		m1p1.disabled = false;
		m1p2.disabled = false;
		m1p3.disabled = false;
		m1p4.disabled = false;
		m1evcomp.disabled = false;
		m2p1.disabled = false;
		m2p2.disabled = false;
		m2p3.disabled = false;
		m2p4.disabled = false;		
		m2evcomp.disabled = false;
		em.style.color = 'black';
		hem.value = em.value;
		dm2.disabled = false;
		dm2.checked = false;
		calcular_nota_final(codigo_inscripcion);
	}else if(modulo==2 && elemento.checked==false){
		m2p1.disabled = false;
		m2p2.disabled = false;
		m2p3.disabled = false;
		m2p4.disabled = false;		
		m2evcomp.disabled = false;
		em.style.color = 'black';
		hem.value = em.value;
		calcular_nota_final(codigo_inscripcion);
	}
}

function guardar(event){
	event.preventDefault(); 
	var contadorComportamiento = 0;
	var contadorNotaFinal = 0;

	var combos = document.getElementsByTagName('select');

	for(var i = 0; i < combos.length; i++){
		if(combos[i].disabled==false && combos[i].value=='-1'){
			contadorComportamiento++;
		}
	}

	var notasFinales = document.getElementsByClassName('nota_fin');

	for(var i = 0; i < notasFinales.length; i++){
		if(notasFinales[i].value=='' || notasFinales[i].value==null){
			contadorNotaFinal++;
		}
	}

	if(contadorComportamiento > 0){
		swal('Faltan Datos','Debe seleccionar la Evaluación de Comportamiento de los estudiantes','error');
	}else if(contadorNotaFinal > 0){
		swal('Faltan Datos','Debe ingresar las calificaciones de los estudiantes','error');
	}else{
		swal('Calificaciones Alfabetización','Registro de calificaciones exitoso','success');
		setTimeout(document.getElementById('frm_guardar_alfa').submit(),4000);
	}
}