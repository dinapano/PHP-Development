<?php
session_start(); // Start a session to remember the logged-in user aka store the data

// Ensure the user is logged in and is an employee
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'employee') {
    header("Location: login.php");
    exit;
}

include 'database.php'; // Include database connection

// Handle request submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $employee_id = $_SESSION['user_id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];
    $reason = $_POST['reason'];

    // Insert the request into the database
    if ($conn->query("INSERT INTO vacation_requests (employee_id, start_date, end_date, reason, status) 
            VALUES ($employee_id, '$start_date', '$end_date', '$reason', 'Pending')") === TRUE) {
        $message = "Request submitted successfully!";
    } else {
        $message = "Error: " . $conn->error;
    }
}

// Fetch all requests for the logged-in employee
$employee_id = $_SESSION['user_id'];
$result = $conn->query("SELECT * FROM vacation_requests WHERE employee_id = $employee_id");
?>

<!DOCTYPE html>
<html>
<head>
    <title>Vacation Request</title>
</head>
<body>
    <h1>Vacation Request</h1>
    
    <?php if (isset($message)) echo "$message"; ?>

    <form method="POST">
        <label for="start_date">Start Date:</label>
        <input type="date" id="start_date" name="start_date" required>
        <br>
        <label for="end_date">End Date:</label>
        <input type="date" id="end_date" name="end_date" required>
        <br>
        <label for="reason">Reason:</label>
        <textarea id="reason" name="reason" required></textarea>
        <br>
        <button type="submit">Submit Request</button>
    </form>

    <h2>Your Requests</h2>
    <?php if ($result->num_rows > 0): ?>
        <table border="1">
            <tr>
                <th>Start Date</th>
                <th>End Date</th>
                <th>Reason</th>
                <th>Status</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()): ?>
                <tr>
                    <td><?= $row['start_date'] ?></td>
                    <td><?= $row['end_date'] ?></td>
                    <td><?= $row['reason'] ?></td>
                    <td><?= $row['status'] ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    <?php else: ?>
        <p>You have not submitted any vacation requests yet.</p>
    <?php endif; ?>
</body>
</html>