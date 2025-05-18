<?php
function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function getUserTasks($user_id, $pdo, $search = null) {
    $sql = "SELECT * FROM tasks WHERE user_id = :user_id";
    $params = [':user_id' => $user_id];
    
    if ($search) {
        $sql .= " AND (title LIKE :search OR description LIKE :search)";
        $params[':search'] = "%$search%";
    }
    
    $sql .= " ORDER BY deadline ASC";
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function checkLateTasks($tasks) {
    $now = new DateTime();
    foreach ($tasks as &$task) {
        $deadline = new DateTime($task['deadline']);
        $task['is_late'] = ($task['status'] == 'pending' && $deadline < $now);
    }
    return $tasks;
}

function uploadProfilePicture($file) {
    $target_dir = "uploads/";
    $imageFileType = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $new_filename = uniqid() . '.' . $imageFileType;
    $target_file = $target_dir . $new_filename;
    
    // Check if image file is a actual image
    $check = getimagesize($file['tmp_name']);
    if ($check === false) {
        return ['success' => false, 'message' => 'File is not an image.'];
    }
    
    // Check file size (max 2MB)
    if ($file['size'] > 2000000) {
        return ['success' => false, 'message' => 'Sorry, your file is too large.'];
    }
    
    // Allow certain file formats
    if (!in_array($imageFileType, ['jpg', 'png', 'jpeg', 'gif'])) {
        return ['success' => false, 'message' => 'Only JPG, JPEG, PNG & GIF files are allowed.'];
    }
    
    if (move_uploaded_file($file['tmp_name'], $target_file)) {
        return ['success' => true, 'filename' => $new_filename];
    } else {
        return ['success' => false, 'message' => 'Sorry, there was an error uploading your file.'];
    }
}

function getProfilePicture($userId) {
    $default = 'assets/images/default-profile.jpg';
    $uploadDir = "uploads/";

    // Pastikan sudah ada koneksi $pdo
    require __DIR__ . '/../config/database.php'; // Pastikan path dan $pdo benar

    $stmt = $pdo->prepare("SELECT profile_picture FROM users WHERE id = ?");
    $stmt->execute([$userId]);
    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($row && !empty($row['profile_picture'])) {
        $profilePath = $uploadDir . $row['profile_picture'];
        if (file_exists($profilePath)) {
            return $profilePath;
        }
    }
    return $default;
}
?>

                                        