<?php

require "../../conexion.php";

$id_sorteo = $_GET['s'];
$id_revisor = $_GET['r'];
$tipo = $_GET['t'];

if ($tipo == 1) {

	$info_revisor = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE id = '$id_revisor' ");
	$ob_revisor = mysqli_fetch_object($info_revisor);
	$nombre_revisor = $ob_revisor->nombre_completo;

	$asignaciones = mysqli_query($conn, "SELECT * FROM `cc_revisores_sorteos_mayores` WHERE `id_sorteo` = $id_sorteo AND `id_revisor` = $id_revisor ");

	while ($num_revision = mysqli_fetch_array($asignaciones)) {
		$num_lista = $num_revision['numero'];

		echo '<div class=" card" >';

		echo "<div class = 'card-header bg-dark text-white'>REVISOR: " . $nombre_revisor . "</div>";

		echo "<div class = 'card-body' >";

		$n_l = $num_revision['numero'];
		$revisiones = mysqli_query($conn, "SELECT * FROM cc_produccion_mayor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revisor = '$n_l'    ");
		if ($revisiones === false) {
			echo mysqli_error($conn);
		}

		$concat_numero_revision = '';

		if (mysqli_num_rows($revisiones) > 0) {

			while ($row_revision = mysqli_fetch_array($revisiones)) {
				$revision = $row_revision['numero_revision'];

				$concat_numero_revision .= $revision . ',';

				echo "<a  class = 'btn btn-secondary' href = './cc_revisor_operativo_mayor_detalle.php?id_sort=" . $id_sorteo . "&num_asig=" . $num_revision['numero'] . "&id_rev=" . $id_revisor . "&revision=" . $revision . "' style = 'width:100%; margin-top: 5px; margin-bottom: 5px;'> Reposicion " . $revision . " Finalizada</a>";
			}

		} else {

			echo "<a class = 'btn btn-success' href = './cc_revisor_operativo_mayor_detalle.php?id_sort=" . $id_sorteo . "&num_asig=" . $num_revision['numero'] . "&id_rev=" . $id_revisor . "&revision=1' style = 'width:100%'> Realizar Reposicion 1</a>";

		}

		$concat_numero_revision = substr($concat_numero_revision, 0, -1);

		if (strlen($concat_numero_revision) == 0) {

			$revisiones_pendientes = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' GROUP BY numero_revision ");

		} else {

			$revisiones_pendientes = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_mayores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' AND numero_revision NOT IN ($concat_numero_revision)  GROUP BY numero_revision ");

		}

		if ($revisiones_pendientes === FALSE) {
			echo mysqli_error($conn);
		}

		while ($row_revisiones_pendietes = mysqli_fetch_array($revisiones_pendientes)) {

			$revision_pendiente = $row_revisiones_pendietes['numero_revision'];

			echo "<a class = 'btn btn-success' href = './cc_revisor_operativo_mayor_detalle.php?id_sort=" . $id_sorteo . "&num_asig=" . $num_revision['numero'] . "&id_rev=" . $id_revisor . "&revision=" . $revision_pendiente . "' style = 'width:100%; margin-top: 5px; margin-bottom: 5px;' > Realizar Reposicion " . $revision_pendiente . "</a>";

		}

		echo "</div>";
		echo "</div>";

	}

} else {

	$info_revisor = mysqli_query($conn, "SELECT * FROM pani_usuarios WHERE id = '$id_revisor' ");
	$ob_revisor = mysqli_fetch_object($info_revisor);
	$nombre_revisor = $ob_revisor->nombre_completo;

	$asignaciones = mysqli_query($conn, "SELECT * FROM `cc_revisores_sorteos_menores` WHERE `id_sorteo` = $id_sorteo AND `id_revisor` = $id_revisor ");

	while ($num_revision = mysqli_fetch_array($asignaciones)) {
		$num_lista = $num_revision['numero'];

		echo '<div class=" card" >';

		echo "<div class = 'card-header bg-dark text-white'>REVISOR: " . $nombre_revisor . "</div>";

		echo "<div class = 'card-body' >";

		$n_l = $num_revision['numero'];
		$revisiones = mysqli_query($conn, "SELECT * FROM cc_produccion_menor WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND numero_revisor = '$n_l'    ");
		if ($revisiones === false) {
			echo mysqli_error();
		}

		$concat_numero_revision = '';

		if (mysqli_num_rows($revisiones) > 0) {

			while ($row_revision = mysqli_fetch_array($revisiones)) {
				$revision = $row_revision['numero_revision'];

				$concat_numero_revision .= $revision . ',';

				echo "<a  class = 'btn btn-secondary' href = './cc_revisor_operativo_menor_detalle.php?id_sort=" . $id_sorteo . "&num_asig=" . $num_revision['numero'] . "&id_rev=" . $id_revisor . "&revision=" . $revision . "' style = 'width:100%; margin-top: 5px; margin-bottom: 5px;'> Reposicion " . $revision . " Finalizada</a>";
			}

		} else {

			echo "<a class = 'btn btn-success' href = './cc_revisor_operativo_menor_detalle.php?id_sort=" . $id_sorteo . "&num_asig=" . $num_revision['numero'] . "&id_rev=" . $id_revisor . "&revision=1' style = 'width:100%'> Realizar Reposicion 1</a>";

		}

		$concat_numero_revision = substr($concat_numero_revision, 0, -1);

		if (strlen($concat_numero_revision) == 0) {

			$revisiones_pendientes = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' GROUP BY numero_revision ");

		} else {

			$revisiones_pendientes = mysqli_query($conn, "SELECT * FROM cc_revisores_sorteos_menores_control WHERE id_sorteo = '$id_sorteo' AND id_revisor = '$id_revisor' AND num_lista = '$num_lista' AND numero_revision NOT IN ($concat_numero_revision)  GROUP BY numero_revision ");

		}

		if ($revisiones_pendientes === FALSE) {
			echo mysqli_error($conn);
		}

		while ($row_revisiones_pendietes = mysqli_fetch_array($revisiones_pendientes)) {

			$revision_pendiente = $row_revisiones_pendietes['numero_revision'];

			echo "<a class = 'btn btn-success' href = './cc_revisor_operativo_menor_detalle.php?id_sort=" . $id_sorteo . "&num_asig=" . $num_revision['numero'] . "&id_rev=" . $id_revisor . "&revision=" . $revision_pendiente . "' style = 'width:100%; margin-top: 5px; margin-bottom: 5px;' > Realizar Reposicion " . $revision_pendiente . "</a>";

		}

		echo "</div>";
		echo "</div>";

	}

}

?>