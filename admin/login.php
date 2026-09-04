<?php
session_start();
$pageTitle = 'Admin Login — Abigail Beauty Bar';
$current = 'admin'; // Add this line
$baseUrl = '../';
require '../includes/header.php';

$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    require '../includes/db.php';
    
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if ($email && $password) {
        try {
            $conn = get_db_connection();
            $stmt = $conn->prepare('SELECT id, email, password_hash FROM admins WHERE email = ?');
            $stmt->bind_param('s', $email);
            $stmt->execute();
            $result = $stmt->get_result();
            $admin = $result->fetch_assoc();
            
            if ($admin && password_verify($password, $admin['password_hash'])) {
                $_SESSION['admin_logged_in'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_email'] = $admin['email'];
                   header('Location: dashboard.php');
                exit;
            } else {
                $error = 'Invalid email or password.';
            }
        } catch (Exception $e) {
            $error = 'Database error. Please try again.';
        }
            
     
    } else {
        $error = 'Please enter both email and password.';
    }
}

// Use admin-specific header (no navigation)
require 'admin_header.php';
?>

<h2> Admin Login</h2>
<p class="subtitle">Access the admin dashboard</p>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<form action="login.php" method="POST">
    <div class="form-group">
        <label for="email">Email</label>
        <input type="email" id="email" name="email" required autofocus>
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required>
    </div>
    <button type="submit" class="btn-login">Login</button>
</form>

<p class="form-note">No admin account yet? <a href="register.php">Register here</a>.</p>

<div class="form-note">
    Default login: <strong>admin@example.com</strong> / <strong>admin123</strong><br>
    <strong> IMPORTANT:</strong> Change this password immediately after first login!
</div>

<?php require 'admin_footer.php'; ?>