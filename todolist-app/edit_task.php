<?php
require_once 'includes/auth.php';

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit();
}

$task_id = $_GET['id'];

// Verify task belongs to user
$stmt = $pdo->prepare("SELECT * FROM tasks WHERE id = :id AND user_id = :user_id");
$stmt->execute([':id' => $task_id, ':user_id' => $_SESSION['user_id']]);
$task = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$task) {
    $_SESSION['error'] = "Task not found or you don't have permission to edit it.";
    header('Location: index.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $title = trim($_POST['title']);
    $description = trim($_POST['description']);
    $deadline = $_POST['deadline'];
    
    // Validation
    $errors = [];
    
    if (empty($title)) {
        $errors[] = "Title is required.";
    }
    
    if (empty($deadline)) {
        $errors[] = "Deadline is required.";
    }
    
    if (empty($errors)) {
        $stmt = $pdo->prepare("UPDATE tasks SET title = :title, description = :description, deadline = :deadline WHERE id = :id");
        $stmt->execute([
            ':title' => $title,
            ':description' => $description,
            ':deadline' => $deadline,
            ':id' => $task_id
        ]);
        
        $_SESSION['success'] = "Task updated successfully.";
        header('Location: index.php');
        exit();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Task</h4>
            </div>
            <div class="card-body">
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST">
                    <div class="mb-3">
                        <label for="title" class="form-label">Title</label>
                        <input type="text" class="form-control" id="title" name="title" value="<?= htmlspecialchars($task['title']) ?>" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"><?= htmlspecialchars($task['description']) ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control" id="deadline" name="deadline"
                            value="<?= date('Y-m-d\TH:i', strtotime($task['deadline'])) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Update Task</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>