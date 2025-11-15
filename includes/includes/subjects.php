<?php
require_once 'includes/config.php';
require_once 'includes/auth.php';
require_once 'includes/functions.php';

requireLogin();

if (!isset($_GET['id'])) {
    header("Location: dashboard.php");
    exit();
}

$subject_id = $_GET['id'];
$subject = getSubject($pdo, $subject_id);
$documents = getSubjectDocuments($pdo, $subject_id);

if (!$subject) {
    header("Location: dashboard.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($subject['name']); ?> - Portal Universitario</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1><?php echo htmlspecialchars($subject['name']); ?></h1>
            <div class="user-info">
                <span>Bienvenido, <?php echo $_SESSION['name'] ?: $_SESSION['registration_number']; ?></span>
                <a href="dashboard.php" class="btn btn-secondary">Volver a Materias</a>
                <a href="logout.php" class="btn btn-secondary">Cerrar Sesión</a>
            </div>
        </header>
        
        <main class="main-content">
            <div class="subject-info">
                <p><strong>Docente:</strong> <?php echo htmlspecialchars($subject['teacher']); ?></p>
                <p><strong>Código:</strong> <?php echo htmlspecialchars($subject['code']); ?></p>
                <?php if ($subject['description']): ?>
                    <p><strong>Descripción:</strong> <?php echo htmlspecialchars($subject['description']); ?></p>
                <?php endif; ?>
            </div>
            
            <div class="documents-section">
                <h2>Documentos</h2>
                
                <?php if (empty($documents)): ?>
                    <p>No hay documentos disponibles para esta materia.</p>
                <?php else: ?>
                    <div class="documents-list">
                        <?php foreach ($documents as $document): ?>
                            <div class="document-item">
                                <h4><?php echo htmlspecialchars($document['title']); ?></h4>
                                <div class="document-actions">
                                    <a href="<?php echo $document['file_path']; ?>" target="_blank" class="btn">Ver Documento</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="js/script.js"></script>
</body>
</html>
