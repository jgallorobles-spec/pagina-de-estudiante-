<?php
function getAllSubjects($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM subjects ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getSubject($pdo, $id) {
    $stmt = $pdo->prepare("SELECT * FROM subjects WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

function getSubjectDocuments($pdo, $subject_id) {
    $stmt = $pdo->prepare("SELECT * FROM documents WHERE subject_id = ? ORDER BY uploaded_at DESC");
    $stmt->execute([$subject_id]);
    return $stmt->fetchAll();
}

function getAllStudents($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'student' ORDER BY registration_number");
    $stmt->execute();
    return $stmt->fetchAll();
}

function getAllAdmins($pdo) {
    $stmt = $pdo->prepare("SELECT * FROM users WHERE role = 'admin' ORDER BY name");
    $stmt->execute();
    return $stmt->fetchAll();
}

function addStudent($pdo, $registration_number) {
    // Verificar si ya existe
    $stmt = $pdo->prepare("SELECT id FROM users WHERE registration_number = ?");
    $stmt->execute([$registration_number]);
    if ($stmt->fetch()) {
        return false;
    }
    
    $stmt = $pdo->prepare("INSERT INTO users (registration_number, role) VALUES (?, 'student')");
    return $stmt->execute([$registration_number]);
}

function addStudentsFromList($pdo, $registration_list) {
    $registrations = explode(',', $registration_list);
    $success_count = 0;
    
    foreach ($registrations as $reg) {
        $reg = trim($reg);
        if (!empty($reg)) {
            if (addStudent($pdo, $reg)) {
                $success_count++;
            }
        }
    }
    
    return $success_count;
}

function deleteUser($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM users WHERE id = ?");
    return $stmt->execute([$id]);
}

function addSubject($pdo, $name, $teacher, $code, $description) {
    $stmt = $pdo->prepare("INSERT INTO subjects (name, teacher, code, description) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$name, $teacher, $code, $description]);
}

function updateSubject($pdo, $id, $name, $teacher, $code, $description) {
    $stmt = $pdo->prepare("UPDATE subjects SET name = ?, teacher = ?, code = ?, description = ? WHERE id = ?");
    return $stmt->execute([$name, $teacher, $code, $description, $id]);
}

function deleteSubject($pdo, $id) {
    $stmt = $pdo->prepare("DELETE FROM subjects WHERE id = ?");
    return $stmt->execute([$id]);
}

function addDocument($pdo, $subject_id, $title, $file_path, $file_type) {
    $stmt = $pdo->prepare("INSERT INTO documents (subject_id, title, file_path, file_type) VALUES (?, ?, ?, ?)");
    return $stmt->execute([$subject_id, $title, $file_path, $file_type]);
}

function deleteDocument($pdo, $id) {
    // Obtener la ruta del archivo para eliminarlo
    $stmt = $pdo->prepare("SELECT file_path FROM documents WHERE id = ?");
    $stmt->execute([$id]);
    $document = $stmt->fetch();
    
    if ($document && file_exists($document['file_path'])) {
        unlink($document['file_path']);
    }
    
    $stmt = $pdo->prepare("DELETE FROM documents WHERE id = ?");
    return $stmt->execute([$id]);
}

function updateSiteSetting($pdo, $key, $value) {
    $stmt = $pdo->prepare("INSERT INTO site_settings (setting_key, setting_value) VALUES (?, ?) ON DUPLICATE KEY UPDATE setting_value = ?");
    return $stmt->execute([$key, $value, $value]);
}
?>
