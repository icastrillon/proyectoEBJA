<?php
echo "<style>body{font-size:10px;}</style>";
echo "<table style='width:80%;'>";
echo "<tr>";
echo "<td>";
echo "<img src='images/mineduc.png' style='width: 150px; height: 70px;'>";
echo "</td>";
echo "<td><center>";
echo "<p style='font-size:12px;margin-top:0;margin-bottom:0;'>SUBSECRETARIA DE EDUCACIÓN ESPECIALIZADA E INCLUSIVA</p>";
echo "<p style='font-size:12px;margin-top:0;margin-bottom:0;'>DIRECCIÓN NACIONAL DE EDUCACIÓN PARA PERSONAS CON ESCOLARIDAD INCONCLUSA</p>";
echo "<p style='font-size:12px;margin-top:0;margin-bottom:0;'>FORMATO DE REGISTRO DE CALIFICACIONES PARA BÁSICA MEDIA (POST-ALFABETIZACIÓN)</p>";
echo "</center></td>";
echo "</tr>";
echo "</table>";

echo "<table>";
echo "<tr>";
echo "<td><label style='font-weight: bold;'>CÓGIGO AMIE:</label></td>";
echo "<td><label>$ie->amie</label></td>";
echo "<td><label style='font-weight: bold;'>INSTITUCIÓN EDUCATIVA:</label></td>";
echo "<td><label>$ie->institucion</label></td>";
echo "<td><label style='font-weight: bold;'>DISTRITO EDUCATIVO:</label></td>";
echo "<td><label>$ie->distrito</label></td>";
echo "</tr>";
echo "<tr>";
echo "<td><label style='font-weight: bold;'>PERIODO LECTIVO:</label></td>";
echo "<td><label>$oferta->periodo</label></td>";
echo "<td><label style='font-weight: bold;'>OFERTA EDUCATIVA:</label></td>";
echo "<td><label>".strtoupper($oferta->nombre)."</label></td>";
echo "<td><label style='font-weight: bold;'>AÑO(S) EDUCATIVO(S):</label></td>";
if($oferta->id == 3){
	echo "<td><label>3 EGB - 4 EGB</label></td>";
}else if($oferta->id == 4){
	echo "<td><label>5 EGB - 6 EGB</label></td>";
}else if($oferta->id == 11){
	echo "<td><label>5 EGB - 6 EGB</label></td>";
}else{
	echo "<td><label>7 EGB</label></td>";
}
echo "</tr>";
echo "<tr>";
echo "<td><label style='font-weight: bold; font-size: 12px;'>ASIGNATURA:</label></td>";
echo "<td>".strtoupper($asignatura->nombre)."</td>";
echo "<td colspan='4'></td>";
echo "</tr>";
echo "</table>";

echo "<hr style='margin-bottom: 0;margin-top: 0;border: 1px solid silver'>";
echo "<center>";
echo "<label style='font-weight: bold;'>ESTADOS DE LOS ESTUDIANTES: </label>";
echo "<label><em>P</em> = PROMOVIDO</label>";
echo "<label><em>; NP</em> = NO PROMOVIDO</label>";
echo "<label><em>; D</em> = DESERTADO</label>";
echo "<hr style='margin-bottom: 0;margin-top: 0;border: 1px solid silver'>";
echo "<label style='font-weight: bold;'>EVALUACIÓN COMPORTAMIENTO: </label>";
echo "<label><em>A</em> = Muy Satisfactorio</label>";
echo "<label><em>; B</em> = Satisfactorio</label>";
echo "<label><em>; C</em> = Poco Satisfactorio</label>";
echo "<label><em>; D</em> = Mejorable</label>";
echo "<label><em>; E</em> = Insatisfactorio</label>";
echo "</center>";

echo "<table style='border-top: 1px solid silver;border-left: 1px solid silver;border-bottom: 1px solid silver;'>";
echo "<tr>";
echo "<th>No</th>";
echo "<th>ESTUDIANTE</th>";
echo "<th>IDENTIFICACIÓN</th>";
echo "<th>DESERTADO</th>";
echo "<th>Parcial 1</th>";
echo "<th>Parcial 2</th>";
echo "<th>Promedio General 10/10</th>";
echo "<th>Promedio Global 80%</th>";
echo "<th>Examen 10/10</th>";
echo "<th>Examen Final 20%</th>";
echo "<th>EVALUACIÓN COMPORTAMIENTO</th>";
echo "<th>PROMEDIO FINAL</th>";
echo "<th>ESTADO</th>";
echo "</tr>";
if (isset($estudiantes)){
	foreach ($estudiantes as $key => $mat){
		$loop = $key + 1;
		echo "<tr>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$loop</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;'>$mat->nombres_aspirantes</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;'>$mat->cedula_identidad</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->desertado</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->parcial_1</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->parcial_2</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->promedio_parciales</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>".bcdiv($mat->promedio_parciales * 0.80,'1',2)."</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->examen</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>".bcdiv($mat->examen * 0.20,'1',2)."</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->comportamiento</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->nota_final</td>";
			echo "<td style='border-right: 1px solid silver;border-top: 1px solid silver;text-align:center;'>$mat->estado</td>";
		echo "</tr>";
	}
}
echo "</table>";