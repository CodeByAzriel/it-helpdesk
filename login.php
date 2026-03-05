<?php 
include 'header.php';

if(isset($_POST['login'])){
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $result = $conn->query("SELECT * FROM users WHERE email='$email'");
    $user = $result->fetch_assoc();

    if($user && password_verify($password, $user['password'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];

        // Redirect based on role
        if($user['role'] === 'admin'){
            header("Location: admin.php");
        } else {
            header("Location: dashboard.php");
        }
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<div class="auth-card">
    <h2>Welcome Back</h2>
    <p class="auth-subtitle" style="color:#cbd5e1; text-align:center; margin-bottom:20px;">
        Log in to manage your support tickets.
    </p>

    <?php if(isset($error)): ?>
        <div class="auth-error"><?= $error ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="form-buttons">
            <button type="submit" name="login" class="btn-primary full">Login</button>
        </div>
    </form>

    <p style="text-align:center; margin-top:15px; color:#cbd5e1;">
        Don't have an account? <a href="register.php" style="color:#38bdf8;">Create one</a>
    </p>
</div>

<?php include 'footer.php'; ?>