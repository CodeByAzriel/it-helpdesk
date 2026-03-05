<?php
include 'header.php';

if(!isset($_SESSION['user_id']) || $_SESSION['role'] != 'admin'){
    header("Location: login.php");
    exit();
}

// Handle delete action
if(isset($_GET['delete'])){
    $delete_id = $_GET['delete'];
    $del_stmt = $conn->prepare("DELETE FROM tickets WHERE id=?");
    $del_stmt->bind_param("i", $delete_id);
    $del_stmt->execute();
    $del_stmt->close();
    $success = "Ticket deleted successfully.";
}

// Handle resolve action
if(isset($_GET['resolve'])){
    $resolve_id = $_GET['resolve'];
    $res_stmt = $conn->prepare("UPDATE tickets SET status='Resolved' WHERE id=?");
    $res_stmt->bind_param("i", $resolve_id);
    $res_stmt->execute();
    $res_stmt->close();
    $success = "Ticket marked as resolved.";
}

// Fetch all tickets with user info
$query = "SELECT tickets.*, users.name, users.email FROM tickets INNER JOIN users ON tickets.user_id = users.id ORDER BY created_at DESC";
$result = $conn->query($query);
?>

<div class="page-wrapper">
    <div class="dashboard-header">
        <h1>Admin Dashboard</h1>
        <p>Manage all tickets in the system</p>
    </div>

    <?php if(isset($success)): ?>
        <div class="success-message"><?= $success ?></div>
    <?php endif; ?>
<div class="dashboard-header">
    <a href="admin_submit_ticket.php" class="btn-primary large-btn">+ Submit Ticket</a>
</div>
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>User</th>
                    <th>Email</th>
                    <th>Title</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Created At</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($result->num_rows > 0): ?>
                    <?php while($ticket = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?= $ticket['id'] ?></td>
                            <td><?= htmlspecialchars($ticket['name']) ?></td>
                            <td><?= htmlspecialchars($ticket['email']) ?></td>
                            <td><?= htmlspecialchars($ticket['title']) ?></td>
                            <td><?= $ticket['category'] ?></td>
                            <td><?= $ticket['priority'] ?></td>
                            <td>
                                <span class="status-badge <?= strtolower($ticket['status']) ?>">
                                    <?= $ticket['status'] ?>
                                </span>
                            </td>
                            <td><?= $ticket['created_at'] ?></td>
                            <td>
                                <a href="admin.php?resolve=<?= $ticket['id'] ?>" class="btn-primary small">Resolve</a>
                                <a href="admin.php?delete=<?= $ticket['id'] ?>" class="btn-logout small" onclick="return confirm('Are you sure you want to delete this ticket?')">Delete</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="9" style="text-align:center;">No tickets found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php include 'footer.php'; ?>