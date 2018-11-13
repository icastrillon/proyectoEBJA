function trunc (x, posiciones = 0) {
  var s = x.toString()
  var l = s.length
  var decimalLength = s.indexOf('.') + 1
  var numStr = s.substr(0, decimalLength + posiciones)
  return Number(numStr)
}

function calcular_promedio(codigo_inscripcion){
	var p1 = document.getElementById('p1-'+codigo_inscripcion);
	var p2 = document.getElementById('p2-'+codigo_inscripcion);
	var pm = document.getElementById('pm-'+codigo_inscripcion);
	var hpm = document.getElementById('hpm-'+codigo_inscripcion);
	var pmg = document.getElementById('pmg-'+codigo_inscripcion);
	var ex = document.getElementById('ex-'+codigo_inscripcion);
	var exs = document.getElementById('exs-'+codigo_inscripcion);
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var hnf = document.getElementById('hnf-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);

	if(isNaN(p1.value)){
		p1.value = 0.00;
		return;
	}else if(p1.value * 1 > 10 || p1.value * 1 < 0){
		p1.value = 0.00;
		return;
	}

	if(isNaN(p2.value)){
		p2.value = 0.00;
		return;
	}else if(p2.value * 1 > 10 || p2.value * 1 < 0){
		p2.value = 0.00;
		return;
	}

	if(isNaN(ex.value)){
		ex.value = 0.00;
		return;
	}else if(ex.value * 1 > 10 || ex.value * 1 < 0){
		ex.value = 0.00;
		return;
	}

	pm.value = trunc(((p1.value * 1) + (p2.value * 1))/2,2);

	if(pm.value < 7){
		pm.style.color = 'red';
	}else{
		pm.style.color = 'black';
	}

	hpm.value = pm.value;
	pmg.value = trunc(pm.value * 0.80, 2);

	exs.value = trunc(ex.value * 0.20, 2);

	nf.value = trunc((pmg.value * 1) + (exs.value * 1), 2);
	hnf.value = nf.value;

	if(nf.value * 1 < 7){
		nf.style.color = 'red';
		em.value = 'NP';
		em.style.backgroundColor = '#ffb3b3';
		em.style.color = 'black';
	}else{
		nf.style.color = 'blue';
		em.value = 'P';
		em.style.backgroundColor = '#b3ffcc';
		em.style.color = 'black';
	}

	hem.value = em.value;
}

function marcar_desercion(codigo_inscripcion, elemento){
	var p1 = document.getElementById('p1-'+codigo_inscripcion);
	var p2 = document.getElementById('p2-'+codigo_inscripcion);
	var ex = document.getElementById('ex-'+codigo_inscripcion);
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);
	var evcomp = document.getElementById('evcomp-'+codigo_inscripcion);

	if(elemento.checked==true){
		p1.disabled = true;
		p2.disabled = true;
		ex.disabled = true;
		evcomp.disabled = true;
		nf.value = trunc(0,2);
		em.style.backgroundColor = 'orange';
		em.value = 'D';
	}else{
		p1.disabled = false;
		p2.disabled = false;
		ex.disabled = false;
		evcomp.disabled = false;
		calcular_promedio(codigo_inscripcion);
	}
	hem.value = em.value;
}

function guardarPost(event){
	event.preventDefault(); 
	var contadorComportamiento = 0;

	var combos = document.getElementsByTagName('select');

	for(var i = 0; i < combos.length; i++){
		if(combos[i].disabled==false && combos[i].value=='-1'){
			contadorComportamiento++;
		}
	}

	if(contadorComportamiento > 0){
		swal('Faltan Datos','Debe seleccionar la Evaluación de Comportamiento de los estudiantes','error');
	}else{
		swal('Calificaciones Post-Alfabetización','Registro de calificaciones exitoso','success');
		setTimeout(document.getElementById('frm_guardar_post').submit(),4000);
	}
}