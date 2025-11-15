<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

if (isAdmin()) {
    header("Location: admin/dashboard.php");
    exit();
}

$subjects = getAllSubjects($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Materias - Portal Universitario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Materias Disponibles</h1>
            <div class="user-info">
                <span>Bienvenido, <?php echo $_SESSION['name'] ?: $_SESSION['registration_number']; ?></span>
                <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </header>
        
        <main class="main-content">
            <div class="subjects-grid">
                <?php foreach ($subjects as $subject): ?>
                    <div class="subject-card">
                        <h3><?php echo htmlspecialchars($subject['name']); ?></h3>
                        <p><strong>Docente:</strong> <?php echo htmlspecialchars($subject['teacher']); ?></p>
                        <p><strong>Código:</strong> <?php echo htmlspecialchars($subject['code']); ?></p>
                        <a href="subjects.php?id=<?php echo $subject['id']; ?>" class="btn">Ver Contenido</a>
                    </div>
                <?php endforeach; ?>
                
                <?php if (empty($subjects)): ?>
                    <p>No hay materias disponibles en este momento.</p>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
