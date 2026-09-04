<?php
session_start();

// Check if admin is already logged in, redirect to dashboard
if (isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true) {
    header('Location: dashboard.php');
    exit;
}

require '../includes/db.php';

$message = null;
$error = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';

    if ($email === '' || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = 'Please enter a valid email address.';
    } elseif (strlen($password) < 8) {
        $error = 'Password must be at least 8 characters.';
    } else {
        try {
            $conn = get_db_connection();
            $hash = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $conn->prepare('INSERT INTO admins (email, password_hash) VALUES (?, ?)');
            $stmt->bind_param('ss', $email, $hash);
            $stmt->execute();
            $message = "Admin account created for $email. You can now log in at login.php — "
                     . "delete this create_admin.php file now for security.";
        } catch (mysqli_sql_exception $e) {
            $error = str_contains($e->getMessage(), 'Duplicate')
                ? 'An admin with that email already exists.'
                : 'Something went wrong creating the account.';
        }
    }
}

// Use admin-specific header
$pageTitle = 'Create Admin Account';
require 'admin_header.php';
?>

<h2>Create Admin Account</h2>
<p class="subtitle">One-time setup - delete this file after use</p>

<?php if ($message): ?>
    <div class="alert alert-success"><?= htmlspecialchars($message) ?></div>
<?php endif; ?>

<?php if ($error): ?>
    <div class="alert alert-error"><?= htmlspecialchars($error) ?></div>
<?php endif; ?>

<?php if (!$message): ?>
    <form method="POST">
        <div class="form-group">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required>
        </div>
        <div class="form-group">
            <label for="password">Password (min 8 characters)</label>
            <input type="password" id="password" name="password" required minlength="8">
        </div>
        <button type="submit" class="btn-login">Create Account</button>
    </form>
<?php endif; ?>

<div class="form-note">
    <strong>SECURITY:</strong> Delete this file immediately after creating your account!
</div>

<?php require 'admin-footer.php'; ?>