<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$message = '';
$error = '';
$editing_subject = null;

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_subject'])) {
        $name = $_POST['name'];
        $teacher = $_POST['teacher'];
        $code = $_POST['code'];
        $description = $_POST['description'];
        
        if (addSubject($pdo, $name, $teacher, $code, $description)) {
            $message = "Materia creada exitosamente";
        } else {
            $error = "Error al crear materia";
        }
    } elseif (isset($_POST['update_subject'])) {
        $id = $_POST['subject_id'];
        $name = $_POST['name'];
        $teacher = $_POST['teacher'];
        $code = $_POST['code'];
        $description = $_POST['description'];
        
        if (updateSubject($pdo, $id, $name, $teacher, $code, $description)) {
            $message = "Materia actualizada exitosamente";
            $editing_subject = null;
        } else {
            $error = "Error al actualizar materia";
        }
    } elseif (isset($_POST['delete_subject'])) {
        $subject_id = $_POST['subject_id'];
        if (deleteSubject($pdo, $subject_id)) {
            $message = "Materia eliminada exitosamente";
        } else {
            $error = "Error al eliminar materia";
        }
    }
}

// Obtener materias para editar
if (isset($_GET['edit'])) {
    $editing_subject = getSubject($pdo, $_GET['edit']);
}

$subjects = getAllSubjects($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Materias - Portal Universitario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestionar Materias</h1>
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
                <h2><?php echo $editing_subject ? 'Editar Materia' : 'Crear Nueva Materia'; ?></h2>
                
                <form method="POST">
                    <?php if ($editing_subject): ?>
                        <input type="hidden" name="subject_id" value="<?php echo $editing_subject['id']; ?>">
                    <?php endif; ?>
                    
                    <div class="form-group">
                        <label for="name">Nombre de la Materia:</label>
                        <input type="text" id="name" name="name" value="<?php echo $editing_subject ? htmlspecialchars($editing_subject['name']) : ''; ?>" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="teacher">Docente:</label>
                        <input type="text" id="teacher" name="teacher" value="<?php echo $editing_subject ? htmlspecialchars($editing_subject['teacher']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="code">Código:</label>
                        <input type="text" id="code" name="code" value="<?php echo $editing_subject ? htmlspecialchars($editing_subject['code']) : ''; ?>">
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Descripción:</label>
                        <textarea id="description" name="description" rows="4"><?php echo $editing_subject ? htmlspecialchars($editing_subject['description']) : ''; ?></textarea>
                    </div>
                    
                    <?php if ($editing_subject): ?>
                        <button type="submit" name="update_subject" class="btn">Actualizar Materia</button>
                        <a href="subjects.php" class="btn btn-secondary">Cancelar</a>
                    <?php else: ?>
                        <button type="submit" name="add_subject" class="btn">Crear Materia</button>
                    <?php endif; ?>
                </form>
            </div>
            
            <div class="admin-section">
                <h2>Lista de Materias</h2>
                
                <?php if (empty($subjects)): ?>
                    <p>No hay materias creadas.</p>
                <?php else: ?>
                    <div class="subjects-list">
                        <?php foreach ($subjects as $subject): ?>
                            <div class="subject-item">
                                <div class="subject-info">
                                    <h3><?php echo htmlspecialchars($subject['name']); ?></h3>
                                    <p><strong>Docente:</strong> <?php echo htmlspecialchars($subject['teacher']); ?></p>
                                    <p><strong>Código:</strong> <?php echo htmlspecialchars($subject['code']); ?></p>
                                    <?php if ($subject['description']): ?>
                                        <p><strong>Descripción:</strong> <?php echo htmlspecialchars($subject['description']); ?></p>
                                    <?php endif; ?>
                                </div>
                                
                                <div class="subject-actions">
                                    <a href="subjects.php?edit=<?php echo $subject['id']; ?>" class="btn">Editar</a>
                                    <a href="subject_content.php?id=<?php echo $subject['id']; ?>" class="btn">Gestionar Contenido</a>
                                    <form method="POST" class="inline-form">
                                        <input type="hidden" name="subject_id" value="<?php echo $subject['id']; ?>">
                                        <button type="submit" name="delete_subject" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar esta materia?')">Eliminar</button>
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
