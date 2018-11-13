<?php
echo "<html>";
echo "<head>";
echo "<style>";
echo "body{font-size:12px;}";
echo "</style>";
echo "</head>";
//8 vo.
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
echo "<p style='text-align: justify;margin-top: 0;'><span style='font-weight:bold;'>del OCTAVO GRADO DE EDUCACIÓN GENERAL BÁSICA,</span> obtuvo las siguientes calificaciones durante el presente periodo educativo.</p>";
echo "<br>";
echo "<center>";

//tabla de calificaciones
echo "<table style='border-collapse: collapse;font-size: 12px;width: 700px;'>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td rowspan='2' style='border: black 1px solid;text-align:center;font-weight: bold;'>ASIGNATURAS</td>";
echo "<td colspan='2' style='border: black 1px solid;text-align:center;font-weight: bold;'>CALIFICACIONES</td>";
echo "</tr>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 120px;'>CUANTITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 300px;'>CUALITATIVA</td>";
echo "</tr>";

//iterar calificaciones
$total_materias = 1;
if(isset($calificaciones)){	
	if(count($calificaciones)>0){
		$total_materias = count($calificaciones);
	}
	$promedio_final = 0;
	foreach ($calificaciones as $i => $cal) {
		$promedio_final += $cal->nota_final;
		echo "<tr>";
		echo "<td style='border: black 1px solid;'>".strtoupper($cal->asignatura)."</td>";		
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirNota($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirEquivalencia($cal->nota_final)."</td>";
		echo "</tr>";
	}
	$promedio_final = $promedio_final / $total_materias;
}

echo "<tr style='background-color: #e6e6e6;'>";
echo "<td style='border: black 1px solid;font-weight: bold;'>PROMEDIO GENERAL</td>";
echo "<td colspan='2' style='border: black 1px solid;text-align:center;'>".escribirNota(bcdiv($promedio_final,1,2))."</td>";
echo "</tr>";
echo "<tr style='background-color: #e6e6e6;'>";
echo "<td style='border: black 1px solid;font-weight: bold;'>EVALUACIÓN DEL COMPORTAMIENTO</td>";
if(isset($calificaciones) and count($calificaciones)>0){
	echo "<td colspan='2' style='border: black 1px solid;text-align:center;'>".recuperarComportamiento($calificaciones[$total_materias-1]->comportamiento)."</td>";
}else{
	echo "<td colspan='2' style='border: black 1px solid;text-align:center;'></td>";
}
echo "</tr>";
echo "</table><br>";
echo "</center>";

echo "<p style='text-align: justify;'>Por lo tanto, es promovido/a al: NOVENO GRADO DE EDUCACIÓN GENERAL BÁSICA.";
echo " Para certificar suscriben en unidad de acto el/la Director/a – Rector/a ";
echo "con el/la Secretario/a General del Plantel o Profesor/a de Grado.</p>";
echo "<br><br><br><br><br><br><br><br>";
echo "<div style='width: 400px; float: left; margin-left: 50px;'>f) DIRECTOR/A o RECTOR/A</div><div style='width: 150px; float: right; margin-right: 50px;'>f) SECRETARIO/A</div>";
echo "</div>";

//9 no.
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
echo "<p style='text-align: justify;margin-top: 0;'><span style='font-weight:bold;'>del NOVENO GRADO DE EDUCACIÓN GENERAL BÁSICA,</span> obtuvo la siguiente calificación durante el presente periodo educativo.</p>";
echo "<br>";
echo "<center>";

//tabla de calificaciones
echo "<table style='border-collapse: collapse;font-size: 12px;width: 700px;'>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td rowspan='2' style='border: black 1px solid;text-align:center;font-weight: bold;'>ASIGNATURAS</td>";
echo "<td colspan='2' style='border: black 1px solid;text-align:center;font-weight: bold;'>CALIFICACIONES</td>";
echo "</tr>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 120px;'>CUANTITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 300px;'>CUALITATIVA</td>";
echo "</tr>";

//iterar calificaciones
$total_materias = 1;
if(isset($calificaciones)){	
	if(count($calificaciones)>0){
		$total_materias = count($calificaciones);
	}
	$promedio_final = 0;
	foreach ($calificaciones as $i => $cal) {
		$promedio_final += $cal->nota_final;
		echo "<tr>";
		echo "<td style='border: black 1px solid;'>".strtoupper($cal->asignatura)."</td>";		
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirNota($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirEquivalencia($cal->nota_final)."</td>";
		echo "</tr>";
	}
	$promedio_final = $promedio_final / $total_materias;
}

echo "<tr style='background-color: #e6e6e6;'>";
echo "<td style='border: black 1px solid;font-weight: bold;'>PROMEDIO GENERAL</td>";
echo "<td colspan='2' style='border: black 1px solid;text-align:center;'>".escribirNota(bcdiv($promedio_final,1,2))."</td>";
echo "</tr>";
echo "<tr style='background-color: #e6e6e6;'>";
echo "<td style='border: black 1px solid;font-weight: bold;'>EVALUACIÓN DEL COMPORTAMIENTO</td>";
if(isset($calificaciones) and count($calificaciones)>0){
	echo "<td colspan='2' style='border: black 1px solid;text-align:center;'>".recuperarComportamiento($calificaciones[$total_materias-1]->comportamiento)."</td>";
}else{
	echo "<td colspan='2' style='border: black 1px solid;text-align:center;'></td>";
}
echo "</tr>";
echo "</table><br>";
echo "</center>";

echo "<p style='text-align: justify;'>Por lo tanto, es promovido/a al: DÉCIMO GRADO DE EDUCACIÓN GENERAL BÁSICA.";
echo " Para certificar suscriben en unidad de acto el/la Director/a – Rector/a ";
echo "con el/la Secretario/a General del Plantel o Profesor/a de Grado.</p>";
echo "<br><br><br><br><br><br><br><br>";
echo "<div style='width: 400px; float: left; margin-left: 50px;'>f) DIRECTOR/A o RECTOR/A</div><div style='width: 150px; float: right; margin-right: 50px;'>f) SECRETARIO/A</div>";
echo "</div>";

//10 mo.
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
echo "<p style='text-align: justify;margin-top: 0;'><span style='font-weight:bold;'>del DÉCIMO GRADO DE EDUCACIÓN GENERAL BÁSICA,</span> obtuvo la siguiente calificación durante el presente periodo educativo.</p>";
echo "<br>";
echo "<center>";

//tabla de calificaciones
echo "<table style='border-collapse: collapse;font-size: 12px;width: 700px;'>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td rowspan='2' style='border: black 1px solid;text-align:center;font-weight: bold;'>ASIGNATURAS</td>";
echo "<td colspan='2' style='border: black 1px solid;text-align:center;font-weight: bold;'>CALIFICACIONES</td>";
echo "</tr>";
echo "<tr style='background-color: #b3b3b3;'>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 120px;'>CUANTITATIVA</td>";
echo "<td style='border: black 1px solid;text-align:center;font-weight: bold;width: 300px;'>CUALITATIVA</td>";
echo "</tr>";

//iterar calificaciones
$total_materias = 1;
if(isset($calificaciones)){	
	if(count($calificaciones)>0){
		$total_materias = count($calificaciones);
	}
	$promedio_final = 0;
	foreach ($calificaciones as $i => $cal) {
		$promedio_final += $cal->nota_final;
		echo "<tr>";
		echo "<td style='border: black 1px solid;'>".strtoupper($cal->asignatura)."</td>";		
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirNota($cal->nota_final)."</td>";
		echo "<td style='border: black 1px solid;text-align:center;'>".escribirEquivalencia($cal->nota_final)."</td>";
		echo "</tr>";
	}
	$promedio_final = $promedio_final / $total_materias;
}

echo "<tr style='background-color: #e6e6e6;'>";
echo "<td style='border: black 1px solid;font-weight: bold;'>PROMEDIO GENERAL</td>";
echo "<td colspan='2' style='border: black 1px solid;text-align:center;'>".escribirNota(bcdiv($promedio_final,1,2))."</td>";
echo "</tr>";
echo "<tr style='background-color: #e6e6e6;'>";
echo "<td style='border: black 1px solid;font-weight: bold;'>EVALUACIÓN DEL COMPORTAMIENTO</td>";
if(isset($calificaciones) and count($calificaciones)>0){
	echo "<td colspan='2' style='border: black 1px solid;text-align:center;'>".recuperarComportamiento($calificaciones[$total_materias-1]->comportamiento)."</td>";
}else{
	echo "<td colspan='2' style='border: black 1px solid;text-align:center;'></td>";
}
echo "</tr>";
echo "</table><br>";
echo "</center>";

echo "<p style='text-align: justify;'>Por lo tanto, es promovido/a al: PRIMER CURSO DE BACHILLERATO GENERAL UNIFICADO.";
echo " Para certificar suscriben en unidad de acto el/la Director/a – Rector/a ";
echo "con el/la Secretario/a General del Plantel o Profesor/a de Grado.</p>";
echo "<br><br><br><br><br><br><br><br>";
echo "<div style='width: 400px; float: left; margin-left: 50px;'>f) DIRECTOR/A o RECTOR/A</div><div style='width: 150px; float: right; margin-right: 50px;'>f) SECRETARIO/A</div>";
echo "</div>";

echo "</html>";

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