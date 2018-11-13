function marcar_sin_razon_desercion(element){
	if(element.value=='-1'){
		element.style.borderColor = 'red';
	}else{
		element.style.borderColor = 'inherit';
	}
	marcar_todos();
}

function marcar_todos(){
	var contador = 0;
	var razones = document.getElementsByTagName('select');
	for(var i = 0; i < razones.length; i++){
		if(razones[i].value=='-1'){
			razones[i].style.borderColor = 'red';
			contador++;
		}else{
			razones[i].style.borderColor = 'inherit';
		}
	}
	var btn = document.getElementById('btn_razones');
	if(contador > 0){
		btn.disabled = true;
	}else{
		btn.disabled = false;
	}
}