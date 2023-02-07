<?php

$id_sorteo = $_SESSION['deposito_mayor'];

$info_sorteo = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id =  '$id_sorteo' ");

while ($row2 = mysqli_fetch_array($info_sorteo)) {
	$num_sorteo = $row2['no_sorteo_may'];
	$cantidad = $row2['cantidad_numeros'];
	$fecha = $row2['fecha_sorteo'];
	$descripcion = $row2['descripcion_sorteo_may'];
}

////////////////////////////// guarfar configuracion //////////////////////////////////////

if (isset($_POST['guardar'])) {

	$id_oculto = $_POST['id_oculto'];
	$mezcla = $_POST['mezcla'];

	$sorteo_mayor = mysqli_query($conn, "SELECT * FROM sorteos_mayores WHERE id = '$id_oculto' ");

	while ($row = mysqli_fetch_array($sorteo_mayor)) {
		$cantidad = $row['cantidad_numeros'];
	}

	if ($mezcla == 200) {

		$num_rangos = $cantidad / $mezcla;
		$num_paquetes = $num_rangos / 5;

///////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////

// CRECION DE VECTORES SEGUN TERMINACION  ////////////////////////////////////////////////////////

		$i = 0;
		$a = 0;
		$b = 0;
		$c = 0;
		$d = 0;
		$e = 0;

		while ($i < $num_rangos) {

			$terminacion = substr($i, -1);

// Vector de terminaciones 0 y 5 //////////////////////////////////////////////////////////////////
			if ($terminacion == 0 || $terminacion == 5) {
				$rangos05[$a] = $i;
				$a++;
			}

// Vector de terminaciones 1 y 6 //////////////////////////////////////////////////////////////////

			if ($terminacion == 1 || $terminacion == 6) {
				$rangos16[$b] = $i;
				$b++;
			}

// Vector de terminaciones 2 y 7 //////////////////////////////////////////////////////////////////
			if ($terminacion == 2 || $terminacion == 7) {
				$rangos27[$c] = $i;
				$c++;
			}

// Vector de terminaciones 3 y 8 //////////////////////////////////////////////////////////////////
			if ($terminacion == 3 || $terminacion == 8) {
				$rangos38[$d] = $i;
				$d++;
			}

// Vector de terminaciones 4 y 9 //////////////////////////////////////////////////////////////////
			if ($terminacion == 4 || $terminacion == 9) {
				$rangos49[$e] = $i;
				$e++;
			}

			$i++;
		} // fin de creacion de vectores segun terminacion  ///////////////////////////////////////////////

		shuffle($rangos05);
		shuffle($rangos16);
		shuffle($rangos27);
		shuffle($rangos38);
		shuffle($rangos49);
/////////////////////////////////////////////////////////////////////////////////////////////////

		$i = 0;
		$j = 0;
		$a = 0;

		$validar_registro = mysqli_query($conn, "SELECT * FROM sorteos_mezclas WHERE id_sorteo = '$id_oculto' ");

		if (mysqli_num_rows($validar_registro) == 0) {

			while ($i < $num_paquetes) {

				mysqli_query($conn, "INSERT INTO sorteos_mezclas(num_mezcla, id_sorteo) VALUES ('$i','$id_oculto')");

				$rango = $rangos05[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos16[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos27[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos38[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos49[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$a++;
				$i++;
			}

			mysqli_query($conn, "UPDATE sorteos_mayores SET mezcla = '$mezcla', estado_sorteo = 'PENDIENTE DISTRIBUCION' WHERE id = '$id_oculto' ");

		} else {

			echo "<div class = 'alert alert-danger'> Este sorteo ya tiene mezclas asignadas</div>";

		}

	}

	if ($mezcla == 20) {

		$num_rangos = $cantidad / $mezcla;
		$num_paquetes = $num_rangos / 5;

///////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////

// CRECION DE VECTORES SEGUN TERMINACION  ////////////////////////////////////////////////////////

		$i = 0;
		$a = 0;
		$b = 0;
		$c = 0;
		$d = 0;
		$e = 0;

		while ($i < $num_rangos) {

			$terminacion = substr($i, -1);

// Vector de terminaciones 0 y 5 //////////////////////////////////////////////////////////////////
			if ($terminacion == 0 || $terminacion == 5) {
				$rangos05[$a] = $i;
				$a++;
			}

// Vector de terminaciones 1 y 6 //////////////////////////////////////////////////////////////////

			if ($terminacion == 1 || $terminacion == 6) {
				$rangos16[$b] = $i;
				$b++;
			}

// Vector de terminaciones 2 y 7 //////////////////////////////////////////////////////////////////
			if ($terminacion == 2 || $terminacion == 7) {
				$rangos27[$c] = $i;
				$c++;
			}

// Vector de terminaciones 3 y 8 //////////////////////////////////////////////////////////////////
			if ($terminacion == 3 || $terminacion == 8) {
				$rangos38[$d] = $i;
				$d++;
			}

// Vector de terminaciones 4 y 9 //////////////////////////////////////////////////////////////////
			if ($terminacion == 4 || $terminacion == 9) {
				$rangos49[$e] = $i;
				$e++;
			}

			$i++;
		} // fin de creacion de vectores segun terminacion  ///////////////////////////////////////////////

		shuffle($rangos05);
		shuffle($rangos16);
		shuffle($rangos27);
		shuffle($rangos38);
		shuffle($rangos49);
/////////////////////////////////////////////////////////////////////////////////////////////////

		$i = 0;
		$j = 0;
		$a = 0;

		$validar_registro = mysqli_query($conn, "SELECT * FROM sorteos_mezclas WHERE id_sorteo = '$id_oculto' ");

		if (mysqli_num_rows($validar_registro) == 0) {

			while ($i < $num_paquetes) {

				mysqli_query($conn, "INSERT INTO sorteos_mezclas(num_mezcla, id_sorteo) VALUES ('$i','$id_oculto')");

				$rango = $rangos05[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos16[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos27[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos38[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos49[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$a++;
				$i++;
			}

			mysqli_query($conn, "UPDATE sorteos_mayores SET mezcla = '$mezcla', estado_sorteo = 'PENDIENTE DISTRIBUCION' WHERE id = '$id_oculto' ");

		}

	} elseif ($mezcla == 50) {

		$num_rangos = $cantidad / $mezcla;
		$num_paquetes = $num_rangos / 2;

///////////////////////////////////////////////////////////////////////////////////////////
		//////////////////////////////////////////////////////////////////////////////////////////////

// CRECION DE VECTORES SEGUN TERMINACION  ////////////////////////////////////////////////////////

		$i = 0;
		$a = 0;
		$b = 0;

		while ($i < $num_rangos) {

			$terminacion = substr($i, -1);

// Vector de terminaciones 0 y 5 //////////////////////////////////////////////////////////////////
			if ($terminacion == 0 || $terminacion == 2 || $terminacion == 4 || $terminacion == 6 || $terminacion == 8) {
				$rangos0[$a] = $i;
				$a++;
			}

// Vector de terminaciones 4 y 9 //////////////////////////////////////////////////////////////////
			if ($terminacion == 1 || $terminacion == 3 || $terminacion == 5 || $terminacion == 7 || $terminacion == 9) {
				$rangos5[$b] = $i;
				$b++;
			}

			$i++;
		} // fin de creacion de vectores segun terminacion  ///////////////////////////////////////////////

		shuffle($rangos0);
		shuffle($rangos5);
/////////////////////////////////////////////////////////////////////////////////////////////////

		$i = 0;
		$j = 0;
		$a = 0;

		$validar_registro = mysqli_query($conn, "SELECT * FROM sorteos_mezclas WHERE id_sorteo = '$id_oculto' ");

		if (mysqli_num_rows($validar_registro) == 0) {

			while ($i < $num_paquetes) {

				mysqli_query($conn, "INSERT INTO sorteos_mezclas(num_mezcla, id_sorteo) VALUES ('$i','$id_oculto')");

				$rango = $rangos0[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$rango = $rangos5[$a] * $mezcla;
				mysqli_query($conn, "INSERT INTO sorteos_mezclas_rangos(`num_mezcla`, `rango`, `id_sorteo`) VALUES ('$i','$rango','$id_oculto')");

				$a++;
				$i++;
			}

			mysqli_query($conn, "UPDATE sorteos_mayores SET mezcla = '$mezcla', estado_sorteo = 'PENDIENTE DISTRIBUCION' WHERE id = '$id_oculto' ");

		}

	}

	?>
<script type="text/javascript">

swal("", "Registros guardados correctamente.", "success")
.then((value) => {
    window.location.href = './screen_sorteos_mezclas.php';
});

</script>
<?php

}

?>