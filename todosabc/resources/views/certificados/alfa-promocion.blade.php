<?php
if($matriculado->ultimo_anio_aprobado!='2 EGB'){
echo "<div style='page-break-after:always;'>";
echo "<p><center><img src='images/mineduc-logos.png' style='width: 300px; height: 70px;'></center></p>";
echo "<span><center>$barra</center></span>";
echo "<span style='font-size:8px;'><center>EBJA-$codigo</center></span>";
echo "<p>$distrito</p><center>";
echo "<p style='margin-top: 0;margin-bottom: 0;font-size: 24px; font-weight: bold;'>CERTIFICADO DE PROMOCIÓN</p>";
echo "<p style='margin-top: 0;margin-bottom: 0;font-size: 14px;'>PERIODO EDUCATIVO: $oferta->periodo</p></center>";
echo "<center><p style='font-size: 18px; font-weight: bold;'>$ie_nombre</p></center>";
echo "<p style='text-align: justify;margin-top: 0;margin-bottom: 0'>De conformidad con lo prescrito en el Art. 197 del Reglamento General a la Ley Orgánica ";
echo "de Educación Intercultural y demás normas vigentes, certifica que el/la estudiante:</p><br>";
echo "<p style='margin-top: 0;margin-bottom: 0;text-align: center;font-size: 18px; font-weight: bold; font-style: italic;'>$matriculado->nombres_aspirantes</p><br>";
echo "<p style='text-align: justify;margin-top: 0;'><span style='font-weight:bold;'>del SEGUNDO GRADO DE EDUCACIÓN GENERAL BÁSICA,</span> obtuvo la siguiente calificación durante el presente periodo educativo.</p>";
echo "<br>";
echo "<center>";

//tabla de calificaciones
echo "<table style='border-collapse: collapse;font-size: 12px;width: 700px;'>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td colspan='3' style='border: black 1px solid;text-align:center;font-weight: bold;'>CALIFICACIÓN</td>";
echo "</tr>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 120px;'>CUANTITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 300px;'>CUALITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;'>EVALUACIÓN DE COMPORTAMIENTO</td>";
echo "</tr>";

//iterar calificaciones
if(isset($calificaciones)){
	foreach ($calificaciones as $i => $cal) {
		echo "<tr>";
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirNota($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;font-size:10px;'>".escribirEquivalencia($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;text-align:center;'>".recuperarComportamiento($calificaciones[0]->modulo_2_comportamiento)."</td>";
		echo "</tr>";
	}
}

echo "</table><br>";
echo "</center>";

echo "<p style='text-align: justify;'>Por lo tanto, es promovido/a a: ";
echo "TERCER GRADO DE EDUCACIÓN GENERAL BÁSICA. Para certificar suscriben en unidad ";
echo "de acto el/la Director/a – Rector/a con el/la Secretario/a General del Plantel o ";
echo "Profesor/a de Grado.</p>";
echo "<br><br><br><br><br><br><br><br><br><br>";
echo "<p style='width: 400px; float: left; margin-left: 50px;'>f) DIRECTOR/A o RECTOR/A</div><div style='width: 150px; float: right; margin-right: 50px;'>f) SECRETARIO/A</p>";
echo "</div>";
}

//3ro
echo "<div style='page-break-after:always;'>";
echo "<p><center><img src='images/mineduc-logos.png' style='width: 300px; height: 70px;'></center></p>";
echo "<span><center>$barra</center></span>";
echo "<span style='font-size:8px;'><center>EBJA-$codigo</center></span>";
echo "<p>$distrito</p><center>";
echo "<p style='margin-top: 0;margin-bottom: 0;font-size: 24px; font-weight: bold;'>CERTIFICADO DE PROMOCIÓN</p>";
echo "<p style='margin-top: 0;margin-bottom: 0;font-size: 14px;'>PERIODO EDUCATIVO: $oferta->periodo</p></center>";
echo "<center><p style='font-size: 18px; font-weight: bold;'>$ie_nombre</p></center>";
echo "<p style='text-align: justify;margin-top: 0;margin-bottom: 0'>De conformidad con lo prescrito en el Art. 197 del Reglamento General a la Ley Orgánica ";
echo "de Educación Intercultural y demás normas vigentes, certifica que el/la estudiante:</p><br>";
echo "<p style='margin-top: 0;margin-bottom: 0;text-align: center;font-size: 18px; font-weight: bold; font-style: italic;'>$matriculado->nombres_aspirantes</p><br>";
echo "<p style='text-align: justify;margin-top: 0;'><span style='font-weight:bold;'>del TERCER GRADO DE EDUCACIÓN GENERAL BÁSICA,</span> obtuvo la siguiente calificación durante el presente periodo educativo.</p>";
echo "<br>";
echo "<center>";

//tabla de calificaciones
echo "<table style='border-collapse: collapse;font-size: 12px;width: 700px;'>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td colspan='3' style='border: black 1px solid;text-align:center;font-weight: bold;'>CALIFICACIÓN</td>";
echo "</tr>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 120px;'>CUANTITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 300px;'>CUALITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;'>EVALUACIÓN DE COMPORTAMIENTO</td>";
echo "</tr>";

//iterar calificaciones
if(isset($calificaciones)){
	foreach ($calificaciones as $i => $cal) {
		echo "<tr>";
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirNota($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;font-size:10px;'>".escribirEquivalencia($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;text-align:center;'>".recuperarComportamiento($calificaciones[0]->modulo_2_comportamiento)."</td>";
		echo "</tr>";
	}
}

echo "</table><br>";
echo "</center>";

echo "<p style='text-align: justify;'>Por lo tanto, es promovido/a a: ";
echo "CUARTO GRADO DE EDUCACIÓN GENERAL BÁSICA. Para certificar suscriben en unidad ";
echo "de acto el/la Director/a – Rector/a con el/la Secretario/a General del Plantel o ";
echo "Profesor/a de Grado.</p>";
echo "<br><br><br><br><br><br><br><br><br><br>";
echo "<p style='width: 400px; float: left; margin-left: 50px;'>f) DIRECTOR/A o RECTOR/A</div><div style='width: 150px; float: right; margin-right: 50px;'>f) SECRETARIO/A</p>";
echo "</div>";

function escribirEquivalencia($valor){
	if($valor >= 9 and $valor <= 10){
		return 'Domina los aprendizajes requeridos (D.A.R)';
	}else if($valor >= 7 and $valor <= 8.99){
		return 'Alcanza los aprendizajes requeridos (A.A.R)';
	}else if($valor >= 4.01 and $valor <= 6.99){
		return 'Está próximo a alcanzar los aprendizajes requeridos (E.P.A.A.R)';
	}else{
		return 'No alcanza los aprendizajes requeridos (N.A.A.R)';
	}
}

function escribirNota($valor){
	if(strlen($valor)==1 or strlen($valor)==2){
		$valor = $valor.'.00';
	}else if(strlen($valor)==3){
		$valor = $valor.'0';
	}
	return $valor;
}

function recuperarComportamiento($valor){
	$comportamiento = '';
	if($valor=='A'){
		$comportamiento = 'Muy Satisfactorio ('.$valor.')';
	}else if($valor=='B'){
		$comportamiento = 'Satisfactorio ('.$valor.')';
	}else if($valor=='C'){
		$comportamiento = 'Poco satisfactorio ('.$valor.')';
	}else if($valor=='D'){
		$comportamiento = 'Mejorable ('.$valor.')';
	}else{
		$comportamiento = 'Insatisfactorio ('.$valor.')';
	}
	return $comportamiento;
}