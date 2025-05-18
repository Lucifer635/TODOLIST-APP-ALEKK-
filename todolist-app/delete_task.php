<?php
require_once 'includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['task_id'])) {
    $task_id = $_POST['task_id'];
    
    // Verify task belongs to user
    $stmt = $pdo->prepare("DELETE FROM tasks WHERE id = :id AND user_id = :user_id");
    $stmt->execute([
        ':id' => $task_id,
        ':user_id' => $_SESSION['user_id']
    ]);
    
    if ($stmt->rowCount() > 0) {
        $_SESSION['success'] = "Task deleted successfully.";
    } else {
        $_SESSION['error'] = "Task not found or you don't have permission to delete it.";
    }
}

header('Location: index.php');
exit();
?>