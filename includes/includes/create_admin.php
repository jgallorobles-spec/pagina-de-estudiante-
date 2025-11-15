<?php
require_once 'includes/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $registration_number = $_POST['registration_number'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $name = $_POST['name'];
    
    // Verificar si ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE registration_number = ?");
    $stmt->execute([$registration_number]);
    
    if ($stmt->fetch()) {
        $error = "El número de registro ya existe";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (registration_number, password, name, role) VALUES (?, ?, ?, 'admin')");
        if ($stmt->execute([$registration_number, $password, $name])) {
            $success = "Administrador creado exitosamente";
        } else {
            $error = "Error al crear administrador";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Crear Administrador</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Crear Administrador</h1>
        </header>
        
        <main class="main-content">
            <?php if (isset($error)): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <?php if (isset($success)): ?>
                <div class="alert alert-success"><?php echo $success; ?></div>
            <?php endif; ?>
            
            <form method="POST" class="admin-form">
                <div class="form-group">
                    <label for="name">Nombre:</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="registration_number">Número de Registro:</label>
                    <input type="text" id="registration_number" name="registration_number" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Contraseña:</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirmar Contraseña:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <button type="submit" class="btn">Crear Administrador</button>
            </form>
            
            <div class="back-link">
                <a href="index.html">Volver al inicio</a>
            </div>
        </main>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
