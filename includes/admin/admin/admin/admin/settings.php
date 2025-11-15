<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$message = '';
$error = '';

// Procesar formulario
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $site_title = $_POST['site_title'];
    $career_name = $_POST['career_name'];
    $primary_color = $_POST['primary_color'];
    
    // Actualizar configuraciones
    updateSiteSetting($pdo, 'site_title', $site_title);
    updateSiteSetting($pdo, 'career_name', $career_name);
    updateSiteSetting($pdo, 'primary_color', $primary_color);
    
    // Procesar logo
    if (isset($_FILES['logo']) && $_FILES['logo']['error'] === UPLOAD_ERR_OK) {
        $upload_dir = '../uploads/';
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        
        $file_name = 'logo_' . time() . '.' . pathinfo($_FILES['logo']['name'], PATHINFO_EXTENSION);
        $file_path = $upload_dir . $file_name;
        
        if (move_uploaded_file($_FILES['logo']['tmp_name'], $file_path)) {
            updateSiteSetting($pdo, 'logo_path', $file_path);
        } else {
            $error = "Error al subir el logo";
        }
    }
    
    if (!$error) {
        $message = "Configuración actualizada exitosamente";
        // Recargar configuraciones
        $site_settings = getSiteSettings($pdo);
    }
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Configuración del Sitio - Portal Universitario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Configuración del Sitio</h1>
            <div class="user-info">
                <span>Bienvenido, <?php echo $_SESSION['name']; ?></span>
                <a href="dashboard.php" class="btn btn-secondary">Volver al Panel</a>
                <a href="../logout.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </header>
        
        <main class="main-content">
            <?php if ($message): ?>
                <div class="alert alert-success"><?php echo $message; ?></div>
            <?php endif; ?>
            
            <?php if ($error): ?>
                <div class="alert alert-error"><?php echo $error; ?></div>
            <?php endif; ?>
            
            <div class="admin-section">
                <h2>Configuración General</h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="site_title">Título del Sitio:</label>
                        <input type="text" id="site_title" name="site_title" value="<?php echo htmlspecialchars($site_settings['site_title']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="career_name">Nombre de la Carrera:</label>
                        <input type="text" id="career_name" name="career_name" value="<?php echo htmlspecialchars($site_settings['career_name']); ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="primary_color">Color Principal:</label>
                        <input type="color" id="primary_color" name="primary_color" value="<?php echo htmlspecialchars($site_settings['primary_color']); ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="logo">Logo del Sitio:</label>
                        <input type="file" id="logo" name="logo" accept="image/*">
                        <?php if ($site_settings['logo_path'] && file_exists($site_settings['logo_path'])): ?>
                            <p>Logo actual: <img src="<?php echo $site_settings['logo_path']; ?>" alt="Logo" style="max-height: 50px;"></p>
                        <?php endif; ?>
                    </div>
                    
                    <button type="submit" class="btn">Guardar Configuración</button>
                </form>
            </div>
        </main>
    </div>
    
    <script src="../js/script.js"></script>
</body>
</html>
