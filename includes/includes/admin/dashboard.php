<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$subjects = getAllSubjects($pdo);
$students_count = count(getAllStudents($pdo));
$admins_count = count(getAllAdmins($pdo));
$subjects_count = count($subjects);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel de Administración - Portal Universitario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Panel de Administración</h1>
            <div class="user-info">
                <span>Bienvenido, <?php echo $_SESSION['name']; ?></span>
                <a href="../logout.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </header>
        
        <nav class="admin-nav">
            <ul>
                <li><a href="users.php">Gestionar Usuarios</a></li>
                <li><a href="subjects.php">Gestionar Materias</a></li>
                <li><a href="settings.php">Configuración del Sitio</a></li>
            </ul>
        </nav>
        
        <main class="main-content">
            <div class="stats-grid">
                <div class="stat-card">
                    <h3>Estudiantes</h3>
                    <p class="stat-number"><?php echo $students_count; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Administradores</h3>
                    <p class="stat-number"><?php echo $admins_count; ?></p>
                </div>
                
                <div class="stat-card">
                    <h3>Materias</h3>
                    <p class="stat-number"><?php echo $subjects_count; ?></p>
                </div>
            </div>
            
            <div class="quick-actions">
                <h2>Acciones Rápidas</h2>
                <div class="action-buttons">
                    <a href="users.php" class="btn">Agregar Usuarios</a>
                    <a href="subjects.php" class="btn">Crear Materia</a>
                    <a href="settings.php" class="btn">Configurar Sitio</a>
                </div>
            </div>
        </main>
    </div>
    
    <script src="../js/script.js"></script>
</body>
</html>
