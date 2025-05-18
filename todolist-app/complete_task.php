<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id']) && isset($_POST['status'])) {
    $task_id = $_POST['task_id'];
    $status = $_POST['status'];
    
    // Verify task belongs to user
    $stmt = $pdo->prepare("UPDATE tasks SET status = :status WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ':status' => $status,
        ':id' => $task_id,
        ':user_id' => $_SESSION['user_id']
    ]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Task status updated successfully.";
    } else {
        $_SESSION['error'] = "Task not found or you don't have permission to update it.";
    }
}

header('Location: index.php');
exit();
?>