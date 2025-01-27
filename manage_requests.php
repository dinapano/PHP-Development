<?php
session_start(); /// Start a session to remember the logged-in user aka store the data

// Check if the user is logged in and is a manager
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'manager') {
    header("Location: login.php");
    exit;
}


include 'database.php'; // Include database connection

// Handle approval or rejection
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $request_id = $_POST['request_id'];
    $action = $_POST['action'];

    // Update the request status
    $status = ($action === "Approve") ? "Approved" : "Rejected";
    $conn->query("UPDATE vacation_requests SET status = '$status' WHERE id = $request_id");
}

// Fetch pending requests
$result = $conn->query("SELECT * FROM vacation_requests WHERE status = 'Pending'");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Vacation Requests</title>
</head>
<body>
    <h1>Manage Vacation Requests</h1>

    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Employee ID</th>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Actions</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['employee_id'] ?></td>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?></td>
                    <td><?= $row['reason'] ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="Approve">Approve</button>
                        </form>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="request_id" value="<?= $row['id'] ?>">
                            <button type="submit" name="action" value="Reject">Reject</button>
                        </form>
                    </td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>No pending vacation requests.</p>
    <?php endif; ?>
</body>
</html>