<?php
include 'header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// Fetch all users
$users_result = $conn->query("SELECT id, name, email FROM users ORDER BY name ASC");

// Handle form submission
if(isset($_POST['submit'])){
    $user_id = $_POST['user_id'];
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];
    $status = $_POST['status'];

    $stmt = $conn->prepare("INSERT INTO tickets (user_id, title, description, category, priority, status, created_at) VALUES (?, ?, ?, ?, ?, ?, NOW())");
    $stmt->bind_param("isssss", $user_id, $title, $description, $category, $priority, $status);
    $stmt->execute();
    $stmt->close();

    $success = "Ticket submitted successfully.";
}
?>

<div class="page-wrapper">
    <div class="dashboard-header">
        <h1>Submit Ticket for a User</h1>
        <p>Fill out the form below</p>
    </div>

    <div class="auth-card">
        <?php if(isset($success)) echo "<div class='success-message'>$success</div>"; ?>

        <form method="POST">
            <div class="input-group">
                <label>Select User</label>
                <select name="user_id" required>
                    <option value="">-- Choose a user --</option>
                    <?php while($user = $users_result->fetch_assoc()): ?>
                        <option value="<?= $user['id'] ?>"><?= htmlspecialchars($user['name']) ?> (<?= htmlspecialchars($user['email']) ?>)</option>
                    <?php endwhile; ?>
                </select>
            </div>

            <div class="input-group">
                <label>Title</label>
                <input type="text" name="title" required>
            </div>

            <div class="input-group">
                <label>Description</label>
                <textarea name="description" required></textarea>
            </div>

            <div class="input-group">
                <label>Category</label>
                <select name="category" required>
                    <option value="Hardware">Hardware</option>
                    <option value="Software">Software</option>
                    <option value="Network">Network</option>
                </select>
            </div>

            <div class="input-group">
                <label>Priority</label>
                <select name="priority" required>
                    <option value="Low">Low</option>
                    <option value="Medium">Medium</option>
                    <option value="High">High</option>
                </select>
            </div>

            <div class="input-group">
                <label>Status</label>
                <select name="status" required>
                    <option value="Open">Open</option>
                    <option value="Resolved">Resolved</option>
                    <option value="Closed">Closed</option>
                </select>
            </div>

            <div class="form-buttons">
                <button type="submit" name="submit" class="btn-primary full-width">Submit Ticket</button>
                <a href="admin.php" class="btn-outline full-width">Cancel</a>
            </div>
        </form>
    </div>
</div>

<?php include 'footer.php'; ?>