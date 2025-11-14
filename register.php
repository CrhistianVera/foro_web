<?php
include 'db.php';
$msg = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = trim($_POST['nombre']);
    $telefono = trim($_POST['telefono']);
    $edad = intval($_POST['edad']);
    $correo = trim($_POST['correo']);
    $contraseña = trim($_POST['contraseña']);

    if (empty($nombre) || empty($telefono) || empty($edad) || empty($correo) || empty($contraseña)) {
        $msg = "<div class='msg error' Por favor, completa todos los campos.</div>";
    } elseif ($edad < 18) {
        $msg = "<div class='msg error'> Debes ser mayor de edad para registrarte.</div>";
    } elseif (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        $msg = "<div class='msg error'> El correo no tiene un formato válido.</div>";
    } elseif (!preg_match('/^(?=.*[A-Z])(?=.*[a-z])(?=.*\\d)(?=.*[@$!%*#?&])[A-Za-z\\d@$!%*#?&]{8,}$/', $contraseña)) {
        $msg = "<div class='msg error'> La contraseña debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.</div>";
    } else {
        $sql_check = "SELECT * FROM usuarios WHERE correo = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $correo);
        $stmt_check->execute();
        $result = $stmt_check->get_result();

        if ($result->num_rows > 0) {
            $msg = "<div class='msg error' El correo ya está registrado.</div>";
        } else {
            $hash = password_hash($contraseña, PASSWORD_DEFAULT);
            $sql = "INSERT INTO usuarios (nombre, telefono, edad, correo, contraseña) VALUES (?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssiss", $nombre, $telefono, $edad, $correo, $hash);

            if ($stmt->execute()) {
                $msg = "<div class='msg success'> Registro exitoso. <a href='login.php'>Inicia sesión</a></div>";
            } else {
                $msg = "<div class='msg error'> Error al registrar.</div>";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<title>Registro de Usuario</title>
<link rel="stylesheet" href="styles.css">
</head>
<body>
<div class="container">
  <h2>Registro de Usuario</h2>
  <?php echo $msg; ?>
  <form method="POST" action="">
    <div class="form-control">
      <input type="text" name="nombre" placeholder="Nombre completo" required>
    </div>
    <div class="form-control">
      <input type="tel" name="telefono" placeholder="Número de teléfono" required pattern="[0-9]{7,15}">
    </div>
    <div class="form-control">
      <input type="number" name="edad" placeholder="Edad" min="1" required>
    </div>
    <div class="form-control">
      <input type="email" name="correo" placeholder="Correo electrónico" required>
    </div>
    <div class="form-control">
      <input id="passRegister" type="password" name="contraseña" placeholder="Contraseña" required>
      <button type="button" class="toggle-pass" onclick="togglePassword('passRegister', this)">👁️</button>
    </div>
    <small>Debe tener al menos 8 caracteres, una mayúscula, un número y un símbolo.</small>
    <button class="submit-btn" type="submit">Registrar</button>
  </form>
  <br>
  <a href="login.php">¿Ya tienes cuenta? Inicia sesión</a>
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
