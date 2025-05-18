<?php
require_once 'includes/auth.php';

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
        $stmt = $pdo->prepare("INSERT INTO tasks (user_id, title, description, deadline) VALUES (:user_id, :title, :description, :deadline)");
        $stmt->execute([
            ':user_id' => $_SESSION['user_id'],
            ':title' => $title,
            ':description' => $description,
            ':deadline' => $deadline
        ]);
        
        $_SESSION['success'] = "Task added successfully.";
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
                <h4>Add New Task</h4>
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
                        <input type="text" class="form-control" id="title" name="title" required>
                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="deadline" class="form-label">Deadline</label>
                        <input type="datetime-local" class="form-control" id="deadline" name="deadline"
                            value="<?= date('Y-m-d\TH:i', strtotime($task['deadline'])) ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Add Task</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>