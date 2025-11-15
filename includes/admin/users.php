<?php
require_once '../includes/config.php';
require_once '../includes/auth.php';
require_once '../includes/functions.php';

requireAdmin();

$message = '';
$error = '';

// Procesar formularios
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_student_single'])) {
        $registration_number = $_POST['registration_number'];
        if (addStudent($pdo, $registration_number)) {
            $message = "Usuario registrado exitosamente";
        } else {
            $error = "Error al registrar usuario (puede que ya exista)";
        }
    } elseif (isset($_POST['add_students_list'])) {
        $registration_list = $_POST['registration_list'];
        $success_count = addStudentsFromList($pdo, $registration_list);
        $message = "Se registraron $success_count usuarios exitosamente";
    } elseif (isset($_POST['delete_user'])) {
        $user_id = $_POST['user_id'];
        if (deleteUser($pdo, $user_id)) {
            $message = "Usuario eliminado exitosamente";
        } else {
            $error = "Error al eliminar usuario";
        }
    }
}

$students = getAllStudents($pdo);
$admins = getAllAdmins($pdo);
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestionar Usuarios - Portal Universitario</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body>
    <div class="container">
        <header>
            <h1>Gestionar Usuarios</h1>
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
                <h2>Agregar Estudiantes</h2>
                
                <div class="form-section">
                    <h3>Agregar un Estudiante</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="registration_number">Número de Registro:</label>
                            <input type="text" id="registration_number" name="registration_number" required>
                        </div>
                        <button type="submit" name="add_student_single" class="btn">Agregar Estudiante</button>
                    </form>
                </div>
                
                <div class="form-section">
                    <h3>Agregar Varios Estudiantes</h3>
                    <form method="POST">
                        <div class="form-group">
                            <label for="registration_list">Números de Registro (separados por coma):</label>
                            <textarea id="registration_list" name="registration_list" rows="5" placeholder="Ej: 2021001, 2021002, 2021003"></textarea>
                        </div>
                        <button type="submit" name="add_students_list" class="btn">Agregar Estudiantes</button>
                    </form>
                </div>
            </div>
            
            <div class="admin-section">
                <h2>Lista de Estudiantes</h2>
                <?php if (empty($students)): ?>
                    <p>No hay estudiantes registrados.</p>
                <?php else: ?>
                    <div class="users-list">
                        <?php foreach ($students as $student): ?>
                            <div class="user-item">
                                <span><?php echo htmlspecialchars($student['registration_number']); ?></span>
                                <form method="POST" class="inline-form">
                                    <input type="hidden" name="user_id" value="<?php echo $student['id']; ?>">
                                    <button type="submit" name="delete_user" class="btn btn-danger" onclick="return confirm('¿Estás seguro de eliminar este usuario?')">Eliminar</button>
                                </form>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            
            <div class="admin-section">
                <h2>Lista de Administradores</h2>
                <?php if (empty($admins)): ?>
                    <p>No hay administradores registrados.</p>
                <?php else: ?>
                    <div class="users-list">
                        <?php foreach ($admins as $admin): ?>
                            <div class="user-item">
                                <span><?php echo htmlspecialchars($admin['name'] . ' (' . $admin['registration_number'] . ')'); ?></span>
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
