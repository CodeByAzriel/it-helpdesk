<?php
include 'header.php';

if(isset($_SESSION['user_id'])){
    header("Location: dashboard.php");
    exit();
}

$error = '';
$success = '';

if(isset($_POST['register'])){
    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    // Basic validations
    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)){
        $error = "All fields are required.";
    } elseif(!filter_var($email, FILTER_VALIDATE_EMAIL)){
        $error = "Invalid email format.";
    } elseif($password !== $confirm_password){
        $error = "Passwords do not match.";
    } else {
        // Check if email already exists
        $stmt = $conn->prepare("SELECT id FROM users WHERE email=?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if($stmt->num_rows > 0){
            $error = "Email already registered.";
        } else {
            // Hash password & insert
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $insert = $conn->prepare("INSERT INTO users (name, email, password, role) VALUES (?, ?, ?, 'user')");
            $insert->bind_param("sss", $name, $email, $hashed_password);
            if($insert->execute()){
                $success = "Registration successful! You can now <a href='login.php'>login</a>.";
            } else {
                $error = "Registration failed. Try again later.";
            }
            $insert->close();
        }
        $stmt->close();
    }
}
?>

<div class="auth-card">
    <h2>Create Account</h2>

    <?php if($error): ?>
        <div class="auth-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if($success): ?>
        <div class="success-message"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Name</label>
            <input type="text" name="name" value="<?= isset($name) ? htmlspecialchars($name) : '' ?>" required>
        </div>

        <div class="input-group">
            <label>Email</label>
            <input type="email" name="email" value="<?= isset($email) ? htmlspecialchars($email) : '' ?>" required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password" name="password" required>
        </div>

        <div class="input-group">
            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>
        </div>

        <div class="form-buttons">
            <button type="submit" name="register" class="btn-primary full">Register</button>
        </div>

        <p style="text-align:center; margin-top:15px; color:#cbd5e1;">
            Already have an account? <a href="login.php" style="color:#38bdf8;">Login</a>
        </p>
    </form>
</div>

<?php include 'footer.php'; ?>