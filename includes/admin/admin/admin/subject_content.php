<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

if (!isset($_GET['id'])) {
    header("Location: subjects.php");
    exit();
}

$subject_id = $_GET['id'];
$subject = getSubject($pdo, $subject_id);
$documents = getSubjectDocuments($pdo, $subject_id);

if (!$subject) {
    header("Location: subjects.php");
    exit();
}

$message = '';
$error = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_document'])) {
        $title = $_POST['title'];
        
        // Procesar archivo
        if (isset($_FILES['document']) && $_FILES['document']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = '../uploads/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }
            
            $file_name = time() . '_' . basename($_FILES['document']['name']);
            $file_path = $upload_dir . $file_name;
            $file_type = strtolower(pathinfo($file_path, PATHINFO_EXTENSION));
            
            // Mover archivo
            if (move_uploaded_file($_FILES['document']['tmp_name'], $file_path)) {
                if (addDocument($pdo, $subject_id, $title, $file_path, $file_type)) {
                    $message = "Documento agregado exitosamente";
                } else {
                    $error = "Error al agregar documento a la base de datos";
                }
            } else {
                $error = "Error al subir el archivo";
            }
        } else {
            $error = "Por favor, selecciona un archivo válido";
        }
    } elseif (isset($_POST['delete_document'])) {
        $document_id = $_POST['document_id'];
        if (deleteDocument($pdo, $document_id)) {
            $message = "Documento eliminado exitosamente";
        } else {
            $error = "Error al eliminar documento";
        }
    }
}

// Actualizar lista de documentos después de cambios
$documents = getSubjectDocuments($pdo, $subject_id);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Contenido - <?php echo htmlspecialchars($subject['name']); ?></title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestionar Contenido: <?php echo htmlspecialchars($subject['name']); ?></h1>
            <div class="user-info">
                <span>Bienvenido, <?php echo $_SESSION['name']; ?></span>
                <a href="subjects.php" class="btn btn-secondary">Volver a Materias</a>
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
                <h2>Agregar Documento</h2>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="title">Título del Documento:</label>
                        <input type="text" id="title" name="title" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="document">Archivo:</label>
                        <input type="file" id="document" name="document" required>
                    </div>
                    
                    <button type="submit" name="add_document" class="btn">Agregar Documento</button>
                </form>
            </div>
            
            <div class="admin-section">
                <h2>Documentos de la Materia</h2>
                
                <?php if (empty($documents)): ?>
                    <p>No hay documentos para esta materia.</p>
                <?php else: ?>
                    <div class="documents-list">
                        <?php foreach ($documents as $document): ?>
                            <div class="document-item">
                                <div class="document-info">
                                    <h3><?php echo htmlspecialchars($document['title']); ?></h3>
                                    <p><strong>Subido:</strong> <?php echo date('d/m/Y H:i', strtotime($document['uploaded_at'])); ?></p>
                                    <p><strong>Tipo:</strong> <?php echo strtoupper($document['file_type']); ?></p>
                                </div>
                                
                                <div class="document-actions">
                                    <a href="<?php echo $document['file_path']; ?>" target="_blank" class="btn">Ver Documento</a>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="document_id" value="<?php echo $document['id']; ?>">
                                        <button type="submit" name="delete_document" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este documento?')">Eliminar</button>
                                    </form>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
        </main>
    </div>
    
    <script src="../js/script.js"></script>
</body>
</html>
