<?php
session_start();
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    $sql = "SELECT * FROM usuarios WHERE correo = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $correo);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 1) {
        $user = $result->fetch_assoc();
        if (password_verify($contraseña, $user['contraseña'])) {
            $_SESSION['usuario'] = $user['nombre'];
            header("Location: protected.php");
            exit();
        } else {
            $msg = "<div class='msg error'>Contraseña incorrecta.</div>";
        }
    } else {
        $msg = "<div class='msg error'>Correo no encontrado.</div>";
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Iniciar Sesión</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h2>Inicio de Sesión</h2>
  <?php echo $msg; ?>
  <form method="POST" action="">
    <div class="form-control">
      <input type="email" name="correo" placeholder="Correo electrónico" required>
    </div>
    <div class="form-control">
      <input id="passLogin" type="password" name="contraseña" placeholder="Contraseña" required>
      <button type="button" class="toggle-pass" onclick="togglePassword('passLogin', this)">👁️</button>
    </div>
    <button class="submit-btn" type="submit">Entrar</button>
  </form>
  <br>
  <a href="register.php">¿No tienes cuenta? Regístrate</a>
</div>

<script>
function togglePassword(inputId, btn) {
  const input = document.getElementById(inputId);
  if (input.type === "password") {
    input.type = "text";
    btn.textContent = "🙈";
  } else {
    input.type = "password";
    btn.textContent = "👁️";
  }
}
</script>
</body>
</html>
