<?php 

include_once 'db.php';

session_start();
if (isset($_SESSION['idUsuario'])) {
	header('Location: panel.php');
}

if (isset($_POST['btn-login'])) {
	$email = $_POST['email'];
	$pass = $_POST['pass'];

	$userData = queryDB("SELECT * FROM usuarios WHERE email = '" . $email . "'");

	if (sizeOf($userData) > 0) {
		if ($userData[0]['pass'] == $pass) {
			$_SESSION['idUsuario'] = $userData[0]['idUsuario'];
			header('Location: panel.php');
		}
		else {
			$msg = 'La contraseña es incorrecta.';
		}
	}
	else {
		$msg = 'El usuario no existe.';
	}
}

 ?>

<!DOCTYPE html>
<html lang="es">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>webgenerator Manuel Rafael</title>
</head>
<body>
	<h1>Iniciar sesión</h1>
	<form action="" method="POST">
		<p>
			<input type="email" name="email" placeholder="E-mail" required>	
		</p>
		<p>
			<input type="password" name="pass" placeholder="Contraseña" required>
		</p>
		<p>
			<input type="submit" name="btn-login" value="Iniciar sesión">
		</p>
	</form>
	<p>
		<a href="register.php">Registrarse</a>
	</p>

	<?php if (isset($msg)): ?>
		<p style="color: red;">
			<?php echo $msg; ?>
		</p>
	<?php endif ?>

	<?php if (isset($_GET['registered'])): ?>
		<p style="color: green;">¡Registrado con éxito!</p>
	<?php endif ?>
</body>
</html>