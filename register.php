<?php 

include_once 'db.php';


session_start();
if (isset($_SESSION['idUsuario'])) {
	header('Location: panel.php');
}

if (isset($_POST['btn-login'])) {
	$email = $_POST['email'];
	$pass = $_POST['pass'];
	$pass2 = $_POST['pass2'];

	if ($pass == $pass2) {
		$query = "SELECT * FROM usuarios WHERE email = '" . $email . "'";
		$emails = queryDB($query);

		if (sizeOf($emails) == 0) {

			queryDB("INSERT INTO usuarios (email, pass) VALUES ('" . $email . "', '". $pass . "')");
			header('Location: login.php?registered=true');
		}
		else {
			$msg = 'El email ya está registrado.';
		}
	}
	else {
		$msg = 'Las contraseñas no coinciden.';
	}
}

 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Registrarte es simple</title>
</head>
<body>
	<h1>Registrarse</h1>
	<form action="" method="POST">
		<p>
			<input type="email" name="email" placeholder="E-mail" required>	
		</p>
		<p>
			<input type="password" name="pass" placeholder="Contraseña" required>
		</p>
		<p>
			<input type="password" name="pass2" placeholder="Repetir contraseña" required>
		</p>
		<p>
			<input type="submit" name="btn-login" value="Iniciar sesión">
		</p>
	</form>
	<p>
		<a href="login.php">Iniciar sesión</a>
	</p>

	<?php if (isset($msg)): ?>
		<p style="color: red;">
			<?php echo $msg; ?>
		</p>
	<?php endif ?>
</body>
</html>