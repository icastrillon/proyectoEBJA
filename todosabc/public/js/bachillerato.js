function trunc (x, posiciones = 0) {
  var s = x.toString()
  var l = s.length
  var decimalLength = s.indexOf('.') + 1
  var numStr = s.substr(0, decimalLength + posiciones)
  return Number(numStr)
}

function desertar(codigo_inscripcion, elemento){
	var q1p1 = document.getElementById('q1p1-'+codigo_inscripcion);
	var q1p2 = document.getElementById('q1p2-'+codigo_inscripcion);
	var ex1 = document.getElementById('ex1-'+codigo_inscripcion);
	var compq1 = document.getElementById('compq1-'+codigo_inscripcion);
	var q2p1 = document.getElementById('q2p1-'+codigo_inscripcion);
	var q2p2 = document.getElementById('q2p2-'+codigo_inscripcion);
	var ex2 = document.getElementById('ex2-'+codigo_inscripcion);
	var compq2 = document.getElementById('compq2-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);
	var sup = document.getElementById('sup-'+codigo_inscripcion);
	var grac = document.getElementById('grac-'+codigo_inscripcion);
	var rem = document.getElementById('rem-'+codigo_inscripcion);

	if(elemento.checked==true){
		q1p1.disabled = true;
		q1p2.disabled = true;
		ex1.disabled = true;
		compq1.disabled = true;
		q2p1.disabled = true;
		q2p2.disabled = true;
		ex2.disabled = true;
		compq2.disabled = true;
		sup.disabled = true;
		grac.disabled = true;
		rem.disabled = true;
		em.value = 'D';
		hem.value = em.value;
		em.style.backgroundColor = 'orange';
	}else{
		q1p1.disabled = false;
		q1p2.disabled = false;
		ex1.disabled = false;
		compq1.disabled = false;
		q2p1.disabled = false;
		q2p2.disabled = false;
		ex2.disabled = false;
		compq2.disabled = false;
		calcular_pm_parciales_q1(codigo_inscripcion);
		calcular_nota20_q1(codigo_inscripcion);
		calcular_pm_parciales_q2(codigo_inscripcion);
		calcular_nota20_q2(codigo_inscripcion);
	}
}

function calcular_pm_parciales_q1(codigo_inscripcion){
	var q1p1 = document.getElementById('q1p1-'+codigo_inscripcion);
	var q1p2 = document.getElementById('q1p2-'+codigo_inscripcion);
	var q1pmpar = document.getElementById('q1pmpar-'+codigo_inscripcion);
	var hq1pmpar = document.getElementById('hq1pmpar-'+codigo_inscripcion);
	var q1pmgpar = document.getElementById('q1pmgpar-'+codigo_inscripcion);


	if(isNaN(q1p1.value)){
		q1p1.value = 0.00;
		return;
	}else if(q1p1.value * 1 > 10 || q1p1.value * 1 < 0){
		q1p1.value = 0.00;
		return;
	}

	if(isNaN(q1p2.value)){
		q1p2.value = 0.00;
		return;
	}else if(q1p2.value * 1 > 10 || q1p2.value * 1 < 0){
		q1p2.value = 0.00;
		return;
	}

	var pm = (q1p1.value * 1 + q1p2.value * 1)/2;
	q1pmpar.value = trunc(pm, 2);
	hq1pmpar.value = trunc(pm, 2);
	q1pmgpar.value = trunc(pm * 0.80, 2);
	calcular_final_q1(codigo_inscripcion);
	calcular_nota_final(codigo_inscripcion);
	deshabilitar_extras(codigo_inscripcion);
}

function calcular_nota20_q1(codigo_inscripcion){
	var ex1 = document.getElementById('ex1-'+codigo_inscripcion);
	var exs1 = document.getElementById('exs1-'+codigo_inscripcion);

	if(isNaN(ex1.value)){
		ex1.value = 0.00;
		return;
	}else if(ex1.value * 1 > 10 || ex1.value * 1 < 0){
		ex1.value = 0.00;
		return;
	}

	exs1.value = trunc(ex1.value * 1 * 0.20, 2);
	calcular_final_q1(codigo_inscripcion);
	calcular_nota_final(codigo_inscripcion);
	deshabilitar_extras(codigo_inscripcion);
}

function calcular_pm_parciales_q2(codigo_inscripcion){
	var q2p1 = document.getElementById('q2p1-'+codigo_inscripcion);
	var q2p2 = document.getElementById('q2p2-'+codigo_inscripcion);
	var q2pmpar = document.getElementById('q2pmpar-'+codigo_inscripcion);
	var hq2pmpar = document.getElementById('hq2pmpar-'+codigo_inscripcion);
	var q2pmgpar = document.getElementById('q2pmgpar-'+codigo_inscripcion);

	if(isNaN(q2p1.value)){
		q2p1.value = 0.00;
		return;
	}else if(q2p1.value * 1 > 10 || q2p1.value * 1 < 0){
		q2p1.value = 0.00;
		return;
	}

	if(isNaN(q2p2.value)){
		q2p2.value = 0.00;
		return;
	}else if(q2p2.value * 1 > 10 || q2p2.value * 1 < 0){
		q2p2.value = 0.00;
		return;
	}

	var pm = (q2p1.value * 1 + q2p2.value * 1)/2;
	q2pmpar.value = trunc(pm, 2);
	hq2pmpar.value = trunc(pm, 2);
	q2pmgpar.value = trunc(pm * 0.80, 2);
	calcular_final_q2(codigo_inscripcion);
	calcular_nota_final(codigo_inscripcion);
	deshabilitar_extras(codigo_inscripcion);
}

function calcular_nota20_q2(codigo_inscripcion){
	var ex2 = document.getElementById('ex2-'+codigo_inscripcion);
	var exs2 = document.getElementById('exs2-'+codigo_inscripcion);

	if(isNaN(ex2.value)){
		ex2.value = 0.00;
		return;
	}else if(ex2.value * 1 > 10 || ex2.value * 1 < 0){
		ex2.value = 0.00;
		return;
	}

	exs2.value = trunc(ex2.value * 1 * 0.20, 2);
	calcular_final_q2(codigo_inscripcion);
	calcular_nota_final(codigo_inscripcion);
	deshabilitar_extras(codigo_inscripcion);
}

function calcular_final_q1(codigo_inscripcion){
	var q1pmgpar = document.getElementById('q1pmgpar-'+codigo_inscripcion).value * 1;
	var exs1 = document.getElementById('exs1-'+codigo_inscripcion).value * 1;
	var pmfq1 = document.getElementById('pmfq1-'+codigo_inscripcion);
	var hpmfq1 = document.getElementById('hpmfq1-'+codigo_inscripcion);
	pmfq1.value = trunc(q1pmgpar + exs1, 2);
	hpmfq1.value = pmfq1.value;
}

function calcular_final_q2(codigo_inscripcion){
	var q2pmgpar = document.getElementById('q2pmgpar-'+codigo_inscripcion).value * 1;
	var exs2 = document.getElementById('exs2-'+codigo_inscripcion).value * 1;
	var pmfq2 = document.getElementById('pmfq2-'+codigo_inscripcion);
	var hpmfq2 = document.getElementById('hpmfq2-'+codigo_inscripcion);
	pmfq2.value = trunc(q2pmgpar + exs2, 2);
	hpmfq2.value = pmfq2.value;
}

function calcular_nota_final(codigo_inscripcion){
	var pmfq1 = document.getElementById('pmfq1-'+codigo_inscripcion).value * 1;
	var pmfq2 = document.getElementById('pmfq2-'+codigo_inscripcion).value * 1;
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var hnf = document.getElementById('hnf-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);

	var pmf = (pmfq1 + pmfq2) / 2;
	nf.value = trunc(pmf, 2);
	hnf.value = nf.value;

	if(nf.value < 7){
		nf.style.color = 'red';
		em.value = 'NP';
		hem.value = em.value;
		em.style.backgroundColor = '#ffb3b3';
	}else{
		nf.style.color = 'blue';
		em.value = 'P';
		hem.value = em.value;
		em.style.backgroundColor = '#b3ffcc';
	}
}

function deshabilitar_extras(codigo_inscripcion){
	var nf = document.getElementById('nf-'+codigo_inscripcion).value * 1;
	var hsup = document.getElementById('hsup-'+codigo_inscripcion);
	var hrem = document.getElementById('hrem-'+codigo_inscripcion);
	var hgrac = document.getElementById('hgrac-'+codigo_inscripcion);
	var sup = document.getElementById('sup-'+codigo_inscripcion);
	var rem = document.getElementById('rem-'+codigo_inscripcion);
	var grac = document.getElementById('grac-'+codigo_inscripcion);

	if(nf >= 7){
		sup.disabled = true;
		rem.disabled = true;
		grac.disabled = true;
		sup.value = 0;
		rem.value = 0;
		grac.value = 0;
		hsup.value = 0;
		hrem.value = 0;
		hgrac.value = 0;
	}else if(nf >= 5 && nf < 7){
		sup.disabled = false;
		rem.disabled = true;
		grac.disabled = true;
		sup.value = 0;
		rem.value = 0;
		grac.value = 0;
		hsup.value = 0;
		hrem.value = 0;
		hgrac.value = 0;
	}else if (nf < 5){
		sup.disabled = true;
		rem.disabled = false;
		grac.disabled = true;
		sup.value = 0;
		rem.value = 0;
		grac.value = 0;
		hsup.value = 0;
		hrem.value = 0;
		hgrac.value = 0;
	}
}

function recalcular_nota_final(codigo_inscripcion, elemento){
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var hnf = document.getElementById('hnf-'+codigo_inscripcion);
	var hsup = document.getElementById('hsup-'+codigo_inscripcion);
	var hrem = document.getElementById('hrem-'+codigo_inscripcion);
	var hgrac = document.getElementById('hgrac-'+codigo_inscripcion);
	var sup = document.getElementById('sup-'+codigo_inscripcion);
	var rem = document.getElementById('rem-'+codigo_inscripcion);
	var grac = document.getElementById('grac-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);

	if(sup.value * 1 > 0 && rem.value * 1 == 0 && nf.value * 1 > 0 && nf.value * 1 < 5){
		sup.value = 0;
		hsup.value = sup.value;
		return;
	}

	if(isNaN(elemento.value)){
		elemento.value = 0.00;
		return;
	}else if(elemento.value * 1 > 10 || elemento.value * 1 < 0){
		elemento.value = 0.00;
		return;
	}

	if(sup.value * 1 >= 7){
		nf.value = trunc(7, 2);
		rem.value = 0;
		hrem.value = rem.value;
		grac.value = 0;
		hgrac.value = grac.value;
	}else if(sup.value * 1 > 0 && rem.value * 1 >= 7 && grac.value * 1 == 0){
		nf.value = trunc(7, 2);
	}else if(sup.value * 1 == 0 && rem.value * 1 >= 7 && grac.value * 1 == 0){
		nf.value = trunc(7, 2);
	}else if(sup.value * 1 == 0 && rem.value * 1 >= 7 && grac.value * 1 > 0){
		nf.value = trunc(7, 2);
		grac.value = 0;
		hgrac.value = grac.value;
	}else if(sup.value * 1 >= 0 && sup.value * 1 < 7 && rem.value * 1 > 0 && grac.value * 1 > 0){
		nf.value = trunc(sup.value * 1, 2);
		rem.value = 0;
		hrem.value = rem.value;
		grac.value = 0;
		hgrac.value = grac.value;
	}else if(sup.value * 1 > 0 && sup.value * 1 < 7 && rem.value * 1 == 0 && grac.value * 1 == 0){
		nf.value = trunc(sup.value * 1, 2);
		hsup.value = sup.value * 1;
	}else if(sup.value * 1 > 0 && rem.value * 1 > 0 && rem.value * 1 < 7 && grac.value * 1 == 0){
		nf.value = trunc(rem.value * 1, 2);
		hrem.value = rem.value * 1;
	}else if(sup.value * 1 == 0 && rem.value * 1 > 0 && grac.value * 1 == 0){
		nf.value = trunc(rem.value * 1, 2);
		hrem.value = rem.value * 1;
	}

	hnf.value = nf.value;
	
	if(nf.value < 7){
		nf.style.color = 'red';
		em.value = 'NP';
		hem.value = em.value;
		em.style.backgroundColor = '#ffb3b3';
	}else{
		nf.style.color = 'blue';
		em.value = 'P';
		hem.value = em.value;
		em.style.backgroundColor = '#b3ffcc';
	}
}

function habilitar_remedial(codigo_inscripcion, elemento){
	var rem = document.getElementById('rem-'+codigo_inscripcion);
	if(elemento.value * 1 < 7){
		rem.disabled = false;
	}else{
		rem.disabled = true;
	}
}

function habilitar_gracia(codigo_inscripcion, elemento){
	var grac = document.getElementById('grac-'+codigo_inscripcion);
	if(elemento.value * 1 < 7){
		grac.disabled = false;
	}else{
		grac.disabled = true;
	}
}

function recalcular_nota_final_con_ex_gracia(codigo_inscripcion, elemento){
	var nf = document.getElementById('nf-'+codigo_inscripcion);
	var hnf = document.getElementById('hnf-'+codigo_inscripcion);
	var rem = document.getElementById('rem-'+codigo_inscripcion);
	var em = document.getElementById('em-'+codigo_inscripcion);
	var hem = document.getElementById('hem-'+codigo_inscripcion);

	if(isNaN(elemento.value)){
		elemento.value = 0.00;
		return;
	}else if(elemento.value * 1 > 10 || elemento.value * 1 < 0){
		elemento.value = 0.00;
		return;
	}else if(rem.value * 1 == 0 || rem.value * 1 >= 7){
		elemento.value = 0.00;
		return;
	}

	if((elemento.value * 1) >= 7){
		nf.value = trunc(7, 2);
	}else if(elemento.value * 1 > 0 && elemento.value * 1 < 7){
		nf.value = trunc(elemento.value * 1, 2);
	}

	hnf.value = nf.value;

	if(nf.value < 7){
		nf.style.color = 'red';
		em.value = 'NP';
		hem.value = em.value;
		em.style.backgroundColor = '#ffb3b3';
	}else{
		nf.style.color = 'blue';
		em.value = 'P';
		hem.value = em.value;
		em.style.backgroundColor = '#b3ffcc';
	}
}

function guardar_bachillerato(event){
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
		swal('Calificaciones Bachillerato Intensivo','Registro de calificaciones exitoso','success');
		setTimeout(document.getElementById('frm_guardar_bachillerato').submit(),4000);
	}
}

function marcar_fase(codigo_inscripcion, elemento){
	if(elemento.checked==true){
		elemento.value = true;
	}else{
		elemento.value = false;
	}

	var f1 = document.getElementById('f1-'+codigo_inscripcion);
	var f2 = document.getElementById('f2-'+codigo_inscripcion);
	var f3 = document.getElementById('f3-'+codigo_inscripcion);

	if(f1.checked==true && f3.checked==true){
		f2.value = true;
		f2.checked = 'checked';
	}
}

function marcar_todos_fase1(elemento){
	var cajas = document.getElementsByTagName('input');

	for(var i = 0; i < cajas.length; i++){
		if(cajas[i].type == 'checkbox'){
			if(cajas[i].name.includes('f1-')){
				cajas[i].checked = elemento.checked;
			}
		}
	}
}

function marcar_todos_fase2(elemento){
	var cajas = document.getElementsByTagName('input');

	for(var i = 0; i < cajas.length; i++){
		if(cajas[i].type == 'checkbox'){
			if(cajas[i].name.includes('f2-')){
				cajas[i].checked = elemento.checked;
			}
		}
	}
}

function marcar_todos_fase3(elemento){
	var cajas = document.getElementsByTagName('input');

	for(var i = 0; i < cajas.length; i++){
		if(cajas[i].type == 'checkbox'){
			if(cajas[i].name.includes('f3-')){
				cajas[i].checked = elemento.checked;
			}
		}
	}
}

function guardar_fases(event){
	event.preventDefault();
	var contadorTrue = 0;
	var contador = 0;
	var cajas = document.getElementsByTagName('input');

	for(var i = 0; i < cajas.length; i++){
		if(cajas[i].type == 'checkbox'){
			contador++;
			if(cajas[i].checked==true){
				contadorTrue++;
			}
		}
	}

	if(contadorTrue < 1){
		swal('Fases Educativas','Debe marcar las fases educativas','error');
	}else{
		swal('Fases Educativas','Registro de fases exitoso','success');
		setTimeout(document.getElementById('frm_guardar_fases').submit(),4000);
	}
}