<?php
include 'header.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

// Get ticket ID from URL
if(!isset($_GET['id'])){
    header("Location: dashboard.php");
    exit();
}

$ticket_id = $_GET['id'];

// Fetch ticket
$stmt = $conn->prepare("SELECT * FROM tickets WHERE id = ? AND user_id = ?");
$stmt->bind_param("ii", $ticket_id, $user_id);
$stmt->execute();
$result = $stmt->get_result();
$ticket = $result->fetch_assoc();

if(!$ticket){
    echo "<div class='auth-error'>Ticket not found or access denied.</div>";
    include 'footer.php';
    exit();
}

if(isset($_POST['update_ticket'])){
    $title = $_POST['title'];
    $description = $_POST['description'];
    $category = $_POST['category'];
    $priority = $_POST['priority'];

    $update = $conn->prepare("UPDATE tickets SET title=?, description=?, category=?, priority=? WHERE id=? AND user_id=?");
    $update->bind_param("ssssii", $title, $description, $category, $priority, $ticket_id, $user_id);

    if($update->execute()){
        $success = "Ticket updated successfully!";
        // Refresh data
        $ticket['title'] = $title;
        $ticket['description'] = $description;
        $ticket['category'] = $category;
        $ticket['priority'] = $priority;
    } else {
        $error = "Failed to update ticket.";
    }

    $update->close();
}

$stmt->close();
?>

<div class="submit-ticket-card">
    <h2>Update Ticket</h2>

    <?php if(isset($error)): ?>
        <div class="auth-error"><?= $error ?></div>
    <?php endif; ?>

    <?php if(isset($success)): ?>
        <div class="success-message"><?= $success ?></div>
    <?php endif; ?>

    <form method="POST">
        <div class="input-group">
            <label>Title</label>
            <input type="text" name="title" value="<?= htmlspecialchars($ticket['title']) ?>" required>
        </div>

        <div class="input-group">
            <label>Description</label>
            <textarea name="description" required><?= htmlspecialchars($ticket['description']) ?></textarea>
        </div>

        <div class="input-group">
            <label>Category</label>
            <select name="category" required>
                <option value="">-- Select Category --</option>
                <option value="Hardware" <?= $ticket['category'] == 'Hardware' ? 'selected' : '' ?>>Hardware</option>
                <option value="Software" <?= $ticket['category'] == 'Software' ? 'selected' : '' ?>>Software</option>
                <option value="Network" <?= $ticket['category'] == 'Network' ? 'selected' : '' ?>>Network</option>
                <option value="Other" <?= $ticket['category'] == 'Other' ? 'selected' : '' ?>>Other</option>
            </select>
        </div>

        <div class="input-group">
            <label>Priority</label>
            <select name="priority" required>
                <option value="">-- Select Priority --</option>
                <option value="Low" <?= $ticket['priority'] == 'Low' ? 'selected' : '' ?>>Low</option>
                <option value="Medium" <?= $ticket['priority'] == 'Medium' ? 'selected' : '' ?>>Medium</option>
                <option value="High" <?= $ticket['priority'] == 'High' ? 'selected' : '' ?>>High</option>
            </select>
        </div>

        <div class="form-buttons">
            <button type="submit" name="update_ticket" class="btn-primary full">Update Ticket</button>
            <a href="dashboard.php" class="btn-outline full">Back to Dashboard</a>
        </div>
    </form>
</div>

<?php include 'footer.php'; ?>