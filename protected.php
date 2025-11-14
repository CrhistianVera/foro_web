<?php
session_start();
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}

$usuario = $_SESSION['usuario'];
$poema = <<<EOD
 <b>Poema: Celebra la Vida</b> <br><br>
No dejes que termine el día sin haber crecido un poco,<br>
sin haber sido feliz, sin haber aumentado tus sueños.<br>
No te dejes vencer por el desaliento,<br>
no permitas que nadie te quite el derecho<br>
a expresar lo que sientes, a vivir lo que sueñas.<br><br>
¡Celebra la vida! porque en cada amanecer<br>
hay una nueva oportunidad para empezar de nuevo.
EOD;
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Bienvenido</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
    <h2>¡Bienvenido, <?php echo htmlspecialchars($usuario); ?>! </h2>
    <p><?php echo $poema; ?></p>
    <br>
    <a href="logout.php">Cerrar sesión</a>
</div>
</body>
</html>
