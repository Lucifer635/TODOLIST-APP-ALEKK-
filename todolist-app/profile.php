<?php
require_once 'includes/auth.php';

$user_id = $_SESSION['user_id'];

// Get current user data
$stmt = $pdo->prepare("SELECT * FROM users WHERE id = :id");
$stmt->execute([':id' => $user_id]);
$user = $stmt->fetch(PDO::FETCH_ASSOC);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $current_password = $_POST['current_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];
    
    // Handle profile picture upload
    $profile_picture = $user['profile_picture'];
    if (!empty($_FILES['profile_picture']['name'])) {
        $upload_result = uploadProfilePicture($_FILES['profile_picture']);
        if ($upload_result['success']) {
            // Delete old profile picture if it's not the default
            if ($profile_picture && $profile_picture != 'default-profile.jpg') {
                @unlink("uploads/$profile_picture");
            }
            $profile_picture = $upload_result['filename'];
        } else {
            $_SESSION['error'] = $upload_result['message'];
            header('Location: profile.php');
            exit();
        }
    }
    
    
    // Validation
    $errors = [];
    
    if (empty($username)) {
        $errors[] = "Username is required.";
    }
    
    if (empty($email)) {
        $errors[] = "Email is required.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Invalid email format.";
    }
    
    // Check if username or email already exists (excluding current user)
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM users WHERE (username = :username OR email = :email) AND id != :id");
    $stmt->execute([':username' => $username, ':email' => $email, ':id' => $user_id]);
    $count = $stmt->fetchColumn();
    
    if ($count > 0) {
        $errors[] = "Username or email already exists.";
    }
    
    // Password change validation
    $password_changed = false;
    if (!empty($current_password) || !empty($new_password) || !empty($confirm_password)) {
        if (!password_verify($current_password, $user['password'])) {
            $errors[] = "Current password is incorrect.";
        }
        
        if (empty($new_password)) {
            $errors[] = "New password is required.";
        } elseif (strlen($new_password) < 6) {
            $errors[] = "New password must be at least 6 characters long.";
        }
        
        if ($new_password !== $confirm_password) {
            $errors[] = "New passwords do not match.";
        }
        
        if (empty($errors)) {
            $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
            $password_changed = true;
        }
    }
    
    if (empty($errors)) {
        if ($password_changed) {
            $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, password = :password, profile_picture = :profile_picture WHERE id = :id");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':password' => $hashed_password,
                ':profile_picture' => $profile_picture,
                ':id' => $user_id
            ]);
        } else {
            $stmt = $pdo->prepare("UPDATE users SET username = :username, email = :email, profile_picture = :profile_picture WHERE id = :id");
            $stmt->execute([
                ':username' => $username,
                ':email' => $email,
                ':profile_picture' => $profile_picture,
                ':id' => $user_id
            ]);
        }
        
        // Update session data
        $_SESSION['username'] = $username;
        $_SESSION['email'] = $email;
        $_SESSION['profile_picture'] = $profile_picture;
        
        $_SESSION['success'] = "Profile updated successfully.";
        header('Location: profile.php');
        exit();
    }
}
?>

<?php include 'includes/header.php'; ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h4>Edit Profile</h4>
            </div>
            <div class="card-body">
                <?php if (isset($_SESSION['success'])): ?>
                    <div class="alert alert-success"><?= $_SESSION['success'] ?></div>
                    <?php unset($_SESSION['success']); ?>
                <?php endif; ?>
                
                <?php if (isset($_SESSION['error'])): ?>
                    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
                    <?php unset($_SESSION['error']); ?>
                <?php endif; ?>
                
                <?php if (!empty($errors)): ?>
                    <div class="alert alert-danger">
                        <?php foreach ($errors as $error): ?>
                            <p><?= $error ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" enctype="multipart/form-data">
                    <div class="row mb-3">
                        <div class="col-md-4 text-center">
                            <img src="uploads/<?= $user['profile_picture'] ?? 'default-profile.jpg' ?>" class="img-thumbnail rounded-circle mb-2" style="width: 150px; height: 150px; object-fit: cover;">
                            <div class="mb-3">
                                <label for="profile_picture" class="form-label">Change Profile Picture</label>
                                <input class="form-control" type="file" id="profile_picture" name="profile_picture" accept="image/*">
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" value="<?= htmlspecialchars($user['username']) ?>" required>
                            </div>
                            <div class="mb-3">
                                <label for="email" class="form-label">Email</label>
                                <input type="email" class="form-control" id="email" name="email" value="<?= htmlspecialchars($user['email']) ?>" required>
                            </div>
                        </div>
                    </div>
                    
                    <h5 class="mt-4">Change Password</h5>
                    <div class="mb-3">
                        <label for="current_password" class="form-label">Current Password</label>
                        <input type="password" class="form-control" id="current_password" name="current_password">
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">New Password</label>
                        <input type="password" class="form-control" id="new_password" name="new_password">
                    </div>
                    <div class="mb-3">
                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" id="confirm_password" name="confirm_password">
                    </div>
                    
                    <button type="submit" class="btn btn-primary">Update Profile</button>
                    <a href="index.php" class="btn btn-secondary">Cancel</a>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include 'includes/footer.php'; ?>