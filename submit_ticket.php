<?php 
include 'header.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

if(isset($_POST['submit_ticket'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];

    $stmt = $conn->prepare("INSERT INTO tickets (user_id, title, description, category, priority, status, created_at) VALUES (?, ?, ?, ?, ?, 'Open', NOW())");
    $stmt->bind_param("issss", $user_id, $title, $description, $category, $priority);

    if($stmt->execute()){
        $success = "Ticket submitted successfully!";
    } else {
        $error = "Failed to submit ticket. Try again.";
    }

    $stmt->close();
}
?>

<div class="submit-ticket-card">
    <h2>Submit a Support Ticket</h2>

    <?php if(isset($error)): ?>
        <div class="auth-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if(isset($success)): ?>
        <div class="success-message"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Title</label>
            <input type="text" name="title" placeholder="Brief title of the issue" required>
        </div>

        <div class="input-group">
            <label>Description</label>
            <textarea name="description" placeholder="Describe the problem in detail..." required></textarea>
        </div>

        <div class="input-group">
            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Hardware">Hardware</option>
                <option value="Software">Software</option>
                <option value="Network">Network</option>
                <option value="Other">Other</option>
            </select>
        </div>

        <div class="input-group">
            <label>Priority</label>
            <select name="priority" required>
                <option value="">-- Select Priority --</option>
                <option value="Low">Low</option>
                <option value="Medium">Medium</option>
                <option value="High">High</option>
            </select>
        </div>

        <div class="form-buttons">
            <button type="submit" name="submit_ticket" class="btn-primary full">Submit Ticket</button>
            <a href="dashboard.php" class="btn-outline full">Back to Dashboard</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>