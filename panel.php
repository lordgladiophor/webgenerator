<?php 

include_once 'db.php';

//shell_exec("rm -r js tpl php img css index.php");
//shell_exec("rm -r ../webs");
//shell_exec("rm -r *.zip ../webs/*.zip");

session_start();
if (! isset($_SESSION['idUsuario'])) {
	header('Location: login.php');
}

$idUser = $_SESSION['idUsuario'];

if (isset($_POST['btn-create'])) {
	$webName = str_replace(" ", "_", $idUser . $_POST['name']);

	$webList = queryDB("SELECT * FROM webs WHERE dominio = '" . $webName . "'");

	if (sizeOf($webList) == 0) {
		queryDB("INSERT INTO webs (idUsuario, dominio) VALUES (" . $idUser . ", '" . $webName . "')");
		if (! file_exists('../webs')) {
			shell_exec('mkdir ../webs');
		}

		shell_exec('../wix.sh ../webs/' . $webName . ' Index');
	}
	else {
		$msg = 'Ya hay un dominio con ese nombre!';
	}
}

downloadWeb();
deleteWeb();


function deleteWeb() {
	global $idUser;
	if (isset($_GET['delete'])) {
		$web = queryDB("SELECT * FROM webs WHERE dominio = '" . $_GET['delete'] . "'");
		if (!$web) {
			return;
		}
		if ($web[0]['idUsuario'] != $idUser and $idUser != 1) {
			return;
		}

		queryDB("DELETE FROM webs WHERE idWeb = " . $web[0]['idWeb']);

		shell_exec("rm -r ../webs/" . $web[0]['dominio']);
		shell_exec("rm -r ../webs/" . $web[0]['dominio'] . ".zip");
		header('Location: ?');
	}
}


function downloadWeb() {
	global $idUser;
	if (isset($_GET['download'])) {
		$web = queryDB("SELECT * FROM webs WHERE dominio = '" . $_GET['download'] . "'");
		if (!$web) {
			return;
		}
		if ($web[0]['idUsuario'] != $idUser and $idUser != 1) {
			return;
		}

		shell_exec("./sticky_fingers.sh ../webs " . $web[0]['dominio']);
		header("Location: ../webs/" . $web[0]['dominio'] . ".zip");
	}
}

if ($idUser != 1) {
	$myWebs = queryDB("SELECT dominio FROM webs WHERE idUsuario = " . $idUser);
}
else {
	$myWebs = queryDB("SELECT dominio FROM webs");
}


if ($myWebs) {
	$webs = '<br><br><table>';

	for ($i=0; $i < sizeOf($myWebs); $i++) { 
		$webs .= '<tr><td><a href="../webs/' . $myWebs[$i]['dominio'] . '">' . $myWebs[$i]['dominio'] . '</a></td>';
		$webs .= '<td><a href="?download='.$myWebs[$i]['dominio'].'">Descargar</td>';
		$webs .= '<td><a href="?delete='.$myWebs[$i]['dominio'].'">Eliminar</td>';
		$webs .= '</tr>';
	}
}


?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Bienvenido a tu panel</title>
</head>
<body>
	<a href="logout.php">Cerrar sesión de <?php echo $idUser; ?></a>
	<h2>Generar web de:</h2>

	<form action="" method="POST">
		<input type="text" name="name" required placeholder="Nombre de la nueva web">
		<input type="submit" name="btn-create" value="Crear web">
	</form>

	<?php if (isset($msg)): ?>
		<p style="color: red;">
			<?php echo $msg; ?>
		</p>
	<?php endif ?>

	<?php
		if (isset($webs)) {
			echo $webs;
		}
	?>

</body>
</html>