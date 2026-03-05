<?php 
include 'header.php';

if(!isset($_SESSION['user_id'])){
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];

$result = $conn->query("
    SELECT * FROM tickets 
    WHERE user_id='$user_id'
    ORDER BY created_at DESC
");
?>

<div class="dashboard-header">
    <div>
        <h1>My Support Tickets</h1>
        <p>Track, manage and monitor your IT requests.</p>
    </div>
    <a href="submit_ticket.php" class="btn-primary large-btn">+ Submit Ticket</a>
</div>

<?php if($result->num_rows > 0): ?>
    <div class="table-section">
        <table>
            <thead>
                <tr>
                    <th>Ticket ID</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Category</th>
                    <th>Priority</th>
                    <th>Status</th>
                    <th>Submitted On</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td><?= $row['id'] ?></td>
                        <td><?= htmlspecialchars($row['title']) ?></td>
                        <td><?= htmlspecialchars(substr($row['description'], 0, 60)) ?>...</td>
                        <td><?= $row['category'] ?></td>
                        <td><?= $row['priority'] ?></td>
                        <td>
                            <span class="status-badge <?= strtolower($row['status']) ?>">
                                <?= $row['status'] ?>
                            </span>
                        </td>
                        <td><?= date("d M Y", strtotime($row['created_at'])) ?></td>
                        <td>
                            <a href="update_ticket.php?id=<?= $row['id'] ?>" class="btn-outline small">Edit</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
<?php else: ?>

    <div class="empty-state">
        <h3>No tickets yet</h3>
        <p>You haven't submitted any support requests.</p>
        <a href="submit_ticket.php" class="btn-primary">Create First Ticket</a>
    </div>

<?php endif; ?>

<?php include 'footer.php'; ?>